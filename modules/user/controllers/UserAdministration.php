<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Description of Class UserAdministration 
 * @author Guntar 30/10/2013
 */
class UserAdministration extends MX_Controller {    	
	function __construct() {
		parent::__construct();
		$userdata = $this->session->userdata('logged_in');
		$this -> load -> library('grocery_CRUD');
		$this -> load -> model('master/ModelMaster','mm',TRUE);
	}
	public function _userAdministration_output($output = NULL, $judul = NULL) {
		$data = array('title' => 'BBPPT | User Administration', 
					  'userdata' => $this -> session -> userdata('username'));
		$this -> load -> view('header', $data);
		$this -> load -> view('main', $data);
		$this -> load -> view('user/ViewUser.php', $output);
		$this -> load -> view('footer', $data);
	}	
	function index() {
		//if($this->session->userdata('logged_in') == ''){ redirect('home',TRUE); }
		$sess = $this->session->userdata('logged_in');
		if($sess == FALSE){
			redirect('Home',TRUE);
		}else{
			$this ->_userAdministration_output((object) array('output' => '', 'js_files' => array(), 'css_files' => array()));	
		}
		
	}
	public function users(){
	  	try {
		  	$crud = new grocery_CRUD();	 
			$role = $this->session->userdata['role'];
			switch($role){
				case "Administrator":
					$crud -> set_theme('datatables')//twitter-bootstrap
						  -> set_table('user')			  
						  -> set_subject('User')
						  -> required_fields('user_name','user_password')          
						  -> columns('nama','user_name','role')
						  -> fields('nama','user_name','user_password','role')
						  -> field_type('user_password', 'password')
						  -> callback_before_insert(array($this,'encrypt_password_callback'))
						  -> callback_before_update(array($this,'encrypt_password_callback'))
						  -> callback_edit_field('password',array($this,'decrypt_password_callback'))
						  -> unset_export()
						  -> unset_print();
					break;
				case "Direktur":
					redirect('Home',TRUE);
					break;
				case "User":
					redirect('Home',TRUE);
					break;
			}
		    		 
			$output = $crud->render();
	    	$this-> _userAdministration_output($output);
		} catch(Exception $e) {
			show_error($e -> getMessage() . ' --- ' . $e -> getTraceAsString());
		}
	}
	function notaSurat() {
		try {
			$crud = new grocery_CRUD();
			$role = $this->session->userdata['role'];
			switch($role){
				case "Administrator":
					$crud -> set_theme('datatables')
						  -> set_table('nota_dinas')
						  -> set_subject('Nota Surat')				  
						  -> fields('nomor', 'tanggal', 'tentang')
						  -> required_fields('nomor','tentang')				  
						  -> unset_export()
						  -> unset_print();
					break;
				case "Direktur":
					$crud -> set_theme('datatables')
						  -> set_table('nota_dinas')
						  -> set_subject('Nota Surat')				  
						  -> fields('nomor', 'tanggal', 'tentang')
						  -> required_fields('nomor','tentang')				  
						  -> unset_export()
						  -> unset_print()
						  -> unset_delete();
					break;
				case "User":
					$crud -> set_theme('datatables')
						  -> set_table('nota_dinas')
						  -> set_subject('Nota Surat')				  
						  -> fields('nomor', 'tanggal', 'tentang')
						  -> required_fields('nomor','tentang')				  
						  -> unset_export()
						  -> unset_print()
						  -> unset_delete();
					break;
			}
			
			$output = $crud -> render();
			$this->_userAdministration_output($output);			
		} catch(Exception $e) {
			show_error($e -> getMessage() . ' --- ' . $e -> getTraceAsString());
		}
	}
	function notaSuratExtra() {
		try {
			$crud = new grocery_CRUD();
			$role = $this->session->userdata['role'];
			switch($role){
				case "Administrator":
					$crud -> set_theme('datatables')
						  -> set_table('jenis_surat')
						  -> set_subject('Nota Tambahan')				  
						  -> fields('jenis_undangan','nomor', 'tanggal', 'perihal','penerbit_surat')
						  -> required_fields('jenis_undangan','nomor', 'tanggal', 'perihal')
						  -> display_as('jenis_undangan', 'Jenis Nota')				 				 				  
						  -> unset_export()
						  -> unset_print();
					break;
				case "Direktur":
					$crud -> set_theme('datatables')
						  -> set_table('jenis_surat')
						  -> set_subject('Nota Tambahan')				  
						  -> fields('jenis_undangan','nomor', 'tanggal', 'perihal','penerbit_surat')
						  -> required_fields('jenis_undangan','nomor', 'tanggal', 'perihal')
						  -> display_as('jenis_undangan', 'Jenis Nota')				 				 				  
						  -> unset_export()
						  -> unset_print()
						  -> unset_delete();
					break;	
				case "User":
					$crud -> set_theme('datatables')
						  -> set_table('jenis_surat')
						  -> set_subject('Nota Tambahan')				  
						  -> fields('jenis_undangan','nomor', 'tanggal', 'perihal','penerbit_surat')
						  -> required_fields('jenis_undangan','nomor', 'tanggal', 'perihal')
						  -> display_as('jenis_undangan', 'Jenis Nota')				 				 				  
						  -> unset_export()
						  -> unset_print()
						  -> unset_delete();
					break;	
			}
			
			$output = $crud -> render();
			$this->_userAdministration_output($output);			
		} catch(Exception $e) {
			show_error($e -> getMessage() . ' --- ' . $e -> getTraceAsString());
		}
	}
	function no_tanggal_alokasiAnggaran(){
		try {
			$crud = new grocery_CRUD();
			$role = $this->session->userdata['role'];
			if ($role == 'Administrator'){
				$crud -> set_theme('datatables')
						  -> set_table('no_tgl_anggaran')
						  -> set_subject('Nomor Alokasi Anggaran')				  
						  -> fields('nomor','tanggal')
						  -> required_fields('nomor','tanggal')			 				 				  
						  -> unset_export()
						  -> unset_print();
			}
			$output = $crud -> render();
			$this->_userAdministration_output($output);
		} catch(Exception $e) {
			show_error($e -> getMessage() . ' --- ' . $e -> getTraceAsString());
		}	
	}
	function alokasiAnggaran() {
		try {
			$crud = new grocery_CRUD();
			$role = $this->session->userdata['role'];
			switch($role){
				case "Administrator":
					$crud -> set_theme('flexigrid')
						  -> set_table('alokasi_anggaran')
						  -> set_relation('nomor', 'no_tgl_anggaran', '{nomor}')
						  //-> set_relation('tanggal', 'no_tgl_anggaran', '{tanggal}')
						  -> set_subject('Alokasi Anggaran')				  
						  -> fields('nomor','kode_kegiatan', 'sub_kode_kegiatan','kegiatan','mak1','mak1_ket', 'mak2', 'mak2_ket', 'mak3', 'mak3_ket')
						  -> required_fields('nomor','kode_kegiatan', 'sub_kode_kegiatan','kegiatan','mak1','mak1_ket', 'mak2', 'mak2_ket', 'mak3', 'mak3_ket')			 				 				  
						 // -> display_as('kode_kegiatan','Test')
						 // -> display_as('sub_kode_kegiatan','Andri')
						  -> unset_export()
						  -> unset_print();
					break;
				case "Direktur":
					$crud -> set_theme('flexigrid')
						  -> set_table('alokasi_anggaran')
						  -> set_relation('nomor','no_tgl_anggaran', '{nomor}')
						 // -> set_relation('tanggal', 'no_tgl_anggaran', '{tanggal}')
						  -> set_subject('Alokasi Anggaran')				  
						  -> fields('nomor','kode_kegiatan', 'sub_kode_kegiatan','kegiatan','mak1','mak1_ket', 'mak2', 'mak2_ket', 'mak3', 'mak3_ket')
						  -> required_fields('nomor','kode_kegiatan', 'sub_kode_kegiatan','kegiatan','mak1','mak1_ket', 'mak2', 'mak2_ket', 'mak3', 'mak3_ket')			 				 				  			 				 				  
						  -> unset_export()
						  -> unset_print()
						  -> unset_delete();
					break;
				case "User":
					$crud -> set_theme('flexigrid')
						  -> set_table('alokasi_anggaran')
						  -> set_relation('nomor', 'no_tgl_anggaran', '{nomor}')
						 // -> set_relation('tanggal', 'no_tgl_anggaran', '{tanggal}')
						  -> set_subject('Alokasi Anggaran')				  
						  -> fields('nomor','kode_kegiatan', 'sub_kode_kegiatan','kegiatan','mak1','mak1_ket', 'mak2', 'mak2_ket', 'mak3', 'mak3_ket')
						  -> required_fields('nomor','kode_kegiatan', 'sub_kode_kegiatan','kegiatan','mak1','mak1_ket', 'mak2', 'mak2_ket', 'mak3', 'mak3_ket')			 				 				  	 				 				  
						  -> unset_export()
						  -> unset_print()
						  -> unset_delete();
					break;
			}

			$output = $crud -> render();
			$this->_userAdministration_output($output);			
		} catch(Exception $e) {
			show_error($e -> getMessage() . ' --- ' . $e -> getTraceAsString());
		}
	}
	/*
	function mak() {
		try {
			$crud = new grocery_CRUD();
			$crud -> set_theme('datatables')
				  -> set_table('mak')
				  -> set_subject('MAK')	
				  -> set_relation('fk_anggaran', 'alokasi_anggaran', 'nomor')				  
				  -> fields('fk_anggaran','mak1','mak1_ket', 'mak2', 'mak2_ket', 'mak3', 'mak3_ket')
				  -> required_fields('fk_anggaran')			 				 				  
				  -> display_as('fk_anggaran','Nomor Anggaran')
				  //-> display_as('mak1','')
				  -> unset_export()
				  -> unset_print();
			$output = $crud -> render();
			$this->_userAdministration_output($output);			
		} catch(Exception $e) {
			show_error($e -> getMessage() . ' --- ' . $e -> getTraceAsString());
		}
	}*/
	
