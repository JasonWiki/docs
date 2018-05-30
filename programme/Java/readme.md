# Java


## * 基本介绍

``` java

if（obj instanceof String)


```


### 1、循环

``` java
public class Test3 {
    public static void main(String[] args) {

        String[] arr_1 = {
                "a",
                "b",
                "c"
       };

       /**
        * 语法
        for(元素类型t 元素变量x : 遍历对象obj){
          x 是引用
        }
        */
       for (String a : arr_1) {
           System.out.println(a);
       }
    }
}
```

### 2、switch

- switch 语句中的变量类型只能为 byte、short、int、char
- case 语句中的值的数据类型必须与变量的数据类型相同，而且只能是常量或者字面常量
- switch 语句可以包含一个 default 分支，default分支不需要break语句




## * 数据类型

### 1、介绍

文章 ：http://www.w3cschool.cc/java/java-basic-datatypes.html

- 内置(基本)数据类型
- 引用数据类型 (引用只是对象的别名，引用不等于对象)


### 2、内置(基本)八种基本数据类型

- 六种数字类型
  - 四个整数型 (byte-8、short-16、int-32、long-64)

    ``` java
    1) byte 数据类型是 8 位 , 有符号的 , 以二进制补码表示的整数
      默认值是 0
      最小值是-128（-2^7）
      最大值是127（2^7-1）
      byte 变量占用的空间只有 int 类型的四分之一

    2) short 数据类型是 16 位、有符号的以二进制补码表示的整数
      默认值是 0
      最小值是 -32768（-2^15）
      最大值是 32767（2^15 - 1）
      一个 short 变量是 int 型变量所占空间的二分之一

    3) int 数据类型是 32 位、有符号的以二进制补码表示的整数
      默认值是 0
      最小值是 -2,147,483,648（-2^31）
      最大值是 2,147,485,647（2^31 - 1）
      一般地整型变量默认为 int 类型

    4) long 数据类型是 64 位、有符号的以二进制补码表示的整数
      默认值是 0L
      最小值是 -9,223,372,036,854,775,808（-2^63）
      最大值是 9,223,372,036,854,775,807
      这种类型主要使用在需要比较大整数的系统上
    ```

  - 两个浮点型 (float-32、double-64)

    ``` java
    5) float 数据类型是单精度、32位、符合IEEE 754标准的浮点数
      默认值是 0.0f
      浮点数不能用来表示精确的值
      float 在储存大型浮点数组的时候可节省内存空间

    6) double 数据类型是双精度、64位、符合IEEE 754标准的浮点数
      默认值是 0.0f
      浮点数的默认类型为 double 类型
      double 类型同样不能表示精确的值，如货币
    ```


- 一种布尔型 (boolean)

  ``` java
   7) boolean 数据类型表示一位的信息
    默认值是 false
    只有两个取值 : true 和 false
    JAVA  语言的字符类型使用的是 Unicode 字符集(支持全球通用的字符)
    C 语言的字符类型使用的是ASCII码字符集 (占用一个字节 )
  ```

- 一种字符类型 (char-16)

  ``` java
  8) char 类型是一个单一的 16 位 Unicode 字符
    最小值是’\u0000’（即为0）
    最大值是’\uffff’（即为65,535）
    char 数据类型可以储存任何字符
  ```

- 位数排序快速查询

  ``` java
  byte 8    (-128 ~ 127)
  short 16  (-32768 ~ 32767)
  char 16   (字符串)
  int 32    (-2,147,483,648 ~ 2,147,485,647)
  float 32  ('\u0000',即为 0）~ '\uffff' 即为 65,535)
  long 64   (-9,223,372,036,854,775,808 ~ 9,223,372,036,854,775,807)
  double 64

  数自动转换
  byte(8)->short(16)->int(32)->long(64)->float(32)->double(64)

  ```

### 3、引用数据类型

- 引用类型变量由类的构造函数创建，可以使用它们访问所引用的对象。这些变量在声明时被指定为一个特定的类型，比 如 Employee、Pubby 等。变量一旦声明后，类型就不能被改变了
- 对象(object)、数组(array),都是引用数据类型
- 所有引用类型的默认值都是 (null)
- 一个引用变量可以用来引用与任何与之兼容的类型

- 补充类型

  ``` java
  1) 用来表示非常巨大，接近宇宙级别的大数字的基本数据类型
    BigInteger
    BigDecimal

  2) 字符类型
    String 对象
  ```


### 4、数据类型转换

