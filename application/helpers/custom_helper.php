<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Custom Helper Functions
 */

if (!function_exists('check_login')) {
    /**
     * Check if user is logged in
     */
    function check_login() {
        $ci =& get_instance();
        if (!$ci->session->userdata('logged_in')) {
            redirect('login');
        }
    }
}

if (!function_exists('check_role')) {
    /**
     * Check user role
     */
    function check_role($allowed_roles = array()) {
        $ci =& get_instance();
        check_login();
        
        $user_role = $ci->session->userdata('role');
        if (!in_array($user_role, $allowed_roles)) {
            show_error('Anda tidak memiliki akses ke halaman ini', 403, 'Akses Ditolak');
        }
    }
}

if (!function_exists('get_user_data')) {
    /**
     * Get current user data
     */
    function get_user_data($key = null) {
        $ci =& get_instance();
        if ($key) {
            return $ci->session->userdata($key);
        }
        return array(
            'user_id' => $ci->session->userdata('user_id'),
            'username' => $ci->session->userdata('username'),
            'role' => $ci->session->userdata('role'),
            'guru_id' => $ci->session->userdata('guru_id'),
            'nama' => $ci->session->userdata('nama')
        );
    }
}

if (!function_exists('format_tanggal')) {
    /**
     * Format tanggal Indonesia
     */
    function format_tanggal($date, $format = 'long') {
        $bulan = array(
            1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        );
        
        $split = explode('-', date('Y-m-d', strtotime($date)));
        
        if ($format == 'long') {
            return $split[2] . ' ' . $bulan[(int)$split[1]] . ' ' . $split[0];
        } else {
            return $split[2] . '-' . $split[1] . '-' . $split[0];
        }
    }
}

if (!function_exists('format_hari')) {
    /**
     * Get hari dalam bahasa Indonesia
     */
    function format_hari($date) {
        $hari = array(
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu'
        );
        
        return $hari[date('l', strtotime($date))];
    }
}

if (!function_exists('hitung_keterlambatan')) {
    /**
     * Hitung menit keterlambatan
     */
    function hitung_keterlambatan($jam_masuk_seharusnya, $jam_masuk_aktual) {
        $seharusnya = strtotime($jam_masuk_seharusnya);
        $aktual = strtotime($jam_masuk_aktual);
        
        if ($aktual <= $seharusnya) {
            return 0;
        }
        
        $selisih = $aktual - $seharusnya;
        return round($selisih / 60); // dalam menit
    }
}

if (!function_exists('status_kehadiran')) {
    /**
     * Determine status kehadiran
     */
    function status_kehadiran($waktu_masuk, $jam_kerja, $toleransi = 15) {
        if (empty($waktu_masuk)) {
            return 'Alpha';
        }
        
        $keterlambatan = hitung_keterlambatan($jam_kerja, $waktu_masuk);
        
        if ($keterlambatan <= $toleransi) {
            return 'Tepat Waktu';
        } else {
            return 'Terlambat ' . $keterlambatan . ' menit';
        }
    }
}

if (!function_exists('upload_file')) {
    /**
     * Upload file helper
     */
    function upload_file($field_name, $upload_path, $allowed_types = 'jpg|jpeg|png', $max_size = 2048) {
        $ci =& get_instance();
        
        $config['upload_path'] = $upload_path;
        $config['allowed_types'] = $allowed_types;
        $config['max_size'] = $max_size;
        $config['encrypt_name'] = TRUE;
        
        $ci->upload->initialize($config);
        
        if ($ci->upload->do_upload($field_name)) {
            return array(
                'status' => true,
                'data' => $ci->upload->data(),
                'file_name' => $ci->upload->data('file_name')
            );
        } else {
            return array(
                'status' => false,
                'error' => $ci->upload->display_errors('', '')
            );
        }
    }
}

if (!function_exists('generate_nomor_surat')) {
    /**
     * Generate nomor surat BK
     */
    function generate_nomor_surat($prefix = 'BK') {
        $ci =& get_instance();
        $tahun = date('Y');
        $bulan = date('m');
        
        // Get last number
        $ci->db->select('nomor_surat');
        $ci->db->from('surat_bk');
        $ci->db->like('nomor_surat', $prefix . '/' . $bulan . '/' . $tahun, 'after');
        $ci->db->order_by('id', 'DESC');
        $ci->db->limit(1);
        $query = $ci->db->get();
        
        if ($query->num_rows() > 0) {
            $last = $query->row()->nomor_surat;
            $parts = explode('/', $last);
            $number = (int)$parts[0] + 1;
        } else {
            $number = 1;
        }
        
        return sprintf('%03d', $number) . '/' . $prefix . '/' . $bulan . '/' . $tahun;
    }
}

if (!function_exists('parse_whatsapp_template')) {
    /**
     * Parse template WhatsApp dengan variable
     */
    function parse_whatsapp_template($template, $data = array()) {
        foreach ($data as $key => $value) {
            $template = str_replace('{' . $key . '}', $value, $template);
        }
        return $template;
    }
}

if (!function_exists('is_hari_kerja')) {
    /**
     * Check apakah hari ini hari kerja
     */
    function is_hari_kerja($tanggal = null) {
        $ci =& get_instance();
        
        if (!$tanggal) {
            $tanggal = date('Y-m-d');
        }
        
        $hari = format_hari($tanggal);
        
        // Check jam kerja
        $ci->db->where('hari', $hari);
        $ci->db->where('is_kerja', 1);
        $jam_kerja = $ci->db->get('jam_kerja')->row();
        
        if (!$jam_kerja) {
            return false;
        }
        
        // Check hari libur
        $ci->db->where('tanggal', $tanggal);
        $libur = $ci->db->get('hari_libur')->row();
        
        return !$libur;
    }
}

if (!function_exists('json_response')) {
    /**
     * Send JSON response
     */
    function json_response($status, $message, $data = null) {
        $ci =& get_instance();
        $ci->output
            ->set_content_type('application/json')
            ->set_output(json_encode(array(
                'status' => $status,
                'message' => $message,
                'data' => $data
            )));
    }
}
