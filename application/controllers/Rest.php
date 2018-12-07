<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*	
 *	@author : Sharif Uddin
 *	date	: April 01, 2016
 */ 
 /* test comment for git */  

class Rest extends MY_Controller
{
    function __construct()
    {
    	
        parent::__construct();
    }
    
    /***default functin, redirects to login page if no admin logged in yet***/
    public function index()
    {
    	$this->load->model('functioncodesmodel');
    	$res = $this->functioncodesmodel->getAll();
    	print_r($res);
        //commonTasks();
        echo "{}";
		return;
    }
    
    public function security($method)
    {
		$_POST = json_decode(file_get_contents('php://input'), true);
		$request = $this->input->post();
		//print_r($request);
		$operation = $request['operation'];
		if($operation == "Login"){
			$r = $this->login($request);
			echo json_encode($r);
		}else if($operation == 'CheckUserSession'){
			$r = $this->checkUserSession($request);
			echo json_encode($r);
		}else if($operation == 'getAllLoginId'){
			//$r = $this->checkUserSession($request);
			//echo json_encode($r);
			echo "{}";
		}

    }
    
    private function login($object){
    	
    	$this->load->model('usersmodel');
    	
    	$res = $this->usersmodel->authenticate($object['loginId'], $object['password']);
    	if($res->success){
   			
    		$dataArr = (array)$res->data;
    		$dataArr['loginId'] = $dataArr['uniqueCode'];
    		$dataArr['menuJSON'] = $this->getMenuJSON($dataArr['componentId']);
    		
    		$this->session->set_userdata('username', $dataArr['uniqueCode']);
    		$this->session->set_userdata('userid', $dataArr['componentId']);						
    		$this->session->set_userdata('loggedInUserName', $dataArr['firstName'].' '.$dataArr['firstName']);
    		
    		$dataArr['sessionId'] = session_id();//$this->session->userdata('session_id');
    		
    		$res->data = (object)$dataArr;
    		
    	}
    	
    	return $res;
	}
	
	function user($param){
		
		$_POST = json_decode(file_get_contents('php://input'), true);
		$request = $this->input->post();
		$operation = $request['operation'];
		if($param == 'post'){
			if($operation=='GetAll'){
				$r = $this->getAllUsers();
				echo json_encode($r);
			}else if($operation == 'GetById'){
				$componentId = $request['componentId'];
				
				$r = $this->getUserById($componentId);
				echo json_encode($r);
			}else if($operation == 'Update'){
				$componentId = $request['componentId'];
				
				$r = $this->updateUser($componentId, $request);
				echo json_encode($r);
			}else if($operation == 'Save'){
	
				$r = $this->saveUser($request);
				echo json_encode($r);
			}else if($operation == 'GetUserRolesByID'){
				$componentId = $request['componentId'];
				$r = $this->getUserroles($componentId);
				echo json_encode($r);
			}else if($operation == 'RoleAssign'){
				$componentId = $request['componentId'];
				$roles = $request['roles'];
				$r = $this->assignRoles($componentId, $roles);
				echo json_encode($r);
			}
		}
		
	}
	
	function role($param){
	
		$_POST = json_decode(file_get_contents('php://input'), true);
		$request = $this->input->post();
		$operation = $request['operation'];
		if($param == 'post'){
			if($operation=='GetAll'){
				$r = $this->getAllRoles();
				echo json_encode($r);
			}else if($operation == 'GetById'){
				$componentId = $request['componentId'];
	
				$r = $this->getRoleById($componentId);
				echo json_encode($r);
			}else if($operation == 'Update'){
				$componentId = $request['componentId'];
	
				$r = $this->updateRole($componentId, $request);
				echo json_encode($r);
			}else if($operation == 'Save'){
	
				$r = $this->saveRole($request);
				echo json_encode($r);
			}else if($operation == 'GetRoleFeaturesByID'){
				$componentId = $request['componentId'];
				$r = $this->getRoleFeatures($componentId);
				echo json_encode($r);
			}else if($operation = 'FeatureAssign'){
				$componentId = $request['componentId'];
				$featureIds = $request['featureIds'];
				$r = $this->assignFeatures($componentId, $featureIds);
				echo json_encode($r);
			}
		}
	
	}
	
