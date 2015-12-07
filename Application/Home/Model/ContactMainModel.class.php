<?php
namespace Home\Model;
use Think\Model;
class ContactMainModel extends Model {
		
	public function checkItemSettled($contact_id){
		$condition = array();
		$condition['contact_id'] = $contact_id;
		$res = $this -> where($condition) -> find();
		if($res['settled'] == 1){
			return TRUE;
		}else{
			return FALSE;
		}
	}	
		
	public function deleteItems($arr_check_list){
		$condition = array();
		foreach ($arr_check_list as $key => $value) {
			$condition['contact_id'] = $value;
			$this -> where($condition)->delete();
		}
	}
	public function addContactMain($all_contact_main,$begin_date){
		$month = date('Y-m',strtotime($begin_date));
		foreach ($all_contact_main as $key => $value) {
			// $last_month = date('Y-m',strtotime('-1 month'));
			$value['date'] = $month;
			$this -> add($value);
		}
	}
	public function addItem($data){
		$this -> add($data);
	}
	public function getSettlementContact($Page){
		$condition = array();
		$condition['settlement'] = 1;
		$condition['settled'] = 0;
		$condition['settling'] = 0;
		$condition['manual'] = 0;
		$res = $this -> where($condition)->limit($Page->firstRow.','.$Page->listRows) -> select();
		return $res;
	}
	
	public function countSettlementContact(){
		$condition = array();
		$condition['settlement'] = 1;
		$condition['settled'] = 0;
		$condition['settling'] = 0;
		$condition['manual'] = 0;
		$res = $this -> where($condition) -> count();
		return $res;
	}
	
	public function getSettlingContact($Page){
		$condition = array();
		$condition['settlement'] = 1;
		$condition['settled'] = 0;
		$condition['settling'] = 1;
		$res = $this -> where($condition)->limit($Page->firstRow.','.$Page->listRows) -> select();
		return $res;
	}
	public function countSettlingContact(){
		$condition = array();
		$condition['settlement'] = 1;
		$condition['settled'] = 0;
		$condition['settling'] = 1;
		$res = $this -> where($condition)->count();
		return $res;
	}
	public function getManualContact($Page){
		$condition = array();
		$condition['settlement'] = 1;
		$condition['manual'] = 1;
		$condition['settling'] = 0;
		$condition['settled'] = 0;
		$res = $this -> where($condition)->limit($Page->firstRow.','.$Page->listRows) -> select();
		return $res;
	}
	
	public function countManualContact(){
		$condition = array();
		$condition['settlement'] = 1;
		$condition['manual'] = 1;
		$condition['settling'] = 0;
		$condition['settled'] = 0;
		$res = $this -> where($condition) -> count();
		return $res;
	}
	
	public function getManualSettledContact($Page){
		$condition = array();
		$condition['settled'] = 1;
		$condition['manual'] = 1;
		$res = $this -> where($condition) ->limit($Page->firstRow.','.$Page->listRows)-> select();
		return $res;
	}
	
	public function countManualSettledContact(){
		$condition = array();
		$condition['settled'] = 1;
		$condition['manual'] = 1;
		$res = $this -> where($condition) -> count();
		return $res;
	}
	
	public function getEditedSettledContact($Page){
		$condition = array();
		$condition['settled'] = 1;
		$condition['edited'] = 1;
		$res =  $this -> where($condition) ->limit($Page->firstRow.','.$Page->listRows)-> select();
		return $res;
	}
	
	public function countEditedSettledContact(){
		$condition = array();
		$condition['settled'] = 1;
		$condition['edited'] = 1;
		$res =  $this -> where($condition) -> count();
		return $res;
	}
	public function getSettledContact($Page){
		$condition = array();
		$condition['settled'] = 1;
		$res = $this -> where($condition) ->limit($Page->firstRow.','.$Page->listRows)-> select();
		return $res;
	}
	
	public function countSettledContact(){
		$condition = array();
		$condition['settled'] = 1;
		$res = $this -> where($condition) -> count();
		return $res;
	}
	
