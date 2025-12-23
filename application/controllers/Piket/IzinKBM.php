<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class IzinKBM extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
        $role = $this->session->userdata('role');
        if ($role != 'guru_piket') {
            redirect('guru/dashboard');
        }
        $this->load->model('IzinKBM_model');
        $this->load->model('Kelas_model');
        $this->load->model('Siswa_model');
    }

    public function index() {
        $data['izin_list'] = $this->IzinKBM_model->get_izin_hari_ini();
        $data['kelas_list'] = $this->Kelas_model->get_active_year();
        $data['title'] = 'Izin KBM';
        
        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar_piket');
        $this->load->view('templates/sidebar_piket');
        $this->load->view('piket/izin/index', $data);
        $this->load->view('templates/footer');
    }

    public function tambah() {
        if ($this->input->method() == 'post') {
            $this->form_validation->set_rules('siswa_id', 'Siswa', 'required');
            $this->form_validation->set_rules('tanggal', 'Tanggal', 'required');
            $this->form_validation->set_rules('jenis', 'Jenis', 'required');
            $this->form_validation->set_rules('jam_izin', 'Jam Izin', 'required');
            $this->form_validation->set_rules('alasan', 'Alasan', 'required');
            
            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata('error', validation_errors());
            } else {
                $data = [
                    'siswa_id' => $this->input->post('siswa_id'),
                    'tanggal' => $this->input->post('tanggal'),
                    'jenis' => $this->input->post('jenis'),
                    'jam_izin' => $this->input->post('jam_izin'),
                    'jam_kembali' => $this->input->post('jam_kembali') ?: null,
                    'alasan' => $this->input->post('alasan'),
                    'guru_piket_id' => $this->session->userdata('guru_id')
                ];
                
                if ($this->IzinKBM_model->add_izin_kbm($data)) {
                    $this->session->set_flashdata('success', 'Data izin KBM berhasil ditambahkan');
                    redirect('piket/izin');
                } else {
                    $this->session->set_flashdata('error', 'Gagal menambahkan data izin KBM');
                }
            }
        }
        
        $data['kelas_list'] = $this->Kelas_model->get_active_year();
        $data['title'] = 'Tambah Izin KBM';
        
        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar_piket');
        $this->load->view('templates/sidebar_piket');
        $this->load->view('piket/izin/form', $data);
        $this->load->view('templates/footer');
    }

    public function edit($id) {
        $izin = $this->IzinKBM_model->get($id);
        
        if (!$izin) {
            $this->session->set_flashdata('error', 'Data izin tidak ditemukan');
            redirect('piket/izin');
        }
        
        if ($this->input->method() == 'post') {
            $this->form_validation->set_rules('siswa_id', 'Siswa', 'required');
            $this->form_validation->set_rules('tanggal', 'Tanggal', 'required');
            $this->form_validation->set_rules('jenis', 'Jenis', 'required');
            $this->form_validation->set_rules('jam_izin', 'Jam Izin', 'required');
            $this->form_validation->set_rules('alasan', 'Alasan', 'required');
            
            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata('error', validation_errors());
            } else {
                $data = [
                    'siswa_id' => $this->input->post('siswa_id'),
                    'tanggal' => $this->input->post('tanggal'),
                    'jenis' => $this->input->post('jenis'),
                    'jam_izin' => $this->input->post('jam_izin'),
                    'jam_kembali' => $this->input->post('jam_kembali') ?: null,
                    'alasan' => $this->input->post('alasan')
                ];
                
                if ($this->IzinKBM_model->update($id, $data)) {
                    $this->session->set_flashdata('success', 'Data izin KBM berhasil diupdate');
                    redirect('piket/izin');
                } else {
                    $this->session->set_flashdata('error', 'Gagal mengupdate data izin KBM');
                }
            }
        }
        
        $data['izin'] = $izin;
        $data['kelas_list'] = $this->Kelas_model->get_active_year();
        $data['title'] = 'Edit Izin KBM';
        
        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar_piket');
        $this->load->view('templates/sidebar_piket');
        $this->load->view('piket/izin/form', $data);
        $this->load->view('templates/footer');
    }

    public function hapus($id) {
        if ($this->IzinKBM_model->delete($id)) {
            $this->session->set_flashdata('success', 'Data izin berhasil dihapus');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus data izin');
        }
        
        redirect('piket/izin');
    }

    public function get_data() {
        $tanggal = $this->input->post('tanggal') ?: date('Y-m-d');
        $kelas_id = $this->input->post('kelas_id');
        
        if ($kelas_id) {
            $izin_list = $this->IzinKBM_model->get_izin_by_kelas($kelas_id);
        } else {
            $izin_list = $this->IzinKBM_model->get_izin_by_tanggal($tanggal);
        }
        
        $data = [];
        $no = 1;
        foreach ($izin_list as $izin) {
            $jenis_badge = '';
            switch ($izin->jenis) {
                case 'Masuk Terlambat':
                    $jenis_badge = 'badge-warning';
                    break;
                case 'Keluar Awal':
                    $jenis_badge = 'badge-info';
                    break;
                case 'Tidak Masuk':
                    $jenis_badge = 'badge-danger';
                    break;
            }
            
            $row = [];
            $row[] = $no++;
            $row[] = date('d/m/Y', strtotime($izin->tanggal));
            $row[] = $izin->nis;
            $row[] = $izin->nama_lengkap;
            $row[] = $izin->nama_kelas;
            $row[] = '<span class="badge ' . $jenis_badge . '">' . $izin->jenis . '</span>';
            $row[] = date('H:i', strtotime($izin->jam_izin));
            $row[] = $izin->jam_kembali ? date('H:i', strtotime($izin->jam_kembali)) : '-';
            $row[] = '
                <a href="' . base_url('piket/izin/edit/' . $izin->id) . '" class="btn btn-sm btn-warning">
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

    public function get_siswa_by_kelas() {
        $kelas_id = $this->input->post('kelas_id');
        $siswa = $this->Siswa_model->get_by_kelas($kelas_id);
        echo json_encode($siswa);
    }
}
