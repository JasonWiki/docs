# lua 脚本

- 模拟 post1.lua

``` sh
wrk.method = "POST"
wrk.headers["Content-Type"] = "application/json"
wrk.headers["Authorization"] = "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJwYXlsb2FkIjoie1wiY2xpZW50SWRcIjpcIjExMTFcIixcImNsaWVudFNlY3JldFwiOlwiMTExMVwiLFwiY3JlYXRlZEF0XCI6XCIyMDE5LTA2LTIyVDEyOjU1OjExXCIsXCJpZFwiOjEsXCJpc0FjdGl2ZVwiOjEsXCJ1cGRhdGVBdFwiOlwiMjAxOS0wNi0yMlQxMjo1NToxMVwifSIsImlzcyI6ImJ1bWJsZWJlZSIsImV4cCI6MTU2MTgwNzU3MTY5NH0.EY00jN62WPdviTh1QQ_HBMK6GJdejiWqNpxYwk0k4ViiC6OGG8JoJSx9doDVRGRbS-CbaDJsFLbmgrJOC7hxgIBhzg5UnGdVnuhHBsFeDupzWAFSVBv8aw_MY2tpEO1cgWXBLWtG7bd1E9DKYp6zTX6Mv0CaL5al9r3mQgBMFqY"

wrk.body  = '{"experimentId":"304421714917752832", "platformId":"1","projectId":"2","identityId":"867252030155914"}'

function request() 
  return wrk.format('POST', nil, nil, body)
end

./wrk -t32 -c1000 -d30s --latency  -s ./post1.lua  http://test.bumblebee.2345.com/experiment/experiment/action

./wrk -t32 -c1000 -d30s --latency   http://test.bumblebee.2345.com/bigbrother/info
```

- 模拟 post2.lua

``` sh
wrk.method  = "POST"
wrk.headers["Content-Type"] = "application/json"
wrk.body = "{\"experimentId\":\"304421714917752832\",\"platformId\":\"1\",\"projectId\":\"2\",\"identityId\":\"867252030155914\"}"

./wrk -t32 -c1000 -d30s --latency -s ./post2.lua  http://172.17.0.112:33017/experiment/action
```
