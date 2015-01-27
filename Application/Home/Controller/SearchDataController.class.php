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
	public function searchSettledContact(){
		$business_percent = A('BusinessPercent');
		$business_percent -> loadSettledContactPage();
	}
	public function searchCommissionBusiness(){
		$business_percent = A('BusinessPercent');
		$business_percent -> loadCommissionBuisnessPage();
	}
	public function searchShanghaiSalary(){
		$this -> display('ShanghaiSalaryPage');
	}
	public function searchKunshanSalary(){
		$this -> display('KunshanSalaryPage');
	}
	public function searchIncidentalFee(){
		$this -> display('IncidentalFeePage');
	}
	public function searchTotalSalary(){
		$this -> display('TotalSalaryPage');
	}
	public function searchSalaryDetail(){
		$this -> display('SalaryDetailPage');
	}
}
?>