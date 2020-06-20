<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Pinjam extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        cek_login();
        cek_user();
    }
    
    public function index()
    {
        $data['title']  = "Data Peminjaman";
        $data['user']   = $this->User_model->cekData(['email' => $this->session->userdata('email')])->row_array();
        $data['pinjam'] = $this->Pinjam_model->joinData();

        $this->load->view('templates/admin/header', $data);
        $this->load->view('templates/admin/sidebar', $data);
        $this->load->view('templates/admin/topbar', $data);
        $this->load->view('booking/data-pinjam', $data);
        $this->load->view('templates/admin/footer');
    }

    public function daftarBooking()
    {
        $data['title'] = "Data Booking";
        $data['user'] = $this->User_model->cekData(['email' => $this->session->userdata('email')])->row_array();
        $data['pinjam'] = $this->db->query("select*from booking")->result_array();
        
        $this->load->view('templates/admin/header', $data);
        $this->load->view('templates/admin/sidebar', $data);
        $this->load->view('templates/admin/topbar', $data);
        $this->load->view('booking/daftar-booking', $data);
        $this->load->view('templates/admin/footer');
    }

    public function bookingDetail()
    {
        $id_booking = $this->uri->segment(3);
        $data['title'] = "Booking Detail";
        $data['user'] = $this->User_model->cekData(['email' => $this->session->userdata('email')])->row_array();
        $data['agt_booking'] = $this->db->query("select*from booking b, user u where b.id_user=u.id and b.id_booking='$id_booking'")->result_array();
        $data['detail'] = $this->db->query("select id_buku,judul_buku,pengarang,penerbit,tahun_terbit from booking_detail d, buku b where d.id_buku=b.id and d.id_booking='$id_booking'")->result_array();
        
        $this->load->view('templates/admin/header', $data);
        $this->load->view('templates/admin/sidebar', $data);
        $this->load->view('templates/admin/topbar', $data);
        $this->load->view('booking/booking-detail', $data);
        $this->load->view('templates/admin/footer');
    }

    public function pinjamAct()
    {
        $id_booking     = $this->uri->segment(3);
        $lama           = $this->input->post('lama', TRUE);
        $bo             = $this->db->query("SELECT*FROM booking WHERE id_booking='$id_booking'")->row();
        $tglsekarang    = date('Y-m-d');
        $no_pinjam      = $this->Booking_model->kodeOtomatis('pinjam', 'no_pinjam');
        $databooking    = [
            'no_pinjam'         => $no_pinjam,
            'id_booking'        => $id_booking,
            'tgl_pinjam'        => $tglsekarang,
            'id_user'           => $bo->id_user,
            'tgl_kembali'       => date('Y-m-d', strtotime('+' . $lama . ' days', strtotime($tglsekarang))),
            'tgl_pengembalian'  => '0000-00-00',
            'status'            => 'Pinjam',
            'total_denda'       => 0
        ];

        $this->Pinjam_model->simpanPinjam($databooking);
        $this->Pinjam_model->simpanDetail($id_booking, $no_pinjam);
        $denda = $this->input->post('denda', TRUE);
        $this->db->query("update pinjam_detail set denda='$denda'");

        //hapus Data booking yang bukunya diambil untuk dipinjam
        $this->Pinjam_model->deleteData('booking', ['id_booking' => $id_booking]);
        $this->Pinjam_model->deleteData('booking_detail', ['id_booking' => $id_booking]);

        //update dibooking dan dipinjam pada tabel buku saat buku yang dibooking diambil untuk dipinjam
        $this->db->query("UPDATE buku, pinjam_detail SET buku.dipinjam=buku.dipinjam+1, buku.dibooking=buku.dibooking-1 WHERE buku.id=pinjam_detail.id_buku");
        $this->session->set_flashdata('message', '<div class="alert alert-message alert-success" role="alert">Data Peminjaman Berhasil Disimpan</div>');
        redirect(base_url('pinjam'));
    }

    public function ubahStatus()
    {
        $id_buku    = $this->uri->segment(3);
        $no_pinjam  = $this->uri->segment(4);
        $where      = ['id_buku' => $this->uri->segment(3),];
        $tgl        = date('Y-m-d');
        $status     = 'Kembali';

        //update status menjadi kembali pada saat buku dikembalikan
        $this->db->query("UPDATE pinjam, pinjam_detail SET pinjam.status='$status', pinjam.tgl_pengembalian='$tgl' WHERE pinjam_detail.id_buku='$id_buku' AND pinjam.no_pinjam='$no_pinjam'");
        
        //update stok dan dipinjam pada tabel buku
        $this->db->query("UPDATE buku, pinjam_detail SET buku.dipinjam=buku.dipinjam-1, buku.stok=buku.stok+1 WHERE buku.id=pinjam_detail.id_buku");
        
        $this->session->set_flashdata('message', '<div class="laert alert-message alert-success" role="alert"></div>');
        redirect(base_url('pinjam'));
    }

}

/* End of file Pinjam.php */
