# lua 脚本

- 模拟 post1.lua

``` sh
wrk.method = "POST"
wrk.headers["Content-Type"] = "application/json"
wrk.headers["Authorization"] = ""

wrk.body  = '{"a":"1","b":"2","c":"3","d":"4"}'

function request() 
  return wrk.format('POST', nil, nil, body)
end

./wrk -t32 -c1000 -d30s --latency  -s ./post1.lua  http://hostname/xxx

./wrk -t32 -c1000 -d30s --latency   http://hostname/xxx
```


- 模拟 post2.lua

``` sh
wrk.method  = "POST"
wrk.headers["Content-Type"] = "application/json"

wrk.body = "{\"a\":\"1\",\"b\":\"2\",\"c\":\"3\",\"d\":\"4\"}"

./wrk -t32 -c1000 -d30s --latency -s ./post2.lua  http://hostname/xxx
```
