<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan extends CI_Controller {

    public function index()
    {
        
    }

    public function laporan_buku()
    {
        $data['title']      = 'Laporan Data Buku';
        $data['user']       = $this->User_model->cekData(['email' => $this->session->userdata('email')])->row_array();
        $data['buku']       = $this->Buku_model->getBuku()->result_array();
        $data['kategori']   = $this->Buku_model->getKategori()->result_array();

        $this->load->view('templates/admin/header', $data);
        $this->load->view('templates/admin/sidebar', $data);
        $this->load->view('templates/admin/topbar', $data);
        $this->load->view('buku/laporan-buku', $data);
        $this->load->view('templates/admin/footer');
    }

    public function CetakLaporan()
    {
        $data['buku']       = $this->Buku_model->getBuku()->result_array();
        $data['kategori']   = $this->Buku_model->getKategori()->result_array();

        $this->load->view('buku/laporan-print-buku', $data);
    }

}

/* End of file Laporan.php */
