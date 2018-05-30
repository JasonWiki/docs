# Pig

## 一、介绍

- Apache Pig 解决了 MapReduce 存在的大量手写代码，语义隐藏，提供操作种类少的问题。类似的项目还有Cascading，JAQL等。

## 二、流程

```

- Apache Pig 也是 Hadoop 框架中的一部分

- Pig 提供类SQL语言（Pig Latin）通过 MapReduce 来处理大规模半结构化数据

- Pig Latin 是更高级的过程语言，通过将 MapReduce 中的设计模式抽象为操作，如Filter，GroupBy，Join，OrderBy，由这些操作组成

- 而 Pig Latin 又是通过编译为 MapReduce，在 Hadoop 集群上执行的。上述程序被编译成 MapReduce 时，会产生如下图所示的Map和Reduce
```
