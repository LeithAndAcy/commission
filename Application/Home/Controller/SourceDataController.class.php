<?php
namespace Home\Controller;
use Think\Controller;
class SourceDataController extends Controller {
	
	private $db_salesman;
	private $db_normal_business_ratio;
	private $db_special_business_ratio;
	private $db_normial_profit_ratio;
	private $db_insurance_fund;
	private $db_price_float_ratio;
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
	}
    public function loadSourceDataPage(){
    	$this -> display('SourceDataPage');
	}
	
	public function loadSettleSummaryPage(){
		//取值，赋值给模板变量
		$this -> display('SettleSummaryPage');
	}
	public function loadNormalBusinessPage(){
		$all_normal_business_ratio = $this -> db_normal_business_ratio -> getAllNormalBusinessRatio();
		$res = $this -> _addSalesmanName($all_normal_business_ratio);
		$all_normal_business_ratio = $res['data'];
		$shanghai_salesmen = $res['shanghai'];
		$kunshan_salesmen = $res['kunshan'];
		$this -> assign("all_normal_business_ratio",$all_normal_business_ratio);
		$this -> assign("shanghai_salesmen",$shanghai_salesmen);
		$this -> assign("kunshan_salesmen",$kunshan_salesmen);
		$this -> display('NormalBusinessPage');
	}
	public function addNewNormalBusinessRatio(){
		$data = array();
		$data['salesman_id'] = $_POST['add_new_salesman_id'];
		$data['classification'] = $_POST['add_new_classification'];
		$data['specification'] = $_POST['add_new_specification'];
		$data['model'] = $_POST['add_new_model'];
		$data['ratio'] = $_POST['add_new_ratio'];
		
		$this -> db_normal_business_ratio -> addNormalBusinessRatio($data);
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
		$shanghai_salesmen = $res['shanghai'];
		$kunshan_salesmen = $res['kunshan'];
		$this -> assign("all_special_business_ratio",$all_special_business_ratio);
		$this -> assign("shanghai_salesmen",$shanghai_salesmen);
		$this -> assign("kunshan_salesmen",$kunshan_salesmen);
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
		$this -> db_special_business_ratio -> addSpecialBusinessRatio($data);
		$this -> loadNormalBusinessPage();
	}
	public function loadNormalProfitPage(){
		$all_normal_profit_ratio = $this -> db_normial_profit_ratio -> getAllNormalProfitRatio();
		$res = $this -> _addSalesmanName($all_normal_profit_ratio);
		$all_normal_profit_ratio = $res['data'];
		$shanghai_salesmen = $res['shanghai'];
		$kunshan_salesmen = $res['kunshan'];
		$this -> assign("all_normal_profit_ratio",$all_normal_profit_ratio);
		$this -> assign("shanghai_salesmen",$shanghai_salesmen);
		$this -> assign("kunshan_salesmen",$kunshan_salesmen);
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
		$data['specification'] = $_POST['add_new_specification'];
		$data['model'] = $_POST['add_new_model'];
		$data['ratio'] = $_POST['add_new_ratio'];
		$data['low_limit'] = $_POST['add_new_low_limit'];
		$data['high_limit'] = $_POST['add_new_high_limit'];
		$this -> db_normial_profit_ratio -> addNormalProfitRatio($data);
		$this -> loadNormalProfitPage();
	}
	public function deleteNormalProfitRatioById(){
		$id = $_POST['delete_id'];
		$this -> db_normial_profit_ratio -> deleteNormalProfitRatioById($id);
	}
	public function loadSpecialProfitPage(){
		$this -> display('SpecialProfitPage');
	}
	public function loadInsuranceAndFundPage(){
		$all_insurance_fund = $this -> db_insurance_fund -> getAllInsuranceFund();
		$res = $this -> _addSalesmanName($all_insurance_fund);
		$all_insurance_fund = $res['data'];
		$shanghai_salesmen = $res['shanghai'];
		$kunshan_salesmen = $res['kunshan'];
		$this -> assign("all_insurance_fund",$all_insurance_fund);
		$this -> assign("shanghai_salesmen",$shanghai_salesmen);
		$this -> assign("kunshan_salesmen",$kunshan_salesmen);
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
		// $strBegin = "货存编码的第";
		// $strEnd = "位数字";
		// $flag = 0;
		// foreach ($all_price_float_ratio as $key => $value) {
			// $index = strpos($value['condition'], '&');
			// if($index === false){
				// $all_price_float_ratio[$key]['condition'] = $strBegin.$all_price_float_ratio[$key]['condition'].$strEnd;
			// }else{
				// while($index !== false){
					// $temp_number = substr($all_price_float_ratio[$key]['condition'], $flag,$index-$flag).",";
					// $strBegin .= $temp_number;
					// $flag = $index+1;
					// $index = strpos($value['condition'], '&',$flag);
				// }
				// $temp_number = substr($all_price_float_ratio[$key]['condition'], $flag);
				// $all_price_float_ratio[$key]['condition'] = $strBegin.$temp_number.$strEnd;
				// $strBegin = "货存编码的第";
				// $flag = 0;
			// }
		// }
		$this -> assign("all_price_ratio_ratio",$all_price_float_ratio);
		$this -> display('PriceFloatPage');
	}
	public function addPriceFloatRatio(){
		
	}
	public function editPriceFloatRatio(){
			
	}
	public function deletePriceFloatRatio(){
		
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
		$this -> display('HumanWagePage');
	}
	public function loadSalesmanPage(){
		$all_salesmen = $this-> db_salesman ->getAllSalesmanInfo();
		foreach ($all_salesmen as $key => $value) {
			if($value['status'] == SHANGHAI){
				$all_salesmen[$key]['status'] = "上海";
			}elseif($value['status'] == KUNSHAN){
				$all_salesmen[$key]['status'] = "昆山";
			}
		}
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
					$flag = TRUE;
					break;
				}
			}
		}
		$shanghai_salesmen = array();
		$kunshan_salesmen = array();
		foreach ($all_salesmen as $key => $value) {
			if($value['status'] == SHANGHAI){
				$shanghai_salesmen[$key] = $value;
			}elseif($value['status'] == KUNSHAN){
				$kunshan_salesmen[$key] = $value;
			}
		}
		$res = array();
		$res['data'] = $array_data;
		$res['shanghai'] = $shanghai_salesmen;
		$res['kunshan'] = $kunshan_salesmen;
		return $res;
	}
	
}
?>