``` java
低精度<自动>转换为高精度

高精度必须<强制>转换为低精度


/**
 * 数据类型转换
 */
public class DataType {

    public static void main(String[] args) {

        //小的 int 转换为大的 double,自动转换
        int n = 100;
        double d = n;
        System.out.println(d);

        //大的 double 转换为小的 int,需要加 (数据类型) 进行强制类型转换
        double d2 = 123.656;    //不遵循四舍五入
        int n2 = (int) d2;      //强制转换
        System.out.println(n2);

        //char 和 int 可以互相转换
        char c3 = 'A';  //char(2)
        //这里是小的 char(2) 转换为 大的 int(4)，是自动的
        int n3 = c3;    //转换为 ASCII(占用一个字节)
        //这里是大的 int(4) 转换为小的 char(2)，要强制转换
        System.out.println((char)n3);   //可以再次互相转换
    }
}

```

### 5、转义字符

``` java

\n	换行 (0x0a)
\r	回车 (0x0d)
\f	换页符(0x0c)
\b	退格 (0x08)
\s	空格 (0x20)
\t	制表符
\"	双引号
\'	单引号
\\	反斜杠
\ddd	八进制字符 (ddd)
\uxxxx	16进制Unicode字符 (xxxx)

public class DateType2 {

    public static void main(String[] args) {
        System.out.print("hello world!\n");
        System.out.print("hello world!\n");

        System.out.print("\"hello world\"\n");
        System.out.print("\'hello world\'\n");
    }

}

```

### 6、进制数制转换

``` java
十进制
二进制
八进制
十六进制

在线转换工具：http://tool.oschina.net/hexconvert/


public class IntegerTest {

    public static void main(String[] args) {
        int n = 97;
        //转换为 2 进制
        String d1 = Integer.toBinaryString(n);
        System.out.println(d1);
    }

}

```



## * Java 输出、输入

### 1、System.out

``` java
向标准输出设备输出，向控制台输出(显示器)

System.out.println();
System.out.print();
```

### 3、Scanner 获取用户输入

``` java

import java.util.Scanner;

public class Input {

    public static void main(String[] args) {
        int num;
        double d;
        boolean flag;
        String s;

        Scanner input = new Scanner(System.in);

        System.out.println("请输入一个整数");
        num = input.nextInt();

        System.out.println("请输入一个小数");
        d = input.nextDouble();

        System.out.println("请输入一个布尔值");
        flag = input.nextBoolean();

        System.out.println("请输入一个字符串");
        s = input.next();

        System.out.println(num);
        System.out.println(d);
        System.out.println(flag);
        System.out.println(s);
    }
}

```



## * 算数运算符

### 1、基本算数运算

``` java

public class MathDemo {

    public static void main(String[] args) {

        //除法
        int x1 = 10;
        int y1 = 3;
        System.out.println(x1/y1);    //分子分母都为整数，得到的是整数

        int x2 = 10;
        double y2 = 3.13;
        System.out.println(x2/y2);  //分子分母有一个为小数，得到的是小数

        //求余数
        int x3 = 10;
        int y3 = 3;
        System.out.println(x3 % y3); //结果为1
    }

}

```

### 2、位运算

#### 2.1、小技巧

不借助第三方容器，交换 x 和 y 的值

``` java
public class MathDemo {

    public static void main(String[] args) {

       //不借助第三方容器，交换 x 和 y 的值

        int x = 10;
        int y = 7;

        x = x + y;  //x = 17
        y = x - y; //y = 10
        x = x - y; //x = 7

    }

}
```



## * 面向对象

### 1、介绍

关键字
- this 指向本类
- super 指向父类
- final 定义常量
- static 定义静态资源
- package 定义包作用域
- import 引入定义过的包

修饰符
- 可访问修饰符 : default, public , protected, private
- 不可访问修饰符 : final, abstract, strictfp

Java变量
- 局部变量
- 类变量 (静态变量)
- 成员变量

源文件声明规则
- 一个源文件中只能有一个 public 类
- 一个源文件可以有多个非 public 类
- 源文件的名称应该和 publi c类的类名保持一致
- 如果一个类定义在某个包中，那么package语句应该在源文件的首行



### 2、构造方法

- 名字与类完全相同，没有返回类型的方法 (void 也是一种返回类型，构造方法不存在返回类型)
- 构造方法一般的作用是初始化
- 类中没有构造方法，系统会自动生成一个没有参数的构造方法

- 关键字
  - this(); 调用本类构造方法
  - super(); 调用父类

