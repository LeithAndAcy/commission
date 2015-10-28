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
	private $db_area_price_float_ratio;
	private $db_special_approve_price_float_ratio;
	private $db_load_history;
	private $db_customer;
	private $db_U8;
	private $db_contact_main;
	private $db_contact_detail;
	private $db_constomer_funds;
	private $db_length_limit;
	private $db_funds_back;
	private $db_tax_ratio;
	private $db_wage_deduction;
	private $db_fee_ratio;
	private $db_sale_expense;
	private $db_salary;
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
		$this -> db_area_price_float_ratio = D("AreaPriceFloatRatio");
		$this -> db_special_approve_price_float_ratio = D("SpecialApprovePriceFloatRatio");
		$this -> db_special_profit_ratio = D("SpecialProfitRatio");
		$this -> db_load_history = D("LoadHistory");
		$this -> db_customer = D("Customer");
		$this -> db_contact_main = D("ContactMain");
		$this -> db_contact_detail = D("ContactDetail");
		$this -> db_constomer_funds = D("CustomerFunds");
		$this -> db_length_limit = D("LengthLimit");
		$this -> db_funds_back = D("FundsBack");
		$this -> db_tax_ratio = D("TaxRatio");
		$this -> db_wage_deduction = D("WageDeduction");
		$this -> db_fee_ratio = D("FeeRatio");
		$this -> db_sale_expense = D("SaleExpense");
		$this -> db_salary = D("Salary");
	}
    public function loadSourceDataPage(){
    	\Think\Log::record('测试日志信息');
    	$this -> display('SourceDataPage');
	}
	
	public function loadData(){
		$contact_date = $_POST['contact_date'];
		
		$begin_date = date('Y-m-01', strtotime($contact_date)); 
		$end_date = date('Y-m-d', strtotime("$begin_date +1 month -1 day")); 
		$last_end_date = $this -> db_load_history -> getLastEndDate();
		$today = date("Y-m-d");
		if($end_date >= $today){
			$this -> error("日期只能选择之前月份");
		}
		if($this -> db_salary->checkSalarySettled($contact_date)){
			// return true; already settled
			$this -> error("该月份工资已结算，不能重新导入");
		}
		if($this -> db_load_history ->checkLoadHistory($begin_date)){
			// return true; already loaded;
			$this -> error("该月份合同已导入，不能重新导入");
		}
		$this -> db_U8 = D("U8");
		$edited_contact_main = $this -> db_U8 ->getEditedContactMain($begin_date,$end_date);
		if($edited_contact_main == null){
			$this -> _processData($begin_date,$end_date);
		}else{
			$edited_contact_main = $this -> db_customer -> addCustomerName($edited_contact_main);
			$temp_edited_contact_mian = $this -> _addSalesmanName($edited_contact_main);
			$edited_contact_main = $temp_edited_contact_mian['data'];
		//	print_r($edited_contact_detail);
			$edited_contact_detail = $this -> db_U8 ->getContactDetail($edited_contact_main);
			$edited_contact_detail = $this -> db_U8 ->getInventoryDetailOfConflictPage($edited_contact_detail);
			
			$this -> assign("edited_contact_detail",$edited_contact_detail);
			$this -> assign("begin_date",$begin_date);
			$this -> assign("end_date",$end_date);
			$this -> display('ConflictPage');
		}
	}
	
	public function processData(){
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
		$this -> db_contact_main -> addContactMain($all_contact_main,$begin_date);
		//取得存货信息
		$all_contact_detail = $this -> db_U8 -> getContactDetailByDate($begin_date,$end_date);
		
		//插入contact_detail
		$this -> db_contact_detail -> addContactDetail($all_contact_detail,$begin_date);
		// 取得客户回款金额
		$customer_funds =  $this -> db_U8 ->getCustomerFunds($begin_date,$end_date);
		
		$this -> db_constomer_funds -> updateItems($customer_funds);
		// 插入load_history
		$this -> db_load_history -> addItem($begin_date,$end_date,count($all_contact_main),count($all_contact_detail));
		$this -> loadSettleSummaryPage();
	}
	public function setManualContact(){
		$contact_id = $_POST['contact_id'];
		$this -> db_contact_main->setManualContact($contact_id);
	}
	public function loadSettleSummaryPage(){
		$this -> db_U8 = D("U8");
		$load_history = $this -> db_load_history -> getLastThreeHistory();
		$count_settlement_contact = $this -> db_contact_main -> countSettlementContact();
		$count_settlement_contact_detail = $this -> db_contact_main -> countSettlementContactDetail();
		$Page = new \Think\Page($count_settlement_contact,100);
		$show = $Page->show();// 分页显示输出
		$settlement_contact = $this -> db_contact_main -> getSettlementContact($Page);
		
		// $settlement_contact = $this -> db_customer -> addCustomerName($settlement_contact);
		// $settlement_contact = $this -> _addSalesmanName($settlement_contact);
		// $settlement_contact = $settlement_contact['data'];
		
		$settlement_contact_detail = $this -> db_contact_detail -> getContactDetail($settlement_contact);
		$this -> assign('count_settlement_contact',$count_settlement_contact);
		$this -> assign('count_settlement_contact_detail',$count_settlement_contact_detail);
		$this -> assign('settlement_contact_detail',$settlement_contact_detail);
		$this -> assign('page',$show);
		$this -> assign('load_history',$load_history);
		$this -> display('SettleSummaryPage');
	}
	public function ratioAdjust(){
		$contact_id = $_POST['edit_contact_id'];
		$inventory_id = $_POST['edit_inventory_id'];
		$business_adjust = $_POST['edit_business_adjust'];
		$profit_adjust =  $_POST['edit_profit_adjust'];
		$cost_price_adjust = $_POST['edit_cost_price_adjust'];
		$this -> db_contact_detail -> updateAdjust($contact_id,$inventory_id,$business_adjust,$profit_adjust,$cost_price_adjust);
		$this -> db_contact_main -> setContactEdited($contact_id);
		$this -> loadSettleSummaryPage();
	}
	public function getSettlementRatio(){
		$this -> db_U8 = D("U8");
		// $contact_main = $this -> db_contact_main -> getSettlementContact();
		// $contact_detail = $this -> db_contact_detail ->getContactDetail($contact_main);
		$contact_detail = $this -> db_contact_main -> getSettlementContactDetail();
		//$contact_detail = $this -> db_U8 -> getInventoryDetail($contact_detail);
		$normal_business_ratio = $this -> db_normal_business_ratio -> getAllHandledNormalBusinessRatio();
		$normal_profit_ratio = $this -> db_normial_profit_ratio -> getAllNormalProfitRatio();
		$price_float_ratio = $this -> db_price_float_ratio -> getAllPriceFloatRatio();
		$all_sale_expense = $this -> db_sale_expense -> getAllHandledSaleExpense();
		$arr_ratio = array();
		foreach ($contact_detail as $key => $value) {
			$salesman_id = $value['salesman_id'];
			$arr_ratio[$key]['salesman_id'] = $value['salesman_id'];
			$arr_ratio[$key]['contact_id'] = $value['contact_id'];
			$temp_inventory_id = substr($value['inventory_id'], 0,1) ;
			$arr_ratio[$key]['normal_business_ratio'] = $normal_business_ratio[$salesman_id][$temp_inventory_id];
			if($arr_ratio[$key]['normal_business_ratio'] == null){
				$arr_ratio[$key]['normal_business_ratio'] = $normal_business_ratio[$salesman_id]['其他'];
			}
			$arr_ratio[$key]['inventory_id'] = $value['inventory_id'];
			foreach ($normal_profit_ratio as $kkk => $vvv) {
				if($value['salesman_id'] == $vvv['salesman_id']){
					$arr_ratio[$key]['normal_profit_ratio'] = $vvv['ratio'];
					break;
				}
			}
			//使用 join直接查出来地区浮动比例
			$area_price_float_ratio = $value['ratio'];
			foreach ($price_float_ratio as $kkkk => $vvvv) {
				if($vvvv['classification_id'] == $contact_detail[$key]['classification_id'] &&
				$vvvv['low_price'] <= $contact_detail[$key]['cost_price'] && $vvvv['high_price'] > $contact_detail[$key]['cost_price'] &&
				$vvvv['low_length'] <= $contact_detail[$key]['delivery_quantity'] && $vvvv['high_length'] > $contact_detail[$key]['delivery_quantity']){
					$arr_ratio[$key]['float_price'] = ($vvvv['ratio'] * 0.01 + $area_price_float_ratio) * $contact_detail[$key]['cost_price'];
					$arr_ratio[$key]['end_cost_price'] = $arr_ratio[$key]['float_price'] + $contact_detail[$key]['cost_price'] + $contact_detail[$key]['cost_price_adjust'];
					$arr_ratio[$key]['float_price_ratio'] = $vvvv['ratio']* 0.01; 
					break;
				}else{
					$arr_ratio[$key]['float_price'] = $area_price_float_ratio * $contact_detail[$key]['cost_price'];
					$arr_ratio[$key]['end_cost_price'] = ($contact_detail[$key]['cost_price'] + $arr_ratio[$key]['float_price'] + $contact_detail[$key]['cost_price_adjust']);
					$arr_ratio[$key]['float_price_ratio'] = 0;
				}
			}
			$temp_bPurchase = $value['purchase'];
			//取销售费用以及销售费用比例
			$arr_ratio[$key]['sale_expense'] = $all_sale_expense[$salesman_id][$value['contact_id']][$value['inventory_id']]['sale_expense'];
			$arr_ratio[$key]['sale_expense_ratio'] = $all_sale_expense[$salesman_id][$value['contact_id']][$value['inventory_id']]['sale_expense_ratio'];
			if($temp_bPurchase){
			//	$arr_ratio[$key]['normal_profit_ratio'] = 100;
			}else{
				if($arr_ratio[$key]['sale_expense'] >0){
			//		$arr_ratio[$key]['normal_profit_ratio'] = 50;
				}
			}
		}
		$this -> db_contact_detail -> updateSettlementRatio($arr_ratio);
	}
	public function deleteContact(){
		$contact_id = $_POST['contact_id'];
		$inventory_id = $_POST['inventory_id'];
		$this -> db_contact_main -> deleteItem($contact_id);
		$this -> db_contact_detail -> deleteItem($contact_id,$inventory_id);
	}
	public function loadNormalBusinessPage(){
		$this -> db_U8 = D("U8");
		$all_normal_business_ratio = $this -> db_normal_business_ratio -> getAllNormalBusinessRatio();
		$res = $this -> _addSalesmanName($all_normal_business_ratio);
		$all_normal_business_ratio = $res['data'];
		$all_salesman = $res['salesman'];
		// $all_normal_business_ratio = $this -> db_U8 -> getInventoryDetail($all_normal_business_ratio);
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
		$data['inventory_id'] = $_POST['add_new_inventory_id'];
		$temp = $this -> db_special_business_ratio -> addSpecialBusinessRatio($data);
		if($temp == FALSE){
			$this -> error("回款区间有重复，请重新输入!!",'/commission/index.php/Home/SourceData/loadSpecialBusinessPage',3);
		}else{
			$this -> loadSpecialBusinessPage();
		}
	}
	public function loadNormalProfitPage(){
		$this -> db_U8 = D("U8");
		$all_normal_profit_ratio = $this -> db_normial_profit_ratio -> getAllNormalProfitRatio();
		$res = $this -> _addSalesmanName($all_normal_profit_ratio);
		$all_normal_profit_ratio = $res['data'];
		$all_normal_profit_ratio = $this -> db_U8 -> getInventoryDetail($all_normal_profit_ratio);
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
	public function loadFeeRatioPage(){
		$all_fee_ratio = $this -> db_fee_ratio -> getAllFeeRatio();
		// $all_fee_ratio = $this -> db_salesman -> addSalesmanName($all_fee_ratio);
		// $all_salesman = $this -> db_salesman -> getAllSalesman();
		// $this -> assign("all_salesman",$all_salesman);
		$this -> assign("all_fee_ratio",$all_fee_ratio);
		$this -> display("AllFeeRatioPage");
	}
	public function editFeeRatio(){
		$id = $_POST['edit_id'];
		$fee_ratio = $_POST['edit_ratio'];
		$this -> db_fee_ratio -> editItem($id,$fee_ratio);
		$this -> loadFeeRatioPage();
	}
	public function addFeeRatio(){
		$data = array();
		$data['salesman_id'] = $_POST['add_new_salesman_id'];
		$data['fee_ratio'] = $_POST['add_new_ratio'];
		$this -> db_fee_ratio -> addItem($data);
		$this -> loadFeeRatioPage();
	}
	public function deleteFeeRatioById(){
		$id = $_POST['delete_id'];
		$this -> db_fee_ratio -> deleteItem($id);
	}
	public function loadSaleExpensePage(){
		$all_sale_expense = $this -> db_sale_expense -> getAllItems();
		$res = $this ->  _addSalesmanName($all_sale_expense);
		$all_sale_expense = $res['data'];
		$all_salesman = $res['salesman'];
		$this -> assign("all_salesman",$all_salesman);
		$this -> assign("all_sale_expense",$all_sale_expense);
		$this -> display('SaleExpensePage');
		
	}
	public function editSaleExpense(){
		$id = $_POST['edit_id'];
		$sale_expense = $_POST['edit_sale_expense'];
		$ratio = $_POST['edit_ratio'];
		$this -> db_sale_expense -> editItem($id,$sale_expense,$ratio);
		$this -> loadSaleExpensePage();
	}
	public function addSaleExpense(){
		$data = array();
		$data['salesman_id'] = $_POST['add_new_salesman_id'];
		$data['inventory_id'] = $_POST['add_new_inventory_id'];
		$data['sale_expense'] = $_POST['add_new_sale_expense'];
		$data['contact_id'] = $_POST['add_new_contact_id'];
		$data['ratio'] = $_POST['add_new_ratio'];
		$this -> db_sale_expense -> addItem($data);
		$this -> loadSaleExpensePage();
	}
	public function deleteSaleExpenseById(){
		$id = $_POST['delete_id'];
		$this -> db_sale_expense -> deleteItem($id);
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
		}else{
			$this -> loadSpecialProfitPage();
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
		$this -> assign("all_salesman",$all_salesman);
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
		$this -> loadInsuranceAndFundPage();
	}
	public function deleteInsuranceFund(){
		$id = $_POST['delete_id'];
		$this -> db_insurance_fund -> deleteItemById($id);
	}
	
	public function loadPriceFloatPage(){
		$this -> db_U8 = D("U8");
		$all_price_float_ratio = $this -> db_price_float_ratio ->getAllPriceFloatRatio();
		$all_price_float_ratio = $this -> db_U8 -> getClassificationName($all_price_float_ratio);
		$this -> assign("all_price_ratio_ratio",$all_price_float_ratio);
		$this -> display('PriceFloatPage');
	}
	public function addPriceFloatRatio(){
		$data['classification_id'] = $_POST['add_new_classification_id'];
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
		$data['ratio'] = $_POST['edit_ratio'];
		$this -> db_price_float_ratio ->editItem($id,$data);
		$this -> loadPriceFloatPage();
	}
	public function deletePriceFloatRatio(){
		$delete_id = $_POST['delete_id'];
		$this -> db_price_float_ratio -> deleteItemById($delete_id);
	}
	public function loadAreaPriceFloatPage(){
		$this -> db_U8 = D("U8");
		$all_area_price_float_ratio = $this -> db_area_price_float_ratio ->getAllAreaPriceFloatRatio();
		$all_area_price_float_ratio = $this -> db_U8 -> getClassificationName($all_area_price_float_ratio);
		$this -> assign("all_area_price_ratio_ratio",$all_area_price_float_ratio);
		$this -> display('AreaPriceFloatPage');
	}
	public function addAreaPriceFloatRatio(){
		$data['classification_id'] = $_POST['add_new_classification_id'];
		$data['area'] = $_POST['add_new_area'];
		$data['ratio'] = $_POST['add_new_ratio'];
		$this -> db_area_price_float_ratio -> addItem($data);
		$this -> loadAreaPriceFloatPage();
	}
	public function editAreaPriceFloatRatio(){
		$id = $_POST['edit_id'];
		$data['ratio'] = $_POST['edit_ratio'];
		$this -> db_area_price_float_ratio ->editItem($id,$data);
		$this -> loadAreaPriceFloatPage();
	}
	public function deleteAreaPriceFloatRatio(){
		$delete_id = $_POST['delete_id'];
		$this -> db_area_price_float_ratio -> deleteItemById($delete_id);
	}
	public function loadSpecialApprovePriceFloatPage(){
		
		$special_approve_price_float_ratio = $this -> db_special_approve_price_float_ratio -> getAllItems();
		$this -> assign("special_approve_price_float_ratio",$special_approve_price_float_ratio);
		$this -> display('SpecialApprovePriceFloatPage');
	}
	public function addSpecialApprovePriceFloatRatio(){
		$data['classification_id'] = $_POST['add_new_classification_id'];
		$data['customer_id'] = $_POST['add_new_customer_id'];
		$data['ratio'] = $_POST['add_new_ratio'];
		$this -> db_special_approve_price_float_ratio ->  addItem($data);
		$this -> loadSpecialApprovePriceFloatPage();
	}
	public function deleteSpecialApprovePriceFloatRatio(){
		$delete_id = $_POST['delete_id'];
		$this -> db_special_approve_price_float_ratio -> deleteItemById($delete_id);
	}
	public function editSpecialApprovePriceFloatRatio(){
		$id = $_POST['edit_id'];
		$data['ratio'] = $_POST['edit_ratio'];
		$this -> db_special_approve_price_float_ratio ->editItem($id,$data);
		$this -> loadSpecialApprovePriceFloatPage();
	}
	public function loadLengthLimitPage(){
		$all_length_limit = $this -> db_length_limit -> getAllItems();
		$this -> assign("all_length_limit",$all_length_limit);
		$this -> display('LengthLimitPage');
	}
	public function editLengthLimit(){
		$id = $_POST['edit_id'];
		$limit = $_POST['edit_limit'];
		$this -> db_length_limit -> editItem($id,$limit);
		$this -> loadLengthLimitPage();
	}
	public function addLengthLimit(){
		$data = array();
		$data['low_length'] = $_POST['add_new_low_length'];
		$data['high_length'] = $_POST['add_new_high_length'];
		$data['limit'] = $_POST['add_new_limit'];
		$temp =  $this -> db_length_limit -> addItem($data);
		if($temp){
			$this -> loadLengthLimitPage();
		}else{
			$this -> error("长度区间重复，请重新输入!!",'/commission/index.php/Home/SourceData/addLengthLimit',3);
		}
		
	}
	public function deleteLengthLimit(){
		$id = $_POST['delete_id'];
		$this -> db_length_limit -> deleteItem($id);
	}
	public function loadFundsBackPage(){
		$all_funds_back = $this -> db_funds_back -> getAllItems();
		$all_funds_back = $this -> db_customer -> addCustomerName($all_funds_back);
		$this -> assign('all_funds_back',$all_funds_back);
		$this -> display('FundsBackPage');
	}
	public function addFundsBack(){
		$data = array();
		$data['customer_id'] =  $_POST['add_new_customer_id'];
		$data['funds_back_money'] = $_POST['add_new_funds_back_money'];
		$this -> db_funds_back -> addItem($data);
		$this -> loadFundsBackPage();
	}
	public function editFundsBack(){
		$id = $_POST['edit_id'];
		$funds_back_money = $_POST['edit_funds_back_money'];
		$this -> db_funds_back -> editItem($id,$funds_back_money);
		$this -> loadFundsBackPage();
	}
	public function deleteFundsBack(){
		$id = $_POST['delete_id'];
		$this -> db_funds_back -> deleteItem($id);
	}
	public function loadHumanWagePage(){
		$month = date('Y-m',strtotime('-1 month'));
		$all_wage_deduction = $this -> db_wage_deduction -> getItemsByMonth($month);
		$res = $this -> _addSalesmanName($all_wage_deduction);
		$all_wage_deduction = $res['data'];
		$this -> assign("all_wage_deduction",$all_wage_deduction);
		$this -> display('HumanWagePage');
	}
	public function addHumanWagePage(){
		
	}
	public function editHumanWagePage(){
		
	}
	public function deleteWageDeduction(){
		$id = $_POST['delete_id'];
		$this -> db_wage_deduction -> deleteItem($id);
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
		$all_tax_ratio = $this -> db_tax_ratio -> getAllItems();
		$tax_beginning_point = $this -> db_tax_ratio -> getTaxBeginningPoint();
		$this -> assign("tax_beginning_point",$tax_beginning_point);
		$this -> assign("all_tax_ratio",$all_tax_ratio);
		$this ->display('TaxPage');
	}
	public function editTaxBeginningPoint(){
		$tax_beginning_point = $_POST['edit_tax_beginning_point'];
		$this -> db_tax_ratio -> editTaxBeginningPoint($tax_beginning_point);
		$this -> loadTaxPage();
	}
	public function editTaxRatio(){
		$id = $_POST['edit_id'];
		$ratio = $_POST['edit_ratio'];
		$this -> db_tax_ratio -> editItem($id,$ratio);
		$this -> loadTaxPage();
	}
	public function addTaxRatio(){
		$data = array();
		$data['low_limit'] = $_POST['add_new_low_limit'];
		$data['high_limit'] = $_POST['add_new_high_limit'];
		$data['ratio'] = $_POST['add_new_ratio'];
		$temp = $this -> db_tax_ratio -> addItem($data);
		if($temp){
			$this -> loadTaxPage();
		}else{
			$this -> error("工资区间重复，请重新输入!!",'/commission/index.php/Home/SourceData/loadTaxPage',3);
		}
		
	}
	public function deleteTaxRatioById(){
		$id = $_POST['delete_id'];
		$this -> db_tax_ratio -> deleteItem($id);
	}
	public function loadCustomerPage(){
		$count_all_customer = $this -> db_customer -> count();
		$Page = new \Think\Page($count_all_customer,1000);
		$show = $Page->show();
		$all_customer = $this -> db_customer ->getAllCustomer($Page);
		$this -> assign("all_customer",$all_customer);
		$this -> assign('page',$show);
		$this -> display('CustomerPage');
	}
	public function renewCustomer(){
		$this -> db_U8 = D("U8");
		$all_customer_area = $this -> db_U8 -> getAllCustomerAndArea();
		$this -> db_customer -> renewItems($all_customer_area);
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
		$data['onboard_status'] = $_POST['edit_onboard_status'];
		$data['shanghai_salary'] = $_POST['edit_shanghai_salary'];
		$data['kunshan_salary'] = $_POST['edit_kunshan_salary'];
		
		$this -> db_salesman ->editSalesman($id,$data);
		$this -> loadSalesmanPage();
	}
	public function addSalesman(){
		$data = array();
		$data['salesman_id'] = $_POST['add_new_salesman_id'];
		$data['salesman_name'] = $_POST['add_new_name'];
		$data['status'] = $_POST['add_new_status'];
		$data['onboard_status'] = $_POST['add_new_onboard_status'];
		$data['shanghai_salary'] = $_POST['add_new_shanghai_salary'];
		$data['kunshan_salary'] = $_POST['add_new_kunshan_salary'];
		$this -> db_salesman -> addItem($data);
		$this -> loadSalesmanPage();
	}
	public function loadLoadHistoryPage(){
		$load_history = $this -> db_load_history -> getAllItems();
		$this -> assign("load_history",$load_history);
		$this -> display('LoadHistoryPage');
	}
	public function loadTotalCustomerFundsPage(){
		$total_customer_funds = $this -> db_constomer_funds -> getTotalCustomerFunds();
		$total_customer_funds = $this -> db_customer -> addCustomerName($total_customer_funds);
		$this -> assign("total_customer_funds",$total_customer_funds);
		$this -> display('TotalCustomerFundsPage');
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
	public function complicateSearch(){
		$condition = array();
		$temp_array = array();
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
		if($res == null){
			$this -> error("无符合的数据");
		}
		$count_settlement_contact = 0;
		$count_settlement_contact_detail = count($res);
		foreach ($res as $key => $value) {
			$temp =  $this -> db_contact_main -> getSettlementContactByContactId($value['contact_id']);
			if($temp == null){
				unset($res[$key]);
			}else{
				if(!in_array($value['contact_id'], $temp_array)){
					array_push($temp_array,$value['contact_id']);
				}
			}
		}
		// $res = $this -> db_salesman -> addSalesmanName($res);
		// $res = $this -> db_customer -> addCustomerName($res);
		$count_settlement_contact = count($temp_array);
		$load_history = $this -> db_load_history -> getLastThreeHistory();
		$this -> assign('count_settlement_contact',$count_settlement_contact);
		$this -> assign('count_settlement_contact_detail',$count_settlement_contact_detail);
		$this -> assign('settlement_contact_detail',$res);
		$this -> assign('load_history',$load_history);
		$this -> display('SettleSummaryPage');
	}
}
?>