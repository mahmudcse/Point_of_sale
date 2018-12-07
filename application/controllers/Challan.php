<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*	
 *	@author : Sharif Uddin
 *	date	: April 01, 2016
 */

class Challan extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
		
    }
    
    /***default functin, redirects to login page if no admin logged in yet***/
    public function index()
    {
        //commonTasks();
        redirect(base_url() . 'index.php/challan/search', 'refresh');
    }
    public function commonTasks(){
    	$data = parent::commonTasks();
    	$data['component'] = 'challan';
    	return $data;
    }
    public function search()
    {
    	$data = $this->commonTasks();
    	$data = $this->commonSearch($data);
    	$data['search'] = '';
    	
    	if($this->input->post('search')!=null)
    		$data['search'] = $this->input->post('search');
    	$data['page_title'] = 'Dashboard';
    	$data['page_name'] = 'home';
    	$data['searchAction'] = 'challan/search';
    	$searchSQL = "SELECT c.componentId, c.uniqueCode AS challanRef, billTo, billingAddress, t.uniqueCode, DATE_FORMAT(t.tdate, '%d-%M-%Y') AS tdate
					  FROM challan c
					  INNER JOIN transaction t ON (c.transactionId = t.componentId) WHERE c.uniqueCode LIKE '%".$data['search']."%' ORDER BY c.componentId DESC ";
    	$pageSQL = " LIMIT ".($data['pageNo']-1)*$data['limit'].",  ".$data['limit'];
    	$query = $this->db->query($searchSQL);
    	$data['total'] = $query->num_rows();
    	//echo $searchSQL.$pageSQL;
    	//return;
    	$query1 = $this->db->query($searchSQL.$pageSQL);
    	$data['searchData'] = $query1->result();
    	
    	$data['propertyArr'] = ['tdate'=>'Date', 'challanRef'=>'Challan Ref', 'billTo'=>'Bill To', 'uniqueCode'=>'Transaction Ref'];
    	$data['addmodifyAction'] = 'index.php/challan/add';
    	 // Capitalize the first letter
    	
		$this->load->view('challan/search/index.php', $data);
    }
    
    public function add($id = 0)
    {
    	
    	$data = $this->loadChallanData($id);
    	$this->load->view('challan/add/index', $data);
    }
    
    public function save($id = 0)
    {
    
    	$data = $this->commonTasks();
    	 
    	$data['page_title'] = 'Add challan';
    	$data['page_name'] = 'home';
    	 
    	$data['id'] = $this->input->post('id');
    	$dataToSave['billTo'] = $this->input->post('billTo');
    	$dataToSave['billingAddress'] = $this->input->post('billingAddress');
    	$dataToSave['shipTo'] = $this->input->post('shipTo');
    	$dataToSave['email'] = $this->input->post('email');
    	$dataToSave['shippingAddress'] = $this->input->post('shippingAddress');
		$dataToSave['deliverydate'] = $this->input->post('deliverydate');
    	
    		if($data['id']>0){
    		$this->db->where('componentId', $data['id']);
    		$this->db->update('challan', $dataToSave);
    	}else{
    		$this->db->insert('challan',$dataToSave);
    	}
    
    	 redirect(base_url() . 'index.php/challan/search', 'refresh');
    }
    public function delete()
    {
    	$data = $this->commonTasks();
    	
    	$data['page_title'] = 'Add challan';
    	$data['page_name'] = 'home';
    	$data['id'] = $this->input->post('id');
    	$data['version'] = $this->input->post('version');
    	$data['challanName'] = $this->input->post('challanName');
    	$data['firstName'] = $this->input->post('firstName');
    	$data['lastName'] = $this->input->post('lastName');
    	$data['email'] = $this->input->post('email');
    	$data['password']= $this->input->post('password');
    	$this->db->where('componentId', $data['id']);
		if($this->db->delete('challan')){
			redirect(base_url() . 'index.php/challan/search', 'refresh');
        }else{
        	$this->load->view('challan/add/index', $data);
        }
 
    	
    }
    
    private function loadChallanData($id, $auth = 1){
    	if($auth==1){
    		$data = $this->commonTasks();
    	}
    	if($id > 0)
    		$data['page_title'] = 'Modify Challan';
    	else
    		$data['page_title'] = 'Add Challan';
    	$data['page_name'] = 'home';
    	 
    	$version = 0;
    	$uniqueCode = '';
    	$billTo = '';
    	$billingAddress = '';
    	$shipTo = '';
    	$email = '';
    	$shippingAddress = '';
    	$status = 1;
    	$transactionId = -1;
    	$tdate = '';
		$deliverydate = '';
    	$transactionRef = '';
    	$total = 0;
    	$paid = 0;
    	$due = 0;
    	$discount = 0;
    	
    	if($id>0){
    		$sql = "SELECT c.componentId, c.uniqueCode, c.billTo, c.billingAddress, c.shipTo, c.email,
    		c.shippingAddress, c.transactionId, c.version, c.status,c.deliverydate
    		FROM challan c
    		WHERE c.componentId = $id ";
    	
    		$query = $this->db->query($sql);
    	
    		foreach ($query->result() as $row)
    		{
    			$id = $row->componentId;
    			$uniqueCode = $row->uniqueCode;
    			$billTo = $row->billTo;
    			$billingAddress = $row->billingAddress;
    			$shipTo = $row->shipTo;
    			$email = $row->email;
    			$shippingAddress = $row->shippingAddress;
    			$version = $row->version;
    			$status = $row->status;
				$deliverydate = $row->deliverydate;
    			$transactionId = $row->transactionId;
    		}
    	
    	}
    	 
    	$data ['id'] = $id;
    	 
    	$data ['challanRef'] = $uniqueCode;

    	$data ['invoiceRef'] = str_replace ( Applicationconst::TRANSACTION_DOC_CHALLAN , Applicationconst::TRANSACTION_DOC_INVOICE , $uniqueCode) ;
    	$data ['billTo'] = $billTo;
    	$data ['shipTo'] = $shipTo;
    	$data ['billingAddress'] = $billingAddress;
    	$data ['shippingAddress'] = $shippingAddress;
		$data ['deliverydate'] = $deliverydate;
		$data ['items'] = $this->load ( 'inventoryView', "WHERE transactionId = $transactionId "  );


    	$paymentTotalDue = $this->load ( 'paymentDueView', "WHERE transactionId = $transactionId ");

        $installmentInfo = "SELECT
                            componentId installmentId,
                            DATE_FORMAT(deadLine, '%d %M %Y') deadLine,
                            amount
                            FROM
                            installment WHERE transactionId = $transactionId";
        $data['installmentInfo'] = $this->db->query($installmentInfo)->result_array();
    	
    	foreach($paymentTotalDue as $row):
    	$tdate = $row->tdate;
    	$transactionRef = $row->uniqueCode;
    	$total = $row->total;
    	$paid = $row->paid;
    	$due = $row->due;
    	$discount = $row->discount;
    	endforeach;

    	$data ['tdate'] = $tdate;
    	$data ['transactionRef'] = $transactionRef;
    	$data ['total'] = $total;
    	$data ['paid'] = $paid;
    	$data ['due'] = $due;
    	$data ['discount'] = $discount;
    	$data ['email'] = $email;
    	$data ['deliverydate'] = $deliverydate;
		
    	$data['inputs'] = [
    			'0' =>['type'=>'hidden','fielddata'=>['name' => 'id', 'id' => 'id', 'value' => $id,]],
    			'1' =>['type'=>'hidden','fielddata'=>['name' => 'version', 'id' => 'version', 'value' => $version,]],
    			'2' =>['type'=>'hidden','fielddata'=>['name' => 'status', 'status' => 'status', 'value' => $status,]],
    			'3' =>['type'=>'hidden','fielddata'=>['name' => 'uniqueCode', 'id' => 'uniqueCode', 'value' => $uniqueCode,]],
    			'billTo' =>['type'=>'textfield', 'label'=>'Bill To','fielddata'=>['name' => 'billTo', 'id' => 'billTo', 'value' => $billTo,]],
    			'billingAddress' =>['type'=>'textarea','label'=>'Billing Address', 'fielddata'=>['name' => 'billingAddress', 'id' => 'billingAddress', 'rows' => '2','colss' => '10', 'value' => $billingAddress,]],
    			'shipTo' =>['type'=>'textfield', 'label'=>'Ship To','fielddata'=>['name' => 'shipTo', 'id' => 'shipTo', 'value' => $shipTo,]],
    			'shippingAddress' =>['type'=>'textarea','label'=>'Shipping Address', 'fielddata'=>['name' => 'shippingAddress', 'id' => 'shippingAddress', 'rows' => '2','colss' => '10', 'value' => $shippingAddress,]],
    			'email' =>['type'=>'textfield', 'label'=>'Email','fielddata'=>['name' => 'email', 'id' => 'email', 'value' => $email,]],
				'deliverydate' =>['type'=>'textfield', 'label'=>'Delivery Date','fielddata'=>['name' => 'deliverydate', 'id' => 'deliverydate', 'value' => $deliverydate,]],
    	];
    	
    	return $data;
    }

    
    function pdf($id){
    	$data = $this->loadChallanData($id);
    	
    	$this->load->view('challan/pdf/index', $data);
    	// Get output html
    	$html = $this->output->get_output();
    	
    	// Load library
    	$this->load->library('dompdf_gen');

    	$this->dompdf->load_html($html);
    	$this->dompdf->render();
    	$this->dompdf->stream("welcome.pdf");
    }
    
    function challanpdf($id, $auth=1){
    	$data = $this->loadChallanData($id, $auth);
    	 
    	$this->load->view('challan/add/challan', $data);
    	// Get output html
    	$html = $this->output->get_output();
    	 
    	// Load library
    	$this->load->library('dompdf_gen');
    
    	$this->dompdf->load_html($html);
    	$this->dompdf->render();
    	$this->dompdf->stream($data['challanRef'].".pdf");
    }
    
    function downloadchallan($id){
    	$id = base64_decode($id);
    	$id = substr($id, 0, 6);
    	return $this->challanpdf($id, 0);
    }
    
    function invoicepdf($id, $auth=1){
    	$data = $this->loadChallanData($id, $auth);
    
    	$this->load->view('challan/add/invoice', $data);
    	// Get output html
    	$html = $this->output->get_output();
    
    	// Load library
    	$this->load->library('dompdf_gen');
    
    	$this->dompdf->load_html($html);
    	$this->dompdf->render();
    	$this->dompdf->stream( str_replace (Applicationconst::TRANSACTION_DOC_CHALLAN, Applicationconst::TRANSACTION_DOC_INVOICE, $data['challanRef']).".pdf");
    }
             
    function downloadinvoice($id){
    	$id = base64_decode($id);
    	$id = substr($id, 0, 6);
    	return $this->invoicepdf($id, 0);
    }
    
    function challanemail($id){
    	$data = $this->loadChallanData($id);
    	$to = $data['email'];
    	$from = Applicationconst::EMAIL_FROM;
    	
    	$str = "000000".$id;
    	$str = substr ($str, strlen($str)-6);
    	$str = $str.$str.$str;
    	$data['downloadid'] =base64_encode($str);
    	$msg = $this->load->view('challan/email/ctemplate', $data, true);
    	//$this->load->view('challan/add/challan', $data);
    	// Get output html
    	//$html = $this->output->get_output();
    	
    	$this->email->from($from, 'Info');
    	$this->email->to($to);
    	$this->email->set_mailtype("html");
    	$this->email->subject('[POS+] System generated challan');
    	$this->email->message($msg);
    	
    	//Send mail 
         if($this->email->send()) 
         $this->session->set_flashdata("email_sent","Email sent successfully."); 
         else 
         $this->session->set_flashdata("email_sent","Error in sending Email."); 
         
    	$this->load->view('challan/email/index', $data);
    	redirect(base_url() . 'index.php/challan/add/'.$id, 'refresh');
    }
    
    function invoiceemail($id){
    	$data = $this->loadChallanData($id);
    	$to = $data['email'];
    	$from = Applicationconst::EMAIL_FROM;
    	 
    	$str = "000000".$id;
    	$str = substr ($str, strlen($str)-6);
    	$str = $str.$str.$str;
    	$data['downloadid'] = base64_encode($str);
    	$msg = $this->load->view('challan/email/itemplate', $data, true);
    	//$this->load->view('challan/add/challan', $data);
    	// Get output html
    	//$html = $this->output->get_output();
    	 
    	$this->email->from($from, 'Info');
    	$this->email->to($to);
    	$this->email->set_mailtype("html");
    	$this->email->subject('[POS+] System generated invoice');
    	$this->email->message($msg);
    	 
    	//Send mail
    	if($this->email->send())
    		$this->session->set_flashdata("email_sent","Email sent successfully.");
    	else
    		$this->session->set_flashdata("email_sent","Error in sending Email.");
    	 
    	$this->load->view('challan/email/index', $data);
    	
    	redirect(base_url() . 'index.php/challan/add/'.$id, 'refresh');
    }
    
}
