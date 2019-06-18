<?php
namespace Home\Controller;
use Think\Controller;
class BusinessPercentController extends Controller {

	private $db_constomer_funds;
	private $db_contact_main;
	private $db_contact_detail;
	private $db_customer;
	private $db_salesman;
	private $db_coustomer_funds;
	private $db_normal_business_ratio;
	private $db_special_business_ratio;
	private $db_special_approve_price_float_ratio;
	private $db_normial_profit_ratio;
	private $db_normial_profit_discount_ratio;
	private $db_special_profit_ratio;
	private $db_price_float_ratio;
	private $db_area_price_float_ratio;
	private $db_wage_deduction;
	private $db_insurance_fund;
	private $db_tax;
	private $db_salary;
	private $db_U8;
	private $db_funds_back;
	private $db_fee_ratio;
	private $db_sale_expense;
	private $db_load_history;
	private $db_settled_history;
	private $db_salesman_funds;
	function _initialize() {
		if (!_checkLogin()) {
			$this -> error('登陆超时,请重新登陆。', '/commission', 2);
			exit ;
		}
		$this -> db_constomer_funds = D("CustomerFunds");
		$this -> db_contact_main = D("ContactMain");
		$this -> db_contact_detail = D("ContactDetail");
		$this -> db_customer = D("Customer");
		$this -> db_coustomer_funds = D("CustomerFunds");
		$this -> db_salesman = D("Salesman");
		$this -> db_normal_business_ratio = D("NormalBusinessRatio");
		$this -> db_special_business_ratio = D("SpecialBusinessRatio");
		$this -> db_special_approve_price_float_ratio = D("SpecialApprovePriceFloatRatio");
		$this -> db_normial_profit_ratio = D("NormalProfitRatio");
		$this -> db_normial_profit_discount_ratio = D("NormalProfitDiscountRatio");
		$this -> db_special_profit_ratio = D("SpecialProfitRatio");
		$this -> db_price_float_ratio = D("PriceFloatRatio");
		$this -> db_area_price_float_ratio = D("AreaPriceFloatRatio");
		$this -> db_wage_deduction = D("WageDeduction");
		$this -> db_tax_ratio = D("TaxRatio");
		$this -> db_insurance_fund = D("InsuranceFund");
		$this -> db_salary = D("Salary");
		$this -> db_funds_back = D("FundsBack");
		$this -> db_fee_ratio = D("FeeRatio");
		$this -> db_sale_expense = D("SaleExpense");
		$this -> db_load_history = D("LoadHistory");
		$this -> db_settled_history = D("SettledHistory");
		$this -> db_salesman_funds = D("SalesmanFunds");
	}

	public function loadBusinessPercentPage() {
		$this -> display('BusinessPercentPage');

	}

	public function loadSettlingContactPage() {
		$this -> db_U8 = D("U8");
		$count_settling_contact = $this -> db_contact_main -> countSettlingContact();
		$Page = new \Think\Page($count_settling_contact, 100);
		$show = $Page -> show();
		// 分页显示输出
		$count_settling_contact_detail = $this -> db_contact_main -> countSettlingContactDetail();

		$this -> assign('count_settling_contact', $count_settling_contact);
		$this -> assign('count_settling_contact_detail', $count_settling_contact_detail);
		$settling_contact = $this -> db_contact_main -> getSettlingContact($Page);
		// $settling_contact = $this -> db_customer -> addCustomerName($settling_contact);
		// $settling_contact = $this ->db_salesman -> addSalesmanName($settling_contact);
		$settling_contact_detail = $this -> db_contact_detail -> getContactDetail($settling_contact);
		$this -> assign("settling_contact_detail", $settling_contact_detail);
		$this -> assign("page", $show);
		$this -> display('SettlingContactPage');
	}

	public function getSettlingContact() {
		//由于回笼资金表的使用！
		$all_funds_back = $this -> db_funds_back -> getAllItems();
		$condition = array();
		foreach ($all_funds_back as $key => $value) {
			$this -> db_constomer_funds -> addSomeCustomer($value['customer_id']);
		}
		//判断哪些合同可结算    先判断回款，再判断发货数量
		//  add condition:  normal_business_ratio > 0
		//  add condition:  normal_business_ratio > 0   &&  cost_price=0    不可取到可计算合同中
		$total_customer_funds = $this -> db_constomer_funds -> getTotalCustomerFunds();
		foreach ($total_customer_funds as $key => $value) {
			$condition = array();
			$condition['customer_id'] = $value['customer_id'];
			$temp = $this -> db_funds_back -> getFunds($condition);
			$total_customer_funds[$key]['total_funds'] += $temp;
			$this -> db_constomer_funds -> setThisMonthFundsBack($condition, $temp);
			$this -> db_funds_back -> where($condition) -> delete();
		}
		$condition = array();
		$contact_main = array();
		foreach ($total_customer_funds as $key => $value) {
			$condition['customer_id'] = $value['customer_id'];
			$condition['settling'] = 0;
			$condition['settled'] = 0;
			$contact_main[$key] = $this -> db_contact_main -> getContactByCondition($condition);
			$delete = array();
			foreach ($contact_main[$key] as $kk => $vv) {
				$temp_contact_id = $vv['contact_id'];
				foreach ($contact_main[$key] as $kkk => $vvv) {
					if ($kk == $kkk) {
						continue;
					} elseif (in_array($kk, $delete)) {
						continue;
					} else {
						if ($temp_contact_id == $vvv['contact_id']) {
							unset($contact_main[$key][$kkk]);
							array_push($delete, $kkk);
						}
					}
				}
			}
			$contact_main[$key]['total_funds'] = $value['total_funds'];
		}
		foreach ($contact_main as $key => $value) {
			foreach ($value as $kk => $vv) {
				if ($kk === "total_funds") {
					$this -> db_constomer_funds -> setCustomerFunds($total_customer_funds[$key]['customer_id'], $contact_main[$key]['total_funds']);
					break;
				}
				$contact_total_money = $this -> db_contact_detail -> getContactTotalMoney($vv['contact_id']);
				// if($contact_total_money > $contact_main[$key]['total_funds']){
				if (bccomp($contact_total_money, $contact_main[$key]['total_funds'], 4) > 0) {
					//continue;
                    $this -> db_constomer_funds -> setCustomerFunds($total_customer_funds[$key]['customer_id'], $contact_main[$key]['total_funds']);
                    break;
				} else {
					//检测发货数量  and normal business ratio >0
					if ($this -> db_contact_detail -> checkContactSettable($vv['contact_id'])) {
						// if(1){
						$this -> db_contact_main -> setSettlingContact($vv['contact_id']);
						//更新本月结算金额
						$this -> db_coustomer_funds -> updateThisMonthSettledMoney($vv['customer_id'], $contact_total_money);

						$contact_main[$key]['total_funds'] = $contact_main[$key]['total_funds'] - $contact_total_money;
						$temp_customer_id = $vv['customer_id'];
						$temp_salesman_id = $vv['salesman_id'];
					} else {

					}
				}
			}
		}
		$this -> loadSettlingContactPage();
	}

