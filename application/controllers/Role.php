<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*	
 *	@author : Sharif Uddin
 *	date	: April 01, 2016
 */

class Role extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
		
    }
    
    /***default functin, redirects to login page if no admin logged in yet***/
    public function index()
    {
        //commonTasks();
        redirect(base_url() . 'index.php/role/search', 'refresh');
    }
    public function commonTasks(){
    	$data = parent::commonTasks();
    	$data['component'] = 'role';
    	return $data;
    }
    public function search()
    {
    	$data = $this->commonTasks();
    	$data = $this->commonSearch($data);
    	$data['search'] = '';
    	
    	if($this->input->post('search')!=null)
    		$data['search'] = $this->input->post('search');
    	$data['page_title'] = 'Roles';
    	$data['page_name'] = 'home';
    	$data['searchAction'] = 'role/search';
    	$data['searchDisplayTxt'] = 'searchDisplayTxt';
    	$searchSQL = "SELECT * FROM role WHERE uniqueCode LIKE '%".$data['search']."%' ";
    	$pageSQL = " LIMIT ".($data['pageNo']-1)*$data['limit'].",  ".$data['limit'];
    	$query = $this->db->query($searchSQL);
    	$data['total'] = $query->num_rows();
    	//echo $searchSQL.$pageSQL;
    	//return;
    	$query1 = $this->db->query($searchSQL.$pageSQL);
    	$data['searchData'] = $query1->result();
    	
    
    	
    	$data['propertyArr'] = ['uniqueCode'=>'Name', 'description'=>'Description'];
    	$data['addmodifyAction'] = 'index.php/role/add';
    	 // Capitalize the first letter
    	
		$this->load->view('role/search/index.php', $data);
    }
    
    public function add($id = 0)
    {
    	
    	$data = $this->commonTasks();
    	if($id > 0)
    		$data['page_title'] = 'Add Role';
    	else
    		$data['page_title'] = 'Modify Role';
    	$data['page_name'] = 'home';
    	
    	$version = 0;
    	$name = '';
    	$description = '';
    	$status = '';

    	if($id>0){
    		$query = $this->db->query("SELECT componentId, uniqueCode, description, version, status FROM role WHERE componentId = $id ");
		
			foreach ($query->result() as $row)
			{
				$id = $row->componentId;
				$version = $row->version;
				$name = $row->uniqueCode;
				$description = $row->description;
				$status = $row->status;
	    	}
    	}
    	
    	$data['inputs'] = [
    			'0' =>['type'=>'hidden','fielddata'=>['name' => 'id', 'id' => 'id', 'value' => $id,]],
    			'1' =>['type'=>'hidden','fielddata'=>['name' => 'version', 'id' => 'version', 'value' => $version,]],
    			'2' =>['type'=>'hidden','fielddata'=>['name' => 'status', 'status' => 'status', 'value' => $status,]],
    			'3' =>['type'=>'textfield','fielddata'=>['name' => 'name', 'id' => 'name', 'value' => $name,]],
    			'4' =>['type'=>'textfield','fielddata'=>['name' => 'description', 'id' => 'description', 'value' => $description,]]
    	];
    	
    	$this->load->view('role/add/index', $data);
    }
    
    public function save($id = 0)
    {
    
    	$data = $this->commonTasks();
    	 
    	$data['page_title'] = 'Add role';
    	$data['page_name'] = 'home';
    	 
    	$data['id'] = $this->input->post('id');
    	$dataToSave['version'] = $this->input->post('version');
    	$dataToSave['uniqueCode'] = $this->input->post('name');
    	$dataToSave['description'] = $this->input->post('description');
    	
    	$data['fail_message'] = array();
    
    	if( $this->input->post('name') == null){
    		array_push($data['fail_message'], 'rolename can not be null');
    	}
    	
    	if(count($data['fail_message'])){
    		$data['version'] = $this->input->post('version');
    		$data['name'] = $this->input->post('name');
    		$data['description'] = $this->input->post('description');
    		$this->load->view('role/add/index', $data);
    		return;
    	}
    	 
    	
    	if($data['id']>0){
    		$this->db->where('componentId', $data['id']);
    		$this->db->update('role', $dataToSave);
    	}else{
    		$this->db->insert('role',$dataToSave);
    	}
    
    	 redirect(base_url() . 'index.php/role/search', 'refresh');
    }
    public function delete()
    {
    	$data = $this->commonTasks();
    	
    	$data['page_title'] = 'Add role';
    	$data['page_name'] = 'home';
    	$data['id'] = $this->input->post('id');
    	$data['version'] = $this->input->post('version');
    	$data['roleName'] = $this->input->post('roleName');
    	$data['firstName'] = $this->input->post('firstName');
    	$data['lastName'] = $this->input->post('lastName');
    	$data['email'] = $this->input->post('email');
    	$data['password']= $this->input->post('password');
    	$this->db->where('componentId', $data['id']);
		if($this->db->delete('role')){
			redirect(base_url() . 'index.php/role/search', 'refresh');
        }else{
        	$this->load->view('role/add/index', $data);
        }
 
    	
    }
    
    public function assignment()
    {
    	
    	 
    	$data = $this->commonTasks();
    	$data['page_title'] = 'User-Role Assignment';
    	$data['page_name'] = 'home';
    	$userId  = -1;
    	
    	if($this->input->post('userId') !=null){
    		$userId = $this->input->post('userId');
    	}
    	
    	if($this->input->post('assign') != null){
    		$roles = $this->input->post('roles');
    		$userId = $this->input->post('userId');
    		
    		$this->db->where('userId', $userId);
    		$this->db->delete('userrole');
    		
    		foreach ($roles as $roleId):
    			$this->db->insert('userrole', ['userId'=>$userId, 'roleId'=>$roleId]);
    		endforeach;
    		
    	}
    	
    	$data['searchAction'] = 'role/assignment';
    	$data['assignAction'] = 'role/assignment';
    	
    	$data['userId'] = $userId;
    	
    	$data['user'] = array('-1' => 'Select user');
    	foreach ($this->load('user') as $row){
    		$data['user'][$row->componentId] = $row->firstName.' '.$row->lastName.' ('.$row->uniqueCode.')';
    	}
    
    	$query = $this->db->query("SELECT r.componentId, r.uniqueCode, IFNULL(ur.userId, 0) assigned
								FROM role r
								LEFT JOIN userrole ur ON (r.componentId = ur.roleId AND ur.userId = $userId)");
    	
    	$data['searchData'] = $query->result();
    	 
    	$this->load->view('role/roleassign/index', $data);
    }
    
}
