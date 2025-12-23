<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * TahunAjaran Model
 * Handle academic year database operations
 */
class TahunAjaran_model extends CI_Model {

    /**
     * Get all academic years
     */
    public function get_all() {
        $this->db->order_by('tahun_ajaran', 'DESC');
        $query = $this->db->get('tahun_ajaran');
        return $query->result_array();
    }

    /**
     * Get academic year by ID
     */
    public function get_by_id($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('tahun_ajaran');
        return $query->row_array();
    }

    /**
     * Get active academic year
     */
    public function get_active() {
        $this->db->where('is_active', 1);
        $this->db->limit(1);
        $query = $this->db->get('tahun_ajaran');
        return $query->row_array();
    }

    /**
     * Insert new academic year
     */
    public function insert($data) {
        return $this->db->insert('tahun_ajaran', $data);
    }

    /**
     * Update academic year
     */
    public function update($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('tahun_ajaran', $data);
    }

    /**
     * Delete academic year
     */
    public function delete($id) {
        $this->db->where('id', $id);
        return $this->db->delete('tahun_ajaran');
    }

    /**
     * Set active academic year
     */
    public function set_active($id) {
        // Deactivate all
        $this->db->update('tahun_ajaran', array('is_active' => 0));
        
        // Activate selected
        $this->db->where('id', $id);
        return $this->db->update('tahun_ajaran', array('is_active' => 1));
    }

    /**
     * Get for dropdown
     */
    public function get_dropdown() {
        $this->db->select('id, tahun_ajaran');
        $this->db->order_by('tahun_ajaran', 'DESC');
        $query = $this->db->get('tahun_ajaran');
        
        $result = array();
        foreach ($query->result_array() as $row) {
            $result[$row['id']] = $row['tahun_ajaran'];
        }
        
        return $result;
    }
}
