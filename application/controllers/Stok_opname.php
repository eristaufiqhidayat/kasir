<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Stok_opname extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		if ($this->session->userdata('status') !== 'login') {
			redirect('/');
		}
		$this->load->model('stok_opname_model');
	}

	public function index()
	{
		$this->load->view('stok_opname');
	}

	public function read()
	{
		header('Content-type: application/json');
		if ($this->stok_opname_model->read()->num_rows() > 0) {
			foreach ($this->stok_opname_model->read()->result() as $stok_masuk) {
				$tanggal = new DateTime($stok_masuk->tanggal);
				$data[] = array(
					'tanggal' => $tanggal->format('d-m-Y H:i:s'),
					'barcode' => $stok_masuk->barcode,
					'nama_produk' => $stok_masuk->nama_produk,
					'jumlah' => $stok_masuk->jumlah,
					'jumlah_opname' => $stok_masuk->jumlah_opname,
					'harga_perolehan' => $stok_masuk->harga_perolehan,
					'keterangan' => $stok_masuk->keterangan
				);
			}
		} else {
			$data = array();
		}
		$stok_masuk = array(
			'data' => $data
		);
		echo json_encode($stok_masuk);
	}

	public function add()
	{
		$id = $this->input->post('barcode');
		$addStok = $this->stok_opname_model->addStok($id, $this->input->post('jumlah_opname'), $this->input->post('harga_perolehan'));
		if ($addStok) {
			$tanggal = new DateTime($this->input->post('tanggal'));
			$data = array(
				'tanggal' => $tanggal->format('Y-m-d H:i:s'),
				'barcode' => $this->input->post('barcode'),
				'jumlah' => $this->input->post('jumlah'),
				'jumlah_opname' => $this->input->post('jumlah_opname'),
				'harga_perolehan' => $this->input->post('harga_perolehan'),
				'keterangan' => $this->input->post('keterangan'),
				'supplier' => $this->input->post('supplier')
			);
			if ($this->stok_opname_model->create($data)) {
				echo json_encode('sukses');
			}
		}
	}

	public function get_barcode()
	{
		$barcode = $this->input->post('barcode');
		$kategori = $this->stok_opname_model->getKategori(isset($id));
		if ($kategori->row()) {
			echo json_encode($kategori->row());
		}
	}

	public function laporan()
	{
		header('Content-type: application/json');
		if ($this->stok_opname_model->laporan()->num_rows() > 0) {
			foreach ($this->stok_opname_model->laporan()->result() as $stok_masuk) {
				$tanggal = new DateTime($stok_masuk->tanggal);
				$data[] = array(
					'tanggal' => $tanggal->format('d-m-Y H:i:s'),
					'barcode' => $stok_masuk->barcode,
					'nama_produk' => $stok_masuk->nama_produk,
					'jumlah' => $stok_masuk->jumlah,
					'keterangan' => $stok_masuk->keterangan,
					'supplier' => $stok_masuk->supplier
				);
			}
		} else {
			$data = array();
		}
		$stok_masuk = array(
			'data' => $data
		);
		echo json_encode($stok_masuk);
	}

	public function stok_hari()
	{
		header('Content-type: application/json');
		$now = date('d m Y');
		$total = $this->stok_opname_model->stokHari($now);
		echo json_encode($total->total == null ? 0 : $total);
	}
}

/* End of file Stok_masuk.php */
/* Location: ./application/controllers/Stok_masuk.php */