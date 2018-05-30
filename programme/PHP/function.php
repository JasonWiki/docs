<?php

/**
 * 系统语法
 */
eval('$this->'.$this->_m.'();');
const BCS_SDK_ACL_EFFECT_DENY = "deny";
static $ACL_EFFECTS = 'aaa';
self::$ACL_EFFECTS;
$empty_object = new stdClass();		//创建空对象
sleep(2);		//延迟执行

gettype($var);				//获取数据类型
settype($var, $type);	//设置数据的数据类型

intval(12313);

print_r(get_class_methods($this));	//获取当前类的所有方法

//引入时，注意各文件的与类名的大小写。
function __autoload($_className) {//返回实例化后，但是没有被引入的类名
	if (substr($_className,-6) == 'Action') {//类名后面6位是Action
		require ROOT_PATH.'/action/'.$_className.'.class.php';//业务逻辑
	} else if (substr($_className,-5) == 'Model') {
		require ROOT_PATH.'/model/'.$_className.'.class.php';//数据层
	} else {
		require ROOT_PATH.'/includes/'.$_className.'.class.php';//系统核心类
	}
}//print_r(get_included_files() );//返回被引用的文件


/**
 * 参数过滤
 */

/**
 * 判断是否为post提交
 * @$value  post提交的值
 * return 布尔值
*/
function isPost($value) {
	//是post提交 ，并且post值存在，或者post值不为空
	if ($_SERVER['REQUEST_METHOD'] == 'POST' && count($value) && !empty($value)) {
		return true;
	} else {
		return false;
	}
}
/**
 * 2.预防SQL注入，转义非法字符
 * @param unknown_type $str
 * @return Ambigous <unknown, string>
 */
function setString($str) {
	return get_magic_quotes_gpc() ? $str : addslashes($str);
}
/**
 * 3.把转译的字符返回没有转义前的样子
 * @param string $str
 * @return string
 */
function unSetString($str) {
	return stripslashes($str);
}
//4.预防SQL注入
function setSql($_str) {
	if (is_array($_str)) {	//数组
		foreach ($_str as $_key => $_value) {
			$_string[$_key] =  setSql($_value);
		}
	} else if (is_object($_str)) {	//对象
		foreach ($_str as $_key => $_value) {
			$_string->$_key =  setSql($_value);
		}
	} else {	//字符串
		$_string = setString($_str);
	}
	return $_string;
}

/**
 * 5.把HTML标签转换为字符串
 * @param Array、Object、Str  $_date
 * @return Array、Object、Str
 */
function htmlString ($_date) {
	if (is_array($_date)) {
		foreach ($_date as $_key => $_value) {
			$_string[$_key] =  htmlString($_value);
		}
	} else if (is_object($_date)) {
		foreach ($_date as $_key => $_value) {
			$_string->$_key =  htmlString($_value);
		}
	} else {
		$_string = htmlspecialchars($_date);
	}
	return $_string;//传入的是对象，返回对象、是数组，返回数组、是字符串则返回字符串
}
/**
 * 6.把htmlString 转换的字符串，重新转换为HTML标签
 * @param string $_str
 * @return string	//HTML字符串
 */
function unHtmlString($_str) {
	if (is_array($_str)) {
		foreach ($_str as $_key => $_value) {
			$_string[$_key] =  unHtmlString($_value);
		}
	} else if (is_object($_str)) {
		foreach ($_str as $_key => $_value) {
			$_string->$_key =  unHtmlString($_value);
		}
	} else {
		$_string = htmlspecialchars_decode($_str);
	}
	return $_string;
}

/**
 * 3、清理URL中不需要的变量
 * @param array $array 必须是数组格式：array('a','b');
 * @return str 清理过后的URL
 */
function clearUrlVal(Array $array) {
	$_url = $_SERVER['REQUEST_URI'];//获取当前运行页面的地址栏中的所有信息，如：/cms/feedback.php?cid=32&page=1
	$_par = parse_url($_url);
	if (isset($_par['query'])) {
		parse_str($_par['query'],$_query);//解析地址栏字符串，保存在数组中
		foreach($array as $value) {
			unset($_query[$value]);
		}
		$_url = $_par['path'].'?'.http_build_query($_query);//重组URL
	}
	return $_url;
}

urlencode();	//URL编码
urldecode();	//解码




/**
 * 文件处理
 */
//文件函数
is_dir($dir);												//判断目录是否存在
file_exists($file);										//判断文件是否存在
filemtime($file);										//计算文件修改时间
mkdir($dirName);									//创建目录
file_put_contents('text.txt','123'.PHP_EOL,FILE_APPEND | LOCK_EX);	//追加到文件
file_get_contents('text.txt');					//读取文件内容//file_get_contents("http://www.baidu.com/index.php");或者打开远程文件

/**
 * xml处理Simple
 */
$object = simplexml_load_file('文件地址');
$object = simplexml_load_string('xml数据');
$object->xpath('/root/taglib');	//获取某个几点的数据

/**
 *1. 根据字节大小，计算相应的大小
 * 字节格式化 把字节数格式为 B K M G T 描述的大小
 * @return string
 */
function byte_format($size, $dec=2) {
	$a = array("B", "KB", "MB", "GB", "TB", "PB");
	$pos = 0;
	while ($size >= 1024) {
		$size /= 1024;
		$pos++;
	}
	return round($size,$dec)." ".$a[$pos];
}

/**
 * 2、删除目录和该目录下的所有文件
 * $dirname 文件目录 如："/lamp/apache/htdocs/CMS/uploads/20120917"
* 	return 布尔值
*/
function deleteDir($dirname) {
	if (!is_dir($dirname)) return false;	//如果不是目录退出，不执行了
	if (!$handle = opendir($dirname)) return false;//打开一个目录
	//readdir(目录句柄) 		返回目录中的文件和文件名.	return string字符串形式
	//scandir(目录)			返回目录中的文件和文件名. return array  数组形式
	while (($_file = readdir($handle)) != false) { //目录不是空则，列出目录中的所有文件
		if ($_file != '.' && $_file !='..' ) {
			$_dir = $dirname.'/'.$_file;
			is_dir($_dir) ? deleteDir($_dir) : unlink($_dir);
		}
	}
	closedir($handle);			//打开目录要关闭目录
	return rmdir($dirname);//删除目录，返回布尔值
}

/**
 * 3、删除指定目录下的所有文件，保留目录
 * 	$_dirName  目录路径
* 	return 删除文件是否成功  布尔值
*/
function deleteUrlFile($_dirName) {
	if (!is_dir($_dirName)) return false;		//是不是正确路径
	if (!$_dir = opendir($_dirName)) return false;	//打开目录
	while (($_file = readdir($_dir)) != false) {			//目录不是空则，列出目录中的所有文件
		if ($_file == '.' || $_file == '..') continue;			//跳出此循环
		unlink($_dirName.'/'.$_file);							//删除目录中所有文件
	}
	closedir($_dir);
	return true;
}

