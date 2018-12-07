<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*	
 * 
 * 
 *	@author : Sharif Uddin
 *	date	: April 01, 2016
 */

class Company extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('companymodel');
    }
    
    /***default functin, redirects to login page if no admin logged in yet***/
    public function index()
    {
        //commonTasks();
        redirect(base_url() . 'index.php/company/search', 'refresh');
    }
    public function commonTasks(){
    	$data = parent::commonTasks();
    	$data['component'] = 'company';
    	return $data;
    }
    public function search()
    {
    	$data = $this->commonTasks();		
    	$data = $this->commonSearch($data);

        //echo $data['pageNo'].'-------->';		

    	$data['parentId'] = '';		
    	if($this->input->post('parentId')!=null)			
    		$data['parentId'] = $this->input->post('parentId');				
    	$options = [0=>'Select one'];	
    	
    	$arr = $this->companymodel->getAll();
    	
    	foreach ($arr as $comp):
    		$options[$comp->componentId]=$comp->name;
    	endforeach;
    	
    	$data['inputs']['category'] = ['type'=>'dropdown','label'=>'Parent Company', 'fielddata'=>['name' => 'parentId', 'options'=>$options, 'value' => $data['parentId'],]];    	
    	if($this->input->post('search')!=null)
    		$data['search'] = $this->input->post('search');
    	$data['page_title'] = 'Company hierarchy';
    	$data['page_name'] = 'Company Hierarchy';
    	$data['searchAction'] = base_url() . 'index.php/company/search';
    	$data['searchDisplayTxt'] = 'searchDisplayTxt';
    	$searchSQL = "SELECT c.componentId, c.name, p.name AS parent, c.address, c.contact FROM company c 
			LEFT JOIN company p ON c.parentId = p.componentId 
			WHERE c.uniqueCode LIKE '%".$data['search']."%' and c.componentId != 0";    	    	
    	if($data['parentId']!='')    		
    			$searchSQL .= " AND c.parentId='".$data['parentId']."'";
    	$pageSQL = " LIMIT ".($data['pageNo']-1)*$data['limit'].",  ".$data['limit'];
    	$query = $this->db->query($searchSQL);
    	$data['total'] = $query->num_rows();
    	//echo $searchSQL.$pageSQL;
    //	return;
    	$query1 = $this->db->query($searchSQL.$pageSQL);
    	$data['searchData'] = $query1->result();
    	$data['propertyArr'] = ['name'=>'Name','address'=>'Address','contact'=>'Contact','parent'=>'Parent Company'];
    	$data['addmodifyAction'] = 'index.php/company/add';
    	 // Capitalize the first letter
    	
		$this->load->view('company/search/index.php', $data);
    }
    
    public function add($id = 0)
    {
    	
    	$data = $this->commonTasks();
    	if($id > 0)
    		$data['page_title'] = 'Modify Company';
    	else
    		$data['page_title'] = 'Add Company';
    	$data['page_name'] = 'home';
    	
    	$version = 0;
    	$uniqueCode = '';
		$name = '';
    	$parentId = '';
		$address = '';
		$contact = '';
		
    	$status = '';

    	if($id>0){
    		$query = $this->db->query("SELECT componentId, uniqueCode, name, parentId, address, contact, version, status FROM company WHERE componentId = $id ");
		
			foreach ($query->result() as $row)
			{
				$id = $row->componentId;
				$version = $row->version;
				$uniqueCode = $row->uniqueCode;
				$name = $row->name;
				$parentId = $row->parentId;
				$address = $row->address;
				$contact = $row->contact;


				$status = $row->status;
	    	}
    	}
    	
    	$options = [0=>'Select one'];
    	$arr = $this->companymodel->getAll();
    	foreach ($arr as $comp):
    		$options[$comp->componentId]=$comp->name;
    	endforeach;
    	
    	$data['inputs'] = [
    			'0' =>['type'=>'hidden','fielddata'=>['class'=>'form-control', 'name' => 'id', 'id' => 'id', 'value' => $id,]],
    			'1' =>['type'=>'hidden','fielddata'=>['class'=>'form-control', 'name' => 'version', 'id' => 'version', 'value' => $version,]],
    			'2' =>['type'=>'hidden','fielddata'=>['class'=>'form-control', 'name' => 'status', 'status' => 'status', 'value' => $status,]],
    			'3' =>['type'=>'hidden', 'label'=>'Code','fielddata'=>['class'=>'form-control', 'name' => 'uniqueCode', 'id' => 'uniqueCode', 'value' => $uniqueCode,]],
    			'4' =>['type'=>'textfield', 'label'=>'Name', 'fielddata'=>['class'=>'form-control', 'name' => 'name', 'id' => 'name', 'value' => $name,]],
    			'5' =>['type'=>'dropdown', 'label'=>'Parent Company', 'fielddata'=>['class'=>'form-control', 'name' => 'parentId', 'id' => 'parentId','options'=>$options, 'value' => $parentId,]],
    			'6' =>['type'=>'textfield', 'label'=>'Address', 'fielddata'=>['class'=>'form-control', 'name' => 'address', 'id' => 'address', 'value' => $address,]],
    			'7' =>['type'=>'textfield', 'label'=>'Contact', 'fielddata'=>['class'=>'form-control', 'name' => 'contact', 'id' => 'contact', 'value' => $contact,]],
    			
    	];
    	
    	$this->load->view('company/add/index', $data);
    }
    
    public function save($id = 0)
    {
    
    	$data = $this->commonTasks();
    	 
    	$data['page_title'] = 'Add company';
    	$data['page_name'] = 'home';
    	 
    	$data['id'] = $this->input->post('id');
    	$dataToSave['version'] = $this->input->post('version');
    	$dataToSave['uniqueCode'] = $this->input->post('uniqueCode');
		$dataToSave['name'] = $this->input->post('name');
    	$dataToSave['parentId'] = $this->input->post('parentId');
		$dataToSave['address'] = $this->input->post('address');
		$dataToSave['contact'] = $this->input->post('contact');

	
    	
    	$data['fail_message'] = array();
    
    	if( $this->input->post('name') == null){
    		array_push($data['fail_message'], 'companyname can not be null');
    	}
    	
    	if(count($data['fail_message'])){
    		$data['version'] = $this->input->post('version');
    		$data['uniqueCode'] = $this->input->post('uniqueCode');
			$data['name'] = $this->input->post('name');
    		$data['parentId'] = $this->input->post('parentId');
			$data['address'] = $this->input->post('address');
			$data['contact'] = $this->input->post('contact');

    		$this->load->view('company/add/index', $data);
    		return;
    	}
    	 
    	
    	if($data['id']>0){
    		$this->db->where('componentId', $data['id']);
    		$this->db->update('company', $dataToSave);
    	}else{
    		$this->db->insert('company',$dataToSave);
    	}
    	 redirect(base_url() . 'index.php/company/search', 'refresh');
    }
    public function delete()
    {
    	$data = $this->commonTasks();
    	
    	$data['page_title'] = 'Add company';
    	$data['page_name'] = 'home';
    	$data['id'] = $this->input->post('id');
    	$data['version'] = $this->input->post('version');
    	$data['uniqueCode'] = $this->input->post('uniqueCode');
    	$data['firstName'] = $this->input->post('firstName');
    	$data['lastName'] = $this->input->post('lastName');
    	$data['email'] = $this->input->post('email');
    	$data['password']= $this->input->post('password');
    	$this->db->where('componentId', $data['id']);
		if($this->db->delete('company')){
			redirect(base_url() . 'index.php/company/search', 'refresh');
        }else{
        	$this->load->view('company/add/index', $data);
        }
 
    	
    }
    
    public function assignment()
    {
    	$data = $this->commonTasks();
    	$data['page_title'] = 'User-Company Assignment';
    	$data['page_name'] = 'home';
    	$userId  = -1;
    	
    	if($this->input->post('userId') !=null){
    		$userId = $this->input->post('userId');
    	}
    	
    	if($this->input->post('assign') != null){
    		$companies= $this->input->post('companies');
    		$userId = $this->input->post('userId');
    		
    		$this->db->where('userId', $userId);
    		$this->db->delete('usercompany');
    		
    		foreach ($companies as $companyId):
    		$this->db->insert('usercompany', ['userId'=>$userId, 'companyId'=>$companyId]);
    		endforeach;
    		
    	}
    	
    	$data['searchAction'] = 'company/assignment';
    	$data['assignAction'] = 'company/assignment';
    	
    	$data['userId'] = $userId;
    	
    	$data['user'] = array('-1' => 'Select user');
    	foreach ($this->load('user') as $row){
    		$data['user'][$row->componentId] = $row->firstName.' '.$row->lastName.' ('.$row->uniqueCode.')';
    	}
    	
    	$query = $this->db->query("SELECT c.componentId, c.name, IFNULL(uc.userId, 0) assigned
    			FROM company c
    			LEFT JOIN usercompany uc ON (c.componentId = uc.companyId AND uc.userId = $userId)");
    	
    	$data['searchData'] = $query->result();
    	
    	$this->load->view('company/companyassign/index', $data);
    }
    
}