	function questionanswer($param){
	
		$_POST = json_decode(file_get_contents('php://input'), true);
		$request = $this->input->post();
		$operation = $request['operation'];
		if($param == 'post'){
			if($operation=='GetAll'){
				$r = $this->getAllQuestionanswers();
				echo json_encode($r);
			}else if($operation == 'GetById'){
				$componentId = $request['componentId'];
	
				$r = $this->getQuestionanswerById($componentId);
				echo json_encode($r);
			}else if($operation == 'Update'){
				$componentId = $request['componentId'];
	
				$r = $this->updateQuestionanswer($componentId, $request);
				echo json_encode($r);
			}else if($operation == 'Save'){
	
				$r = $this->saveQuestionanswer($request);
				echo json_encode($r);
			}else if($operation == 'Delete'){
				$componentId = $request['componentId'];
	
				$r = $this->deleteQuestionanswer($componentId);
				echo json_encode($r);
			}
		}
	
	}
	
	function feature($param){
	
		$_POST = json_decode(file_get_contents('php://input'), true);
		$request = $this->input->post();
		$operation = $request['operation'];
		if($param == 'post'){
			if($operation=='GetAll'){
				$r = $this->getAllFeatures();
				echo json_encode($r);
			}else if($operation == 'GetById'){
				$componentId = $request['componentId'];
	
				$r = $this->getFeatureById($componentId);
				echo json_encode($r);
			}else if($operation == 'Update'){
				$componentId = $request['componentId'];
	
				$r = $this->updateFeature($componentId, $request);
				echo json_encode($r);
			}else if($operation == 'Save'){
	
				$r = $this->saveFeature($request);
				echo json_encode($r);
			}
		}
	
	}
	
	private function checkUserSession($request){
		$res = $this->load->model('response');
		if(isset($request['sessionId']))
			if($request['sessionId'] == session_id())
				$res ->success = true;
	
		return $res;
	
	}
	
	private function assignRoles($userId, $roles){

		$ret = $this->load->model('response');
		
		$this->load->model('userrolesmodel');
		$data = $this->userrolesmodel->assignRoles($userId, $roles);
		$ret->success = true;
		$ret->data = $data;
		
		return $ret;		
	}
	
	private function assignFeatures($componentId, $featureIds){
		$ret = $this->load->model('response');
		
		$this->load->model('functionrolesmodel');
		$data = $this->functionrolesmodel->assignFeatures($componentId, $featureIds);
		$ret->success = true;
		$ret->data = $data;
		
		return $ret;
	}
	
	private function getRoleFeatures($componentId){
		
		$ret = $this->load->model('response');
		
		$this->load->model('functionrolesmodel');
		$data = $this->functionrolesmodel->getRolefunctions($componentId);
		$ret->success = true;
		$ret->data = $data;
		
		return $ret;
	}
	
	private function getMenuJSON($userId){
		$this->load->model('functioncodesmodel');
		$res = $this->functioncodesmodel->getUserMenu($userId);
		if(!$res)
			$res = [];
			return json_encode($res);
	}
	
	private function getAllUsers(){
		$this->load->model('usersmodel');
		$data = $this->usersmodel->getAll();
		
		$ret = $this->load->model('response');
		$ret->success = true;
		$ret->data = $data;
		
		return $ret;
	}
	
	private function getUserroles($userId){
		$ret = $this->load->model('response');
		
		$this->load->model('userrolesmodel');
		$data = $this->userrolesmodel->getUserroles($userId);
		$ret->success = true;
		$ret->message = 'Loading User Roles are successful';
		$ret->data = $data;
		
		return $ret;
	}
	
	private function getUserById($componentId){
		$ret = $this->load->model('response');
		
		$this->load->model('usersmodel');
		$data = $this->usersmodel->getById($componentId);
		$ret->success = true;
		$ret->data = $data;
	
		return $ret;
	}
	
	private function requestToModel($object){
		$operation = $object['operation'];
		//if(isset($object['operation']))
		unset($object['operation']);
		//if(isset($object['loginBean']))
		unset($object['loginBean']);
		unset($object['confirmPassword']);
		
		if($operation == 'Update'){
			//if(isset($object['componentId']))
			unset($object['componentId']);
		}
		
		return $object;
	}
	
	private function updateUser($componentId, $object){
		$ret = $this->load->model('response');
		
		try {
			$this->load->model('usersmodel');
			$data = $this->usersmodel->update($componentId, $this->requestToModel($object));
			$ret->success = true;
			$ret->data = $data;
			$ret->message = 'Update successful';
		} catch (Exception $e) {
			$ret->message =  $e->getMessage();
		} 
		
		
		return $ret;
	}
	