/**
 * 4、删除一个文件
 * $fileUrl 文件路径  如:/lamp/apache/htdocs/CMS/uploads/20120917/20120917203535645.jpg
* return 删除文件是否成功  布尔值
*/
function deleteFile($_fileUrl) {
	if (!file_exists($_fileUrl)) return false;
	return unlink($_fileUrl);//删除文件，返回布尔值
}

/**
 * 2、获取当前运行脚本文件名
 * $_name 	//传入需要生成的文件类型。如：.tpl
 * return 		新的文件名
 */
function UrlName($_name = null) {
	$_str  = explode('/',$_SERVER['SCRIPT_NAME']);//获取当前运行页面的地址，并且分割成数组
	$_num =  $_str[count($_str)-1];//获取数组最后一位的值(文件名)
	$_str = explode('.',$_num);		//把文件名分割成数组
	return $_str[0].$_name;			//返回规定的文件名
	/*方法二:
	 $_url = basename(__file__);	//获取当前路径文件名部分
	$_array = explode('.',$_url);	//分割字符串为数组
	return $_array[0].$_name;		//重组文件名
	*/
}



/**
 * HTTP请求
 */

/**
 *1、 获取客户端IP地址
 * @param integer $type 返回类型 0 返回IP地址 1 返回IPV4地址数字
 * @return mixed
 */
function get_client_ip($type = 0) {
	$type       =  $type ? 1 : 0;
	static $ip  =   NULL;
	if ($ip !== NULL) return $ip[$type];
	if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		$arr    =   explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
		$pos    =   array_search('unknown',$arr);
		if(false !== $pos) unset($arr[$pos]);
		$ip     =   trim($arr[0]);
	}elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
		$ip     =   $_SERVER['HTTP_CLIENT_IP'];
	}elseif (isset($_SERVER['REMOTE_ADDR'])) {
		$ip     =   $_SERVER['REMOTE_ADDR'];
	}
	// IP地址合法验证
	$long = sprintf("%u",ip2long($ip));
	$ip   = $long ? array($ip, $long) : array('0.0.0.0', 0);
	return $ip[$type];
}

/**
 *4、 向浏览器发送HTTP状态。用于页面报错
 * @param integer $code 状态码
 * @return void
 */
function send_http_status($code) {
	static $_status = array(
			// Success 2xx
			200 => 'OK',
			// Redirection 3xx
			301 => 'Moved Permanently',	//客户试图访问的资源已移到新的永久位置
			302 => 'Moved Temporarily',  // 1.1 搬到临时
			// Client Error 4xx
			400 => 'Bad Request',		//坏的请求
			403 => 'Forbidden',			//无权访问
			404 => 'Not Found',			//网页不存在
			// Server Error 5xx
			500 => 'Internal Server Error',		//服务器维护
			503 => 'Service Unavailable',		//服务器错误
	);
	if(isset($_status[$code])) {
		header('HTTP/1.1 '.$code.' '.$_status[$code]);
		// 确保FastCGI模式下正常
		header('Status:'.$code.' '.$_status[$code]);
	}
}

/**
 * 客户端向服务器、HTTP请求Request时的header报文数据
 * @return array
 */
function HttpHeaderRequest() {
	$headers = array();
	foreach ($_SERVER as $key => $value) {
		if ('HTTP_' == substr($key, 0, 5)) {
			$headers[str_replace('_', '-', substr($key, 5))] = $value;
		}
		if (isset($_SERVER['PHP_AUTH_DIGEST'])) {
			$header['AUTHORIZATION'] = $_SERVER['PHP_AUTH_DIGEST'];
		} elseif (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {
			$header['AUTHORIZATION'] = base64_encode($_SERVER['PHP_AUTH_USER'] . ':' . $_SERVER['PHP_AUTH_PW']);
		}
		if (isset($_SERVER['CONTENT_LENGTH'])) {
			$header['CONTENT-LENGTH'] = $_SERVER['CONTENT_LENGTH'];
		}
		if (isset($_SERVER['CONTENT_TYPE'])) {
			$header['CONTENT-TYPE'] = $_SERVER['CONTENT_TYPE'];
		}
	}
	return $headers;
}

/**
 * 服务器向客户端 Http(response)响应时的header报文数据
 */
function HttpHeaderResponse() {
	$list = headers_list();
	$info = array();
	foreach ($list AS $key=>$val) {
		$display = getLeftRigthStr($val,':');
		$info[$display['left']] = $display['right'];
	}
	return $info;
}
header('AUTHORIZATION:123');		//自定义设置报文内容
$headers_list = headers_list();			//获取服务器响应时的header数据列表

/*
 * 模拟post请求	 stream
* post.php
*  $url = "http://localhost/demo/p_test.php";
*  $str = "a=1&b=2";			//请求字符串，可以是任何种类的字符串。
*  $result = Request_post($url, $str,$res);

* server.php 服务器端取得post请求的数据
* file_get_contents("php://input");		//获取请求的所有数据
	$GLOBALS['HTTP_RAW_POST_DATA'] 获取所有请求
*/
function RequestPost ($url,$content,&$response) {
	$content_length = strlen($content);	//计算字符长度
	//请求信息
	$options = array(
		 'http'=>array(
		 		'method' => 'POST',//请求类型
		 		//文件信息
		 		'header' =>
		 		"Content-type: application/x-www-form-urlencoded\r\n" .	//application/json
		 		"Command-length: $content_length\r\n" .
		 		"Content-length: $content_length\r\n",
		 		'content' => $content
		 ),
	);
	//发送请求
	$context = stream_context_create($options);
	//获取请求数据
	return file_get_contents($url, false, $context);
}
$url = "http://localhost/demo/p_test.php";//请求地址
$str = "a=1&b=2"; 	//请求数据体
$result = RequestPost($url, $str,$res);
echo $result;

/**
 * POST提交---curl
 * @param String $content 推送内容
 * @param String $_URL
 *
 echo send_gcm_notify($info,'AIzaSyAns4Fqv5imCCd6qNILFG1wj0CZ8rw7b6Q',$url);
 */
function CURL_POST($content,$_URL='https://android.googleapis.com/gcm/send') {

	$headers = array(
			'Authorization: key=' . $_GOOGLE_API_KEY,
			'Content-Type: application/json'		//application/x-www-form-urlencoded(表单)  multipart/form-data(原始数据)
	);

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $_URL);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);	//头文件信息
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $content);	//请求信息

	$result = curl_exec($ch);
	if ($result === FALSE) {
		die('Problem occurred: ' . curl_error($ch));
	}

	curl_close($ch);

	return $result;
}

/**
 * 模拟post 提交
 * @param Array $post_data
 * @param String $url
 * @return bool
 */
function php_post ($post_data,$url) {
    $o="";
    foreach ($post_data as $k=>$v)
    {
        $o.= "$k=".urlencode($v)."&";
    }

    $post_data=substr($o,0,-1);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_URL,$url);
    //为了支持cookie
    //curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookie.txt');
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);

    $result = curl_exec($ch);
    if ($result === FALSE) {
        die('Problem occurred: ' . curl_error($ch));
    }
    curl_close($ch);

    return $result;
}


