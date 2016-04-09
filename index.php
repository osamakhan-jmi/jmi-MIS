<?php
include("connection-sql.php");
//$conn is connection variabl
session_start();
if($_SESSION){
		session_unset();
		session_destroy();
}
?>
<!doctype html>
<head>
<title>Jamia-School</title>
<link href="css/bootstrap.css" rel="stylesheet">
</head>
<div class="col-md-4 col-md-offset-4"><img src="img/logo.jpg" alt="jamia logo"></div><br><br><br><br><br><br>
<hr>
<h1 align="center">Jamia School</h1>
<hr>
<body>

	<div class="col-md-4 col-md-offset-4">
	<form method="post" action="index.php">
    	<label>Login ID</label>
        <br>
        <input class="form-control" required name="id" type="text">
        <br>
   		<label>Password</label>
        <br>
        <input class="form-control" required name="pass" type="password">
        <br>
        <input type="submit" name="submit" class="btn tf-btn btn-default" value="Login">
    </form>
    </div>
</body>
<?php
if(isset($_POST["submit"])){
	$id = $_POST["id"];
	$pass = $_POST["pass"];
	$query = "select username,password,super,occur from access where username='$id' and password='$pass'";
	$result = mysqli_query($conn,$query);
	$row = mysqli_fetch_assoc($result);
	if($row["username"]==$id&&$row["occur"]==0){
		$_SESSION["setpass"]=$id;
		header("Location:newpass.php");
		}
	
	if($row["username"]==$id&&$row["password"]==$pass&&$row["super"]=="YES"&&$row["occur"]==1){
		if($_SESSION){
			session_unset();
			}
		$_SESSION["aid"] = $id;
		$_SESSION["type"] = "admin";
		header("Location:superAdmin.php");
	}
	else if($row["username"]==$id&&$row["password"]==$pass&&$row["super"]=="NO"&&$row["occur"]==1){
		if($_SESSION){
			session_unset();
			}
		$_SESSION["id"] = $id;
		$_SESSION["type"] = "normal";
		header("Location:entry.php");
	}
	else
		echo '<script>alert("wrong id or password")</script>';
	
	mysqli_close($conn);
}

?>

</html>
