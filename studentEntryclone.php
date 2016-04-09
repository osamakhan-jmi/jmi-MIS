<?php
include("connection-sql.php");
//$conn is connection variabl
session_start();
if(!$_SESSION){
		header("Location:index.php");
}
if(isset($_REQUEST["type"])&&isset($_REQUEST["year"])){
	$type = $_REQUEST["type"];
	$class = $_SESSION["class"];
	$year = $_REQUEST["year"];
	$sid = $_SESSION["sid"];
	//echo $sid;exit;
	//$spr = $_SESSION["spr"];
	if($_SESSION["type"]!="admin"&&$type!="EX"){
		echo "You are allowed to fill Ex Student only";exit;}
	else if($_SESSION["type"]=="normal"&&$type=="EX"){
		$q = "select roll from student$class where sid='$sid' group by sid";
		$r = mysqli_query($conn,$q);
		$row = mysqli_fetch_assoc($r);
		$sid = $row["roll"];
		}

	if($type=="CR"){
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
		echo '<th>'.'Enter'.'</th>';}
	else if($class==10)
	{echo '<th>'.'SA-I'.'</th>';
	echo '<th>'.'SA-II'.'</th>';
	echo '<th>'.'FA'.'</th>';
	echo '<th>'.'Enter'.'</th>';}
	echo '</tr>';
	while($row = mysqli_fetch_assoc($r)){
		if($row["total"]<33){
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
		
		echo '<td>'.$row["ue"].'</td>';
		
		echo '<td>'.$row["ia"].'</td>';
		
		echo '<td>'.$row["uep"].'</td>';
		
		echo '<td>'.'<a target="_blank" href="addcrdi.php?roll='.$row["roll"].'&&class='.$class.'&&code='.$row["subject_code"].'">Enter</a>'.'</td>';
		echo '</tr>';}	
	}
	echo '</table>';exit;
	}
	else
		echo "Student Is not From Present Year";exit;
	}
	
	else if($type=="DI"){
		$query = "select sid,enroll,roll,cyear,sname,fname,dob,subject_code,ue,ia,iap,uep from class$class where roll = '$sid'";
		$r = mysqli_query($conn,$query);//echo $query;exit;
	if(mysqli_num_rows($r)>0){
		echo "student cannot be DI";exit;}
		else{
		if($class==10){
			
		$q = "insert into class10 (sid,roll,enroll,sname,fname,dob,gender,phoneno,subject_code,ue,ia,uep,iap,cyear,private) select sid,roll,enroll,sname,fname,dob,gender,phoneno,subject_code,ue,ia,uep,iap,year,private from student10 where roll='$sid'";
		mysqli_query($conn,$q);
		$qu1 = "update class10 set sauth='NO' where roll='$sid'";
		mysqli_query($conn,$qu1);
		$qu = "update class10 set cat='CR-DI' where roll='$sid'";
		mysqli_query($conn,$qu);
		$qu = "update student10 set cat='CR-DI' where roll='$sid'";
		mysqli_query($conn,$qu);}
		else if($class==12){
			
			$q = "insert into class12 (sid,roll,enroll,sname,fname,dob,gender,phoneno,subject_code,ue,ia,uep,iap,cyear,private,stream) select sid,roll,enroll,sname,fname,dob,gender,phoneno,subject_code,ue,ia,uep,iap,year,private,stream from student12 where roll='$sid'";
		mysqli_query($conn,$q);
		$qu1 = "update class12 set sauth='NO' where roll='$sid'";
		mysqli_query($conn,$qu1);
		$qu = "update class12 set cat='CR-DI' where roll='$sid'";
		mysqli_query($conn,$qu);
		$qu = "update student12 set cat='CR-DI' where roll='$sid'";
		mysqli_query($conn,$qu);}
		}
	$query = "select sid,enroll,roll,year,sname,fname,dob,subject_code,ue,ia,uep,iap from student$class where roll = '$sid'";
	$r = mysqli_query($conn,$query);
	echo '<br><table align="center" border="1">';
	echo '<tr>';
	echo '<th>'.'SID'.'</th>';
	echo '<th>'.'Enroll'.'</th>';
	echo '<th>'.'Roll'.'</th>';
	echo '<th>'.'Year Of Passout'.'</th>';
	echo '<th>'.'Student Name'.'</th>';
	echo '<th>'.'Father Name'.'</th>';
	echo '<th>'.'Date Of Birth'.'</th>';
	echo '<th>'.'Subject Code'.'</th>';
		if($class==12){
	echo '<th>'.'UE'.'</th>';
	echo '<th>'.'IA'.'</th>';
	echo '<th>'.'UEP'.'</th>';
	}
	else if($class==10){
	echo '<th>'.'SA-I'.'</th>';
	echo '<th>'.'SA-II'.'</th>';
	echo '<th>'.'FA'.'</th>';
	}
	echo '<th>'.'Enter'.'</th>';
	echo '</tr>';
	while($row = mysqli_fetch_assoc($r)){
		echo '<tr>';
		echo '<td>'.$row["sid"].'</td>';
		echo '<td>'.$row["enroll"].'</td>';
		echo '<td>'.$row["roll"].'</td>';
		echo '<td>'.$row["year"].'</td>';
		echo '<td>'.$row["sname"].'</td>';
		echo '<td>'.$row["fname"].'</td>';
		echo '<td>'.$row["dob"].'</td>';
		echo '<td>'.$row["subject_code"].'</td>';
		echo '<td>'.$row["ue"].'</td>';
		echo '<td>'.$row["ia"].'</td>';
		echo '<td>'.$row["uep"].'</td>';
		echo '<td>'.'<a target="_blank" href="addcrdi.php?roll='.$row["roll"].'&&class='.$class.'&&code='.$row["subject_code"].'">Enter</a>'.'</td>';
		echo '</tr>';	
	}
	echo '</table>';exit;
	}
	else if($type=="EX"){
		
		$query = "select sid from class$class where roll = '$sid'";
		$r3 = mysqli_query($conn,$query);
	if(mysqli_num_rows($r3)>0){
		echo "student cannot be EX";exit;}
		else{
		if($class==10){
		$q = "insert into class10 (sid,roll,enroll,sname,fname,dob,gender,phoneno,subject_code,private) select sid,roll,enroll,sname,fname,dob,gender,phoneno,subject_code,private from student10 where roll='$sid'";
		mysqli_query($conn,$q);
		$q = "update class10 set cat='EX',cyear=$year where roll='$sid'";
		mysqli_query($conn,$q);
		$q = "update student10 set cat='EX',year=$year where roll='$sid'";
		mysqli_query($conn,$q);
		echo "Confirmed EX Entry!!";exit;
		}
		else if($class==12){
			$q = "insert into class12 (sid,roll,enroll,sname,fname,dob,gender,phoneno,subject_code,private,stream) select sid,roll,enroll,sname,fname,dob,gender,phoneno,subject_code,private,stream from student12 where roll='$sid'";
		mysqli_query($conn,$q);
		$q = "update class12 set cat='EX',cyear=$year where roll='$sid'";
		mysqli_query($conn,$q);
		$q = "update student12 set cat='EX',year=$year where roll='$sid'";
		mysqli_query($conn,$q);
		echo "Confirmed EX Entry!!";exit;
		}
		}
	}
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>CR-DI / EX</title>
<script src="js/jquery.js"></script>
<link href="css/table.css" rel="stylesheet">
</head>
<a style="color:red;float:right" href="logout.php">Logout</a>
<a style="color:red" href="superAdmin.php">Home</a>
<hr>
<h1 align="center">Jamia School - CR/DI/EX Student Entry</h1>
<hr>
<script>
$(document).ready(function() {
    $("#typeSubmit").click(function() {
        var type = $("#stype").val();
		var year = $("#year").val();
		$.ajax({
			url:"studentEntryclone.php",
			data:{"type":type,"year":year},
			success: function(data){
				//alert(data);
				$("#ajaxOutput").html(data);
			},
			error: function(data){
				alert("ERROR_CONNECTION");
			}
		});
    });
});
</script>

<body>
	<table align="center" border="1">
    	<tr>
        	<td>Student Type</td>
            <td><select id="stype">
            		<option>EX</option>
                    <option>CR</option>
                    <option>DI</option>
            	</select>
            </td>
            <td align="center"><select id="year">
            				   <?php 
							   	$t = time();
								$y1 = date("Y",$t);
								$y2 = $y1+1;
								echo "<option>$y1</option>";
								echo "<option>$y2</option>";
							    ?>
                               </select></td>
            <td><button id="typeSubmit">Submit</button></td>
        </tr>
      </table>
      <div id="ajaxOutput"></div>
</body>
</html>