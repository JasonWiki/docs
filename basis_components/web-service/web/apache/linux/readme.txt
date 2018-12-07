<IfModule mod_rewrite.c>
  RewriteEngine On
  RewriteCond %{REQUEST_FILENAME} !-d
  RewriteCond %{REQUEST_FILENAME} !-f
  RewriteRule ^(.*)$ /index.php?s=/$1 [QSA,PT,L]	
  [ ^(.*)$ =>表示匹配域名。]
  [ /index.php?s=/ 表示默认的URL后面跟的参数]
  [$1 表示域名后面所有的参数 ]
</IfModule>