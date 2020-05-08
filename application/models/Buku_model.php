<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Buku_model extends CI_Model {

    public function getBuku()
    {
        return $this->db->get('buku');
    }

    public function getBukuPag($limit, $start)
    {
        return $this->db->get('buku', $limit, $start)->result_array();
    }

    public function countAllBook()
    {
        return $this->db->get('buku')->num_rows();
    }

    public function bukuWhere($where)
    {
        return $this->db->get_where('buku', $where);
    }

    public function simpanBuku($data = null) 
    { 
        $this->db->insert('buku',$data); 
    }

    public function updateBuku($data = null, $where= null)
    {
        $this->db->update('buku',$data, $where);
    }

    public function hapusBuku($where = null)
    {
        $this->db->delete('buku', $where);
    }

    public function total($field, $where)
    {
        $this->db->select_sum($field);
        if(!empty($where) && count($where) > 0){
            $this->db->where($where);
        }
        $this->db->from('buku');
        return $this->db->get()->row($field);
    }

    //manajemen kategori
    public function getKategori()
    {
        return $this->db->get('kategori');
    }

    public function kategoriWhere($where)
    {
        return $this->db->get_where('kategori', $where);
    }

    public function simpanKategori($data = null)
    {
        $this->db->insert('kategori', $data);
    }

    public function hapusKategori($where = null)
    {
        $this->db->delete('kategori', $where);
    }

    public function updateKategori($where = null, $data = null)
    {
        $this->db->update('kategori', $data, $where);
    }

    //join
    public function joinKategoriBuku($where)
    {
        $this->db->select('*');
        $this->db->from('buku');
        $this->db->join('kategori', 'kategori.id_kategori = buku.id_kategori');
        $this->db->where($where);
        return $this->db->get();
    }

}

/* End of file BukuModel.php */