	public function getContact($Page){
		$res = $this ->limit($Page->firstRow.','.$Page->listRows)->select();
		return $res;
	}
	public function getContactByCondition($condition){
		$res = $this -> where($condition) ->select();
		return $res;
	}
	public function setSettlingContact($contact_id){
		$condition = array();
		$condition['contact_id'] = $contact_id;
		$this -> where($condition)-> setField('settling',1);
	}
	public function setManualContact($contact_id){
		$condition = array();
		$condition['contact_id'] = $contact_id;
		$this -> where($condition)-> setField('manual',1);
	}
	public function getContactSalemanAndCustomer($contact_id){
		$condition = array();
		$condition['contact_id'] = $contact_id;
		$res = $this -> where($condition) -> field("id,salesman_id,customer_id")->find(); 
		return $res;
	}
	public function getSettlingContactBySalesmanId($salesman_id){
		$condition = array();
		$condition['salesman_id'] = $salesman_id;
		$condition['settled'] = 0;
		$condition['settling'] = 1;
		$res = $this -> where($condition) -> select();
		return $res;
	}
	public function setContactSettled($arr_contact_id){
		$this_month = date('Y-m',strtotime("now"));
		$str_contact_id;
		foreach ($arr_contact_id as $key => $value) {
			$str_contact_id .= "'".$value."'".',';
		}
		$str_contact_id = substr($str_contact_id, 0,-1);
		$this -> query("update commission_contact_main set settled = 1 where date<>'$this_month' and contact_id in($str_contact_id)");
	}
	public function setContactEdited($contact_id){
		$condition = array();
		$condition['contact_id'] = $contact_id;
		$this -> where($condition) -> setField('edited',1);
	}

