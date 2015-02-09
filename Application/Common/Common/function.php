<?php
// define data

function _checkLogin()
	{
		// setcookie("REQUEST_URI", $_SERVER['REQUEST_URI'], time()+3600);
		if($_SESSION['user_name'] )
		{
			return true;
		}
		else
		{
			return false;
		}
	}	
?>

