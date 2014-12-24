<?php
namespace Home\Controller;
use Think\Controller;
class SystemConfigController extends Controller {
		
	private $db_login;
	
	function _initialize() {
		if (!_checkLogin()) {
			$this->error('登陆超时,请重新登陆。','/commission',2);
			exit;
		}
		$this -> db_login = D("Login");
	}
    public function loadSystemConfigPage(){
    	$this -> display('SystemConfigPage');
		
	}
	
	public function checkCurrentPWD(){
		$user_name = session('user_name');
		$pwd_md5 = $this -> db_login->getPWDByUserName($user_name);
		$pwd_current = md5( $_GET['fieldValue']);
		if($pwd_current == $pwd_md5){
			$data = array('currentPWD',TRUE);
		}else{
			$data = array('currentPWD',FALSE);
		}
		$json_data = json_encode($data);
		echo $json_data;
	}
	
	public function changePWD(){
		$new_pwd = $_POST['newPWD'];
		$this -> db_login ->updatePWD($new_pwd);
		$this -> loadSystemConfigPage();
	}
	public function checkUserName(){
		$new_user_name = $_GET['fieldValue'];
		if($this -> db_login ->checkDuplicateName($new_user_name)){
			$data = array('newUserName',TRUE);
		}else{
			$data = array('newUserName',FALSE);
		}
		$json_data = json_encode($data);
		echo $json_data;
	}
	public function addUser(){
		
		$new_user_name = $_POST['newUserName'];
		$new_user_pwd = $_POST['newUserPWD'];
		$this -> db_login -> addNewUser($new_user_name,$new_user_pwd);
		$this ->loadSystemConfigPage();
	}
}
?>