	public function ratioAdjust() {
		$contact_id = $_POST['edit_contact_id'];
		$inventory_id = $_POST['edit_inventory_id'];
		$business_adjust = $_POST['edit_business_adjust'];
		$profit_adjust = $_POST['edit_profit_adjust'];
		$cost_price_adjust = $_POST['edit_cost_price_adjust'];
		$this -> db_contact_detail -> updateAdjust($contact_id, $inventory_id, $business_adjust, $profit_adjust, $cost_price_adjust);
		$this -> db_contact_main -> setContactEdited($contact_id);
		$this -> loadSettlingContactPage();
	}

	public function caculateSpecialBusiness() {
		$contact_detail = $this -> db_contact_main -> getSettlingContactDetail();
		$special_business_ratio = $this -> db_special_business_ratio -> getAllHandledSpecialBusinessRatio();
		$all_salesman_funds = $this -> db_salesman_funds -> getAllHandledSalesmanFunds();
        $all_salesman_delivery_money = $this -> db_contact_main -> getSettlingContactTotalDeliveryMoney();
		$arr_ratio = array();
		foreach ($contact_detail as $key => $value) {
			$salesman_id = $value['salesman_id'];
			$arr_ratio[$key]['contact_id'] = $value['contact_id'];
			$arr_ratio[$key]['inventory_id'] = $value['inventory_id'];
			$temp_inventory_id = substr($value['inventory_id'], 0, 1);
			$salesman_funds = $all_salesman_funds[$salesman_id];
			$temp_fee_ratio = $this -> db_fee_ratio -> getFeeRatio($salesman_id);
			$temp_special_business_ratio = null;
            $temp_total_funds = $all_salesman_delivery_money[$salesman_id];
			//没有匹配的就默认为null
			foreach ($special_business_ratio as $kkkkk => $vvvvv) {
				if ($vvvvv['salesman_id'] == $salesman_id && $temp_total_funds >= $vvvvv['low_limit'] && $temp_total_funds < $vvvvv['high_limit']) {
					if (substr($value['inventory_id'], 0, 1) == "F" || substr($value['inventory_id'], 0, 1) == "K") {
						if (substr($value['inventory_id'], 0, 6) == $vvvvv['inventory_id']) {
							$temp_special_business_ratio = $vvvvv['ratio'];
							break;
						}
					} elseif (substr($value['inventory_id'], 0, 1) == "X") {
						if (substr($value['inventory_id'], 0, 2) == "XG" || substr($value['inventory_id'], 0, 2) == "XH" || substr($value['inventory_id'], 0, 2) == "XI" || substr($value['inventory_id'], 0, 2) == "XJ" || substr($value['inventory_id'], 0, 2) == "XA" || substr($value['inventory_id'], 0, 2) == "XE") {
							if (substr($value['inventory_id'], 0, 2) == $vvvvv['inventory_id']) {
								$temp_special_business_ratio = $vvvvv['ratio'];
								break;
							}
						} elseif (substr($value['inventory_id'], 0, 2) == "XK"  || substr($value['inventory_id'], 0, 2) == "XF") {
							if (substr($value['inventory_id'], 0, 7) == $vvvvv['inventory_id']) {
								$temp_special_business_ratio = $vvvvv['ratio'];
								break;
							}
						} else {
							if (substr($value['inventory_id'], 0, 1) == $vvvvv['inventory_id']) {
								$temp_special_business_ratio = $vvvvv['ratio'];
								break;
							}
						}
					} else {
						if (substr($value['inventory_id'], 0, 1) == $vvvvv['inventory_id']) {
							$temp_special_business_ratio = $vvvvv['ratio'];
							break;
						}
					}
				}
			}
			//匹配不到  取其他
			if ($temp_special_business_ratio === null) {
				foreach ($special_business_ratio as $kkkkk => $vvvvv) {
					if ($vvvvv['salesman_id'] == $salesman_id && ('其他' == $vvvvv['inventory_id']) && $temp_total_funds >= $vvvvv['low_limit'] && $temp_total_funds < $vvvvv['high_limit']) {
						$temp_special_business_ratio = $vvvvv['ratio'];
						break;
					}
				}
			}
			$arr_ratio[$key]['special_business_ratio'] = $temp_special_business_ratio;
			if ($contact_detail[$key]['end_cost_price'] > $contact_detail[$key]['sale_price']) {
				$arr_ratio[$key]['special_business'] = $contact_detail[$key]['delivery_quantity'] * $contact_detail[$key]['sale_price'] * $arr_ratio[$key]['special_business_ratio'] * $temp_fee_ratio;
			} else {
				$arr_ratio[$key]['special_business'] = $contact_detail[$key]['delivery_quantity'] * $contact_detail[$key]['end_cost_price'] * $arr_ratio[$key]['special_business_ratio'] * $temp_fee_ratio;
			}
			$arr_ratio[$key]['total_business_profit'] = $arr_ratio[$key]['special_business'] + $contact_detail[$key]['normal_business'] + +$contact_detail[$key]['normal_profit'];
		}
		$this -> db_contact_detail -> updateSpeicalBusiness($arr_ratio);
	}

