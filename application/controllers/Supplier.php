<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*	
 *	@author : Sharif Uddin
 *	date	: April 01, 2016
 */

class Supplier extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
		
    }
    
    /***default functin, redirects to login page if no admin logged in yet***/
    public function index()
    {
        //commonTasks();
        redirect(base_url() . 'index.php/supplier/search', 'refresh');
    }
    public function commonTasks(){
    	$data = parent::commonTasks();
    	$data['component'] = 'supplier';
    	return $data;
    }
    public function search()
    {
    	$data = $this->commonTasks();    	
    	$data = $this->commonSearch($data);
    	$data['search'] = '';
    	
    	if($this->input->post('search')!=null)
    		$data['search'] = $this->input->post('search');
    	$data['page_title'] = 'Suppliers';
    	$data['page_name'] = 'home';
    	$data['searchAction'] = 'supplier/search';
    	$data['searchDisplayTxt'] = 'searchDisplayTxt';
    	$searchSQL = "SELECT * FROM customer WHERE isSupplier = 1 AND uniqueCode LIKE '%".$data['search']."%' ";
    	$pageSQL = " LIMIT ".($data['pageNo']-1)*$data['limit'].",  ".$data['limit'];
    	$query = $this->db->query($searchSQL);
    	$data['total'] = $query->num_rows();
    	//echo $searchSQL.$pageSQL;
    	//return;
    	$query1 = $this->db->query($searchSQL.$pageSQL);
    	$data['searchData'] = $query1->result();
    	
    
    	
    	$data['propertyArr'] = ['name'=>'Supplier Name', 'address'=>'Description', 'city'=>'City', 'phone'=>'Phone'];
    	$data['addmodifyAction'] = 'index.php/supplier/add';
    	 // Capitalize the first letter
    	
		$this->load->view('supplier/search/index.php', $data);
    }
    
    public function add($id = 0)
    {
    	
    	$data = $this->commonTasks();
    	if($id > 0)
    		$data['page_title'] = 'Modify Custoer';
    	else
    		$data['page_title'] = 'Add Custoer';
    	$data['page_name'] = 'home';
    	
    	$version = 0;
    	$name = '';
		$name = '';
    	$address = '';
		$city = '';
		$phone = '';
    	$status = '';

    	if($id>0){
    		$query = $this->db->query("SELECT componentId, uniqueCode,name, address, city, phone, version, status FROM customer WHERE componentId = $id ");
		
			foreach ($query->result() as $row)
			{
				$id = $row->componentId;
				$version = $row->version;
				$name = $row->uniqueCode;
				$name = $row->name;
				$address = $row->address;
				$city = $row->city;
				$phone = $row->phone;
				$status = $row->status;
	    	}
    	}
    	
    	$data['inputs'] = [
    			'0' =>['type'=>'hidden','fielddata'=>['name' => 'id', 'id' => 'id', 'value' => $id,]],
    			'1' =>['type'=>'hidden','fielddata'=>['name' => 'version', 'id' => 'version', 'value' => $version,]],
    			'2' =>['type'=>'hidden','fielddata'=>['name' => 'status', 'status' => 'status', 'value' => $status,]],
				'3' =>['type'=>'textfield','fielddata'=>['name' => 'name', 'id' => 'name', 'value' => $name,]],
    			'4' =>['type'=>'textfield','fielddata'=>['name' => 'address', 'id' => 'address', 'value' => $address,]],
				'5' =>['type'=>'textfield','fielddata'=>['name' => 'city', 'id' => 'city', 'value' => $city,]],
				'6' =>['type'=>'textfield','fielddata'=>['name' => 'phone', 'id' => 'phone', 'value' => $phone,]]
    	];
    	
    	$this->load->view('supplier/add/index', $data);
    }
    
    public function save($id = 0)
    {
    
    	$data = $this->commonTasks();
    	 
    	$data['page_title'] = 'Add Supplier';
    	$data['page_name'] = 'home';
    	 
    	$data['id'] = $this->input->post('id');
    	$dataToSave['version'] = $this->input->post('version');
    	$dataToSave['uniqueCode'] = $this->input->post('name').uniqid();
		$dataToSave['name'] = $this->input->post('name');
    	$dataToSave['address'] = $this->input->post('address');
		$dataToSave['city'] = $this->input->post('city');
		$dataToSave['phone'] = $this->input->post('phone');
		$dataToSave['isSupplier'] = 1;
    	
    	$data['fail_message'] = array();
    
    	if( $this->input->post('name') == null){
    		array_push($data['fail_message'], 'Supplier name can not be null');
    	}
    	
    	if(count($data['fail_message'])){
    		$data['version'] = $this->input->post('version');
    		//$data['name'] = $this->input->post('name');
			$data['name'] = $this->input->post('name');
    		$data['address'] = $this->input->post('address');
			$data['city'] = $this->input->post('city');
			$data['Phone'] = $this->input->post('Phone');
    		$this->load->view('supplier/add/index', $data);
    		return;
    	}
    	 
    	
    	if($data['id']>0){
    		$this->db->where('componentId', $data['id']);
    		$this->db->update('customer', $dataToSave);
    	}else{
		
    		$this->db->insert('customer',$dataToSave);
    	}
    
    	 redirect(base_url() . 'index.php/supplier/search', 'refresh');
    }
    public function delete()
    {
    	$data = $this->commonTasks();
    	
    	$data['page_title'] = 'Delete Cuustomer';
    	$data['page_name'] = 'home';
    	$data['id'] = $this->input->post('id');
    	$data['version'] = $this->input->post('version');
    	$data['name'] = $this->input->post('name');
    	$data['email'] = $this->input->post('email');
    	$data['password']= $this->input->post('password');
    	$this->db->where('componentId', $data['id']);
		if($this->db->delete('customer')){
			redirect(base_url() . 'index.php/supplier/search', 'refresh');
        }else{
        	$this->load->view('supplier/add/index', $data);
        }
 
    	
    }
    
}
