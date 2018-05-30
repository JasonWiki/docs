//获取对象CSS样式
function getStyle(obj,attr) {//对象，属性
	if (obj.currentStyle) {
		return obj.currentStyle[attr];	//IE模式
	} else {
		return getComputedStyle(obj,false)[attr];//FF模式
	} 
}

/*	事件绑定
 * @ obj 	对象
 * @	sEv	事件
 * @ fn 	执行函数
 */

function myAddEvent(obj,sEv,fn) {
	
	if (obj.attachEvent) { //IE 下 (ie下有bug会把绑定事情的对象this指针指向window)
		obj.attachEvent('on'+sEv,function() {
			//调用fn函数，并且把这个函数指定给传入的对象
			if (false == fn.call(obj)) {
				event.cancelBubble = true;	//防止事件冒泡
				return false;						//阻止浏览器默认事件
			}
				
		})	;
	} else {	//FF	下				  	
		obj.addEventListener(sEv,function (ev) {
			if (false == fn.call(obj,ev)) {
				ev.cancelBubble = true;			//防止事件冒泡
				ev.preventDefault();//FF事件绑定中  阻止浏览器默认事件
			}
		},false);
	}
}

//把数组二添加到数组一中
 function appendArr(arr1,arr2) {
 	var i = null;
 	for (i=0;i<arr2.length;i++) {
 		arr1.push(arr2[i]);
 	}
 }

//通过class获取指定对象
function getByClass(oParent,sClass) {//父级,className
	var aEle = oParent.getElementsByTagName('*');	//选取父级下所有元素
	var aResult = new Array();
	
	var re = new RegExp('\\b'+sClass+'\\b','i');	//匹配字符是否为独立单词

	for (var i=0;i<aEle.length;i++) {
		//如：box 在  box tab  中，正则匹配到，并且是个独立的单词
		if (re.test(aEle[i].className)) {//匹配
			aResult.push(aEle[i]);
		}
	}
	return aResult;
}

//获取同级元素的序号
function getIndex(obj) {
	//			  对象.      父级.           子级
	var all = obj.parentNode.children;		//对象同级的所有元素
	var i = null;
	for(i=0;i<all.length;i++) {
		if (all[i] == obj) {	//如果找到和自己一样的
			return i;
		}
	}	
	return null;
}


//碰撞检测九宫格	//obj1碰到obj2，返回true,反之false
function detection (obj1,obj2) {		//对象1，对象2
		//对象1九宫格
		var l1 = obj1.offsetLeft;											//左
		var r1 = obj1.offsetLeft + obj1.offsetWidth;			//右
		var t1 = obj1.offsetTop;											//上
		var b1 = obj1.offsetTop + obj1.offsetHeight;		//下
		//对象2九宫格
		var l2 = obj2.offsetLeft;											//左
		var r2 = obj2.offsetLeft + obj2.offsetWidth;			//右
		var t2 = obj2.offsetTop;											//上
		var b2 = obj2.offsetTop + obj2.offsetHeight;		//下
		
		//检测碰撞
		if (l1 > r2 || r1 < l2 || t1 > b2 ||  b1 < t2) {	//没有碰上
			return false;	
		} else {		//碰上了
			return true;
		}
}


//计算对象碰到物体后，对象离物体最近的那个 .return 被碰撞物体的obj
function minObj(obj,obj2) {	//对象，对象数组
		var i =0;
		var iMin = 999999999;				//最小距离初始值
		var iMinIndex = null;				//最小距离对象索引值
	
		//计算对象与物体距离最小的物体
		for (i=0;i<obj2.length;i++) {		
			if (obj == obj2[i]) continue;
			if (detection(obj,obj2[i])) {						//对象与指定物体碰上后	
			var count = getCount(obj,obj2[i]);			//计算对象与碰撞物体的距离
				if (count < iMin) {								//找出对象与碰撞物体，距离最近的
					iMin = count;											
					iMinIndex = i;							//把最近物体的在数组所在位置，放入变量中
				}	
			}
		}
		
		//如果找到，返回距离最小物体的对象
		if (iMinIndex != null) {
			return obj2[iMinIndex];
		} else {
			return null;
		}
}


