<?php

    function check_login()
    {
        $ci = get_instance();
        
        if (!$ci->session->userdata('email')) {
            redirect('auth');
        } else {
            $role_id = $ci->session->userdata('role_id');
            $menu = $ci->uri->segment(1);

            $query = $ci->db->get_where('user_menu', ['menu' => $menu])->row_array();
            $menu_id = $query['id'];

            $userAccess = $ci->db->get_where('user_access_menu', [
                'role_id' => $role_id, 
                'menu_id' => $menu_id
            ]);

            if ($userAccess->num_rows() < 1) {
                redirect('auth/blocked');
            }
        }
    }

    function check_access($role_id, $menu_id)
    {
        $ci = get_instance();

        $ci->db->where('role_id', $role_id);
        $ci->db->where('menu_id', $menu_id);
        $result = $ci->db->get('user_access_menu');

        if($result->num_rows() > 0){
            return "checked=checked";
        }
    }

    function cek_login()
    {
        $ci = get_instance();

        if (!$ci->session->userdata('email')) {
            $ci->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Akses ditolak. Silahkah login terlebih dahulu!! </div>');
            if ($ci->session->userdata('role_id') == 1) {
                redirect('auth');
            } else {
                redirect('home');
            }
        } else {
            $role_id = $ci->session->userdata('role_id');
            $id_user = $ci->session->userdata('id_user');
        }
    }

    function cek_user()
    {
        $ci = get_instance();
        $role_id = $ci->session->userdata('role_id');
        
        if ($role_id != 1) {
            $ci->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Akses tidak diizinkan </div>');
            redirect('home');
        }
    }

