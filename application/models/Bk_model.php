<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * BK Model
 * Handle Bimbingan Konseling operations
 */
class Bk_model extends CI_Model {

    /**
     * Get siswa bermasalah (alpha >= 3 or terlambat >= 5)
     */
    public function get_siswa_bermasalah($bulan = null, $tahun = null) {
        if (!$bulan) $bulan = date('m');
        if (!$tahun) $tahun = date('Y');
        
        $this->db->select('monitoring_bk.*, siswa.nama, siswa.nis, siswa.nisn, 
                          kelas.nama_kelas, kelas.tingkat');
        $this->db->from('monitoring_bk');
        $this->db->join('siswa', 'siswa.id = monitoring_bk.siswa_id');
        $this->db->join('kelas', 'kelas.id = siswa.kelas_id', 'left');
        $this->db->where('monitoring_bk.bulan', $bulan);
        $this->db->where('monitoring_bk.tahun', $tahun);
        $this->db->where('monitoring_bk.is_flagged', 1);
        $this->db->order_by('monitoring_bk.jumlah_alpha', 'DESC');
        $this->db->order_by('monitoring_bk.jumlah_terlambat', 'DESC');
        
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * Get monitoring by bulan
     */
    public function get_monitoring_by_bulan($bulan, $tahun) {
        $this->db->select('monitoring_bk.*, siswa.nama, siswa.nis, siswa.nisn, 
                          kelas.nama_kelas, kelas.tingkat');
        $this->db->from('monitoring_bk');
        $this->db->join('siswa', 'siswa.id = monitoring_bk.siswa_id');
        $this->db->join('kelas', 'kelas.id = siswa.kelas_id', 'left');
        $this->db->where('monitoring_bk.bulan', $bulan);
        $this->db->where('monitoring_bk.tahun', $tahun);
        $this->db->order_by('monitoring_bk.is_flagged', 'DESC');
        $this->db->order_by('kelas.nama_kelas', 'ASC');
        $this->db->order_by('siswa.nama', 'ASC');
        
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * Get monitoring by siswa
     */
    public function get_monitoring_by_siswa($siswa_id, $limit = 12) {
        $this->db->select('monitoring_bk.*');
        $this->db->from('monitoring_bk');
        $this->db->where('siswa_id', $siswa_id);
        $this->db->order_by('tahun', 'DESC');
        $this->db->order_by('bulan', 'DESC');
        $this->db->limit($limit);
        
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * Add catatan BK
     */
    public function add_catatan_bk($siswa_id, $bulan, $tahun, $catatan) {
        $this->db->where('siswa_id', $siswa_id);
        $this->db->where('bulan', $bulan);
        $this->db->where('tahun', $tahun);
        
        return $this->db->update('monitoring_bk', array('catatan_bk' => $catatan));
    }

    /**
     * Get surat list
     */
    public function get_surat_list($filters = array()) {
        $this->db->select('surat_bk.*, siswa.nama as nama_siswa, siswa.nis, 
                          kelas.nama_kelas, guru.nama as nama_bk');
        $this->db->from('surat_bk');
        $this->db->join('siswa', 'siswa.id = surat_bk.siswa_id');
        $this->db->join('kelas', 'kelas.id = siswa.kelas_id', 'left');
        $this->db->join('guru', 'guru.id = surat_bk.bk_id');
        
        if (isset($filters['bulan']) && isset($filters['tahun'])) {
            $this->db->where('MONTH(surat_bk.tanggal)', $filters['bulan']);
            $this->db->where('YEAR(surat_bk.tanggal)', $filters['tahun']);
        }
        
        if (isset($filters['siswa_id'])) {
            $this->db->where('surat_bk.siswa_id', $filters['siswa_id']);
        }
        
        $this->db->order_by('surat_bk.tanggal', 'DESC');
        $this->db->order_by('surat_bk.created_at', 'DESC');
        
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * Get surat by ID
     */
    public function get_surat_by_id($id) {
        $this->db->select('surat_bk.*, siswa.nama as nama_siswa, siswa.nis, siswa.nisn,
                          siswa.jenis_kelamin, siswa.tempat_lahir, siswa.tanggal_lahir,
                          kelas.nama_kelas, guru.nama as nama_bk');
        $this->db->from('surat_bk');
        $this->db->join('siswa', 'siswa.id = surat_bk.siswa_id');
        $this->db->join('kelas', 'kelas.id = siswa.kelas_id', 'left');
        $this->db->join('guru', 'guru.id = surat_bk.bk_id');
        $this->db->where('surat_bk.id', $id);
        
        $query = $this->db->get();
        return $query->row_array();
    }

    /**
     * Create surat panggilan
     */
    public function create_surat($data) {
        return $this->db->insert('surat_bk', $data);
    }

    /**
     * Update surat
     */
    public function update_surat($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('surat_bk', $data);
    }

    /**
     * Delete surat
     */
    public function delete_surat($id) {
        $this->db->where('id', $id);
        return $this->db->delete('surat_bk');
    }

    /**
     * Get dashboard stats
     */
    public function get_dashboard_stats($bulan = null, $tahun = null) {
        if (!$bulan) $bulan = date('m');
        if (!$tahun) $tahun = date('Y');
        
        $stats = array();
        
        // Total siswa bermasalah
        $this->db->where('bulan', $bulan);
        $this->db->where('tahun', $tahun);
        $this->db->where('is_flagged', 1);
        $stats['total_bermasalah'] = $this->db->count_all_results('monitoring_bk');
        
        // Alpha tinggi (>= 5)
        $this->db->where('bulan', $bulan);
        $this->db->where('tahun', $tahun);
        $this->db->where('jumlah_alpha >=', 5);
        $stats['alpha_tinggi'] = $this->db->count_all_results('monitoring_bk');
        
        // Terlambat tinggi (>= 10)
        $this->db->where('bulan', $bulan);
        $this->db->where('tahun', $tahun);
        $this->db->where('jumlah_terlambat >=', 10);
        $stats['terlambat_tinggi'] = $this->db->count_all_results('monitoring_bk');
        
        // Surat bulan ini
        $this->db->where('MONTH(tanggal)', $bulan);
        $this->db->where('YEAR(tanggal)', $tahun);
        $stats['surat_bulan_ini'] = $this->db->count_all_results('surat_bk');
        
        return $stats;
    }

    /**
     * Get detail siswa with history
     */
    public function get_siswa_detail($siswa_id) {
        $this->db->select('siswa.*, kelas.nama_kelas, kelas.tingkat');
        $this->db->from('siswa');
        $this->db->join('kelas', 'kelas.id = siswa.kelas_id', 'left');
        $this->db->where('siswa.id', $siswa_id);
        
        $query = $this->db->get();
        $siswa = $query->row_array();
        
        if ($siswa) {
            // Get monitoring history
            $siswa['monitoring_history'] = $this->get_monitoring_by_siswa($siswa_id);
            
            // Get surat history
            $siswa['surat_history'] = $this->get_surat_list(array('siswa_id' => $siswa_id));
        }
        
        return $siswa;
    }
}
