<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jadwal extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Jadwal_model');
        $this->load->model('Semester_model');
        $this->load->model('Kelas_model');
        $this->load->model('Mapel_model');
        $this->load->model('Guru_model');
        $this->load->helper('custom');
        
        // Check login
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
        
        // Check role
        if (!in_array($this->session->userdata('role'), ['admin'])) {
            show_error('Access denied', 403);
        }
    }

    public function index() {
        $data['title'] = 'Jadwal Pelajaran';
        $data['semesters'] = $this->Semester_model->get_dropdown();
        $data['kelas_list'] = $this->Kelas_model->get_dropdown();
        
        // Get filter values
        $semester_id = $this->input->get('semester_id');
        $kelas_id = $this->input->get('kelas_id');
        $hari = $this->input->get('hari');
        
        // Get jadwal with filters
        $data['jadwal'] = $this->Jadwal_model->get_all($semester_id, $kelas_id, $hari);
        $data['semester_id'] = $semester_id;
        $data['kelas_id'] = $kelas_id;
        $data['hari'] = $hari;
        
        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar');
        $this->load->view('templates/sidebar_admin');
        $this->load->view('admin/jadwal/index', $data);
        $this->load->view('templates/footer');
    }

    public function add() {
        $this->form_validation->set_rules('semester_id', 'Semester', 'required');
        $this->form_validation->set_rules('kelas_id', 'Kelas', 'required');
        $this->form_validation->set_rules('mapel_id', 'Mata Pelajaran', 'required');
        $this->form_validation->set_rules('guru_id', 'Guru', 'required');
        $this->form_validation->set_rules('hari', 'Hari', 'required');
        $this->form_validation->set_rules('jam_mulai', 'Jam Mulai', 'required');
        $this->form_validation->set_rules('jam_selesai', 'Jam Selesai', 'required');

        if ($this->form_validation->run() == FALSE) {
            $response = array('status' => 'error', 'message' => validation_errors());
            echo json_encode($response);
            return;
        }

        $data = array(
            'semester_id' => $this->input->post('semester_id'),
            'kelas_id' => $this->input->post('kelas_id'),
            'mapel_id' => $this->input->post('mapel_id'),
            'guru_id' => $this->input->post('guru_id'),
            'hari' => $this->input->post('hari'),
            'jam_mulai' => $this->input->post('jam_mulai'),
            'jam_selesai' => $this->input->post('jam_selesai'),
            'ruangan' => $this->input->post('ruangan')
        );

        // Validate time
        if (strtotime($data['jam_selesai']) <= strtotime($data['jam_mulai'])) {
            $response = array('status' => 'error', 'message' => 'Jam selesai harus lebih besar dari jam mulai');
            echo json_encode($response);
            return;
        }

        // Check conflict
        $conflict = $this->Jadwal_model->check_conflict(
            $data['guru_id'], 
            $data['kelas_id'], 
            $data['hari'], 
            $data['jam_mulai'], 
            $data['jam_selesai']
        );

        if ($conflict) {
            $response = array('status' => 'error', 'message' => $conflict);
            echo json_encode($response);
            return;
        }

        if ($this->Jadwal_model->insert($data)) {
            $response = array('status' => 'success', 'message' => 'Jadwal berhasil ditambahkan');
        } else {
            $response = array('status' => 'error', 'message' => 'Gagal menambahkan jadwal');
        }

        echo json_encode($response);
    }

    public function get($id) {
        $data = $this->Jadwal_model->get_by_id($id);
        if ($data) {
            echo json_encode(array('status' => 'success', 'data' => $data));
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'Data tidak ditemukan'));
        }
    }

    public function edit($id) {
        $this->form_validation->set_rules('semester_id', 'Semester', 'required');
        $this->form_validation->set_rules('kelas_id', 'Kelas', 'required');
        $this->form_validation->set_rules('mapel_id', 'Mata Pelajaran', 'required');
        $this->form_validation->set_rules('guru_id', 'Guru', 'required');
        $this->form_validation->set_rules('hari', 'Hari', 'required');
        $this->form_validation->set_rules('jam_mulai', 'Jam Mulai', 'required');
        $this->form_validation->set_rules('jam_selesai', 'Jam Selesai', 'required');

        if ($this->form_validation->run() == FALSE) {
            $response = array('status' => 'error', 'message' => validation_errors());
            echo json_encode($response);
            return;
        }

        $data = array(
            'semester_id' => $this->input->post('semester_id'),
            'kelas_id' => $this->input->post('kelas_id'),
            'mapel_id' => $this->input->post('mapel_id'),
            'guru_id' => $this->input->post('guru_id'),
            'hari' => $this->input->post('hari'),
            'jam_mulai' => $this->input->post('jam_mulai'),
            'jam_selesai' => $this->input->post('jam_selesai'),
            'ruangan' => $this->input->post('ruangan')
        );

        // Validate time
        if (strtotime($data['jam_selesai']) <= strtotime($data['jam_mulai'])) {
            $response = array('status' => 'error', 'message' => 'Jam selesai harus lebih besar dari jam mulai');
            echo json_encode($response);
            return;
        }

        // Check conflict (exclude current)
        $conflict = $this->Jadwal_model->check_conflict(
            $data['guru_id'], 
            $data['kelas_id'], 
            $data['hari'], 
            $data['jam_mulai'], 
            $data['jam_selesai'],
            $id
        );

        if ($conflict) {
            $response = array('status' => 'error', 'message' => $conflict);
            echo json_encode($response);
            return;
        }

        if ($this->Jadwal_model->update($id, $data)) {
            $response = array('status' => 'success', 'message' => 'Jadwal berhasil diupdate');
        } else {
            $response = array('status' => 'error', 'message' => 'Gagal mengupdate jadwal');
        }

        echo json_encode($response);
    }

    public function delete($id) {
        // Check if used in jurnal
        $this->load->model('Jurnal_model');
        if ($this->Jurnal_model && method_exists($this->Jurnal_model, 'is_jadwal_used')) {
            if ($this->Jurnal_model->is_jadwal_used($id)) {
                $response = array('status' => 'error', 'message' => 'Jadwal tidak dapat dihapus karena sudah digunakan di jurnal mengajar');
                echo json_encode($response);
                return;
            }
        }

        if ($this->Jadwal_model->delete($id)) {
            $response = array('status' => 'success', 'message' => 'Jadwal berhasil dihapus');
        } else {
            $response = array('status' => 'error', 'message' => 'Gagal menghapus jadwal');
        }

        echo json_encode($response);
    }

    public function export() {
        // Get filter values
        $semester_id = $this->input->get('semester_id');
        $kelas_id = $this->input->get('kelas_id');
        $hari = $this->input->get('hari');
        
        // Get data
        $jadwal = $this->Jadwal_model->get_all($semester_id, $kelas_id, $hari);

        if (empty($jadwal)) {
            $this->session->set_flashdata('error', 'Tidak ada data untuk diexport');
            redirect('admin/jadwal');
            return;
        }

        // Load PhpSpreadsheet
        require_once FCPATH . 'vendor/autoload.php';
        
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set header
        $sheet->setCellValue('A1', 'Semester');
        $sheet->setCellValue('B1', 'Kelas');
        $sheet->setCellValue('C1', 'Hari');
        $sheet->setCellValue('D1', 'Jam Mulai');
        $sheet->setCellValue('E1', 'Jam Selesai');
        $sheet->setCellValue('F1', 'Mata Pelajaran');
        $sheet->setCellValue('G1', 'Guru');
        $sheet->setCellValue('H1', 'Ruangan');

        // Style header
        $headerStyle = [
            'font' => ['bold' => true],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'E2E8F0']
            ]
        ];
        $sheet->getStyle('A1:H1')->applyFromArray($headerStyle);

        // Fill data
        $row = 2;
        foreach ($jadwal as $item) {
            $sheet->setCellValue('A' . $row, $item->semester_nama ?? '-');
            $sheet->setCellValue('B' . $row, $item->kelas_nama ?? '-');
            $sheet->setCellValue('C' . $row, $item->hari);
            $sheet->setCellValue('D' . $row, substr($item->jam_mulai, 0, 5));
            $sheet->setCellValue('E' . $row, substr($item->jam_selesai, 0, 5));
            $sheet->setCellValue('F' . $row, $item->mapel_nama ?? '-');
            $sheet->setCellValue('G' . $row, $item->guru_nama ?? '-');
            $sheet->setCellValue('H' . $row, $item->ruangan ?? '-');
            $row++;
        }

        // Auto width
        foreach (range('A', 'H') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Download
        $filename = 'Jadwal_Pelajaran_' . date('YmdHis') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
}
