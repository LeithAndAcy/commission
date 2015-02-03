<?php
namespace Home\Controller;
use Think\Controller;
class SearchDataController extends Controller {
		
	private $db_salary;
	private $db_salesman;
	
	function _initialize() {
		if (!_checkLogin()) {
			$this->error('登陆超时,请重新登陆。','/commission',2);
			exit;
		}
		$this -> db_salary = D("Salary");
		$this -> db_salesman = D("Salesman");
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
		$payroll = $this -> db_salary -> getAllShanghaiSalary();
		$payroll = $this -> db_salesman -> addSalesmanName($payroll);
		foreach ($payroll as $key => $value) {
			$payroll[$key]['total'] = $value['shanghai_salary'] + $value['kunshan_salary'] + $value['bogus']; 
		}
		$this -> assign("payroll",$payroll);
		$this -> display('ShanghaiSalaryPage');
	}
	public function searchKunshanSalary(){
		$payroll = $this -> db_salary -> getAllKunshanSalary();
		$payroll = $this -> db_salesman -> addSalesmanName($payroll);
		foreach ($payroll as $key => $value) {
			$payroll[$key]['total'] = $value['shanghai_salary'] + $value['kunshan_salary'] + $value['bogus']; 
		}
		$this -> assign("payroll",$payroll);
		$this -> display('KunshanSalaryPage');
	}
	public function searchIncidentalFee(){
		$payroll = $this -> db_salary -> getAllSalary();
		$payroll = $this -> db_salesman -> addSalesmanName($payroll);
		$this -> assign("payroll",$payroll);
		$this -> display('IncidentalFeePage');
	}
	public function searchTotalSalary(){
		$payroll = $this -> db_salary -> getAllSalary();
		$payroll = $this -> db_salesman -> addSalesmanName($payroll);
		foreach ($payroll as $key => $value) {
			$payroll[$key]['total'] = $value['shanghai_salary'] + $value['kunshan_salary'] + $value['bogus']; 
		}
		$this -> assign("payroll",$payroll);
		$this -> display('TotalSalaryPage');
	}
	public function searchSalaryDetail(){
		$payroll = $this -> db_salary -> getAllSalary();
		$payroll = $this -> db_salesman -> addSalesmanName($payroll);
		foreach ($payroll as $key => $value) {
			$payroll[$key]['total'] = $value['shanghai_salary'] + $value['kunshan_salary'] + $value['bogus']; 
		}
		$this -> assign("payroll",$payroll);
		$this -> display('SalaryDetailPage');
	}
}
?>