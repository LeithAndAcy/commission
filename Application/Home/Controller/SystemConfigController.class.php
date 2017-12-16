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
        if(_authCheck()){
            $this -> display('SystemConfigPage');
        }else{
            $this -> error("权限不足");
        }
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
		$current_pwd = $_POST['currentPWD'];
		$user_name = $_SESSION['user_name'];
		$new_pwd = $_POST['newPWD'];
		if($this -> db_login ->updatePWD($current_pwd,$new_pwd)){
			$this -> success("修改成功");
		}else{
			$this -> error("当前密码错误");
		}
		
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
		$new_user_power = $_POST['power'];
		$this -> db_login -> addNewUser($new_user_name,$new_user_pwd,$new_user_power);
		$this ->loadSystemConfigPage();
	}
}
?>