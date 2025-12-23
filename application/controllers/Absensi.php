<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Absensi Controller
 * Public RFID display page (no login required)
 */
class Absensi extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Absensi_model');
        $this->load->model('Settings_model');
    }

    /**
     * RFID Display Page (Full Screen)
     */
    public function index() {
        $data['title'] = 'Absensi RFID - Real Time';
        $data['settings'] = $this->Settings_model->get_settings();
        $data['latest_absensi'] = $this->Absensi_model->get_latest_today(20);
        
        $this->load->view('absensi/rfid', $data);
    }
}
