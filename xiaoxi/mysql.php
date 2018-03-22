<?php
/*
*
*/

function connect($host , $user , $pwd , $charset , $name)
{
	$link = mysqli_connect($host , $user , $pwd);
	
	if (!$link) {
		//exit('数据库链接失败');
		return false;
	}
	
	mysqli_set_charset($link , $charset);
	
	if (!mysqli_select_db($link , $name)) {
		return false;
	}
	
	return $link;
}


function insert($link , $table , $data)
{
	//insert into $table(字段1，字段2 。。。) values(值1，值2，，，，，)
	//数据是从post提交过来的
	//var_dump($data);
	$keys = array_keys($data); //就是数据库的字段
	
	//var_dump($keys);  //username , password , sex
	$fields = join(',' , $keys);
	
	//var_dump($fields);
	$value = array_values($data); //获取就是要插入的数据的值
	
	$values = join(',' , parseValue($value));
	
	$sql = "insert into $table($fields) values($values)";
	//var_dump($sql);
	
	$result = mysqli_query($link , $sql);
	
	if ($result && mysqli_affected_rows($link)) {
		return mysqli_insert_id($link);
	} else {
		return false;
	}
	
}


//处理值为字符串的情况 用引号引起来
function parseValue($data)
{
	if (is_string($data)) {
		$data = '\''.$data.'\'';
	} else if (is_array($data)) {
		$data = array_map('parseValue' ,$data);
	} else if (is_null($data)) {
		$data = null;
	}
	return $data;
}


function update($link , $table , $data , $where)
{
	//update $table set usename='？？' , password=??? , sex =??? where id = $where;
	
	$set = join(',' , parseSet($data));
	
	//var_dump($set);
	
	$sql = "update $table set $set where $where";
	//echo $sql;
	$result = mysqli_query($link , $sql);
	
	return $result;
	
	
}
//处理修改的时候的set问题
function parseSet($data)
{
	foreach ($data as $key => $value) {
		$value = parseValue($value);
		
		if (is_scalar($value)) {
			$set[] = $key . '=' . $value;
		}
	}
	
	return $set;
}

//update($link , 'bbs_user' , $data , $where);

function del($link , $table , $where)
{
	//delte from $table where $wehre;
	
	$sql = "delete from $table where $where ";
	
	$result = mysqli_query($link , $sql);
	
	if ($result && mysqli_affected_rows($link)) {
		//return mysqli_insert_id($link);
		return mysqli_affected_rows($link);
	} else {
		return false;
	}
}
//del($link , 'bbs_user' , 'id=69');

function select($link , $table , $where , $fields = '*')
{
	$sql = "select $fields from $table where $where";
	
	$result = mysqli_query($link , $sql);
	
	if ($result && mysqli_affected_rows($link)) {
		
		while ($rows = mysqli_fetch_assoc($result)) {
			$data[] = $rows;
		}
		return $data;
	} else {
		return false;
	}
	
}

//$data = select($link , 'bbs_user' , 'id>40');

//var_dump($data);

function sum($link , $table , $fields = 'id')
{
	$sql = "select sum($fields) as sum from $table";
	
	$result = mysqli_query($link , $sql);
	
	$sum = mysqli_fetch_assoc($result);
	
	return $sum['sum'];
}


//echo sum($link , 'bbs_user');
function MyMax($link , $table , $fields = 'id')
{
	$sql = "select max($fields) as max from $table";
	
	$result = mysqli_query($link , $sql);
	
	$max = mysqli_fetch_assoc($result);
	
	return $max['max'];
}
//echo MyMax($link , 'bbs_user');

function MyCount($link , $table , $fields = 'id')
{
	$sql = "select count($fields) as count from $table";
	
	$result = mysqli_query($link , $sql);
	
	$count = mysqli_fetch_assoc($result);
	
	return $count['count'];
}

//echo MyCount($link , 'bbs_user');

function MyAvg($link , $table , $fields = 'id')
{
	$sql = "select avg($fields) as avg from $table";
	
	$result = mysqli_query($link , $sql);
	
	$avg = mysqli_fetch_assoc($result);
	
	return $avg['avg'];
}
//求最小值
function MyMin($link,$table,$fields = 'id'){
	$sql = "select min($fields) as min from $table";
	$result = mysqli_query($link,$sql);
	$min = mysqli_fetch_assoc($result);
	
	return $min['min'];
}

//封装一个分组的方法

function group($link,$table,$fields = "id",$group = "province"){
	$sql = "select count($fields) as a from $table group by $group";
	var_dump($sql);
	$result = mysqli_query($link,$sql);
	if($result && mysqli_affected_rows($link)){
		while($arr = mysqli_fetch_assoc($result)){
				$arrsy[] = $arr['a'];
		}
		return $arrsy;
	}else{
		return false;
	}
	
}


/*
*封装一个分页的方法
*
*@prame $table  表名
*@prame $page 页数
*@prame $num 个数
**/
 function fenye($link,$table,$page,$num){
	$sql = 'select count(*) as c from bbs_user';
	//var_dump($sql);
	//执行sql 语句
	$result = mysqli_query($link,$sql);
	//var_dump($result);
	//获得mysql中数据的中条数
	$arrs = mysqli_fetch_assoc($result);
	//求出总个数来
	$pageNumber = ceil($arrs['c']/$num);
	//求出偏移量来
	$offset = ($page-1)*$num;
	$prev = $page;
	if($prev>=1){
		$prev = $prev-1;
		
		if($prev<1){
			$prev=1;
		}
	}
	$arr['prev'] = $prev;
	
	$next = $page;
	if($next>=$pageNumber){
		$next = $pageNumber;
	}else{
		$next = $next+1;
	}
	$arr['next'] = $next;
	$arr['pageNumber'] = $pageNumber;
	$arr['offset'] = $offset;
	
	return $arr;
}
 

//验证信息填写是否有误  
	function check()  
	{  
	   if(form.username.value.length<6 || form.username.value.length>16)  
	   {  
	   alert('用户名不合法！请输入6-16位用户名');  
	   form.username.focus();  
	   return false;  
	   }  
	   if(form.pass.value.length<6 ||form.pass.value.length>16)  
	   {  
	   alert('密码不合法！请输入6-16位密码');  
	   form.username.focus();  
	   return false;  
	   }  
	   if(form.pass.value != form.pass2.value)//判断两次输入的密码是否一致  
	   {  
		alert("两次输入的密码不一致！");  
		form.pass.focus();  
		return false;  
	   }  
	}  




















