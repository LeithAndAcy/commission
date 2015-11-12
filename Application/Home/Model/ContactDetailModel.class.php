<?php
namespace Home\Model;
use Think\Model;
class ContactDetailModel extends Model {

	public function deleteItems($arr_check_list = array()) {
		foreach ($arr_check_list as $key => $value) {
			$condition['contact_id'] = $value;
			$this -> where($condition) -> delete();
		}
	}

	public function addItem($data) {
		$this -> add($data);
	}

	//插入数据
	public function addContactDetail($all_contact_detail,$begin_date) {
		$month = date('Y-m',strtotime($begin_date));
		foreach ($all_contact_detail as $key => $value) {
			$value['date'] = $month;
			if($value['custom_fee'] == null){
				$value['custom_fee'] = 0;
			}
			$value['delivery_money'] = round($value['iNatSum'] / $value['sale_quantity'],6) * $value['delivery_quantity'];
			unset($value['iNatSum']);
			$this -> add($value);
		}

	}
	
	public function updateDeliveryQuantityAndMoney($cSOCode,$inventory_id,$data){
		$condition = array();
		$condition['cSOCode'] = $cSOCode;
		$condition['inventory_id'] = $inventory_id;
		$this -> where($condition) -> save($data);
	}
	
	public function getContactDetail($contact_main) {
		$condition = array();
		$contact_detail = array();
		foreach ($contact_main as $key => $value) {
			$condition['contact_id'] = $value['contact_id'];
			$contact_main[$key]['contact_detail'] = $this -> where($condition) -> select();
		}
		$i = 0;
		foreach ($contact_main as $key => $value) {
			foreach ($value['contact_detail'] as $kk => $vv) {
				$contact_detail[$i] = $vv;
				$contact_detail[$i]['customer_id'] = $value['customer_id'];
				// $contact_detail[$i]['customer_name'] = $value['customer_name'];
				$contact_detail[$i]['salesman_id'] = $value['salesman_id'];
				// $contact_detail[$i]['salesman_name'] = $value['salesman_name'];
				$contact_detail[$i]['cSOCode'] = $value['cSOCode'];
				$contact_detail[$i]['normal_business_ratio'] *= 100;
				$contact_detail[$i]['normal_profit_ratio'] *= 100;
				$contact_detail[$i]['special_business_ratio'] *= 100;
				$contact_detail[$i]['business_adjust'] *= 100;
				$contact_detail[$i]['profit_adjust'] *= 100;
				$contact_detail[$i]['sale_expense_ratio'] *= 100;
				$contact_detail[$i]['special_approve_float_price_ratio'] *= 100;
				$i++;
			}
		}
		return $contact_detail;
	}

	public function updateSettlingRatio($arr_ratio = array()) {
		$condition = array();
		$data = array();
		foreach ($arr_ratio as $key => $value) {
			$condition['contact_id'] = $value['contact_id'];
			$condition['inventory_id'] = $value['inventory_id'];
			$data['normal_business_ratio'] = $value['normal_business_ratio'];
			$data['normal_profit_ratio'] = $value['normal_profit_ratio'] * 0.01;
			$data['special_business_ratio'] = $value['special_business_ratio'];
			$data['float_price'] = $value['float_price'];
			$data['end_cost_price'] = $value['end_cost_price'];
			$data['sale_expense'] = $value['sale_expense'];
			$data['sale_expense_ratio'] = $value['sale_expense_ratio'];
			$data['end_sale_expense'] = $value['end_sale_expense'];
			
			$data['special_approve_float_price_ratio'] = $value['special_approve_float_price_ratio'];
			$data['special_approve_float_price'] = $value['special_approve_float_price'];
			$data['custom_fee_float_price'] = $value['custom_fee_float_price'];
			
			$data['normal_business'] = $value['normal_business'];
			$data['special_business'] = $value['special_business'];
			$data['normal_profit'] = $value['normal_profit'];
			// $data['special_profit'] = $value['special_profit'];
			$data['total_business_profit'] = $data['normal_business'] + $data['normal_profit'] + $data['special_business'];
			$this -> where($condition) -> save($data);
		}
	}

	public function updateSettlementRatio($arr_ratio = array()) {
		foreach ($arr_ratio as $key => $value) {
			$condition = array();
			$data = array();
			$condition['contact_id'] = $value['contact_id'];
			$condition['inventory_id'] = $value['inventory_id'];
			$data['normal_business_ratio'] = $value['normal_business_ratio'];
			$data['normal_profit_ratio'] = $value['normal_profit_ratio'] * 0.01;
			$data['float_price'] = $value['float_price'];
			$data['end_cost_price'] = $value['end_cost_price'];
			$data['sale_expense'] = $value['sale_expense'];
			$data['sale_expense_ratio'] = $value['sale_expense_ratio'];
			$data['special_approve_float_price_ratio'] = $value['special_approve_float_price_ratio'];
			$data['special_approve_float_price'] = $value['special_approve_float_price'];
			$data['custom_fee_float_price'] = $value['custom_fee_float_price'];
			$this -> where($condition) -> save($data);
			unset($condition);unset($data);
		}
	}

