<?php
namespace Home\Model;
use Think\Model;
class ContactDetailModel extends Model {
		
	public function deleteItems($arr_check_list = array()){
		foreach ($arr_check_list as $key => $value) {
			$condition['contact_id'] = $value;
			$this -> where($condition)->delete();
		}
	}
	public function addContactDetail($all_contact_detail){
		foreach ($all_contact_detail as $key => $value) {
			$contact_detail = $value['contact_detail'];
			foreach ($contact_detail as $kk => $vv) {
				$vv['contact_id'] = $value['contact_id'];
				unset($vv['cSOCode']);
				$this -> add($vv);
			}
		}
	}
	public function getContactDetail($contact_main){
		$condition = array();
		$contact_detail = array();
		foreach ($contact_main as $key => $value) {
			$condition['contact_id'] = $value['contact_id'];
			$contact_main[$key]['contact_detail'] = $this -> where($condition)->select();
		}
		$i=0;
		foreach ($contact_main as $key => $value) {
			foreach ($value['contact_detail'] as $kk => $vv) {
				$contact_detail[$i] = $vv;
				$contact_detail[$i]['customer_id'] = $value['customer_id'];
				$contact_detail[$i]['customer_name'] = $value['customer_name'];
				$contact_detail[$i]['salesman_id'] = $value['salesman_id'];
				$contact_detail[$i]['salesman_name'] = $value['salesman_name'];
				$contact_detail[$i]['normal_business_ratio'] *= 100;
				$contact_detail[$i]['normal_profit_ratio'] *= 100;
				$i++;
			}
		}
		return $contact_detail;
	}
	// public function getSettlementContactRatio($contact_main){
		// $condition = array();
		// $contact_detail = array();
		// foreach ($contact_main as $key => $value) {
			// $condition['contact_id'] = $value['contact_id'];
			// $contact_main[$key]['contact_detail'] = $this -> where($condition)->getField('inventory_id',true);
		// }
		// return $contact_main;
	// }
	public function updateSettlementRatio($arr_ratio=array()){
		$condition = array();
		$data = array();
		foreach ($arr_ratio as $key => $value) {
			$condition['contact_id'] = $value['contact_id'];
			$condition['inventory_id'] = $value['inventory_id'];
			$data['normal_business_ratio'] = $value['normal_business_ratio']*0.01;
			$data['normal_profit_ratio'] = $value['normal_profit_ratio']*0.01;
			$this -> where($condition) -> save($data);
		}
	}
	public function getContactTotalMoney($contact_id){
		$condition = array();
		$condition['contact_id'] = $contact_id;
		$total_money = $this -> where($condition) -> sum('delivery_money');
		return $total_money;
	}
}
?>