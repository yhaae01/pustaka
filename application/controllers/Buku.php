<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Buku extends CI_Controller {

    public function index()
    {
        $data['title']      = 'Data Buku';
        $data['user']       = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        // Menggunakan pagination
        // CONFIG
        $config['base_url'] = 'http://localhost/pustaka/buku/index';
        $config['total_rows'] = $this->Buku_model->countAllBook();
        $config['per_page'] = 5;
        // STYLE
        $config['full_tag_open'] = '<nav aria-label="Page navigation example"><ul class="pagination justify-content-center">';
        $config['full_tag_close'] = '</ul> </nav>';
        $config['first_link']   = 'First';
        $config['first_tag_open'] = '<li class="page-item">';
        $config['first_tag_close'] = '</li">';
        $config['last_link']   = 'Last';
        $config['last_tag_open'] = '<li class="page-item">';
        $config['last_tag_close'] = '</li">';
        $config['next_link']   = '&raquo';
        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_tag_close'] = '</li">';
        $config['prev_link']   = '&laquo';
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tag_close'] = '</li">';
        $config['cur_tag_open'] = '<li class="page-item active"><a class="page-link" href="#">';
        $config['cur_tag_close'] = '</a></li">';
        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li">';
        $config['attributes'] = array('class' => 'page-link');
        // INITIALIZE
        $this->pagination->initialize($config);
        // EKSEKUSI
        $data['start']      = $this->uri->segment(3);
        $data['buku']       = $this->Buku_model->getBukuPag($config['per_page'], $data['start']);
        
        $data['anggota']    = $this->User_model->getUserLimit()->result_array();

        $this->load->view('templates/admin/header', $data);
        $this->load->view('templates/admin/sidebar', $data);
        $this->load->view('templates/admin/topbar', $data);
        $this->load->view('admin/buku/index', $data);
        $this->load->view('templates/admin/footer');
    }

}

/* End of file Buku.php */