//计算对象obj1到obj2之间的距离，(勾股定律)	//返回num
function getCount(obj1,obj2) {	//对象1，对象二
	var a = obj1.offsetLeft - obj2.offsetLeft;
	var b = obj1.offsetTop - obj2.offsetTop;
	return Math.sqrt(Math.pow(a,2) + Math.pow(b,2));
		
}


//滚动条(可视区)，距离页面顶部的距离
function scroll(string) {
	switch (string) {
		case 'x' ://下边滚动条
			return document.documentElement.scrollLeft || document.body.scrollLeft;
			break;
		case 'y' ://有边滚动条
			return document.documentElement.scrollTop || document.body.scrollTop;
			break;															 
	}
}


//可视区窗口大小
function visual (string) {
	switch (string) {
		case 'w' ://可视区长度
			return document.documentElement.clientWidth;
			break;
		case 'h' ://可视区高度
			return  document.documentElement.clientHeight;
			break;
	}	
}




//实例化一个vQuery对象
function $(vArg) {
	return new VQuery(vArg); 
}

//构造类
function VQuery(vArg) {	//选择器

	//实例化就运行的方法
	this.elements = new Array();//存放对象
	
	switch(typeof vArg) {
		case 'function' :
			myAddEvent(window,'load',vArg);//对象、事件、方法
			break;
		case 'string' :
			switch (vArg.charAt(0)) {//返回字符串第1字符
				case '#' :		//id
					var obj = window.document.getElementById(vArg.substring(1));
					//substring(1)窃取字符串，跳过第一个字符
					this.elements.push(obj);
					break;
				case '.' :		//class
					this.elements = getByClass(document,vArg.substring(1));
					break;
				default :		//标签
				 	this.elements = window.document.getElementsByTagName(vArg);
			}
			break;	
		case 'object' :		//对象
			this.elements.push(vArg);
			break;	 
	}
}

//函数对象原型添加方法

//点击事件
VQuery.prototype.click = function (fn) {		
	var i = null;
	for(i=0;i<this.elements.length;i++) {
		myAddEvent(this.elements[i],'click',fn);
	}
	return this;//返回对象
}


//显示
VQuery.prototype.show = function(fn) {
	var i = null;
	for(i=0;i<this.elements.length;i++) {
		this.elements[i].style.display = 'block';
	}
	return this;//返回对象
}


//影藏
VQuery.prototype.hide = function (fn) {
	var i = null;
	for(i=0;i<this.elements.length;i++) {
		this.elements[i].style.display = 'none';
	}	
	return this;//返回对象
}


//移入、移除事件
VQuery.prototype.hover = function (fnOver,fnOut) {
	var i = null;
	for(i=0;i<this.elements.length;i++) {
		myAddEvent(this.elements[i],'mouseover',fnOver);
		myAddEvent(this.elements[i],'mouseout',fnOut);
	}
	return this;//返回对象
}


//设置、获取样式
VQuery.prototype.css = function(attr,value) {
	var i = null;
	
	if (arguments.length == 2) { //设置样式
		for(i=0;i<this.elements.length;i++) {
			this.elements[i].style[attr] = value;
		}
	} else {		
		
		if (typeof attr == 'string') {		//获取样式
			return	getStyle(this.elements[0],attr);//获取匹配到的第一个CSS样式	
		} else {		//设置多个样式
			for (i=0;i<this.elements.length;i++) {//遍历对象
				for (var key in attr) {								//遍历整个Json
					this.elements[i].style[key] =  attr[key];//添加到一个对象中
				}
			}
		}
	}
	return this;//返回对象
}


