<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * WhatsApp Model
 * Handle WhatsApp queue and notifications
 */
class Wa_model extends CI_Model {

    /**
     * Add message to queue
     */
    public function add_to_queue($no_hp, $pesan) {
        $data = array(
            'no_hp' => $no_hp,
            'pesan' => $pesan,
            'status' => 'pending',
            'attempt' => 0,
            'max_attempt' => 3
        );

        return $this->db->insert('wa_queue', $data);
    }

    /**
     * Get pending messages from queue
     */
    public function get_pending_queue($limit = 10) {
        $this->db->where('status', 'pending');
        $this->db->where('attempt <', 3);
        $this->db->order_by('created_at', 'ASC');
        $this->db->limit($limit);
        
        $query = $this->db->get('wa_queue');
        return $query->result_array();
    }

    /**
     * Update queue status
     */
    public function update_queue_status($id, $status, $response = null) {
        $data = array(
            'status' => $status,
            'response' => $response
        );

        if ($status == 'sent') {
            $data['sent_at'] = date('Y-m-d H:i:s');
        }

        $this->db->where('id', $id);
        return $this->db->update('wa_queue', $data);
    }

    /**
     * Increment attempt count
     */
    public function increment_attempt($id) {
        $this->db->set('attempt', 'attempt + 1', FALSE);
        $this->db->where('id', $id);
        return $this->db->update('wa_queue');
    }

    /**
     * Get WhatsApp settings
     */
    public function get_settings() {
        $this->db->limit(1);
        $query = $this->db->get('wa_settings');

        return $query->row();
    }

    /**
     * Update WhatsApp settings
     */
    public function update_settings($data) {
        // Check if settings exist
        $query = $this->db->get('wa_settings');
        
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $this->db->where('id', $row->id);
            return $this->db->update('wa_settings', $data);
        } else {
            return $this->db->insert('wa_settings', $data);
        }
    }

    /**
     * Get template by type
     */
    public function get_template($tipe) {
        $this->db->where('tipe', $tipe);
        $this->db->where('is_active', 1);
        $this->db->limit(1);
        $query = $this->db->get('wa_templates');

        return $query->row_array();
    }

    /**
     * Get all templates
     */
    public function get_templates() {
        $this->db->order_by('tipe', 'ASC');
        $query = $this->db->get('wa_templates');
        return $query->result();
    }

    /**
     * Update template by type
     */
    public function update_template($tipe, $template) {
        $this->db->where('tipe', $tipe);
        $data = ['template' => $template];
        return $this->db->update('wa_templates', $data);
    }

    /**
     * Get active classes for notification
     */
    public function get_active_classes() {
        $this->db->select('kelas_id');
        $this->db->from('wa_kelas_aktif');
        $this->db->where('is_active', 1);
        $result = $this->db->get()->result();
        
        $ids = [];
        foreach ($result as $row) {
            $ids[] = $row->kelas_id;
        }
        return $ids;
    }

    /**
     * Update active classes
     */
    public function update_active_classes($kelas_ids) {
        // Deactivate all first
        $this->db->update('wa_kelas_aktif', ['is_active' => 0]);
        
        // Activate selected classes
        if (!empty($kelas_ids)) {
            foreach ($kelas_ids as $kelas_id) {
                // Check if exists
                $this->db->where('kelas_id', $kelas_id);
                $query = $this->db->get('wa_kelas_aktif');
                
                if ($query->num_rows() > 0) {
                    // Update to active
                    $this->db->where('kelas_id', $kelas_id);
                    $this->db->update('wa_kelas_aktif', ['is_active' => 1]);
                } else {
                    // Insert new
                    $this->db->insert('wa_kelas_aktif', [
                        'kelas_id' => $kelas_id,
                        'is_active' => 1
                    ]);
                }
            }
        }
        
        return true;
    }

    /**
     * Get queue statistics
     */
    public function get_queue_stats() {
        $stats = [];
        
        // Pending
        $this->db->where('status', 'pending');
        $stats['pending'] = $this->db->count_all_results('wa_queue');
        
        // Sent
        $this->db->where('status', 'sent');
        $stats['sent'] = $this->db->count_all_results('wa_queue');
        
        // Failed
        $this->db->where('status', 'failed');
        $stats['failed'] = $this->db->count_all_results('wa_queue');
        
        // Total
        $stats['total'] = $this->db->count_all('wa_queue');
        
        return $stats;
    }

    /**
     * Clear failed queue
     */
    public function clear_failed_queue() {
        $this->db->where('status', 'failed');
        return $this->db->delete('wa_queue');
    }

    /**
     * Check if class is active for notification
     */
    public function is_kelas_aktif($kelas_id) {
        $this->db->where('kelas_id', $kelas_id);
        $this->db->where('is_active', 1);
        $query = $this->db->get('wa_kelas_aktif');

        return $query->num_rows() > 0;
    }

    /**
     * Get active classes for notification
     */
    public function get_kelas_aktif() {
        $this->db->select('wa_kelas_aktif.*, kelas.nama_kelas');
        $this->db->from('wa_kelas_aktif');
        $this->db->join('kelas', 'kelas.id = wa_kelas_aktif.kelas_id');
        $this->db->where('wa_kelas_aktif.is_active', 1);
        
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * Toggle kelas aktif status
     */
    public function toggle_kelas_aktif($kelas_id) {
        // Check if exists
        $this->db->where('kelas_id', $kelas_id);
        $query = $this->db->get('wa_kelas_aktif');

        if ($query->num_rows() > 0) {
            // Toggle status
            $current = $query->row_array();
            $new_status = $current['is_active'] == 1 ? 0 : 1;
            
            $this->db->where('id', $current['id']);
            return $this->db->update('wa_kelas_aktif', array('is_active' => $new_status));
        } else {
            // Insert new
            return $this->db->insert('wa_kelas_aktif', array(
                'kelas_id' => $kelas_id,
                'is_active' => 1
            ));
        }
    }

    /**
     * Send WhatsApp message via API
     */
    public function send_message($no_hp, $pesan) {
        $settings = $this->get_settings();

        if (!$settings) {
            return array(
                'status' => false,
                'message' => 'WhatsApp settings not configured'
            );
        }

        // Prepare phone number (remove leading 0, add 62)
        $no_hp = preg_replace('/^0/', '62', $no_hp);

        // Prepare API request
        $data = array(
            'api_key' => $settings['api_key'],
            'sender' => $settings['sender'],
            'number' => $no_hp,
            'message' => $pesan
        );

        // Send via cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $settings['api_url']);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($http_code == 200) {
            return array(
                'status' => true,
                'message' => 'Message sent successfully',
                'response' => $response
            );
        } else {
            return array(
                'status' => false,
                'message' => 'Failed to send message',
                'response' => $response
            );
        }
    }
}
