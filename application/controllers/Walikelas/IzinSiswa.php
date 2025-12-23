<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Walikelas IzinSiswa Controller
 * Input sakit/izin untuk siswa di kelas yang diwalikan
 */
class IzinSiswa extends CI_Controller {

    public function __construct() {
        parent::__construct();
        check_role(['walikelas']);
        $this->load->model('IzinSiswa_model');
        $this->load->model('Kelas_model');
        $this->load->model('Siswa_model');
    }

    /**
     * List izin siswa
     */
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
            // Get izin for this class
            $filters = array(
                'kelas_id' => $kelas_wali['id']
            );
            
            // Filter by date if provided
            if ($this->input->get('tanggal')) {
                $filters['tanggal'] = $this->input->get('tanggal');
            }
            
            $data['list_izin'] = $this->IzinSiswa_model->get_all($filters);
        } else {
            $data['list_izin'] = array();
        }
        
        $data['title'] = 'Input Sakit/Izin Siswa';
        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar_guru');
        $this->load->view('templates/sidebar_walikelas');
        $this->load->view('walikelas/izin/index', $data);
        $this->load->view('templates/footer');
    }

    /**
     * Form tambah izin
     */
    public function tambah() {
        $guru_id = $this->session->userdata('guru_id');
        
        // Get kelas yang diwalikan
        $this->db->select('kelas.*');
        $this->db->from('kelas');
        $this->db->where('wali_kelas_id', $guru_id);
        $query = $this->db->get();
        $kelas_wali = $query->row_array();
        
        if (!$kelas_wali) {
            $this->session->set_flashdata('error', 'Anda tidak terdaftar sebagai wali kelas');
            redirect('walikelas/izin-siswa');
        }
        
        // Get siswa in this class
        $data['siswa_list'] = $this->IzinSiswa_model->get_siswa_by_kelas($kelas_wali['id']);
        $data['kelas_wali'] = $kelas_wali;
        
        $data['title'] = 'Tambah Izin Siswa';
        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar_guru');
        $this->load->view('templates/sidebar_walikelas');
        $this->load->view('walikelas/izin/form', $data);
        $this->load->view('templates/footer');
    }

    /**
     * Process tambah izin
     */
    public function simpan() {
        $guru_id = $this->session->userdata('guru_id');
        
        $this->form_validation->set_rules('siswa_id', 'Siswa', 'required');
        $this->form_validation->set_rules('tanggal', 'Tanggal', 'required');
        $this->form_validation->set_rules('jenis', 'Jenis Izin', 'required|in_list[Sakit,Izin]');
        $this->form_validation->set_rules('keterangan', 'Keterangan', 'required|trim');
        
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('walikelas/izin-siswa/tambah');
        }
        
        $siswa_id = $this->input->post('siswa_id', TRUE);
        $tanggal = $this->input->post('tanggal', TRUE);
        
        // Check if already exists
        if ($this->IzinSiswa_model->check_existing($siswa_id, $tanggal)) {
            $this->session->set_flashdata('error', 'Siswa sudah memiliki izin pada tanggal tersebut');
            redirect('walikelas/izin-siswa/tambah');
        }
        
        $data = array(
            'siswa_id' => $siswa_id,
            'tanggal' => $tanggal,
            'jenis' => $this->input->post('jenis', TRUE),
            'keterangan' => $this->input->post('keterangan', TRUE),
            'guru_id' => $guru_id
        );
        
        if ($this->IzinSiswa_model->insert($data)) {
            $this->session->set_flashdata('success', 'Izin siswa berhasil ditambahkan');
        } else {
            $this->session->set_flashdata('error', 'Gagal menambahkan izin siswa');
        }
        
        redirect('walikelas/izin-siswa');
    }

    /**
     * Form edit izin
     */
    public function edit($id) {
        $guru_id = $this->session->userdata('guru_id');
        
        // Get izin
        $izin = $this->IzinSiswa_model->get_by_id($id);
        
        if (!$izin) {
            show_404();
        }
        
        // Check if this guru is wali kelas
        if ($izin['guru_id'] != $guru_id) {
            show_error('Access Denied', 403);
        }
        
        // Get kelas yang diwalikan
        $this->db->select('kelas.*');
        $this->db->from('kelas');
        $this->db->where('wali_kelas_id', $guru_id);
        $query = $this->db->get();
        $kelas_wali = $query->row_array();
        
        // Get siswa in this class
        $data['siswa_list'] = $this->IzinSiswa_model->get_siswa_by_kelas($kelas_wali['id']);
        $data['izin'] = $izin;
        $data['kelas_wali'] = $kelas_wali;
        
        $data['title'] = 'Edit Izin Siswa';
        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar_guru');
        $this->load->view('templates/sidebar_walikelas');
        $this->load->view('walikelas/izin/form', $data);
        $this->load->view('templates/footer');
    }

    /**
     * Process update izin
     */
    public function update($id) {
        $guru_id = $this->session->userdata('guru_id');
        
        // Get izin
        $izin = $this->IzinSiswa_model->get_by_id($id);
        
        if (!$izin || $izin['guru_id'] != $guru_id) {
            show_error('Access Denied', 403);
        }
        
        $this->form_validation->set_rules('siswa_id', 'Siswa', 'required');
        $this->form_validation->set_rules('tanggal', 'Tanggal', 'required');
        $this->form_validation->set_rules('jenis', 'Jenis Izin', 'required|in_list[Sakit,Izin]');
        $this->form_validation->set_rules('keterangan', 'Keterangan', 'required|trim');
        
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('walikelas/izin-siswa/edit/' . $id);
        }
        
        $siswa_id = $this->input->post('siswa_id', TRUE);
        $tanggal = $this->input->post('tanggal', TRUE);
        
        // Check if already exists (exclude current)
        if ($this->IzinSiswa_model->check_existing($siswa_id, $tanggal, $id)) {
            $this->session->set_flashdata('error', 'Siswa sudah memiliki izin pada tanggal tersebut');
            redirect('walikelas/izin-siswa/edit/' . $id);
        }
        
        $data = array(
            'siswa_id' => $siswa_id,
            'tanggal' => $tanggal,
            'jenis' => $this->input->post('jenis', TRUE),
            'keterangan' => $this->input->post('keterangan', TRUE)
        );
        
        if ($this->IzinSiswa_model->update($id, $data)) {
            $this->session->set_flashdata('success', 'Izin siswa berhasil diupdate');
        } else {
            $this->session->set_flashdata('error', 'Gagal mengupdate izin siswa');
        }
        
        redirect('walikelas/izin-siswa');
    }

    /**
     * Delete izin
     */
    public function hapus($id) {
        $guru_id = $this->session->userdata('guru_id');
        
        // Get izin
        $izin = $this->IzinSiswa_model->get_by_id($id);
        
        if (!$izin || $izin['guru_id'] != $guru_id) {
            $this->session->set_flashdata('error', 'Data tidak ditemukan atau akses ditolak');
            redirect('walikelas/izin-siswa');
        }
        
        if ($this->IzinSiswa_model->delete($id)) {
            $this->session->set_flashdata('success', 'Izin siswa berhasil dihapus');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus izin siswa');
        }
        
        redirect('walikelas/izin-siswa');
    }
}
