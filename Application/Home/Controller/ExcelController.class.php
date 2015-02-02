<?php
namespace Home\Controller;
use Think\Controller;
class ExcelController extends Controller {
	
	private $db_salesman;
	private $db_normal_business_ratio;
	private $db_special_business_ratio;
	private $db_normial_profit_ratio;
	private $db_special_profit_ratio;
	private $db_insurance_fund;
	private $db_price_float_ratio;
	private $db_load_history;
	private $db_customer;
	private $db_contact_main;
	private $db_contact_detail;
	private $db_constomer_funds;
	private $db_length_limit;
	private $db_funds_back;
	private $db_tax_ratio;
	private $db_U8;
	private $db_wage_deduction;
	function _initialize() {
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
		$this -> db_length_limit = D("LengthLimit");
		$this -> db_funds_back = D("FundsBack");
		$this -> db_tax_ratio = D("TaxRatio");
		$this -> db_wage_deduction = D("WageDeduction");
	}
	//import
	public function loadPriceFloatExcel() {
		$excel_name = $_FILES['excel_file']['name'];
		$index = stripos($excel_name, ".");
		if (strtolower(substr($excel_name, $index + 1)) != "xls" && strtolower(substr($excel_name, $index + 1)) != "xlsx") {
			$this -> error("上传文件格式出错");
		}
		vendor('PHPExcel.PHPExcel');
		Vendor('PHPExcel.PHPExcel.IOFactory');
		Vendor('PHPExcel.PHPExcel.Reader.Excel5.php');
		$PHPReader = new \PHPExcel_Reader_Excel5();
		$PHPexcel = new \PHPExcel();
		$excel_obj = $_FILES['excel_file']['tmp_name'];
		$PHPExcel_obj = $PHPReader -> load($excel_obj);
		$currentSheet = $PHPExcel_obj -> getSheet(0);
		$highestColumn = $currentSheet -> getHighestColumn();
		$highestRow = $currentSheet -> getHighestRow();
		$arr_price_float_ratio = array();
		for ($j = 2; $j <= $highestRow; $j++)
		{
			for ($k = 'B'; $k <= $highestColumn; $k++)
			{
				$arr_normal_profir_ratio[$j][$k] = $PHPExcel_obj -> getActiveSheet() -> getCell("$k$j") -> getValue();
			}
		}
		foreach ($arr_normal_profir_ratio as $key => $value) {
			unset($arr_normal_profir_ratio[$key]);
			$arr_price_float_ratio[$key]['classification_id'] = $value['B'];
			$arr_price_float_ratio[$key]['classification_name'] = $value['C'];
			$arr_price_float_ratio[$key]['low_price'] = $value['D'];
			$arr_price_float_ratio[$key]['high_price'] = $value['E'];
			$arr_price_float_ratio[$key]['low_length'] = $value['F'];
			$arr_price_float_ratio[$key]['high_length'] = $value['G'];
			$arr_price_float_ratio[$key]['ratio'] = $value['H'];
			$this -> db_price_float_ratio -> addItem($arr_price_float_ratio[$key]);
		}
		$this -> success("添加成功！");
	}
	public function importCustomersInfoExcel() {
		$excel_name = $_FILES['excel_file']['name'];
		$index = stripos($excel_name, ".");
		if (strtolower(substr($excel_name, $index + 1)) != "xls" && strtolower(substr($excel_name, $index + 1)) != "xlsx") {
			$this -> error("上传文件格式出错");
		}
		vendor('PHPExcel.PHPExcel');
		Vendor('PHPExcel.PHPExcel.IOFactory');
		Vendor('PHPExcel.PHPExcel.Reader.Excel5.php');
		$PHPReader = new \PHPExcel_Reader_Excel5();
		$PHPexcel = new \PHPExcel();
		$excel_obj = $_FILES['excel_file']['tmp_name'];
		$PHPExcel_obj = $PHPReader -> load($excel_obj);
		$currentSheet = $PHPExcel_obj -> getSheet(0);
		$highestColumn = $currentSheet -> getHighestColumn();
		$highestRow = $currentSheet -> getHighestRow();
		$arr_customers_info = array();
		for ($j = 2; $j <= $highestRow; $j++)
		{
			for ($k = 'B'; $k <= $highestColumn; $k++)
			{
				$arr_customers_info[$j][$k] = $PHPExcel_obj -> getActiveSheet() -> getCell("$k$j") -> getValue();
			}
		}
		foreach ($arr_customers_info as $key => $value) {
			unset($arr_customers_info[$key]);
			$arr_customers_info[$key]['customer_id'] = $value['B'];
			$arr_customers_info[$key]['customer_name'] = $value['C'];
			$this -> db_customer -> addItem($arr_customers_info[$key]);
		}
		$this -> success("添加成功！");
	}
	public function importSalesmanInfoExcel() {
		$excel_name = $_FILES['excel_file']['name'];
		$index = stripos($excel_name, ".");
		if (strtolower(substr($excel_name, $index + 1)) != "xls" && strtolower(substr($excel_name, $index + 1)) != "xlsx") {
			$this -> error("上传文件格式出错");
		}
		vendor('PHPExcel.PHPExcel');
		Vendor('PHPExcel.PHPExcel.IOFactory');
		Vendor('PHPExcel.PHPExcel.Reader.Excel5.php');
		$PHPReader = new \PHPExcel_Reader_Excel5();
		$PHPexcel = new \PHPExcel();
		$excel_obj = $_FILES['excel_file']['tmp_name'];
		$PHPExcel_obj = $PHPReader -> load($excel_obj);
		$currentSheet = $PHPExcel_obj -> getSheet(0);
		$highestColumn = $currentSheet -> getHighestColumn();
		$highestRow = $currentSheet -> getHighestRow();
		$arr_salesman_info = array();
		for ($j = 2; $j <= $highestRow; $j++)//从第2行开始读取数据
		{
			for ($k = 'B'; $k <= $highestColumn; $k++)//从B列读取数据
			{
				$arr_salesman_info[$j][$k] = $PHPExcel_obj -> getActiveSheet() -> getCell("$k$j") -> getValue();
			}
		}
		foreach ($arr_salesman_info as $key => $value) {
			unset($arr_salesman_info[$key]);
			$arr_salesman_info[$key]['salesman_id'] = $value['B'];
			$arr_salesman_info[$key]['salesman_name'] = $value['C'];
			$arr_salesman_info[$key]['status'] = $value['D'];
			$arr_salesman_info[$key]['onboard_status'] = $value['E'];
			$arr_salesman_info[$key]['shanghai_salary'] = $value['F'];
			$arr_salesman_info[$key]['kunshan_salary'] = $value['G'];
			$this -> db_salesman -> addItem($arr_salesman_info[$key]);
		}
		$this -> success("添加成功！");
	}
	public function importInsuranceAndFundExcel() {
		$excel_name = $_FILES['excel_file']['name'];
		$index = stripos($excel_name, ".");
		if (strtolower(substr($excel_name, $index + 1)) != "xls" && strtolower(substr($excel_name, $index + 1)) != "xlsx") {
			$this -> error("上传文件格式出错");
		}
		vendor('PHPExcel.PHPExcel');
		Vendor('PHPExcel.PHPExcel.IOFactory');
		Vendor('PHPExcel.PHPExcel.Reader.Excel5.php');
		$PHPReader = new \PHPExcel_Reader_Excel5();
		$PHPexcel = new \PHPExcel();
		$excel_obj = $_FILES['excel_file']['tmp_name'];
		$PHPExcel_obj = $PHPReader -> load($excel_obj);
		$currentSheet = $PHPExcel_obj -> getSheet(0);
		$highestColumn = $currentSheet -> getHighestColumn();
		$highestRow = $currentSheet -> getHighestRow();
		$arr_insurance_fund = array();
		for ($j = 2; $j <= $highestRow; $j++)//从第2行开始读取数据
		{
			for ($k = 'B'; $k <= $highestColumn; $k++)//从B列读取数据
			{
				$arr_insurance_fund[$j][$k] = $PHPExcel_obj -> getActiveSheet() -> getCell("$k$j") -> getValue();
			}
		}
		foreach ($arr_insurance_fund as $key => $value) {
			unset($arr_insurance_fund[$key]);
			$arr_insurance_fund[$key]['salesman_id'] = $value['B'];
			$arr_insurance_fund[$key]['insurance'] = $value['C'];
			$arr_insurance_fund[$key]['fund'] = $value['D'];
			$this -> db_insurance_fund -> addItem($arr_insurance_fund[$key]);
		}
		$this -> success("添加成功！");
	}
	public function importTaxRatioExcel() {
		$excel_name = $_FILES['excel_file']['name'];
		$index = stripos($excel_name, ".");
		if (strtolower(substr($excel_name, $index + 1)) != "xls" && strtolower(substr($excel_name, $index + 1)) != "xlsx") {
			$this -> error("上传文件格式出错");
		}
		vendor('PHPExcel.PHPExcel');
		Vendor('PHPExcel.PHPExcel.IOFactory');
		Vendor('PHPExcel.PHPExcel.Reader.Excel5.php');
		$PHPReader = new \PHPExcel_Reader_Excel5();
		$PHPexcel = new \PHPExcel();
		$excel_obj = $_FILES['excel_file']['tmp_name'];
		$PHPExcel_obj = $PHPReader -> load($excel_obj);
		$currentSheet = $PHPExcel_obj -> getSheet(0);
		$highestColumn = $currentSheet -> getHighestColumn();
		$highestRow = $currentSheet -> getHighestRow();
		$arr_tax_ratio = array();
		for ($j = 2; $j <= $highestRow; $j++)//从第2行开始读取数据
		{
			for ($k = 'B'; $k <= $highestColumn; $k++)//从B列读取数据
			{
				$arr_tax_ratio[$j][$k] = $PHPExcel_obj -> getActiveSheet() -> getCell("$k$j") -> getValue();
			}
		}
		foreach ($arr_tax_ratio as $key => $value) {
			unset($arr_tax_ratio[$key]);
			$arr_tax_ratio[$key]['low_limit'] = $value['B'];
			$arr_tax_ratio[$key]['high_limit'] = $value['C'];
			$arr_tax_ratio[$key]['ratio'] = $value['D'];
			$this -> db_tax_ratio -> addItem($arr_tax_ratio[$key]);
		}
		$this -> success("添加成功！");
	}
	public function importLengthLimitExcel() {
		$excel_name = $_FILES['excel_file']['name'];
		$index = stripos($excel_name, ".");
		if (strtolower(substr($excel_name, $index + 1)) != "xls" && strtolower(substr($excel_name, $index + 1)) != "xlsx") {
			$this -> error("上传文件格式出错");
		}
		vendor('PHPExcel.PHPExcel');
		Vendor('PHPExcel.PHPExcel.IOFactory');
		Vendor('PHPExcel.PHPExcel.Reader.Excel5.php');
		$PHPReader = new \PHPExcel_Reader_Excel5();
		$PHPexcel = new \PHPExcel();
		$excel_obj = $_FILES['excel_file']['tmp_name'];
		$PHPExcel_obj = $PHPReader -> load($excel_obj);
		$currentSheet = $PHPExcel_obj -> getSheet(0);
		$highestColumn = $currentSheet -> getHighestColumn();
		$highestRow = $currentSheet -> getHighestRow();
		$arr_length_limit = array();
		for ($j = 2; $j <= $highestRow; $j++)//从第2行开始读取数据
		{
			for ($k = 'B'; $k <= $highestColumn; $k++)//从B列读取数据
			{
				$arr_length_limit[$j][$k] = $PHPExcel_obj -> getActiveSheet() -> getCell("$k$j") -> getValue();
			}
		}
		foreach ($arr_length_limit as $key => $value) {
			unset($arr_length_limit[$key]);
			$arr_length_limit[$key]['low_length'] = $value['B'];
			$arr_length_limit[$key]['high_length'] = $value['C'];
			$arr_length_limit[$key]['limit'] = $value['D'];
			$this -> db_length_limit -> addItem($arr_length_limit[$key]);
		}
		$this -> success("添加成功！");
	}
	public function importFundsBackExcel() {
		$excel_name = $_FILES['excel_file']['name'];
		$index = stripos($excel_name, ".");
		if (strtolower(substr($excel_name, $index + 1)) != "xls" && strtolower(substr($excel_name, $index + 1)) != "xlsx") {
			$this -> error("上传文件格式出错");
		}
		vendor('PHPExcel.PHPExcel');
		Vendor('PHPExcel.PHPExcel.IOFactory');
		Vendor('PHPExcel.PHPExcel.Reader.Excel5.php');
		$PHPReader = new \PHPExcel_Reader_Excel5();
		$PHPexcel = new \PHPExcel();
		$excel_obj = $_FILES['excel_file']['tmp_name'];
		$PHPExcel_obj = $PHPReader -> load($excel_obj);
		$currentSheet = $PHPExcel_obj -> getSheet(0);
		$highestColumn = $currentSheet -> getHighestColumn();
		$highestRow = $currentSheet -> getHighestRow();
		$arr_funds_back = array();
		for ($j = 2; $j <= $highestRow; $j++)//从第2行开始读取数据
		{
			for ($k = 'B'; $k <= $highestColumn; $k++)//从B列读取数据
			{
				$arr_funds_back[$j][$k] = $PHPExcel_obj -> getActiveSheet() -> getCell("$k$j") -> getValue();
			}
		}
		foreach ($arr_funds_back as $key => $value) {
			unset($arr_funds_back[$key]);
			$arr_funds_back[$key]['customer_id'] = $value['B'];
			$arr_funds_back[$key]['funds_back_money'] = $value['C'];
			$this -> db_funds_back -> addItem($arr_funds_back[$key]);
		}
		$this -> success("添加成功！");
	}
	public function importSpecialProfitRatioExcel() {
		$excel_name = $_FILES['excel_file']['name'];
		$index = stripos($excel_name, ".");
		if (strtolower(substr($excel_name, $index + 1)) != "xls" && strtolower(substr($excel_name, $index + 1)) != "xlsx") {
			$this -> error("上传文件格式出错");
		}
		vendor('PHPExcel.PHPExcel');
		Vendor('PHPExcel.PHPExcel.IOFactory');
		Vendor('PHPExcel.PHPExcel.Reader.Excel5.php');
		$PHPReader = new \PHPExcel_Reader_Excel5();
		$PHPexcel = new \PHPExcel();
		$excel_obj = $_FILES['excel_file']['tmp_name'];
		$PHPExcel_obj = $PHPReader -> load($excel_obj);
		$currentSheet = $PHPExcel_obj -> getSheet(0);
		$highestColumn = $currentSheet -> getHighestColumn();
		$highestRow = $currentSheet -> getHighestRow();
		$arr_special_profit_ratio = array();
		for ($j = 2; $j <= $highestRow; $j++)//从第2行开始读取数据
		{
			for ($k = 'B'; $k <= $highestColumn; $k++)//从B列读取数据
			{
				$arr_special_profit_ratio[$j][$k] = $PHPExcel_obj -> getActiveSheet() -> getCell("$k$j") -> getValue();
			}
		}
		foreach ($arr_special_profit_ratio as $key => $value) {
			unset($arr_special_profit_ratio[$key]);
			$arr_special_profit_ratio[$key]['salesman_id'] = $value['B'];
			$arr_special_profit_ratio[$key]['low_limit'] = $value['C'];
			$arr_special_profit_ratio[$key]['high_limit'] = $value['D'];
			$arr_special_profit_ratio[$key]['ratio'] = $value['E'];
			$this -> db_special_profit_ratio -> addItem($arr_special_profit_ratio[$key]);
		}
		$this -> success("添加成功！");
	}
	public function importNormalProfitRatioExcel() {
		$excel_name = $_FILES['excel_file']['name'];
		$index = stripos($excel_name, ".");
		if (strtolower(substr($excel_name, $index + 1)) != "xls" && strtolower(substr($excel_name, $index + 1)) != "xlsx") {
			$this -> error("上传文件格式出错");
		}
		vendor('PHPExcel.PHPExcel');
		Vendor('PHPExcel.PHPExcel.IOFactory');
		Vendor('PHPExcel.PHPExcel.Reader.Excel5.php');
		$PHPReader = new \PHPExcel_Reader_Excel5();
		$PHPexcel = new \PHPExcel();
		$excel_obj = $_FILES['excel_file']['tmp_name'];
		$PHPExcel_obj = $PHPReader -> load($excel_obj);
		$currentSheet = $PHPExcel_obj -> getSheet(0);
		$highestColumn = $currentSheet -> getHighestColumn();
		$highestRow = $currentSheet -> getHighestRow();
		$arr_normal_profit_ratio = array();
		for ($j = 2; $j <= $highestRow; $j++)//从第2行开始读取数据
		{
			for ($k = 'B'; $k <= $highestColumn; $k++)//从B列读取数据
			{
				$arr_normal_profit_ratio[$j][$k] = $PHPExcel_obj -> getActiveSheet() -> getCell("$k$j") -> getValue();
			}
		}
		foreach ($arr_normal_profit_ratio as $key => $value) {
			unset($arr_normal_profit_ratio[$key]);
			$arr_normal_profit_ratio[$key]['salesman_id'] = $value['B'];
			$arr_normal_profit_ratio[$key]['ratio'] = $value['C'];
			$this -> db_normial_profit_ratio -> addNormalProfitRatio($arr_normal_profit_ratio[$key]);
		}
		$this -> success("添加成功！");
	}
	public function importNormalBusinessRatioExcel() {
		$excel_name = $_FILES['excel_file']['name'];
		$index = stripos($excel_name, ".");
		if (strtolower(substr($excel_name, $index + 1)) != "xls" && strtolower(substr($excel_name, $index + 1)) != "xlsx") {
			$this -> error("上传文件格式出错");
		}
		vendor('PHPExcel.PHPExcel');
		Vendor('PHPExcel.PHPExcel.IOFactory');
		Vendor('PHPExcel.PHPExcel.Reader.Excel5.php');
		$PHPReader = new \PHPExcel_Reader_Excel5();
		$PHPexcel = new \PHPExcel();
		$excel_obj = $_FILES['excel_file']['tmp_name'];
		$PHPExcel_obj = $PHPReader -> load($excel_obj);
		$currentSheet = $PHPExcel_obj -> getSheet(0);
		$highestColumn = $currentSheet -> getHighestColumn();
		$highestRow = $currentSheet -> getHighestRow();
		$arr_normal_business_ratio = array();
		for ($j = 2; $j <= $highestRow; $j++)//从第2行开始读取数据
		{
			for ($k = 'B'; $k <= $highestColumn; $k++)//从B列读取数据
			{
				$arr_normal_business_ratio[$j][$k] = $PHPExcel_obj -> getActiveSheet() -> getCell("$k$j") -> getValue();
			}
		}
		foreach ($arr_normal_business_ratio as $key => $value) {
			unset($arr_normal_business_ratio[$key]);
			$arr_normal_business_ratio[$key]['salesman_id'] = $value['B'];
			$arr_normal_business_ratio[$key]['inventory_id'] = $value['C'];
			$arr_normal_business_ratio[$key]['ratio'] = $value['D'];
			$this -> db_normal_business_ratio -> addNormalBusinessRatio($arr_normal_business_ratio[$key]);
		}
		$this -> success("添加成功！");
	}
	public function importSpecialBusinessRatioExcel() {
		$excel_name = $_FILES['excel_file']['name'];
		$index = stripos($excel_name, ".");
		if (strtolower(substr($excel_name, $index + 1)) != "xls" && strtolower(substr($excel_name, $index + 1)) != "xlsx") {
			$this -> error("上传文件格式出错");
		}
		vendor('PHPExcel.PHPExcel');
		Vendor('PHPExcel.PHPExcel.IOFactory');
		Vendor('PHPExcel.PHPExcel.Reader.Excel5.php');
		$PHPReader = new \PHPExcel_Reader_Excel5();
		$PHPexcel = new \PHPExcel();
		$excel_obj = $_FILES['excel_file']['tmp_name'];
		$PHPExcel_obj = $PHPReader -> load($excel_obj);
		$currentSheet = $PHPExcel_obj -> getSheet(0);
		$highestColumn = $currentSheet -> getHighestColumn();
		$highestRow = $currentSheet -> getHighestRow();
		$arr_special_business_ratio = array();
		for ($j = 2; $j <= $highestRow; $j++)//从第2行开始读取数据
		{
			for ($k = 'B'; $k <= $highestColumn; $k++)//从B列读取数据
			{
				$arr_special_business_ratio[$j][$k] = $PHPExcel_obj -> getActiveSheet() -> getCell("$k$j") -> getValue();
			}
		}
		foreach ($arr_special_business_ratio as $key => $value) {
			unset($arr_special_business_ratio[$key]);
			$arr_special_business_ratio[$key]['salesman_id'] = $value['B'];
			$arr_special_business_ratio[$key]['classification_id'] = $value['C'];
			$arr_special_business_ratio[$key]['low_limit'] = $value['D'];
			$arr_special_business_ratio[$key]['high_limit'] = $value['E'];
			$arr_special_business_ratio[$key]['ratio'] = $value['F'];
			$this -> db_special_business_ratio -> addSpecialBusinessRatio($arr_special_business_ratio[$key]);
		}
		$this -> success("添加成功！");
	}
	public function importWageDeductionExcel() {
		$excel_name = $_FILES['excel_file']['name'];
		$index = stripos($excel_name, ".");
		if($_POST['date'] == null){
			$this -> error("请选择月份");
		}
		if (strtolower(substr($excel_name, $index + 1)) != "xls" && strtolower(substr($excel_name, $index + 1)) != "xlsx") {
			$this -> error("上传文件格式出错");
		}
		vendor('PHPExcel.PHPExcel');
		Vendor('PHPExcel.PHPExcel.IOFactory');
		Vendor('PHPExcel.PHPExcel.Reader.Excel5.php');
		$PHPReader = new \PHPExcel_Reader_Excel5();
		$PHPexcel = new \PHPExcel();
		$excel_obj = $_FILES['excel_file']['tmp_name'];
		$PHPExcel_obj = $PHPReader -> load($excel_obj);
		$currentSheet = $PHPExcel_obj -> getSheet(0);
		$highestColumn = $currentSheet -> getHighestColumn();
		$highestRow = $currentSheet -> getHighestRow();
		$arr_wage_deduction = array();
		for ($j = 3; $j <= $highestRow-1; $j++)// 第三行开始，
		{
			for ($k = 'D'; $k <= $highestColumn; $k++)//从B列读取数据
			{
				$arr_wage_deduction[$j][$k] = $PHPExcel_obj -> getActiveSheet() -> getCell("$k$j") -> getValue();
			}
		}
		foreach ($arr_wage_deduction as $key => $value) {
			unset($arr_wage_deduction[$key]);
			if($value['D'] == ""){
				continue;
			}
			$arr_wage_deduction[$key]['salesman_id'] = $value['D'];
			$arr_wage_deduction[$key]['human_wage'] = $value['F'];
			$arr_wage_deduction[$key]['nagative_salary'] = $value['G'];
			$arr_wage_deduction[$key]['human_deduction'] = $value['H'];
			$arr_wage_deduction[$key]['audit_deduction'] = $value['I'];
			$arr_wage_deduction[$key]['invoice_deduction'] = $value['J'];
			$arr_wage_deduction[$key]['wire_cutting'] = $value['K'];
			$arr_wage_deduction[$key]['gurantee_delivery'] = $value['L'];
			$arr_wage_deduction[$key]['receivables_deduction'] = $value['M'];
			$arr_wage_deduction[$key]['blocking_material'] = $value['N'];
			$arr_wage_deduction[$key]['incidental'] = $value['O'];
			$arr_wage_deduction[$key]['rework_cost'] = $value['P'];
			$arr_wage_deduction[$key]['total'] = $value['G']+$value['H']+$value['I']+$value['J']+$value['K']+$value['L']+$value['M']+$value['N']-$value['P'];
			$arr_wage_deduction[$key]['date'] = $_POST['date'];
			$this -> db_wage_deduction -> addItem($arr_wage_deduction[$key]);
		}
		$this -> success("添加成功！");
	}
	//export
	public function generatePriceFloatRatioExcelFile(){
		vendor('PHPExcel.PHPExcel');
		$float_price_ratio = $this -> db_price_float_ratio ->getAllPriceFloatRatio();
		$temp_trans = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ', );
		$objPHPExcel = new \PHPExcel();

		$objPHPExcel -> getProperties() -> setCreator("提成管理系统")
		 -> setLastModifiedBy("提成管理系统") -> setTitle("上浮底价调整比例表") 
		 -> setSubject("上浮底价调整比例表") -> setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
		 -> setKeywords("office 2007 openxml php") -> setCategory("Test result file");

		$objPHPExcel -> setActiveSheetIndex(0);

		$objPHPExcel -> getActiveSheet() -> setTitle('上浮底价调整比例表');
		
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('A') -> setWidth(18);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('B') -> setWidth(18);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('C') -> setWidth(18);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('D') -> setWidth(18);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('E') -> setWidth(18);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('F') -> setWidth(18);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('G') -> setWidth(18);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('H') -> setWidth(18);
		
		$objPHPExcel -> getActiveSheet() -> getDefaultStyle() -> getFont() -> setSize(12);
		
		$objPHPExcel -> setActiveSheetIndex(0) -> setCellValue('A1', '#') -> setCellValue('B1', '业务员编码') 
		-> setCellValue('C1', '业务员姓名') -> setCellValue('D1', '存货编码') -> setCellValue('E1', '存货名称') 
		-> setCellValue('F1', '存货类别') -> setCellValue('G1', '规格型号') -> setCellValue('H1', '基本业绩提成比例(%)');
		
		foreach ($float_price_ratio as $key => $value) {
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('A' . ($key + 2), $key+1);
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('B' . ($key + 2), $value['classification_id']);
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('C' . ($key + 2), $value['classification_name']);
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('D' . ($key + 2), $value['low_price']);
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('E' . ($key + 2), $value['high_price']);
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('F' . ($key + 2), $value['low_length']);
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('G' . ($key + 2), $value['high_length']);
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('H' . ($key + 2), $value['ratio']);
		}
		
		
		$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$filename = "上浮底价调整比例表".date('Ymdhis').".xls";
		$filename = iconv("utf-8", "gb2312", $filename);
		header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Disposition:inline;filename="'.$filename.'"');
        header("Content-Transfer-Encoding: binary");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Pragma: no-cache");
		header('Content-Encoding: none');
		header('Expires: 0');
		$objWriter -> save('php://output');
	}

