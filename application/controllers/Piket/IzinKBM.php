<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Piket IzinKBM Controller
 * Input izin siswa masuk/keluar saat KBM
 */
class IzinKBM extends CI_Controller {

    public function __construct() {
        parent::__construct();
        check_role(['guru_piket']);
        $this->load->model('IzinKBM_model');
        $this->load->model('Kelas_model');
        $this->load->model('Siswa_model');
    }

    /**
     * List izin KBM
     */
    public function index() {
        // Get izin for today by default
        $tanggal = $this->input->get('tanggal') ?: date('Y-m-d');
        
        $filters = array(
            'tanggal' => $tanggal
        );
        
        // Filter by kelas if provided
        if ($this->input->get('kelas_id')) {
            $filters['kelas_id'] = $this->input->get('kelas_id');
        }
        
        $data['list_izin'] = $this->IzinKBM_model->get_all($filters);
        $data['tanggal'] = $tanggal;
        $data['kelas_list'] = $this->Kelas_model->get_all();
        
        $data['title'] = 'Izin KBM Siswa';
        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar_guru');
        $this->load->view('templates/sidebar_piket');
        $this->load->view('piket/izin/index', $data);
        $this->load->view('templates/footer');
    }

    /**
     * Form tambah izin KBM
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
        
        $data['title'] = 'Tambah Izin KBM';
        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar_guru');
        $this->load->view('templates/sidebar_piket');
        $this->load->view('piket/izin/form', $data);
        $this->load->view('templates/footer');
    }

    /**
     * Process tambah izin KBM
     */
    public function simpan() {
        $guru_id = $this->session->userdata('guru_id');
        
        $this->form_validation->set_rules('siswa_id', 'Siswa', 'required');
        $this->form_validation->set_rules('tanggal', 'Tanggal', 'required');
        $this->form_validation->set_rules('jenis', 'Jenis Izin', 'required|in_list[masuk_terlambat,keluar_awal,tidak_masuk]');
        $this->form_validation->set_rules('jam_izin', 'Jam Izin', 'required');
        $this->form_validation->set_rules('alasan', 'Alasan', 'required|trim');
        
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('piket/izin-kbm/tambah');
        }
        
        $data = array(
            'siswa_id' => $this->input->post('siswa_id', TRUE),
            'tanggal' => $this->input->post('tanggal', TRUE),
            'jenis' => $this->input->post('jenis', TRUE),
            'jam_izin' => $this->input->post('jam_izin', TRUE),
            'jam_kembali' => $this->input->post('jam_kembali', TRUE) ?: NULL,
            'alasan' => $this->input->post('alasan', TRUE),
            'guru_piket_id' => $guru_id
        );
        
        if ($this->IzinKBM_model->insert($data)) {
            $this->session->set_flashdata('success', 'Izin KBM berhasil ditambahkan');
        } else {
            $this->session->set_flashdata('error', 'Gagal menambahkan izin KBM');
        }
        
        redirect('piket/izin-kbm');
    }

    /**
     * Form edit izin KBM
     */
    public function edit($id) {
        $guru_id = $this->session->userdata('guru_id');
        
        // Get izin
        $izin = $this->IzinKBM_model->get_by_id($id);
        
        if (!$izin) {
            show_404();
        }
        
        // Check if this guru is the one who created it
        if ($izin['guru_piket_id'] != $guru_id) {
            show_error('Access Denied', 403);
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
        
        $data['izin'] = $izin;
        $data['title'] = 'Edit Izin KBM';
        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar_guru');
        $this->load->view('templates/sidebar_piket');
        $this->load->view('piket/izin/form', $data);
        $this->load->view('templates/footer');
    }

    /**
     * Process update izin KBM
     */
    public function update($id) {
        $guru_id = $this->session->userdata('guru_id');
        
        // Get izin
        $izin = $this->IzinKBM_model->get_by_id($id);
        
        if (!$izin || $izin['guru_piket_id'] != $guru_id) {
            show_error('Access Denied', 403);
        }
        
        $this->form_validation->set_rules('siswa_id', 'Siswa', 'required');
        $this->form_validation->set_rules('tanggal', 'Tanggal', 'required');
        $this->form_validation->set_rules('jenis', 'Jenis Izin', 'required|in_list[masuk_terlambat,keluar_awal,tidak_masuk]');
        $this->form_validation->set_rules('jam_izin', 'Jam Izin', 'required');
        $this->form_validation->set_rules('alasan', 'Alasan', 'required|trim');
        
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('piket/izin-kbm/edit/' . $id);
        }
        
        $data = array(
            'siswa_id' => $this->input->post('siswa_id', TRUE),
            'tanggal' => $this->input->post('tanggal', TRUE),
            'jenis' => $this->input->post('jenis', TRUE),
            'jam_izin' => $this->input->post('jam_izin', TRUE),
            'jam_kembali' => $this->input->post('jam_kembali', TRUE) ?: NULL,
            'alasan' => $this->input->post('alasan', TRUE)
        );
        
        if ($this->IzinKBM_model->update($id, $data)) {
            $this->session->set_flashdata('success', 'Izin KBM berhasil diupdate');
        } else {
            $this->session->set_flashdata('error', 'Gagal mengupdate izin KBM');
        }
        
        redirect('piket/izin-kbm');
    }

    /**
     * Delete izin KBM
     */
    public function hapus($id) {
        $guru_id = $this->session->userdata('guru_id');
        
        // Get izin
        $izin = $this->IzinKBM_model->get_by_id($id);
        
        if (!$izin || $izin['guru_piket_id'] != $guru_id) {
            $this->session->set_flashdata('error', 'Data tidak ditemukan atau akses ditolak');
            redirect('piket/izin-kbm');
        }
        
        if ($this->IzinKBM_model->delete($id)) {
            $this->session->set_flashdata('success', 'Izin KBM berhasil dihapus');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus izin KBM');
        }
        
        redirect('piket/izin-kbm');
    }
}
