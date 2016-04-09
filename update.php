<?php
include("connection-sql.php");
session_start();
if(!$_SESSION)
	header("Location:index.php");

$sid = $_REQUEST["sid"];
$type = $_REQUEST["type"];
$class = $_REQUEST["class"];
$value = $_REQUEST["value"];
$code = $_REQUEST["code"];
$prv= $_REQUEST["prv"];

		if($value=="a"||$value=="A"){
			$value = -1;
		}
		else if($value=="z"||$value=="Z")
			$value=-2;
		else if(!is_numeric($value)){
			echo "check values";
			exit;
		}
	
	if(!isset($_SESSION["id"])){
		echo "UnAutharised Access!!!";
		exit;
	}	
	else $id=$_SESSION["id"];
	
	$query2 = "select name from access where username='$id'";
	$result2 = mysqli_query($conn,$query2);
	$row2=mysqli_fetch_assoc($result2);
	$name = $row2["name"];
	$t = time();
	$date = date("Y-m-d",$t);
	$query1 = "insert into log(name,sub_code,class,type,private) values('$name','$code','$class','$type','$prv')";
	mysqli_query($conn,$query1) or header("Location:index.php");
	
	$q = "select $type as max from subject$class where code='$code' and private='$prv'";
	$r = mysqli_query($conn,$q);
	$row = mysqli_fetch_assoc($r);
	$max = $row["max"];
	if($value>$max){
		echo "Maximum Marks"."=".$max;
		exit;
		}
			
	$q3 = "select cat from class$class where sid = $sid group by sid";
	$r3 = mysqli_query($conn,$q3);
	$row = mysqli_fetch_assoc($r3);
	$cat = $row["cat"];
			
	if($cat=="REGULAR"||$cat =="PRIVATE"||$cat=="EX"){
		$q4 = "update student$class set $type=$value where sid=$sid and subject_code='$code'";
		mysqli_query($conn,$q4);
		$q1 = "update class$class set $type=$value where sid=$sid and subject_code='$code' and private='$prv'";
		if(mysqli_query($conn,$q1)){
			if($class==10){
				$q2 = "call check_class10($sid,'$code','$prv')";
				mysqli_query($conn,$q2);
				echo "true";
				exit;
			}
			else if($class=12){
				$q2 = "call check_class12($sid,'$code','$prv')";
				mysqli_query($conn,$q2);
				echo "true";
				exit;
					}
		}
		else{
			$q1 = "update student$class set $type=0 where sid=$sid and subject_code='$code' and private='$prv'";
			mysqli_query($conn,$q1);
			echo "Fail to make Entry!!\nTry Again...";
			exit;
		}
	}
	else if($cat=="CR-DI"){	
		$q_crdi = "select $type from student$class where sid=$sid and subject_code='$code'";
		$r_crdi=mysqli_query($conn,$q_crdi);
		$row3 = mysqli_fetch_assoc($r_crdi);
		$pre = $row3[$type];
		if($value<$pre){
			$qi = "insert into di_log(sid,subject_code,type,marks) values ($sid,'$code','$type',$value)";
			mysqli_query($conn,$qi);
			$qu = "update class$class set $type = $pre where sid = $sid and subject_code='$code'";
			mysqli_query($conn,$qu);
			if($class==10){
				$q2 = "call check_class10($sid,'$code','$prv')";
				mysqli_query($conn,$q2);
				echo "true";
				exit;
			}
			else if($class=12){
				$q2 = "call check_class12($sid,'$code','$prv')";
				mysqli_query($conn,$q2);
				echo "true";
				exit;
					}
		}
		else if($value>=$pre){
			$qi = "insert into di_log(sid,subject_code,type,marks) values ($sid,'$code','$type',$pre)";
			mysqli_query($conn,$qi);
			$qu1 = "update student$class set $type = $value where sid = $sid and subject_code='$code'";
			mysqli_query($conn,$qu1);
			$qu2 = "update class$class set $type = $value where sid = $sid and subject_code='$code'";
			mysqli_query($conn,$qu2);
			
			if($class==10){
				$q2 = "call check_class10($sid,'$code','$prv')";
				mysqli_query($conn,$q2);
				echo "true";
				exit;
			}
			else if($class=12){
				$q2 = "call check_class12($sid,'$code','$prv')";
				mysqli_query($conn,$q2);
				echo "true";
				exit;
					}
		}
		
	}
?>