<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Surat extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
        $role = $this->session->userdata('role');
        if ($role != 'bk') {
            show_error('Access Denied', 403);
        }
        $this->load->model('Bk_model');
        $this->load->model('Siswa_model');
        $this->load->model('Settings_model');
    }

    public function index() {
        $data['surat_list'] = $this->Bk_model->get_surat_list();
        $data['title'] = 'Surat Panggilan Orang Tua';
        
        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar_bk');
        $this->load->view('templates/sidebar_bk');
        $this->load->view('bk/surat/index', $data);
        $this->load->view('templates/footer');
    }

    public function tambah() {
        if ($this->input->method() == 'post') {
            $this->form_validation->set_rules('siswa_id', 'Siswa', 'required');
            $this->form_validation->set_rules('nomor_surat', 'Nomor Surat', 'required');
            $this->form_validation->set_rules('tanggal_surat', 'Tanggal Surat', 'required');
            $this->form_validation->set_rules('waktu_panggilan', 'Waktu Panggilan', 'required');
            $this->form_validation->set_rules('perihal', 'Perihal', 'required');
            
            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata('error', validation_errors());
            } else {
                $data = [
                    'siswa_id' => $this->input->post('siswa_id'),
                    'nomor_surat' => $this->input->post('nomor_surat'),
                    'tanggal_surat' => $this->input->post('tanggal_surat'),
                    'waktu_panggilan' => $this->input->post('waktu_panggilan'),
                    'perihal' => $this->input->post('perihal'),
                    'keterangan' => $this->input->post('keterangan'),
                    'status' => 'Belum Dipanggil',
                    'created_by' => $this->session->userdata('user_id')
                ];
                
                if ($this->Bk_model->create_surat($data)) {
                    $this->session->set_flashdata('success', 'Surat panggilan berhasil dibuat');
                    redirect('bk/surat');
                } else {
                    $this->session->set_flashdata('error', 'Gagal membuat surat panggilan');
                }
            }
        }
        
        $data['siswa_list'] = $this->db->get('siswa')->result();
        $data['title'] = 'Tambah Surat Panggilan';
        
        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar_bk');
        $this->load->view('templates/sidebar_bk');
        $this->load->view('bk/surat/form', $data);
        $this->load->view('templates/footer');
    }

    public function edit($id) {
        $surat = $this->Bk_model->get_surat_by_id($id);
        
        if (!$surat) {
            $this->session->set_flashdata('error', 'Surat tidak ditemukan');
            redirect('bk/surat');
        }
        
        if ($this->input->method() == 'post') {
            $this->form_validation->set_rules('siswa_id', 'Siswa', 'required');
            $this->form_validation->set_rules('nomor_surat', 'Nomor Surat', 'required');
            $this->form_validation->set_rules('tanggal_surat', 'Tanggal Surat', 'required');
            $this->form_validation->set_rules('waktu_panggilan', 'Waktu Panggilan', 'required');
            $this->form_validation->set_rules('perihal', 'Perihal', 'required');
            
            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata('error', validation_errors());
            } else {
                $data = [
                    'siswa_id' => $this->input->post('siswa_id'),
                    'nomor_surat' => $this->input->post('nomor_surat'),
                    'tanggal_surat' => $this->input->post('tanggal_surat'),
                    'waktu_panggilan' => $this->input->post('waktu_panggilan'),
                    'perihal' => $this->input->post('perihal'),
                    'keterangan' => $this->input->post('keterangan'),
                    'status' => $this->input->post('status')
                ];
                
                if ($this->Bk_model->update_surat($id, $data)) {
                    $this->session->set_flashdata('success', 'Surat panggilan berhasil diupdate');
                    redirect('bk/surat');
                } else {
                    $this->session->set_flashdata('error', 'Gagal mengupdate surat panggilan');
                }
            }
        }
        
        $data['surat'] = $surat;
        $data['siswa_list'] = $this->db->get('siswa')->result();
        $data['title'] = 'Edit Surat Panggilan';
        
        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar_bk');
        $this->load->view('templates/sidebar_bk');
        $this->load->view('bk/surat/form', $data);
        $this->load->view('templates/footer');
    }

    public function cetak($id) {
        $surat = $this->Bk_model->get_surat_by_id($id);
        
        if (!$surat) {
            show_404();
        }
        
        $data['surat'] = $surat;
        $data['settings'] = $this->Settings_model->get();
        
        $this->load->view('bk/surat/cetak', $data);
    }

    public function hapus($id) {
        if ($this->Bk_model->delete_surat($id)) {
            $this->session->set_flashdata('success', 'Surat berhasil dihapus');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus surat');
        }
        
        redirect('bk/surat');
    }
}
