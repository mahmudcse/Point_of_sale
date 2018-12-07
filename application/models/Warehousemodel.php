<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Warehousemodel extends MY_Model {
	
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        $this->tableName = 'warehouse';
    }
    
    public function insertSaleInventory($datas){
		$this->db->limit(1);
    	$this->db->order_by('componentId', 'DESC');
    	$query  = $this->db->get('transaction');
		$row = $query->row_array();

		for ($j =0 ; $j<count($datas['warehouseIdsss']);$j++){
			$inv=array(
				"tdate"=>$datas["tdate"],
				"warehouseId" =>$datas["warehouseId"],
				"type" =>$datas["type"],
				"uniqueCode"=>$datas["name"],
				"description"=>$datas["description"],
				"transactionId"=>$row['componentId']
			);
	
	  	$this->db->insert("inventory",$records);
	}

	$this->db->limit(1);
    $this->db->order_by('componentId', 'DESC');
    $query  = $this->db->get('inventory');
    // return $query->row_array();
	//$query=$this->db->get("inventory");
	$row=$query->row_array();


		for($i=0;$i<$datas['productCounter'];$i++){
		// $datas['itemId'] = $this->input->post('itemId'.$i);
		// $datas['quantity'] = $this->input->post('quantity'.$i);
			//$rec=
			$rec = array(
		"itemId"=>$this->input->post('itemId'.$i),
		"quantity"=>$this->input->post('quantity'.$i),
		"condition"=>"Good",
		"type"=>-1,
		"warehouseId"=>$this->input->post('warehouseIdsss'),
		"inventoryId"=>$row['componentId']
		
		
		);
			 $this->db->insert('inventorydetail',$rec);
		// echo "<pre>";
  //           //  print_r($datas['itemId']);
  //              print_r($rec);
  //              echo "</pre>";
	}



	// for($i = 0; $i<$datas['productCounter']; $i++){

	// 	$record = array(
	// 	"itemId"=>$datas['itemId'][$i],
	// 	"quantity"=>$data['quantity'][$i],
	// 	"condition"=>$data['condition'][$i],
	// 	"warehouseId"=>$data["warehouseId"],
	// 	"inventoryId"=>$row['componentId'],
	// 	"type"=>1
		
	// 	);



	// //   echo "<pre>";
	// // print_r($record);
	// // echo "</pre>";
	// 	 $this->db->insert('inventorydetail',$record);
	// 	}
}

}
?>
