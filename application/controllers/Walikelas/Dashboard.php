<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Walikelas Dashboard Controller
 */
class Dashboard extends CI_Controller {

    public function __construct() {
        parent::__construct();
        check_role(['walikelas']);
        $this->load->model('Kelas_model');
        $this->load->model('Siswa_model');
        $this->load->model('IzinSiswa_model');
        $this->load->model('Jadwal_model');
        $this->load->model('Jurnal_model');
    }

    public function index() {
        $guru_id = $this->session->userdata('guru_id');
        
        // Get kelas yang diwalikan
        $this->db->select('kelas.*');
        $this->db->from('kelas');
        $this->db->where('wali_kelas_id', $guru_id);
        $query = $this->db->get();
        $kelas_wali = $query->row_array();
        
        $data['kelas_wali'] = $kelas_wali;
        
        if ($kelas_wali) {
            // Get stats for kelas
            $data['total_siswa'] = $this->db->where('kelas_id', $kelas_wali['id'])
                                            ->where('status', 'Aktif')
                                            ->count_all_results('siswa');
            
            // Get izin bulan ini
            $data['izin_bulan_ini'] = $this->IzinSiswa_model->count_by_guru(
                $guru_id, 
                date('m'), 
                date('Y')
            );
            
            // Get recent izin
            $data['recent_izin'] = $this->IzinSiswa_model->get_all(array(
                'guru_id' => $guru_id,
                'kelas_id' => $kelas_wali['id']
            ));
            
            if (count($data['recent_izin']) > 5) {
                $data['recent_izin'] = array_slice($data['recent_izin'], 0, 5);
            }
        } else {
            $data['total_siswa'] = 0;
            $data['izin_bulan_ini'] = 0;
            $data['recent_izin'] = array();
        }
        
        // Get today's schedule (as guru)
        $data['today_schedule'] = $this->Jadwal_model->get_by_teacher_date($guru_id, date('Y-m-d'));
        $data['schedules_today'] = count($data['today_schedule']);
        
        $data['title'] = 'Dashboard Wali Kelas';
        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar_guru');
        $this->load->view('templates/sidebar_walikelas');
        $this->load->view('walikelas/dashboard', $data);
        $this->load->view('templates/footer');
    }
}
