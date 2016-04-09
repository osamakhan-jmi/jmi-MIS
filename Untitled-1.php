<?php
include("connection-sql.php");

set_time_limit ( 0 );
$q = "select * from datax";
$r = mysqli_query($conn,$q);
while($row=mysqli_fetch_assoc($r)){
	
	$sid = $row["studentid"];
	$roll = $row["roll"];
	$enroll = $row["enroll"];
	$sname = $row["sname"];
	$fname = $row["fname"];
	$dob = $row["dob"];
	$gender = $row["gender"];
	$add = $row["address1"];
	$cat = $row["cat"];
	if($cat==NULL)
		$cat = "REGULAR";
	else if($cat==3)
		$cat = "CR-DI";
	else
		$cat = "EX";
			
	$code1 = $row["code1"];
	$code2 = $row["code2"];
	$code3 = $row["code3"];
	$code4 = $row["code4"];
	$code5 = $row["code5"];
	$code6 = $row["code6"];
	$code7 = $row["code7"];
	
	$q1 = "insert into student10(sid,roll,enroll,sname,fname,dob,gender,cat,subject_code,address) values ($sid,'$roll','$enroll','$sname','$fname','$dob','$gender','$cat','$code1','$add')";
	$r1 = mysqli_query($conn,$q1);
	
	$q1 = "insert into student10(sid,roll,enroll,sname,fname,dob,gender,cat,subject_code,address) values ($sid,'$roll','$enroll','$sname','$fname','$dob','$gender','$cat','$code2','$add')";
	$r1 = mysqli_query($conn,$q1);
	
	$q1 = "insert into student10(sid,roll,enroll,sname,fname,dob,gender,cat,subject_code,address) values ($sid,'$roll','$enroll','$sname','$fname','$dob','$gender','$cat','$code3','$add')";
	$r1 = mysqli_query($conn,$q1);
	
	$q1 = "insert into student10(sid,roll,enroll,sname,fname,dob,gender,cat,subject_code,address) values ($sid,'$roll','$enroll','$sname','$fname','$dob','$gender','$cat','$code4','$add')";
	$r1 = mysqli_query($conn,$q1);
	
	$q1 = "insert into student10(sid,roll,enroll,sname,fname,dob,gender,cat,subject_code,address) values ($sid,'$roll','$enroll','$sname','$fname','$dob','$gender','$cat','$code5','$add')";
	$r1 = mysqli_query($conn,$q1);
	
	$q1 = "insert into student10(sid,roll,enroll,sname,fname,dob,gender,cat,subject_code,address) values ($sid,'$roll','$enroll','$sname','$fname','$dob','$gender','$cat','$code6','$add')";
	$r1 = mysqli_query($conn,$q1);
	
	$q1 = "insert into student10(sid,roll,enroll,sname,fname,dob,gender,cat,subject_code,address) values ($sid,'$roll','$enroll','$sname','$fname','$dob','$gender','$cat','$code7','$add')";
	$r1 = mysqli_query($conn,$q1);

}
mysqli_close($conn);
?>