/**
 * 常用函数
 */
//PHP跳转
URL:header("Location: http://bbs.lampbrother.net");

//1、弹窗返回
function alertBack($_info) {
	echo "<script type='text/javascript'>alert('$_info');history.back();</script>";
	exit();
}

//2、弹窗关闭
function alertClose($_info) {
	echo "<script type='text/javascript'>alert('$_info');close();</script>";
	exit();

}

//3、弹窗跳转
function alertLocation ($_info,$_url) {
	if (!empty($_info)) {
		echo "<script type='text/javascript'>alert('$_info');location.href='$_url';</script>";
		exit();
	} else {
		header('Location:'.$_url);
		exit();
	}
}

//4、退出，清除SESSION
function UnSession () {
	if (session_start()) {

		session_unset();			//清空session
		if (isset($_SESSION)) {
			unset($_SESSIONI);	//注销$_SESSION
		}
		session_destroy();
	}
}

//5、生成唯一标示符
function _uniqid() {
	return md5(uniqid(mt_rand(1,9999),true));//根据随机数与时间，再加密，生成唯一不会重复的 32位字符串
}

/**
 * 6、产生随机字串，可用来自动生成密码 默认长度6位 字母和数字混合
 * @param string $len 长度
 * @param string $type 字串类型
 * 0 字母 1 数字 其它 混合
 * @param string $addChars 额外字符
 * @return string
 */
function rand_string($len=6,$type='',$addChars='') {
	$str ='';
	switch($type) {
		case 0:
			$chars='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'.$addChars;
			break;
		case 1:
			$chars= str_repeat('0123456789',3);
			break;
		case 2:
			$chars='ABCDEFGHIJKLMNOPQRSTUVWXYZ'.$addChars;
			break;
		case 3:
			$chars='abcdefghijklmnopqrstuvwxyz'.$addChars;
			break;
		case 4:
			$chars = "们以我到他会作时要动国产的一是工就年阶义发成部民可出能方进在了不和有大这主中人上为来分生对于学下级地个用同行面说种过命度革而多子后自社加小机也经力线本电高量长党得实家定深法表着水理化争现所二起政三好十战无农使性前等反体合斗路图把结第里正新开论之物从当两些还天资事队批点育重其思与间内去因件日利相由压员气业代全组数果期导平各基或月毛然如应形想制心样干都向变关问比展那它最及外没看治提五解系林者米群头意只明四道马认次文通但条较克又公孔领军流入接席位情运器并飞原油放立题质指建区验活众很教决特此常石强极土少已根共直团统式转别造切九你取西持总料连任志观调七么山程百报更见必真保热委手改管处己将修支识病象几先老光专什六型具示复安带每东增则完风回南广劳轮科北打积车计给节做务被整联步类集号列温装即毫知轴研单色坚据速防史拉世设达尔场织历花受求传口断况采精金界品判参层止边清至万确究书术状厂须离再目海交权且儿青才证低越际八试规斯近注办布门铁需走议县兵固除般引齿千胜细影济白格效置推空配刀叶率述今选养德话查差半敌始片施响收华觉备名红续均药标记难存测士身紧液派准斤角降维板许破述技消底床田势端感往神便贺村构照容非搞亚磨族火段算适讲按值美态黄易彪服早班麦削信排台声该击素张密害侯草何树肥继右属市严径螺检左页抗苏显苦英快称坏移约巴材省黑武培著河帝仅针怎植京助升王眼她抓含苗副杂普谈围食射源例致酸旧却充足短划剂宣环落首尺波承粉践府鱼随考刻靠够满夫失包住促枝局菌杆周护岩师举曲春元超负砂封换太模贫减阳扬江析亩木言球朝医校古呢稻宋听唯输滑站另卫字鼓刚写刘微略范供阿块某功套友限项余倒卷创律雨让骨远帮初皮播优占死毒圈伟季训控激找叫云互跟裂粮粒母练塞钢顶策双留误础吸阻故寸盾晚丝女散焊功株亲院冷彻弹错散商视艺灭版烈零室轻血倍缺厘泵察绝富城冲喷壤简否柱李望盘磁雄似困巩益洲脱投送奴侧润盖挥距触星松送获兴独官混纪依未突架宽冬章湿偏纹吃执阀矿寨责熟稳夺硬价努翻奇甲预职评读背协损棉侵灰虽矛厚罗泥辟告卵箱掌氧恩爱停曾溶营终纲孟钱待尽俄缩沙退陈讨奋械载胞幼哪剥迫旋征槽倒握担仍呀鲜吧卡粗介钻逐弱脚怕盐末阴丰雾冠丙街莱贝辐肠付吉渗瑞惊顿挤秒悬姆烂森糖圣凹陶词迟蚕亿矩康遵牧遭幅园腔订香肉弟屋敏恢忘编印蜂急拿扩伤飞露核缘游振操央伍域甚迅辉异序免纸夜乡久隶缸夹念兰映沟乙吗儒杀汽磷艰晶插埃燃欢铁补咱芽永瓦倾阵碳演威附牙芽永瓦斜灌欧献顺猪洋腐请透司危括脉宜笑若尾束壮暴企菜穗楚汉愈绿拖牛份染既秋遍锻玉夏疗尖殖井费州访吹荣铜沿替滚客召旱悟刺脑措贯藏敢令隙炉壳硫煤迎铸粘探临薄旬善福纵择礼愿伏残雷延烟句纯渐耕跑泽慢栽鲁赤繁境潮横掉锥希池败船假亮谓托伙哲怀割摆贡呈劲财仪沉炼麻罪祖息车穿货销齐鼠抽画饲龙库守筑房歌寒喜哥洗蚀废纳腹乎录镜妇恶脂庄擦险赞钟摇典柄辩竹谷卖乱虚桥奥伯赶垂途额壁网截野遗静谋弄挂课镇妄盛耐援扎虑键归符庆聚绕摩忙舞遇索顾胶羊湖钉仁音迹碎伸灯避泛亡答勇频皇柳哈揭甘诺概宪浓岛袭谁洪谢炮浇斑讯懂灵蛋闭孩释乳巨徒私银伊景坦累匀霉杜乐勒隔弯绩招绍胡呼痛峰零柴簧午跳居尚丁秦稍追梁折耗碱殊岗挖氏刃剧堆赫荷胸衡勤膜篇登驻案刊秧缓凸役剪川雪链渔啦脸户洛孢勃盟买杨宗焦赛旗滤硅炭股坐蒸凝竟陷枪黎救冒暗洞犯筒您宋弧爆谬涂味津臂障褐陆啊健尊豆拔莫抵桑坡缝警挑污冰柬嘴啥饭塑寄赵喊垫丹渡耳刨虎笔稀昆浪萨茶滴浅拥穴覆伦娘吨浸袖珠雌妈紫戏塔锤震岁貌洁剖牢锋疑霸闪埔猛诉刷狠忽灾闹乔唐漏闻沈熔氯荒茎男凡抢像浆旁玻亦忠唱蒙予纷捕锁尤乘乌智淡允叛畜俘摸锈扫毕璃宝芯爷鉴秘净蒋钙肩腾枯抛轨堂拌爸循诱祝励肯酒绳穷塘燥泡袋朗喂铝软渠颗惯贸粪综墙趋彼届墨碍启逆卸航衣孙龄岭骗休借".$addChars;
			break;
		default :
			// 默认去掉了容易混淆的字符oOLl和数字01，要添加请使用addChars参数
			$chars='ABCDEFGHIJKMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789'.$addChars;
			break;
	}
	if($len>10 ) {//位数过长重复字符串一定次数
		$chars= $type==1? str_repeat($chars,$len) : str_repeat($chars,5);
	}
	if($type!=4) {
		$chars   =   str_shuffle($chars);
		$str     =   substr($chars,0,$len);
	}else{
		// 中文随机字
		for($i=0;$i<$len;$i++){
			$str.= msubstr($chars, floor(mt_rand(0,mb_strlen($chars,'utf-8')-1)),1);
		}
	}
	return $str;
}

