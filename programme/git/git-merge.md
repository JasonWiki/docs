# git merge 合并仓技巧

## 一. 通过私有仓库 维护 开源仓库代码


### 1. 初始化私有仓库

``` doc
# 创建/下载 私有仓库
git clone https://gitee.com/techonline/tidb-ansible.git

# 进入 私有仓库
cd tidb-ansible

# 添加 开源仓库 地址
git remote add source https://github.com/pingcap/tidb-ansible.git

# 查看所有仓库地址
git remote -v

# 获取 开源仓库 到 本地仓库
git pull --rebase source master

# 提交 本地仓库 到 私有仓库
git push origin master
```


### 2. 通过开源仓库制作私有仓库

``` doc
##### 方法一 通过 tag 方式 #####

# 获取 开源仓库 指定 tag
git fetch source tag [tagname]
git checkout -b [tagname]


# 通过 开源仓库 tag 打出 branch 用于修改。 例如基于 tag v3.0.1 创建分支  tag-v3.0.1-v1
git checkout -b [branchName] [tagName]
# 例如
git checkout -b tag-v3.0.1-v1 v3.0.1

# 修改后的内容, 提交到 私有 仓库中
git push origin [tagname-v1]:[tagname-v1]
# 例如
git push origin tag-v3.0.1-v1:tag-v3.0.1-v1


# 编辑 本地仓库 内容后提交 tag 到 私有仓库
git tag -a [tagname-v1] -m "tagname 修改"









# 直接获取 私有仓库 指定 tag
git clone -b [tagname-v1] https://gitee.com/techonline/tidb-ansible.git


git checkout -b <branchName> <tagName>

git checkout -b aaaa v3.0.1



##### 方法二 通过 branch 方式 #####

# 获取 开源仓库 指定 branch
git fetch source [branchname]:[branchname-v1]

# 编辑 本地仓库 内容后提交 branchname 到 私有仓库
git checkout [branchname-v1]
git push add -A
git commit -m 'branch 修改内容'
git push origin branchname-v1:branchname-v1

# 获取 私有仓库 指定 branchname
git fetch origin branchname-v1:branchname-v1
git chekcout branchname-v1
```


### 3. 更新开源仓库到私有仓库

``` doc
##### 通过 branch 方式 #####
git clone https://gitee.com/techonline/tidb-ansible.git
git checkout master
git pull --rebase origin master


#  直接获取指定 tag
git clone -b v3.0.1-v1 https://gitee.com/techonline/tidb-ansible.git
```
