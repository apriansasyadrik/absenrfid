<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Siswa Model
 * Handle student database operations
 */
class Siswa_model extends CI_Model {

    /**
     * Get all students
     */
    public function get_all($limit = null, $offset = null, $search = null) {
        $this->db->select('siswa.*, kelas.nama_kelas');
        $this->db->from('siswa');
        $this->db->join('kelas', 'kelas.id = siswa.kelas_id', 'left');
        
        if ($search) {
            $this->db->group_start();
            $this->db->like('siswa.nama', $search);
            $this->db->or_like('siswa.nis', $search);
            $this->db->or_like('siswa.nisn', $search);
            $this->db->or_like('kelas.nama_kelas', $search);
            $this->db->group_end();
        }
        
        $this->db->order_by('siswa.nama', 'ASC');
        
        if ($limit) {
            $this->db->limit($limit, $offset);
        }
        
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * Count all students
     */
    public function count_all($search = null) {
        if ($search) {
            $this->db->join('kelas', 'kelas.id = siswa.kelas_id', 'left');
            $this->db->group_start();
            $this->db->like('siswa.nama', $search);
            $this->db->or_like('siswa.nis', $search);
            $this->db->or_like('siswa.nisn', $search);
            $this->db->or_like('kelas.nama_kelas', $search);
            $this->db->group_end();
        }
        
        return $this->db->count_all_results('siswa');
    }

    /**
     * Get student by ID
     */
    public function get_by_id($id) {
        $this->db->select('siswa.*, kelas.nama_kelas');
        $this->db->from('siswa');
        $this->db->join('kelas', 'kelas.id = siswa.kelas_id', 'left');
        $this->db->where('siswa.id', $id);
        
        $query = $this->db->get();
        return $query->row_array();
    }

    /**
     * Get student by RFID UID
     */
    public function get_by_rfid($rfid_uid) {
        $this->db->select('siswa.*, kelas.nama_kelas');
        $this->db->from('siswa');
        $this->db->join('kelas', 'kelas.id = siswa.kelas_id', 'left');
        $this->db->where('siswa.rfid_uid', $rfid_uid);
        $this->db->where('siswa.is_active', 1);
        
        $query = $this->db->get();
        return $query->row_array();
    }

    /**
     * Get students by class
     */
    public function get_by_kelas($kelas_id) {
        $this->db->select('siswa.*, kelas.nama_kelas');
        $this->db->from('siswa');
        $this->db->join('kelas', 'kelas.id = siswa.kelas_id', 'left');
        $this->db->where('siswa.kelas_id', $kelas_id);
        $this->db->where('siswa.is_active', 1);
        $this->db->order_by('siswa.nama', 'ASC');
        
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * Insert new student
     */
    public function insert($data) {
        return $this->db->insert('siswa', $data);
    }

    /**
     * Update student
     */
    public function update($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('siswa', $data);
    }

    /**
     * Delete student
     */
    public function delete($id) {
        $this->db->where('id', $id);
        return $this->db->delete('siswa');
    }

    /**
     * Check if NIS exists
     */
    public function is_nis_exists($nis, $exclude_id = null) {
        $this->db->where('nis', $nis);
        
        if ($exclude_id) {
            $this->db->where('id !=', $exclude_id);
        }
        
        $query = $this->db->get('siswa');
        return $query->num_rows() > 0;
    }

    /**
     * Check if RFID UID exists
     */
    public function is_rfid_exists($rfid_uid, $exclude_id = null) {
        $this->db->where('rfid_uid', $rfid_uid);
        
        if ($exclude_id) {
            $this->db->where('id !=', $exclude_id);
        }
        
        $query = $this->db->get('siswa');
        return $query->num_rows() > 0;
    }

    /**
     * Bulk insert for import
     */
    public function bulk_insert($data) {
        return $this->db->insert_batch('siswa', $data);
    }

    /**
     * Update class for multiple students (naik kelas)
     */
    public function bulk_update_kelas($siswa_ids, $new_kelas_id) {
        $this->db->where_in('id', $siswa_ids);
        return $this->db->update('siswa', array('kelas_id' => $new_kelas_id));
    }
}
