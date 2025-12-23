<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Semester extends CI_Controller {

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
        
        $this->load->model('Semester_model');
        $this->load->model('TahunAjaran_model');
    }

    public function index()
    {
        $data['title'] = 'Data Semester';
        $data['semester'] = $this->Semester_model->get_all_with_tahun();
        $data['tahun_ajaran'] = $this->TahunAjaran_model->get_all();
        
        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar');
        $this->load->view('templates/sidebar_admin');
        $this->load->view('admin/master/semester', $data);
        $this->load->view('templates/footer');
    }

    public function add()
    {
        $this->form_validation->set_rules('tahun_ajaran_id', 'Tahun Ajaran', 'required|numeric');
        $this->form_validation->set_rules('nama_semester', 'Nama Semester', 'required|in_list[Ganjil,Genap]');
        $this->form_validation->set_rules('tanggal_mulai', 'Tanggal Mulai', 'required');
        $this->form_validation->set_rules('tanggal_selesai', 'Tanggal Selesai', 'required');
        $this->form_validation->set_rules('is_active', 'Status Aktif', 'required|in_list[0,1]');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(['status' => 'error', 'message' => validation_errors()]);
            return;
        }

        $tanggal_mulai = $this->input->post('tanggal_mulai');
        $tanggal_selesai = $this->input->post('tanggal_selesai');
        
        if (strtotime($tanggal_selesai) <= strtotime($tanggal_mulai)) {
            echo json_encode(['status' => 'error', 'message' => 'Tanggal selesai harus lebih besar dari tanggal mulai']);
            return;
        }

        $is_active = $this->input->post('is_active');
        
        // If setting as active, deactivate others
        if ($is_active == 1) {
            $this->Semester_model->deactivate_all();
        }

        $data = [
            'tahun_ajaran_id' => $this->input->post('tahun_ajaran_id'),
            'nama_semester' => $this->input->post('nama_semester'),
            'tanggal_mulai' => $tanggal_mulai,
            'tanggal_selesai' => $tanggal_selesai,
            'is_active' => $is_active
        ];

        if ($this->Semester_model->insert($data)) {
            echo json_encode(['status' => 'success', 'message' => 'Semester berhasil ditambahkan']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal menambahkan semester']);
        }
    }

    public function get($id)
    {
        $data = $this->Semester_model->get_by_id($id);
        if ($data) {
            echo json_encode(['status' => 'success', 'data' => $data]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Data tidak ditemukan']);
        }
    }

    public function edit($id)
    {
        $this->form_validation->set_rules('tahun_ajaran_id', 'Tahun Ajaran', 'required|numeric');
        $this->form_validation->set_rules('nama_semester', 'Nama Semester', 'required|in_list[Ganjil,Genap]');
        $this->form_validation->set_rules('tanggal_mulai', 'Tanggal Mulai', 'required');
        $this->form_validation->set_rules('tanggal_selesai', 'Tanggal Selesai', 'required');
        $this->form_validation->set_rules('is_active', 'Status Aktif', 'required|in_list[0,1]');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(['status' => 'error', 'message' => validation_errors()]);
            return;
        }

        $tanggal_mulai = $this->input->post('tanggal_mulai');
        $tanggal_selesai = $this->input->post('tanggal_selesai');
        
        if (strtotime($tanggal_selesai) <= strtotime($tanggal_mulai)) {
            echo json_encode(['status' => 'error', 'message' => 'Tanggal selesai harus lebih besar dari tanggal mulai']);
            return;
        }

        $is_active = $this->input->post('is_active');
        
        // If setting as active, deactivate others
        if ($is_active == 1) {
            $this->Semester_model->deactivate_all();
        }

        $data = [
            'tahun_ajaran_id' => $this->input->post('tahun_ajaran_id'),
            'nama_semester' => $this->input->post('nama_semester'),
            'tanggal_mulai' => $tanggal_mulai,
            'tanggal_selesai' => $tanggal_selesai,
            'is_active' => $is_active
        ];

        if ($this->Semester_model->update($id, $data)) {
            echo json_encode(['status' => 'success', 'message' => 'Semester berhasil diupdate']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal mengupdate semester']);
        }
    }

    public function delete($id)
    {
        // Check if semester is being used
        $this->db->where('semester_id', $id);
        $used_count = $this->db->count_all_results('jadwal_pelajaran');
        
        if ($used_count > 0) {
            echo json_encode(['status' => 'error', 'message' => 'Semester sedang digunakan pada jadwal pelajaran']);
            return;
        }

        if ($this->Semester_model->delete($id)) {
            echo json_encode(['status' => 'success', 'message' => 'Semester berhasil dihapus']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus semester']);
        }
    }

    public function set_active($id)
    {
        $this->Semester_model->deactivate_all();
        
        $data = ['is_active' => 1];
        if ($this->Semester_model->update($id, $data)) {
            $this->session->set_flashdata('success', 'Semester berhasil diaktifkan');
        } else {
            $this->session->set_flashdata('error', 'Gagal mengaktifkan semester');
        }
        
        redirect('admin/semester');
    }
}
