# Java EE

## 概念

### 1、Servlet 和 Jsp

```

1.Servlet 和 Jsp 其实是完全统一的，二者在底层运行原理是完全一样的。

2.Jsp 必须被 Web 编译成 Servlet , 真正运行的是 Servlet . Jsp 相当于一个“草稿”文件，Web 服务器根据该“草稿”文件来生成 Servlet 。

3.真正提供 HTTP 服务的是 Servlet ，广义的 Servlet 包含了 JSP 和 Servlet.

4.Web 应用中，每个 JSP 页面都会由 "Servlet 容器" 生成对应的 Servlet。对与 Tomcat 来说，JSP 页面生成的 Servlet 放在 work 路径对应的 Web 应用下。


```



### 2、数据传输

```

1.Jsp 只负责简单的展示，所以 Jsp 无法访问应用的底层状态。Java EE 使用 JavaBean 来传输数据。

2.在严格的 Java EE 应用中，中间层的组件会将应用状态信息封装成 JavaBean 集，这些 JavaBean 被称为 DTO(Data Transfer Object,数据传输对象)
并将这些 DTO 集传到 JSP 页面，从而让 JSP 可以显示应用的底层状态

```


### 3、相关技术以及替代

Java EE 开发可已分为 2 种

- 以 Spring 为核心的的轻量级 Java EE 开发平台

  ```

  Struts :
    实现 MVC。
    替代者 Spring MVC 和 JSF

  Hibernate :
    持久层，ORM(Object Relation Mapping) ，对象关系映射
    替代者 MyBatis ，可以将 SQL 语句查询结果映射成对象，因此 MyBatis 也称为 SQL Mapping 工具。

  Spring :
    中间层容器，上可以与 MVC 框架整合，下可以与持久层框架整合。Spring 充满了各种设计模式的应用，如单例模式、工厂模式、抽象工程模式、命令行模式等等
    目前没有更好的替代

  ```

- 以 EJB3 + JPA 为核心的 Java EE 开发平台




## JSP / Servlet 详解

### Servlet 介绍

Jsp 的实质就是 Servlet，所以操作 JSP 相当操作 Servlet 的对象属性和方法

### 1、JSP 申明以及注意事项

- JSP 申明将会转换成对应的 Servlet 的成员变量、或者成员方法
- JSP 页面会编译成一个 Servlet 类，每个 Servlet 类在容器中只有一个实例
- JSP 申明可以适用 Private | Public 修饰符，也可以使用 static 修饰，但不能适使用 abstract 修饰符，因为抽象方法会导致 JSP 对应 Servlet 变成抽象类，从而导致无法实例化

  ``` jsp

  jsp 申明方法

  <%!
  public int count;

  public String info () {
      return "hello";
  }
  %>

  <!-- 普通输出 -->
  <%
  out.println(this.count++);
  out.println(this.info());
  %>

  <!-- 等价于上述 -->
  <%=this.count++ %>
  <%=this.info() %>

  ```

### 2、JSP 3 个编译指令

- page : 该指令是针对当前页面的指令
- include : 用于指定包含另一个页面
- taglib : 用于定义和访问自定义标签

  ``` jsp

  编译指令的语法格式
  <%@ 编译指令名 属性名称="属性值" %>

  详细格式：71 page

  案例
    1. include 引入其他文件
    静态导入
    <%@include file="test1.jsp" %>

    动态导入

    <jsp:include page="test1.jsp" flush="true" >
      <jsp:param name="a" value="1"/>
    </jsp>

  ```

### 4、JSP 的 7 个动作指令

