# Throwable 处理

异常：可以理解意料之外的情况，异常又分为很多类

- 编译异常
- 运行异常
- 或断电、断网、服务宕机


## 异常类结构

Throwable 类是 Java 所有错误或者异常的超类，有 2 个子类

- Throwable 异常
  - Error : 指合理的程序不应该试图捕获的严重问题，比如 VirtualMachineError Java 虚拟机错误。
  - Exception ：指合理的应用程序想要捕获的条件，分为 2 类
    - RuntimeException : 由于程序错误导致的异常, 此类异常是编写者的问题
    - IOException : IO 异常
  - UncheckedException : 不需要捕获


## 代码中，异常注意事项

- 子类的异常不能比父类大，如父类是 SQLException 异常，子类的异常就不能是 Exception
- 子类的异常可以 = 父类的异常，就是说父类的异常和子类的异常在一个范围内
- 使用 try ... catch ... 语句时，注意捕获异常的数据，小异常捕获在前，大异常捕获在后


## 异常

### 捕获异常
``` java
public class ExceptionTest {

    public static void main(String[] args) {

        //发生异常之后的语句是不会执行的
        try {

            int x = 10;
            int y = 0;

            System.out.println(x/y);

            Class.forName("cn.com.A");

        }

        //捕获运算异常
        catch (ArithmeticException e) {
           e.printStackTrace();
        }
        //捕获找不到类异常
        catch (ClassNotFoundException e) {
            // 打印异常对象的堆栈信息
            e.printStackTrace();
        }

        //做一些释放资源的操作等等
        finally {
            System.out.println("异常也要执行！");
        }

    }
}

```

### 自定义异常

``` java

public class FileFormatException extends IOException{

    public FileFormatException() {

    }

    // 定义异常说明
    public FileFormatException(String gripe) {
        super(gripe);
    }


    //  
    public String 抛出异常(String s) throws FileFormatException{
        if (s == null) {
            // 抛出异常
            throw new FileFormatException();
        }

        return s;
    }

    public void test (){

        try {
            this.抛出异常("13");
        }
        //捕获运算异常
        catch (FileFormatException e) {
           e.printStackTrace();
        }
    }
}

```