/**
 * 获取登录验证码 默认为4位数字 ，与随机字符串函数配合使用
 * @param string $fmode 文件名
 * @return string
 */
function build_verify ($length=4,$mode=1) {
	return rand_string($length,$mode);
}
/**
 *	代码输出，详细信息
 * @param mixed $var 变量
 * @param boolean $echo 是否输出 默认为True 如果为false 则返回输出字符串
 * @param string $label 标签 默认为空
 * @param boolean $strict 是否严谨 默认为true
 * @return void|string
 */
function dump($var, $echo=true, $label=null, $strict=true) {
	$label = ($label === null) ? '' : rtrim($label) . ' ';
	if (!$strict) {
		if (ini_get('html_errors')) {
			$output = print_r($var, true);
			$output = '<pre>' . $label . htmlspecialchars($output, ENT_QUOTES) . '</pre>';
		} else {
			$output = $label . print_r($var, true);
		}
	} else {
		ob_start();
		var_dump($var);
		$output = ob_get_clean();
		if (!extension_loaded('xdebug')) {
			$output = preg_replace('/\]\=\>\n(\s+)/m', '] => ', $output);
			$output = '<pre>' . $label . htmlspecialchars($output, ENT_QUOTES) . '</pre>';
		}
	}
	if ($echo) {
		echo($output);
		return null;
	}else
		return $output;
}

/**
 * 对查询结果集进行排序
 * @access public
 * @param array $list 查询结果
 * @param string $field 排序的字段名
 * @param array $sortby 排序类型
 * asc正向排序 desc逆向排序 nat自然排序
 * @return array
 */
function list_sort_by($list,$field, $sortby='asc') {
	if(is_array($list)){
		$refer = $resultSet = array();
		foreach ($list as $i => $data)
			$refer[$i] = &$data[$field];
		switch ($sortby) {
			case 'asc': // 正向排序
				asort($refer);
				break;
			case 'desc':// 逆向排序
				arsort($refer);
				break;
			case 'nat': // 自然排序
				natcasesort($refer);
				break;
		}
		foreach ( $refer as $key=> $val)
			$resultSet[] = &$list[$key];
		return $resultSet;
	}
	return false;
}


/**
* 快速排序
* @param Array $array
* @param String $field	//需要排序的字段
* @param String $sort	//asc 从小到大   desc 从大到下
*/
function quickSort(&$array,$field,$sort = 'asc'){
	$count = count ($array);
	if ($count <= 1) return $array;

	$key = $array [0];

	$left_array = array ();
	$middle_array = array ();
	$right_array = array ();

	foreach ($array as $k => $val ) {
		//这里改变大于小于，改变数组的排序
		//如if ($key[$field] > $val[$field]) {
		if ($sort == 'asc') {
			if ($key[$field] > $val[$field]) {
				$left_array[] = $val;
			} else if ($key[$field] == $val[$field]) {
				$middle_array [] = $val;					 	//直接插入
			} else {
				$right_array [] = $val;
			}
		} elseif ($sort == 'desc') {
			if ($key[$field] < $val[$field]) {
				$left_array[] = $val;
			} else if ($key[$field] == $val[$field]) {
				$middle_array [] = $val;					 	//直接插入
			} else {
				$right_array [] = $val;
			}
		}

	}

	//递归
	$left_array = quickSort($left_array,$field,$sort);
	$right_array = quickSort($right_array,$field,$sort);

	//合并数组
	$array = array_merge ($left_array, $middle_array, $right_array);
	return $array;
}


/**
 * 随机生成一组字符串，放在数组中
 * @param number 	$number 数组长度
 * @param number 	$length	  字符串长度
 * @param number   $mode		1数字 其他字符串
 * @return array 		数组
 */
function build_count_rand ($number,$length=4,$mode=1) {
	if($mode==1 && $length<strlen($number) ) {
		//不足以生成一定数量的不重复数字
		return false;
	}
	$rand   =  array();
	for($i=0; $i<$number; $i++) {
		$rand[] =   rand_string($length,$mode);
	}
	$unqiue = array_unique($rand);
	if(count($unqiue)==count($rand)) {
		return $rand;
	}
	$count   = count($rand)-count($unqiue);
	for($i=0; $i<$count*3; $i++) {
		$rand[] =   rand_string($length,$mode);
	}
	$rand = array_slice(array_unique ($rand),0,$number);
	return $rand;
}



/**
 * 日期函数
 */
//1、获取从1970年1月1日到指定日期的毫秒数
mktime(0,0,0,3,12,2013);

//2、获取从1970年1月1日到指定日期的毫秒数
strtotime('2013-3-12');	//日期转换为时间戳
strtotime('-2 hours');		//2个小时前
strtotime('3 days');			//2天后;  -3为3天前
strtotime('2 months');		//2个月后：
strtotime('-1 years');		//1年前：

/**
 * 根据指定日期，返回需要的日期格式 	(格式化日期)
 * @param num or string $month		月
 * @param num or string $year		年
 * @param num or string $day			日
 * @param num or string $type		返回时间类型,可以指定时间类型如：Y-m-d H:i:s
 * type: w：返回星期几、t：返回当月总计天数
 * @return string 	date
 */
function getFormatDate($day,$month,$year,$type = 't') {
	return date($type,mktime(0,0,0,$month,$day,$year));
}

/**
 * 验证
 * @param String $start
 * @param String $over
 * @param Int $type		true日期：2013-10-15    false时间戳
 */
function count_days ($start,$over,$type = true) {	//传入时间戳、或者字符类型日期
		if ($type == true) {
			//转换为时间戳
			$d1=strtotime($start);
			$d2=strtotime($over);
			//计算二个时间戳之差,获取相差天数
			$Days = round(($d2 - $d1)/3600/24);
		} else {
			$Days = round(($over - $start)/3600/24);
		}
		return $Days;
	}

/**
 * 计算日期距离时间
 * @param unknown_type $the_time
 * @return unknown|string
 */
