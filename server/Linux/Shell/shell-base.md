# 基本部分

## * 基本定义

```sh
文件头
  #!/bin/bash

公共变量
  $HOME 		//当前用户目录
  $PATH			//当前脚本路径地址
  $MAIL			//当前mail目录
  $LANG			//当前语言
  env 			//查看所有环境变量(304)
  set 			//查看所有环境变量和自己设置的变量(305)
  $$				//echo 	$$	 查看Shell线程代号，所谓的PID
  $?				//echo 	$?	 查看上一个执行命令错误信息，没有错误则为0

  $n           $1 表示第一个参数，$2 表示第二个参数 ...
   $#           命令行参数的个数
   $0           当前程序的名称
   $?           前一个命令或函数的返回码，返回0代表成功
   $*            以(参数1 参数2 ... ) 形式保存所有参数
   $@          以('参数1' '参数2' ...) 形式保存所有参数
   $$           本程序的(进程ID号)PID
   $!           最后程序的PID
   () : 将其內的命令置于 nested subshell 执行，或用于运算或命令替換。
   {} : 将其內的命令置于 non-named function 执行，或用在变量替換的界定范围。

引入文件
  source 文件.shell
  会把 source 里面执行的 shell 文件，里面的变量变成当前 shell 的全局变量

当前脚本运行路径
  basepath=$(cd `dirname $0`; pwd)

  解说
    dirname $0，取得当前执行的脚本文件的父目录
    cd `dirname $0`，进入这个目录(切换当前工作目录)
    pwd，显示当前工作目录(cd执行后的)

运算字符串中的 bash 语法
  eval '${NAME}=${VALUE}'

declare 定义变量类型
  function fn_name() {
    #申明一个局部变量
    declare var_name;
  }

  #定义数组
  declare -A var_name

  #定义整数
  declare -i var_name

  #定义函数
  declare -f fn_name

  #定义环境变量
  declare -x var_name=xxxx

# 批量注释
:<<BLOCK
123123
BLOCK

```


## * 输出

``` sh
输出
  aaa=1;
  echo $1;

原始格式输出
  bbb="2\n312312";
  echo -e "${bbb}";

/dev/null和/dev/zero是非常有趣的两个设备，它们都犹如一个黑洞，什么东西掉进去都会消失殆尽
  echo 123 >> /dev/null


* 2 错误输出,
  1 标准输出,
  0 标准输入
  & 类同与C语言中的取地址，上述中代表错误输出2也重定向到标准输出1的设备上。
  tee 据双定向, 接收管道传递过来的信息，将其保存到文件，同时也在屏幕上输出。


关闭所有输出
  xxx1.sh xxx2.sh 1>&- 2>&-

开启所有输出
  xx1.sh >> a.log 2>&1

把输出结果定位到空中，最后一个 & 表示后台运行,不能有分号
  xxx.sh > /dev/null  2>&1  &  


tee 管道符号使用

function log_format() {
	local import=`tee`
	local curDate=$(date -d today +"%Y-%m-%d")
	local curTime=$(date -d today +"%Y-%m-%d %H:%M:%S")

  echo "${curTime}:  ${import}"
}

echo "aaaBBBB"  | log_format

```


## * 基本语法

### 1、定义变量
``` sh
定义普通变量
  a=1;
  echo $a;
  echo "a is ${a}";
  -e 解析转义字符
  -n 回车不换行，Linux系统默认回车换行。


定义默认值语法:
  案例 1
  当变量 a 存在使用变量 a,如果变量 a 为空,使用 b
  c=${a:-${b}};

  案例 2
  当变量 a 存在使用变量 a,如果变量 a 为空,则使用 string
  b=${a:-string} ;
```


### 2、$() 和 `` 关键字