	public function exportLengthLimitExcelFile(){
		vendor('PHPExcel.PHPExcel');
		$length_limit = $this -> db_length_limit ->getAllItems();
		$temp_trans = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ', );
		$objPHPExcel = new \PHPExcel();

		$objPHPExcel -> getProperties() -> setCreator("提成管理系统")
		 -> setLastModifiedBy("提成管理系统") -> setTitle("上浮底价调整比例表") 
		 -> setSubject("合同结算长度限定表") -> setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
		 -> setKeywords("office 2007 openxml php") -> setCategory("Test result file");

		$objPHPExcel -> setActiveSheetIndex(0);

		$objPHPExcel -> getActiveSheet() -> setTitle('合同结算长度限定表');
		
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('A') -> setWidth(18);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('B') -> setWidth(18);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('C') -> setWidth(18);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('D') -> setWidth(30);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('E') -> setWidth(18);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('F') -> setWidth(18);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('G') -> setWidth(18);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('H') -> setWidth(18);
		
		$objPHPExcel -> getActiveSheet() -> getDefaultStyle() -> getFont() -> setSize(12);
		

		$objPHPExcel -> setActiveSheetIndex(0) -> setCellValue('A1', '#') -> setCellValue('B1', '长度下限') 
		-> setCellValue('C1', '长度上限') -> setCellValue('D1', '可结算发货数量比例(%)');
		foreach ($length_limit as $key => $value) {
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('A' . ($key + 2), $key+1);
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('B' . ($key + 2), $value['low_length']);
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('C' . ($key + 2), $value['high_length']);
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('D' . ($key + 2), $value['limit']);
		}
		
		
		$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$filename = "合同结算长度限定表".date('Ymdhis').".xls";
		$filename = iconv("utf-8", "gb2312", $filename);
		header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Disposition:inline;filename="'.$filename.'"');
        header("Content-Transfer-Encoding: binary");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Pragma: no-cache");
		header('Content-Encoding: none');
		header('Expires: 0');
		$objWriter -> save('php://output');
	}
	public function exportFundsBackFile(){
		vendor('PHPExcel.PHPExcel');
		$all_funds_back = $this -> db_funds_back -> getAllItems();
		$all_funds_back = $this -> db_customer -> addCustomerName($all_funds_back);
		$temp_trans = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ', );
		$objPHPExcel = new \PHPExcel();

		$objPHPExcel -> getProperties() -> setCreator("提成管理系统")
		 -> setLastModifiedBy("提成管理系统") -> setTitle("回笼资金调整表") 
		 -> setSubject("回笼资金调整表") -> setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
		 -> setKeywords("office 2007 openxml php") -> setCategory("Test result file");

		$objPHPExcel -> setActiveSheetIndex(0);

		$objPHPExcel -> getActiveSheet() -> setTitle('回笼资金调整表');
		
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('A') -> setWidth(18);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('B') -> setWidth(18);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('C') -> setWidth(18);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('D') -> setWidth(18);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('E') -> setWidth(18);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('F') -> setWidth(18);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('G') -> setWidth(18);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('H') -> setWidth(18);
		
		$objPHPExcel -> getActiveSheet() -> getDefaultStyle() -> getFont() -> setSize(12);
		

		$objPHPExcel -> setActiveSheetIndex(0) -> setCellValue('A1', '#') -> setCellValue('B1', '客户编码') 
		-> setCellValue('C1', '客户名称') -> setCellValue('D1', '资金回笼调整金额');
		foreach ($all_funds_back as $key => $value) {
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('A' . ($key + 2), $key+1);
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('B' . ($key + 2), $value['customer_id']);
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('C' . ($key + 2), $value['customer_name']);
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('D' . ($key + 2), $value['funds_back_money']);
		}
		
		
		$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$filename = "回笼资金调整表".date('Ymdhis').".xls";
		$filename = iconv("utf-8", "gb2312", $filename);
		header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Disposition:inline;filename="'.$filename.'"');
        header("Content-Transfer-Encoding: binary");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Pragma: no-cache");
		header('Content-Encoding: none');
		header('Expires: 0');
		$objWriter -> save('php://output');
	}
	
