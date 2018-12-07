<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*	
 *	@author : Sharif Uddin
 *	date	: April 01, 2016

 *  Software Developer
 */

class Account extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
		
    }
    
    /***default functin, redirects to login page if no admin logged in yet***/
    public function index()
    {
        //commonTasks();
        redirect(base_url() . 'index.php/account/search', 'refresh');
    }
    public function commonTasks(){
    	$data = parent::commonTasks();
    	$data['component'] = 'account';
    	return $data;
    }
    public function search()
    {
    	$data = $this->commonTasks();
    	$data = $this->commonSearch($data);
    	$data['search'] = '';
    	
    	if($this->input->post('search')!=null)
    		$data['search'] = $this->input->post('search');
    	$data['page_title'] = 'Account';
    	$data['page_name'] = 'home';
    	$data['searchAction'] = 'account/search';
    	$data['searchDisplayTxt'] = 'searchDisplayTxt';
    	$searchSQL = "SELECT * FROM account WHERE uniqueCode LIKE '%".$data['search']."%' ";
    	$pageSQL = " LIMIT 0, 20 ";
    	$query = $this->db->query($searchSQL);
    	$data['total'] = $query->num_rows();
    	//echo $searchSQL.$pageSQL;
    	//return;
    	$query1 = $this->db->query($searchSQL.$pageSQL);
    	$data['searchData'] = $query1->result();
    	
    
    	
    	$data['propertyArr'] = ['uniqueCode'=>'Name', 'description'=>'Description','category1'=>'Cat 1','category2'=>'Cat 2','category3'=>'Cat 3'];
    	$data['addmodifyAction'] = 'index.php/account/add';
    	 // Capitalize the first letter
    	
		$this->load->view('account/search/index.php', $data);
    }
    
    public function add($id = 0)
    {
    	
    	$data = $this->commonTasks();
    	if($id > 0)
    		$data['page_title'] = 'Modify Account';
    	else
    		$data['page_title'] = 'Add Account';
    	$data['page_name'] = 'home';
    	
    	$version = 0;
    	$name = '';
    	$description = '';
    	$status = '';
    	$category1 = '';
    	$category2 = '';
    	$category3 = '';
    	
    	$options1 = [
    			Applicationconst::ACCOUNT_CAT1_ASSET=>Applicationconst::ACCOUNT_CAT1_ASSET, 
    			Applicationconst::ACCOUNT_CAT1_LIABILITY=>Applicationconst::ACCOUNT_CAT1_LIABILITY,
    			Applicationconst::ACCOUNT_CAT1_EXPENSE=>Applicationconst::ACCOUNT_CAT1_EXPENSE,
    			Applicationconst::ACCOUNT_CAT1_REVENUE=>Applicationconst::ACCOUNT_CAT1_REVENUE,   			 
    	];
    	$options2 = [
    			''=>'NONE',
    			Applicationconst::ACCOUNT_CAT2_CURRENT_ASSET=>Applicationconst::ACCOUNT_CAT2_CURRENT_ASSET,
    			Applicationconst::ACCOUNT_CAT2_FIXED_ASSET=>Applicationconst::ACCOUNT_CAT2_FIXED_ASSET,
    			Applicationconst::ACCOUNT_CAT2_OPERATING_EXPENSE=>Applicationconst::ACCOUNT_CAT2_OPERATING_EXPENSE,
    			Applicationconst::ACCOUNT_CAT2_CURRENT_LIABILITY=>Applicationconst::ACCOUNT_CAT2_CURRENT_LIABILITY,
    			Applicationconst::ACCOUNT_CAT2_OTHER_LIABILITY=>Applicationconst::ACCOUNT_CAT2_OTHER_LIABILITY,
    			];
    	$options3 = [
    					''=>'NONE',
    					Applicationconst::ACCOUNT_CAT3_CASH=>Applicationconst::ACCOUNT_CAT3_CASH,
    					Applicationconst::ACCOUNT_CAT3_BANK=>Applicationconst::ACCOUNT_CAT3_BANK,
    			];

    	if($id>0){
    		$query = $this->db->query("SELECT componentId, uniqueCode, description,category1,category2,category3, version, status FROM account WHERE componentId = $id ");
		
			foreach ($query->result() as $row)
			{
				$id = $row->componentId;
				$version = $row->version;
				$name = $row->uniqueCode;
				$description = $row->description;
				$category1 = $row->category1;
				$category2 = $row->category2;
				$category3 = $row->category3;
				$status = $row->status;
	    	}
    	}
    	
    	$data['inputs'] = [
    			'0' =>['type'=>'hidden','fielddata'=>['name' => 'id', 'id' => 'id', 'value' => $id,]],
    			'1' =>['type'=>'hidden','fielddata'=>['name' => 'version', 'id' => 'version', 'value' => $version,]],
    			'2' =>['type'=>'hidden','fielddata'=>['name' => 'status', 'status' => 'status', 'value' => $status,]],
    			'3' =>['type'=>'textfield','fielddata'=>['name' => 'name', 'id' => 'name', 'value' => $name,]],
    			'4' =>['type'=>'textfield','fielddata'=>['name' => 'description', 'id' => 'description', 'value' => $description,]],
    			'5' =>['type'=>'dropdown','fielddata'=>['name' => 'category1', 'options' => $options1, 'value' => $category1,]],
    			'6' =>['type'=>'dropdown','fielddata'=>['name' => 'category2', 'options' => $options2, 'value' => $category2,]],
    			'7' =>['type'=>'dropdown','fielddata'=>['name' => 'category3', 'options' => $options3, 'value' => $category3,]]
    	];
    	
    	$this->load->view('account/add/index', $data);
    }
    
    public function save($id = 0)
    {
    
    	$data = $this->commonTasks();
    	 
    	$data['page_title'] = 'Add account';
    	$data['page_name'] = 'home';
    	 
    	$data['id'] = $this->input->post('id');
    	$dataToSave['version'] = $this->input->post('version');
    	$dataToSave['uniqueCode'] = $this->input->post('name');
    	$dataToSave['description'] = $this->input->post('description');
    	$dataToSave['category1'] = $this->input->post('category1');
    	$dataToSave['category2'] = $this->input->post('category2');
    	$dataToSave['category3'] = $this->input->post('category3');
    	
    	$data['fail_message'] = array();
    
    	if( $this->input->post('name') == null){
    		array_push($data['fail_message'], 'accountname can not be null');
    	}
    	
    	if(count($data['fail_message'])){
    		$data['version'] = $this->input->post('version');
    		$data['name'] = $this->input->post('name');
    		$data['description'] = $this->input->post('description');
    		$data['category1'] = $this->input->post('category1');
    		$data['category2'] = $this->input->post('category2');
    		$data['category3'] = $this->input->post('category3');
    		$this->load->view('account/add/index', $data);
    		return;
    	}
    	 
    	
    	if($data['id']>0){
    		$this->db->where('componentId', $data['id']);
    		$this->db->update('account', $dataToSave);
    	}else{
    		$this->db->insert('account',$dataToSave);
    	}
    
    	 redirect(base_url() . 'index.php/account/search', 'refresh');
    }
    public function delete()
    {
    	$data = $this->commonTasks();
    	
    	$data['page_title'] = 'Add account';
    	$data['page_name'] = 'home';
    	$data['id'] = $this->input->post('id');
    	$data['version'] = $this->input->post('version');
    	$data['accountName'] = $this->input->post('accountName');
    	$data['firstName'] = $this->input->post('firstName');
    	$data['lastName'] = $this->input->post('lastName');
    	$data['email'] = $this->input->post('email');
    	$data['password']= $this->input->post('password');
    	$this->db->where('componentId', $data['id']);
		if($this->db->delete('account')){
			redirect(base_url() . 'index.php/account/search', 'refresh');
        }else{
        	$this->load->view('account/add/index', $data);
        }
 
    	
    }
    
}
