<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller 
{
    public function __construct()
    {
        parent::__construct();
        check_login();
    }

    public function index()
    {
        $data['title'] = 'My Profile';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        
        $this->load->view('templates/admin/header', $data);
        $this->load->view('templates/admin/sidebar', $data);
        $this->load->view('templates/admin/topbar', $data);
        $this->load->view('admin/user/index', $data);
        $this->load->view('templates/admin/footer');
    }

    public function edit()
    {
        $data['title'] = 'Edit Profile';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        $this->form_validation->set_rules('name', 'Full Name', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('templates/admin/header', $data);
            $this->load->view('templates/admin/sidebar', $data);
            $this->load->view('templates/admin/topbar', $data);
            $this->load->view('admin/user/edit', $data);
            $this->load->view('templates/admin/footer');
        } else {
            $name = $this->input->post('name');
            $email = $this->input->post('email');

            // cek jika ada gambar yang akan diupload
            $upload_image = $_FILES['image']['name'];

            if ($upload_image) {
                $config['allowed_types'] = 'gif|jpg|png';
                $config['max_size']      = '2048';
                $config['upload_path']   = './assets/img/profile/';

                $this->load->library('upload', $config);

                if ($this->upload->do_upload('image')) {
                    $old_image = $data['user']['image'];
                    if ($old_image != 'default.jpg') {
                        unlink(FCPATH . 'assets/img/profile/' . $old_image);
                    }
                    $new_image = $this->upload->data('file_name');
                    $this->db->set('image', $new_image);
                } else {
                    echo $this->upload->dispay_errors();
                }
            }

            // Update nama
            $this->db->set('name', $name);
            $this->db->where('email', $email);
            $this->db->update('user');
            
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
            Your profile has ben updated!
            </div>');
            redirect('user');
        }
    }

    public function changepassword()
    {
        $data['title'] = 'Change Password';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        
        $this->form_validation->set_rules('current_password', 'Current Password', 'trim|required',[
            'required'      => 'Harus diisi!'
        ]);
        $this->form_validation->set_rules('new_password1', 'New Password', 'trim|required|min_length[5]|matches[new_password2]',[
            'required'      => 'Harus diisi!',
            'min_length'    => 'Minimal 5 karakter',
            'matches'       => 'Password tidak sama'
        ]);
        $this->form_validation->set_rules('new_password2', 'Confirm Password', 'trim|required|min_length[5]|matches[new_password1]',[
            'required'      => 'Harus diisi!',
            'min_length'    => 'Minimal 5 karakter'
        ]);
        
        
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('templates/admin/header', $data);
            $this->load->view('templates/admin/sidebar', $data);
            $this->load->view('templates/admin/topbar', $data);
            $this->load->view('admin/user/change-password', $data);
            $this->load->view('templates/admin/footer');
        } else {
            $current_password = $this->input->post('current_password');
            $new_password = $this->input->post('new_password1');

            // Kalau passwordnya salah
            if(!password_verify($current_password, $data['user']['password'])){
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
                Password sebelumnya salah!
                </div>');
                redirect('user/changepassword');
            } else {
                // Kalau password sama dengan yang lama
                if($current_password == $new_password){
                    $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
                    Password baru tidak boleh sama dengan password sebelumnya!
                    </div>');
                    redirect('user/changepassword');
                } else {
                    $password_hash = password_hash($new_password, PASSWORD_DEFAULT);

                    $this->db->set('password', $password_hash);
                    $this->db->where('email', $this->session->userdata('email'));
                    $this->db->update('user');

                    $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
                    Password berhasil diubah!
                    </div>');
                    redirect('user/changepassword');
                }
            }
        }
    }

}

/* End of file User.php */