```
1. jsp:forward 执行页面跳转
  <jsp:forward page="test1.jsp">
  	<jsp:param name="age" value="29"/>
  </jsp:forward>


2. jsp:param 传递参数，即使跨越一级、二级或者跟多页面，参数也不会丢失
  比如 post 给 a.jsp -> b.jsp -> c.jsp ，在 c.jsp 依旧可以获得提及的参数

  <jsp:param name="age" value="29"/>

  其他页面通过这个方法获取
  request.getParameter("age")


3. 动态 include 指令，仅导入页面的 body 内容插入本页面
  <jsp:include page="scriptlet.jsp" flush="true"/>
    <jsp:param name="age" value="32"/>
  </jsp:include>


4. useBean(在 JSP 页面初始化实例)、setProperty、getProperty
  用途 :
    在多个 JSP 页面需要重复使用某段代码，则可以把这段代码定义成 Java 类的方法，让多个 JSP 页面调用该方法。

  语法 :
    <jsp:useBean id="p1" class="lee.Person" scope="page"/>
    id : JavaBean 实例名称
    class : JavaBean 实现的实体类(如 lee 包下的 Person 类)
    scope : JavaBean 实例的作用范围
      page : 本页面
      requset : 本次请求
      session : 本次 session
      application : 本应用内

  案例 :
    <!-- 创建lee.Person的实例，该实例的实例名为p1 -->
    <jsp:useBean id="p1" class="lee.Person" scope="page"/>
    <!-- 设置p1的name属性值 -->
    <jsp:setProperty name="p1" property="name" value="crazyit.org"/>
    <!-- 设置p1的age属性值 -->
    <jsp:setProperty name="p1" property="age" value="23"/>
    <!-- 输出p1的name属性值 -->
    <jsp:getProperty name="p1" property="name"/><br/>
    <!-- 输出p1的age属性值 -->
    <jsp:getProperty name="p1" property="age"/>

  page:79

```

### 5、JSP 脚本中的 9 个内置对象

- JSP 脚本中包含 9 个内置对象，这个 9 个内置对象都是 Servlet API 接口的实例，JSP 默认对这个几个对象进行了初始化，可以直接使用

``` java
page 82

概念 :
  Web 应用的 JSP 页面、Servlet 都将由 Web 服务器调用，Jsp 和 Servlet 不会互相调用
  Jsp 和 Servlet 交互数据，是使用 Web 服务器提供的 4 个 MAP 结构，让 Jsp 和 Servlet 把数据放入到这 4 个 MAP 结构中

  4 个 MAP 结构分别是 :
    application : 整个应用
    session : 本次会话
    request : 本次请求
    page : 当前页面


1. application 对象
  page 84

  application 案例 :
    <!-- JSP声明 -->
    <%!
    int i;
    %>
    <!-- 将i值自加后放入application的变量内 -->
    <%
    application.setAttribute("counter",String.valueOf(++i));
    %>
    <!-- 输出i值 -->
    <%=i%>

    <!-- 直接输出application 变量值 -->
    <%=application.getAttribute("counter")%>


  读取 web.xml 配置文件内容
    web.xml 配置内容
      <context-param>
    		<param-name>driver</param-name>
    		<param-value>com.mysql.jdbc.Driver</param-value>
    	</context-param>

    从配置参数中获取驱动
      String driver = application.getInitParameter("driver");


2. config 对象
  读取 web.xml 配置文件内容，到 Servlet

  web.xml
    <servlet>

      <servlet-name>TestConfig</servlet-name> <!-- 指定 TestConfig Servlet 名字  -->

      <jsp-file>/configTest2.jsp</jsp-file> <!-- 指定 /configTest2.jsp 页面配置成 Servlet -->

      <init-param> <!-- 配置 MAP 值 -->
        <param-name>name</param-name>
        <param-value>crazyit.org</param-value>
      </init-param>

      <init-param> <!-- 配置 MAP 值 -->
        <param-name>age</param-name>
        <param-value>30</param-value>
      </init-param>
    </servlet>

    <servlet-mapping>

  		<servlet-name>TestConfig</servlet-name> 	<!-- 将 TestConfig Servlet 映射到 /config 路径，并且必须使用此路径，才能读取到 web.xml 中配置的值  -->
  		<url-pattern>/config</url-pattern>
  	</servlet-mapping>

  读取 web.xml 配置内容
    <%=config.getInitParameter("name")%>


3. exception 对象


4. out 对象
  对页面输出流
  out.println("1")

  <%=...%> 的本质就是 out.write(...);


5. page 对象


6. pageContext 对象
  page : 93
  代表页面的上下文，该对象用于访问 JSP 之间的共享数据，使用 pageContext 可以访问 page、request、session、application 范围的变量

  x.jsp 中
  上下文设置 :
    SCOPE 范围值 :
      pageContext.PAGE_SCOPE : 当前页面，默认就是当前页面
      pageContext.REQUEST_SCOPE : 当前请求
      pageContext.SESSION_SCOPE : 当期会话
      pageContext.APPLICATION_SCOPE : 当前应用

    设置上下文值 :
      pageContext.setAttribute("key","val",SCOPE)

    获取 :
      pageContext.getAttribute("key",SCOPE)

  还可获取其他对象:
    pageContext.getRequest() : 请求对象
    pageContext.getResponse() :  响应对象
    pageContext.getServletConfig() : conf 对象，获取当前 Servlet 对象的配置参数
    pageContext.getServletContext() : application 对象，获取整个 Web 对象的配置参数
    pageContext.getSession() : seesion 对象


7. request 对象
  page 95

  jsp 页面中

  1) 获取所有请求头的名称
    Enumeration<String> headerNames = request.getHeaderNames();
    while(headerNames.hasMoreElements())
    {
    	String headerName = headerNames.nextElement();
    	// 获取每个请求、及其对应的值
    	out.println(
    		headerName + "-->" + request.getHeader(headerName) + "<br/>");
    }

  2) 提交的字段
    //对请求的字符进行转码成 UTF-8
    request.setCharacterEncoding("UTF-8");
    String gender = request.getParameter("gender");
    // 如果某个请求参数有多个值，将使用该方法获取多个值
    String[] color = request.getParameterValues("color");


  3) 案例
    // 获取请求里包含的查询字符串
    String rawQueryStr = request.getQueryString();
    out.println("原始查询字符串为：" + rawQueryStr + "<hr/>");

    // 使用URLDecoder解码字符串
    String queryStr = java.net.URLDecoder.decode(
    rawQueryStr , "UTF-8");
    out.println("解码后的查询字符串为：" + queryStr + "<hr/>");

    // 以&符号分解查询字符串
    String[] paramPairs = queryStr.split("&");
    for(String paramPair : paramPairs)
    {
    out.println("每个请求参数名、值对为：" + paramPair + "<br/>");

    // 以=来分解请求参数名和值
    String[] nameValue = paramPair.split("=");
    out.println(nameValue[0] + "参数的值是：" +
      nameValue[1]+ "<hr/>");
    }


8. response 对象
  x.jsp 页面中

  1) 重定向，生成第二次请求
    response.sendRedirect("redirect-result.jsp");

  2) Cookie
    设置 Cookie :
      Cookie c = new Cookie("username" , "jason");
      // 设置Cookie对象的生存期限
      c.setMaxAge(24 * 3600);
      // 向客户端增加Cookie对象
      response.addCookie(c);

    获取本站在客户端上保留的所有 Cookie :
      Cookie[] cookies = request.getCookies();
      // 遍历客户端上的每个Cookie
      for (Cookie c : cookies)
      {
      	// 如果Cookie的名为username，表明该Cookie是需要访问的Cookie
      	if(c.getName().equals("username"))
      	{
      		out.println(c.getValue());
      	}
      }


9. session 对象
  session 对象是 HttpSession 的实例

  设置和获取 Session :
    session.setAttribute("a" , "b");
    session.getAttribute("a");

```


