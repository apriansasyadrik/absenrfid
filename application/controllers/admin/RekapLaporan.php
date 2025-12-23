<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class RekapLaporan extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        // Check if user is logged in
        if (!$this->session->userdata('logged_in')) {
            redirect('login');
        }
        
        // Check if user is admin
        if ($this->session->userdata('role') != 'admin') {
            redirect('dashboard');
        }
        
        $this->load->model('Laporan_model');
        $this->load->model('Semester_model');
        $this->load->model('Settings_model');
    }
    
    public function index() {
        $data['title'] = 'Rekap Laporan';
        
        // Get filter parameters
        $semester_id = $this->input->get('semester_id');
        $type = $this->input->get('type') ?: 'siswa';
        
        $data['semester_id'] = $semester_id;
        $data['type'] = $type;
        
        // Get data
        if ($semester_id) {
            $data['rekap'] = $this->Laporan_model->get_rekap_per_semester($semester_id, $type);
        } else {
            $data['rekap'] = [];
        }
        
        $data['semester_list'] = $this->Semester_model->get_all();
        
        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar');
        $this->load->view('templates/sidebar_admin');
        $this->load->view('admin/laporan/rekap', $data);
        $this->load->view('templates/footer');
    }
    
    public function export_excel() {
        $semester_id = $this->input->get('semester_id');
        $type = $this->input->get('type') ?: 'siswa';
        
        $rekap = $this->Laporan_model->get_rekap_per_semester($semester_id, $type);
        $semester = $this->db->get_where('semester', ['id' => $semester_id])->row();
        
        // Load PhpSpreadsheet
        require_once APPPATH . '../vendor/autoload.php';
        
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Header
        $settings = $this->Settings_model->get_settings();
        $title = $type == 'siswa' ? 'REKAP ABSENSI SISWA' : 'REKAP ABSENSI GURU';
        $sheet->setCellValue('A1', $title);
        $sheet->setCellValue('A2', $settings->nama_sekolah ?? 'Sekolah');
        $sheet->setCellValue('A3', 'Semester: ' . ($semester->jenis ?? '') . ' ' . ($semester->tahun_ajaran_id ?? ''));
        
        // Table header
        $sheet->setCellValue('A5', 'No');
        if ($type == 'siswa') {
            $sheet->setCellValue('B5', 'NIS');
            $sheet->setCellValue('C5', 'Nama Siswa');
            $sheet->setCellValue('D5', 'Kelas');
        } else {
            $sheet->setCellValue('B5', 'NIP');
            $sheet->setCellValue('C5', 'Nama Guru');
            $sheet->setCellValue('D5', 'Jabatan');
        }
        $sheet->setCellValue('E5', 'Total Hadir');
        $sheet->setCellValue('F5', 'Total Terlambat');
        
        // Data
        $row = 6;
        $no = 1;
        foreach ($rekap as $item) {
            $sheet->setCellValue('A' . $row, $no++);
            if ($type == 'siswa') {
                $sheet->setCellValue('B' . $row, $item->nis);
                $sheet->setCellValue('C' . $row, $item->nama_lengkap);
                $sheet->setCellValue('D' . $row, $item->tingkat . ' ' . $item->nama_kelas);
            } else {
                $sheet->setCellValue('B' . $row, $item->nip);
                $sheet->setCellValue('C' . $row, $item->nama_lengkap);
                $sheet->setCellValue('D' . $row, $item->jabatan);
            }
            $sheet->setCellValue('E' . $row, $item->total_hadir);
            $sheet->setCellValue('F' . $row, $item->total_terlambat);
            $row++;
        }
        
        // Auto width
        foreach (range('A', 'F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        
        // Download
        $filename = 'Rekap_' . ($type == 'siswa' ? 'Siswa' : 'Guru') . '_Semester_' . $semester_id . '.xlsx';
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
    }
}
