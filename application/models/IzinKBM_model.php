<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class IzinKBM_model extends CI_Model {

    /**
     * Get izin KBM records for today
     */
    public function get_izin_hari_ini() {
        return $this->db
            ->select('i.*, s.nama_lengkap, s.nis, k.nama_kelas, k.tingkat, g.nama as nama_guru')
            ->from('izin_kbm i')
            ->join('siswa s', 's.id = i.siswa_id')
            ->join('kelas k', 'k.id = s.kelas_id')
            ->join('guru g', 'g.id = i.guru_piket_id')
            ->where('i.tanggal', date('Y-m-d'))
            ->order_by('i.created_at', 'DESC')
            ->get()->result();
    }

    /**
     * Get izin KBM by date
     */
    public function get_izin_by_tanggal($tanggal) {
        return $this->db
            ->select('i.*, s.nama_lengkap, s.nis, k.nama_kelas, k.tingkat, g.nama as nama_guru')
            ->from('izin_kbm i')
            ->join('siswa s', 's.id = i.siswa_id')
            ->join('kelas k', 'k.id = s.kelas_id')
            ->join('guru g', 'g.id = i.guru_piket_id')
            ->where('i.tanggal', $tanggal)
            ->order_by('i.jam_izin', 'DESC')
            ->get()->result();
    }

    /**
     * Get izin KBM by class
     */
    public function get_izin_by_kelas($kelas_id) {
        return $this->db
            ->select('i.*, s.nama_lengkap, s.nis, k.nama_kelas, k.tingkat, g.nama as nama_guru')
            ->from('izin_kbm i')
            ->join('siswa s', 's.id = i.siswa_id')
            ->join('kelas k', 'k.id = s.kelas_id')
            ->join('guru g', 'g.id = i.guru_piket_id')
            ->where('s.kelas_id', $kelas_id)
            ->order_by('i.tanggal', 'DESC')
            ->order_by('i.jam_izin', 'DESC')
            ->get()->result();
    }

    /**
     * Get single izin KBM record
     */
    public function get($id) {
        return $this->db
            ->select('i.*, s.nama_lengkap, s.nis, s.kelas_id, k.nama_kelas, k.tingkat')
            ->from('izin_kbm i')
            ->join('siswa s', 's.id = i.siswa_id')
            ->join('kelas k', 'k.id = s.kelas_id')
            ->where('i.id', $id)
            ->get()->row();
    }

    /**
     * Add izin KBM record
     */
    public function add_izin_kbm($data) {
        $this->db->insert('izin_kbm', $data);
        return $this->db->insert_id();
    }

    /**
     * Update izin KBM record
     */
    public function update($id, $data) {
        return $this->db->where('id', $id)->update('izin_kbm', $data);
    }

    /**
     * Delete izin KBM record
     */
    public function delete($id) {
        return $this->db->where('id', $id)->delete('izin_kbm');
    }

    /**
     * Get rekap izin KBM by month and year
     */
    public function get_rekap($bulan, $tahun) {
        return $this->db
            ->select('i.*, s.nama_lengkap, s.nis, k.nama_kelas, k.tingkat, g.nama as nama_guru')
            ->from('izin_kbm i')
            ->join('siswa s', 's.id = i.siswa_id')
            ->join('kelas k', 'k.id = s.kelas_id')
            ->join('guru g', 'g.id = i.guru_piket_id')
            ->where('MONTH(i.tanggal)', $bulan)
            ->where('YEAR(i.tanggal)', $tahun)
            ->order_by('i.tanggal', 'DESC')
            ->get()->result();
    }

    /**
     * Get rekap by date range
     */
    public function get_by_date_range($start_date, $end_date, $kelas_id = null) {
        $this->db
            ->select('i.*, s.nama_lengkap, s.nis, k.nama_kelas, k.tingkat, g.nama as nama_guru')
            ->from('izin_kbm i')
            ->join('siswa s', 's.id = i.siswa_id')
            ->join('kelas k', 'k.id = s.kelas_id')
            ->join('guru g', 'g.id = i.guru_piket_id')
            ->where('i.tanggal >=', $start_date)
            ->where('i.tanggal <=', $end_date);
        
        if ($kelas_id) {
            $this->db->where('s.kelas_id', $kelas_id);
        }
        
        return $this->db->order_by('i.tanggal', 'DESC')->get()->result();
    }

    /**
     * Count total izin by type in a month
     */
    public function count_by_type_month($bulan, $tahun) {
        return $this->db
            ->select('jenis, COUNT(*) as total')
            ->from('izin_kbm')
            ->where('MONTH(tanggal)', $bulan)
            ->where('YEAR(tanggal)', $tahun)
            ->group_by('jenis')
            ->get()->result();
    }

    /**
     * Count izin for a student in a month
     */
    public function count_by_student_month($siswa_id, $bulan, $tahun) {
        return $this->db
            ->from('izin_kbm')
            ->where('siswa_id', $siswa_id)
            ->where('MONTH(tanggal)', $bulan)
            ->where('YEAR(tanggal)', $tahun)
            ->count_all_results();
    }
}
