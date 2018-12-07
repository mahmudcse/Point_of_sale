<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Userwarehousemodel extends MY_Model {
	
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        $this->tableName = 'userwarehouse';
    }
    
    
    public function getUserrWarehouses($userId){
    	$ret = $this->getByFilter(['userId'=>$userId]);;
    	
    	$warehouses = array();
    	$this->load->model('warehousemodel');
    	foreach ($ret as $r){
    		array_push($warehouses, $this->warehousemodel->getById($r->warehouseId));
    	}
    	return $warehouses;
    }
    /*
    public function assignRoles($user, $roles){
    	$existing  = $this->getUserRolesByUserId($user);
    	
    	foreach ($existing as $ext){
    		if(in_array($ext->roleId, $roles)){
    			;
    		}else{
    			
    			$this->delete($ext->componentId);
    		}
    	}
    	$d2s = array('userId'=>$user, 'roleId'=>-1);
    	foreach ($roles as $role){
    		$found = false;
    		$d2s['roleId'] = $role;
    		foreach ($existing as $ext){
    			if($ext->roleId == $role){
    				$found = true;
    				break;
    			}
    		}
    		if($found == false){
    			
    			$this->save($d2s);
    		}
    	}
    	return [];
    }*/

}
?>