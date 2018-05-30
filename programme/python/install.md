# Python 安装下载配置

## * 安装

### 一、下载地址

- [Linux/UNIX](https://www.python.org/downloads/source/)
- [Mac OS X](https://www.python.org/downloads/mac-osx/)


#### 1、Linux/UNIX

``` python

1. 系统会默认安装 python

2. tar 包方式安装 (如果要折腾的话)
  linx 选择 (Gzipped source tarball) 自己编译

  wget http://www.python.org/ftp/python/2.7.10/Python-2.7.10.tar.xz
  unxz Python-2.7.10.tar.xz

  tar -zxvf Python-version.tgz 解压

  cd Python-version 进入

  ./configure --prefix=/usr/local/Python-version  编译

  make

  make install

3. centos 2.6 升级到 2.7

  1) 更新依赖
    yum -y update
    yum install epel-release
    yum install sqlite-devel
    yum install -y zlib-devel.x86_64
    yum install -y openssl-devel.x86_64

  2) 下载编译
    cd /usr/local/src/
    wget http://www.python.org/ftp/python/2.7.10/Python-2.7.10.tar.xz
    unxz Python-2.7.10.tar.xz
    tar -vxf Python-2.7.10.tar

    cd /usr/local/src/Python-2.7.10
    ./configure --enable-shared --enable-loadable-sqlite-extensions --with-zlib

    make && make install

  3) 备份老系统 python
    mv /usr/bin/python /usr/bin/python2.6.6
    ln -s /usr/local/bin/python2.7 /usr/bin/python

  4) yum 却换到老系统中
    vim /usr/bin/yum

    #!/usr/bin/python
    替换成
    #!/usr/bin/python2.6.6

  5) 问题
    python -V 出现错误 error while loading shared libraries: libpython2.7.so.1.0: cannot open shared object file: No such file or directory

    解决:
      vim /etc/ld.so.conf
      # 增加一行
      /usr/local/lib

    执行:
      /sbin/ldconfig  
      /sbin/ldconfig -v
```


## * 管理第三方模块

### 一、 管理加载包

#### 1、包存放地址

``` python

1. 管理软件包存放的地址 (pip 和 easy_install)
  /usr/local/lib/python2.7/dist-packages

2. 第三方自定义下载的包存放地址
  /usr/local/lib/python2.7/site-packages

3. 查找 site-packages
  locate site-packages

```

#### 2、加载类包方法

- [文章](http://blog.sina.com.cn/s/blog_7de9d5d80101hlj5.html)

``` python

1.在脚本中
  import sys
  sys.path
  sys.path.append(path)

2.PYTHONPATH 环境变量
  export PYTHONPATH=$PYTHONPATH:/usr/local/lib/python2.7/site-packages

```


### 二、安装 python 管理模块工具


#### 1、easy_install 管理 python 包依赖

- easy_install 是由 PEAK(Python Enterprise Application Kit) 开发的 setuptools 包里带的一个命令，
以使用 easy_install 实际上是在调用 setuptools 来完成安装模块的工作。

``` python

1. apt-get 方式安装

  sudo apt-get install python-setuptools

  sudo yum install python-setuptools

  1) 操作
    安装包
    easy_install package-name（比如 easy_install pylab)

    卸载包
    easy_install -m package-name （比如easy_install -m pylab)
    easy_install -m 包名，可以卸载软件包，但是卸载后还要手动删除遗留文件。

2. tar 包安装
  https://pypi.python.org/pypi/setuptools/

```

#### 2、pip 工具

- [官方文档](http://pip-cn.readthedocs.org/en/latest/installing.html)

``` python

1.Debian 和 Ubuntu:
  sudo apt-get install python-pip

2.Fedora
  sudo yum install python-pip

3.Centos
  sudo yum -y install epel-release  如果找不到就
  sudo yum install python-pip

4.使用
  sudo pip install pyhs2
```
