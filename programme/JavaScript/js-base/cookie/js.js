//设置Cookie
function setCookie (name,value,iDay) {	
	var oDate = new Date();
	//.setDate计算N天后的日期 ( getDate获取当前日期 + 天数)
	oDate.setDate(oDate.getDate() + iDay);//设置过期日期
	document.cookie = name + '=' + value + ';expires=' +oDate;
	//document.cookie = 'user=wade;expires=10';//内容与过期天数
}


//获取Cookie
function getCookie (name) {					
	var arr = document.cookie.split('; ');//切割成数组
	var i = '';
	for (i = 0;i<arr.length;i++) {					//再次切割
		var arr2 = arr[i].split('=');
		if (arr2[0] == name) {
			return arr2[1];
		}
	}
	return '';
}

//删除 Cookie
function removeCookie(name) {
	setCookie(name,'1',-1);
}

setCookie('wade','man',1);
setCookie('pass','man',10);
removeCookie('wade');
alert(document.cookie);
