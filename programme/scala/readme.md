# Scala

## 应用

``` java

// 退出
sys.exit()
```

## 一、基础

### `1. 数据类型对象 (没有原始类型)`

| 数据类型 | 描述 |
| --- | --- |
| Byte    | 8位有符号值。范围从-128到127 |
| Short   | 16位有符号值。范围从-32768至32767 |
| Int     |	32 位有符号值。范围从 -2147483648 to 2147483647 |
| BigInt  |	|
| Long    |	64位有符号值。 从-9223372036854775808到9223372036854775807 |
| Float   |	32位IEEE754单精度浮点数 |
| Double  |	64位IEEE754双精度浮点数 |
| Char    |	16位无符号Unicode字符。范围由U+0000至U+FFFF |
| String  |	字符序列 |
| Boolean |	无论是字面true或false字面 |
| Unit    |	对应于没有值 |
| Null    |	空或空引用 |
| Nothing |	每一个其他类型的子类型; 包括无值 |
| Any     |	Any类型的超类型;任何对象是任何类型 |
| AnyRef  |	任何引用类型的超类型 |


### `2. 定义变量 `

- val 定义不可变的常量

- var 定义可变的变量

- 如果定义变量不指定类型，则 Scala 会使用 类型推断（Type Inference）,编译器会根据上下文推断出类型信息

``` java
1. val 定义不可变的常量
  val test1 = 123

2. var 定义可变的变量
  var test2 = 456

3. 指定类型
  val test3 : String = null
  val test4 : Any = "Hellow"

4. 多个值声明
  val xmax,ymax = 100

5. 多个值指定类型声明
  var greeting,message : String = null

6. 指定类型批量定义,并且复制(val)
  val (myInt1: Int, myString1: String) = Pair(40, "Foo")
  val (a,b) = (100,200) // 把二元组里的值分别赋给a，b两个变量

7. lazy 懒值,在调用时才初始化
  lazy val words: String = "Hello World"
  lazy val fn = (x:Int) => x + 2
```


### `2. 数据类型`

- 7 种数据类型 (Byte,Char,Short,Int,Long,Float,Double)

``` java
1. Scala 不刻意区分基本类型和引用类型
  1.toString()    // 转化成字符串

2. Int 值 1 首先被转换成 RichInt,然后用 to 方法
  1.to(10)        // 产出 Range(1,2,3,4,5,6,7,8,10)

3. Scala 用底层的 java.lang.String 类来表示字符串,通过 StringOps 类给字符串追加了上百种操作方法
  "Hello".intersect("World")

4. 正则匹配
  "1111".matches("[0-9]+")

```


### `3. apply 方法`

- () 操作符的重载形式,背后实现是 apply() 方法

``` java
1. 案例一
  "Hello"(4)
  "Hello".apply(4)

2. 案例二
  BigInt("1234567890")  // BigInt 的伴生对象
  BigInt.apply("1234567890")  // 伴生对象

3. 案例三
  Array(1,4,9,16)   // Array 的伴生对象
  Array.apply(1,4,9,16)

```



## 三、控制结构

### `1. 条件表达式`

- 表达式有值 : 并且每个表达式都有一个输出类型
- 语句 : 是执行动作

``` java
val x = 10

1. 把表达式返回的类型值,赋值给 s
  val s = if (x > 0) 1 else -1

2. (): 当做 "无有用值的占位符"
  if (x < 0) 1 else ()

3. Unit 类: 当做 Java 中的 void
  if (x < 0) 1 else Unit
```


### `2. {} 块表达式`

- 块包含一系列的表达式
- 块最后一个表达式的值,就是块的值

``` java
val ds = {
  // 赋值动作,本身没有值,严格来说是 Unit 类型,这个类型的值写作 ()
  val dx = 1 + 1
  dx + 1
}
```


### `3. println 输入和输出`

``` java
1. 输出
  print("Hello World")

2. 带换行符
  println("Hello World")

3. 格式化字符串
  printf("Hello , %s! You ar %d years old\n","Jason",24)
```


### `4. readLine 从控制台读取数据`

``` java
1. 读取输入行的数据
  val name = readLine("Your Name:")
  println(name)

2. 读取 Int 值
  val age = readInt()
  println(age)

```


### `5. 循环控制`