	public function getSettlementContactByContactId($contact_id){
		$condition = array();
		$condition['contact_id'] = $contact_id;
		$condition['settlement'] = 1;
		$condition['settled'] = 0;
		$condition['settling'] = 0;
		$condition['manual'] = 0;
		$res =  $this -> where($condition) -> find();
		return $res;
	}
	public function getSettlingContactByContactId($contact_id){
		$condition = array();
		$condition['contact_id'] = $contact_id;
		$condition['settlement'] = 1;
		$condition['settled'] = 0;
		$condition['settling'] = 1;
		$res = $this -> where($condition) -> find();
		return $res;
	}
	public function getSettledContactByContactId($contact_id){
		$condition = array();
		$condition['contact_id'] = $contact_id;
		$condition['settlement'] = 1;
		$condition['settled'] = 1;
		$res = $this -> where($condition) -> find();
		return $res;
	}
	public function getManualSettledContactByContactId($contact_id){
		$condition = array();
		$condition['contact_id'] = $contact_id;
		$condition['settlement'] = 1;
		$condition['settled'] = 1;
		$condition['manual'] = 1;
		$res = $this -> where($condition) -> find();
		return $res;
	}
	public function getEditedSettledContactByContactId($contact_id){
		$condition = array();
		$condition['contact_id'] = $contact_id;
		$condition['settlement'] = 1;
		$condition['settled'] = 1;
		$condition['edited'] = 1;
		$res = $this -> where($condition) -> find();
		return $res;
	}
	public function getSettlingContactDetail(){
		// $res = $this -> query("select * from commission_contact_main join commission_contact_detail on 
		// commission_contact_main.settling=1 and commission_contact_main.settled=0 and commission_contact_main.settlement=1
		// and commission_contact_main.contact_id = commission_contact_detail.contact_id
		// join commission_customer on commission_contact_main.customer_id = commission_customer.customer_id
		// join commission_area_price_float_ratio on commission_area_price_float_ratio.area = commission_customer.area
		// and commission_contact_detail.classification_id = commission_area_price_float_ratio.classification_id;");
		$res = $this -> query("
		select commission_contact_main.contact_id,commission_contact_main.customer_id,commission_contact_main.salesman_id,
		commission_contact_main.cSOCode,commission_contact_detail.contact_id,commission_contact_detail.inventory_id,
		commission_contact_detail.purchase,commission_contact_detail.classification_id,commission_contact_detail.classification_name,
		commission_contact_detail.inventory_name,commission_contact_detail.specification,commission_contact_detail.colour,
		commission_contact_detail.custom_fee,
		commission_contact_detail.sale_price,commission_contact_detail.cost_price,commission_contact_detail.float_price,
		commission_contact_detail.sale_quantity,commission_contact_detail.delivery_quantity,commission_contact_detail.normal_business_ratio,
		commission_contact_detail.special_business_ratio,commission_contact_detail.normal_profit_ratio,commission_contact_detail.business_adjust,
		commission_contact_detail.profit_adjust,commission_contact_detail.cost_price_adjust,commission_contact_detail.normal_business,
		commission_contact_detail.special_business,commission_contact_detail.normal_profit,commission_contact_detail.end_cost_price,
		commission_contact_detail.total_business_profit,commission_customer.customer_name,commission_customer.area_code,
		commission_customer.area,commission_area_price_float_ratio.ratio
		from commission_contact_main join commission_contact_detail on 
		commission_contact_main.settling=1 and commission_contact_main.settled=0 and commission_contact_main.settlement=1
		and commission_contact_main.contact_id = commission_contact_detail.contact_id
		left join commission_customer on commission_contact_main.customer_id = commission_customer.customer_id
		left join commission_area_price_float_ratio on commission_area_price_float_ratio.area = commission_customer.area
		and commission_contact_detail.classification_id = commission_area_price_float_ratio.classification_id
		");
		return $res;
	}
	public function getSettledContactDetail($begin_date,$end_date){
		$res = $this -> query("
		select commission_contact_detail.contact_id,commission_contact_detail.cSOCode,
		commission_contact_detail.inventory_id,commission_contact_detail.inventory_name,
		commission_contact_detail.customer_name,commission_contact_detail.customer_id,
		commission_contact_detail.salesman_name,commission_contact_detail.salesman_id,
		commission_contact_detail.purchase,commission_contact_detail.classification_id,commission_contact_detail.classification_name,
		commission_contact_detail.specification,commission_contact_detail.colour,
		commission_contact_detail.custom_fee,commission_contact_detail.settled_date,
		commission_contact_detail.sale_price,commission_contact_detail.cost_price,commission_contact_detail.float_price,
		commission_contact_detail.sale_quantity,commission_contact_detail.delivery_quantity,
		commission_contact_detail.normal_business_ratio * 100 as normal_business_ratio,
		commission_contact_detail.special_business_ratio * 100 as special_business_ratio,
		commission_contact_detail.normal_profit_ratio * 100 as normal_profit_ratio,
		commission_contact_detail.business_adjust * 100 as business_adjust,
		commission_contact_detail.profit_adjust * 100 as profit_adjust,
		commission_contact_detail.cost_price_adjust,commission_contact_detail.normal_business,
		commission_contact_detail.special_business,commission_contact_detail.normal_profit,commission_contact_detail.end_cost_price,
		commission_contact_detail.total_business_profit,
		commission_contact_detail.special_approve_float_price_ratio  * 100 as special_approve_float_price_ratio,
		commission_contact_detail.special_approve_float_price,
		commission_contact_detail.custom_fee,
		commission_contact_detail.custom_fee_float_price,
		commission_contact_detail.delivery_money,
		commission_contact_detail.sale_expense,
		commission_contact_detail.sale_expense_ratio * 100 as sale_expense_ratio,
		commission_contact_detail.end_sale_expense
		from commission_contact_detail join commission_contact_main on 
		commission_contact_main.settling=1 and commission_contact_main.settled=1 and commission_contact_main.settlement=1
		and commission_contact_main.cSOCode = commission_contact_detail.cSOCode
		and commission_contact_detail.settled_date >= '$begin_date'
		and  commission_contact_detail.settled_date <= '$end_date'
		");
		return $res;
	}
	public function deleteItem($contact_id){
		$condition = array();
		$condition['contact_id'] = $contact_id;
		$this -> where($condition)->delete();
	}
	public function getSettledContactOfMonth($month){
		$condition = array();
		$arr = array();
		$arr_contact_list = array();
		$condition['settled'] = 1;
		$condition['date'] = $month;
		// $res = $this -> where($condition) -> getField('salesman_id,contact_id');
		$res = $this -> where($condition) -> select();
		foreach ($res as $key => $value) {
			$arr[$value['salesman_id']] .= $value['contact_id'].',';
		}
		foreach ($arr as $key => $value) {
			$arr_contact_list[$key] = explode(',', $value);
		}
		foreach ($arr_contact_list as $key => $value) {
			foreach ($value as $kk => $vv) {
				if($vv == null){
					unset($arr_contact_list[$key][$kk]);
				}
			}	
		}
		return $arr_contact_list;
	}
	public function getSettlementContactDetail(){
		// $res = $this -> query("select * from commission_contact_main join commission_contact_detail on 
		// commission_contact_main.settling=0 and commission_contact_main.settled=0 and commission_contact_main.settlement=1
		// and commission_contact_main.contact_id = commission_contact_detail.contact_id
		// left join commission_customer on commission_contact_main.customer_id = commission_customer.customer_id
		// left join commission_area_price_float_ratio on commission_area_price_float_ratio.area = commission_customer.area
		// and commission_contact_detail.classification_id = commission_area_price_float_ratio.classification_id;
		// ");
		$res = $this -> query("
		select commission_contact_main.contact_id,commission_contact_main.customer_id,commission_contact_main.salesman_id,
		commission_contact_main.cSOCode,commission_contact_detail.contact_id,commission_contact_detail.inventory_id,
		commission_contact_detail.purchase,commission_contact_detail.classification_id,commission_contact_detail.classification_name,
		commission_contact_detail.inventory_name,commission_contact_detail.specification,commission_contact_detail.colour,
		commission_contact_detail.custom_fee,
		commission_contact_detail.sale_price,commission_contact_detail.cost_price,commission_contact_detail.float_price,
		commission_contact_detail.sale_quantity,commission_contact_detail.delivery_quantity,commission_contact_detail.normal_business_ratio,
		commission_contact_detail.special_business_ratio,commission_contact_detail.normal_profit_ratio,commission_contact_detail.business_adjust,
		commission_contact_detail.profit_adjust,commission_contact_detail.cost_price_adjust,commission_contact_detail.normal_business,
		commission_contact_detail.special_business,commission_contact_detail.normal_profit,commission_contact_detail.end_cost_price,
		commission_contact_detail.total_business_profit,commission_customer.customer_name,commission_customer.area_code,
		commission_customer.area,commission_area_price_float_ratio.ratio
		from commission_contact_main join commission_contact_detail on 
		commission_contact_main.settling=0 and commission_contact_main.settled=0 and commission_contact_main.settlement=1
		and commission_contact_main.contact_id = commission_contact_detail.contact_id
		left join commission_customer on commission_contact_main.customer_id = commission_customer.customer_id
		left join commission_area_price_float_ratio on commission_area_price_float_ratio.area = commission_customer.area
		and commission_contact_detail.classification_id = commission_area_price_float_ratio.classification_id
		");
		return $res;
	}
	public function getSettlingContactTotalDeliveryMoney(){
		$res = $this -> query("select commission_contact_main.salesman_id,SUM(commission_contact_detail.delivery_money) as total_delivery_money from commission_contact_detail right join commission_contact_main on 
		commission_contact_main.settling=1 and commission_contact_main.settled=0 and commission_contact_main.settlement=1 and commission_contact_main.contact_id = commission_contact_detail.contact_id
		group by commission_contact_main.salesman_id;");
		$arr = array();
		foreach ($res as $key => $value) {
			$arr[$value['salesman_id']] = $value['total_delivery_money'];
		}
		return $arr;
	}
	public function countSettlementContactDetail(){
		$res = $this -> query("select count (*) as count_contact_detail from commission_contact_main join commission_contact_detail on 
		commission_contact_main.settling=0 and commission_contact_main.settled=0 and commission_contact_main.settlement=1
		and commission_contact_main.contact_id = commission_contact_detail.contact_id;");
		return $res[0]['count_contact_detail'];
	}
	public function countSettlingContactDetail(){
		$res = $this -> query("select count (*) as count_contact_detail from commission_contact_main join commission_contact_detail on 
		commission_contact_main.settling=1 and commission_contact_main.settled=0 and commission_contact_main.settlement=1
		and commission_contact_main.contact_id = commission_contact_detail.contact_id;");
		return $res[0]['count_contact_detail'];
	}
	public function countSettledContactDetail(){
		$res = $this -> query("select count (*) as count_contact_detail from commission_contact_main join commission_contact_detail on 
		commission_contact_main.settling=1 and commission_contact_main.settled=1 and commission_contact_main.settlement=1
		and commission_contact_main.cSOCode = commission_contact_detail.cSOCode;");
		return $res[0]['count_contact_detail'];
	}
	public function getSettlingContactGroupByDate(){
		$res = $this -> query("select date,contact_id from commission_contact_main where settling = 1 and settled = 0 and settlement = 1 order by date");
		$arr = array();
		foreach ($res as $key => $value) {
			$arr[$value['date']][$key] = $value['contact_id'];
		}
		return $arr;
	}
	public function searchCountByDate($search_begin_date,$search_end_date){
		$res = $this -> where("date between '$search_begin_date' and '$search_end_date'") -> count();
		
		return $res;
	}
	public function getSettlementContactcSOCode(){
		$res = $this -> query("select commission_contact_detail.cSOCode,commission_contact_detail.inventory_id,commission_contact_detail.delivery_quantity from commission_contact_detail join commission_contact_main on 
		commission_contact_main.settling=0 and commission_contact_main.settled=0 and commission_contact_main.settlement=1 
		and commission_contact_main.contact_id = commission_contact_detail.contact_id");
		return $res;		
	}
}
?>