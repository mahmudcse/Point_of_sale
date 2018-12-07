<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*  
 *  @author : Sharif Uddin
 *  date  : April 01, 2016
 */ 
 /* test comment for git */  

class Installment extends MY_Controller
{
    function __construct()
    {
        parent::__construct();

    }
    
    /***default functin, redirects to login page if no admin logged in yet***/
    public function index()
    {
        //commonTasks();
        redirect(base_url() . 'index.php/admin/dashboard', 'refresh');
    }
     public function commonTasks(){
        $data = parent::commonTasks();
        $data['component'] = 'installment';
        return $data;
    }
   
    
      
    public function search()
    {
        $data = $this->commonTasks();
        $data = $this->commonSearch($data);
        
       $data['search'] = '';
       $data['stdate']='';
       $data['etdate']='';
       $data['tn_search']='';
       
       if($this->input->post('search')!=null)
          $data['search'] = $this->input->post('search');

         if($this->input->post('tn_search')!=null)
          $data['tn_search'] = $this->input->post('tn_search');
         
        $data['stdate'] = date('Y-m-d',strtotime("-7 days"));
        if($this->input->post('stdate')!=null)
            $data['stdate'] = $this->input->post('stdate');  
      
         $data['etdate'] = date('Y-m-d',strtotime("0 days"));
         if($this->input->post('etdate')!=null)
           $data['etdate'] = $this->input->post('etdate');
      
        $data['inputs']['search'] = ['type'=>'textfield','label'=>'Name','fielddata'=>['name' => 'search', 'id' => 'search', 'value' => $data['search']]];
        $data['inputs']['stdate'] = ['type'=>'textfield','label'=>'Start  date','fielddata'=>['name' => 'stdate', 'id' => 'stdate', 'value' => $data['stdate'] ]];
        $data['inputs']['etdate'] = ['type'=>'textfield','label'=>'End  date','fielddata'=>['name' => 'etdate', 'id' => 'etdate', 'value' => $data['etdate'] ]];
        $data['inputs']['tn_search'] = ['type'=>'textfield','label'=>'TransactioNo','fielddata'=>['name' => 'tn_search', 'id' => 'tn_search', 'value' => $data['tn_search']]];
       $data['inputs']['btn_search'] = ['type'=>'hidden','fielddata'=>['name' => 'btn_search', 'id' => 'btn_search', 'value' => 'btn_search']];
    
        $data['page_title'] = 'Installment';
        $data['page_name'] = 'home';
        $data['searchAction'] = 'installment/search';
        $data['searchDisplayTxt'] = 'searchDisplayTxt';  
        
        $searchSQL="SELECT tn.uniqueCode, cs.componentId ,cs.name, am.componentId installmentId , am.amount, DATE_FORMAT(am.deadLine, '%d-%M-%Y') deadLine ,tn.componentId, td.transactionId, td.userId, cs.componentId, td.accountId
           FROM transaction  tn 
           INNER JOIN  installment am on (tn.componentId=am.transactionId)
           INNER JOIN transaction_detail td on (td.transactionId=tn.componentId)
           INNER JOIN  customer cs on  (cs.componentId=td.userId ) WHERE accountId ='".Applicationconst::ACCOUNT_HEAD_SALE."' AND (deadLine BETWEEN '".$data['stdate']."' AND '".$data['etdate']."' ) " ;

           if($data['search']!='') 
              $searchSQL .= " And cs.name='".$data['search']."'" ;
            
            else if($data['tn_search']!='') 
               $searchSQL .= " And tn.uniqueCode='".$data['tn_search']."'" ;
  
        $pageSQL = " LIMIT ".($data['pageNo']-1)*$data['limit'].",  ".$data['limit'];
        $query = $this->db->query($searchSQL);

        $data['total'] = $query->num_rows();
        $query1 = $this->db->query($searchSQL.$pageSQL);

        $data['searchData'] = $query1->result();
        
        $data['propertyArr'] = ['uniqueCode'=>'TransactionNO','name'=>'Name', 'amount'=>'Amount','deadLine'=>'Date'];
        $data['addmodifyAction'] = 'installment/search';
         // Capitalize the first letter
        
        $this->load->view('installment/search/index.php', $data);
    }

    public function commonForPayment(){
        $data = parent::commonTasks();
        $data['component'] = 'Payment';
        return $data;
    }

