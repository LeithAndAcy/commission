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
}
?>