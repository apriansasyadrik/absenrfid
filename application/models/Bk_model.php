<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bk_model extends CI_Model {

    /**
     * Get siswa bermasalah based on alpha >= 3 or terlambat >= 5 in a month
     */
    public function get_siswa_bermasalah($bulan, $tahun) {
        // Get students with alpha >= 3
        $alpha_query = "
            SELECT s.id, s.nama_lengkap, s.nis, s.kelas_id, k.nama_kelas, k.tingkat,
                   COUNT(CASE WHEN am.status = 'A' THEN 1 END) as total_alpha,
                   0 as total_terlambat,
                   'alpha' as jenis_masalah
            FROM siswa s
            JOIN kelas k ON s.kelas_id = k.id
            LEFT JOIN absensi_mapel am ON s.id = am.siswa_id
            WHERE MONTH(am.created_at) = ? AND YEAR(am.created_at) = ?
            GROUP BY s.id
            HAVING total_alpha >= 3
        ";
        
        // Get students with terlambat >= 5
        $terlambat_query = "
            SELECT s.id, s.nama_lengkap, s.nis, s.kelas_id, k.nama_kelas, k.tingkat,
                   0 as total_alpha,
                   COUNT(CASE WHEN ah.status_masuk = 'terlambat' THEN 1 END) as total_terlambat,
                   'terlambat' as jenis_masalah
            FROM siswa s
            JOIN kelas k ON s.kelas_id = k.id
            LEFT JOIN absensi_harian ah ON s.id = ah.siswa_id
            WHERE MONTH(ah.tanggal) = ? AND YEAR(ah.tanggal) = ?
            GROUP BY s.id
            HAVING total_terlambat >= 5
        ";
        
        // Combine both queries
        $query = "
            SELECT DISTINCT s.id, s.nama_lengkap, s.nis, s.kelas_id, k.nama_kelas, k.tingkat,
                   COALESCE(alpha_data.total_alpha, 0) as total_alpha,
                   COALESCE(terlambat_data.total_terlambat, 0) as total_terlambat
            FROM siswa s
            JOIN kelas k ON s.kelas_id = k.id
            LEFT JOIN (
                SELECT siswa_id, COUNT(*) as total_alpha
                FROM absensi_mapel
                WHERE status = 'A' AND MONTH(created_at) = ? AND YEAR(created_at) = ?
                GROUP BY siswa_id
                HAVING COUNT(*) >= 3
            ) alpha_data ON s.id = alpha_data.siswa_id
            LEFT JOIN (
                SELECT siswa_id, COUNT(*) as total_terlambat
                FROM absensi_harian
                WHERE status_masuk = 'terlambat' AND MONTH(tanggal) = ? AND YEAR(tanggal) = ?
                GROUP BY siswa_id
                HAVING COUNT(*) >= 5
            ) terlambat_data ON s.id = terlambat_data.siswa_id
            WHERE alpha_data.siswa_id IS NOT NULL OR terlambat_data.siswa_id IS NOT NULL
            ORDER BY k.tingkat, k.nama_kelas, s.nama_lengkap
        ";
        
        return $this->db->query($query, [$bulan, $tahun, $bulan, $tahun])->result();
    }

    /**
     * Get detail pelanggaran for a student
     */
    public function get_detail_pelanggaran($siswa_id, $bulan, $tahun) {
        // Get alpha records
        $alpha = $this->db
            ->select('am.*, jp.hari, jp.jam_mulai, mp.nama_mapel')
            ->from('absensi_mapel am')
            ->join('jurnal_mengajar jm', 'jm.id = am.jurnal_id')
            ->join('jadwal_pelajaran jp', 'jp.id = jm.jadwal_id')
            ->join('mata_pelajaran mp', 'mp.id = jp.mapel_id')
            ->where('am.siswa_id', $siswa_id)
            ->where('am.status', 'A')
            ->where('MONTH(am.created_at)', $bulan)
            ->where('YEAR(am.created_at)', $tahun)
            ->order_by('am.created_at', 'DESC')
            ->get()->result();
        
        // Get terlambat records
        $terlambat = $this->db
            ->select('*')
            ->from('absensi_harian')
            ->where('siswa_id', $siswa_id)
            ->where('status_masuk', 'terlambat')
            ->where('MONTH(tanggal)', $bulan)
            ->where('YEAR(tanggal)', $tahun)
            ->order_by('tanggal', 'DESC')
            ->get()->result();
        
        return [
            'alpha' => $alpha,
            'terlambat' => $terlambat
        ];
    }

    /**
     * Get riwayat alpha for a student in a month
     */
    public function get_riwayat_alpha($siswa_id, $bulan, $tahun) {
        return $this->db
            ->select('am.*, jm.tanggal, jp.hari, jp.jam_mulai, mp.nama_mapel, k.nama_kelas')
            ->from('absensi_mapel am')
            ->join('jurnal_mengajar jm', 'jm.id = am.jurnal_id')
            ->join('jadwal_pelajaran jp', 'jp.id = jm.jadwal_id')
            ->join('mata_pelajaran mp', 'mp.id = jp.mapel_id')
            ->join('kelas k', 'k.id = jp.kelas_id')
            ->where('am.siswa_id', $siswa_id)
            ->where('am.status', 'A')
            ->where('MONTH(jm.tanggal)', $bulan)
            ->where('YEAR(jm.tanggal)', $tahun)
            ->order_by('jm.tanggal', 'DESC')
            ->get()->result();
    }

    /**
     * Get riwayat terlambat for a student in a month
     */
    public function get_riwayat_terlambat($siswa_id, $bulan, $tahun) {
        return $this->db
            ->select('*')
            ->from('absensi_harian')
            ->where('siswa_id', $siswa_id)
            ->where('status_masuk', 'terlambat')
            ->where('MONTH(tanggal)', $bulan)
            ->where('YEAR(tanggal)', $tahun)
            ->order_by('tanggal', 'DESC')
            ->get()->result();
    }

    /**
     * Add catatan BK for a student
     */
    public function add_catatan($siswa_id, $catatan, $user_id) {
        $data = [
            'siswa_id' => $siswa_id,
            'bulan' => date('n'),
            'tahun' => date('Y'),
            'total_alpha' => 0,
            'total_terlambat' => 0,
            'catatan' => $catatan,
            'created_by' => $user_id
        ];
        
        // Check if record exists
        $existing = $this->db
            ->where('siswa_id', $siswa_id)
            ->where('bulan', date('n'))
            ->where('tahun', date('Y'))
            ->get('monitoring_bk')->row();
        
        if ($existing) {
            return $this->db
                ->where('id', $existing->id)
                ->update('monitoring_bk', [
                    'catatan' => $catatan,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
        } else {
            $this->db->insert('monitoring_bk', $data);
            return $this->db->insert_id();
        }
    }

    /**
     * Get catatan BK for a student
     */
    public function get_catatan($siswa_id, $bulan, $tahun) {
        return $this->db
            ->select('mb.*, u.nama as created_by_name')
            ->from('monitoring_bk mb')
            ->join('users u', 'u.id = mb.created_by', 'left')
            ->where('mb.siswa_id', $siswa_id)
            ->where('mb.bulan', $bulan)
            ->where('mb.tahun', $tahun)
            ->get()->row();
    }

    /**
     * Get surat list
     */
    public function get_surat_list() {
        return $this->db
            ->select('sp.*, s.nama_lengkap, s.nis, k.nama_kelas')
            ->from('surat_panggilan sp')
            ->join('siswa s', 's.id = sp.siswa_id')
            ->join('kelas k', 'k.id = s.kelas_id')
            ->order_by('sp.tanggal_surat', 'DESC')
            ->get()->result();
    }

    /**
     * Create surat panggilan
     */
    public function create_surat($data) {
        $this->db->insert('surat_panggilan', $data);
        return $this->db->insert_id();
    }

    /**
     * Update surat panggilan
     */
    public function update_surat($id, $data) {
        return $this->db->where('id', $id)->update('surat_panggilan', $data);
    }

    /**
     * Get surat by ID
     */
    public function get_surat_by_id($id) {
        return $this->db
            ->select('sp.*, s.nama_lengkap, s.nis, k.nama_kelas, k.tingkat')
            ->from('surat_panggilan sp')
            ->join('siswa s', 's.id = sp.siswa_id')
            ->join('kelas k', 'k.id = s.kelas_id')
            ->where('sp.id', $id)
            ->get()->row();
    }

    /**
     * Delete surat
     */
    public function delete_surat($id) {
        return $this->db->where('id', $id)->delete('surat_panggilan');
    }

    /**
     * Get statistics for dashboard
     */
    public function get_statistics($bulan, $tahun) {
        $total_bermasalah = $this->db
            ->from('monitoring_bk')
            ->where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->count_all_results();
        
        $total_surat = $this->db
            ->from('surat_panggilan')
            ->where('MONTH(tanggal_surat)', $bulan)
            ->where('YEAR(tanggal_surat)', $tahun)
            ->count_all_results();
        
        return [
            'total_bermasalah' => $total_bermasalah,
            'total_surat' => $total_surat
        ];
    }
}
