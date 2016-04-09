<?php
include("connection-sql.php");
//$conn is connection variabl
session_start();
if(!$_SESSION["setpass"])
	header("Location:index.php");
?>
<!doctype html>
<head>
<title>New Password</title>
<link href="css/bootstrap.css" rel="stylesheet">
</head>
<body>
	<div class="col-md-4 col-md-offset-4">
	<form method="post" action="newpass.php">
    	<label>New Password</label>
        <input class="form-control" required name="pass" type="password">
        <br>
        <input type="submit" name="submit" class="btn tf-btn btn-default" value="Login">
    </form>
    </div>
</body>
<?php
if(isset($_POST["submit"])){
	$pass = $_POST["pass"];
	$id = $_SESSION["setpass"];
	$query = "update access set occur=1,password='$pass' where username='$id'";
	$result = mysqli_query($conn,$query);
	session_unset();
	session_destroy();
	mysqli_close($conn);
	header("Location:index.php");
}

?>

</html>
