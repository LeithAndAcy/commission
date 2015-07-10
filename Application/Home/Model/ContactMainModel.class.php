<?php
namespace Home\Model;
use Think\Model;
class ContactMainModel extends Model {
		
		
		
	public function deleteItems($arr_check_list){
		$condition = array();
		foreach ($arr_check_list as $key => $value) {
			$condition['contact_id'] = $value;
			$this -> where($condition)->delete();
		}
	}
	public function addContactMain($all_contact_main){
		$this_month = date('Y-m');
		foreach ($all_contact_main as $key => $value) {
			$last_month = date('Y-m',strtotime('-1 month'));
			$value['date'] = $this_month;
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
		$last_month = date('Y-m',strtotime('-1 month'));
		$str_contact_id;
		foreach ($arr_contact_id as $key => $value) {
			$str_contact_id .= "'".$value."'".',';
		}
		$str_contact_id = substr($str_contact_id, 0,-1);
		$this -> query("update commission_contact_main set settled = 1 where date='$last_month' and contact_id in($str_contact_id)");
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
		// $res = $this -> query("select * from commission_contact_main left join commission_contact_detail on 
		// commission_contact_main.settling=1 and commission_contact_main.settled=0 and commission_contact_main.settlement=1
		// and commission_contact_main.contact_id = commission_contact_detail.contact_id;");
		$res = $this -> query("select * from commission_contact_main left join commission_contact_detail on 
		commission_contact_main.settling=1 and commission_contact_main.settled=0 and commission_contact_main.settlement=1
		and commission_contact_main.contact_id = commission_contact_detail.contact_id
		left join commission_customer on commission_contact_main.customer_id = commission_customer.customer_id
		left join commission_area_price_float_ratio on commission_area_price_float_ratio.area = commission_customer.area
		and commission_contact_detail.classification_id = commission_area_price_float_ratio.classification_id;");
		return $res;
	}
	public function deleteItem($contact_id){
		$condition = array();
		$condition['contact_id'] = $contact_id;
		$this -> where($condition)->delete();
	}
	public function getSettledContactOfMonth($month){
		$condition = array();
		$condition['settled'] = 1;
		$condition['date'] = $month;
		$res = $this -> where($condition) -> getField('salesman_id,contact_id');
		return $res;
	}
	public function getSettlementContactDetail(){
		$res = $this -> query("select * from commission_contact_main left join commission_contact_detail on 
		commission_contact_main.settling=0 and commission_contact_main.settled=0 and commission_contact_main.settlement=1
		and commission_contact_main.contact_id = commission_contact_detail.contact_id
		left join commission_customer on commission_contact_main.customer_id = commission_customer.customer_id
		left join commission_area_price_float_ratio on commission_area_price_float_ratio.area = commission_customer.area
		and commission_contact_detail.classification_id = commission_area_price_float_ratio.classification_id;");
		return $res;
	}
	public function getSettlingContactTotalDeliveryMonry(){
		$res = $this -> query("select commission_contact_main.salesman_id,SUM(commission_contact_detail.delivery_money) as total_delivery_money from commission_contact_detail right join commission_contact_main on 
		commission_contact_main.settling=1 and commission_contact_main.settled=0 and commission_contact_main.settlement=1 and commission_contact_main.contact_id = commission_contact_detail.contact_id
		group by commission_contact_main.salesman_id;");
		$arr = array();
		foreach ($res as $key => $value) {
			$arr[$value['salesman_id']] = $value['total_delivery_money'];
		}
		return $arr;
	}
}
?>