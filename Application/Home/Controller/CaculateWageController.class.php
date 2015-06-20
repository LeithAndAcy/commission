<?php
namespace Home\Controller;
use Think\Controller;
class CaculateWageController extends Controller {
	
	
	private $db_contact_main;
	private $db_contact_detail;
	private $db_customer;
	private $db_salesman;
	private $db_coustomer_funds;
	private $db_wage_deduction;
	private $db_insurance_fund;
	private $db_tax;
	private $db_salary;
	private $db_U8;
	private $db_funds_back;
	private $db_fee_ratio;
	private $db_sale_expense;
	function _initialize() {
		if (!_checkLogin()) {
			$this->error('登陆超时,请重新登陆。','/commission',2);
			exit;
		}
		$this -> db_contact_main = D("ContactMain");
		$this -> db_contact_detail = D("ContactDetail");
		$this -> db_customer = D("Customer");
		$this -> db_coustomer_funds = D("CustomerFunds");
		$this -> db_salesman = D("Salesman");
		$this -> db_wage_deduction = D("WageDeduction");
		$this -> db_tax_ratio = D("TaxRatio");
		$this -> db_insurance_fund = D("InsuranceFund");
		$this -> db_salary = D("Salary");
	}
	
    public function loadCaculateWagePage(){
    	$this -> display('CaculateWagePage');
	}
	
	public function LoadPayrollPage(){
		$last_month = date("Y-m",strtotime("-1 month"));
		$payroll = $this -> db_salary -> getSalary($last_month);
		$payroll = $this -> db_salesman -> addSalesmanName($payroll);
		$this -> assign("payroll",$payroll);
		$this -> display('PayrollPage');
	}
	
	public function LoadShanghaiSalaryPage(){
		$last_month = date("Y-m",strtotime("-1 month"));
		$payroll = $this -> db_salary -> getShanghaiSalary($last_month);
		$payroll = $this -> db_salesman -> addSalesmanName($payroll);
		$this -> assign("payroll",$payroll);
		$this -> display('ShanghaiSalaryPage');
	}
	public function LoadKunshanSalaryPage(){
		$last_month = date("Y-m",strtotime("-1 month"));
		$payroll = $this -> db_salary -> getKunshanSalary($last_month);
		$payroll = $this -> db_salesman -> addSalesmanName($payroll);
		$this -> assign("payroll",$payroll);
		$this -> display('KunshanSalaryPage');
	}
	public function LoadIncidentalFeePage(){
		$last_month = date("Y-m",strtotime("-1 month"));
		$payroll = $this -> db_salary -> getSalary($last_month);
		$payroll = $this -> db_salesman -> addSalesmanName($payroll);
		$this -> assign("payroll",$payroll);
		$this -> display('IncidentalFeePage');
	}
	
	public function checkSalarySettled(){
		$date = $_POST['date'];
		if($this -> db_salary -> checkSalarySettled($date)){
			$this -> ajaxReturn("settled");
		}else{
			$this -> CaculateWageOfMonth($date);
		}
	}
	
