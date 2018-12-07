<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
	
	/*
 * @author : Sharif Uddin
 * date : April 01, 2016
 */
class Purchase extends MY_Controller {
	function __construct() {
		parent::__construct ();
		$this->load->model("userwarehousemodel");
		$this->load->model("inventorymodel");
	}
	
	/**
	 * *default functin, redirects to login page if no admin logged in yet**
	 */
	public function index() {
		// commonTasks();
		redirect ( base_url () . 'index.php/purchase/search', 'refresh' );
	}
	public function commonTasks() {
		$data = parent::commonTasks ();
		
		
		$data ['component'] = 'purchase';
		return $data;
	}
	public function search() {
		$data = $this->commonTasks ();
		$data = $this->commonSearch($data);
		
		$data['stdate'] = date('Y-m-d',strtotime("-3 days"));
		if($this->input->post('stdate')!=null)
			$data['stdate'] = $this->input->post('stdate');
		
		$data['etdate'] = date('Y-m-d',strtotime("0 days"));
		if($this->input->post('etdate')!=null)
			$data['etdate'] = $this->input->post('etdate');

		$data['description'] = '';

		if($this->input->post('description')!=null)
			$data['description'] = $this->input->post('description');


		
		$data['inputs']['search'] = ['type'=>'hidden','fielddata'=>['name' => 'search', 'id' => 'search', 'value' => $data['search']]];

		$data['inputs']['description'] = ['type'=>'textfield','label'=>'Description','fielddata'=>['name' => 'description', 'id' => 'description', 'value' => $data['description'] ]];

		$data['inputs']['stdate'] = ['type'=>'textfield','label'=>'Start  date','fielddata'=>['name' => 'stdate', 'id' => 'stdate', 'value' => $data['stdate'] ]];
		$data['inputs']['etdate'] = ['type'=>'textfield','label'=>'End  date','fielddata'=>['name' => 'etdate', 'id' => 'etdate', 'value' => $data['etdate'] ]];
		
		$data ['page_title'] = 'Search Purchase';
		$data ['page_name'] = 'home';
		$data ['searchAction'] = 'purchase/search';
		$data ['searchDisplayTxt'] = 'searchDisplayTxt';
		$searchSQL = "SELECT componentId, description, uniqueCode, DATE_FORMAT(tdate, '%d-%M-%Y') tdate, type,  amount  
				FROM vTransactions 
				WHERE type = '".Applicationconst::TRANSACTION_TYPE_PURCHASE."' AND description LIKE '%".$data['description']."%' AND (tdate BETWEEN '".$data['stdate']."' AND '".$data['etdate']."' )  ORDER BY tdate DESC, componentId DESC";
				
		$pageSQL = " LIMIT ".($data['pageNo']-1)*$data['limit'].",  ".$data['limit'];
		
		$query = $this->db->query ( $searchSQL );
		$data ['total'] = $query->num_rows ();
		// echo $searchSQL.$pageSQL;
		// return;
		$query1 = $this->db->query ( $searchSQL . $pageSQL );

		$data ['searchData'] = $query1->result ();
		
		$data ['propertyArr'] = [ 
				'tdate' => 'Date',
				'uniqueCode' => 'Reference',
				'description' => 'Description',
				'amount' => 'Amount'
		];
		$data ['addmodifyAction'] = 'index.php/transaction/add';
		// Capitalize the first letter
		
		$this->load->view ( 'purchase/search/index.php', $data );
	}
	
