<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Cron API Controller
 * Handle scheduled tasks
 */
class Cron extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Absensi_model');
        $this->load->model('Siswa_model');
        $this->load->model('Wa_model');
        $this->load->model('Kelas_model');
    }

    /**
     * Check students who haven't checked in by 9 AM
     * Send notification to homeroom teacher
     * Run at 9:00 AM: 0 9 * * 1-6 php /path/to/index.php api/cron/check_belum_absen
     */
    public function check_belum_absen() {
        // Get students who haven't checked in
        $belum_absen = $this->Absensi_model->get_belum_absen();
        
        if (empty($belum_absen)) {
            log_message('info', 'All students have checked in');
            json_response(true, 'All students have checked in');
            return;
        }
        
        // Group by class
        $per_kelas = array();
        foreach ($belum_absen as $siswa) {
            if (empty($siswa['kelas_id'])) continue;
            
            if (!isset($per_kelas[$siswa['kelas_id']])) {
                $per_kelas[$siswa['kelas_id']] = array(
                    'nama_kelas' => $siswa['nama_kelas'],
                    'siswa' => array()
                );
            }
            
            $per_kelas[$siswa['kelas_id']]['siswa'][] = $siswa['nama'];
        }
        
        // Send notification to each homeroom teacher
        $sent = 0;
        foreach ($per_kelas as $kelas_id => $data) {
            // Get homeroom teacher
            $kelas = $this->Kelas_model->get_by_id($kelas_id);
            
            if (empty($kelas) || empty($kelas['walikelas_id'])) continue;
            
            // Check if class is active for notification
            if (!$this->Wa_model->is_kelas_aktif($kelas_id)) continue;
            
            // Get walikelas phone number
            $this->db->select('no_hp');
            $this->db->where('id', $kelas['walikelas_id']);
            $guru = $this->db->get('guru')->row_array();
            
            if (empty($guru) || empty($guru['no_hp'])) continue;
            
            // Prepare message
            $template = $this->Wa_model->get_template('notif_walikelas');
            
            if (!$template) continue;
            
            $daftar_siswa = implode(', ', $data['siswa']);
            
            $message = parse_whatsapp_template($template['template'], array(
                'kelas' => $data['nama_kelas'],
                'jumlah' => count($data['siswa']),
                'daftar_siswa' => $daftar_siswa
            ));
            
            // Add to queue
            $this->Wa_model->add_to_queue($guru['no_hp'], $message);
            $sent++;
        }
        
        $message = sprintf(
            'Notification sent to %d homeroom teachers for %d students who haven\'t checked in',
            $sent,
            count($belum_absen)
        );
        
        log_message('info', $message);
        json_response(true, $message);
    }

    /**
     * Update BK monitoring data
     * Count alpha and late per student per month
     * Run at 11:00 PM: 0 23 * * * php /path/to/index.php api/cron/update_monitoring_bk
     */
    public function update_monitoring_bk() {
        $bulan = date('m');
        $tahun = date('Y');
        $start_date = date('Y-m-01');
        $end_date = date('Y-m-t');
        
        // Get all active students
        $siswa_list = $this->Siswa_model->get_all();
        
        $updated = 0;
        $flagged = 0;
        
        foreach ($siswa_list as $siswa) {
            // Count alpha (tidak hadir tanpa keterangan)
            $this->db->where('jenis', 'siswa');
            $this->db->where('user_id', $siswa['id']);
            $this->db->where('tanggal >=', $start_date);
            $this->db->where('tanggal <=', $end_date);
            $this->db->where('waktu_masuk IS NULL');
            
            // Exclude those with izin
            $this->db->where('tanggal NOT IN (SELECT tanggal FROM izin_siswa WHERE siswa_id = ' . $siswa['id'] . ')', NULL, FALSE);
            
            $jumlah_alpha = $this->db->count_all_results('absensi_harian');
            
            // Count late
            $this->db->where('jenis', 'siswa');
            $this->db->where('user_id', $siswa['id']);
            $this->db->where('tanggal >=', $start_date);
            $this->db->where('tanggal <=', $end_date);
            $this->db->where('status_masuk', 'terlambat');
            $jumlah_terlambat = $this->db->count_all_results('absensi_harian');
            
            // Check if should be flagged
            $is_flagged = ($jumlah_alpha >= 3 || $jumlah_terlambat >= 5) ? 1 : 0;
            
            if ($is_flagged) $flagged++;
            
            // Update or insert monitoring
            $this->db->where('siswa_id', $siswa['id']);
            $this->db->where('bulan', $bulan);
            $this->db->where('tahun', $tahun);
            $existing = $this->db->get('monitoring_bk')->row_array();
            
            $data = array(
                'jumlah_alpha' => $jumlah_alpha,
                'jumlah_terlambat' => $jumlah_terlambat,
                'is_flagged' => $is_flagged
            );
            
            if ($existing) {
                $this->db->where('id', $existing['id']);
                $this->db->update('monitoring_bk', $data);
            } else {
                $data['siswa_id'] = $siswa['id'];
                $data['bulan'] = $bulan;
                $data['tahun'] = $tahun;
                $this->db->insert('monitoring_bk', $data);
            }
            
            $updated++;
        }
        
        $message = sprintf(
            'BK Monitoring updated for %d students, %d flagged',
            $updated,
            $flagged
        );
        
        log_message('info', $message);
        json_response(true, $message);
    }
}
