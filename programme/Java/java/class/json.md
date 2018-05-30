# Json 处理

## 一、

``` java

    /**
     * json 字符 -> map
     * @param jsonString jsonArr = "[{\"pcsafe\":[{\"az500\":\"1\"},{\"az03\":\"1\"},{\"az04\":\"S\"},{\"az05\":\"100115\"}]},{\"a\":\"1\"},\"b\"]";
     * @return  Map<String,Object>
     */
    private Map<String,Object> jsonStrToMap(String jsonString) {

        // 保存处理好的 Json 格式
        Map<String,Object> resultMap = new HashMap<String, Object>();

        try {
            // 待处理的 Json 格式
            List<Object> listMap = objectMapper.readValue(jsonString, List.class);

            Map<String,Object> formatMap;
            for (int i = 0; i <= listMap.size() - 1; i ++) {
                Object valMap = listMap.get(i);
                try {
                    formatMap = (Map<String, Object>) valMap;
                    // 把 list map 中嵌套数据, 放到 map 中
                    for(Map.Entry<String, Object> entry :formatMap.entrySet()) {
                        resultMap.put(entry.getKey(), entry.getValue());
                    }
                } catch (ClassCastException e)  {
                    System.out.println("Exception: " + valMap + " : " + e.getMessage());
                }
                formatMap = null;
            }

        } catch(Exception e) {
            System.out.println("Exception: " + jsonString + " : " + e.toString());
        }

        return resultMap;
    }


    /**
     * map -> json 字符
     * @param map  Map<String, Object>
     * @return String
     */
    public String mapToJsonStr(Map<String,Object> map) {
        String jsonStr = null;

        try {
            jsonStr = objectMapper.writeValueAsString(map);
        } catch(Exception e) {
            System.out.println("Exception: " + map + " : " + e.toString());
        }

        return jsonStr;
    }




```
