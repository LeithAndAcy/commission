<?php
namespace Home\Controller;
use Think\Controller;
class SearchDataController extends Controller {
	function _initialize() {
		if (!_checkLogin()) {
			$this->error('登陆超时,请重新登陆。','/commission',2);
			exit;
		}
	}
    public function loadSearchDataPage(){
    	$this -> display('SearchDataPage');
		
	}
	public function CaculatBusinessAndProfit(){
		$this -> display('BusinessAndProfitPage');
	}
	public function SearchBusinessData(){
		$this -> display('BusinessDataPage');
	}
}
?>