	public function exportSpeicalProfitRatioExcelFile(){
		vendor('PHPExcel.PHPExcel');
		$special_profit_ratio = $this -> db_special_profit_ratio -> getAllItems();
		$special_profit_ratio = $this -> db_salesman -> addSalesmanName($special_profit_ratio);
		$temp_trans = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ', );
		$objPHPExcel = new \PHPExcel();

		$objPHPExcel -> getProperties() -> setCreator("提成管理系统")
		 -> setLastModifiedBy("提成管理系统") -> setTitle("未达标利润提成比例表") 
		 -> setSubject("未达标利润提成比例表") -> setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
		 -> setKeywords("office 2007 openxml php") -> setCategory("Test result file");

		$objPHPExcel -> setActiveSheetIndex(0);

		$objPHPExcel -> getActiveSheet() -> setTitle('未达标利润提成比例表');
		
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('A') -> setWidth(18);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('B') -> setWidth(18);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('C') -> setWidth(18);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('D') -> setWidth(18);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('E') -> setWidth(18);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('F') -> setWidth(18);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('G') -> setWidth(18);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('H') -> setWidth(18);
		
		$objPHPExcel -> getActiveSheet() -> getDefaultStyle() -> getFont() -> setSize(12);
		

		$objPHPExcel -> setActiveSheetIndex(0) -> setCellValue('A1', '#') -> setCellValue('B1', '业务员编码') 
		-> setCellValue('C1', '业务员姓名') -> setCellValue('D1', '回款下限')-> setCellValue('E1', '回款上限')
		-> setCellValue('F1', '未达标利润提成比例');
		foreach ($special_profit_ratio as $key => $value) {
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('A' . ($key + 2), $key+1);
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('B' . ($key + 2), $value['salesman_id']);
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('C' . ($key + 2), $value['salesman_name']);
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('D' . ($key + 2), $value['low_limit']);
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('E' . ($key + 2), $value['high_limit']);
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('F' . ($key + 2), $value['ratio']."%");
		}
		
		
		$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$filename = "未达标利润提成比例表".date('Ymdhis').".xls";
		$filename = iconv("utf-8", "gb2312", $filename);
		header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Disposition:inline;filename="'.$filename.'"');
        header("Content-Transfer-Encoding: binary");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Pragma: no-cache");
		header('Content-Encoding: none');
		header('Expires: 0');
		$objWriter -> save('php://output');
	}