### 6、Servlet 配置

page : 110

从 Servlet 3.0 开始，配置 Servlet 有 2 种方式

- 1.通过 web.xml 文件中进行配置
- 2.在 Servlet 类中使用 @WebServlet 注解进行配置


#### 6.1 通过 web.xml 文件中进行配置

``` java

web.xml (需要配置 <servlet> 和 <servlet-mapping>) :

  <!-- 配置一个 Servlet -->
	<servlet>
		<!-- ָ指定 Servlet 的名字，相当与指定 @WebServlet 的 name 属性-->
		<servlet-name>firstServlet</servlet-name>
		<!-- 指定 Servlet 的实现类 ָ-->
		<servlet-class>lee.FirstServlet</servlet-class>
	</servlet>

	<!-- 配置 Servlet 的URL -->
	<servlet-mapping>
		<!-- 需要配置 Servlet 的名字 -->
		<servlet-name>firstServlet</servlet-name>
		<!-- firstServlet 的访问 URL 路径  -->
		<url-pattern>/aa</url-pattern>
	</servlet-mapping>


java 代码 :

  package jason;

  import javax.servlet.*;
  import javax.servlet.http.*;
  import javax.servlet.annotation.*;

  import java.io.*;

  public class ServletHttp extends HttpServlet {

      //表示统一用 service 处理 POST 和 GET 请求
      public void service(HttpServletRequest request,
              HttpServletResponse response)
      throws ServletException,java.io.IOException {

          response.setContentType("text/html;charSet=UTF-8");

          PrintStream out = new PrintStream(response.getOutputStream());
          out.println("<html>");
          out.println("<head>");
          out.println("<title>第一张页面</title>");
          out.println("</head>");
          out.println("<body>");
          out.println("你好呀");
          out.println("</body>");
          out.println("</html>");
      }
  }

```

