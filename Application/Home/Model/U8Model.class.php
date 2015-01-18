<?php
namespace Home\Model;
use Think\Model;
class U8Model extends Model {
	protected $connection = array(
		'DB_TYPE' => 'sqlsrv', // 数据库类型
		'DB_HOST' => 'localhost', // 服务器地址
		'DB_NAME' => 'U8', // 数据库名
		'DB_USER' =>'sa',
		'DB_PWD' =>'000000',
		'DB_PORT' => 1433, // 端口
		'tablePrefix' => ''
	);
	protected $trueTableName;
	public function getEditedContactMain($begin_date,$end_date){
		// so_somain 取订单号，合同号，客户编码，业务员编码
		$res = $this -> query("select cSOCode,cDefine2 as contact_id,cPersonCode as salesman_id,cCusCode as customer_id from SO_SOMain where dmoddate between '$begin_date' and '$end_date'");
		return $res;
	}
	public function getContactDetail($contact_main){
		//so_soDetail 根据订单号取订单明细的数量，存货编码，单价，成本价，存货编码，数量？
		$condition = array();
		foreach ($contact_main as $key => $value) {
			$temp = $value['cSOCode'];
			$res = $this -> query("select cSOCode,iQuantity as sale_quantity,cInvCode as inventory_id,iTaxUnitPrice as sale_price,iQuotedPrice as cost_price,iFHQuantity as delivery_quantity,iFHMoney as delivery_money from SO_SODetails where cSoCode = '$temp'");
			$temp_count = count($res,0);
			foreach ($res as $kk => $vv) {
				foreach($vv as $kkk => $vvv){
					$pos = strpos($vvv, ".");
					if($pos !== FALSE){
						$aaa=  substr($vvv, 0,$pos+3);
						$res[$kk][$kkk] = substr($vvv, 0,$pos);
						
					}
				}
			}
			$contact_main[$key]['count_length'] = $temp_count;
			$contact_main[$key]['contact_detail'] = $res;
		}
		return $contact_main;
	}
	public function getInventoryDetail($contact_detail){
		//Inventory Table  取商品的信息，存货类别编码，存货名称，规格型号，颜色
		// $this -> trueTableName = "Inventory";
		
		foreach ($contact_detail as $key => $value) {
			$temp_contact_detail = $value['contact_detail'];
			foreach ($temp_contact_detail as $kk => $vv) {
				$temp_inventory_id = $vv['inventory_id'];
			}
			$res = $this -> query("select cInvCCode as classification_id,cInvName as inventory_name,cInvCCode as classification,cInvStd as specification form Inventory where cInvCode='$temp_inventory_id'");
			print_r($res);exit;
		}
		
	}
	
}
?>