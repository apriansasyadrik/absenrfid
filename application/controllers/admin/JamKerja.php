<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class JamKerja extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        // Check if user is logged in
        if (!$this->session->userdata('logged_in')) {
            redirect('login');
        }
        
        // Check if user is admin
        if ($this->session->userdata('role') != 'admin') {
            redirect('dashboard');
        }
        
        $this->load->model('Settings_model');
    }
    
    public function index() {
        $data['title'] = 'Pengaturan Jam Kerja';
        
        // Get jam kerja settings from database
        $jam_kerja = $this->db->get('jam_kerja')->result();
        $data['jam_kerja'] = [];
        foreach ($jam_kerja as $jk) {
            $data['jam_kerja'][$jk->hari] = $jk;
        }
        
        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar');
        $this->load->view('templates/sidebar_admin');
        $this->load->view('admin/settings/jam_kerja', $data);
        $this->load->view('templates/footer');
    }
    
    public function update() {
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
        
        foreach ($days as $day) {
            $is_kerja = $this->input->post('is_kerja_' . $day);
            $jam_masuk = $this->input->post('jam_masuk_' . $day);
            $jam_pulang = $this->input->post('jam_pulang_' . $day);
            $toleransi = $this->input->post('toleransi_' . $day);
            
            $data = [
                'hari' => $day,
                'is_kerja' => $is_kerja ? 1 : 0,
                'jam_masuk' => $jam_masuk,
                'jam_pulang' => $jam_pulang,
                'toleransi_menit' => $toleransi ? $toleransi : 15
            ];
            
            // Check if exists
            $exists = $this->db->get_where('jam_kerja', ['hari' => $day])->row();
            
            if ($exists) {
                $this->db->where('hari', $day);
                $this->db->update('jam_kerja', $data);
            } else {
                $this->db->insert('jam_kerja', $data);
            }
        }
        
        $this->session->set_flashdata('success', 'Jam kerja berhasil diperbarui');
        redirect('admin/jam-kerja');
    }
}
