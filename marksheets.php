<?php
$file = scandir("marksheets");
echo '<table align="center" border="1">';
echo '<tr><th>Download</th></tr>';
$i=0;
foreach($file as $f){
	$i++;
	if($i>2)
		echo '<tr><td><a href=marksheets/'.$f.'>'.$f.'</td></tr>';
}
echo '</table>';
?>