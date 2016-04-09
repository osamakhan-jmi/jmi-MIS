<?php
include("connection-sql.php");
include("idsgenrator.php");
//$conn is connection variabl
session_start();
if(!$_SESSION)
	header("Location:index.php");
if($_SESSION["class"]!=10){
		header("Location:studentEntry.php");
}
if(isset($_REQUEST["sname"])&&isset($_REQUEST["fname"])&&isset($_REQUEST["dob"])&&isset($_REQUEST["sub1"])&&isset($_REQUEST["sub2"])&&isset($_REQUEST["sub3"])&&isset($_REQUEST["sub4"])&&isset($_REQUEST["sub5"])&&isset($_REQUEST["sub6"])&&isset($_REQUEST["sub7"])&&isset($_REQUEST["gen"])&&isset($_REQUEST["spr"])&&isset($_REQUEST["phno"])&&isset($_REQUEST["add"])&&isset($_REQUEST["year"])){
	
		
	$q = "select count(*) from student12 group by sid,roll,enroll";
	$r = mysqli_query($conn,$q);
	$count1 = mysqli_num_rows($r);
	$q = "select count(*) from student10 group by sid,roll,enroll";
	$r = mysqli_query($conn,$q);
	$count = $count1 + mysqli_num_rows($r);
		
	$sid = $_SESSION["sid"];
	$sname = $_REQUEST["sname"];
	$fname = $_REQUEST["fname"];
	$dob = $_REQUEST["dob"];
		
	$spr = $_REQUEST["spr"];
	
	$sub1 = $_REQUEST["sub1"];
	$sub2 = $_REQUEST["sub2"];
	$sub3 = $_REQUEST["sub3"];
	$sub4 = $_REQUEST["sub4"];
	$sub5 = $_REQUEST["sub5"];
	$sub6 = $_REQUEST["sub6"];
	$sub7 = $_REQUEST["sub7"];
	$phno = $_REQUEST["phno"];	
	$add = $_REQUEST["add"];
	$gen = $_REQUEST["gen"];
	
	if($gen=="select"||$sub3==""||$sub4==""||$sub5==""||$sub6==""||$sub7==""||$sub4=="select")
		{echo "check Subjects or gender";exit;}
	if($spr=='YES')
		$cat = 'PRIVATE';
	else 
		$cat = 'REGULAR';
	
	$roll = year().course(10,NULL,$spr).digits(10,NULL,$count+1,"roll");
	$enroll = year()."-".digits(10,NULL,$count+1,"enroll");
	$check = 0;
	
	if($sname==""||$fname==""||ctype_digit($sname)||ctype_digit($fname)){
		echo "Check Names";
		exit;
	}
	else{
		$c1=0;
		$c2=0;
		
		$y = $_REQUEST["year"];
		$subjects = array($sub1,$sub2,$sub3,$sub4,$sub5,$sub6,$sub7);
		
		for($i=0;$i<7;$i++){
		
		$q1 = "insert into student10 values ($sid,'$roll','$enroll','$y','$sname','$fname','$dob','$gen',$phno,'$add','$subjects[$i]',0,0,0,0,'$cat','$spr')";
		
		mysqli_query($conn,$q1);
		
		$q2 = "insert into class10 values ($sid,'$roll','$enroll','$sname','$fname','$dob','$gen',$phno,'$subjects[$i]',0,0,0,0,0,'YES','$y','$cat','$spr')";
		
		mysqli_query($conn,$q2);
		
		}
		//echo $q1.$q2;
		echo "true";
		exit;
		
	}
	
}
?>
<!doctype html>
<html>
<head>
<title>Jamia-School</title>
<script src="js/jquery.js"></script>
<script src="js/jquery-ui.js"></script>
<link href="css/jquery-ui.css" rel="stylesheet">
<link href="css/table.css" rel="stylesheet">
</head>
<a style="color:red;float:right" href="logout.php">Logout</a>
<a style="color:red" href="entry.php">Home</a>
<hr>
<h1 align="center">Jamia School</h1>
<hr>
<script>

