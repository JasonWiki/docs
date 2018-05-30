# python 操作 hive

- [官方文档](https://cwiki.apache.org/confluence/display/Hive/Setting+Up+HiveServer2#SettingUpHiveServer2-PythonClientDriver)

## 方法一

- 提前安装 pip 工具

``` sh
# On a Debian-based distro:
  sudo apt-get install sasl2-bin libsasl2-2 libsasl2-dev libsasl2-modules

  sudo pip install pyhs2

# On an RHEL-based distro:
  sudo yum install cyrus-sasl-plain  cyrus-sasl-devel  cyrus-sasl-gssapi
  sudo yum install python-devel gcc-c++

  sudo pip install pyhs2
```

> 案例

``` python

#coding=utf-8

import pyhs2

class HiveModel:

    def __init__(self):

       self.hiveServer2("show tables")

    def hiveServer2(self,sql):
        with pyhs2.connect(host='uhadoop-ociicy-master2',
                   port=10000,
                   authMechanism="PLAIN",
                   user='dwadmin',
                   password='dwadmin',
                   database='default') as conn:
            with conn.cursor() as cur:
                #Show databases
                print cur.getDatabases()

                #Execute query
                cur.execute("select * from inventory_all")

                #Return column info from query
                print cur.getSchema()

                #Fetch table results
                for i in cur.fetch():
                    print i


```

## 方法二 (配置繁琐)

- 通过 git 方式 [git 链接](https://github.com/BradRuderman/pyhs2)
