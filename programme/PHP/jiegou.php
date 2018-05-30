<?php
/**
 * 介绍：数据结构：三种基本结构
 * 1.线性结构：数据与元素一对一的关系。用键和值存储
 * 2.树形结构：数据与元素一对多的关系	用保存父节点和保存子节点存储
 * 3.网状结构：数据与元素多对多的关系	用矩阵或者一张关系表存储
 */



/**
 * 数组任意部位插入新的值，保持排序
 * @param data  		$data		插入的数据
 * @param num	 	$num		插入的位置
 * @param array 		 $array		要操作的数组
 * @return array
 */
function InsertValArray($data,$num,$array) {
	for ($i=count($array);$i>$num;$i--) {
		$array[$i] = $array[$i-1];	//把数组的值向后移动
	}
	$array[$num] =  $data;			//在指定位置插入数据
	return $array;
}

/**
 * 删除数组中任意值，保持排序
 * @param num $num		删除的位置
 * @param array $array	操作的数组
 * @return array				
 */
function delValArray($num,$array) {
	$max = count($array);
	if ($num<0 || $num>=$max) {
		return false;
	}
	for ($i=$num;$i<$max;$i++) {
		$array[$i] = $array[$i+1];		//把后一个指针，向前推一个
	}
	unset($array[$max-1]);
	return $array;
}





?>