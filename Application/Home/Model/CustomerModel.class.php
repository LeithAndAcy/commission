<?php
namespace Home\Model;
use Think\Model;
class CustomerModel extends Model {
	
	public function getAllCustomer($Page){
		$res = $this-> limit($Page->firstRow.','.$Page->listRows) ->select();
		return $res;
	}
	public function editItem($id,$data){
		$condition['id'] =$id;
		$this -> where($condition)->save($data);
	}
	public function addItem($data){
		if($this -> checkDuplicate($data['customer_id'])){
			$condition = array();
			$condition['customer_id'] = $data['customer_id'];
			$this -> where($condition) -> save($data);
		}else{
			$this -> add($data);
		}
	}
	public function deleteItem($id){
		$condition['id'] = $id;
		$this -> where($condition)->delete();
	}
	public function addCustomerName($array){
		$res = $this -> select();
		foreach ($array as $key => $value) {
			foreach ($res as $kk => $vv) {
				if($value['customer_id'] == $vv['customer_id']){
					$array[$key]['customer_name'] = $vv['customer_name'];
					break;
				}
			}
		}
		return $array;
	}
	public function getIdByName($name){
		$condition = array();
		$condition['customer_name'] = $name;
		$res = $this -> where($condition) -> getField("customer_id");
		return $res;
	}
	public function checkDuplicate($id){
		$condition = array();
		$condition['customer_id'] = $id;
		$res = $this -> where($condition) -> find();
		if($res){
			return true;
		}else{
			return false;
		}
	}
}
?>