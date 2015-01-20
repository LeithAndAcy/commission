<?php
namespace Home\Controller;
use Think\Controller;
class ExcelController extends Controller {
	
	private $db_normal_profit_ratio;
	private $db_price_float_ratio;
	function _initialize() {
		
	}

	public function loadPriceFloatExcel() {
		$this -> db_price_float_ratio = D("PriceFloatRatio");
		$excel_name = $_FILES['normal_profit_ratio_excel']['name'];
		$index = stripos($excel_name, ".");
		if (strtolower(substr($excel_name, $index + 1)) != "xls" && strtolower(substr($excel_name, $index + 1)) != "xlsx") {
			$this -> error("上传文件格式出错");
		}
		vendor('PHPExcel.PHPExcel');
		Vendor('PHPExcel.PHPExcel.IOFactory');
		Vendor('PHPExcel.PHPExcel.Reader.Excel5.php');
		$PHPReader = new \PHPExcel_Reader_Excel5();
		$PHPexcel = new \PHPExcel();
		$excel_obj = $_FILES['normal_profit_ratio_excel']['tmp_name'];
		$PHPExcel_obj = $PHPReader -> load($excel_obj);
		$currentSheet = $PHPExcel_obj -> getSheet(0);
		$highestColumn = $currentSheet -> getHighestColumn();
		$highestRow = $currentSheet -> getHighestRow();
		$arr_price_float_ratio = array();
		for ($j = 2; $j <= $highestRow; $j++)//从第一行开始读取数据
		{
			for ($k = 'B'; $k <= $highestColumn; $k++)//从A列读取数据
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

}
?>