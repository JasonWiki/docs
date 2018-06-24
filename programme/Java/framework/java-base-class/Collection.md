# 集合

## 1、介绍

Java 数组几个特点
- 连续的
- 大小固定
- 数据类型完全一致 (如果是小的数据类型，可自动转换为大的数据类型)
- 数组是储存在堆上的对象，可以保存多个同类型变量

## 2、案例

### 2.1、定义数组

``` java

public class Test2 {

    public static void main(String[] args) {

        //静态初始化
        int[] arr = new int[10];
        arr[0] = 100;
        arr[1] = 200;
        arr[2] = '啊'; //小的类型，自动转换为大的

        int arr_length = arr.length;    //数组长度，10
        int arr_key3 = arr[2];
        System.out.println(arr_key3);


        //静态初始化
        int[] arr_two = {1,2,3,4,5,6,7};
        System.out.println(arr_two.length);

    }

}

```

### 2.2、 MAP

``` java
import java.util.Map;
import java.util.HashMap;

public class Test2 {

    public static void main(String[] args) {

        //1、定义 MAP
        Map<String, String> map_1 = new HashMap<String, String>();
        map_1.put("a", "第四条新闻");
        map_1.put("b", "第五条新闻");
        map_1.get("a");
        //循环
        for(Map.Entry<String, String> entry :map_1.entrySet()) {
            System.out.println(entry.getKey());
            System.out.println(entry.getValue());
        }

        // 初始化
        Map<String, String> map = new HashMap<String, String>() {
            {
                put("Name", "June");  
                put("QQ", "4889983");  
            }
        };


        //2、定义指定类型的，泛型 MAP
        Map<Integer,String> map_2 = new HashMap<Integer,String>();
        map_2.put(1,"a");
        map_2.put(2,"b");


        //3、定义指定的自定义对象 Map
        Map<Integer,NewsEntity> map_2 = new HashMap<Integer,NewsEntity>();
        map_2.put(1,new NewsEntity(1,"标题1"));
        map_2.put(2,new NewsEntity(2,"标题2"));
        map_2.get(1).getNewsname();
        //循环
        for(Map.Entry<Integer,NewsEntity> entry : map_2.entrySet()) {
          System.out.println(entry.getKey());
          System.out.println(entry.getValue().getNewsname());//打印出对象中的值
        }


        // 自定义 Object 的 Map
        Map<String,Object> map_3 = new HashMap<String, Object>();
        for(Map.Entry<String, Object> entry : map_3.entrySet()) {
            resultMap.put(entry.getKey(), entry.getValue());
        }


        //4、LinkedHashMap
        Map<Integer,String> map_list = new LinkedHashMap<Integer,String>();
        map_list.put(1, "星期一");
        map_list.put(2, "星期二");
        map_list.put(3, "星期三");
        map_list.put(4, "星期四");
        map_list.put(5, "星期五");
        map_list.put(6, "星期六");
        map_list.put(7, "星期日");
        //循环 LinkedHashMap
        for(Map.Entry<Integer, String> entry: map_list.entrySet()) {
            System.out.print(entry.getKey() + ":" + entry.getValue() + "\t");
        }


        // 5. 循环嵌套
        Map<String, Map<String,String>> mapDemo = new HashMap<String, Map<String,String>>();

        // 方法 1
        Iterator<Map.Entry<String, Map<String, String>>> mapDemoEntries = mapDemo.entrySet().iterator();
        while (actionNeedsEntries.hasNext()) {  
            Map.Entry<String, Map<String, String>> entry = mapDemo.next();  
            System.out.println("Key = " + entry.getKey() + ", Value = " + entry.getValue());  
        }

        // 方法 2
        for(Map.Entry<String, Map<String, String>> curMap : mapDemo.entrySet()) {
           String key = curMap.getKey();
           Map<String, String> curMapInfo = curMap.getValue();

           System.out.println("Key = " + key + ", Value = " + curMapInfo);  
       }



    }

}

```


