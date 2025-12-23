<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jadwal_model extends CI_Model {

    protected $table = 'jadwal_pelajaran';

    public function get_all($semester_id = null, $kelas_id = null, $hari = null) {
        $this->db->select('jp.*, 
            s.nama as semester_nama,
            CONCAT(k.tingkat, " ", k.nama_kelas) as kelas_nama,
            mp.nama_mapel as mapel_nama,
            g.nama as guru_nama');
        $this->db->from($this->table . ' jp');
        $this->db->join('semester s', 's.id = jp.semester_id', 'left');
        $this->db->join('kelas k', 'k.id = jp.kelas_id', 'left');
        $this->db->join('mata_pelajaran mp', 'mp.id = jp.mapel_id', 'left');
        $this->db->join('guru g', 'g.id = jp.guru_id', 'left');
        
        if ($semester_id) {
            $this->db->where('jp.semester_id', $semester_id);
        }
        if ($kelas_id) {
            $this->db->where('jp.kelas_id', $kelas_id);
        }
        if ($hari) {
            $this->db->where('jp.hari', $hari);
        }
        
        $this->db->order_by('jp.hari', 'ASC');
        $this->db->order_by('jp.jam_mulai', 'ASC');
        
        return $this->db->get()->result();
    }

    public function get_by_id($id) {
        $this->db->select('jp.*, 
            s.nama as semester_nama,
            CONCAT(k.tingkat, " ", k.nama_kelas) as kelas_nama,
            mp.nama_mapel as mapel_nama,
            g.nama as guru_nama');
        $this->db->from($this->table . ' jp');
        $this->db->join('semester s', 's.id = jp.semester_id', 'left');
        $this->db->join('kelas k', 'k.id = jp.kelas_id', 'left');
        $this->db->join('mata_pelajaran mp', 'mp.id = jp.mapel_id', 'left');
        $this->db->join('guru g', 'g.id = jp.guru_id', 'left');
        $this->db->where('jp.id', $id);
        return $this->db->get()->row();
    }

    public function insert($data) {
        return $this->db->insert($this->table, $data);
    }

    public function update($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }

    public function delete($id) {
        $this->db->where('id', $id);
        return $this->db->delete($this->table);
    }

    public function check_conflict($guru_id, $kelas_id, $hari, $jam_mulai, $jam_selesai, $exclude_id = null) {
        // Check teacher conflict
        $this->db->where('guru_id', $guru_id);
        $this->db->where('hari', $hari);
        $this->db->group_start();
            $this->db->group_start();
                $this->db->where('jam_mulai <', $jam_selesai);
                $this->db->where('jam_selesai >', $jam_mulai);
            $this->db->group_end();
        $this->db->group_end();
        
        if ($exclude_id) {
            $this->db->where('id !=', $exclude_id);
        }
        
        $teacher_conflict = $this->db->get($this->table)->row();
        
        if ($teacher_conflict) {
            return 'Guru ini sudah mengajar di kelas lain pada waktu yang sama';
        }

        // Check class conflict
        $this->db->where('kelas_id', $kelas_id);
        $this->db->where('hari', $hari);
        $this->db->group_start();
            $this->db->group_start();
                $this->db->where('jam_mulai <', $jam_selesai);
                $this->db->where('jam_selesai >', $jam_mulai);
            $this->db->group_end();
        $this->db->group_end();
        
        if ($exclude_id) {
            $this->db->where('id !=', $exclude_id);
        }
        
        $class_conflict = $this->db->get($this->table)->row();
        
        if ($class_conflict) {
            return 'Kelas ini sudah memiliki jadwal lain pada waktu yang sama';
        }

        return false;
    }

    public function get_by_class($kelas_id, $semester_id = null) {
        $this->db->select('jp.*, 
            mp.nama_mapel as mapel_nama,
            g.nama as guru_nama');
        $this->db->from($this->table . ' jp');
        $this->db->join('mata_pelajaran mp', 'mp.id = jp.mapel_id', 'left');
        $this->db->join('guru g', 'g.id = jp.guru_id', 'left');
        $this->db->where('jp.kelas_id', $kelas_id);
        
        if ($semester_id) {
            $this->db->where('jp.semester_id', $semester_id);
        }
        
        $this->db->order_by('jp.hari', 'ASC');
        $this->db->order_by('jp.jam_mulai', 'ASC');
        
        return $this->db->get()->result();
    }

    public function get_by_teacher($guru_id, $semester_id = null) {
        $this->db->select('jp.*, 
            CONCAT(k.tingkat, " ", k.nama_kelas) as kelas_nama,
            mp.nama_mapel as mapel_nama');
        $this->db->from($this->table . ' jp');
        $this->db->join('kelas k', 'k.id = jp.kelas_id', 'left');
        $this->db->join('mata_pelajaran mp', 'mp.id = jp.mapel_id', 'left');
        $this->db->where('jp.guru_id', $guru_id);
        
        if ($semester_id) {
            $this->db->where('jp.semester_id', $semester_id);
        }
        
        $this->db->order_by('jp.hari', 'ASC');
        $this->db->order_by('jp.jam_mulai', 'ASC');
        
        return $this->db->get()->result();
    }
}
