<?php
namespace Home\Model;
use Think\Model;
class FundsBackModel extends Model {
	
	public function getAllItems(){
		$res = $this -> query("select id,customer_id,ltrim(str(funds_back_money,18,4)) as funds_back_money from commission_funds_back");
		return $res;
	}
	public function editItem($id,$funds_back_money){
		$condition = array();
		$condition['id'] = $id;
		$this -> where($condition) -> setField('funds_back_money',$funds_back_money);
	}
	public function addItem($data){
		$condition = array();
		$condition['customer_id'] = $data['customer_id'];
		if($this -> where($condition) -> find()){
			$this -> where($condition) ->save($data);
		}else{
			$this -> add($data);
		}
	}
	public function deleteItem($id){
		$condition = array();
		$condition['id'] = $id;
		$this -> where($condition)-> delete();
	}
	public function getFunds($condition){
		$temp = $this -> where($condition) -> getField('funds_back_money');
		return $temp;
	}
}
?>