``` sh

反短斜线 (``)
  可以让一个命令的输出，作为另外一个命令行的命令参数
  如: tar -zcvf lastmod.tar.gz `find . -mtime -1 -type f -print`;

$() 把执行后的数据获取到
  如:
  date_time=$(date -d today +"%Y-%m-%d %H:%M:%S");
  echo $date_time;

```


### 3、流程控制
- 注意空格位置

``` sh
文件比较
  [ -f "somefile" ] ：判断是否是一个文件
  [ -e "somefile" ] : 如果 filename存在，则为真
  [ -x "/bin/ls" ] ：判断/bin/ls是否存在并有可执行权限
  [ -n "$var" ] ：判断$var变量是否有值
  [ "$a" = "$b" ] ：判断$a和$b是否相等 ,注意“=”和变量之间要有空格。
  [ -r "$mailfolder" ] 文件是否可读
  [ -s file ] 如果文件大小不为零则为真
  [ -w file ] 如果文件可写则为真
  [ ! -d $log_data_dir ]; 如果目录不存在

算术比较:
  expression1 -eq expression2    如果相等则为真
  expression1 -ne expression2    如果不等则为真
  expression1 -gt expression2    如果大于则为真
  expression1 -ge expression2    大于等于则为真
  expression1 -lt expression2    如果小于则为真
  expression1 -le expression2    小于等于则为真
  !expression            如查为假则为真

字符串比较:
  string1 = string2     如果相等则为真
  string1 != string2    如果不等则为真
  [ -n "${log_data_dir}" ]; 如果不空则为真
  [ -z "${log_data_dir}" ]; 如果为空则为真


if语法
  if [ -f "lastmod.tar.gz" ];then
    echo 123;
  elif [  $timeofday = “no” ]; then
     echo “Good afternoon”
  else
    echo 456;
  fi;


多条件组合 & 匹配,配合 echo "" 使用技巧
  案例 1:
  if [ ${mysql_rows} -gt 0 ] && echo "" && [ ${db_sync_table_rows} -eq 0 ];  then
    echo 123;
  fi

  案例 2:
  if [ -f file_one ] && echo “hello” && [ -f file_two ] && echo “ there” ;then
      echo “in if”
  else
      echo “in else”
  fi

多条件组合 || 匹配
   if [ $xx = "xx" ] || [ $xx = "xx" ];then
      echo $xx
   fi


三元运算符 && 和 ||

  && :  第一个命令执行成功，才执行下一个，反之不执行
  || :  第一个执行不成功，才则执行下一个，反之不执行

  左边的表达式为真，则执行右边的语句
  [ -f "/etc/shadow" ] && echo "This computer uses shadow passwors";

  左边的表达式不为真，则执行 Can not read,三元表达式
  [ -r "lastmod.tar.gz" ] || { echo "Can not read lastmod.tar.gz" ; echo 4; }


case 语法
  echo “Is it morning? Please answer yes or no”
  read timeofday
  case $timeofday in
      yes) echo “Good Morning”;;
      no ) echo “Good Afternoon”;;
      y ) echo “Good Morning”;;
      n ) echo “Good Afternoon”;;
      * ) echo “Sorry, answer not recognized”;;
  esac

```


## * 数组

- bash 的数组使用空格作为分隔的

``` sh

数组长度
  arr_length=${#arr[@]};


定义数组
  arr_1=(1 2 3 4);

  arr_2[1]="123";

输出
  echo ${arr_1[1]};

map 数组
  定义 map 数组
  declare -A map_arr
  map_arr[a]=1
  map_arr[b]=2

```


### 4、循环

