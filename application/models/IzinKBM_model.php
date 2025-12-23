<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * IzinKBM Model
 * Handle izin KBM (Kegiatan Belajar Mengajar) operations for Guru Piket
 */
class IzinKBM_model extends CI_Model {

    /**
     * Get all izin KBM
     */
    public function get_all($filters = array()) {
        $this->db->select('izin_kbm.*, siswa.nama as nama_siswa, siswa.nis, 
                          kelas.nama_kelas, guru.nama as nama_guru');
        $this->db->from('izin_kbm');
        $this->db->join('siswa', 'siswa.id = izin_kbm.siswa_id');
        $this->db->join('kelas', 'kelas.id = siswa.kelas_id', 'left');
        $this->db->join('guru', 'guru.id = izin_kbm.guru_piket_id');
        
        if (isset($filters['tanggal'])) {
            $this->db->where('izin_kbm.tanggal', $filters['tanggal']);
        }
        
        if (isset($filters['kelas_id'])) {
            $this->db->where('siswa.kelas_id', $filters['kelas_id']);
        }
        
        if (isset($filters['guru_piket_id'])) {
            $this->db->where('izin_kbm.guru_piket_id', $filters['guru_piket_id']);
        }
        
        if (isset($filters['jenis'])) {
            $this->db->where('izin_kbm.jenis', $filters['jenis']);
        }
        
        if (isset($filters['bulan']) && isset($filters['tahun'])) {
            $this->db->where('MONTH(izin_kbm.tanggal)', $filters['bulan']);
            $this->db->where('YEAR(izin_kbm.tanggal)', $filters['tahun']);
        }
        
        $this->db->order_by('izin_kbm.tanggal', 'DESC');
        $this->db->order_by('izin_kbm.jam_izin', 'DESC');
        
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * Get izin KBM by ID
     */
    public function get_by_id($id) {
        $this->db->select('izin_kbm.*, siswa.nama as nama_siswa, siswa.nis, 
                          kelas.nama_kelas, guru.nama as nama_guru');
        $this->db->from('izin_kbm');
        $this->db->join('siswa', 'siswa.id = izin_kbm.siswa_id');
        $this->db->join('kelas', 'kelas.id = siswa.kelas_id', 'left');
        $this->db->join('guru', 'guru.id = izin_kbm.guru_piket_id');
        $this->db->where('izin_kbm.id', $id);
        
        $query = $this->db->get();
        return $query->row_array();
    }

    /**
     * Insert new izin KBM
     */
    public function insert($data) {
        return $this->db->insert('izin_kbm', $data);
    }

    /**
     * Update izin KBM
     */
    public function update($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('izin_kbm', $data);
    }

    /**
     * Delete izin KBM
     */
    public function delete($id) {
        $this->db->where('id', $id);
        return $this->db->delete('izin_kbm');
    }

    /**
     * Get rekap by date range
     */
    public function get_rekap($start_date, $end_date, $kelas_id = null) {
        $this->db->select('izin_kbm.*, siswa.nama as nama_siswa, siswa.nis, 
                          kelas.nama_kelas, guru.nama as nama_guru');
        $this->db->from('izin_kbm');
        $this->db->join('siswa', 'siswa.id = izin_kbm.siswa_id');
        $this->db->join('kelas', 'kelas.id = siswa.kelas_id', 'left');
        $this->db->join('guru', 'guru.id = izin_kbm.guru_piket_id');
        $this->db->where('izin_kbm.tanggal >=', $start_date);
        $this->db->where('izin_kbm.tanggal <=', $end_date);
        
        if ($kelas_id) {
            $this->db->where('siswa.kelas_id', $kelas_id);
        }
        
        $this->db->order_by('izin_kbm.tanggal', 'DESC');
        $this->db->order_by('kelas.nama_kelas', 'ASC');
        
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * Count izin by guru piket
     */
    public function count_by_guru($guru_id, $bulan = null, $tahun = null) {
        $this->db->where('guru_piket_id', $guru_id);
        
        if ($bulan && $tahun) {
            $this->db->where('MONTH(tanggal)', $bulan);
            $this->db->where('YEAR(tanggal)', $tahun);
        }
        
        return $this->db->count_all_results('izin_kbm');
    }

    /**
     * Get stats by type
     */
    public function get_stats_by_type($start_date, $end_date, $kelas_id = null) {
        $this->db->select('jenis, COUNT(*) as jumlah');
        $this->db->from('izin_kbm');
        $this->db->where('tanggal >=', $start_date);
        $this->db->where('tanggal <=', $end_date);
        
        if ($kelas_id) {
            $this->db->join('siswa', 'siswa.id = izin_kbm.siswa_id');
            $this->db->where('siswa.kelas_id', $kelas_id);
        }
        
        $this->db->group_by('jenis');
        
        $query = $this->db->get();
        return $query->result_array();
    }
}