	private function saveUser($object){
		$ret = $this->load->model('response');
	
		try {
			$this->load->model('usersmodel');
			$data = $this->usersmodel->save( $this->requestToModel($object));
			$ret->success = true;
			$ret->data = $data;
			$ret->message = 'Save successful';
		} catch (Exception $e) {
			$ret->message =  $e->getMessage();
		}
	
	
		return $ret;
	}
	
	private function getAllRoles(){
		$this->load->model('rolesmodel');
		$data = $this->rolesmodel->getAll();
	
		$ret = $this->load->model('response');
		$ret->success = true;
		$ret->data = $data;
	
		return $ret;
	}
	
	private function getRoleById($componentId){
		$ret = $this->load->model('response');
	
		$this->load->model('rolesmodel');
		$data = $this->rolesmodel->getById($componentId);
		$ret->success = true;
		$ret->data = $data;
	
		return $ret;
	}

	
	private function updateRole($componentId, $object){
		$ret = $this->load->model('response');
	
		try {
			$this->load->model('rolesmodel');
			$data = $this->rolesmodel->update($componentId, $this->requestToModel($object));
			$ret->success = true;
			$ret->data = $data;
			$ret->message = 'Update successful';
		} catch (Exception $e) {
			$ret->message =  $e->getMessage();
		}
	
	
		return $ret;
	}
	
	private function saveRole($object){
		$ret = $this->load->model('response');
	
		try {
			$this->load->model('rolesmodel');
			$data = $this->rolesmodel->save( $this->requestToModel($object));
			$ret->success = true;
			$ret->data = $data;
			$ret->message = 'Save successful';
		} catch (Exception $e) {
			$ret->message =  $e->getMessage();
		}
	
	
		return $ret;
	}
	
	private function getAllFeatures(){
		$this->load->model('functioncodesmodel');
		$data = $this->functioncodesmodel->getAll();
	
		$ret = $this->load->model('response');
		$ret->success = true;
		$ret->data = $data;
	
		return $ret;
	}
	
	private function getFeatureById($componentId){
		$ret = $this->load->model('response');
	
		$this->load->model('functioncodesmodel');
		$data = $this->functioncodesmodel->getById($componentId);
		$ret->success = true;
		$ret->data = $data;
	
		return $ret;
	}
	
	
	private function updateFeature($componentId, $object){
		$ret = $this->load->model('response');
	
		try {
			$this->load->model('functioncodesmodel');
			$data = $this->functioncodesmodel->update($componentId, $this->requestToModel($object));
			$ret->success = true;
			$ret->data = $data;
			$ret->message = 'Update successful';
		} catch (Exception $e) {
			$ret->message =  $e->getMessage();
		}
	
	
		return $ret;
	}
	
	private function saveFeature($object){
		$ret = $this->load->model('response');
	
		try {
			$this->load->model('functioncodesmodel');
			$data = $this->functioncodesmodel->save( $this->requestToModel($object));
			$ret->success = true;
			$ret->data = $data;
			$ret->message = 'Save successful';
		} catch (Exception $e) {
			$ret->message =  $e->getMessage();
		}
	
	
		return $ret;
	}
    
	
	private function getAllQuestionanswers(){
		$ret = $this->load->model('response');
		
		$this->load->model('questionanswermodel');
		$data = $this->questionanswermodel->getAll();
	
		$ret->success = true;
		$ret->data = $data;
	
		return $ret;
	}
	
	private function getQuestionanswerById($componentId){
		$ret = $this->load->model('response');
	
		$this->load->model('questionanswermodel');
		$data = $this->questionanswermodel->getById($componentId);
		$ret->success = true;
		$ret->data = $data;
	
		return $ret;
	}
	
	
	private function updateQuestionanswer($componentId, $object){
		$ret = $this->load->model('response');
	
		try {
			$this->load->model('questionanswermodel');
			$data = $this->questionanswermodel->update($componentId, $this->requestToModel($object));
			$ret->success = true;
			$ret->data = $data;
			$ret->message = 'Update successful';
		} catch (Exception $e) {
			$ret->message =  $e->getMessage();
		}
	
	
		return $ret;
	}
	
