# OS 模块

``` python

 os.setsid()  该方法做一系列的事： session_id
  1.首先它使得该进程成为一个新会话的领导者
  2.接下来它将进程转变一个新进程组的领导者
  3.最后该进程不再控制终端,
  4.运行的时候，建立一个进程，linux会分配个进程号。然后调用os.fork()创建子进程。若pid>0就是自己，自杀。子进程跳过if语句，通过os.setsid()成为linux中的独立于终端的进程（不响应sigint，sighup等）。


os.setpgrp()
  当前所有组

  
```
