<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Description of Class ModelUser
 * @author Guntar 28/10/2013
 */
class ModelUser extends CI_Model {    
    private $table_name = 'user';
    private $username;
    private $password;    
    public function __construct() { parent::__construct(); }        
    public function login_user($username, $password) {      
        //$sql = "SELECT * FROM user US WHERE US.user_name = '".$username."' AND US.user_password='21232f297a57a5a743894a0e4a801fc3'";        
		$this->load->library('encrypt');
		$key = 'super-secret-key';
		//$pass = $this->encrypt->encode($password,$key);
		$pass = md5($password);
		//$pass1 = "NP+ceq+cX0t42a2lBZhJIRn0a06vGCTmKQRS1hW/5VijLJe+ePnlisajzmjKCzl2vm8cQx1YDo9a7noVRrKT1w==" ;
		$sql = "SELECT * FROM user US WHERE US.user_name = '".$username."' AND US.user_password='".$pass."'";   
        $query = $this->db->query($sql);               
        return $query;
    }    
    function checkUserLogin($username,$password) {
        $query = $this->db->where("user_name",$username);
        $query = $this->db->where("user_password",$password);
        $query = $this->db->limit(1,0);
        $query = $this->db->get($this->table_name);        
        if ($query->num_rows() == 0) { return NULL; }
        return TRUE;
    }
	function _unserialize($data) {
		$data = @unserialize(strip_slashes($data));
		if (is_array($data)) {
			foreach ($data as $key => $val)	{
				if (is_string($val)) { $data[$key] = str_replace('{{slash}}', '\\', $val); }
			}
			return $data;
		}
		return (is_string($data)) ? str_replace('{{slash}}', '\\', $data) : $data;
	}
	function checkUserName($username) {
        $query = $this->db->query("select count(*) jumlah from user where user_name = '".$username."' ");        
		$jumlah = $query->result();		
		$ret = $jumlah[0]->JUMLAH;		
		return $ret;
    }
	function get_data_user($username) {        
        $this->db->select("*");
        $this->db->from("user");
        $this->db->where("user_name",$userID);        
        $query = $this->db->get();        
        return $query;    
    }
}
/* End of file ModelUser.php */
/* Location: ./appspj/modules/user/models/ModelUser.php */