	private function saveQuestionanswer($object){
		$ret = $this->load->model('response');
	
		try {
			$this->load->model('questionanswermodel');
			$data = $this->questionanswermodel->save( $this->requestToModel($object));
			$ret->success = true;
			$ret->data = $data;
			$ret->message = 'Save successful';
		} catch (Exception $e) {
			$ret->message =  $e->getMessage();
		}
	
	
		return $ret;
	}
	
	private function deleteQuestionanswer($componentId){
		$ret = $this->load->model('response');
	
		try {
			$this->load->model('questionanswermodel');
			$data = $this->questionanswermodel->delete($componentId);
			$ret->success = true;
			$ret->data = $data;
			$ret->message = 'Update successful';
		} catch (Exception $e) {
			$ret->message =  $e->getMessage();
		}
	
	
		return $ret;
	}
	
	
	function mcq($param){
	
		$_POST = json_decode(file_get_contents('php://input'), true);
		$request = $this->input->post();
		$operation = $request['operation'];
		if($param == 'post'){
			if($operation=='GetAll'){
				$r = $this->getAllMcqs();
				echo json_encode($r);
			}else if($operation == 'GetById'){
				$componentId = $request['componentId'];
	
				$r = $this->getMcqById($componentId);
				echo json_encode($r);
			}else if($operation == 'Update'){
				$componentId = $request['componentId'];
	
				$r = $this->updateMcq($componentId, $request);
				echo json_encode($r);
			}else if($operation == 'Save'){
	
				$r = $this->saveMcq($request);
				echo json_encode($r);
			}else if($operation == 'Delete'){
				$componentId = $request['componentId'];
	
				$r = $this->deleteMcq($componentId);
				echo json_encode($r);
			}
		}
	
	}
	
	private function getAllMcqs(){
		$ret = $this->load->model('response');
	
		$this->load->model('mcqmodel');
		$data = $this->mcqmodel->getAll();
	
		$ret->success = true;
		$ret->data = $data;
	
		return $ret;
	}
	
	private function getMcqById($componentId){
		$ret = $this->load->model('response');
	
		$this->load->model('mcqmodel');
		$data = $this->mcqmodel->getById($componentId);
		$ret->success = true;
		$ret->data = $data;
	
		return $ret;
	}
	
	
	private function updateMcq($componentId, $object){
		$ret = $this->load->model('response');
	
		try {
			$this->load->model('mcqmodel');
			$data = $this->mcqmodel->updateMcq($componentId, $this->requestToModel($object));
			$ret->success = true;
			$ret->data = $data;
			$ret->message = 'Update successful';
		} catch (Exception $e) {
			$ret->message =  $e->getMessage();
		}
	
	
		return $ret;
	}
	
	private function saveMcq($object){
		$ret = $this->load->model('response');
	
		try {
			$this->load->model('mcqmodel');
			$data = $this->mcqmodel->saveMcq( $this->requestToModel($object));
			$ret->success = true;
			$ret->data = $data;
			$ret->message = 'Save successful';
		} catch (Exception $e) {
			$ret->message =  $e->getMessage();
		}
	
	
		return $ret;
	}
	
	private function deleteMcq($componentId){
		$ret = $this->load->model('response');
	
		try {
			$this->load->model('mcqmodel');
			$data = $this->mcqmodel->delete($componentId);
			$ret->success = true;
			$ret->data = $data;
			$ret->message = 'Update successful';
		} catch (Exception $e) {
			$ret->message =  $e->getMessage();
		}
	
	
		return $ret;
	}
	
	function menu($param){
	
		$_POST = json_decode(file_get_contents('php://input'), true);
		$request = $this->input->post();
		$operation = $request['operation'];
		if($param == 'post'){
			if($operation=='GetAll'){
				$r = $this->getAllMenus();
				echo json_encode($r);
			}else if($operation == 'GetById'){
				$componentId = $request['componentId'];
	
				$r = $this->getMenuById($componentId);
				echo json_encode($r);
			}else if($operation == 'Update'){
				$componentId = $request['componentId'];
	
				$r = $this->updateMenu($componentId, $request);
				echo json_encode($r);
			}else if($operation == 'Save'){
	
				$r = $this->saveMenu($request);
				echo json_encode($r);
			}else if($operation == 'Delete'){
				$componentId = $request['componentId'];
	
				$r = $this->deleteMenu($componentId);
				echo json_encode($r);
			}
		}
	
	}
	
