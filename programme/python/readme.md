# Python 语法

- python 一切都是对象，变量也是

## 基础语法

### 一、常用语法


#### 1、HELLO WORLD

``` python

* 创建文件
touch test.py
print "hello world";


1.指定路径执行
  /usr/bin/python test.py


2.文件首行写执行环境,像 shell 的 #!/bin/bash
  #!/usr/bin/python
  ./test.py


```

#### 2、数据类型

``` python

1、字符串
  1) str='this is string';

  2) 多行字符串
    str='''this is string
    this is pythod string
    this is string'''
  print str;

2、布尔类型
  bool=False;

3、整数
  int=20;

4、浮点数
  float=2.3;

5、数字
  包括整数、浮点数

6、列表 list
  list=['physics', 'chemistry', 1997, 2000];

7、元组 tuple
  tup1 = ('physics', 'chemistry', 1997, 2000);

8、字典
  dict = {'Alice': '2341', 'Beth': '9102', 'Cecil': '3258'};

9、日期
  import time, datetime;
  http://www.cnblogs.com/linjiqin/p/3608541.html

  time.sleep()
```



#### 3、变量赋值

``` python

1. 统一赋值
  x , y , z = 1 , 2 , 3

2. 元祖赋值
  x = 1,2,3
  print x
    (1, 2, 3)

3. 链式赋值
  x = y = 1

4. 查看变量类型
  x.__class__

5. 定义全局变量
  global a;
```


#### 4、逻辑判断

``` python

1.bool() 函数验证参数布尔值
  print bool(1)

2.if elif else
  name = raw_input("what's you name");
  if name.endswith("jason"):
    print "hello" + name;
  elif name.endswith("ray"):
    print "hello" + name
  else:
    print "who are youj"

3.表达式
  x == y
  x != y
  x is y        x 和 y 是同一个对象
  x is not y    x 和 y 不是同一个对象
  x in y        x 是 y 容器 (如，序列) 的成员
  x not in y    x 不是 y 容器 (如，序列) 的成员

4. and 和 or
  1) if ("a" == "a") and ("aaa" == "aaa1"):
      false

  2) if ("a" == "a") or ("aaa" == "aaa1"):
      true

4. None
  if xxx is None:
    pass

5. in
  if xxx in (aaaa,bbb,ccc)
    pass

6. 三元表达式
  status = True
  status and 'Y' or 'N'
```


#### 5、循环

``` python

* ps
  continue 跳过当前循环
  break    退出循环

1. while
  status = True;
  i = 0;

  while status == True :
    print i
    i += 1;
    if i > 100 :
        status = False

2. for
  1) 普通循环
    codes = ['a','b','c','d','e','f','g','h','i','j']
    for now_code in codes:
      print now_code

  2) 字典遍历
    map_data = {'a': 1 , 'b' : 2 , 'c' : 3}
    for key in map_data:
      print key , "-" , map_data[key]

  3) 深度遍历
    for i,obj in  [('host', '10.10.2.91'), ('user', 'hadoop')]:
      print i,obj

    host 10.10.2.91
    user hadoop

  4) 循环次数，0 到 10
    import math
    for i in range(0,10):
      print i

    在需要的时候生成 list  
    for i in xrange(0,10):
      print i

  5) 字典遍历
    person={'name':'lizhong','age':'26','city':'BeiJing','blog':'www.jb51.net'}

    for key,value in person.items():
      print 'key=',key,'，value=',value

    key= blog ，value= www.jb51.net
    key= city ，value= BeiJing
    key= age ，value= 26
    key= name ，value= lizhong


```


#### 6、自省

``` python

1. pass 当前行跳过,相当远行占位符


2. del 删除变量名,但不删除内存数据
  x = 1
  del x

3. exec() 执行字符语句
  1) 一般执行
    exec "print 'hello world'"

  2) 命名空间执行
    nameSpace = {} #定义一个字段
    exec "a = 1" in nameSpace; #把执行的数据放入字段中
    print nameSpace['a'] #即可全局获取

4. eval() 用于求值
  print eval("1 + 2")


5. help() 帮助
    help(str)

6. type() 查看数据类型
    type("Hello")

7. isinstance() 验证一个对象，是否是另外一个对象的类型
    isinstance('hellow',str)

8. dir() 查看对象所有的函数
    dir(str)

```


### 二、字符串