``` java

* 循环
1. do while
  var a = 10;
  do {
    println( "Value of a: " + a );
    a += 1;
  } while ( a < 20 )

2. while
  var a = 0
  while( true ){
    println( "Value of a: " + a );
    a += 1
  }

3. for
  var a = 0;
  // for 循环范围, 左箭头 < - 操作者被称为生成器
  for( a <- 2 to 10){
     println( "Value of a: " + a );
  }

4. for 循环执行的集合
  var a = 0;
  val numList = List(1,2,3,4,5,6);
  for( a <- numList ){
    println( "Value of a: " + a );
  }

5. for 循环字符串
  for (i <- "Hello")
    println(i)

6. 高阶应用,多个生成器
  // (循环一次 i <- 1 to 3; 再循环一次 j <- 1 to 3) 以此类推
  for (i <- 1 to 3; j <- 1 to 3) {
    println("i=" + i + "  j=" + j)
  }
  // 笛卡尔集
  i=1  j=1   
  i=1  j=2
  i=1  j=3

  i=2  j=1
  i=2  j=2
  i=2  j=3

  i=3  j=1
  i=3  j=2
  i=3  j=3

7. for 生成器守卫
  for (i <- 1 to 3;
      // 生成器守卫
      j <- 1 to 3 if i != j)
  {
      println("i=" + i + "  j=" + j)
  }
  // 过滤后的结果
  // i=1  j=1  这行被过滤
  i=1  j=2
  i=1  j=3

  i=2  j=1
  //i=2  j=2  这行被过滤
  i=2  j=3

  i=3  j=1
  i=3  j=2
  //i=3  j=3  这行被过滤

8. 变量 for 循环
  for (i <- 1 to 3;
    from = 4 - i;
    j <- from to 3)
  {
    println("i=" + i + "  j=" + j)
  }

9. for yield 会根据表达式构造一个新的集合
  var retVal = for {
    a <- List(1,2,3,4,5,6,7,8,9,10)
    if a != 3
    if a < 8
  } yield a

  println(retVal)
  //List(1, 2, 4, 5, 6, 7)

10. for yield 会根据表达式构造一个新的集合
  val a = for(i <- 1 to 10) yield i + 1

  println(a)
  //Vector(2, 3, 4, 5, 6, 7, 8, 9, 10, 11)


* 循环控制
1. 导入包
  import scala.util.control._

2. 创建对象
  val outer = new Breaks;

3. 包裹循环体
  outer.breakable {
     for( a <- 2 to 10){
         if (a > 9) {
           // 结束循环, Scala 没有 continue(如果需要，请自行控制循环的范围)
           outer.break;
         }
         println( "Value of a: " + a );
      }
   }

```

## 四、函数与异常

* 函数在 Scala 中属于一级对象,可以作为参数传递给其他函数,可以作为另一个函数的返回值,或者赋给一个变量

* 关键字 def 函数名称 (参数名称 : 类型) : 返回类型 = 函数体
  *  1) 如果不是递归函数可以选择省略 : 返回类型
  *  2) 支持定义匿名函数，匿名函数由参数列表，箭头连接符和函数体组成
  *  3) 不带参数的 Scala 方法,通常不适用圆括号
  *  4) 过程函数: 不返回任何值,即 Unit 类型,所以可以不需要 = 号
  *  5) 函数不是递归,无需指定返回类型

*  Scala 没有静态方法,有类似特性叫做单列对象 (singleton object)

*  Scala 类会有个伴生对象 (companion object)

### `1. 函数与表达式`

``` java

1. scala 开头的包,可以省去 scala 前缀

// import scala.math._  =  import math._
// math.sqrt(2)  =  scala.math.sqrt(2)
  import scala.math._
  math.sqrt(2);


2. 定义函数
  1) 函数不是递归,无需指定返回类型
    // 方式一
    def abs (x: Double) = if (x >= 0) x else -x

    // 方式二
    def abs (n: Int) = {
        if (n >= 0) n else -n
    }

  2) 定义函数,函数是递归的,需要指定返回类型
    // 方式一
    def fac (n: Double): Double = if (n <= 0) 1 else n * fac(n-1)

    // 方式二
    def fac (n: Int): Int = {
      if (n <= 0) {
         1
      } else {
        n * fac(n-1)
      }
    }


3. 定义函数: 带名参数
  def decorate(str: String, left: String = "(", right: String = ")" ) = {
      left + str + right
  }
  // 使用
  decorate("Hello",right = "zzz")


4. 定义函数: 变长参数
  def sum(args: Int*) = {
     var result = 0
     for (arg <- args) result += arg
     result
  }
  // 直接使用
  sum(1,2,3,4,5)

  // 传入序列: 需要使用 :_* 操作
  // :_*  当做参数序列处理，追加 :_*
  sum(1 to 5:_*) // 1 to 5 被当做参数序列处理


5. 定义函数: 过程函数
  过程函数不返回任何值,即 Unit 类型,所以可以不需要 = 号
  // 案例一
  def box(s: String) {

  }

  // 案例二,推荐写法
  def box(s: String): Unit =  {

  }


6. 异常处理
  // try/catch
  try {

  } catch {
    case ex: Exception =>
    case _:

  }

  // try/finally
  try {

  } finally {

  }

```


### `2. 高阶函数`

