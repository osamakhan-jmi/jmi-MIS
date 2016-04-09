<?php
include("connection-sql.php");
//$conn is connection variabl
session_start();
if(!$_SESSION){
		header("Location:index.php");
session_unset($_SESSION["class"]);
session_unset($_SESSION["str"]);
session_unset($_SESSION["sid"]);
}
?>
<?php
if(isset($_REQUEST["sid"])&&isset($_REQUEST["clss"])&&isset($_REQUEST["str"])){//&&isset($_REQUEST["spr"])){
$sid = $_REQUEST["sid"];
$clss = $_REQUEST["clss"];
$str = $_REQUEST["str"];
//$spr = $_REQUEST["spr"];
$q1 = "select sid from student10 where sid=$sid";
$r1 = mysqli_query($conn,$q1);

$q2 = "select sid from student12 where sid=$sid";
$r2 = mysqli_query($conn,$q2);

if(mysqli_num_rows($r1)>0||mysqli_num_rows($r2)>0)
	{
		$_SESSION["sid"] = $sid;
		$_SESSION["class"] = $clss;
		//$_SESSION["spr"] = $spr;
		echo "false";
		exit;
	}
else
	{
		$_SESSION["sid"] = $sid;
		$_SESSION["class"] = $clss;
		$_SESSION["str"] = $str;
		//$_SESSION["spr"] = $spr;
		echo "true";
		exit;
	}
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Student Entry</title>
<link href="css/table.css" rel="stylesheet">
</head>
<!doctype html>
<head>
<title>Jamia-School</title>
<script src="js/jquery.js"></script>
</head>
<a style="color:red;float:right" href="logout.php">Logout</a>
<a style="color:red" href="entry.php">Home </a>
<br>
<hr>
<h1 align="center">Jamia School - Student Entry</h1>
<hr>

<script>
$(document).ready(function() {
       
	$("#checksid").click(function() {
        var sid = $("#sid").val();
		var clss = $("#cls").val();
		var str = $("#str").val();
		//var sprivate = $("#sprivate").val();
		if(sid==""||clss=="Class"||isNaN(sid)||str=="Stream")
			alert("check entries");
		else{
		$.ajax({
			url:"studentEntry.php",
			data:{"sid":sid,"clss":clss,"str":str},///"spr":sprivate},
			success: function(data)
			{
				
				if(data=="true"&&clss==10&&str=="None"){
					window.location.href = "studentEntry10.php";}
				else if(data=="true"&&clss==12&&str=="ART"){
					window.location.href = "studentEntry12art.php";}
				else if(data=="true"&&clss==12&&str=="SCIENCE"){
					window.location.href = "studentEntry12sci.php";}
				else if(data=="true"&&clss==12&&str=="COMMERCE"){
					window.location.href = "studentEntry12com.php";}
				else if(data=="false"){
					var choice = confirm("Student ID:"+sid+" exsist\n\nMake A Entry For CR-DI / EX ?");
						if(choice)
							window.location.href = "studentEntryclone.php";
					alert("Student Id Already Exsists!!");
				}
				else
					alert("check entries");
			},
			error:function(data){
				alert("ERROR_CONNECTION");
			}
		});}
    });
});

</script>

<body>
	<br><br><br>
	<table align="center" border="1">
    	<tr>
        	<th  colspan="4" style="color:#FC0105">Step I</th>
        </tr>   
        <tr>
            <td align="center"><input placeholder="Student ID" id="sid" type="text"></td>
        
            <td><select id="cls"><option>Class</option><option>10</option><option>12</option></select></td>
			<td><select id="str"><option>Stream</option><option>None</option><option>COMMERCE</option><option>ART</option><option>SCIENCE</option></select></td>  
            

        	<!--td align="center">Private</td>
            <td align="center"><select id="sprivate"><option>NO</option><option>YES</option></select></td-->
              
            <td colspan="2" align="right"><button id="checksid">Check</button></td>
        </tr>
    </table>
    
</body>
</html>