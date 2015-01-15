<?php
namespace Home\Model;
use Think\Model;
class U8Model extends Model {
	protected $connection = array(
		'DB_TYPE' => 'sqlsrv', // 数据库类型
		'DB_HOST' => 'localhost', // 服务器地址
		'DB_NAME' => 'U8', // 数据库名
		'DB_USER' =>'sa',
		'DB_PWD' =>'000000',
		'DB_PORT' => 1433, // 端口
		'tablePrefix' => ''
	);
	public function test(){
		$res = $this -> query("select * from SO_SOMain");
		print_r($res);
	}
}
?>