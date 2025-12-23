<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Siswa Controller (Admin)
 * Manage student data
 */
class Siswa extends CI_Controller {

    public function __construct() {
        parent::__construct();
        check_role(['admin']);
        $this->load->model('Siswa_model');
        $this->load->model('Kelas_model');
    }

    /**
     * Student list page
     */
    public function index() {
        $data['title'] = 'Data Siswa';
        $data['siswa'] = $this->Siswa_model->get_all();
        $data['kelas_list'] = $this->Kelas_model->get_dropdown();
        
        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('templates/sidebar_admin', $data);
        $this->load->view('admin/master/siswa', $data);
        $this->load->view('templates/footer');
    }

    /**
     * Add new student
     */
    public function add() {
        $this->form_validation->set_rules('nis', 'NIS', 'required|trim|is_unique[siswa.nis]');
        $this->form_validation->set_rules('nama', 'Nama', 'required|trim');
        $this->form_validation->set_rules('jenis_kelamin', 'Jenis Kelamin', 'required');
        $this->form_validation->set_rules('kelas_id', 'Kelas', 'required');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode([
                'status' => false,
                'message' => validation_errors()
            ]);
            return;
        }

        $data = array(
            'nis' => $this->input->post('nis', TRUE),
            'nisn' => $this->input->post('nisn', TRUE),
            'rfid_uid' => $this->input->post('rfid_uid', TRUE),
            'nama' => $this->input->post('nama', TRUE),
            'jenis_kelamin' => $this->input->post('jenis_kelamin', TRUE),
            'tempat_lahir' => $this->input->post('tempat_lahir', TRUE),
            'tanggal_lahir' => $this->input->post('tanggal_lahir', TRUE),
            'alamat' => $this->input->post('alamat', TRUE),
            'no_hp_siswa' => $this->input->post('no_hp_siswa', TRUE),
            'nama_ortu' => $this->input->post('nama_ortu', TRUE),
            'no_hp_ortu' => $this->input->post('no_hp_ortu', TRUE),
            'email' => $this->input->post('email', TRUE),
            'kelas_id' => $this->input->post('kelas_id', TRUE),
            'is_active' => 1
        );

        // Handle photo upload
        if (!empty($_FILES['foto']['name'])) {
            $upload = upload_file('foto', './assets/uploads/foto_siswa/', 'jpg|jpeg|png', 2048);
            if ($upload['status']) {
                $data['foto'] = $upload['file_name'];
            }
        }

