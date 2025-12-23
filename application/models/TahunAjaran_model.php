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
        $this->db->order_by('tahun_mulai', 'DESC');
        $query = $this->db->get('tahun_ajaran');
        return $query->result();
    }

    /**
     * Get academic year by ID
     */
    public function get_by_id($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('tahun_ajaran');
        return $query->row();
    }

    /**
     * Get active academic year
     */
    public function get_active() {
        $this->db->where('is_active', 1);
        $this->db->limit(1);
        $query = $this->db->get('tahun_ajaran');
        return $query->row();
    }

    /**
     * Deactivate all academic years
     */
    public function deactivate_all() {
        return $this->db->update('tahun_ajaran', array('is_active' => 0));
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
        $this->db->select('id, nama_tahun');
        $this->db->order_by('tahun_mulai', 'DESC');
        $query = $this->db->get('tahun_ajaran');
        
        $result = array();
        foreach ($query->result() as $row) {
            $result[$row->id] = $row->nama_tahun;
        }
        
        return $result;
    }
}
