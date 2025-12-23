<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Izin_model extends CI_Model {

    /**
     * Get students by class ID
     */
    public function get_siswa_by_kelas($kelas_id) {
        return $this->db
            ->select('s.*, k.nama_kelas, k.tingkat')
            ->from('siswa s')
            ->join('kelas k', 'k.id = s.kelas_id')
            ->where('s.kelas_id', $kelas_id)
            ->order_by('s.nama_lengkap', 'ASC')
            ->get()->result();
    }

    /**
     * Get izin records by walikelas (guru_id)
     */
    public function get_izin_by_walikelas($guru_id) {
        return $this->db
            ->select('i.*, s.nama_lengkap, s.nis, k.nama_kelas, k.tingkat')
            ->from('izin_siswa i')
            ->join('siswa s', 's.id = i.siswa_id')
            ->join('kelas k', 'k.id = s.kelas_id')
            ->where('i.guru_id', $guru_id)
            ->order_by('i.tanggal', 'DESC')
            ->get()->result();
    }

    /**
     * Get all izin records for a class
     */
    public function get_izin_by_kelas($kelas_id) {
        return $this->db
            ->select('i.*, s.nama_lengkap, s.nis, k.nama_kelas, k.tingkat, g.nama as nama_guru')
            ->from('izin_siswa i')
            ->join('siswa s', 's.id = i.siswa_id')
            ->join('kelas k', 'k.id = s.kelas_id')
            ->join('guru g', 'g.id = i.guru_id')
            ->where('s.kelas_id', $kelas_id)
            ->order_by('i.tanggal', 'DESC')
            ->get()->result();
    }

    /**
     * Get single izin record
     */
    public function get($id) {
        return $this->db
            ->select('i.*, s.nama_lengkap, s.nis, s.kelas_id, k.nama_kelas, k.tingkat')
            ->from('izin_siswa i')
            ->join('siswa s', 's.id = i.siswa_id')
            ->join('kelas k', 'k.id = s.kelas_id')
            ->where('i.id', $id)
            ->get()->row();
    }

    /**
     * Add izin record
     */
    public function add_izin($data) {
        $this->db->insert('izin_siswa', $data);
        return $this->db->insert_id();
    }

    /**
     * Update izin record
     */
    public function update_izin($id, $data) {
        return $this->db->where('id', $id)->update('izin_siswa', $data);
    }

    /**
     * Delete izin record
     */
    public function delete_izin($id) {
        return $this->db->where('id', $id)->delete('izin_siswa');
    }

    /**
     * Get izin records by date range
     */
    public function get_by_date_range($start_date, $end_date, $kelas_id = null) {
        $this->db
            ->select('i.*, s.nama_lengkap, s.nis, k.nama_kelas, k.tingkat')
            ->from('izin_siswa i')
            ->join('siswa s', 's.id = i.siswa_id')
            ->join('kelas k', 'k.id = s.kelas_id')
            ->where('i.tanggal >=', $start_date)
            ->where('i.tanggal <=', $end_date);
        
        if ($kelas_id) {
            $this->db->where('s.kelas_id', $kelas_id);
        }
        
        return $this->db->order_by('i.tanggal', 'DESC')->get()->result();
    }

    /**
     * Count izin by type for a student in a month
     */
    public function count_by_student_month($siswa_id, $bulan, $tahun) {
        return $this->db
            ->select('jenis, COUNT(*) as total')
            ->from('izin_siswa')
            ->where('siswa_id', $siswa_id)
            ->where('MONTH(tanggal)', $bulan)
            ->where('YEAR(tanggal)', $tahun)
            ->group_by('jenis')
            ->get()->result();
    }
}