        if ($this->Siswa_model->insert($data)) {
            echo json_encode([
                'status' => true,
                'message' => 'Siswa berhasil ditambahkan'
            ]);
        } else {
            echo json_encode([
                'status' => false,
                'message' => 'Gagal menambahkan siswa'
            ]);
        }
    }

    /**
     * Get student by ID
     */
    public function get($id) {
        $siswa = $this->Siswa_model->get_by_id($id);
        echo json_encode([
            'status' => true,
            'data' => $siswa
        ]);
    }

    /**
     * Update student
     */
    public function edit($id) {
        $this->form_validation->set_rules('nama', 'Nama', 'required|trim');
        $this->form_validation->set_rules('jenis_kelamin', 'Jenis Kelamin', 'required');
        $this->form_validation->set_rules('kelas_id', 'Kelas', 'required');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode([
                'status' => false,
                'message' => validation_errors()
            ]);
            return;
        }

        $data = array(
            'nisn' => $this->input->post('nisn', TRUE),
            'rfid_uid' => $this->input->post('rfid_uid', TRUE),
            'nama' => $this->input->post('nama', TRUE),
            'jenis_kelamin' => $this->input->post('jenis_kelamin', TRUE),
            'tempat_lahir' => $this->input->post('tempat_lahir', TRUE),
            'tanggal_lahir' => $this->input->post('tanggal_lahir', TRUE),
            'alamat' => $this->input->post('alamat', TRUE),
            'no_hp_siswa' => $this->input->post('no_hp_siswa', TRUE),
            'nama_ortu' => $this->input->post('nama_ortu', TRUE),
            'no_hp_ortu' => $this->input->post('no_hp_ortu', TRUE),
            'email' => $this->input->post('email', TRUE),
            'kelas_id' => $this->input->post('kelas_id', TRUE)
        );

        // Handle photo upload
        if (!empty($_FILES['foto']['name'])) {
            $upload = upload_file('foto', './assets/uploads/foto_siswa/', 'jpg|jpeg|png', 2048);
            if ($upload['status']) {
                // Delete old photo
                $old_data = $this->Siswa_model->get_by_id($id);
                if (!empty($old_data['foto']) && file_exists('./assets/uploads/foto_siswa/' . $old_data['foto'])) {
                    unlink('./assets/uploads/foto_siswa/' . $old_data['foto']);
                }
                $data['foto'] = $upload['file_name'];
            }
        }

        if ($this->Siswa_model->update($id, $data)) {
            echo json_encode([
                'status' => true,
                'message' => 'Siswa berhasil diupdate'
            ]);
        } else {
            echo json_encode([
                'status' => false,
                'message' => 'Gagal mengupdate siswa'
            ]);
        }
    }

    /**
     * Delete student
     */
    public function delete($id) {
        // Delete photo
        $siswa = $this->Siswa_model->get_by_id($id);
        if (!empty($siswa['foto']) && file_exists('./assets/uploads/foto_siswa/' . $siswa['foto'])) {
            unlink('./assets/uploads/foto_siswa/' . $siswa['foto']);
        }

        if ($this->Siswa_model->delete($id)) {
            $this->session->set_flashdata('success', 'Siswa berhasil dihapus');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus siswa');
        }

        redirect('admin/siswa');
    }

    /**
     * Import from Excel
     */
    public function import() {
        if (empty($_FILES['file']['name'])) {
            $this->session->set_flashdata('error', 'File tidak boleh kosong');
            redirect('admin/siswa');
            return;
        }

        $config['upload_path'] = './assets/uploads/import/';
        $config['allowed_types'] = 'xlsx|xls';
        $config['max_size'] = 10240; // 10MB
        $config['encrypt_name'] = TRUE;

        $this->upload->initialize($config);

        if ($this->upload->do_upload('file')) {
            $file_data = $this->upload->data();
            $file_path = './assets/uploads/import/' . $file_data['file_name'];

            try {
                require_once FCPATH . 'vendor/autoload.php';
                
                $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file_path);
                $sheet = $spreadsheet->getActiveSheet();
                $rows = $sheet->toArray();

                $success = 0;
                $failed = 0;

                // Skip header row
                for ($i = 1; $i < count($rows); $i++) {
                    $row = $rows[$i];
                    
                    // Skip empty rows
                    if (empty($row[0])) continue;

                    $data = array(
                        'nis' => $row[0],
                        'nisn' => $row[1],
                        'rfid_uid' => $row[2],
                        'nama' => $row[3],
                        'jenis_kelamin' => $row[4],
                        'tempat_lahir' => $row[5],
                        'tanggal_lahir' => $row[6],
                        'alamat' => $row[7],
                        'no_hp_siswa' => $row[8],
                        'nama_ortu' => $row[9],
                        'no_hp_ortu' => $row[10],
                        'email' => $row[11],
                        'kelas_id' => $row[12],
                        'is_active' => 1
                    );

                    // Check if NIS already exists
                    if ($this->Siswa_model->is_nis_exists($data['nis'])) {
                        $failed++;
                        continue;
                    }

                    if ($this->Siswa_model->insert($data)) {
                        $success++;
                    } else {
                        $failed++;
                    }
                }

                // Delete uploaded file
                unlink($file_path);

                $this->session->set_flashdata('success', "Import berhasil. $success data ditambahkan, $failed gagal/duplikat");
            } catch (Exception $e) {
                $this->session->set_flashdata('error', 'Error: ' . $e->getMessage());
            }
        } else {
            $this->session->set_flashdata('error', $this->upload->display_errors('', ''));
        }

        redirect('admin/siswa');
    }

    /**
     * Export to Excel
     */
    public function export() {
        require_once FCPATH . 'vendor/autoload.php';

        $siswa = $this->Siswa_model->get_all();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $sheet->setCellValue('A1', 'NIS');
        $sheet->setCellValue('B1', 'NISN');
        $sheet->setCellValue('C1', 'RFID UID');
        $sheet->setCellValue('D1', 'Nama');
        $sheet->setCellValue('E1', 'Jenis Kelamin');
        $sheet->setCellValue('F1', 'Tempat Lahir');
        $sheet->setCellValue('G1', 'Tanggal Lahir');
        $sheet->setCellValue('H1', 'Alamat');
        $sheet->setCellValue('I1', 'No HP Siswa');
        $sheet->setCellValue('J1', 'Nama Orang Tua');
        $sheet->setCellValue('K1', 'No HP Orang Tua');
        $sheet->setCellValue('L1', 'Email');
        $sheet->setCellValue('M1', 'Kelas');

        // Style header
        $sheet->getStyle('A1:M1')->getFont()->setBold(true);
        $sheet->getStyle('A1:M1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFCCCCCC');

        // Data
        $row = 2;
        foreach ($siswa as $s) {
            $sheet->setCellValue('A' . $row, $s['nis']);
            $sheet->setCellValue('B' . $row, $s['nisn']);
            $sheet->setCellValue('C' . $row, $s['rfid_uid']);
            $sheet->setCellValue('D' . $row, $s['nama']);
            $sheet->setCellValue('E' . $row, $s['jenis_kelamin']);
            $sheet->setCellValue('F' . $row, $s['tempat_lahir']);
            $sheet->setCellValue('G' . $row, $s['tanggal_lahir']);
            $sheet->setCellValue('H' . $row, $s['alamat']);
            $sheet->setCellValue('I' . $row, $s['no_hp_siswa']);
            $sheet->setCellValue('J' . $row, $s['nama_ortu']);
            $sheet->setCellValue('K' . $row, $s['no_hp_ortu']);
            $sheet->setCellValue('L' . $row, $s['email']);
            $sheet->setCellValue('M' . $row, $s['nama_kelas']);
            $row++;
        }

        // Auto size columns
        foreach (range('A', 'M') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        
        $filename = 'Data_Siswa_' . date('YmdHis') . '.xlsx';
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output');
    }
}
