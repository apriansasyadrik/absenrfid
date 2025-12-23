<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Piket Rekap Controller
 * Rekap izin KBM per periode
 */
class Rekap extends CI_Controller {

    public function __construct() {
        parent::__construct();
        check_role(['guru_piket']);
        $this->load->model('IzinKBM_model');
        $this->load->model('Kelas_model');
    }

    /**
     * Rekap izin KBM
     */
    public function index() {
        // Get date range from input or default to this month
        $start_date = $this->input->get('start_date') ?: date('Y-m-01');
        $end_date = $this->input->get('end_date') ?: date('Y-m-t');
        $kelas_id = $this->input->get('kelas_id') ?: null;
        
        // Get rekap data
        $data['list_izin'] = $this->IzinKBM_model->get_rekap($start_date, $end_date, $kelas_id);
        
        // Get stats
        $data['stats'] = $this->IzinKBM_model->get_stats_by_type($start_date, $end_date, $kelas_id);
        
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;
        $data['kelas_id'] = $kelas_id;
        $data['kelas_list'] = $this->Kelas_model->get_all();
        
        $data['title'] = 'Rekap Izin KBM';
        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar_guru');
        $this->load->view('templates/sidebar_piket');
        $this->load->view('piket/rekap/index', $data);
        $this->load->view('templates/footer');
    }

    /**
     * Export rekap to Excel
     */
    public function export() {
        $start_date = $this->input->get('start_date') ?: date('Y-m-01');
        $end_date = $this->input->get('end_date') ?: date('Y-m-t');
        $kelas_id = $this->input->get('kelas_id') ?: null;
        
        // Get rekap data
        $list_izin = $this->IzinKBM_model->get_rekap($start_date, $end_date, $kelas_id);
        
        // Load PHPExcel or similar library here
        // For now, just output as CSV
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="rekap_izin_kbm_' . date('Ymd') . '.csv"');
        
        $output = fopen('php://output', 'w');
        
        // Header
        fputcsv($output, array('Tanggal', 'NIS', 'Nama Siswa', 'Kelas', 'Jenis Izin', 'Jam Izin', 'Jam Kembali', 'Alasan', 'Guru Piket'));
        
        // Data
        foreach ($list_izin as $izin) {
            fputcsv($output, array(
                date('d/m/Y', strtotime($izin['tanggal'])),
                $izin['nis'],
                $izin['nama_siswa'],
                $izin['nama_kelas'],
                $izin['jenis'],
                $izin['jam_izin'],
                $izin['jam_kembali'] ?: '-',
                $izin['alasan'],
                $izin['nama_guru']
            ));
        }
        
        fclose($output);
        exit;
    }
}
