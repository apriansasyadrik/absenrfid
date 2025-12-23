<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan_model extends CI_Model {
    
    public function get_laporan_siswa($bulan = null, $tahun = null, $kelas_id = null) {
        $this->db->select('s.*, k.tingkat, k.nama_kelas, 
                          COUNT(DISTINCT ah.id) as total_hadir,
                          SUM(CASE WHEN ah.keterangan_terlambat IS NOT NULL AND ah.keterangan_terlambat != "" THEN 1 ELSE 0 END) as total_terlambat');
        $this->db->from('siswa s');
        $this->db->join('kelas k', 'k.id = s.kelas_id', 'left');
        $this->db->join('absensi_harian ah', 'ah.siswa_id = s.id AND ah.status = "masuk"', 'left');
        
        if ($bulan) {
            $this->db->where('MONTH(ah.tanggal)', $bulan);
        }
        if ($tahun) {
            $this->db->where('YEAR(ah.tanggal)', $tahun);
        }
        if ($kelas_id) {
            $this->db->where('s.kelas_id', $kelas_id);
        }
        
        $this->db->group_by('s.id');
        $this->db->order_by('k.tingkat, k.nama_kelas, s.nama_lengkap');
        
        return $this->db->get()->result();
    }
    
    public function get_laporan_guru($bulan = null, $tahun = null) {
        $this->db->select('g.*, 
                          COUNT(DISTINCT ah.id) as total_hadir,
                          SUM(CASE WHEN ah.keterangan_terlambat IS NOT NULL AND ah.keterangan_terlambat != "" THEN 1 ELSE 0 END) as total_terlambat');
        $this->db->from('guru g');
        $this->db->join('absensi_harian ah', 'ah.guru_id = g.id AND ah.status = "masuk"', 'left');
        
        if ($bulan) {
            $this->db->where('MONTH(ah.tanggal)', $bulan);
        }
        if ($tahun) {
            $this->db->where('YEAR(ah.tanggal)', $tahun);
        }
        
        $this->db->group_by('g.id');
        $this->db->order_by('g.nama_lengkap');
        
        return $this->db->get()->result();
    }
    
    public function get_rekap_siswa_detail($siswa_id, $bulan, $tahun) {
        $this->db->where('siswa_id', $siswa_id);
        $this->db->where('MONTH(tanggal)', $bulan);
        $this->db->where('YEAR(tanggal)', $tahun);
        $this->db->order_by('tanggal', 'ASC');
        
        return $this->db->get('absensi_harian')->result();
    }
    
    public function get_rekap_guru_detail($guru_id, $bulan, $tahun) {
        $this->db->where('guru_id', $guru_id);
        $this->db->where('MONTH(tanggal)', $bulan);
        $this->db->where('YEAR(tanggal)', $tahun);
        $this->db->order_by('tanggal', 'ASC');
        
        return $this->db->get('absensi_harian')->result();
    }
    
    public function get_rekap_per_semester($semester_id, $type = 'siswa') {
        // Get semester dates
        $semester = $this->db->get_where('semester', ['id' => $semester_id])->row();
        
        if (!$semester) return [];
        
        if ($type == 'siswa') {
            $this->db->select('s.*, k.tingkat, k.nama_kelas, 
                              COUNT(DISTINCT ah.id) as total_hadir,
                              SUM(CASE WHEN ah.keterangan_terlambat IS NOT NULL AND ah.keterangan_terlambat != "" THEN 1 ELSE 0 END) as total_terlambat');
            $this->db->from('siswa s');
            $this->db->join('kelas k', 'k.id = s.kelas_id', 'left');
            $this->db->join('absensi_harian ah', 'ah.siswa_id = s.id AND ah.status = "masuk"', 'left');
            $this->db->where('ah.tanggal >=', $semester->tanggal_mulai);
            $this->db->where('ah.tanggal <=', $semester->tanggal_selesai);
            $this->db->group_by('s.id');
            $this->db->order_by('k.tingkat, k.nama_kelas, s.nama_lengkap');
        } else {
            $this->db->select('g.*, 
                              COUNT(DISTINCT ah.id) as total_hadir,
                              SUM(CASE WHEN ah.keterangan_terlambat IS NOT NULL AND ah.keterangan_terlambat != "" THEN 1 ELSE 0 END) as total_terlambat');
            $this->db->from('guru g');
            $this->db->join('absensi_harian ah', 'ah.guru_id = g.id AND ah.status = "masuk"', 'left');
            $this->db->where('ah.tanggal >=', $semester->tanggal_mulai);
            $this->db->where('ah.tanggal <=', $semester->tanggal_selesai);
            $this->db->group_by('g.id');
            $this->db->order_by('g.nama_lengkap');
        }
        
        return $this->db->get()->result();
    }
}
