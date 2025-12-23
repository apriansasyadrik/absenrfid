<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Settings Controller (Admin)
 * Manage school settings
 */
class Settings extends CI_Controller {

    public function __construct() {
        parent::__construct();
        check_role(['admin']);
        $this->load->model('Settings_model');
    }

    /**
     * Settings index page
     */
    public function index() {
        $data['title'] = 'Pengaturan Sekolah';
        $data['settings'] = $this->Settings_model->get_settings();
        
        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('templates/sidebar_admin', $data);
        $this->load->view('admin/settings/index', $data);
        $this->load->view('templates/footer');
    }

    /**
     * Update settings
     */
    public function update() {
        $this->form_validation->set_rules('nama_sekolah', 'Nama Sekolah', 'required|trim');
        $this->form_validation->set_rules('alamat', 'Alamat', 'required|trim');
        $this->form_validation->set_rules('kepala_sekolah', 'Kepala Sekolah', 'required|trim');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('admin/settings');
        }

        $data = array(
            'nama_sekolah' => $this->input->post('nama_sekolah', TRUE),
            'alamat' => $this->input->post('alamat', TRUE),
            'kepala_sekolah' => $this->input->post('kepala_sekolah', TRUE)
        );

        if ($this->Settings_model->update_settings($data)) {
            $this->session->set_flashdata('success', 'Pengaturan berhasil diupdate');
        } else {
            $this->session->set_flashdata('error', 'Gagal mengupdate pengaturan');
        }

        redirect('admin/settings');
    }

    /**
     * Upload logo
     */
    public function upload_logo() {
        $config['upload_path'] = './assets/uploads/logo/';
        $config['allowed_types'] = 'jpg|jpeg|png';
        $config['max_size'] = 2048; // 2MB
        $config['encrypt_name'] = TRUE;

        $this->upload->initialize($config);

        if ($this->upload->do_upload('logo')) {
            $file_data = $this->upload->data();
            
            // Delete old logo
            $old_settings = $this->Settings_model->get_settings();
            if (!empty($old_settings['logo']) && file_exists('./assets/uploads/logo/' . $old_settings['logo'])) {
                unlink('./assets/uploads/logo/' . $old_settings['logo']);
            }

            // Update database
            if ($this->Settings_model->update_logo($file_data['file_name'])) {
                $this->session->set_flashdata('success', 'Logo berhasil diupload');
            } else {
                $this->session->set_flashdata('error', 'Gagal menyimpan logo');
            }
        } else {
            $this->session->set_flashdata('error', $this->upload->display_errors('', ''));
        }

        redirect('admin/settings');
    }

    /**
     * Delete logo
     */
    public function delete_logo() {
        $settings = $this->Settings_model->get_settings();
        
        if (!empty($settings['logo'])) {
            // Delete file
            if (file_exists('./assets/uploads/logo/' . $settings['logo'])) {
                unlink('./assets/uploads/logo/' . $settings['logo']);
            }

            // Update database
            $this->Settings_model->update_logo(NULL);
            $this->session->set_flashdata('success', 'Logo berhasil dihapus');
        } else {
            $this->session->set_flashdata('error', 'Tidak ada logo untuk dihapus');
        }

        redirect('admin/settings');
    }
}
