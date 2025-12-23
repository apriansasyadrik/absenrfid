<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Guru Model
 * Handle teacher database operations
 */
class Guru_model extends CI_Model {

    /**
     * Get all teachers
     */
    public function get_all($limit = null, $offset = null, $search = null) {
        $this->db->select('guru.*');
        $this->db->from('guru');
        
        if ($search) {
            $this->db->group_start();
            $this->db->like('guru.nama', $search);
            $this->db->or_like('guru.nip', $search);
            $this->db->or_like('guru.jabatan', $search);
            $this->db->group_end();
        }
        
        $this->db->order_by('guru.nama', 'ASC');
        
        if ($limit) {
            $this->db->limit($limit, $offset);
        }
        
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * Count all teachers
     */
    public function count_all($search = null) {
        if ($search) {
            $this->db->group_start();
            $this->db->like('nama', $search);
            $this->db->or_like('nip', $search);
            $this->db->or_like('jabatan', $search);
            $this->db->group_end();
        }
        
        return $this->db->count_all_results('guru');
    }

    /**
     * Get teacher by ID
     */
    public function get_by_id($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('guru');
        return $query->row_array();
    }

    /**
     * Get teacher by RFID UID
     */
    public function get_by_rfid($rfid_uid) {
        $this->db->where('rfid_uid', $rfid_uid);
        $this->db->where('is_active', 1);
        $query = $this->db->get('guru');
        return $query->row_array();
    }

    /**
     * Insert new teacher
     */
    public function insert($data) {
        return $this->db->insert('guru', $data);
    }

    /**
     * Update teacher
     */
    public function update($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('guru', $data);
    }

    /**
     * Delete teacher
     */
    public function delete($id) {
        $this->db->where('id', $id);
        return $this->db->delete('guru');
    }

    /**
     * Check if NIP exists
     */
    public function is_nip_exists($nip, $exclude_id = null) {
        $this->db->where('nip', $nip);
        
        if ($exclude_id) {
            $this->db->where('id !=', $exclude_id);
        }
        
        $query = $this->db->get('guru');
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
        
        $query = $this->db->get('guru');
        return $query->num_rows() > 0;
    }

    /**
     * Bulk insert for import
     */
    public function bulk_insert($data) {
        return $this->db->insert_batch('guru', $data);
    }

    /**
     * Get active teachers for dropdown
     */
    public function get_active_dropdown() {
        $this->db->select('id, nama');
        $this->db->where('is_active', 1);
        $this->db->order_by('nama', 'ASC');
        $query = $this->db->get('guru');
        
        $result = array();
        foreach ($query->result_array() as $row) {
            $result[$row['id']] = $row['nama'];
        }
        
        return $result;
    }
}
