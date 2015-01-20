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
			unset($allContactMain[$key]['cSOCode']);
			$this -> add($allContactMain[$key]);
		}
	}
	public function getSettlementContact($all_contact_main){
		$res = $this -> query("select contact_id,customer_id,salesman_id from commission_contact_main where settlement = 1 and settled = 0 and settling = 0");
		return $res;
	}
}
?>