<?php

class Assignment_model extends CI_model{

    var $table = 'assignment';
    var $column_order = array(null,'assignment','kelas_id','aktif','created_date','due_date'); 
    var $column_search = array('assignment');  
    var $order = array('assignment_id' => 'desc'); 

    private function _get_datatables_query()
    {
        if($this->session->level_akses == "teacher"):
            $this->db->select("a.*, k.nama_kelas")
                     ->from('assignment a')
                     ->join('kelas k', 'k.kelas_id = a.kelas_id')
                     ->where("k.teacher_id", $this->session->user_id);
        else:
            $this->db->select('a.*, k.nama_kelas')
                     ->from('assignment a')
                     ->join('mahasiswa m', 'm.kelas_id = a.kelas_id')
                     ->join('kelas k', 'k.kelas_id = a.kelas_id')
                     ->where('m.akun_id', $this->session->user_id);
        endif;

        if (!empty($_POST['kelas'])) {
            $this->db->like('k.nama_kelas', $_POST['kelas']);
        }

        if (!empty($_POST['active'])) {
            $this->db->where('a.aktif', $_POST['active']);
        }

        if (!empty($_POST['created_date'])) {
            $this->db->where('DATE(a.crated_date)', $_POST['created_date']);
        }

        if (!empty($_POST['due_date'])) {
            $this->db->where('DATE(a.due_date)', $_POST['due_date']);
        }
        
        $i = 0;
    
        foreach ($this->column_search as $item)
        {
            if($_POST['search']['value'])
            {
                
                if($i===0)
                {
                    $this->db->group_start();
                    $this->db->like($item, $_POST['search']['value']);
                }
                else
                {
                    $this->db->or_like($item, $_POST['search']['value']);
                }

                if(count($this->column_search) - 1 == $i)
                    $this->db->group_end();
            }
            $i++;
        }
        
        if(isset($_POST['order']))
        {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } 
        else if(isset($this->order))
        {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function get_datatables()
    {
        $this->_get_datatables_query();
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all()
    {
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

}