<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Model untuk Cread, Read, Update, dan Delete
 *
 * @package CMS Ilarabka
 * @link    http://www.sharecode.id
 * @author  Ali Akbar <aliakbaruncp@gmail.com>
 */
Class Crud_model extends CI_model{
    
    /**
     * Method untuk menambahkan data kedalam tabel
     * @param  string   $tabel  nama_tabelnya tanpa prefix
     * @param  array    $data
     * @return boolean
     */
    public function create($tabel,$data)
    {
    	$this->db->insert($tabel,$data);
        if($this->db->affected_rows()){
            return true;
        }
        else{
            return false;
        }
    }

    /**
     * Method untuk mengambil data dari tabel
     * @param  string       $tabel  nama_tabelnya tanpa prefix
     * @param  null|array   $where
     * @return array
     */
    public function read($tabel,$where=null)
    {
    	if($where!=null){
    		$this->db->where($where);
    	}
    	$record = $this->db->get($tabel);
    	return $record;
    }

    /**
     * Method untuk mengupdate data kedalam tabel
     * @param  string       $tabel  nama_tabelnya tanpa prefix
     * @param  array        $where
     * @param  array        $data
     * @return boolean
     */
    public function update($tabel,$where, $data)
    {
    	$this->db->where($where);
    	$this->db->update($tabel,$data);
        if($this->db->affected_rows()){
            return true;
        }
        else{
            return false;
        }
    }

    /**
     * Method untuk menghapus data dari tabel
     * @param  string       $tabel  nama_tabelnya tanpa prefix
     * @param  array        $where
     * @return boolean
     */
    public function delete($tabel,$where)
    {
    	$this->db->where($where);
    	$this->db->delete($tabel);
        if($this->db->affected_rows()){
            return true;
        }
        else{
            return false;
        }
    }

}