#### 6.2 在 Servlet 类中使用 @WebServlet 注解进行配置

``` java

web.xml :
  <servlet>
       <servlet-name>ServletHttp</servlet-name>
       <servlet-class>jason.ServletHttp</servlet-class>
   </servlet>

java 代码 :
  package jason;
  import javax.servlet.*;
  import javax.servlet.http.*;
  import javax.servlet.annotation.*;

  import java.io.*;

  @WebServlet(name="ServletHttp"
  , urlPatterns={"/servletHttp"})
  public class ServletHttp extends HttpServlet {

      public void service(HttpServletRequest request,
              HttpServletResponse response)
      throws ServletException,java.io.IOException {

          response.setContentType("text/html;charSet=UTF-8");

          PrintStream out = new PrintStream(response.getOutputStream());
          out.println("<html>");
          out.println("<head>");
          out.println("<title>第一张页面</title>");
          out.println("</head>");
          out.println("<body>");
          out.println("你好呀");
          out.println("</body>");
          out.println("</html>");
      }
  }

```


#### 6.3 Servlet 生命周期以及配置

page 112

Servlet 创建时机

- 1.Web 容器启动时 ，load-on-startup
- 2.客户端每次请求时，系统创建

Servlet 创建流程

创建 Servlet -> init() 方法初始化 -> 响应客户端请求 -> destroy() 回收资源 -> 实例销毁


#### 6.4 Servlet 角色

page 115

- Servlet 当做 Controller
- JavaBean 充当 Model
- Jsp 用作 View


#### 6.5 Servlet 3.0 新特性 - 注解

Page 159

``` java

- @WebListener 修饰 Listener 类，用于部署 Listener

- @WebFilter 修饰 Filter 类，用于部署 WebFilter

- @WebServlet 修饰一个 Servlet 类，用于部署 Servlet
 - @WebInitParam 用于与 @WebServlet 或 @WebFilter 一起使用，为 @WebServlet 或 @WebFilter 配置参数

- @MultipartConfig 用于修饰 Servlet 类，该 Servlet 会处理 multipart/form-data 类型的请求，用于文件上传

- @ServletSecurity  与 JASS 有关的注解，修饰 Servlet 指定该 Servlet 的安全与授权控制
  - @HttpConstraint 用于与 @ServletSecurity 一起使用，用于指定该 Servlet 的安全与授权控制
  - @HttpMethodConstraint 用于与 @ServletSecurity 一起使用，用于指定该 Servlet 的安全与授权控制

```

#### 6.6 Servlet 3.0 新特性 - 模块支持

Page 160

用于部署、管理 Web 模块和先后加载顺序，主要管理 jar 包的加载顺序


- 结构

  ```
  xxx.jar
    |-- META-INF
      |-- web-fragment.xml
    |-- 所有资源等

  ```

- 案例
 - a.jar (META-INF/web-fragment.xml 文件)

   ``` xml
   <?xml version="1.0" encoding="UTF-8"?>
    <web-fragment xmlns="http://xmlns.jcp.org/xml/ns/javaee"
        xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
        xsi:schemaLocation="http://xmlns.jcp.org/xml/ns/javaee  
        http://xmlns.jcp.org/xml/ns/javaee/web-fragment_3_1.xsd" version="3.1">
        <!-- ָWeb 模块唯一标识 -->
        <name>moudle_1</name>
        <!-- 自定义配置 -->
        <listener>
            <listener-class>lee.CrazyitListener</listener-class>
        </listener>
        <!-- 配置加载顺序 -->
        <ordering>
            <!-- before 用于配置本模块在哪些模块之前加载 -->
            <before>
                <!-- others 标识 在所有模块之前加载 -->
                <others/>
            </before>
        </ordering>
    </web-fragment>
   ```

 - b.jar (META-INF/web-fragment.xml 文件)

   ``` xml
     <?xml version="1.0" encoding="UTF-8"?>
     <web-fragment xmlns="http://xmlns.jcp.org/xml/ns/javaee"
     	xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
     	xsi:schemaLocation="http://xmlns.jcp.org/xml/ns/javaee  
     	http://xmlns.jcp.org/xml/ns/javaee/web-fragment_3_1.xsd" version="3.1">
     	<!-- Web 模块唯一标识 -->
     	<name>moudle_4</name>
     	<!-- 自定义配置 -->
     	<listener>
     		<listener-class>lee.LeegangListener</listener-class>
     	</listener>
     	<ordering>
     		<!-- after 表示该 web 模块必须位于哪些模块只有加载 -->
     		<after>
     			<!-- 表示必须等这些 web 模块加载好才能加载 -->
     			<name>moudle_1</name>
          <name>moudle_2</name>
          <name>moudle_3</name>
     		</after>
     	</ordering>
     </web-fragment>
   ```

 - 最后在项目中导入这些 jar，web 启动后会自动按照配置的顺序加载


