<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*	
 *	@author : Sharif Uddin
 *	date	: April 01, 2016
 */

class Functioncode extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
		
    }
    
    /***default functin, redirects to login page if no admin logged in yet***/
    public function index()
    {
        //commonTasks();
        redirect(base_url() . 'index.php/functioncode/search', 'refresh');
    }
    public function commonTasks(){
    	$data = parent::commonTasks();
    	$data['component'] = 'functioncode';
    	return $data;
    }
    public function search()
    {
    	$data = $this->commonTasks();		
    	$data = $this->commonSearch($data);		
  
    	if($this->input->post('search')!=null)
    		$data['search'] = $this->input->post('search');
    	$data['page_title'] = 'Functions';
    	$data['page_name'] = 'home';
    	$data['searchAction'] = base_url() . 'index.php/functioncode/search';
    	$data['searchDisplayTxt'] = 'searchDisplayTxt';
    	$searchSQL = "SELECT componentId, uniqueCode, displayName, functionGroup, actionUrl, isMenu FROM functioncode WHERE uniqueCode LIKE '%".$data['search']."%' ";
    	$pageSQL = " LIMIT ".($data['pageNo']-1)*$data['limit'].",  ".$data['limit'];
    	$query = $this->db->query($searchSQL);
    	$data['total'] = $query->num_rows();
    	$query1 = $this->db->query($searchSQL.$pageSQL);
    	$data['searchData'] = $query1->result();
  		$data['propertyArr'] = ['uniqueCode'=>'Name','displayName'=>'Display Name', 'functionGroup'=>'Group','actionUrl'=>'URL','isMenu'=>'Menu'];
    	$data['addmodifyAction'] = 'index.php/functioncode/add';
    	 // Capitalize the first letter
    	
		$this->load->view('functioncode/search/index.php', $data);
    }
    
    public function add($id = 0)
    {
    	
    	$data = $this->commonTasks();
    	if($id > 0)
    		$data['page_title'] = 'Modify FunctionCode';
    	else
    		$data['page_title'] = 'Add FunctionCode';
    	$data['page_name'] = 'home';
    	
    	$version = 0;
    	$name = '';
		$displayName = '';
    	$functionGroup = '';
		$actionUrl = '';
		$isMenu = 0;
		$status = 1;
		
    	if($id>0){
    		$query = $this->db->query("SELECT componentId, uniqueCode, displayName, functionGroup,actionUrl, isMenu, version, status FROM functioncode WHERE componentId = $id ");
		
			foreach ($query->result() as $row)
			{
				$id = $row->componentId;
				$version = $row->version;
				$name = $row->uniqueCode;
				$displayName = $row->displayName;
				$functionGroup = $row->functionGroup;
				$isMenu = $row->isMenu;
				$actionUrl = $row->actionUrl;
				$status = $row->status;
	    	}
    	}
    	$data['inputs'] = [
    			'0' =>['type'=>'hidden','fielddata'=>['name' => 'id', 'id' => 'id', 'value' => $id,]],
    			'1' =>['type'=>'hidden','fielddata'=>['name' => 'version', 'id' => 'version', 'value' => $version,]],
    			'2' =>['type'=>'hidden','fielddata'=>['name' => 'status', 'id' => 'status', 'value' => $status,]],
    			'3' =>['type'=>'textfield','fielddata'=>['name' => 'displayName', 'id' => 'displayName', 'value' => $displayName,]],
    			'4' =>['type'=>'textfield','fielddata'=>['name' => 'name', 'id' => 'name', 'value' => $name,]],
				'5' =>['type'=>'textfield','fielddata'=>['name' => 'functionGroup', 'id' => 'functionGroup', 'value' => $functionGroup,]],
				'6' =>['type'=>'textfield','fielddata'=>['name' => 'actionUrl', 'id' => 'actionUrl', 'value' => $actionUrl,]],
				'7' =>['type'=>'textfield','fielddata'=>['name' => 'isMenu', 'id' => 'isMenu', 'value' => $isMenu,]],
    	];
    	
    	$this->load->view('functioncode/add/index', $data);
    }
    
    public function save($id = 0)
    {
    
    	$data = $this->commonTasks();
    	 
    	$data['page_title'] = 'Add Functioncode';
    	$data['page_name'] = 'home';
   
    	$data['id'] = $this->input->post('id');
    	$dataToSave['version'] = $this->input->post('version');
    	$dataToSave['uniqueCode'] = $this->input->post('name');
		$dataToSave['displayName'] = $this->input->post('displayName');
    	$dataToSave['functionGroup'] = $this->input->post('functionGroup');
		$dataToSave['actionUrl'] = $this->input->post('actionUrl');
		$dataToSave['isMenu'] = intval($this->input->post('isMenu'));
    	
    	$data['fail_message'] = array();
    
    	if( $this->input->post('name') == null){
    		array_push($data['fail_message'], 'name can not be null');
    	}
    	//uniqueCode, displayName, functionGroup,actionUrl, isMenu, version, status
    	if(count($data['fail_message'])){
    		$data['version'] = $this->input->post('version');
    		$data['name'] = $this->input->post('name');
			$data['displayName'] = $this->input->post('displayName');
    		$data['functionGroup'] = $this->input->post('functionGroup');
			$data['actionUrl'] = $this->input->post('actionUrl');
			$data['isMenu'] = intval ($this->input->post('isMenu'));
    		$this->load->view('functioncode/add/index', $data);
    		return;
    	}
    	 
    	$sql = "SELECT MAX(codeNumber) + 1 AS codenumber FROM functioncode";
    	$query = $this->db->query($sql);
    	$codeNumber = 0;
    	foreach ($query->result() as $row)
    	{
    		$codeNumber = $row->codenumber;
    	}
    	if($data['id']>0){
    		$this->db->where('componentId', $data['id']);
    		$this->db->update('functioncode', $dataToSave);
    	}else{
    		$dataToSave['codeNumber'] = $codeNumber;
    		$this->db->insert('functioncode',$dataToSave);
    	}
    
    	 redirect(base_url() . 'index.php/functioncode/search', 'refresh');
    }
    public function delete()
    {
    	$data = $this->commonTasks();
    	
    	$data['page_title'] = 'Delete function code';
    	$data['page_name'] = 'home';
    	$data['id'] = $this->input->post('id');
    	$data['version'] = $this->input->post('version');
    	$data['name'] = $this->input->post('name');
		$data['displayName'] = $this->input->post('displayName');
    	$data['functionGroup'] = $this->input->post('functionGroup');
		$data['actionUrl'] = $this->input->post('actionUrl');
		$data['isMenu'] = $this->input->post('isMenu');
    	$this->db->where('componentId', $data['id']);
		if($this->db->delete('function')){
			redirect(base_url() . 'index.php/functioncode/search', 'refresh');
        }else{
        	$this->load->view('item/functioncode/index', $data);
        }
    }
    
    
    public function assignment()
    {
    	$data = $this->commonTasks();
    	$data['page_title'] = 'Role-Function Assignment';
    	$data['page_name'] = 'home';
    	$roleId  = -1;
    
    	if($this->input->post('roleId') !=null){
    		$roleId = $this->input->post('roleId');
    	}
    
    	if($this->input->post('assign') != null){
    		$functions = $this->input->post('functions');
    		$roleId = $this->input->post('roleId');
    
    		$this->db->where('roleId', $roleId);
    		$this->db->delete('functionrole');
    
    		foreach ($functions as $functionId):
    		$this->db->insert('functionrole', ['functionId'=>$functionId, 'roleId'=>$roleId]);
    		endforeach;
    
    	}
    
    	$data['searchAction'] = 'functioncode/assignment';
    
    	$data['roleId'] = $roleId;
    
    	$data['role'] = array('-1' => 'Select role');
    	foreach ($this->load('role') as $row){
    		$data['role'][$row->componentId] = $row->uniqueCode;
    	}
    
    	$query = $this->db->query("SELECT f.componentId, f.uniqueCode, IFNULL(fr.roleId, 0) assigned
    			FROM functioncode f
    			LEFT JOIN functionrole fr ON (f.componentId = fr.functionId AND fr.roleId = $roleId)");
   
    	$data['searchData'] = $query->result();
    
    	$this->load->view('functioncode/functionassign/index', $data);
    }
}
