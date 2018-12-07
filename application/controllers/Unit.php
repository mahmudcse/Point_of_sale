<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*	
 *	@author : Sharif Uddin
 *	date	: April 01, 2016
 */

class Unit extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
		
    }
    
    /***default functin, redirects to login page if no admin logged in yet***/
    public function index()
    {
        //commonTasks();
        redirect(base_url() . 'index.php/unit/search', 'refresh');
    }
    public function commonTasks(){
    	$data = parent::commonTasks();
    	$data['component'] = 'unit';
    	return $data;
    }
    public function search()
    {
    	$data = $this->commonTasks();
    	$data = $this->commonSearch($data);
    	$data['search'] = '';
    	
    	if($this->input->post('search')!=null)
    		$data['search'] = $this->input->post('search');
    	$data['page_title'] = 'Units';
    	$data['page_name'] = 'home';
    	$data['searchAction'] = 'unit/search';
    	$data['searchDisplayTxt'] = 'searchDisplayTxt';
    	$searchSQL = "SELECT * FROM unit WHERE uniqueCode LIKE '%".$data['search']."%' ";
    	$pageSQL = " LIMIT ".($data['pageNo']-1)*$data['limit'].",  ".$data['limit'];
    	$query = $this->db->query($searchSQL);
    	$data['total'] = $query->num_rows();
    	//echo $searchSQL.$pageSQL;
    	//return;
    	$query1 = $this->db->query($searchSQL.$pageSQL);
    	$data['searchData'] = $query1->result();
    	
    
    	
    	$data['propertyArr'] = ['uniqueCode'=>'Name', 'description'=>'Description'];
    	$data['addmodifyAction'] = 'index.php/unit/add';
    	 // Capitalize the first letter
    	
		$this->load->view('unit/search/index.php', $data);
    }
    
    public function add($id = 0)
    {
    	
    	$data = $this->commonTasks();
    	if($id > 0)
    		$data['page_title'] = 'Modify Unit';
    	else
    		$data['page_title'] = 'Add Unit';
    	$data['page_name'] = 'home';
    	
    	$version = 0;
    	$name = '';
    	$description = '';
    	$status = '';
    	


    	if($id>0){
    		$query = $this->db->query("SELECT componentId, uniqueCode, description, version, status FROM unit WHERE componentId = $id ");
		
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
    	
    	$this->load->view('unit/add/index', $data);
    }
    
    public function save($id = 0)
    {
    
    	$data = $this->commonTasks();
    	 
    	$data['page_title'] = 'Add unit';
    	$data['page_name'] = 'home';
    	 
    	$data['id'] = $this->input->post('id');
    	$dataToSave['version'] = $this->input->post('version');
    	$dataToSave['uniqueCode'] = $this->input->post('name');
    	$dataToSave['description'] = $this->input->post('description');
    	
    	$data['fail_message'] = array();
    
    	if( $this->input->post('name') == null){
    		array_push($data['fail_message'], 'unitname can not be null');
    	}
    	
    	if(count($data['fail_message'])){
    		$data['version'] = $this->input->post('version');
    		$data['name'] = $this->input->post('name');
    		$data['description'] = $this->input->post('description');
    		$this->load->view('unit/add/index', $data);
    		return;
    	}
    	 
    	
    	if($data['id']>0){
    		$this->db->where('componentId', $data['id']);
    		$this->db->update('unit', $dataToSave);
    	}else{
    		$this->db->insert('unit',$dataToSave);
    	}
    
    	 redirect(base_url() . 'index.php/unit/search', 'refresh');
    }
    public function delete()
    {
    	$data = $this->commonTasks();
    	
    	$data['page_title'] = 'Add unit';
    	$data['page_name'] = 'home';
    	$data['id'] = $this->input->post('id');
    	$data['version'] = $this->input->post('version');
    	$data['unitName'] = $this->input->post('unitName');
    	$data['firstName'] = $this->input->post('firstName');
    	$data['lastName'] = $this->input->post('lastName');
    	$data['email'] = $this->input->post('email');
    	$data['password']= $this->input->post('password');
    	$this->db->where('componentId', $data['id']);
		if($this->db->delete('unit')){
			redirect(base_url() . 'index.php/unit/search', 'refresh');
        }else{
        	$this->load->view('unit/add/index', $data);
        }
 
    	
    }
    
    public function assignment()
    {
    	
    	 
    	$data = $this->commonTasks();
    	$data['page_title'] = 'User-Unit Assignment';
    	$data['page_name'] = 'home';
    	$userId  = -1;
    	
    	if($this->input->post('userId') !=null){
    		$userId = $this->input->post('userId');
    	}
    	
    	if($this->input->post('assign') != null){
    		$units = $this->input->post('units');
    		$userId = $this->input->post('userId');
    		
    		$this->db->where('userId', $userId);
    		$this->db->delete('userunit');
    		
    		foreach ($units as $unitId):
    			$this->db->insert('userunit', ['userId'=>$userId, 'unitId'=>$unitId]);
    		endforeach;
    		
    	}
    	
    	$data['searchAction'] = 'unit/assignment';
    	$data['assignAction'] = 'unit/assignment';
    	
    	$data['userId'] = $userId;
    	
    	$data['user'] = array('-1' => 'Select user');
    	foreach ($this->load('user') as $row){
    		$data['user'][$row->componentId] = $row->firstName.' '.$row->lastName.' ('.$row->uniqueCode.')';
    	}
    
    	$query = $this->db->query("SELECT r.componentId, r.uniqueCode, IFNULL(ur.userId, 0) assigned
								FROM unit r
								LEFT JOIN userunit ur ON (r.componentId = ur.unitId AND ur.userId = $userId)");
    	
    	$data['searchData'] = $query->result();
    	 
    	$this->load->view('unit/unitassign/index', $data);
    }
    
}