#### 6.7 Servlet 3.0 新特性 - 异步处理

Page 162

- 异步允许 Servlet 启用一个新的进程处理请求,防止堵塞


#### 6.7 Servlet 3.0 改进 Servlet API

- 文件上传 166



### 7、Jsp 2

#### 7.1 Jsp 2 的自定义标签

Page 119

- Sun 公司提供了一套为 JSTL 的标签库
- DisplayTag 是 Apache 下的一套开源标签库,用于生成页面并且显示
- 实现
 - jsp 引入标签库
 - 使用标签


``` xml

TLD (Tag Library Definition)
  标签库定义 *.tld 文件

使用自定义标签库
  标签库 uri
  标签名

  <%@ taglib uri="http://www.crazyit.org/mytaglib" prefix="mytag"%>

  <mytag:helloWorld />


使用官方的的标签库
  <%@ taglib uri="http://java.sun.com/jsp/jstl/core" prefix="mytag" %>

```


#### 7.2 Jsp 2 的特性

Page 147

- web.xml 文件必须使用 Servlet 2.4 以上版本的配置文件
- Servlet 3.1 对应的 Jsp 2.3 规范

#### 7.3 Jsp 2 web.xml 的规范写法

``` xml
<?xml version="1.0" encoding="UTF-8"?>
<web-app xmlns="http://xmlns.jcp.org/xml/ns/javaee"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:schemaLocation="http://xmlns.jcp.org/xml/ns/javaee
    http://xmlns.jcp.org/xml/ns/javaee/web-app_3_1.xsd"
    version="3.1">

    <jsp-config>
      <!-- 第一组配置 -->
  		<jsp-property-group>
  			<!-- 对哪些应用配置 -->
  			<url-pattern>/noscript/*</url-pattern>
  			<!-- 忽略表达式语言 -->
  			<el-ignored>true</el-ignored>

  			<!-- 替换每个 jsp 页面 contentType 字符集 -->
  			<page-encoding>UTF-8</page-encoding>
  			<!-- 不允许使用 Java 脚本 -->
  			<scripting-invalid>true</scripting-invalid>

  			<!-- 隐式导入页面头 -->
  			<include-prelude>/inc/top.jspf</include-prelude>
  			<!-- 隐式导入页面尾 -->
  			<include-coda>/inc/bottom.jspf</include-coda>
  		</jsp-property-group>

      <!-- 第二组配置 -->
  		<jsp-property-group>
  			<!-- 对哪些应用配置 -->
  			<url-pattern>*.jsp</url-pattern>
  			<el-ignored>false</el-ignored>
  			<page-encoding>UTF-8</page-encoding>
  			<scripting-invalid>false</scripting-invalid>
  		</jsp-property-group>

      <!-- 第三组配置 -->
  		<jsp-property-group>
  			<url-pattern>/inc/*</url-pattern>
  			<el-ignored>false</el-ignored>
  			<page-encoding>UTF-8</page-encoding>
  			<scripting-invalid>true</scripting-invalid>
  		</jsp-property-group>
  	</jsp-config>

</web-app>

```

#### 7.4 Jsp 2 表达式

Page 152

``` jsp

运算表达式
  ${1.2 + 2.3}
  ${10 % 4}
  ${10 mod 4}
  ${(1==2) ? 3 : 4}

比较表达式
  ${1 lt; 2} : 小于
  ${1 gt; (4/2)} : 大于
  ${100.0 eq 100} : 等于

内置对象
  取得请求参数值
    ${param.name}
    ${param["name"]}

  取得请求 host
    ${header.host} =  127.0.0.1:8888

  取得请求头的值
    ${header["accept"]} = text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8

  取得初始化参数值
    ${initParam["author"]}

  取得session的属性值
    ${sessionScope["user"]}

  取得指定Cookie的值
    ${cookie["name"].value}

  获取应用级别的参数
    ${applicationScope.xxx}
```


### 8、Filter

Page 131

