<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Auth Model
 * Handle authentication database operations
 */
class Auth_model extends CI_Model {

    /**
     * Check login credentials
     */
    public function check_login($username, $password) {
        $this->db->select('users.*, guru.nama');
        $this->db->from('users');
        $this->db->join('guru', 'guru.id = users.guru_id', 'left');
        $this->db->where('users.username', $username);
        $this->db->where('users.is_active', 1);
        $query = $this->db->get();

        if ($query->num_rows() == 1) {
            $user = $query->row_array();
            
            // Verify password
            if (password_verify($password, $user['password'])) {
                return $user;
            }
        }

        return false;
    }

    /**
     * Update last login time
     */
    public function update_last_login($user_id) {
        $data = array(
            'last_login' => date('Y-m-d H:i:s')
        );
        
        $this->db->where('id', $user_id);
        return $this->db->update('users', $data);
    }

    /**
     * Get user by ID
     */
    public function get_user_by_id($id) {
        $this->db->select('users.*, guru.nama, guru.foto, guru.email, guru.no_hp');
        $this->db->from('users');
        $this->db->join('guru', 'guru.id = users.guru_id', 'left');
        $this->db->where('users.id', $id);
        $query = $this->db->get();

        return $query->row_array();
    }

    /**
     * Change password
     */
    public function change_password($user_id, $new_password) {
        $data = array(
            'password' => password_hash($new_password, PASSWORD_BCRYPT)
        );
        
        $this->db->where('id', $user_id);
        return $this->db->update('users', $data);
    }

    /**
     * Create new user
     */
    public function create_user($data) {
        $user_data = array(
            'username' => $data['username'],
            'password' => password_hash($data['password'], PASSWORD_BCRYPT),
            'role' => $data['role'],
            'guru_id' => isset($data['guru_id']) ? $data['guru_id'] : NULL,
            'is_active' => 1
        );
        
        return $this->db->insert('users', $user_data);
    }

    /**
     * Update user
     */
    public function update_user($id, $data) {
        $user_data = array(
            'username' => $data['username'],
            'role' => $data['role'],
            'guru_id' => isset($data['guru_id']) ? $data['guru_id'] : NULL,
            'is_active' => $data['is_active']
        );
        
        if (isset($data['password']) && !empty($data['password'])) {
            $user_data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        }
        
        $this->db->where('id', $id);
        return $this->db->update('users', $user_data);
    }

    /**
     * Delete user
     */
    public function delete_user($id) {
        $this->db->where('id', $id);
        return $this->db->delete('users');
    }

    /**
     * Get all users
     */
    public function get_all_users() {
        $this->db->select('users.*, guru.nama');
        $this->db->from('users');
        $this->db->join('guru', 'guru.id = users.guru_id', 'left');
        $this->db->order_by('users.id', 'ASC');
        $query = $this->db->get();

        return $query->result_array();
    }
}