``` python

1. print "Let's go" + ",Jason"


2. repr() 和 ``(不建议使用 ``) 是把结果字符串转换为合法的 Python 表达式
  1) print repr("Hello,world!");
    'Hello,world!'

  2) print repr(10000L);
    10000L

  3) x = "test";
    print repr(x);
      'test'


3. str() 把值转换成字符串
  1) print str("Hello World");
      Hello World

  2) print str(10000L);
      10000


4. print r 原始字符输出
  1) print r'C:\Program'
      C:\Program


5. 字符串连接
  1) 关键字 %
    str = "%s %s"%('a','b');

    字典替换
    str = '''%(a)s %(b)s %(c)s'''% {'a':'a1', 'b':'b1', 'c':'c1'}


    %c	转换成字符(ASCII 码值,或者长度为一的字符串)
    %r	优先用 repr()函数进行字符串转换
    %s	优先用 str()函数进行字符串转换
    %d / %i	转成有符号十进制数
    %ub	转成无符号十进制数
    %ob	转成无符号八进制数
    %xb/%Xb	(Unsigned)转成无符号十六进制数(x/X 代表转换后的十六进制字符的大小写)
    %e/%E	转成科学计数法(e/E 控制输出 e/E)
    %f/%F	转成浮点数(小数部分自然截断)
    %g/%G	%e 和%f/%E 和%F 的简写
    %%	输出%

  2) join
    var_list = ['tom', 'david', 'john']
    #按照 , 号组合
    str = ','.join(var_list)

  3) 元祖
    xx = 'Jim', 'Green'
    str = ','.join(xx)

  4) 字符串连接
    str = "Let's go" + ",Jason"


6. split 分割字符串
    s = u'1*2*3*4'
    s.split('*')

    [u'1', u'2', u'3', u'4']


7. join 字符串
  s = u'alexzhou'
  u'*'.join(s)

  u'a*l*e*x*z*h*o*u'


8. replace 替换字符串
   s = u'111111'
   s.replace('1','2')

   u'222222'


9. 正则表达式

  *) 参数说明
    pattern: 匹配的正则表达式
    string: 要匹配的字符串
    flags: 标志位，用于控制正则表达式的匹配方式，如：是否区分大小写，多行匹配等等。
      re.I	使匹配对大小写不敏感
      re.L	做本地化识别（locale-aware）匹配
      re.M	多行匹配，影响 ^ 和 $
      re.S	使 . 匹配包括换行在内的所有字符
      re.U	根据Unicode字符集解析字符。这个标志影响 \w, \W, \b, \B.
      re.X	该标志通过给予你更灵活的格式以便你将正则表达式写得更易于理解。

  1) re.match(pattern, string, flags=0), 尝试从字符串的起始位置匹配一个模式，如果不是起始位置匹配成功的话，match()就返回none
    import re

    pattern = r'(.*) are (.*?) .*'
    line = "Cats are smarter than dogs"
    matchObj = re.match(pattern, line, re.M|re.I)

    if matchObj:
     print "matchObj.group() : ", matchObj.group()
     print "matchObj.group(1) : ", matchObj.group(1)
     print "matchObj.group(2) : ", matchObj.group(2)
    else:
       print "No match!!"


  2) re.search(pattern, string, flags=0), 扫描整个字符串并返回第一个成功的匹配
    import re

    pattern = r'(.*) are (.*?) .*'
    line = "Cats are smarter than dogs";
    searchObj = re.search(pattern, line, re.M|re.I)

    if searchObj:
     print "searchObj.group() : ", searchObj.group()
     print "searchObj.group(1) : ", searchObj.group(1)
     print "searchObj.group(2) : ", searchObj.group(2)
    else:
     print "Nothing found!!"


  3) re.sub(pattern, repl, string, count=0, flags=0), 替换

    参数解释:
      pattern : 正则中的模式字符串。
      repl : 替换的字符串，也可为一个函数。
      string : 要被查找替换的原始字符串。
      count : 模式匹配后替换的最大次数，默认 0 表示替换所有的匹配。

    import re

    phone = "2004-959-559 # 这是一个国外电话号码"

    # 删除字符串中的 Python注释
    num = re.sub(r'#.*$', "", phone)
    print "电话号码是: ", num

    # 删除非数字(-)的字符串
    num = re.sub(r'\D', "", phone)
    print "电话号码是 : ", num


  4) Pattern, 是一个编译好的正则表达式，通过Pattern提供的一系列方法可以对文本进行匹配查找

    value = "jason"
    line = "jason so cool"

    # 获取正则对象
    pattern = re.compile(value)
    # 检索
    match = pattern.search(line)

    print match.group(0)
```


