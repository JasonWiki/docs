//构造一个类
function FaterClass(name,sex) {		//构造函数
	this.name = name;							//字段属性
	this.sex = sex;
}
FaterClass.prototype.showName = function () {//添加方法
	alert(this.name);
}
FaterClass.prototype.showSex = function () {
	alert(this.sex);
}



//子类继承父类
function ChildClass (name,sex,jop) {
	//对象，参数
	FaterClass.call(this,name,sex);			//把父类函数方法，指向子类对象。模拟继承父类成员字段
	this.jop = jop;										//设置自己的字段属性
}


//第一种(推荐)	//复制父类的原型方法，到子类的原型中，模拟继承方法。
for (var key in FaterClass.prototype) {	//继承父类方法
	//FaterClass.prototype  关联数组   [key]为字符串
	ChildClass.prototype[key] = FaterClass.prototype[key];//复制方法
}
ChildClass.prototype.showJop = function () {	//自己的方法
	alert(this.jop);
}


//第二种
ChildClass.prototype = new FaterClass();
//任何一个prototype对象都有一个constructor属性，指向它的构造函数。
ChildClass.prototype.constructor  = FaterClass;



//调用
var cCl = new ChildClass('wade','man','php');
cCl.showName();
cCl.showJop();


//位运算符
//& 转换为2进制，二个都为1，则为1。其他为0

// |  转换为2进制，二个都为0，则为0。其他都为1

// ^ 转换为2进制，一个为1，一个为0，则为1 。其他都为0
