<?php
namespace Home\Controller;
use Think\Controller;
class CaculateWageController extends Controller {
	
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
		$payroll = $this -> db_salary -> getKunshanSalary	($last_month);
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
}
?>