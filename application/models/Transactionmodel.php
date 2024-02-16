<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Transactionmodel extends MY_Model {
	
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        $this->tableName = 'transaction';
    }
    
    public function save($data){
    	$this->db->select('COUNT(*) as total');
    	$this->db->where(['type'=>$data['transaction']['type']]);
    	$result = $this->db->get('transaction')->result();
    	
    	$refNo = $result[0]->total;
    	
    	$refNo +=1;
    	
    	$data['transaction']['uniqueCode'] = $data['transaction']['type'].'-'.$refNo;
    	
    	$this->db->insert($this->tableName, $data['transaction']);
    	$transactionId = $this->db->insert_id();
    	
    	foreach ($data['details'] as $details):
    		$details['transactionId'] = $transactionId;
    		$this->db->insert('transaction_detail', $details);
    	endforeach;
    	
    	return $this->getById($transactionId);
    
    }
    
    public function getByFilter($filter){
    	$ret = $this->db->get_where($this->tableName, $filter)->result();
    	$r = array();
    	foreach ($ret as $tr):
    		$trar = (array)$tr;
    	//$foo['bar'] = '1234';
    	//$foo = (object)$foo;
    		$trd = $this->db->get_where('transaction_detail', ['transactionId'=>$tr->componentId])->result();
    		$trar['details'] = $trd;
    		array_push($r, (object)$trar);
    	endforeach;
    	
    	return $r;
    }

}
?>
