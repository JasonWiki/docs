# Git 文档

## 概念

### branch 分支

``` doc
一个分支意味着一个独立的、拥有自己历史信息的代码线（code line）。
你可以从已有的代码中生成一个新的分支,这个分支与剩余的分支完全独立。默认的分支往往是叫master。
用户可以选择一个分支，选择一个分支叫做checkout.
如果你想开发一个新的产品功能，你可以建立一个分支，对这个分支的进行修改，而不至于会影响到主支上的代码。
```

### Repository 仓库

``` doc
一个仓库包括了所有的版本信息、所有的分支和标记信息.
在Git中仓库的每份拷贝都是完整的。仓库让你可以从中取得你的工作副本。
```

### Commit 提交

``` doc
提交代码后，仓库会创建一个新的版本。这个版本可以在后续被重新获得。
每次提交都包括作者和提交者，作者和提交者可以是不同的人
```

### URL 路径

``` URL
URl用来标识一个仓库的位置
```

### 远程仓库

``` doc
远端Git仓库和标准的Git仓库有如下差别：一个标准的Git仓库包括了源代码和历史信息记录。
我们可以直接在这个基础上修改代码，因为它已经包含了一个工作副本。
但是远端仓库没有包括工作副本，只包括了历史信息。可以使用–bare选项来创建一个这样的仓库。
git clone --bare . ../zun/rete.git		//克隆本地仓库到rete里
```

### 术语

``` doc
origin: 代码仓库源点
master: 主分支
branch: 主分支下的一个从分支
deploy: 测试分支
rebase: 很多人同时开发的时候，会有不同的分支线。rebase表示把别人版本比自己高的分支，合并到自己当前的分支中
```


## 操作指令

### 初始化

``` sh

# 初始化流程
$ ssh-keygen -t rsa -C "your_email@youremail.com"

一路回车，看到yes/no 选择yes
把生成的id_rsa.pub文件的内容复制到网站的key上

# 测试连接
$ ssh -T git@github.com

# 设置本地的账号和邮箱
$ git config --global user.name "your name"
$ git config --global user.email "your_email@youremail.com"

# 创建项目
1、网站上创建一个项目----然后（进入项目所在目录）
$ git init 	//初始化下
$ git remote add origin git@github.com:yourName/yourRepo.git		//项目的地址

touch README.md
git init
git add README.md
git commit -m "first commit"
git remote add origin git@github.com:cyndiWade/zun.git
git push -u origin master
```


### 分支操作

