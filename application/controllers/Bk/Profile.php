<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
        $role = $this->session->userdata('role');
        if ($role != 'bk') {
            show_error('Access Denied', 403);
        }
        $this->load->model('Guru_model');
    }

    public function index() {
        $guru_id = $this->session->userdata('guru_id');
        $data['guru'] = $this->Guru_model->get_by_id($guru_id);
        $data['title'] = 'Profile';
        
        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar_bk');
        $this->load->view('templates/sidebar_bk');
        $this->load->view('bk/profile/index', $data);
        $this->load->view('templates/footer');
    }
}