    public function payments($userId = '', $installmentId = ''){
      
      $data = $this->commonForPayment();
      $data = $this->commonSearch($data);
      array_pop($data['inputs']);

       $data['stdate']='';
       $data['etdate']='';
         
        $data['stdate'] = date('Y-m-d',strtotime("-7 days"));
        if($this->input->post('stdate')!=null)
            $data['stdate'] = $this->input->post('stdate');  
      
         $data['etdate'] = date('Y-m-d',strtotime("0 days"));
         if($this->input->post('etdate')!=null)
           $data['etdate'] = $this->input->post('etdate');
      

        $data['inputs']['stdate'] = ['type'=>'textfield','label'=>'Start  date','fielddata'=>['name' => 'stdate', 'id' => 'stdate', 'value' => $data['stdate'] ]];
        $data['inputs']['etdate'] = ['type'=>'textfield','label'=>'End  date','fielddata'=>['name' => 'etdate', 'id' => 'etdate', 'value' => $data['etdate'] ]];

       $data['inputs']['btn_search'] = ['type'=>'hidden','fielddata'=>['name' => 'btn_search', 'id' => 'btn_search', 'value' => 'btn_search']];
    
        $data['page_title'] = 'Payments';
        $data['page_name'] = 'home';
        $data['searchAction'] = 'Installment/payments/'.$userId;

        $searchSQL = "SELECT
                    td.componentId,
                    td.transactionId,
                    td.userId,
                    td.accountId,
                    (td.quantity * td.unitPrice) AS amount,
                    DATE_FORMAT(t.tdate, '%d-%M-%Y') tdate
                    FROM
                    transaction_detail td
                    INNER JOIN transaction t ON t.componentId = td.transactionId
                    WHERE td.transactionId in (
                      SELECT
                        td2.transactionId
                        FROM transaction_detail td2
                        INNER JOIN transaction t2 ON t2.componentId = td2.transactionId
                        WHERE td2.userId = $userId AND td2.type = '-1' AND (td2.accountId = 1 or td2.accountId = 30) and t2.tdate between '".$data['stdate']."' AND '".$data['etdate']."'
                    ) AND (td.accountId = 1 OR td.accountId = 30) AND td.`type` = 1
                    ORDER BY t.tdate"; 



        $pageSQL = " LIMIT ".($data['pageNo']-1)*$data['limit'].",  ".$data['limit'];
        $query = $this->db->query($searchSQL);

        $data['total'] = $query->num_rows();
        $query1 = $this->db->query($searchSQL.$pageSQL);

        $data['searchData'] = $query1->result();
        $data['installmentId'] = $installmentId;
        
        $data['propertyArr'] = ['amount'=>'Amount','tdate'=>'Date'];
        $data['addmodifyAction'] = 'installment/search';
         // Capitalize the first letter
        
        $this->load->view('payments/index', $data);
        


    }

    public function saveReconcile($transactionId = '', $transaction_detail_id = '', $installmentId = ''){

      $previousUsedAmount = "
                            SELECT
                              SUM(ir.amount) previousAmount
                              FROM
                              installment_reconcile ir
                              WHERE ir.transaction_detail_id = $transaction_detail_id";
      $previousUsedAmount = $this->db->query($previousUsedAmount)->row()->previousAmount;
      $amountInHere = "SELECT
                        SUM(td.quantity * td.unitPrice) amountHere
                        FROM
                        transaction_detail td
                        WHERE td.componentId = $transaction_detail_id";
      $amountInHere = $this->db->query($amountInHere)->row()->amountHere;
      $remains = $amountInHere - $previousUsedAmount;

      $amountNeededNow = "SELECT
                          SUM(ir.amount) previousPaid,
                          (i.amount - sum(ir.amount)) amountNeeded
                          FROM
                          installment_reconcile ir
                          INNER JOIN installment i ON i.componentId = ir.installmentId
                          WHERE i.componentId = $installmentId";
      $amountNeeded = $this->db->query($amountNeededNow)->row()->amountNeeded;

      if( $remains >= $amountNeeded){
        $reconcileData['amount'] = $amountNeeded;
        $remaining = $remains - $amountNeeded;
      }else if($remains > 0 && $remains < $amountNeeded){
        $reconcileData['amount'] = $remains;
        $remaining = 0;
      }

      $reconcileData['installmentId']         = $installmentId;
      $reconcileData['transactionId']         = $transactionId;
      $reconcileData['transaction_detail_id'] = $transaction_detail_id;
      $reconcileData['reconcile_date']        = date("Y-m-d h:i:s");
      $this->db->insert('installment_reconcile', $reconcileData);

      echo json_encode($remaining);
    }
}
