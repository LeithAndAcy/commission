<?php
// define data

function _checkLogin()
	{
		// setcookie("REQUEST_URI", $_SERVER['REQUEST_URI'], time()+3600);
		if($_SESSION['user_name'])
		{
			return true;
		}
		else
		{
			return false;
		}
	}	
	
function _getLastMonth(){
	$last_month = date("Y-m",strtotime("-1 month"));
	date_default_timezone_set('Asia/Shanghai');
	$first_day_of_month = date('Y-m',time()) . '-01 00:00:01';
	$t = strtotime($first_day_of_month);
	$last_month = date("Y-m",strtotime('- 1 month',$t));
	return $last_month;
}
function _authCheck(){
	if($_SESSION['user_name'] == "admin"){
		return true;
	}else{
		return false;
	}
}
?>

