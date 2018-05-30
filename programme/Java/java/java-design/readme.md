# java 设计技巧

## 一、只对外暴露接口

思路

- 定义接口，定义需要实现的方法

  ``` java

  public interface NavBar {
      //代表导航栏接口
      public String getBarContent ();//获取导航内容
  }

  ```

- 定义一个子类，实现接口

  ```java

  public class BottomBar implements NavBar {

    @Override
    public String getBarContent() {
        String html =  "STRING";
        return html;
    }
  }
  ```

- 外部调用，返回类型是接口

  ``` java

  NavBar topBar = new BottomBar();
  topBar.getBarContent();

  ```