	private function getAllMenus(){
		$ret = $this->load->model('response');
	
		$this->load->model('menumodel');
		$data = $this->menumodel->getAll();
	
		$ret->success = true;
		$ret->data = $data;
	
		return $ret;
	}
	
	private function getMenuById($componentId){
		$ret = $this->load->model('response');
	
		$this->load->model('menumodel');
		$data = $this->menumodel->getById($componentId);
		$ret->success = true;
		$ret->data = $data;
	
		return $ret;
	}
	
	
	private function updateMenu($componentId, $object){
		$ret = $this->load->model('response');
	
		try {
			$this->load->model('menumodel');
			$data = $this->menumodel->update($componentId, $this->requestToModel($object));
			$ret->success = true;
			$ret->data = $data;
			$ret->message = 'Update successful';
		} catch (Exception $e) {
			$ret->message =  $e->getMessage();
		}
	
	
		return $ret;
	}
	
	private function saveMenu($object){
		$ret = $this->load->model('response');
	
		try {
			$this->load->model('menumodel');
			$data = $this->menumodel->save( $this->requestToModel($object));
			$ret->success = true;
			$ret->data = $data;
			$ret->message = 'Save successful';
		} catch (Exception $e) {
			$ret->message =  $e->getMessage();
		}
	
	
		return $ret;
	}
	
	private function deleteMenu($componentId){
		$ret = $this->load->model('response');
	
		try {
			$this->load->model('menumodel');
			$data = $this->menumodel->delete($componentId);
			$ret->success = true;
			$ret->data = $data;
			$ret->message = 'Update successful';
		} catch (Exception $e) {
			$ret->message =  $e->getMessage();
		}
	
	
		return $ret;
	}
	
	
	function transaction($param){
	
		$_POST = json_decode(file_get_contents('php://input'), true);
		$request = $this->input->post();
		$operation = $request['operation'];
		if($param == 'post'){
			if($operation=='GetAll'){
				$r = $this->getAllTransactions();
				echo json_encode($r);
			}else if($operation == 'GetById'){
				$componentId = $request['componentId'];
	
				$r = $this->getTransactionById($componentId);
				echo json_encode($r);
			}else if($operation == 'Update'){
				$componentId = $request['componentId'];
	
				$r = $this->updateTransaction($componentId, $request);
				echo json_encode($r);
			}else if($operation == 'Save'){
	
				$r = $this->saveTransaction($request);
				echo json_encode($r);
			}else if($operation == 'Delete'){
				$componentId = $request['componentId'];
	
				$r = $this->deleteTransaction($componentId);
				echo json_encode($r);
			}
		}
	
	}
	
	private function getAllTransactions(){
		$ret = $this->load->model('response');
	
		$this->load->model('transactionmodel');
		$data = $this->transactionmodel->getAll();
	
		$ret->success = true;
		$ret->data = $data;
	
		return $ret;
	}
	
	private function getTransactionById($componentId){
		$ret = $this->load->model('response');
	
		$this->load->model('transactionmodel');
		$data = $this->transactionmodel->getById($componentId);
		$ret->success = true;
		$ret->data = $data;
	
		return $ret;
	}
	
	
	private function updateTransaction($componentId, $object){
		$ret = $this->load->model('response');
	
		try {
			$this->load->model('transactionmodel');
			$data = $this->transactionmodel->update($componentId, $this->requestToModel($object));
			$ret->success = true;
			$ret->data = $data;
			$ret->message = 'Update successful';
		} catch (Exception $e) {
			$ret->message =  $e->getMessage();
		}
	
	
		return $ret;
	}
	
	private function saveTransaction($object){
		$ret = $this->load->model('response');
	
		try {
			$this->load->model('transactionmodel');
			$data = $this->transactionmodel->save( $this->requestToModel($object));
			$ret->success = true;
			$ret->data = $data;
			$ret->message = 'Save successful';
		} catch (Exception $e) {
			$ret->message =  $e->getMessage();
		}
	
	
		return $ret;
	}
	
	private function deleteTransaction($componentId){
		$ret = $this->load->model('response');
	
		try {
			$this->load->model('transactionmodel');
			$data = $this->transactionmodel->delete($componentId);
			$ret->success = true;
			$ret->data = $data;
			$ret->message = 'Update successful';
		} catch (Exception $e) {
			$ret->message =  $e->getMessage();
		}
	
	
		return $ret;
	}
	
	
}
