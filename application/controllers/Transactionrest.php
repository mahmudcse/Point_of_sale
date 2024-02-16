<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*	
 *	@author : Sharif Uddin
 *	date	: April 01, 2016
 */ 
 /* test comment for git */  

class Transactionrest extends MY_RestController
{
    function __construct()
    {
        parent::__construct();
        
        $this->load->model('transactionmodel');
        $this->model = $this->transactionmodel;
    }
    
    protected function updateAuditInfo($object){
    	$object['transaction']['createdby'] =  $this->session->userdata('userid');
    	$object['transaction']['createddate'] = date("Y-m-d h:i:s");
    	return $object;
    }

	
}
