<title>Log - File</title>
<?php
include("connection-sql.php");
$q = "select * from log order by dop desc";
$r = mysqli_query($conn,$q) or die("not yet logined");
echo '<table align="center" border="1">';
	echo '<tr>';
	echo '<th align="center" colspan="5" style="color:#FC0307">'.'Log File'.'</th>';
	echo '</tr>';
	echo '<tr>';
	echo '<th>'.'Name'.'</th>';
	echo '<th>'.'Login Date'.'</th>';
	echo '<th>'.'Changed In Subject'.'</th>';
	echo '<th>'.'For Class'.'</th>';
	echo '<th>'.'Type Of Change'.'</th>';
	echo '</tr>';
while($row = mysqli_fetch_assoc($r)){
	echo "<tr>";
	echo "<td align=\"center\">".$row["name"]."</td>";
	echo "<td align=\"center\">".$row["dop"]."</td>";
	echo "<td align=\"center\">".$row["sub_code"]."</td>";
	echo "<td align=\"center\">".$row["class"]."</td>";
	echo "<td align=\"center\">".$row["type"]."</td>";
	echo "</tr>";
}
echo "</table>";
?>