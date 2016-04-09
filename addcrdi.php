<?php
include("connection-sql.php");
$class = $_GET["class"];
$roll = $_GET["roll"];
$code = $_GET["code"];
//$type= $_GET["type"];

if($class==12){
	$q= "update class12 set ue=0,sauth='YES' where roll='$roll' and subject_code='$code'";
	mysqli_query($conn,$q);
	$q = "update class12 set cat='CR-DI' where roll='$roll'";
	mysqli_query($conn,$q);
	$q = "update student12 set cat='CR-DI' where roll='$roll'";
	mysqli_query($conn,$q);
}
else if($class==10){
	$q= "update class10 set ue=0,ia=0,uep=0,iap=0,sauth='YES' where roll='$roll' and subject_code='$code'";
	mysqli_query($conn,$q);
	$q = "update student10 set cat='CR-DI' where roll='$roll'";
	mysqli_query($conn,$q);
	$q = "update class10 set cat='CR-DI' where roll='$roll'";
	mysqli_query($conn,$q);
}
echo "Confirmed CR-DI Entry!!";
?>