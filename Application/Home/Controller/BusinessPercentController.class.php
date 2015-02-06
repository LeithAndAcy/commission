<?php
namespace Home\Controller;
use Think\Controller;
class BusinessPercentController extends Controller {
	
	private $db_constomer_funds;
	private $db_contact_main;
	private $db_contact_detail;
	private $db_customer;
	private $db_salesman;
	private $db_coustomer_funds;
	private $db_normal_business_ratio;
	private $db_special_business_ratio;
	private $db_normial_profit_ratio;
	private $db_special_profit_ratio;
	private $db_price_float_ratio;
	private $db_wage_deduction;
	private $db_insurance_fund;
	private $db_tax;
	private $db_salary;
	private $db_U8;
	
	function _initialize() {
		
		$this -> db_constomer_funds = D("CustomerFunds");
		$this -> db_contact_main = D("ContactMain");
		$this -> db_contact_detail = D("ContactDetail");
		$this -> db_customer = D("Customer");
		$this -> db_coustomer_funds = D("CustomerFunds");
		$this -> db_salesman = D("Salesman");
		$this -> db_normal_business_ratio = D("NormalBusinessRatio");
		$this -> db_special_business_ratio = D("SpecialBusinessRatio");
		$this -> db_normial_profit_ratio = D("NormalProfitRatio");
		$this -> db_special_profit_ratio = D("SpecialProfitRatio");
		$this -> db_price_float_ratio = D("PriceFloatRatio");
		$this -> db_wage_deduction = D("WageDeduction");
		$this -> db_tax_ratio = D("TaxRatio");
		$this -> db_insurance_fund = D("InsuranceFund");
		$this -> db_salary = D("Salary");
		if (!_checkLogin()) {
			$this->error('登陆超时,请重新登陆。','/commission',2);
			exit;
		}
	}
    public function loadBusinessPercentPage(){
    	$this -> display('BusinessPercentPage');
		
	}
	public function loadSettlingContactPage(){
		$this -> db_U8 = D("U8");
		$count_settling_contact = $this -> db_contact_main -> countSettlingContact();
		$Page = new \Think\Page($count_settling_contact,1000);
		$show = $Page->show();// 分页显示输出
		
		$settling_contact = $this -> db_contact_main ->getSettlingContact($Page);
		$settling_contact = $this -> db_customer -> addCustomerName($settling_contact);
		$settling_contact = $this ->db_salesman -> addSalesmanName($settling_contact);
		$settling_contact_detail = $this -> db_contact_detail ->getContactDetail($settling_contact);
		$settling_contact_detail = $this -> db_U8 -> getInventoryDetail($settling_contact_detail);
		
		$this -> assign("settling_contact_detail",$settling_contact_detail);
		$this -> assign("page",$show);
		$this -> display('SettlingContactPage');
	}
	public function getSettlingContact(){
		//判断哪些合同可结算    先判断回款，再判断发货数量
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
					//检测发货数量
					if($this -> db_contact_detail -> checkContactSettable($vv['contact_id'])){
						$this -> db_contact_main -> setSettlingContact($vv['contact_id']);
						$contact_main[$key]['total_funds'] = $contact_main[$key]['total_funds'] - $contact_total_money;
						$temp_customer_id = $vv['customer_id'];
						$temp_salesman_id = $vv['salesman_id'];
					}
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
		$this -> db_contact_main -> setContactEdited($contact_id);
		$this -> loadSettlingContactPage();
	}
	public function getSettlingRatioAndPrice(){
		//取基本业绩提成比例以及基本利润提成比例
		$this -> db_U8 = D("U8");
		$contact_main = $this -> db_contact_main -> getSettlingContact();
		$contact_detail = $this -> db_contact_detail ->getContactDetail($contact_main);
		$normal_business_ratio = $this -> db_normal_business_ratio -> getAllNormalBusinessRatio();
		$normal_profit_ratio = $this -> db_normial_profit_ratio -> getAllNormalProfitRatio();
		$price_float_ratio = $this -> db_price_float_ratio -> getAllPriceFloatRatio();
		$arr_ratio = array();
		foreach ($contact_detail as $key => $value) {
			//取存货类别
			$contact_detail[$key]['classification_id'] = "classification_id_01";
			$arr_ratio[$key]['salesman_id'] = $value['salesman_id'];
			$arr_ratio[$key]['contact_id'] = $value['contact_id'];
			$arr_ratio[$key]['inventory_id'] = $value['inventory_id'];
			foreach ($normal_business_ratio as $kk => $vv) {
				if($value['salesman_id'] == $vv['salesman_id'] && $value['inventory_id'] == $vv['inventory_id']){
					$arr_ratio[$key]['normal_business_ratio'] = $vv['ratio'];
					break;
				}
			}
			foreach ($normal_profit_ratio as $kkk => $vvv) {
				if($value['salesman_id'] == $vvv['salesman_id']){
					$arr_ratio[$key]['normal_profit_ratio'] = $vvv['ratio'];
					break;
				}
			}
			//计算上浮底价   以及最终实际底价
			foreach ($price_float_ratio as $kkkk => $vvvv) {
				if($vvvv['classification_id'] == $contact_detail[$key]['classification_id'] &&
				$vvvv['low_price'] <= $contact_detail[$key]['cost_price'] && $vvvv['high_price'] > $contact_detail[$key]['cost_price'] &&
				$vvvv['low_length'] <= $contact_detail[$key]['delivery_quantity'] && $vvvv['high_length'] > $contact_detail[$key]['delivery_quantity']){
					$arr_ratio[$key]['float_price'] = $vvvv['ratio'] * 0.01 * $contact_detail[$key]['cost_price'];
					$arr_ratio[$key]['end_cost_price'] = $arr_ratio[$key]['float_price'] + $contact_detail[$key]['cost_price'] + $contact_detail[$key]['cost_price_adjust'];
					break;
				}
			}
			//取回款，计算各种比例和金额    
			$salesman_id = $value['salesman_id'];
			$temp_total_funds = $this -> db_U8 -> getFundsBySalesmanAndDate($salesman_id);
			$temp_special_business_ratio = $this -> db_special_business_ratio ->getSpecialBusinessRatio($salesman_id,$contact_detail[$key]['classification_id'],$temp_total_funds);
			$arr_ratio[$key]['special_business_ratio'] = $temp_special_business_ratio;
			$temp_special_profit_ratio = $this -> db_special_profit_ratio ->getSpecialProfitRatio($salesman_id,$temp_total_funds);
			$arr_ratio[$key]['special_profit_ratio'] = $temp_special_profit_ratio;
			$arr_ratio[$key]['normal_business'] = $contact_detail[$key]['delivery_money'] * ($arr_ratio[$key]['normal_business_ratio'] + $contact_detail[$key]['business_adjust'] );
			$arr_ratio[$key]['special_business'] = $temp_total_funds * $arr_ratio[$key]['special_business_ratio'];
			$arr_ratio[$key]['normal_profit'] = ($contact_detail[$key]['sale_price'] - $arr_ratio[$key]['end_cost_price']) * $contact_detail[$key]['delivery_quantity'] *($arr_ratio[$key]['normal_profit_ratio'] + $contact_detail[$key]['profit_adjust']);
			$arr_ratio[$key]['special_profit'] = $temp_special_profit_ratio * $temp_total_funds;
		}
		$this -> db_contact_detail -> updateSettlementRatio($arr_ratio);
	}
	public function loadManualContactPage(){
		$this -> db_U8 = D("U8");
		$count_manual_contact = $this -> db_contact_main -> countManualContact();
		$Page = new \Think\Page($count_manual_contact,1000);
		$show = $Page->show();// 分页显示输出 
		
		$manual_contact = $this -> db_contact_main -> getManualContact($Page);
		$manual_contact = $this -> db_customer -> addCustomerName($manual_contact);
		$manual_contact = $this ->db_salesman -> addSalesmanName($manual_contact);
		$manual_contact_detail = $this -> db_contact_detail -> getContactDetail($manual_contact);
		$manual_contact_detail = $this -> db_U8 -> getInventoryDetail($manual_contact_detail);
		$this -> assign("manual_contact_detail",$manual_contact_detail);
		$this -> assign("page",$show);
		$this -> display('ManualContactPage');
	}
	public function setSettlingContact(){
		// 使手工结算合同加入可结算合同，并扣除回款
		$contact_id = $_POST['contact_id'];
		$this -> db_contact_main -> setSettlingContact($contact_id);
		$contact_total_money = $this -> db_contact_detail -> getContactTotalMoney($contact_id);
		$temp = $this -> db_contact_main -> getContactSalemanAndCustomer($contact_id);
		$salesman_id = $temp['salesman_id'];
		$customer_id = $temp['customer_id'];
		$this -> db_coustomer_funds -> subtractCustomerBenefit($customer_id,$salesman_id,$contact_total_money);
	}
	public function settleContact(){
		//结算合同
		$last_month = date('Y-m',strtotime('-1 month'));
		$all_salesman = $this -> db_salesman -> getOnboardSalesmanInfo();
	//	print_r($all_salesman);exit;
		foreach ($all_salesman as $key => $value) {
			$temp_human_wage = $this -> db_wage_deduction -> getHumanWage($value['salesman_id'],$last_month);
			$temp_deduction_wage = $this -> db_wage_deduction -> getTotalDeduction($value['salesman_id'],$last_month);
			$temp_arr_contact = $this -> db_contact_main -> getSettlingContactBySalesmanId($value['salesman_id']);
			$temp_business_profit = $this -> db_contact_detail ->getBusinessAndProfit($temp_arr_contact);
			$this -> db_contact_main -> setContactSettled($value['salesman_id']);
			$temp_should_pay = $temp_human_wage+$temp_business_profit;
			$temp_fact_pay = $temp_should_pay -$temp_deduction_wage;
			
			$temp_insurance_fund_ratio = $this -> db_insurance_fund -> getInsuranceFund($value['salesman_id']);
			$temp_insurance_ratio = $temp_insurance_fund_ratio['insurance'];
			$temp_fund_ratio = $temp_insurance_fund_ratio['fund'];
			
			if($value['status'] == "上海"){
				$kunshan_bogus = $temp_fact_pay - $value['shanghai_salary'];
				if($kunshan_bogus <= 0){
					$temp_insurace = $temp_fact_pay * $temp_insurance_ratio;
					$temp_fund = $temp_fact_pay * $temp_fund_ratio;
					$temp_left = $temp_fact_pay - $temp_insurace - $temp_fund;
					
					$temp_tax_ratio = $this -> db_tax_ratio -> getTaxRatio($temp_left);
					$temp_tax =$temp_left * $temp_tax_ratio;
					$temp_shanghai_salary = $temp_left - $temp_tax;
					$salary = array();
					$salary['salesman_id'] = $value['salesman_id'];
					$salary['status'] = $value['status'];
					$salary['shanghai_salary'] = $temp_shanghai_salary;
					$salary['insurance'] = $temp_insurace;
					$salary['fund']= $temp_fund;
					$salary['tax'] = $temp_tax;
					$salary['date'] = $last_month;
					$this -> db_salary -> addItem($salary);
				}else{
					$temp_insurace = $value['shanghai_salary'] * $temp_insurance_ratio;
					$temp_fund = $value['shanghai_salary'] * $temp_fund_ratio;
					$temp_left = $value['shanghai_salary'] - $temp_insurace - $temp_fund;
					
					$temp_tax_ratio = $this -> db_tax_ratio -> getTaxRatio($temp_left);
					$temp_tax =$temp_left * $temp_tax_ratio;
					$temp_shanghai_salary = $temp_left - $temp_tax;
					$salary = array();
					$salary['salesman_id'] = $value['salesman_id'];
					$salary['status'] = $value['status'];
					$salary['shanghai_salary'] = $temp_shanghai_salary;
					if($kunshan_bogus <= $value['kunshan_salary']){
						$salary['kunshan_salary'] = $kunshan_bogus;
					}else{
						$salary['kunshan_salary'] = $value['kunshan_salary'];
						$salary['bogus'] = $kunshan_bogus - $value['kunshan_salary'];
					}
					$salary['insurance'] = $temp_insurace;
					$salary['fund']= $temp_fund;
					$salary['tax'] = $temp_tax;
					$salary['date'] = $last_month;
					$this -> db_salary -> addItem($salary);
				}
				
			}elseif($value['status'] == "昆山"){
				$shanghai_bogus = $temp_fact_pay - $value['kunshan_salary'];
				if($shanghai_bogus <= 0){
					$temp_insurace = $temp_fact_pay * $temp_insurance_ratio;
					$temp_fund = $temp_fact_pay * $temp_fund_ratio;
					$temp_left = $temp_fact_pay - $temp_insurace - $temp_fund;
					
					$temp_tax_ratio = $this -> db_tax_ratio -> getTaxRatio($temp_left);
					$temp_tax =$temp_left * $temp_tax_ratio;
					$temp_kunshan_salary = $temp_left - $temp_tax;
					$salary = array();
					$salary['salesman_id'] = $value['salesman_id'];
					$salary['status'] = $value['status'];
					$salary['kunshan_salary'] = $temp_kunshan_salary;
					$salary['insurance'] = $temp_insurace;
					$salary['fund']= $temp_fund;
					$salary['tax'] = $temp_tax;
					$salary['date'] = $last_month;
					$this -> db_salary -> addItem($salary);
				}else{
					$temp_insurace = $value['kunshan_salary'] * $temp_insurance_ratio;
					$temp_fund = $value['kunshan_salary'] * $temp_fund_ratio;
					$temp_left = $value['kunshan_salary'] - $temp_insurace - $temp_fund;
					
					$temp_tax_ratio = $this -> db_tax_ratio -> getTaxRatio($temp_left);
					$temp_tax =$temp_left * $temp_tax_ratio;
					$temp_shanghai_salary = $temp_left - $temp_tax;
					$salary = array();
					$salary['salesman_id'] = $value['salesman_id'];
					$salary['status'] = $value['status'];
					$salary['shanghai_salary'] = $temp_shanghai_salary;
					if($shanghai_bogus <= $value['shanghai_salary']){
						$salary['shanghai_salary'] = $shanghai_bogus;
					}else{
						$salary['shanghai_salary'] = $value['kunshan_salary'];
						$salary['bogus'] = $shanghai_bogus - $value['kunshan_salary'];
					}
					$salary['insurance'] = $temp_insurace;
					$salary['fund']= $temp_fund;
					$salary['tax'] = $temp_tax;
					$salary['date'] = $last_month;
					$this -> db_salary -> addItem($salary);
				}
			}
		}
	}
	
