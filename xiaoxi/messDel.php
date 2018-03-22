<?php
$conn = mysqli_connect('localhost' , 'root' , 'root');
mysqli_select_db($conn , 'weixi');
mysqli_set_charset($conn , 'utf8');

// echo $_GET['id'];
// die;
$id = $_GET['id'];
$sql = "delete from weixi where id=$id";

$result = mysqli_query($conn , $sql);

if ($result && mysqli_affected_rows($conn)) {
	echo '1';
} else {
	echo '0';
	// header('location:index.php');
}