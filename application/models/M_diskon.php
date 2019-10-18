<?php
	/**
	 * 
	 */
	class M_diskon extends CI_Model
	{

		function getDataDiskon(){
			$hasil=$this->db->query("SELECT a.diskon_id,a.potongan_harga,a.barang_id,b.barang_nama,DATE_FORMAT(a.tanggal_mulai,'%d/%m/%Y') AS mulai_diskon ,DATE_FORMAT(a.tanggal_berakhir,'%d/%m/%Y') AS akhir_diskon FROM diskon a, barang b WHERE a.barang_id = b.barang_id");
        	return $hasil;
		}

		
		function saveDiskon($barang_id,$diskon,$tanggal_mulai,$tanggal_berakhir){
			date_default_timezone_set("Asia/Jakarta");
        	$tanggal = date("Y-m-d");
			$this->db->trans_start();
			$this->db->query("INSERT INTO diskon(barang_id,potongan_harga,tanggal_mulai,tanggal_berakhir) VALUES ('$barang_id','$diskon','$tanggal_mulai','$tanggal_berakhir')");
			
	      	$this->db->trans_complete();
	        if($this->db->trans_status()==true)
	        return true;
	        else
	        return false;
		}

		function updateDiskon($diskon_id,$barang_id,$diskon,$tanggal_mulai,$tanggal_berakhir){
			$this->db->trans_start();
			$this->db->query("UPDATE diskon SET potongan_harga = '$diskon' WHERE diskon_id='$diskon_id'");
			$this->db->query("UPDATE diskon SET tanggal_mulai = '$tanggal_mulai' WHERE diskon_id='$diskon_id'");
			$this->db->query("UPDATE diskon SET tanggal_berakhir = '$tanggal_berakhir' WHERE diskon_id='$diskon_id'");
	      	$this->db->trans_complete();
	        if($this->db->trans_status()==true)
	        return true;
	        else
	        return false;
		}

		function hapusDiskon($diskon_id,$barang_id){
			$this->db->trans_start();
			$this->db->query("DELETE FROM diskon WHERE diskon_id='$diskon_id'");
	      	$this->db->trans_complete();
	        if($this->db->trans_status()==true)
	        return true;
	        else
	        return false;
		}

		function hapusDiskonT($tanggal){
			$this->db->trans_start();
			$a = $this->db->query("SELECT * FROM diskon WHERE diskon_tanggal = '$tanggal'");
			foreach ($a->result_array() as $i) {
				$barang_id = $i['barang_id'];
				$diskon_id = $i['diskon_id'];
				$z = $this->db->query("SELECT * FROM diskon WHERE diskon_id = '$diskon_id'")->row_array();
				$diskon_lama = $z['potongan_harga'];
				$this->db->query("UPDATE barang_non_reseller SET bnr_harga = bnr_harga + '$diskon_lama' WHERE barang_id='$barang_id'");
				$this->db->query("DELETE FROM diskon WHERE diskon_id='$diskon_id'");
			}
	      	$this->db->trans_complete();
	        if($this->db->trans_status()==true)
	        return true;
	        else
	        return false;
		}

	}
?>