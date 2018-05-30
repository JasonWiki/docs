<?php
// 本类由系统自动生成，仅供测试用途
class IndexAction extends Action {
	
	
    public function index(){
    	
    	echo 123;
    	
    	$this->assign('index','我是index测试数据');
    	$this->display();
    }
    
    
    public function user() {
    	
		$this->display();    	 
    }
    
}