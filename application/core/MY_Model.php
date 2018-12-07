<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Model extends CI_Model {
	var $tableName = 'users';
	var $componentId = -1;
    var $uniqueCode = '';
    var $version    = 0;
    var $status   = 0;
    var $createdBy   = 0;
    var $createdDate   = '2017-01-01';
    var $updatedBy   = 0;
    var $updatedDate   = '2017-01-01';
    
    function __construct()
    {
    	// Call the Model constructor
    	parent::__construct();
    }
    
    public function getAll(){
    	return $this->db->get($this->tableName)->result();
    }
    
    public function getById($id){
    
    	$ret = $this->getByFilter(['componentId'=>$id]);
    	if(count($ret) > 0 )
    		$ret = $ret[0];
    	return $ret;
    	
    	return $this->getByFilter(['componentId'=>$id]);
    }
    
    public function getByFilter($filter){
    	$ret = $this->db->get_where($this->tableName, $filter)->result();
    	return $ret;
    }
    
    public function update($id, $data){
    
    	$this->db->where('componentId', $id);
    	return $this->db->update($this->tableName, $data);
    
    }
    
    public function delete($id){
    	$this->db->where('componentId', $id);
    	return $this->db->delete($this->tableName);
    
    }
    
    public function save($data){
    
    	$this->db->insert($this->tableName, $data);
    	
    	return $this->db->insert_id();
    
    }
    
}?>