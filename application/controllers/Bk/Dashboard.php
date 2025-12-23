<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
        $role = $this->session->userdata('role');
        if ($role != 'bk') {
            show_error('Access Denied', 403);
        }
        $this->load->model('Bk_model');
    }

    public function index() {
        $bulan = date('n');
        $tahun = date('Y');
        
        // Get siswa bermasalah
        $data['siswa_bermasalah'] = $this->Bk_model->get_siswa_bermasalah($bulan, $tahun);
        
        // Get statistics
        $data['statistics'] = $this->Bk_model->get_statistics($bulan, $tahun);
        $data['total_bermasalah'] = count($data['siswa_bermasalah']);
        
        $data['bulan'] = $bulan;
        $data['tahun'] = $tahun;
        $data['title'] = 'Dashboard BK';
        
        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar_bk');
        $this->load->view('templates/sidebar_bk');
        $this->load->view('bk/dashboard', $data);
        $this->load->view('templates/footer');
    }
}