//切换
VQuery.prototype.toggle = function () {//参数为执行函数
	var i = null;
	var _arguments = arguments;//存放toggle的参数信息，返回数组
	
	//执行函数
	function addtoggle(obj) {	//指定对象												
		var count = 0;			//闭包，每个对象独享这个变量												
		myAddEvent(obj,'click',function() {								//绑定事件
			//0 % 3 =0    //1 % 3 =1    //2 % 3 =2    //3 % 3 =0   
			_arguments[count % _arguments.length].call(obj);//对象执行函数
			count++;	//每次执行累加
		});
	}
	
	for(i=0;i<this.elements.length;i++) {
			addtoggle(this.elements[i]);	//为每个对象设置一个事件和一个执行函数
	}
	
	return this;//返回对象
}


//获取、设置标签的属性
VQuery.prototype.attr = function (attr,value) {
	if (arguments.length == 2) {
		var i = null;
		for(i=0;i<this.elements.length;i++) {
			this.elements[i][attr] = value;
		}
	} else {
		return this.elements[0][attr];
	}
	return this;//返回对象
}


//对象数组，计数选择器
 VQuery.prototype.eq = function (n) {
 	return $(this.elements[n]) ;	  //把dom对象转换VQuery对象，返回出去

 }
 
 
 //选择父级下的指定标签，返回出去
  VQuery.prototype.find = function(str) {
  	var i = null;
  	var aResult = new Array();//存放获取到的对象
  	
  	for (i=0;i<this.elements.length;i++) {	//遍历选中的对象
  	
  		switch (str.charAt(0)) {
  			case '.' :	//样式选择器 如：class="div1"
  				//在所有父级对象下，选中指定的class
  				var aEle = getByClass(this.elements[i],str.substring(1));	
  				appendArr (aResult,aEle);		//把选中的对象添加到数组中
  				break;
  			default:	//标签选择器 如： div
  				//在所有父级对象下，选中指定的标签名
  				var aEle = this.elements[i].getElementsByTagName(str);
  				appendArr (aResult,aEle);		//把选中的标签名加到数组中
  		}
  		
  	}
  	
  	//把父级下找到的指定标签，放到一个新的对象中，返回出去
  	var newVQuery = $();		//定义一个新的VQuery对象
  	newVQuery.elements = aResult;		//为新对象成员字段赋值
  	return newVQuery;			//再把对象返回出去
  	
  }
 
 
 //取得同级元素下的当前对象的序号
 VQuery.prototype.index = function () {
 	
 	return getIndex(this.elements[0]);//返回对象元素序号
 	
 }
 
 
 //添加任意事件
  VQuery.prototype.bind = function (sEv,fn) {//事件名，函数
  	var i = null;
  	for(i=0;i<this.elements.length;i++) {
  		myAddEvent(this.elements[i],sEv,fn);
  	}
  	
  	return this;
  }
 
 
 //在原型上添加方法
VQuery.prototype.extend = function (name,fn) {
	VQuery.prototype[name] = fn;//在原型上添加方法
	/*如：
	 * $().extend('show',function(){
		return this.elements.length;
	} );
	 */
	 return this;
}
 

