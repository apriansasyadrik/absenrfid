<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Absensi Model
 * Handle attendance database operations
 */
class Absensi_model extends CI_Model {

    /**
     * Insert new attendance
     */
    public function insert($data) {
        return $this->db->insert('absensi_harian', $data);
    }

    /**
     * Get today's attendance for specific user
     */
    public function get_today_absensi($jenis, $user_id, $tanggal = null) {
        if (!$tanggal) {
            $tanggal = date('Y-m-d');
        }

        $this->db->where('jenis', $jenis);
        $this->db->where('user_id', $user_id);
        $this->db->where('tanggal', $tanggal);
        $query = $this->db->get('absensi_harian');

        return $query->row_array();
    }

    /**
     * Update check-out time
     */
    public function update_pulang($id, $waktu_pulang) {
        $data = array(
            'waktu_pulang' => $waktu_pulang
        );

        $this->db->where('id', $id);
        return $this->db->update('absensi_harian', $data);
    }

    /**
     * Get jam kerja for specific date
     */
    public function get_jam_kerja($tanggal) {
        $hari = format_hari($tanggal);
        
        $this->db->where('hari', $hari);
        $this->db->where('is_kerja', 1);
        $query = $this->db->get('jam_kerja');

        if ($query->num_rows() > 0) {
            return $query->row_array();
        }

        return false;
    }

    /**
     * Count attendance today by type
     */
    public function count_today($jenis) {
        $this->db->where('jenis', $jenis);
        $this->db->where('tanggal', date('Y-m-d'));
        return $this->db->count_all_results('absensi_harian');
    }

    /**
     * Get weekly attendance data for charts
     */
    public function get_weekly_data($jenis) {
        $data = array();
        
        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));
            
            $this->db->where('jenis', $jenis);
            $this->db->where('tanggal', $date);
            $count = $this->db->count_all_results('absensi_harian');
            
            $data[] = array(
                'tanggal' => format_tanggal($date, 'short'),
                'jumlah' => $count
            );
        }
        
        return $data;
    }

    /**
     * Get recent activities
     */
    public function get_recent_activities($limit = 10) {
        $this->db->select('absensi_harian.*, 
                          CASE 
                            WHEN absensi_harian.jenis = "siswa" THEN siswa.nama 
                            WHEN absensi_harian.jenis = "guru" THEN guru.nama 
                          END as nama,
                          CASE 
                            WHEN absensi_harian.jenis = "siswa" THEN siswa.foto 
                            WHEN absensi_harian.jenis = "guru" THEN guru.foto 
                          END as foto,
                          kelas.nama_kelas');
        $this->db->from('absensi_harian');
        $this->db->join('siswa', 'siswa.id = absensi_harian.user_id AND absensi_harian.jenis = "siswa"', 'left');
        $this->db->join('guru', 'guru.id = absensi_harian.user_id AND absensi_harian.jenis = "guru"', 'left');
        $this->db->join('kelas', 'kelas.id = siswa.kelas_id', 'left');
        $this->db->where('absensi_harian.tanggal', date('Y-m-d'));
        $this->db->order_by('absensi_harian.waktu_masuk', 'DESC');
        $this->db->limit($limit);
        
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * Get latest attendance for real-time display
     */
    public function get_latest_today($limit = 10) {
        $this->db->select('absensi_harian.*, 
                          CASE 
                            WHEN absensi_harian.jenis = "siswa" THEN siswa.nama 
                            WHEN absensi_harian.jenis = "guru" THEN guru.nama 
                          END as nama,
                          CASE 
                            WHEN absensi_harian.jenis = "siswa" THEN siswa.foto 
                            WHEN absensi_harian.jenis = "guru" THEN guru.foto 
                          END as foto,
                          kelas.nama_kelas,
                          guru.jabatan');
        $this->db->from('absensi_harian');
        $this->db->join('siswa', 'siswa.id = absensi_harian.user_id AND absensi_harian.jenis = "siswa"', 'left');
        $this->db->join('guru', 'guru.id = absensi_harian.user_id AND absensi_harian.jenis = "guru"', 'left');
        $this->db->join('kelas', 'kelas.id = siswa.kelas_id', 'left');
        $this->db->where('absensi_harian.tanggal', date('Y-m-d'));
        $this->db->order_by('absensi_harian.created_at', 'DESC');
        $this->db->limit($limit);
        
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * Get attendance by date range
     */
    public function get_by_date_range($jenis, $start_date, $end_date, $user_id = null) {
        $this->db->select('absensi_harian.*, 
                          CASE 
                            WHEN absensi_harian.jenis = "siswa" THEN siswa.nama 
                            WHEN absensi_harian.jenis = "guru" THEN guru.nama 
                          END as nama,
                          kelas.nama_kelas');
        $this->db->from('absensi_harian');
        $this->db->join('siswa', 'siswa.id = absensi_harian.user_id AND absensi_harian.jenis = "siswa"', 'left');
        $this->db->join('guru', 'guru.id = absensi_harian.user_id AND absensi_harian.jenis = "guru"', 'left');
        $this->db->join('kelas', 'kelas.id = siswa.kelas_id', 'left');
        $this->db->where('absensi_harian.jenis', $jenis);
        $this->db->where('absensi_harian.tanggal >=', $start_date);
        $this->db->where('absensi_harian.tanggal <=', $end_date);
        
        if ($user_id) {
            $this->db->where('absensi_harian.user_id', $user_id);
        }
        
        $this->db->order_by('absensi_harian.tanggal', 'DESC');
        $this->db->order_by('absensi_harian.waktu_masuk', 'ASC');
        
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * Get students who haven't checked in
     */
    public function get_belum_absen($tanggal = null) {
        if (!$tanggal) {
            $tanggal = date('Y-m-d');
        }

        $this->db->select('siswa.*, kelas.nama_kelas');
        $this->db->from('siswa');
        $this->db->join('kelas', 'kelas.id = siswa.kelas_id', 'left');
        $this->db->where('siswa.is_active', 1);
        $this->db->where('siswa.id NOT IN (
            SELECT user_id FROM absensi_harian 
            WHERE jenis = "siswa" AND tanggal = "' . $tanggal . '"
        )');
        
        $query = $this->db->get();
        return $query->result_array();
    }
}
