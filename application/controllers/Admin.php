<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller 
{
    public function __construct()
    {
        parent::__construct();
        check_login();
        cek_login();
        cek_user();
    }

    public function index()
    {
        $data['title']      = 'Dashboard';
        $data['user']       = $this->User_model->cekData(['email' => $this->session->userdata('email')])->row_array();
        $data['anggota']    = $this->User_model->getUserLimit()->result_array();
        
        //mengupdate stok dan dibooking pada tabel buku
        $detail = $this->db->query("SELECT*FROM booking,booking_detail WHERE DAY(curdate()) < DAY(batas_ambil) AND booking.id_booking=booking_detail.id_booking")->result_array();
        foreach ($detail as $key) {
            $id_buku    = $key['id_buku'];
            $batas      = $key['tgl_booking'];
            $tglawal    = date_create($batas);
            $tglskrg    = date_create();
            $beda       = date_diff($tglawal, $tglskrg);
            if ($beda->days > 2) {
                $this->db->query("UPDATE buku SET stok=stok+1, dibooking=dibooking-1 WHERE id='$id_buku'");
            }
        }

        //menghapus otomatis data booking yang sudah lewat dari 2 hari
        $booking = $this->Booking_model->getData('booking');
        if (!empty($booking)) {
            foreach ($booking as $bo) {
                $id_booking = $booking->id_booking;
                $tglbooking = $booking->tgl_booking;
                $tglawal    = date_create($tglbooking);
                $tglskrg    = date_create();
                $beda       = date_diff($tglawal, $tglskrg);
                    if ($beda->days > 2) {
                        $this->db->query("DELETE FROM booking WHERE id_booking='$id_booking'");
                        $this->db->query("DELETE FROM booking_detail WHERE id_booking='$id_booking'");
                    }
            }
        }

        $this->load->view('templates/admin/header', $data);
        $this->load->view('templates/admin/sidebar', $data);
        $this->load->view('templates/admin/topbar', $data);
        $this->load->view('admin/index', $data);
        $this->load->view('templates/admin/footer');
    }

    public function role()
    {
        $data['title'] = 'Role';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        $data['role'] = $this->db->get('user_role')->result_array();
        
        $this->load->view('templates/admin/header', $data);
        $this->load->view('templates/admin/sidebar', $data);
        $this->load->view('templates/admin/topbar', $data);
        $this->load->view('admin/role/role', $data);
        $this->load->view('templates/admin/footer');
    }
    
    public function addrole()
    {
        $data['title'] = 'Role';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        $data['role'] = $this->db->get('user_role')->result_array();
        
        $this->form_validation->set_rules('role', 'Role', 'trim|required');
        
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('templates/admin/header', $data);
            $this->load->view('templates/admin/sidebar', $data);
            $this->load->view('templates/admin/topbar', $data);
            $this->load->view('admin/role/role', $data);
            $this->load->view('templates/admin/footer');
        } else {
            $this->Admin_model->addrole();
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
            Role berhasil ditambah!
            </div>'); 
            redirect('admin/role'); 
        }
        
    }

    public function editRole($id)
    {
        $data['title']      = 'Ubah Role';
        $data['user']       = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        $data['role']       = $this->db->get('user_role')->result_array();
        $data['user_role']  = $this->Admin_model->getUserById($id);
        
        $this->form_validation->set_rules('role', 'Role', 'trim|required');
        
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('templates/admin/header', $data);
            $this->load->view('templates/admin/sidebar', $data);
            $this->load->view('templates/admin/topbar', $data);
            $this->load->view('admin/role/edit-role', $data);
            $this->load->view('templates/admin/footer');
        } else {
            $this->Admin_model->editRole();
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
            Role berhasil diubah!
            </div>'); 
            redirect('admin/role'); 
        }
        
    }
    
    

    public function deleterole($id)
    {
        $where = ['id' => $this->uri->segment(3)]; 
        $this->Admin_model->deleterole($where);
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
        Role berhasil dihapus!
        </div>'); 
		redirect('admin/role'); 
    }

    public function roleAccess($role_id)
    {
        $data['title'] = 'Role Access';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        $data['role'] = $this->db->get_where('user_role', ['id' => $role_id])->row_array();
        
        $this->db->where('id !=', 1);
        $data['menu'] = $this->db->get('user_menu')->result_array();

        $this->load->view('templates/admin/header', $data);
        $this->load->view('templates/admin/sidebar', $data);
        $this->load->view('templates/admin/topbar', $data);
        $this->load->view('admin/role/role-access', $data);
        $this->load->view('templates/admin/footer');
    }

    public function changeAccess()
    {
        $menu_id = $this->input->post('menuId');
        $role_id = $this->input->post('roleId');

        $data = [
            'role_id' => $role_id,
            'menu_id' => $menu_id
        ];

        $result = $this->db->get_where('user_access_menu', $data);

        if ($result->num_rows() < 1) {
            $this->db->insert('user_access_menu', $data);
        } else {
            $this->db->delete('user_access_menu', $data);
        }

        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Akses diubah!</div>');
    }

}

/* End of file Admin.php */
