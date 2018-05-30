# 读取配置文件

## Properties
```
Properties props = new Properties();
FileInputStream file = new FileInputStream("XXX.Properties")
props.load(file);

file.close();
props.get();
```