``` sh
(for) 创建了一个变量foo,并且在for循环中每次赋于一个不同的值
  for foo in bar fud 43
  do
     echo $foo
  done


(for) 命令所使用的参数列表是由包含在,$()中的命令的输出来提供的
  for file in $(ls /);
  do
    echo $file
  done


(for) 循环数组
  table_config=(
      dw_basis_angejia.broker_team_member-1
      dw_basis_angejia.broker_team-1
  );
  for now_info in ${table_config[@]};
    do
      #按照 - 转换为数组
      arr_now_info=(${now_info//-/ });
      echo $now_info;
    done;
  }

(for) 递减循环
  for i in $(seq $offset_day -1 1 )
  do
      echo $i
  done

(while) 无限循环，直到满足条件
  while [ “$trythis” != “secret” ];
  do
    echo “Sorry, try again”
    #read 变量  ： 监控用户输出
    read trythis
  done


(while) 满足条件后才停止
  foo2=1
  while [ $foo2 -le 20 ]
  do
     echo “Here we go again”
     foo2=$(($foo2+1))
  done


(until) 语句适用于我们希望进行循环直到某件事发生时为止的情况
  until who | grep “$1” > /dev/null
  do
     sleep 60
  done

```


## * FUNCTION 函数

- Shell函数返回值，一般有3种方式：return，argv，echo

``` sh
定义函数
  foo() {
      #echo 也是返回值
      echo “Function foo is executing”;
      #表示成功
      return 0;
  }


定义 echo 返回值的函数
  function myfunc()
  {
      local  myresult='some value'
      echo "$myresult"
  }
  result=$(myfunc);
  echo $result;


函数接收传入的参数
  function getArgs () {
    echo $0;
    echo $1;
    echo $2;
    echo $3;
  }
  getArgs 1 2 3


处理技巧
  fn_result_1=`getArgs 1 2 3`; #获取函数的返回值
  fn_result_2=$(getArgs 4 5 6); #获取函数的返回值

```


## * Date 处理

