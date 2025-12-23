<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TahunAjaran extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        // Check if user is logged in
        if (!$this->session->userdata('user_id')) {
            redirect('auth/login');
        }
        
        // Check if user is admin
        if ($this->session->userdata('role') != 'admin') {
            show_error('Unauthorized Access', 403);
        }
        
        $this->load->model('TahunAjaran_model');
    }

    public function index()
    {
        $data['title'] = 'Data Tahun Ajaran';
        $data['tahun_ajaran'] = $this->TahunAjaran_model->get_all();
        
        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar');
        $this->load->view('templates/sidebar_admin');
        $this->load->view('admin/master/tahun_ajaran', $data);
        $this->load->view('templates/footer');
    }

    public function add()
    {
        $this->form_validation->set_rules('tahun_mulai', 'Tahun Mulai', 'required|numeric|min_length[4]|max_length[4]');
        $this->form_validation->set_rules('tahun_selesai', 'Tahun Selesai', 'required|numeric|min_length[4]|max_length[4]');
        $this->form_validation->set_rules('is_active', 'Status Aktif', 'required|in_list[0,1]');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(['status' => 'error', 'message' => validation_errors()]);
            return;
        }

        $tahun_mulai = $this->input->post('tahun_mulai');
        $tahun_selesai = $this->input->post('tahun_selesai');
        
        if ($tahun_selesai <= $tahun_mulai) {
            echo json_encode(['status' => 'error', 'message' => 'Tahun selesai harus lebih besar dari tahun mulai']);
            return;
        }

        $is_active = $this->input->post('is_active');
        
        // If setting as active, deactivate others
        if ($is_active == 1) {
            $this->TahunAjaran_model->deactivate_all();
        }

        $data = [
            'tahun_mulai' => $tahun_mulai,
            'tahun_selesai' => $tahun_selesai,
            'nama_tahun' => $tahun_mulai . '/' . $tahun_selesai,
            'is_active' => $is_active
        ];

        if ($this->TahunAjaran_model->insert($data)) {
            echo json_encode(['status' => 'success', 'message' => 'Tahun ajaran berhasil ditambahkan']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal menambahkan tahun ajaran']);
        }
    }

    public function get($id)
    {
        $data = $this->TahunAjaran_model->get_by_id($id);
        if ($data) {
            echo json_encode(['status' => 'success', 'data' => $data]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Data tidak ditemukan']);
        }
    }

    public function edit($id)
    {
        $this->form_validation->set_rules('tahun_mulai', 'Tahun Mulai', 'required|numeric|min_length[4]|max_length[4]');
        $this->form_validation->set_rules('tahun_selesai', 'Tahun Selesai', 'required|numeric|min_length[4]|max_length[4]');
        $this->form_validation->set_rules('is_active', 'Status Aktif', 'required|in_list[0,1]');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(['status' => 'error', 'message' => validation_errors()]);
            return;
        }

        $tahun_mulai = $this->input->post('tahun_mulai');
        $tahun_selesai = $this->input->post('tahun_selesai');
        
        if ($tahun_selesai <= $tahun_mulai) {
            echo json_encode(['status' => 'error', 'message' => 'Tahun selesai harus lebih besar dari tahun mulai']);
            return;
        }

        $is_active = $this->input->post('is_active');
        
        // If setting as active, deactivate others
        if ($is_active == 1) {
            $this->TahunAjaran_model->deactivate_all();
        }

        $data = [
            'tahun_mulai' => $tahun_mulai,
            'tahun_selesai' => $tahun_selesai,
            'nama_tahun' => $tahun_mulai . '/' . $tahun_selesai,
            'is_active' => $is_active
        ];

        if ($this->TahunAjaran_model->update($id, $data)) {
            echo json_encode(['status' => 'success', 'message' => 'Tahun ajaran berhasil diupdate']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal mengupdate tahun ajaran']);
        }
    }

    public function delete($id)
    {
        // Check if tahun ajaran is being used
        $this->db->where('tahun_ajaran_id', $id);
        $used_count = $this->db->count_all_results('kelas');
        
        if ($used_count > 0) {
            echo json_encode(['status' => 'error', 'message' => 'Tahun ajaran sedang digunakan pada ' . $used_count . ' kelas']);
            return;
        }

        if ($this->TahunAjaran_model->delete($id)) {
            echo json_encode(['status' => 'success', 'message' => 'Tahun ajaran berhasil dihapus']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus tahun ajaran']);
        }
    }

    public function set_active($id)
    {
        $this->TahunAjaran_model->deactivate_all();
        
        $data = ['is_active' => 1];
        if ($this->TahunAjaran_model->update($id, $data)) {
            $this->session->set_flashdata('success', 'Tahun ajaran berhasil diaktifkan');
        } else {
            $this->session->set_flashdata('error', 'Gagal mengaktifkan tahun ajaran');
        }
        
        redirect('admin/tahunajaran');
    }
}