``` java
1. Scala 函数按名称调用 : (方法体中,使用时再执行的函数)
  object Test {

     def main(args: Array[String]) {
       // 调用了
       delayed(time());
     }

    // 默认最后一行作为返回
    def time() = {
      println("time(): 开始执行了...")
      System.nanoTime
    }

    // 表达式调用时，才运行 time() 函数,并且获得返回值
    // 注意表达式 ( t: => Long )
    def delayed( t: => Long ) = {
      println("delayed(): 开始执行了...")

      // 这里才开始调动 time() 方法
      println("调用时,运行 time() 返回值: " + t)
    }
  }


2. Scala 高阶函数和函数传递

  // 案例 1
  object Test {

    // 等待入参的函数
    def layout[A] (x: A) : String = {
      return x.toString()
    }

     // fn1: Int => String  是一个函数,入参是 Int,返回 String
     // v2: Int  是一个入参
     // fn1(v2)  是一个匿名函数
     def apply(fn1: Int => String, v2: Int) = {
       fn1(v2)
     }

     def main(args: Array[String]) {

        // 把 layout 函数 和 10 ,传递给 apply(fn1,v2)
        // 执行 fn1(v2) ,就是相当于执行 layout(10)
        var rs = apply( layout, 10)
        println(rs)
     }
  }

  // 案例 2
  // 功能函数写法
   def featureFn(arg1: Int, arg2: Int) {
      println(arg1 + arg2)
   }

   // 执行函数写法
   // fn: (String, String) 表示一个函数参数, featureFn 传递给 runFn 执行, 其中参数类型保持一致即可
   def runFn(fn: (Int, Int) => Unit) {
     fn(1, 2)
   }

   // 调用执行
   runFn(featureFn)


3. Scala 匿名函数 (快速定义一个函数)

  (参数) => {方法体}   // => 把参数和方法体分开

  // 函数名 = (入参) => 函数体
  var fn1 = (x:Int) => x + 2

  // 多参数匿名函数
  var fn2 = (x:Int,y:Int) => x + y

  // 无参数函数
  var fn3 = () => {
    System.getProperty("user.dir")
  }

  // 或者这样
  { i: Int =>
    println("Hello World")
    i * 2
  }



4. Scala 柯里函数 (柯里转换函数接受多个参数成一条链的函数，每次取一个参数。也可以定义多个参数列表)
  object Test {
      /*
       * 写法 1
       * def fn1 ()() = {}
       */
      def fn1 (s1:String) (s2:String) = {
        s1 + s2
      }

      /*
       * 写法 2
       * def fn2 () = () => {}
       */
      def fn2 (s1:String,s2:String) = (s3:String) => {
        s1 + s2 + s3
      }

     def main(args: Array[String]) {
       var str1 : String = "Hello,"
       var str2 : String = "World,"
       var str3 : String = "Scala!"

       println(fn1(str1)(str2))
       println(fn2(str1,str2)(str3))

     }


  }


5. Scala 嵌套函数 (局部被调用)

  object Test {

     def main(args: Array[String]) {

       var rs = this.fn1(2)
       println(rs)
     }


     def fn1 (i : Int) : Int = {

       // 定义嵌套函数
       def fn2 (x : Int,y : Int) : Int = {
         x + y
       }

       // 局部被调用
       fn2(i,1)
     }
  }


6. Scala 部分应用函数 (当被调用的时候才初始化)
  import java.util.Date;

  object Test {

   def main(args: Array[String]) {
     // 创建时间对象
     val date = new Date;

     // 创建应用函数, 把 date 绑定在第一个参数(这个点记录的函数), 并且在(下划线)处绑定第二个参数
     val fn2 = fn1(date, _: String)

     fn2("message1")
     fn2("message2")
     fn2("message3")
   }

   def fn1(date: Date, message: String) = {
     println(date + "------" + message)
   }

  }


7. Scala 函数重写
  // 原始类
  object ScalaIntList {
    final case class Node(next: Node, value: Int)
  }
  // 重写前
  final class ScalaIntList {
    var head: ScalaIntList.Node = null
    var size: Int = 0
  }

  // 改写(通过语言特性)
  object ScalaIntList {
    final case class Node(next: Node, value: Int)
  }
  // 后
  final class ScalaIntList {
    var head: ScalaIntList.Node = null
    final def size: Int = {
      var n = head
      var i = 0
      while (n != null) {
        n = n.next
        i++
      }
      i;
    }
  }

  // 访问
  val myList = new ScalaIntList
  println(myList.size);
```

```


### `3. 闭包函数`

- 闭包是也是函数，它的返回值取决于此函数之外声明一个或多个变量的值

``` java
object Test {

   def main(args: Array[String]) {
     var rs = fn(2)

     println(rs)
   }

   var factor : Int = 3
   // 定义闭包函数,外部不调用的时候,形成闭包
   val fn = (i:Int) => {
     // i 是入参
     // factor 是外部环境的变量
     i * factor
   }
}

