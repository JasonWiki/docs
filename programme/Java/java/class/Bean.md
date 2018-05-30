# Bean

``` java

ApplicationContext ac = new ClassPathXmlApplicationContext("dwloggerforhive.xml");

//获取             
DWLogger logger = (DWLogger)ac.getBean("loggerHive");
```
