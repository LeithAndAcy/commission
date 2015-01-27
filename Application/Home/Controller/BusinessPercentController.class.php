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
	public function getSettlingContact(){
		//判断哪些合同可结算
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
	public function ratioAdjust(){
		$contact_id = $_POST['edit_contact_id'];
		$inventory_id = $_POST['edit_inventory_id'];
		$business_adjust = $_POST['edit_business_adjust'];
		$profit_adjust =  $_POST['edit_profit_adjust'];
		$cost_price_adjust = $_POST['edit_cost_price_adjust'];
		$this -> db_contact_detail -> updateAdjust($contact_id,$inventory_id,$business_adjust,$profit_adjust,$cost_price_adjust);
		$this -> loadSettlingContactPage();
	}
	public function getSettlingRatioAndPrice(){
		//依次计算上浮底价，最终实际底价，考虑回款金额，计算达标业绩提成比例，未达标利润提成比例。最后再计算业绩和利润提成金额。
		$settling_contact =  $this -> db_contact_main ->getSettlingContact();
		$settling_contact_detail = $this -> db_contact_detail ->getContactDetail($settling_contact);
		// print_r($settling_contact_detail);exit;
		foreach ($settling_contact_detail as $key => $value) {
			$settling_contact_detail[$key]['normal_business'] = $value['normal_business_ratio'] * $value['delivery_money'] * 0.01;
	
		}
		
	}
	public function loadManualContactPage(){
		$this -> display('ManualContactPage');
	}
	public function loadSettledContactPage(){
		$this -> display('BusinessPercent:SettledCommissionPage');
	}
	public function loadCommissionBuisnessPage(){
		$this -> display('BusinessPercent:CommissionBusinessPage');
	}
}
?>