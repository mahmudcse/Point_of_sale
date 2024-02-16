
<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
	
	/*
 * @author : Sharif Uddin
 * date : April 01, 2016
 */
class Sale extends MY_Controller {
	function __construct() {
		parent::__construct ();
		$this->load->model("Userwarehousemodel");
		$this->load->model("Inventorymodel");
		$this->load->model("Itemmodel");
	}
	
	/**
	 * *default functin, redirects to login page if no admin logged in yet**
	 */
	public function index() {
		// commonTasks();
		redirect ( base_url () . 'index.php/sale/search', 'refresh' );
	}
	public function commonTasks() {
		$data = parent::commonTasks ();
		$data ['component'] = 'sale';
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

		$data['inputs']['description'] = ['type'=>'textfield','label'=>'Description','fielddata'=>['name' => 'description', 'id' => 'description', 'value' => $data['description'] ]];
		
		$data['inputs']['search'] = ['type'=>'hidden','fielddata'=>['name' => 'search', 'id' => 'search', 'value' => $data['search']]];
		$data['inputs']['stdate'] = ['type'=>'textfield','label'=>'Start  date','fielddata'=>['name' => 'stdate', 'id' => 'stdate', 'value' => $data['stdate'] ]];
		$data['inputs']['etdate'] = ['type'=>'textfield','label'=>'End  date','fielddata'=>['name' => 'etdate', 'id' => 'etdate', 'value' => $data['etdate'] ]];
		
		$data ['page_title'] = 'Sale';
		$data ['page_name'] = 'home';
		$data ['searchAction'] = 'sale/search';
		
		$searchSQL = "SELECT componentId, description, uniqueCode, DATE_FORMAT(tdate, '%d-%M-%Y') AS tdate, type,  amount
				FROM vTransactions
				WHERE type = '".Applicationconst::TRANSACTION_TYPE_SALE."' AND description LIKE '%".$data['description']."%' AND (tdate BETWEEN '".$data['stdate']."' AND '".$data['etdate']."' )  ORDER BY tdate DESC";
		
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
		
		$this->load->view ( 'sale/search/index.php', $data );
	}
	
	public function add($id = 0) {

		$data = $this->commonTasks ();
		if ($id > 0)
			$data ['page_title'] = 'Modify Transaction';
		else
			$data ['page_title'] = 'Add Transaction';
		$data ['page_name'] = 'home';
		
		$data['hotItem'] = $this->load ( 'vFrequentItem', "LIMIT 0, 10");
		$units = array();
		foreach ($this->load('unit') as $row){
			$units[$row->componentId] = $row->uniqueCode;
		}
		$data['units'] = $units;
		$data['jsonitem'] = json_encode($this->load ( 'vItem', "WHERE category1 = '".Applicationconst::ITEM_TYPE_INVENTORY."'" ));
		
		$data['items'] = $this->Itemmodel->getAll();
		
		$data ['account'] = array ();
		foreach ( $this->load ( 'account', "WHERE category3 = '".Applicationconst::ACCOUNT_CAT3_BANK."' OR category3 = '".Applicationconst::ACCOUNT_CAT3_CASH."' OR componentId = '".Applicationconst::ACCOUNT_HEAD_RECEIVABLE."'"  ) as $row ) {
			$data ['account'] [$row->componentId] = $row->uniqueCode;
		}
		$data ['customer'] = array ();
		foreach ( $this->load ( 'customer' ,'WHERE isCustomer = 1') as $row ) {
			$data ['customer'] [$row->componentId] = $row->name;
		}
		$version = 0;
		$name = '';
		$description = '';
		$status = '';
		$tdate = date ( 'Y-m-d' );
		$name = Applicationconst::TRANSACTION_TYPE_SALE . '-' . $this->getSequence ( Applicationconst::TRANSACTION_TYPE_SALE );
		// 	$names = '';
		// $names = Applicationconst::TRANSACTION_TYPE_RECEIVED_WAREHOUSE. '-' . $this->getSequence ( Applicationconst::TRANSACTION_TYPE_RECEIVED_WAREHOUSE);
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
								'value' => Applicationconst::TRANSACTION_TYPE_SALE,
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
				// 'names' => [ 
				// 		'type' => 'textfield',
				// 		'fielddata' => [ 
				// 				'name' => 'name',
				// 				'id' => 'name',
				// 				'value' => $names,
				// 				'readonly' => 'true' 
				// 		] 
				// ],
				'drs' => $details [1],
				'crs' => $details [- 1] 
		];
		
