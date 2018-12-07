<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*	
 *	@author : Sharif Uddin
 *	date	: April 01, 2016
 */

class User extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
		
    }
    
    /***default functin, redirects to login page if no admin logged in yet***/
    public function index()
    {
        //commonTasks();
        redirect(base_url() . 'index.php/admin/dashboard', 'refresh');
    }
    
    public function commonTasks(){
    	$data = parent::commonTasks();
    	$data['component'] = 'item';
    	return $data;
    }
    
    public function search()
    {
    	
    	$data = $this->commonTasks();
    	$data = $this->commonSearch($data);
    	$data['search'] = '';
    	if($this->input->post('search')!=null)
    		$data['search'] = $this->input->post('search');
    	$data['page_title'] = 'Users';
    	$data['page_name'] = 'home';
    	$data['searchAction'] = 'user/search';
    	$data['searchDisplayTxt'] = 'searchDisplayTxt';
    	$searchSQL = "SELECT * FROM user WHERE uniqueCode LIKE '%".$data['search']."%' ";
    	$pageSQL = " LIMIT ".($data['pageNo']-1)*$data['limit'].",  ".$data['limit'];
    	$query = $this->db->query($searchSQL);
    	$data['total'] = $query->num_rows();
    	//echo $searchSQL.$pageSQL;
    	//return;
    	$query1 = $this->db->query($searchSQL.$pageSQL);
    	$data['searchData'] = $query1->result();
    	
    
    	
    	$data['propertyArr'] = ['firstName'=>'First Name', 'lastName'=>'Last Name', 'uniqueCode'=>'User Name'];
    	$data['addmodifyAction'] = 'index.php/user/add';
    	 // Capitalize the first letter
    	
		$this->load->view('user/search/index.php', $data);
    }
    
    public function add($id = 0){
    	
    	$data = $this->commonTasks();
    	
    	$data['page_title'] = 'Add User';
    	$data['page_name'] = 'home';
    	
    	$data['userId'] = 0;
    	$data['version'] = 0;
    	$data['userName'] = '';
    	$data['firstName'] = '';
    	$data['lastName'] = '';
    	$data['email'] = '';
    	
    	if($id>0){
    		$query = $this->db->query("SELECT componentId, uniqueCode, firstName, lastName, email, version, status FROM user WHERE componentId = $id ");
		
			foreach ($query->result() as $row)
			{
				$data['userId'] = $row->componentId;
				$data['version'] = $row->version;
				$data['userName'] = $row->uniqueCode;
				$data['firstName'] = $row->firstName;
				$data['lastName'] = $row->lastName;
				$data['email'] = $row->email;
	    	}
    	}
    	$this->load->view('user/add/index', $data);
    }
    
    public function save($id = 0)
    {
    
    	$data = $this->commonTasks();
    	 
    	$data['page_title'] = 'Add User';
    	$data['page_name'] = 'home';
    	 
    	$data['userId'] = $this->input->post('id');
    	$dataToSave['version'] = $this->input->post('version');
    	$dataToSave['uniqueCode'] = $this->input->post('userName');
    	$dataToSave['firstName'] = $this->input->post('firstName');
    	$dataToSave['lastName'] = $this->input->post('lastName');
    	$dataToSave['email'] = $this->input->post('email');
    	$dataToSave['password'] = md5($this->input->post('password'));
    	$data['confirmPassword'] = $this->input->post('confirmPassword');
    	$data['fail_message'] = array();
    	if( $this->input->post('password') != $this->input->post('confirmPassword')){
    		array_push($data['fail_message'], 'Password mismath');
    	}
    	if( $this->input->post('userName') == null){
    		array_push($data['fail_message'], 'Username can not be null');
    	}
    	
    	if(count($data['fail_message'])){
    		$data['version'] = $this->input->post('version');
    		$data['userName'] = $this->input->post('userName');
    		$data['firstName'] = $this->input->post('firstName');
    		$data['lastName'] = $this->input->post('lastName');
    		$data['email'] = $this->input->post('email');
    		$data['password']= $this->input->post('password');
    		$this->load->view('user/add/index', $data);
    		return;
    	}
    	 
    	
    	if($data['userId']>0){
    		$dataToSave['componentId'] = $data['userId'];
            $this->db->where('componentId', $data['userId']);
    		$this->db->update('user', $dataToSave);
    	}else{
    		$this->db->insert('user',$dataToSave);
    	}
    
    	 redirect(base_url() . 'index.php/user/search', 'refresh');
    }
    public function delete()
    {
    	$data = $this->commonTasks();
    	
    	$data['page_title'] = 'Add User';
    	$data['page_name'] = 'home';
    	$data['id'] = $this->input->post('id');
    	$data['version'] = $this->input->post('version');
    	$data['userName'] = $this->input->post('userName');
    	$data['firstName'] = $this->input->post('firstName');
    	$data['lastName'] = $this->input->post('lastName');
    	$data['email'] = $this->input->post('email');
    	$data['password']= $this->input->post('password');
    	$this->db->where('componentId', $data['id']);
		if($this->db->delete('user')){
			redirect(base_url() . 'index.php/user/search', 'refresh');
        }else{
        	$this->load->view('user/add/index', $data);
        }
 
    	
    }
    
}