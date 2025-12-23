<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class IzinSiswa_model extends CI_Model {

    public function get_by_kelas($kelas_id) {
        return $this->db->query("
            SELECT i.*, s.nis, s.nama_lengkap, s.kelas_id
            FROM izin_siswa i
            JOIN siswa s ON s.id = i.siswa_id
            WHERE s.kelas_id = ?
            ORDER BY i.tanggal_mulai DESC
        ", [$kelas_id])->result();
    }

    public function get_by_id($id) {
        return $this->db->get_where('izin_siswa', ['id' => $id])->row();
    }

    public function insert($data) {
        return $this->db->insert('izin_siswa', $data);
    }

    public function update($id, $data) {
        return $this->db->update('izin_siswa', $data, ['id' => $id]);
    }

    public function delete($id) {
        return $this->db->delete('izin_siswa', ['id' => $id]);
    }
}
