<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class WaSettings extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        // Check if user is logged in
        if (!$this->session->userdata('user_id')) {
            redirect('auth/login');
        }
        
        // Check if user is admin
        if ($this->session->userdata('role') != 'admin') {
            show_error('Unauthorized Access', 403);
        }
        
        $this->load->model('Wa_model');
        $this->load->model('Kelas_model');
    }

    public function index()
    {
        $data['title'] = 'Pengaturan WhatsApp Notifikasi';
        
        // Get WA settings
        $data['settings'] = $this->Wa_model->get_settings();
        
        // Get templates
        $data['templates'] = $this->Wa_model->get_templates();
        
        // Get active classes
        $data['kelas'] = $this->Kelas_model->get_all();
        $data['active_kelas'] = $this->Wa_model->get_active_classes();
        
        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar');
        $this->load->view('templates/sidebar_admin');
        $this->load->view('admin/wa/settings', $data);
        $this->load->view('templates/footer');
    }

    public function save_settings()
    {
        $this->form_validation->set_rules('api_url', 'API URL', 'required|valid_url');
        $this->form_validation->set_rules('api_key', 'API Key', 'required');
        $this->form_validation->set_rules('sender', 'Sender', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('admin/wa-settings');
            return;
        }

        $data = [
            'api_url' => $this->input->post('api_url'),
            'api_key' => $this->input->post('api_key'),
            'sender' => $this->input->post('sender'),
            'link_url' => $this->input->post('link_url'),
            'is_active' => $this->input->post('is_active') ? 1 : 0
        ];

        if ($this->Wa_model->update_settings($data)) {
            $this->session->set_flashdata('success', 'Pengaturan WhatsApp berhasil disimpan');
        } else {
            $this->session->set_flashdata('error', 'Gagal menyimpan pengaturan WhatsApp');
        }

        redirect('admin/wa-settings');
    }

    public function save_template()
    {
        $jenis = $this->input->post('jenis');
        $template = $this->input->post('template');

        if (empty($jenis) || empty($template)) {
            echo json_encode(['status' => 'error', 'message' => 'Jenis dan template harus diisi']);
            return;
        }

        if ($this->Wa_model->update_template($jenis, $template)) {
            echo json_encode(['status' => 'success', 'message' => 'Template berhasil disimpan']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan template']);
        }
    }

    public function save_active_classes()
    {
        $kelas_ids = $this->input->post('kelas_ids');
        
        if (!is_array($kelas_ids)) {
            $kelas_ids = [];
        }

        if ($this->Wa_model->update_active_classes($kelas_ids)) {
            echo json_encode(['status' => 'success', 'message' => 'Kelas aktif berhasil diupdate']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal mengupdate kelas aktif']);
        }
    }

    public function test_send()
    {
        $phone = $this->input->post('phone');
        
        if (empty($phone)) {
            echo json_encode(['status' => 'error', 'message' => 'Nomor telepon harus diisi']);
            return;
        }

        // Get settings
        $settings = $this->Wa_model->get_settings();
        
        if (!$settings || !$settings->is_active) {
            echo json_encode(['status' => 'error', 'message' => 'WhatsApp API belum dikonfigurasi atau tidak aktif']);
            return;
        }

        // Prepare test message
        $message = "Test Message dari Sistem Absensi RFID\n\n";
        $message .= "Tanggal: " . date('d/m/Y H:i:s') . "\n";
        $message .= "Pesan ini dikirim untuk testing koneksi WhatsApp API.\n\n";
        $message .= "Jika Anda menerima pesan ini, berarti konfigurasi WhatsApp sudah benar.";

        // Try to send
        $result = $this->send_wa_direct($settings, $phone, $message);

        if ($result['success']) {
            echo json_encode(['status' => 'success', 'message' => 'Pesan test berhasil dikirim ke ' . $phone]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal mengirim pesan: ' . $result['message']]);
        }
    }

    private function send_wa_direct($settings, $phone, $message)
    {
        // Clean phone number
        $phone = preg_replace('/[^0-9]/', '', $phone);
        if (substr($phone, 0, 1) == '0') {
            $phone = '62' . substr($phone, 1);
        }

        // Prepare API request
        $data = [
            'api_key' => $settings->api_key,
            'sender' => $settings->sender,
            'number' => $phone,
            'message' => $message
        ];

        // Send via cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $settings->api_url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curl_error = curl_error($ch);
        curl_close($ch);

        if ($curl_error) {
            return ['success' => false, 'message' => 'cURL Error: ' . $curl_error];
        }

        if ($http_code != 200) {
            return ['success' => false, 'message' => 'HTTP Code: ' . $http_code];
        }

        $result = json_decode($response, true);
        
        if ($result && isset($result['status']) && $result['status'] == 'success') {
            return ['success' => true, 'message' => 'Pesan berhasil dikirim'];
        } else {
            $error_msg = isset($result['message']) ? $result['message'] : 'Unknown error';
            return ['success' => false, 'message' => $error_msg];
        }
    }

    public function queue_stats()
    {
        $stats = $this->Wa_model->get_queue_stats();
        echo json_encode(['status' => 'success', 'data' => $stats]);
    }

    public function clear_failed_queue()
    {
        if ($this->Wa_model->clear_failed_queue()) {
            echo json_encode(['status' => 'success', 'message' => 'Queue gagal berhasil dibersihkan']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal membersihkan queue']);
        }
    }
}