``` sh
# 使用时配置全局名
git config  --global user.name "cyndiWade"
git config  --global user.emal "zhanglin492103904@qq.com"
git config 	--list							//获取配置文件信息
cat ~/.gitconfig


# 查看分支
git remote -v

# 添加仓库源
git remote add origin git@git.corp.anjuke.com:aifang/aifang-branch

# 添加源 branch 和  origin 源
git remote add branch git@git.corp.anjuke.com:aifang/aifang-branch
git remote add origin git@git.corp.anjuke.com:aifang/aifang-site


# 选中
git add . 		

# 相当于 git add .; git commit; 。git commit -a无法把新增文件或文件夹加入进来	         
git commit -a  		

# 表示不提交某个修改文件			
git co filename.php


# 获取分支
# 其实pull命令完成了两个动作，首先从远端分支获取diff信息，第二个动作就是将改变合并到本地分支中。
git pull		

# 从服务器合并最新的分支仓库到本地仓库
git pull --rebase branch pmt-20352 		

# 拉一个服务器分支到本地的分支中
git fetch branch pmt-20352:pmt-20352

# 新建分支时，要从服务器master主分支 到 到本地的online-fangjia-bug分支中
git fetch origin master:online-fangjia-bug


# 推送分支
# 将本地仓库推送到远程服务器仓库
git push

# 提交到当前库到 源仓库的pmt-20352分支中
git push origin pmt-20352

# 把更改提交到 源仓库的主线分支中
git push origin master

# 把本地分支仓库，提交到服务器的某个分支仓库, 本地仓库:服务器分支仓库
git push branch pmt-20352:pmt-20352

# 把本地分支仓库，提交到服务器的某个分支仓库, 服务器分支仓库：本地仓库
git pull branch pmt-20352:pmt-20352


# 进入开发分支
git checkout pmt-20352
# 从服务器源仓库拉取主分支(线上仓库)
git fetch origin master
# 检测本地分支是否与线上分支冲突了，如果有冲突找人解决，没有冲突则可以上线了
git rebase FETCH_HEAD


# 横跨几个月的项目需要合并master的分支
git pull --rebase origin master

//小项目只需要
git pull --rebase git-branch pmt-21146		//合并开发分支的项目

# 解决冲突
# 重新检测冲突
git rebase --abort
# 出现这个时
CONFLICT (content): Merge conflict in app-web/page/taofang/Topic.phtml
# vi这个冲突文件时，解决冲突
git add app-web/page/taofang/Topic.phtml
# 重新rebase,如果有冲突继续解决,每次解决都重复这条命令,直到解决未知
[(no branch)] git rebase --continue
# 然后在提交到一个新的分支上
git push git-branch pmt-23423:pmt-23423-01
# 如果中间遇到某个补丁不需要应用，可以用下面命令忽略：
git rebase --skip


# 回滚到某个版本
git reset --hard 2e1b1db4c4de485a45aea29c9e72e339f5baeed1
git reset  2e1b1db4c4de485a45aea29c9e72e339f5baeed1  （没有提交commit的状态）


# 暂存
git stash
git stash pop 取出


# 日志查看
git reflog

# 查看那些文件发生了改动。这个命令在git commit之前有效，
git status

# 查看某个分支历史记录
git show master

# 查看某个指纹的修改信息
git show 81e01bf5d5f5c611f6528936d734f23359a43c66

# 查看某个指纹的修改信息
git log --graph

# 查看以前的commit
git log --oneline

# 查看当前分支的父级历史记录
git show HEAD^
					git show HEAD^^ 表示父级的父级

# 双父级时的处理
git show HEAD^1		//查看第一个父级
git show HEAD^2		//查看第二个父级

#  检测修改过的数据与现有.git仓库中的数据是否一致。这个命令只在git add之前使用有效。如果已经add了，那么此命令输出为空 。
git diff

# 这个命令在 git add 之后在 git commit 之前有效。
git diff –cached


# 显示当前都有哪些分支，其中标注*为当前所在分支
git branch

# 创建一个试验分支，名称叫 experimen
git branch experimental

# 删除分支 (-d 表示在分支已经合并到主干后删除分支. -D 表示不论如何都删除分支)
git branch -d experimental

# 删除文件和目录
git rm

# 把文件从版本控制删除
git rm -r --cached path

# 版本回退
git reset --hard HEAD


# 切换分支
git checkout master

# 克隆仓库到本地上的kfs目录中
git clone git@github.com:cyndiWade/zun.git

# 比较master主分支与bobworks分支都做了些什么改变
git whatchanged -p master:bobworks

# 删除远程分支
git push origin :pmt-cms-21395


# 分支别名, 创建一个 V3 的分支
git branch stable V3

# 谁创建了或者是修改了这个文件
git blame filename
```


### tag 标签操作

``` sh
# tag 标签可以针对某一时间点的版本做标记，常用于版本发布

# 列出标签
git tag
# 搜索符合模式的标签
git tag -l 'v0.1.*'


# 获取远程tag
git fetch origin tag <tagname>


# 创建本地 tag, tag 有两种类型, 轻量标签、附注标签,

## 轻量标签是指向提交对象的引用
git tag v0.1.2-light

## 附注标签则是仓库中的一个独立对象, 建议使用附注标签
git tag -a v0.1.2 -m "0.1.2版本"


# 切换到标签，与切换分支命令相同
git checkout -b [tagname]


# 查看标签信息，标签的版本信息
git show v0.1.2


# 删除本地标签
git tag -d v0.1.2

# 删除远程 tag
git push origin --delete tag


# 给指定的 commit 打标签， git log 获取
git tag -a v0.1.1 9fbc3d0


# 标签发布
# 将 v0.1.2 标签提交到 git 服务器
git push origin v0.1.2
```
