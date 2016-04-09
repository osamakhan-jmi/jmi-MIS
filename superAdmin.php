<?php
include("connection-sql.php");
session_start();
if($_SESSION["type"]!="admin")
	header("Location:index.php");

if(isset($_REQUEST["sid"])&&isset($_REQUEST["clss"])&&isset($_REQUEST["str"])){//&&isset($_REQUEST["spr"])){
$sid = $_REQUEST["sid"];
$clss = $_REQUEST["clss"];
$str = $_REQUEST["str"];
//$spr = $_REQUEST["spr"];
if($clss==10){
$q1 = "select roll from student10 where roll='$sid'";
$r1 = mysqli_query($conn,$q1);
if(mysqli_num_rows($r1)>0){
		$_SESSION["sid"] = $sid;
		$_SESSION["class"] = $clss;
		//$_SESSION["spr"] = $spr;
		echo "true";
		exit;}
	else
	{
		//$_SESSION["sid"] = $sid;
		//$_SESSION["class"] = $clss;
		//$_SESSION["str"] = $str;
		//$_SESSION["spr"] = $spr;
		echo "false";
		exit;
	}
}
else if($clss==12){
$q2 = "select roll from student12 where roll='$sid' and stream='$str'";
$r2 = mysqli_query($conn,$q2);
if(mysqli_num_rows($r2)>0)
	{
		$_SESSION["sid"] = $sid;
		$_SESSION["class"] = $clss;
		//$_SESSION["spr"] = $spr;
		echo "true";
		exit;
	}	
	else
	{
		//$_SESSION["sid"] = $sid;
		//$_SESSION["class"] = $clss;
		//$_SESSION["str"] = $str;
		//$_SESSION["spr"] = $spr;
		echo "false";
		exit;
	}
}
else
	{echo "false";
	exit;}
}
?>
<!doctype html>
<html>
<head>
<title>Super Admin</title>
<link href="css/superadmin.css" rel="stylesheet">

<script src="js/jquery.min.js"></script>
<link href="css/table.css" rel="stylesheet">
</head>

<div class="col-md-4 col-md-offset-4"><img src="img/logo.jpg" alt="jamia logo"></div>
<br>
<a style="color:red;" href="details.php" target="_blank">Details</a>
<a href="logs.php" target="_blank">Logs</a>
<a href="logout.php">Logout</a>
<hr>
<h1 align="center">Jamia School - Super Admin</h1>
<hr>
<script src="js/superAdmin.js"></script>
<script>
$(document).ready(function(e) {
    $("#newSession").click(function() {
        var choice = confirm("Start a Fresh session ?");
			if(choice){
				window.open("session_update.php");
			}
    });
});
</script>
<script>
$(document).ready(function() {
     
	 $("#seeMarks").click(function() {
            var roll = $("#seeroll").val();
			var clas =  $("#seeclas").val();
			if(roll==""||clas=="choose")	alert("enter roll number or class");
			else {
				window.open("viewMarks.php?roll="+roll+"&&clas="+clas);
			}
        });
	 
	   
	$("#checksid").click(function() {
        var sid = $("#sid").val();
		var clss = $("#cls").val();
		var str = $("#str").val();
		//var sprivate = $("#sprivate").val();
		if(sid==""||clss=="Class"||str=="Stream")
			alert("check entries");
		else if(clss==10&&str!="None"){
			alert("Check Entries");}
		else{
		$.ajax({
			url:"superAdmin.php",
			data:{"sid":sid,"clss":clss,"str":str},///"spr":sprivate},
			success: function(data)
			{				
				if(data=="true"){
				var choice = confirm("Student ID:"+sid+" exsist\n\nMake A Entry For CR-DI / EX ?");
					if(choice)
							window.location.href = "studentEntryclone.php";
				}
				else
					//alert(data);
					alert("Student Not Found");
			},
			error:function(data){
				alert("ERROR_CONNECTION");
			}
		});}
    });
});

</script>