	public function exprotNormalProfitRatioExcelFile(){
		vendor('PHPExcel.PHPExcel');
		$normal_profit_ratio = $this -> db_normial_profit_ratio -> getAllNormalProfitRatio();
		$normal_profit_ratio = $this -> db_salesman -> addSalesmanName($normal_profit_ratio);
		$temp_trans = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ', );
		$objPHPExcel = new \PHPExcel();

		$objPHPExcel -> getProperties() -> setCreator("提成管理系统")
		 -> setLastModifiedBy("提成管理系统") -> setTitle("基本利润提成比例表") 
		 -> setSubject("基本利润提成比例表") -> setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
		 -> setKeywords("office 2007 openxml php") -> setCategory("Test result file");

		$objPHPExcel -> setActiveSheetIndex(0);

		$objPHPExcel -> getActiveSheet() -> setTitle('基本利润提成比例表');
		
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('A') -> setWidth(18);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('B') -> setWidth(18);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('C') -> setWidth(18);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('D') -> setWidth(18);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('E') -> setWidth(18);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('F') -> setWidth(18);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('G') -> setWidth(18);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('H') -> setWidth(18);
		
		$objPHPExcel -> getActiveSheet() -> getDefaultStyle() -> getFont() -> setSize(12);
		

		$objPHPExcel -> setActiveSheetIndex(0) -> setCellValue('A1', '#') -> setCellValue('B1', '业务员编码') 
		-> setCellValue('C1', '业务员姓名') -> setCellValue('D1', '基本利润提成比例');
		foreach ($normal_profit_ratio as $key => $value) {
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('A' . ($key + 2), $key+1);
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('B' . ($key + 2), $value['salesman_id']);
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('C' . ($key + 2), $value['salesman_name']);
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('D' . ($key + 2), $value['ratio']."%");
		}
		
		
		$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$filename = "未达标利润提成比例表".date('Ymdhis').".xls";
		$filename = iconv("utf-8", "gb2312", $filename);
		header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Disposition:inline;filename="'.$filename.'"');
        header("Content-Transfer-Encoding: binary");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Pragma: no-cache");
		header('Content-Encoding: none');
		header('Expires: 0');
		$objWriter -> save('php://output');
	}
		
