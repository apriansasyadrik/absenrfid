<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
        $role = $this->session->userdata('role');
        if (!in_array($role, ['guru', 'walikelas', 'guru_piket'])) {
            show_error('Access Denied', 403);
        }
        $this->load->model('Guru_model');
        $this->load->model('Auth_model');
    }

    public function index() {
        $guru_id = $this->session->userdata('guru_id');
        $data['guru'] = $this->Guru_model->get($guru_id);
        $data['title'] = 'Profile';
        
        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar_guru');
        $this->load->view('templates/sidebar_guru');
        $this->load->view('guru/profile/index', $data);
        $this->load->view('templates/footer');
    }

    public function edit() {
        $guru_id = $this->session->userdata('guru_id');
        
        if ($this->input->method() == 'post') {
            $data = [
                'nama_lengkap' => $this->input->post('nama_lengkap'),
                'no_hp' => $this->input->post('no_hp'),
                'email' => $this->input->post('email'),
                'alamat' => $this->input->post('alamat')
            ];
            
            // Handle photo upload
            if (!empty($_FILES['foto']['name'])) {
                $config['upload_path'] = './assets/uploads/foto_guru/';
                $config['allowed_types'] = 'jpg|jpeg|png';
                $config['max_size'] = 2048;
                $config['encrypt_name'] = TRUE;
                
                $this->load->library('upload', $config);
                
                if ($this->upload->do_upload('foto')) {
                    $upload_data = $this->upload->data();
                    $data['foto'] = $upload_data['file_name'];
                    
                    // Delete old photo
                    $guru = $this->Guru_model->get($guru_id);
                    if ($guru->foto && file_exists('./assets/uploads/foto_guru/' . $guru->foto)) {
                        unlink('./assets/uploads/foto_guru/' . $guru->foto);
                    }
                }
            }
            
            $this->Guru_model->update($guru_id, $data);
            $this->session->set_flashdata('message', '<div class="alert alert-success">Profile berhasil diperbarui</div>');
            redirect('guru/profile');
        }
    }

    public function change_password() {
        $user_id = $this->session->userdata('user_id');
        
        if ($this->input->method() == 'post') {
            $old_password = $this->input->post('old_password');
            $new_password = $this->input->post('new_password');
            $confirm_password = $this->input->post('confirm_password');
            
            $user = $this->Auth_model->get_user($user_id);
            
            if (!password_verify($old_password, $user->password)) {
                $this->session->set_flashdata('message', '<div class="alert alert-danger">Password lama salah</div>');
                redirect('guru/profile');
                return;
            }
            
            if ($new_password != $confirm_password) {
                $this->session->set_flashdata('message', '<div class="alert alert-danger">Password baru tidak cocok</div>');
                redirect('guru/profile');
                return;
            }
            
            $data = ['password' => password_hash($new_password, PASSWORD_BCRYPT)];
            $this->Auth_model->update_user($user_id, $data);
            
            $this->session->set_flashdata('message', '<div class="alert alert-success">Password berhasil diubah</div>');
            redirect('guru/profile');
        }
    }
}