//计算选中元素个数
 VQuery.prototype.size = function () {
 	return this.elements.length;
 }
 
 
 //运动框架
 VQuery.prototype.animate = function (json,fn) {	//
  	var i =null;
  	var fn = fn;
	for (i=0;i<this.elements.length;i++) {	//为所有选中的对象添加运动
		startMove(this.elements[i],json,fn);	//运动
	}

	//完美运动框架
	function startMove(oBject,json,fn) {	//对象,json数组,执行函数
	
		if(oBject.timer) clearInterval(oBject.timer);	//如果定时器开启则关闭
		
		oBject.timer= setInterval(function(){		//每隔30毫秒执行一次
		
			var oStop = true;//		所用运动结束，设置为true
			
		//1.取当前值
			for (var key in json) {	//同时改变json数组中的所有值
				//获取对象当前计算过后的CSS样式中的某一属性值。
				var iCur = '';	//转换样式string为number
				switch (key) {	
					case 'opacity':
						//计算机对小数处理，会出现各种问题，比如不能整除的数，会出现bug，所有，尽量用整数代替小数
						iCur = parseInt(parseFloat(getStyle(oBject,key))*100); //取整
						document.title = iCur;
						break;
					case 'background':	
						iCur = json[key];
						break;
					default:
						iCur = parseInt(getStyle(oBject,key));	//获取对象当前CSS行间样式中属性的值(取整)
				}
				
		//2.算速度
				//CSS样式属性改变值幅度  
				//速度 = (目标距离 - 对象当前位置) / 码数  
				var iSpeed = (json[key] - iCur) / 7;	
				
				//把+-小数取整
				iSpeed = iSpeed >0 ? Math.ceil(iSpeed) : Math.floor(iSpeed);
				//Math.ceil 取一个数的最大数   Math.floor 取一个数的最小数
				
		//3.检测停止。
				if (iCur != json[key]) {	//对象当前值不是目标值
					oStop=false;			//设置为false
				}
			
		//4.改变对象行间样式
				switch (key) {		
					case 'opacity':	//相片透明度
						//FF下
						oBject.style.opacity = (iCur+iSpeed) / 100;		
						//oBject.style.filter = 'alpha(opacity:'+(iCur+iSpeed)+')';	//IE模式				
						break;			
					case 'background'://背景
						oBject.style[key] = json[key];		
						break;
					default:	//数字样式
						//对象行间样式 = 对象当前样式+速度
						oBject.style[key] = iCur+iSpeed+'px';			
				}
			}
	
			//循环结束后，才关闭定时器
			if(oStop) {	
				clearInterval(oBject.timer);	//结束执行
				//alert('');
				if (fn) {	//链式运动
					fn();
				}
			}
			
		},30);
	}

}


//拖拽框架
VQuery.prototype.drag = function (style) {//对象
	var i = null;
	
	for (i=0;i<this.elements.length;i++) {
		DivMove(this.elements[i],style);
	}
		
	function DivMove (oDiv,style) { 
		var scrollx = scroll('x');	//滚轮距离顶部的位置
		var scrolly = scroll('y');	//滚轮距离顶部的位置
		oDiv.onmousedown = function (ev) {	//按下
			var oEvent = ev || event;
			var disX = oEvent.clientX + scrollx- oDiv.offsetLeft;	//鼠标x - 对象当前x = 鼠标距离对象左边框位置
			var disY = oEvent.clientY + scrolly - oDiv.offsetTop;
			
			oDiv.onmousemove = function (ev) {	//移动
				var oEvent = ev || event;
				var iLeft = oEvent.clientX + scrollx - disX;	//鼠标x  - 鼠标距离对象左边框位置 = 对象实际移动位置
				var iTop = oEvent.clientY + scrolly - disY;                                                                 
				
				if (style) { //是否允许div的拖出浏览器可视区
					//x轴限制  设置如：iLeft < 50 可以做磁性吸附
					if (iLeft < 0) {	//对象小于可视区x位置
						iLeft = 0;
						//对象大于 可视区-对象长度 = 对象最终可移动到的位置极限
					} else if (iLeft > document.documentElement.clientWidth - oDiv.offsetWidth) {
						iLeft = document.documentElement.clientWidth - oDiv.offsetWidth;
					}		
					//y轴限制
					if (iTop < 0) {
						iTop = 0;
					} else if (iTop > document.documentElement.clientHeight - oDiv.offsetHeight) {
						iTop = document.documentElement.clientHeight - oDiv.offsetHeight;
					}
				}
				
				//移动位置
				oDiv.style.left = iLeft + 'px';
				oDiv.style.top = iTop + 'px';
			};
					
			oDiv.onmouseup = function () {	//抬起鼠标销毁事件
				this.onmousemove = null;
				this.onmouseup = null;
				this.releaseCapture(); 	//销毁对象身上事件捕获 
			};
			
			//IE  FF通用
			oDiv.setCapture();		//事件捕获 (对象发生某个事件时，所有相同的事件都指向该对象)
		
			return false; //阻止浏览器默认行为(选中文字等)
		};
	}	
	return this;
}


