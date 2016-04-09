<title>Details</title>

<?php
include("connection-sql.php");
$q10 = "select count(distinct sid) as c from class10";
$r10 = mysqli_query($conn,$q10);
$row10 = mysqli_fetch_assoc($r10);
echo '<table align="center" border="1">';
echo '<tr><th>Strength in class 10th</th></tr>';
echo '<tr><td align="center">'.$row10["c"].'</td></tr>';
echo '</table>';
echo '<br><br><br>';
$q12 = "select count(distinct sid) as c,stream from class12 group by stream";
$r12 = mysqli_query($conn,$q12);
echo '<table align="center" border="1">';
echo '<tr><th>Strength</th>';
echo '<th>Stream</td></tr>';
while($row12 = mysqli_fetch_assoc($r12)){
	echo '<tr>';
	echo '<td align="center">'.$row12["c"].'</td>';
	echo '<td align="center">'.$row12["stream"].'</td>';
	echo '</tr>';
}
echo '</table>';
?>