<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jurnal extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
        $role = $this->session->userdata('role');
        if (!in_array($role, ['guru', 'walikelas', 'guru_piket'])) {
            show_error('Access Denied', 403);
        }
        $this->load->model('Jurnal_model');
        $this->load->model('Jadwal_model');
        $this->load->model('Siswa_model');
        $this->load->model('Absensi_model');
    }

    public function index() {
        $guru_id = $this->session->userdata('guru_id');
        $data['journals'] = $this->Jurnal_model->get_by_teacher($guru_id);
        $data['title'] = 'Jurnal Mengajar';
        
        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar_guru');
        $this->load->view('templates/sidebar_guru');
        $this->load->view('guru/jurnal/index', $data);
        $this->load->view('templates/footer');
    }

    public function add() {
        $guru_id = $this->session->userdata('guru_id');
        
        if ($this->input->method() == 'post') {
            $jadwal_id = $this->input->post('jadwal_id');
            
            // Insert journal
            $journal_data = [
                'jadwal_id' => $jadwal_id,
                'tanggal' => $this->input->post('tanggal'),
                'materi' => $this->input->post('materi'),
                'kegiatan' => $this->input->post('kegiatan'),
                'hambatan' => $this->input->post('hambatan')
            ];
            
            $jurnal_id = $this->Jurnal_model->insert($journal_data);
            
            // Insert attendance per student
            $students = $this->input->post('students');
            if ($students) {
                foreach ($students as $siswa_id) {
                    $status = $this->input->post('status_' . $siswa_id);
                    if ($status) {
                        $attendance_data = [
                            'jurnal_id' => $jurnal_id,
                            'siswa_id' => $siswa_id,
                            'status' => $status,
                            'keterangan' => $this->input->post('keterangan_' . $siswa_id)
                        ];
                        $this->Absensi_model->insert_mapel($attendance_data);
                    }
                }
            }
            
            $this->session->set_flashdata('message', '<div class="alert alert-success">Jurnal berhasil disimpan</div>');
            redirect('guru/jurnal');
        }
        
        $data['schedules'] = $this->Jadwal_model->get_by_teacher($guru_id);
        $data['title'] = 'Tambah Jurnal';
        
        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar_guru');
        $this->load->view('templates/sidebar_guru');
        $this->load->view('guru/jurnal/form', $data);
        $this->load->view('templates/footer');
    }

    public function get_students($jadwal_id) {
        $jadwal = $this->Jadwal_model->get($jadwal_id);
        $students = $this->Siswa_model->get_by_kelas($jadwal->kelas_id);
        echo json_encode($students);
    }

    public function edit($id) {
        $guru_id = $this->session->userdata('guru_id');
        $journal = $this->Jurnal_model->get($id);
        
        if ($this->input->method() == 'post') {
            $journal_data = [
                'tanggal' => $this->input->post('tanggal'),
                'materi' => $this->input->post('materi'),
                'kegiatan' => $this->input->post('kegiatan'),
                'hambatan' => $this->input->post('hambatan')
            ];
            
            $this->Jurnal_model->update($id, $journal_data);
            
            // Update attendance
            $students = $this->input->post('students');
            if ($students) {
                foreach ($students as $siswa_id) {
                    $status = $this->input->post('status_' . $siswa_id);
                    if ($status) {
                        $attendance_data = [
                            'status' => $status,
                            'keterangan' => $this->input->post('keterangan_' . $siswa_id)
                        ];
                        $this->Absensi_model->update_mapel_by_jurnal_siswa($id, $siswa_id, $attendance_data);
                    }
                }
            }
            
            $this->session->set_flashdata('message', '<div class="alert alert-success">Jurnal berhasil diperbarui</div>');
            redirect('guru/jurnal');
        }
        
        $data['journal'] = $journal;
        $data['schedules'] = $this->Jadwal_model->get_by_teacher($guru_id);
        $data['attendance'] = $this->Absensi_model->get_mapel_by_jurnal($id);
        $data['title'] = 'Edit Jurnal';
        
        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar_guru');
        $this->load->view('templates/sidebar_guru');
        $this->load->view('guru/jurnal/form', $data);
        $this->load->view('templates/footer');
    }

    public function delete($id) {
        $this->Jurnal_model->delete($id);
        $this->session->set_flashdata('message', '<div class="alert alert-success">Jurnal berhasil dihapus</div>');
        redirect('guru/jurnal');
    }
}
