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
		$count_manual_settled_count = $this -> db_contact_main -> countManualSettledContact();
		$Page = new \Think\Page($count_manual_settled_count,1000);
		$show = $Page->show();// 分页显示输出
		$manual_settled_contact = $this -> db_contact_main -> getManualSettledContact($Page);
		$manual_settled_contact_detail = $this -> db_contact_detail -> getContactDetail($manual_settled_contact);
		$manual_settled_contact_detail = $this -> db_salesman -> addSalesmanName($manual_settled_contact_detail);
		$manual_settled_contact_detail = $this -> db_customer -> addCustomerName($manual_settled_contact_detail);
		$manual_settled_contact_detail = $this -> db_U8 -> getInventoryDetail($manual_settled_contact_detail);
		$this -> assign('page',$show);
		$this -> assign("manual_settled_contact_detail",$manual_settled_contact_detail);
		$this -> display('ManualSettledContactPage');
	}
	public function loadEditedSettledContactPage(){
		$this -> db_U8 = D("U8");
		$count_edited_settled_contact = $this -> db_contact_main ->countEditedSettledContact();
		$Page = new \Think\Page($count_edited_settled_contact,1000);
		$show = $Page->show();// 分页显示输出
		$edited_settled_contact = $this -> db_contact_main -> getEditedSettledContact($Page);
		$edited_settled_contact_detail = $this -> db_contact_detail -> getContactDetail($edited_settled_contact);
		$edited_settled_contact_detail = $this -> db_salesman -> addSalesmanName($edited_settled_contact_detail);
		$edited_settled_contact_detail = $this -> db_customer -> addCustomerName($edited_settled_contact_detail);
		$edited_settled_contact_detail = $this -> db_U8 -> getInventoryDetail($edited_settled_contact_detail);
		$this -> assign('page',$show);
		$this -> assign("manual_settled_contact_detail",$edited_settled_contact_detail);
		$this -> display('EditedSettledContactPage');
	}
	public function searchCommissionBusiness(){
		$business_percent = A('BusinessPercent');
		$business_percent -> loadCommissionBuisnessPage();
	}
	
	public function searchShanghaiSalary(){
		$count_payroll = $this -> db_salary ->countAllShanghaiSalary();
		$Page = new \Think\Page($count_payroll,1000);
		$show = $Page->show();// 分页显示输出
		$payroll = $this -> db_salary -> getAllShanghaiSalary($Page);
		$payroll = $this -> db_salesman -> addSalesmanName($payroll);
		foreach ($payroll as $key => $value) {
			$payroll[$key]['total'] = $value['shanghai_salary'] + $value['kunshan_salary'] + $value['bogus']; 
		}
		$this -> assign("payroll",$payroll);
		$this -> assign('page',$show);
		$this -> display('ShanghaiSalaryPage');
	}
	public function searchKunshanSalary(){
		$count_payroll = $this -> db_salary ->countAllKunshanSalary();
		$Page = new \Think\Page($count_payroll,1000);
		$show = $Page->show();// 分页显示输出
		$payroll = $this -> db_salary -> getAllKunshanSalary($Page);
		$payroll = $this -> db_salesman -> addSalesmanName($payroll);
		foreach ($payroll as $key => $value) {
			$payroll[$key]['total'] = $value['shanghai_salary'] + $value['kunshan_salary'] + $value['bogus']; 
		}
		$this -> assign("payroll",$payroll);
		$this -> assign('page',$show);
		$this -> display('KunshanSalaryPage');
	}
	public function searchIncidentalFee(){
		$count_payroll = $this -> db_salary -> count();
		$Page = new \Think\Page($count_payroll,1000);
		$show = $Page->show();// 分页显示输出
		$payroll = $this -> db_salary -> getAllSalary($Page);
		$payroll = $this -> db_salesman -> addSalesmanName($payroll);
		$this -> assign("payroll",$payroll);
		$this -> assign('page',$show);
		$this -> display('IncidentalFeePage');
	}
	public function searchTotalSalary(){
		$count_payroll = $this -> db_salary -> count();
		$Page = new \Think\Page($count_payroll,1000);
		$show = $Page->show();// 分页显示输出
		$payroll = $this -> db_salary -> getAllSalary($Page);
		$payroll = $this -> db_salesman -> addSalesmanName($payroll);
		foreach ($payroll as $key => $value) {
			$payroll[$key]['total'] = $value['shanghai_salary'] + $value['kunshan_salary'] + $value['bogus']; 
		}
		$this -> assign("payroll",$payroll);
		$this -> assign('page',$show);
		$this -> display('TotalSalaryPage');
	}
	public function searchSalaryDetail(){
		$count_payroll = $this -> db_salary -> count();
		$Page = new \Think\Page($count_payroll,1000);
		$show = $Page->show();// 分页显示输出
		$payroll = $this -> db_salary -> getAllSalary($Page);
		$payroll = $this -> db_salesman -> addSalesmanName($payroll);
		foreach ($payroll as $key => $value) {
			$payroll[$key]['total'] = $value['shanghai_salary'] + $value['kunshan_salary'] + $value['bogus']; 
		}
		$this -> assign("payroll",$payroll);
		$this -> assign('page',$show);
		$this -> display('SalaryDetailPage');
	}
}
?>