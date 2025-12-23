<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Monitoring extends CI_Controller {

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
    }

    public function index() {
        $bulan = $this->input->get('bulan') ?: date('m');
        $tahun = $this->input->get('tahun') ?: date('Y');
        $kriteria = $this->input->get('kriteria') ?: 'semua';
        
        // Get problem students
        if ($kriteria == 'alpha') {
            $data['students'] = $this->Bk_model->get_students_by_alpha($bulan, $tahun, 3);
        } elseif ($kriteria == 'terlambat') {
            $data['students'] = $this->Bk_model->get_students_by_terlambat($bulan, $tahun, 5);
        } else {
            // Get all problem students
            $alpha_students = $this->Bk_model->get_students_by_alpha($bulan, $tahun, 3);
            $terlambat_students = $this->Bk_model->get_students_by_terlambat($bulan, $tahun, 5);
            
            // Merge and remove duplicates
            $all_students = array_merge($alpha_students, $terlambat_students);
            $unique_students = [];
            $seen_ids = [];
            
            foreach ($all_students as $student) {
                if (!in_array($student->siswa_id, $seen_ids)) {
                    $unique_students[] = $student;
                    $seen_ids[] = $student->siswa_id;
                }
            }
            
            $data['students'] = $unique_students;
        }
        
        $data['bulan'] = $bulan;
        $data['tahun'] = $tahun;
        $data['kriteria'] = $kriteria;
        $data['title'] = 'Monitoring Siswa Bermasalah';
        
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar_bk');
        $this->load->view('templates/topbar');
        $this->load->view('bk/monitoring/index', $data);
        $this->load->view('templates/footer');
    }

    public function add_note($siswa_id) {
        if ($this->input->method() == 'post') {
            $catatan = $this->input->post('catatan');
            
            $data = [
                'siswa_id' => $siswa_id,
                'catatan' => $catatan,
                'bk_id' => $this->session->userdata('user_id'),
                'tanggal' => date('Y-m-d')
            ];
            
            if ($this->db->insert('monitoring_bk', $data)) {
                echo json_encode(['success' => true, 'message' => 'Catatan berhasil ditambahkan']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Gagal menambahkan catatan']);
            }
        }
    }
}
