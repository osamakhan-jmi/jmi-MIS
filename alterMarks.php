<?php
include("connection-sql.php");
if($_REQUEST["code"]&&$_REQUEST["roll"]&&$_REQUEST["type"]){
	$code = $_REQUEST["code"];
	$roll = $_REQUEST["roll"];
	$type = $_REQUEST["type"];
	
	if($type=="fa")
		$type="uep";
	else if($type=="sa1")
		$type = "ue";
	else if($type=="sa2")
		$type = "ia";
	else {echo "wrong entries";exit;}
		
	$q1 = "select roll from class10 where roll='$roll' and subject_code='$code'";
	$r1 = mysqli_query($conn,$q1);
	
	if(mysqli_num_rows($r1)==0){
		$q1 = "select roll from class12 where roll='$roll'  and subject_code='$code'";
		$r1 = mysqli_query($conn,$q1);
		if(mysqli_num_rows($r1)==0)	{echo "wrong entries!!!";exit;}
		$q2= "call alter_class12('$roll','$code','$type')";
		if(mysqli_query($conn,$q2)){
			echo "Now $code Marks Can be Changed";}
		else
			echo "code or roll no is wrong!!";	
	}
	else{
		$q2= "call alter_class10('$roll','$code','$type')";
		if(mysqli_query($conn,$q2))
			echo "Now $code Marks Can be Changed";
		else
			echo "code or roll no is wrong!!";
	}
}
?>