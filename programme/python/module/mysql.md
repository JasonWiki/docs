# python 安装 mysql 模块

- 注意，需要依赖 easy_install ，请先安装 easy_install

``` python

1. apt-get
  1) mysql-config 安装 ()
    sudo apt-get install libmysqlclient-dev  libmysqlclient18

  2) python-dev 安装
    sudo apt-get install python-dev

  3) mysql-python 模块安装
    sudo easy_install mysql-python

  4) 测试
    import MySQLdb

  PS: Google 后得知 mysql_config 是属于 MySQL 开发用的文件，而使用 apt-get 安装的 MySQL 是没有这个文件的，于是在包安装器里面寻找

    sudo apt-get install libmysqld-dev

    sudo apt-get install libmysqlclient-dev

2. yum 等其他系统参照
  http://blog.csdn.net/a657941877/article/details/8944683

```
