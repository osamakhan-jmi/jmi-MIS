<?php
include("connection-sql.php");
session_start();
if(!$_SESSION)
	header("Location:index.php");

function subjectFetch($conn){
	$query = "select code,subname from subject10 where private='NO'";
	$result = mysqli_query($conn,$query);
	echo '<br><br><table align="center" border="1">';
	echo '<tr><th colspan="6">Class 10th Regular</th></tr>';
	echo '<tr>';
	echo '<th>'.'Subject Code'.'</th>';
	echo '<th>'.'Subject'.'</th>';
	echo '<th>'.'Class'.'</th>';
	echo '<th>'.'Enter FA'.'</th>';
	echo '<th>'.'Enter SA-I'.'</th>';
	echo '<th>'.'Enter SA-II'.'</th>';
	echo '</tr>';
	while($row=mysqli_fetch_assoc($result)){
		echo '<tr>';
		echo '<td align="center">'.$row["code"].'</td>';
		echo '<td align="center">'.$row["subname"].'</td>';
		echo '<td align="center">'."10".'</td>';
		echo '<td align="center"><a target=new href="markentry.php?subjectcode='.$row["code"].'&&class='."10".'&&type='."uep".'&&private=NO'.'">Enter</a></td>';
		echo '<td align="center"><a target=new href="markentry.php?subjectcode='.$row["code"].'&&class='."10".'&&type='."ue".'&&private=NO'.'">Enter</a></td>';
		echo '<td align="center"><a target=new href="markentry.php?subjectcode='.$row["code"].'&&class='."10".'&&type='."ia".'&&private=NO'.'">Enter</a></td>';
		echo '</tr>';
		}
	echo '</table><br><br><br>';

	$query = "select code,subname,ue,ia,uep,iap from subject10 where private='YES'";
	$result = mysqli_query($conn,$query);
	echo '<table align="center" border="1">';
	echo '<tr><th colspan="7">Class 10th Private</th></tr>';
	echo '<tr>';
	echo '<th rowspan="2">'.'Subject Code'.'</th>';
	echo '<th rowspan="2">'.'Subject'.'</th>';
	echo '<th rowspan="2">'.'Class'.'</th>';
	echo '<th colspan="4">'.'Enter'.'</th>';
		echo '<tr><td>UE</td><td>IA</td><td>UEP</td><td>IAP</td></tr>';
	echo '</tr>';
	while($row=mysqli_fetch_assoc($result)){
		echo '<tr>';
		echo '<td align="center">'.$row["code"].'</td>';
		echo '<td align="center">'.$row["subname"].'</td>';
		echo '<td align="center">'."12".'</td>';
		
		if($row["ue"]!=0)
		echo '<td align="center"><a target=new href="markentry.php?subjectcode='.$row["code"].'&&private=YES'.'&&class='."10".'&&type=ue">Enter UE</a></td>';
		else echo '<td>Not Found</td>';
			
		if($row["ia"]!=0)
		echo '<td align="center"><a target=new href="markentry.php?subjectcode='.$row["code"].'&&private=YES'.'&&class='."10".'&&type=ia">Enter IA</a></td>';
		else echo'<td>Not Found</td>';
		
		if($row["uep"]!=0)
		echo '<td align="center"><a target=new href="markentry.php?subjectcode='.$row["code"].'&&private=YES'.'&&class='."10".'&&type=uep">Enter UEP</a></td>';
		else echo'<td>Not Found</td>';
		
		if($row["iap"]!=0)
		echo '<td align="center"><a target=new href="markentry.php?subjectcode='.$row["code"].'&&private=YES'.'&&class='."10".'&&type=uep">Enter UEP</a></td>';
		else echo'<td>Not Found</td>';
		
		echo '</tr>';
		}
	echo '</table><br><br><br>';


	
	$query = "select code,subname,ue,ia,uep,iap from subject12 where private='NO'";
	$result = mysqli_query($conn,$query);
	echo '<table align="center" border="1">';
		echo '<tr><th colspan="7">Class 12th Regular</th></tr>';
	echo '<tr>';
	echo '<th rowspan="2">'.'Subject Code'.'</th>';
	echo '<th rowspan="2">'.'Subject'.'</th>';
	echo '<th rowspan="2">'.'Class'.'</th>';
	echo '<th colspan="4">'.'Enter'.'</th>';
		echo '<tr><td>UE</td><td>IA</td><td>UEP</td><td>IAP</td></tr>';
	echo '</tr>';
	while($row=mysqli_fetch_assoc($result)){
		echo '<tr>';
		echo '<td align="center">'.$row["code"].'</td>';
		echo '<td align="center">'.$row["subname"].'</td>';
		echo '<td align="center">'."12".'</td>';
		
		if($row["ue"]!=0)
		echo '<td align="center"><a target=new href="markentry.php?subjectcode='.$row["code"].'&&private=NO'.'&&class='."12".'&&type=ue">Enter UE</a></td>';
		else echo '<td>Not Found</td>';
			
		if($row["ia"]!=0)
		echo '<td align="center"><a target=new href="markentry.php?subjectcode='.$row["code"].'&&private=NO'.'&&class='."12".'&&type=ia">Enter IA</a></td>';
		else echo'<td>Not Found</td>';
		
		if($row["uep"]!=0)
		echo '<td align="center"><a target=new href="markentry.php?subjectcode='.$row["code"].'&&private=NO'.'&&class='."12".'&&type=uep">Enter UEP</a></td>';
		else echo'<td>Not Found</td>';
		
		if($row["iap"]!=0)
		echo '<td align="center"><a target=new href="markentry.php?subjectcode='.$row["code"].'&&private=NO'.'&&class='."12".'&&type=uep">Enter UEP</a></td>';
		else echo'<td>Not Found</td>';
		
		echo '</tr>';
		}
	echo '</table><br><br><br>';
	
	$query = "select code,subname,ue,ia,uep,iap from subject12 where private='YES'";
	$result = mysqli_query($conn,$query);
	echo '<table align="center" border="1">';
		echo '<tr><th colspan="7">Class 12th Private</th></tr>';
	echo '<tr>';
	echo '<th rowspan="2">'.'Subject Code'.'</th>';
	echo '<th rowspan="2">'.'Subject'.'</th>';
	echo '<th rowspan="2">'.'Class'.'</th>';
	echo '<th colspan="4">'.'Enter'.'</th>';
		echo '<tr><td>UE</td><td>IA</td><td>UEP</td><td>IAP</td></tr>';
	echo '</tr>';
	while($row=mysqli_fetch_assoc($result)){
		echo '<tr>';
		echo '<td align="center">'.$row["code"].'</td>';
		echo '<td align="center">'.$row["subname"].'</td>';
		echo '<td align="center">'."12".'</td>';
		
		if($row["ue"]!=0)
		echo '<td align="center"><a target=new href="markentry.php?subjectcode='.$row["code"].'&&private=YES'.'&&class='."12".'&&type=ue">Enter UE</a></td>';
		else echo '<td>Not Found</td>';
			
		if($row["ia"]!=0)
		echo '<td align="center"><a target=new href="markentry.php?subjectcode='.$row["code"].'&&private=YES'.'&&class='."12".'&&type=ia">Enter IA</a></td>';
		else echo'<td>Not Found</td>';
		
		if($row["uep"]!=0)
		echo '<td align="center"><a target=new href="markentry.php?subjectcode='.$row["code"].'&&private=YES'.'&&class='."12".'&&type=uep">Enter UEP</a></td>';
		else echo'<td>Not Found</td>';
		
		if($row["iap"]!=0)
		echo '<td align="center"><a target=new href="markentry.php?subjectcode='.$row["code"].'&&private=YES'.'&&class='."12".'&&type=uep">Enter UEP</a></td>';
		else echo'<td>Not Found</td>';
		
		echo '</tr>';
		}
	echo '</table>';
}
if(isset($_REQUEST["type"])){
	$type = $_REQUEST["type"];
	
	if($type=="Marks Entry"){
		subjectFetch($conn);
		exit;
	}
	else if($type=="Student Entry"){
		header("Location:StudentEntry.php");
		exit;
	}
	else{
		echo "false";
		exit;
		}

}
?>
<!doctype html>
<html>
<head>
<title>Entry</title>
<link href="css/table.css" rel="stylesheet">
</head>
<a style="color:red;float:right" href="logout.php">Logout</a>
<a style="color:red" href="index.php">Home</a>
<br>
<hr>
<h1 align="center">Entry</h1>
<hr>
<body>
	
<table align="center">
	<tr>
    <td><select id="type">
    	<option>Select</option>
    	<option>Marks Entry</option>
    	<option>Student Entry</option>
	</select></td>
	<td><button id="typeSubmit">Submit</button></td>
	</tr>
</table>
<div id="ajaxOutput">
</div>
<script src="js/jquery.min.js"></script>
<script>
$(document).ready(function() {
	$("#typeSubmit").click(function() {
        var type = $("#type").val();
        if(type=="Student Entry")
        		window.location.href="StudentEntry.php";
        else{
		$.ajax({
			url:"entry.php",
			data:{"type":type},
			success: function(data){
				if(data=="false")
					alert("enter something to do!!");
				else{	
					$("#ajaxOutput").html(data);}
				}
		})};
    });
});
</script>

</body>
</html>