function time_tran($the_time){
//	$now_time = date("Y-m-d H:i:s",time()+8*60*60);
	$now_time = date("Y-m-d H:i:s");
	$now_time = strtotime($now_time);	//转换日期为毫秒数
	$show_time = strtotime($the_time);
	$dur = $now_time - $show_time;		//计算当前日期与传入日期毫秒数之差
	if($dur < 0){
		return $the_time;
	}else{
		if($dur < 60){
			return $dur.'秒前';
		}else{
			if($dur < 3600){
				return floor($dur/60).'分前';
			}else{
				if($dur < 86400){
					return floor($dur/3600).'时前';
				}else{
					if($dur < 259200){//3天内
						return floor($dur/86400).'天前';
					}else{
						return floor($dur/86400).'天前';
						//return $the_time;
					}
				}
			}
		}
	}
}

/**
* 格式化日期成时间戳,
* @param String $str_date  2014-1-25 20:15:30
* @return number
*/
function intFormatDate($str_date) {

	$arr_date = getLeftRigthStr($str_date,' ');

	$left_date = explode('-', $arr_date['left']);
	$right_date = explode(':', $arr_date['right']);

	$year = $left_date[0];
	$month = $left_date[1];
	$day = $left_date[2];

	$hours = $right_date[0];
	$minutes = $right_date[1];
	$seconds = $right_date[2];

	return mktime($hours,$minutes,$seconds,$month,$day,$year);
}


/**
 * 字符串与正则处理
 */
mb_strlen($str,'utf-8');									//计算字符串长度，return数字
str_replace('我','你','我要成为构架师');				// 把字符串中的某个字符替换成指定字符
substr(dirname(__FILE__),0,-6);				//截取字符串
strpos('123,456', ',');									//计算字符串，分隔符左边的字符长度

//字符编码
mb_convert_encoding($str,"UTF-8","euc-jp");		//转换字符串编码
iconv("UTF-8","GB2312//IGNORE",$date) ;			//把字符创转换为UTF-8编码格式
urlencode(@iconv('UTF-8', 'GB2312', $msg));		//转换为UTF-8编码

//字符替换
sprintf();
$description_format = '%1$s,%2$s,%3$s,';
$result=sprintf($description_format,123,456,789);
/**
 * 获取字符编码
 * @param unknown_type $str
 * @return unknown
 */
function getFileEncoding($str){
	$encoding=mb_detect_encoding($str);
	if(empty($encoding)){
		$encoding=detect_utf_encoding($str);
	}
	return $encoding;
}

/**
 * 计算字符串分割符左右二边的字符
 * @param string $string
 * @param string $needle 分隔符
 * @return array	数组
 */
function getLeftRigthStr($string,$needle) {
	$array = array();
	$num = strpos($string,$needle);				//计算分隔符左边字符的长度
	$array['left'] = substr($string,0,$num);
	$array['right'] = substr($string,$num+1);	//截取到最后
	return $array;
}

/**
 * 检查字符串是否是UTF8编码
 * @param string $string 字符串
 * @return Boolean
 */
function is_utf8($string) {
	return preg_match('%^(?:
			[\x09\x0A\x0D\x20-\x7E]            # ASCII
			| [\xC2-\xDF][\x80-\xBF]             # non-overlong 2-byte
			|  \xE0[\xA0-\xBF][\x80-\xBF]        # excluding overlongs
			| [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}  # straight 3-byte
			|  \xED[\x80-\x9F][\x80-\xBF]        # excluding surrogates
			|  \xF0[\x90-\xBF][\x80-\xBF]{2}     # planes 1-3
			| [\xF1-\xF3][\x80-\xBF]{3}          # planes 4-15
			|  \xF4[\x80-\x8F][\x80-\xBF]{2}     # plane 16
	)*$%xs', $string);
}

/**
 * 3、搜素字符串
 * @param STRING $find		//搜索源
 * @param STRING $str		//要搜索的字符串
 */
function find_string ($find,$str) {
	if (strpos($find,$str) ===false) {
		return false;
	} else {
		return true;
	}
}

/**
 * 显示长度限制
 * 字符串截取，支持中文和其他编码
 * @static
 * @access public
 * @param string $str 需要转换的字符串
 * @param string $start 开始位置
 * @param string $length 截取长度
 * @param string $charset 编码格式
 * @param string $suffix 是否显示后缀
 * @return string
 */
function msubstr($str, $start=0, $length, $charset="utf-8", $suffix=true) {
	if(function_exists("mb_substr"))
		$slice = mb_substr($str, $start, $length, $charset);
	elseif(function_exists('iconv_substr')) {
		$slice = iconv_substr($str,$start,$length,$charset);
		if(false === $slice) {
			$slice = '';
		}
	}else{
		$re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
		$re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
		$re['gbk']    = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
		$re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
		preg_match_all($re[$charset], $str, $match);
		$slice = join("",array_slice($match[0], $start, $length));
	}
	return $suffix ? $slice.'...' : $slice;
}

/**
 * 分割中文字符串
 * @param string $str
 * @param number $split_length
 * @return multitype:string
 */
function mb_str_split($str,$split_length=1,$charset="UTF-8"){
  if(func_num_args()==1){
    return preg_split('/(?<!^)(?!$)/u', $str);
  }
  if($split_length<1)return false;
  $len = mb_strlen($str, $charset);
  $arr = array();
  for($i=0;$i<$len;$i+=$split_length){
    $s = mb_substr($str, $i, $split_length, $charset);
    $arr[] = $s;
  }
	return $arr;
}



/**
 * 把字符串分割成数组，解决中文路吗问题
 * @param String $str
 * @param string $charset
 * @return Array
 */
function mbstringtoarray($str,$charset='utf-8') {
	$strlen=mb_strlen($str);
	while($strlen){
		$array[]=mb_substr($str,0,1,$charset);
		$str=mb_substr($str,1,$strlen,$charset);
		$strlen=mb_strlen($str);
	}
	return $array;
}


/**
 * 在字符串的任意位置插入字符
 * @param String $string  被插的字符串
 * @param String $str  需要插入的字符、或需要查找的字符
 * @param String Or Int  $location  插入的位置
 * 				String表示按照字符传入的字符作为条件替换，在后面追加需要添加的字符
 * 				Int表示在在字符串的某个位置后追加字符 ，如abcdefg  写4表示在d后追加字符
 * @param string $charset
 * @return boolean|string|mixed
 */
function string_insert_str($string,$str,$location,$charset='utf-8') {
	if(empty($string)) {
		return false;
	}
	$string_two = $string;

	$type = gettype($location);
	if ($type == 'integer') {

		$array = mbstringtoarray($string);

		$result_array = array();
		foreach ($array as $key=>$val) {
			if ($key % $location == 0) {
				if ($key != 0) {
					array_push($result_array,$str);
				}
			}
			array_push($result_array,$val);
		}

		return implode('', $result_array);
	} elseif ($type == 'string') {

		return str_replace($location,$location.$str,$string_two);
	}

}

/**
 * 正则表达式
 */
