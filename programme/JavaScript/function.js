/**
window指代的是当前页面，例如对于此例它指的是top.html页面。

parent指的是当前页面的父页面，也就是包含它的框架页面。例如对于此例它指的是framedem

frames是window对象，是一个数组。代表着该框架内所有子页面。

self是当前窗口（等价window），

这样iframe里的js要操作父级窗口的dom可以通过parent，top这些对象来获取父窗口的window对象，例如：
 */
parent.document.getElementById("dom ID");


//FF调试工具，用来代替alert()或document.write()
console.log($data);

//执行字符串程序
eval("var aaa ="+getServerInfo('?s=/App/edit/',{'id' : id}));

//打印
window.print();

//数组对象
arguments

document.getElementById()				//选取id、返回对象
document.getElementsByName()		//选取标签名、返回数组



/**
 * 验证浏览器版本
 */
(function ($) {
	var userAgent = window.navigator.userAgent.toLowerCase();

	$.browser.msie10 = $.browser.msie && /msie 10\.0/i.test(userAgent);
	$.browser.msie9 = $.browser.msie && /msie 9\.0/i.test(userAgent);
	$.browser.msie8 = $.browser.msie && /msie 8\.0/i.test(userAgent);
	$.browser.msie7 = $.browser.msie && /msie 7\.0/i.test(userAgent);
	$.browser.msie6 = !$.browser.msie8 && !$.browser.msie7 && $.browser.msie && /msie 6\.0/i.test(userAgent);

	$.browser.mozilla = /firefox/.test(navigator.userAgent.toLowerCase());	//火狐
	$.browser.webkit = /webkit/.test(navigator.userAgent.toLowerCase());		//google
	$.browser.opera = /opera/.test(navigator.userAgent.toLowerCase());		//苹果浏览器
	//$.browser.msie = /msie/.test(navigator.userAgent.toLowerCase());		//IE
	var check_browser = false;
	var arr_browser = [
			$.browser.msie10,
			$.browser.msie9,
			$.browser.msie8,
			$.browser.mozilla,
			$.browser.webkit,
			$.browser.opera
	]
	for (var i in arr_browser) {
		if (arr_browser[i] == true) {
			check_browser = true;
			break;
		}
	}

	if (check_browser == false) {
		alert('亲爱的用户，您的浏览器版本太低，为了更好用户体验，请下载最新的浏览器');
		window.location.href='http://www.google.com/intl/zh-CN/chrome/'	;
	}

})(jQuery);




/**
 * 获取数据类型
 * @param {Object} 任意数据
 */
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


//cookie操作
//两个参数，一个是cookie的名子，一个是值
function setCookie(name,value) {
	var Days = 30; //此 cookie 将被保存 30 天
	var exp = new Date();    //new Date("December 31, 9998");
	exp.setTime(exp.getTime() + Days*24*60*60*1000);
	document.cookie = name + "="+ escape (value) + ";expires=" + exp.toGMTString()+ ";path=/;";

	+ "path=/;"	//设置cookie的保存路径
	+ "domain = cookieDomain";		//cookie的域名
}

//取cookies函数
function getCookie(name)  {
	var arr = document.cookie.match(new RegExp("(^| )"+name+"=([^;]*)(;|$)"));
	if(arr != null) return unescape(arr[2]); return null;
}

//取cookies函数
function delCookie(name) {//删除cookie
	var exp = new Date();
	exp.setTime(exp.getTime() - 1);
	var cval=getCookie(name);
	if(cval!=null) document.cookie= name + "="+cval+";expires="+exp.toGMTString() + ";path=/;";

}


// Num数据类型操作
/**
 * 格式化数字 (1)
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
 /**
 * 格式化数字(可以对小数进行四舍五入) (2)
 * @ num 数字
 * @ pattern ：
 * 用法:
 * formatNumber(12345.999,'#,##0.00');
 * formatNumber(12345.999,'#,##0.##');
 * formatNumber(123,'000000');
 */