### 三、数组

- 列表 (可以修改)
  - 索引
  - 分片
  - 步长
  - 序列运算
  - 成员资格 (是否存在)
  - 函数
  - 操作
- 元祖 (不能修改)
- 字典

#### 1、序列

``` python

1.定义序列
  1) 普通定义
    edward = ['Edward Gumby',42];
    john = ['John Smith',50];
    databases = [edward,john];

    print databases
      [['Edward Gumby', 42], ['John Smith', 50]]

2.索引
  1) 字符串序列 (字符串就是由字符组成的序列)
    greeting = 'Hello'
    #从第 0 个索引获取
    print greeting[0]
      H
    #从最后一个索引获取
    print greeting[-1]


3.分片 (边界操作)
  code = ['a','b','c','d','e','f','g','h','i','j']

  1) 取索引 3 ~ 6 范围数据
    print code[3:6]
      ['d', 'e', 'f']

  2) 取索引 3 ~ 最后所有的数据
    print code[3:]
      ['d', 'e', 'f', 'g', 'h', 'i', 'j']

  3) 取最后索引 -3 ~ 为止，最后的所有数据
    print code[-3:]
      ['h','i','j']

  4) 取索引 0 - 4 边界的数据
    print code[:4]
      ['a', 'b', 'c', 'd']

  4) 取索引 0 - max()-1 位置的数据
    print code[:-1]
      ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i']


4.步长 (每次数据指针跳过动的位置，默认 1)
  code = ['a','b','c','d','e','f','g','h','i','j']

  1) 默认是步长 1
    print code[0:10:1]
      ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j']

  2) 设置步长 3
    print code[0:10:3]
      ['a', 'd', 'g', 'j']


5. 序列运算
  other = ['st','nd','rd'] + 2 * ['th'] \
        + ['st','nd','rd'] + 3 * ['zd']
  print other
    ['st', 'nd', 'rd', 'th', 'th', 'st', 'nd', 'rd', 'zd', 'zd', 'zd']


6.成员资格
  1) 是否在序列中
    code = ['a','b','c','d','e','f','g','h','i','j']

    print 'a' in code
      True
    print 'z' in code
      False

  2) 组合验证
    box = [
      ['a',123],
      ['b',456]
    ]

    print ['a',123] in box
      True
    print ['a',8989] in box
      False

7.函数

  len() max() min()
  1) len()
    numbers = [1,2,3,4,5,6,7,8,9,10]
    print len(numbers)
      10

  2) max()
    numbers = [1,2,3,4,5,6,7,8,9,10]
    print max(numbers)
      10

  3) min()
    numbers = [1,2,3,4,5,6,7,8,9,10]
    print min(numbers)
      1

    print min(1,3,6,8)  
      1


  list() join()
  1) list() 字符串转数字
    arr_hello = list("Hello") ;
    print arr_hello
      ['H', 'e', 'l', 'l', 'o']

  2) join() 数组转字符串
    str_hello = ''.join(arr_hello)
    print str_hello
      Hello



  append() count() extend() index() insert() pop() remove() reverse() sort()

  1) append() 追加对象
    code = ['a','b','c','d']
    code.append('e')

  2) count() 统计元素在数组出现的次数
    code = ['a','b','c','d',[2,3]]
    code.count('a')
    code.count([2,3])

  3) extend() 在数组尾部追加 1 个或者 n 个值
    code = ['a','b','c','d']
    code.extend('a')
    code.extend([2,3])

  4) index() 找出值所在索引位置
    code = ['a','b','c','d']
    code.index('b')

  5) insert() 指定位置插入数据
    code = ['a','b','c','d']
    code.insert(3, 'zzzzz')

  6) pop() 从尾部移除数组值
    code = ['a','b','c','d']
    code.pop(2)
    print code
      ['a', 'b', 'd']

  7) remove() 移除匹配的值
    code = ['a','b','c','d']
    code.remove('b')

  8) reverse() 倒序排序
    numbers = [1,3,2,5,4]
    numbers.reverse();
    print numbers
      [4, 5, 2, 3, 1]

  9) sort() 正序排序
    numbers = [1,3,2,5,4]
    numbers.sort();
    print numbers
      [1, 2, 3, 4, 5]


8.操作

  1) 修改元素
    code = ['a','b','c','d']
    code[0] = 'aaa';
    print code
      ['aaa', 'b', 'c', 'd']

  2) 删除元素
    code = ['a','b','c','d']
    del code[2]
    print code
      ['a', 'b', 'd']

  3) 分片赋值
    code = ['a','b','c','d']
    code[1:] = list('Hello')
    print code
      ['a', 'H', 'e', 'l', 'l', 'o']

  4) 替换为空
    code = ['a','b','c','d']
    code[2:4] = []
    print code
      ['a', 'b']

```


