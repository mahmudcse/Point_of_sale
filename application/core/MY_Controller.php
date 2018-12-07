<?php
class MY_Controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    
        $this->load->database();
        
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
    }
    
    public function index(){
    	
    	return;
    }
    
    public function commonSearch($pageVar){
    	$pageVar['limit']  = 100;
    	$pageVar['pageNo'] = 1;
    	$pageVar['search'] = '';
    	if($this->input->post('pageNo') != null)
    		$pageVar['pageNo'] = $this->input->post('pageNo');
    	if($this->input->post('search') != null)
    		$pageVar['search'] = $this->input->post('search');
    	if($this->input->get('pageNo') != null)
    		$pageVar['pageNo'] = $this->input->get('pageNo');
    	
    	$pageVar['inputs'] = [
    			
    			'pageNo' =>['type'=>'hidden','fielddata'=>['name' => 'pageNo', 'id' => 'pageNo', 'value' => $pageVar['pageNo'],]],
    	
    			'search' =>['type'=>'textfield','label'=>'Search text','fielddata'=>['name' => 'search', 'id' => 'search', 'class' => 'form-control', 'value' => $pageVar['search'] ,]],
    			];
    			 
    	
    	return $pageVar;
    }

    public function isLoggedIn()
    {
        if($this->session->userdata('username'))
        	return true;
 		else
        	return false;
    }
    
    public function commonTasks()
    {
    	$data = array();
    	$system_name	=	$this->db->get_where('codes', array('uniqueCode' => 'system_name'))->row()->key_value;
    	$system_title	=	$this->db->get_where('codes', array('uniqueCode' => 'system_title'))->row()->key_value;
    	$data['system_name'] = $system_name;

    	$data['system_title'] = $system_title;

        $component= $this->router->fetch_class();
        $method= $this->router->fetch_method();
         $acl=$component.$method;	
       

    	
    	if ($this->isLoggedIn() == false)
        	redirect(base_url() . 'index.php/login', 'refresh');
    	else{
    		$userId = $this->session->userdata('userid');
	    	$query = $this->db->query("SELECT * FROM vuserfunctions WHERE userid = $userId ");
			$menu = array();

			$accessDenied = true;
			foreach ($query->result() as $row)
			{


				if($row->uniqueCode==$acl)
					$accessDenied = false;
				

				$menu[$row->functionGroup][$row->componentId] = $row;

			}
			
			if($accessDenied==true && $acl!="homedashboard" && $acl!="homeaccessdenied") 
      		{   

      			redirect(base_url() . 'index.php/home/accessdenied','refresh');
      			

      		}

   			
			$data['menu'] = $menu;
			$data['username'] = $this->session->userdata('username');
    
			return $data;


			
    	}

    }
    
    public function getSequence($seqName){
    	$query = $this->db->query("CALL getsequence('".$seqName."');");
    	$res = $query->result_array();
    	
    	$query->next_result();
    	$query->free_result();
    	
    	$currentValue = 0;
    	foreach ($res as $row)
    	{
    		$currentValue = $row['currentValue'];
    	}
    	
    	return $currentValue;
    }
    
    public function load($tableName, $where = ""){
    	$query = $this->db->query("SELECT * FROM $tableName $where ");
    	return $query->result();
    }
    
    
}

class MY_RestController extends MY_Controller
{
	var $model;
	public function __construct()
	{
		parent::__construct();
		$this->load->model('my_model');
		$this->model = $this->my_model;
		
		

	}

	public function index(){
		 
		return;
	}
	
	private function checkAuthentication($request){
		if($request == null)
			return false;
		return true;
	}
	
	private function checkAuthorization($request){
		if($request == null)
			return false;
		return true;
	}
	
	/**
	 * All requests should go through this method
	 * 
	 * This method contains authentication and authorization activities.
	 * 
	 * @param string $param
	 */
	public function post($param = ''){
		$response = ["success"=>false];
		$_POST = json_decode(file_get_contents('php://input'), true);
		$request = $this->input->post();
		$isLoggedin = $this->checkAuthentication($request);
		$isAuthorized = $this->checkAuthorization($request);
		$operation = $request['operation'];
		
		if($operation == null){
			//TODO implement operan null case
			;
		}else{
			switch ($operation) {
				case Applicationconst::OPERATION_ADD:
					$response['data'] = $this->add($request['data']);
					$response['success'] = true;
					break;
				case Applicationconst::OPERATION_MODIFY:
					$response['data'] = $this->modify();
					$response['success'] = true;
					break;
				case Applicationconst::OPERATION_DELETE:
					$response['data'] = $this->delete();
					$response['success'] = true;
					break;
				case Applicationconst::OPERATION_GET:
					$response['data'] = $this->get($request['componentId']);
					$response['success'] = true;
					break;
				case Applicationconst::OPERATION_GET_ALL:
					$response['data'] = $this->getAll();
					$response['success'] = true;
					break;
				case Applicationconst::OPERATION_GET_BY_FILTER:
					$response['data'] = $this->getByFilter($request['filter']);
					$response['success'] = true;
					break;
				default:
					$response['success'] = true;
					$response['message'] = 'Invalid operation';
			}
		}
		
		echo json_encode($response);
		return;
	}
	
	/**
	 * Save data
	 * @param unknown $object
	 */
	protected function add($object){
		$object = $this->updateAuditInfo($object);
		return $this->model->save($object);
	}
	
	protected function updateAuditInfo($object){
		$object['createdby'] =  $this->session->userdata('userid');
		$object['createddate'] = date("Y-m-d h:i:s");
		return $object;
	}
	/**
	 * Modify data
	 * @param unknown $object
	 */
	protected function modify($objectId, $object){
		return $this->model->update($objectId, $object);
	}
	
	/**
	 * delete data
	 * @param unknown $object
	 */
	protected function delete($objectId){
		return $this->model->delete($objectId);
	}
	
	/**
	 * fetch all data
	 * @param unknown $object
	 */
	protected function getAll(){
		return $this->model->getAll();
	}
	
	/**
	 * fetch data
	 * @param unknown $object
	 */
	protected function get($objectId){
		return $this->model->getById($objectId);
	}
	
	/**
	 * fetch data by filter
	 * @param unknown $object
	 */
	protected function getByFilter($filter){
		return $this->model->getByFilter($filter);
	
	}
	
	
	/*
	public function get($param = ''){
		echo 'Test';
		return;
	}
	
	public function put($param = ''){
		echo 'Test';
		return;
	}
	
	public function delete($param = ''){
		echo 'Test';
		return;
	}
	*/

}