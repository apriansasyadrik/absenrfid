<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Settings Model
 * Handle school settings
 */
class Settings_model extends CI_Model {

    /**
     * Get school settings
     */
    public function get_settings() {
        $this->db->limit(1);
        $query = $this->db->get('settings');
        return $query->row_array();
    }

    /**
     * Update settings
     */
    public function update_settings($data) {
        $this->db->where('id', 1);
        return $this->db->update('settings', $data);
    }

    /**
     * Update logo
     */
    public function update_logo($logo_filename) {
        $data = array('logo' => $logo_filename);
        $this->db->where('id', 1);
        return $this->db->update('settings', $data);
    }
}
