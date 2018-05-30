# SSH 详解

## 一、Struts

### 1. 注解

``` java

@Namespace: 定义命名空间
    @Namespace("/broker")


@ParentPackage
    @ParentPackage("json-default")  //继承 struts2 json 包


@Result : 设置返回资源
    // 全局设置返回资源
    @Result(
    // 返回资源类型为 json
    type = "json",
    // 设置返回资源的数据变量
    params = { "root", "result" }
    )


@Action: 定义访问地址和返回资源类型
  @Action(value="list", results={@Result(location="list.jsp")})
  @Action("broker-user-mate-inventory") // 定义访问地址


@ResultPath
```


## 二、Spring


### 1. 注解

``` java
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Controller;
import org.springframework.stereotype.Service;
import org.springframework.stereotype.Repository;
import org.springframework.stereotype.Component;
import org.springframework.transaction.annotation.Transactional; // 事物注解

@Controller 默认产生的Bean的name就是类（UserAction）的第一个字母小写（userAction）。
    @Controller("uu"),这样就<action name="user_*" class="uu" method="{1}">
    @Controller 用于标注控制层组件（如struts中的action）,


@Repository 用于标注数据访问组件，即DAO组件，


@Service 用于标注业务层组件，


@Component 泛指组件，当组件不好归类的时候，我们可以使用这个注解进行标注。


@Autowired 它对类成员变量、方法及构造函数进行标注，完成自动装配的工作。
    通过 @Autowired 的使用来消除 set ，get方法。


@Qualifier 的标注对象是成员变量、方法入参、构造函数入参。对于 @Autowired 注解的找不到 bean 做处理
    例如配合使用
    @Autowired
    @Qualifier("commonProperties")

    单独使用
    @Qualifier("commonProperties")


@Transactional spring 与 hibernate 整合时, 用到的事物注解


@SuppressWarnings
    @SuppressWarnings("unused")
    J2SE 提供的最后一个批注是 @SuppressWarnings。该批注的作用是给编译器一条指令，告诉它对被批注的代码元素内部的某些警告保持静默
```


## 三、Hibernate


### 1. 注解

``` java
import javax.persistence.Column;
// 注解将一个类声明为一个实体bean(即一个持久化POJO类)
import javax.persistence.Entity;
// 注解定义主键标识符的生成策略
import javax.persistence.GeneratedValue;
import javax.persistence.GenerationType;
import javax.persistence.Id;
import javax.persistence.Table;



@Transient 表示该属性并非一个到数据库表的字段的映射,ORM框架将忽略该属性


@Id 注解主键


@GeneratedValue 字段生成规则
    @GeneratedValue(strategy = GenerationType.AUTO)


@Column 注解字段
    @Column(name = "city_id", length = 9)
```
