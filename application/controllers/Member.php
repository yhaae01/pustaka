<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Member extends CI_Controller {

    public function index()
    {
        $this->_login();
    }

    private function _login()
    {
        $email = htmlspecialchars($this->input->post('email', true));
        $password = $this->input->post('password', true);
        $user = $this->User_model->cekData(['email' => $email])->row_array();
        
        //jika usernya ada
        if ($user) {
        //jika user sudah aktif
        if ($user['is_active'] == 1) {
        
            //cek password
            if (password_verify($password, $user['password'])) {
                $data = [
                    'email'     => $user['email'],
                    'role_id'   => $user['role_id'],
                    'id_user'   => $user['id'],
                    'name'      => $user['name']
                ];
                $this->session->set_userdata($data);
                redirect('home');

                } else {
                    $this->session->set_flashdata('message', '<div class="alert alert-danger alert-message" role="alert">Password salah!!</div>');
                    redirect('member/login');
                }

            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger alert-message" role="alert">User belum diaktifasi!!</div>');
                redirect('member/login');
            }

        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger alert-message" role="alert">Email tidak terdaftar!!</div>');
            redirect('member/login');
        }
    }

    public function login()
    {
        if ($this->session->userdata('email')) 
        {
            $user = $this->User_model->cekData(['email' => $this->session->userdata('email')])->row_array();
            $data['user'] = $user['name'];
            $data['title'] = "Login";
            
            $this->load->view('templates/user/header', $data);
            $this->load->view('member/login-anggota', $data);
            $this->load->view('templates/user/footer', $data);
        } else {
            $data['user'] = 'Pengunjung';
            $data['title'] = "Login Anggota";
            $this->load->view('templates/user/header', $data);
            $this->load->view('member/login-anggota', $data);
            $this->load->view('templates/user/footer', $data);
        }
    }

    public function daftarView()
    {
        if ($this->session->userdata('email')) 
        {
            $user = $this->User_model->cekData(['email' => $this->session->userdata('email')])->row_array();
            $data['user'] = $user['name'];
            $data['title'] = "Daftar";
            
            $this->load->view('templates/user/header', $data);
            $this->load->view('member/daftar-anggota', $data);
            $this->load->view('templates/user/footer', $data);
        } else {
            $data['user'] = 'Pengunjung';
            $data['title'] = "Daftar Anggota";
            $this->load->view('templates/user/header', $data);
            $this->load->view('member/daftar-anggota', $data);
            $this->load->view('templates/user/footer', $data);
        }
    }

    public function daftar()
    {
        $this->form_validation->set_rules('name', 'Nama Lengkap', 'required', [
            'required'      => 'Nama Belum diisi!'
        ]);

        $this->form_validation->set_rules('address', 'Alamat Lengkap', 'required', [
            'required'      => 'Alamat Belum diisi!'
        ]);

        $this->form_validation->set_rules('email', 'Alamat Email', 'required|trim|valid_email|is_unique[user.email]', [
            'valid_email'   => 'Email Tidak Benar!',
            'required'      => 'Email Belum diisi!',
            'is_unique'     => 'Email Sudah Terdaftar!'
        ]);
        
        $this->form_validation->set_rules('password1', 'Password', 'required|trim|min_length[3]|matches[password2]', [
            'matches'       => 'Password Tidak Sama!',
            'min_length'    => 'Password Terlalu Pendek!',
            'required'      => 'Password Belum diisi!'
        ]);

        $this->form_validation->set_rules('password2', 'Repeat Password', 'required|trim|matches[password1]',[
            'required'      => 'Password Belum diisi!'
        ]);
        
        
        if ($this->form_validation->run() == FALSE) {
            if ($this->session->userdata('email')) 
            {
                $user = $this->User_model->cekData(['email' => $this->session->userdata('email')])->row_array();
                $data['user'] = $user['name'];
                $data['title'] = "Daftar";
                
                $this->load->view('templates/user/header', $data);
                $this->load->view('member/daftar-anggota', $data);
                $this->load->view('templates/user/footer', $data);
            } else {
                $data['user'] = 'Pengunjung';
                $data['title'] = "Daftar";
                $this->load->view('templates/user/header', $data);
                $this->load->view('member/daftar-anggota', $data);
                $this->load->view('templates/user/footer', $data);
            }
        } else {
            $email = $this->input->post('email', true);
            $data = [
                'name'          => htmlspecialchars($this->input->post('name', true)),
                'address'       => $this->input->post('address', true),
                'email'         => htmlspecialchars($email),
                'image'         => 'default.jpg',
                'password'      => password_hash($this->input->post('password1'), PASSWORD_DEFAULT),
                'role_id'       => 2,
                'is_active'     => 1,
                'date_created'  => time()
            ];
    
            $this->User_model->simpanData($data);
            $this->session->set_flashdata('message', '<div class="alert alert-success alert-message" role="alert">Selamat!! akun anggota anda sudah dibuat.</div>');
            redirect(base_url());
        }
        

    }

    public function myProfil()
    {
        $data['title'] = 'Profil Saya';
        $user = $this->User_model->cekData(['email' => $this->session->userdata('email')])->row_array();
        
        foreach ($user as $a) {
            $data = [
                'image'         => $user['image'],
                'user'          => $user['name'],
                'email'         => $user['email'],
                'date_created'  => $user['date_created'],
                ];
        }

        $this->load->view('templates/user/header', $data);
        $this->load->view('member/index', $data);
        $this->load->view('templates/user/modal');
        $this->load->view('templates/user/footer', $data);
    }

    public function ubahProfil()
    {
    $data['title'] = 'Profil Saya';
    $user = $this->User_model->cekData(['email' => $this->session->userdata('email')])->row_array();

    foreach ($user as $a) {
        $data = [
            'image'         => $user['image'],
            'user'          => $user['name'],
            'email'         => $user['email'],
            'date_created'  => $user['date_created'],
        ];
    }
    $this->form_validation->set_rules('name', 'Nama Lengkap', 'required|trim', [
        'required' => 'Nama tidak Boleh Kosong'
    ]);

        if ($this->form_validation->run() == false) 
        {
            $this->load->view('templates/user/header', $data);
            $this->load->view('member/ubah-anggota', $data);
            $this->load->view('templates/user/modal');
            $this->load->view('templates/user/footer', $data);
        } else {
            $name = $this->input->post('name', true);
            $email = $this->input->post('email', true);

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

                $this->session->set_flashdata('message', '<div class="alert alert-success alert-message" role="alert">Profil Berhasil diubah </div>');
                redirect('member/myprofil');
        }
    }

    public function logout()
    {
        $this->session->unset_userdata('email');
        $this->session->unset_userdata('role_id');
        $this->session->set_flashdata('message', '<div class="alert alert-success alert-message" role="alert">Anda telah logout!!</div>');
        redirect('home');
    }

}

/* End of file Member.php */
