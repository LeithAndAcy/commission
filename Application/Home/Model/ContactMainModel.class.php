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
	
	public function getSettlingContact($all_contact_main){
		$res = $this -> query("select contact_id,customer_id,salesman_id,cSOCode from commission_contact_main where settlement = 1 and settled = 0 and settling = 1 and manual != 1");
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
}
?>