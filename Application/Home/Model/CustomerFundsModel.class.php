<?php
namespace Home\Model;
use Think\Model;
class CustomerFundsModel extends Model {
	public function updateItems($customer_funds=array()){
		$condition = array();
		foreach ($customer_funds as $key => $value) {
			$condition['customer_id'] = $key;
			//$condition['customer_id'] = $value['customer_id'];
			// $condition['salesman_id'] = $value['salesman_id'];
			$res = $this -> where($condition) -> find();
			if($res == null){
				$data['customer_id'] = $key;
				$data['funds'] = $value;
				$this -> add($data);
			}else{
				$this -> where($condition)->setInc('funds',$value);
			}
		}
	}
	public function getTotalCustomerFunds(){
		$res = $this -> select();
		foreach ($res as $key => $value) {
			$res[$key]['total_funds'] = $value['funds'] + $value['benefit'];
			if($value['funds'] == 0){
				if($value['this_month_funds'] == 0){
					$condition = array();
					$data = array();
					$condition['customer_id'] = $value['customer_id'];
					$data['last_month_benefit'] = $value['benefit'];
					$this -> where($condition) -> save($data);
				}
			}else{
				$condition = array();
				$data = array();
				$condition['customer_id'] = $value['customer_id'];
				$data['this_month_funds'] = $value['funds'];
				$data['last_month_benefit'] = $value['benefit'];
				$this -> where($condition) -> save($data);
			}
		}
		return $res;
	}
	public function setCustomerFunds($customer_id,$benefit){
		$condition = array();
		$condition['customer_id'] = $customer_id;
		// $condition['salesman_id'] = $salesman_id;
		$data['funds'] = 0;
		$data['benefit'] = $benefit;
		$this -> where($condition)->save($data);
	}
	public function subtractCustomerBenefit($customer_id,$benefit){
		//减去手动结算合同的扣款
		$condition = array();
		$condition['customer_id'] = $customer_id;
		// $condition['salesman_id'] = $salesman_id;
		$data = $this -> where($condition) -> find();
		if($data == null){
			$data['customer_id'] = $customer_id;
			$data['benefit'] = $benefit*-1;
			$this -> add($data);
		}else{
			$last_benefit = $data['benefit'] - $benefit;
			$this -> where($condition)->setField('benefit',$last_benefit);
		}
		
	}
	public function addSomeCustomer($customer_id){
		$condition = array();
		$condition['customer_id'] = $customer_id;
		$res = $this -> where($condition) -> find();
		if($res == null){
			$data = array();
			$data['customer_id'] = $customer_id;
			$this -> add($data);
		}
	}
	public function updateThisMonthSettledMoney($customer_id,$money){
		$condition = array();
		$condition['customer_id'] = $customer_id;
		$this -> where($condition)->setInc('this_month_settled_money',$money);
	}
	public function clearThisMonthSettledMoney(){
		$this -> query('update commission_customer_funds set this_month_settled_money =0');
		$this -> query('update commission_customer_funds set this_month_funds_back =0');
		$this -> query('update commission_customer_funds set this_month_funds =0');
	}
	public function setThisMonthFundsBack($condition,$this_month_funds_back){
		$this -> where($condition) -> setInc('this_month_funds_back',$this_month_funds_back);
	}
}
?>