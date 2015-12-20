<?php
namespace Home\Model;
use Think\Model;
class SettledHistoryModel extends Model {
	
	
	public function addItem($customer_id,$this_month_settled_money,$this_month_funds_back,$this_month_funds,$last_month_benefit,$benefit,$customer_name,$date){
		$data = array();
		$conidtion = array();
		$conidtion['customer_id'] = $customer_id;
		$conidtion['date'] = date('Y-m',time());
		if($this -> where($conidtion)->find()){
			$this -> where($conidtion) -> setField('benefit',$benefit);
			$this -> where($conidtion) -> setInc('this_month_settled_money',$this_month_settled_money);
			$this -> where($conidtion) -> setInc('this_month_funds_back',$this_month_funds_back);
		}else{
			$data['customer_id'] = $customer_id;
			$data['customer_name'] = $customer_name;
			$data['this_month_settled_money'] =  $this_month_settled_money;
			$data['this_month_funds_back'] =  $this_month_funds_back;
			$data['this_month_funds'] =  $this_month_funds;
			$data['last_month_benefit'] =  $last_month_benefit;
			$data['benefit'] = $benefit;
			$data['date'] = $date;
			$this -> add($data);
		}
		
	}
	
	public function searchByCondition($condition){
		$res = $this -> where($condition) -> select();
		return $res;
	}

}
?>