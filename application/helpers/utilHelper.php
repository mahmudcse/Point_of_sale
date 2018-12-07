<?php
function asset_url(){
	return base_url().'assets/';
}

if ( !function_exists('sessionRead'))
{
	function sessionRead($var)
	{
		if (isset($_SESSION[$var]))
		{
			return $_SESSION[$var];
		}
		else
		{
			return false;
		}
	}
}

if ( !function_exists('sessionWrite'))
{
	function sessionWrite($var, $val)
	{
		$_SESSION[$var]=$val;
	}
}

if ( !function_exists('startsWith'))
	{
	function startsWith($haystack, $needle) {
		// search backwards starting from haystack length characters from the end
		return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== FALSE;
	}
}



if ( !function_exists('monthArray'))
{
	function monthArray() {
		$monthOptions = array(
			1 => 'Jan', 2 => 'Feb',3 => 'Mar', 4 => 'Apr',5 => 'May', 6 => 'Jun',7 => 'Jul', 8 => 'Aug',9 => 'Sep', 10 => 'Oct',11 => 'Nov', 12 => 'Dec'
				
		);
		return $monthOptions;
	}
}

?>