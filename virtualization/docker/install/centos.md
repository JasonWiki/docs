# Docker

## 一. 安装

### * 准备工作

``` sh
# 卸载老版本
sudo yum remove docker \
                  docker-client \
                  docker-client-latest \
                  docker-common \
                  docker-latest \
                  docker-latest-logrotate \
                  docker-logrotate \
                  docker-selinux \
                  docker-engine-selinux \
                  docker-engine

# 删除
rm -fr /var/lib/docker/
```

### 1. yum 方式

- [参考文档](https://yq.aliyun.com/articles/110806?spm=5176.8351553.0.0.68241991bRWxG1)

``` sh
# step 1: 安装必要的一些系统工具
sudo yum install -y yum-utils device-mapper-persistent-data lvm2

# Step 2: 添加软件源信息
sudo yum-config-manager --add-repo http://mirrors.aliyun.com/docker-ce/linux/centos/docker-ce.repo

  安装后的文件地址在: /etc/yum.repos.d/docker-ce.repo

# Step 3: 更新并安装 Docker-CE
sudo yum makecache fast

sudo yum -y install docker-ce

# Step 4: 开启Docker服务
sudo service docker restart

# Step 5: 安装校验
docker version

# 当前用户添加到 docker 组中
sudo groupadd docker
sudo gpasswd -a ${USER} docker

# 注意：
# 官方软件源默认启用了最新的软件，您可以通过编辑软件源的方式获取各个版本的软件包。例如官方并没有将测试版本的软件源置为可用，你可以通过以下方式开启。同理可以开启各种测试版本等。
# vim /etc/yum.repos.d/docker-ce.repo
#   将 [docker-ce-test] 下方的 enabled=0 修改为 enabled=1
#
# 安装指定版本的Docker-CE:
# Step 1: 查找Docker-CE的版本:
# yum list docker-ce.x86_64 --showduplicates | sort -r
#   Loading mirror speeds from cached hostfile
#   Loaded plugins: branch, fastestmirror, langpacks
#   docker-ce.x86_64            17.03.1.ce-1.el7.centos            docker-ce-stable
#   docker-ce.x86_64            17.03.1.ce-1.el7.centos            @docker-ce-stable
#   docker-ce.x86_64            17.03.0.ce-1.el7.centos            docker-ce-stable
#   Available Packages
# Step2 : 安装指定版本的Docker-CE: (VERSION 例如上面的 17.03.0.ce.1-1.el7.centos)
# sudo yum -y install docker-ce-[VERSION]
```


### 2. package 方式

``` sh
1. 转到 https://download.docker.com/linux/centos/7/x86_64/stable/Packages/ 并下载 .rpm 要安装的 Docker 版本的文件。

2. 安装 Docker CE，将下面的路径更改为您下载 Docker 软件包的路径。

  sudo yum install /your_path/docker-ce-18.03.1.ce-1.el7.centos.x86_64.rpm
```


### 配置镜像加速器

``` sh
# 配置镜像加速器
sudo mkdir -p /etc/docker

# 可用的镜像资源有
阿里云:      https://registry.docker-cn.com
阿里云私人：  https://uy1w23te.mirror.aliyuncs.com  (需要去私人账号注册才可使用， 注册地址 https://cr.console.aliyun.com/cn-shanghai/mirrors)
中科大：     https://docker.mirrors.ustc.edu.cn （参考手册 https://lug.ustc.edu.cn/wiki/mirrors/help/docker）
网易：       http://hub-mirror.c.163.com
官方：       https://registry.docker-cn.com

# 写入配置(使用私人阿里云)
sudo tee /etc/docker/daemon.json <<-'EOF'
{
  "registry-mirrors": ["https://uy1w23te.mirror.aliyuncs.com"]
}
EOF

# 重启服务
sudo systemctl daemon-reload
sudo systemctl restart docker

```


## 二. 启动关闭

``` sh
启动/重启 Docker
  # 启动
  sudo systemctl start docker

  # 重启
  sudo systemctl restart docker.service
```
