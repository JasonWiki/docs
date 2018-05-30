# Queue 队列模块


# 队列与并发使用案例

``` python

#coding=utf-8
import threading
from time import ctime,sleep
import Queue

#only for test sleep in random mode
import random

# 执行方法实体
def extract():
    # 不停监控队列变化
    while True:
        # 取出队列中的值
        i = q.get()
        print i

        #sleep(random.randint(1, 10))
        sleep(2)

        # 向任务已经完成的队列发送一个信号
        q.task_done()


if __name__ == '__main__':

    # 定义先进先出的队列
    q = Queue.Queue()

    # 设置线程数
    num_thread_pool=5

    # 等待放入队列的 1 - 50 的 list
    srctbl = range(1, 50)

    # list 放入队列中
    for i in range(0, len(srctbl)):
      curTb = (srctbl[i],i)
      q.put(curTb)

    # 开一个线程消费队列
    #extract()

    # 开 5 个线程, 消费队列
    for i in range(num_thread_pool):
      # 并发执行每个任务
      th = threading.Thread(target=extract)
      # 非守护进程，父进程会等待所有子进程执行完毕，父进程才会退出
      #th.setDaemon(False)
      # 守护进程 (子线程启动后，父进程不等待子进程结束,继续执行，当父进程执行完毕，子进程也一起退出)
      th.setDaemon(True)
      th.start()


    # 等待队列为空再执行
    q.join()

    print "all over %s" %ctime()


````
