<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu extends CI_Controller 
{
    public function __construct()
    {
        parent::__construct();
        check_login();
    }

    public function index()
    {
        $data['title'] = 'Menu Management';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        $data['menu'] = $this->db->get('user_menu')->result_array();

        $this->form_validation->set_rules('menu', 'Menu', 'trim|required');
        
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('templates/admin/header', $data);
            $this->load->view('templates/admin/sidebar', $data);
            $this->load->view('templates/admin/topbar', $data);
            $this->load->view('admin/menu/index', $data);
            $this->load->view('templates/admin/footer');
        } else {
            $this->db->insert('user_menu', ['menu' => $this->input->post('menu')]);
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
            Menu baru ditambahkan!
            </div>');
            redirect('menu');
        }
    
    }

    public function SubMenu()
    {
        $data['title'] = 'Submenu Management';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        $data['menu'] = $this->db->get('user_menu')->result_array();
        // Nama model
        $this->load->model('Menu_model', 'menu');

        $data['subMenu'] = $this->menu->getSubMenu();
        $data['menu'] = $this->db->get('user_menu')->result_array();

        $this->form_validation->set_rules('title', 'Title', 'trim|required');
        $this->form_validation->set_rules('menu_id', 'Menu', 'trim|required');
        $this->form_validation->set_rules('url', 'Url', 'trim|required');
        $this->form_validation->set_rules('icon', 'Icon', 'trim|required');
        
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('templates/admin/header', $data);
            $this->load->view('templates/admin/sidebar', $data);
            $this->load->view('templates/admin/topbar', $data);
            $this->load->view('admin/menu/submenu', $data);
            $this->load->view('templates/admin/footer');
        } else {
            $data = [
                'title' => $this->input->post('title'),
                'menu_id' => $this->input->post('menu_id'),
                'url' => $this->input->post('url'),
                'icon' => $this->input->post('icon'),
                'is_active' => $this->input->post('is_active')
            ];
            $this->db->insert('user_sub_menu', $data);
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
            Submenu baru ditambahkan!
            </div>');
            redirect('menu/submenu');
        }
        
    }

    public function deletemenu() 
	{ 
		$where = ['id' => $this->uri->segment(3)]; 
        $this->Menu_model->deletemenu($where);
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
        Menu berhasil dihapus!
        </div>'); 
		redirect('menu'); 
    }
    
    public function deletesubmenu() 
	{ 
		$where = ['id' => $this->uri->segment(3)]; 
        $this->Menu_model->deletesubmenu($where);
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
        Menu berhasil dihapus!
        </div>'); 
		redirect('menu/submenu'); 
	}

}

/* End of file Menu.php */
