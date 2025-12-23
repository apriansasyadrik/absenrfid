<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Piket Dashboard Controller
 */
class Dashboard extends CI_Controller {

    public function __construct() {
        parent::__construct();
        check_role(['guru_piket']);
        $this->load->model('IzinKBM_model');
        $this->load->model('Jadwal_model');
        $this->load->model('Siswa_model');
    }

    public function index() {
        $guru_id = $this->session->userdata('guru_id');
        
        // Get today's izin KBM
        $data['izin_hari_ini'] = $this->IzinKBM_model->get_all(array(
            'tanggal' => date('Y-m-d')
        ));
        
        // Get stats
        $data['total_izin_hari_ini'] = count($data['izin_hari_ini']);
        $data['izin_bulan_ini'] = $this->IzinKBM_model->count_by_guru(
            $guru_id, 
            date('m'), 
            date('Y')
        );
        
        // Get stats by type for today
        $stats_today = $this->IzinKBM_model->get_stats_by_type(
            date('Y-m-d'),
            date('Y-m-d')
        );
        
        $data['stats_today'] = array();
        foreach ($stats_today as $stat) {
            $data['stats_today'][$stat['jenis']] = $stat['jumlah'];
        }
        
        // Get today's schedule (if also a teacher)
        $data['today_schedule'] = $this->Jadwal_model->get_by_teacher_date($guru_id, date('Y-m-d'));
        $data['schedules_today'] = count($data['today_schedule']);
        
        $data['title'] = 'Dashboard Guru Piket';
        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar_guru');
        $this->load->view('templates/sidebar_piket');
        $this->load->view('piket/dashboard', $data);
        $this->load->view('templates/footer');
    }
}
