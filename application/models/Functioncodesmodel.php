<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Functioncodesmodel extends MY_Model {
	
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        $this->tableName = 'functioncode';
    }
    
    public function getUserMenu($userId){
    	$menus = $this->db->get_where('vuserfunctions',['isMenu'=>1, 'userId'=>$userId])->result();
    	
    	$ret = array();
    	
    	foreach ($menus as $menu){
    		$m = (array)$menu;
    		$m['child'] = array();
    		$m = (object)$m;
    		if(isset($ret[$m->parentMenuId])==false)
    			$ret[$m->parentMenuId] = array();
    		array_push($ret[$m->parentMenuId], $m);
    	}
    	
    	$final = array();
    	 foreach ($ret[0] as $r){
    	 	
    	 	if(isset($ret[$r->componentId])){
    	 		$r->child = $ret[$r->componentId];
    	 	}
    	 	array_push($final,$r);
    	 	
    	 }
    	
    
    	return $final;
    }

}
?>