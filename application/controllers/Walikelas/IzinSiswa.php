<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class IzinSiswa extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
        $role = $this->session->userdata('role');
        if ($role != 'walikelas') {
            redirect('guru/dashboard');
        }
        $this->load->model('Izin_model');
        $this->load->model('Kelas_model');
        $this->load->model('Siswa_model');
    }

    public function index() {
        $guru_id = $this->session->userdata('guru_id');
        
        // Get walikelas class
        $kelas_walikelas = $this->Kelas_model->get_by_walikelas($guru_id);
        
        if ($kelas_walikelas) {
            $data['izin_list'] = $this->Izin_model->get_izin_by_kelas($kelas_walikelas->id);
            $data['kelas_walikelas'] = $kelas_walikelas;
        } else {
            $data['izin_list'] = [];
            $data['kelas_walikelas'] = null;
        }
        
        $data['title'] = 'Input Izin Siswa';
        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar_walikelas');
        $this->load->view('templates/sidebar_walikelas');
        $this->load->view('walikelas/izin/index', $data);
        $this->load->view('templates/footer');
    }

    public function tambah() {
        $guru_id = $this->session->userdata('guru_id');
        
        // Get walikelas class
        $kelas_walikelas = $this->Kelas_model->get_by_walikelas($guru_id);
        
        if (!$kelas_walikelas) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki kelas yang diwalikan');
            redirect('walikelas/dashboard');
        }
        
        if ($this->input->method() == 'post') {
            $this->form_validation->set_rules('siswa_id', 'Siswa', 'required');
            $this->form_validation->set_rules('tanggal', 'Tanggal', 'required');
            $this->form_validation->set_rules('jenis', 'Jenis Izin', 'required|in_list[Sakit,Izin]');
            $this->form_validation->set_rules('keterangan', 'Keterangan', 'required');
            
            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata('error', validation_errors());
            } else {
                $data = [
                    'siswa_id' => $this->input->post('siswa_id'),
                    'tanggal' => $this->input->post('tanggal'),
                    'jenis' => $this->input->post('jenis'),
                    'keterangan' => $this->input->post('keterangan'),
                    'guru_id' => $guru_id
                ];
                
                if ($this->Izin_model->add_izin($data)) {
                    $this->session->set_flashdata('success', 'Data izin berhasil ditambahkan');
                    redirect('walikelas/izin');
                } else {
                    $this->session->set_flashdata('error', 'Gagal menambahkan data izin');
                }
            }
        }
        
        $data['students'] = $this->Siswa_model->get_by_kelas($kelas_walikelas->id);
        $data['kelas_walikelas'] = $kelas_walikelas;
        $data['title'] = 'Tambah Izin Siswa';
        
        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar_walikelas');
        $this->load->view('templates/sidebar_walikelas');
        $this->load->view('walikelas/izin/form', $data);
        $this->load->view('templates/footer');
    }

    public function edit($id) {
        $guru_id = $this->session->userdata('guru_id');
        
        // Get walikelas class
        $kelas_walikelas = $this->Kelas_model->get_by_walikelas($guru_id);
        
        if (!$kelas_walikelas) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki kelas yang diwalikan');
            redirect('walikelas/dashboard');
        }
        
        $izin = $this->Izin_model->get($id);
        
        if (!$izin || $izin->kelas_id != $kelas_walikelas->id) {
            $this->session->set_flashdata('error', 'Data izin tidak ditemukan');
            redirect('walikelas/izin');
        }
        
        if ($this->input->method() == 'post') {
            $this->form_validation->set_rules('siswa_id', 'Siswa', 'required');
            $this->form_validation->set_rules('tanggal', 'Tanggal', 'required');
            $this->form_validation->set_rules('jenis', 'Jenis Izin', 'required|in_list[Sakit,Izin]');
            $this->form_validation->set_rules('keterangan', 'Keterangan', 'required');
            
            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata('error', validation_errors());
            } else {
                $data = [
                    'siswa_id' => $this->input->post('siswa_id'),
                    'tanggal' => $this->input->post('tanggal'),
                    'jenis' => $this->input->post('jenis'),
                    'keterangan' => $this->input->post('keterangan')
                ];
                
                if ($this->Izin_model->update_izin($id, $data)) {
                    $this->session->set_flashdata('success', 'Data izin berhasil diupdate');
                    redirect('walikelas/izin');
                } else {
                    $this->session->set_flashdata('error', 'Gagal mengupdate data izin');
                }
            }
        }
        
        $data['izin'] = $izin;
        $data['students'] = $this->Siswa_model->get_by_kelas($kelas_walikelas->id);
        $data['kelas_walikelas'] = $kelas_walikelas;
        $data['title'] = 'Edit Izin Siswa';
        
        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar_walikelas');
        $this->load->view('templates/sidebar_walikelas');
        $this->load->view('walikelas/izin/form', $data);
        $this->load->view('templates/footer');
    }

    public function hapus($id) {
        $guru_id = $this->session->userdata('guru_id');
        
        // Get walikelas class
        $kelas_walikelas = $this->Kelas_model->get_by_walikelas($guru_id);
        
        if (!$kelas_walikelas) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki kelas yang diwalikan');
            redirect('walikelas/dashboard');
        }
        
        $izin = $this->Izin_model->get($id);
        
        if (!$izin || $izin->kelas_id != $kelas_walikelas->id) {
            $this->session->set_flashdata('error', 'Data izin tidak ditemukan');
            redirect('walikelas/izin');
        }
        
        if ($this->Izin_model->delete_izin($id)) {
            $this->session->set_flashdata('success', 'Data izin berhasil dihapus');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus data izin');
        }
        
        redirect('walikelas/izin');
    }

    public function get_data() {
        $guru_id = $this->session->userdata('guru_id');
        $kelas_walikelas = $this->Kelas_model->get_by_walikelas($guru_id);
        
        if (!$kelas_walikelas) {
            echo json_encode(['data' => []]);
            return;
        }
        
        $izin_list = $this->Izin_model->get_izin_by_kelas($kelas_walikelas->id);
        
        $data = [];
        $no = 1;
        foreach ($izin_list as $izin) {
            $jenis_badge = $izin->jenis == 'Sakit' ? 'badge-warning' : 'badge-info';
            $row = [];
            $row[] = $no++;
            $row[] = $izin->nis;
            $row[] = $izin->nama_lengkap;
            $row[] = date('d/m/Y', strtotime($izin->tanggal));
            $row[] = '<span class="badge ' . $jenis_badge . '">' . $izin->jenis . '</span>';
            $row[] = $izin->keterangan;
            $row[] = '
                <a href="' . base_url('walikelas/izin/edit/' . $izin->id) . '" class="btn btn-sm btn-warning">
                    <i class="fas fa-edit"></i>
                </a>
                <button onclick="confirmDelete(' . $izin->id . ')" class="btn btn-sm btn-danger">
                    <i class="fas fa-trash"></i>
                </button>
            ';
            $data[] = $row;
        }
        
        echo json_encode(['data' => $data]);
    }
}
