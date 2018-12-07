<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*	
 *	@author : Sharif Uddin
 *	date	: April 01, 2016
 */

class Transaction extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
		
    }
    
 /***default functin, redirects to login page if no admin logged in yet***/
    public function index()
    {
        //commonTasks();
        redirect(base_url() . 'index.php/transaction/search', 'refresh');
    }
    public function commonTasks(){
    	$data = parent::commonTasks();
    	$data['component'] = 'transaction';
    	return $data;
    }
    public function search()
    {
    	$data = $this->commonTasks();    
    	$data = $this->commonSearch($data);

        $data['description'] = '';

        if($this->input->post('description')!=null)
            $data['description'] = $this->input->post('description');
    	
    	$data['stdate'] = date('Y-m-d',strtotime("-3 days"));
    	if($this->input->post('stdate')!=null)
    		$data['stdate'] = $this->input->post('stdate');
    	
    	$data['etdate'] = date('Y-m-d',strtotime("0 days"));
    	if($this->input->post('etdate')!=null)
    		$data['etdate'] = $this->input->post('etdate');
    	
    	$data['xno'] = '';
    	if($this->input->post('xno')!=null)
    		$data['xno'] = $this->input->post('xno');

        $data['inputs']['description'] = ['type'=>'textfield','label'=>'Description','fielddata'=>['name' => 'description', 'id' => 'description', 'value' => $data['description'] ]];
    	
    	$data['inputs']['search'] = ['type'=>'hidden','fielddata'=>['name' => 'search', 'id' => 'search', 'value' => $data['search']]];
    	$data['inputs']['stdate'] = ['type'=>'textfield','label'=>'Start  date','fielddata'=>['name' => 'stdate', 'id' => 'stdate', 'value' => $data['stdate'] ]];
    	$data['inputs']['etdate'] = ['type'=>'textfield','label'=>'End  date','fielddata'=>['name' => 'etdate', 'id' => 'etdate', 'value' => $data['etdate'] ]];
    	$data['inputs']['transNo'] = ['type'=>'textfield','label'=>'Transaction No','fielddata'=>['name' => 'xno', 'id' => 'xno', 'value' => $data['xno'] ]];
    	
    	$data ['page_title'] = 'Transactions';
    	$data ['page_name'] = 'home';
    	$data ['searchAction'] = 'transaction/search';
    	
    	$searchSQL = "SELECT componentId, description, uniqueCode, DATE_FORMAT(tdate, '%d-%M-%Y') tdate , type,  amount
				FROM vTransactions
				WHERE description LIKE '%".$data['description']."%' AND (tdate BETWEEN '".$data['stdate']."' AND '".$data['etdate']."' )  AND uniqueCode LIKE '%".$data['xno']."%' 
				ORDER BY tdate DESC";
    	
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
    	
    	$data['addmodifyAction'] = 'index.php/transaction/add';
    	 // Capitalize the first letter
    	
		$this->load->view('transaction/search/index.php', $data);
    }
    
    
    public function save($id = 0)
    {
    
    	$data = $this->commonTasks();
    
    	$data['page_title'] = 'Add transaction';
    	$data['page_name'] = 'home';
    
    	$data['id'] = $this->input->post('id');
    	$dataToSave['version'] = $this->input->post('version');
    	$dataToSave['uniqueCode'] = $this->input->post('name');
    	$dataToSave['type'] = $this->input->post('type');
    	$dataToSave['description'] = $this->input->post('description');
    	$dataToSave['tdate'] = $this->input->post('tdate');
    	$rowcount =  $this->input->post('rowcount');
    	
    	$details = array();
    	
    	for($i=0;$i<=$rowcount;$i++){
    		$detailData = array();
    		$detailData['accountId'] = $this->input->post('accountId'.$i);
    		$detailData['itemId'] = $this->input->post('itemId'.$i);
    		$detailData['userId'] = $this->input->post('userId'.$i);
    		$detailData['quantity'] = $this->input->post('quantity'.$i);
    		$detailData['unitPrice'] = $this->input->post('unitPrice'.$i);
    		$detailData['type'] = $this->input->post('type'.$i);;
    		if($data['id'] > 0){
    			$detailData['transactionId'] = $data['id'];
    			//$detailData['componentId'] = $this->input->post('detailIdd'.$i);;
    		}
    		array_push($details, $detailData);
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

    	if($data['id']>0){
    		$this->db->where('transactionId', $data['id']);
    		$this->db->delete('transaction_detail');
    		
    		$this->db->where('componentId', $data['id']);
    		$this->db->update('transaction', $dataToSave);
    	}else{
    		$this->db->insert('transaction',$dataToSave);
    	}
    	
    	$sql = "SELECT componentId FROM transaction WHERE uniqueCode ='".$dataToSave['uniqueCode']."' ";
    	$transactionId = -1;
    	$query = $this->db->query($sql);
    	foreach ($query->result() as $row){
    		$transactionId = $row->componentId;
    	}
    	
    	foreach($details as $row){
    		$row['transactionId'] = $transactionId;
    		$this->db->insert('transaction_detail', $row);
    	}

    	redirect(base_url() . 'index.php/transaction/search', 'refresh');
    }
    public function delete()
    {
    	$data = $this->commonTasks();
    	 
    	$data['page_title'] = 'Add transaction';
    	$data['page_name'] = 'home';
    	$data['id'] = $this->input->post('id');
    	
    	$this->db->where('transactionId', $data['id']);
    	$this->db->delete('transaction_detail');
    	
    	$this->db->where('componentId', $data['id']);
    	if($this->db->delete('transaction')){
    		redirect(base_url() . 'index.php/transaction/search', 'refresh');
    	}else{
    		$this->load->view('transaction/add/index', $data);
    	}
    	
    }
    
    public function add($id = 0)
    {
    	 
    	$data = $this->commonTasks();
    	if($id > 0)
    		$data['page_title'] = 'Modify Transaction';
    	else
    		$data['page_title'] = 'Add Transaction';
    	$data['page_name'] = 'home';
    	 
    	$data['item'] = array();
    	foreach ($this->load('item') as $row){
    		$data['item'][$row->componentId] = $row->uniqueCode;
    	}
    	
    	$data['itemjson'] = json_encode($this->load ( 'vItem' ));	
    	$data['userjson'] = json_encode($this->load ( 'customer' ));
    	$data['accountjson'] = json_encode($this->load ( 'account' ));
    	 
    	$data['account'] = array();
    	foreach ($this->load('account') as $row){
    		$data['account'][$row->componentId] = $row->uniqueCode;
    	}
    	 
    	$data['customer'] = array();
    	foreach ($this->load('customer') as $row){
    		$data['customer'][$row->componentId] = $row->name;
    	}
    	 
    	 
    
    	$version = 0;
    	$name = '';
    	$description = '';
    	$status = '';
    	$tdate = date('Y-m-d');
    	$type = Applicationconst::TRANSACTION_TYPE_JOURNAL;
    	$name = Applicationconst::TRANSACTION_TYPE_JOURNAL.'-'.$this->getSequence(Applicationconst::TRANSACTION_TYPE_JOURNAL);
    	$details = [1=>array(),-1=>array()];
    	$detailData = array();
    	if($id>0){
    		$query = $this->db->query("SELECT componentId, uniqueCode, type, description, version, status,tdate FROM transaction WHERE componentId = $id ");
    
    		foreach ($query->result() as $row)
    		{
    			$id = $row->componentId;
    			$version = $row->version;
    			$name = $row->uniqueCode;
    			$description = $row->description;
    			$type = $row->type;
    			$tdate = $row->tdate;
    			$status = $row->status;
    		}
    
    		$query = $this->db->query("SELECT componentId, userId, itemId, accountId, type, quantity, unitPrice FROM transaction_detail WHERE transactionId = $id ORDER BY type DESC");
    		
    		$detailData  = $query->result();
    	}
  
    	
    	if(count($detailData)==0){
    		array_push($detailData, ['detailId'=>'','userId'=>'','itemId'=>Applicationconst::ITEM_CASH,'accountId'=>'','type'=>'1','quantity'=>'0','unitPrice'=>'1',]);
    		array_push($detailData, ['detailId'=>'','userId'=>'','itemId'=>Applicationconst::ITEM_CASH,'accountId'=>'','type'=>'-1','quantity'=>'0','unitPrice'=>'1',]);
    	}
    	
    	$data['detailData'] = json_encode($detailData);
    	
    	$data['inputs'] = [
    			'0' =>['type'=>'hidden','fielddata'=>['name' => 'id', 'id' => 'id', 'value' => $id,]],
    			'1' =>['type'=>'hidden','fielddata'=>['name' => 'version', 'id' => 'version', 'value' => $version,]],
    			'2' =>['type'=>'hidden','fielddata'=>['name' => 'status', 'id' => 'status', 'value' => $status,]],
    			'3' =>['type'=>'hidden','fielddata'=>['name' => 'type', 'id' => 'type', 'value' => $type,]],
    			'name' =>['type'=>'textfield','fielddata'=>['name' => 'name', 'id' => 'name', 'value' => $name,'readonly'=>'true']],
    			'description' =>['type'=>'textfield','fielddata'=>['name' => 'description', 'id' => 'description', 'value' => $description,'cols'=>10,'rows'=>2,]],
    			'tdate' =>['type'=>'textfield','fielddata'=>['name' => 'tdate', 'id' => 'tdate', 'value' => $tdate,]]
    	];
    	 
    	$this->load->view('transaction/add/index', $data);
    }
    
    
    
    public function payment($id = 0)
    {
    
    	$data = $this->commonTasks();
    	if($id > 0)
    		$data['page_title'] = 'Modify Payment';
    	else
    		$data['page_title'] = 'Add Payment';
    	$data['page_name'] = 'home';
    
    	$data['customer'] = array('0'=>'Select payee');
    	foreach ($this->load('customer') as $row){
    		$data['customer'][$row->componentId] = $row->name;
    	}
    	
    	$data ['account'] = array ();
    	foreach ( $this->load ( 'account', "WHERE category3 = '".Applicationconst::ACCOUNT_CAT3_BANK."' OR category3 = '".Applicationconst::ACCOUNT_CAT3_CASH."'" ) as $row ) {
    		$data ['account'] [$row->componentId] = $row->uniqueCode;
    	}
    
    	$version = 0;
    	$name = '';
    	$description = '';
    	$status = '';
    	$tdate = date('Y-m-d');
    	$name = Applicationconst::TRANSACTION_TYPE_PAYMENT.'-'.$this->getSequence(Applicationconst::TRANSACTION_TYPE_PAYMENT);

    	$data['inputs'] = [
    			'0' =>['type'=>'hidden','fielddata'=>['name' => 'id', 'id' => 'id', 'value' => $id,]],
    			'1' =>['type'=>'hidden','fielddata'=>['name' => 'version', 'id' => 'version', 'value' => $version,]],
    			'2' =>['type'=>'hidden','fielddata'=>['name' => 'status', 'id' => 'status', 'value' => $status,]],
    			'3' =>['type'=>'hidden','fielddata'=>['name' => 'type', 'id' => 'status', 'value' => Applicationconst::TRANSACTION_TYPE_PAYMENT,]],
    			'name' =>['type'=>'textfield','fielddata'=>['name' => 'name', 'id' => 'name', 'value' => $name,'readonly'=>'true']],
    			'description' => ['type' => 'textfield', 'fielddata' => [ 'name' => 'description', 'id' => 'description', 'value' => $description,'cols' => 10,'rows' => 2]],
    			'customerId' =>['type'=>'dropdown','fielddata'=>['name' => 'customerId', 'options' => $data['customer'], 'value' => '',]],
    			'tdate' =>['type'=>'textfield','fielddata'=>['name' => 'tdate', 'id' => 'tdate', 'value' => $tdate,]],
    	];
    
    	$this->load->view('transaction/payment/index', $data);
    }
    
    public function savePayment($id = 0)
    {
    
    	$data = $this->commonTasks();
	
		$data['page_title'] = 'Add transaction';
		$data['page_name'] = 'home';
	
		$data['id'] = $this->input->post('id');
		$dataToSave['version'] = $this->input->post('version');
		$dataToSave['uniqueCode'] = $this->input->post('name');
		$dataToSave['description'] = $this->input->post('description');
		$dataToSave['tdate'] = $this->input->post('tdate');
		$dataToSave['type'] = $this->input->post('type');
	
		$totaldr =  $this->input->post('totaldr');
	
		$details = array();
		$drAccount = Applicationconst::ACCOUNT_HEAD_PAYABLE;
		$supplierId =  $this->input->post('customerId');
		$cashItem = Applicationconst::ITEM_CASH;
		$userCompany = Applicationconst::USER_COMPANY;
	
	
		$detailData = array();
	
		$detailData['accountId'] = $drAccount;
		$detailData['itemId'] = $cashItem;
		$detailData['userId'] = $supplierId;
		$detailData['quantity'] = $this->input->post('amount');
		$detailData['unitPrice'] = 1;
		$detailData['type'] = 1;
	
		if($data['id'] > 0){
			$detailData['transactionId'] = $data['id'];
			$detailData['componentId'] = $this->input->post('detailId'.$i);
		}
	
		array_push($details, $detailData);
	
		for($i=1;$i<=$totaldr;$i++){
	
			$detailData = array();
	
			$detailData['accountId'] = $this->input->post('accountIdc'.$i);
			$detailData['itemId'] = $cashItem;
    		$detailData['userId'] = $userCompany;
			$detailData['quantity'] = $this->input->post('quantityc'.$i);
			$detailData['unitPrice'] = 1;
			$detailData['type'] = -1;
			if($data['id'] > 0){
				$detailData['transactionId'] = $data['id'];
				$detailData['componentId'] = $this->input->post('detailIdc'.$i);;
			}
			if($detailData['quantity']>0){
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
	
			$this->load->view('transaction/payment/index', $data);
	
			return;
	
		}
	
		if($data['id']>0){
	
			foreach($details as $row){
	
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
	
				$row['transactionId'] = $transactionId;
	
				$this->db->insert('transaction_detail', $row);
	
			}
	
		}
	
		redirect ( base_url () . 'index.php/transaction/search', 'refresh' );
    }
    
    public function receive($id = 0)
    {
        
    
    	$data = $this->commonTasks();
    	if($id > 0)
    		$data['page_title'] = 'Modify Receive';
    	else
    		$data['page_title'] = 'Add Receive';
    	$data['page_name'] = 'home';
    
    	$data['customer'] = array('0'=>'Select payer');
    	foreach ($this->load('customer') as $row){
    		$data['customer'][$row->componentId] = $row->name;
    	}
    	 
    	$data ['account'] = array ();
    	foreach ( $this->load ( 'account', "WHERE category3 = '".Applicationconst::ACCOUNT_CAT3_BANK."' OR category3 = '".Applicationconst::ACCOUNT_CAT3_CASH."'" ) as $row ) {
    		$data ['account'] [$row->componentId] = $row->uniqueCode;
    	}
    
    	$version = 0;
    	$name = '';
    	$description = '';
    	$status = '';
    	$tdate = date('Y-m-d');
    	$name = Applicationconst::TRANSACTION_TYPE_RECEIVE.'-'.$this->getSequence(Applicationconst::TRANSACTION_TYPE_RECEIVE);
    
    	$data['inputs'] = [
    			'0' =>['type'=>'hidden','fielddata'=>['name' => 'id', 'id' => 'id', 'value' => $id,]],
    			'1' =>['type'=>'hidden','fielddata'=>['name' => 'version', 'id' => 'version', 'value' => $version,]],
    			'2' =>['type'=>'hidden','fielddata'=>['name' => 'status', 'id' => 'status', 'value' => $status,]],
    			'3' =>['type'=>'hidden','fielddata'=>['name' => 'type', 'id' => 'status', 'value' => Applicationconst::TRANSACTION_TYPE_RECEIVE,]],
    			'name' =>['type'=>'textfield','fielddata'=>['name' => 'name', 'id' => 'name', 'value' => $name,'readonly'=>'true']],
    			'description' => ['type' => 'textfield', 'fielddata' => [ 'name' => 'description', 'id' => 'description', 'value' => $description,'cols' => 10,'rows' => 2]],
    			'customerId' =>['type'=>'dropdown','fielddata'=>['name' => 'customerId', 'options' => $data['customer'], 'value' => '',]],
    			'tdate' =>['type'=>'textfield','fielddata'=>['name' => 'tdate', 'id' => 'tdate', 'value' => $tdate,]],
    	];
    
    	$this->load->view('transaction/receive/index', $data);
    }
    
    public function saveReceive($id = 0)
    {
    
    	$data = $this->commonTasks();
    
    	$data['page_title'] = 'Add Receive';
    	$data['page_name'] = 'home';
    
    	$data['id'] = $this->input->post('id');
    	$dataToSave['version'] = $this->input->post('version');
    	$dataToSave['uniqueCode'] = $this->input->post('name');
    	$dataToSave['description'] = $this->input->post('description');
    	$dataToSave['tdate'] = $this->input->post('tdate');
    	$dataToSave['type'] = $this->input->post('type');
    
    	$totaldr =  $this->input->post('totaldr');
    
    	$details = array();
    	$drAccount = Applicationconst::ACCOUNT_HEAD_RECEIVABLE;
    	$supplierId =  $this->input->post('customerId');
    	$cashItem = Applicationconst::ITEM_CASH;
    	$userCompany = Applicationconst::USER_COMPANY;
    
    	$detailData = array();
    
    	$detailData['accountId'] = $drAccount;
    	$detailData['itemId'] = $cashItem;
    	$detailData['userId'] = $supplierId;
    	$detailData['quantity'] = $this->input->post('amount');
    	$detailData['unitPrice'] = 1;
    	$detailData['type'] = -1;
    
    	if($data['id'] > 0){
    		$detailData['transactionId'] = $data['id'];
    		$detailData['componentId'] = $this->input->post('detailId'.$i);
    	}
    
    	array_push($details, $detailData);
    
    	for($i=1;$i<=$totaldr;$i++){
    
    		$detailData = array();
    
    		$detailData['accountId'] = $this->input->post('accountIdc'.$i);
    		$detailData['itemId'] = $cashItem;
    		$detailData['userId'] = $userCompany;
    		$detailData['quantity'] = $this->input->post('quantityc'.$i);
    		$detailData['unitPrice'] = 1;
    		$detailData['type'] = 1;
    		if($data['id'] > 0){
    			$detailData['transactionId'] = $data['id'];
    			$detailData['componentId'] = $this->input->post('detailIdc'.$i);;
    		}
    		if($detailData['quantity']>0){
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
    
    		$this->load->view('transaction/receive/index', $data);
    
    		return;
    
    	}
    
    	if($data['id']>0){
    
    		foreach($details as $row){
    
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
    
    			$row['transactionId'] = $transactionId;
    
    			$this->db->insert('transaction_detail', $row);
    
    		}
    
    	}
    
    	redirect ( base_url () . 'index.php/transaction/search', 'refresh' );
    }
    
    
    
    
}

    
