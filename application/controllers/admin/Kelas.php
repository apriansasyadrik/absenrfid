<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Kelas Controller (Admin)
 * Manage class data
 */
class Kelas extends CI_Controller {

    public function __construct() {
        parent::__construct();
        check_role(['admin']);
        $this->load->model('Kelas_model');
        $this->load->model('Guru_model');
        $this->load->model('TahunAjaran_model');
    }

    /**
     * Class list page
     */
    public function index() {
        $data['title'] = 'Data Kelas';
        $data['kelas'] = $this->Kelas_model->get_all();
        $data['guru_list'] = $this->Guru_model->get_active_dropdown();
        $data['tahun_ajaran_list'] = $this->TahunAjaran_model->get_dropdown();
        
        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('templates/sidebar_admin', $data);
        $this->load->view('admin/master/kelas', $data);
        $this->load->view('templates/footer');
    }

    /**
     * Add new class
     */
    public function add() {
        $this->form_validation->set_rules('nama_kelas', 'Nama Kelas', 'required|trim');
        $this->form_validation->set_rules('tingkat', 'Tingkat', 'required');
        $this->form_validation->set_rules('tahun_ajaran_id', 'Tahun Ajaran', 'required');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode([
                'status' => false,
                'message' => validation_errors()
            ]);
            return;
        }

        $data = array(
            'nama_kelas' => $this->input->post('nama_kelas', TRUE),
            'tingkat' => $this->input->post('tingkat', TRUE),
            'jurusan' => $this->input->post('jurusan', TRUE),
            'walikelas_id' => $this->input->post('walikelas_id', TRUE),
            'tahun_ajaran_id' => $this->input->post('tahun_ajaran_id', TRUE)
        );

        if ($this->Kelas_model->insert($data)) {
            echo json_encode([
                'status' => true,
                'message' => 'Kelas berhasil ditambahkan'
            ]);
        } else {
            echo json_encode([
                'status' => false,
                'message' => 'Gagal menambahkan kelas'
            ]);
        }
    }

    /**
     * Get class by ID
     */
    public function get($id) {
        $kelas = $this->Kelas_model->get_by_id($id);
        echo json_encode([
            'status' => true,
            'data' => $kelas
        ]);
    }

    /**
     * Update class
     */
    public function edit($id) {
        $this->form_validation->set_rules('nama_kelas', 'Nama Kelas', 'required|trim');
        $this->form_validation->set_rules('tingkat', 'Tingkat', 'required');
        $this->form_validation->set_rules('tahun_ajaran_id', 'Tahun Ajaran', 'required');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode([
                'status' => false,
                'message' => validation_errors()
            ]);
            return;
        }

        $data = array(
            'nama_kelas' => $this->input->post('nama_kelas', TRUE),
            'tingkat' => $this->input->post('tingkat', TRUE),
            'jurusan' => $this->input->post('jurusan', TRUE),
            'walikelas_id' => $this->input->post('walikelas_id', TRUE),
            'tahun_ajaran_id' => $this->input->post('tahun_ajaran_id', TRUE)
        );

        if ($this->Kelas_model->update($id, $data)) {
            echo json_encode([
                'status' => true,
                'message' => 'Kelas berhasil diupdate'
            ]);
        } else {
            echo json_encode([
                'status' => false,
                'message' => 'Gagal mengupdate kelas'
            ]);
        }
    }

    /**
     * Delete class
     */
    public function delete($id) {
        if ($this->Kelas_model->delete($id)) {
            $this->session->set_flashdata('success', 'Kelas berhasil dihapus');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus kelas');
        }

        redirect('admin/kelas');
    }
}
