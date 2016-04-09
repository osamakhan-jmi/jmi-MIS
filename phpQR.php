<?php
	//include('connection-sql.php');
//$conn = mysqli_connect("localhost","root","19011996","jmi_school");

function genQR($sid,$string)
{
    include('phpqrcode/qrlib.php');
    //include('config.php');    
    $tempDir = "image/qr/";
    $codeContents = $string;
    $fileName = $sid.'.png';
    
    $pngAbsoluteFilePath = $tempDir.$fileName;
    QRcode::png($codeContents, $pngAbsoluteFilePath);
}
    
    
?>
