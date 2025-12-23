<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
        $role = $this->session->userdata('role');
        if (!in_array($role, ['guru', 'walikelas', 'guru_piket'])) {
            show_error('Access Denied', 403);
        }
        $this->load->model('Jurnal_model');
        $this->load->model('Jadwal_model');
    }

    public function index() {
        $guru_id = $this->session->userdata('guru_id');
        $bulan = $this->input->get('bulan') ?: date('m');
        $tahun = $this->input->get('tahun') ?: date('Y');
        
        $data['stats'] = [
            'total_jurnal' => $this->Jurnal_model->count_by_teacher_month($guru_id, $tahun, $bulan),
            'total_jam' => $this->Jurnal_model->count_hours_by_teacher_month($guru_id, $tahun, $bulan),
            'total_kelas' => $this->Jadwal_model->count_classes_by_teacher($guru_id)
        ];
        
        $data['journals'] = $this->Jurnal_model->get_by_teacher_month($guru_id, $tahun, $bulan);
        $data['title'] = 'Laporan Kinerja';
        
        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar_guru');
        $this->load->view('templates/sidebar_guru');
        $this->load->view('guru/laporan/index', $data);
        $this->load->view('templates/footer');
    }

    public function export() {
        // Excel export logic here
        $guru_id = $this->session->userdata('guru_id');
        $bulan = $this->input->get('bulan') ?: date('m');
        $tahun = $this->input->get('tahun') ?: date('Y');
        
        $journals = $this->Jurnal_model->get_by_teacher_month($guru_id, $tahun, $bulan);
        
        // Use PhpSpreadsheet for export
        // Implementation similar to other export functions
    }
}
