<?php
namespace Home\Model;
use Think\Model;
class SettledHistoryModel extends Model {
	
	
	public function addItem($customer_id,$money){
		$data = array();
		$data['customer_id'] = $customer_id;
		$data['settled_money'] =  $money;
		$data['date'] = date('Y-m',time());
		$this -> add($data);
	}

}
?>