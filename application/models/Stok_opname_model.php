<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Stok_opname_model extends CI_Model
{

	private $table = 'stok_opname';

	public function create($data)
	{
		return $this->db->insert($this->table, $data);
	}

	public function read()
	{
		$this->db->select('stok_opname.tanggal, stok_opname.jumlah,stok_opname.jumlah_opname,stok_opname.harga_perolehan, stok_opname.keterangan, produk.barcode, produk.nama_produk');
		$this->db->from($this->table);
		$this->db->join('produk', 'produk.id = stok_opname.barcode');
		return $this->db->get();
	}

	public function laporan()
	{
		$this->db->select('stok_opname.tanggal, stok_opname.jumlah,stok_opname.harga_perolehan, stok_opname.keterangan, produk.barcode, produk.nama_produk, supplier.nama as supplier');
		$this->db->from($this->table);
		$this->db->join('produk', 'produk.id = stok_opname.barcode');
		$this->db->join('supplier', 'supplier.id = stok_opname.supplier', 'left outer');
		return $this->db->get();
	}

	public function getStok($id)
	{
		$this->db->select('stok');
		$this->db->where('id', $id);
		return $this->db->get('produk')->row();
	}

	public function addStok($id, $stok, $harga_perolehan)
	{
		$this->db->where('id', $id);
		$this->db->set('stok', $stok);
		$this->db->set('harga_perolehan', $harga_perolehan);
		return $this->db->update('produk');
	}

	public function stokHari($hari)
	{
		return $this->db->query("SELECT SUM(jumlah) AS total FROM $this->table WHERE DATE_FORMAT(tanggal, '%d %m %Y') = '$hari'")->row();
	}
}

/* End of file stok_opname_model.php */
/* Location: ./application/models/stok_opname_model.php */