function formatNumber(num,pattern){
  var strarr = num?num.toString().split('.'):['0'];
  var fmtarr = pattern?pattern.split('.'):[''];
  var retstr='';
  // 整数部分
  var str = strarr[0];
  var fmt = fmtarr[0];
  var i = str.length-1;
  var comma = false;
  for(var f=fmt.length-1;f>=0;f--){
    switch(fmt.substr(f,1)){
      case '#':
        if(i>=0 ) retstr = str.substr(i--,1) + retstr;
        break;
      case '0':
        if(i>=0) retstr = str.substr(i--,1) + retstr;
        else retstr = '0' + retstr;
        break;
      case ',':
        comma = true;
        retstr=','+retstr;
        break;
    }
  }
  if(i>=0){
    if(comma){
      var l = str.length;
      for(;i>=0;i--){
        retstr = str.substr(i,1) + retstr;
        if(i>0 && ((l-i)%3)==0) retstr = ',' + retstr;
      }
    }
    else retstr = str.substr(0,i+1) + retstr;
  }

  retstr = retstr+'.';
  // 处理小数部分
  str=strarr.length>1?strarr[1]:'';
  fmt=fmtarr.length>1?fmtarr[1]:'';
  i=0;
  for(var f=0;f<fmt.length;f++){
    switch(fmt.substr(f,1)){
      case '#':
        if(i<str.length) retstr+=str.substr(i++,1);
        break;
      case '0':
        if(i<str.length) retstr+= str.substr(i++,1);
        else retstr+='0';
        break;
    }
  }
  return retstr.replace(/^,+/,'').replace(/\.$/,'');
}
/**
 * 把有，分隔符的数字，还原成纯数字
 * @ s	 str 	字符串
 * return num 数字
 */
function rmoney(s)
{
   return parseFloat(s.replace(/[^\d\.-]/g, ""));
}



//CSS操作
/**
 * 获取对象CSS样式
 * @param obj obj			//对象
 * @param str attr			//属性
 */
function getStyle(obj,attr) {//对象，
	if (obj.currentStyle) {
		return obj.currentStyle[attr];	//IE模式
	} else {
		return getComputedStyle(obj,false)[attr];//FF模式
	}
}



//事件类
/**
 * 事件绑定
 * @param obj  obj		对象
 * @param str  sEv		事件类型
 * @param fn   fn			事件函数
 */