```



## 五、数组

### `1. 定义数组`

``` java
1. 定义固定长度数组
  import Array._;

  // 存放 10 个元素,初始化值为 null
  val arr = new Array[Int](10);
  var arr : Array[Int] = new Array[Int](10)

  // 定义数组, Array[String]  这是根据推断机制推断出来的
  val s = Array("Hello","A")
  val s: Array[String]  = Array("Hello","A")

  // 访问数组
  s.(0)

  // 验证值是否在数组中
  s.contains("A")


2. 变长数组: 数组缓冲(ArrayBuffer) 不定长数组
  import scala.collection.mutable.ArrayBuffer;
  // 定义缓冲数组
  val inventoryIds: ArrayBuffer[String] = new ArrayBuffer[String]()


  b += 1                 // 在数组尾部追加元素
  b += (1,2,3,4)         // 数组尾部追加多个元素,用括号括起来
  b ++= Array(5,6,7)     // 追加任何集合
  b.trimEnd(1)           // 移除最后 1 个元素
  b.insert(2,8,9)        // 在下标 2 之前插入 8、9 这两个元素
  b.remove(2)            // 移除指定下标的元素
  b.remove(2,3)          // 移除指定下标后的的 3 个元素

  // 把 ArrayBuffer 转换成一个 Array
  b.toArray

  // 把 Array 转换成一个 ArrayBuffer
  b.toBuffer


3. 定义数组: 多维数组
  1) Array[Array[Double]](n)      // 数组指定长度
    val arr = Array.ofDim[Double](3,4)

    arr(1)(2) = 123    // 赋值

    print(arr(1)(2))   // 访问


  2) new Array[Array[Int]](10)
    val arr = new Array[Array[Int]](10)
    for (i <- 0 until arr.length)
      arr(i) = new Array[Int](2)

    println(arr(2).toBuffer)


4. 遍历数组
  * until : 是 RichInt 类的方法,返回所有小于(但不包含)上限的数字
    val a = (0 until 10)    // 实际调用的是 0.until(10)
    println(a) // Range(0, 1, 2, 3, 4, 5, 6, 7, 8, 9)

  1) 按照顺序遍历
    for (i <- 0 until b.length)
      println(b(i))

  2) 按照顺序遍历 : 步长 2
    0 until (b.length,2) = 0.until(b.length,2)
    for (i <- 0 until (b.length,2))
      println(b(i))

  3) 倒着循环
    (0 until b.length).reverse =  0.until(b.length).reverse
    for (i <- (0 until b.length).reverse)
      println(b(i))


5. 数组转换
  var a = Array(2,3,5,7,11)

  // 案例一
  val result = for(
    elem <- a
    if elem > 3 // 满足条件的值拿出来
  ) yield {
    1 + elem
  }

  // 案例二, 保留条件通过后的的数据
  val result = a.filter { _ > 3 }.map( 1 + _ )  // 作用相同

  // 输出
  println(result.toBuffer)  // ArrayBuffer(6, 8, 12)
  println(result.mkString(","))  // 把数组的值，按照指定符号分割


* 数组方法
  1) concat() 组合数组
    var concatArr = concat(arr1,arr2,arr3)


  2) range() 生成返范围数组
    // 生成 10 ~ 20 的数组
    var myList2 = range(10,20)

    // 生成 10 ~ 20 的数组, 步长设置为 2
    var myList1 = range(10, 20, 2)

```


### `2. 映射和元祖 (key/value 对)`

``` java
1. 定义 Map: 不可变
  import scala.collection.immutable.Map;
  import scala.collection.immutable.HashMap;

  // 三种定义 空值 Map 写法
  val map = Map()
  val map : Map[String,Int] = Map()
  val map : Map[String,Int] = Map[String,Int]()

  // 三种定义 默认值 Map 写法
  val map = Map("Alice" -> 10, "Bob" -> 3, "Cindy" -> 8)  // 自动判断定义
  val map = Map(("Alice", 10), ("Bob", 3), ("Cindy", 8))  // 元祖类型
  val map: Map[String, Int] = Map("Alice" -> 10, "Bob" -> 3, "Cindy" -> 8) // 指定类型定义

  println(map.toBuffer)   // 打印


2. 定义 Map: 可变的

  // 定义一个空的可变的 Map 的两种写法
  import scala.collection.mutable.Map
  import scala.collection.mutable.HashMap
  var map = new HashMap[String,Int];
  var map : Map[String,Int] = new HashMap[String,Int]();


