<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LaporanGuru extends CI_Controller {
    
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
        $this->load->model('Settings_model');
    }
    
    public function index() {
        $data['title'] = 'Laporan Absensi Guru';
        
        // Get filter parameters
        $bulan = $this->input->get('bulan') ?: date('m');
        $tahun = $this->input->get('tahun') ?: date('Y');
        
        $data['bulan'] = $bulan;
        $data['tahun'] = $tahun;
        
        // Get data
        $data['laporan'] = $this->Laporan_model->get_laporan_guru($bulan, $tahun);
        
        // Generate month and year options
        $data['bulan_list'] = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        
        $data['tahun_list'] = range(date('Y') - 5, date('Y') + 1);
        
        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar');
        $this->load->view('templates/sidebar_admin');
        $this->load->view('admin/laporan/guru', $data);
        $this->load->view('templates/footer');
    }
    
    public function export_excel() {
        $bulan = $this->input->get('bulan') ?: date('m');
        $tahun = $this->input->get('tahun') ?: date('Y');
        
        $laporan = $this->Laporan_model->get_laporan_guru($bulan, $tahun);
        
        // Load PhpSpreadsheet
        require_once APPPATH . '../vendor/autoload.php';
        
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Header
        $settings = $this->Settings_model->get_settings();
        $sheet->setCellValue('A1', 'LAPORAN ABSENSI GURU');
        $sheet->setCellValue('A2', $settings->nama_sekolah ?? 'Sekolah');
        $sheet->setCellValue('A3', 'Periode: ' . $bulan . '/' . $tahun);
        
        // Table header
        $sheet->setCellValue('A5', 'No');
        $sheet->setCellValue('B5', 'NIP');
        $sheet->setCellValue('C5', 'Nama Guru');
        $sheet->setCellValue('D5', 'Jabatan');
        $sheet->setCellValue('E5', 'Total Hadir');
        $sheet->setCellValue('F5', 'Total Terlambat');
        
        // Data
        $row = 6;
        $no = 1;
        foreach ($laporan as $item) {
            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, $item->nip);
            $sheet->setCellValue('C' . $row, $item->nama_lengkap);
            $sheet->setCellValue('D' . $row, $item->jabatan);
            $sheet->setCellValue('E' . $row, $item->total_hadir);
            $sheet->setCellValue('F' . $row, $item->total_terlambat);
            $row++;
        }
        
        // Auto width
        foreach (range('A', 'F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        
        // Download
        $filename = 'Laporan_Guru_' . $bulan . '_' . $tahun . '.xlsx';
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
    }
}