	public function exportCustomerExcelFile(){
		vendor('PHPExcel.PHPExcel');
		$all_customer = $this -> db_customer -> getAllCustomer();
		$temp_trans = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ', );
		$objPHPExcel = new \PHPExcel();

		$objPHPExcel -> getProperties() -> setCreator("提成管理系统")
		 -> setLastModifiedBy("提成管理系统") -> setTitle("客户信息表") 
		 -> setSubject("客户信息表") -> setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
		 -> setKeywords("office 2007 openxml php") -> setCategory("Test result file");

		$objPHPExcel -> setActiveSheetIndex(0);

		$objPHPExcel -> getActiveSheet() -> setTitle('客户信息表');
		
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('A') -> setWidth(18);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('B') -> setWidth(18);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('C') -> setWidth(30);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('D') -> setWidth(18);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('E') -> setWidth(18);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('F') -> setWidth(18);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('G') -> setWidth(18);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('H') -> setWidth(18);
		
		$objPHPExcel -> getActiveSheet() -> getDefaultStyle() -> getFont() -> setSize(12);
		

		$objPHPExcel -> setActiveSheetIndex(0) -> setCellValue('A1', '#') -> setCellValue('B1', '业务员编码')-> setCellValue('C1', '业务员姓名');
		foreach ($all_customer as $key => $value) {
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('A' . ($key + 2), $key+1);
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('B' . ($key + 2), $value['customer_id']);
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('C' . ($key + 2), $value['customer_name']);
		}
		
		
		$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$filename = "客户信息表".date('Ymdhis').".xls";
		$filename = iconv("utf-8", "gb2312", $filename);
		header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Disposition:inline;filename="'.$filename.'"');
        header("Content-Transfer-Encoding: binary");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Pragma: no-cache");
		header('Content-Encoding: none');
		header('Expires: 0');
		$objWriter -> save('php://output');
	}

