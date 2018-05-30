# Git 项目管理

## 文章
- [git clone](http://blog.csdn.net/techbirds_bao/article/details/9179853)

## 简介
我们现在规划 3 类分支

### 1、master 即主分支（服务器生产环境分支）
服务器只更新此分支的内容

### 2、feature  开发分支
当需要开发新的功能、修复 bug 时从最新的 master 分支拉去生成

分支命名规范 ：
```
feature-项目名称-日期,如：feature-accessLog-20150421
```

### 3、hotfix BUG 修复
命名规则

```
hotfix-项目名称-日期,如：hotfix-accessBug-20150421
```


## 开发流程

### 单人开发模式

```
1.拉去最新的 master 分支到本地，名字为
git fetch origin master:feature-name-20150421

2.切换到此分支
git checkout feature-name-20150421

3.你在此分支开发的一系列操作......
git add xxxx
git commit -m 'xxxx'

4.提交到服务器的分支,为你拉去的分支（注意！!!）
git push origin feature-name-20150421:feature-name-20150421

```

### 多人协作开发模式

```
1.拉去同伴创建的分支
git fetch origin feature-name-20150421:feature-name-20150421

2.接环到此分支
git checkout feature-name-20150421

3.你在此分支开发的一系列操作......
git add xxxx
git commit -m 'xxxx'

4.拉去同伴最新的修改信息
git pull --rebase origin feature-name-20150421:feature-name-20150421

这里如果有冲突通知同伴解决冲突

5.提交代码到分支
git push origin feature-name-20150421:feature-name-20150421

```


### 上线流程

```
查看所有你的分支
git branch

却换到你的分支
git checkout feature-name-20150421

拉取最新的 master 分支到你的分支中
git fetch origin master

检查 master 的分支和你的分支是否有冲突
git rebase FETCH_HEAD

有冲突找，找跟你冲突的人一起解决
没有冲突，在 git 上面提交请求合并操作
Merge Requests -> New Merge Requests -> Source branch(选择你的分支) -> Target branch (选择 master 分支) -> 选择处理人(你的 Leader) -> 通知 Leader Code Review 后合并到 master
```
