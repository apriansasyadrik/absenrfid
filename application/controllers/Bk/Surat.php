<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * BK Surat Controller
 * Manage surat panggilan orang tua
 */
class Surat extends CI_Controller {

    public function __construct() {
        parent::__construct();
        check_role(['bk']);
        $this->load->model('Bk_model');
        $this->load->model('Siswa_model');
        $this->load->model('Settings_model');
    }

    /**
     * List surat panggilan
     */
    public function index() {
        $filters = array();
        
        if ($this->input->get('bulan') && $this->input->get('tahun')) {
            $filters['bulan'] = $this->input->get('bulan');
            $filters['tahun'] = $this->input->get('tahun');
        }
        
        $data['list_surat'] = $this->Bk_model->get_surat_list($filters);
        $data['title'] = 'Surat Panggilan';
        
        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar_guru');
        $this->load->view('templates/sidebar_bk');
        $this->load->view('bk/surat/index', $data);
        $this->load->view('templates/footer');
    }

    /**
     * Form buat surat
     */
    public function tambah() {
        // Get all active students
        $this->db->select('siswa.*, kelas.nama_kelas, kelas.tingkat');
        $this->db->from('siswa');
        $this->db->join('kelas', 'kelas.id = siswa.kelas_id', 'left');
        $this->db->where('siswa.status', 'Aktif');
        $this->db->order_by('kelas.nama_kelas', 'ASC');
        $this->db->order_by('siswa.nama', 'ASC');
        $query = $this->db->get();
        $data['siswa_list'] = $query->result_array();
        
        // Generate nomor surat
        $data['nomor_surat'] = generate_nomor_surat('BK');
        
        $data['title'] = 'Buat Surat Panggilan';
        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar_guru');
        $this->load->view('templates/sidebar_bk');
        $this->load->view('bk/surat/form', $data);
        $this->load->view('templates/footer');
    }

    /**
     * Process simpan surat
     */
    public function simpan() {
        $bk_id = $this->session->userdata('guru_id');
        
        $this->form_validation->set_rules('nomor_surat', 'Nomor Surat', 'required|trim');
        $this->form_validation->set_rules('siswa_id', 'Siswa', 'required');
        $this->form_validation->set_rules('hari', 'Hari', 'required|trim');
        $this->form_validation->set_rules('tanggal', 'Tanggal', 'required');
        $this->form_validation->set_rules('waktu', 'Waktu', 'required');
        $this->form_validation->set_rules('perihal', 'Perihal', 'required|trim');
        
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('bk/surat/tambah');
        }
        
        $data = array(
            'nomor_surat' => $this->input->post('nomor_surat', TRUE),
            'siswa_id' => $this->input->post('siswa_id', TRUE),
            'hari' => $this->input->post('hari', TRUE),
            'tanggal' => $this->input->post('tanggal', TRUE),
            'waktu' => $this->input->post('waktu', TRUE),
            'perihal' => $this->input->post('perihal', TRUE),
            'bk_id' => $bk_id
        );
        
        if ($this->Bk_model->create_surat($data)) {
            $this->session->set_flashdata('success', 'Surat panggilan berhasil dibuat');
            redirect('bk/surat');
        } else {
            $this->session->set_flashdata('error', 'Gagal membuat surat panggilan');
            redirect('bk/surat/tambah');
        }
    }

    /**
     * Edit surat
     */
    public function edit($id) {
        $surat = $this->Bk_model->get_surat_by_id($id);
        
        if (!$surat) {
            show_404();
        }
        
        // Get all active students
        $this->db->select('siswa.*, kelas.nama_kelas, kelas.tingkat');
        $this->db->from('siswa');
        $this->db->join('kelas', 'kelas.id = siswa.kelas_id', 'left');
        $this->db->where('siswa.status', 'Aktif');
        $this->db->order_by('kelas.nama_kelas', 'ASC');
        $this->db->order_by('siswa.nama', 'ASC');
        $query = $this->db->get();
        $data['siswa_list'] = $query->result_array();
        
        $data['surat'] = $surat;
        $data['title'] = 'Edit Surat Panggilan';
        
        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar_guru');
        $this->load->view('templates/sidebar_bk');
        $this->load->view('bk/surat/form', $data);
        $this->load->view('templates/footer');
    }

    /**
     * Update surat
     */
    public function update($id) {
        $surat = $this->Bk_model->get_surat_by_id($id);
        
        if (!$surat) {
            show_404();
        }
        
        $this->form_validation->set_rules('nomor_surat', 'Nomor Surat', 'required|trim');
        $this->form_validation->set_rules('siswa_id', 'Siswa', 'required');
        $this->form_validation->set_rules('hari', 'Hari', 'required|trim');
        $this->form_validation->set_rules('tanggal', 'Tanggal', 'required');
        $this->form_validation->set_rules('waktu', 'Waktu', 'required');
        $this->form_validation->set_rules('perihal', 'Perihal', 'required|trim');
        
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('bk/surat/edit/' . $id);
        }
        
        $data = array(
            'nomor_surat' => $this->input->post('nomor_surat', TRUE),
            'siswa_id' => $this->input->post('siswa_id', TRUE),
            'hari' => $this->input->post('hari', TRUE),
            'tanggal' => $this->input->post('tanggal', TRUE),
            'waktu' => $this->input->post('waktu', TRUE),
            'perihal' => $this->input->post('perihal', TRUE)
        );
        
        if ($this->Bk_model->update_surat($id, $data)) {
            $this->session->set_flashdata('success', 'Surat panggilan berhasil diupdate');
        } else {
            $this->session->set_flashdata('error', 'Gagal mengupdate surat panggilan');
        }
        
        redirect('bk/surat');
    }

    /**
     * Delete surat
     */
    public function hapus($id) {
        if ($this->Bk_model->delete_surat($id)) {
            $this->session->set_flashdata('success', 'Surat panggilan berhasil dihapus');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus surat panggilan');
        }
        
        redirect('bk/surat');
    }

    /**
     * Cetak surat
     */
    public function cetak($id) {
        $data['surat'] = $this->Bk_model->get_surat_by_id($id);
        
        if (!$data['surat']) {
            show_404();
        }
        
        $data['settings'] = $this->Settings_model->get_settings();
        
        $this->load->view('bk/surat/cetak', $data);
    }
}
