<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * BK Profile Controller
 */
class Profile extends CI_Controller {

    public function __construct() {
        parent::__construct();
        check_role(['bk']);
        $this->load->model('Guru_model');
    }

    public function index() {
        $guru_id = $this->session->userdata('guru_id');
        
        $data['guru'] = $this->Guru_model->get_by_id($guru_id);
        
        if (!$data['guru']) {
            show_error('Data guru tidak ditemukan', 404);
        }
        
        $data['title'] = 'Profile';
        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar_guru');
        $this->load->view('templates/sidebar_bk');
        $this->load->view('bk/profile/index', $data);
        $this->load->view('templates/footer');
    }

    public function update() {
        $guru_id = $this->session->userdata('guru_id');
        
        $this->form_validation->set_rules('nip', 'NIP', 'required|trim');
        $this->form_validation->set_rules('nama', 'Nama', 'required|trim');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('no_hp', 'No HP', 'required|trim');
        
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('bk/profile');
        }
        
        $data = array(
            'nip' => $this->input->post('nip', TRUE),
            'nama' => $this->input->post('nama', TRUE),
            'email' => $this->input->post('email', TRUE),
            'no_hp' => $this->input->post('no_hp', TRUE),
            'alamat' => $this->input->post('alamat', TRUE)
        );
        
        if ($this->Guru_model->update($guru_id, $data)) {
            // Update session nama
            $this->session->set_userdata('nama', $data['nama']);
            $this->session->set_flashdata('success', 'Profile berhasil diupdate');
        } else {
            $this->session->set_flashdata('error', 'Gagal mengupdate profile');
        }
        
        redirect('bk/profile');
    }

    public function change_password() {
        $this->form_validation->set_rules('old_password', 'Password Lama', 'required');
        $this->form_validation->set_rules('new_password', 'Password Baru', 'required|min_length[6]');
        $this->form_validation->set_rules('confirm_password', 'Konfirmasi Password', 'required|matches[new_password]');
        
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('bk/profile');
        }
        
        $user_id = $this->session->userdata('user_id');
        $old_password = $this->input->post('old_password', TRUE);
        $new_password = $this->input->post('new_password', TRUE);
        
        // Check old password
        $this->db->where('id', $user_id);
        $user = $this->db->get('users')->row_array();
        
        if (!$user || !password_verify($old_password, $user['password'])) {
            $this->session->set_flashdata('error', 'Password lama salah');
            redirect('bk/profile');
        }
        
        // Update password
        $this->db->where('id', $user_id);
        $this->db->update('users', array('password' => password_hash($new_password, PASSWORD_DEFAULT)));
        
        $this->session->set_flashdata('success', 'Password berhasil diubah');
        redirect('bk/profile');
    }
}
