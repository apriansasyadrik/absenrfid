<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rekap extends CI_Controller {

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
    }

    public function index() {
        $tanggal = $this->input->get('tanggal') ?: date('Y-m-d');
        $kelas_id = $this->input->get('kelas_id');
        
        // Build query
        $this->db->select('i.*, s.nis, s.nama_lengkap, k.tingkat, k.nama_kelas, g.nama_lengkap as guru_nama');
        $this->db->from('izin_siswa i');
        $this->db->join('siswa s', 's.id = i.siswa_id');
        $this->db->join('kelas k', 'k.id = s.kelas_id');
        $this->db->join('guru g', 'g.id = i.guru_id');
        $this->db->where('i.tanggal_mulai', $tanggal);
        
        if ($kelas_id) {
            $this->db->where('s.kelas_id', $kelas_id);
        }
        
        $this->db->order_by('i.created_at', 'DESC');
        $data['rekap_list'] = $this->db->get()->result();
        
        // Get classes for filter
        $data['kelas_list'] = $this->db->get('kelas')->result();
        $data['tanggal'] = $tanggal;
        $data['kelas_id'] = $kelas_id;
        $data['title'] = 'Rekap Izin';
        
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar_piket');
        $this->load->view('templates/topbar_guru');
        $this->load->view('piket/rekap/index', $data);
        $this->load->view('templates/footer');
    }
}
