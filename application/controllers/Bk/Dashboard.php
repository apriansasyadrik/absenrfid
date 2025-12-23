<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * BK Dashboard Controller
 */
class Dashboard extends CI_Controller {

    public function __construct() {
        parent::__construct();
        check_role(['bk']);
        $this->load->model('Bk_model');
    }

    public function index() {
        $bulan = $this->input->get('bulan') ?: date('m');
        $tahun = $this->input->get('tahun') ?: date('Y');
        
        // Get dashboard stats
        $data['stats'] = $this->Bk_model->get_dashboard_stats($bulan, $tahun);
        
        // Get siswa bermasalah
        $data['siswa_bermasalah'] = $this->Bk_model->get_siswa_bermasalah($bulan, $tahun);
        
        // Limit to top 10 for dashboard
        if (count($data['siswa_bermasalah']) > 10) {
            $data['siswa_bermasalah'] = array_slice($data['siswa_bermasalah'], 0, 10);
        }
        
        $data['bulan'] = $bulan;
        $data['tahun'] = $tahun;
        
        $data['title'] = 'Dashboard BK';
        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar_guru');
        $this->load->view('templates/sidebar_bk');
        $this->load->view('bk/dashboard', $data);
        $this->load->view('templates/footer');
    }
}
