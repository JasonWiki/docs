 function Ajax (url,fnContent,fnReturn) {	//文件地址，返回内容，返回错误
 		var oAjax = '';
 	//1.创建对象：以文本或者一个 DOM 文档形式返回内容
 		if (window.XMLHttpRequest) {	//如果这个属性存在
 			oAjax = new XMLHttpRequest();									//FF模式
 		} else {
 			oAjax = new ActiveXObject("Microsoft.XMLHTTP");	//IE6兼容模式
 		}
 		
 	//2.连接服务器：
 		//open(方法,url,是否异步) 同步：一件一件事情做。 异步：多件事情一起做
 		oAjax.open('GET',url,true);
 		
 	//3.发送请求
 		oAjax.send();
 		
 	//4.接收服务器返回信息	当Ajax与服务器建立连接的时候，触发这个方法
 		oAjax.onreadystatechange = function () {
 			/*readyState属性请求结果
 			 * 	0:初始化状态。XMLHttpRequest 对象已创建或已被 abort() 方法重置
 			 * 1:	open() 方法已调用，但是 send() 方法未调用。请求还没有被发送。
 			 * 2:	Send() 方法已调用，HTTP 请求已发送到 Web 服务器。未接收到响应。
 			 * 3:	所有响应头部都已经接收到。响应体开始接收但未完成。
 			 * 4:	HTTP 响应已经完全接收。代表服务器返回结束，但是不代表是否成功
 			 */
 			if (oAjax.readyState == 4) {	
 				switch (oAjax.status) { //接收服务器返回值
 					case 200 : //成功
 						fnContent (oAjax.responseText);//函数传参
 						break;
 					default :	//失败
 						 if (fnReturn) {
 						 	fnReturn(oAjax.status);//函数传参
 						 }
 				}
 			}
 		}			

 }
 
 
 
 
$.ajaxSetup({
			async: false,//async:false 同步请求  true为异步请求
});
 
/**
 *  jQuery方式
 */
	//  GET方式
	var id = this.value;
	var ul = "http://localhost/home.php?s=/Home/User/ajax/";
	$.get(ul,{
		id : id , 
		name:'wade'
	},function(obj){
		oContent.value = obj;
	},'text');//返回的数据格式有：xml, html, script, json, text, _default。
	
	
	//	POST方式
	var id = this.value;
	var ul = "http://localhost/home.php?s=/Home/User/ajax/";
	//提交的地址，post传入的参数
	$.post(ul,{
		id:id	,
		 name:'wade'
	},function(obj){
		oContent.value = obj;
	},'text');	
 
 