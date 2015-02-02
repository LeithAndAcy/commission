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
		foreach ($all_contact_main as $key => $value) {
			$this -> add($value);
		}
	}
	public function getSettlementContact(){
		$res = $this -> query("select contact_id,customer_id,salesman_id,cSOCode from commission_contact_main where settlement = 1 and settled = 0 and settling = 0 and manual != 1");
		return $res;
	}
	
	public function getSettlingContact(){
		$res = $this -> query("select contact_id,customer_id,salesman_id,cSOCode from commission_contact_main where settlement = 1 and settled = 0 and settling = 1");
		return $res;
	}
	public function getManualContact(){
		$res = $this -> query("select contact_id,customer_id,salesman_id,cSOCode from commission_contact_main where settlement = 1 and settled = 0 and settling = 0 and manual = 1");
		return $res;
	}
	public function getContact($condition){
		$res = $this -> where($condition)->select();
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
	public function setContactSettled($salesman_id){
		$condition = array();
		$condition['salesman_id'] = $salesman_id;
		$condition['settled'] = 0;
		$condition['settling'] = 1;
		$this -> where($condition) -> setField("settled",1);
	}
}
?>