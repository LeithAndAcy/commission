<?php
namespace Home\Controller;
use Think\Controller;
class SearchDataController extends Controller {
		
	private $db_salary;
	private $db_salesman;
	private $db_contact_main;
	private $db_contact_detail;
	private $db_customer;
	private $db_U8;
	
	function _initialize() {
		if (!_checkLogin()) {
			$this->error('登陆超时,请重新登陆。','/commission',2);
			exit;
		}
		$this -> db_salary = D("Salary");
		$this -> db_salesman = D("Salesman");
		$this -> db_contact_main = D("ContactMain");
		$this -> db_contact_detail = D("ContactDetail");
		$this -> db_customer = D("Customer");
	}
    public function loadSearchDataPage(){
    	$this -> display('SearchDataPage');
		
	}
	public function searchSettledContact(){
		$business_percent = A('BusinessPercent');
		$business_percent -> loadSettledContactPage();
	}
	public function loadManualSettledContactPage(){
		$this -> db_U8 = D("U8");
		$manual_settled_contact = $this -> db_contact_main -> getManualSettledContact();
		$manual_settled_contact_detail = $this -> db_contact_detail -> getContactDetail($manual_settled_contact);
		$manual_settled_contact_detail = $this -> db_salesman -> addSalesmanName($manual_settled_contact_detail);
		$manual_settled_contact_detail = $this -> db_customer -> addCustomerName($manual_settled_contact_detail);
		$manual_settled_contact_detail = $this -> db_U8 -> getInventoryDetail($manual_settled_contact_detail);
		$this -> assign("manual_settled_contact_detail",$manual_settled_contact_detail);
		$this -> display('ManualSettledContactPage');
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