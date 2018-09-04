//构造函数
function Class(name,age) {		
	this.name = name;
	this.age = age;
	this.family = ['爸爸','妈妈','儿子'];
}
//原型共享方法
Class.prototype = {			
	constructor : Class,		//每个构造函数prototype对象都有一个constructor属性，指向它的构造函数。
	
	show : function ($str) {
		return this.name + this.age + 'show' + $sos;
	},
	
	hide : function ($num) {
		return  $num;
	},
	
	over : function ($over) {
		return this.name + $over;
	}
};


/**
 * 匿名函数
 * 语法：( function(变量) {代码块} )( 参数 );
 */
var niName = (function($age){
	return $age;
})(1);
//执行
//alert(box);

/**
 * 闭包 ：函数有权访问另外一个函数作用域内的变量的函数
 */
function biBao($str) {
	return function ($strTwo) {			//闭包
		return '匿名函数' + $str + $strTwo;
	};
}
//alert(biBao('这个是闭包')('123'));

//闭包累加局部变量
function box() {
	var age = 100;		
	return function xyz() {
		age++ ;
		return age;
	};
}
var b = box();
alert(b());
alert(b());
b = null;		//接触引用，等待垃圾回收，闭包会影响性能










