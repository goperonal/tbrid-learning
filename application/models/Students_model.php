<?php

class Students_model extends CI_model{

    public function get_kelas($where = "")
    {
        $this->db->where($where);
        $this->db->select('m.akun_id, m.nim, k.*, sk.kelas_id, sk.nama_sub_kelas, sk.sub_kelas_id')
                 ->from('mahasiswa m')
                 ->join('sub_kelas sk','sk.kelas_id = m.kelas_id')
                 ->join('kelas k','k.kelas_id = m.kelas_id')
                 ->order_by('k.nama_kelas');
        $results = $this->db->get();

        $output = array();
        foreach ($results->result() as $result) {
            // Membuat key unik berdasarkan kelas_id
            $key = $result->kelas_id;
            
            // Jika key belum ada di array output, tambahkan
            if (!array_key_exists($key, $output)) {
                $output[$key] = array(
                    'kelas_id' => $result->kelas_id,
                    // 'teacher_id' => $result->teacher_id,
                    'nama_kelas' => $result->nama_kelas,
                    'materi' => $result->materi,
                    'tutup' => $result->tutup,
                    'sub_kelas' => array()
                );
            }
            
            // Tambahkan nama_sub_kelas ke dalam sub_kelas pada array output
            $output[$key]['sub_kelas'][] = array(
                'sub_kelas_id'      => $result->sub_kelas_id,
                'nama_sub_kelas'    => $result->nama_sub_kelas
            );
        }

        // Urutkan sub_kelas berdasarkan sub_kelas_id di setiap elemen $output
        foreach ($output as &$kelas) {
            usort($kelas['sub_kelas'], function($a, $b) {
                return $a['sub_kelas_id'] - $b['sub_kelas_id'];
            });
        }
        
        // Ubah array assosiatif hasil menjadi array numerik
        $output = array_values($output);
        return $output;
    }


    public function data_mahasiswa($kelas_id)
    {
        $queryBuilder = $this->db->where('k.kelas_id',$kelas_id)
                 ->select('CONCAT(a.nama_depan, " ", a.nama_belakang) as nama, a.email, m.nim')
                 ->from('akun a')
                 ->join('mahasiswa m','m.akun_id = a.akun_id')
                 ->join('kelas k','k.kelas_id = m.kelas_id')
                 ->order_by('m.nim');
        $datatables = new Ngekoding\CodeIgniterDataTables\DataTables($queryBuilder, '3');
        return $datatables->asObject()->generate(); // done
    }

    public function get_mhs_live_zoom($sub_kelas_id)
    {
        $this->db->select("m.*, a.nama_depan, a.nama_belakang")
                 ->from('mahasiswa m')
                 ->join('sub_kelas sk', 'sk.kelas_id = m.kelas_id')
                 ->join('akun a', 'a.akun_id = m.akun_id')
                 ->where("sk.sub_kelas_id", $sub_kelas_id);
        return $this->db->get();
    }

    public function get_assignment_by_ID($id)
    {
        $this->db->select("a.*, k.nama_kelas, ar.nilai, ar.file_name, ar.akun_id, ar.question_id")
                 ->from('assignment a')
                 ->join('kelas k', 'k.kelas_id = a.kelas_id')
                 ->join('assignment_response ar', 'ar.assignment_id = a.assignment_id', 'left')
                 ->where("a.assignment_id", $id);
        return $this->db->get()->result_array();
    }


    // Mengecek apakah task_id sudah ada di database
    public function task_exists($where) {
        $this->db->where($where);
        $query = $this->db->get('student_response');
        
        return ($query->num_rows() > 0) ? true : false;
    }

    // Update task jika task_id sudah ada
    public function update_task($where, $data) {
        $this->db->where($where);
        $this->db->update('student_response', $data);
    }

    // Insert task jika task_id belum ada
    public function insert_task($data) {
        $this->db->insert('student_response', $data);
    }

}