3. 操作 Map
  var map: Map[String, Int] = Map[String, Int]()

  map += ("a" -> 1, "b" -> 2)     // 增加键值对
  map.put("c", 3)                 // 增加键值对

  map("a") = 2                    // 更新 key 值
  map -= "b"                      // 移除 key
  map.remove("b")                 // 移除 key

  map.contains("a")               // 验证 key 是否存在
  map("a")                        // 读取 key
  map.getOrElse("a", 0)           // 当 key 不出在,返回默认值
  map.get("a")                    // 返回一个 Option 对象 Some(值) 或者 None


  map.toMap                       // 可变 map 转换成为不可变 Map                    

                                  //  不可变 Map 转换为可变 Map
  scala.collection.mutable.Map(map.toSeq:_*)    // 方法一
  scala.collection.mutable.Map[String, Object](map.toSeq:_*)

  collection.mutable.Map() ++  map              // 方法二

  实际案例: 把不可变 Map 转换为 可变 Map
  val bsMap = Map[String, Object]()  // 这里的 Object 里面包含的也是一个 Map
  val bsMapFormat = bsMap.map{case (k,v) =>
    val curK = k
    // 把元祖 v 转换为 Map[String,String]
    val curV = v.asInstanceOf[scala.collection.immutable.Map[String,String]]
    // 再把 map 转换为可变 Map
    val formatV = scala.collection.mutable.Map(curV.toSeq:_*)
    k -> formatV
  }          


4. for 迭代映射 Map
  // for ((k, v) <- 映射) 处理 k 和 v
  for ((k, v) <- map) {
    println("k:" + k + "  v:" + v)
  }

  // 只读 key
  for (k <- map.keySet) {
    println(k)
  }
  val tmp = map.keySet.toArray
  tmp(0)

  // 只读 value
  for (v <- map.values) {
    println(v)
  }


5. 元祖 tuple
  1)  定义元祖
    val t = (1, 3.14, "Fred")
    val t: (Int,Double,String) = (1, 3.14, "Fred")
    val t: (Int,Double,String) = null
  2) 元祖复制
    val (a, b, c) = t
    println(c) // Fred

    //元祖复制,只要部分 (使用 _ 占位符来代替)
    val (a, _, c) = t
    println(c)  // Fred

  3) 拉链操作: 合并元祖
    val t1 = Array("a", "b", "c")
    val t2 = Array(1, 2, 3)

    val rs = t1.zip(t2)     // 转换成对偶数组
    println(rs.toBuffer)    // ArrayBuffer((a,1), (b,2), (c,3))

    val maps = rs.toMap     // 转换成 Map
    println(maps)           // Map(a -> 1, b -> 2, c -> 3)

  4) 访问元祖
    t._1
    t._2


  5) 定义返回 元祖的函数
  // 拆解 Map 返回
  def dismantlingMap (data: Map[Int,Int]): (Int, Int) = {
       val keys = data.keySet.toArray

       (keys(0), data.get(keys(0)).get)
   }

```


### `3. 集合 `

``` java

1) List[T] 集合

  Nil 表示 空 LIST

  (1) List of Strings
    val fruit: List[String] = List("apples", "oranges", "pears")
    // 另一种写法
    val fruit = "apples" :: ("oranges" :: ("pears" :: Nil)

  (2) List of Intege
    val nums: List[Int] = List(1, 2, 3, 4)
    // 另一种写法
    val nums = 1 :: (2 :: (3 :: (4 :: Nil)))

  (3) Empty List
    val empty: List[Nothing] = List()
    // 另一种写法
    val empty = Nil

  (4) 二维  List
    val dim: List[List[Int]] =
       List(
          List(1, 0, 0),
          List(0, 1, 0),
          List(0, 0, 1)
       )
    // 另一种写法
    val dim = (1 :: (0 :: (0 :: Nil))) ::
            (0 :: (1 :: (0 :: Nil))) ::
            (0 :: (0 :: (1 :: Nil))) :: Nil

  (5) 可变的 List
    import scala.collection.mutable.ListBuffer
    val list = ListBuffer[Any]()
    list.append(1)


  (6) 循环累加
    var list = List[String]()

    for(i <- 0 to jsonArr.size() - 1) {
            // 在List的头部增加元素
            val node = jsonArr.get(i).toString()

            // list 头部放入节点
            list = list +: list
            // list 追加节点
            list = list :+ node

    }


2) Set 集合是不包含重复元素的集合
  // 默认使用的是不可变集合
  import scala.collection.immutable.Set;

  // 可变集合
  import scala.collection.mutable.Set;

  (1) Empty set of integer type
    var s : Set[Int] = Set()

  (2) Set of integer type
    var s : Set[Int] = Set(1,3,5,7)
    // 另一种写法
    var s = Set(1,3,5,7)


3) Map 映射是键/值对的集合
  import scala.collection.mutable.Map;

  (1) 定义 Map , 设置值
    var map1 : Map[Char,Int] = Map()
    map1 += ('a' -> 1 )
    map1 += ('b' -> 2 )

  (2) 定义 Map , 设置默认值
    val map2 = Map("red" -> "#FF0000", "azure" -> "#F0FFFF")

  (3) 操作
    map1.contains('keyName') // 验证 key 是否存在


