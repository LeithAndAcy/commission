<?php
namespace Home\Model;
use Think\Model;
class U8Model extends Model {
	protected $connection = array(
		'DB_TYPE' => 'sqlsrv', // 数据库类型
		'DB_HOST' => 'localhost', // 服务器地址
	//	'DB_NAME' => 'U8', // 数据库名
		'DB_NAME' => 'UFDATA_111_2015', // 数据库名
		'DB_USER' =>'sa',
		'DB_PWD' =>'aaa111',//密码
		'DB_PORT' => 1433, // 端口
		'tablePrefix' => ''
	);
	
	protected $trueTableName;
	public function getEditedContactMain($begin_date,$end_date){
		// so_somain 取订单号，合同号，客户编码，业务员编码
		// $res = $this -> query("select cSOCode,cDefine2 as contact_id,cPersonCode as salesman_id,cCusCode as customer_id from SO_SOMain where
		// ((dChangeVerifyDate is null and cVerifier is not null and dDate not between '$begin_date' and '$end_date') 
		// OR (dChangeVerifyDate is not null and cChangeVerifier is not null and dDate not between '$begin_date' and '$end_date'))");
		$res = null;
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
	
	
	public function getContactDetailByDate($begin_date,$end_date){
		
		// $res = $this -> query("select SO_SOMain.cPersonCode as salesman_id,SO_SOMain.cCusCode as customer_id,
		// Person.cPersonName as salesman_name,Customer.cCusName as customer_name,
		// SO_SOMain.cDefine2 as contact_id,SO_SODetails.cSOCode,SO_SODetails.iQuantity as sale_quantity,SO_SODetails.cInvCode as inventory_id,
		// SO_SODetails.iTaxUnitPrice as sale_price,SO_SODetails.iQuotedPrice as cost_price,SO_SODetails.iFHQuantity as delivery_quantity,SO_SODetails.iFHMoney as delivery_money,
		// Inventory.cInvCCode as classification_id,Inventory.cInvName as inventory_name,Inventory.cInvStd as specification,
		// Inventory.cInvDefine1 as colour,Inventory.bPurchase as purchase,InventoryClass.cInvCName as classification_name
		// from SO_SOMain join SO_SODetails on SO_SOMain.cSOCode = SO_SODetails.cSOCode
		// and (SO_SOMain.cCloser is null and ((SO_SOMain.dChangeVerifyDate is null and SO_SOMain.dverifydate between '$begin_date' and '$end_date' and SO_SOMain.dDate between '$begin_date' and '$end_date') 
		// OR (SO_SOMain.dChangeVerifyDate is not null and SO_SOMain.dChangeVerifyDate between '$begin_date' and '$end_date' and SO_SOMain.dDate between '$begin_date' and '$end_date')))
		// join Inventory on SO_SODetails.cInvCode = Inventory.cInvCode
		// join InventoryClass on Inventory.cInvCCode = InventoryClass.cInvCCode
		// left join Person on Person.cPersonCode = SO_SOMain.cPersonCode
		// left join Customer on Customer.cCusCode = SO_SOMain.cCusCode
		// ");
		$res = $this -> query("select SO_SOMain.cPersonCode as salesman_id,SO_SOMain.cCusCode as customer_id,
		Person.cPersonName as salesman_name,Customer.cCusName as customer_name,
		SO_SOMain.cDefine2 as contact_id,cDefine33 as custom_fee,SO_SODetails.cSOCode,SO_SODetails.iQuantity as sale_quantity,SO_SODetails.cInvCode as inventory_id,
		SO_SODetails.iTaxUnitPrice as sale_price,SO_SODetails.iQuotedPrice as cost_price,SO_SODetails.iFHQuantity as delivery_quantity, SO_SODetails.iNatSum,
		Inventory.cInvCCode as classification_id,Inventory.cInvName as inventory_name,Inventory.cInvStd as specification,
		Inventory.cInvDefine1 as colour,Inventory.bPurchase as purchase,InventoryClass.cInvCName as classification_name
		from SO_SOMain join SO_SODetails on SO_SOMain.cSOCode = SO_SODetails.cSOCode
		and (
		(SO_SOMain.dChangeVerifyDate is null and SO_SOMain.dverifydate between '$begin_date' and '$end_date') 
		OR (SO_SOMain.dChangeVerifyDate is not null and SO_SOMain.cChangeVerifier is not null and SO_SOMain.dverifydate between '$begin_date' and '$end_date')
		)
		join Inventory on SO_SODetails.cInvCode = Inventory.cInvCode
		join InventoryClass on Inventory.cInvCCode = InventoryClass.cInvCCode
		left join Person on Person.cPersonCode = SO_SOMain.cPersonCode
		left join Customer on Customer.cCusCode = SO_SOMain.cCusCode
		order by SO_SODetails.AutoID
		");
		return $res;
	}
	
	
	public function getInventoryDetail($contact_detail){
		//Inventory Table  取商品的信息，存货类别编码，存货名称，规格型号，颜色
		// $this -> trueTableName = "Inventory";
		
		foreach ($contact_detail as $key => $value) {
			$temp_inventory_id = $value['inventory_id'];
			$res = $this -> query("select top 1 cInvCCode as classification_id,cInvName as inventory_name,cInvStd as specification,cInvDefine1 as colour from Inventory where cInvCode='$temp_inventory_id'");
			$contact_detail[$key]['classification_id'] = $res[0]['classification_id'];
			$contact_detail[$key]['inventory_name'] = $res[0]['inventory_name'];
			$contact_detail[$key]['specification'] = $res[0]['specification'];
			$contact_detail[$key]['colour'] = $res[0]['colour'];
		}
		return $contact_detail;
	}
	public function getInventoryDetailOfConflictPage($contact_detail){
		//Inventory Table  取商品的信息，存货类别编码，存货名称，规格型号，颜色
		// $this -> trueTableName = "Inventory";
		
		foreach ($contact_detail as $key => $value) {
			foreach ($value['contact_detail'] as $kk => $vv) {
				$temp_inventory_id = $vv['inventory_id'];
				$res = $this -> query("select top 1 cInvCCode as classification_id,cInvName as inventory_name,cInvStd as specification,cInvDefine1 as colour from Inventory where cInvCode='$temp_inventory_id'");
				$contact_detail[$key]['contact_detail'][$kk]['classification_id'] = $res[0]['classification_id'];
				$contact_detail[$key]['contact_detail'][$kk]['inventory_name'] = $res[0]['inventory_name'];
				$contact_detail[$key]['contact_detail'][$kk]['specification'] = $res[0]['specification'];
				$contact_detail[$key]['contact_detail'][$kk]['colour'] = $res[0]['colour'];
			}
		}
		return $contact_detail;
	}
	
	public function getClassificationName($array){
		foreach ($array as $key => $value) {
			$temp_classification_id = $value['classification_id'];
			$res = $this -> query("select top 1 cInvCCode as classification_id,cInvCName as classification_name from InventoryClass where cInvCCode='$temp_classification_id'");
			$array[$key]['classification_name'] = $res[0]['classification_name'];
		}
		return $array;
	}
	
	public function getAllContactMain($begin_date,$end_date){
		// so_somain 取订单号，合同号，客户编码，业务员编码
		// $res = $this -> query("select cSOCode,cDefine2 as contact_id,cPersonCode as salesman_id,cCusCode as customer_id from SO_SOMain 
		// where cCloser is null and ( (dChangeVerifyDate is null and dverifydate between '$begin_date' and '$end_date' and dDate between '$begin_date' and '$end_date') OR (dChangeVerifyDate is not null and dChangeVerifyDate between '$begin_date' and '$end_date' and dDate between '$begin_date' and '$end_date'))");
		$res = $this -> query("select cSOCode,cDefine2 as contact_id,cPersonCode as salesman_id,cCusCode as customer_id from SO_SOMain 
		where ( (SO_SOMain.dChangeVerifyDate is null and SO_SOMain.dverifydate between '$begin_date' and '$end_date')
		 OR(SO_SOMain.dChangeVerifyDate is not null and SO_SOMain.cChangeVerifier is not null and SO_SOMain.dverifydate between '$begin_date' and '$end_date')
		 )");
		return $res;
	}
	
	public function getCustomerFunds($begin_date,$end_date){
		//Ap_Close_bill
		$res_plus = $this -> query("select cDwCode as customer_id,iAmount as funds from Ap_CloseBill where (dVouchDate between '$begin_date' and '$end_date') AND (cVouchType = 48) AND (cCheckMan is not null) AND (cFlag = 'AR')");
		$res_minus = $this -> query("select cDwCode as customer_id,iAmount as funds from Ap_CloseBill where (dVouchDate between '$begin_date' and '$end_date') AND (cVouchType = 49) AND (cCheckMan is not null) AND (cFlag = 'AR')");
		$arr_funds = array();
		foreach ($res_plus as $key => $value) {
			$arr_funds[$value['customer_id']] += $value['funds'];
		}
		foreach ($res_minus as $key => $value) {
			$arr_funds[$value['customer_id']] -= $value['funds'];
		}
		return $arr_funds;
	}
	public function getFundsBySalesmanAndDate($salesman_id){
		//某个员工上个月的总回款
		$first_day = date('Y-m-01',strtotime('-1 month'));
		$last_day = date('Y-m-t', strtotime('-1 month'));
		$res = $this -> query("select sum(iAmount) as total_funds from Ap_CloseBill where (dVouchDate between '$first_day' and '$last_day') AND (cVouchType = 48) AND (cCheckMan is not null) AND(cPerson = '$salesman_id')");
		$total_funds = $res[0]['total_funds'];
		return $total_funds;
	}
	public function checkInventoryBPurchase($inventory_id){
		$res = $this -> query("select bPurchase from inventory where cInvCode='$inventory_id'");
		return $res[0]['bPurchase'];
	}
	// public function getCustomerArea($area_code){
		// $res = $this -> query("select cDCName from DistrictClass where cDCCode='$area_code'");
		// return $res[0]['cDCName'];
	// }
	public function getAllCustomerAndArea(){
		$res = $this -> query("select Customer.cCusCode as customer_id, Customer.cCusName as customer_name, Customer.cDCCode as area_code, DistrictClass.cDCName as area from Customer left join DistrictClass on Customer.cDCCode=DistrictClass.cDCCode");
		return $res;
	}
	
	public function getDeliveryQuantityAndINatSum($cSOCode,$inventory_id){
		$res = $this -> query("select iFHQuantity as delivery_quantity,iQuantity as sale_quantity,iNatSum from SO_SODetails where cSOCode = '$cSOCode' AND cInvCode = '$inventory_id'");
		return $res[0];
	}
	
	
}
?>