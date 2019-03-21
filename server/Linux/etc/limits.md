# ulimit 限制用户资源

- /etc/security/limits.conf

``` sh
shell 的当前用户所有进程能打开的最大文件数量设置为 1000

ulimit 限制用户 文件系统及程序资源(312)

  *）查看 linux 能打开的最大文件数(最大文件数不能超过这个数字)
     sysctl -a | grep fs.file
     cat /proc/sys/fs/file-max

  1) ulimit  -a  				// 列出用户所有限额

    open files    (-n) 65535 表示用户打开文件数的限额

  2) ulimit -f 10240		// 限制用户可以创建最大的文件

  3) ulimit -n  				// 查看最大可以打开的文件数
     ulimit -n 65535  	// 设置最大可以打开的文件数

     PS : Centos 系统 ~/.bashrc

         Ubuntu 系统系统 ~/.bashrc
         // 方法一、当前终端生效
         ulimit -HSn 65535

         // 方法二、当前环境生效
         vim ~/.bashrc
         ulimit -HSn 65535

         // 方法三、永久生效
         sudo vim /etc/security/limits.conf
         [用户账号]   hard    nofile          65536
         [用户账号]   soft    nofile          65536
         * 表示所有账号

         修改了配置文件, 重新登录就生效

  4) ulimit -n 只能设置比 /etc/security/limits.conf 小的数量

  *)	其他还有CPU、内存、进程等限制

```