4) Tuples 元组可以容纳不同类型的对象，但它们也是不可改变的

  (1) 定义 4 位的元祖
    语法 :
      new Tuple[n]
    例如 :
      val t = new Tuple4(1, "hello", "World","Scala")

  (2) 直接定义元祖
    val t = (1, "hello", "World","Scala")

  (3) 访问
    t._1
    t._2
    t._3


5) Option[T] 可以是一些[T]或 None 对象，它代表一个缺失值

  // 定义变量 a ,类型为 Option[Int]
  val a : Option[Int] = Some(5)
  var rs = a.getOrElse(0)

  val b : Option[Int] = None
  var rs = b.getOrElse(10)


6) Iterators  迭代器不是集合，而是一种由一个访问的集合之一的元素。

  //在一个迭代的两种基本操作：next 和 hasNext。
  val it = Iterator("a", "number", "of", "words")

  while (it.hasNext){
    // it.next()将返回迭代器的下一个元素,推进迭代器的状态
    println(it.next())
  }


*) 排序
  1. map 排序
  val mapData:MAP[String, String] = Map("90" -> "1", "87" -> "2")
  mapData.toList.sortBy(kv =>
      kv._2.toString().toInt    
  ).reverse. // 表示倒序,如果不写则是正序
  foreach {
     case (key,value) => println(key + " = " + value)
  }
  // 或者再次转换成
  mapData.toList.sortBy(kv => kv._2.toString().toInt).reverse.toMap

```


## 六、类与对象



### `1. Class 类`

``` java

1. 对象方法说明

  class Counter {

    var value = 0    // 必须提前初始化
    private var value2 = 0    // 测试方法 2

    def increment() { value += 1}    // 方法默认是公有的

    def current() = value;           // 返回值 value

  }

    // 调用
    val myCounter = new Counter() // 或者 new Counter

    myCounter.increment()

    // 调用无参数方法,可以不写 ()
    myCounter.current
    myCounter.current()

    // 对于有修改的方法使用 ()
    myCounter.increment()     

    // 只读取值,不修改对象状态的方法,不带是不错的风格 ()
    myCounter.current        


2. getter 和 setter

  /**
   * getter 和 setter
   *  1. 如果字段是私有的 : getter 和 setter 方法也是私有的
   *  2. 如果字段是 val : 只生成 getter, 修改无效
   *  3. 不需要 getter 和 setter : 将字段声明为 private[this]
   */
  class GetterAndSetter {
      var age = 0

      private var value = 0        // 私有变量,

      def increment() { value += 1}

      def current = value;    // 声明没有 (), 因为 getter 方法没有定义 (), 只读取值的方法不需要写 ()

      // 对象私有,只能访问到当前对象的 value 字段
      def isLess(other: GetterAndSetter) = {
          value < other.value
          value
      }

  }

    var getterAndSetter = new GetterAndSetter()

    getterAndSetter.age           // 调用 age() 方法, 在 JVM 中 public int age()      
    getterAndSetter.age = 21      // 调用 this.age = (21), 在 JVM 中 public void age_$eq(int)

    getterAndSetter.increment()    // 修改值
    getterAndSetter.current        // 直接访问, 这样调用就出错了 getterAndSetter.current()

    println(getterAndSetter.isLess(getterAndSetter))     //



3. 主构造器(primary constructor) 和 辅助构造器(auxiliary constructor)

1) 辅助构造器
  class Person {
      private var name = ""
      private var age = 0;

      def this(name: String) {    // 一个辅助构造器 this
          this()    // 调用构造器
          this.name = name
      }

      def this(name: String, age: Int) {    // 又一个辅助构造器
          this(name)    // 调用辅助构造器
          this.age = age
      }
  }

    val p1 = new Person            // 主构造器
    val p2 = new Person("A")       // 第一个辅助构造器
    val p3 = new Person("B",1)     // 第二个辅助构造器


2) 主构造器
  class Person(name: String, age: Int) {

    def printLn() : Unit = {
        println(this.name)
        println(this.age)
    }
  }

    val p1 = new Person("Jason",24)            // 主构造器
    p1.printLn()

```


### `2. Object 对象 `

- object 中所有成员变量和方法默认都是 static
- object 使用场景
  - 存放工具函数
  - 存放常量、静态方法
  - 对象单例、高效共享不可变实例
- class 可以拥有一个同名的伴生对象 object
- object 的 apply 方法通常用来构造伴生类 class 的新实例
- 可以通过扩展 Enumeration 对象实现枚举


``` java
1. 单例对象
  object Accounts {
      private var lastNumber = 0

      def newUniqueNumber() = {
          lastNumber += 1
          lastNumber
      }
  }

  Accounts.newUniqueNumber()


