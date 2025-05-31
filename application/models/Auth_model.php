<?php

class Auth_model extends CI_model{

    function registrasi($data)
    {
        $this->db->insert('akun',$data);
    }

    public function get_profile()
    {
        $where = array(
            'akun_id'    => $this->session->user_id
        );
        $this->db->where($where);
        return $this->db->get('akun')->row();
    }

}