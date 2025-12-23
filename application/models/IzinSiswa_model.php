<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * IzinSiswa Model
 * Handle izin siswa operations (Walikelas & Piket)
 */
class IzinSiswa_model extends CI_Model {

    /**
     * Get all izin siswa
     */
    public function get_all($filters = array()) {
        $this->db->select('izin_siswa.*, siswa.nama as nama_siswa, siswa.nis, 
                          kelas.nama_kelas, guru.nama as nama_guru');
        $this->db->from('izin_siswa');
        $this->db->join('siswa', 'siswa.id = izin_siswa.siswa_id');
        $this->db->join('kelas', 'kelas.id = siswa.kelas_id', 'left');
        $this->db->join('guru', 'guru.id = izin_siswa.guru_id');
        
        if (isset($filters['tanggal'])) {
            $this->db->where('izin_siswa.tanggal', $filters['tanggal']);
        }
        
        if (isset($filters['kelas_id'])) {
            $this->db->where('siswa.kelas_id', $filters['kelas_id']);
        }
        
        if (isset($filters['guru_id'])) {
            $this->db->where('izin_siswa.guru_id', $filters['guru_id']);
        }
        
        if (isset($filters['jenis'])) {
            $this->db->where('izin_siswa.jenis', $filters['jenis']);
        }
        
        if (isset($filters['bulan']) && isset($filters['tahun'])) {
            $start_date = $filters['tahun'] . '-' . str_pad($filters['bulan'], 2, '0', STR_PAD_LEFT) . '-01';
            $end_date = date('Y-m-t', strtotime($start_date));
            $this->db->where('izin_siswa.tanggal >=', $start_date);
            $this->db->where('izin_siswa.tanggal <=', $end_date);
        }
        
        $this->db->order_by('izin_siswa.tanggal', 'DESC');
        $this->db->order_by('izin_siswa.created_at', 'DESC');
        
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * Get izin by ID
     */
    public function get_by_id($id) {
        $this->db->select('izin_siswa.*, siswa.nama as nama_siswa, siswa.nis, 
                          kelas.nama_kelas, guru.nama as nama_guru');
        $this->db->from('izin_siswa');
        $this->db->join('siswa', 'siswa.id = izin_siswa.siswa_id');
        $this->db->join('kelas', 'kelas.id = siswa.kelas_id', 'left');
        $this->db->join('guru', 'guru.id = izin_siswa.guru_id');
        $this->db->where('izin_siswa.id', $id);
        
        $query = $this->db->get();
        return $query->row_array();
    }

    /**
     * Get siswa by kelas (for walikelas)
     */
    public function get_siswa_by_kelas($kelas_id) {
        $this->db->select('siswa.*, kelas.nama_kelas');
        $this->db->from('siswa');
        $this->db->join('kelas', 'kelas.id = siswa.kelas_id');
        $this->db->where('siswa.kelas_id', $kelas_id);
        $this->db->where('siswa.status', 'Aktif');
        $this->db->order_by('siswa.nama', 'ASC');
        
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * Insert new izin
     */
    public function insert($data) {
        return $this->db->insert('izin_siswa', $data);
    }

    /**
     * Update izin
     */
    public function update($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('izin_siswa', $data);
    }

    /**
     * Delete izin
     */
    public function delete($id) {
        $this->db->where('id', $id);
        return $this->db->delete('izin_siswa');
    }

    /**
     * Check if siswa already has izin on date
     */
    public function check_existing($siswa_id, $tanggal, $exclude_id = null) {
        $this->db->where('siswa_id', $siswa_id);
        $this->db->where('tanggal', $tanggal);
        
        if ($exclude_id) {
            $this->db->where('id !=', $exclude_id);
        }
        
        $query = $this->db->get('izin_siswa');
        return $query->num_rows() > 0;
    }

    /**
     * Count izin by guru (for stats)
     */
    public function count_by_guru($guru_id, $bulan = null, $tahun = null) {
        $this->db->where('guru_id', $guru_id);
        
        if ($bulan && $tahun) {
            $start_date = $tahun . '-' . str_pad($bulan, 2, '0', STR_PAD_LEFT) . '-01';
            $end_date = date('Y-m-t', strtotime($start_date));
            $this->db->where('tanggal >=', $start_date);
            $this->db->where('tanggal <=', $end_date);
        }
        
        return $this->db->count_all_results('izin_siswa');
    }

    /**
     * Get izin stats by type
     */
    public function get_stats_by_type($guru_id, $bulan = null, $tahun = null) {
        $this->db->select('jenis, COUNT(*) as jumlah');
        $this->db->from('izin_siswa');
        $this->db->where('guru_id', $guru_id);
        
        if ($bulan && $tahun) {
            $start_date = $tahun . '-' . str_pad($bulan, 2, '0', STR_PAD_LEFT) . '-01';
            $end_date = date('Y-m-t', strtotime($start_date));
            $this->db->where('tanggal >=', $start_date);
            $this->db->where('tanggal <=', $end_date);
        }
        
        $this->db->group_by('jenis');
        
        $query = $this->db->get();
        return $query->result_array();
    }
}