	public function getContactTotalMoney($contact_id) {
		$condition = array();
		$condition['contact_id'] = $contact_id;
		$total_money = $this -> where($condition) -> sum('delivery_money');
		return $total_money;
	}

	public function getContactTotalCostPrice($contact_id) {
		$condition = array();
		$condition['contact_id'] = $contact_id;
		$res = $this -> where($condition) -> select();
		foreach ($res as $key => $value) {
			$total_money += ($value['cost_price'] * $value['delivery_quantity']);
		}

		return $total_money;
	}

	public function updateAdjust($contact_id, $inventory_id, $business_adjust, $profit_adjust, $cost_price_adjust) {
		$condition = array();
		$condition['contact_id'] = $contact_id;
		$condition['inventory_id'] = $inventory_id;
		$cost_price = $this -> where($condition) -> getField('cost_price');
		$float_price = $this -> where($condition) -> getField('float_price');
		$data['business_adjust'] = $business_adjust * 0.01;
		$data['profit_adjust'] = $profit_adjust * 0.01;
		$data['cost_price_adjust'] = $cost_price_adjust;
		$data['end_cost_price'] = $data['cost_price_adjust'] + $cost_price + $float_price;
		$data['operator'] = $_SESSION['user_name'];
		$this -> where($condition) -> save($data);
	}

	public function checkContactSettable($contact_id) {
		//判断一个合同发货数量够不够结算，要用到表commission_length_limit
		//normal_business_ratio
		$condition = array();
		$condition['contact_id'] = $contact_id;
		$arr_contacat = $this -> where($condition) -> getField('id,sale_quantity,delivery_quantity,normal_business_ratio,cost_price');
		$flag = 1;
		foreach ($arr_contacat as $key => $value) {
			$delivery_rate = $value['delivery_quantity'] / $value['sale_quantity'];
			$sale_quantity = $value['sale_quantity'];
			$temp = $this -> query("select limit from commission_length_limit where '$sale_quantity' >= low_length AND '$sale_quantity'<high_length");
			if($temp == null){
				return 0;
			}
			if ($delivery_rate < $temp[0]['limit'] || ($value['normal_business_ratio'] == 0 && $value['cost_price'] !=0)) {
				$flag = 0;
				break;
			}
		}
		return $flag;
	}

	public function getBusinessAndProfit($contact_main) {
		$total_money = 0;
		foreach ($contact_main as $key => $value) {
			$condition = array();
			$condition['contact_id'] = $value['contact_id'];
			$total_money += $this -> where($condition) -> sum('total_business_profit');
		}
		return $total_money;
	}

	public function searchByCondition($condition) {
		if($condition == null){
			return null;
		}
		$res = $this -> where($condition) -> select();
		foreach ($res as $key => $value) {
			$res[$key]['normal_business_ratio'] *= 100;
			$res[$key]['special_business_ratio'] *= 100;
			$res[$key]['normal_profit_ratio'] *= 100;
			$res[$key]['business_adjust'] *= 100;
			$res[$key]['profit_adjust'] *= 100;
		}
		return $res;
	}
	
	public function searchCountByDate($search_begin_date,$search_end_date){
		// $res = $this -> query("
			// select * from commission_contact_detail where date >='$search_begin_date' AND date <= '$search_end_date'
		// ");
		$res = $this -> where("date between '$search_begin_date' and '$search_end_date'") -> count();
		
		return $res;
	}
	
	public function searchByDate($search_begin_date,$search_end_date,$Page){
		$res = $this -> where("date between '$search_begin_date' and '$search_end_date'")->limit($Page->firstRow.','.$Page->listRows) -> select();
		// $res = $this ->limit($Page->firstRow.','.$Page->listRows) -> select();
		return $res;
	}
	
	public function deleteItem($contact_id, $inventory_id) {
		$condition = array();
		$condition['contact_id'] = $contact_id;
		$condition['inventory_id'] = $inventory_id;
		$this -> where($condition) -> delete();
	}

	public function getTotalBusinessAndProfit($arr_contact_id) {
		$saleman_bogus = array();
		foreach ($arr_contact_id as $key => $value) {
			foreach ($value as $kk => $vv) {
				$condition = array();
				$condition['contact_id'] = $vv;
				$res = $this -> where($condition) -> sum('total_business_profit');
				$saleman_bogus[$key] += $res;
			}
		}
		return $saleman_bogus;
	}
	public function getTotalEndSaleExpense($month){
		$arr_end_sale_expense = array();
		$condition = array();
		$condition['date'] = $month;
		$res = $this -> where($condition) -> field('salesman_id,end_sale_expense')->select();
		foreach ($res as $key => $value) {
			$arr_end_sale_expense[$value['salesman_id']] += $value['end_sale_expense'];
		}
		return $arr_end_sale_expense;
	}
}
?>