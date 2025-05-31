<?php

class E_class_detail_model extends CI_model{

    var $table = 'akun';
    
    //set column field database for datatable orderable
    var $column_order = array(null,'email'); 
    
    //set column field database for datatable searchable
    var $column_search = array('email');  

    // default order
    var $order = array('akun_id' => 'desc'); 

    private function _get_datatables_query()
    {
        $kelas_id = $this->uri->segment(4);
        $this->db->select("a.*, m.nim")
                 ->from('akun a')
                 ->join('mahasiswa m', 'm.akun_id = a.akun_id')
                 ->join('kelas k', 'k.kelas_id = m.kelas_id')
                 ->where('m.kelas_id', $kelas_id)
                 ->group_by('a.nama_depan');
            
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