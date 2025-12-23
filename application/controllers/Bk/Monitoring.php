<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * BK Monitoring Controller
 * Monitor siswa dengan masalah kehadiran
 */
class Monitoring extends CI_Controller {

    public function __construct() {
        parent::__construct();
        check_role(['bk']);
        $this->load->model('Bk_model');
    }

    /**
     * List monitoring siswa
     */
    public function index() {
        $bulan = $this->input->get('bulan') ?: date('m');
        $tahun = $this->input->get('tahun') ?: date('Y');
        
        $data['list_monitoring'] = $this->Bk_model->get_monitoring_by_bulan($bulan, $tahun);
        $data['bulan'] = $bulan;
        $data['tahun'] = $tahun;
        
        $data['title'] = 'Monitoring Siswa';
        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar_guru');
        $this->load->view('templates/sidebar_bk');
        $this->load->view('bk/monitoring/index', $data);
        $this->load->view('templates/footer');
    }

    /**
     * Detail monitoring siswa
     */
    public function detail($siswa_id) {
        $data['siswa'] = $this->Bk_model->get_siswa_detail($siswa_id);
        
        if (!$data['siswa']) {
            show_404();
        }
        
        $data['title'] = 'Detail Monitoring Siswa';
        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar_guru');
        $this->load->view('templates/sidebar_bk');
        $this->load->view('bk/monitoring/detail', $data);
        $this->load->view('templates/footer');
    }

    /**
     * Add catatan BK
     */
    public function tambah_catatan($siswa_id) {
        $bulan = $this->input->post('bulan', TRUE);
        $tahun = $this->input->post('tahun', TRUE);
        $catatan = $this->input->post('catatan', TRUE);
        
        if ($this->Bk_model->add_catatan_bk($siswa_id, $bulan, $tahun, $catatan)) {
            $this->session->set_flashdata('success', 'Catatan BK berhasil ditambahkan');
        } else {
            $this->session->set_flashdata('error', 'Gagal menambahkan catatan BK');
        }
        
        redirect('bk/monitoring/detail/' . $siswa_id);
    }
}
