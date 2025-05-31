<?php

class E_class_model extends CI_model{

    var $table = 'kelas';
    
    //set column field database for datatable orderable
    var $column_order = array(null,'nama_kelas','jumlah_mahasiswa','tutup'); 
    
    //set column field database for datatable searchable
    var $column_search = array('nama_kelas');  

    // default order
    var $order = array('nama_kelas' => 'desc'); 

    private function _get_datatables_query()
    {
        if($this->session->level_akses == "teacher"):
            $this->db->where("k.teacher_id", $this->session->user_id);
        else:
            $this->db->where("m.akun_id", $this->session->user_id);
        endif;
        $this->db->select("k.kelas_id, k.nama_kelas, 
                           COUNT(m.akun_id) as jumlah_mahasiswa, 
                           CASE WHEN k.tutup = 1 THEN 'lock' 
                                WHEN k.tutup = 2 THEN 'unlock' 
                                ELSE k.tutup END as tutup", false)
                 ->from('tbl_kelas k')
                 ->join('tbl_mahasiswa m', 'k.kelas_id = m.kelas_id', 'left')
                 ->group_by('k.kelas_id');
        
        $i = 0;
    
        foreach ($this->column_search as $item) // loop column 
        {
            if($_POST['search']['value']) // if datatable send POST for search
            {
                
                if($i===0) // first loop
                {
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_POST['search']['value']);
                }
                else
                {
                    $this->db->or_like($item, $_POST['search']['value']);
                }

                if(count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }
        
        if(isset($_POST['order'])) // here order processing
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