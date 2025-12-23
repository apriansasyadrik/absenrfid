<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Guru Controller (Admin)
 * Manage teacher data
 */
class Guru extends CI_Controller {

    public function __construct() {
        parent::__construct();
        check_role(['admin']);
        $this->load->model('Guru_model');
    }

    /**
     * Teacher list page
     */
    public function index() {
        $data['title'] = 'Data Guru & Staff';
        $data['guru'] = $this->Guru_model->get_all();
        
        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('templates/sidebar_admin', $data);
        $this->load->view('admin/master/guru', $data);
        $this->load->view('templates/footer');
    }

    /**
     * Add new teacher
     */
    public function add() {
        $this->form_validation->set_rules('nama', 'Nama', 'required|trim');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode([
                'status' => false,
                'message' => validation_errors()
            ]);
            return;
        }

        $data = array(
            'nip' => $this->input->post('nip', TRUE),
            'rfid_uid' => $this->input->post('rfid_uid', TRUE),
            'nama' => $this->input->post('nama', TRUE),
            'jenis_kelamin' => $this->input->post('jenis_kelamin', TRUE),
            'tempat_lahir' => $this->input->post('tempat_lahir', TRUE),
            'tanggal_lahir' => $this->input->post('tanggal_lahir', TRUE),
            'alamat' => $this->input->post('alamat', TRUE),
            'no_hp' => $this->input->post('no_hp', TRUE),
            'email' => $this->input->post('email', TRUE),
            'jabatan' => $this->input->post('jabatan', TRUE),
            'is_active' => 1
        );

        // Handle photo upload
        if (!empty($_FILES['foto']['name'])) {
            $upload = upload_file('foto', './assets/uploads/foto_guru/', 'jpg|jpeg|png', 2048);
            if ($upload['status']) {
                $data['foto'] = $upload['file_name'];
            }
        }

        if ($this->Guru_model->insert($data)) {
            echo json_encode([
                'status' => true,
                'message' => 'Guru berhasil ditambahkan'
            ]);
        } else {
            echo json_encode([
                'status' => false,
                'message' => 'Gagal menambahkan guru'
            ]);
        }
    }

    /**
     * Get teacher by ID
     */
    public function get($id) {
        $guru = $this->Guru_model->get_by_id($id);
        echo json_encode([
            'status' => true,
            'data' => $guru
        ]);
    }

    /**
     * Update teacher
     */
    public function edit($id) {
        $this->form_validation->set_rules('nama', 'Nama', 'required|trim');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode([
                'status' => false,
                'message' => validation_errors()
            ]);
            return;
        }

        $data = array(
            'rfid_uid' => $this->input->post('rfid_uid', TRUE),
            'nama' => $this->input->post('nama', TRUE),
            'jenis_kelamin' => $this->input->post('jenis_kelamin', TRUE),
            'tempat_lahir' => $this->input->post('tempat_lahir', TRUE),
            'tanggal_lahir' => $this->input->post('tanggal_lahir', TRUE),
            'alamat' => $this->input->post('alamat', TRUE),
            'no_hp' => $this->input->post('no_hp', TRUE),
            'email' => $this->input->post('email', TRUE),
            'jabatan' => $this->input->post('jabatan', TRUE)
        );

        // Handle photo upload
        if (!empty($_FILES['foto']['name'])) {
            $upload = upload_file('foto', './assets/uploads/foto_guru/', 'jpg|jpeg|png', 2048);
            if ($upload['status']) {
                // Delete old photo
                $old_data = $this->Guru_model->get_by_id($id);
                if (!empty($old_data['foto']) && file_exists('./assets/uploads/foto_guru/' . $old_data['foto'])) {
                    unlink('./assets/uploads/foto_guru/' . $old_data['foto']);
                }
                $data['foto'] = $upload['file_name'];
            }
        }

        if ($this->Guru_model->update($id, $data)) {
            echo json_encode([
                'status' => true,
                'message' => 'Guru berhasil diupdate'
            ]);
        } else {
            echo json_encode([
                'status' => false,
                'message' => 'Gagal mengupdate guru'
            ]);
        }
    }

    /**
     * Delete teacher
     */
    public function delete($id) {
        // Delete photo
        $guru = $this->Guru_model->get_by_id($id);
        if (!empty($guru['foto']) && file_exists('./assets/uploads/foto_guru/' . $guru['foto'])) {
            unlink('./assets/uploads/foto_guru/' . $guru['foto']);
        }

        if ($this->Guru_model->delete($id)) {
            $this->session->set_flashdata('success', 'Guru berhasil dihapus');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus guru');
        }

        redirect('admin/guru');
    }

    /**
     * Export to Excel
     */
    public function export() {
        require_once FCPATH . 'vendor/autoload.php';

        $guru = $this->Guru_model->get_all();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $sheet->setCellValue('A1', 'NIP');
        $sheet->setCellValue('B1', 'RFID UID');
        $sheet->setCellValue('C1', 'Nama');
        $sheet->setCellValue('D1', 'Jenis Kelamin');
        $sheet->setCellValue('E1', 'Tempat Lahir');
        $sheet->setCellValue('F1', 'Tanggal Lahir');
        $sheet->setCellValue('G1', 'Alamat');
        $sheet->setCellValue('H1', 'No HP');
        $sheet->setCellValue('I1', 'Email');
        $sheet->setCellValue('J1', 'Jabatan');

        // Style header
        $sheet->getStyle('A1:J1')->getFont()->setBold(true);
        $sheet->getStyle('A1:J1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFCCCCCC');

        // Data
        $row = 2;
        foreach ($guru as $g) {
            $sheet->setCellValue('A' . $row, $g['nip']);
            $sheet->setCellValue('B' . $row, $g['rfid_uid']);
            $sheet->setCellValue('C' . $row, $g['nama']);
            $sheet->setCellValue('D' . $row, $g['jenis_kelamin']);
            $sheet->setCellValue('E' . $row, $g['tempat_lahir']);
            $sheet->setCellValue('F' . $row, $g['tanggal_lahir']);
            $sheet->setCellValue('G' . $row, $g['alamat']);
            $sheet->setCellValue('H' . $row, $g['no_hp']);
            $sheet->setCellValue('I' . $row, $g['email']);
            $sheet->setCellValue('J' . $row, $g['jabatan']);
            $row++;
        }

        // Auto size columns
        foreach (range('A', 'J') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        
        $filename = 'Data_Guru_' . date('YmdHis') . '.xlsx';
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output');
    }
}