	public function getSettlingRatioAndPrice() {
		//取基本业绩提成比例以及基本利润提成比例
		$this -> db_U8 = D("U8");
		// PLZ 不能删除
		// $contact_main = $this -> db_contact_main -> getSettlingContact();
		// $contact_detail = $this -> db_contact_detail ->getContactDetail($contact_main);
		$contact_detail = $this -> db_contact_main -> getSettlingContactDetail();
		$normal_business_ratio = $this -> db_normal_business_ratio -> getAllHandledNormalBusinessRatio();
		$normal_profit_ratio = $this -> db_normial_profit_ratio -> getAllNormalProfitRatio();
		$all_normal_profit_discount_ratio = $this -> db_normial_profit_discount_ratio -> getAllHandledNormalProfitDiscountRatio();
		$price_float_ratio = $this -> db_price_float_ratio -> getAllPriceFloatRatio();
		$temp_fee_ratio = $this -> db_fee_ratio -> getFeeRatio("");
		$special_business_ratio = $this -> db_special_business_ratio -> getAllHandledSpecialBusinessRatio();
		$all_sale_expense = $this -> db_sale_expense -> getAllHandledSaleExpense();
		$all_special_approve_float_price_ratio = $this -> db_special_approve_price_float_ratio -> getAllHandledSpecialApprovePriceFloatRatio();
		//取所有员工的发货金额综合
		$all_salesman_delivery_money = $this -> db_contact_main -> getSettlingContactTotalDeliveryMoney();
		$arr_ratio = array();
		foreach ($contact_detail as $key => $value) {
			$salesman_id = $value['salesman_id'];
			$customer_id = $value['customer_id'];
			//取存货类别
			$arr_ratio[$key]['salesman_id'] = $value['salesman_id'];
			$arr_ratio[$key]['contact_id'] = $value['contact_id'];
			$temp_inventory_id = substr($value['inventory_id'], 0, 1);
			$temp_normal_profit_discount_ratio = $all_normal_profit_discount_ratio[$salesman_id][substr($value['contact_id'], 0, 4)];
			if ($temp_normal_profit_discount_ratio == null) {
				$temp_normal_profit_discount_ratio = 0;
			}
			$arr_ratio[$key]['normal_profit_discount_ratio'] = $temp_normal_profit_discount_ratio;
			if ($temp_inventory_id == 'F') {
				$arr_ratio[$key]['normal_business_ratio'] = $normal_business_ratio[$salesman_id][substr($value['inventory_id'], 0, 6)];
			} else if ($temp_inventory_id == 'K') {
				$arr_ratio[$key]['normal_business_ratio'] = $normal_business_ratio[$salesman_id][substr($value['inventory_id'], 0, 6)];
			} else if ($temp_inventory_id == 'X') {
				if (substr($value['inventory_id'], 0, 2) == 'XG' || substr($value['inventory_id'], 0, 2) == 'XH' || substr($value['inventory_id'], 0, 2) == 'XI' || substr($value['inventory_id'], 0, 2) == 'XJ' || substr($value['inventory_id'], 0, 2) == 'XA' || substr($value['inventory_id'], 0, 2) == 'XE') {
					$arr_ratio[$key]['normal_business_ratio'] = $normal_business_ratio[$salesman_id][substr($value['inventory_id'], 0, 2)];
				} elseif (substr($value['inventory_id'], 0, 2) == 'XK' || substr($value['inventory_id'], 0, 2) == 'XF') {
					$arr_ratio[$key]['normal_business_ratio'] = $normal_business_ratio[$salesman_id][substr($value['inventory_id'], 0, 7)];
				} else {
					$arr_ratio[$key]['normal_business_ratio'] = $normal_business_ratio[$salesman_id][substr($value['inventory_id'], 0, 1)];
					if ($arr_ratio[$key]['normal_business_ratio'] == null) {
						$arr_ratio[$key]['normal_business_ratio'] = $normal_business_ratio[$salesman_id]['其他'];
					}
				}
			} else {
				$arr_ratio[$key]['normal_business_ratio'] = $normal_business_ratio[$salesman_id][$temp_inventory_id];
			}
			if ($arr_ratio[$key]['normal_business_ratio'] == null) {
				$arr_ratio[$key]['normal_business_ratio'] = $normal_business_ratio[$salesman_id]['其他'];
			}
			$arr_ratio[$key]['inventory_id'] = $value['inventory_id'];
			foreach ($normal_profit_ratio as $kkk => $vvv) {
				if ($value['salesman_id'] == $vvv['salesman_id']) {
					$arr_ratio[$key]['normal_profit_ratio'] = $vvv['ratio'];
					break;
				}
			}
			//计算上浮底价   以及最终实际底价
			//使用left join直接查出来地区浮动比例
			$area_price_float_ratio = $value['ratio'];

			//计算总经理上浮底价和技术上浮底价
			$arr_ratio[$key]['gm_price'] = $value['gm_ratio'] * $value['cost_price'];
			$arr_ratio[$key]['skill_price'] = $value['skill_ratio'] * $value['cost_price'];

			//计算特批上浮底价
			$arr_ratio[$key]['special_approve_float_price_ratio'] = $all_special_approve_float_price_ratio[$customer_id][$temp_inventory_id];
			if ($arr_ratio[$key]['special_approve_float_price_ratio'] === null) {
				$arr_ratio[$key]['special_approve_float_price_ratio'] = $all_special_approve_float_price_ratio[$customer_id]['其他'];
			}
			$arr_ratio[$key]['special_approve_float_price'] = $arr_ratio[$key]['special_approve_float_price_ratio'] * $contact_detail[$key]['cost_price'];

			//定制费
			// print_r(round(30/605,6)) ;
			$arr_ratio[$key]['custom_fee_float_price'] = round($contact_detail[$key]['custom_fee'] / $contact_detail[$key]['sale_quantity'], 6);

			foreach ($price_float_ratio as $kkkk => $vvvv) {
				if ($vvvv['classification_id'] == $contact_detail[$key]['classification_id'] && $vvvv['low_price'] <= $contact_detail[$key]['cost_price'] && $vvvv['high_price'] > $contact_detail[$key]['cost_price'] && $vvvv['low_length'] <= $contact_detail[$key]['sale_quantity'] && $vvvv['high_length'] > $contact_detail[$key]['sale_quantity']) {
					if($value['inStore'] == '现货' ||$value['inStore'] == '外购' || $value['inStore'] == '现货-备货' || $value['inStore'] == '现货-裁线' || $value['inStore'] == '线束' || $value['inStore'] == '代卖'){
						$vvvv['ratio'] = 0;
					}	
					$arr_ratio[$key]['float_price'] = ($vvvv['ratio'] * 0.01 + $area_price_float_ratio) * $contact_detail[$key]['cost_price'];
					$arr_ratio[$key]['end_cost_price'] = $arr_ratio[$key]['float_price'] + $contact_detail[$key]['cost_price'] + $contact_detail[$key]['cost_price_adjust'] + $arr_ratio[$key]['special_approve_float_price'] + $arr_ratio[$key]['custom_fee_float_price'] + $arr_ratio[$key]['gm_price'] + $arr_ratio[$key]['skill_price'];
					$arr_ratio[$key]['float_price_ratio'] = $vvvv['ratio'] * 0.01;
					break;
				} else {
					$arr_ratio[$key]['float_price'] = $area_price_float_ratio * $contact_detail[$key]['cost_price'];
					$arr_ratio[$key]['end_cost_price'] = $contact_detail[$key]['cost_price'] + $arr_ratio[$key]['float_price'] + $contact_detail[$key]['cost_price_adjust'] + $arr_ratio[$key]['special_approve_float_price'] + $arr_ratio[$key]['custom_fee_float_price'] + $arr_ratio[$key]['gm_price'] + $arr_ratio[$key]['skill_price'];
					$arr_ratio[$key]['float_price_ratio'] = 0;
				}
			}
			//业务员编号为S开头的合同，最终底价=本币销售单价；,业务员编号为非S开头的合同，最终底价=底价；
            if(substr($value['salesman_id'], 0, 1) == 'S' || substr($value['salesman_id'], 0, 1) == 's'){
				$arr_ratio[$key]['end_cost_price'] = $contact_detail[$key]['sale_price'];
			}else{
                $arr_ratio[$key]['end_cost_price'] = $contact_detail[$key]['cost_price'];
			}
			//计算销售费用单价以及取销售费用比例  sale_expense销售费用单价比例   sale_expense_ratio销售费用比例
			$arr_ratio[$key]['sale_expense'] = $all_sale_expense[$salesman_id][$value['contact_id']]['sale_expense'] * ($value['sale_price'] - $value['end_cost_price']);
			$arr_ratio[$key]['sale_expense_ratio'] = $all_sale_expense[$salesman_id][$value['contact_id']]['sale_expense_ratio'];
			//没配置比例表的情况
			if ($price_float_ratio == null) {
				$arr_ratio[$key]['float_price'] = $area_price_float_ratio * $contact_detail[$key]['cost_price'];
				$arr_ratio[$key]['end_cost_price'] = ($contact_detail[$key]['cost_price'] + $arr_ratio[$key]['float_price'] + $contact_detail[$key]['cost_price_adjust'] + $arr_ratio[$key]['gm_price'] + $arr_ratio[$key]['skill_price']);
				$arr_ratio[$key]['float_price_ratio'] = 0;
			}
			//取回款，计算各种比例和金额
			$temp_total_funds = $all_salesman_delivery_money[$salesman_id];

			$temp_special_business_ratio = null;
			//没有匹配的就默认为null
			//2016-12-25新需求 特殊业绩提成比例和基本业绩提成比例算法一样
			foreach ($special_business_ratio as $kkkkk => $vvvvv) {
				if ($vvvvv['salesman_id'] == $salesman_id && $temp_total_funds >= $vvvvv['low_limit'] && $temp_total_funds < $vvvvv['high_limit']) {
					if (substr($value['inventory_id'], 0, 1) == "F" || substr($value['inventory_id'], 0, 1) == "K") {
						if (substr($value['inventory_id'], 0, 6) == $vvvvv['inventory_id']) {
							$temp_special_business_ratio = $vvvvv['ratio'];
							break;
						}
					} elseif (substr($value['inventory_id'], 0, 1) == "X") {
						if (substr($value['inventory_id'], 0, 2) == "XG" || substr($value['inventory_id'], 0, 2) == "XH" || substr($value['inventory_id'], 0, 2) == "XI" || substr($value['inventory_id'], 0, 2) == "XJ" || substr($value['inventory_id'], 0, 2) == "XA" || substr($value['inventory_id'], 0, 2) == "XE") {
							if (substr($value['inventory_id'], 0, 2) == $vvvvv['inventory_id']) {
								$temp_special_business_ratio = $vvvvv['ratio'];
								break;
							}
						} elseif (substr($value['inventory_id'], 0, 2) == "XK"  || substr($value['inventory_id'], 0, 2) == "XF") {
							if (substr($value['inventory_id'], 0, 7) == $vvvvv['inventory_id']) {
								$temp_special_business_ratio = $vvvvv['ratio'];
								break;
							}
						} else {
							if (substr($value['inventory_id'], 0, 1) == $vvvvv['inventory_id']) {
								$temp_special_business_ratio = $vvvvv['ratio'];
								break;
							}
						}
					} else {
						if (substr($value['inventory_id'], 0, 1) == $vvvvv['inventory_id']) {
							$temp_special_business_ratio = $vvvvv['ratio'];
							break;
						}
					}
				}
			}
			//匹配不到  取其他
			if ($temp_special_business_ratio === null) {
				foreach ($special_business_ratio as $kkkkk => $vvvvv) {
					if ($vvvvv['salesman_id'] == $salesman_id && ('其他' == $vvvvv['inventory_id']) && $temp_total_funds >= $vvvvv['low_limit'] && $temp_total_funds < $vvvvv['high_limit']) {
						$temp_special_business_ratio = $vvvvv['ratio'];
						break;
					}
				}
			}
			$arr_ratio[$key]['special_business_ratio'] = $temp_special_business_ratio;
			unset($temp_special_business_ratio);
			if ($arr_ratio[$key]['end_cost_price'] > $contact_detail[$key]['sale_price']) {
				$arr_ratio[$key]['normal_business'] = $contact_detail[$key]['delivery_quantity'] * $contact_detail[$key]['sale_price'] * ($arr_ratio[$key]['normal_business_ratio'] + $contact_detail[$key]['business_adjust']) * $temp_fee_ratio;
				$arr_ratio[$key]['special_business'] = $contact_detail[$key]['delivery_quantity'] * $contact_detail[$key]['sale_price'] * $arr_ratio[$key]['special_business_ratio'] * $temp_fee_ratio;
			} else {
				$arr_ratio[$key]['normal_business'] = $contact_detail[$key]['delivery_quantity'] * $arr_ratio[$key]['end_cost_price'] * ($arr_ratio[$key]['normal_business_ratio'] + $contact_detail[$key]['business_adjust']) * $temp_fee_ratio;
				$arr_ratio[$key]['special_business'] = $contact_detail[$key]['delivery_quantity'] * $arr_ratio[$key]['end_cost_price'] * $arr_ratio[$key]['special_business_ratio'] * $temp_fee_ratio;
			}
            //更新条件与发货数量相关
            $arr_ratio[$key]['delivery_quantity'] = $contact_detail[$key]['delivery_quantity'];
            $temp_bPurchase = $value['purchase'];
			//销售费用= 发货米数*销售费用单价*销售费用比例；
			$arr_ratio[$key]['end_sale_expense'] = $value['delivery_quantity'] * $arr_ratio[$key]['sale_expense'] * $arr_ratio[$key]['sale_expense_ratio'];
			$temp_sale_expense = $arr_ratio[$key]['sale_expense'];
			// $arr_ratio[$key]['end_sale_expense'] = $temp_sale_expense;
			if (FALSE) {
				//	$arr_ratio[$key]['normal_profit_ratio'] = 100;
			} else {
				if ($arr_ratio[$key]['sale_expense'] > 0 && $arr_ratio[$key]['normal_profit_ratio'] > 50) {
					$arr_ratio[$key]['normal_profit_ratio'] = 50;
				}
			}
			if($arr_ratio[$key]['end_cost_price'] == 0){
				$arr_ratio[$key]['normal_profit'] =0;
				$arr_ratio[$key]['normal_profit_1'] =0;
				$arr_ratio[$key]['normal_profit_2'] =0;
			}else{
				if ($temp_sale_expense == 0 && ($contact_detail[$key]['sale_price'] - $arr_ratio[$key]['end_cost_price'] * 1.1) > 0) {
					if($arr_ratio[$key]['normal_profit_ratio'] * 0.01 + $contact_detail[$key]['profit_adjust'] - 0.1 <=0){
						$arr_ratio[$key]['normal_profit'] = $contact_detail[$key]['delivery_quantity'] * ($arr_ratio[$key]['end_cost_price'] * 0.1 * ($arr_ratio[$key]['normal_profit_ratio'] * 0.01 + $contact_detail[$key]['profit_adjust']));
						$arr_ratio[$key]['normal_profit_1'] = $contact_detail[$key]['delivery_quantity'] * ($arr_ratio[$key]['end_cost_price'] * 0.1 * ($arr_ratio[$key]['normal_profit_ratio'] * 0.01 + $contact_detail[$key]['profit_adjust'])) * $temp_fee_ratio;
						$arr_ratio[$key]['normal_profit_2'] = $contact_detail[$key]['delivery_quantity'] * ($arr_ratio[$key]['end_cost_price'] * 0.1 * ($arr_ratio[$key]['normal_profit_ratio'] * 0.01 + $contact_detail[$key]['profit_adjust'])) * $temp_fee_ratio * $temp_normal_profit_discount_ratio;
					}else{
						$arr_ratio[$key]['normal_profit'] = $contact_detail[$key]['delivery_quantity'] * (($contact_detail[$key]['sale_price'] - $arr_ratio[$key]['end_cost_price'] * 1.1) * (($arr_ratio[$key]['normal_profit_ratio'] * 0.01 + $contact_detail[$key]['profit_adjust'] - 0.1)) + $arr_ratio[$key]['end_cost_price'] * 0.1 * ($arr_ratio[$key]['normal_profit_ratio'] * 0.01 + $contact_detail[$key]['profit_adjust']));
						$arr_ratio[$key]['normal_profit_1'] = $contact_detail[$key]['delivery_quantity'] * (($contact_detail[$key]['sale_price'] - $arr_ratio[$key]['end_cost_price'] * 1.1) * (($arr_ratio[$key]['normal_profit_ratio'] * 0.01 + $contact_detail[$key]['profit_adjust'] - 0.1)) + $arr_ratio[$key]['end_cost_price'] * 0.1 * ($arr_ratio[$key]['normal_profit_ratio'] * 0.01 + $contact_detail[$key]['profit_adjust'])) * $temp_fee_ratio;
						$arr_ratio[$key]['normal_profit_2'] = $contact_detail[$key]['delivery_quantity'] * (($contact_detail[$key]['sale_price'] - $arr_ratio[$key]['end_cost_price'] * 1.1) * (($arr_ratio[$key]['normal_profit_ratio'] * 0.01 + $contact_detail[$key]['profit_adjust'] - 0.1)) + $arr_ratio[$key]['end_cost_price'] * 0.1 * ($arr_ratio[$key]['normal_profit_ratio'] * 0.01 + $contact_detail[$key]['profit_adjust'])) * $temp_fee_ratio * $temp_normal_profit_discount_ratio;
					}
				} elseif ($temp_sale_expense == 0 && ($contact_detail[$key]['sale_price'] - $arr_ratio[$key]['end_cost_price'] * 1.1) <= 0) {
					$arr_ratio[$key]['normal_profit'] = $contact_detail[$key]['delivery_quantity'] * ($contact_detail[$key]['sale_price'] - $arr_ratio[$key]['end_cost_price']) * ($arr_ratio[$key]['normal_profit_ratio'] * 0.01 + $contact_detail[$key]['profit_adjust']);
					$arr_ratio[$key]['normal_profit_1'] = $contact_detail[$key]['delivery_quantity'] * ($contact_detail[$key]['sale_price'] - $arr_ratio[$key]['end_cost_price']) * ($arr_ratio[$key]['normal_profit_ratio'] * 0.01 + $contact_detail[$key]['profit_adjust']) * $temp_fee_ratio;
					$arr_ratio[$key]['normal_profit_2'] = $contact_detail[$key]['delivery_quantity'] * ($contact_detail[$key]['sale_price'] - $arr_ratio[$key]['end_cost_price']) * ($arr_ratio[$key]['normal_profit_ratio'] * 0.01 + $contact_detail[$key]['profit_adjust']) * $temp_fee_ratio * $temp_normal_profit_discount_ratio;
				} else {
					$arr_ratio[$key]['normal_profit'] = $contact_detail[$key]['delivery_quantity'] * ($contact_detail[$key]['sale_price'] - $arr_ratio[$key]['end_cost_price'] - $temp_sale_expense) * ($arr_ratio[$key]['normal_profit_ratio'] * 0.01 + $contact_detail[$key]['profit_adjust']);
					$arr_ratio[$key]['normal_profit_1'] = $contact_detail[$key]['delivery_quantity'] * ($contact_detail[$key]['sale_price'] - $arr_ratio[$key]['end_cost_price'] - $temp_sale_expense) * ($arr_ratio[$key]['normal_profit_ratio'] * 0.01 + $contact_detail[$key]['profit_adjust']) * $temp_fee_ratio;
					$arr_ratio[$key]['normal_profit_2'] = $contact_detail[$key]['delivery_quantity'] * ($contact_detail[$key]['sale_price'] - $arr_ratio[$key]['end_cost_price'] - $temp_sale_expense) * ($arr_ratio[$key]['normal_profit_ratio'] * 0.01 + $contact_detail[$key]['profit_adjust']) * $temp_fee_ratio * $temp_normal_profit_discount_ratio;
				}
			}
            //对业务员编码为S开头的业务员的合同并且合同日期的审核日期是2019年7月1日（含2019年7月1日）以后的合同采用的算法；
            if(substr($value['salesman_id'], 0, 1) == 'S' || substr($value['salesman_id'], 0, 1) == 's'){
                //超期扣提成比例=超期天数*0.00028 ；
                $arr_ratio[$key]['delay_ratio'] = $contact_detail[$key]['delay_day'] * 0.00028;
                //当 最终底价=0：超期扣提成=0;
                //当 最终底价 < 销售单价：超期扣提成=发货米数 * 销售单价 *超期扣提成比例;
                //当 最终底价 > 销售单价：超期扣提成=发货米数 * 最终底价 *超期扣提成比例;
                if($arr_ratio[$key]['end_cost_price'] == 0){
                    $arr_ratio[$key]['delay_money'] = 0;
                }elseif($arr_ratio[$key]['end_cost_price'] <= $arr_ratio[$key]['sale_price']){
                    $arr_ratio[$key]['delay_money'] = $arr_ratio[$key]['delivery_quantity'] * $arr_ratio[$key]['sale_price'] * $arr_ratio[$key]['delay_ratio'];
                }elseif($arr_ratio[$key]['end_cost_price'] > $arr_ratio[$key]['sale_price']){
                    $arr_ratio[$key]['delay_money'] = $arr_ratio[$key]['delivery_quantity'] * $arr_ratio[$key]['end_cost_price'] * $arr_ratio[$key]['delay_ratio'];
                }
                if(($arr_ratio[$key]['normal_business'] + $arr_ratio[$key]['special_business']) <= $arr_ratio[$key]['delay_money']){
                    $arr_ratio[$key]['delay_money'] = $arr_ratio[$key]['normal_business'] + $arr_ratio[$key]['special_business'];
				}
            }
		}
		$this -> db_contact_detail -> updateSettlingRatio($arr_ratio);
	}

