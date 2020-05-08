<?php

defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

class Booking extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        cek_login();
    }

    public function index()
    {
        $id = ['bo.id_user' => $this->uri->segment(3)];
        $id_user = $this->session->userdata('id_user');
        $data['booking'] = $this->Booking_model->joinOrder($id)->result();
        $user = $this->User_model->cekData(['email' => $this->session->userdata('email')])->row_array();
        
        foreach ($user as $a) {
            $data = [
                'image'         => $user['image'],
                'user'          => $user['name'],
                'email'         => $user['email'],
                'date_created'  => $user['date_created']
            ];
        }
        $dtb = $this->Booking_model->showtemp(['id_user' => $id_user])->num_rows();
        
        if ($dtb < 1) {
            $this->session->set_flashdata('message', '<div class="alert alert-massage alert-danger" role="alert">Tidak Ada Buku dikeranjang</div>');
            redirect(base_url());
        } else {
            $data['temp'] = $this->db->query("select image, judul_buku, penulis, penerbit, tahun_terbit,id_buku from temp where id_user='$id_user'")->result_array();
        }

        $data['title'] = "Data Booking";
        $this->load->view('templates/user/header', $data);
        $this->load->view('booking/data-booking', $data);
        $this->load->view('templates/user/modal');
        $this->load->view('templates/user/footer');
    }

    public function tambahBooking()
    {
        $id_buku = $this->uri->segment(3);
        //memilih data buku yang untuk dimasukkan ke tabel temp/keranjang melalui variabel $isi
        $d = $this->db->query("Select*from buku where id='$id_buku'")->row();
        //berupa data2 yang akan disimpan ke dalam tabel temp/keranjang
        $isi = [
            'id_buku'       => $id_buku,
            'judul_buku'    => $d->judul_buku,
            'id_user'       => $this->session->userdata('id_user'),
            'email_user'    => $this->session->userdata('email'),
            'tgl_booking'   => date('Y-m-d H:i:s'),
            'image'         => $d->image,
            'penulis'       => $d->pengarang,
            'penerbit'      => $d->penerbit,
            'tahun_terbit'  => $d->tahun_terbit
        ];

        //cek apakah buku yang di klik booking sudah ada di keranjang40
        $temp = $this->Booking_model->getDataWhere('temp', ['id_buku' => $id_buku])->num_rows();
        $userid = $this->session->userdata('id_user');
        
        //cek jika sudah memasukan 3 buku untuk dibooking dalam keranjang
        $tempuser = $this->db->query("select*from temp where id_user ='$userid'")->num_rows();
        
        //cek jika masih ada booking buku yang belum diambil
        $databooking = $this->db->query("select*from booking where id_user='$userid'")->num_rows();
        if ($databooking > 0) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger alert-message" role="alert">Masih Ada booking buku sebelumnya yang belum diambil.<br> Abmil Buku yang dibooking atau tunggu 1x24 Jam untuk bisa booking kembali </div>');
            redirect(base_url());
        } 
        
        //jika buku yang diklik booking sudah ada di keranjang
        if ($temp > 0) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger alert-message" role="alert">Buku ini Sudah anda booking </div>');
            redirect(base_url() . 'home');
        }
        //jika buku yang akan dibooking sudah mencapai 3 item
        if ($tempuser == 3) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger alert-message" role="alert">Booking Buku Tidak Boleh Lebih dari 3</div>');
            redirect(base_url() . 'home');
        }

        //membuat tabel temp jika belum ada
        $this->Booking_model->createTemp();
        $this->Booking_model->insertData('temp', $isi);

        //message ketika berhasil memasukkan buku ke keranjang
        $this->session->set_flashdata('message', '<div class="alert alert-success alert-message" role="alert">Buku berhasil ditambahkan ke keranjang </div>');
        redirect(base_url() . 'home');
    }

    public function hapusbooking()
    {
        $id_buku = $this->uri->segment(3);
        $id_user = $this->session->userdata('id_user');
        $this->Booking_model->deleteData(['id_buku' => $id_buku], 'temp');
        $kosong = $this->db->query("select*from temp where id_user='$id_user'")->num_rows();
        if ($kosong < 1) {
            $this->session->set_flashdata('message', '<div class="alert alert-massage alert-danger" role="alert">Tidak Ada Buku dikeranjang</div>');
            redirect(base_url());
        } else {
            redirect(base_url() . 'booking');
        }
    }

    public function bookingSelesai($where)
    {
        //mengupdate stok dan dibooking di tabel buku saat proses booking diselesaikan
        $this->db->query("UPDATE buku, temp SET buku.dibooking=buku.dibooking+1, buku.stok=buku.stok-1 WHERE buku.id=temp.id_buku");
        $tglsekarang = date('Y-m-d');
        $isibooking = [
            'id_booking'    => $this->Booking_model->kodeOtomatis('booking', 'id_booking'),
            'tgl_booking'   => date('Y-m-d H:m:s'),
            'batas_ambil'   => date('Y-md', strtotime('+2 days', strtotime($tglsekarang))),
            'id_user'       => $where
        ];

        //menyimpan ke tabel booking dan detail booking, dan mengosongkan tabel temporari
        $this->Booking_model->insertData('booking', $isibooking);
        $this->Booking_model->simpanDetail($where);
        $this->Booking_model->kosongkanData('temp');
        redirect(base_url() . 'booking/info');
    }

    public function info()
    {
        $where = $this->session->userdata('id_user');
        $data['user'] = $this->session->userdata('name');
        $data['title'] = "Selesai Booking";
        $data['useraktif'] = $this->User_model->cekData(['id' => $this->session->userdata('id_user')])->result();
        $data['items'] = $this->db->query("select*from booking bo, booking_detail d, buku bu where d.id_booking=bo.id_booking and d.id_buku=bu.id and bo.id_user='$where'")->result_array();
        
        $this->load->view('templates/user/header', $data);
        $this->load->view('booking/info-booking', $data);
        $this->load->view('templates/user/modal');
        $this->load->view('templates/user/footer');
    }

    public function exportToPdf()
    {
        $id_user            = $this->session->userdata('id_user');
        $data['user']       = $this->session->userdata('name');
        $data['title']      = "Cetak Bukti Booking";
        $data['useraktif']  = $this->User_model->cekData(['id' => $this->session->userdata('id_user')])->result();
        $data['items']      = $this->db->query("select*from booking bo, booking_detail d, buku bu where d.id_booking=bo.id_booking and d.id_buku=bu.id and bo.id_user='$id_user'")->result_array();
        
        $this->load->library('dompdf_gen');
        $this->load->view('booking/bukti-pdf', $data);
        
        $paper_size = 'A4'; // ukuran kertas
        $orientation = 'landscape'; //tipe format kertas potrait atau landscape
        $html = $this->output->get_output();
        $this->dompdf->set_paper($paper_size, $orientation);
        //Convert to PDF
        $this->dompdf->load_html($html);
        $this->dompdf->render();
        ob_end_clean();
        $this->dompdf->stream("bukti-booking-$id_user.pdf", array('Attachment' => 0));
        // name file pdf yang di hasilkan
    }

}

/* End of file Booking.php */
