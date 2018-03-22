<?php 
include 'Page.php';
include 'config.php';
include 'mysql.php';

$conn = connect(DB_HOST,DB_USER,DB_PWD,DB_CHARSET,DB_NAME);
$sql = "select count(*) as c from weixi";
//var_dump($sql);
 $result = mysqli_query($conn,$sql);
// //总条数
 $count = mysqli_fetch_assoc($result);

 $page = new Page(2, $count['c']);

// file_put_contents('test.php',$page);
//$sql = "select * from user";
$sql = "select * from weixi limit ".$page->limit();


$qry = mysqli_query($conn , $sql);
// var_dump($qry);
$a = [];
while($rst = mysqli_fetch_assoc($qry)){
    $a[] = $rst;
}
// var_dump($info);
$info['shuju'] = $a;
$info['xi']=$page->allpage() ;
// var_dump($info);
// 通过json格式给客户端提供数据
if (!empty($info)) {
	echo json_encode($info);
}