- [优秀文章](文档地址：http://www.xuebuyuan.com/1122682.html)

``` sh
基本
  yesterday=`date -d yesterday "+%Y%m%d"`; #昨天日期
  today=`date -d today "+%Y%m%d"`;  #今天日期
  tomorrow=`date -d "1 day" +"%Y%m%d"`; #明天日期

当前时分秒
  echo $(date -d today "+%Y-%m-%d %H:%M:%S");

当前时间戳
  echo $(date -d today "+%s");

转换日期为时间戳
  echo $(date -d "2010-10-18 00:00:00" "+%s");

时间戳转换为日期 ，格式为 2010-10-17 23:58:40
  echo $(date -d "@1287331120" "+%F %T");

日期格式化日期
  echo $(date -d 20151112 +%Y-%m-%d);

日期累加
  m_date=20150406;
  for (( i=1; i<22; i++)); do
      m_date=`date -d "${m_date} +1 day " +%Y%m%d`;
      echo $m_date;
  done

获取两个小时前日期
  date -d "-2 hours"
  $(date -d "-2 hours" "+%Y-%m-%d")

```

## * String 处理

- [文章1](http://blog.chinaunix.net/uid-124706-id-3475936.html)
- [文章2](http://www.cnblogs.com/chengmo/archive/2010/10/02/1841355.html)

### 1、常用处理

``` sh
字符串处理
  ${#string}	$string的长度

  ${string:position}	在$string中, 从位置$position开始提取子串
  ${string:position:length}	在$string中, 从位置$position开始提取长度为$length的子串

  ${string#substring}	从变量$string的开头, 删除最短匹配$substring的子串
  ${string##substring}	从变量$string的开头, 删除最长匹配$substring的子串
  ${string%substring}	从变量$string的结尾, 删除最短匹配$substring的子串
  ${string%%substring}	从变量$string的结尾, 删除最长匹配$substring的子串

  ${string/substring/replacement}	使用$replacement, 来代替第一个匹配的$substring
  ${string//substring/replacement}	使用$replacement, 代替所有匹配的$substring
  ${string/#substring/replacement}	如果$string的前缀匹配$substring, 那么就用$replacement来代替匹配到的$substring
  ${string/%substring/replacement}	如果$string的后缀匹配$substring, 那么就用$replacement来代替匹配到的$substring


匹配字符
  echo $e | grep "[a-zA-Z]*"
  echo $ic | grep "[0-9a-zA-Z]*"


查找长度
  echo ${#var}


字符串转换数组
  var="get the length of me";
  #这里把字符串var存放到字符串数组var_arr中了，默认以空格作为分割符
  var_arr=($var);


替换字符，把 . 替换为 --
  var=${mysql_info//./--}
把 . 替换为空格并且转换为数组
  var=(${mysql_info//./ });


分割成数组
  string=16:49:32.840500453;
  #把字符串 $string,按照"."作为分隔符拆分，并且放到 var 数组中
  split($string,var,".");

字符串第一次出现的位置
  echo `expr index "$string" "#"`;

截取字符串  
  echo ${string1:0:${#string1}-1};

```

### 2、处理工具

#### 2.1、awk 针对列级别处理

``` sh

读取变动信息，打印出来
  如：
  Modify: 2015-05-28 16:50:53.544119805 +0800
  $1      $2         $3                 $4
  #一行行处理匹配到的数据
  stat ./readme.md | grep Modify | awk '{
    #把 $3(16:50:53.544119805) , 按照.号分割成数组，放如 var 数组中
    split($3,var,".");
    printf $2;
    printf " ";
    printf var[1];
  }';


截取字符串
  ${log_file:0:10} 从 0 开始取 10 个
  echo $log_file | awk '{printf("%d\n", match($0,"web"));}' 查找web出现的位置


逐列处理, 删除指定进程
  ps -aux | grep xxx | awk '{print $2}' | while read pid;
  do
    kill -9 $pid;
  done

逐列处理, 删除指定进程
  # grep -v grep 过滤 grep , awk 查出第二列,  xargs 行转列
  ps -ef | grep "tomcat" | grep -v grep | awk  '{print $2}' | xargs kill -9


xargs:
  用作替换工具，读取输入数据重新格式化后输出

  # 假如你有一个文件包含了很多你希望下载的URL，你能够使用xargs下载所有链接：
  cat url-list.txt | xargs wget -c


su 切换账号执行
  su - hadoop -c /path/scripts.sh
```

#### 2.2 sed 针对行级别处理

- [文章](http://blog.chinaunix.net/uid-8656705-id-2017937.html)

``` sh
读取文件第 4 行数据(指定行,某一行)
  sed -n '4p' file


读取字符串第 4 行数据
  cat file | sed -n '4p'


读取字符串第 1- 4 行数据  
  cat file | sed -n '1,4p'


sed 正则表达:

  s/regexp/replacement/flag  :   用 replacement 替换模式空间由 regexp 匹配到的内容


  替换命令 s/regexp[行匹配值]/replacement[行替换的值]/flag 中 的flag：

    (flag)
      g : 进行全局替换。不使用此选项将只对该行匹配到的第一个结果进行替换
      p : 打印模式空间中的内容（替换之后的内容）
      w : filename将替换之后的内容写入文件filename

  正则案例(sed 的正则可以写多种组合,都是针对行的级别的,每个正则用 ; 分隔即可)
    导出 csv 格式
    mysql -h10.10.2.91 -uhadoop -pangejia888 -s -e "select * FROM test.performance_mb limit 10" | sed 's/\t/","/g; s/^/"/g; s/$/"\r/g'

    导出自定义格式
    mysql -h10.10.2.91 -uhadoop -pangejia888 -s -e "select * FROM test.performance_mb limit 10" | sed 's/\t/,/g; s/$/\n/g';

    处理 Mysql NULL 和换行符
    mysql -h10.10.2.91 -uhadoop -pangejia888 -s -e "select * FROM test.performance_mb limit 10" | sed 's/[\n|\r\n|\\n]/\^/g;s/NULL/\\N/g;'


  sed -r 's/ +/ /g' 貌似一行一行处理的

```

#### 2.2 tr 命令 用来从标准输入中通过替换/删除进行字符转换

``` sh
$(find /etc/hive/auxlib -name '*.jar' | sort | tr '\n' ',' | head -c -1)

$(ls /etc/hive/conf/log4j.properties)
```


## * 运算、累加
``` sh
循环累加字符串
  for fn_cgt_c2_field in aaa bbb ccc;
  do
    fn_cgt_c2_snapshoot_format_table_fields+="${fn_cgt_c2_field} string,";
  done


循环累加数字
  i=0;
  while [ ${i} -le ${parameter_num} ];
  do
     i=$(($i+1));
     #i=`expr $i + 1`;
  done


循环一行行处理文件
  案例 1: (需要有实体文件,可以在循环中累加)
    while read line;
    do
      echo $line;
      eval "${line_conf[0]}=${line_conf[1]}";
    done < $file_dir;

  案例 2: (不需要有实体文件，但是无法在循环中累加)
    echo "$fn_gtc_result" | while read line;
    do
      ehco $line;

    done

  案例 3: (使用 wc 和 sed),此方法可以累加,并且不需要实体文件
    #获取字符串行数
    count=`echo "${String}"|wc -l`;
    #循环
    for (( i=1; i<=$count; i++)); do
        #读取每一行数据
        line=`echo "${String}" | sed -n "${i}p"`
    done;

```



## * 文件处理

### 1、统计文件行数

- [文章](http://www.cnblogs.com/fullhouse/archive/2011/07/17/2108786.html)

``` sh
统计文件行数 (以及查看文件请看 base.txt cat 章节)
  grep -c "" uba_web_visit_20150320.log
  wc -l uba_web_visit_20150320.log
  awk 'END{print NR}' uba_web_visit_20150320.log
  sed -n '$=' filename
```

## * 异步程序

``` sh

{
  echo 123;
  sleep 2
} &
# 等待所有执行完成再执后面操作
wait


```

## * 参数处理

- getopts shell builtin 内键指令 ,不支持长的选项（如：--prefix 等）
- getopt  外部 binary 文件 , 支持长选项

### 1. getopts 短参数用法

``` sh
# :s:h 表示这个命令接受 2 个带参数选项，分别是 -h 和 -s
# 第一个 : 表示屏蔽报错
# s: 表示这个字符必须有附加的参数
# h  表示不需要有参数
while getopts ":s:h" opt
do  
    case $opt in
        s)  
            echo "-s=$OPTARG"
            s=$OPTARG
            ;;
       :)
            echo "-$OPTARG 需要一个参数"
            ;;
       h)  
            echo "-h=$OPTARG"
            h=$OPTARG
            ;;
        *)  
            echo "-$opt not recognized"
            ;;
    esac
done

echo $s
echo $h
```

### 2. getopt 长参数用法

- getopt 命令不能很好的处理带空格的参数值，它将空格解析为参数分隔符，而不是将双引号引起来的两个值合并为一个参数。

``` sh

# 命令格式 getopt options optstring parameters
# -o D:T:SH  表示短参数
# -l database:,table:,status,help  表示长参数
# : 表示需要填写 args 的
# 注意: 第一个参数必须 短参数和长参数一起使用

ARGS=`getopt -a -o D:T:SH -l database:,table:,status,help -- "$@"`  
[ $? -ne 0 ] && usage  
#set -- "${ARGS}"  
eval set -- "${ARGS}"

while true  
do  
        case "$1" in
        -D|--database)  
                database="$2"
                echo "database : ${database}"
                shift  
                ;;  
        -T|--table)  
                table="$2"
                echo "table : ${table}"
                shift  
                ;;  
        -S|--status)  
                STATUS="yes"
                echo "STATUS : yes"
                ;;  
        -H|--help)  
                usage  
                ;;  
        --)  
                shift  
                break
                ;;  
        esac  
shift  
done

```
