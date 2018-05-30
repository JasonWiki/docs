# threading 多线程处理工具


## 单线程

### 1.案例

``` python

from time import ctime,sleep

def music():
    for i in range(2):
        print "I was listening to music. %s" %ctime()
        sleep(1)

def move():
    for i in range(2):
        print "I was at the movies! %s" %ctime()
        sleep(5)

if __name__ == '__main__':
    music()
    move()
    print "all over %s" %ctime()

```


## 多线程

### 1.案例

``` python

import threading
from time import ctime,sleep


def music(func):
    for i in range(2):
        print "I was listening to %s. %s" %(func,ctime())
        sleep(1)

def move(func):
    for i in range(2):
        print "I was at the %s! %s" %(func,ctime())
        sleep(5)

#多线程配置
t1 = threading.Thread(target=music,args=(u'GD',))
t2 = threading.Thread(target=move,args=(u'阿凡达',))

if __name__ == '__main__':

    #所有多线程一起启动

    #设置非守护进程，父进程会等待所有子进程执行完毕，父进程才会退出
    t1.setDaemon(False)
    t1.start()

    #设置守护进程 (子线程启动后，父进程不等待子进程继续执行，当父进程执行完毕，子进程也一起退出)
    t2.setDaemon(True)
    t2.start();

    print "all over %s" %ctime()

    #当前运行中的Thread对象列表
    for item in threading.enumerate():
        print item

```