	public function exportInsurancrAndFundsExcelFile(){
		vendor('PHPExcel.PHPExcel');
		$all_insurance_fund = $this -> db_insurance_fund -> getAllInsuranceFund();
		$all_insurance_fund = $this -> db_salesman -> addSalesmanName($all_insurance_fund);
		$temp_trans = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ', );
		$objPHPExcel = new \PHPExcel();

		$objPHPExcel -> getProperties() -> setCreator("提成管理系统")
		 -> setLastModifiedBy("提成管理系统") -> setTitle("社保及公积金表") 
		 -> setSubject("社保及公积金表") -> setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
		 -> setKeywords("office 2007 openxml php") -> setCategory("Test result file");

		$objPHPExcel -> setActiveSheetIndex(0);

		$objPHPExcel -> getActiveSheet() -> setTitle('社保及公积金表');
		
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('A') -> setWidth(18);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('B') -> setWidth(18);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('C') -> setWidth(30);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('D') -> setWidth(18);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('E') -> setWidth(18);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('F') -> setWidth(18);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('G') -> setWidth(18);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('H') -> setWidth(18);
		
		$objPHPExcel -> getActiveSheet() -> getDefaultStyle() -> getFont() -> setSize(12);
		

		$objPHPExcel -> setActiveSheetIndex(0) -> setCellValue('A1', '#') -> setCellValue('B1', '业务员编码')
		-> setCellValue('C1', '业务员姓名')-> setCellValue('D1', '社保')-> setCellValue('E1', '公积金');
		foreach ($all_insurance_fund as $key => $value) {
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('A' . ($key + 2), $key+1);
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('B' . ($key + 2), $value['salesman_id']);
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('C' . ($key + 2), $value['salesman_name']);
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('D' . ($key + 2), $value['insurance']."%");
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('E' . ($key + 2), $value['fund']."%");
		}
		
		
		$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$filename = "社保及公积金表".date('Ymdhis').".xls";
		$filename = iconv("utf-8", "gb2312", $filename);
		header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Disposition:inline;filename="'.$filename.'"');
        header("Content-Transfer-Encoding: binary");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Pragma: no-cache");
		header('Content-Encoding: none');
		header('Expires: 0');
		$objWriter -> save('php://output');
	}
	public function exportTaxRatioExcelFile(){
		vendor('PHPExcel.PHPExcel');
		$all_tax_ratio = $this -> db_tax_ratio -> getAllItems();
		$temp_trans = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ', );
		$objPHPExcel = new \PHPExcel();

		$objPHPExcel -> getProperties() -> setCreator("提成管理系统")
		 -> setLastModifiedBy("提成管理系统") -> setTitle("个税基础表") 
		 -> setSubject("个税基础表") -> setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
		 -> setKeywords("office 2007 openxml php") -> setCategory("Test result file");

		$objPHPExcel -> setActiveSheetIndex(0);

		$objPHPExcel -> getActiveSheet() -> setTitle('个税基础表');
		
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('A') -> setWidth(18);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('B') -> setWidth(18);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('C') -> setWidth(18);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('D') -> setWidth(18);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('E') -> setWidth(18);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('F') -> setWidth(18);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('G') -> setWidth(18);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('H') -> setWidth(18);
		
		$objPHPExcel -> getActiveSheet() -> getDefaultStyle() -> getFont() -> setSize(12);
		

		$objPHPExcel -> setActiveSheetIndex(0) -> setCellValue('A1', '#') -> setCellValue('B1', '工资区间下限')
		-> setCellValue('C1', '工资区间上限')-> setCellValue('D1', '个税比例');
		foreach ($all_tax_ratio as $key => $value) {
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('A' . ($key + 2), $key+1);
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('B' . ($key + 2), $value['low_limit']);
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('C' . ($key + 2), $value['high_limit']);
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('D' . ($key + 2), $value['ratio']."%");
		}
		
		
		$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$filename = "个税基础表".date('Ymdhis').".xls";
		$filename = iconv("utf-8", "gb2312", $filename);
		header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Disposition:inline;filename="'.$filename.'"');
        header("Content-Transfer-Encoding: binary");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Pragma: no-cache");
		header('Content-Encoding: none');
		header('Expires: 0');
		$objWriter -> save('php://output');
	}
	public function exportSalesmanInfoExcelFile(){
		vendor('PHPExcel.PHPExcel');
		$salesman_info = $this -> db_salesman -> getAllSalesmanInfo();
		$temp_trans = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ', );
		$objPHPExcel = new \PHPExcel();

		$objPHPExcel -> getProperties() -> setCreator("提成管理系统")
		 -> setLastModifiedBy("提成管理系统") -> setTitle("人员工资基本信息表") 
		 -> setSubject("人员工资基本信息表") -> setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
		 -> setKeywords("office 2007 openxml php") -> setCategory("Test result file");

		$objPHPExcel -> setActiveSheetIndex(0);

		$objPHPExcel -> getActiveSheet() -> setTitle('人员工资基本信息表');
		
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('A') -> setWidth(18);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('B') -> setWidth(18);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('C') -> setWidth(18);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('D') -> setWidth(18);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('E') -> setWidth(18);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('F') -> setWidth(18);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('G') -> setWidth(18);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('H') -> setWidth(18);
		
		$objPHPExcel -> getActiveSheet() -> getDefaultStyle() -> getFont() -> setSize(12);
		

		$objPHPExcel -> setActiveSheetIndex(0) -> setCellValue('A1', '#') -> setCellValue('B1', '业务员编码')
		-> setCellValue('C1', '业务员姓名')-> setCellValue('D1', '人员状态')-> setCellValue('E1', '在职状态')
		-> setCellValue('F1', '上海基本工资(元)')-> setCellValue('G1', '昆山基本工资(元)');
		foreach ($salesman_info as $key => $value) {
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('A' . ($key + 2), $key+1);
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('B' . ($key + 2), $value['salesman_id']);
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('C' . ($key + 2), $value['salesman_name']);
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('D' . ($key + 2), $value['status']);
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('E' . ($key + 2), $value['onboard_status']);
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('F' . ($key + 2), $value['shanghai_salary']);
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('G' . ($key + 2), $value['kunshan_salary']);
		}
		
		
		$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$filename = "人员工资基本信息表".date('Ymdhis').".xls";
		$filename = iconv("utf-8", "gb2312", $filename);
		header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Disposition:inline;filename="'.$filename.'"');
        header("Content-Transfer-Encoding: binary");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Pragma: no-cache");
		header('Content-Encoding: none');
		header('Expires: 0');
		$objWriter -> save('php://output');
	}
	public function exportNormalBusinessRatioExcelFile(){
		vendor('PHPExcel.PHPExcel');
		$this -> db_U8 = D("U8");
		$normal_business_ratio = $this -> db_normal_business_ratio ->getAllNormalBusinessRatio();
		$normal_business_ratio = $this -> db_salesman -> addSalesmanName($normal_business_ratio);
	//	$normal_business_ratio = $this -> db_U8 -> getInventoryDetail($normal_business_ratio);
		$temp_trans = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ', );
		$objPHPExcel = new \PHPExcel();

		$objPHPExcel -> getProperties() -> setCreator("提成管理系统")
		 -> setLastModifiedBy("提成管理系统") -> setTitle("上浮底价调整比例表") 
		 -> setSubject("基本业绩提成比例表") -> setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
		 -> setKeywords("office 2007 openxml php") -> setCategory("Test result file");

		$objPHPExcel -> setActiveSheetIndex(0);

		$objPHPExcel -> getActiveSheet() -> setTitle('上浮底价调整比例表');
		
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('A') -> setWidth(18);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('B') -> setWidth(18);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('C') -> setWidth(18);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('D') -> setWidth(18);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('E') -> setWidth(18);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('F') -> setWidth(18);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('G') -> setWidth(18);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('H') -> setWidth(30);
		
		$objPHPExcel -> getActiveSheet() -> getDefaultStyle() -> getFont() -> setSize(12);
		
		$objPHPExcel -> setActiveSheetIndex(0) -> setCellValue('A1', '#') -> setCellValue('B1', '业务员编码') 
		-> setCellValue('C1', '业务员姓名') -> setCellValue('D1', '存货编码') -> setCellValue('E1', '存货名称') 
		-> setCellValue('F1', '存货类别') -> setCellValue('G1', '规格型号') -> setCellValue('H1', '基本业绩提成比例(%)');
		
		foreach ($normal_business_ratio as $key => $value) {
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('A' . ($key + 2), $key+1);
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('B' . ($key + 2), $value['salesman_id']);
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('C' . ($key + 2), $value['salesman_name']);
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('D' . ($key + 2), $value['inventory_id']);
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('E' . ($key + 2), $value['inventory_name']);
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('F' . ($key + 2), $value['classification']);
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('G' . ($key + 2), $value['specification']);
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('H' . ($key + 2), $value['ratio']);
		}
		
		
		$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$filename = "基本业绩提成比例表".date('Ymdhis').".xls";
		$filename = iconv("utf-8", "gb2312", $filename);
		header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Disposition:inline;filename="'.$filename.'"');
        header("Content-Transfer-Encoding: binary");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Pragma: no-cache");
		header('Content-Encoding: none');
		header('Expires: 0');
		$objWriter -> save('php://output');
	}
	public function exportSpecialBusinessRatioExcelFile(){
		vendor('PHPExcel.PHPExcel');
		$this -> db_U8 = D("U8");
		$special_business_ratio = $this -> db_special_business_ratio ->getAllSpecialBusinessRatio();
		$special_business_ratio = $this -> db_salesman -> addSalesmanName($special_business_ratio);
	//	$special_business_ratio = $this -> db_U8 -> getInventoryDetail($special_business_ratio);
		$temp_trans = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ', );
		$objPHPExcel = new \PHPExcel();

		$objPHPExcel -> getProperties() -> setCreator("提成管理系统")
		 -> setLastModifiedBy("提成管理系统") -> setTitle("达标业绩提成比例表") 
		 -> setSubject("达标业绩提成比例表") -> setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
		 -> setKeywords("office 2007 openxml php") -> setCategory("Test result file");

		$objPHPExcel -> setActiveSheetIndex(0);

		$objPHPExcel -> getActiveSheet() -> setTitle('上浮底价调整比例表');
		
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('A') -> setWidth(18);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('B') -> setWidth(18);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('C') -> setWidth(18);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('D') -> setWidth(30);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('E') -> setWidth(30);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('F') -> setWidth(18);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('G') -> setWidth(18);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('H') -> setWidth(30);
		
		$objPHPExcel -> getActiveSheet() -> getDefaultStyle() -> getFont() -> setSize(12);
		
		$objPHPExcel -> setActiveSheetIndex(0) -> setCellValue('A1', '#') -> setCellValue('B1', '业务员编码') 
		-> setCellValue('C1', '业务员姓名') -> setCellValue('D1', '存货类别编码') -> setCellValue('E1', '存货类别名称') 
		-> setCellValue('F1', '回款下限') -> setCellValue('G1', '回款上限') -> setCellValue('H1', '回款达标业绩提成比例(%)');
		
		foreach ($special_business_ratio as $key => $value) {
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('A' . ($key + 2), $key+1);
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('B' . ($key + 2), $value['salesman_id']);
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('C' . ($key + 2), $value['salesman_name']);
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('D' . ($key + 2), $value['classification_id']);
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('E' . ($key + 2), $value['classification_name']);
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('F' . ($key + 2), $value['low_limit']);
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('G' . ($key + 2), $value['high_limit']);
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('H' . ($key + 2), $value['ratio']);
		}
		
		
		$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$filename = "达标业绩提成比例表".date('Ymdhis').".xls";
		$filename = iconv("utf-8", "gb2312", $filename);
		header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Disposition:inline;filename="'.$filename.'"');
        header("Content-Transfer-Encoding: binary");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Pragma: no-cache");
		header('Content-Encoding: none');
		header('Expires: 0');
		$objWriter -> save('php://output');
	}
}
?>