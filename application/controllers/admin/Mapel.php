<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mapel extends CI_Controller {

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
        
        $this->load->model('Mapel_model');
        $this->load->model('Guru_model');
    }

    public function index()
    {
        $data['title'] = 'Data Mata Pelajaran';
        $data['mapel'] = $this->Mapel_model->get_all();
        $data['guru'] = $this->Guru_model->get_all();
        
        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar');
        $this->load->view('templates/sidebar_admin');
        $this->load->view('admin/master/mapel', $data);
        $this->load->view('templates/footer');
    }

    public function add()
    {
        $this->form_validation->set_rules('kode_mapel', 'Kode Mata Pelajaran', 'required|trim|max_length[20]|is_unique[mata_pelajaran.kode_mapel]');
        $this->form_validation->set_rules('nama_mapel', 'Nama Mata Pelajaran', 'required|trim');
        $this->form_validation->set_rules('guru_id', 'Guru Pengampu', 'numeric');
        $this->form_validation->set_rules('kkm', 'KKM', 'required|numeric|greater_than[0]|less_than_equal_to[100]');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(['status' => 'error', 'message' => validation_errors()]);
            return;
        }

        $data = [
            'kode_mapel' => strtoupper($this->input->post('kode_mapel')),
            'nama_mapel' => $this->input->post('nama_mapel'),
            'guru_id' => $this->input->post('guru_id') ?: null,
            'kkm' => $this->input->post('kkm'),
            'deskripsi' => $this->input->post('deskripsi')
        ];

        if ($this->Mapel_model->insert($data)) {
            echo json_encode(['status' => 'success', 'message' => 'Mata pelajaran berhasil ditambahkan']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal menambahkan mata pelajaran']);
        }
    }

    public function get($id)
    {
        $data = $this->Mapel_model->get_by_id($id);
        if ($data) {
            echo json_encode(['status' => 'success', 'data' => $data]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Data tidak ditemukan']);
        }
    }

    public function edit($id)
    {
        // Check if kode_mapel is unique (except for current record)
        $current = $this->Mapel_model->get_by_id($id);
        $kode_mapel = strtoupper($this->input->post('kode_mapel'));
        
        if ($kode_mapel != $current->kode_mapel) {
            $this->db->where('kode_mapel', $kode_mapel);
            $check = $this->db->get('mata_pelajaran')->row();
            if ($check) {
                echo json_encode(['status' => 'error', 'message' => 'Kode mata pelajaran sudah digunakan']);
                return;
            }
        }

        $this->form_validation->set_rules('kode_mapel', 'Kode Mata Pelajaran', 'required|trim|max_length[20]');
        $this->form_validation->set_rules('nama_mapel', 'Nama Mata Pelajaran', 'required|trim');
        $this->form_validation->set_rules('guru_id', 'Guru Pengampu', 'numeric');
        $this->form_validation->set_rules('kkm', 'KKM', 'required|numeric|greater_than[0]|less_than_equal_to[100]');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(['status' => 'error', 'message' => validation_errors()]);
            return;
        }

        $data = [
            'kode_mapel' => $kode_mapel,
            'nama_mapel' => $this->input->post('nama_mapel'),
            'guru_id' => $this->input->post('guru_id') ?: null,
            'kkm' => $this->input->post('kkm'),
            'deskripsi' => $this->input->post('deskripsi')
        ];

        if ($this->Mapel_model->update($id, $data)) {
            echo json_encode(['status' => 'success', 'message' => 'Mata pelajaran berhasil diupdate']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal mengupdate mata pelajaran']);
        }
    }

    public function delete($id)
    {
        // Check if mapel is being used in jadwal
        $used_count = $this->Mapel_model->check_usage($id);
        
        if ($used_count > 0) {
            echo json_encode(['status' => 'error', 'message' => 'Mata pelajaran sedang digunakan pada ' . $used_count . ' jadwal']);
            return;
        }

        if ($this->Mapel_model->delete($id)) {
            echo json_encode(['status' => 'success', 'message' => 'Mata pelajaran berhasil dihapus']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus mata pelajaran']);
        }
    }

    public function export()
    {
        $mapel = $this->Mapel_model->get_all();
        
        // Create new Spreadsheet
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Set headers
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Kode');
        $sheet->setCellValue('C1', 'Nama Mata Pelajaran');
        $sheet->setCellValue('D1', 'Guru Pengampu');
        $sheet->setCellValue('E1', 'KKM');
        $sheet->setCellValue('F1', 'Deskripsi');
        
        // Style header
        $sheet->getStyle('A1:F1')->getFont()->setBold(true);
        $sheet->getStyle('A1:F1')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFE0E0E0');
        
        // Fill data
        $row = 2;
        $no = 1;
        foreach ($mapel as $m) {
            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, $m->kode_mapel);
            $sheet->setCellValue('C' . $row, $m->nama_mapel);
            $sheet->setCellValue('D' . $row, $m->nama_guru ?? '-');
            $sheet->setCellValue('E' . $row, $m->kkm);
            $sheet->setCellValue('F' . $row, $m->deskripsi ?? '-');
            $row++;
        }
        
        // Auto width
        foreach (range('A', 'F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        
        // Download
        $filename = 'Data_Mata_Pelajaran_' . date('YmdHis') . '.xlsx';
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
    }
}
