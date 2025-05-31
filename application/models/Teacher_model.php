<?php

class Teacher_model extends CI_model{

    public function generate_unique_code($table, $field, $length = 6) {
        
        $is_unique = false;

        do {
            
            $code = random_string('numeric', $length);
            
            $this->db->where($field, $code);
            $query = $this->db->get($table);

            if ($query->num_rows() == 0) {
                $is_unique = true;
            }
        } while (!$is_unique);

        return $code;
    }

    public function simpan_kelas($data) {
        $data['kelas_id'] = $this->generate_unique_code('kelas', 'kelas_id');

        // Simpan data ke database
        $this->db->insert('kelas', $data);

        return $data['kelas_id'];
    }

    public function get_kelas($where = "")
    {
        $this->db->where($where);
        $this->db->select('k.*, sk.kelas_id, sk.nama_sub_kelas, sk.sub_kelas_id')
                 ->from('kelas k')
                 ->join('sub_kelas sk','sk.kelas_id = k.kelas_id')
                 ->order_by('k.nama_kelas');
        $results = $this->db->get();

        $output = array();
        foreach ($results->result() as $result) {
            $key = $result->kelas_id;
            
            if (!array_key_exists($key, $output)) {
                $output[$key] = array(
                    'kelas_id' => $result->kelas_id,
                    'teacher_id' => $result->teacher_id,
                    'nama_kelas' => $result->nama_kelas,
                    'materi' => $result->materi,
                    'tutup' => $result->tutup,
                    'sub_kelas' => array()
                );
            }
            
            $output[$key]['sub_kelas'][] = array(
                'sub_kelas_id'      => $result->sub_kelas_id,
                'nama_sub_kelas'    => $result->nama_sub_kelas
            );
        }

        foreach ($output as &$kelas) {
            usort($kelas['sub_kelas'], function($a, $b) {
                return $a['sub_kelas_id'] - $b['sub_kelas_id'];
            });
        }
        
        $output = array_values($output);
        return $output;
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
        $this->db->select("a.*, k.nama_kelas, ar.as_id, ar.nilai, ar.file_name, ar.question_id")
                 ->from('assignment a')
                 ->join('kelas k', 'k.kelas_id = a.kelas_id')
                 ->join('assignment_response ar', 'ar.assignment_id = a.assignment_id', 'left')
                 ->where("a.assignment_id", $id);
        return $this->db->get()->result_array();
    }

    public function get_assignment_by_student_ID($assignment_id,$akun_id)
    {
        $this->db->select("a.*, k.nama_kelas, ar.as_id, ar.nilai, ar.file_name, ar.question_id")
                 ->from('assignment a')
                 ->join('kelas k', 'k.kelas_id = a.kelas_id')
                 ->join('assignment_response ar', 'ar.assignment_id = a.assignment_id', 'left')
                 ->where("a.assignment_id", $assignment_id)
                 ->where("ar.akun_id", $akun_id);
        return $this->db->get()->result_array();
    }

    public function get_assignment_mhs($kelas_id,$assignment_id)
    {
        $this->db->distinct('m.nim')
                 ->select("m.*, a.nama_depan, a.nama_belakang")
                 ->from('mahasiswa m')
                 ->join('akun a', 'a.akun_id = m.akun_id')
                 ->join('assignment_response ar', 'ar.akun_id = a.akun_id', 'right')
                 ->where("m.kelas_id", $kelas_id)
                 ->where("ar.assignment_id", $assignment_id);
        return $this->db->get();
    }

}