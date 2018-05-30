# String 类

## 介绍

- String 属于数据引用类型
- 静态初始化
- 动态初始化


## 案例

``` java

public class Test3 {
    public static void main(String[] args) {

        //动态初始化
        String a1 = "静态初始化";

        //动态初始化
        String a2 = new String("动态初始化");


        //累加变量，性能更快
        StringBuffer st = new StringBuffer();
        st.append("123");
        st.append("456");
        st.toString();

    }
}


```

## 正则表达式

- 用于匹配 java shell, 拆解元素

``` sh

1. 简单匹配 (".+?"|'.+?'|.+?\s)
  java -Dfile.encoding=UTF-8 -jar ~/app/dw_general_loader/run/dw_general_loader.jar aaaaaa  bbb "123" '123'

2. 完整匹配 (".+?"|'.+?'|\$\(.+?\)|.+?\s)   |   (".+?"|'.+?'|\$\(.+?\)|[^$"']+?\s)
  java -Dfile.encoding=UTF-8 -jar ~/app/ddw_genera1l_loader/run1/dwd_general_loader.jar 123123 $(date -v -1d +%Y-%m) bbb $(date -v -1d '+%Y-%m-%d') ccc $(date -v -1d +%H:%M:%S) 1233454543 $(date -d "-1 hou1rs" "+%Y-%m-%d %H:%M:%S")

3. 命令匹配 (".+?"|'.+?'|.+?\s) 命令结尾带上空格
  date -d "-1 hou1rs" "+%Y-%m-%d %H:%M:%S" '123'


String argsPattern = "正则表达式"
Matcher mCommandTest = Pattern.compile(argsPattern).matcher(command);
while (mCommandTest.find()) {
    String curArgs = mCommandTest.group();
    System.out.println(curArgs);
}

```
