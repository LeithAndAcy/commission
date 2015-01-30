<?php
namespace Home\Model;
use Think\Model;
class FundsBackModel extends Model {
	
	public function getAllItems(){
		$res = $this->select();
		return $res;
	}
	public function editItem($id,$funds_back_money){
		$condition = array();
		$condition['id'] = $id;
		$this -> where($condition) -> setField('funds_back_money',$funds_back_money);
	}
	public function addItem($data){
		$this -> add($data);	
	}
	public function deleteItem($id){
		$condition = array();
		$condition['id'] = $id;
		$this -> where($condition)-> delete();
	}
}
?>