# subprocess

优秀文章
- [文章地址：](http://www.osetc.com/archives/13697.html)
- [文章地址：](http://www.oschina.net/question/234345_52660)

原理：

它在 Linux/Unix 平台下的实现方式是 fork(创建进程的函数，它所创建的进程是子进程，是父进程的拷贝) 产生子进程然后 exec(执行) 载入外部可执行程序

### OPEN() 方法


``` python

可以和主程序一起执行

class subprocess.Popen(
      args,
      bufsize=0,
      executable=None,
      stdin=None,
      stdout=None,
      stderr=None,
      preexec_fn=None,
      close_fds=False,
      shell=False,
      cwd=None,
      env=None,
      universal_newlines=False,
      startupinfo=None,
      creationflags=0)


-- 参数
  args
    可以是字符串或者序列类型（如：list，元组），用于指定进程的可执行文件及其参数。如果是序列类型，第一个元素通常是可执行文件的路径。我们也可以显式的使用executeable参数来指定可执行文件的路径。


  bufsize
    指定缓冲。0 无缓冲,1 行缓冲,其他 缓冲区大小,负值 系统缓冲(全缓冲)  


  stdin, stdout, stderr
    分别表示程序的标准输入、输出、错误句柄。他们可以是PIPE，文件描述符或文件对象，也可以设置为None，表示从父进程继承。

    stdin  标准输入

    stdout 标准输出

    stderr 错误句柄

    subprocess.PIPE 管道
      用于 Popen 的 stdin 、stdout 和 stderr 3个参数
    subprocess.STDOUT
      用于 Popen 的 stderr 参数的输出值


  preexec_fn
    只在Unix平台下有效，用于指定一个可执行对象（callable object），它将在子进程运行(所有有子进程的运行)之前被调用。

    1) 自从被运行程序 fork 以后，产生的子进程都享有独立的进程空间和 pid
    2) 并在 fork 之后 exec 之前的间隙中执行它。我们可以利用这个特性对被运行的子进程做出一些修改，比如执行 setsid() 成立一个独立的进程组
    3) Linux 的进程组是一个进程的集合，任何进程用系统调用 setsid 可以创建一个新的进程组，并让自己成为首领进程。首领进程的子子孙孙只要没有再调用 setsid 成立自己的独立进程组，那么它都将成为这个进程组的成员。 之后进程组内只要还有一个存活的进程，那么这个进程组就还是存在的，即使首领进程已经死亡也不例外。 而这个存在的意义在于，我们只要知道了首领进程的 pid (同时也是进程组的 pgid)， 那么可以给整个进程组发送 signal，组内的所有进程都会收到。
    4) 因此利用这个特性，就可以通过 preexec_fn 参数让 Popen 成立自己的进程组， 然后再向进程组发送 SIGTERM 或 SIGKILL，中止 subprocess.Popen 所启动进程的子子孙孙。当然，前提是这些子子孙孙中没有进程再调用 setsid 分裂自立门户

    所以杀掉子进程的所有上下文用
      p = subprocess.Popen(command,shell=True,preexec_fn=os.setsid,stdin=sys.stdin, stdout=None, stderr=None)

  close_fds
    如果 close_fds 被设置为 True，则新创建的子进程将不会继承父进程的输入、输出、错误管 道。
    不能将 close_fds 设置为 True 同时重定向子进程的标准输入、输出与错误(stdin, stdout, stderr)。


  shell
    如果 shell=True
    unix下相当于args前面添加了 "/bin/sh“ ”-c”

    window下，相当于添加"cmd.exe /c"


  cwd
    用于设置子进程的当前目录


  env
    是字典类型，用于指定子进程的环境变量。如果env = None，子进程的环境变量将从父进程中继承。


  universal_newlines
    不同操作系统下，文本的换行符是不一样的。
    如：windows下用’/r/n’表示换，而Linux下用’/n’。
    如果将此参数设置为True，Python统一把这些换行符当作’/n’来处理。



-- Open 类的方法

  1)、Popen.poll()：用于检查子进程是否已经结束。设置并返回returncode属性。

  2)、Popen.wait()：等待子进程结束。设置并返回returncode属性。主程序不会自动等待子进程完成，所以如果要主程序等待子程序知心完成则设置此参数。

  3)、Popen.communicate(input=None)：与子进程进行交互。向stdin发送数据，或从stdout和stderr中读取数据。可选参数input指定发送到子进程的参数。Communicate()返回一个元组：(stdoutdata, stderrdata)。注意：如果希望通过进程的stdin向其发送数据，在创建Popen对象的时候，参数stdin必须被设置为PIPE。同样，如果希望从stdout和stderr获取数据，必须将stdout和stderr设置为PIPE。

  4)、Popen.send_signal(signal)：向子进程发送信号。

  5)、Popen.terminate()：停止(stop)子进程。在windows平台下，该方法将调用Windows API TerminateProcess（）来结束子进程。

  6)、Popen.kill()：杀死子进程。

  7)、Popen.stdin：如果在创建Popen对象时，参数stdin被设置为PIPE，Popen.stdin将返回一个文件对象用于策子进程发送指令。否则返回None。

  8)、Popen.stdout：如果在创建Popen对象时，参数stdout被设置为PIPE，Popen.stdout 将返回一个文件对象用于策子进程发送指令。否则返回None。

  9)、Popen.stderr：如果在创建Popen对象时，参数stdout被设置为PIPE，Popen.stdout将返回一个文件对象用于策子进程发送指令。否则返回None。

  10)、Popen.pid：获取子进程的进程ID。

  11)、Popen.returncode：获取进程的返回值。如果进程还没有结束，返回None。

  12)、subprocess.call(*popenargs, **kwargs)：运行命令。该函数将一直等待到子进程运行结束，并返回进程的returncode。文章一开始的例子就演示了call函数。如果子进程不需要进行交互,就可以使用该函数来创建。

  13)、subprocess.check_call(*popenargs, **kwargs)：与subprocess.call(*popenargs, **kwargs)功能一样，只是如果子进程返回的returncode不为0的话，将触发CalledProcessError异常。在异常对象中，包括进程的returncode信息。



-- 使用案例

  1、Open 方法
  #subprocess.PIPE
  #标准输出 stdout，输出到管道中 subprocess.PIPE
  child1 = subprocess.Popen(["ls","-l"], stdout = subprocess.PIPE)
  #把 child1 输出，
  child2 = subprocess.Popen(["wc"], stdin = child1.stdout , stdout = subprocess.PIPE)

  out = child2.communicate()

  print out



  p = subprocess.call(command,shell=True, stdout=subprocess.PIPE, stderr=subprocess.STDOUT)
  #Popen对象创建后，主程序不会自动等待子进程完成。我们必须调用对象的wait()
  p.wait()
  #用于检查子进程是否已经结束。设置并返回returncode属性。
  p.poll()


  #表示可以杀掉子进程的函数(退出上下文的时候清理现场，也就是结束被跑起来的子进程)
  p = subprocess.Popen(command,shell=True,preexec_fn=os.setsid,stdin=sys.stdin, stdout=None, stderr=None)


  #给子进程输入
  child = subprocess.Popen(["cat"], stdin=subprocess.PIPE)
  child.communicate("vamei") #不为空，则写入subprocess.PIPE，为空，则从subprocess.PIPE读取







```


## CALL 方法

``` python

必须等待命令执行完毕后

```
