<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan extends CI_Controller {

    
    public function __construct()
    {
        parent::__construct();
        $this->load->library('dompdf_gen');
        
    }
    

    public function index()
    {
        
    }

    public function laporan_buku()
    {
        $data['title']      = 'Laporan Data Buku';
        $data['user']       = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        // Menggunakan pagination
        // CONFIG
        $config['base_url'] = 'http://localhost/pustaka/laporan/laporan_buku';
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

        $this->load->view('templates/admin/header', $data);
        $this->load->view('templates/admin/sidebar', $data);
        $this->load->view('templates/admin/topbar', $data);
        $this->load->view('buku/laporan-buku', $data);
        $this->load->view('templates/admin/footer');


        // $data['title']      = 'Laporan Data Buku';
        // $data['user']       = $this->User_model->cekData(['email' => $this->session->userdata('email')])->row_array();
        // $data['buku']       = $this->Buku_model->getBuku()->result_array();
        // $data['kategori']   = $this->Buku_model->getKategori()->result_array();

        // $this->load->view('templates/admin/header', $data);
        // $this->load->view('templates/admin/sidebar', $data);
        // $this->load->view('templates/admin/topbar', $data);
        // $this->load->view('buku/laporan-buku', $data);
        // $this->load->view('templates/admin/footer');
    }

    public function CetakLaporan()
    {
        $data['buku']       = $this->Buku_model->getBuku()->result_array();
        $data['kategori']   = $this->Buku_model->getKategori()->result_array();

        $this->load->view('buku/laporan-print-buku', $data);
    }

    public function LaporanPDF()
    {
        $data['buku']   = $this->Buku_model->getBuku()->result_array();
        
        $this->load->library('dompdf_gen');
        $this->load->view('buku/laporan-pdf', $data);
        
        $paper_size = 'A4';
        $orientation = 'landscape';
        $html = $this->output->get_output();
        $this->dompdf->set_paper($paper_size, $orientation);
        
        // Convert to PDF
        $this->dompdf->load_html($html);
        $this->dompdf->render();
        ob_end_clean();
        $this->dompdf->stream("laporan_data_buku.pdf", array('Attachment' => 0));
    }

    public function LaporanExcel()
    {
        $data = array (
            'title' => 'Laporan Buku',
            'buku'  => $this->Buku_model->getBuku()->result_array()
        );
        
        $this->load->view('buku/laporan-excel', $data);
        
    }

    public function laporan_peminjaman()
    {
        $data['title']      = 'Laporan Data Peminjaman';
        $data['user']       = $this->User_model->cekData(['email' => $this->session->userdata('email')])->row_array();
        $data['laporan']    = $this->db->query("SELECT * FROM pinjam p, pinjam_detail d, buku b, user u
                                                        WHERE d.id_buku = d.id_buku = b.id
                                                          AND p.no_pinjam = d.no_pinjam")->result_array();

        $this->load->view('templates/admin/header', $data);
        $this->load->view('templates/admin/sidebar', $data);
        $this->load->view('templates/admin/topbar', $data);
        $this->load->view('admin/pinjam/laporan-peminjaman', $data);
        $this->load->view('templates/admin/footer');
        
    }

    public function cetak_laporan_pinjam()
    {
        $data['laporan'] = $this->db->query("SELECT * FROM pinjam p,pinjam_detail d, buku b,user u 
                                                     WHERE d.id_buku=b.id 
                                                       AND p.id_user=u.id 
                                                       AND p.no_pinjam=d.no_pinjam")->result_array();
        
        $this->load->view('admin/pinjam/laporan-print-pinjam', $data);
    }

    public function laporan_pinjam_pdf()
    {
        {
        $this->load->library('dompdf_gen');
        $data['laporan'] = $this->db->query("SELECT * FROM pinjam p,pinjam_detail d, buku b,user u 
                                                     WHERE d.id_buku=b.id 
                                                       AND p.id_user=u.id 
                                                       AND p.no_pinjam=d.no_pinjam")->result_array();
        
        $this->load->view('admin/pinjam/laporan-pdf-pinjam', $data);
        
        $paper_size = 'A4'; // ukuran kertas
        $orientation = 'landscape'; //tipe format kertas potrait atau landscape
        $html = $this->output->get_output();
        $this->dompdf->set_paper($paper_size, $orientation);
        
        //Convert to PDF
        $this->dompdf->load_html($html);
        $this->dompdf->render();
        ob_end_clean();
        $this->dompdf->stream("laporan data peminjaman.pdf", array('Attachment' => 0));
        // nama file pdf yang di hasilkan
        }
    }

    public function export_excel_pinjam()
    {
        $data = array( 
            'title'     => 'Laporan Data Peminjaman Buku', 
            'laporan'   => $this->db->query("SELECT * FROM pinjam p,pinjam_detail d, buku b,user u 
                                                     WHERE d.id_buku=b.id 
                                                       AND p.id_user=u.id 
                                                       AND p.no_pinjam=d.no_pinjam")->result_array());
                                                       
        $this->load->view('admin/pinjam/export-excel-pinjam', $data);
    }

}

/* End of file Laporan.php */
