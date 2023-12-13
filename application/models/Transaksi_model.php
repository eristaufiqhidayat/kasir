<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Transaksi_model extends CI_Model
{

	private $table = 'transaksi';

	public function removeStok($id, $stok)
	{
		$this->db->where('id', $id);
		$this->db->set('stok', $stok);
		return $this->db->update('produk');
	}

	public function addTerjual($id, $jumlah)
	{
		$this->db->where('id', $id);
		$this->db->set('terjual', $jumlah);
		return $this->db->update('produk');;
	}

	public function create($data)
	{
		return $this->db->insert($this->table, $data);
	}

	public function read($tanggal)
	{
		!$tanggal ? $date = date('Y-m-d') : $date = $tanggal;
		//echo $tanggal;
		$this->db->select('transaksi.id, transaksi.tanggal, transaksi.barcode, transaksi.qty, transaksi.total_bayar, transaksi.jumlah_uang, transaksi.diskon, pelanggan.nama as pelanggan');
		$this->db->from($this->table);
		$this->db->join('pelanggan', 'transaksi.pelanggan = pelanggan.id', 'left outer');
		$this->db->where('DATE_FORMAT(tanggal, "%Y-%m-%d")=', $date);
		return $this->db->get();
	}

	public function delete($id)
	{
		$this->db->where('id', $id);
		return $this->db->delete($this->table);
	}

	public function getProduk($barcode, $qty)
	{
		$total = explode(',', $qty);
		foreach ($barcode as $key => $value) {
			if (isset($total[$key])) $jml = $total[$key];
			else $jml = "<font style='color:red'>null</font>";
			$this->db->select('nama_produk');
			$this->db->where('id', $value);
			if (isset($this->db->get('produk')->row()->nama_produk)) {
				$this->db->select('nama_produk');
				$this->db->where('id', $value);
				$nama_produk = $this->db->get('produk')->row()->nama_produk;
			} else {
				$nama_produk = "<font style='color:red'>Barang dengan id = $value Sudah di Hapus</font>";
			}
			$data[] = '<tr><td>[' . $value . '] ' . $nama_produk . ' (' . $jml . ')</td></tr>';
			$nama_produk = "";
		}
		return join($data);
	}


	public function penjualanBulan($date)
	{
		$qty = $this->db->query("SELECT qty FROM transaksi WHERE DATE_FORMAT(tanggal, '%d %m %Y') = '$date'")->result();
		$d = [];
		$data = [];
		foreach ($qty as $key) {
			$d[] = explode(',', $key->qty);
		}
		foreach ($d as $key) {
			$data[] = array_sum($key);
		}
		return $data;
	}

	public function transaksiHari($hari)
	{
		return $this->db->query("SELECT COUNT(*) AS total FROM transaksi WHERE DATE_FORMAT(tanggal, '%d %m %Y') = '$hari'")->row();
	}

	public function transaksiTerakhir($hari)
	{
		return $this->db->query("SELECT transaksi.qty FROM transaksi WHERE DATE_FORMAT(tanggal, '%d %m %Y') = '$hari' LIMIT 1")->row();
	}

	public function getAll($id)
	{
		$this->db->select('transaksi.nota, transaksi.tanggal, transaksi.barcode, transaksi.qty, transaksi.total_bayar, transaksi.jumlah_uang, pengguna.nama as kasir');
		$this->db->from('transaksi');
		$this->db->join('pengguna', 'transaksi.kasir = pengguna.id');
		$this->db->where('transaksi.id', $id);
		return $this->db->get()->row();
	}
	public function getSummary($date)
	{
		$this->db->select('count(*) as jmltransaksi,sum(total_bayar) as rptransaksi');
		$this->db->from('transaksi');
		$this->db->where('DATE_FORMAT(tanggal, "%Y-%m-%d")=', $date);
		return $this->db->get()->row();
	}
	public function getName($barcode)
	{
		foreach ($barcode as $b) {
			$this->db->select('nama_produk, harga');
			$this->db->where('id', $b);
			$data[] = $this->db->get('produk')->row();
		}
		return $data;
	}
}

/* End of file Transaksi_model.php */
/* Location: ./application/models/Transaksi_model.php */