	public function add($id = 0) {
		$data = $this->commonTasks ();
		if ($id > 0)
			$data ['page_title'] = 'Modify Pruchase';
		else
			$data ['page_title'] = 'Add Purchase';
		$data ['page_name'] = 'home';
	
		$data['hotItem'] = $this->load ( 'vFrequentItem', "LIMIT 0, 7");
	
	
		$data['jsonitem'] = json_encode($this->load ( 'item', "WHERE category1 = '".Applicationconst::ITEM_TYPE_INVENTORY."'" ));
	
		$data ['account'] = array ();
		foreach ( $this->load ( 'account', "WHERE category3 = '".Applicationconst::ACCOUNT_CAT3_BANK."' OR category3 = '".Applicationconst::ACCOUNT_CAT3_CASH."' OR componentId = '".Applicationconst::ACCOUNT_HEAD_PAYABLE."' OR componentId = '".Applicationconst::ACCOUNT_HEAD_CASH_ADVANCE."'"  ) as $row ) {
			$data ['account'] [$row->componentId] = $row->uniqueCode;
		}
		$data ['customer'] = array ();
		foreach ( $this->load ( 'customer', 'WHERE isSupplier = 1') as $row ) {
			$data ['customer'] [$row->componentId] = $row->name;
		}
		$version = 0;
		$name = '';
		$description = '';
		$status = '';
		$tdate = date ( 'Y-m-d' );
		$name = Applicationconst::TRANSACTION_TYPE_PURCHASE . '-' . $this->getSequence ( Applicationconst::TRANSACTION_TYPE_PURCHASE );
		$details = [
				1 => array (),
				- 1 => array ()
		];
		if ($id > 0) {
			$query = $this->db->query ( "SELECT componentId, uniqueCode, description, version, status,tdate FROM transaction WHERE componentId = $id " );
			foreach ( $query->result () as $row ) {
				$id = $row->componentId;
				$version = $row->version;
				$name = $row->uniqueCode;
				$description = $row->description;
				$tdate = $row->tdate;
				$status = $row->status;
			}
			$query = $this->db->query ( "SELECT componentId, userId, itemId, accountId, type, quantity, unitPrice FROM transaction_detail WHERE transactionId = $id ORDER BY type DESC" );
			foreach ( $query->result () as $row ) {
				$detailId = $row->componentId;
				$userId = $row->userId;
				$itemId = $row->itemId;
				$accountId = $row->accountId;
				$type = $row->type;
				$quantity = $row->quantity;
				$unitPrice = $row->unitPrice;
				array_push ( $details [$type], [
						'detailId' => $detailId,
						'userId' => $userId,
						'itemId' => $itemId,
						'accountId' => $accountId,
						'type' => $type,
						'quantity' => $quantity,
						'unitPrice' => $unitPrice
				] );
			}
		}
	
		$data ['userId'] = 1;
		$data ['inputs'] = [
				'0' => [
						'type' => 'hidden',
						'fielddata' => [
								'name' => 'id',
								'id' => 'id',
								'value' => $id
						]
				],
				'1' => [
						'type' => 'hidden',
						'fielddata' => [
								'name' => 'version',
								'id' => 'version',
								'value' => $version
						]
				],
				'2' => [
						'type' => 'hidden',
						'fielddata' => [
								'name' => 'status',
								'id' => 'status',
								'value' => $status
						]
				],
				'3' => [
						'type' => 'hidden',
						'fielddata' => [
								'name' => 'type',
								'id' => 'type',
								'value' => Applicationconst::TRANSACTION_TYPE_PURCHASE,
						]
				],
				'name' => [
						'type' => 'textfield',
						'fielddata' => [
								'name' => 'name',
								'id' => 'name',
								'value' => $name,
								'readonly' => 'true'
						]
				],
				'description' => [
						'type' => 'textfield',
						'fielddata' => [
								'name' => 'description',
								'id' => 'description',
								'value' => $description,
								'cols' => 10,
								'rows' => 2
						]
				],
				'userId' => [
						'type' => 'dropdown',
						'fielddata' => [
								'name' => 'userId',
								'id' => 'userId',
								'value' => $data ['userId']
						]
				],
				'tdate' => [
						'type' => 'textfield',
						'fielddata' => [
								'name' => 'tdate',
								'id' => 'tdate',
								'value' => $tdate
						]
				],
				'drs' => $details [1],
				'crs' => $details [- 1]
		];
		
		
		$userId = $this->session->userdata('userid');
		
		$data["warehouses"]=$this->userwarehousemodel->getUserrWarehouses($userId);
		
		
		$this->load->view ( 'purchase/add/index', $data );
	}

