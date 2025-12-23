<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Check if user is logged in
        if (!$this->session->userdata('user_id')) {
            redirect('auth/login');
        }
        
        // Check if user has bk role
        $role = $this->session->userdata('role');
        if ($role != 'bk' && $role != 'admin') {
            show_error('Access denied', 403);
        }
        
        $this->load->model('Bk_model');
    }

    public function index() {
        // Get statistics
        $bulan = date('m');
        $tahun = date('Y');
        
        $data['total_alpha'] = $this->Bk_model->count_students_by_criteria('alpha', $bulan, $tahun, 3);
        $data['total_terlambat'] = $this->Bk_model->count_students_by_criteria('terlambat', $bulan, $tahun, 5);
        $data['total_monitoring'] = $this->Bk_model->count_all_monitoring();
        $data['total_surat'] = $this->Bk_model->count_all_surat();
        
        // Get recent problem students
        $data['recent_students'] = $this->Bk_model->get_recent_problem_students(10);
        
        $data['title'] = 'Dashboard BK';
        
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar_bk');
        $this->load->view('templates/topbar');
        $this->load->view('bk/dashboard', $data);
        $this->load->view('templates/footer');
    }
}
