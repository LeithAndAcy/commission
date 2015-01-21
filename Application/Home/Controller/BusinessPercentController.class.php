<?php
namespace Home\Controller;
use Think\Controller;
class BusinessPercentController extends Controller {
	
	private $db_constomer_funds;
	private $db_contact_main;
	private $db_contact_detail;
	private $db_customer;
	private $db_salesman;
	
	function _initialize() {
		
		$this -> db_constomer_funds = D("CustomerFunds");
		$this -> db_contact_main = D("ContactMain");
		$this -> db_contact_detail = D("ContactDetail");
		$this -> db_customer = D("Customer");
		$this -> db_salesman = D("Salesman");
		if (!_checkLogin()) {
			$this->error('登陆超时,请重新登陆。','/commission',2);
			exit;
		}
	}
    public function loadBusinessPercentPage(){
    	$this -> display('BusinessPercentPage');
		
	}
	public function loadSettlingContactPage(){
		
		$settling_contact = $this -> db_contact_main ->getSettlingContact();
		$settling_contact = $this -> db_customer -> addCustomerName($settling_contact);
		$settling_contact = $this ->db_salesman -> addSalesmanName($settling_contact);
		$settling_contact_detail = $this -> db_contact_detail ->getContactDetail($settling_contact);
		
		$this -> assign("settling_contact_detail",$settling_contact_detail);
		
		$this -> display('SettlingContactPage');
	}
	public function getSeettlingContact(){
		$total_customer_funds = $this -> db_constomer_funds ->getTotalCustomerFunds();
		$condition = array();
		$contact_main = array();
		foreach ($total_customer_funds as $key => $value) {
			$condition['customer_id'] = $value['customer_id'];
			$condition['salesman_id'] = $value['salesman_id'];
			$condition['settling'] = 0;
			$condition['settled'] = 0;
			$contact_main[$key] = $this -> db_contact_main ->getContact($condition);
			$contact_main[$key]['total_funds'] = $value['total_funds'];
		}
		foreach ($contact_main as $key => $value) {
			foreach ($value as $kk => $vv) {
				if($kk === "total_funds"){
					$this -> db_constomer_funds ->setCustomerFunds($temp_customer_id,$temp_salesman_id,$contact_main[$key]['total_funds']);
					break;
				}
 				$contact_total_money = $this -> db_contact_detail -> getContactTotalMoney($vv['contact_id']);
				if($contact_total_money > $contact_main[$key]['total_funds']){
					$this -> db_constomer_funds ->setCustomerFunds($vv['customer_id'],$vv['salesman_id'],$contact_main[$key]['total_funds']);
					break;
				}else{
					$this -> db_contact_main -> setSettlingContact($vv['contact_id']);
					$contact_main[$key]['total_funds'] = $contact_main[$key]['total_funds'] - $contact_total_money;
					$temp_customer_id = $vv['customer_id'];
					$temp_salesman_id = $vv['salesman_id'];
				}
			}
		}
		$this -> loadSettlingContactPage();
	}
	public function SettledCommission(){
		$this -> display('SettledCommissionPage');
	}
	public function CommissionBuisness(){
		$this -> display('CommissionBusinessPage');
	}
}
?>