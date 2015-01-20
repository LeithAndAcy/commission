<?php
namespace Home\Model;
use Think\Model;
class LoadHistoryModel extends Model {
	
	public function getLastThreeHistory(){
		$res = $this -> order('end_date desc')->limit(3)->select();
		return $res;
	}
	
	public function getLastEndDate(){
		$res = $this ->max('end_date');
		return $res;
	}
	
	public function addItem($begin_date,$end_date){
		$data = array();
		$data['begin_date'] = $begin_date;
		$data['end_date'] =  $end_date;
		$this -> add($data);
	}
}
?>