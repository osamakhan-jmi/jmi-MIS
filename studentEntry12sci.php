<?php
include("connection-sql.php");
include("idsgenrator.php");
//$conn is connection variabl
session_start();
if(!$_SESSION)
	header("Location:index.php");
if($_SESSION["class"]!=12||$_SESSION["str"]!='SCIENCE'){
		header("Location:studentEntry.php");}
		
if(isset($_REQUEST["sname"])&&isset($_REQUEST["fname"])&&isset($_REQUEST["dob"])&&isset($_REQUEST["sub1"])&&isset($_REQUEST["sub2"])&&isset($_REQUEST["sub3"])&&isset($_REQUEST["sub4"])&&isset($_REQUEST["sub5"])&&$_REQUEST["gen"]&&isset($_REQUEST["phno"])&&isset($_REQUEST["add"])&&isset($_REQUEST["year"])){
	
	$q = "select count(*) from student12 group by sid,roll,enroll";
	$r = mysqli_query($conn,$q);
	$count1 = mysqli_num_rows($r);
	$q = "select count(*) from student10 group by sid,roll,enroll";
	$r = mysqli_query($conn,$q);
	$count = $count1 + mysqli_num_rows($r);
	
	$sid = $_SESSION["sid"];
	$stream = $_SESSION["str"];
	$sname = $_REQUEST["sname"];
	$fname = $_REQUEST["fname"];
	$sub1 =  $_REQUEST["sub1"];
	$sub2 =  $_REQUEST["sub2"];
	$sub3 =  $_REQUEST["sub3"];
	$sub4 =  $_REQUEST["sub4"];
	$sub5 =  $_REQUEST["sub5"];
	$phno = $_REQUEST["phno"];	
	$add = $_REQUEST["add"];
	$dob = $_REQUEST["dob"];
	//$spr = $_SESSION["spr"];
	$gen = $_REQUEST["gen"];
	$spr = "NO";
	if($spr=="YES")
		$spr="NO";
	$roll = year().course(12,$stream,"NO").digits(12,$stream,$count+1,"roll");
	$enroll = year()."-".digits(12,$stream,$count+1,"enroll");
	$check = 0;
	
	if($sname==""||$fname==""||ctype_digit($sname)||ctype_digit($fname)||$sub4=="select")
		{echo "Check Names";exit;}
	if($gen=="select"||$sub3==""||$sub4==""||$sub5==""||$sub4=="select"||$sub5=="select")
		{echo "check Subjects";exit;}
	
	$subjects = array($sub1,$sub2,$sub3,$sub4,$sub5);
	/*for($i=0;$i<5;$i++){
		for($j=$i+1;$j<5;$j++){
		if($subjects[$i]==$subjects[$j])
			{$check=1;break;}
		}
	}
	//if($check)
		//{echo "check Subjects";exit;}	*/

	$y = $_REQUEST["year"];
	
	for($i=0;$i<5;$i++){
		$q1 = "insert into student12 values ($sid,'$enroll','$roll','$y','$sname','$fname','$dob','$gen',$phno,'$add','$subjects[$i]',0,0,0,0,'$spr','REGULAR','$stream')";
		mysqli_query($conn,$q1);
		$q2 = "insert into class12 values ($sid,'$roll','$enroll','$sname','$fname','$dob','$gen',$phno,'$subjects[$i]',0,0,0,0,0,'YES','$y','REGULAR','$spr','$stream')";
		mysqli_query($conn,$q2);
	}
	echo "true";exit;
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Student Entry</title>
</head>
<!doctype html>
<head>
<title>Jamia-School</title>
<script src="js/jquery.js"></script>
<script src="js/jquery-ui.js"></script>
<link href="css/jquery-ui.css" rel="stylesheet">
<link href="css/table.css" rel="stylesheet">
</head>
<a style="color:red;float:right" href="logout.php">Logout</a>
<a style="color:red" href="entry.php">Home </a>
<hr>
<h1 align="center">Jamia School</h1>
<hr>
<script>
$(function(){
$("#sdob").datepicker({dateFormat:'yy-mm-dd'});
});
$(document).ready(function(e) {
	
	$("#sub4").change(function() {
        var sub4 = $("#sub4").val();
		var i = sub4.indexOf("/")+1;
		sub4 = sub4.substring(i,sub4.length);
		if(sub4=="CSSS03"){
			$("#sub5").html("<option>BIOLOGY/CSSS04</option><option>COMPUTER SCIENCE/CSSS05</option><option>ECONOMICS/CSSA01</option>");
			}
		else if(sub4=="CSSS04"){
			$("#sub5").html("<option>ECONOMICS/CSSA01</option><option>COMPUTER SCIENCE/CSSS05</option>");
			}
		else
			$("#sub5").html("");
    });
	
    $("#detsubmit").click(function(e) {
        var sname = $("#sname").val();
		//alert(sname);
		var fname = $("#fname").val();
		//alert(fname);
		var dob = $("#sdob").val();
		//alert(dob);
		//var sprivate = $("#sprivate").val();
		//alert(sprivate);
		var gen = $("#gen").val();
		
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
		var i = sub5.indexOf("/")+1;
		sub5 = sub5.substring(i,sub5.length);
		//alert(sub5);
		
		var year = $("#year").val();
		
		var add = $("#add").val();
		var phno = $("#phno").val();
		//alert(add+phno);
		if(phno.length!=10||add==""||isNaN(phno))
			alert("Wrong Address or Phone Number");
		else{
				
		
		$.ajax({
		url:"studentEntry12sci.php",
		data:{"sname":sname,"fname":fname,"sub1":sub1,"sub2":sub2,"sub3":sub3,"sub4":sub4,"sub5":sub5,"dob":dob,"gen":gen,"phno":phno,"add":add,"year":year},
		success: function(data){
				if(data=="true"){
						alert("entries successfully made!!");
						window.location.href = "studentEntry.php";}
				else if(data=="Check Names"||data=="check Subjects")
						alert("incorect entries!!");
				else{
					//alert(data);
					alert("entries cannot be made!!");
					window.location.href = "studentEntry.php";
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
            <td align="center"><input  placeholder="YYYY-MM-DD" type="date" id="sdob"></td>
        </tr>
        <tr>
        	<td align="center">Gender</td>
            <td align="center"><select id="gen"><option>select</option><option>M</option><option>F</option></select></td>
        </tr>
        <tr>
        	<td align="center">Phone Number</td>
            <td align="center"><input type="text" id="phno"></td>
        </tr>
        <tr>
        	<td align="center">Address</td>
            <td align="center"><input type="text" id="add"></td>
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
							    ?>
                               </select></td>		
        </tr>
        <tr>
        	<td align="center">Subject 1</td>
            <td align="center"><select id="sub1">
            		            <option>ENGLISH(CORE)/CSSA07</option>  
								</select>
            </td>
        </tr>
        <tr>
        	<td align="center">Subject 2</td>
            <td align="center"><select id="sub2">
  								<option>PHYSICS/CSSS02</option> 
								</select>
            </td>
        </tr>
        <tr>
        	<td align="center">Subject 3</td>
            <td align="center"><select id="sub3">
            		               <option>CHEMISTRY/CSSS01</option> 
								</select>
            </td>
        </tr>
        <tr>
        	<td align="center">Subject 4</td>
            <td align="center"><select id="sub4" onChange="choosecat()">
            		               <option>select</option>
                                   <option>MATHEMATICS/CSSS03</option>
                                   <option>BIOLOGY/CSSS04</option>
								</select>
            </td>
        </tr>
        <tr>
        	<td align="center">Subject 5</td>
            <td align="center"><select id="sub5">
            		               <option>select</option> 
								</select>
            </td>
        </tr>
        
        <tr>
        	<td align="center" colspan="2"><button id="detsubmit">Submit</button></td>
        </tr>
    </table>
</body>
</html>