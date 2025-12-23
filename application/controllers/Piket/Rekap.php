<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rekap extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
        $role = $this->session->userdata('role');
        if ($role != 'guru_piket') {
            redirect('guru/dashboard');
        }
        $this->load->model('IzinKBM_model');
        $this->load->model('Kelas_model');
        $this->load->library('PHPExcel');
    }

    public function index() {
        $bulan = $this->input->get('bulan') ?: date('n');
        $tahun = $this->input->get('tahun') ?: date('Y');
        $kelas_id = $this->input->get('kelas_id');
        
        if ($kelas_id) {
            $data['rekap_list'] = $this->IzinKBM_model->get_izin_by_kelas($kelas_id);
        } else {
            $data['rekap_list'] = $this->IzinKBM_model->get_rekap($bulan, $tahun);
        }
        
        $data['bulan'] = $bulan;
        $data['tahun'] = $tahun;
        $data['kelas_id'] = $kelas_id;
        $data['kelas_list'] = $this->Kelas_model->get_active_year();
        $data['title'] = 'Rekap Izin KBM';
        
        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar_piket');
        $this->load->view('templates/sidebar_piket');
        $this->load->view('piket/rekap/index', $data);
        $this->load->view('templates/footer');
    }

    public function export() {
        $bulan = $this->input->get('bulan') ?: date('n');
        $tahun = $this->input->get('tahun') ?: date('Y');
        $kelas_id = $this->input->get('kelas_id');
        
        if ($kelas_id) {
            $rekap_list = $this->IzinKBM_model->get_izin_by_kelas($kelas_id);
        } else {
            $rekap_list = $this->IzinKBM_model->get_rekap($bulan, $tahun);
        }
        
        // Create new PHPExcel object
        $excel = new PHPExcel();
        
        // Set document properties
        $excel->getProperties()->setCreator("Sistem Absensi RFID")
            ->setLastModifiedBy("Sistem Absensi RFID")
            ->setTitle("Rekap Izin KBM")
            ->setSubject("Rekap Izin KBM");
        
        // Add data
        $excel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'REKAP IZIN KBM')
            ->setCellValue('A2', 'Bulan: ' . $this->_get_bulan_name($bulan) . ' ' . $tahun);
        
        $excel->getActiveSheet()->mergeCells('A1:H1');
        $excel->getActiveSheet()->mergeCells('A2:H2');
        
        // Header
        $excel->setActiveSheetIndex(0)
            ->setCellValue('A4', 'No')
            ->setCellValue('B4', 'Tanggal')
            ->setCellValue('C4', 'NIS')
            ->setCellValue('D4', 'Nama Siswa')
            ->setCellValue('E4', 'Kelas')
            ->setCellValue('F4', 'Jenis')
            ->setCellValue('G4', 'Jam Izin')
            ->setCellValue('H4', 'Alasan');
        
        // Data
        $row = 5;
        $no = 1;
        foreach ($rekap_list as $izin) {
            $excel->setActiveSheetIndex(0)
                ->setCellValue('A' . $row, $no++)
                ->setCellValue('B' . $row, date('d/m/Y', strtotime($izin->tanggal)))
                ->setCellValue('C' . $row, $izin->nis)
                ->setCellValue('D' . $row, $izin->nama_lengkap)
                ->setCellValue('E' . $row, $izin->nama_kelas)
                ->setCellValue('F' . $row, $izin->jenis)
                ->setCellValue('G' . $row, date('H:i', strtotime($izin->jam_izin)))
                ->setCellValue('H' . $row, $izin->alasan);
            $row++;
        }
        
        // Style
        $excel->getActiveSheet()->getStyle('A1:H1')->getFont()->setBold(true)->setSize(14);
        $excel->getActiveSheet()->getStyle('A4:H4')->getFont()->setBold(true);
        $excel->getActiveSheet()->getStyle('A4:H4')->getFill()
            ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
            ->getStartColor()->setRGB('CCCCCC');
        
        // Auto width
        foreach (range('A', 'H') as $col) {
            $excel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
        }
        
        // Rename worksheet
        $excel->getActiveSheet()->setTitle('Rekap Izin KBM');
        
        // Set active sheet index to the first sheet
        $excel->setActiveSheetIndex(0);
        
        // Redirect output to a client's web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Rekap_Izin_KBM_' . $bulan . '_' . $tahun . '.xlsx"');
        header('Cache-Control: max-age=0');
        
        $writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        $writer->save('php://output');
    }

    private function _get_bulan_name($bulan) {
        $bulan_names = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        return $bulan_names[$bulan];
    }
}
