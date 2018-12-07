<?php
class Login extends CI_Controller {
	
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		/*cache control*/
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
		$this->output->set_header('Pragma: no-cache');
	}

	public function index(){
		if($this->session->userdata('username')!=FALSE){
			redirect(base_url() . 'index.php/home/dashboard', 'refresh');
		}
		$data['title'] = ucfirst('login'); // Capitalize the first letter
		$data['errormsg'] = '';
		$this->load->view('login.php', $data);
	}
	
	public function four_zero_four(){
		$data['title'] = '404';
		$this->load->view('404.php', $data);
	}
	
	public function authenticate(){
	
		$data['title'] = ucfirst('login'); // Capitalize the first letter
		$data['errormsg'] = '';
		
		$password = $this->input->post('password');
		$username = $this->input->post('username');
		$ret = $this->login($username, $password);
		
		if($ret==false){
			$data['title'] = ucfirst('login'); // Capitalize the first letter
			$data['errormsg'] = 'Invalid userid / password.';
			$this->load->view('login.php', $data);
		}else{
			redirect(base_url() . 'index.php/home/dashboard', 'refresh');
		}
	}
	
	private function login($username, $password)
	{
		$ret = $this->validateUser($username, $password);
	
		
		
		if(isset($ret))
		{
			$this->session->set_userdata('username', $username);
			$this->session->set_userdata('userid', $ret->componentId);
	
			return true;
		}
	
		return false;
	}
	
	public function logout(){
		
		$this->session->sess_destroy();

		redirect(base_url() . 'index.php/login', 'refresh');
	}
	
	private function validateUser($username, $password)
	{
		$query = $this->db->query("SELECT * FROM user WHERE uniqueCode = '$username'");
		
		foreach ($query->result() as $row)
		{
			if($row->password == md5($password)){
		    	return $row;
			}
		}
		
		return null;
	}
	
}