<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Monitoring extends CI_Controller {

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
        $this->load->model('Kelas_model');
    }

    public function index() {
        $bulan = $this->input->get('bulan') ?: date('n');
        $tahun = $this->input->get('tahun') ?: date('Y');
        $kelas_id = $this->input->get('kelas_id');
        
        $siswa_bermasalah = $this->Bk_model->get_siswa_bermasalah($bulan, $tahun);
        
        // Filter by class if specified
        if ($kelas_id) {
            $siswa_bermasalah = array_filter($siswa_bermasalah, function($siswa) use ($kelas_id) {
                return $siswa->kelas_id == $kelas_id;
            });
        }
        
        $data['siswa_bermasalah'] = $siswa_bermasalah;
        $data['bulan'] = $bulan;
        $data['tahun'] = $tahun;
        $data['kelas_id'] = $kelas_id;
        $data['kelas_list'] = $this->Kelas_model->get_active_year();
        $data['title'] = 'Monitoring Siswa Bermasalah';
        
        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar_bk');
        $this->load->view('templates/sidebar_bk');
        $this->load->view('bk/monitoring/index', $data);
        $this->load->view('templates/footer');
    }

    public function detail($siswa_id) {
        $bulan = $this->input->get('bulan') ?: date('n');
        $tahun = $this->input->get('tahun') ?: date('Y');
        
        // Get siswa data
        $this->load->model('Siswa_model');
        $data['siswa'] = $this->Siswa_model->get_by_id($siswa_id);
        
        if (!$data['siswa']) {
            show_404();
        }
        
        // Get pelanggaran detail
        $data['pelanggaran'] = $this->Bk_model->get_detail_pelanggaran($siswa_id, $bulan, $tahun);
        $data['riwayat_alpha'] = $this->Bk_model->get_riwayat_alpha($siswa_id, $bulan, $tahun);
        $data['riwayat_terlambat'] = $this->Bk_model->get_riwayat_terlambat($siswa_id, $bulan, $tahun);
        $data['catatan'] = $this->Bk_model->get_catatan($siswa_id, $bulan, $tahun);
        
        $data['bulan'] = $bulan;
        $data['tahun'] = $tahun;
        $data['title'] = 'Detail Siswa Bermasalah';
        
        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar_bk');
        $this->load->view('templates/sidebar_bk');
        $this->load->view('bk/monitoring/detail', $data);
        $this->load->view('templates/footer');
    }

    public function catatan($siswa_id) {
        if ($this->input->method() == 'post') {
            $catatan = $this->input->post('catatan');
            $user_id = $this->session->userdata('user_id');
            
            if ($this->Bk_model->add_catatan($siswa_id, $catatan, $user_id)) {
                $this->session->set_flashdata('success', 'Catatan berhasil disimpan');
            } else {
                $this->session->set_flashdata('error', 'Gagal menyimpan catatan');
            }
            
            redirect('bk/monitoring/detail/' . $siswa_id);
        }
    }
}
