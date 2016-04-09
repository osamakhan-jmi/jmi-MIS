<?php

session_start();
if($_SESSION["type"]!="admin"||!$_SESSION)
	header("Location:index.php");
else{
include("connection-sql.php");
if(!file_exists("backups"))
	mkdir("backups");
$t = time();
$t = date("Y-m-d",$t);
//select * into outfile 'C:/xampp/htdocs/jamia_primary/backups/osama.sql' from student10
$dir = getcwd();
for($i=0;$i<strlen($dir);$i++){
	if($dir[$i]=="\\")
		$dir[$i] = "/";
}

$table10 = $dir."/backups/class10-".$t.".sql";
$table12 = $dir."/backups/class12-".$t.".sql";

mysqli_query($conn,"select * into outfile '$table10' from class10");
mysqli_query($conn,"select * into outfile '$table12' from class12");

$q1 = "TRUNCATE table class10";
$q2 = "TRUNCATE table class12";
$q3 = "TRUNCATE table log";
mysqli_query($conn,$q1);
mysqli_query($conn,$q2);
mysqli_query($conn,$q3);
echo 'Now You Can Start New Session.....';
}
?>
