<?php
 class PublicAction extends Action {
	
    public function login(){
		
    	echo 456;
    	
    	$this->assign('login','我是login测试数据');
    	$this->display();
    }
    
    
    
}