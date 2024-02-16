<?php 
class Crudmodel extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
    /*
 	function getAll($table)
 	{
 		
 		$q = $this->db->get($table);
 		if($q->num_rows() > 0)
 		{
 			return $q->result();
 		}
 		
 		return array();
 	}
 	*/
    
    function getFields($objectId){
    	$fields = array();
    	$fq = $this->db->get_where('objectfield',['objectId'=>$objectId]);
    	if($fq->num_rows() > 0)
    		$fields = $fq->result();
    	return $fields;
    }
 	function getObjectByName($objectName)
 	{
 		$object = array();
 		
 		$oq = $this->db->get_where('object',['uniqueCode'=>$objectName]);
 		if($oq->num_rows() > 0)
 		{
 			$obs = $oq->result()[0];
 			$object['name'] = $obs->uniqueCode;
 			$object['componentId'] = $obs->componentId;
 			$object['fields'] = $this->getFields($obs->componentId);
 			
 		}
 		
 		return $object;
 	}
 	
 	function getObjectById($objectId)
 	{
 		$object = array();
 			
 		$oq = $this->db->get_where('object',['componentId'=>$objectId]);
 		if($oq->num_rows() > 0)
 		{
 			$obs = $oq->result()[0];
 			$object['name'] = $obs->uniqueCode;
 			$object['componentId'] = $obs->componentId;
 	
 			$object['fields'] = $this->getFields($obs->componentId);
 		}
 			
 		return $object;
 	}
 	
 	function getObjects()
 	{
 		$objects = array();
 	
 		$oq = $this->db->get('object');
 		if($oq->num_rows() > 0)
 		{
 			$obs = $oq->result();
 			foreach ($obs as $ob){
 				$object = array(
 					'name' => $ob->uniqueCode,
 					'componentId' => $ob->componentId,
 					'fields' => $this->getFields($ob->componentId)
 				);
 				array_push($objects, $object);
 			}
 		}
 	
 		return $objects;
 	}
 	
 	function saveObject($object, $fields){
 
 		$this->db->trans_start();
 		
		$this->db->insert('object', $object);
		$insert_id = $this->db->insert_id();
		
		foreach ($fields as $field){
			$field['objectId'] = $insert_id;
			$this->db->insert('objectfield', $field);
		}
		
		$this->db->trans_complete();
		return true;
 	}
 	/*
 	function saveValues($object, $data){
 		$headers = $this->getHeader($object);
 		$headerType = array();
 		$finalData = array();
 		foreach ($headers as $header){
 				if($header->datatype == 'INTEGER'){
	 				$finalData['integerValue'] = $data[];
	 			}else if($header->datatype == 'SMALL_STRING'){
	 				$records[$datum->recordId][$datum->fieldName] = $datum->smallStringValue;
	 			}else if($header->datatype == 'LONG_STRING'){
	 				$records[$datum->recordId][$datum->fieldName] = $datum->longStringValue;
	 			}else if($header->datatype == 'FLOAT'){
	 				$records[$datum->recordId][$datum->fieldName] = $datum->floatValue;
	 			}else if($header->datatype == 'DATE'){
	 				$records[$datum->recordId][$datum->fieldName] = $datum->dateValue;
	 			}
 		}
 		
 		
 		
 	}
 	
 	function get( $where)
 	{
 		$dq = $this->db->get_where($object, $where);
 		$records = array();
 		if($dq->num_rows() > 0)
 		{
	 		$data = $dq->result();
	 		foreach($data as $datum){
	 			if($datum->datatype == 'INTEGER'){
	 				$records[$datum->recordId][$datum->fieldName] = $datum->integerValue;
	 				//$records[$datum->recordId][$datum->fieldId] = $datum->integerValue;
	 			}else if($datum->datatype == 'SMALL_STRING'){
	 				$records[$datum->recordId][$datum->fieldName] = $datum->smallStringValue;
	 			}else if($datum->datatype == 'LONG_STRING'){
	 				$records[$datum->recordId][$datum->fieldName] = $datum->longStringValue;
	 			}else if($datum->datatype == 'FLOAT'){
	 				$records[$datum->recordId][$datum->fieldName] = $datum->floatValue;
	 			}else if($datum->datatype == 'DATE'){
	 				$records[$datum->recordId][$datum->fieldName] = $datum->dateValue;
	 			}
	 		}
 		}
 			 
 		return $records;
 			
 	}
	*/
}