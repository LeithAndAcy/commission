<?php
namespace Home\Controller;
use Think\Controller;
class ExcelController extends Controller {
	
	private $db_normal_profit_ratio;
	private $db_price_float_ratio;
	function _initialize() {
		$this -> db_price_float_ratio = D("PriceFloatRatio");
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
		

		$objPHPExcel -> setActiveSheetIndex(0) -> setCellValue('A1', '#') -> setCellValue('B1', '存货类别编码') 
		-> setCellValue('C1', '货存类别名称') -> setCellValue('D1', '底价下限') -> setCellValue('E1', '底价上限') 
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