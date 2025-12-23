<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * RFID API Controller
 * Handle RFID scanning and absensi processing
 */
class Rfid extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Absensi_model');
        $this->load->model('Siswa_model');
        $this->load->model('Guru_model');
        $this->load->model('Wa_model');
    }

    /**
     * Scan RFID card
     * Endpoint: POST /api/rfid/scan
     * Parameters: rfid_uid, timestamp (optional)
     */
    public function scan() {
        // Get POST data
        $rfid_uid = $this->input->post('rfid_uid', TRUE);
        $timestamp = $this->input->post('timestamp') ? $this->input->post('timestamp') : date('Y-m-d H:i:s');
        
        if (empty($rfid_uid)) {
            json_response(false, 'RFID UID required');
            return;
        }

        // Check if RFID belongs to student or teacher
        $siswa = $this->Siswa_model->get_by_rfid($rfid_uid);
        $guru = $this->Guru_model->get_by_rfid($rfid_uid);

        if ($siswa) {
            $result = $this->_process_siswa_absensi($siswa, $timestamp);
        } elseif ($guru) {
            $result = $this->_process_guru_absensi($guru, $timestamp);
        } else {
            // Unknown RFID - log it
            $this->_log_rfid($rfid_uid, 'unknown', null, 'unknown', $timestamp, 'failed', 'RFID tidak terdaftar');
            json_response(false, 'RFID tidak terdaftar dalam sistem');
            return;
        }

        json_response($result['status'], $result['message'], $result['data']);
    }

    /**
     * Process student attendance
     */
    private function _process_siswa_absensi($siswa, $timestamp) {
        $tanggal = date('Y-m-d', strtotime($timestamp));
        $waktu = date('H:i:s', strtotime($timestamp));

        // Check if already absent today
        $existing = $this->Absensi_model->get_today_absensi('siswa', $siswa['id'], $tanggal);

        if ($existing) {
            // Already checked in - this is check out
            if (empty($existing['waktu_pulang'])) {
                $this->Absensi_model->update_pulang($existing['id'], $timestamp);
                
                // Add to WhatsApp queue (non-blocking)
                if ($siswa['no_hp_ortu']) {
                    $this->_queue_wa_pulang($siswa, $timestamp);
                }

                $this->_log_rfid($siswa['rfid_uid'], 'siswa', $siswa['id'], 'tap_pulang', $timestamp, 'success', 'Absen pulang berhasil');

                return array(
                    'status' => true,
                    'message' => 'Absen pulang berhasil',
                    'data' => array(
                        'type' => 'pulang',
                        'nama' => $siswa['nama'],
                        'kelas' => $siswa['kelas'],
                        'foto' => $siswa['foto'],
                        'waktu' => $timestamp,
                        'status' => 'Pulang'
                    )
                );
            } else {
                return array(
                    'status' => false,
                    'message' => 'Sudah absen pulang hari ini',
                    'data' => null
                );
            }
        } else {
            // First time - check in
            $jam_kerja = $this->Absensi_model->get_jam_kerja($tanggal);
            
            if (!$jam_kerja) {
                return array(
                    'status' => false,
                    'message' => 'Hari ini bukan hari kerja',
                    'data' => null
                );
            }

            // Calculate lateness
            $keterlambatan = hitung_keterlambatan($jam_kerja['jam_masuk'], $waktu);
            $status_masuk = ($keterlambatan <= $jam_kerja['toleransi_keterlambatan']) ? 'tepat_waktu' : 'terlambat';

            // Insert absensi
            $absensi_data = array(
                'tanggal' => $tanggal,
                'jenis' => 'siswa',
                'user_id' => $siswa['id'],
                'waktu_masuk' => $timestamp,
                'status_masuk' => $status_masuk,
                'keterlambatan_menit' => $keterlambatan
            );

            $this->Absensi_model->insert($absensi_data);

            // Add to WhatsApp queue (non-blocking)
            if ($siswa['no_hp_ortu']) {
                $this->_queue_wa_masuk($siswa, $timestamp, $status_masuk, $keterlambatan);
            }

            $this->_log_rfid($siswa['rfid_uid'], 'siswa', $siswa['id'], 'tap_masuk', $timestamp, 'success', 'Absen masuk berhasil');

            $status_text = $status_masuk == 'tepat_waktu' ? 'Tepat Waktu' : 'Terlambat ' . $keterlambatan . ' menit';

            return array(
                'status' => true,
                'message' => 'Absen masuk berhasil',
                'data' => array(
                    'type' => 'masuk',
                    'nama' => $siswa['nama'],
                    'kelas' => $siswa['kelas'],
                    'foto' => $siswa['foto'],
                    'waktu' => $timestamp,
                    'status' => $status_text,
                    'keterlambatan' => $keterlambatan
                )
            );
        }
    }

    /**
     * Process teacher attendance
     */
    private function _process_guru_absensi($guru, $timestamp) {
        $tanggal = date('Y-m-d', strtotime($timestamp));
        $waktu = date('H:i:s', strtotime($timestamp));

        // Check if already absent today
        $existing = $this->Absensi_model->get_today_absensi('guru', $guru['id'], $tanggal);

        if ($existing) {
            // Already checked in - this is check out
            if (empty($existing['waktu_pulang'])) {
                $this->Absensi_model->update_pulang($existing['id'], $timestamp);
                
                $this->_log_rfid($guru['rfid_uid'], 'guru', $guru['id'], 'tap_pulang', $timestamp, 'success', 'Absen pulang berhasil');

                return array(
                    'status' => true,
                    'message' => 'Absen pulang berhasil',
                    'data' => array(
                        'type' => 'pulang',
                        'nama' => $guru['nama'],
                        'jabatan' => $guru['jabatan'],
                        'foto' => $guru['foto'],
                        'waktu' => $timestamp,
                        'status' => 'Pulang'
                    )
                );
            } else {
                return array(
                    'status' => false,
                    'message' => 'Sudah absen pulang hari ini',
                    'data' => null
                );
            }
        } else {
            // First time - check in
            $jam_kerja = $this->Absensi_model->get_jam_kerja($tanggal);
            
            if (!$jam_kerja) {
                return array(
                    'status' => false,
                    'message' => 'Hari ini bukan hari kerja',
                    'data' => null
                );
            }

            // Calculate lateness
            $keterlambatan = hitung_keterlambatan($jam_kerja['jam_masuk'], $waktu);
            $status_masuk = ($keterlambatan <= $jam_kerja['toleransi_keterlambatan']) ? 'tepat_waktu' : 'terlambat';

            // Insert absensi
            $absensi_data = array(
                'tanggal' => $tanggal,
                'jenis' => 'guru',
                'user_id' => $guru['id'],
                'waktu_masuk' => $timestamp,
                'status_masuk' => $status_masuk,
                'keterlambatan_menit' => $keterlambatan
            );

            $this->Absensi_model->insert($absensi_data);

            $this->_log_rfid($guru['rfid_uid'], 'guru', $guru['id'], 'tap_masuk', $timestamp, 'success', 'Absen masuk berhasil');

            $status_text = $status_masuk == 'tepat_waktu' ? 'Tepat Waktu' : 'Terlambat ' . $keterlambatan . ' menit';

            return array(
                'status' => true,
                'message' => 'Absen masuk berhasil',
                'data' => array(
                    'type' => 'masuk',
                    'nama' => $guru['nama'],
                    'jabatan' => $guru['jabatan'],
                    'foto' => $guru['foto'],
                    'waktu' => $timestamp,
                    'status' => $status_text,
                    'keterlambatan' => $keterlambatan
                )
            );
        }
    }

    /**
     * Add WhatsApp notification to queue for check-in
     */
    private function _queue_wa_masuk($siswa, $timestamp, $status, $keterlambatan) {
        $template = $this->Wa_model->get_template('absen_masuk');
        
        if (!$template) return;

        $status_text = $status == 'tepat_waktu' ? 'Tepat Waktu' : 'Terlambat';
        $terlambat_text = $keterlambatan > 0 ? 'Terlambat ' . $keterlambatan . ' menit.' : '';

        $message = parse_whatsapp_template($template['template'], array(
            'nama' => $siswa['nama'],
            'kelas' => $siswa['kelas'],
            'waktu' => date('H:i', strtotime($timestamp)),
            'status' => $status_text,
            'terlambat' => $terlambat_text
        ));

        $this->Wa_model->add_to_queue($siswa['no_hp_ortu'], $message);
    }

    /**
     * Add WhatsApp notification to queue for check-out
     */
    private function _queue_wa_pulang($siswa, $timestamp) {
        $template = $this->Wa_model->get_template('absen_pulang');
        
        if (!$template) return;

        $message = parse_whatsapp_template($template['template'], array(
            'nama' => $siswa['nama'],
            'kelas' => $siswa['kelas'],
            'waktu' => date('H:i', strtotime($timestamp))
        ));

        $this->Wa_model->add_to_queue($siswa['no_hp_ortu'], $message);
    }

    /**
     * Log RFID activity
     */
    private function _log_rfid($rfid_uid, $jenis, $user_id, $aksi, $waktu, $status, $keterangan) {
        $log_data = array(
            'rfid_uid' => $rfid_uid,
            'jenis' => $jenis,
            'user_id' => $user_id,
            'aksi' => $aksi,
            'tanggal' => date('Y-m-d', strtotime($waktu)),
            'waktu' => $waktu,
            'status' => $status,
            'keterangan' => $keterangan
        );

        $this->db->insert('rfid_log', $log_data);
    }

    /**
     * Get latest attendance for display
     */
    public function get_latest() {
        $limit = $this->input->get('limit') ? $this->input->get('limit') : 10;
        $data = $this->Absensi_model->get_latest_today($limit);
        
        json_response(true, 'Success', $data);
    }
}
