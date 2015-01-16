<?php
namespace Home\Model;
use Think\Model;
class CustomerModel extends Model {
	
	public function getAllCustomer(){
		$res = $this->select();
		return $res;
	}
	public function editItem($id,$data){
		$condition['id'] =$id;
		$this -> where($condition)->save($data);
	}
	public function addItem($data){
		$this -> add($data);
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
}
?>