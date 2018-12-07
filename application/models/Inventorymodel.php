<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Inventorymodel extends MY_Model {
	
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        $this->tableName = 'inventory';
    }
    
    public function getById($id){
    	
    	$ret = $this->getByFilter(['componentId'=>$id]);
    	if(count($ret) > 0 )
    		$ret = $ret[0];
    	else {
    		$ret = ["pquantity"=>0];
    		$ret = ["squantity"=>0];
    		$ret = (object)$ret;
    	}
    	
    	return $ret;
    		

    }
    
    function save($data){
    	$invId = parent::save($data['main']);
    	foreach ($data['details'] as $row){
    		$row['inventoryId']=$invId;
    		$this->db->insert('inventorydetail', $row);
    	}
    }

}
?>
