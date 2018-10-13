<?php
namespace Home\Controller;
use Think\Controller;
class ExcelController extends Controller {
	
	private $db_salesman;
	private $db_normal_business_ratio;
	private $db_special_business_ratio;
	private $db_normial_profit_ratio;
	private $db_normal_profit_discount_ratio;
	private $db_special_profit_ratio;
	private $db_insurance_fund;
	private $db_price_float_ratio;
	private $db_area_price_float_ratio;
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
	private $db_sale_expense;
	private $db_special_approve_price_float_ratio;
	private $db_salesman_funds;
	function _initialize() {
		$this -> db_salesman = D("Salesman");
		$this -> db_normal_business_ratio = D("NormalBusinessRatio");
		$this -> db_special_business_ratio = D("SpecialBusinessRatio");
		$this -> db_normial_profit_ratio = D("NormalProfitRatio");
		$this -> db_normal_profit_discount_ratio = D("NormalProfitDiscountRatio");
		$this -> db_insurance_fund = D("InsuranceFund");
		$this -> db_price_float_ratio = D("PriceFloatRatio");
		$this -> db_area_price_float_ratio = D("AreaPriceFloatRatio");
		$this -> db_special_profit_ratio = D("SpecialProfitRatio");
		$this -> db_special_approve_price_float_ratio = D("SpecialApprovePriceFloatRatio");
		$this -> db_load_history = D("LoadHistory");
		$this -> db_customer = D("Customer");
		$this -> db_contact_main = D("ContactMain");
		$this -> db_contact_detail = D("ContactDetail");
		$this -> db_constomer_funds = D("CustomerFunds");
		$this -> db_length_limit = D("LengthLimit");
		$this -> db_funds_back = D("FundsBack");
		$this -> db_tax_ratio = D("TaxRatio");
		$this -> db_wage_deduction = D("WageDeduction");
		$this -> db_sale_expense = D("SaleExpense");
		$this -> db_salesman_funds = D("SalesmanFunds");
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
				$arr_normal_profir_ratio[$j][$k] = (string)$PHPExcel_obj -> getActiveSheet() -> getCell("$k$j") -> getValue();
			}
		}
		foreach ($arr_normal_profir_ratio as $key => $value) {
			unset($arr_normal_profir_ratio[$key]);
			$arr_price_float_ratio[$key]['classification_id'] = $value['B'];
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
				$arr_customers_info[$j][$k] = (string)$PHPExcel_obj -> getActiveSheet() -> getCell("$k$j") -> getValue();
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
				$arr_salesman_info[$j][$k] = (string)$PHPExcel_obj -> getActiveSheet() -> getCell("$k$j") -> getValue();
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
				$arr_insurance_fund[$j][$k] = (string)$PHPExcel_obj -> getActiveSheet() -> getCell("$k$j") -> getValue();
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
				$arr_tax_ratio[$j][$k] = (string)$PHPExcel_obj -> getActiveSheet() -> getCell("$k$j") -> getValue();
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
				$arr_length_limit[$j][$k] = (string)$PHPExcel_obj -> getActiveSheet() -> getCell("$k$j") -> getValue();
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
				$arr_funds_back[$j][$k] = (string)$PHPExcel_obj -> getActiveSheet() -> getCell("$k$j") -> getValue();
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
	
	public function importSpecialApprovePriceFloatRatioExcel() {
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
		$arr_special_approve_price_float_ratio = array();
		for ($j = 2; $j <= $highestRow; $j++)//从第2行开始读取数据
		{
			for ($k = 'B'; $k <= $highestColumn; $k++)//从B列读取数据
			{
				$arr_special_approve_price_float_ratio[$j][$k] = (string)$PHPExcel_obj -> getActiveSheet() -> getCell("$k$j") -> getValue();
			}
		}
		$this -> db_special_approve_price_float_ratio -> deleteAllItems();
		foreach ($arr_special_approve_price_float_ratio as $key => $value) {
			unset($arr_special_approve_price_float_ratio[$key]);
			$arr_special_approve_price_float_ratio[$key]['customer_id'] = $value['B'];
			$arr_special_approve_price_float_ratio[$key]['inventory_id'] = $value['C'];
			$arr_special_approve_price_float_ratio[$key]['ratio'] = $value['D'];
			$this -> db_special_approve_price_float_ratio -> addItem($arr_special_approve_price_float_ratio[$key]);
		}
		$this -> success("添加成功！");
	}
	
	public function importAreaPriceFloatRatioExcel() {
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
		$arr_area_price_float_ratio = array();
		for ($j = 2; $j <= $highestRow; $j++)//从第2行开始读取数据
		{
			for ($k = 'B'; $k <= $highestColumn; $k++)//从B列读取数据
			{
				$arr_area_price_float_ratio[$j][$k] = (string)$PHPExcel_obj -> getActiveSheet() -> getCell("$k$j") -> getValue();
			}
		}
		foreach ($arr_area_price_float_ratio as $key => $value) {
			unset($arr_area_price_float_ratio[$key]);
			$arr_area_price_float_ratio[$key]['classification_id'] = $value['B'];
			$arr_area_price_float_ratio[$key]['area'] = $value['C'];
			$arr_area_price_float_ratio[$key]['ratio'] = $value['D'];
			$this -> db_area_price_float_ratio -> addItem($arr_area_price_float_ratio[$key]);
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
				$arr_special_profit_ratio[$j][$k] = (string)$PHPExcel_obj -> getActiveSheet() -> getCell("$k$j") -> getValue();
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
				$arr_normal_profit_ratio[$j][$k] = (string)$PHPExcel_obj -> getActiveSheet() -> getCell("$k$j") -> getValue();
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
	public function importNormalProfitDiscountRatioExcel() {
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
		$arr_normal_profit_discount_ratio = array();
		for ($j = 2; $j <= $highestRow; $j++)//从第2行开始读取数据
		{
			for ($k = 'B'; $k <= $highestColumn; $k++)//从B列读取数据
			{
				$arr_normal_profit_discount_ratio[$j][$k] = (string)$PHPExcel_obj -> getActiveSheet() -> getCell("$k$j") -> getValue();
			}
		}
		foreach ($arr_normal_profit_discount_ratio as $key => $value) {
			unset($arr_normal_profit_discount_ratio[$key]);
			$arr_normal_profit_discount_ratio[$key]['salesman_id'] = $value['B'];
			$arr_normal_profit_discount_ratio[$key]['date'] = $value['C'];
			$arr_normal_profit_discount_ratio[$key]['ratio'] = $value['D'];
			$this -> db_normal_profit_discount_ratio -> addNormalProfitDiscountRatio($arr_normal_profit_discount_ratio[$key]);
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
				$arr_normal_business_ratio[$j][$k] = (string)$PHPExcel_obj -> getActiveSheet() -> getCell("$k$j") -> getValue();
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
				$arr_special_business_ratio[$j][$k] = (string)$PHPExcel_obj -> getActiveSheet() -> getCell("$k$j") -> getValue();
			}
		}
		foreach ($arr_special_business_ratio as $key => $value) {
			unset($arr_special_business_ratio[$key]);
			$arr_special_business_ratio[$key]['salesman_id'] = $value['B'];
			$arr_special_business_ratio[$key]['inventory_id'] = $value['C'];
			$arr_special_business_ratio[$key]['low_limit'] = $value['D'];
			$arr_special_business_ratio[$key]['high_limit'] = $value['E'];
			$arr_special_business_ratio[$key]['ratio'] = $value['F'];
			$this -> db_special_business_ratio -> addSpecialBusinessRatio($arr_special_business_ratio[$key]);
		}
		$this -> success("添加成功！");
	}
	public function importSaleExpenseExcel() {
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
				$arr_sale_expense[$j][$k] = (string)$PHPExcel_obj -> getActiveSheet() -> getCell("$k$j") -> getValue();
			}
		}
		foreach ($arr_sale_expense as $key => $value) {
			unset($arr_sale_expense[$key]);
			$arr_sale_expense[$key]['salesman_id'] = $value['B'];
			$arr_sale_expense[$key]['contact_id'] = $value['C'];
			$arr_sale_expense[$key]['sale_expense'] = $value['D'];
			$arr_sale_expense[$key]['ratio'] = $value['E'];
			$this -> db_sale_expense -> addItem($arr_sale_expense[$key]);
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
		// $highestColumn = $currentSheet -> getHighestColumn();
		$highestColumn = 'P';
		$highestRow = $currentSheet -> getHighestRow();
		$arr_wage_deduction = array();
		for ($j = 3; $j <= $highestRow-1; $j++)// 第三行开始，
		{
			for ($k = 'D'; $k <= $highestColumn; $k++)
			{
				$arr_wage_deduction[$j][$k] = (string)$PHPExcel_obj -> getActiveSheet() -> getCell("$k$j") -> getValue();
			}
		}
		foreach ($arr_wage_deduction as $key => $value) {
			unset($arr_wage_deduction[$key]);
			if($value['D'] == ""){
				continue;
			}
			$arr_wage_deduction[$key]['salesman_id'] = $value['D'];
			$arr_wage_deduction[$key]['human_wage'] = $value['F'];
			// $arr_wage_deduction[$key]['nagative_salary'] = $value['G'];
			// $arr_wage_deduction[$key]['human_deduction'] = $value['H'];
			$arr_wage_deduction[$key]['audit_deduction'] = $value['G'];
			$arr_wage_deduction[$key]['invoice_deduction'] = $value['H'];
			$arr_wage_deduction[$key]['wire_cutting'] = $value['I'];
			$arr_wage_deduction[$key]['gurantee_delivery'] = $value['J'];
			$arr_wage_deduction[$key]['receivables_deduction'] = $value['K'];
			$arr_wage_deduction[$key]['blocking_material'] = $value['L'];
			$arr_wage_deduction[$key]['incidental'] = $value['M'];
			$arr_wage_deduction[$key]['rework_cost'] = $value['N'];
			$arr_wage_deduction[$key]['add_one'] = $value['O'];
			$arr_wage_deduction[$key]['add_two'] = $value['P'];
			$arr_wage_deduction[$key]['total'] = $value['F'] - $value['G']-$value['H']-$value['I']-$value['J']-$value['K']-$value['L']-$value['M']-$value['N'] + $value['O'] +$value['P'];
			$arr_wage_deduction[$key]['date'] = $_POST['date'];
			$this -> db_wage_deduction -> addItem($arr_wage_deduction[$key]);
		}
		$this -> success("添加成功！");
	}
	public function imporProfitAdjustExcel() {
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
				$arr_sale_expense[$j][$k] = (string)$PHPExcel_obj -> getActiveSheet() -> getCell("$k$j") -> getValue();
			}
		}
		foreach ($arr_sale_expense as $key => $value) {
			unset($arr_sale_expense[$key]);
			$arr_profit_adjust[$key]['contact_id'] = $value['B'];
			$arr_profit_adjust[$key]['cSOCode'] = $value['C'];
			$arr_profit_adjust[$key]['inventory_id'] = $value['D'];
			$arr_profit_adjust[$key]['classification_id'] = $value['E'];
			$profit_adjust = $value['F'];
			$this -> db_contact_detail -> updateProfitAdjust($arr_profit_adjust[$key],$profit_adjust);
		}
		$this -> success("调整成功！");
	}
	public function imporSalesmanFundsExcel() {
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
				$arr_sale_expense[$j][$k] = (string)$PHPExcel_obj -> getActiveSheet() -> getCell("$k$j") -> getValue();
			}
		}

		foreach ($arr_sale_expense as $key => $value) {
			unset($arr_sale_expense[$key]);
			$arr_salesman_funds[$key]['salesman_id'] = $value['B'];
			$arr_salesman_funds[$key]['salesman_name'] = $value['C'];
			$arr_salesman_funds[$key]['funds'] = $value['D'];
			$arr_salesman_funds[$key]['month'] = $value['E'];
		}
		$this -> db_salesman_funds -> addItems($arr_salesman_funds);
		$this -> success("添加成功！");
	}
	//export
	public function generatePriceFloatRatioExcelFile(){
		$this -> db_U8 = D("U8");
		vendor('PHPExcel.PHPExcel');
		$float_price_ratio = $this -> db_price_float_ratio ->getAllPriceFloatRatio();
		$float_price_ratio = $this -> db_U8 ->getClassificationName($float_price_ratio);
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
		
		$objPHPExcel -> setActiveSheetIndex(0) -> setCellValue('A1', '#') -> setCellValue('B1', '存货类别编码') 
		-> setCellValue('C1', '存货类别名称') -> setCellValue('D1', '底价下限') -> setCellValue('E1', '底价上限') 
		-> setCellValue('F1', '长度下限') -> setCellValue('G1', '长度上限') -> setCellValue('H1', '浮动比例(%)');
		
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
		ob_end_clean();//清除缓冲区,避免乱码
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
		-> setCellValue('C1', '长度上限') -> setCellValue('D1', '可结算发货数量比例');
		foreach ($length_limit as $key => $value) {
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('A' . ($key + 2), $key+1);
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('B' . ($key + 2), $value['low_length']);
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('C' . ($key + 2), $value['high_length']);
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('D' . ($key + 2), $value['limit']."%");
		}
		
		
		$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$filename = "合同结算长度限定表".date('Ymdhis').".xls";
		$filename = iconv("utf-8", "gb2312", $filename);
		ob_end_clean();//清除缓冲区,避免乱码
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
		-> setCellValue('C1', '客户名称') -> setCellValue('D1', '资金回笼调整金额(元)');
		foreach ($all_funds_back as $key => $value) {
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('A' . ($key + 2), $key+1);
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('B' . ($key + 2), $value['customer_id']);
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('C' . ($key + 2), $value['customer_name']);
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('D' . ($key + 2), $value['funds_back_money']);
		}
		
		
		$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$filename = "回笼资金调整表".date('Ymdhis').".xls";
		$filename = iconv("utf-8", "gb2312", $filename);
		ob_end_clean();//清除缓冲区,避免乱码
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
		ob_end_clean();//清除缓冲区,避免乱码
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
		ob_end_clean();//清除缓冲区,避免乱码
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
	
	public function exprotNormalProfitDiscountRatioExcelFile(){
		vendor('PHPExcel.PHPExcel');
		$normal_profit_discount_ratio = $this -> db_normal_profit_discount_ratio -> getAllNormalProfitDiscountRatio();
		$normal_profit_discount_ratio = $this -> db_salesman -> addSalesmanName($normal_profit_discount_ratio);
		$temp_trans = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ', );
		$objPHPExcel = new \PHPExcel();

		$objPHPExcel -> getProperties() -> setCreator("提成管理系统")
		 -> setLastModifiedBy("提成管理系统") -> setTitle("基本利润提成比例表") 
		 -> setSubject("基本利润提成比折扣例表") -> setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
		 -> setKeywords("office 2007 openxml php") -> setCategory("Test result file");

		$objPHPExcel -> setActiveSheetIndex(0);

		$objPHPExcel -> getActiveSheet() -> setTitle('基本利润提成比例折扣表');
		
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
		-> setCellValue('C1', '业务员姓名') -> setCellValue('D1', '日期')-> setCellValue('E1', '基本利润提成比例折扣');
		foreach ($normal_profit_discount_ratio as $key => $value) {
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('A' . ($key + 2), $key+1);
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('B' . ($key + 2), $value['salesman_id']);
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('C' . ($key + 2), $value['salesman_name']);
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('D' . ($key + 2), $value['date']);
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('E' . ($key + 2), $value['ratio']."%");
		}
		
		
		$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$filename = "基本利润提成比例折扣表".date('Ymdhis').".xls";
		$filename = iconv("utf-8", "gb2312", $filename);
		ob_end_clean();//清除缓冲区,避免乱码
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
		ob_end_clean();//清除缓冲区,避免乱码
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
		-> setCellValue('C1', '业务员姓名')-> setCellValue('D1', '社保(元)')-> setCellValue('E1', '公积金(元)');
		foreach ($all_insurance_fund as $key => $value) {
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('A' . ($key + 2), $key+1);
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('B' . ($key + 2), $value['salesman_id']);
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('C' . ($key + 2), $value['salesman_name']);
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('D' . ($key + 2), $value['insurance']);
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('E' . ($key + 2), $value['fund']);
		}
		
		
		$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$filename = "社保及公积金表".date('Ymdhis').".xls";
		$filename = iconv("utf-8", "gb2312", $filename);
		ob_end_clean();//清除缓冲区,避免乱码
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
		ob_end_clean();//清除缓冲区,避免乱码
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
		ob_end_clean();//清除缓冲区,避免乱码
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
		$normal_business_ratio = $this -> db_U8 -> getInventoryDetail($normal_business_ratio);
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
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('F' . ($key + 2), $value['classification_id']);
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('G' . ($key + 2), $value['specification']);
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('H' . ($key + 2), $value['ratio']);
		}
		
		
		$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$filename = "基本业绩提成比例表".date('Ymdhis').".xls";
		$filename = iconv("utf-8", "gb2312", $filename);
		ob_end_clean();//清除缓冲区,避免乱码
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
		$special_business_ratio = $this -> db_U8 -> getClassificationName($special_business_ratio);
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
		-> setCellValue('F1', '回款下限') -> setCellValue('G1', '回款上限') -> setCellValue('H1', '回款达标业绩提成比例');
		
		foreach ($special_business_ratio as $key => $value) {
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('A' . ($key + 2), $key+1);
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('B' . ($key + 2), $value['salesman_id']);
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('C' . ($key + 2), $value['salesman_name']);
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('D' . ($key + 2), $value['classification_id']);
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('E' . ($key + 2), $value['classification_name']);
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('F' . ($key + 2), $value['low_limit']);
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('G' . ($key + 2), $value['high_limit']);
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('H' . ($key + 2), $value['ratio']."%");
		}
		
		
		$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$filename = "达标业绩提成比例表".date('Ymdhis').".xls";
		$filename = iconv("utf-8", "gb2312", $filename);
		ob_end_clean();//清除缓冲区,避免乱码
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

	public function generateComplicateSearchSettleContactExcelFile(){
		vendor('PHPExcel.PHPExcel');
		$this -> db_U8 = D("U8");
		
		$search_settled_contact_data = session('search_settled_contact_data');
		// print_r($search_settled_contact_data);
		session('search_settled_contact_data',null);
		
		//查数据
		$condition = array();
		$temp_array = array();
		$condition['contact_id'] = $search_settled_contact_data['search_contact_id'];
		$condition['cSOCode'] = $search_settled_contact_data['search_cSOCode'];
		$condition['salesman_id'] = $search_settled_contact_data['search_salesman_id'];
		$condition['salesman_name'] = $search_settled_contact_data['search_salesman_name'];
		$condition['customer_id'] = $search_settled_contact_data['search_customer_id'];
		$condition['customer_name'] = $search_settled_contact_data['search_customer_name'];
		$condition['classification_id'] = $search_settled_contact_data['search_classification_id'];
		$condition['inventory_id'] = $search_settled_contact_data['search_inventory_id'];
		$condition['specification'] = $search_settled_contact_data['search_specification'];
		$condition['colour'] = $search_settled_contact_data['search_colour'];
		$type = $_POST['search_type'];
		foreach ($condition as $key => $value) {
			if($value == ""){
				unset($condition[$key]);
			}
		}
		
		$res = $this -> db_contact_detail -> searchByCondition($condition);
		
		foreach ($res as $key => $value) {
			if($this->db_contact_main->checkItemSettled($value['contact_id'])){
				
			}else{
				unset($res[$key]);
			}
		}
		
		$search_begin_date = $search_settled_contact_data['search_begin_date'];
		$search_end_date = $search_settled_contact_data['search_end_date'];		
		
		if($condition == null){
			$res = $this -> db_contact_main -> getSettledContactDetail($search_begin_date,$search_end_date);
		}
		
		if($condition == null){
			
		}else{
			foreach ($res as $key => $value) {
				if($search_begin_date != null && $search_end_date != null){
					if($value['settled_date'] < $search_begin_date || $value['settled_date'] > $search_end_date){
						unset($res[$key]);
						continue;
					}
				}
			}
		}
		
		
		if($type == "settling"){
			//do nothing
		}elseif($type == "settled"){
		//	$count_settled_contact_detail = count($res);
			//do nothing
		}elseif($type == "commission_business"){
			//do nothing
		}
		
		$temp_trans = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ', );
		$objPHPExcel = new \PHPExcel();

		$objPHPExcel -> getProperties() -> setCreator("提成管理系统")
		 -> setLastModifiedBy("提成管理系统") -> setTitle("已结算合同") 
		 -> setSubject("已结算合同") -> setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
		 -> setKeywords("office 2007 openxml php") -> setCategory("Test result file");

		$objPHPExcel -> setActiveSheetIndex(0);

		$objPHPExcel -> getActiveSheet() -> setTitle('已结算合同');
		
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('A') -> setWidth(18);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('B') -> setWidth(18);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('C') -> setWidth(18);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('D') -> setWidth(30);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('E') -> setWidth(30);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('F') -> setWidth(18);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('G') -> setWidth(18);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('H') -> setWidth(30);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('I') -> setWidth(18);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('J') -> setWidth(18);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('K') -> setWidth(18);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('L') -> setWidth(30);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('M') -> setWidth(30);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('N') -> setWidth(18);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('O') -> setWidth(18);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('P') -> setWidth(30);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('Q') -> setWidth(18);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('R') -> setWidth(18);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('S') -> setWidth(18);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('T') -> setWidth(30);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('U') -> setWidth(30);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('V') -> setWidth(18);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('W') -> setWidth(18);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('X') -> setWidth(30);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('Y') -> setWidth(18);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('Z') -> setWidth(18);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('AA') -> setWidth(18);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('AB') -> setWidth(30);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('AC') -> setWidth(30);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('AD') -> setWidth(18);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('AE') -> setWidth(18);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('AF') -> setWidth(30);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('AG') -> setWidth(30);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('AH') -> setWidth(30);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('AI') -> setWidth(30);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('AJ') -> setWidth(30);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('AK') -> setWidth(30);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('AL') -> setWidth(30);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('AM') -> setWidth(30);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('AN') -> setWidth(30);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('AO') -> setWidth(30);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('AP') -> setWidth(30);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('AQ') -> setWidth(30);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('AR') -> setWidth(30);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('AS') -> setWidth(30);
		$objPHPExcel -> getActiveSheet() -> getDefaultStyle() -> getFont() -> setSize(12);
		
		$objPHPExcel -> setActiveSheetIndex(0) -> setCellValue('A1', '#') -> setCellValue('B1', '合同号') 
		-> setCellValue('C1', '销售订单号') -> setCellValue('D1', '客户编码') -> setCellValue('E1', '客户名称') 
		-> setCellValue('F1', '业务员编码') -> setCellValue('G1', '业务员姓名') -> setCellValue('H1', '存货编码')
		-> setCellValue('I1', '存货类别编码') -> setCellValue('J1', '存货名称') -> setCellValue('K1', '规格型号')
		-> setCellValue('L1', '颜色') -> setCellValue('M1', '芯线颜色')-> setCellValue('N1', '现货')
		-> setCellValue('O1', '销售单价') -> setCellValue('P1', '总经理底价上浮比例') -> setCellValue('Q1', '总经理上浮底价') 
		-> setCellValue('R1', '技术底价上浮比例') -> setCellValue('S1', '技术上浮底价') -> setCellValue('T1', '底价（元）') 
		-> setCellValue('U1', '月结底价上浮比例') -> setCellValue('V1', '月结上浮底价') -> setCellValue('W1', '定制费')
		-> setCellValue('X1', '定制费上浮底价') -> setCellValue('Y1', '短米上浮底价（元）') -> setCellValue('Z1', '销售数量（米数）')
		-> setCellValue('AA1', '发货数量（米数）') -> setCellValue('AB1', '销售金额(元)') -> setCellValue('AC1', '销售费用单价(元)')
		-> setCellValue('AD1', '销售费用比例') -> setCellValue('AE1', '销售费用(元)') -> setCellValue('AF1', '基本业绩提成比例')
		-> setCellValue('AG1', '达标业绩提成比例') -> setCellValue('AH1', '基本利润提成比例') -> setCellValue('AI1', '业务提成调整比例')
		-> setCellValue('AJ1', '利润提成调整比例') -> setCellValue('AK1', '底价调整金额(元)') -> setCellValue('AL1', '最终实际底价(元)')
		-> setCellValue('AM1', '基本业绩提成(元)') -> setCellValue('AN1', '回款达标业绩提成(元)') -> setCellValue('AO1', '基本利润提成(元)')
		-> setCellValue('AP1', '九折后基本利润提成(元)') -> setCellValue('AQ1', '达标折扣后基本利润提成(元)') -> setCellValue('AR1', '业绩利润提成汇总')
		-> setCellValue('AS1', '达标利润提成比例折扣')-> setCellValue('AT1', '汇率');
		$row_id = 0;
		foreach ($res as $key => $value) {
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('A' . ($row_id + 2), $row_id+1);
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('B' . ($row_id + 2), $value['contact_id']);
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('C' . ($row_id + 2), $value['cSOCode']);
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('D' . ($row_id + 2), $value['customer_id']);
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('E' . ($row_id + 2), $value['customer_name']);
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('F' . ($row_id + 2), $value['salesman_id']);
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('G' . ($row_id + 2), $value['salesman_name']);
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('H' . ($row_id + 2), $value['inventory_id']);
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('I' . ($row_id + 2), $value['classification_id']);
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('J' . ($row_id + 2), $value['inventory_name']);
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('K' . ($row_id + 2), $value['specification']);
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('L' . ($row_id + 2), $value['colour']);
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('M' . ($row_id + 2), $value['coreColour']);
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('N' . ($row_id + 2), $value['inStore']);
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('O' . ($row_id + 2), $value['sale_price']);
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('P' . ($row_id + 2), $value['gm_ratio']."%");
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('Q' . ($row_id + 2), $value['gm_price']);
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('R' . ($row_id + 2), $value['skill_ratio']."%");
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('S' . ($row_id + 2), $value['skill_price']);
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('T' . ($row_id + 2), $value['cost_price']);
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('U' . ($row_id + 2), $value['special_approve_float_price_ratio']."%");
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('V' . ($row_id + 2), $value['special_approve_float_price']);
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('W' . ($row_id + 2), $value['custom_fee']);
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('X' . ($row_id + 2), $value['custom_fee_float_price']);
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('Y' . ($row_id + 2), $value['float_price']);
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('Z' . ($row_id + 2), $value['sale_quantity']);
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('AA' . ($row_id + 2), $value['delivery_quantity']);
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('AB' . ($row_id + 2), $value['delivery_money']);
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('AC' . ($row_id + 2), $value['sale_expense']);
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('AD' . ($row_id + 2), $value['sale_expense_ratio']."%");
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('AE' . ($row_id + 2), $value['end_sale_expense']);
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('AF' . ($row_id + 2), $value['normal_business_ratio']."%");
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('AG' . ($row_id + 2), $value['special_business_ratio']."%");
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('AH' . ($row_id + 2), $value['normal_profit_ratio']."%");
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('AI' . ($row_id + 2), $value['business_adjust']."%");
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('AJ' . ($row_id + 2), $value['profit_adjust']."%");
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('AK' . ($row_id + 2), $value['cost_price_adjust']);
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('AL' . ($row_id + 2), $value['end_cost_price']);
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('AM' . ($row_id + 2), $value['normal_business']);
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('AN' . ($row_id + 2), $value['special_business']);
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('AO' . ($row_id + 2), $value['normal_profit']);
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('AP' . ($row_id + 2), $value['normal_profit_1']);
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('AQ' . ($row_id + 2), $value['normal_profit_2']);
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('AR' . ($row_id + 2), $value['total_business_profit']);
			$objPHPExcel -> getActiveSheet(0) -> setCellValue('AS' . ($row_id + 2), $value['normal_profit_discount_ratio']."%");
            $objPHPExcel -> getActiveSheet(0) -> setCellValue('AT' . ($row_id + 2), $value['exch_rate']);
			$row_id = $row_id+1;
		}
		
		$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$filename = "已结算合同".date('Ymdhis').".xls";
		$filename = iconv("utf-8", "gb2312", $filename);
		ob_end_clean();//清除缓冲区,避免乱码
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