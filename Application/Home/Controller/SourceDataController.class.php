<?php
namespace Home\Controller;
use Think\Controller;
class SourceDataController extends Controller {
	
	private $db_salesman;
	private $db_normal_business_ratio;
	private $db_special_business_ratio;
	private $db_normial_profit_ratio;
	private $db_special_profit_ratio;
	private $db_insurance_fund;
	private $db_price_float_ratio;
	private $db_load_history;
	private $db_customer;
	function _initialize() {
		if (!_checkLogin()) {
			$this->error('登陆超时,请重新登陆。','/commission',2);
			exit;
		}
		$this -> db_salesman = D("Salesman");
		$this -> db_normal_business_ratio = D("NormalBusinessRatio");
		$this -> db_special_business_ratio = D("SpecialBusinessRatio");
		$this -> db_normial_profit_ratio = D("NormalProfitRatio");
		$this -> db_insurance_fund = D("InsuranceFund");
		$this -> db_price_float_ratio = D("PriceFloatRatio");
		$this -> db_special_profit_ratio = D("SpecialProfitRatio");
		$this -> db_load_history = D("LoadHistory");
		$this -> db_customer = D("Customer");
	}
    public function loadSourceDataPage(){
    	$this -> display('SourceDataPage');
	}
	
	public function loadData(){
		$begin_date = $_POST['begin_date'];
		$end_date = $_POST['end_date'];
		$last_end_date = $this -> db_load_history -> getLastEndDate();
		$today = date("Y-m-d");
		if($begin_date == "" || $end_date == ""){
			$this -> error("起始日期或者结束日期为空");
		}elseif($begin_date > $end_date){
			$this -> error("起始日期大于结束日期");
		}elseif($begin_date <= $last_end_date){
			$this -> error("起始日期范围非法");
		}elseif($end_date == $today){
			$this -> error("结束日期最大只能为昨天");
		}
		
		$db_U8 = D("U8");
		$db_U8 -> test();
		exit;
		$this -> display('ConflictPage');
		
	}
	
