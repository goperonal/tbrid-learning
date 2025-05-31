<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Report extends CI_Controller
{
    function __construct()
	{
		parent::__construct();

		$this->load->helper('string');
		$this->load->model(array(
			'Crud_model'		=>'crud',
			'Teacher_model'		=>'m_teacher',
			'E_class_model'		=>'m_class',
			'Report_model'		=> 'm_report'
		));
		$this->load->library('upload');
		is_login();
	}

    public function index() {
	    $this->load->library('pagination');

	    $kelas_id = $this->uri->segment(4);

	    // Pagination configuration
	    $config['base_url'] = base_url('teacher/report/index/' . $kelas_id);
	    $config['total_rows'] = $this->m_report->count_mahasiswa_in_class($kelas_id);
	    $config['per_page'] = 10; // Jumlah data per halaman
	    $config['uri_segment'] = 5; // Segment untuk halaman (page number)
	    
	    // Pagination style customization
	    $config['full_tag_open'] = '<ul class="pagination">';
	    $config['full_tag_close'] = '</ul>';
	    $config['num_tag_open'] = '<li class="page-item">';
	    $config['num_tag_close'] = '</li>';
	    $config['cur_tag_open'] = '<li class="page-item active"><a class="page-link">';
	    $config['cur_tag_close'] = '</a></li>';
	    $config['next_tag_open'] = '<li class="page-item">';
	    $config['next_tag_close'] = '</li>';
	    $config['prev_tag_open'] = '<li class="page-item">';
	    $config['prev_tag_close'] = '</li>';
	    $config['attributes'] = array('class' => 'page-link');

	    $this->pagination->initialize($config);

	    // Ambil halaman saat ini
	    $page = ($this->uri->segment(5)) ? $this->uri->segment(5) : 0;

	    // Ambil data laporan berdasarkan pagination
	    $data = array(
	        'title' => 'Report',
	        'record' => $this->m_teacher->get_kelas(['teacher_id' => $this->session->user_id]),
	        'laporan' => $this->m_report->get_laporan_kelas($kelas_id, $config['per_page'], $page),
	        'pagination' => $this->pagination->create_links()
	    );

	    $this->template->load('template', 'teacher/report_view', $data);
	}


	public function export_to_excel()
    {
    	$kelas_id = $this->uri->segment(4);
        // Ambil data laporan kelas dari model
        $laporan = $this->m_report->get_laporan_kelas($kelas_id);

        if (empty($laporan['assignments'])) {
            show_error('Tidak ada data untuk diexport.');
            return;
        }

        // Ambil nama kelas
        $nama_kelas = $laporan['assignments'][0]['nama_kelas'];

        // Membuat Spreadsheet baru
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Mengisi header
        $sheet->setCellValue('A1', 'NO');
        $sheet->setCellValue('B1', $nama_kelas);
        $columnIndex = 'C'; // Mulai dari kolom C
        
        // Menambahkan judul assignment di header
        foreach ($laporan['assignments'] as $assignment) {
            $sheet->setCellValue($columnIndex . '1', $assignment['assignment']);
            $columnIndex++;
        }
        $sheet->setCellValue($columnIndex . '1', 'Rata-rata Nilai');

        // Mengisi data mahasiswa
        $rowNumber = 2;
        $no = 1;
        foreach ($laporan['mahasiswa'] as $mhs) {
            $sheet->setCellValue('A' . $rowNumber, $no++);
            $sheet->setCellValue('B' . $rowNumber, $mhs['nama']);
            
            $total_nilai = 0;
            $jumlah_assignment = count($laporan['assignments']);
            $columnIndex = 'C';
            
            // Menambahkan nilai untuk setiap assignment
            foreach ($laporan['assignments'] as $assignment) {
                $nilai = isset($mhs['nilai'][$assignment['assignment_id']]) ? $mhs['nilai'][$assignment['assignment_id']] : 0;
                $sheet->setCellValue($columnIndex . $rowNumber, $nilai);
                $total_nilai += $nilai;
                $columnIndex++;
            }
            
            // Menambahkan rata-rata nilai
            $average = $jumlah_assignment > 0 ? $total_nilai / $jumlah_assignment : 0;
            $sheet->setCellValue($columnIndex . $rowNumber, $average);
            
            $rowNumber++;
        }

        // Set auto-size pada kolom
        foreach (range('A', $columnIndex) as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        // Membuat file Excel
        $writer = new Xlsx($spreadsheet);
        $filename = 'Laporan_Kelas_' . $nama_kelas . '.xlsx';

        // Mengirimkan header agar file bisa diunduh
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output'); // Mengirimkan file Excel ke browser
        exit;
    }


}