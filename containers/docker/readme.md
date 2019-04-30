# Docker

## 一. 介绍

- Docker生态系统需要了解的核心概念主要有三大组件

``` doc
Docker 镜像 - Docker images
Docker 仓库 - Docker registeries
Docker 容器 - Docker containers
```

- Docker 容器 本质是一个进程

``` doc
实际上是一个由 Linux Namespace、Linux Cgroups、rootfs 三种技术构建出来的进程的隔离环境

容器镜像（Container Image）: 一组联合挂载在 /var/lib/docker/aufs/mnt 上的 rootfs，是容器的静态视图

容器运行时（Container Runtime）: 一个由 Namespace + Cgroups 构成的隔离环境，是容器的动态视图
```

- Docker 实现原理

``` doc
Namespaces(进程、网络、文件系统的隔离)
   1. Linux 为我们提供的用于分离（进程树、网络接口、挂载点以及进程间通信等—）资源的方法。
      PID Namespaces  进程隔离
      NET Namespaces  管理网络接口
      IPC Namespaces  管理进程间通讯
      MNT Namespaces  管理 Mount 点
      UTS Namespaces  隔离内核和版本信息

   2. Docker 其实就通过 Linux 的 Namespaces 对不同的容器实现了隔离

   3. Linux 的命名空间机制提供了以下七种不同的命名空间
      CLONE_NEWCGROUP、CLONE_NEWIPC、CLONE_NEWNET、CLONE_NEWNS、CLONE_NEWPID、CLONE_NEWUSER、CLONE_NEWUTS
      通过这七个选项我们能在创建新的进程时设置新进程应该在哪些资源上与宿主机器进行隔离

   4. 进程隔离
      a) 进程是 Linux 以及现在操作系统中非常重要的概念，它表示一个正在执行的程序，也是在现代分时系统中的一个任务单元。
      b) Linux 的命名空间实现进程的隔离
      containerRouter.postContainersStart
      └── daemon.ContainerStart
          └── daemon.createSpec
              └── setNamespaces
                  └── setNamespace
      c) setNamespaces 方法中不仅会设置进程相关的命名空间，还会设置与用户、网络、IPC 以及 UTS 相关的命名空间

    5. 网络
      a) 每一个使用 docker run 启动的容器其实都具有单独的网络命名空间
      b) Docker 为我们提供了四种不同的网络模式 <Host、Container、None、Bridge>
      c) Docker 的容器需要将服务暴露给宿主机器，就会为容器分配一个 IP 地址，同时向 iptables 中追加一条新的规则

    6. libnetwork
      a) 整个网络部分的功能都是通过 Docker 拆分出来的 libnetwork 实现的
        它提供了一个连接不同容器的实现, 同时也能够为应用给出一个能够提供一致的编程接口和网络层抽象的 <容器网络模型>
      b) libnetwork 中最重要的概念，容器网络模型由以下的几个主要组件组成，分别是 <Sandbox、Endpoint、Network>

    7. 挂载点
        a) Docker 容器中的进程仍然能够访问或者修改宿主机器上的其他目录，这是我们不希望看到的
        b) 在新的进程中创建隔离的挂载点命名空间需要在 clone 函数中传入 CLONE_NEWNS
          这样子进程就能得到父进程挂载点的拷贝，如果不传入这个参数 <子进程对文件系统的读写都会同步回父进程以及整个主机的文件系统>
        c) 一个容器需要启动
          一定需要提供一个根文件系统（rootfs），容器需要使用这个文件系统来创建一个新的进程，所有二进制的执行都必须在这个根文件系统中
        d) libcontainer
          为了保证当前的容器进程没有办法访问宿主机器上其他目录，还需要通过 libcontainer 提供的 pivot_root 或者 chroot 函数改变进程能够访问个文件目录的根节点

    8. chroot （change root）
      在 Linux 系统中，系统默认的目录就都是以 / 也就是根目录开头的。
      chroot 能够改变进程运行时的工作目录，并且能够限定在这个目录中，只能做简单的隔离，存在安全隐患。
      所以 Docker 设计了 Layered FS, 把文件系统分为多个层, 使多个容器间可以使用, 层公共的部分。
      镜像就是由 Layered FS 组成的, 并且它是只读的。当容器运行时，会在镜像上再加一层可读写层。


CGroups(物理资源隔离)
  1. Control Groups（简称 CGroups）能够隔离宿主机器上的物理资源 <CPU、内存、磁盘 I/O 和网络带宽>
    CGroups 可以限定容器使用的硬件资源，内存容量，CPU 数量等。

  2. Control Group 能够为一组进程分配资源(CPU、内存、网络带宽等), 通过对资源的分配，CGroup 能够提供以下的几种功能
    在 CGroup 中，所有的任务就是一个系统的一个进程，而 CGroup 就是一组按照某种标准划分的进程.
    在 CGroup 这种机制中，所有的资源控制都是以 CGroup 作为单位实现的，每一个进程都可以随时加入一个 CGroup 也可以随时退出一个 CGroup

  3. Linux 使用文件系统来实现 CGroup，我们可以直接使用下面的命令查看当前的 CGroup 中有哪些子系统
    命令:   lssubsys -m
    目录:   ls /sys/fs/cgroup

  4. Linux 上安装了 Docker，你就会发现所有子系统的目录下都有一个名为 docker 的文件夹
      ls /sys/fs/cgroup/cpu/docker


UnionFS (Union File System)
  1. Docker 镜像就是一个文件
    Docker 中的每一个镜像都是由一系列只读的层组成的，Dockerfile 中的每一个命令都会在已有的只读层上创建一个新的层
      FROM ubuntu:15.04
      COPY . /app
      RUN make /app
      CMD python /app/app.py

  2. 容器和镜像的区别
    所有的镜像都是只读的，而每一个容器其实等于镜像加上一个可读写的层，也就是同一个镜像可以对应多个容器

  3. UnionFS
    a) UnionFS 其实是一种为 Linux 操作系统设计的用于把多个文件系统『联合』到同一个挂载点的文件系统服务

    b) 联合挂载（Union Mount）
      AUFS 作为联合文件系统，它能够将不同文件夹中的层联合（Union）到了同一个文件夹中，这些文件夹在 AUFS 中称作分支，整个『联合』的过程被称为联合挂载（Union Mount）
      AUFS 只是 Docker 使用的存储驱动的一种，除了 AUFS 之外，Docker 还支持了不同的存储驱动，包括 aufs、devicemapper、overlay2、zfs 和 vfs 等等
      在最新的 Docker 中，overlay2 取代了 aufs 成为了推荐的存储驱动，但是在没有 overlay2 驱动的机器上仍然会使用 aufs 作为 Docker 的默认驱动

    c) 镜像层和容器层存储地址
      每一个镜像层或者容器层都是 /var/lib/docker/ 目录下的一个子文件夹；在 Docker 中，所有镜像层和容器层的内容都存储在 /var/lib/docker/aufs/diff/ 目录中

    d) 挂在结构
      只有每个容器最顶层的容器层才可以被用户直接读写，所有的容器都建立在一些底层服务（Kernel）上包括命名空间、控制组、rootfs 等等
      这种容器的组装方式提供了非常大的灵活性，只读的镜像层通过共享也能够减少磁盘的占用。
```


