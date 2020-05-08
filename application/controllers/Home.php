<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

    public function index()
    {
        $data = [
            'title' => "Katalog Buku",
            'buku'  => $this->Buku_model->getBuku()->result(),
        ];

        if ($this->session->userdata('email')) 
        {
            $user = $this->User_model->cekData(['email' => $this->session->userdata('email')])->row_array();
            $data['user'] = $user['name'];

            $this->load->view('templates/user/header', $data);
            $this->load->view('buku/daftar-buku', $data);
            $this->load->view('templates/user/modal', );
            $this->load->view('templates/user/footer', $data);
        } else {
            $data['user'] = 'Pengunjung';
            $this->load->view('templates/user/header', $data);
            $this->load->view('buku/daftar-buku', $data);
            $this->load->view('templates/user/modal', );
            $this->load->view('templates/user/footer', $data);
        }
    }

    public function detailBuku()
    {
        $data['title']  = "Detail Buku";

        if ($this->session->userdata('email')) 
        {
            $id             = $this->uri->segment(3);
            $buku           = $this->Buku_model->joinKategoriBuku(['buku.id' => $id])->result();
            $user           = $this->User_model->cekData(['email' => $this->session->userdata('email')])->row_array();
            $data['user']   = $user['name'];

            foreach ($buku as $fields) 
            {
                $data['judul']      = $fields->judul_buku;
                $data['pengarang']  = $fields->pengarang;
                $data['penerbit']   = $fields->penerbit;
                $data['kategori']   = $fields->kategori;
                $data['tahun']      = $fields->tahun_terbit;
                $data['isbn']       = $fields->isbn;
                $data['gambar']     = $fields->image;
                $data['dipinjam']   = $fields->dipinjam;
                $data['dibooking']  = $fields->dibooking;
                $data['stok']       = $fields->stok;
                $data['id']         = $id;
            }
            
            $this->load->view('templates/user/header', $data);        
            $this->load->view('buku/detail-buku', $data);
            $this->load->view('templates/user/modal');
            $this->load->view('templates/user/footer', $data);

        }else{
            $id             = $this->uri->segment(3);
            $buku           = $this->Buku_model->joinKategoriBuku(['buku.id' => $id])->result();
            $data['user']   = "Pengunjung";

            foreach ($buku as $fields) 
            {
                $data['judul']      = $fields->judul_buku;
                $data['pengarang']  = $fields->pengarang;
                $data['penerbit']   = $fields->penerbit;
                $data['kategori']   = $fields->kategori;
                $data['tahun']      = $fields->tahun_terbit;
                $data['isbn']       = $fields->isbn;
                $data['gambar']     = $fields->image;
                $data['dipinjam']   = $fields->dipinjam;
                $data['dibooking']  = $fields->dibooking;
                $data['stok']       = $fields->stok;
                $data['id']         = $id;
            }

            $this->load->view('templates/user/header', $data);        
            $this->load->view('buku/detail-buku', $data);
            $this->load->view('templates/user/modal');
            $this->load->view('templates/user/footer', $data);
        }
    }

}
/* End of file Home.php */
