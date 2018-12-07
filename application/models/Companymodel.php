<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Companymodel extends MY_Model {
	
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        $this->tableName = 'company';
    }

}
?>
