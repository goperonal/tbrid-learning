<?php

class Live_chat_model extends CI_model{

    function get_chat($sub_kelas_id)
    {
        $this->db->select("c.*, a.nama_depan, a.nama_belakang")
                 ->from('live_chat c')
                 ->join('akun a', 'a.akun_id = c.user_id')
                 ->where("c.sub_kelas_id", $sub_kelas_id);
        return $this->db->get();
    }

}