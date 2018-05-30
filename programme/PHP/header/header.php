<?php
/*
 *
 ini_get('post_max_size');			
 ini_get('upload_max_filesize');
 
 
//常用头
header('Content-Type:text/html;charset=utf-8');	
header('Content-Type:application/json;charset=utf-8');
header("Content-type: text/xml;charset=utf-8");
	
//一定时间后跳转
header("refresh:3;url=http://www.baidu.com");	
<meta http-equiv="refresh" content="10;http://www.example.org/ />	//html语法
//直接跳转		
header('Location: http://www.www.org/');					


//不存在页面报错
header("http/1.1 404 Not Found");		
					
 //告诉浏览器最后一次修改时间
$time = time() - 60;
header('Last-Modified: '.gmdate('D, d M Y H:i:s', $time).' GMT');

//告诉浏览器文档内容没有发生改变
header('HTTP/1.1 304 Not Modified');	 

// 对当前文档禁用缓存
header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header('Pragma: no-cache');

//显示登陆对话框
header('HTTP/1.1 401 Unauthorized');
header('WWW-Authenticate: Basic realm="Top Secret"');
 
输出内容类型
header('Content-Type: text/plain'); 				//纯文本格式
header('Content-Type: image/jpeg'); 			//JPG图片
header('Content-Type: application/zip'); 		// ZIP文件
header("Content-Type: text/csv");					//CSV文件
header('Content-Type: application/pdf'); 		// PDF文件
header('Content-Type: audio/mpeg'); 			// 音频文件
header('Content-Type: application/x-shockwave-flash'); //Flash动画
 
 */


//一些例子
//输出类型与下载文档
header("Content-Type: application/force-download");
header("Content-Type: application/download");
header("Content-Type: application/octet-stream");
header("Content-Type: text/csv");																//内容类型
header("Content-Disposition: attachment; filename=name.csv");					//强制跳出下载对话框
header("Content-length:123123");																//文件字节
														
//禁止浏览器缓存
header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); 
header('Pragma: no-cache');
header("Content-Description: PHP5 Generated Data");
header('Expires:0');
header('Pragma:public');
echo '123456';

//告诉浏览器缓存
header("Cache-Control: public");
header("Pragma: cache");
$offset = 30*60*60*24; // cache 缓存一个月
$ExpStr = "Expires: ".gmdate("D, d M Y H:i:s", time() + $offset)." GMT";
header($ExpStr);


//向浏览器生成文件csv文件配置
header('Content-Type:text/html;charset=utf-8');
header("Content-Type: application/force-download");			
header("Content-Type: application/download");		
header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate');		//禁止缓存
header("Content-Type: text/csv");																	//CSV文件
header("Content-Disposition: attachment; filename=name.csv");						//强制跳出下载对话框
header('Expires:0');
header('Pragma:public');


//向浏览器输出图像
function PE_img_by_path($PE_imgpath = "")
{
	if (file_exists($PE_imgpath)) {
		$PE_imgarray = pathinfo($PE_imgpath);
		$iconcontent = file_get_contents($PE_imgpath);
		header("Content-type: image/" . $PE_imgarray["extension"]);
		header("Content-length:". strlen($iconcontent));
		echo $iconcontent;
		die(0);
	}
	return false;
}




