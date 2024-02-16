<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Customermodel extends MY_Model {

	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
		$this->tableName = 'customer';
	}


}
?>
