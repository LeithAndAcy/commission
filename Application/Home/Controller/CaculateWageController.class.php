<?php
namespace Home\Controller;
use Think\Controller;
class CaculateWageController extends Controller {
		
	function _initialize() {
		if (!_checkLogin()) {
			$this->error('登陆超时,请重新登陆。','/commission',2);
			exit;
		}
	}
	
    public function loadCaculateWagePage(){
    	$this -> display('CaculateWagePage');
	}
	
	public function LoadPayrollPage(){
		$this -> display('PayrollPage');
	}
	
	public function LoadShanghaiSalaryPage(){
		$this -> display('ShanghaiSalaryPage');
	}
	public function LoadKunshanSalaryPage(){
		$this -> display('KunshanSalaryPage');
	}
	public function LoadIncidentalFeePage(){
		$this -> display('IncidentalFeePage');
	}
}
?>