	public function deleteContact() {
		$contact_id = $_POST['contact_id'];
		$inventory_id = $_POST['inventory_id'];
		$this -> db_contact_main -> deleteItem($contact_id);
		$this -> db_contact_detail -> deleteItem($contact_id, $inventory_id);
	}

	public function loadManualContactPage() {
		$this -> db_U8 = D("U8");
		$count_manual_contact = $this -> db_contact_main -> countManualContact();
		$Page = new \Think\Page($count_manual_contact, 1000);
		$show = $Page -> show();
		// 分页显示输出

		$manual_contact = $this -> db_contact_main -> getManualContact($Page);
		// $manual_contact = $this -> db_customer -> addCustomerName($manual_contact);
		// $manual_contact = $this ->db_salesman -> addSalesmanName($manual_contact);
		$manual_contact_detail = $this -> db_contact_detail -> getContactDetail($manual_contact);
		$manual_contact_detail = $this -> db_U8 -> getInventoryDetail($manual_contact_detail);
		$this -> assign("manual_contact_detail", $manual_contact_detail);
		$this -> assign("page", $show);
		$this -> display('ManualContactPage');
	}

	public function setSettlingContact() {
		// 使手工结算合同加入可结算合同，并扣除回款
		$contact_id = $_POST['contact_id'];
		$this -> db_contact_main -> setSettlingContact($contact_id);
		$contact_total_money = $this -> db_contact_detail -> getContactTotalMoney($contact_id);
		$temp = $this -> db_contact_main -> getContactSalemanAndCustomer($contact_id);
		$customer_id = $temp['customer_id'];
		$this -> db_coustomer_funds -> subtractCustomerBenefit($customer_id, $contact_total_money);
	}