2. 伴生对象
  class Account {
      val id = Account.newUniqueNumber()    // 静态方法
      private var balance = 0.0
      def deposit(amount: Double) {balance += amount}
  }

  // class Account 的伴生对象
  object Account {
      // 类和伴生对象可以相互访问私有特性,但必须在同一个文件中
      private var lastNumber = 0
      private def newUniqueNumber() = {
          lastNumber += 1
          lastNumber
      }
  }


4. apply 伴生方法
  // 相当调用 Array.apply()
  Array("a","b","c")  // 相当于 Array.apply("a","b","c")

  // 这是调用构造器 this()
  new Array("a","b","c")

  // 案例
  class Account private(val id: Int, user: String) {
      private var people = user;
  }

  // 伴生对象
  object Account {
      // 自定义 apply 方法
      def apply(user: String) = {
          new Account(1,user)
      }
  }

  // 调用
  Account("angejia")  //  案例一
  Account.apply("angejia") // 案例二


5. 应用程序 App
  // 第一种写法
  object Hello extends App {
    println(args.length)
    println(args(0))
  }

  // 第二种
  object Hello {
    def main(args: Array[String]) {

    }
  }
```


### `3. 继承`

- extends 继承父类

- override 重写方法

- final
  - java 表示不可变
  - scala 表示不可重写

- super 调用超类

``` java
1. 继承 + 抽象类
  abstract class Person {
    def getId(id: Int): Int // 定义抽象方法
    def getName(name: String): String;

    // 无参数方法
    def getAge : Int

  }    
  class PersonImpl extends Person{

    // 实现抽象方法
    def getId(id: Int): Int = {
        1
    }

    // 实现抽象方法
    def getName(name: String): String = {
        "Angejia"
    }

    // 实现无参数方法
    def getAge : Int = 1
  }

  // 调用
  val person: Person = new PersonImpl
  person.getId(1)

```



### `4. 特性 - 类似接口`

- abstract  抽象类, 接口, 可实现部分方法
- trait  特性类, 可实现部分方法
- with   Class 实现或继承接口类后, with 可继承多个特性

``` java

trait Equal {
  def isEqual(x: Any): Boolean
  def isNotEqual(x: Any): Boolean = !isEqual(x)
}


// 定义接口类 某种动物
abstract class Animal {
  // 定义未实现的方法
  def walk(speed:Int)

  // 接口可以实现方法
  def breathe() = {
    println("animal breathes")
  }
}

// 定义特性类 飞行
trait Flyable {
  def hasFeather = true
  def fly
}

// 定义特性类 游泳
trait Swimable {
  def swim
}

// 定义一个实体类(一种鸟) extends 动物 with 可以飞行 with 可以游泳
class FishEagle extends Animal with Flyable with Swimable {
  // 实现具体方法
  def walk(speed:Int) = println("fish eagle walk with speed " + speed)
  def swim() = println("fish eagle swim fast")
  def fly() = println("fish eagle fly fast")
}

// 运行
object App {
   def main(args : Array[String]) {
     val fishEagle = new FishEagle
     val flyable:Flyable = fishEagle
     flyable.fly

     val swimmer:Swimable = fishEagle
     swimmer.swim
   }
}


```


### `5. 模式匹配`

- 经过函数值和闭包

``` java
object Test {
   def main(args: Array[String]) {
      println(matchTest(3)) // many

   }
   // 模式匹配, ps : 注意写法 x match
   def matchTest(x: Int): String = x match {
      case 1 => "one"
      case 2 => "two"
      case _ => "many"
   }

   // 第二种写法
   def matchTest2(x: Any){
     // x 表示变量值
      x match {
         case 1 => "one"
         case "two" => 2
         case y: Int => "scala.Int"
         case _ => "many"
      }
   }
}


/**
 * 模式匹配类
 */
object Test {
    // 模式匹配类.
   case class Person(name: String, age: Int)

   def main(args: Array[String]) {
       val alice = new Person("Alice", 25)
       val bob = new Person("Bob", 32)
       val charlie = new Person("Charlie", 32)

      for (person <- List(alice, bob, charlie)) {
        // 模式匹配
         person match {
            case Person("Alice", 25) => println("Hi Alice!")
            case Person("Bob", 32) => println("Hi Bob!")
            case Person(name, age) =>
               println("Age: " + age + " year, name: " + name + "?")
         }
      }
   }

}
// 返回结果
Hi Alice!
Hi Bob!
Age: 32 year, name: Charlie?


/**
 * 案例：学校门禁
 * Scala 中提供了一种特殊的类，用 case class 进行声明，中文也可以称作样例类。case class 其实有点类似于 Java 中的 JavaBean 的概念。即只定义 field，并且由 Scala 编译时自动提供 getter 和 setter 方法，但是没有 method。
 * case class 的主构造函数接收的参数通常不需要使用 var 或 val 修饰，Scala 自动就会使用 val 修饰（但是如果你自己使用 var 修饰，那么还是会按照 var 来）
 * Scala 自动为 case class 定义了伴生对象，也就是 object，并且定义了 apply() 方法，该方法接收主构造函数中相同的参数，并返回 case class 对象
 */
