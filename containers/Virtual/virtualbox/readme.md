# Virtualbox 虚拟机

## 一、官方地址
https://www.virtualbox.org/wiki/Linux_Downloads

## 二、安装

### 1、下载ubuntu
http://download.virtualbox.org/virtualbox/4.3.24/virtualbox-4.3_4.3.24-98716~Ubuntu~raring_amd64.deb

### 2、设置源
vim /etc/apt/sources.list
deb http://download.virtualbox.org/virtualbox/debian trusty contrib
deb http://download.virtualbox.org/virtualbox/debian saucy contrib
deb http://download.virtualbox.org/virtualbox/debian raring contrib
deb http://download.virtualbox.org/virtualbox/debian quantal contrib
deb http://download.virtualbox.org/virtualbox/debian precise contrib
deb http://download.virtualbox.org/virtualbox/debian lucid contrib non-free
deb http://download.virtualbox.org/virtualbox/debian wheezy contrib
deb http://download.virtualbox.org/virtualbox/debian squeeze contrib non-free

### 3、继续安装

sudo apt-key add oracle_vbox.asc

wget -q https://www.virtualbox.org/download/oracle_vbox.asc -O- | sudo apt-key add -

sudo apt-get update

sudo apt-get install virtualbox-4.3

sudo apt-get install dkms
