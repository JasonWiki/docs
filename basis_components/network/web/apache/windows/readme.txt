//设置apache的配置文件

//修改端口
httpd.conf
Listen 80 改成 Listen 8080

//允许所有ip访问网站目录
DocumentRoot "C:/wamp/www" 从这里找
Directory "c:/wamp/www/"
Order Allow,Deny
Deny from all		//不允许
Allow from all		//允许所有网站访问


//开启URL重写
LoadModule rewrite_module modules/mod_rewrite.so
AllowOverride ALL
//放在入口文件同一个目录下
.htaccess
<IfModule mod_rewrite.c>
  RewriteEngine on
  RewriteCond %{REQUEST_FILENAME} !-d
  RewriteCond %{REQUEST_FILENAME} !-f
  RewriteRule ^(.*)$ index.php/$1 [QSA,PT,L]
</IfModule>




//域名指向二级目录(虚拟空间配置)
#在配置文件中
conf/httpd.conf
#引入配置文件。
Include conf/extra/httpd-vhosts.conf

conf/extra/httpd-vhosts.conf 中添加以下内容
#可有可无
NameVirtualHost *:80
#jiezoudashi.eicp.net--设置域名指向的目录（详细设置看httpd-vhosts.conf	）
<VirtualHost *:80>
    ServerAdmin linus.php@gmail.com
    #这里是二级目录路径
    DocumentRoot "C:\AppServ\www\jiezoudashi"
    ServerName jiezoudashi.eicp.net
    #如果多个域名，不要设置别名，否则冲突
    ServerAlias jiezoudashi.eicp.net
    ErrorLog "logs/www.linus.com-error.log"
    CustomLog "logs/www.linus.com-access.log" common
</VirtualHost>


2.配置默认主机名：加上index.php(有index文件的情况下会默认定位到此文件，没有则列出网站目录)
<IfModule dir_module>
    DirectoryIndex index.html default.htm index.php
</IfModule>


3.也可以关闭WEB列网站目录(不是必须的)
<Directory "C:/Program Files/Apache Software Foundation/Apache2.2/cgi-bin"> //这里是你的blog目录
    Options Indexes FollowSymLinks  //这里把indexs去掉
</Directory>