class Person
case class Teacher(name: String, subject: String) extends Person
case class Student(name: String, classroom: String) extends Person

def judgeIdentify(p: Person) {
  p match {
    case Teacher(name, subject) => println("Teacher, name is " + name + ", subject is " + subject)
    case Student(name, classroom) => println("Student, name is " + name + ", classroom is " + classroom)
    case _ => println("Illegal access, please go out of the school!")
  }  
}


/**
 * 案例：成绩查询
 * Scala 有一种特殊的类型，叫做 Option。Option 有两种值，一种是 Some，表示有值，一种是 None，表示没有值。
 * Option 通常会用于模式匹配中，用于判断某个变量是有值还是没有值，这比 null 来的更加简洁明了
 * Option 的用法必须掌握，因为 Spark 源码中大量地使用了 Option，比如 Some(a)、None 这种语法，因此必须看得懂 Option 模式匹配，才能够读懂 spark 源码。
 */
val grades = Map("Leo" -> "A", "Jack" -> "B", "Jen" -> "C")

def getGrade(name: String) {
  val grade = grades.get(name)
  grade match {
    case Some(grade) => println("your grade is " + grade)
    case None => println("Sorry, your grade information is not in the system")
  }
}
```


## * 时间应用

### `1. sleep`

``` java
1.  TimeUnit
  import java.util.concurrent.TimeUnit

  TimeUnit.SECONDS.sleep(4); // 停顿 1 秒
  TimeUnit.MINUTES.sleep(4); // 停顿 1 分
  TimeUnit.HOURS.sleep(1);   // 停顿 1 小时
  TimeUnit.DAYS.sleep(1);    // 停顿 1 天

2. Thread
  import java.lang.Thread

  Thread.sleep(1 * 1000) // 停顿 1 秒
  Thread.sleep(60 * 1000) // 停顿 1 分
  Thread.sleep(60 * 60 * 1000) // 停顿 1 小时
  Thread.sleep(24 * 60 * 60 * 1000) // 停顿 1 天
```

## * 技巧

### `1. 技巧`

``` java
1. 语法
  map(_._2) 等价于 map(t => t._2)  // t 是个 2 项以上的元组
  map(_._2, _) 等价与 map(t => t._2, t)  


2. 变量类型传递(元祖) Tuple2
  // spark 抽取前 n 个数据,
  val id = inventoryId
  val result: Array[(Int, Double)] = itemCosineSimilarity.take(1000)

  // 转换成元祖 Tuple2
  val t: (Int,Array[(Int, Double)]) = (id, result)

  // 在其他位置访问
  val inventoryData = t
  inventoryData._1
  inventoryData._2(0)._1


3. 类型转换
  1) scala 内部, 类型转换
    // 把 Obj 转化弄成 Map[String, String], 这个 Obj 必须是被转换对象的子类
    obj.asInstanceOf[collection.Map[String, Object]]

  2）java 转 scala

    // 自动完成双向转型
    import collection.JavaConversions._

    // 进而显式调用 asJava() 或 asScala() 方法完成转型
    import collection.JavaConverters._

      // 例如: 显示 java 对象转换为 scala 对象
      Map[String, String].asScala()   

    支持转换的数据类型
      * scala.collection.Iterable <=> java.lang.Iterable
      * scala.collection.Iterable <=> java.util.Collection
      * scala.collection.Iterator <=> java.util.{ Iterator, Enumeration }
      * scala.collection.mutable.Buffer <=> java.util.List
      * scala.collection.mutable.Set <=> java.util.Set
      * scala.collection.mutable.Map <=> java.util.{ Map, Dictionary }
      * scala.collection.concurrent.Map <=> java.util.concurrent.ConcurrentMap

      * scala.collection.Seq => java.util.List
      * scala.collection.mutable.Seq => java.util.List
      * scala.collection.Set => java.util.Set
      * scala.collection.Map => java.util.Map
      * java.util.Properties => scala.collection.mutable.Map[String, String]


4. 读取 resources 目录下的文件
  import scala.io.Source
  import java.io.{ InputStream, BufferedReader, InputStreamReader, PushbackReader }

  // 读取行
  val lines = Source.fromURL(getClass.getResource("/xxx.conf")).getLines()
  lines.foreach(println)

  // 获取 resource 文件读输入流
  val inputStreamReader: InputStreamReader = Source.fromURL(getClass.getResource("/xxx.conf")).reader()
```


### 2. 修饰符

``` java
@volatile  修饰符, 告诉编译器变量是随时可能发生变化的, 每次访问都需要重新读取

  @volatile private var sr: String = null
```


### 3. 语言特性