$(function(){
$("#sdob").datepicker({dateFormat:'yy-mm-dd'});
});

$(document).ready(function(e) {
	
	$("#sub4").change(function(e) {
		var sub4 = $("#sub4").val();
		var i = sub4.indexOf("/")+1;
		sub4 = sub4.substring(i,sub4.length);
		if(sub4=="CLU003")
			$("#sub5").html("<option>HINDI-A/CSC102</option>");
		else if(sub4=="CSC101")
			$("#sub5").html("<option>HINDI-B/CSC103</option>");
		else 
			$("#sub5").html("");
    });
	
	$("#spr").change(function() {
        var spr = $("#spr").val();
		var gen = $("#gen").val();
		if(gen=="F"&&spr=="YES"){
			$("#sub6").html("<option>MATHEMATICS/CSC105</option><option>HOME SCIENCE/CSC108</option>");
			$("#sub7").html("<option>SCIENCE AND TECHNOLOGY/CSC106</option><option>GENRAL SCIENCE/CSC109</option>");}
		else{
			$("#sub6").html("<option>MATHEMATICS/CSC105</option>");
			$("#sub7").html("<option>SCIENCE AND TECHNOLOGY/CSC106</option>");
		}
    });
	
	$("#gen").change(function() {
		var gen = $("#gen").val();
		var spr = $("#spr").val();
		if((gen=="M"||gen=="F")&&spr=="NO"){
			$("#sub6").html("<option>MATHEMATICS/CSC105</option>");
			$("#sub7").html("<option>SCIENCE AND TECHNOLOGY/CSC106</option>");
		}
		
		else if(gen=="F"&&spr=="YES"){
			$("#sub6").html("<option>MATHEMATICS/CSC105</option><option>HOME SCIENCE/CSC108</option>");
			$("#sub7").html("<option>SCIENCE AND TECHNOLOGY/CSC106</option><option>GENRAL SCIENCE/CSC109</option>");}
		
		else if((gen=="M"||gen=="F")&&spr=="YES"){
			$("#sub6").html("<option>MATHEMATICS/CSC105</option>");
			$("#sub7").html("<option>SCIENCE AND TECHNOLOGY/CSC106</option>");
		}
		else {
			$("#sub6").html("");
			$("#sub7").html("");}
    });
	
	$("#detsubmit").click(function(e) {
        var sname = $("#sname").val();
		//alert(sname);
		var fname = $("#fname").val();
		//alert(fname);
		var dob = $("#sdob").val();
		//alert(dob);
		var gen = $("#gen").val();
		
		var spr = $("#spr").val();
		
		var sub1 = $("#sub1").val();
		
		var i = sub1.indexOf("/")+1;
		sub1 = sub1.substring(i,sub1.length);
		//alert(sub1);
		
		var sub2 = $("#sub2").val();
		var i = sub2.indexOf("/")+1;
		sub2 = sub2.substring(i,sub2.length);
		//alert(sub2);
		
		var sub3 = $("#sub3").val();
		var i = sub3.indexOf("/")+1;
		sub3 = sub3.substring(i,sub3.length);
		//alert(sub3);
		
		var sub4 = $("#sub4").val();
		var i = sub4.indexOf("/")+1;
		sub4 = sub4.substring(i,sub4.length);
		//alert(sub4);
		
		var sub5 = $("#sub5").val();
		if(sub5=="")
			alert("subject is empty");
		var i = sub5.indexOf("/")+1;
		sub5 = sub5.substring(i,sub5.length);
		//alert(sub5);
		
		var sub6 = $("#sub6").val();
		if(sub6=="")
			alert("subject is empty");
		var i = sub6.indexOf("/")+1;
		sub6 = sub6.substring(i,sub6.length);
		//alert(sub6);
		
		var sub7 = $("#sub7").val();
		var i = sub7.indexOf("/")+1;
		sub7 = sub7.substring(i,sub7.length);
		//alert(sub7);
		
		var year = $("#year").val();
		
		var add = $("#add").val();
		var phno = $("#phno").val();
		//alert(add+phno);
		if(phno.length!=10||add==""||isNaN(phno))
			alert("Wrong Address or Phone Number");
		else{
				
		$.ajax({
		url:"studentEntry10.php",
		cache:false,
		data:{"sname":sname,"fname":fname,"dob":dob,"sub1":sub1,"sub2":sub2,"sub3":sub3,"sub4":sub4,"sub5":sub5,"sub6":sub6,"sub7":sub7,"gen":gen,"spr":spr,"phno":phno,"add":add,"year":year,"dob":dob},
		success: function(data){
				//alert(data);
				
				if(data=="true"){
						alert("entries successfully made!!");
						window.location.href = "studentEntry.php";}
				
				else{
					alert(data);
					//alert("entries cannot be made!!");
					//window.location.href = "studentEntry.php";
					}
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
    	<tr><th colspan="2" style="color:#FB0509">Final Step Fill Entries </th></tr>
        <tr>
        	<td align="center">Student Name</td>
            <td align="center"><input type="text" id="sname"></td>
        </tr>
        <tr>
        	<td align="center">Father Name</td>
            <td align="center"><input type="text" id="fname"></td>
        </tr>
        <tr>
        	<td align="center">Date Of Birth</td>
            <td align="center"><input placeholder="YYYY-MM-DD" id="sdob" type="text">
            				   
            </td>
        </tr>
        <tr>
        	<td align="center">Gender</td>
            <td align="center"><select id="gen">><option>select</option><option>M</option><option>F</option></select></td>
        </tr>
        <tr>
        	<td align="center">Phone Number</td>
            <td align="center"><input type="text" id="phno"></td>
        </tr>
        <tr>
        	<td align="center">Address</td>
            <td align="center"><textarea type="text" id="add"></textarea></td>
        </tr>
        <tr>
        	<td align="center">Private</td>
            <td align="center"><select id="spr"><option>NO</option><option>YES</option></select></td>
        </tr>
		<tr>
        	<td align="center">Year Of Pass</td>
            <td align="center"><select id="year">
            				   <?php 
							   	$t = time();
								$y1 = date("Y",$t);
								$y2 = $y1+1;
								echo "<option>$y1</option>";
								echo "<option>$y2</option>";
							    ?>
                               </select></td>		
        </tr>
        <tr>
        	<td>Subject 1</td>
            <td align="center"><select id="sub1"><option>ENGLISH/CSC104</option></select></td>
        </tr>
        <tr>
        	<td>Subject 2</td>
            <td align="center"><select id="sub2"><option>SOCIAL SCIENCE/CSC107</option></select></td>
        </tr>
        <tr>
        	<td>Subject 3</td>
            <td align="center"><select id="sub3"><option>ISLAMIAT/CIS001</option><option>HINDU ETHICS/CLH002</option></td>
        </tr>
        <tr>
        	<td>Subject 4</td>
            <td align="center"><select id="sub4"><option>select</option><option>ELEMENTRY URDU/CLU003</option><option>ADVANCE URDU/CSC101</option></td>
        </tr>
        <tr>
        	<td>Subject 5</td>
            <td align="center"><select id="sub5"></select></td>
        </tr>
        <tr>
        	<td>Subject 6</td>
            <td align="center"><select id="sub6"><option>MATHEMATICS/CSC105</option></select></td>
        </tr>
        <tr>
        	<td>Subject 7</td>
            <td align="center"><select id="sub7"><option>SCIENCE AND TECHNOLOGY/CSC106</option></select></td>
        </tr>
        <tr>
        	<td align="center" colspan="2"><button id="detsubmit">Submit</button></td>
        </tr>
    </table><br><br>
    <div id="ajaxOutput"></div>
</body>
</html>