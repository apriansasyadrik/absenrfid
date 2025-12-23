<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class IzinSiswa extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Check if user is logged in
        if (!$this->session->userdata('user_id')) {
            redirect('auth/login');
        }
        
        // Check if user has walikelas role
        $role = $this->session->userdata('role');
        if ($role != 'walikelas' && $role != 'admin') {
            show_error('Access denied', 403);
        }
        
        $this->load->model('IzinSiswa_model');
        $this->load->model('Siswa_model');
        $this->load->model('Kelas_model');
    }

    public function index() {
        $guru_id = $this->session->userdata('guru_id');
        
        // Get walikelas info
        $data['kelas'] = $this->db->get_where('kelas', ['walikelas_id' => $guru_id])->row();
        
        if (!$data['kelas']) {
            $this->session->set_flashdata('error', 'Anda belum ditugaskan sebagai walikelas');
            redirect('guru/dashboard');
        }
        
        // Get izin list for this class
        $data['izin_list'] = $this->IzinSiswa_model->get_by_kelas($data['kelas']->id);
        $data['title'] = 'Input Izin Siswa';
        
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar_walikelas');
        $this->load->view('templates/topbar_guru');
        $this->load->view('walikelas/izin/index', $data);
        $this->load->view('templates/footer');
    }

    public function add() {
        $guru_id = $this->session->userdata('guru_id');
        $kelas = $this->db->get_where('kelas', ['walikelas_id' => $guru_id])->row();
        
        if (!$kelas) {
            $this->session->set_flashdata('error', 'Anda belum ditugaskan sebagai walikelas');
            redirect('guru/dashboard');
        }
        
        if ($this->input->method() == 'post') {
            $this->form_validation->set_rules('siswa_id', 'Siswa', 'required');
            $this->form_validation->set_rules('tanggal_mulai', 'Tanggal Mulai', 'required');
            $this->form_validation->set_rules('tanggal_selesai', 'Tanggal Selesai', 'required');
            $this->form_validation->set_rules('jenis', 'Jenis Izin', 'required|in_list[sakit,izin]');
            $this->form_validation->set_rules('keterangan', 'Keterangan', 'required');
            
            if ($this->form_validation->run() == TRUE) {
                $data = [
                    'siswa_id' => $this->input->post('siswa_id'),
                    'tanggal_mulai' => $this->input->post('tanggal_mulai'),
                    'tanggal_selesai' => $this->input->post('tanggal_selesai'),
                    'jenis' => $this->input->post('jenis'),
                    'keterangan' => $this->input->post('keterangan'),
                    'guru_id' => $guru_id
                ];
                
                if ($this->IzinSiswa_model->insert($data)) {
                    $this->session->set_flashdata('success', 'Izin siswa berhasil ditambahkan');
                } else {
                    $this->session->set_flashdata('error', 'Gagal menambahkan izin siswa');
                }
                
                redirect('walikelas/izinsiswa');
            }
        }
        
        // Get students in this class
        $data['siswa_list'] = $this->Siswa_model->get_by_kelas($kelas->id);
        $data['kelas'] = $kelas;
        $data['title'] = 'Tambah Izin Siswa';
        
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar_walikelas');
        $this->load->view('templates/topbar_guru');
        $this->load->view('walikelas/izin/form', $data);
        $this->load->view('templates/footer');
    }

    public function edit($id) {
        $guru_id = $this->session->userdata('guru_id');
        $izin = $this->IzinSiswa_model->get_by_id($id);
        
        if (!$izin || $izin->guru_id != $guru_id) {
            show_error('Data tidak ditemukan', 404);
        }
        
        if ($this->input->method() == 'post') {
            $this->form_validation->set_rules('tanggal_mulai', 'Tanggal Mulai', 'required');
            $this->form_validation->set_rules('tanggal_selesai', 'Tanggal Selesai', 'required');
            $this->form_validation->set_rules('jenis', 'Jenis Izin', 'required|in_list[sakit,izin]');
            $this->form_validation->set_rules('keterangan', 'Keterangan', 'required');
            
            if ($this->form_validation->run() == TRUE) {
                $data = [
                    'tanggal_mulai' => $this->input->post('tanggal_mulai'),
                    'tanggal_selesai' => $this->input->post('tanggal_selesai'),
                    'jenis' => $this->input->post('jenis'),
                    'keterangan' => $this->input->post('keterangan')
                ];
                
                if ($this->IzinSiswa_model->update($id, $data)) {
                    $this->session->set_flashdata('success', 'Izin siswa berhasil diupdate');
                } else {
                    $this->session->set_flashdata('error', 'Gagal mengupdate izin siswa');
                }
                
                redirect('walikelas/izinsiswa');
            }
        }
        
        $data['izin'] = $izin;
        $data['title'] = 'Edit Izin Siswa';
        
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar_walikelas');
        $this->load->view('templates/topbar_guru');
        $this->load->view('walikelas/izin/form', $data);
        $this->load->view('templates/footer');
    }

    public function delete($id) {
        $guru_id = $this->session->userdata('guru_id');
        $izin = $this->IzinSiswa_model->get_by_id($id);
        
        if (!$izin || $izin->guru_id != $guru_id) {
            echo json_encode(['success' => false, 'message' => 'Data tidak ditemukan']);
            return;
        }
        
        if ($this->IzinSiswa_model->delete($id)) {
            echo json_encode(['success' => true, 'message' => 'Izin siswa berhasil dihapus']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal menghapus izin siswa']);
        }
    }
}
