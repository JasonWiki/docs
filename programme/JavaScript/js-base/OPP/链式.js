//对象链式
function show(str) {
	this.str = str;
}

show.prototype.bbb = function(str) {
	alert(str);
	return this;	//核心：方法执行完毕后，返回对象
}

//new show().bbb('xxx').bbb('ccc');


//函数链式
function fn (str) {
	alert(str);
	return fn;		//核心：函数执行完毕，返回函数本身
}

fn('123')('456')('789');