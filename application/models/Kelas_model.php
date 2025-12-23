<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Kelas Model
 * Handle class database operations
 */
class Kelas_model extends CI_Model {

    /**
     * Get all classes
     */
    public function get_all() {
        $this->db->select('kelas.*, guru.nama as nama_walikelas, tahun_ajaran.tahun_ajaran');
        $this->db->from('kelas');
        $this->db->join('guru', 'guru.id = kelas.walikelas_id', 'left');
        $this->db->join('tahun_ajaran', 'tahun_ajaran.id = kelas.tahun_ajaran_id');
        $this->db->order_by('kelas.tingkat', 'ASC');
        $this->db->order_by('kelas.nama_kelas', 'ASC');
        
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * Get class by ID
     */
    public function get_by_id($id) {
        $this->db->select('kelas.*, guru.nama as nama_walikelas, tahun_ajaran.tahun_ajaran');
        $this->db->from('kelas');
        $this->db->join('guru', 'guru.id = kelas.walikelas_id', 'left');
        $this->db->join('tahun_ajaran', 'tahun_ajaran.id = kelas.tahun_ajaran_id');
        $this->db->where('kelas.id', $id);
        
        $query = $this->db->get();
        return $query->row_array();
    }

    /**
     * Insert new class
     */
    public function insert($data) {
        return $this->db->insert('kelas', $data);
    }

    /**
     * Update class
     */
    public function update($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('kelas', $data);
    }

    /**
     * Delete class
     */
    public function delete($id) {
        $this->db->where('id', $id);
        return $this->db->delete('kelas');
    }

    /**
     * Get classes for dropdown
     */
    public function get_dropdown() {
        $this->db->select('id, nama_kelas');
        $this->db->order_by('tingkat', 'ASC');
        $this->db->order_by('nama_kelas', 'ASC');
        $query = $this->db->get('kelas');
        
        $result = array();
        foreach ($query->result_array() as $row) {
            $result[$row['id']] = $row['nama_kelas'];
        }
        
        return $result;
    }

    /**
     * Get active academic year classes
     */
    public function get_active_year() {
        $this->db->select('kelas.*, guru.nama as nama_walikelas');
        $this->db->from('kelas');
        $this->db->join('guru', 'guru.id = kelas.walikelas_id', 'left');
        $this->db->join('tahun_ajaran', 'tahun_ajaran.id = kelas.tahun_ajaran_id');
        $this->db->where('tahun_ajaran.is_active', 1);
        $this->db->order_by('kelas.tingkat', 'ASC');
        $this->db->order_by('kelas.nama_kelas', 'ASC');
        
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * Get class by walikelas (guru_id)
     */
    public function get_by_walikelas($guru_id) {
        $this->db->select('kelas.*, guru.nama as nama_walikelas, tahun_ajaran.tahun_ajaran');
        $this->db->from('kelas');
        $this->db->join('guru', 'guru.id = kelas.walikelas_id', 'left');
        $this->db->join('tahun_ajaran', 'tahun_ajaran.id = kelas.tahun_ajaran_id');
        $this->db->where('kelas.walikelas_id', $guru_id);
        
        $query = $this->db->get();
        return $query->row();
    }
}
