<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
        $role = $this->session->userdata('role');
        if ($role != 'guru_piket') {
            redirect('guru/dashboard');
        }
        $this->load->model('IzinKBM_model');
        $this->load->model('Kelas_model');
        $this->load->model('Jadwal_model');
    }

    public function index() {
        $guru_id = $this->session->userdata('guru_id');
        
        // Get today's izin KBM
        $data['izin_today'] = $this->IzinKBM_model->get_izin_hari_ini();
        
        // Get statistics for today
        $data['total_izin_today'] = count($data['izin_today']);
        
        // Count by jenis
        $masuk_terlambat = 0;
        $keluar_awal = 0;
        $tidak_masuk = 0;
        
        foreach ($data['izin_today'] as $izin) {
            switch ($izin->jenis) {
                case 'Masuk Terlambat':
                    $masuk_terlambat++;
                    break;
                case 'Keluar Awal':
                    $keluar_awal++;
                    break;
                case 'Tidak Masuk':
                    $tidak_masuk++;
                    break;
            }
        }
        
        $data['masuk_terlambat'] = $masuk_terlambat;
        $data['keluar_awal'] = $keluar_awal;
        $data['tidak_masuk'] = $tidak_masuk;
        
        // Get monthly statistics
        $data['total_izin_bulan_ini'] = count($this->IzinKBM_model->get_rekap(date('n'), date('Y')));
        
        // Get all classes for quick access
        $data['kelas_list'] = $this->Kelas_model->get_active_year();
        
        $data['title'] = 'Dashboard Guru Piket';
        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar_piket');
        $this->load->view('templates/sidebar_piket');
        $this->load->view('piket/dashboard', $data);
        $this->load->view('templates/footer');
    }
}