``` java
public class Test2 {

    private int age;
    private String sname;

    Test2  (int _age,String _sname) {
        this.age = _age;
        this.sname = _sname;
    }

    public String getSname() {
        String a = this.sname;
        return a;
    }


    public static void main(String[] args) {


      //(类型) (引用) (赋值) (-------对象--------)
        Test2 obj_2   =    new Test2(1,"jason");

        System.out.println(obj_2.getSname());
    }

}
```

### 3、方法重载

``` java
public class Test3 {

    private String name;
    private int age;

    Test3 () {

    }

    //方法重载
    Test3 (String name,int age) {
        this.name = name;
        this.age = age;
    }

    public static void main(String[] args) {
        Test3 test3 = new Test3("Jason",20);
        test3.thinking();
    }

    public void thinking () {
         System.out.println(this.name+"，年纪："+this.age);
    }

}
```

### 4、静态属性方法

- 静态属于类，而不属于对象
- 推荐用类名访问
- <静态的属性和方法，是被所有的对象共享的>

``` java
public class Test3 {

    private static int num = 0;

    Test3 () {
        System.out.println("Im,Test3");
    }

    //方法重载
    Test3 (int num) {
        this();     //本类构造方法
        Test3.num = num;
    }

    public static void main(String[] args) {
        Test3 obj_1 = new Test3();
        obj_1.thinking();
        System.out.println(Test3.num);

        Test3 obj_2 = new Test3();
        obj_2.thinking();
        System.out.println(Test3.num);

    }

    public void thinking () {
        Test3.num++;    //这里做运算被所有的对象共享
    }

}
```


### 5、静态代码块
- 静态代码块，会优先执行，在所有的构造函数前，并且不管实例多少次，只会执行一次。

``` java

public class Test3 {

    private static int num = 0;


    /**
     * 静态代码块，会优先执行，在所有的构造函数前，并且不管实例多少次，只会执行一次。
     */
    static  {
        System.out.println("静态代码块");
    }

    Test3 () {
        System.out.println("Im,Test3");
    }


    public static void main(String[] args) {
        Test3 obj_1 = new Test3();
        obj_1.thinking();
        System.out.println(Test3.num);

        Test3 obj_2 = new Test3();
        obj_2.thinking();
        System.out.println(Test3.num);

    }

    public void thinking () {
        Test3.num++;    //这里做运算被所有的对象共享
    }

}

```


### 6、抽象方法

- abstract 抽象 class 的 abstract function，不能有方法体，等价于接口

``` java


import java.util.*;

abstract class Test1 {

    int x;
    abstract void shape(int y);

}
```


### 7、Final 修饰符

- Final 变量能被显式地初始化并且只能初始化一次
- 被声明为 final 的对象的引用不能指向不同的对象。但是 final 对象里的数据可以被改变。也就是说 final 对象的引用不能改变，但是里面的值可以改变

- Final 常量 ： 通常和 static 修饰符一起使用来创建类常量

  ``` java
  public class Test {

      final int value = 10;

      public static final int BOXWIDTH = 6;

      static final String TITLE = "Manager";

      public static void main (String [] arg) {
          System.out.println(Test.TITLE);
      }

  }

  ```

- final 方法 ：类中的Final方法可以被子类继承，但是不能被子类修改

  ``` java
  class Test2 {

    public final String bbbbb () {
        //方法体

        return "aaa";

    }
  }
  ```

- Final 类 ：不能被继承，没有类能够继承 final 类的任何特性

  ``` java
  public final class Test {
     // 类体
  }

  ```


### 8、Synchronized 修饰符

- Synchronized 关键字声明的方法同一时间只能被一个线程访问。Synchronized 修饰符可以应用于四个访问修饰符

``` java
public synchronized void showDetails(){
.......
}
```


### 9、Transient 修饰符

- 序列化的对象包含被 transient 修饰的实例变量时，java 虚拟机 (JVM) 跳过该特定的变量
- 该修饰符包含在定义变量的语句中，用来预处理类和变量的数据类型

``` java
public transient int limit = 55;   // will not persist
public int b; // will persist
```


### 10、volatile 修饰符

- java 线程每次访问成员变量时，都强制从内存读取
- 当成员变量发生变化时，再强制写会内存中

``` java
public class MyRunnable implements Runnable {
    private volatile boolean active;
    public void run() {
        active = true;
        while (active) // line 1
        {
            // 代码
        }
    }

    public void stop() {
        active = false; // line 2
    }
}
```


### 11、内部类