	public function loadSettledContactPage(){
		$this -> db_U8 = D("U8");
		$count_settled_contact = $this -> db_contact_main -> countSettledContact();
		$Page = new \Think\Page($count_settled_contact,1000);
		$show = $Page->show();// 分页显示输出
		
		$settled_contact = $this -> db_contact_main ->getSettledContact($Page);
		$settled_contact = $this -> db_customer -> addCustomerName($settled_contact);
		$settled_contact = $this ->db_salesman -> addSalesmanName($settled_contact);
		$settled_contact_detail = $this -> db_contact_detail ->getContactDetail($settled_contact);
		$settled_contact_detail = $this -> db_U8 -> getInventoryDetail($settled_contact_detail);
		$this -> assign("page",$show);
		$this -> assign("settled_contact_detail",$settled_contact_detail);
		$this -> display('BusinessPercent:SettledContactPage');
	}
	
	
	
	public function loadCommissionBuisnessPage(){
		
		$count_contact_main = $this -> db_contact_main -> count();
		$Page = new \Think\Page($count_contact_main,1000);
		$show = $Page->show();// 分页显示输出
		$contact_main = $this -> db_contact_main -> getContact($Page);
		$contact_detail = $this -> db_contact_detail -> getContactDetail($contact_main);
		$contact_detail = $this -> db_salesman -> addSalesmanName($contact_detail);
		$this -> assign('page',$show);
		$this -> assign("contact_detail",$contact_detail);
		$this -> display('BusinessPercent:CommissionBusinessPage');
	}
}
?>