		$userId = $this->session->userdata('userid');
		
		$data["warehouses"]=$this->Userwarehousemodel->getUserrWarehouses($userId);
		
		
		//$data["userWarehouse"]=$this->warehousemodel->getUserWarehouse();
		$this->load->view ( 'sale/add/index', $data );
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
    	$drAccount = Applicationconst::ACCOUNT_HEAD_SALE;
    	$supplierId =  $this->input->post('userId');
    	$cashItem = Applicationconst::ITEM_CASH;
    	$userCompany = Applicationconst::USER_COMPANY;

    	for($i=0;$i<$productCount;$i++){

    		$detailData = array();

    		$detailData['accountId'] = $drAccount;
    		$detailData['itemId'] = $this->input->post('itemId'.$i);
    		$detailData['userId'] = $supplierId;
    		$detailData['quantity'] = $this->input->post('quantity'.$i);
    		$detailData['unitPrice'] = $this->input->post('unitPrice'.$i);
    		$detailData['description'] = $this->input->post('description'.$i);
    		$detailData['type'] = -1;
	
    		if($data['id'] > 0){
    			$detailData['transactionId'] = $data['id'];
    			$detailData['componentId'] = $this->input->post('detailId'.$i);
    		}

    		array_push($details, $detailData);
    		
    		/*****************Commission********************/
    		$sql = "SELECT IFNULL(getCommission(".$detailData['itemId']."),0.0) AS commission";

    		$commission = 0.0;
    		
    		$query = $this->db->query($sql);
    		
    		foreach ($query->result() as $row){
    			$commission = $row->commission;
    		}
    		
    		$detailCommData = array();
    		$detailSuspData = array();
    		
    		$detailCommData['accountId'] = Applicationconst::ACCOUNT_HEAD_PURCHASE_COMMISSION;
    		$detailCommData['itemId'] = $detailData['itemId'];
    		$detailCommData['userId'] = Applicationconst::USER_COMPANY;
    		$detailCommData['quantity'] = $detailData['quantity'];
    		$detailCommData['unitPrice'] = $commission;
    		$detailCommData['type'] = 1;
    		
    		$detailSuspData['accountId'] = Applicationconst::ACCOUNT_HEAD_SUSPENSION;
    		$detailSuspData['itemId'] = $detailData['itemId'];
    		$detailSuspData['userId'] = Applicationconst::USER_COMPANY;
    		$detailSuspData['quantity'] = $detailData['quantity'];
    		$detailSuspData['unitPrice'] = $commission;
    		$detailSuspData['type'] = -1;
    		
    		if($data['id'] > 0){
    			$detailCommData['transactionId'] = $data['id'];
    			$detailCommData['componentId'] = $this->input->post('detailId'.$i);
    			
    			$detailSuspData['transactionId'] = $data['id'];
    			$detailSuspData['componentId'] = $this->input->post('detailId'.$i);
    		}
    		
    		array_push($details, $detailCommData);
    		array_push($details, $detailSuspData);
    		
    		/**********************************************/

    	}

    	for($i=1;$i<=$totaldr;$i++){

    		$detailData = array();

    		$detailData['accountId'] = $this->input->post('accountIdc'.$i);
    		$detailData['itemId'] = $cashItem;
    		if($detailData['accountId'] == Applicationconst::ACCOUNT_HEAD_RECEIVABLE){
    			$detailData['userId'] = $supplierId;
    		}else{
    			$detailData['userId'] = $userCompany;
    		}
    		$detailData['quantity'] = $this->input->post('quantityc'.$i);
    		$detailData['unitPrice'] = 1;
    		$detailData['type'] = 1;
    		if($data['id'] > 0){
    			$detailData['transactionId'] = $data['id'];
    			$detailData['componentId'] = $this->input->post('detailIdc'.$i);;
    		}
			if($detailData['quantity']>0.1){
    			array_push($details, $detailData);
			}

    	}
    	
