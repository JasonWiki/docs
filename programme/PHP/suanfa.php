<?php 
/**	一、数据结构及排序
 * 数据结构：三种基本结构
 * 1.线性结构：数据与元素一对一的关系。用键和值存储
 * 2.树形结构：数据与元素一对多的关系	用保存父节点和保存子节点存储
 * 3.网状结构：数据与元素多对多的关系	用矩阵或者一张关系表存储
 * 
 *  算法：
 * 1.算法的执行效率：0(n)
 * 	2.算法评价原则：正确性、高效性、空间性、可读性
 */

/**
 * 1、二分算法
 * 算法思路：从每次的结果中，取当前结果的一半，去验证答案是否正确，直到推算出最终的结果。
 */

/**
 * 2、递推法
 * 算法思路：从已知的信息，推算出最终的结果。
 * 1).顺推法：从已知<条件>推算出结果。
 * 2).逆推法：从已知<结果>推算出条件。
 */

/**
 * 3、枚举法(穷举法)
 * 算法思路：没有办法确定答案是什么，只能从候选答案搜索正确的结果。
 * 枚举法条件：
 * 			1).确定候选答案的<数量>
 * 			2).确定候选答案的<值>
 */

/**
 * 4、递归法
 * 算法思路：算法自身调用自身的算法。
 */

/**
 * 5、分治法
 * 算法思路：当处理的问题很多，求解过程很复杂。分解成部分小问题，逐一算出结果。
 * 步骤：
 * 		1).分解：将问题分解成若干规模较小的问题。
 * 		2).求解：将小问题用简单的方法解出答案。
 * 		3).合并：按求解要求，将小问题的解，合并成需要的结果。
 */

/**
 * 6、贪婪法
 * 算法思路：从问题的某一个初解，逐步逼近目标的解，给出一个近似的解。
 * 特点：不求最优结果，快速得到满意结果。通过局部的最优，达到全局的最优。
 * 存在问题：
 * 			1).不能保证解是最优的。
 * 			2).不能用来求最大或最小解。
 * 			3).只能满足约束条件下给定值的范围。(如超市的找零，面值在一个范围内)
 */

/**
 * 7、试探法
 * 算法思路：预先设想下一步可能出现的问题，提前给出解决方法。
 */

/**
 * 8、模拟法
 * 算法思路：模拟自然界不可预测的情况。(如产生随机数，让用户去猜测)
 */



/**
 * 二、数据排序
 * 内部排序=>{
 * 		交换排序=>{
 * 			冒泡排序
 * 			快速排序
 * 		},
 * 		选择排序=>{
 * 			简单选择排序,
 * 			堆排序
 * 		},
 * 		插入排序=>{
 * 			直接插入排序,
 * 			希尔(shell)排序
 * 		},
 * 		合并排序
 * }				
 */
//测试数据


/**
 * 1、冒泡排序：从最底部开始交换位置，完成排序。
 * @var array $array 数组
 */
$arr1 = array(0 => 86,1 => 27,2 => 52,3 => 42,4 => 87,5 => 62,6 => 24,7=> 32);
function bubbleSort($array) {
	$length = count($array);
	
	if ($length == 0) return false;
	
	$state = 0;	//标志是否完成了排序
	
	for ($i=0;$i<$length;$i++) {				//遍历整个数组	
		
		for ($j=$length-1; $j>$i; $j--) {		//把最小的推到最顶层
			//此处改变大于小于，决定排序
			if ($array[$j] < $array[$j-1]){		
				$tmp = $array[$j];					//互换位置
				$array[$j] = $array[$j-1];
				$array[$j-1] = $tmp;	
				$state = 1;
			}			
		}
		
		//完成排序，跳出循环
		if ($state == 0) {
			break;
		} else {
			$state = 0;
		}	
	}	
	return $array;
}

function _bubbleSort($array,$field) {
	$length = count($array);

	if ($length == 0) return false;

	$state = 0;	//标志是否完成了排序

	for ($i=0;$i<$length;$i++) {				//遍历整个数组

		for ($j=$length-1; $j>$i; $j--) {		//把最小的推到最顶层
			//此处改变大于小于，决定排序
			if ($array[$j][$field] > $array[$j-1][$field]){
				$tmp = $array[$j];					//互换位置
				$array[$j] = $array[$j-1];
				$array[$j-1] = $tmp;
				$state = 1;
			}
		}

		//完成排序，跳出循环
		if ($state == 0) {
			break;
		} else {
			$state = 0;
		}

	}
	return $array;
}

