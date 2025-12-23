<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class HariLibur extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        // Check if user is logged in
        if (!$this->session->userdata('logged_in')) {
            redirect('login');
        }
        
        // Check if user is admin
        if ($this->session->userdata('role') != 'admin') {
            redirect('dashboard');
        }
    }
    
    public function index() {
        $data['title'] = 'Hari Libur Nasional';
        
        // Get holidays
        $data['holidays'] = $this->db->order_by('tanggal', 'DESC')->get('hari_libur')->result();
        
        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar');
        $this->load->view('templates/sidebar_admin');
        $this->load->view('admin/settings/hari_libur', $data);
        $this->load->view('templates/footer');
    }
    
    public function add() {
        $this->form_validation->set_rules('tanggal', 'Tanggal', 'required');
        $this->form_validation->set_rules('keterangan', 'Keterangan', 'required');
        
        if ($this->form_validation->run() == FALSE) {
            $response = ['success' => false, 'message' => validation_errors()];
        } else {
            $data = [
                'tanggal' => $this->input->post('tanggal'),
                'keterangan' => $this->input->post('keterangan')
            ];
            
            if ($this->db->insert('hari_libur', $data)) {
                $response = ['success' => true, 'message' => 'Hari libur berhasil ditambahkan'];
            } else {
                $response = ['success' => false, 'message' => 'Gagal menambahkan hari libur'];
            }
        }
        
        echo json_encode($response);
    }
    
    public function get($id) {
        $holiday = $this->db->get_where('hari_libur', ['id' => $id])->row();
        echo json_encode($holiday);
    }
    
    public function edit($id) {
        $this->form_validation->set_rules('tanggal', 'Tanggal', 'required');
        $this->form_validation->set_rules('keterangan', 'Keterangan', 'required');
        
        if ($this->form_validation->run() == FALSE) {
            $response = ['success' => false, 'message' => validation_errors()];
        } else {
            $data = [
                'tanggal' => $this->input->post('tanggal'),
                'keterangan' => $this->input->post('keterangan')
            ];
            
            $this->db->where('id', $id);
            if ($this->db->update('hari_libur', $data)) {
                $response = ['success' => true, 'message' => 'Hari libur berhasil diperbarui'];
            } else {
                $response = ['success' => false, 'message' => 'Gagal memperbarui hari libur'];
            }
        }
        
        echo json_encode($response);
    }
    
    public function delete($id) {
        $this->db->where('id', $id);
        if ($this->db->delete('hari_libur')) {
            $response = ['success' => true, 'message' => 'Hari libur berhasil dihapus'];
        } else {
            $response = ['success' => false, 'message' => 'Gagal menghapus hari libur'];
        }
        
        echo json_encode($response);
    }
}
