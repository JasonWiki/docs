# ssh 操作

## 安装

``` sh
sudo apt-get install openssh-client
sudo apt-get install openssh-server

```

## 高级特性

- [文章](http://blog.csdn.net/fdipzone/article/details/23000201)

``` sh
1. 基础命令
  ssh-keygen -t rsa -P "密码"	 //创建非对称秘钥
      ssh-keygen -t rsa  //不需要密码的
  ssh www@192.168.1.24 		//链接远程主机
  ssh-add -l 			//查看添加的key缓存
  ssh-add -D      //清除缓存
  ssh -t ac@ad command 执行远程命令
  ssh -vvv ac@ad command 显示调试模式
  ssh-add ~/.ssh/id_rsa   添加本地 key 到缓存
  ssh-copy-id -i ~/.ssh/id_rsa.pub username@hostname  拷贝本机公钥到远程服务器

  执行远程脚本，需要免密码登陆
  ssh -t -p 22 hadoop@192.168.160.45 touch /tmp/aaaaa.txt


2.远程执行命令配置

usage: ssh [-1246AaCfgKkMNnqsTtVvXxYy] [-b bind_address] [-c cipher_spec]  
         [-D [bind_address:]port] [-e escape_char] [-F configfile]  
         [-I pkcs11] [-i identity_file]  
         [-L [bind_address:]port:host:hostport]  
         [-l login_name] [-m mac_spec] [-O ctl_cmd] [-o option] [-p port]  
         [-R [bind_address:]port:host:hostport] [-S ctl_path]  
         [-W host:port] [-w local_tun[:remote_tun]]  
         [user@]hostname [command]  

       -l 指定登入用户
       -p 设置端口号
       -f 后台运行，并推荐加上 -n 参数
       -n 将标准输入重定向到 /dev/null，防止读取标准输入
       -N 不执行远程命令，只做端口转发
       -q 安静模式，忽略一切对话和错误提示
       -T 禁用伪终端配置

  // 远程执行命令格式
  ssh [options][remote host][command]


  ~/.bashrc 里面，如果不是交互模式会推出,如果特殊需求，这里注释掉
  # If not running interactively, don't do anything
  case $- in
      *i*) ;;
        *) return;;
  esac

  案例：必须在目标的服务器，使用脚本作为容器来执行指定 shell
  ssh -q -t dwadmin@bi2 "bash -i /usr/local/hive/bin/hive -e 'show tables;'"


  ssh -q -t dwadmin@10.10.2.91 "bash -i /usr/local/hive/bin/hive -e \"LOAD DATA LOCAL INPATH '/data/log/uba/uba_web_visit_20151201.log' OVERWRITE INTO TABLE real_time.uba_web_visit_log\""

3. 根据私钥生成公钥
  openssl
  输入
  rsa -in /home/hadoop/.ssh/id_rsa -pubout -out /home/hadoop/.ssh/id_rsa.pub
```