	public function loadSettleSummaryPage(){
		$load_history = $this -> db_load_history -> getLastThreeHistory();
		$this -> assign('load_history',$load_history);
		$this -> display('SettleSummaryPage');
	}
	public function loadNormalBusinessPage(){
		$all_normal_business_ratio = $this -> db_normal_business_ratio -> getAllNormalBusinessRatio();
		$res = $this -> _addSalesmanName($all_normal_business_ratio);
		$all_normal_business_ratio = $res['data'];
		$all_salesman = $res['salesman'];
		$this -> assign("all_normal_business_ratio",$all_normal_business_ratio);
		$this -> assign("all_salesman",$all_salesman);
		$this -> display('NormalBusinessPage');
	}
	public function addNewNormalBusinessRatio(){
		$data = array();
		$data['salesman_id'] = $_POST['add_new_salesman_id'];
		$data['classification'] = $_POST['add_new_classification'];
		$data['specification'] = $_POST['add_new_specification'];
		$data['ratio'] = $_POST['add_new_ratio'];
		$this -> db_normal_business_ratio -> addNormalBusinessRatio($data);
		$this -> loadNormalBusinessPage();
	}
	public function editNormalBusinessRatio(){
		$id = $_POST['edit_id'];
		$ratio = $_POST['edit_ratio'];
		$this -> db_normal_business_ratio ->edtiNormalBusinessRatio($id,$ratio);
		$this -> loadNormalBusinessPage();
	}
	public function deleteNormalBusinessRatioById(){
		$id = $_POST['delete_id'];
		$this -> db_normal_business_ratio -> deleteNormalBusinessRatioById($id);
	}
	public function loadSpecialBusinessPage(){
		$all_special_business_ratio = $this -> db_special_business_ratio -> getAllSpecialBusinessRatio();
		$res = $this -> _addSalesmanName($all_special_business_ratio);
		$all_special_business_ratio = $res['data'];
		$all_salesman = $res['salesman'];
		$this -> assign("all_special_business_ratio",$all_special_business_ratio);
		$this -> assign("all_salesman",$all_salesman);
		$this -> display('SpecialBusinessPage');
	}
	public function editSpecialBusinessRatio(){
		$id = $_POST['edit_id'];
		$ratio = $_POST['edit_ratio'];
		$this -> db_special_business_ratio ->edtiSpecialBusinessRatio($id,$ratio);
		$this -> loadSpecialBusinessPage();
	}
	public function deleteSpecialBusinessRatioById(){
		$id = $_POST['delete_id'];
		$this -> db_special_business_ratio -> deleteSpecialBusinessRatioById($id);
	}
	public function addNewSpecialBusinessRatio(){
		$data = array();
		$data['salesman_id'] = $_POST['add_new_salesman_id'];
		$data['low_limit'] = $_POST['add_new_low_limit'];
		$data['high_limit'] = $_POST['add_new_high_limit'];
		$data['ratio'] = $_POST['add_new_ratio'];
		$data['classification'] = $_POST['add_new_classification'];
		$this -> db_special_business_ratio -> addSpecialBusinessRatio($data);
		$temp = $this -> loadSpecialBusinessPage();
		if($temp == FALSE){
			$this -> error("回款区间有重复，请重新输入!!",'/commission/index.php/Home/SourceData/loadSpecialBusinessPage',3);
		}
	}
	public function loadNormalProfitPage(){
		$all_normal_profit_ratio = $this -> db_normial_profit_ratio -> getAllNormalProfitRatio();
		$res = $this -> _addSalesmanName($all_normal_profit_ratio);
		$all_normal_profit_ratio = $res['data'];
		$all_salesman = $res['salesman'];
		$this -> assign("all_normal_profit_ratio",$all_normal_profit_ratio);
		$this -> assign("all_salesman",$all_salesman);
		$this -> display('NormalProfitPage');
	}
	public function editNormalProfitRatio(){
		$id = $_POST['edit_id'];
		$ratio = $_POST['edit_ratio'];
		$this -> db_normial_profit_ratio ->edtiNormalProfitRatio($id,$ratio);
		$this -> loadNormalProfitPage();
	}
	public function addNewNormalProfitRatio(){
		$data = array();
		$data['salesman_id'] = $_POST['add_new_salesman_id'];
		$data['ratio'] = $_POST['add_new_ratio'];
		$this -> db_normial_profit_ratio -> addNormalProfitRatio($data);
		$this -> loadNormalProfitPage();
	}
	public function deleteNormalProfitRatioById(){
		$id = $_POST['delete_id'];
		$this -> db_normial_profit_ratio -> deleteNormalProfitRatioById($id);
	}
	public function loadSpecialProfitPage(){
		$all_special_profit_ratio = $this -> db_special_profit_ratio ->getAllItems();
		$res = $this -> _addSalesmanName($all_special_profit_ratio);
		$all_special_profit_ratio = $res['data'];
		$all_salesman = $res['salesman'];
		$this -> assign("all_special_profit_ratio",$all_special_profit_ratio);
		$this -> assign("all_salesman",$all_salesman);
		$this -> display('SpecialProfitPage');
	}
	public function addNewSpecialProfitRatio(){
		$data = array();
		$data['salesman_id'] = $_POST['add_new_salesman_id'];
		$data['low_limit'] = $_POST['add_new_low_limit'];
		$data['high_limit'] = $_POST['add_new_high_limit'];
		$data['ratio'] = $_POST['add_new_ratio'];
		$temp = $this ->db_special_profit_ratio -> addItem($data);
		if($temp == FALSE){
			$this -> error("回款区间有重复，请重新输入!!",'/commission/index.php/Home/SourceData/loadSpecialProfitPage',3);
		}
	}
	public function editSpecialProfitRatio(){
		$id = $_POST['edit_id'];
		$ratio = $_POST['edit_ratio'];
		$this -> db_special_profit_ratio -> editItem($id,$ratio);
		$this -> loadSpecialProfitPage();
	}
	public function deleteSpecialProfitRatioById(){
		$id = $_POST['delete_id'];
		$this -> db_special_profit_ratio -> deleteItemById($id);
	}
	public function loadInsuranceAndFundPage(){
		$all_insurance_fund = $this -> db_insurance_fund -> getAllInsuranceFund();
		$res = $this -> _addSalesmanName($all_insurance_fund);
		$all_insurance_fund = $res['data'];
		$all_salesman = $res['salesman'];
		$this -> assign("all_insurance_fund",$all_insurance_fund);
		$this -> display('InsuranceAndFundPage');
	}
	public function editInsuranceFund(){
		$id = $_POST['edit_id'];
		$data['insurance'] = $_POST['edit_insurance'];
		$data['fund'] = $_POST['edit_fund'];
		
		$this -> db_insurance_fund ->editInsuranceAndFund($id,$data);
		$this -> loadInsuranceAndFundPage();
	}
	public function addInsuranceFund(){
		$data = array();
		$data['salesman_id'] = $_POST['add_new_salesman_id'];
		$data['insurance'] = $_POST['add_new_insurance'];
		$data['fund'] = $_POST['add_new_fund'];
		$this -> db_insurance_fund -> addItem($data);
		$this -> loadNormalProfitPage();
	}
	public function deleteInsuranceFund(){
		$id = $_POST['delete_id'];
		$this -> db_insurance_fund -> deleteItemById($id);
	}
	
