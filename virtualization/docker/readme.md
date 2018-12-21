# Docker

## 一. 介绍

Docker生态系统需要了解的核心概念主要有三大组件
``` doc
Docker 镜像 - Docker images
Docker 仓库 - Docker registeries
Docker 容器 - Docker containers
```

Docker 容器 本质是一个进程
``` doc
实际上是一个由 Linux Namespace、Linux Cgroups、rootfs 三种技术构建出来的进程的隔离环境。

一组联合挂载在 /var/lib/docker/aufs/mnt 上的 rootfs，这一部分我们称为“容器镜像”（Container Image），是容器的静态视图；
一个由 Namespace + Cgroups 构成的隔离环境，这一部分我们称为“容器运行时”（Container Runtime），是容器的动态视图。
```

### 1. Docker images

Docker 镜像是用于创建 Docker 容器的模板

Docker 镜像是 Docker 容器运行时的只读模板，每一个镜像由一系列的层 (layers) 组成。Docker 使用 UnionFS 来将这些层联合到单独的镜像中。UnionFS 允许独立文件系统中的文件和文件夹(称之为分支)被透明覆盖，形成一个单独连贯的文件系统。正因为有了这些层的存在，Docker 是如此的轻量。当你改变了一个 Docker 镜像，比如升级到某个程序到新的版本，一个新的层会被创建。因此，不用替换整个原先的镜像或者重新建立(在使用虚拟机的时候你可能会这么做)，只是一个新 的层被添加或升级了。现在你不用重新发布整个镜像，只需要升级，层使得分发 Docker 镜像变得简单和快速。


### 2. Docker registeries

Docker 仓库用来保存镜像，可以理解为代码控制中的代码仓库

Docker Hub是类似于Github的一种代码仓库，同样的，Docker 仓库也有公有和私有的概念。公有的 Docker 仓库名字是 Docker Hub。Docker Hub 提供了庞大的镜像集合供使用。这些镜像可以是自己创建，或者在别人的镜像基础上创建。Docker 仓库是 Docker 的分发部分。


### 3. Docker containers

容器是独立运行的一个或一组应用

Docker 容器和文件夹很类似，一个Docker容器包含了所有的某个应用运行所需要的环境。每一个 Docker 容器都是从 Docker 镜像创建的。Docker 容器可以运行、开始、停止、移动和删除。每一个 Docker 容器都是独立和安全的应用平台，Docker 容器是 Docker 的运行部分。
