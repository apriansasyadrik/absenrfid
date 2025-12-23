<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mapel_model extends CI_Model {

    public function get_all()
    {
        $this->db->select('mata_pelajaran.*, guru.nama_lengkap as nama_guru');
        $this->db->from('mata_pelajaran');
        $this->db->join('guru', 'mata_pelajaran.guru_id = guru.id', 'left');
        $this->db->order_by('mata_pelajaran.nama_mapel', 'ASC');
        return $this->db->get()->result();
    }

    public function get_by_id($id)
    {
        return $this->db->where('id', $id)->get('mata_pelajaran')->row();
    }

    public function insert($data)
    {
        return $this->db->insert('mata_pelajaran', $data);
    }

    public function update($id, $data)
    {
        return $this->db->where('id', $id)->update('mata_pelajaran', $data);
    }

    public function delete($id)
    {
        return $this->db->where('id', $id)->delete('mata_pelajaran');
    }

    public function get_dropdown()
    {
        $this->db->select('id, nama_mapel');
        $this->db->order_by('nama_mapel', 'ASC');
        $result = $this->db->get('mata_pelajaran')->result();
        
        $dropdown = [];
        foreach ($result as $row) {
            $dropdown[$row->id] = $row->nama_mapel;
        }
        return $dropdown;
    }

    public function check_usage($id)
    {
        $this->db->where('mapel_id', $id);
        return $this->db->count_all_results('jadwal_pelajaran');
    }
}
