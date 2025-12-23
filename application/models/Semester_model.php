<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Semester_model extends CI_Model {

    public function get_all()
    {
        return $this->db->order_by('tahun_ajaran_id', 'DESC')
                        ->order_by('nama_semester', 'ASC')
                        ->get('semester')
                        ->result();
    }

    public function get_all_with_tahun()
    {
        $this->db->select('semester.*, tahun_ajaran.nama_tahun');
        $this->db->from('semester');
        $this->db->join('tahun_ajaran', 'semester.tahun_ajaran_id = tahun_ajaran.id');
        $this->db->order_by('tahun_ajaran.tahun_mulai', 'DESC');
        $this->db->order_by('semester.nama_semester', 'ASC');
        return $this->db->get()->result();
    }

    public function get_by_id($id)
    {
        return $this->db->where('id', $id)->get('semester')->row();
    }

    public function get_active()
    {
        return $this->db->where('is_active', 1)->get('semester')->row();
    }

    public function insert($data)
    {
        return $this->db->insert('semester', $data);
    }

    public function update($id, $data)
    {
        return $this->db->where('id', $id)->update('semester', $data);
    }

    public function delete($id)
    {
        return $this->db->where('id', $id)->delete('semester');
    }

    public function deactivate_all()
    {
        return $this->db->update('semester', ['is_active' => 0]);
    }

    public function get_dropdown()
    {
        $result = $this->get_all_with_tahun();
        $dropdown = [];
        foreach ($result as $row) {
            $dropdown[$row->id] = $row->nama_tahun . ' - ' . $row->nama_semester;
        }
        return $dropdown;
    }
}
