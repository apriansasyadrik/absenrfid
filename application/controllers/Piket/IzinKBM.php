<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class IzinKBM extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Check if user is logged in
        if (!$this->session->userdata('user_id')) {
            redirect('auth/login');
        }
        
        // Check if user has guru_piket role
        $role = $this->session->userdata('role');
        if ($role != 'guru_piket' && $role != 'admin') {
            show_error('Access denied', 403);
        }
        
        $this->load->model('Siswa_model');
        $this->load->model('Kelas_model');
    }

    public function index() {
        // Get today's KBM permissions
        $today = date('Y-m-d');
        $data['izin_list'] = $this->db->query("
            SELECT i.*, s.nis, s.nama_lengkap, k.tingkat, k.nama_kelas
            FROM izin_siswa i
            JOIN siswa s ON s.id = i.siswa_id
            JOIN kelas k ON k.id = s.kelas_id
            WHERE i.tanggal_mulai = ?
            ORDER BY i.created_at DESC
        ", [$today])->result();
        
        $data['title'] = 'Izin KBM';
        
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar_piket');
        $this->load->view('templates/topbar_guru');
        $this->load->view('piket/izin_kbm/index', $data);
        $this->load->view('templates/footer');
    }

    public function add() {
        if ($this->input->method() == 'post') {
            $this->form_validation->set_rules('siswa_id', 'Siswa', 'required');
            $this->form_validation->set_rules('tanggal', 'Tanggal', 'required');
            $this->form_validation->set_rules('jenis', 'Jenis Izin', 'required|in_list[masuk_terlambat,keluar_awal,tidak_masuk]');
            $this->form_validation->set_rules('jam_izin', 'Jam Izin', 'required');
            $this->form_validation->set_rules('alasan', 'Alasan', 'required');
            
            if ($this->form_validation->run() == TRUE) {
                $data = [
                    'siswa_id' => $this->input->post('siswa_id'),
                    'tanggal_mulai' => $this->input->post('tanggal'),
                    'tanggal_selesai' => $this->input->post('tanggal'),
                    'jenis' => 'izin',
                    'keterangan' => $this->input->post('jenis') . ' - ' . $this->input->post('alasan'),
                    'jam_izin' => $this->input->post('jam_izin'),
                    'jam_kembali' => $this->input->post('jam_kembali'),
                    'guru_id' => $this->session->userdata('guru_id')
                ];
                
                if ($this->db->insert('izin_siswa', $data)) {
                    $this->session->set_flashdata('success', 'Izin KBM berhasil ditambahkan');
                } else {
                    $this->session->set_flashdata('error', 'Gagal menambahkan izin KBM');
                }
                
                redirect('piket/izinkbm');
            }
        }
        
        // Get all students
        $data['siswa_list'] = $this->Siswa_model->get_all();
        $data['kelas_list'] = $this->Kelas_model->get_all();
        $data['title'] = 'Tambah Izin KBM';
        
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar_piket');
        $this->load->view('templates/topbar_guru');
        $this->load->view('piket/izin_kbm/form', $data);
        $this->load->view('templates/footer');
    }

    public function delete($id) {
        if ($this->db->delete('izin_siswa', ['id' => $id])) {
            echo json_encode(['success' => true, 'message' => 'Izin KBM berhasil dihapus']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal menghapus izin KBM']);
        }
    }

    // AJAX endpoint to get students by class
    public function get_students_by_class($kelas_id) {
        $students = $this->Siswa_model->get_by_kelas($kelas_id);
        echo json_encode($students);
    }
}