``` java

public class Inner {

    public static void main(String[] args) {
        System.out.println("-- 通过外部类成员方位内部类成员 --");
        School a = new School();
        a.output();

        System.out.println("-- 直接方位内部类成员 --");
        School.Student b = new School().new Student("金融学院", "李四", 23);
        b.output();
    }

}

class School {
    public String name;

    /**
     *1.内部类可以随意使用外部类的成员变量（包括私有）而不用生成外部类的对象，这也是内部类的唯一优点
     *
     *2.必须先有外部类的对象才能生成内部类的对象，因为内部类的作用就是为了访问外部类中的成员变量
     */
    public class Student {
        public String name;
        public int age;

        public Student (String schoolName,String studentName,int newAge) {
            School.this.name = schoolName;
            this.name = studentName;
            this.age = newAge;
        }

        public void output() {
            System.out.println("学校：" + School.this.name);
            System.out.println("姓名：" + this.name);
            System.out.println("年龄：" + this.age);
        }
    }

    //直接调用内部类
    public void output () {
        Student stu = new Student("金融学院","张三",24);
        stu.output();
    }
}




```


### 12、继承 和 接口

``` java
//继承、接口
interface Achievemet {
    public float average();
}

class Person1 {
    public String name;
    public int age;

    public Person1 (String newName,int newAge) {
        this.name = newName;
        this.age = newAge;
    }

    public void introduce () {
        System.out.println("你好，我是" + this.name + ",今年" + this.age + " 岁");
    }
}

class Student1 extends Person1 implements Achievemet {

    public int Chinese;
    public int Math;
    public int English;


    public Student1(String newName, int newAge) {
        super(newName, newAge);
    }

    public void setScore (int c,int m,int e) {
        this.Chinese = c;
        this.Math = m;
        this.English = e;
    }

    public float average() {
        return (this.Chinese + this.Math + this.English) / 3;
    }

}

public class JieKou {


    public static void main(String[] args) {
        Student1 s1 = new Student1("张三",16);
        s1.introduce();
        s1.setScore(80, 90, 80);
        System.out.println("我的平均分是" + s1.average());
    }

}

```




## * 内存 栈内存、堆内存


### 1、介绍

- 栈内存：数据结构
- 堆内存：离散结构
- 内存分配：
  - 基本数据类型 : 在<栈>内存分配
  - 对象 : 在<堆>内存分配



## * 包 package

### 1、包的一些命名规则

- package 包名;
- 小写自字母，域名倒写
- 使用包的优点
 - 防止命名冲突
 - 便于阻止管理

- import 路径;

### 2、包的案例

``` java
A.java
package cn.com.sina; //这里是三层目录了 cn/com/sina;

public class A {

    public String[] data;

    public String[] getDataTwo(String[] data) {

        this.data = data;

        return this.data;

    }
}


Index.java
package cn.com.sina.run;

import cn.com.sina.A;

public class Index {

    public static void main(String[] args) {

        //引入包的方式
        A a = new A();
        String[] a1 = {"A","B","C"};
        System.out.println(a.getDataTwo(a1)[0]);

        //直接调用包的方式
        cn.com.sina.A b = new cn.com.sina.A();
        String[] a2 = {"A","B","C"};
        System.out.println(b.getDataTwo(a2)[0]);

    }
}
```


## * 泛型方法

### 1、介绍

- 泛型方法 : 调用时可以接收不同类型的参数
- 参数 : 注意类型参数只能代表引用型类型，不能是原始类型（像int,double,char的等）。
- 返回值类型 : 类型参数能被用来(声明返回值类型)，并且能作为泛型方法得到的实际参数类型的占位符。

### 2、案例

#### 2.1、一般泛型案例

``` java
public class GenericMethodTest {

    public static < E > void printArray ( E[] inputArray ) {
        // 输出数组
        for ( E element : inputArray ) {
            System.out.printf( "%s ", element );
        }
        System.out.println();

    }


    public static void main(String[] args) {

        //创建不同类型的数组
        Integer [] intArray = {1,2,3,4,5};
        Double [] doubleArray = {1.1,2.2,3.3,4.4,5.5};
        Character[] charArray = { 'H', 'E', 'L', 'L', 'O' };

        System.out.println( "Array integerArray contains:" );
        GenericMethodTest.printArray( intArray  ); // 传递一个整型数组

        System.out.println( "\nArray doubleArray contains:" );
        GenericMethodTest.printArray( doubleArray ); // 传递一个双精度型数组

        System.out.println( "\nArray characterArray contains:" );
        GenericMethodTest.printArray( charArray ); // 传递一个字符型型数组
    }

}

```


#### 2.2、有界类型的案例

- 比如一个操作数字的方法可能只希望接受Number或者Number子类的实例

