<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jurnal_model extends CI_Model {

    public function get_all() {
        return $this->db->get('jurnal_mengajar')->result();
    }

    public function get($id) {
        return $this->db->get_where('jurnal_mengajar', ['id' => $id])->row();
    }

    public function get_by_teacher($guru_id) {
        return $this->db
            ->select('j.*, jp.hari, jp.jam_mulai, jp.jam_selesai, k.tingkat, k.nama_kelas, mp.nama_mapel')
            ->from('jurnal_mengajar j')
            ->join('jadwal_pelajaran jp', 'jp.id = j.jadwal_id')
            ->join('kelas k', 'k.id = jp.kelas_id')
            ->join('mata_pelajaran mp', 'mp.id = jp.mapel_id')
            ->where('jp.guru_id', $guru_id)
            ->order_by('j.tanggal', 'DESC')
            ->get()->result();
    }

    public function get_by_teacher_month($guru_id, $tahun, $bulan) {
        return $this->db
            ->select('j.*, jp.hari, jp.jam_mulai, jp.jam_selesai, k.tingkat, k.nama_kelas, mp.nama_mapel')
            ->from('jurnal_mengajar j')
            ->join('jadwal_pelajaran jp', 'jp.id = j.jadwal_id')
            ->join('kelas k', 'k.id = jp.kelas_id')
            ->join('mata_pelajaran mp', 'mp.id = jp.mapel_id')
            ->where('jp.guru_id', $guru_id)
            ->where('YEAR(j.tanggal)', $tahun)
            ->where('MONTH(j.tanggal)', $bulan)
            ->order_by('j.tanggal', 'DESC')
            ->get()->result();
    }

    public function get_recent_by_teacher($guru_id, $limit = 5) {
        return $this->db
            ->select('j.*, jp.hari, jp.jam_mulai, jp.jam_selesai, k.tingkat, k.nama_kelas, mp.nama_mapel')
            ->from('jurnal_mengajar j')
            ->join('jadwal_pelajaran jp', 'jp.id = j.jadwal_id')
            ->join('kelas k', 'k.id = jp.kelas_id')
            ->join('mata_pelajaran mp', 'mp.id = jp.mapel_id')
            ->where('jp.guru_id', $guru_id)
            ->order_by('j.tanggal', 'DESC')
            ->limit($limit)
            ->get()->result();
    }

    public function count_by_teacher_month($guru_id, $tahun, $bulan) {
        return $this->db
            ->from('jurnal_mengajar j')
            ->join('jadwal_pelajaran jp', 'jp.id = j.jadwal_id')
            ->where('jp.guru_id', $guru_id)
            ->where('YEAR(j.tanggal)', $tahun)
            ->where('MONTH(j.tanggal)', $bulan)
            ->count_all_results();
    }

    public function count_hours_by_teacher_month($guru_id, $tahun, $bulan) {
        $result = $this->db
            ->select('SUM(TIMESTAMPDIFF(MINUTE, jp.jam_mulai, jp.jam_selesai) / 60) as total_jam')
            ->from('jurnal_mengajar j')
            ->join('jadwal_pelajaran jp', 'jp.id = j.jadwal_id')
            ->where('jp.guru_id', $guru_id)
            ->where('YEAR(j.tanggal)', $tahun)
            ->where('MONTH(j.tanggal)', $bulan)
            ->get()->row();
        
        return $result ? round($result->total_jam, 1) : 0;
    }

    public function insert($data) {
        $this->db->insert('jurnal_mengajar', $data);
        return $this->db->insert_id();
    }

    public function update($id, $data) {
        return $this->db->where('id', $id)->update('jurnal_mengajar', $data);
    }

    public function delete($id) {
        // Delete related attendance records first
        $this->db->where('jurnal_id', $id)->delete('absensi_mapel');
        return $this->db->where('id', $id)->delete('jurnal_mengajar');
    }
}
