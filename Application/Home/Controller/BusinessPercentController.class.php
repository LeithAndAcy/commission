<?php
namespace Home\Controller;
use Think\Controller;
class BusinessPercentController extends Controller {
	function _initialize() {
		if (!_checkLogin()) {
			$this->error('登陆超时,请重新登陆。','/commission',2);
			exit;
		}
	}
    public function loadBusinessPercentPage(){
    	$this -> display('BusinessPercentPage');
		
	}
	
	public function CaculateNormalBusiness(){
		$this -> display('NormalBusinessPage');
	}
	public function CaculateSpecialBusiness(){
		$this -> display('SpecialBusinessPage');
	}
	public function CaculateNormalProfit(){
		$this -> display('NormalProfitPage');
	}
	public function CaculateSpecialProfit(){
		$this -> display('SpecialProfitPage');
	}
	
}
?>