	public function loadPriceFloatPage(){
		$all_price_float_ratio = $this -> db_price_float_ratio ->getAllPriceFloatRatio();
		$this -> assign("all_price_ratio_ratio",$all_price_float_ratio);
		$this -> display('PriceFloatPage');
	}
	public function addPriceFloatRatio(){
		$data['classification_id'] = $_POST['add_new_classification_id'];
		$data['name'] = $_POST['add_new_name'];
		$data['low_price'] = $_POST['add_new_low_price'];
		$data['high_price'] = $_POST['add_new_high_price'];
		$data['low_length'] = $_POST['add_new_low_length'];
		$data['high_length'] = $_POST['add_new_high_length'];
		$data['ratio'] = $_POST['add_new_ratio'];
		$this -> db_price_float_ratio -> addItem($data);
		$this -> loadPriceFloatPage();
	}
	public function editPriceFloatRatio(){
		$id = $_POST['edit_id'];
		$data['classification_id'] = $_POST['edit_classification_id'];
		$data['name'] = $_POST['edit_name'];
		$data['low_price'] = $_POST['edit_low_price'];
		$data['high_price'] = $_POST['edit_high_price'];
		$data['low_length'] = $_POST['edit_low_length'];
		$data['high_length'] = $_POST['edit_high_length'];
		$data['ratio'] = $_POST['edit_ratio'];
		$this -> db_price_float_ratio ->editItem($id,$data);
		$this -> loadPriceFloatPage();
	}
	public function deletePriceFloatRatio(){
		$delete_id = $_POST['delete_id'];
		$this -> db_price_float_ratio -> deleteItemById($delete_id);
	}
	public function loadFundsBackPage(){
		$all_funds_back = array(
			array(
				'customer_id' => 'customer_id_001',
				'customer_name' => 'customer_name_001',
				'funds_back_money' => '资金回笼调整金额一'
			),
			array(
				'customer_id' => 'customer_id_002',
				'customer_name' => 'customer_name_002',
				'funds_back_money' => '资金回笼调整金额二'
			)
		);
		$this -> assign('all_funds_back',$all_funds_back);
		$this -> display('FundsBackPage');
	}
	public function addFundsBack(){
		
	}
	public function editFundsBack(){
		
	}
	public function deleteFundsBack(){
		
	}
	public function loadHumanWagePage(){
		$all_human_wage = array(
			array(
				'salesman_id' => '员工编码一',
				'name' => '员工姓名',
				'real_wage' => '实发工资',
				'should_wage' => '应发工资',
				'freight' => '运费',
				'trencher' => '木盘非',
				'blocking' => '挤压物资费',
				'cutting' => '裁剪费',
				'reimbursement' => '报销费',
				'gurantee' => '担保',
				'deduction' => '上月扣款',
			),
		);
		$this -> assign("all_human_wage",$all_human_wage);
		$this -> display('HumanWagePage');
	}
	public function addHumanWagePage(){
		
	}
	public function editHumanWagePage(){
		
	}
	public function deleteHumanWagePage(){
		
	}
	public function loadIncidentalFeePage(){
		$all_incidental_fee = array(
			array(
				'contact_id' => '合同号',
				'freight' => '运费',
				'trencherg' => '木盘非',
				'blocking' => '挤压物资费',
				'cutting' => '裁剪费',
				'reimbursement' => '报销费',
			),
		);
		$this -> assign("all_incidental_fee",$all_incidental_fee);
		$this -> display('IncidentalFeePage');
	}
	public function loadTaxPage(){
		$all_tax_ratio = array(
			array(
				'low_limit' => '1000',
				'high_limit' => '2000',
				'ratio' => '20',
			),
		);
		$this -> assign("all_tax_ratio",$all_tax_ratio);
		$this ->display('TaxPage');
	}
	public function editTaxRatio(){
		
	}
	public function addTaxRatio(){
		
	}
	public function deleteTaxRatioById(){
		
	}
	public function loadCustomerPage(){
		$all_customer = $this -> db_customer ->getAllCustomer();
		$this -> assign("all_customer",$all_customer);
		$this -> display('CustomerPage');
	}
	public function editCustomer(){
		$id = $_POST['edit_id'];
		$data['customer_id'] = $_POST['edit_customer_id'];
		$data['customer_name'] = $_POST['edit_customer_name'];
		$this -> db_customer ->editItem($id,$data);
		$this -> loadCustomerPage();
 	}
	public function addCustomer(){
		$data['customer_id'] = $_POST['add_new_customer_id'];
		$data['customer_name'] = $_POST['add_new_customer_name'];
		$this -> db_customer -> addItem($data);
		$this -> loadCustomerPage();
	}
	public function deleteCustomer(){
		$id = $_POST['delete_id'];
		$this -> db_customer -> deleteItem($id);
	}
	public function loadSalesmanPage(){
		$all_salesmen = $this-> db_salesman ->getAllSalesmanInfo();
		$this -> assign("all_salesmen",$all_salesmen);
		$this -> display('SalesmanPage');
	}
	public function editSalesman(){
		$id = $_POST['edit_id'];
		$data['shanghai_salary'] = $_POST['edit_shanghai_salary'];
		$data['kunshan_salary'] = $_POST['edit_kunshan_salary'];
		
		$this -> db_salesman ->editSalesman($id,$data);
		$this -> loadSalesmanPage();
	}
	public function addSalesman(){
		$data = array();
		$data['salesman_id'] = $_POST['add_new_salesman_id'];
		$data['name'] = $_POST['add_new_name'];
		$data['status'] = $_POST['add_new_status'];
		$data['shanghai_salary'] = $_POST['add_new_shanghai_salary'];
		$data['kunshan_salary'] = $_POST['add_new_kunshan_salary'];
		$this -> db_salesman -> addItem($data);
		$this -> loadSalesmanPage();
	}
	
	public function checkSalesmanId(){
		$salesman_id = $_GET['fieldValue'];
		if($this -> db_salesman ->checkDuplicateSalesmanId($salesman_id)){
			$data = array('add_new_salesman_id',TRUE);
		}else{
			$data = array('add_new_salesman_id',FALSE);
		}
		$json_data = json_encode($data);
		echo $json_data;
	}
	public function deleteSalesman(){
		$id = $_POST['delete_id'];
		$this -> db_salesman -> deleteItem($id);
	}
	private function _addSalesmanName($array_data){
		$all_salesmen = $this-> db_salesman ->getAllSalesman();
		foreach ($array_data as $key => $value) {
			foreach ($all_salesmen as $kk => $vv) {
				if($value['salesman_id'] == $vv['salesman_id']){
					$array_data[$key]['name'] = $vv['name'];
					break;
				}
			}
		}
		$res = array();
		$res['data'] = $array_data;
		$res['salesman'] = $all_salesmen;
		return $res;
	}
	
}
?>