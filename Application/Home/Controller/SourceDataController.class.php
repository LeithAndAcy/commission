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
	private $db_U8;
	private $db_contact_main;
	private $db_contact_detail;
	private $db_constomer_funds;
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
		$this -> db_contact_main = D("ContactMain");
		$this -> db_contact_detail = D("ContactDetail");
		$this -> db_constomer_funds = D("CustomerFunds");
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
		}elseif($end_date >= $today){
			$this -> error("结束日期最大只能为昨天");
		}
		
		$this -> db_U8 = D("U8");
		$edited_contact_main = $this -> db_U8 ->getEditedContactMain($begin_date,$end_date);
		if(count($edited_contact_main) == 0){
			$this -> _processData($begin_date,$end_date);
		}else{
			$edited_contact_main = $this -> db_customer -> addCustomerName($edited_contact_main);
			$temp_edited_contact_mian = $this -> _addSalesmanName($edited_contact_main);
			$edited_contact_main = $temp_edited_contact_mian['data'];
		//	print_r($edited_contact_detail);
			$edited_contact_detail = $this -> db_U8 ->getContactDetail($edited_contact_main);
		//  去合同明细，等字段确认再做。
		//	$edited_contact_inventory_detail = $this -> db_U8 ->getInventoryDetail($edited_contact_detail);
			$this -> assign("edited_contact_detail",$edited_contact_detail);
			$this -> assign("begin_date",$begin_date);
			$this -> assign("end_date",$end_date);
			$this -> display('ConflictPage');
		}
	}
	
	public function processData(){
		//删除可能修改过的。
		$check_list = $_POST['check_list'];
		$begin_date = $_POST['begin_date'];
		$end_date = $_POST['end_date'];
		$arr_check_list = explode(",",$check_list);
		$temp_count = count($arr_check_list,0);
		unset($arr_check_list[$temp_count-1]);
		//删除可能重复的数据。
		$this -> db_contact_main -> deleteItems($arr_check_list);
		$this -> db_contact_detail -> deleteItems($arr_check_list);
		$this -> _processData($begin_date, $end_date);
	}
	
	private function _processData($begin_date,$end_date){
		//去所有符合条件的数据插入commission的表中  contact_main  contact_detail   customer_fund  load_history
		$this -> db_U8 = D("U8");
		$all_contact_main = $this -> db_U8 -> getAllContactMain($begin_date,$end_date);
		// 插入 contact_main
		$this -> db_contact_main -> addContactMain($all_contact_main);
		//插入contact_detail  
		$all_contact_detail = $this -> db_U8 -> getContactDetail($all_contact_main);
		$this -> db_contact_detail -> addContactDetail($all_contact_detail);
		// 取得客户回款金额
		$customer_funds =  $this -> db_U8 ->getCustomerFunds($begin_date,$end_date);
		$this -> db_constomer_funds -> updateItems($customer_funds);
		// 插入load_history
		$this -> db_load_history -> addItem($begin_date,$end_date);
		$this -> loadSettleSummaryPage();
	}
	public function loadSettleSummaryPage(){
		$load_history = $this -> db_load_history -> getLastThreeHistory();
		$settlement_contact = $this -> db_contact_main -> getSettlementContact();
		$settlement_contact = $this -> db_customer -> addCustomerName($settlement_contact);
		$settlement_contact = $this -> _addSalesmanName($settlement_contact);
		$settlement_contact = $settlement_contact['data'];
		
		$settlement_contact_detail = $this -> db_contact_detail -> getContactDetail($settlement_contact);
		$this -> assign('settlement_contact_detail',$settlement_contact_detail);
		$this -> assign('load_history',$load_history);
		$this -> display('SettleSummaryPage');
	}
	public function ratioAdjust(){
		print_r("ratio adjust function!!");
	}
	public function getSettlementRatio(){
		$contact_main = $this -> db_contact_main -> getSettlementContact();
		$contact_detail = $this -> db_contact_detail ->getContactDetail($contact_main);
		$normal_business_ratio = $this -> db_normal_business_ratio -> getAllNormalBusinessRatio();
		$normal_profit_ratio = $this -> db_normial_profit_ratio -> getAllNormalProfitRatio();
		$arr_ratio = array();
		foreach ($contact_detail as $key => $value) {
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
		}
		$this -> db_contact_detail -> updateSettlementRatio($arr_ratio);
		$this -> loadSettleSummaryPage();
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
		$data['inventory_id'] = $_POST['add_new_inventory_id'];
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
		$data['classification_name'] = $_POST['add_new_name'];
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
		$data['classification_name'] = $_POST['edit_name'];
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
					$array_data[$key]['salesman_name'] = $vv['salesman_name'];
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