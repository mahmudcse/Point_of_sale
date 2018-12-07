<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*	
 * 
 * 
 *	@author : Sharif Uddin
 *	date	: April 01, 2016
 */

class Item extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
		
    }
    
    /***default functin, redirects to login page if no admin logged in yet***/
    public function index()
    {
        //commonTasks();
        redirect(base_url() . 'index.php/item/search', 'refresh');
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

        //echo $data['pageNo'].'-------->';		

    	$data['category1'] = '';		
    	if($this->input->post('category1')!=null)			
    		$data['category1'] = $this->input->post('category1');				
    	$options = [''=>'Select one',Applicationconst::ITEM_TYPE_INVENTORY=>Applicationconst::ITEM_TYPE_INVENTORY, Applicationconst::ITEM_TYPE_OTHERS=>Applicationconst::ITEM_TYPE_OTHERS];		    	
    	$data['inputs']['category'] = ['type'=>'dropdown','label'=>'Category 1', 'fielddata'=>['name' => 'category1', 'options'=>$options, 'value' => $data['category1'],]];    	
    	if($this->input->post('search')!=null)
    		$data['search'] = $this->input->post('search');
    	$data['page_title'] = 'Items';
    	$data['page_name'] = 'home';
    	$data['searchAction'] = base_url() . 'index.php/item/search';
    	$data['searchDisplayTxt'] = 'searchDisplayTxt';
    	$searchSQL = "SELECT * FROM item WHERE uniqueCode LIKE '%".$data['search']."%' and componentId != 0";    	    	if($data['category1']!='')    		$searchSQL .= " AND category1='".$data['category1']."'";
    	$pageSQL = " LIMIT ".($data['pageNo']-1)*$data['limit'].",  ".$data['limit'];
    	$query = $this->db->query($searchSQL);
    	$data['total'] = $query->num_rows();
    	//echo $searchSQL.$pageSQL;
    //	return;
    	$query1 = $this->db->query($searchSQL.$pageSQL);
    	$data['searchData'] = $query1->result();
  		$data['propertyArr'] = ['itemName'=>'Item Name','minQty'=>'Min Qty', 'salePrice'=>'Sale Price','category1'=>'Category 1','category2'=>'Category 2','category3'=>'Category 3'];
    	$data['addmodifyAction'] = 'index.php/item/add';
    	 // Capitalize the first letter
    	
		$this->load->view('item/search/index.php', $data);
    }
    
    public function add($id = 0)
    {
    	
    	$data = $this->commonTasks();
    	if($id > 0)
    		$data['page_title'] = 'Modify Item';
    	else
    		$data['page_title'] = 'Add Item';
    	$data['page_name'] = 'home';
    	
    	$version = 0;
    	$name = '';
		$itemName = '';
    	$category1 = '';
		$category2 = '';
		$category3 = '';
		$salePrice = '';
		$minQty = '';
		$unit = 0;
    	$status = '';

    	if($id>0){
    		$query = $this->db->query("SELECT componentId, uniqueCode, itemName, category1, category2, category3, salePrice, minQty, unitId, version, status FROM item WHERE componentId = $id ");
		
			foreach ($query->result() as $row)
			{
				$id = $row->componentId;
				$version = $row->version;
				$name = $row->uniqueCode;
				$itemName = $row->itemName;
				$category1 = $row->category1;
				$category2 = $row->category2;
				$category3 = $row->category3;
				$salePrice = $row->salePrice;
				$minQty = $row->minQty;
				$unit = $row->unitId;
				$status = $row->status;
	    	}
    	}
    	$options = [Applicationconst::ITEM_TYPE_INVENTORY=>Applicationconst::ITEM_TYPE_INVENTORY, Applicationconst::ITEM_TYPE_OTHERS=>Applicationconst::ITEM_TYPE_OTHERS];
    	$unitOptions = array();
    	foreach ($this->load('unit') as $row){
    		$unitOptions[$row->componentId] = $row->uniqueCode;
    	}
    	
    	$data['inputs'] = [
    			'0' =>['type'=>'hidden','fielddata'=>['name' => 'id', 'id' => 'id', 'value' => $id,]],
    			'1' =>['type'=>'hidden','fielddata'=>['name' => 'version', 'id' => 'version', 'value' => $version,]],
    			'2' =>['type'=>'hidden','fielddata'=>['name' => 'status', 'status' => 'status', 'value' => $status,]],
    			'3' =>['type'=>'textfield','fielddata'=>['name' => 'itemCode', 'id' => 'itemCode', 'value' => $name,]],
    			'4' =>['type'=>'textfield','fielddata'=>['name' => 'itemName', 'id' => 'itemName', 'value' => $itemName,]],
    			'5' =>['type'=>'dropdown','fielddata'=>['name' => 'category1', 'id' => 'category1','options'=>$options, 'value' => $category1,]],
				'6' =>['type'=>'textfield','fielddata'=>['name' => 'category2', 'id' => 'category2', 'value' => $category2,]],
				'7' =>['type'=>'textfield','fielddata'=>['name' => 'category3', 'id' => 'category3', 'value' => $category3,]],
				'8' =>['type'=>'textfield','fielddata'=>['name' => 'salePrice', 'id' => 'salePrice', 'value' => $salePrice,]],
				'9' =>['type'=>'textfield','fielddata'=>['name' => 'minQty', 'id' => 'minQty', 'value' => $minQty,]],
    			'10' =>['type'=>'dropdown', 'label' => 'Unit','fielddata'=>['name' => 'unit', 'id' => 'unit','options'=>$unitOptions, 'value' => $unit,]],
    	];
    	
    	$this->load->view('item/add/index', $data);
    }
    
    public function save($id = 0)
    {
    
    	$data = $this->commonTasks();
    	 
    	$data['page_title'] = 'Add item';
    	$data['page_name'] = 'home';
    	 
    	$data['id'] = $this->input->post('id');
    	$dataToSave['version'] = $this->input->post('version');
    	$dataToSave['uniqueCode'] = $this->input->post('itemCode');
		$dataToSave['itemName'] = $this->input->post('itemName');
    	$dataToSave['category1'] = $this->input->post('category1');
		$dataToSave['category2'] = $this->input->post('category2');
		$dataToSave['category3'] = $this->input->post('category3');
		$dataToSave['salePrice'] = $this->input->post('salePrice');
		$dataToSave['minQty'] = $this->input->post('minQty');
		$dataToSave['unitId'] = $this->input->post('unit');
    	
    	$data['fail_message'] = array();
    
    	if( $this->input->post('itemName') == null){
    		array_push($data['fail_message'], 'itemname can not be null');
    	}
    	
    	if(count($data['fail_message'])){
    		$data['version'] = $this->input->post('version');
    		$data['itemCode'] = $this->input->post('itemCode');
			$data['itemName'] = $this->input->post('itemName');
    		$data['category1'] = $this->input->post('category1');
			$data['category2'] = $this->input->post('category2');
			$data['category3'] = $this->input->post('category3');
			$data['salePrice'] = $this->input->post('salePrice');
			$data['minQty'] = $this->input->post('minQty');
    		$this->load->view('item/add/index', $data);
    		return;
    	}
    	 
    	
    	if($data['id']>0){
    		$this->db->where('componentId', $data['id']);
    		$this->db->update('item', $dataToSave);
    	}else{
    		$this->db->insert('item',$dataToSave);
    	}
    	 redirect(base_url() . 'index.php/item/search', 'refresh');
    }
    public function delete()
    {
    	$data = $this->commonTasks();
    	
    	$data['page_title'] = 'Add item';
    	$data['page_name'] = 'home';
    	$data['id'] = $this->input->post('id');
    	$data['version'] = $this->input->post('version');
    	$data['itemCode'] = $this->input->post('itemCode');
    	$data['firstName'] = $this->input->post('firstName');
    	$data['lastName'] = $this->input->post('lastName');
    	$data['email'] = $this->input->post('email');
    	$data['password']= $this->input->post('password');
    	$this->db->where('componentId', $data['id']);
		if($this->db->delete('item')){
			redirect(base_url() . 'index.php/item/search', 'refresh');
        }else{
        	$this->load->view('item/add/index', $data);
        }
 
    	
    }
    
}