//u禁止贪婪
$pattern = "/^[\x{4e00}-\x{9fa5}]+$/u";						//中文汉字
$pattern = "/^([\w\.\-]+)\@([\w]+)\.(com|cn)$/u";			//邮箱验证
$pattern = "/^[A-Za-z][\w]{4,}$/";									//账号验证
$pattern = "/^[1-9][0-9]{5}$/";										//邮政编码
$pattern = "/^([\d]{3}\-[\d]{8})|([\d]{4}\-[\d]{7})$/";		//座机号码验证
$pattern = "/^([1-9][0-9]{4,}$)/";									//QQ号码验证
$pattern = "/^[\d]+$/u";												//数字验证
$pattern = "/^1[358]\d{9}$/";										//手机号码验证
$pattern = "'([\s])[\s]+'";												//匹配换行符号

$pattern = ".*";	//代表匹配任何数据

//匹配html换行
$string = '
<div class="news-summary">
&nbsp;</div>';
$pattern = "/^<(div class=\"news-summary\")>([\s][\s]+)(\&nbsp\;)<(\/div)>/usi";

preg_match($pattern, $string,$arr);							//正则匹配
preg_match_all($pattern, $string,$arr);					//全局匹配
//正则匹配替换 如： preg_replace($_pattenIf,'php if (\$this->_vars['$1']) {',$this->_tpl);
preg_replace('/^\/leading\/con_([0-9]+)\//','$1',$this->matches[0]);
preg_replace($pattern, "|",$content);		//把正则匹配到的$content中的内容，替换成|

//数字验证
function checkNum ($_data) {
	if (!is_numeric($_data)) return false;
	return true;
}
//替换掉字符串中的空格
function strTrim($str) {
	return preg_replace('/\s/','',$str);
}

//身份证验证
function check_identity($string) {
	$length = mb_strlen($string);
	if ($length == 15) {
		return preg_match("/^[1-9]\d{7}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}$/", $string);
	} else if ($length ==18) {
		return preg_match("/^[1-9]\d{5}[1-9]\d{3}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}(\d|x|X)$/", $string);
	}
}



/**
 * 进制函数
 */
/**
 * unpack		从二进制字符串对数据进行解包。
 * format	必需。规定在解包数据时所使用的格式。
 * data		可选。规定被解包的二进制数据。
 */
$data = "PHP";
print_r(unpack("H*",$data));

/**
 * pack		数据装入一个二进制字符串。
 * format	必需。规定在包装数据时所使用的格式。
 * args	可选。规定被包装的一个或多个参数。
 */
$str1 = pack('H*',$data1[1]);		//提取数据

//测试
$data = "PHP";		//字符串
$data1 = unpack("H*",$data);		//转换为16进制
print_r($data1);	//结果为十六进制的数据
$str1 = pack('H*',$data1[1]);			//提取数据
print_r($str1);		//结果为：PHP

bin2hex($STR);		// 函数把 ASCII 字符的字符串转换为十六进制值。

/**		进制函数(以下是参数)
 a 将字符串空白以 NULL 字符填满
 A 将字符串空白以 SPACE 字符 (空格) 填满
 h 十六进位字符串，低位在前
 H 十六进位字符串，高位在前
 c 有号字符
 C 无号字符
 s 有号短整数 (十六位，依计算机的位顺序)
 S 无号短整数 (十六位，依计算机的位顺序)
 n 无号短整数 (十六位, 高位在后的顺序)
 v 无号短整数 (十六位, 低位在后的顺序)
 i 有号整数 (依计算机的顺序及范围)
 I 无号整数 (依计算机的顺序及范围)
 l 有号长整数 (卅二位，依计算机的位顺序)
 L 无号长整数 (卅二位，依计算机的位顺序)
 N 无号短整数 (卅二位, 高位在后的顺序)
 V 无号短整数 (卅二位, 低位在后的顺序)
 f 单精确浮点数 (依计算机的范围)
 d 倍精确浮点数 (依计算机的范围)
 x 空位
 X 倒回一位
 */



/**
 * 数组处理
 */
//1、序列化数组
serialize($array);			//把数组转换为字符串   			return返回字符串
unserialize($array);		//反序列化，重新转换为数组	 	return数组

//2、反转数组中的键与值
array_flip($array);

//出入栈
//数组出栈,后进先出，数组最后一个单元弹出
array_pop($array);
//数组入栈,将7，8两个数值添加到数组尾部
array_push($array,7,8);

//将数组开头单元移出数组
array_shift($array);
//将7，8添加入数组开头
array_unshift($array,7,8);

//截取数组
array_splice($list,0,5);

//3、函数判断某个数组中是否存在指定的 key，如果该 key 存在，则返回 true，否则返回 false。
array_key_exists($key,$array);
array_keys($array);  //获取数组所有key

array_search('value', $array);		//值、数组		//按照值，获取数组的KEY

array_unique($array);		//去除数组重复值

array_values($array); 	//被返回的数组将使用数值键，从 0 开始且以 1 递增。

shuffle($Arr_show_result['result']);	//打乱数组的排序，不需要返回值
/**
 * 数组交集：数组中相同的值
 */
//一般数组的交集
$fruit1 = array("Apple","Banana","Orange");
$fruit2 = array("Pear","Apple","Grape");
$fruit3 = array("Watermelon","Orange","Apple");
$intersection = array_intersect($fruit1, $fruit2, $fruit3);

//关联数组的交集
$fruit1 = array("red"=>"Apple","yellow"=>"Banana","orange"=>"Orange");
$fruit2 = array("yellow"=>"Pear","red"=>"Apple","purple"=>"Grape");
$fruit3 = array("green"=>"Watermelon","orange"=>"Orange","red"=>"Apple");
$intersection = array_intersect_assoc($fruit1, $fruit2, $fruit3);

/**
 * 数组差集
 */
//普通数组的差集
$fruit1 = array("Apple","Banana","Orange");
$fruit2 = array("Pear","Apple","Grape");
$fruit3 = array("Watermelon","Orange","Apple");
$intersection = array_diff($fruit1, $fruit2, $fruit3);

//关联数组的差集
$fruit1 = array("red"=>"Apple","yellow"=>"Banana","orange"=>"Orange");
$fruit2 = array("yellow"=>"Pear","red"=>"Apple","purple"=>"Grape");
$fruit3 = array("green"=>"Watermelon","orange"=>"Orange","red"=>"Apple");
$intersection = array_diff_assoc($fruit1, $fruit2, $fruit3);

/* 不相同的值取出来放进另外一个数组 */
array_merge(array_diff($arr1, array_intersect($arr1, $arr2)), array_diff($arr2, array_intersect($arr1, $arr2)));

/**
 * 4、计算数组$array2在$array1中的差集
 * @$array1 数组
 * @$array2 数组
 * @return  string
 */
function diff($array1,$array2) {
	$result = array_diff($array1,$array2)	;
	return implode(',',$result);
}
function udiff ($_array1,$_array2) {
	return array_udiff($_array1,$_array2,create_function('$a,$b','return ($a === $b) ? 0:1;'));
}

/**
 * 把数组转换为json格式
 * @param $array  数组
 * json_encode($array)		//转换数组为JSON格式
 * json_decode($json);		//转换JSON为数组
 */