    	if($this->input->post('discount') > 0 ){
    		$detailData = array();
    		
    		$detailData['accountId'] = Applicationconst::ACCOUNT_HEAD_SALE_DISCOUNT;
    		$detailData['itemId'] = $cashItem;
    		$detailData['userId'] = $supplierId;
    		
    		$detailData['quantity'] = $this->input->post('discount');
    		$detailData['unitPrice'] = 1;
    		$detailData['type'] = 1;



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

    		$this->load->view('transaction/add/index', $data);

    		return;

    	}
    	$transactionId = -1;
    	if($data['id']>0){

    		foreach($details as $row){

    			$this->db->where('componentId', $row['componentId']);

    			$this->db->update('transaction_detail', $row);

    		}
    		
    		$transactionId = $data['id'];

    		$this->db->where('componentId', $data['id']);

    		$this->db->update('transaction', $dataToSave);

    	}else{

    		$this->db->insert('transaction',$dataToSave);

    		$transaction_id = $this->db->insert_id();
    		$installmentAmounts = $this->input->post('insamt');
			$installmentDates   = $this->input->post('installmentDate');


			
			

			$installmentNo      = $this->input->post('installmentNo');

			for($i = 0; $i < $installmentNo; $i++){
				$installmentData['deadLine']      = date('Y-m-d' ,strtotime($installmentDates[$i]));
				$installmentData['amount']        = $installmentAmounts[$i];
				$installmentData['uniqueCode']    = $transaction_id.($i+1);
				$installmentData['transactionId'] = $transaction_id;
				$installmentData['installmentNo'] = $i+1;

				$this->db->insert('installment', $installmentData);
			}


    		$sql = "SELECT componentId FROM transaction WHERE uniqueCode ='".$dataToSave['uniqueCode']."' ";

    		$transactionId = -1;

    		$query = $this->db->query($sql);

    		foreach ($query->result() as $row){

    			$transactionId = $row->componentId;

    		}

    		//print_r($details)	;

    		foreach($details as $row){

    			$row['transactionId'] = $transactionId;
    			$this->db->insert('transaction_detail', $row);

    		}
    		//echo "$supplierId";
    		
    		$sql = "SELECT name, address, email FROM customer WHERE componentId = $supplierId ";
    		$query = $this->db->query($sql);
    		$customerName = '';
    		$address = '';
    		$email = '';
    		foreach ($query->result() as $row){
    			$customerName = $row->name;
    			$address = $row->address;
    			$email = $row->email;
    		}
    		
    		$challan['transactionId'] = $transactionId;
    		$challan['billTo'] = $customerName;
    		$challan['shipTo'] = $customerName;
    		$challan['billingAddress'] = $address;
    		$challan['shippingAddress'] = $address;
    		$challan['email'] = $email;
			$challan['deliverydate'] = $this->input->post('tdate');
    		$challan['uniqueCode'] = Applicationconst::TRANSACTION_DOC_CHALLAN . '-' . $this->getSequence ( Applicationconst::TRANSACTION_DOC_CHALLAN );
    		
    		$this->db->insert('challan', $challan);

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
				if($row['accountId'] == Applicationconst::ACCOUNT_HEAD_SALE)
					array_push($inv['details'], ["itemId"=>$row['itemId'], "quantity"=>$row['quantity'], "quantity"=>$row['quantity'],"type"=>-1, "condition"=>"Good", "warehouseId"=>$this->input->post('warehouseId')]);
			}
			
			$this->Inventorymodel->save($inv);
		
		redirect ( base_url () . 'index.php/sale/search', 'refresh' );
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
		if ($this->db->delete ( 'sale' )) {
			redirect ( base_url () . 'index.php/sale/search', 'refresh' );
		} else {
			$this->load->view ( 'sale/add/index', $data );
		}
	}

	public function angsale(){
		$data = $this->commonTasks ();
		$this->load->view ( 'sale/sale', $data );
	}
}
