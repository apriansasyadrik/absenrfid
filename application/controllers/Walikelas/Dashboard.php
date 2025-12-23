<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
        $role = $this->session->userdata('role');
        if ($role != 'walikelas') {
            redirect('guru/dashboard');
        }
        $this->load->model('Kelas_model');
        $this->load->model('Siswa_model');
        $this->load->model('Izin_model');
        $this->load->model('Jadwal_model');
    }

    public function index() {
        $guru_id = $this->session->userdata('guru_id');
        
        // Get walikelas class
        $data['kelas_walikelas'] = $this->Kelas_model->get_by_walikelas($guru_id);
        
        if ($data['kelas_walikelas']) {
            $kelas_id = $data['kelas_walikelas']->id;
            
            // Get statistics
            $data['total_siswa'] = $this->Siswa_model->count_by_kelas($kelas_id);
            $data['total_izin_bulan_ini'] = $this->db
                ->from('izin_siswa')
                ->join('siswa', 'siswa.id = izin_siswa.siswa_id')
                ->where('siswa.kelas_id', $kelas_id)
                ->where('MONTH(izin_siswa.tanggal)', date('n'))
                ->where('YEAR(izin_siswa.tanggal)', date('Y'))
                ->count_all_results();
            
            // Get recent izin
            $data['recent_izin'] = $this->Izin_model->get_izin_by_kelas($kelas_id);
            if (count($data['recent_izin']) > 5) {
                $data['recent_izin'] = array_slice($data['recent_izin'], 0, 5);
            }
            
            // Get today's schedule
            $data['today_schedule'] = $this->Jadwal_model->get_by_class_date($kelas_id, date('Y-m-d'));
            
            // Get students list
            $data['students'] = $this->Siswa_model->get_by_kelas($kelas_id);
        } else {
            $data['total_siswa'] = 0;
            $data['total_izin_bulan_ini'] = 0;
            $data['recent_izin'] = [];
            $data['today_schedule'] = [];
            $data['students'] = [];
        }
        
        $data['title'] = 'Dashboard Walikelas';
        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar_walikelas');
        $this->load->view('templates/sidebar_walikelas');
        $this->load->view('walikelas/dashboard', $data);
        $this->load->view('templates/footer');
    }
}
