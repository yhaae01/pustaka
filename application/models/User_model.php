<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

    public function simpanData($data = null)
    {
        $this->db->insert('user', $data);
    }

    public function selectAllUser(Type $var = null)
    {
        $this->db->get('user');
    }

    public function getUserWhere($where = null)
    {
        return $this->db->get_where('user', $where);
    }

    public function hapusUser($where = null)
    {
        $this->db->delete('user', $where);
    }

    public function cekUserAccess($where = null)
    {
        $this->db->select('*');
        $this->db->form('access_menu');
        $this->db->limit('user');
        return $this->db->get();
    }

    public function getUserLimit()
    {
        $this->db->select('*');
        $this->db->from('user');
        $this->db->limit(10, 0);
        return $this->db->get();
    }

    public function cekData($where = null)
    {
        return $this->db->get_where('user', $where);
    }

}

/* End of file User_model.php */
