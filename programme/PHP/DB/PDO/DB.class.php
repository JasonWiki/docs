<?php 

class DB {
	private $db;		//数据库连接
	static private $_instance;	//保存对象

	//私有化克隆，防止类被克隆
	private  function __clone() {}		//不能有分号
	
	//构造方法(连接数据库)
	private function __construct() {
		try {
			$this->db = new PDO('mysql:host=127.0.01;dbname=thinkphp;charset=UTF8', 'root','514591');
		} catch (PDOEException $e) {
			$this->error = $e->getMessage();
		}	
	}
	
	//外部取得数据库对象
	static public function getInstance() {
		//instanceof  判断一个对象是否是这个类的实例
		if(!(self::$_instance instanceof DB)) {
			self::$_instance = new DB();
		}
		return self::$_instance;
	}
	
	//执行SQL
	public function query($sql) {
		return $this->db->query($sql);
	}
	
	
	
}

?>