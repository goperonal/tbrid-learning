<?php

class Report_model extends CI_Model {

    public function get_laporan_kelas($kelas_id, $limit=null, $offset=null) {
        // Mengambil semua assignment dari kelas
        $this->db->select('tbl_assignment.assignment_id, tbl_assignment.assignment, tbl_kelas.nama_kelas');
        $this->db->from('tbl_assignment');
        $this->db->join('tbl_kelas', 'tbl_assignment.kelas_id = tbl_kelas.kelas_id');
        $this->db->where('tbl_kelas.kelas_id', $kelas_id);
        $assignments = $this->db->get()->result_array();

        // Jika tidak ada assignment, return data kosong
        if (empty($assignments)) {
            return ['assignments' => [], 'mahasiswa' => []];
        }

        // Mengambil data mahasiswa dengan limit dan offset
        $this->db->select('tbl_mahasiswa.akun_id, tbl_akun.nama_depan, tbl_akun.nama_belakang');
        $this->db->from('tbl_mahasiswa');
        $this->db->join('tbl_akun', 'tbl_mahasiswa.akun_id = tbl_akun.akun_id');
        $this->db->where('tbl_mahasiswa.kelas_id', $kelas_id);
        $this->db->limit($limit, $offset);
        $mahasiswa = $this->db->get()->result_array();

        // Jika tidak ada mahasiswa, return assignment tanpa mahasiswa
        if (empty($mahasiswa)) {
            return ['assignments' => $assignments, 'mahasiswa' => []];
        }

        // Mengambil nilai assignment yang telah diberikan untuk mahasiswa
        $akun_ids = array_column($mahasiswa, 'akun_id');
        $this->db->select('tbl_assignment_response.akun_id, tbl_assignment_response.assignment_id, tbl_assignment_response.nilai');
        $this->db->from('tbl_assignment_response');
        $this->db->where_in('tbl_assignment_response.akun_id', $akun_ids);
        $nilai_assignment = $this->db->get()->result_array();

        // Gabungkan data mahasiswa dan nilai per assignment
        return ['assignments' => $assignments, 'mahasiswa' => $this->group_mahasiswa_by_id($mahasiswa, $nilai_assignment)];
    }

    // Untuk menghitung jumlah total mahasiswa di kelas
    public function count_mahasiswa_in_class($kelas_id) {
        $this->db->where('kelas_id', $kelas_id);
        return $this->db->count_all_results('tbl_mahasiswa');
    }

    private function group_mahasiswa_by_id($mahasiswa, $nilai_assignment) {
        $grouped_mahasiswa = [];

        // Inisialisasi mahasiswa tanpa nilai
        foreach ($mahasiswa as $mhs) {
            $akun_id = $mhs['akun_id'];
            $grouped_mahasiswa[$akun_id] = [
                'nama' => $mhs['nama_depan'] . ' ' . $mhs['nama_belakang'],
                'nilai' => []
            ];
        }

        // Isi nilai untuk setiap assignment
        foreach ($nilai_assignment as $nilai) {
            $akun_id = $nilai['akun_id'];
            $assignment_id = $nilai['assignment_id'];
            
            // Jika sudah ada nilai untuk assignment ini, tambahkan nilai baru ke yang sudah ada
            if (isset($grouped_mahasiswa[$akun_id]['nilai'][$assignment_id])) {
                $grouped_mahasiswa[$akun_id]['nilai'][$assignment_id] += $nilai['nilai'];
            } else {
                $grouped_mahasiswa[$akun_id]['nilai'][$assignment_id] = $nilai['nilai'];
            }
        }

        return $grouped_mahasiswa;
    }
}
