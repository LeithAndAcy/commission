<?php
namespace Home\Model;
use Think\Model;
class VariableModel extends Model {

    public function getVariables(){
        $res = $this -> select();
        $variables = array();
        foreach ($res as $key => $value){
            $variables[$value['key']] = $value['value'];
        }
        return $variables;
    }
}
?>