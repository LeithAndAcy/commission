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
		$this -> assign("edited_settled_contact_detail",$edited_settled_contact_detail);
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
	//	$payroll = $this -> db_salesman -> addSalesmanName($payroll);
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
	//	$payroll = $this -> db_salesman -> addSalesmanName($payroll);
		foreach ($payroll as $key => $value) {
			$payroll[$key]['total'] = $value['shanghai_salary'] + $value['kunshan_salary'] + $value['bogus']; 
		}
		$this -> assign("payroll",$payroll);
		$this -> assign('page',$show);
		$this -> display('SalaryDetailPage');
	}
	
	public function complicateSearch(){
		$condition = array();
		$temp_array = array();
		session('search_settled_contact_data',$_POST);
		$condition['contact_id'] = $_POST['search_contact_id'];
		$condition['cSOCode'] = $_POST['search_cSOCode'];
		$condition['salesman_id'] = $_POST['search_salesman_id'];
		$condition['salesman_name'] = $_POST['search_salesman_name'];
		$condition['customer_id'] = $_POST['search_customer_id'];
		$condition['customer_name'] = $_POST['search_customer_name'];
		$condition['classification_id'] = $_POST['search_classification_id'];
		$condition['inventory_id'] = $_POST['search_inventory_id'];
		$condition['specification'] = $_POST['search_specification'];
		$condition['colour'] = $_POST['search_colour'];
		$type = $_POST['search_type'];
		foreach ($condition as $key => $value) {
			if($value == ""){
				unset($condition[$key]);
			}
		}
		$res = $this -> db_contact_detail -> searchByCondition($condition);
		$search_begin_date = $_POST['search_begin_date'];
		$search_end_date = $_POST['search_end_date'];
		if($res == null){
			$count_settled_contact_detail = $this -> db_contact_detail -> searchCountByDate($search_begin_date,$search_end_date);
			$count_settled_contact = $this -> db_contact_main -> searchCountByDate($search_begin_date,$search_end_date);
			$Page = new \Think\Page($count_settled_contact_detail,150000);
			$show = $Page->show();// 分页显示输出
			$res = $this -> db_contact_detail -> searchByDate($search_begin_date,$search_end_date,$Page,$type);
		}	
		foreach ($res as $key => $value) {
			if($search_begin_date != null && $search_end_date != null){
				if($value['settled_date'] < $search_begin_date || $value['settled_date']> $search_end_date){
					unset($res[$key]);
					continue;
				}
			}
			if($type == "settled"){
				$temp =  $this -> db_contact_main -> getSettledContactByContactId($value['contact_id']);
			}elseif($type == "manualSettled"){
				$temp =  $this -> db_contact_main -> getManualSettledContactByContactId($value['contact_id']);
			}elseif($type == "editedSettled"){
				$temp =  $this -> db_contact_main -> getEditedSettledContactByContactId($value['contact_id']);
			}
			if($temp == null && $type != "commission_business"){
				unset($res[$key]);
			}else{
				if(!in_array($value['contact_id'], $temp_array)){
					array_push($temp_array,$value['contact_id']);
				}
			}
		}
		if($type == "settled"){
			$count_settled_contact_detail = count($res);
			if($count_settled_contact == null){
				$count_settled_contact = count($temp_array);
			}
			$count_settled_contact = count($temp_array);
			$this -> assign('count_settled_contact',$count_settled_contact);
			$this -> assign('count_settled_contact_detail',$count_settled_contact_detail);
			$this -> assign("settled_contact_detail",$res);
			$this -> display('BusinessPercent:SettledContactPage');
		}elseif($type == "manualSettled"){
			$this -> assign('manual_settled_contact_detail',$res);
			$this -> display('ManualSettledContactPage');
		}elseif($type == "editedSettled"){
			$this -> assign("edited_settled_contact_detail",$res);
			$this -> display('EditedSettledContactPage');
		}elseif($type == "commission_business"){
			$this -> assign("contact_detail",$res);
			$this -> display('BusinessPercent:CommissionBusinessPage');
		}
		
	}
	
	public function salarySearch(){
		$condition = array();
		$condition['salesman_id'] = $_POST['search_salesman_id'];
		$condition['salesman_name'] = $_POST['search_salesman_name'];
		$condition['date'] = $_POST['search_date'];
		$type = $_POST['search_type'];
		if($type == "上海"){
			$condition['status'] = "上海";
		}elseif($type == "昆山"){
			$condition['status'] = "昆山";
		}
		foreach ($condition as $key => $value) {
			if($value == ""){
				unset($condition[$key]);
			}
		}
		$res = $this -> db_salary -> searchByCondition($condition);
		if($type == "上海"){
			foreach ($res as $key => $value) {
				$res[$key]['total'] = $value['shanghai_salary'] + $value['kunshan_salary'] + $value['bogus']; 
			}
			$this -> assign("payroll",$res);
			$this -> display('ShanghaiSalaryPage');
		}elseif($type == "昆山"){
			foreach ($res as $key => $value) {
				$res[$key]['total'] = $value['shanghai_salary'] + $value['kunshan_salary'] + $value['bogus']; 
			}
			$this -> assign("payroll",$res);
			$this -> display('KunshanSalaryPage');
		}elseif($type == "incidentalFee"){
			$this -> assign("payroll",$res);
			$this -> display('IncidentalFeePage');
		}elseif($type == "totalSalary"){
			foreach ($res as $key => $value) {
				$res[$key]['total'] = $value['shanghai_salary'] + $value['kunshan_salary'] + $value['bogus']; 
			}
			$this -> assign("payroll",$res);
			$this -> display('TotalSalaryPage');
		}elseif($type == "salaryDetail"){
			foreach ($res as $key => $value) {
				$res[$key]['total'] = $value['shanghai_salary'] + $value['kunshan_salary'] + $value['bogus']; 
			}
			$this -> assign("payroll",$res);
			$this -> display('SalaryDetailPage');
		}
	}
	
}
?>