	function subdit() {
		try {
			$crud = new grocery_CRUD();
			$role = $this->session->userdata['role'];
			switch($role){
				case "Administrator":
					$crud -> set_theme('datatables')
						  -> set_table('subdit')
						  -> set_subject('Subdit SDPPI')				  
						  -> fields('subdit','direktorat')
						  -> required_fields('subdit','direktorat')				  				  
						  -> unset_export()
						  -> unset_print();
					break;
				case "Direktur":
					$crud -> set_theme('datatables')
						  -> set_table('subdit')
						  -> set_subject('Subdit SDPPI')				  
						  -> fields('subdit','direktorat')
						  -> required_fields('subdit','direktorat')				  				  
						  -> unset_export()
						  -> unset_print()
						  -> unset_delete();
					break;
				case "User":
					$crud -> set_theme('datatables')
						  -> set_table('subdit')
						  -> set_subject('Subdit SDPPI')				  
						  -> fields('subdit','direktorat')
						  -> required_fields('subdit','direktorat')				  				  
						  -> unset_export()
						  -> unset_print()
						  -> unset_delete()
						  -> unset_edit()
						  -> unset_add();
					break;
			}

			
			$output = $crud -> render();
			$this->_userAdministration_output($output);			
		} catch(Exception $e) {
			show_error($e -> getMessage() . ' --- ' . $e -> getTraceAsString());
		}
	}
	function encrypt_password_callback($post_array, $primary_key = null) {
	    $this->load->library('encrypt');	 
	    $key = 'super-secret-key';
	    //$post_array['user_password'] = $this->encrypt->encode($post_array['user_password'],$key);
		$post_array['user_password'] = md5($post_array['user_password']);
	    return $post_array;
	}	 
	function decrypt_password_callback($value) {
	    $this->load->library('encrypt');	 
	    $key = 'super-secret-key';
	    $decrypted_password = $this->encrypt->decode($value,$key);
	    return "<input type='password' name='password' value='$decrypted_password' />";
	}	
}
/* End of file UserAdministration.php */
/* Location: ./appspj/modules/user/controllers/UserAdministration.php */