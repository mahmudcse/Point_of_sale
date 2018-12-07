<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Usersmodel extends MY_Model {
	
    
    var $firstName    = '';
    var $lastName    = '';
    var $password    = '';
    var $email    = '';
    var $contactNo    = '';
    var $groupId    = 0;
    var $componentId   = 0;

    var $menuJSON;

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        
        $this->tableName = 'user';
        
    }
    
    private function populateFields($obj){
    	$this->componentId  = $obj->componentId;
    	$this->uniqueCode 	= $obj->uniqueCode;
    	$this->firstName    = $obj->firstName;
    	$this->lastName    	= $obj->lastName;
    	$this->password    	= $obj->password;
    	$this->email    	= $obj->email;
    	$this->contactNo    = $obj->contactNo;
    	$this->groupId    	= $obj->groupId;
    	$this->version    	= $obj->version;
    	$this->status   	= $obj->status;
    	$this->createdBy   	= $obj->createdBy;
    	$this->createdDate  = $obj->createdDate;
    	$this->updatedBy   	= $obj->updatedBy;
    	$this->updatedDate  = $obj->updatedDate;
    }
    
    function get_last_ten_entries()
    {
        $query = $this->db->get('user', 10);
        return $query->result();
    }
    
    function getByCode($code)
    {
    	$query = $this->db->get_where('user', ['username'=>$code]);
    	return $query->result();
    }
    

    function insert_entry()
    {
        $this->name   = $_POST['name']; // please read the below note
        $this->username = $_POST['username'];
        $this->password = $_POST['password'];
        
        $this->status = 1;
        $this->createdBy = 0;
        $this->createdDate = time();

        $this->db->insert('user', $this);
    }

    function update_entry()
    {
        $this->name   = $_POST['name']; // please read the below note
        $this->username = $_POST['username'];
        $this->password = $_POST['password'];
        
        $this->status = 1;
        $this->createdBy = 0;
        $this->createdDate = time();

        $this->db->update('user', $this, array('componentId' => $_POST['componentId']));
    }
    
    function authenticate($user, $pass){
    	$query = $this->db->get_where('users', ['uniqueCode'=>$user]);
    	$data = $query->result();
    	
    	$res = $this->load->model('response');;
    	
    	if(count($data)>0){
    		$user = $data[0];
    		if(md5($pass) == $user->password){
    			$res->success = true;
    			$res->message = 'Login successful';
    			$res->data = $user;
    		}else{
    			$res->success = false;
    			$res->message = 'Invalid Password';
    			//$res->data = $user;
    		}
    	}else{
    		$res->success = false;
    		$res->message = 'Invalid login ID';
    		//$res->data = $user;
    	}
    	
    	return $res;
    }

}
?>