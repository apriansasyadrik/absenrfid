<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Auth Controller
 * Handle authentication: login and logout
 */
class Auth extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Auth_model');
    }

    /**
     * Login page
     */
    public function login() {
        // If already logged in, redirect to dashboard
        if ($this->session->userdata('logged_in')) {
            $this->_redirect_by_role();
        }

        $data['title'] = 'Login - Sistem Absensi RFID';
        $this->load->view('auth/login', $data);
    }

    /**
     * Process login
     */
    public function do_login() {
        $this->form_validation->set_rules('username', 'Username', 'required|trim');
        $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', 'Username dan password harus diisi');
            redirect('login');
        }

        $username = $this->input->post('username', TRUE);
        $password = $this->input->post('password', TRUE);

        $user = $this->Auth_model->check_login($username, $password);

        if ($user) {
            // Set session data
            $session_data = array(
                'user_id' => $user['id'],
                'username' => $user['username'],
                'role' => $user['role'],
                'guru_id' => $user['guru_id'],
                'nama' => $user['nama'],
                'logged_in' => TRUE
            );
            
            $this->session->set_userdata($session_data);

            // Update last login
            $this->Auth_model->update_last_login($user['id']);

            $this->session->set_flashdata('success', 'Login berhasil! Selamat datang ' . $user['nama']);
            $this->_redirect_by_role();
        } else {
            $this->session->set_flashdata('error', 'Username atau password salah!');
            redirect('login');
        }
    }

    /**
     * Logout
     */
    public function logout() {
        $this->session->unset_userdata('user_id');
        $this->session->unset_userdata('username');
        $this->session->unset_userdata('role');
        $this->session->unset_userdata('guru_id');
        $this->session->unset_userdata('nama');
        $this->session->unset_userdata('logged_in');
        
        $this->session->set_flashdata('success', 'Anda telah logout');
        redirect('login');
    }

    /**
     * Redirect user based on role
     */
    private function _redirect_by_role() {
        $role = $this->session->userdata('role');
        
        switch ($role) {
            case 'admin':
                redirect('dashboard');
                break;
            case 'guru':
                redirect('guru/dashboard');
                break;
            case 'walikelas':
                redirect('walikelas/dashboard');
                break;
            case 'guru_piket':
                redirect('piket/dashboard');
                break;
            case 'bk':
                redirect('bk/dashboard');
                break;
            default:
                redirect('login');
                break;
        }
    }
}
