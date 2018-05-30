<?

/**
在PHP5中，接口是可以继承自另外一个接口的。这样代码的重用更有效了。要注意只有接口和接口之间使用 继承关键字 extends。类实现接口必须实现其抽象方法，使用实现关键字 implements。
下面的这个例子定义接口User，User有两个抽象方法 getName和setName。 又定义了接口VipUser， 继承自User接口，并增加了和折扣相关的方法getDiscount。
最后定义了类 Vip ，实现了VipUser接口。并实现了其中的三个方法。


1、interface ：接口类。作为模板，子类必须实现接口类的方法。
2、abstract ：抽象类。在父类实现，子类需要时调用

 */
interface User {
	public function getName();
	public function setName($_name);
}

interface VipUser extends User {
	public function getDiscount(); //添加了获得折扣的抽象方法.
}

class Vip implements VipUser {
	private $name;
	private $discount = 0.8;// 定义折扣变量
	
	public function getName(){ //实现getName方法
		return $this->name;
	}
	
	public function setName($_name){//实现setName方法
		$this->name = $_name;
	}
	
	public function getDiscount(){//实现折扣方法.
		return $this->discount;
	}
}



/**
 * 接口可以实现多继承，这是接口很特殊的地方。注意下面的代码和用法。
 * @author wade
 *
 */

interface User1 {
	public function getName();
	public function setName($_name);
}

interface Administrator {
	public function setNews($_news);
}

//注意这里的多继承.
interface NewsAdministrator  extends User,Administrator{
}

class NewsAdmin implements NewsAdministrator { //实现接口
	public function getName(){
		//.........
	}
	public function setName($_name){
		//.........
	}
	public function setNews($_news){
		//.........
	}
}



/**
 * 抽象类实现接口，可以不实现其中的抽象方法，而将抽象方法的实现交付给具体能被实例化的类去处理。
 */
interface User2 {
	public function getName();
	public function setName($_name);
}
//AbstractNormalUser 只实现了 User接口中的一个方法,
abstract class  AbstractNormalUser{
	protected $name;
	public function getName(){
		return $this->name;
	}
}
//这里实现了接口的另外一个方法.
class NormalUser extends AbstractNormalUser {
	public function setName($_name){
		$this->name = $_name;
	}
}

$normalUser = new NormalUser();
$normalUser->setName("tom");
echo "name is ".$normalUser->getName();





?>