	public function settleContact() {
		$contact_main = $this -> db_contact_main -> getSettlingContactGroupByDate();
		foreach ($contact_main as $key => $value) {
			$date = $key;
			if ($this -> db_salary -> checkSalarySettled($date)) {
				//return true 已结算 do nothing
			} else {
				$end_date = $this -> db_load_history -> getLastEndDate();
				$load_month = substr($end_date, 0, 7);
				$this -> db_contact_main -> setContactSettled($value);
				$this -> db_contact_detail -> setContactSettled($value, $load_month);
			}
		}
		$res = $this -> db_coustomer_funds -> select();
		$res = $this -> db_customer -> addCustomerName($res);
		$end_date = $this -> db_load_history -> getLastEndDate();
		$month = substr($end_date, 0, 7);
		foreach ($res as $key => $value) {
			$this -> db_settled_history -> addItem($value['customer_id'], $value['this_month_settled_money'], $value['this_month_funds_back'], $value['this_month_funds'], $value['last_month_benefit'], $value['benefit'], $value['customer_name'], $month);
		}
		$this -> db_constomer_funds -> clearThisMonthSettledMoney();
	}

	public function loadSettledContactPage() {
		$this -> db_U8 = D("U8");
		$count_settled_contact = $this -> db_contact_main -> countSettledContact();
		$count_settled_contact_detail = $this -> db_contact_main -> countSettledContactDetail();
		$Page = new \Think\Page($count_settled_contact, 100);
		$show = $Page -> show();
		// 分页显示输出

		$settled_contact = $this -> db_contact_main -> getSettledContact($Page);
		// $settled_contact = $this -> db_customer -> addCustomerName($settled_contact);
		// $settled_contact = $this ->db_salesman -> addSalesmanName($settled_contact);
		$settled_contact_detail = $this -> db_contact_detail -> getContactDetail($settled_contact);
		//	$settled_contact_detail = $this -> db_U8 -> getInventoryDetail($settled_contact_detail);
		$this -> assign('count_settled_contact', $count_settled_contact);
		$this -> assign('count_settled_contact_detail', $count_settled_contact_detail);
		$this -> assign("page", $show);
		$this -> assign("settled_contact_detail", $settled_contact_detail);
		$this -> display('BusinessPercent:SettledContactPage');
	}

