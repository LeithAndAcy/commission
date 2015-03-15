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
	
	public function addItem($data){
		$this -> add($data);
	}
	
	//插入数据
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
				$contact_detail[$i]['cSOCode'] = $value['cSOCode'];
				$contact_detail[$i]['normal_business_ratio'] *= 100;
				$contact_detail[$i]['normal_profit_ratio'] *= 100;
				$contact_detail[$i]['special_business_ratio'] *= 100;
				$contact_detail[$i]['special_profit_ratio'] *= 100;
				$contact_detail[$i]['business_adjust'] *= 100;
				$contact_detail[$i]['profit_adjust'] *= 100;
				$i++;
			}
		}
		return $contact_detail;
	}
	public function updateSettlingRatio($arr_ratio=array()){
		$condition = array();
		$data = array();
		foreach ($arr_ratio as $key => $value) {
			$condition['contact_id'] = $value['contact_id'];
			$condition['inventory_id'] = $value['inventory_id'];
			$data['normal_business_ratio'] = $value['normal_business_ratio']*0.01;
			$data['normal_profit_ratio'] = $value['normal_profit_ratio']*0.01;
			$data['float_price'] = $value['float_price'];
			$data['end_cost_price'] = $value['end_cost_price'];
			$data['special_business_ratio'] = $value['special_business_ratio'];
			$data['special_profit_ratio'] = $value['special_profit_ratio'];
			$data['normal_business'] = $value['normal_business'] *0.01;
			$data['special_business'] = $value['special_business'] ;
			$data['normal_profit'] = $value['normal_profit'] *0.01;
			$data['special_profit'] = $value['special_profit'];
			$data['total_business_profit'] = $data['normal_business'] + $data['normal_profit'] + $data['special_business'] + $data['special_profit'];
			$this -> where($condition) -> save($data);
		}
	}
	public function updateSettlementRatio($arr_ratio=array()){
		$condition = array();
		$data = array();
		foreach ($arr_ratio as $key => $value) {
			$condition['contact_id'] = $value['contact_id'];
			$condition['inventory_id'] = $value['inventory_id'];
			$data['normal_business_ratio'] = $value['normal_business_ratio']*0.01;
			$data['normal_profit_ratio'] = $value['normal_profit_ratio']*0.01;
			$data['float_price'] = $value['float_price'];
			$data['end_cost_price'] = $value['end_cost_price'];
			$this -> where($condition) -> save($data);
		}
	}
	

	public function getContactTotalMoney($contact_id){
		$condition = array();
		$condition['contact_id'] = $contact_id;
		$total_money = $this -> where($condition) -> sum('delivery_money');
		return $total_money;
	}
	public function updateAdjust($contact_id,$inventory_id,$business_adjust,$profit_adjust,$cost_price_adjust){
		$condition = array();
		$condition['contact_id'] = $contact_id;
		$condition['inventory_id'] = $inventory_id;
		$cost_price = $this -> where($condition) -> getField('cost_price');
		$float_price = $this -> where($condition) -> getField('float_price');
		$data['business_adjust'] = $business_adjust * 0.01;
		$data['profit_adjust'] = $profit_adjust * 0.01;
		$data['cost_price_adjust'] = $cost_price_adjust;
		$data['end_cost_price'] = $data['cost_price_adjust']+ $cost_price + $float_price;
		$data['operator'] = $_SESSION['user_name'];
		$this -> where($condition)->save($data);
 	}
	
	public function checkContactSettable($contact_id){
		//判断一个合同发货数量够不够结算，要用到表commission_length_limit
		$condition = array();
		$condition['contact_id'] = $contact_id;
		$arr_contacat = $this -> where($condition) -> getField('id,sale_quantity,delivery_quantity');
		$flag = 1;
		foreach ($arr_contacat as $key => $value) {
			$delivery_rate = $value['delivery_quantity'] / $value['sale_quantity'];
			$sale_quantity = $value['sale_quantity'];
			$temp = $this -> query("select limit from commission_length_limit where '$sale_quantity' >= low_length AND '$sale_quantity'<high_length");
			if($delivery_rate < $temp[0]['limit']){
				$flag = 0;
				break;
			}
		}
		return $flag;
	}
	public function getBusinessAndProfit($contact_main){
		$result = array();
		foreach ($contact_main as $key => $value) {
			$condition = array();
			$condition['contact_id'] = $value['contact_id'];
			$res = $this -> where($condition)-> find();
			$result['special_business'] += $res['special_business'];
			$result['special_profit'] += $res['special_profit'];
			$result['business_profit'] += $this -> where($condition)->sum('total_business_profit');
		}
		$total_money = $result['special_business'] + $result['special_profit'] + $result['business_profit'];
		return $total_money;
	}
	public function searchByCondition($condition){
		$res = $this -> where($condition) -> select();
		return $res;
	}
}
?>