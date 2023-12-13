<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Laporan_penjualan extends CI_Controller
{

	public function index()
	{
		if ($this->session->userdata('status') !== 'login') {
			redirect('/');
		}
		// $this->load->model('transaksi_model');
		// $tanggal = $this->input->get('tanggal2');
		// //!$tanggal ? $date = date('Y-m-d') : $date = $tanggal;
		// $date = $tanggal;
		// $summary = $this->transaksi_model->getSummary($date);
		// $data['jmltransaksi'] = $summary->jmltransaksi;
		// $data['rptransaksi'] = $summary->rptransaksi;
		$this->load->view('laporan_penjualan');
	}
}

/* End of file Laporan_penjualan.php */
/* Location: ./application/controllers/Laporan_penjualan.php */