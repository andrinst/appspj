<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Description of Model Class ModelReport.php
 * All Function related to complete generate contain as data field for report document SPPT,SPPD & Kuitansi  
 * @version 1.0
 * @author Guntar 11/09/2013
 */ 
 class ModelReport extends CI_Model
 {
 	public  $ID;
	public  $ID_PERJALANAN;
	private $ID_SPT;
		
	function __construct() {
		parent::__construct();
		$this->load->helper('date_format_helper');
		$this->today = date("Y-m-d");		
	}	
	function _getIDSpt() 		 { return $this->ID_SPT; }	
	function getId( ) 			 { return $this->ID; }
	function getIdPerjalanan( )	 { return $this->ID_PERJALANAN; }
	function _setIDSpt($idSpt) 	 { return $this->ID_SPT = $idSpt; }
	function setID($id) 		 { return $this->ID = $id; }	
	function setIdPerjalanan($id){ return $this->ID_PERJALANAN = $id; }	
	function getCountRowsSpj() {
		$sql = ("SELECT	s.nama 
				FROM pangkat p
				INNER JOIN staff s ON p.id = s.golongan
				INNER JOIN perjalanan_multi_detail pmd ON s.id = pmd.personil
				INNER JOIN perjalanan_multi pm ON pmd.id_perjalanan = pm.id
				INNER JOIN dinas d ON pm.dinas = d.id
				INNER JOIN kota k ON d.kota_tujuan = k.id
				WHERE pm.id = '".$this->ID_PERJALANAN."'");
		$query = $this->db->query($sql);		
		return $query->num_rows();
	}	
	function getGolongan() {
		$sql 	= ("SELECT p.golongan FROM perjalanan_multi_detail pmd INNER JOIN staff s on pmd.personil = s.id INNER JOIN pangkat p
				 	on s.golongan = p.id WHERE pmd.id_detail = '".$this->ID."'");
		$query 	= $this->db->query($sql);		
		foreach ($query->result() as $row) { $golongan = $row->golongan; }		
		return ($golongan);
	}	
	function getJabatan() {
		$sql 	= ("SELECT s.jabatan FROM perjalanan_multi_detail pmd INNER JOIN staff s on pmd.personil = s.id WHERE pmd.id_detail = '".$this->ID."'");
		$query 	= $this->db->query($sql);		
		foreach ($query->result() as $row) { $jabatan = $row->jabatan; }		
		return ($jabatan);
	}	
	function getMaksud() {
		$sql 	= ("SELECT d.maksud FROM perjalanan_multi pm INNER JOIN dinas d on pm.dinas = d.id WHERE pm.id = '".$this->ID_PERJALANAN."'");
		$query 	= $this->db->query($sql);		
		foreach ($query->result() as $row) { $maksud = $row->maksud; }		
		return ($maksud);
	}
	function getNotaDinas() {
		$sql 	= ("SELECT nd.nomor,nd.tanggal,nd.tentang,d.nota_dinas_1 FROM perjalanan_multi pm INNER JOIN dinas d on pm.dinas = d.id 
					INNER JOIN nota_dinas nd on d.nota_dinas = nd.id WHERE pm.id = '".$this->ID_PERJALANAN."'");
		$query 	= $this->db->query($sql);
		$result = array();		
		foreach ($query->result_array() as $row) {
			$result [0]	= $row['nomor'];
			$result	[1]	= $row['tanggal'];
			$result	[2]	= $row['tentang'];
			$result	[3]	= $row['nota_dinas_1'];
		}		
		return $result;
	}
	function getExtraNotaDinas() {
		$sql 	= ("SELECT js.nomor,js.tanggal,js.perihal FROM perjalanan_multi pm INNER JOIN dinas d on pm.dinas = d.id 
					INNER JOIN jenis_surat js on d.nota_dinas_1 = js.id WHERE pm.id = '".$this->ID_PERJALANAN."'");
		$query 	= $this->db->query($sql);
		$result = array();		
		foreach ($query->result_array() as $row) {
			$result [0]	= $row['nomor'];
			$result	[1]	= $row['tanggal'];
			$result	[2]	= $row['perihal'];
		}		
		return $result;
	}
	function getJenisNotaDinasExtra() {
		$sql 	= ("SELECT jn.jenis_undangan FROM jenis_surat jn INNER JOIN dinas d on jn.id = d.nota_dinas_1 INNER JOIN 
					perjalanan_multi pm on d.id = pm.dinas WHERE pm.id = '".$this->ID_PERJALANAN."'");
		$query 	= $this->db->query($sql);				
		foreach ($query->result() as $row) {
			$result	= $row->jenis_undangan;			
		}		
		return $result;
	}
	function getTotalHariDinas()	{
		$sql 	= ("SELECT d.berangkat,d.kembali FROM perjalanan_multi pm INNER JOIN dinas d on pm.dinas = d.id WHERE pm.id = '".$this->ID_PERJALANAN."'");
		$query 	= $this->db->query($sql);		
		foreach ($query->result() as $row){
		   $tanggalBerangkat 	= $row->berangkat;		   		   
		   $tanggalKembali		= $row->kembali;
		}		
		return $this->getTotalHari($tanggalKembali,$tanggalBerangkat);
	}	
	function getTotalHari($tanggalKembali,$tanggalBerangkat) {
		$totalHari = (((strtotime($tanggalKembali)) - (strtotime($tanggalBerangkat))) / (60*60*24)) + (1);		
		return $totalHari;
	}	
	function getTypeTicket() {
		$sql	=("SELECT type1 FROM perjalanan_multi WHERE id = '".$this->ID_PERJALANAN."' ");		
		$query 	= $this->db->query($sql);		
		foreach ($query->result() as $row) { $type_ticket = $row->type1; }				
		return $type_ticket;
	}	
	function getIdProvTujuan() {
		$sql 	= ("SELECT k.provinsi FROM perjalanan_multi pm INNER JOIN dinas d on pm.dinas = d.id INNER JOIN kota k on d.kota_tujuan = k.id 
					WHERE pm.id = '".$this->ID_PERJALANAN."'");
		$query 	= $this->db->query($sql);		
		foreach ($query->result() as $row) { $idProvTujuan = $row->provinsi; }	
		return $idProvTujuan;
	}	
	function getIdProvAsal() {
		$sql	= ("SELECT k.provinsi FROM perjalanan_multi pm INNER JOIN dinas d on pm.dinas = d.id INNER JOIN kota k on d.kota_asal = k.id 
				 	WHERE pm.id = '".$this->ID_PERJALANAN."'");
		$query 	= $this->db->query($sql);		
		foreach ($query->result() as $row) { $idProvAsal = $row->provinsi; }	
		return $idProvAsal;
	}
	function getKotaAsal() {
		$sql	= ("SELECT k.kota FROM perjalanan_multi pm INNER JOIN dinas d on pm.dinas = d.id INNER JOIN kota k on d.kota_asal = k.id 
				 	WHERE pm.id = '".$this->ID_PERJALANAN."'");
		$query 	= $this->db->query($sql);		
		foreach ($query->result() as $row){$asal = $row->kota;}		
		return $asal;
	}	
	function getKotaTujuan() {
		$sql 	= ("SELECT k.kota FROM perjalanan_multi pm INNER JOIN dinas d on pm.dinas = d.id INNER JOIN kota k on d.kota_tujuan = k.id 
					WHERE pm.id = '".$this->ID_PERJALANAN."'");				 
		$query	= $this->db->query($sql);		
		foreach ($query->result() as $row) { $tujuan= $row->kota; }	
		return $tujuan;
	}	
	function getUangSaku() {
		$type 			= $this->getTypeUangSaku();
		$idProv			= $this->getIdProvTujuan();
		$golongan		= $this->getGolongan();			
		if(!empty($golongan) && !empty($type) && !empty($idProv)){			
				if($type == 'Fullboard_Luar'){
					$sql 		= ("SELECT sbu.fullboard_luar FROM sbu_uang_saku sbu WHERE sbu.provinsi = '".$idProv."'");
					$typeField 	= 'fullboard_luar';						
				}else if($type == 'Fullboard_Dalam'){
					$sql 		= ("SELECT sbu.fullboard_dalam FROM sbu_uang_saku sbu WHERE sbu.provinsi = '".$idProv."'");
					$typeField 	= 'fullboard_dalam';
				}else if($type == 'Fullday_Dalam'){
					$sql 		= ("SELECT sbu.fullday_dalam FROM sbu_uang_saku sbu WHERE sbu.provinsi = '".$idProv."'");
					$typeField 	= 'fullday_dalam';
				}else if($type == 'Uang_Saku_Murni'){
					$sql 		= ("SELECT sbu.uang_saku_murni FROM sbu_uang_saku sbu WHERE sbu.provinsi = '".$idProv."'");
					$typeField 	= 'uang_saku_murni';
				}else {
					print("Wrong assignment type decision UANG SAKU Staff for Decision Path"); 
				}
				$query = $this->db->query($sql);						
		foreach ($query->result() as $row) { $jumlah_uang_saku = $row->$typeField; }	
		return ($jumlah_uang_saku);
		}
		else{
			 return ($jumlah_uang_saku = "Error"); 
		}
	}
	function sumUangSaku() {
		$totalHari 		= $this->getTotalHariDinas();		
		$type 			= $this->getTypeUangSaku();		
		$uangSaku 		= $this->getUangSaku();
		$taxUangSaku	= $this->getTaxUangSaku();		
		if($type =='Fullboard_Dalam'){
			$sumTax			= ($totalHari) * ($uangSaku) * ($taxUangSaku);
			$sumUangSaku  	= ($uangSaku * $totalHari) - ($sumTax);			
		}else if($type=='Fullday_Dalam'){
			$sumTax			= ($uangSaku) * ($taxUangSaku);
			$sumUangSaku  	= ($uangSaku) - ($sumTax);		
		}else { $sumUangSaku= ($uangSaku * $totalHari); }			
		return ($sumUangSaku);
	}	
	function getTypeUangSaku() {
		$sql 	= ("SELECT uang_saku FROM perjalanan_multi WHERE id = '".$this->ID_PERJALANAN."'");		
		$query 	= $this->db->query($sql);		
		foreach ($query->result() as $row) { $typeUangSaku = $row->uang_saku;	}				
		return ($typeUangSaku);
	}	
	function getTaxUangSaku(){
		$type		= $this->getTypeUangSaku();
		$golongan	= $this->getGolongan();
		$tax 		= 0;		
		if($type =='Fullboard_Dalam' || $type =='Fullday_Dalam' && ($golongan =='III/a'|| 
		   $golongan =='III/b' || $golongan =='III/c' || $golongan =='III/d'||$golongan =='II/a'||
		   $golongan =='II/b'|| $golongan =='II/c'|| $golongan =='II/d' ))
		{ $tax = 0.05; }
		else if($type =='Fullboard_Dalam' || $type =='Fullday_Dalam' && ($golongan =='IV/a'||
			    $golongan =='IV/b' || $golongan =='IV/c' || $golongan =='IV/d'))
		{ $tax = 0.15;}			
		return $tax;
	}	
	function getIdProvTicket() {
		$sql 	= ("SELECT tiket1 FROM perjalanan_multi WHERE id = '".$this->ID_PERJALANAN."' ");		
		$query 	= $this->db->query($sql);		
		foreach ($query->result() as $row) { $ticket = $row->tiket1; }
		return ($ticket);
	}	
	function checkTicket() {
		$sql 	= ("SELECT tiket1 FROM perjalanan_multi WHERE id = '".$this->ID_PERJALANAN."' ");		
		$query 	= $this->db->query($sql);		
		foreach ($query->result() as $row) { $ticket = $row->tiket1; }				
		if($ticket == NULL ){return 0;}
		else { return 1; }		
	}
	function checkTicketManual() {
		$sql 	= ("SELECT tiket_manual FROM perjalanan_multi WHERE id = '".$this->ID_PERJALANAN."' ");		
		$query 	= $this->db->query($sql);		
		foreach ($query->result() as $row) { $ticketManual = $row->tiket_manual; }				
		if($ticketManual == NULL){return 0;}
		else { return 1; }
	}
	function sumTicketManual() {
		$sql 	= ("SELECT tiket_manual FROM perjalanan_multi WHERE id = '".$this->ID_PERJALANAN."' ");		
		$query 	= $this->db->query($sql);		
		foreach ($query->result() as $row) { $ticketManual = $row->tiket_manual; }
		return $ticketManual;		
	}
	function sumTicket() {
		$typeTiket1 	= $this->getTypeTicket();
		$isTicket 		= $this->checkTicket();
		$idProv 		= $this->getIdProvTicket();
		$isTicketManual	= $this->checkTicketManual();
		
		if(!empty($typeTiket1) && ($isTicket == 1) && ($isTicketManual == 0)){			
			if ($typeTiket1 == 'eco'){
				$sql 	= ("SELECT ekonomi FROM sbu_pesawat WHERE id = '".$idProv."'");
				$query 	= $this->db->query($sql);				
				foreach ($query->result() as $row) { $totalTicket = $row->ekonomi; }								
			}else if ($typeTiket1 == 'bis'){
				$sql 	= ("SELECT bisnis FROM sbu_pesawat WHERE id = '".$idProv."'");
				$query 	= $this->db->query($sql);				
				foreach ($query->result() as $row) { $totalTicket = $row->bisnis; }								
			}else {; }
		}else if(!empty($typeTiket1) || ($isTicket == 1) && ($isTicketManual == 1)){ //For ticket manual
				$totalTicket = $this->sumTicketManual();
		}else if(empty($typeTiket1) && $isTicket == 0){
			$sql 	= ("SELECT tiket2 FROM perjalanan_multi WHERE id='".$this->ID_PERJALANAN."'");
			$query 	= $this->db->query($sql);
			foreach ($query->result() as $row) { $totalTicket = $row->tiket2;}					
		}else { ; }	
		return ($totalTicket);
	}
	function getSelectTaxi() {
		$sql 	= ("SELECT taxi FROM perjalanan_multi WHERE id = '".$this->ID_PERJALANAN."'");
		$query 	= $this->db->query($sql);
		foreach ($query->result() as $row) { $taxi = $row->taxi; }
		return $taxi;
	}	
	function getTaxi()
	{
		$taxi 			= $this->getSelectTaxi();
		$idProvAsal 	= $this->getIdProvAsal();
		$idProvTujuan	= $this->getIdProvTujuan();		
		if($taxi =='asal,tujuan'){
			$sqlTaxiAsal = ("SELECT taxi FROM sbu_taxi WHERE provinsi='".$idProvAsal."'");
			$queryTaxiAsal = $this->db->query($sqlTaxiAsal);			
				foreach ($queryTaxiAsal->result() as $rowTaxiAsal) { $taxiAsal = $rowTaxiAsal->taxi; }			
			$sqlTaxiTujuan = ("SELECT taxi FROM sbu_taxi WHERE provinsi='".$idProvTujuan."'");
			$queryTaxiTujuan = $this->db->query($sqlTaxiTujuan);
				foreach ($queryTaxiTujuan->result() as $rowTaxiTujuan) { $taxiTujuan = $rowTaxiTujuan->taxi; }			
			$result = array('taxi_asal'		=>	$taxiAsal,
						 	'taxi_tujuan'	=>	$taxiTujuan);
			return $result;
		}else if($taxi =='asal'){
			$sqlTaxiAsal = ("SELECT taxi FROM sbu_taxi WHERE provinsi='".$idProvAsal."'");
			$queryTaxiAsal = $this->db->query($sqlTaxiAsal);
			foreach ($queryTaxiAsal->result() as $rowTaxiAsal)
			{ $taxiAsal = $rowTaxiAsal->taxi; }
			$result = array('taxi_asal'		=>	$taxiAsal,
						 	'taxi_tujuan'	=>	0);
			return $result;
		}else if($taxi =='tujuan'){
			$sqlTaxiTujuan = ("SELECT taxi FROM sbu_taxi WHERE provinsi='".$idProvTujuan."'");
			$queryTaxiTujuan = $this->db->query($sqlTaxiTujuan);
			foreach ($queryTaxiTujuan->result() as $rowTaxiTujuan)
			{ $taxiTujuan = $rowTaxiTujuan->taxi; }
			$result = array('taxi_asal'		=>	0,
						 	'taxi_tujuan'	=>	$taxiTujuan);
			return $result;			
		}else{
			$result = array('taxi_asal'		=>	0,
						 	'taxi_tujuan'	=>	0);
			return $result;			
		}
	}
	function sumTaxi()	{
		$taxi				= $this->mr->getTaxi();	
		$taxiAsal 			= $taxi['taxi_asal'];
		$taxiTujuan  		= $taxi['taxi_tujuan'];		
		$totalTaxi			= (($taxiAsal * 2) + ($taxiTujuan * 2));		
		return ($totalTaxi);
	}	
	function sumAirportTax() {
		$sql = ("SELECT airport_tax_asal,airport_tax_tujuan FROM perjalanan_multi WHERE id = '".$this->ID_PERJALANAN."'");
		$query = $this->db->query($sql);		
		foreach ($query->result() as $row )	{
			$airportTaxAsal 	= $row->airport_tax_asal;
			$airportTaxTujuan 	= $row->airport_tax_tujuan;
		}
		$sumAirportTax 	= ($airportTaxAsal) + ($airportTaxTujuan);
		return ($sumAirportTax);
	}	
	function getHotel(){
		$sql 	= ("SELECT hotel FROM perjalanan_multi WHERE id ='".$this->ID_PERJALANAN."'");
		$query 	= $this->db->query($sql);
		foreach ($query->result() as $row) { $hotel = $row->hotel; }
		return $hotel;
	}
	function getNoSptApprove(){
		$sql 	= ("SELECT pmd.no_spt FROM perjalanan_multi_detail pmd WHERE pmd.id_detail ='".$this->ID."'");
		$query 	= $this->db->query($sql);
		foreach ($query->result() as $row) { $no_spt = $row->no_spt; }
		return $no_spt;
	}
	function getTglSptApprove(){
		$sql 	= ("SELECT pm.tgl_spt FROM perjalanan_multi pm WHERE pm.id ='".$this->ID_PERJALANAN."'");
		$query 	= $this->db->query($sql);
		foreach ($query->result() as $row) { $tgl_spt = $row->tgl_spt; }
		return $tgl_spt;
	}	
	function getTotalHotel() {
		$idProvTujuan 	= $this->getIdProvTujuan();
		$hotel 			= $this->getHotel();
		$golongan		= $this->getGolongan();
		$pangkat		= $this->getJabatan();		
		if ($hotel =='yes'){
			if($pangkat =='menteri'){
				$sql 	= ("SELECT suite FROM sbu_penginapan WHERE provinsi ='".$idProvTujuan."'");
				$query 	= $this->db->query($sql);
				foreach ($query->result() as $row)
				{ $typeHotel = $row->suite; }
			}else if($pangkat == 'KEPALA BBPPT'){
				$sql 	= ("SELECT star4 FROM sbu_penginapan WHERE provinsi ='".$idProvTujuan."'");
				$query 	= $this->db->query($sql);
				foreach ($query->result() as $row)
				{ $typeHotel = $row->star4; }
			}else if($pangkat != 'KEPALA BBPPT' && ($golongan =='IV/a' || $golongan =='IV/b' ||$golongan =='IV/c' || $golongan =='IV/d' || $golongan =='IV/e')){
				$sql 	= ("SELECT star3 FROM sbu_penginapan WHERE provinsi ='".$idProvTujuan."'");
				$query 	= $this->db->query($sql);
				foreach ($query->result() as $row)
				{ $typeHotel = $row->star3; }
			}else if($golongan =='III/a'||$golongan =='III/b'|| $golongan =='III/c' || $golongan =='III/d'){
				$sql 	= ("SELECT star2 FROM sbu_penginapan WHERE provinsi ='".$idProvTujuan."'");
				$query	= $this->db->query($sql);
				foreach ($query->result() as $row)
				{ $typeHotel = $row->star2; }
			}else if($golongan =='I/a' || $golongan =='I/b'|| $golongan =='I/c' || $golongan =='I/d' ||
					$golongan =='II/a'|| $golongan =='II/b'|| $golongan =='II/c'|| $golongan =='II/d'){
				$sql	= ("SELECT star1 FROM sbu_penginapan WHERE provinsi ='".$idProvTujuan."'");
				$query 	= $this->db->query($sql);
				foreach ($query->result() as $row)
				{ $typeHotel = $row->star1; }
			}else{ ;}			
		}else if($hotel =='no') // ($idProvTujuan == 11 || $idProvTujuan == 12))
		{
			if($pangkat =='menteri'){
				$sql 	= ("SELECT suite FROM sbu_penginapan WHERE provinsi ='".$idProvTujuan."'");
				$query 	= $this->db->query($sql);
				foreach ($query->result() as $row)
				{ $typeHotel = $row->suite; }
			}else if($pangkat == 'KEPALA BBPPT'){
				$sql 	= ("SELECT star4 FROM sbu_penginapan WHERE provinsi ='".$idProvTujuan."'");
				$query 	= $this->db->query($sql);
				foreach ($query->result() as $row)
				{ $typeHotel = $row->star4; }
			}else if($pangkat != 'KEPALA BBPPT' && ($golongan =='IV/a' || $golongan =='IV/b' || $golongan =='IV/c' || $golongan =='IV/d' || $golongan =='IV/e')){
				$sql 	= ("SELECT star3 FROM sbu_penginapan WHERE provinsi ='".$idProvTujuan."'");
				$query 	= $this->db->query($sql);
				foreach ($query->result() as $row)
				{ $typeHotel = $row->star3; }
			}else if($golongan =='III/a'||$golongan =='III/b'|| $golongan =='III/c' || $golongan =='III/d'){
				$sql 	= ("SELECT star2 FROM sbu_penginapan WHERE provinsi ='".$idProvTujuan."'");
				$query	= $this->db->query($sql);
				foreach ($query->result() as $row)
				{ $typeHotel = $row->star2; }
			}else if($golongan =='I/a' || $golongan =='I/b'|| $golongan =='I/c' || $golongan =='I/d' ||
					$golongan =='II/a'|| $golongan =='II/b'|| $golongan =='II/c'|| $golongan =='II/d'){
				$sql	= ("SELECT star1 FROM sbu_penginapan WHERE provinsi ='".$idProvTujuan."'");
				$query 	= $this->db->query($sql);
				foreach ($query->result() as $row)
				{ $typeHotel = $row->star1; }
			}else{ ;}
		}else  $typeHotel = 0;
		return $typeHotel;
	}	
	function sumHotel() {
		$totalHari 		= ($this->getTotalHariDinas()) - (1);
		$hargaHotel		= $this->getTotalHotel();
		$idProvTujuan 	= $this->getIdProvTujuan();
		$hotel  		= $this->getHotel();
		if($hotel == 'no' )//&& ($idProvTujuan == 11 || $idProvTujuan == 12))
		   	 $sumHotel	= ($totalHari) * (($hargaHotel * 0.3));
		else $sumHotel	= ($totalHari) * ($hargaHotel);
		return ($sumHotel);
	}	
	function getTransportDalam() {
		$typeUangSaku = $this->getTypeUangSaku();		
		if($typeUangSaku =='Fullboard_Dalam' || $typeUangSaku == 'Fullday_Dalam'){ $transportDalam = 55000; }
		else { $transportDalam = 0;}
		return ($transportDalam);
	}
	function sumTransportDalam() {
		$transportDalam		= $this->getTransportDalam();		
		$sumTransportDalam	= (($transportDalam) * (2));		
		return ($sumTransportDalam);
	}	
	function sumAll() {
		$sumUangSaku 			= $this->sumUangSaku();
		$sumTicket				= $this->sumTicket();
		$sumUangHotel			= $this->sumHotel();
		$sumTaxi				= $this->sumTaxi();
		$sumTransportDalam		= $this->sumTransportDalam();
		$sumTaxAirport			= $this->sumAirportTax();		
		$sumAll					= (($sumUangSaku) + ($sumTicket) + ($sumUangHotel) + ($sumTaxi) + ($sumTransportDalam) + ($sumTaxAirport));		
		return ($sumAll);
	}	
	function getDateApproval() {
		$sql = ("SELECT tgl_approval FROM perjalanan_multi WHERE id = '".$this->ID_PERJALANAN."'");		
		$query = $this -> db -> query($sql);				
		$dateApproval = "";
		foreach ($query -> result() as $row){
			$dateApproval = $row->tgl_approval;
		}
		return $dateApproval;
	}	
	function getOfMonth() {
		$dateApproval = $this->getDateApproval();
		$arrayDate = str_split(str_replace("-","",$dateApproval),2);	
		$month = $arrayDate[2];		
		return $month;
	}
	function getOfYears() {
		$dateApproval = $this->getDateApproval();				
		$arrayDate = str_split(str_replace("-","",$dateApproval),4);
		$years = $arrayDate[0];		
		return $years;
	}
	function getLastNoSptofMonth() {		
		/**$sql = ("SELECT MAX(no_spt) no_spt 
				 FROM perjalanan_multi_detail 
				 WHERE no_spt is NOT NULL ");//AND create_date_spt LIKE '".date('Y')."-".date('m')."-%'");
		**/
		$sql = ("SELECT no_spt,CONVERT(SUBSTRING_INDEX(no_spt,'/',1),UNSIGNED INTEGER) AS num 
				 FROM perjalanan_multi_detail 
				 WHERE no_spt is NOT NULl
				 ORDER BY num DESC
				 LIMIT 0,1");
		$query = $this -> db ->query($sql);
		$LastNoSPt = "";		
		foreach ($query ->result() as $row){
			$LastNoSPt = $row ->no_spt;
		}		
		$offset = 0;
		for($i = 0 ; $i < strlen($LastNoSPt) ; $i++){
			$ArrSpt = str_split($LastNoSPt,1);
			if ($ArrSpt[$i] == '/' ){
				$offset = $i;
				$i = strlen($LastNoSPt);
			}							
		}		
		$sptResult ="";
		for ($j = 0; $j < $offset ; $j++){
			$ArrTempSpt [$j] = $ArrSpt[$j];
			$sptResult .= $ArrTempSpt[$j]; //group Number of SPT
		}
		return $sptResult;
	}	
	function checkNoSpt(){
		$maxNoSpt = $this -> getLastNoSptofMonth();		
		//if($this->getCountRowsSpj() == 0) $this->_setIDSpt((1));
		//else 
		$this->_setIDSpt($maxNoSpt+(1));
	}
	function setNoSpt()	{
		//Pattern  => NOMOR SPT/DJSDPPI.4/SP.03/TU/month/years		
		$noSpt = $this->_getIDSpt();		
		return ($noSpt)."/BBPPT.31/SP.04.06/".date('m')."/".date('Y');
	}
	function saveNoSpt() {
		$today = date('Y-m-d h-i-s');
		$no_spt = $this -> setNoSpt();		
		if($this->isHasNoSpt() == 1) { //NULL
			$data   = array('no_spt' => $no_spt,
							'create_date_spt'=> $today);
			$this  -> db -> WHERE('id_detail =',$this->ID);
			$this  -> db -> update('perjalanan_multi_detail',$data);			
			$this  -> db -> trans_commit();			
		}else return;//NOT NULL			
	}		
	function approveSpj() {
		if($this ->isApprove() == FALSE){
			$this -> checkNoSpt();			
			$this -> createNoSpt(); // create SPT nomor
			$status= 'Approved';
			$data  = array('status'=> $status,
						   'tgl_approval'=>date('Y-m-d'));		
			$this -> db -> WHERE('id =',$this->ID_PERJALANAN);
			$this -> db -> update('perjalanan_multi',$data);			
			$this -> db -> trans_commit();				
			//redirect("report");
		}
	}
	function createNoSpt(){		
		$staff = $this->getStaff();
		$countStaff = count($staff);				
		for($i = 0 ; $i < $countStaff; $i++){
			$this->setID($staff[$i]);
			$this->saveNoSpt();
		}			
	}
	function getStaff(){
		$sql = ("SELECT	pmd.id_detail 
				FROM perjalanan_multi_detail pmd 
				WHERE pmd.id_perjalanan = '".$this->ID_PERJALANAN."'
				ORDER BY id_detail ASC");
		$query  = $this->db->query($sql);
		$result = array();
		foreach ($query->result() as $row) {
			$result [] = $row->id_detail;			
		}		
		return $result;
	}
	function isApprove() {
		$this -> db -> SELECT('status');
		$this -> db -> FROM('perjalanan_multi');
		$this -> db -> WHERE('id =',$this->ID_PERJALANAN);		
		$result = $this -> db -> get();		
		if ($result === "Declined")
			//return FALSE;
			return TRUE;
		else if($result === "Approved")	return TRUE;
		else return FALSE;
	}	
	function isHasNoSpt() {
		$sql = ("SELECT no_spt FROM perjalanan_multi_detail WHERE id_detail = '".$this->ID."'");		
		$query = $this -> db -> query($sql);		
		foreach($query -> result() as $row){
			$result = $row->no_spt;	
		}			
		if ($result == "") return 1; //TRUE
		else return 0; //FALSE 
	}	
	function value_to_rupiah($value) {
		return strrev(implode('.',str_split(strrev(strval($value)),3)));
	}	
	function terbilang($x, $style = 3) {
		if ($x<0) { $hasil = "minus ". trim($this->kekata($x)); }
		else { $hasil = trim($this->kekata($x)); }
		switch ($style){
			case 1:
				$hasil = strtoupper($hasil); break;
			case 2:
				$hasil = strtolower($hasil); break;
			case 3:
				$hasil = ucwords($hasil); break;
			default:
				$hasil = ucfirst($hasil); break;
		}
		return $hasil;
	}	
	function kekata($x) {
		$x = abs($x);
		$angka = array("", "satu", "dua", "tiga", "empat", "lima",
		"enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
		$temp = "";
		if ($x < 12) { $temp = " ". $angka[$x];}
		else if ($x < 20){
			$temp = $this->kekata($x - 10). " belas";
		}else if ($x < 100){
			$temp = $this->kekata($x/10)." puluh". $this->kekata($x % 10);
		}else if ($x < 200){
			$temp = " seratus" . $this->kekata($x - 100);
		}else if ($x < 1000){
			$temp = $this->kekata($x/100) . " ratus" . $this->kekata($x % 100);
		}else if ($x < 2000){
			$temp = " seribu" . $this->kekata($x - 1000);
		}else if ($x < 1000000){
			$temp = $this->kekata($x/1000) . " ribu" . $this->kekata($x % 1000);
		}else if ($x < 1000000000){
			$temp = $this->kekata($x/1000000) . " juta" . $this->kekata($x % 1000000);
		}else if ($x < 1000000000000){
			$temp = $this->kekata($x/1000000000) . " milyar" . $this->kekata(fmod($x,1000000000));
		}else if ($x < 1000000000000000){
			$temp = $this->kekata($x / 1000000000000) . " trilyun" . $this->kekata(fmod($x,1000000000000));
		}
		return $temp;
	}
 }
/* End of file ModelReport.php */
/* Location: ./appspj/modules/report/model/ModelReport.php */