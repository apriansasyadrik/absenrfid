<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Surat extends CI_Controller {

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
        $this->load->model('Siswa_model');
        $this->load->model('Settings_model');
    }

    public function form($siswa_id = null) {
        if ($this->input->method() == 'post') {
            $this->form_validation->set_rules('siswa_id', 'Siswa', 'required');
            $this->form_validation->set_rules('nomor_surat', 'Nomor Surat', 'required');
            $this->form_validation->set_rules('hari', 'Hari', 'required');
            $this->form_validation->set_rules('tanggal', 'Tanggal', 'required');
            $this->form_validation->set_rules('waktu', 'Waktu', 'required');
            $this->form_validation->set_rules('keperluan', 'Keperluan', 'required');
            
            if ($this->form_validation->run() == TRUE) {
                $data = [
                    'siswa_id' => $this->input->post('siswa_id'),
                    'nomor_surat' => $this->input->post('nomor_surat'),
                    'hari' => $this->input->post('hari'),
                    'tanggal' => $this->input->post('tanggal'),
                    'waktu' => $this->input->post('waktu'),
                    'keperluan' => $this->input->post('keperluan'),
                    'bk_id' => $this->session->userdata('user_id'),
                    'created_at' => date('Y-m-d H:i:s')
                ];
                
                if ($this->db->insert('surat_bk', $data)) {
                    $surat_id = $this->db->insert_id();
                    $this->session->set_flashdata('success', 'Surat berhasil dibuat');
                    redirect('bk/surat/print_surat/' . $surat_id);
                } else {
                    $this->session->set_flashdata('error', 'Gagal membuat surat');
                }
            }
        }
        
        // Get student if provided
        $data['siswa'] = $siswa_id ? $this->Siswa_model->get_by_id($siswa_id) : null;
        $data['siswa_list'] = $this->Siswa_model->get_all();
        $data['title'] = 'Cetak Surat Panggilan';
        
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar_bk');
        $this->load->view('templates/topbar');
        $this->load->view('bk/surat/form', $data);
        $this->load->view('templates/footer');
    }

    public function print_surat($id) {
        $surat = $this->Bk_model->get_surat_by_id($id);
        
        if (!$surat) {
            show_error('Surat tidak ditemukan', 404);
        }
        
        $data['surat'] = $surat;
        $data['settings'] = $this->Settings_model->get_settings();
        
        $this->load->view('bk/surat/print', $data);
    }
}