function JSON($array) {
	arrayRecursive($array, 'urlencode', true);
	$json = json_encode($array);		//转换数组为json格式
	return urldecode($json);
}
function arrayRecursive(&$array, $function, $apply_to_keys_also = false) {
	static $recursive_counter = 0;
	if (++$recursive_counter > 1000) {
		die('possible deep recursion attack');
	}
	foreach ($array as $key => $value) {
		if (is_array($value)) {
			arrayRecursive($array[$key], $function, $apply_to_keys_also);
		} else {
			$array[$key] = $function($value);
		}
		if ($apply_to_keys_also && is_string($key)) {
			$new_key = $function($key);
			if ($new_key != $key) {
				$array[$new_key] = $array[$key];
				unset($array[$key]);
			}
		}
	}
	$recursive_counter--;
}

/**
 * 5、数组任意部位插入新的值，保持排序
 * @param data  		$data		插入的数据
 * @param num	 	$num		插入的位置
 * @param array 		 $array		要操作的数组
 * @return array
 */
function InsertValArray($data,$num,$array) {
	for ($i=count($array);$i>$num;$i--) {
		$array[$i] = $array[$i-1];	//把数组的值向后移动
	}
	$array[$num] =  $data;			//在指定位置插入数据
	return $array;
}

/**
 * 6、删除数组中任意值，保持排序
 * @param num $num		删除的位置
 * @param array $array	操作的数组
 * @return array
 */
function delValArray($num,$array) {
	$max = count($array);
	if ($num<0 || $num>=$max) {
		return false;
	}
	for ($i=$num;$i<$max;$i++) {
		$array[$i] = $array[$i+1];		//把后一个指针，向前推一个
	}
	unset($array[$max-1]);
	return $array;
}

/**
 * 比较二个数组，计算出需要插入和删除的数据，确保一直
 * @param $Array $arr_request		//请求的数据
 * @param $Array $arr_have			//原本已存在的数组，如数据库中的数据
 * @return $Array								//需要插入和删除的数组
 */
function arrar_insert_delete(&$arr_request,&$arr_have) {
	//计算需要插入的数据 (二个数组不同的地方)
	$insert_arr = array_diff($arr_request,$arr_have);

	//计算需要删除的数据(不在$arr_request请求数组中的数据)
	$delete_arr = array();
	foreach ($arr_have AS $key=>$val) {
		if (!in_array($val,$arr_request)) {
			array_push($delete_arr,$val);
		}
	}
	return array('insert'=>$insert_arr,'delete'=>$delete_arr);
}


/**
 * 获取数组val值中的字段
 * @param Array $arr
 * @param string $field
 * return Array
 */
function getArrayByField($arr,$field, $key = '')
{
	$result = array();

	if (empty($arr)) {
		return $result;
	}

	if ($key !== '') {
		foreach ($arr AS $val) {
			$result[$val[$key]] = $val[$field];
		}
	} else {
		foreach ($arr AS $val) {
			$result[] = $val[$field];
		}
	}
	return $result;
}


/**
 * 根据Val值，重新排序数组
 * @param Array $arr			//排序的数组
 * @param String $k				//排序的字段
 * @param Boole $old			//是否按照原数组的排序
 * @return Array
 */
function regroupKey(&$arr,$k,$old = false)
{
	$aRet = array();
	if (empty($arr)) {
		return $aRet;
	}

	if ($old == true) {
		foreach ($arr AS $key=>$val) {
			$aRet[$val[$k]] = $val;
		}
	} else {
		foreach ($arr AS $key=>$val) {
			$aRet[$val[$k]][] = $val;
		}
	}
	return $aRet;
}



/**
 * 筛选数组中邮箱的数据
 * @param Array $array
 * @param Array $kw	(需要，或者不需要的键)
 * @param Blooe $is_not (是否取反，如果为true，则在数组中获取$kw不存在的数据)
 * @return multitype:
 */
function format_array(&$array,$fields,$is_not = false) {
	$result = array();
	if (empty($array)) return $result;

	switch ($is_not) {
		case true :
			foreach ($array as $key=>$detail) {
				$Arr_tmp = array();

				foreach ($detail as $two_key=>$val) {
					if (!in_array($two_key,$fields)) {
						$Arr_tmp[$two_key] = $val[$two_key];
					}
				}

				$result[$key] = $Arr_tmp;
			}
			break;

		case false :
			foreach ($array as $key=>$detail) {

				$Arr_tmp = array();

				foreach($fields as $fd) {
					if(array_key_exists($fd,$detail)) {
						$Arr_tmp[$fd] = $detail[$fd];
					}
				}

				$result[$key] = $Arr_tmp;
			}
			break;
	}

	return $result;
}






/**
 * PHP缓存
 */
ob_start();					//开启缓冲区域
echo '我是缓冲区输出的内容';
ob_get_contents();		//取得缓冲区输出内容(字符串)
ob_end_clean();			//清空缓冲区



/**
 * 加密函数
 */
$encode = chunk_split(base64_encode($str));		//把二进制数转成普通字符用于网络传输
$decode = base64_decode($encode);					//解码成二进制
md5($str);
sha1($str);
/**
 * 加密
 * @param string $txt  加密内容
 * @param string $key	解密时的钥匙
 */
function passport_encrypt($txt, $key) {
	srand((double)microtime() * 1000000);
	$encrypt_key = md5(rand(0, 32000));
	$ctr = 0;
	$tmp = '';
	for($i = 0;$i < strlen($txt); $i++) {
		$ctr = $ctr == strlen($encrypt_key) ? 0 : $ctr;
		$tmp .= $encrypt_key[$ctr].($txt[$i] ^ $encrypt_key[$ctr++]);
	}
	return base64_encode(passport_key($tmp, $key));
}

/**
 * 解密
 * @param string $txt	passport_encrypt()加密后的字符
 * @param $string $key	解密时的钥匙
 * @return Ambigous <string, boolean>
 */
function passport_decrypt($txt, $key) {
	$txt = passport_key(base64_decode($txt), $key);
	$tmp = '';
	for($i = 0;$i < strlen($txt); $i++) {
		$md5 = $txt[$i];
		$tmp .= $txt[++$i] ^ $md5;
	}
	return $tmp;
}
//加密算法
function passport_key($txt, $encrypt_key) {
	$encrypt_key = md5($encrypt_key);
	$ctr = 0;
	$tmp = '';
	for($i = 0; $i < strlen($txt); $i++) {
		$ctr = $ctr == strlen($encrypt_key) ? 0 : $ctr;
		$tmp .= $txt[$i] ^ $encrypt_key[$ctr++];
	}
	return $tmp;
}
//加密演示--------------
$txt = "This is a testa";
$key = "testkey";
$encrypt = passport_encrypt($txt,$key);
$decrypt = passport_decrypt($encrypt,$key);

echo $txt."<br><hr>";
echo $encrypt."<br><hr>";
echo $decrypt."<br><hr>";


/**
 * 取舍，保留
 */