	public function CaculateWageOfMonth($date){
		
		//结算合同
		if($_POST['date'] == null){
			$month = $date;
		}else{
			$month = $_POST['date'];
		}
		//删除上个月可能结算的工资
		$this -> db_salary -> deleteSalaryOfMonth($month);
		$month_before_the_month = date('Y-m',strtotime("$month -1 month"));
		$tax_beginning_point = $this -> db_tax_ratio -> getTaxBeginningPoint();
		$all_tax = $this -> db_tax_ratio -> getAllItemsSorted();
		foreach ($all_tax as $key => $value) {
			if($value['low_limit'] == 0){
				$all_tax[$key]['tax'] = 0;
				$sum = ($value['high_limit'] - $value['low_limit']) * $value['ratio'];
			}else{
				$all_tax[$key]['tax'] = $sum;
				$sum += ($value['high_limit'] - $value['low_limit']) * $value['ratio'];
			}
		}
		
		$settled_contact_of_month = $this -> db_contact_main->getSettledContactOfMonth($month);
		
		$settled_contact_of_month_tatal_business_profit = $this -> db_contact_detail -> getTotalBusinessAndProfit($settled_contact_of_month);
		foreach ($settled_contact_of_month_tatal_business_profit as $key => $value) {
			$salesman_id = $key;
			$total_business_profit = $value;
			$salary_of_last2_month = $this -> db_salary -> getSalaryBySalesmanIdAndMonth($salesman_id,$month_before_the_month);
			$temp_human_wage = $this -> db_wage_deduction -> getHumanWage($salesman_id,$month);
			$temp_fact_pay = $temp_human_wage+$total_business_profit;
			if($salary_of_last_month < 0){
				$temp_fact_pay += $salary_of_last2_month;
			}
			$temp_insurance_fund = $this -> db_insurance_fund -> getInsuranceFund($salesman_id);
			$temp_insurance = $temp_insurance_fund['insurance'];
			$temp_fund = $temp_insurance_fund['fund'];
			
			$salesman_info = $this -> db_salesman -> getSalesmanInfo($salesman_id);
			$status = $salesman_info['status'];
			$shanghai_salary = $salesman_info['shanghai_salary'];
			$kunshan_salary = $salesman_info['kunshan_salary'];
			$salary = array();
			$salary['salesman_id'] = $salesman_id;
			$salary['status'] = $status;
			$salary['date'] = $month;
			if($status == "上海"){
				$kunshan_bogus = $temp_fact_pay - $shanghai_salary;
				if($kunshan_bogus <= 0){
					$temp_left = $temp_fact_pay - $temp_insurace - $temp_fund;
					foreach ($all_tax as $kk => $vv) {
						$temp = $temp_left - $tax_beginning_point;
						if($temp >= $vv['low_limit'] && $temp < $vv['high_limit']){
							$temp_tax = $vv['tax'];
							break;
						}
					}
					$temp_shanghai_salary = $temp_left - $temp_tax;
					$salary['shanghai_salary'] = $temp_shanghai_salary;
					$salary['insurance'] = $temp_insurace;
					$salary['fund']= $temp_fund;
					$salary['tax'] = $temp_tax;
					$this -> db_salary -> addItem($salary);
				}else{
					$temp_left = $shanghai_salary - $temp_insurance - $temp_fund;
					foreach ($all_tax as $kk => $vv) {
						$temp = $temp_left - $tax_beginning_point;
						if($temp >= $vv['low_limit'] && $temp < $vv['high_limit']){
							$temp_tax = $vv['tax'];
							break;
						}
					}
					$temp_shanghai_salary = $temp_left - $temp_tax;
					$salary['shanghai_salary'] = $temp_shanghai_salary;
					if($kunshan_bogus <= $kunshan_salary){
						$salary['kunshan_salary'] = $kunshan_bogus;
						$salary['bogus'] = 0;
					}else{
						$salary['kunshan_salary'] = $kunshan_salary;
						$salary['bogus'] = $kunshan_bogus -$kunshan_salary;
					}
					$salary['insurance'] = $temp_insurance;
					$salary['fund']= $temp_fund;
					$salary['tax'] = $temp_tax;
					// print_r($salary);exit;
					$this -> db_salary -> addItem($salary);
				}
				
			}elseif($status == "昆山"){
				$shanghai_bogus = $temp_fact_pay - $kunshan_salary;
				if($shanghai_bogus <= 0){
					$temp_left = $temp_fact_pay - $temp_insurace - $temp_fund;
					foreach ($all_tax as $kk => $vv) {
						$temp = $temp_left - $tax_beginning_point;
						if($temp >= $vv['low_limit'] && $temp < $vv['high_limit']){
							$temp_tax = $vv['tax'];
							break;
						}
					}
					$temp_kunshan_salary = $temp_left - $temp_tax;
					$salary['kunshan_salary'] = $temp_kunshan_salary;
					$salary['insurance'] = $temp_insurance;
					$salary['fund']= $temp_fund;
					$salary['tax'] = $temp_tax;
					
					// print_r($salary);exit;
					$this -> db_salary -> addItem($salary);
				}else{
					$temp_left = $kunshan_salary - $temp_insurance - $temp_fund;
					foreach ($all_tax as $kk => $vv) {
						$temp = $temp_left - $tax_beginning_point;
						if($temp >= $vv['low_limit'] && $temp < $vv['high_limit']){
							$temp_tax = $vv['tax'];
							break;
						}
					}
					$salary['kunshan_salary'] = $temp_kunshan_salary;
					if($shanghai_bogus <= $shanghai_salary){
						$salary['shanghai_salary'] = $shanghai_bogus;
					}else{
						$salary['shanghai_salary'] = $kunshan_salary;
						$salary['bogus'] = $shanghai_bogus - $kunshan_salary;
					}
					$salary['insurance'] = $temp_insurance;
					$salary['fund']= $temp_fund;
					$salary['tax'] = $temp_tax;
					
					// print_r($salary);exit;
					$this -> db_salary -> addItem($salary);
				}
			}
		}
	}
	
}
?>