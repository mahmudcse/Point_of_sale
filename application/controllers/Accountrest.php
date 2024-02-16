<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*	
 *	@author : Sharif Uddin
 *	date	: April 01, 2016
 */ 
 /* test comment for git */  

class Accountrest extends MY_RestController
{
    function __construct()
    {
        parent::__construct();
        
        $this->load->model('accountmodel');
        $this->model = $this->accountmodel;
    }

	
}
