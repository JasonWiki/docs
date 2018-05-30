# sshfs 远程挂载目录

## 一、介绍

- 通过 ssh 通道, 映射本地路径到服务器路径, 编辑本地路径中的文件, 会同步到服务器上

## 二、使用

- [教程](http://www.php101.cn/2015/03/22/%E4%BD%BF%E7%94%A8sshfs%E6%8C%82%E8%BD%BD%E8%BF%9C%E7%A8%8B%E4%B8%BB%E6%9C%BA%E7%9B%AE%E5%BD%95%E5%88%B0%E6%9C%AC%E5%9C%B0/)
- [下载](https://sourceforge.net/projects/fuse/?source=navbar)

``` sh
安装
  Ubuntu/Debian: sudo apt-get install sshfs

挂载
  sudo sshfs user@hostname:/home/user /home/user/server_dir

卸载
  sudo umount /mnt/droplet

```
