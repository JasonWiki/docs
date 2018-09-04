$(function (){


//函数
//var str = 'abcdefg';	
//var str2 = '你';
//str.search('d');			//查找字符，在字符串中第一次出现的位置，如果没有返回-1,建议正则内使用
//str.indexOf('d');			//查找字符，在字符串中第一次出现的位置。	
//str.lastIndexOf('d');		//查找字符，在字符串中最后次出现的位置。
//str.charAt(0);				//查找某个位置上的字符
//str.slice(1,3);				//截取字符串，从n位置，窃取m-1位置的字符。如果只给一个参数，则取第n个位置到字符串结束的字符。如果是负数，则从最后一个位置开始截取
//str.substring(1,3);		//截取字符串，从n位置，窃取m-1位置的字符。如果只给一个参数，则取第n个位置到字符串结束的字符
//str.substr(1,3);				//截取字符串，从n位置，截取m个字符
//str.split('-');					//按照某种字符，把字符串切割成数组
//str.localeCompare(str2);   	//按照本地语言风格，对二个字符串进行比较
//str.toLowerCase();				//转换成小写
//str.toLocaleUpperCase();	//转换成大写	


//.esarch(正则)	查找匹配到的字符 return 出现的位置
//var str = window.navigator.userAgent;
//var s = str.search(/firefox/i) ;		//在字符串中，搜索是否包含正则内的字符


//.match(正则) 查找匹配到的字符 return 匹配到的字符
//var str = 'qwe 1231 qwesd 55';
//var re = /\d+/g;				//匹配一个或多个数字，全局匹配
//alert(str.match(re));

//.replace() 把查找匹配到的字符,替换成自定义字符 return 一个新的字符串
//var str = 'abcaeft';
//var re = /a/g;
//alert(str.replace(re,'T'));


//元字符
//[ ] : 匹配一个，包含在括号内的字符
//[^ ] : 匹配一个，不包含在括号内的字符
//var str = 'weqjkql1q1kwlk1q1eqeq1q1';
//var re = /1[a-z]1/g;			
//alert(str.match(re));

//^ 在[ ] 中表示排除某个字符
//^ 在[]  外表示匹配行首

//正则有个特点，当匹配一个规则时，如果这个规则中有一部分匹配，则还是返回true。
//要全部匹配的话，要在规则前加上，行首^行尾$都匹配


	




}); 