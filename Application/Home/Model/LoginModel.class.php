<?php
namespace Home\Model;
use Think\Model;
class LoginModel extends Model {
	
	public function getPWDByUserName($user_name){
		$condition = array();
		$condition['name'] = $user_name;
		$res = $this->where($condition)->find();
		$_SESSION['admin'] = $res['admin'];
		return $res['pwd'];
	}
	public function updatePWD($current_pwd,$new_pwd){
		$condition = array();
		$condition['name'] = session('user_name');
		$md5_current_pwd = md5($current_pwd);
		$res = $this -> where($condition) -> getField('pwd');
		if($md5_current_pwd == $res){
			$data['pwd'] = md5($new_pwd);
	 		$this -> where($condition)->save($data);
			return TRUE;
		}else{
			return FALSE;
		}
		
	}
	public function checkDuplicateName($user_name){
		$condition = array();
		$condition['name'] = $user_name;
		$res = $this-> where($condition)->find();
		if($res){
			return false;  //名字重复，新名字不能用
		}else{
			return true; //名字没重复，新名字能用	
		}
	}
	public function addNewUser($user_name,$user_pwd,$power){
		$data = array();
		$data['name'] = $user_name;
		$data['pwd'] = md5($user_pwd);
		$data['admin'] = $power;
		$this -> add($data);
	}
}
?>