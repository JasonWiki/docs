#安装SVN客户端
yum install subversion


#命令(文档http://www.jb51.net/os/RedHat/2461.html)

#查看list (第一次要输入账号密码)
svn list svn://203.88.210.84/cwq.com  

#检出目录，就是拉最新的代码
svn checkout svn://203.88.210.84/cwq.com ./

#检查变动文件
config.inc.php

#往版本库中添加新的文件
svn add test.php(添加test.php)
svn add *.php(添加当前目录下所有的php文件)

#将改动的文件提交到版本库
svn commit -m “LogMessage“ [-N] [--no-unlock] PATH(如果选择了保持锁，就使用–no-unlock开关)
例如：svn commit -m “add test file for my test“ test.php


# "查看"文件或者目录状态
1）svn status
  ?：不在svn的控制中；
  M：内容被修改；
  C：发生冲突；
  A：预定加入到版本库；
  K：被锁定】
2）svn status -v path(显示文件和子目录状态)

svn status
svn diff
svn revert


#更新
svn update
