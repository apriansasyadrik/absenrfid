<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jadwal extends CI_Controller {

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
        $this->load->model('Semester_model');
    }

    public function index() {
        $guru_id = $this->session->userdata('guru_id');
        $semester_id = $this->input->get('semester_id');
        
        $data['schedules'] = $this->Jadwal_model->get_by_teacher($guru_id, $semester_id);
        $data['semesters'] = $this->Semester_model->get_all();
        $data['title'] = 'Jadwal Mengajar';
        
        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar_guru');
        $this->load->view('templates/sidebar_guru');
        $this->load->view('guru/jadwal/index', $data);
        $this->load->view('templates/footer');
    }
}
