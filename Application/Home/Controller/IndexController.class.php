<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    	
    private $db_login;
	
   	function _initialize() {
		
		$this -> db_login = D("Login");
	}
    public function index(){
    	$this -> display("index");
		
	}
	public function checkLogin(){
		$user_name = $_POST['user_name'];
		$user_pwd = $_POST['user_pwd'];
		if($user_name == null || $user_pwd == null){
			$data['status'] = 'failure';
			$data['content'] = '账号或密码不能为空';
			$this -> ajaxReturn($data);
			exit;
		}
		$pwd = md5($user_pwd);
		$pwd_md5 = $this -> db_login -> getPWDByUserName($user_name);
		if($pwd == $pwd_md5){
			//验证成功
			$_SESSION['user_name'] = $user_name;
			$data['status'] = 'success';
			$this -> ajaxReturn($data);
		}else{
			$data['status'] = 'failure';
			$data['content'] = '账号或者密码错误';
			$this -> ajaxReturn($data);
		}
		
	}
	
	public function logout(){
		//退出，清空session
		session_destroy();
		// setcookie("REQUEST_URI", '', 0);
		// exit('<script>window.parent.location.href="' . __ROOT__ . '";</script>');
	}
	
	public function loadHomePage(){
		$this-> display("Public:basePage");
	}
}
?>