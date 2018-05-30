<?php namespace Aifang\Model\User //申明一个命名空间

//类、函数、常量 受命名空间的影响

//当前命名空间下定义一个类
class UserPassword extends Eloquent {


}


//第一种调用 使用命名空间下的类UserPassword 类
use Aifang\Model\User\UserPassword;
$UserPassword = new Aifang\Model\User\UserPassword();


//第二种调用,别名
use Aifang\Model\User\UserPassword as UserPassword;
$UserPassword = new UserPassword();



//使用全局命名空间（顺序，查看参考资料：http://www.cnblogs.com/yjf512/archive/2013/05/14/3077285.html）
new \A();




/**

PHP机制 1、优先加载“命名空间”的类，2、再加载__autoload 引入的同名类，3、最后加载全局的类
*/


?>