### 2.3 、 List

``` java
import java.util.Map;
import java.util.HashMap;
import java.util.List;
import java.util.ArrayList;
import java.util.Arrays;


public class Test3 {

    public static void main(String[] args) {

        //定义 List
        List list_1 = new ArrayList();
        list_1.add(1);
        list_1.add("a");
        list_1.get(0);

        //定义明确类型的泛型 List
        List<String> list_2 = new ArrayList<String>();
        list_2.add("a");
        list_2.add("b");
        list_2.get(0);

        //定义自定义对象的 List
        List<NewsEntity> list_3 = new ArrayList<NewsEntity>();
        list_3.add(new NewsEntity(1,"标题1"));
        list_3.add(new NewsEntity(2,"标题2"));
        list_3.get(0).getNewsname();//获取对应下标的对象方法


        // list 放入 map
        List<Map<String, String>> listResult = new ArrayList<Map<String, String>>();
        Map<String, String> mapRowData = new HashMap<String, String>();

        // 初始化 list 的值
        List<String> listFields = Arrays.asList("id","name");

        // 遍历 List
        for (int i =0; i <= listResult.size()-1; i ++) {
          String col = rs.get(i);
          System.out.println(col);
        }

        // 第二种方式
        for( Map<String, String> curMap :listResult){

            // 当前推荐出来的房源 ID
            String value = curMap.get("key");
        }



        // Ids
        List ids = new ArrayList<String>();
        ids.add("1");
        ids.add("2");
        ids.add("3");

        // 组合成字符串 commons-lang.jar   org.apache.commons.lang.StringUtils
        StringUtils.join(ids,",");

        // 字符串转 list
        String ids = "2;3";
        String[] idsArr =new String[]{};
        idsArr = str.split(",");
        List list = java.util.Arrays.asList(idsArr);

        // 简写
        List<String> strTolist =  Arrays.asList("2;3".split(";"));

        // list 转 array
        String[] listToArr = (String[]) list.toArray(new String[list.size]);

    }

}


```


### 2.3 自定义对象数组

``` java


public class Test4 {

  public static void main (String[] args) {

        MyClass[] myClass = new MyClass[10];

        myClass[0] = new MyClass();
        myClass[0].setName("abc");

        System.out.println(myClass[0].getName());
    }
}

```


### 2.4  排序

``` java

import java.util.Collections;
import java.util.Comparator;
import java.util.Map.Entry;

// 一、对 Map<String, Map<String,String>> 进行排序

    Map<String, Map<String,String>> map = new HashMap<String, Map<String,String>>();

    // 转换成 list
    List<Map.Entry<String, Map<String, String>>> sortMapList = new ArrayList<Map.Entry<String, Map<String, String>>>(map.entrySet());

    // 排序
    Collections.sort(sortMapList,new Comparator<Map.Entry<String, Map<String, String>>> (){
        @Override
        public int compare(Entry<String, Map<String, String>> o1, Entry<String, Map<String, String>> o2) {
          // 升序
          return Integer.parseInt(o2.getValue().get("key")) - Integer.parseInt(o1.getValue().get("key"));

          // 降序
          return Integer.parseInt(o2.getValue().get("key")) - Integer.parseInt(o1.getValue().get("key"));
        }
    });


// 二、对 List<Map<String,String>> 排序

    List<Map<String, String>> listMap = new ArrayList<Map<String, String>>();

    // 排序
    Collections.sort(listMap,new Comparator<Map<String, String>> (){
        @Override
        public int compare(Map<String, String> o1, Map<String, String> o2) {

          // 升序
          return Integer.parseInt(o1.get("xxx")) - Integer.parseInt(o2.get("xxx"));
          // 降序
          return Integer.parseInt(o2.get("xxx")) - Integer.parseInt(o1.get("xxx"));
        }
    });

```
