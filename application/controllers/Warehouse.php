<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 *
 *
 *	@author : Sharif Uddin
 *	date	: April 01, 2016
 */

class Warehouse extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('warehousemodel');
        $this->load->model('itemmodel');
        $this->load->model("userwarehousemodel");
        $this->load->model("inventorymodel");
    }
    
    /***default functin, redirects to login page if no admin logged in yet***/
    public function index()
    {
        //commonTasks();
        redirect(base_url() . 'index.php/warehouse/search', 'refresh');
    }
    public function commonTasks(){
    	$data = parent::commonTasks();
    	$data['component'] = 'warehouse';
    	return $data;
    }

    public function prntar($data){
        echo "<pre>";
        print_r($data);
        echo "</pre>";
        exit();
    }

    public function search()
    {
    	$data = $this->commonTasks();		
    	$data = $this->commonSearch($data);	

    	$data['parentId'] = '';		
    	if($this->input->post('parentId')!=null)			
    		$data['parentId'] = $this->input->post('parentId');				
    	$options = [0=>'Select one'];	
    	
    	$arr = $this->warehousemodel->getAll();
    	
    	foreach ($arr as $comp):
    		$options[$comp->componentId]=$comp->name;
    	endforeach;
    	
    	//$data['inputs']['category'] = ['type'=>'dropdown','label'=>'Parent Warehouse', 'fielddata'=>['name' => 'parentId', 'options'=>$options, 'value' => $data['parentId'],]];    	
    	if($this->input->post('search')!=null)
    		$data['search'] = $this->input->post('search');
    	$data['page_title'] = 'Warehouses';
    	$data['page_name'] = 'Warehouses';
    	$data['searchAction'] = base_url() . 'index.php/warehouse/search';
    	$data['searchDisplayTxt'] = 'searchDisplayTxt';
    	$searchSQL = "SELECT c.componentId, c.name, c.address, c.contact FROM warehouse c 
			
			WHERE c.name LIKE '%".$data['search']."%' and c.componentId != 0";    	    	
    
    	$pageSQL = " LIMIT ".($data['pageNo']-1)*$data['limit'].",  ".$data['limit'];
    	$query = $this->db->query($searchSQL);
    	$data['total'] = $query->num_rows();
    	//echo $searchSQL.$pageSQL;
    //	return;
    	$query1 = $this->db->query($searchSQL.$pageSQL);
    	$data['searchData'] = $query1->result();
    	$data['propertyArr'] = ['name'=>'Name','address'=>'Address','contact'=>'Contact'];
    	$data['addmodifyAction'] = 'index.php/warehouse/add';
    	 // Capitalize the first letter
    	
		$this->load->view('warehouse/search/index.php', $data);
    }
    
    public function transfer()
    {
    	$data = $this->commonTasks();
    	$data = $this->commonSearch($data);
    	
    	$data['parentId'] = '';
    	if($this->input->post('parentId')!=null)
    		$data['parentId'] = $this->input->post('parentId');
    		$options = [0=>'Select one'];
    		
    		$arr = $this->warehousemodel->getAll();
    		
    		foreach ($arr as $comp):
    		$options[$comp->componentId]=$comp->name;
    		endforeach;
    		
    		//$data['inputs']['category'] = ['type'=>'dropdown','label'=>'Parent Warehouse', 'fielddata'=>['name' => 'parentId', 'options'=>$options, 'value' => $data['parentId'],]];
    		if($this->input->post('search')!=null)
    			$data['search'] = $this->input->post('search');

    			$data['page_title'] = 'Warehouse Transfers';
    			$data['page_name'] = 'Warehouse Transfers';
    			$data['searchAction'] = base_url() . 'index.php/warehouse/transfer';
    			$data['searchDisplayTxt'] = 'searchDisplayTxt';
    			$searchSQL = "SELECT i.componentId, i.description, i.`type`, 
								CASE WHEN i.`type` = 'SEND' THEN w.name ELSE w1.name END warehouseFrom,  
								CASE WHEN i.`type` = 'SEND' THEN w1.name ELSE w.name END warehouseTo
							FROM inventory i
							INNER JOIN warehouse w ON (i.warehouseId = w.componentId)
							INNER JOIN warehouse w1 ON (i.refrencewarehouseId = w1.componentId)
							WHERE  (i.`type` = 'SEND' OR  i.`type` = 'RECEIVE') AND  i.description LIKE '%".$data['search']."%' and i.componentId != 0 
							ORDER BY i.componentId DESC";

    			$pageSQL = " LIMIT ".($data['pageNo']-1)*$data['limit'].",  ".$data['limit'];
    			$query = $this->db->query($searchSQL);
    			$data['total'] = $query->num_rows();
    			$query1 = $this->db->query($searchSQL.$pageSQL);


    			$data['searchData'] = $query1->result();
    			$data['propertyArr'] = ['description'=>'Description','type'=>'Type','warehouseFrom'=>'Source', 'warehouseTo'=>'Destination'];
    			$data['addmodifyAction'] = 'index.php/warehouse/add';
    			// Capitalize the first letter
    			
    			$this->load->view('warehouse/transfer/index.php', $data);
    }
    
    public function add($id = 0)
    {
    	
    	$data = $this->commonTasks();
    	if($id > 0)
    		$data['page_title'] = 'Modify Warehouse';
    	else
    		$data['page_title'] = 'Add Warehouse';
    	$data['page_name'] = 'home';
    	
    	$version = 0;
    	$uniqueCode = '';
		$name = '';
		$address = '';
		$contact = '';
		
    	$status = '';

    	if($id>0){
    		$query = $this->db->query("SELECT componentId, uniqueCode, name, address, contact, version, status FROM warehouse WHERE componentId = $id ");
		
			foreach ($query->result() as $row)
			{
				$id = $row->componentId;
				$version = $row->version;
				$uniqueCode = $row->uniqueCode;
				$name = $row->name;
				
				$address = $row->address;
				$contact = $row->contact;


				$status = $row->status;
	    	}
    	}
    	
    	$options = [0=>'Select one'];
    	$arr = $this->warehousemodel->getAll();
    	foreach ($arr as $comp):
    		$options[$comp->componentId]=$comp->name;
    	endforeach;
    	
    	$data['inputs'] = [
    			'0' =>['type'=>'hidden','fielddata'=>['class'=>'form-control', 'name' => 'id', 'id' => 'id', 'value' => $id,]],
    			'1' =>['type'=>'hidden','fielddata'=>['class'=>'form-control', 'name' => 'version', 'id' => 'version', 'value' => $version,]],
    			'2' =>['type'=>'hidden','fielddata'=>['class'=>'form-control', 'name' => 'status', 'status' => 'status', 'value' => $status,]],
    			'3' =>['type'=>'hidden', 'label'=>'Code','fielddata'=>['class'=>'form-control', 'name' => 'uniqueCode', 'id' => 'uniqueCode', 'value' => $uniqueCode,]],
    			'4' =>['type'=>'textfield', 'label'=>'Name', 'fielddata'=>['class'=>'form-control', 'name' => 'name', 'id' => 'name', 'value' => $name,]],
    			'5' =>['type'=>'textfield', 'label'=>'Address', 'fielddata'=>['class'=>'form-control', 'name' => 'address', 'id' => 'address', 'value' => $address,]],
    			'6' =>['type'=>'textfield', 'label'=>'Contact', 'fielddata'=>['class'=>'form-control', 'name' => 'contact', 'id' => 'contact', 'value' => $contact,]],
    			
    	];
    	
    	$this->load->view('warehouse/add/index', $data);
    }
    
    public function send($id = 0)
    {
    	
    	$data = $this->commonTasks();
    	$data['page_title'] = 'Warehouse &gt; Send';
    	$data['page_name'] = 'Warehouse send';
    	
    	$data['tdate'] = date("m/d/Y");
    	$data['operation'] = 'Save';
    	$data['type'] = "SEND";
    	$data['description'] = "";
    	
    	$data['items'] = $this->itemmodel->getAll();
    	$userId = $this->session->userdata('userid');
    	
    
    	$data["sourceOptions"]=$this->userwarehousemodel->getUserrWarehouses($userId);
    	$data["destinationOptions"]=$this->warehousemodel->getAll();
    	
    	if($this->input->post('operation')){
    		
    		$inv=array(
    				"main" => [
    						"tdate"=>date('Y-m-d',strtotime($this->input->post('tdate'))),
    						"warehouseId" =>$this->input->post("source"),
    						"refrencewarehouseId" =>$this->input->post("destination"),
    						"type" =>$this->input->post("type"),
    						"uniqueCode"=>$this->input->post("type") + date('ymdhis'),
    						"description"=>$this->input->post("description")
    				],
    				"details" => []
    		);

    		$size = $this->input->post('size');
    		for($i=0; $i<$size; $i++){
    			array_push($inv['details'], ["itemId"=>$this->input->post('itemId'.$i), "quantity"=>$this->input->post('quantity'.$i),"type"=>-1, "condition"=>$this->input->post('condition'.$i), "warehouseId"=>$this->input->post('source')]);
    		}
    		
    		$this->inventorymodel->save($inv);
    		
    		redirect(base_url() . 'index.php/warehouse/search', 'refresh');
    		
    	}else{
    			
    		$this->load->view('warehouse/send/index', $data);
    	}
    }
    
    public function transfer_(){
    	$inv=array(
    			"main" => [
    					"tdate"=>$this->input->post('tdate'),
    					"warehouseId" =>$this->input->post("warehouseId"),
    					"type" =>$this->input->post("type"),
    					"uniqueCode"=>$this->input->post("name"),
    					"description"=>$this->input->post("description"),
    					"transactionId"=>$transactionId
    			],
    			"details" => []
    	);
    	
    	foreach($details as $row){
    		if($row['accountId'] == Applicationconst::ACCOUNT_HEAD_SALE)
    			array_push($inv['details'], ["itemId"=>$row['itemId'], "quantity"=>$row['quantity'], "quantity"=>$row['quantity'],"type"=>-1, "condition"=>"Good", "warehouseId"=>$this->input->post('warehouseId')]);
    	}
    	
    	$this->inventorymodel->save($inv);
    }
    
    public function save($id = 0)
    {
    
    	$data = $this->commonTasks();
    	 
    	$data['page_title'] = 'Add warehouse';
    	$data['page_name'] = 'home';
    	 
    	$data['id'] = $this->input->post('id');
    	$dataToSave['version'] = $this->input->post('version');
    	$dataToSave['uniqueCode'] = $this->input->post('uniqueCode');
		$dataToSave['name'] = $this->input->post('name');
		$dataToSave['address'] = $this->input->post('address');
		$dataToSave['contact'] = $this->input->post('contact');

	
    	
    	$data['fail_message'] = array();
    
    	if( $this->input->post('name') == null){
    		array_push($data['fail_message'], 'warehousename can not be null');
    	}
    	
    	if(count($data['fail_message'])){
    		$data['version'] = $this->input->post('version');
    		$data['uniqueCode'] = $this->input->post('uniqueCode');
			$data['name'] = $this->input->post('name');
			$data['address'] = $this->input->post('address');
			$data['contact'] = $this->input->post('contact');

    		$this->load->view('warehouse/add/index', $data);
    		return;
    	}
    	 
    	
    	if($data['id']>0){
    		$this->db->where('componentId', $data['id']);
    		$this->db->update('warehouse', $dataToSave);
    	}else{
    		$this->db->insert('warehouse',$dataToSave);
    	}
    	 redirect(base_url() . 'index.php/warehouse/search', 'refresh');
    }
    
    public function delete()
    {
    	$data = $this->commonTasks();
    	
    	$data['page_title'] = 'Add warehouse';
    	$data['page_name'] = 'home';
    	$data['id'] = $this->input->post('id');
    	
    	$this->db->where('componentId', $data['id']);
		if($this->db->delete('warehouse')){
			redirect(base_url() . 'index.php/warehouse/search', 'refresh');
        }else{
        	$this->load->view('warehouse/add/index', $data);
        }
 
    	
    }
    
    public function assignment()
    {
    	$data = $this->commonTasks();
    	$data['page_title'] = 'User-Warehouse Assignment';
    	$data['page_name'] = 'home';
    	$userId  = -1;
    	
    	if($this->input->post('userId') !=null){
    		$userId = $this->input->post('userId');
    	}
    	
    	if($this->input->post('assign') != null){
    		$companies= $this->input->post('companies');
    		$userId = $this->input->post('userId');
    		
    		$this->db->where('userId', $userId);
    		$this->db->delete('userwarehouse');
    		
    		foreach ($companies as $warehouseId):
    		$this->db->insert('userwarehouse', ['userId'=>$userId, 'warehouseId'=>$warehouseId]);
    		endforeach;
    		
    	}
    	
    	$data['searchAction'] = 'warehouse/assignment';
    	$data['assignAction'] = 'warehouse/assignment';
    	
    	$data['userId'] = $userId;
    	
    	$data['user'] = array('-1' => 'Select user');
    	foreach ($this->load('user') as $row){
    		$data['user'][$row->componentId] = $row->firstName.' '.$row->lastName.' ('.$row->uniqueCode.')';
    	}
    	
    	$query = $this->db->query("SELECT c.componentId, c.name, IFNULL(uc.userId, 0) assigned
    			FROM warehouse c
    			LEFT JOIN userwarehouse uc ON (c.componentId = uc.warehouseId AND uc.userId = $userId)");

    	
    	$data['searchData'] = $query->result();


        // echo "<pre>";
        // print_r($data['searchData']);
        // echo "</pre>";
    	
    	$this->load->view('warehouse/warehouseassign/index', $data);
    }

    public function receive(){
        $data = $this->commonTasks();
        $data = $this->commonSearch($data);
        $data['component'] = 'Receiving';
        $data['addmodifyAction'] = 'index.php/warehouse/receiveModification';
        $data['searchAction'] = base_url() . 'index.php/warehouse/receive';
        $data['searchDisplayTxt'] = 'searchDisplayTxt';

        $data['page_title'] = 'Warehouse Receiving';
        $data['page_name'] = 'Warehouse Receiving';
        //$data['tdate'] = date('d/m/Y');
        $userId = $this->session->userdata('userid');

        $condition = array(
            'i.type'   => 'SEND',
            'i.status' => 0,
            'u.userId' => $userId
        );
        $this->db->select('i.componentId,
                i.uniqueCode,
                i.tdate date,
                i.warehouseId,
                i.description,
                wh.name from,
                i.refrencewarehouseId towarehouse,
                w.name to');
        $this->db->from('inventory i');
        $this->db->join('warehouse wh', 'i.warehouseId = wh.componentId', 'inner');
        $this->db->join('userwarehouse u', 'i.refrencewarehouseId = u.warehouseId', 'inner');
        $this->db->join('warehouse w', 'i.refrencewarehouseId = w.componentId', 'inner');
        $this->db->where($condition);

        if($this->input->post('warehouse') != NULL){
            $this->db->where('i.refrencewarehouseId', $this->input->post('warehouse'));
        }

        if($this->input->post('search')!=null){
            $data['search'] = $this->input->post('search');
            $this->db->like('wh.name', $data['search']);
            $this->db->or_like('w.name', $data['search']);
        }
                
        $this->db->group_by('i.componentId');
        $this->db->order_by('i.tdate', 'desc');

        $this->db->limit($data['limit'], ($data['pageNo']-1)*$data['limit']);

        $query1 = $this->db->get();


        $data['searchData'] = $query1->result();
        $data['total'] = $query1->num_rows();
        $data['propertyArr'] = ['date' => 'Date', 'from' => 'From', 'to' => 'To', 'description' => 'description'];
        $this->load->view('warehouse/warehousereceive/index', $data);

    }

    public function receiveModification($id = 0){
        $data = $this->commonTasks();
        $data['page_title'] = 'Receive';
        $data['page_name'] = 'Receive';
        $data['component'] = 'Receiving';


        $this->db->select('id.componentId,
                    id.itemId,
                    i.itemName,
                    id.quantity,
                    id.inventoryId,
                    id.`condition`');
        $this->db->from('inventorydetail id');
        $this->db->join('item i', 'i.componentId = id.itemId', 'inner');
        $this->db->where('id.inventoryId', $id);
        $this->db->where('id.type', -1);
        $this->db->group_by('id.componentId');
        $this->db->order_by('i.itemName');
        $data['detailList'] = $this->db->get()->result_array();

        $this->load->view('warehouse/receiveModification/index', $data);

    }
    public function receiveConfirmation($detailId = NULL, $condition = NULL, $quantity = NULL){
        if($detailId != null)
            $this->db->where('componentId', $detailId);

        $this->db->where('type', -1);
        $query = $this->db->get('inventorydetail')->row();

        $data['itemId']      = $query->itemId;
        $data['inventoryId'] = $query->inventoryId;

        $data['condition']   = $condition;
        $data['type']        = 1;

        if($quantity == null)
            $quantity = $query->quantity;
        $data['quantity']    = $quantity;

        $this->db->where('componentId', $data['inventoryId']);
        $data['warehouseId'] = $this->db->get('inventory')->row()->refrencewarehouseId;

        $this->db->insert('inventorydetail', $data);

        $sentCondition = array(
            'type' => '-1'
        );
        $receivedCondition = array(
            'type' => '1'
        );

        $totalSent = $this->db->select('sum(quantity) total')->from('inventorydetail')->where('inventoryId', $data['inventoryId'])->where($sentCondition)->get()->row()->total;
        //echo $qforTotal->where($sentCondition)->get_compiled_select();

        $totalReceived = $this->db->select('sum(quantity) total')->from('inventorydetail')->where('inventoryId', $data['inventoryId'])->where($receivedCondition)->get()->row()->total;
        
        if($totalSent == $totalReceived){

            $invRcvData['uniqueCode']  = date("ymdhis");
            $invRcvData['tdate']       = date("Y-m-d");
            $invRcvData['type']        = 'RECEIVE';
            $invRcvData['status']      = 1;
            $invRcvData['warehouseId'] = $data['warehouseId'];

            $this->db->where('componentId', $data['inventoryId']);
            $invRcvData['refrencewarehouseId'] = $this->db->get('inventory')->row()->warehouseId;
            $this->db->insert('inventory', $invRcvData);


            $invUpData['status'] = 1;
            $this->db->where('componentId', $data['inventoryId']);
            $this->db->update('inventory', $invUpData);
        }

    }

    public function batchReceive(){
        $detailId    = $this->input->post('detailId');
        $itemId      = $this->db->get_where('inventorydetail', array('componentId' => $detailId))->row()->itemId;
        $inventoryId = $this->input->post('inventoryId');
        $warehouseId = $this->db->get_where('inventory', array('componentId' => $inventoryId))->row()->refrencewarehouseId;

        $quantity    = $this->input->post('quantity');
        $quality     = $this->input->post('quality');

        $sentQuantity = $quantity[0];
        $receivedQuantity = '';

        for ($i=1; $i < sizeof($quantity); $i++) {
            $receivedQuantity += $quantity[$i];
        }

        if($sentQuantity != $receivedQuantity){
            $this->session->set_flashdata('msg', 'Quantity Error!');

            redirect('warehouse/receiveModification/'.$inventoryId);


        }


        for ($i=1; $i < sizeof($quantity); $i++) { 
            $rcvData['itemId']   = $itemId;
            $rcvData['quantity'] = $quantity[$i];
            $rcvData['type'] = 1;
            $rcvData['condition'] = $quality[$i];
            $rcvData['inventoryId'] = $inventoryId;
            $rcvData['warehouseId'] = $warehouseId;

            $this->db->insert('inventorydetail', $rcvData);
        }
        redirect('warehouse/receiveModification/'.$inventoryId,'refresh');
    }
    
}
