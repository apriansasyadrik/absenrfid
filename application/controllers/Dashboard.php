<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Dashboard Controller
 * Main dashboard for admin
 */
class Dashboard extends CI_Controller {

    public function __construct() {
        parent::__construct();
        check_role(['admin']);
        $this->load->model('Absensi_model');
        $this->load->model('Siswa_model');
        $this->load->model('Guru_model');
    }

    /**
     * Dashboard index
     */
    public function index() {
        $data['title'] = 'Dashboard Admin';
        
        // Statistics
        $data['total_siswa'] = $this->Siswa_model->count_all();
        $data['total_guru'] = $this->Guru_model->count_all();
        $data['absen_siswa_hari_ini'] = $this->Absensi_model->count_today('siswa');
        $data['absen_guru_hari_ini'] = $this->Absensi_model->count_today('guru');
        
        // Weekly attendance chart data
        $data['weekly_siswa'] = $this->Absensi_model->get_weekly_data('siswa');
        $data['weekly_guru'] = $this->Absensi_model->get_weekly_data('guru');
        
        // Recent activities
        $data['recent_activities'] = $this->Absensi_model->get_recent_activities(10);
        
        // Load views
        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('templates/sidebar_admin', $data);
        $this->load->view('admin/dashboard', $data);
        $this->load->view('templates/footer');
    }
}
