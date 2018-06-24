
IOC
  控制反转, 反向控制 (Inversion of Control)
  依赖注入 (dependency injection)

AOP
  对象增强, 面向切片编程 (Aspect-Oriented Programming)

  AOP(面向切片编程) 基于IoC, AOP(面向切片编程) 是对 OOP(面向对象编程) 的补充
  OOP(面向对象编程) 将程序分解成各个层次的对象，AOP(面向切片编程) 将程序<运行过程>分解成各个切面。
  AOP 从程序运行角度考虑程序的结构，提取业务处理过程的切面，OOP 是静态的抽象，AOP 是动态的抽象


  AOP 实现技术，主要分为两大类：
    动态代理技术，利用截取消息的方式，对该消息进行装饰，以取代原有对象行为的执行

    采用静态织入的方式，引入特定的语法创建“方面”，从而使得编译器可以在编译期间织入有关“方面”的代码。

  Spring 实现 AOP 原理:
    JDK 动态代理: 其代理对象必须是某个接口的实现，它是通过在运行期间创建一个接口的实现类来完成对目标对象的代理；其核心的两个类是InvocationHandler和Proxy

    CGLIB 代理: 实现原理类似于JDK动态代理，只是它在运行期间生成的代理对象是针对目标类扩展的子类。CGLIB是高效的代码生成包，底层是依靠ASM（开源的java字节码编辑类库）操作字节码实现的，性能比JDK强；需要引入包asm.jar和cglib.jar。使用AspectJ注入式切面和@AspectJ注解驱动的切面实际上底层也是通过动态代理实现的


  AOP 使用场景
    AOP 利用一种称为“横切”的技术，剖解开封装的对象内部，并将那些影响了 多个类的公共行为封装到一个可重用模块，并将其名为“Aspect”，即方面。所谓“方面”，简单地说，就是将那些与业务无关，却为业务模块所共同调用的 逻辑或责任封装起来，比如日志记录，便于减少系统的重复代码，降低模块间的耦合度，并有利于未来的可操作性和可维护性

    使用场景:
      Authentication 权限检查        
      Caching 缓存        
      Context passing 内容传递        
      Error handling 错误处理        
      Lazy loading　延迟加载        
      Debugging　　调试      
      logging, tracing, profiling and monitoring　日志记录，跟踪，优化，校准        
      Performance optimization　性能优化，效率检查        
      Persistence　　持久化        
      Resource pooling　资源池        
      Synchronization　同步        
      Transactions 事务管理    
      另外 Filter 的实现和 struts2 的拦截器的实现都是 AOP 思想的体现。  
