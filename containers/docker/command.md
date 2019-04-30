# Docker

## 一. 命令

``` sh
# 命令帮助
docker [command] --help
docker stats --help


# docker run 命令
docker run [OPTIONS] IMAGE [COMMAND] [ARG...]

## 使用 docker 在 ubuntu:15.10 镜像中运行 /bin/echo 命令，的打印 "Hello world"
sudo docker run ubuntu:15.10 /bin/echo "Hello world"

  docker: Docker 的二进制执行文件。

  run:    与前面的 docker 组合来运行一个容器。

  ubuntu:15.10  指定要运行的镜像，Docker首先从本地主机上查找镜像是否存在，如果不存在，Docker 就会从镜像仓库 Docker Hub 下载公共镜像。

  /bin/echo "Hello world": 在启动的容器里执行的命令


## 运行一个镜像，使用 bash
sudo docker run -i -t ubuntu:15.10 /bin/bash

  -t: 在新容器内指定一个伪终端或终端。

  -i: 允许你对容器内的标准输入 (STDIN) 进行交互。


## 启动容器（后台模式）
sudo docker run -d ubuntu:15.10 /bin/sh -c "while true; do echo hello world; sleep 1; done"

    -d: 表示后台运行


# 查看容器运行的日志
sudo docker logs <[CONTAINER ID] | [CONTAINER NAME]>

  -f  让 docker logs 像使用 tail -f 一样来输出容器内部的标准输出。

  sudo docker logs -f <[CONTAINER ID] | [CONTAINER NAME]>


# 查看运行的所有 docker 容器
sudo docker ps

  -a 为查看所有的容器，包括已经停止的


# 进入一个运行的进项
docker exec -it <[CONTAINER ID]> /bin/bash


# 停止容器
sudo docker stop <[CONTAINER ID] | [CONTAINER NAME]>


# kill 容器
docker kill <[CONTAINER ID] | [CONTAINER NAME]>


# 删除单个容器
docker rm <[CONTAINER ID] | [CONTAINER NAME]>

```


## 镜像

``` sh
# 查看本地所有镜像
docker images <镜像名称>
  docker images nginx

# 查找镜像
sudo docker search <[IMAGE NAME]>


# 删除镜像
sudo docker rmi <[IMAGE NAME]:[TAG NAME]>


# 镜像历史
sudo docker history centos


# 创建镜像的 3 大方式

## 1. 拉取镜像
### 拉去指定镜像
sudo docker pull [域名/][用户名/]镜像名[:版本号]
  [域名/]:     指定了域名会从该域名进行下载, 负责从 /etc/docker 配置中的镜像下载
  [用户名/]:   用户名是隶属于该域名下的子目录, 使用私有仓库时有用
  镜像名:      这是必填项
  [:版本号]:   默认是 latest, 可以指定版本号(建议)

### 拉取案例
sudo docker pull ubuntu:latest


## 2. 通过 commit 把一个运行的容器转换为镜像
### 更新镜像
sudo docker commit -m="add test" -a="jason" <[CONTAINER ID]> centos:mytest-v1
  -m                    提交的描述信息
  -a                    镜像作者
  <[CONTAINER ID]>      容器 ID
  centos:mytest-v1      自定义镜像名称

### 通过 centos:mytest-v1 运行容器
sudo docker run -t -i centos:mytest-v1 /bin/bash

### 查找 centos:mytest-v1 镜像的容器 ID
sudo docker ps

### 进入 centos:mytest-v1 镜像运行的容器中
sudo docker exec -it <[CONTAINER ID]> /bin/bash


## 3. 使用 Dockerfile 生成镜像
### 编辑 Dockerfile 文件
### dockerfile reference https://docs.docker.com/engine/reference/builder/  
### bestpractice https://docs.docker.com/engine/userguide/eng-image/dockerfile_best-practices/
### vim Dockerfile 打开文件
FROM    centos:6.7
MAINTAINER      Fisher "fisher@sudops.com"

RUN     /bin/echo 'root:123456' |chpasswd
RUN     useradd runoob
RUN     /bin/echo 'runoob:123456' |chpasswd
RUN     /bin/echo -e "LANG=\"en_US.UTF-8\"" >/etc/default/local
EXPOSE  22
EXPOSE  80
CMD     /usr/sbin/sshd -D

### 构建命令
docker build -t <镜像名> <Dockerfile路径>
  如 Dockerfile 在当前路径：docker build -t xx/gitlab .

  -t  指定要创建的目标镜像名
  .   Dockerfile 文件所在目录，可以指定Dockerfile 的绝对路径

### 构建
docker build -t runoob/centos:6.7 .

### 进入镜像
docker run -t -i runoob/centos:6.7  /bin/bash



# push 镜像到 docker hub 个人的仓库

## 1. 注册 Docker Hub 账号
https://hub.docker.com

## 2. 登录 Docker Hub
sudo docker login

## 3. 镜像打上 tag 标签
#### docker tag 命令，为镜像添加一个新的标签, 为同一个 IMAGE ID 的镜像, 创建一个新的标签
docker tag <[IMAGE ID]|[IMAGE NAME]>:<TAG NAME> <IMAGE NAME>:<TAG NAME>
  <[IMAGE ID]|[IMAGE NAME]>:<TAG NAME>     <本地镜像 ID | 本地镜像名>:标签名
  <IMAGE NAME>:<TAG NAME>                  <本地镜像 ID | 本地镜像名>:标签名

#### 例如为 centos:mytest 打上 jasonviki/centos:mytest 标签
sudo docker tag centos:mytest jasonviki/centos:mytest

## 4. 上传到个人的 Docker 仓库(注意的是 jasonviki 是个人 Docker Hub 前缀)
sudo docker push jasonviki/centos:mytest

## 5. 查看镜像信息
https://hub.docker.com

```


## 网络

``` sh
# 查看 docker 网络
docker network ls


# 查看网络详情
docker network inspect [bridge|host]


# 后台运行(-d)、并暴露端口(-p)
docker run -d -p 127.0.0.1:33301:22 centos6-ssh


# 运行一个web应用
docker run -d -P training/webapp python app.py

  -d : 让容器在后台运行

  -P : 是容器内部端口, 随机映射到主机的端口

  -p : 是容器内部端口, 绑定到指定的主机端口


# 指定端口映射端口运行
sudo docker run -d -p 5001:5000 training/webapp python app.py

  -p 宿主机端口:内部端口


# 指定容器绑定的网络地址，比如绑定 127.0.0.1
sudo docker run -d -p 127.0.0.1:5001:5000 training/webapp python app.py


# 默认使用 tcp, 这里可选 udp
sudo docker run -d -p 5002:5000/udp training/webapp python app.py


# 查看端口的绑定情况
sudo docker port thirsty_dewdney 5000

  <[CONTAINER ID] | [CONTAINER NAME]>  容器名或者容器ID
  [port] 可选


# 查看 WEB 应用程序容器的进程(我们还可以使用 docker top 来查看容器内部运行的进程)
sudo docker top <[CONTAINER ID] | [CONTAINER NAME]>


# 检查 WEB 应用程序(查看 Docker 的底层信息。它会返回一个 JSON 文件记录着 Docker 容器的配置和状态信息)
sudo docker inspect <[CONTAINER ID] | [CONTAINER NAME]>

```


## 其他

``` sh


```