### 1. Docker images

- 一个特殊的文件系统
- 镜像是用于创建 Docker 容器的模板

Docker 镜像是 Docker 容器运行时的只读模板，每一个镜像由一系列的层 (layers) 组成。Docker 使用 UnionFS 来将这些层联合到单独的镜像中。UnionFS 允许独立文件系统中的文件和文件夹(称之为分支)被透明覆盖，形成一个单独连贯的文件系统。正因为有了这些层的存在，Docker 是如此的轻量。当你改变了一个 Docker 镜像，比如升级到某个程序到新的版本，一个新的层会被创建。因此，不用替换整个原先的镜像或者重新建立(在使用虚拟机的时候你可能会这么做)，只是一个新 的层被添加或升级了。现在你不用重新发布整个镜像，只需要升级，层使得分发 Docker 镜像变得简单和快速。


### 2. Docker containers

- 镜像运行时的实体
- 容器是独立运行的一个或一组应用

Docker 容器和文件夹很类似，一个Docker容器包含了所有的某个应用运行所需要的环境。每一个 Docker 容器都是从 Docker 镜像创建的。Docker 容器可以运行、开始、停止、移动和删除。每一个 Docker 容器都是独立和安全的应用平台，Docker 容器是 Docker 的运行部分。


### 3. Docker registeries

- 集中存放镜像文件的地方
- Docker 仓库用来保存镜像，可以理解为代码控制中的代码仓库

Docker Hub是类似于Github的一种代码仓库，同样的，Docker 仓库也有公有和私有的概念。公有的 Docker 仓库名字是 Docker Hub。Docker Hub 提供了庞大的镜像集合供使用。这些镜像可以是自己创建，或者在别人的镜像基础上创建。Docker 仓库是 Docker 的分发部分。


### 4. Build，Ship，Run

- Build（构建镜像）：镜像就像是集装箱包括文件以及运行环境等等资源。
- Ship（运输镜像）：主机和仓库间运输，这里的仓库就像是超级码头一样。
- Run （运行镜像）：运行的镜像就是一个容器，容器就是运行程序的地方。