/**
 * 2、快速排序：
 * 取数组中第一个作为比较值，遍历数组，把比比较值小的放在一个左数组中，反之放在右数组中。
 * 通过递归排序出需要的结果。
 */
$arr2 = array(0 => 86,1 => 27,2 => 52,3 => 42,4 => 87,5 => 62,6 => 24,7=> 32);
function quickSort($array){
	$count = count ($array);
     if ($count <= 1) return $array;
     
     $key = $array [0];
     
     $left_array = array ();
     $middle_array = array ();
     $right_array = array ();
       
     foreach ($array as $k => $val ) {
     	if ($key > $val) {
     		$left_array[] = $val;				
      	} else if ($key == $val) {
            $middle_array [] = $val;					 	//直接插入
     	} else {
            $right_array [] = $val;
       	}
     }
 
     //递归	这里是递归，注意。。
     $left_array = quickSort($left_array);
     $right_array = quickSort($right_array);
       
     //合并数组
     $array = array_merge ($left_array, $middle_array, $right_array);
     return $array;
}

function _quickSort($array,$field){
	$count = count ($array);
	if ($count <= 1) return $array;
	 
	$key = $array [0];
	 
	$left_array = array ();
	$middle_array = array ();
	$right_array = array ();
	 
	foreach ($array as $k => $val ) {
		//这里改变大于小于，改变数组的排序
		//如if ($key[$field] > $val[$field]) {
		if ($key[$field] > $val[$field]) {
			$left_array[] = $val;
		} else if ($key[$field] == $val[$field]) {
			$middle_array [] = $val;					 	//直接插入
		} else {
			$right_array [] = $val;
		}
	}

	//递归
	$left_array = quickSort($left_array,$field);
	$right_array = quickSort($right_array,$field);
	 
	//合并数组
	$array = array_merge ($left_array, $middle_array, $right_array);
	return $array;
}


/**
 * 3、简单选择排序：
 * 每一趟从待排序的数据元素中选出最小（或最大）的一个元素，顺序放在已排好序的数列的最后，直到全部待排序的数据元素排完。
 */
$arr3 = array(0 => 86,1 => 27,2 => 52,3 => 42,4 => 87,5 => 62,6 => 24,7=> 32);
function easySelectSort($array) {
	$length = count($array);
	for($i=0;$i<$length;$i++) {

		//每轮从第$j个元素开始,找出一个最小的元素,并和第$j个元素交换位置
		for($j=$i+1;$j<$length;$j++) {

			$temp=$array[$j];	
			//这里改变大于小于，改变数组的排序
			if($array[$i]>$array[$j]) {
				$array[$j]=$array[$i];
				 $array[$i]=$temp;
				$temp=$array[$j];
			}
		}
	}

	return $array;
}


/**
 *  4、堆排序
 *  1).将无序数据构成堆 (即用无序数据构成一个二叉树)
 *  2).利用堆排序(再把输出的数据，构建有序的数据)
 */
$arr4 = array(0 => 86,1 => 27,2 => 52,3 => 42,4 => 87,5 => 62,6 => 24,7=> 32);
function exchange(&$a,&$b){
        $temp = $b;
        $b = $a;
        $a = $temp;
}
function left($i){ return $i*2+1;}
function right($i){ return $i*2+2;}
function build_heap(&$array,$i,$heapsize){
        $left = left($i);
        $right = right($i);
        $max = $i;
        if($i < $heapsize && $left<$heapsize  && $array[$left] > $array[$i] ){
                $max = $left;
        }

        if($i < $heapsize && $right<$heapsize && $array[$right] > $array[$max]){
                $max = $right;
        }
        if($i != $max && $i < $heapsize && $max < $heapsize){

                exchange($array[$i],$array[$max]);
                build_heap($array,$max,$heapsize);

        }
}

function sortHeap(&$array,$heapsize){
        while($heapsize){

                exchange($array[0],$array[$heapsize-1]);
                $heapsize = $heapsize -1;
                build_heap($array,0,$heapsize);
        }
}

function createHeap(&$array,$heapsize){
        $i = ceil($heapsize/2)-1;
        for(;$i>=0;$i--){
                build_heap($array,$i,$heapsize);
        }
}

function main(){
        $array = array(88,99,22,11,22,13,9,2,1,100,12);
        $heapsize = count($array);
        createHeap($array,$heapsize);

        print_r($array);
        sortHeap($array,$heapsize);
        print_r($array);
}
//main();


