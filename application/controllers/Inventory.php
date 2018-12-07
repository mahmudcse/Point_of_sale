<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*	
 *	@author : Sharif Uddin
 *	date	: April 01, 2016
 */

class Inventory extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
		
    }
    
    /***default functin, redirects to login page if no admin logged in yet***/
    public function index()
    {
        //commonTasks();
        redirect(base_url() . 'index.php/inventory/search', 'refresh');
    }
    public function commonTasks(){
    	$data = parent::commonTasks();
    	$data['component'] = 'Inventory';
    	return $data;
    }

    public function search()
    {
        
        $data = $this->commonTasks();
        
        
        $data['search'] = '';
        $data['cat'] = '';
        $data['subcat'] = '';
        $data['whoperation'] = '';
        
       
        $data = $this->commonSearch($data);

        $warehouseOperations = [
            ''         => 'Select Operation',
            '-1'       => 'SEND',
            '1'        => 'RECEIVE',
            'PURCHASE' => 'PURCHASE',
            'SALE'     => 'SALE'
        ];


        
        //echo $data['pageNo'].'-------->';
                
        $data['inputs']['cat'] = ['type'=>'textfield','label'=>'Category','fielddata'=>['name' => 'cat', 'id' => 'cat', 'value' => $data['cat'] ,]];
        $data['inputs']['subcat'] = ['type'=>'textfield','label'=>'Sub-category','fielddata'=>['name' => 'subcat', 'id' => 'subcat', 'value' => $data['subcat'], 'class' => 'form-control']];

        $data['inputs']['warehouse'] = ['type'=>'dropdown','label'=>'Warehouse','fielddata'=>['name' => 'whoperation', 'options' => $warehouseOperations, 'value' => $data['whoperation'], 'class' => 'form-control']];
            
        $data['page_title'] = 'Search Inventory';
        $data['page_name'] = 'home';
        $data['searchAction'] = base_url() . 'index.php/inventory/search';
        $data['searchDisplayTxt'] = 'searchDisplayTxt';

        $userId = $this->session->userdata('userid');

        if($this->input->post('search')!=null){
            $data['search'] = $this->input->post('search');
            $this->db->like('itemName', $data['search']);
        }
        if($this->input->post('cat')!=null){
            $data['cat'] = $this->input->post('cat');
            $this->db->like('category2', $data['cat']);
        }
        if($this->input->post('subcat')!=null){
            $data['subcat'] = $this->input->post('subcat');
            $this->db->like('category2', $data['subcat']);
        }
        if($this->input->post('whoperation')!=null){
            $data['whoperation'] = $this->input->post('whoperation');
            $this->db->like('category2', $data['whoperation']);
        }



        $searchSQL = "SELECT
                            id.componentId,
                            id.itemId itemId,
                            i.itemName,
                            (CASE WHEN (id.`type` = -1) THEN 'SEND' ELSE 'RECEIVE' END) AS `type`,
                            SUM(id.quantity * id.`type`) AS `quantity`,
                            id.`condition`,
                            id.inventoryId,
                            id.warehouseId,
                            w.name warehouseName,
                            i.category1,
                            i.category2,
                            i.category3
                        FROM
                        inventorydetail id
                        INNER JOIN warehouse w ON id.warehouseId = w.componentId
                        INNER JOIN userwarehouse uw ON w.componentId = uw.warehouseId AND uw.userId = $userId
                        INNER JOIN item i ON id.itemId = i.componentId

                        WHERE itemName LIKE '%".$data['search']."%' AND category2 LIKE '%".$data['cat']."%' AND category3 LIKE '%".$data['subcat']."%'
                        GROUP BY i.componentId, w.componentId
                        ORDER BY i.itemName";
        $pageSQL = " LIMIT ".($data['pageNo']-1)*$data['limit'].",  ".$data['limit'];

        $query = $this->db->query($searchSQL);
        


        $data['total'] = $query->num_rows();
        $query1 = $this->db->query($searchSQL.$pageSQL);

        $data['searchData'] = $query1->result();

        //$this->prntar($data['searchData']);
        
        // $summerySql = "SELECT count(*) totalRow, SUM(pquantity) totalPurchasedQuantity, SUM(IFNULL(squantity,0)) totalSoldQuantity, SUM(IFNULL(pquantity,0)-IFNULL(squantity,0)) totalQuantity, 
        //                     SUM((IFNULL(pquantity,0)-IFNULL(squantity,0))*(pprice/pquantity)) totalPurchasedAmount, SUM((IFNULL(pquantity,0)-IFNULL(squantity,0))*salePrice) totalSoldAmount
        //                 FROM vInventorySummery
        //             WHERE itemName LIKE '%".$data['search']."%' AND category2 LIKE '%".$data['cat']."%' AND category3 LIKE '%".$data['subcat']."%'  ";

        // $query = $this->db->query($summerySql);
        // $data['summeryData'] = $query->result()[0];
        // $data['total'] = $data['summeryData']->totalRow;
        
        $data['propertyArr'] = ['itemName'=>'Name', 'type'=>'Operation', 'quantity'=>'Quantity','warehouseName'=>'Warehouse'];
        $data['addmodifyAction'] = 'index.php/inventory/details';
        
        $this->load->view('inventory/search/index.php', $data);
    }
    
    function details($id = 0){
    	
    	$data = $this->commonTasks();
    	$data = $this->commonSearch($data);
    	
    	$data['page_title'] = 'Dashboard';
    	$data['page_name'] = 'home';
    	
    	$data['searchAction'] =base_url() . 'index.php/inventory/details';
    	
    	$data['input'] ['search'] =['type'=>'hidden','label'=>'Search text','fielddata'=>['name' => 'search', 'id' => 'search', 'value' => $data['search'] ,]];
    	
    	$searchSQL = "SELECT itemId as componentId, DATE_FORMAT(tdate,'%d %b %y') tdate, voucher, itemName, quantity, lastPurchasePrice(itemId) as purchasePrice, salePrice,minQty
    					FROM inventoryView where itemId = $id ";
    	$pageSQL = " LIMIT ".($data['pageNo']-1)*$data['limit'].",  ".$data['limit'];

    	$query = $this->db->query($searchSQL);
    	$data['total'] = $query->num_rows();

    	$query1 = $this->db->query($searchSQL.$pageSQL);
    	$data['searchData'] = $query1->result();

        //exit();
    	
    	$data['propertyArr'] = ['tdate'=>'Date', 'voucher'=>'Reference','itemName'=>'Name','componentId'=>'Item Id','quantity'=>'Quantity','purchasePrice'=>'Purchase Price','salePrice'=>'Sale Price'];
    	$data['addmodifyAction'] = "#";// 'index.php/item/add';
    	// Capitalize the first letter
    	 
    	$this->load->view('inventory/details/index.php', $data);
    	
    }
    
}
