<link href="css/table.css" rel="stylesheet">
<?php
include("connection-sql.php");
//$conn is connection variabl
session_start();
if(!$_SESSION){
		header("Location:index.php");
}
	$sid = $_GET["roll"];
	$class = $_GET["clas"];
	$query = "select sid,enroll,roll,sname,fname,dob,subject_code,ue,ia,uep,iap,total from class$class where roll = '$sid'";
	$r = mysqli_query($conn,$query);
	if(mysqli_num_rows($r)>0){
	echo '<br><table align="center" border="1">';
	echo '<tr>';
	//echo '<th>'.'Class'.'</th>';
	echo '<th>'.'SID'.'</th>';
	echo '<th>'.'Enroll'.'</th>';
	echo '<th>'.'Roll'.'</th>';
	//echo '<th>'.'Year Of Passout'.'</th>';
	echo '<th>'.'Student Name'.'</th>';
	echo '<th>'.'Father Name'.'</th>';
	echo '<th>'.'Date Of Birth'.'</th>';
	echo '<th>'.'Subject Code'.'</th>';
	if($class==12){
	echo '<th>'.'UE'.'</th>';
	echo '<th>'.'IA'.'</th>';
	echo '<th>'.'UEP'.'</th>';
	}
	else if($class==10)
	{echo '<th>'.'SA-I'.'</th>';
	echo '<th>'.'SA-II'.'</th>';
	echo '<th>'.'FA'.'</th>';
	}
	echo '</tr>';
	while($row = mysqli_fetch_assoc($r)){
		
		echo '<tr>';
		//echo '<td>'.$class.'</td>';
		echo '<td>'.$row["sid"].'</td>';
		echo '<td>'.$row["enroll"].'</td>';
		echo '<td>'.$row["roll"].'</td>';
		//echo '<td>'.$row["year"].'</td>';
		echo '<td>'.$row["sname"].'</td>';
		echo '<td>'.$row["fname"].'</td>';
		echo '<td>'.$row["dob"].'</td>';
		echo '<td>'.$row["subject_code"].'</td>';
		
		if($row["ue"]!=-1)
			echo '<td>'.$row["ue"].'</td>';
		else
			echo '<td>Absent</td>';
		
		if($row["ia"]!=-1)
			echo '<td>'.$row["ia"].'</td>';
		else 
			echo '<td>Absent</td>';
		
		if($row["uep"]!=-1)
			echo '<td>'.$row["uep"].'</td>';
		else
			echo '<td>Absent</td>';
		
		echo '</tr>';	
	}
	echo '</table>';exit;
	}
	else
		echo "Student Not Found!!";exit;
	
?>