/**
 * 5、直接插入排序法
 * 每次将一个待排序的数据元素，插入到前面已经排好序的数列中的适当位置，使数列依然有序；直到待排序数据元素全部插入完为止。
 */
$arr5 = array(0 => 86,1 => 27,2 => 52,3 => 42,4 => 87,5 => 62,6 => 24,7=> 32);
function insertMove(Array $arr, $start = null, $end = null) {
	if(!isset($start) || $start < 0) $start = 0;
	if(!isset($end) || $end >= count($arr)) $end = count($arr) - 2;    #最后只能选到倒数第二个元素
	for($i = $end; $i >= $start; $i--) {
		$arr[$i + 1] = $arr[$i];
	}
	return $arr;
}
#插入排序,使用同一个数组后移方法实现
function insertSort(Array $arr) {
	for($i = 1; $i < count($arr); $i++) {    #未排序数组,从第二个元素开始
		$insertEle = $arr[$i];    #待插入元素
		//整理选择排序
		for($j = 0; $j < $i; $j++) {    #已排序好数组,从第一个元素开始
			if($arr[$j] > $arr[$i]) {    #按升序排序
				$arr = insertMove($arr, $j, $i - 1);    #先将已排序好数组中大于待插入元素的元素全部后移一位
				$arr[$j] = $insertEle;    #插入待插入元素
				break;
			}
		}
	}
	return $arr;
}



/**
 * 6、希尔排序
 * 先取一个小于n的整数d1作为第一个增量，把文件的全部记录分成d1个组。所有距离为d1
	的倍数的记录放在同一个组中。先在各组内进行直接插入排序；然后，取第二个增量d2<d1重复
	上述的分组和排序，直至所取的增量dt=1(dt<dt-l<…<d2<d1)，即所有记录放在同一组中进行
	直接插入排序为止。
	实现：与增量间隔的数比较，直到把大的数放到最后
 */
$arr6 = array(0 => 86,1 => 27,2 => 52,3 => 42,4 => 87,5 => 62,6 => 24,7=> 32);
function shellSort($array)
{
	if(!is_array($array))
	{
		return false;
	}

	$len=count($array);

	$d=$len;//随机增量，初始值为数组长度，以不断除2取值

	while($d >1)
	{
		$d=intval($d / 2);//分组间隔，2为n值，n值减少时，移动的趟数和数据增多

		$temp=NULL;

		$j=0;

		for($i=$d;$i < $len;$i+=$d)
		{
			if($array[$i] < $array[$i-$d])
			{
				$temp=$array[$i];

				$j=$i-$d;

				while(($j >=0) && $temp < $array[$j])
				{
					$array[$j+$d]=$array[$j];
						
					$j = $j - $d;
				}

				$array[$j+$d]=$temp;
			}
		}
	}
	return $array;
}


/**
 * 7、合并排序
 */
$arr7 = array(0 => 86,1 => 27,2 => 52,3 => 42,4 => 87,5 => 62,6 => 24,7=> 32);
#归并排序
#@param    $arr    待排序数组
#@param    $from    排序的起始坐标
#@param    $end    排序的结束坐标
function mergeSort(&$arr, $from, $end) {
	#切分数组直到数组元素只剩下一个
	if($from < $end) {
		$mid = floor(($from + $end) / 2);
		mergeSort($arr, $from, $mid);
		mergeSort($arr, $mid +1, $end);
		 
		#合并数组
		$tempArr = array();
		$leftInx = $from;
		$rightInx = $mid + 1;
		 
		#合并左右两部,直到左边或右边部分全部排入临时数组
		while($leftInx <= $mid && $rightInx <= $end) {
			if($arr[$leftInx] < $arr[$rightInx]) {
				$tempArr[] = $arr[$leftInx++];
			} else {
				$tempArr[] = $arr[$rightInx++];
			}
		}
	 
		#处理没有排完的一部分的剩下元素,因为待合并的部分是有序数组,剩下的元素直接全部加入临时数组
		while($leftInx <= $mid) {
			$tempArr[] = $arr[$leftInx++];
		}
		 
		while($rightInx <= $end) {
			$tempArr[] = $arr[$rightInx++];
		}
		 
		#用临时数组的值替换掉原数组的值
		array_splice($arr, $from, $end - $from + 1, $tempArr);
		}
	}
mergeSort($arr7,0, count($arr7) - 1);
print_r($arr7);



?>