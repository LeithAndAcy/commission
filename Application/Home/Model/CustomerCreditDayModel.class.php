<?php
namespace Home\Model;
use Think\Model;
class CustomerCreditDayModel extends Model {

    public function getCreditDays(){
        $res = $this -> select();
        $days = array();
        foreach ($res as $key => $value){
            $days[$value['customer_id']] = $value['credit_day'];
        }
        return $days;
    }
}
?>