//弹性运动框架
 VQuery.prototype.spring = function (iTarget) {
 	var i = null;
	
	for (i=0;i<this.elements.length;i++) {
		springMove(this.elements[i],iTarget);
	}
 	
 	function springMove(obj,iTarget) {
 		var iSpeed = 0;
		var left = 0;
		clearInterval(obj.timer);
		obj.timer = setInterval(function() {
		//1.计算运动值公式
			//运动变化值 iSpeed += (iTarget - obj.offsetLeft) /5;	
			if (obj.offsetLeft < iTarget) {//小于目标值，递增
				//不断增加运动速度 ： 速度 += 目标点 - 对象当前位置 / 5
				iSpeed += (iTarget - obj.offsetLeft) /5;	
				
			} else {//大于目标值做递减
				//不断减小运动速度 ： 速度 -= 当前对象位置 - 目标值 / 5
				iSpeed -= (obj.offsetLeft - iTarget) /5;	
			}
			iSpeed *= 0.7;	//(乘以一个小于1的数，会越来越小)，以此计算运动产生的摩擦力
			left +=iSpeed;	//改变位置
			
	 	//2.改变样式	
	 		//当速度最小  &&  距离目标值最小
			if (Math.abs(iSpeed) <1 && Math.abs(iTarget - left) < 1) {
				clearInterval(obj.timer);		//关闭定时器
				obj.style.left = iTarget;		//矫正
			} else {
				obj.style.left = left+ 'px';
			}	
		},30);
 	}

 }
 
 
 //apple菜单效果
 //调用此方法，必须外部传入一个事件对象，事件对象，添加时间方法中写
 VQuery.prototype.apple = function (oEvent) {//事件对象引用
	var i =null;
	var left = null;//left容错
	var top = null;//top容错
	if (arguments.length != 1) {	//参数有二个或者三个的时候，就是距离容错处理
		left = arguments[1];
		top = arguments[2];
	}
	for (i=0;i<this.elements.length;i++) {
		 //图片中心，距离网页边界的位置
		var x = this.elements[i].offsetLeft + left + (this.elements[i].offsetWidth /2);	
		var y = this.elements[i].offsetTop + top + (this.elements[i].offsetHeight /2);;

		 //求出鼠标坐标到图片坐标的长度
		var a = x - oEvent.clientX;//图片坐标x - 鼠标的坐标x
		var b = y - oEvent.clientY;//图片坐标y - 鼠标的坐标y
		 	
		 //var zzz= Math.pow(-10,2);	//求出10的二次方(幂)，为多少。结果为+- 100 
		 //Math.sqrt(36);		//计算数的平方根,可以有几次方
		var c = Math.sqrt(Math.pow(a,2)+Math.pow(b,2));	//数据距离图片中心的位置
		 	
		 //除以任意数，取得百分比 。
		var scale = 1- (c / 100);		
		 	
		if (scale < 0.5) scale = 0.5;	//限制比例
		 	
		this.elements[i].style.width = scale * 128 + 'px'; 	//设置图像宽度倍数
			
	}
 
 	return this;	
 }

 
 
 
/*格式化数字
 * @ s  num		数字参数
 * @ n  num		保留小数位数
 * return 			
 */
function setNum (s, n) {
 	n = n > 0 && n <= 20 ? n : 2;  
  	s = parseFloat((s + "").replace(/[^\d\.-]/g, "")).toFixed(n) + "";  
  	var l = s.split(".")[0].split("").reverse(),  
  	r = s.split(".")[1];  
  	t = "";  
  	for(i = 0; i < l.length; i ++ )  {  
    	t += l[i] + ((i + 1) % 3 == 0 && (i + 1) != l.length ? "," : "");  
  	}  
  	return t.split("").reverse().join("") + "." + r;  
 }