<body>
        <table align="center" border="1">
    	<tr><th colspan="4" style="color:#FC0307">Marks Alter</th></tr>
        <tr>
        	<th>Student Roll No</th>
            <th>Subject Code</th>
            <th>Type</th>
            <th>Enter</th>
        </tr>
        <tr>
        	<td><input id="sturoll" type="text"></td>
            <td><select id="stucode">
            		<?php
					$q = "select subname,code,private from subject10";
					$r = mysqli_query($conn,$q);
					while($row = mysqli_fetch_assoc($r)){
						if($row["private"]=="NO")
						echo '<option>10th Regular '.$row["subname"].' /'.$row["code"].'</option>';
						if($row["private"]=="YES")
						echo '<option>10th Private '.$row["subname"].' /'.$row["code"].'</option>';
					}
					$q = "select subname,code,private from subject12";
					$r = mysqli_query($conn,$q);
					while($row = mysqli_fetch_assoc($r)){
						if($row["private"]=="NO")
						echo '<option>12th Regular '.$row["subname"].' /'.$row["code"].'</option>';
						if($row["private"]=="YES")
						echo '<option>12th Private '.$row["subname"].' /'.$row["code"].'</option>';
					}
					?>
                </select></td>
            <td><select id="type">
            		<option>choose</option>
                    <option>fa</option>
                    <option>sa1</option>
                    <option>sa2</option>
                    <option>ue</option>
                    <option>ia</option>
                    <option>uep</option>
                    <option>iap</option>
            	</select>
            </td>
            <td><button id="updateMarks">Submit</button></td>
        </tr>
    	</table>
        
        
        <table align="center" border="1">
    	<tr>
        	<th  colspan="4" style="color:#FC0105">Enter CR-DI/EX</th>
        </tr>   
        <tr>
            <td align="center"><input placeholder="Student Roll No" id="sid" type="text"></td>
        
            <td><select id="cls"><option>Class</option><option>10</option><option>12</option></select></td>
			<td><select id="str"><option>Stream</option><option>None</option><option>COMMERCE</option><option>ART</option><option>SCIENCE</option></select></td>  
                         
            <td colspan="2" align="right"><button id="checksid">Check</button></td>
        </tr>
    </table>
          
            <table align="center" border="1">
    	<tr><th align="center" colspan="3" style="color:#FC0307">Generate Marsheet via Roll No</th></tr>
        <tr>
        	<th>Student Roll No</th>
            <th>Class</th>
            <th>Enter</th>
        </tr>
        <tr>
        	<td align="center"><input id="qRoll" type="text"></td>
            <td><select id="qClas">
            		<option>choose</option>
                    <option>10</option>
                    <option>12</option>
            	</select>
            </td>
            <td><button id="genMarks">Submit</button></td>
        </tr>
    	</table>        
        
        
            <table align="center" border="1">
        <tr><th align="center" colspan="2" style="color:#FC0307">Generate Marsheet for Class X</th></tr>
        <tr>
            <th>Category</th>
            <th>Enter</th>
        </tr>
        <tr>
            <td><select id="qStream10">
                    <option value="Choose">Choose</option>
                    <option value="Non-Private">Non-Private</option>
                    <option value="Private">Private</option>
                </select>
            </td>
            <td><button id="gen10Marks">Submit</button></td>
        </tr>
        </table>
        
            <table align="center" border="1">
        <tr><th align="center" colspan="2" style="color:#FC0307">Generate Marsheet for Class XII</th></tr>
        <tr>
            <th>Stream</th>
            <th>Enter</th>
        </tr>
        <tr>
            <td><select id="qStream12">
                    <option value="Choose">Choose</option>
                    <option value="science">Science</option>
                    <option value="arts">Arts</option>
                    <option value="com">Commerce</option>
                    <option value="private">Private</option>
                </select>
            </td>
            <td><button id="gen12Marks">Submit</button></td>
        </tr>
        </table>
	     
        <table align="center" border="1">
    	<tr><th align="center" colspan="3" style="color:#FC0307">See Marks via Roll No</th></tr>
        <tr>
        	<th>Student Roll No</th>
            <th>Class</th>
            <th>Enter</th>
        </tr>
        <tr>
        	<td align="center"><input id="seeroll" type="text"></td>
            <td><select id="seeclas">
            		<option>choose</option>
                    <option>10</option>
                    <option>12</option>
            	</select>
            </td>
            <td><button id="seeMarks">Submit</button></td>
        </tr>
    	</table>  
         
         
         <table align="center" border="1">
        <tr><th align="center" colspan="6" style="color:#FC0307">Generate Tabulation</th></tr>
        <tr>
            <th>Class</th>
            <th>Private</th>
            <th>Stream</th>
            <th>Category</th>
            <th>Type</th>
            <th>Enter</th>
        </tr>
        <tr>
            <td><select id="classTAB">
                    <option value="10">X</option>
                    <option value="12">XII</option>
                </select>
            </td>
            <td><select id="privateTAB">
                    <option value="Regular">Non-Private</option>
                    <option value="Private">Private</option>
                </select>
            </td>
            <td><select id="streamTAB">
                    <option value="choose">None</option>
                    <option value="science">Science</option>
                    <option value="arts">Arts</option>
                    <option value="commerce">Commerce</option>
                </select>
            </td>
            <td><select id="categoryTAB">
                    <option value="regular">Regular</option>
                    <option value="cr-di">CR-DI</option>
                    <option value="ex">EX</option>
                </select>
            </td>
            <td><select id="typeTAB">
                    <option value="Blank">Blank</option>
                    <option value="Filled">Actual</option>
                </select>
            </td>
            <td><button id="genTab">Submit</button></td>
        </tr>
        </table>
        <script>
            $(document).ready(function(){
                $("#genTab").click(function(){
                    var ct = $("#categoryTAB").val();
                    var cat = $("#streamTAB").val();
                    var clas = $("#classTAB").val();
                    var pri = $("#privateTAB").val();
                    var type = $("#typeTAB").val();

                    if(clas == "10" && cat != "choose") 
                        alert("Class 10 cannot have Stream! Choose NONE Stream!");
                    else if(clas == "12" && pri == "Regular" && cat == "choose") 
                        alert("Class 12 Non Private needs stream!!");
                    else if(clas == "12" && pri == "Private" && cat != "choose") 
                        alert("Class 12 Private needs none stream!!");
                    else
                    {
                        var file;
                        if(clas == "10")
                            file = "class10.php";
                        else
                        {
                            if(cat == "science")
                                file = "class12science.php";
                            else if(cat == "arts")
                                file = "class12arts.php";
                            else if(cat == 'choose')
                                file = "class12.php";
                            else
                                file = "class12commerce.php";
                        }
                        if(pri == "Private" && ct == "regular")
                            ct = "PRIVATE";

                        var path = "Tabulation_Sheets/"+type+"/"+pri+"/"+file+"?cat="+ct;
                        window.open(path);
                    }
                });
            });
        </script>
        
         
            
        <table align="center" border="1">
        	<tr>
            	<td align="center"><button id="newSession">New Session</button></td>
            </tr>
        </table>  

</body>
</html>