<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Functionrolesmodel extends MY_Model {
	
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        $this->tableName = 'functionroles';
    }
    
    private function getFunctionsByRole($roleId){
    	$ret = $this->db->get_where($this->tableName, ['roleId'=>$roleId])->result();
    	return $ret;
    }
    
    public function getRolefunctions($roleId){
    	$ret = $this->getFunctionsByRole($roleId);
    	$functions = array();
    	$this->load->model('functioncodesmodel');
    	foreach ($ret as $r){
   
    		array_push($functions, $this->functioncodesmodel->getById($r->functionId) );
    	}
    	return $functions;
    }
    
    public function assignFeatures($roleId, $featureIds){
    	$existings = $this->getFunctionsByRole($roleId);
    	$extArr = array();
    	foreach ($existings as $ext){
    		array_push($extArr, $ext->functionId);
    		if(in_array($ext->functionId, $featureIds)){
    			;
    		}else{
    			$this->delete($ext->componentId);
    		}
    	}
    	
    	foreach ($featureIds as $featureId){
    		if(in_array($featureId, $extArr)){
    			;
    		}else{
    			$this->save(['roleId'=>$roleId, 'functionId'=>$featureId, 'status'=>1, 'version'=>1]);
    		}
    		
    	}
    	
    	return [];
    }

}
?>