- Servlet 的加强版
- 对请求进行预处理(比如修改 http 表头和数据)，也可以对 HttpServletResponse 进行后处理(在响应客户端之前处理，可以修改 Http 头和数据)，是典型的处理链
- Filter 对请求进行预处理 -> 将请求转给 Servlet 进行处理生成响应 -> 最后 Filter 在对服务器响应进行后处理
- Filter 可以拦截多个请求，然后转发给一个 Servlet
- 实现
  - 定义 Filter 实现类
  - 通过注解或者 web.xml 文件中配置 Filter

#### 8.1 web.xml 配置

``` xml

  <filter>
      <filter-name>log</filter-name>
      <filter-class>jason.LogFilter</filter-class>
  </filter>

```


#### 8.2 java 代码

``` java

package jason;

import javax.servlet.*;
import javax.servlet.http.*;
import javax.servlet.annotation.*;

import java.io.*;

//拦截所有请求
@WebFilter(filterName="log"
    ,urlPatterns={"/*"}
    ,initParams={
        @WebInitParam(name="encoding", value="UTF-8"),
    }
)
public class LogFilter implements Filter
{
    // FilterConfig 可用于访问 Filter 的配置信息
    private FilterConfig config;
    // 实现 init 方法
    public void init(FilterConfig config)
    {
        this.config = config;
    }
    // 实现销毁方法
    public void destroy()
    {
        this.config = null;
    }
    // 知心核心过滤方法
    public void doFilter(ServletRequest request,
        ServletResponse response, FilterChain chain)
        throws IOException,ServletException
    {
        // 获取 ServletContext 对象，用于记录日志
        ServletContext context = this.config.getServletContext();
        long before = System.currentTimeMillis();
        System.out.println("开始过滤...");


        String encoding = config.getInitParameter("encoding");
        System.out.println(encoding);

        // 将请求转换成 HttpServletRequest 对象
        HttpServletRequest hrequest = (HttpServletRequest) request;

        //输出提示信息
        System.out.println("Filter 截获用户请求地址:" +
            hrequest.getServletPath());

        //Filter 只是链式处理，请求依然放行到目的地址，进行处理
        //如果这里不转发的话，则不会交给 Servlet 处理，则显示为空
        chain.doFilter(request, response);

        //放行后，Servlet 响应后，执行的后续处理
        long after = System.currentTimeMillis();

        System.out.println("过滤结束");
        System.out.println("请求被定位到:" + hrequest.getRequestURI() +
            "   耗费时间为: " + (after - before));
    }
}

```


### 9、Listener ['lɪs(ə)nə]

Page 138

- 网站在 Web 容器中运行，有各种状态和动作，发生了这些状态会触发本类

  ```
   - (ServletContextListener 类) Web 启用、停止
   - 用户 Session 开始、结束
   - (ServletContextAttributeListener 类) 当程序把属性,放入、删除、替换，到 application 中 (Page 140)
   - (ServletRequestListener、ServletRequestAttributeListener 类) 监听用户请求 (Page 141)
   - (HttpSessionListener、HttpSessionAttributeListener 类) 监听用户 Session  (Page 142)
  ```
- 实现 Listener

 ```
  - 定义 Listener 实现类
  - 通过注解或者 web.xml 文件中配置 Listener
 ```


#### 9.1 web.xml 配置

``` xml

    <listener>
        <listener-class>jason.GetConnListener</listener-class>
    </listener>

```



#### 9.2 java 代码


``` java

package jason;

import java.sql.*;
import javax.servlet.*;
import javax.servlet.annotation.*;

@WebListener
public class GetConnListener implements ServletContextListener
{
   //应用启动时，该方法被调用
   public void contextInitialized(ServletContextEvent sce)
   {
       System.out.println("应用开启");
       try
       {
           //获取该应用的 ServletContext
           ServletContext application = sce.getServletContext();
           //获取配置内容
           String name = application.getInitParameter("servletContext");

           application.setAttribute("name" , name);
           this.contextDestroyed(sce);
       }
       catch (Exception ex)
       {
           System.out.println("Listener 出现异常"
               + ex.getMessage());
       }
   }


   //应用关闭时会调用本方法
   public void contextDestroyed(ServletContextEvent sce)
   {
       // 获取 ServletContext 实例
       ServletContext application = sce.getServletContext();
       System.out.println(application.getAttribute("name"));


       System.out.println("应用关闭");
   }
}

```


### 10、WebSocket 支持

Page 170