//
echo floor(4.3);   			// 4
echo floor(9.999);			 // 9

echo ceil(4.3);  		 		 // 5
echo ceil(9.999);  			// 10

//也可以保留2位小数
echo round(3.4);         // 3 四舍五入
echo round(3.5);         // 4 四舍五入




/**
 * 计算二个经纬度之间的距离
 * @param unknown_type $d
 * @return number
 */
function rad($d) {
	return $d * 3.1415926535898 / 180.0;
}
function GetDistance($lat1, $lng1, $lat2, $lng2)	{//lat纬度(短的)，lng经度(长的)
	$EARTH_RADIUS = 6378.137;
	$radLat1 = rad($lat1);

	$radLat2 = rad($lat2);
	$a = $radLat1 - $radLat2;
	$b = rad($lng1) - rad($lng2);
	$s = 2 * asin(sqrt(pow(sin($a/2),2) +
			cos($radLat1)*cos($radLat2)*pow(sin($b/2),2)));
	$s = $s *$EARTH_RADIUS;
	$s = round($s * 10000) / 10000;
	return $s;
}


/**
 *用指定经纬度，计算指定范围内，存在的数据
 *@param lng float 经度		(长的)		121.473704
 *@param lat float 纬度		(短的)		31.230393
 *@param distance float 该点所在圆的半径，该圆与此正方形内切，默认值为0.5千米 (范围)
 *@return array 正方形的四个点的经纬度坐标
 *
 *参考资料：http://www.flyphp.cn/phpmysql-%E6%A0%B9%E6%8D%AE%E4%B8%80%E4%B8%AA%E7%BB%99%E5%AE%9A%E7%BB%8F%E7%BA%AC%E5%BA%A6%E7%9A%84%E7%82%B9%EF%BC%8C%E8%BF%9B%E8%A1%8C%E9%99%84%E8%BF%91%E7%9A%84%E4%BA%BA%E6%9F%A5%E8%AF%A2.html
 */
function _SquarePoint($lng, $lat,$distance = 0.5){		//经度、纬度、范围

	define('EARTH_RADIUS', 6371);	//地球半径，平均半径为6371km
	$dlng = 2 * asin(sin($distance / (2 * EARTH_RADIUS)) / cos(deg2rad($lat)));
	$dlng = rad2deg($dlng);

	$dlat = $distance/EARTH_RADIUS;
	$dlat = rad2deg($dlat);

	//返回，经纬度坐标点内，正方形4个点的经纬度
	return array(
			'left-top'=>array('lng'=>$lng-$dlng,'lat'=>$lat + $dlat),				//左上：经度、纬度
			'right-top'=>array('lng'=>$lng + $dlng,'lat'=>$lat + $dlat),			//右上：经度、纬度
			'left-bottom'=>array('lng'=>$lng - $dlng,'lat'=>$lat - $dlat),		//左下：经度、纬度
			'right-bottom'=>array('lng'=>$lng + $dlng,'lat'=>$lat - $dlat)		//又下：经度、纬度
	);
}

$squares = _SquarePoint(121.473704,31.230393);
//带入SQL查询
$sql = "select
					id,locateinfo,lat,lng
		from
					`lbs_info`
		where
					lat<>0 													//纬度不等于0
		and
					lat>{$squares['right-bottom']['lat']}		//纬度>右下点
		and
					lat<{$squares['left-top']['lat']} 			//纬度<左上
		and
					lng>{$squares['left-top']['lng']} 			//经度>左上
		and
					lng<{$squares['right-bottom']['lng']}";//经度<又下

Thinkphp
$map['lat'] = array(
	array('neq',0),		//纬度不等于0
	array('gt',$squares['right-bottom']['lat']),	//纬度>右下点
	array('lt',$squares['left-top']['lat']),				//纬度<左上
	'AND') ;
$map['lng'] = array(
		array('gt',$squares['left-top']['lng']),			//经度>左上
		array('lt',$squares['right-bottom']['lng']),	//经度<又下
'AND') ;

//计算距离
foreach ($purview AS $key=>$val) {
	$purview[$key]['distance'] = round(GetDistance($lat,$lng,$val['lat'], $val['lng']),2);
	array_push($store_ids,$val['store_pic']);			//取得店铺的图片
}
$purview = quickSort($purview,'distance');	//
$purview = list_sort_by($purview,'distance');



/**
 * 中奖算法
 * @param Array $proArr
 *
$proArr =  array (size=7)
  1 => int 1
  2 => int 2
  3 => int 5
  4 => int 7
  5 => int 10
  6 => int 25
  7 => int 50
 */
function getRand($proArr) {
	$result = '';

	//概率数组的总概率精度
	$proSum = array_sum($proArr);

	//概率数组循环
	foreach ($proArr as $key => $proCur) {
		$randNum = mt_rand(1, $proSum);
		if ($randNum <= $proCur) {
			$result = $key;
			break;
		} else {
			$proSum -= $proCur;
		}
	}
	unset ($proArr);

	return $result;
}


/**
 * 解析csv文件 成数组
 * @param String $file_name
 * $csv_data = array_splice($csv_data,1,count($csv_data)-2);
 */
function analysis_csv ($file_name) {
	if (file_exists($file_name) == false) {
		return false;
	}

	$result = array();

	$file = fopen($file_name,"r");
	while(!feof($file))
	{
		$csv_data = fgetcsv($file);

		$tmp_array = array();
		foreach ($csv_data as $key=>$val) {
			$format_string = iconv('gbk','utf-8',$val);
			array_push($tmp_array,$format_string);
		}

		array_push($result,$tmp_array);
		$tmp_array = null;
	}

	fclose($file);
	return $result;
}

/**
 * 解析文本文件成数组
 * @param String $file_name
 * @param String $ex
 */
function analysis_txt ($file_name,$ex = ' ') {
	header('Content-Type:text/html;charset=utf-8');

	if (file_exists($file_name) == false) {
		return false;
	}

	$result = array();

	$file_source = fopen($file_name,"r");
	while (! feof ($file_source)) {
		$line_str = fgets ($file_source);
		if (empty($line_str)) continue;
		$line_array = explode($ex,$line_str);
		array_push($result,$line_array);
	}

	fclose($file_source);

	return $result;
}


/**
 * 根据数据源创建csv文件
 * @param String $name
 * @param Array OR String $content
 */
function create_excel($name,$content) {

	if (is_array($content)) {

		//$title = '会员号,b,c'."\n";
		$result = '';
		foreach ($content as $key=>$val) {
			foreach ($val as $k=>$v) {
				//$str .= (iconv( "UTF-8","gbk",$val['oid'])).',';
				$result .= $v.',';
			}
			$result .= "\n";
		}
	}

	header('Content-Type:text/html;charset=utf-8');
	header("Content-Type: application/force-download");
	header("Content-Type: text/csv");					//CSV文件
	header("Content-Disposition: attachment; filename=$name");					//强制跳出下载对话框
	header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate');
	header('Expires:0');
	header('Pragma:public');

	$content = (iconv( "UTF-8","gbk",$result)).',';

	echo $content;
}



?>