/*	Content-Type参数集合

'hqx' -> 'application/mac-binhex40',
 'cpt' -> 'application/mac-compactpro', 
 'doc' -> 'application/msword', 
'bin' -> 'application/octet-stream',
'dms' -> 'application/octet-stream', 
'lha' -> 'application/octet-stream', 
'lzh' -> 'application/octet-stream',
'exe' -> 'application/octet-stream',
'class' -> 'application/octet-stream', 
'so' -> 'application/octet-stream',
'dll' -> 'application/octet-stream', 
'oda' -> 'application/oda',
'pdf' -> 'application/pdf', 
'ai' -> 'application/postscript', 
'eps' -> 'application/postscript',
'ps' -> 'application/postscript', 
'smi' -> 'application/smil', 
'smil' -> 'application/smil', 
'mif' -> 'application/vnd.mif',
 'xls' -> 'application/vnd.ms-excel', 
 'ppt' -> 'application/vnd.ms-powerpoint', 
 'wbxml' -> 'application/vnd.wap.wbxml',
  'wmlc' -> 'application/vnd.wap.wmlc', 
  'wmlsc' -> 'application/vnd.wap.wmlscriptc', 
  'bcpio' -> 'application/x-bcpio', 
  'vcd' -> 'application/x-cdlink', 
  'pgn' -> 'application/x-chess-pgn',
   'cpio' -> 'application/x-cpio', 
   'csh' -> 'application/x-csh', 
   'dcr' -> 'application/x-director', 
   'dir' -> 'application/x-director',
    'dxr' -> 'application/x-director', 
    'dvi' -> 'application/x-dvi', 
    'spl' -> 'application/x-futuresplash',
     'gtar' -> 'application/x-gtar', 
     'hdf' -> 'application/x-hdf', 
     'js' -> 'application/x-javascript',  
     'cdf' -> 'application/x-netcdf', 
      'swf' -> 'application/x-shockwave-flash',
       'sit' -> 'application/x-stuffit',
        'sv4cpio' -> 'application/x-sv4cpio', 
        'sv4crc' -> 'application/x-sv4crc',
         'tar' -> 'application/x-tar',
          'tcl' -> 'application/x-tcl', 
          'tex' -> 'application/x-tex',
           'texinfo' -> 'application/x-texinfo',
            'texi' -> 'application/x-texinfo', 't' -> 'application/x-troff', 'tr' -> 'application/x-troff', 
            'roff' -> 'application/x-troff', 'man' -> 'application/x-troff-man', 'me' -> 'application/x-troff-me', 
            'ms' -> 'application/x-troff-ms', 'ustar' -> 'application/x-ustar', 'src' -> 'application/x-wais-source', 
            'xhtml' 'application/xhtml+xml', 'xht' -> 'application/xhtml+xml', 'zip' -> 'application/zip', 
            'au' -> 'audio/basic', 'snd' -> 'audio/basic', 
            'mid' -> 'audio/midi', 'midi' -> 'audio/midi', 'kar' -> 
            'audio/midi', 'mpga' -> 'audio/mpeg', 'mp2' -> 
            'audio/mpeg', 'mp3' -> 'audio/mpeg', 'aif' -> 'audio/x-aiff', 'aiff' ->
             'audio/x-aiff', 'aifc' -> 'audio/x-aiff', 'm3u' -> 'audio/x-mpegurl', 'ram' -> 'audio/x-pn-realaudio',
              'rm' -> 'audio/x-pn-realaudio', 'rpm' -> 'audio/x-pn-realaudio-plugin', 'ra' -> 'audio/x-realaudio', 
              'wav' -> 'audio/x-wav', 'pdb' -> 'chemical/x-pdb', 'xyz' -> 'chemical/x-xyz', 'bmp' -> 'image/bmp', 'gif' -> 'image/gif', 'ief' -> 'image/ief', 'jpeg' -> 'image/jpeg', 'jpg' -> 'image/jpeg', 'jpe' -> 'image/jpeg', 'png' -> 'image/png', 'tiff' -> 'image/tiff', 'tif' -> 'image/tiff', 'djvu' -> 'image/vnd.djvu', 'djv' -> 'image/vnd.djvu', 'wbmp' -> 'image/vnd.wap.wbmp', 'ras' -> 'image/x-cmu-raster', 'pnm' -> 'image/x-portable-anymap', 'pbm' -> 'image/x-portable-bitmap', 'pgm' -> 'image/x-portable-graymap', 'ppm' -> 'image/x-portable-pixmap', 'rgb' -> 'image/x-rgb', 'xbm' -> 'image/x-xbitmap', 'xpm' -> 'image/x-xpixmap', 'xwd' -> 'image/x-xwindowdump', 'igs' -> 'model/iges', 'iges' -> 'model/iges', 'msh' -> 'model/mesh', 'mesh' -> 'model/mesh', 'silo' -> 'model/mesh', 'wrl' -> 'model/vrml', 'vrml' -> 'model/vrml', 'css' -> 'text/css', 'html' -> 'text/html', 'htm' -> 'text/html', 'asc' -> 'text/plain', 'txt' -> 'text/plain', 'rtx' -> 'text/richtext', 'rtf' -> 'text/rtf', 'sgml' -> 'text/sgml', 'sgm' -> 'text/sgml', 'tsv' -> 'text/tab-separated-values', 'wml' -> 'text/vnd.wap.wml', 'wmls' -> 'text/vnd.wap.wmlscript', 'etx' -> 'text/x-setext', 'xsl' -> 'text/xml', 'xml' -> 'text/xml', 'mpeg' -> 'video/mpeg', 'mpg' -> 'video/mpeg', 'mpe' -> 'video/mpeg', 'qt' -> 'video/quicktime', 'mov' -> 'video/quicktime', 'mxu' -> 'video/vnd.mpegurl', 'avi' -> 'video/x-msvideo', 
            'movie' -> 'video/x-sgi-movie', 
            'ice' -> 'x-conference/x-cooltalk'
 * 
 */
?>