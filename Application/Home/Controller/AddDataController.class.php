<?php
namespace Home\Controller;
use Think\Controller;
class AddDataController extends Controller {
	
	private $db_contact_main;
	private $db_contact_detail;
	function _initialize() {
		if (!_checkLogin()) {
			$this->error('登陆超时,请重新登陆。','/commission',2);
			exit;
		}
		$this -> db_contact_main = D("ContactMain");
		$this -> db_contact_detail = D("ContactDetail");
	}
	
	public function loadAddDataPage(){
		$this -> display("AddDataPage");
	}
	public function addContact(){
		$data = array();
		$data['contact_id'] = $_POST['contact_id'];
		$data['salesman_id'] = $_POST['salesman_id'];
		$data['customer_id'] = $_POST['customer_id'];
		$data['cSOCode'] = $_POST['cSOCode'];
		$this -> db_contact_main -> addItem($data);
		$this -> loadAddDataPage();
	}
	public function addDetail(){
		$data = array();
		$data = $_POST;
		$this -> db_contact_detail -> addItem($data);
		$this -> loadAddDataPage();
	}
	public function addSalesmanFundsBack(){
		$data = array();
		$data = $_POST;
		$salesman_funds = M("SalesmanFunds");
		$salesman_funds -> add($data);
		$this -> loadAddDataPage();
	}
}
?>