#### 2、元祖

``` python

1.普通定义  “,” 逗号很重要(这是标识 元祖的)

  code = ('a','b','c','d',)
  print code[0]


2.函数
  tuple()  

  1) tuple() 与 list() 基本一致,序列转换成元祖
    code = ['a','b','c','d']
    print tuple(code)
      ('a', 'b', 'c', 'd')

```


#### 3、字典

``` python

1.定义 字典

  map_arr = {}
  map_arr['a'] = 123;
  map_arr['b'] = 456;
  print map_arr

2.查找元素
  print map_arr[a]

3.函数
  1) 长度
    print len(map_arr)

  2) 转换成序列、元祖
    print map_arr.items()
      [('a', 123), ('b', 456)]

  3) map_arr.has_key('a') 验证 key 是否存在

  4) map_arr.get('a') 获取值

  5) map_arr.update({'a':456}) 修改值

  6) map_arr.setdefault({'a':456})
    若key存在，则覆盖之前的值，若key不存在，则给字典添加key-value对

4. 2 个列表转换为字典
  dict(zip(['a','b','c'],[1,2,3]))

```


### 四、函数和类

#### 1、函数

``` python

1.定义函数
  1) 普通参数
    def fn_test(a,b='a'):
      print a
      print b

      return a;

    fn_test(1,2)

  2) 加 *(元祖) 的参数
    def test_fn(*args):
      print args
      print args[0]

    #把参数转换为元祖
    test_fn(1,2,3,4,6)

  3) 加 **(字典) 的参数
    def test_fn(**args):
      print args
      print args['a']

    #把参数转换为字典
    test_fn(a='aaa',b='bbb')

```


#### 2、类

``` python

1. 简单定义
  class Test:

      def __init__(self,name,age):
          self.name = name;
          self.age = age;

      def echo(self):
          return self.name + self.age;


  test = Test("a","b");


  print test.echo()

2. 继承
  class Apple(Test):
    def __init__(self,name,age):
        #调用父类方法
         Test.__init__(self, name,age)

    #重写父类方法
    def echo(self):
      return self.name + self.age


3.静态类的方法

  class Apple:

    @staticmethod
      def method():
        pass

  访问：Apple.method()
```

### 五、模块


#### 1、导入模块

``` python

#自定义包路径
global BASE_PATH;
BASE_PATH = os.getcwd();
core_path = BASE_PATH + "/core";
#sys.path.append(core_path);  

1.import model_name

2.import model_name as model_as_name
  model_as_name.function_1()

3.from model_name import function_1 as fun_as,function_2
       (文件名)           (类名)  
  fun_as()
  function_2()

```

#### 2、动态加载模块

``` python
package = 'test.test_run'
module = 'TestRun'
importClass = __import__(package, fromlist=[module])
className = getattr(importClass, module)

serviceObject = className()


```


### 六、异常

``` python

1. try except 捕获异常
  1) 执行try下的语句，如果引发异常，则执行过程会跳到第一个except语句
  2) 如果第一个except中定义的异常与引发的异常匹配，则执行该except中的语句
  3) 如果引发的异常不匹配第一个except，则会搜索第二个except，允许编写的except数量没有限制
  4) 如果所有的except都不匹配，则异常会传递到下一个调用本代码的最高层try代码中
  5) 如果没有发生异常，则执行else块代码

try:
  status = True
except Exception,ex:
  print Exception,":",ex
  status = False
else:
  xx

2。try finally
  1) try 中无论有没有发生异常都要执行代码 finally 下的代码
  2) 如果发生异常，在该异常传递到下一级try时，执行finally中的代码
  3) 如果没有发生异常，则也执行finally中的代码
try:
   block
finally:
   block

```
