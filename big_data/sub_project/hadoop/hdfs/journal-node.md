# JournalNode

两个 NameNode 为了数据同步，会通过一组称作 JournalNodes 的独立进程进行相互通信。

当 active 状态的 NameNode 的命名空间有任何修改时，会告知大部分的 JournalNodes 进程。

standby 状态的 NameNode 有能力读取 JNs 中的变更信息，并且一直监控 edit log 的变化，把变化应用于自己的命名空间。

standby 可以确保在集群出错时，命名空间状态已经完全同步了

- [原理文章](http://www.tuicool.com/articles/3y6Rvq)