	public function loadCommissionBuisnessPage() {
		$count_contact_main = $this -> db_contact_main -> count();
		$Page = new \Think\Page($count_contact_main, 100);
		$show = $Page -> show();
		// 分页显示输出
		$contact_main = $this -> db_contact_main -> getContact($Page);
		$contact_detail = $this -> db_contact_detail -> getContactDetail($contact_main);
		$this -> assign('page', $show);
		$this -> assign("contact_detail", $contact_detail);
		$this -> display('BusinessPercent:CommissionBusinessPage');
	}

	public function complicateSearch() {

		session('search_settled_contact_data', $_POST);
		$condition = array();
		$temp_array = array();
		$condition['contact_id'] = $_POST['search_contact_id'];
		$condition['cSOCode'] = $_POST['search_cSOCode'];
		$condition['salesman_id'] = $_POST['search_salesman_id'];
		$condition['salesman_name'] = $_POST['search_salesman_name'];
		$condition['customer_id'] = $_POST['search_customer_id'];
		$condition['customer_name'] = $_POST['search_customer_name'];
		$condition['classification_id'] = $_POST['search_classification_id'];
		$condition['inventory_id'] = $_POST['search_inventory_id'];
		$condition['specification'] = $_POST['search_specification'];
		$condition['colour'] = $_POST['search_colour'];
		$type = $_POST['search_type'];
		foreach ($condition as $key => $value) {
			if ($value == "") {
				unset($condition[$key]);
			}
		}
		$res = $this -> db_contact_detail -> searchByCondition($condition);
		$search_begin_date = $_POST['search_begin_date'];
		$search_end_date = $_POST['search_end_date'];
		if ($condition == null) {
			$count_settled_contact_detail = $this -> db_contact_detail -> searchCountByDate($search_begin_date, $search_end_date);
			$count_settled_contact = $this -> db_contact_main -> searchCountByDate($search_begin_date, $search_end_date);
			$Page = new \Think\Page($count_settled_contact_detail, 150000000);
			$show = $Page -> show();
			// 分页显示输出
			$res = $this -> db_contact_detail -> searchByDate($search_begin_date, $search_end_date, $Page, $type);
		}
		foreach ($res as $key => $value) {
			if ($search_begin_date != null && $search_end_date != null) {
				if ($type == "settled") {
					if ($value['settled_date'] < $search_begin_date || $value['settled_date'] > $search_end_date) {
						unset($res[$key]);
						continue;
					}
				} else {
					if ($value['date'] < $search_begin_date || $value['date'] > $search_end_date) {
						unset($res[$key]);
						continue;
					}
				}

			}
			if ($type == "settling") {
				$temp = $this -> db_contact_main -> getSettlingContactByContactId($value['contact_id']);
			} elseif ($type == "settled") {
				$temp = $this -> db_contact_main -> getSettledContactByContactId($value['contact_id']);
			}
			if ($temp == null && $type != "commission_business") {
				unset($res[$key]);
			} else {
				if (!in_array($value['contact_id'], $temp_array)) {
					array_push($temp_array, $value['contact_id']);
				}
			}
		}
		//增加几个字段的合计
		$tempTotal = array();
		$tempTotal['contact_id'] = '合计';
		$tempTotal['normal_business'] = 0;
		$tempTotal['special_business'] = 0;
		$tempTotal['normal_profit'] = 0;
		$tempTotal['normal_profit_1'] = 0;
		$tempTotal['normal_profit_2'] = 0;
		$tempTotal['total_business_profit'] = 0;
		$tempTotal['delivery_money'] = 0;
		$tempTotal['end_sale_expense'] = 0;
		foreach ($res as $key => $value) {
			$tempTotal['normal_business'] += $value['normal_business'];
			$tempTotal['special_business'] += $value['special_business'];
			$tempTotal['normal_profit'] += $value['normal_profit'];
			$tempTotal['normal_profit_1'] += $value['normal_profit_1'];
			$tempTotal['normal_profit_2'] += $value['normal_profit_2'];
			$tempTotal['total_business_profit'] += $value['total_business_profit'];
			$tempTotal['end_sale_expense'] += $value['end_sale_expense'];
			$tempTotal['delivery_money'] += $value['delivery_money'];
		}
		if ($type == "settling") {
			$count_settling_contact_detail = count($res);
			array_unshift($res,$tempTotal);
			$count_settling_contact = count($temp_array);
			$this -> assign('count_settling_contact', $count_settling_contact);
			$this -> assign('count_settling_contact_detail', $count_settling_contact_detail);
			$this -> assign('settling_contact_detail', $res);
			$this -> display('SettlingContactPage');
		} elseif ($type == "settled") {
			$count_settled_contact_detail = count($res);
			array_unshift($res,$tempTotal);
			if ($count_settled_contact == null) {
				$count_settled_contact = count($temp_array);
			}
			$this -> assign('count_settled_contact', $count_settled_contact);
			$this -> assign('count_settled_contact_detail', $count_settled_contact_detail);
			$this -> assign("settled_contact_detail", $res);
			$this -> assign('page', $show);
			$this -> display('BusinessPercent:SettledContactPage');
		} elseif ($type == "commission_business") {
			array_unshift($res,$tempTotal);
			$this -> assign("contact_detail", $res);
			$this -> display('BusinessPercent:CommissionBusinessPage');
		}
	}

}
?>