	public function save($id = 0) {
		$data = $this->commonTasks();
		$data['page_title'] = 'Add transaction';
		$data['page_name'] = 'home';
		$data['id'] = $this->input->post('id');
		$dataToSave['version'] = $this->input->post('version');
		$dataToSave['uniqueCode'] = $this->input->post('name');
		$dataToSave['description'] = $this->input->post('description');
		$dataToSave['tdate'] = $this->input->post('tdate');
		$dataToSave['type'] = $this->input->post('type');
	
		$productCount =  $this->input->post('productCounter');
		$totaldr =  $this->input->post('totaldr');
	
		$details = array();
		$drAccount = Applicationconst::ACCOUNT_HEAD_PURCHASE;
		$supplierId =  $this->input->post('userId');
		$cashItem = Applicationconst::ITEM_CASH;
		$userCompany = Applicationconst::USER_COMPANY;
		
		$percentcommission = $this->input->post('percentcommission');
		$drTotal = 0.0;		$crTotal = 0.0;
		for($i=0;$i<$productCount;$i++){
			$detailData = array();
			$detailData['accountId'] = $drAccount;
			$detailData['itemId'] = $this->input->post('itemId'.$i);
			$detailData['userId'] = $supplierId;
			$detailData['quantity'] = $this->input->post('quantity'.$i);
			$detailData['unitPrice'] = $this->input->post('unitPrice'.$i);
			$detailData['type'] = 1;			$drTotal += $detailData['quantity'] * $detailData['unitPrice'];
	
			if($data['id'] > 0){
				$detailData['transactionId'] = $data['id'];
				$detailData['componentId'] = $this->input->post('detailId'.$i);
			}
	
			array_push($details, $detailData);
			
			if($percentcommission > 0){
				$detailData = array();
				 
				$detailData['accountId'] = Applicationconst::ACCOUNT_HEAD_SUSPENSION;
				$detailData['itemId'] = $this->input->post('itemId'.$i);
				$detailData['userId'] = $supplierId;
				$detailData['quantity'] = $this->input->post('quantity'.$i);
				$detailData['unitPrice'] = round ($this->input->post('unitPrice'.$i)*$percentcommission/100, 2);
				$detailData['type'] = -1;				$crTotal += $detailData['quantity'] * $detailData['unitPrice'];
				 
				if($data['id'] > 0){
					$detailData['transactionId'] = $data['id'];
					$detailData['componentId'] = $this->input->post('detailId'.$i);
				}
				 
				array_push($details, $detailData);
			}
	
		}
	
		for($i=1;$i<=$totaldr;$i++){
	
			$detailData = array();
	
			$detailData['accountId'] = $this->input->post('accountIdc'.$i);
			$detailData['itemId'] = $cashItem;
			if($detailData['accountId'] == Applicationconst::ACCOUNT_HEAD_PAYABLE || $detailData['accountId'] == Applicationconst::ACCOUNT_HEAD_CASH_ADVANCE ){
    			$detailData['userId'] = $supplierId;
    		}else{
    			$detailData['userId'] = $userCompany;
    		}
			$detailData['quantity'] = $this->input->post('quantityc'.$i);
			$detailData['unitPrice'] = 1;
			$detailData['type'] = -1;						$crTotal += $detailData['quantity'] * $detailData['unitPrice'];
			if($data['id'] > 0){
				$detailData['transactionId'] = $data['id'];
				$detailData['componentId'] = $this->input->post('detailIdc'.$i);;
			}
			if($detailData['quantity']>0.1){
				array_push($details, $detailData);
			}
	
		}
	
		$data['fail_message'] = array();
	
		if( $this->input->post('name') == null){
	
			array_push($data['fail_message'], 'transactionname can not be null');
	
		}
	
		if(count($data['fail_message'])){
	
			$data['version'] = $this->input->post('version');
	
			$data['name'] = $this->input->post('name');
	
			$data['description'] = $this->input->post('description');
	
			$this->load->view('purchase/add/index', $data);
	
			return;
	
		}
	
	
		$adj = $drTotal - $crTotal;				//echo $adj." a <br/>";		
		if($data['id']>0){
			foreach($details as $row){
				if($row['accountId'] == Applicationconst::ACCOUNT_HEAD_SUSPENSION && $adj != 0.00){					
					$row['unitPrice'] = $row['unitPrice'] + $adj / $row['quantity'];					
					//echo ($adj / $row['quantity'])." ad <br/>";					
					$adj = 0 ;				
				
				}
				$this->db->where('componentId', $row['componentId']);				
	
				$this->db->update('transaction_detail', $row);
	
			}
	
			$this->db->where('componentId', $data['id']);
	
			$this->db->update('transaction', $dataToSave);
	
		}else{
			
			$this->db->insert('transaction',$dataToSave);
	
			$sql = "SELECT componentId FROM transaction WHERE uniqueCode ='".$dataToSave['uniqueCode']."' ";
	
			$transactionId = -1;
	
			$query = $this->db->query($sql);
	
			foreach ($query->result() as $row){
				
				$transactionId = $row->componentId;
	
			}
	
			//print_r($details)	;
	
			foreach($details as $row){
					if($row['accountId'] == Applicationconst::ACCOUNT_HEAD_SUSPENSION && $adj != 0.00)
					{										
						$row['unitPrice'] = $row['unitPrice'] + $adj / $row['quantity'];					
						//echo ($adj / $row['quantity'])." ad <br/>";					
						$adj = 0 ;				
					}				
					
				$row['transactionId'] = $transactionId;
	
				$this->db->insert('transaction_detail', $row);
	
			}
	
		}
		
		
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
			if($row['accountId'] == Applicationconst::ACCOUNT_HEAD_PURCHASE)
				array_push($inv['details'], ["itemId"=>$row['itemId'], "quantity"=>$row['quantity'], "quantity"=>$row['quantity'],"type"=>1, "condition"=>"Good", "warehouseId"=>$this->input->post('warehouseId')]);
		}
		
		$this->inventorymodel->save($inv);
		
		 redirect ( base_url () . 'index.php/purchase/search', 'refresh' );
	}
	public function delete() {
		$data = $this->commonTasks ();
		
		$data ['page_title'] = 'Delete Cuustomer';
		$data ['page_name'] = 'home';
		$data ['id'] = $this->input->post ( 'id' );
		$data ['version'] = $this->input->post ( 'version' );
		$data ['name'] = $this->input->post ( 'name' );
		$data ['email'] = $this->input->post ( 'email' );
		$data ['password'] = $this->input->post ( 'password' );
		$this->db->where ( 'componentId', $data ['id'] );
		if ($this->db->delete ( 'purchase' )) {
			redirect ( base_url () . 'index.php/purchase/search', 'refresh' );
		} else {
			$this->load->view ( 'purchase/add/index', $data );
		}
	}
	
	public function angpurchase(){
		$data = $this->commonTasks ();
		$this->load->view ( 'purchase/purchase', $data );
	}
}
