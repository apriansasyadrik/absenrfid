<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
        $role = $this->session->userdata('role');
        if (!in_array($role, ['guru', 'walikelas', 'guru_piket'])) {
            show_error('Access Denied', 403);
        }
        $this->load->model('Jadwal_model');
        $this->load->model('Jurnal_model');
        $this->load->model('Guru_model');
    }

    public function index() {
        $guru_id = $this->session->userdata('guru_id');
        
        // Get today's schedule
        $data['today_schedule'] = $this->Jadwal_model->get_by_teacher_date($guru_id, date('Y-m-d'));
        
        // Get stats
        $data['total_classes'] = $this->Jadwal_model->count_classes_by_teacher($guru_id);
        $data['schedules_today'] = count($data['today_schedule']);
        $data['journals_this_month'] = $this->Jurnal_model->count_by_teacher_month($guru_id, date('Y'), date('m'));
        
        // Get recent journals
        $data['recent_journals'] = $this->Jurnal_model->get_recent_by_teacher($guru_id, 5);
        
        // Get next class
        $data['next_class'] = $this->Jadwal_model->get_next_class($guru_id);
        
        $data['title'] = 'Dashboard Guru';
        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar_guru');
        $this->load->view('templates/sidebar_guru');
        $this->load->view('guru/dashboard', $data);
        $this->load->view('templates/footer');
    }
}
