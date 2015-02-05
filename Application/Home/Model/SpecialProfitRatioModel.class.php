<?php
namespace Home\Model;
use Think\Model;
class SpecialProfitRatioModel extends Model {

	public function getAllItems() {
		$res = $this -> query("select id,salesman_id,ltrim(str(low_limit,12,2)) as low_limit,ltrim(str(high_limit,12,2)) as high_limit,ltrim(str(ratio * 100,12,2)) as ratio from commission_special_profit_ratio");
		return $res;
	}

	public function addItem($data) {
		$data['ratio'] *= 0.01;
		$condition = array();
		$condition['salesman_id'] = $data['salesman_id'];
		$condition['low_limit'] = $data['low_limit'];
		$condition['high_limit'] = $data['high_limit'];
		if ($this -> where($condition) -> find()) {
			$this -> where($condition) -> save($data);
			return true;
		} else {
			$condition = array();
			$condition['salesman_id'] = $data['salesman_id'];
			$temp = $this -> where($condition) -> select();
			foreach ($temp as $key => $value) {
				if (($data['low_limit'] > $value['low_limit'] && $data['low_limit'] < $value['high_limit']) || ($data['high_limit'] < $value['high_limit'] && $data['high_limit'] > $value['low_limit'])) {
					return false;
				}
			}
			$this -> add($data);
			return true;
		}

	}

	public function editItem($id, $ratio) {
		$condition = array();
		$condition['id'] = $id;
		$this -> where($condition) -> setField('ratio', $ratio * 0.01);
	}

	public function deleteItemById($id) {
		$condition = array();
		$condition['id'] = $id;
		$this -> where($condition) -> delete();
	}

	public function getSpecialProfitRatio($salesman_id, $funds) {
		$condition = array();
		$res = $this -> query("select ratio from commission_special_profit_ratio where(salesman_id = '$salesman_id') AND(low_limit <= '$funds') AND(high_limit > '$funds')");
		$ratio = $res[0]['ratio'];
		return $ratio;
	}

}
?>