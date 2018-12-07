<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*	
 *	@author : Sharif Uddin
 *	date	: April 01, 2016
 */ 
 /* test comment for git */  

class Home extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
		
    }
    
    /***default functin, redirects to login page if no admin logged in yet***/
    public function index()
    {
        //commonTasks();
        redirect(base_url() . 'index.php/admin/dashboard', 'refresh');
    }
    
    public function dashboard()
    {
    	
    	$data = $this->commonTasks();
    	$data['page_title'] = 'Dashboard';
    	$data['page_name'] = 'home';
    	 // Capitalize the first letter
    	//return;
		$this->load->view('home/dashboard/index', $data);
    }
    public function accessdenied(){

        $data = $this->commonTasks();
        $data['page_title'] = 'AccessDenied';
        $data['page_name'] = 'home';
        

        $this->load->view('urlvalidation/index',$data);
        
    }

    

    
}