``` java
public class MaximumTest {

    //              定义 T 泛型，结构集成 Comparable < T >     把 T 泛型作为返回类型了
    public static    <T extends Comparable < T >>            T        maximum(T x,T y,T z) {
        T max = x; // 假设x是初始最大值

        //compareTo 父泛型类方法
        if ( y.compareTo( max ) > 0 ){
           max = y; //y 更大
        }
        if ( z.compareTo( max ) > 0 ){
           max = z; // 现在 z 更大
        }
        return max; // 返回最大对象
    }


    public static void main(String[] args) {

        System.out.printf( "Max of %d, %d and %d is %d\n\n",
                3, 4, 5, maximum( 3, 4, 5 ) );

        System.out.printf( "Maxm of %.1f,%.1f and %.1f is %.1f\n\n",
                    6.6, 8.8, 7.7, maximum( 6.6, 8.8, 7.7 ) );

        System.out.printf( "Max of %s, %s and %s is %s\n","pear",
          "apple", "orange", maximum( "pear", "apple", "orange" ) );

    }

}

```

### 3、泛型类案例

``` java

public class Box<T> {

    private T t;

    //设置泛型变量
    public void add(T t) {
      this.t = t;
    }

    //T 泛型 ，作为返回类型
    public T get() {
      return t;
    }

    public static void main(String[] args) {

       //申明泛型类
       Box<Integer> integerBox = new Box<Integer>();
       Box<String> stringBox = new Box<String>();

       integerBox.add(new Integer(10));
       stringBox.add(new String("Hello World"));

       System.out.printf("Integer Value :%d\n\n", integerBox.get());
       System.out.printf("String Value :%s\n", stringBox.get());
   }

}

```


## * 多线程

### 1、案例

``` java

class ThreadB extends Thread {

    public int count = 1;
    public int num;

    public ThreadB (int newNum) {
        this.num = newNum;
        System.out.println("创建线程" + this.num);
    }

    public void run () {

        while (true) {
            System.out.println("线程" + this.num + ":计数" + this.count);
            this.count++;

            if (this.count == 3) {
                break;
            }
        }

    }

    public static void main (String[] args) {

        Thread a1 = new Thread(new ThreadB(1));
        Thread a2 = new Thread(new ThreadB(2));
        Thread a3 = new Thread(new ThreadB(3));

        a1.start();
        a2.start();
        a3.start();

        System.out.println("主方法 main() 运行结束！");
    }
}

```


## * 环境变量

### 1. SystemProperty

- 获取 java 运行时的环境变量

``` java

1. 获取 java 自身的环境变量

  System.out.println("java_vendor:" + System.getProperty("java.vendor"));

  System.out.println("java_vendor_url:"
   + System.getProperty("java.vendor.url"));

  System.out.println("java_home:" + System.getProperty("java.home"));

  System.out.println("java_class_version:"
   + System.getProperty("java.class.version"));

  System.out.println("java_class_path:"
  + System.getProperty("java.class.path"));

  System.out.println("os_name:" + System.getProperty("os.name"));
  System.out.println("os_arch:" + System.getProperty("os.arch"));
  System.out.println("os_version:" + System.getProperty("os.version"));
  System.out.println("user_name:" + System.getProperty("user.name"));
  System.out.println("user_home:" + System.getProperty("user.home"));
  System.out.println("user_dir:" + System.getProperty("user.dir"));

  System.out.println("java_vm_specification_version:"
  + System.getProperty("java.vm.specification.version"));

  System.out.println("java_vm_specification_vendor:"
  + System.getProperty("java.vm.specification.vendor"));

  System.out.println("java_vm_specification_name:"
  + System.getProperty("java.vm.specification.name"));

  System.out.println("java_vm_version:"
  + System.getProperty("java.vm.version"));

  System.out.println("java_vm_vendor:"
  + System.getProperty("java.vm.vendor"));
  System.out.println("java_vm_name:" + System.getProperty("java.vm.name"));

  System.out.println("java_ext_dirs:"
  + System.getProperty("java.ext.dirs"));

  System.out.println("file_separator:"
  + System.getProperty("file.separator"));

  System.out.println("path_separator:"
  + System.getProperty("path.separator"));

  System.out.println("line_separator:"
  + System.getProperty("line.separator"));

2. 获取 java 运行时, 自定义的环境变量
  java -Dkey1=value1 -Dkey2=value2 \
  -jar ./xxx.jar

  类中读取:
  System.out.println(System.getProperty("key1"));
  System.out.println(System.getProperty("key2"));

```


### 2. SystemEnv

- 读取 java 运行环境的环境变量, 例如 export 设置的环境变量

``` java

export PROJECT_HOME=/opt/xxx

System.out.println(System.getenv("PROJECT_HOME"));

```
