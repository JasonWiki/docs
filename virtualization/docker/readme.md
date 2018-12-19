# Docker

``` sh
sudo yum install docker-ce

1. 转到 https://download.docker.com/linux/centos/7/x86_64/stable/Packages/ 并下载.rpm要安装的Docker版本的文件。


2. 安装 Docker CE，将下面的路径更改为您下载 Docker 软件包的路径。

  sudo yum install docker-ce-18.03.1.ce-1.el7.centos.x86_64.rpm

3. 启动 Docker

  sudo systemctl start docker

  sudo systemctl restart docker.service

4. docker 通过运行 hello-world 映像验证是否已正确安装

  sudo docker run hello-world


x.
  sudo docker images


  /etc/sysconfig/docker

  /etc/docker/daemon.json

  {
  "registry-mirrors": ["http://hub-mirror.c.163.com"]
  }

  docker image ls
```



containerd.io-1.2.0-1.2.beta.2.el7.x86_64.rpm                                         2018-08-30 00:26:12 22.6 MiB
containerd.io-1.2.0-2.0.rc.0.1.el7.x86_64.rpm                                         2018-10-05 21:07:22 22.1 MiB
containerd.io-1.2.0-2.2.rc.2.1.el7.x86_64.rpm                                         2018-10-24 00:42:40 22.1 MiB
containerd.io-1.2.0-3.el7.x86_64.rpm                                                  2018-11-07 23:58:48 22.1 MiB
docker-ce-17.03.0.ce-1.el7.centos.x86_64.rpm                                          2018-06-08 17:48:30 18.6 MiB
docker-ce-17.03.1.ce-1.el7.centos.x86_64.rpm                                          2018-06-08 17:48:30 18.6 MiB
docker-ce-17.03.2.ce-1.el7.centos.x86_64.rpm                                          2018-06-08 17:48:30 18.6 MiB
docker-ce-17.03.3.ce-1.el7.x86_64.rpm                                                 2018-08-30 23:19:56 18.6 MiB
docker-ce-17.06.0.ce-1.el7.centos.x86_64.rpm                                          2018-06-08 17:48:30 20.6 MiB
docker-ce-17.06.1.ce-1.el7.centos.x86_64.rpm                                          2018-06-08 17:48:30 20.6 MiB
docker-ce-17.06.2.ce-1.el7.centos.x86_64.rpm                                          2018-06-08 17:48:31 20.6 MiB
docker-ce-17.09.0.ce-1.el7.centos.x86_64.rpm                                          2018-06-08 17:48:31 21.1 MiB
docker-ce-17.09.1.ce-1.el7.centos.x86_64.rpm                                          2018-06-08 17:48:31 21.1 MiB
docker-ce-17.12.0.ce-1.el7.centos.x86_64.rpm                                          2018-06-08 17:48:31 30.4 MiB
docker-ce-17.12.1.ce-1.el7.centos.x86_64.rpm                                          2018-06-08 17:48:31 30.4 MiB
docker-ce-18.03.0.ce-1.el7.centos.x86_64.rpm                                          2018-06-08 17:48:32 34.6 MiB
docker-ce-18.03.1.ce-1.el7.centos.x86_64.rpm                                          2018-06-08 17:48:32 34.6 MiB
docker-ce-18.06.0.ce-3.el7.x86_64.rpm                                                 2018-07-18 22:50:14 40.7 MiB
docker-ce-18.06.1.ce-3.el7.x86_64.rpm                                                 2018-08-21 23:02:46 40.7 MiB
docker-ce-18.09.0-3.el7.x86_64.rpm                                                    2018-11-07 23:58:48 18.7 MiB
docker-ce-cli-18.09.0-3.el7.x86_64.rpm                                                2018-11-07 23:58:48 14.0 MiB
docker-ce-selinux-17.03.0.ce-1.el7.centos.noarch.rpm                                  2018-06-08 17:48:32 28.4 KiB
docker-ce-selinux-17.03.1.ce-1.el7.centos.noarch.rpm                                  2018-06-08 17:48:33 28.4 KiB
docker-ce-selinux-17.03.2.ce-1.el7.centos.noarch.rpm                                  2018-06-08 17:48:33 28.4 KiB
docker-ce-selinux-17.03.3.ce-1.el7.noarch.rpm                                         2018-08-30 23:19:56 28.7 KiB
