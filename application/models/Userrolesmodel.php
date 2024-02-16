<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Userrolesmodel extends MY_Model {
	
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        $this->tableName = 'userroles';
    }
    
    private function getUserRolesByUserId($userId){
    	return $this->db->get_where($this->tableName, ['userId'=>$userId])->result();
    }
    
    public function getUserroles($userId){
    	$ret = $this->getUserRolesByUserId($userId);
    	
    	$roles = array();
    	$this->load->model('rolesmodel');
    	foreach ($ret as $r){
    		array_push($roles, $this->rolesmodel->getById($r->roleId));
    	}
    	return $roles;
    }
    
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
    }

}
?>