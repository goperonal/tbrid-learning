<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Zoom_model extends CI_Model {

    public function __construct(){
        // Memanggil constructor dari parent class (CI_Model)
        parent::__construct();
        // Memuat database jika belum dimuat
        // $this->load->database();
    }
 
    public function is_table_empty() {
        $this->db->where('provider', 'zoom');
        $query = $this->db->get('zoom_oauth');
        return $query->num_rows() === 0;
    }
 
    public function get_access_token() {
        $this->db->select('provider_value');
        $this->db->from('zoom_oauth');
        $this->db->where('provider', 'zoom');
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $result = $query->row();
            return json_decode($result->provider_value);
        }

        return null;
    }
 
    public function get_refresh_token() {
        $accessToken = $this->get_access_token();
        return $accessToken->refresh_token ?? null;
    }
 
    public function update_access_token($token) {
        if ($this->is_table_empty()) {
            // Insert data baru jika tabel kosong
            $data = array(
                'provider' => 'zoom',
                'provider_value' => $token
            );
            $this->db->insert('zoom_oauth', $data);
        } else {
            // Update data jika sudah ada
            $data = array(
                'provider_value' => $token
            );
            $this->db->where('provider', 'zoom');
            $this->db->update('zoom_oauth', $data);
        }
    }
}
