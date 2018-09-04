/**
 * 匿名函数
 * 核心语法：()() ->  (函数体 )( 参数体 )		//自我执行语法
 * 语法：( function(变量) {代码块} )( 参数体1,参数体2 );
 */
var niName = (function($age){		//也可以不用变量引用直接执行匿名函数
	return $age;
})(1);
//alert(niName);



/**
 * 闭包 ：函数有权访问另外一个函数作用域内的变量的函数
 * 特性：闭包运行时，this指向的是全局对象window
 */
function biBao($str) {
	return function ($strTwo) {			//闭包
		return '匿名函数' + $str + $strTwo;
	};
}
//alert(biBao('这个是闭包')('123'));

//1.闭包累加局部变量
function box() {
	var age = 100;		
	return function xyz() {
		age++ ;
		return age;
	};
}
//var b = box();
//alert(b());
//alert(b());
//b = null;		//解除引用，等待垃圾回收，闭包会影响性能


//1.循环
function box1() {
	var arr = [];
	for (var i=0;i<5;i++) {
		arr[i] = (function (num) {		//自我执行匿名函数，把返回值赋值给数组
			return num;
		})(i);
	}
	return arr;	//返回数组
}
//var b = box1();
//alert(b);


//2.循环模拟全局变量
function box2() {
	var arr = [];
	for (var i=0;i<5;i++) {
		arr[i] = (function (num) {		//自我执行匿名函数
			return function () {			//返回值，返回给匿名函数
				return num;
			};
			
		})(i);
	}
	return arr;
}
//var b = box2();
//alert(b[0]());


//3.闭包函数作用域
var box3 = {
	user : 'The Box',
	getUser : function () {
		var _this = this;
		return function () {
			return _this.user;
		};	
	},
}
//alert(box3.getUser()());




function box4(){
	
	
	
}













































function getTypeof(obj) {
	var type =null;
	if (obj instanceof Array) {
		type =  'Array';
	} else if (obj instanceof Function) {
		type = 	'Function';
	} else if (obj instanceof Object) {
		type = 'Object';
	} else if (obj instanceof RegExp) {
		type = 'RegExp';
	} else {
		switch (typeof obj) {
			case 'string' :
				type = 'string';
				break;
			case 'number':
				type = 'number'	;
				break;
		}
	} 
	return type;
}
