<!doctype html>
<html>
<head>
<link href="css/table.css" rel="stylesheet">
<title>Marks Entry</title>
<script src="js/jquery.min.js"></script>
</head>
<script>
function update(id,type,cls,code,prv){
	var x = "#" + id;
	var value = $(x).val();
	if(value=="")	alert("enter marks!!");
	
	else{
	$.ajax({
		url:"update.php",
		data:{"sid":id,"code":code,"value":value,"type":type,"class":cls,"prv":prv},
		success: function(data){
			if(data=="true")
				window.location.reload();
			else if(data=="UnAutharised Access!!!"){
				alert(data);
				window.location.href = "index.php";
			}
			else
				alert(data);
		},
		error:function(data){
			alert("server busy!!");
		}
		});}
}
</script>
<body>
<?php
include("connection-sql.php");
session_start();
if(!$_SESSION)
	header("Location:index.php");
$class = $_GET["class"];
if($class == 10)
{
	$type = $_GET["type"];
	if($type=="uep")
		$falsetype="FA";
	else if($type=="ia")
		$falsetype="SA-II";
	else $falsetype="SA-I";
	
	$code = $_GET["subjectcode"];
	$prv = $_GET["private"];
	$query = "select sid,roll,sname from class10 where $type=0 and subject_code='$code' and private='$prv' and sauth='YES'";
	$result = mysqli_query($conn,$query);
	echo '<h5 align="center">Absent: A|a</h5><h5 align="center"> Zero: Z|z</h5>';
	if(mysqli_num_rows($result)>0){
	echo '<br><table align="center" border="1">';
	echo '<tr>';
	echo '<th>'.'Subject Code'.'</th>';
	echo '<th>'.'Roll No'.'</th>';
	echo '<th>'.'Name'.'</th>';	
	echo '<th>'.'Marks Obtained'.'</th>';
	echo '<th>Enter '.$falsetype.'</th>';
	echo '</tr>';
	while($row = mysqli_fetch_assoc($result)){
		echo '<tr>';
		echo '<td align="center">'.$code.'</td>';
		$id = $row["sid"];
		echo '<td align="center">'.$row["roll"].'</td>';
		echo '<td>'.$row["sname"].'</td>';
		echo '<td align="center">'.'<input id="'.$id.'" type="text">'.'</td>';
		echo "<td align=\"center\"><button onClick=\"update('$id','$type','$class','$code','$prv')\">Submit</button></td>";
	}
	echo "</table>";
	}
	else echo "no student enrolled or all entries has been submited!!";
}
else if($class == 12)
{	
	$type = $_GET["type"];
	$code = $_GET["subjectcode"];
	$prv = $_GET["private"];
	$query = "select sid,roll,sname from class12 where $type=0 and subject_code='$code' and private='$prv' and sauth='YES'";
	$result = mysqli_query($conn,$query);
	echo '<h5 align="center">Absent: A|a</h5><h5 align="center"> Zero: Z|z</h5>';	
	if(mysqli_num_rows($result)>0){
	echo '<br><br><table align="center" border="1">';
	echo '<tr>';
	echo '<th>'.'Subject Code'.'</th>';
	echo '<th>'.'Roll No'.'</th>';
	echo '<th>'.'Name'.'</th>';	
	echo '<th>'.'Marks Obtained'.'</th>';
	echo '<th>Enter '.$type.'</th>';
	echo '</tr>';
	while($row = mysqli_fetch_assoc($result)){
		echo '<tr>';
		echo '<td align="center">'.$code.'</td>';
		$id = $row["sid"];
		echo '<td align="center">'.$row["roll"].'</td>';
		echo '<td>'.$row["sname"].'</td>';
		echo '<td align="center">'.'<input id="'.$id.'" type="text">'.'</td>';
		echo "<td align=\"center\"><button onClick=\"update('$id','$type','$class','$code','$prv')\">Submit</button></td>";
	}
	echo "</table>";
	}
	else echo "no student enrolled or all entries has been submited!!";	
}
?>
</body>
</html>