function myAddEvent(obj,sEv,fn) {//对象,
	if (obj.attachEvent) { //IE 下 (ie下有bug会把绑定事件的对象this指针指向window)
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



//DOM操作
/**
 * 	点击赋值元素里的内容(目前只支持IE)
 *	@obj   obj   触发事件的对象
 *  @obj2	obj  复制该元素里的内容
 *  return 	把obj2中的值，赋值到剪切板
 */
function CopyUrl(obj,obj2){
	obj2.value = obj.title;				//赋值
	obj2.select(); 						//选取对象
	js=obj2.createTextRange();  //执行赋值
	js.execCommand("Copy"); 	//执行赋值
}

/**
 * 表单筛选
 * @param {Object} form
 */
function form_set(form) {
	var parts = {};
	var filed = form
	switch (filed.type) {
		case undefined :
		case 'submit' :
		case 'reset' :
		case 'file' :
		case 'button' :
			break;
		case 'radio' :
		case 'checkbox' :
			if (!filed.selected) break;
		case 'select-one' :
		case 'select-multiple' :
			for (var j = 0; j < filed.options.length; j ++) {
				var option = filed.options[j];
				if (option.selected) {
					var optValue = '';
				if (option.hasAttribute) {	//非IE
					optValue = (option.hasAttribute('value') ? option.value : option.text);
				} else {	//IE兼容
					optValue = (option.attributes('value').specified ? option.value : option.text);
				}
					parts[filed.name] = optValue;
				}
			}
			break;
		default :
			parts[filed.name] = filed.value;
	}
	return parts;

}

//关闭当前窗口
function windowclose(gogo) {//URL
    var browserName = navigator.appName;
	//火狐处理，火狐跳转链接
	if(navigator.userAgent.toLowerCase().indexOf("firefox") != -1){
        window.location.href=gogo;
    } else {
		if (browserName=="Netscape") {//其他
        window.open('', '_self', '');
        window.close();
	    } else {
	        if (browserName == "Microsoft Internet Explorer"){//IE
	            window.opener = "whocares";
	            window.opener = null;
	            window.open('', '_top');
	            window.close();
	        }
	    }
	}
}

window.open('aaa.html', '_blank');
/**
 * select元素加首选
 * @objSelect obj  select元素对象
 * @optionValue  str option的value值
 * return 选中元素
 */
function SelectTrue(objSelect,optionValue){
     //判断是否存在
     var isExit = false;
     for(var i=0;i<objSelect.options.length;i++){
         if(objSelect.options[i].text == optionValue)  {
             objSelect.options[i].selected = true;
             isExit = true;
             break;
         }
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
//父级对象下，通过class选取对象
function getByClass(oParent,sClass) {//父级,className
	var aEle = oParent.getElementsByTagName('*');	//选取父级下所有元素
	var aResult = [];

	var re = new RegExp('\\b'+sClass+'\\b','i');	//匹配字符是否为独立单词

	for (var i=0;i<aEle.length;i++) {
		//如：box 在  box tab  中，正则匹配到，并且是个独立的单词
		if (re.test(aEle[i].className)) {//匹配
			aResult.push(aEle[i]);
		}
	}
	return aResult;
}



//功能算法
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



//Array操作
//把数组二添加到数组一中
 function appendArr(arr1,arr2) {
 	var i = null;
 	for (i=0;i<arr2.length;i++) {
 		arr1.push(arr2[i]);
 	}
 }



//获取URL后的参数
function getQueryString(name) {
	var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
	var r = window.location.search.substr(1).match(reg);
	if (r != null) return unescape(r[2]); return null;
}




/**
 *	对话框、提示框
 */
//onclick="return confirm('确定要删除吗？') ? true : false"

//window.location.href='?s=/User/groupUser/{$vo.id}'		//跳转
//history.back();		返回
//history.go(-1);		返回上一页刷新
//window.location.reload(); 	刷新
//close();关闭



/**
 * 系统函数
 * 基本数据类型 ：String、Nuber、布尔值
 * 内置对象：Global 全局对象(web的window对象)
 * 				 Math 	对象 (数学运算)
 */

/**
 * image对象
 * onload:图像加载完毕执行
 * onerror:图像加载失败执行
 */
window.onload = function () {

	var oImg = new Image();
	oImg.src = 'xxxx';
}


/**
 * 内置对象 Math	数学对象
 */
Math.min(1,2,3,4,5,6);			//求最小值
Math.max(1,2,3,4,5,6);			//求最大值
Math.ceil(25.1);					//向上舍入，取最大值
Math.floor(25.1);					//向下舍入，取最小值
Math.round(25.5);					//四舍五入
Math.random();						//生成随机数
function rand(start,end) {		//生成随机数函数
	var total = end - start + 1;
	return Math.floor(Math.random() *total + start);
}
1000.003.toFixed(2);			//保留小数点后面位数
Number($str);			//把字符串转换为数字



/**
 * Var 变量.是所属对象的一个属性，整体的关系就是个<作用链>。访问的时候。
 * 				从最近的对象查找这个属性，没有找到则从上一级开始找，依次类推。
 * 注意：不加var定义的变量，统一为全局变量
 * 			加了var定义的变量，会根据当前作用域指定为某个对象的变量
 */


/**
 * Object 对象
 * var box = new Object();		//new方法
 * var box = {};						//字面量方法
 */


/**
 * Array 数组对象方法
 * var box = new Array();		//new方法
 * var box = [];						//字面量
 */
var box = [];
box.unshift('1');			//数组开头，添加一个元素，返回数组最新长度		//ie返回的是undefined
box.push('1','2');			//数组尾端，添加N个元素，返回数组最新长度
box.shift();					//数组开头， 移除一个元素，返回移除的元素
box.pop();					//数组尾端，移除一个元素，返回移除的元素

//数组排序
box.sort(function (v1,v2) {		//V1，V2是数组中对象
	if (v1 < v2) {
		return -1;
	} else if (v1 > v2) {
		return 1;
	} else {
		return 0;
	}
});									//排序，从小到大
box.reverse();				//排序，从大到小
box.slice(1);					//获取元素，从下标1开始到最后一位  slice(1,3);
box.splice();					//获取、删除、插入、替换元素
/**
 * 对table进行排序
 * 对表单进行排序：
 * 1、对象转换为数组，
 * 2、对数组排序，
 * 3、重新插入html中
 */
arr.sort(function (v1,v2) {
	if (v1.cells[0].innerHTML > v2.cells[0].innerHTML) {
		return -1;
	} else if (v1.cells[0].innerHTML < v2.cells[0].innerHTML) {
		return 1;
	} else {
		return 0;
	}
});
/**
 * 判断一个值是否在数组中
 * @param {Object} value
 * @param {Object} arr
 */
function in_array(value,arr) {
	for (var i in arr) {
		if (value == arr[i]) {
			return true;
		}
	}
	return false;
}


/**
 * Date()时间对象
 */
var date = new Date(timestamp);		//返回当前时间。如果传入毫秒数，则返回毫秒数格式化的日期
date.getFullYear();			//取得日期年份
date.getMonth()+1;			//取得日期月份
date.getDate();				//获取日期天

date.getHours();			//时
date.getMinutes();			//分
date.getSeconds();  		//秒

date.toLocaleDateString();     //获取当前日期
date.toLocaleTimeString();     //获取当前时间
date.toLocaleString( );        //获取日期与时间

var now_date = date.getFullYear()+'-'+(date.getMonth()+1)+'-'+date.getDate()+' '+date.getHours()+':'+date.getMinutes()+':'+date.getSeconds()


//格式化需要的日期。需要类库Date.js支持
Date.format("Y-m-d",new Date(2007,(2-1),300));

//日期转为毫秒数,IE7支持不好
Date.parse('2007/4/5 11:20:30');			//日期转化为毫秒数 。返回格式：月/日/年。浏览器兼容性不好



/**
根据时间戳获取日期
*/
function get_date_by_timestamp (timestamp) {
  var date;
  if (timestamp != undefined) {
    date = new Date(timestamp);		//返回当前时间。如果传入毫秒数，则返回毫秒数格式化的日期
  } else {
    date = new Date();
  }

  date.getFullYear();			//取得日期年份
  date.getMonth()+1;			//取得日期月份
  date.getDate();				//获取日期天

  date.getHours();			//时
  date.getMinutes();			//分
  date.getSeconds();  		//秒

  date.toLocaleDateString();     //获取当前日期
  date.toLocaleTimeString();     //获取当前时间
  date.toLocaleString( );        //获取日期与时间

  var now_date = date.getFullYear()+'-'+(date.getMonth()+1)+'-'+date.getDate()+' '+date.getHours()+':'+date.getMinutes()+':'+date.getSeconds()

  return now_date;
}

/**
 * 格式化日期，成时间戳
 * @param {Object} $date_string 2013-10-10 12:13
 * return 111111111111
 */
function fomat_date ($date_string) {
	return Date.parse($date_string.replace(/-/ig,'/'));
}


/*得到2个日期之间相差的天数*/
function daysBetween(DateOne,DateTwo) //daysBetween('2009-11-15','2009-11-16')
{
    var OneMonth = DateOne.substring(5,DateOne.lastIndexOf ('-'));
    var OneDay = DateOne.substring(DateOne.length,DateOne.lastIndexOf ('-')+1);
    var OneYear = DateOne.substring(0,DateOne.indexOf ('-'));

    var TwoMonth = DateTwo.substring(5,DateTwo.lastIndexOf ('-'));
    var TwoDay = DateTwo.substring(DateTwo.length,DateTwo.lastIndexOf ('-')+1);
    var TwoYear = DateTwo.substring(0,DateTwo.indexOf ('-'));
  	//86400000L=24小时*60分钟*60秒*1000毫秒
    var cha=((Date.parse(TwoMonth+'/'+TwoDay+'/'+TwoYear) - Date.parse(OneMonth+'/'+OneDay+'/'+OneYear))/86400000);
    return cha;
}
/*取得年、月最后一天日期*/
 function getLastDay(year,month)
  {
   var new_year = year;		    //取当前的年份
   var new_month = month++;	//取下一个月的第一天，方便计算（最后一天不固定）
   if(month>12)           				 //如果当前大于12月，则年份转到下一年
   {
    new_month -=12;       			 //月份减
    new_year++;           				 //年份增
   }
   var new_date = new Date(new_year,new_month,1);                //取当年当月中的第一天
   return (new Date(new_date.getTime()-1000*60*60*24)).getDate();//获取当月最后一天日期
}


/**
* 格式化日期，成时间戳
* @param {Object} $date_string 2013-10-10 12:13:00
* return 111111111111
*/
System.prototype.fomat_date = function ($date_string) {
	//var string_date = $date_string.replace(/-/ig,'/');
	var string_date = $date_string;

	var arr_date =  string_date.split(' ');
	//年月日
	var arr_Year_Month_Date = arr_date[0].split('-');
	//时分秒
	var arr_Hours_Minutes_Seconds = arr_date[1].split(':');

	var Year,Month,DateNum,Hours,Minutes,Seconds;
	Year = arr_Year_Month_Date[0];
	Month = arr_Year_Month_Date[1];
	DateNum = arr_Year_Month_Date[2];
	Hours = arr_Hours_Minutes_Seconds[0];
	Minutes = arr_Hours_Minutes_Seconds[1];
	Seconds = arr_Hours_Minutes_Seconds[2];

	var obj_date = new Date();

	//日月年
	obj_date.setFullYear(Year);
	obj_date.setMonth(Month);
	obj_date.setDate(DateNum);

	//时分秒
	obj_date.setHours(Hours);
	obj_date.setMinutes(Minutes);
	obj_date.setSeconds(Seconds);
	obj_date.setMilliseconds(0);//毫秒

	//var now_date = obj_date.getFullYear()+'-'+(obj_date.getMonth())+'-'+obj_date.getDate()+' '+obj_date.getHours()+':'+obj_date.getMinutes()+':'+obj_date.getSeconds();
	return obj_date.getTime();
}

/**
根据时间戳获取日期
*/
System.prototype.get_date_by_timestamp = function  (timestamp) {
	var date;
	if (timestamp != undefined) {
		date = new Date(timestamp);		//返回当前时间。如果传入毫秒数，则返回毫秒数格式化的日期
	} else {
		date = new Date();
	}

	date.getFullYear();			//取得日期年份
	date.getMonth()+1;			//取得日期月份
	date.getDate();				//获取日期天

	date.getHours();			//时
	date.getMinutes();			//分
	date.getSeconds();  		//秒

	date.toLocaleDateString();     //获取当前日期
	date.toLocaleTimeString();     //获取当前时间
	date.toLocaleString();        //获取日期与时间

	var now_date = date.getFullYear()+'-'+(date.getMonth())+'-'+date.getDate()+' '+date.getHours()+':'+date.getMinutes()+':'+date.getSeconds()
	return now_date;
}

function addMonth(month) {

	var date = "2016-01"
	// 日期对象
	var d = new Date(date);

	// 设置日期, 增加 1 一个月
	d.setMonth(d.getMonth() + 1)
	// 设置日期, 日期 增加 1 天
	d.setDate(d.getMonth() + 1)


	var year = d.getFullYear();
	var month = d.getMonth() + 1;

	if(month<10){
		month = "0"+month;
	}

	alert(year);		//取得日期年份
	alert(month);
}



/*
 * 定时器函数
 */
 //开启定时器
 obj.timer=setInterval(function (){
	//这里是执行代码
}, 15);
setTimeout(function () {
},15)

//关闭定时器
clearInterval(obj.timer);


/**
 * 正则表达式
 * 注：[a-z]+只匹配一个字符。如果有多个字符，则需要加上元字符修饰
 * 		([a-z]+)匹配一个字符串组
 * 		| 分割符，必须用分组符号()包装起来
 * 		i:不区分大小写  g:全局  m:匹配换行  可以组合在一起使用
 */
var ze = /构架/ig;
var str = '我要成为一个构架我师';
str.match(ze);										//匹配正则 		return 数组
str.replace(ze,'架构');								//匹配替换		return 替换完毕的字符串
str.split(',')	;											//字符串转换成数组   return Array
arr.join("")	;											//数组转换为字符串	 return Str

str.indexOf('d');										//查找字符，在字符串中第一次出现的位置
str.substr(1,3);										//截取字符串，从n位置，截取m个字符

//分组() $1	 $2 ...
var ze = /8(.*)8/;									//匹配正则
var str = 'This is a 8google8';
str.replace(ze,'<strong>$1</strong>');	//把匹配到的规则替换成指定字符
str.replace(ze,'$2 $1');							//互换位置

var ze = /^[\u4E00-\u9FA5\uf900-\ufa2d]{1,6}$/;	//中文字符验证
var ze = /^([\u4E00-\u9FA5\uf900-\ufa2dA-Za-z]){1,20}$/	//中英文字符

var re = /(^[0-4]{1}\.[0-9]{1}$)|(^[0-5]{1}\.[0]{1}$)/;

//匹配邮政编码
function postal(str) {
	var pattern = /^[1-9][0-9]{5}$/gim;
	return str.match(pattern);
}
//匹配压缩文件
function zip(str) {
	var pattern = /^[\w\-\_]+\.(zip|rar|hz){1}$/gim;
	return str.match(pattern);
}
//匹配邮箱
function email(str) {
	var pattern = /^([\w\.\-]+)\@([\w]+)\.([a-z]{3})$/gim;
	return str.match(pattern);
}
//去除所有空格
function unTrim(str) {
	var pattern = /(\s+)/gim;
	return str.replace(pattern,'');
}
//去除左右的空格
function unTrim(str) {
	var left = /^(\s+)/gim;
	var str = str.replace(left,'a');
	var right = /(\s+)$/;
	return str.replace(right,'z');
}


/**
 * Function 对象
 * var box = new Function('num1','num2','return num1 + num2');	//new 方法
 * var box = function () {  }																//字面量方法
 * function box () { }																		//通用方法
 *	 注：函数本身也可以当做参数传递
 *	 	  函数运行时，始终有个指针(即this)，始终指向一个对象。
 		  函数运行时，会开辟一个属于本身的作用域，叫做作用域链
 */
function box(num1,num2) {
	return num1 + num2;
}
arguments.callee();				//调用自身的函数。相当于PHP中的self方法。可以用于递归的算法
box.prototype							//函数原型

box.apply('对象(即this指向的对象)','参数');			//为函数体指向一个新的对象，或者是说改变函数体的作用域   	如：sum.apply(this,arguments);
box.call(this,num1,num2);							//与apply()相同，不同的是参数要一个个传输

//回调函数
function aaa(fn) {
	var a=1;
	if (fn){fn(a)};	//这里传入要执行的函数名
}
aaa(function (zzz) {
	alert(zzz);		//这里定义函数的流程体
});



/**
 * OPP
 * 构造函数:
 * 1.构造函数没有new Object() 但是后台会自动去new一个对象区域出来
 * 2.构造函数定义，第一个字母必须是大写.
 * 3.调用时，必须new 构造函数
 */
function Box(name,age) {
	this.name = name;
	this.age = age;
	this.run = function () {
		return this.name + '.' + this.age;
	};
}
var box1 = new Box('林',22);
box1.run();


Box.prototype.showName = function () {//添加方法
	alert(this.name);
}




/**
 * 功能模块
 */

//获取经纬度(google)
(function () {
	$('.address_a').blur(function () {
		codeAddress(this.value);
	});
	function codeAddress(address)  {
		var  geocoder =new google.maps.Geocoder();
		geocoder.geocode({ 'address': address }, function(results, status) {
			if(status == google.maps.GeocoderStatus.OK)  {
				//赋值
				$("input[name='lat']").val(results[0].geometry.location.lat()) ;
				$("input[name='lng']").val(results[0].geometry.location.lng()) ;
			} else {
				alert("对不起没有找到此地址:"+ status);
				$("input[name='lat']").val('') ;
				$("input[name='lng']").val('') ;
			}
		});
	}
})();


//获取经纬度(百度)
var baidu_map = function (address) {
	var myGeo = new BMap.Geocoder();
	var result = {};
	myGeo.getPoint(address, function (point) {
         if (point) {
		 	result.status = true;
			result.msg = '获取成功';
			result.lng = point.lng;
			result.lat = point.lat;
         }else{
        	result.status = false;
			result.msg = '获取失败！';
			return result;
         }
    });
	return result;
}
