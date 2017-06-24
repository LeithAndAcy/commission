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
	public function renewItems($array){
		$this -> query("delete from commission_customer;");
		// $str_query = "insert into commission_customer (customer_id,customer_name,area_code,area) values";
		// foreach ($array as $key => $value) {
			// $id = $key +1;
			// $customer_id = $value['customer_id'];
			// $customer_name = $value['customer_name'];
			// $area_code = $value['area_code'];
			// $area = $value['area'];
			// $str_query .= " ('$customer_id','$customer_name','$area_code','$area'),";
		// }
		// $str_query = substr($str_query, 0,-1).';';
		// $this -> query($str_query);
		foreach ($array as $key => $value) {
			$this -> add($value);
		}
	}
	public function getCustomerAreaById($customer_id){
		$condition = array();
		$condition['customer_id'] = $customer_id;
		$res = $this -> where($condition) -> getField('area');
		return $res;
	}
	public function getSHYCId(){
		$condition = array();
		$condition['customer_name'] = "上海易初电线电缆有限公司";
		$res = $this -> where($condition) -> getField('customer_id');
		return $res;
	}
}
?>