<?php
namespace Home\Model;
use Think\Model;
class CustomerFundsModel extends Model {
	public function updateItems($customer_funds=array()){
		$condition = array();
		foreach ($customer_funds as $key => $value) {
			$condition['customer_id'] = $value['customer_id'];
			$condition['salesman_id'] = $value['salesman_id'];
			$res = $this -> where($condition) -> find();
			if($res == null){
				$this -> add($value);
			}else{
				$this -> where($condition)->setInc('funds',$value['funds']);
			}
		}
	}
	public function getTotalCustomerFunds(){
		$res = $this -> select();
		foreach ($res as $key => $value) {
			$res[$key]['total_funds'] = $value['funds'] + $value['benefit'];
		}
		return $res;
	}
	public function setCustomerFunds($customer_id,$salesman_id,$benefit){
		$condition = array();
		$condition['customer_id'] = $customer_id;
		$condition['salesman_id'] = $salesman_id;
		$data['funds'] = 0;
		$data['benefit'] = $benefit;
		$this -> where($condition)->save($data);
	}
}
?>