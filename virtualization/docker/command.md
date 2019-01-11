# Docker

## 一. 命令

``` sh
# 命令帮助
docker [command] --help
docker stats --help


# 使用 docker 在 ubuntu:15.10 镜像中运行 /bin/echo 命令，的打印 "Hello world"
sudo docker run ubuntu:15.10 /bin/echo "Hello world"

  docker: Docker 的二进制执行文件。

  run:与前面的 docker 组合来运行一个容器。

  ubuntu:15.10指定要运行的镜像，Docker首先从本地主机上查找镜像是否存在，如果不存在，Docker 就会从镜像仓库 Docker Hub 下载公共镜像。

  /bin/echo "Hello world": 在启动的容器里执行的命令


# 运行一个镜像，使用 bash
sudo docker run -i -t ubuntu:15.10 /bin/bash

  -t:在新容器内指定一个伪终端或终端。

  -i:允许你对容器内的标准输入 (STDIN) 进行交互。


# 启动容器（后台模式）
sudo docker run -d ubuntu:15.10 /bin/sh -c "while true; do echo hello world; sleep 1; done"

    -d: 表示后台运行


# 查看容器运行的日志
sudo docker logs <[CONTAINER ID] | [CONTAINER NAME]>

  -f  让 docker logs 像使用 tail -f 一样来输出容器内部的标准输出。

  sudo docker logs -f <[CONTAINER ID] | [CONTAINER NAME]>


```

## 镜像

``` sh
# 查看所有镜像
docker images


# 预先下载一个镜像
sudo docker pull <镜像名:tag>
  docker pull ubuntu:latest


# 运行最新的 ubuntu:latest 镜像(如果镜像不存在会拉去)
sudo docker run -t -i ubuntu:latest /bin/bash


# 查找镜像
sudo docker search httpd


# 创建镜像
## 1. 从已经创建的容器中更新镜像，并且提交这个镜像
## 2. 使用 Dockerfile 指令来创建一个新的镜像

# 更新镜像
sudo docker commit -m="update" -a="runoob" 8dfcff08367f runoob/ubuntu:v2

  -m: 提交的描述信息
  -a: 镜像作者
  8dfcff08367f: 容器 ID
  runoob/ubuntu:v2    镜像名称:镜像 Tag


# 进入新的镜像
sudo docker run -t -i runoob/ubuntu:v2 /bin/bash


# 构建镜像




# 构建自己的镜像
docker build -t <镜像名> <Dockerfile路径>
  如 Dockerfile 在当前路径：
  docker build -t xx/gitlab .
```

## 网络



``` sh

# 后台运行(-d)、并暴露端口(-p)
docker run -d -p 127.0.0.1:33301:22 centos6-ssh


# 运行一个web应用
docker run -d -P training/webapp python app.py
  -d: 让容器在后台运行。

  -P:  将容器内部使用的网络端口映射到我们使用的主机上。


# 指定端口映射端口运行
sudo docker run -d -p 5001:5000 training/webapp python app.py

  -p 宿主机端口:内部端口


# 查看容器网络端口
sudo docker port <[CONTAINER ID] | [CONTAINER NAME]>


# 查看 WEB 应用程序容器的进程(我们还可以使用 docker top 来查看容器内部运行的进程)
sudo docker top <[CONTAINER ID] | [CONTAINER NAME]>


# 检查 WEB 应用程序(查看 Docker 的底层信息。它会返回一个 JSON 文件记录着 Docker 容器的配置和状态信息)
sudo docker inspect <[CONTAINER ID] | [CONTAINER NAME]>


# 查看运行的所有 docker 容器
sudo docker ps

  -a 为查看所有的容器，包括已经停止的


# 停止容器
sudo docker stop <[CONTAINER ID] | [CONTAINER NAME]>


# kill 容器
docker kill <容器名orID>


# 删除单个容器
docker rm <容器名orID>


# dockerfile 文件的编写
dockerfile referencehttps://docs.docker.com/engine/reference/builder/
bestpractice https://docs.docker.com/engine/userguide/eng-image/dockerfile_best-practices/

```
