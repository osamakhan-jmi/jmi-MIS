<?php

session_start();
if($_SESSION["type"]!="admin")
	header("Location:index.php");

function marks($value)
{
	return ($value<0)?0:$value;
}

function ret($val,$valm)
{
	if($valm == 0) return ".";
	else if($val < 0) return "Ab";
	else return $val;
}


$cls = $_GET["clas"];
$roll = $_GET["roll"];
$tempQR = "image/qr/";
require('fpdf/fpdf.php');
include("connection-sql.php");

$q = "select private, year from student$cls where roll = '$roll' group by sid";
$result = mysqli_query($conn,$q);
if(mysqli_num_rows($result) == 0)
{
		echo "This Roll No does not exist in Class $cls yet!!";
}
else
{
	$result = mysqli_fetch_array($result,MYSQL_ASSOC);
	if($result['year'] >= date('Y'))
	{
		echo "This Student is currently under processing. Thus the marksheet cannot be generated!!";
	}
	else if(strcasecmp($result['private'],"no") == 0)
		printMarksheet($cls,$roll,'student$cls');
	else if(strcasecmp($result['private'],"yes") == 0)
		printMarksheetPrivate($cls,$roll,'student$cls');
}
	

/*--------------------------------------------------------------------------------------------------------;
				Private Marksheets for both Class X and XII
----------------------------------------------------------------------------------------------------------*/
function printMarksheetPrivate($cls,$roll,$table)
{
include('connection-sql.php');
if($cls == "10")
{
//require('fpdf/fpdf.php');
//$conn = mysqli_connect("localhost","root","19011996","jmi_school");


function cat($c,$p)
{
	if($c == "PRIVATE")
		return "PRIVATE";
	else
		return $c."-PRIVATE";
}

function remarks($ia,$ue,$iap,$uep,$iam,$uem,$iapm,$uepm,$max,$code)
{
	$length = count($code);
	$countia = 0;
	$pos = array();
	$gt = 0;
	$gtm = 0;
	for($i=0;$i<$length; $i++)
	{
		$tot = marks($ue[$i]);
		$totm = $iam[$i] + $uem[$i] + $iapm[$i] + $uepm[$i];
		if($uem[$i] > 0)
		{
			$v = ($ue[$i]*100)/$uem[$i];
			if($v < 33)
			{
				$countia++;
				array_push($pos,$i);
				continue;
			}
		}
		if($totm > 0)
		{
			$v = ($tot*100)/$totm;
			if($v < 33)
			{
				$countia++;
				array_push($pos,$i);
			}
		}
		$gt += $tot;
		$gtm += $totm;
	}
	if($countia <= 2)
	{
		$result = "COMPTT in ";
		for($i=0;$i<count($pos);$i++)
			$result .= $code[$pos[$i]]." ";
		return $result;
	}
	else if($countia > 2)
	{
		return "FAILED";
	}
	else
	{
		$percent = round(($gt * 100)/$gtm);
		if($percent >= 75)
			return "PASSED FIRST DIVISION WITH DISTN";
		else if($percent >=60 && $percent < 75)
			return "PASSED FIRST DIVISION";
		else if($percent >=48 && $percent < 60)
			return "PASSED SECOND DIVISION";
		else if($percent >=33 && $percent < 48)
			return "PASSED THIRD DIVISION";
	}

}


$pdf = new FPDF();
$table = "student$cls";
$q = "select distinct sid, sname, fname, roll, enroll, dob, cat, year from $table where private='yes'";
$retval = mysqli_query($conn,$q);
while($row = mysqli_fetch_array($retval,MYSQL_ASSOC))
{
	$s = "select s.subname as name, s.code as code, c.ia as ia, c.ue as ue, c.uep as uep, c.iap as iap, s.ia as iam, s.ue as uem, s.uep as uepm, s.iap as iapm from subject10 s,$table c where s.code = c.subject_code and c.private = s.private and s.private = 'yes' and c.sid = ".$row['sid'];//." and c.total > 0";
	$retval2 = mysqli_query($conn,$s);
	$code = array();
	$subname = array();
	$ia = array();$iam = array();
	$ue = array();$uem = array();
	$iap = array();$iapm = array();
	$uep = array();$uepm = array();
	$c = 0;
	while($row2 = mysqli_fetch_array($retval2,MYSQL_ASSOC))
	{
		array_push($code, $row2['code']);
		array_push($subname, $row2['name']);
		array_push($ia, $row2['ia']);
		array_push($iap, $row2['iap']);
		array_push($iam, $row2['iam']);
		array_push($iapm, $row2['iapm']);
		array_push($ue, $row2['ue']);
		array_push($uep, $row2['uep']);
		array_push($uem, $row2['uem']);
		array_push($uepm, $row2['uepm']);
		$c++;
	}
	$dob = date("d-m-Y",strtotime($row['dob']));
	$str = explode("-",$dob);

	include_once("testdate.php");

	$dinw = strtoupper(day($str[0]).month($str[1]).year($str[2]));

$PATH = "image/student/".$row['sid'].".JPG";
$pdf->AddPage();
$pdf->SetFont('Arial','B',12);
$pdf->Cell(0,8,"",0,1,'C');
$pdf->Ln();
$pdf->Cell(0,5,"SECONDARY SCHOOL CERTIFICATE (CLASS - X) EXAMINATION ".$row['year'],0,1,'C');
$pdf->SetFont('Arial','',11);
$pdf->Ln();
$pdf->Cell(40,5,"",0,1);
$pdf->Cell(40,5,"NAME");$pdf->Cell(40,5,strtoupper($row['sname']));$pdf->Image($PATH,$pdf->GetX()+70,$pdf->GetY(),32,32);$pdf->Cell(40,2,"",0,1);
$pdf->Cell(40,5,"",0,1);
$pdf->Cell(40,5,"FATHER'S NAME");$pdf->Cell(40,5,strtoupper($row['fname']),0,1);$pdf->Cell(40,2,"",0,1);
$pdf->Cell(40,5,"ROLL NO");$pdf->Cell(40,5,$row['roll'],0,1);$pdf->Cell(40,2,"",0,1);
$pdf->Cell(40,5,"ENROLLMENT NO");$pdf->Cell(40,5,$row['enroll'],0,1);$pdf->Cell(40,2,"",0,1);
$pdf->Cell(40,5,"CATEGORY");$pdf->Cell(40,5,cat($row['cat'],'yes'),0,1);$pdf->Cell(40,2,"",0,1);
$pdf->Cell(40,5,"DATE OF BIRTH");$pdf->Cell(40,5,$dob,0,1);$pdf->Cell(40,2,"",0,1);
$pdf->Cell(40,5,"");$pdf->Cell(40,5,$dinw,0,1);$pdf->Cell(40,2,"",0,1);
$pdf->Ln();
$w1=25;$w2=66;$w7=20;$w3=20;$w4=20;$w5=20;$w6=20;$w8=10;$w9=10;
$h1=$h2=$h3=$h4=$h5=$h6=$h7=5;

$pdf->Line(10,$pdf->GetY(),200,$pdf->GetY());
$pdf->Ln();
$pdf->Cell($w1,$h1,"CODE");$pdf->Cell($w2,$h2,"SUBJECT(S)/PAPER(S)");$pdf->Cell($w7,$h7,"Marks-UE"," ",0,"C");$pdf->Cell($w3,$h3,"Marks-IA","",0,"C");
$pdf->Cell($w4,$h4,"Marks-UEP"," ",0,"C");$pdf->Cell($w5,$h5,"Marks-IAP"," ",0,"C");$pdf->Cell($w6,$h6,"TOTAL"," ",0,"C");
$pdf->Ln();
$pdf->Cell($w1,$h1,"");$pdf->Cell($w2,$h2,"");
$pdf->Cell($w8,$h7,"MO"," ",0,"C");$pdf->Cell($w8,$h7,"MM"," ",0,"C");
$pdf->Cell($w8,$h7,"MO"," ",0,"C");$pdf->Cell($w8,$h7,"MM"," ",0,"C");
$pdf->Cell($w8,$h7,"MO"," ",0,"C");$pdf->Cell($w8,$h7,"MM"," ",0,"C");
$pdf->Cell($w8,$h7,"MO"," ",0,"C");$pdf->Cell($w8,$h7,"MM"," ",0,"C");
$pdf->Cell($w9,$h7,"MO"," ",0,"C");$pdf->Cell($w9,$h7,"MM"," ",0,"C");
$pdf->Ln();
$pdf->Cell(0,1,"",0,1);
$pdf->Cell(0,1,"",0,1);
$pdf->Line(10,$pdf->GetY(),200,$pdf->GetY());
$pdf->Cell(0,2,"",0,1);

$pdf->SetFont('Arial','',10);
$max = 0;
for($i=0; $i<$c;$i++)
{
	$pdf->Ln();
	$pdf->Cell($w1,$h1,$code[$i]);$pdf->Cell($w2,$h2,$subname[$i]);
	$pdf->Cell($w8,$h1,ret($ue[$i],$uem[$i]),0,0,"C");$pdf->Cell($w8,$h1,ret($uem[$i],$uem[$i]),0,0,"C");
	$pdf->Cell($w8,$h1,ret($ia[$i],$iam[$i]),0,0,"C");$pdf->Cell($w8,$h1,ret($iam[$i],$iam[$i]),0,0,"C");
	$pdf->Cell($w8,$h1,ret($uep[$i],$uepm[$i]),0,0,"C");$pdf->Cell($w8,$h1,ret($uepm[$i],$uepm[$i]),0,0,"C");
	$pdf->Cell($w8,$h1,ret($iap[$i],$iapm[$i]),0,0,"C");$pdf->Cell($w8,$h1,ret($iapm[$i],$iapm[$i]),0,0,"C");
	$total = marks($ue[$i]);
	$max += $total;
	$pdf->Cell($w8,$h1,$total,0,0,"C");$pdf->Cell($w8,$h1,"100",0,0,"C");
	$pdf->Ln();
}
if($c < 7)
{
	for($i=0;$i<7-$c+1;$i++)
	{
		$sc = "";		$sn = "";		$fa = "";		$sa = "";		$tot = "";		$gp = "";
		$pdf->Ln();
		$pdf->Cell($w1,$h1,$sc);$pdf->Cell($w2,$h2,$sn);$pdf->Cell($w3,$h3,$fa,0,0,"C");
		$pdf->Cell($w4,$h4,$sa,0,0,"C");$pdf->Cell($w5,$h5,$tot,0,0,"C");$pdf->Cell($w6,$h6,$gp,0,0,"C");
		$pdf->Ln();
	}
}
$pdf->SetFont('Arial','',11);
$pdf->Ln();$pdf->Ln();$pdf->Ln();
$pdf->Ln();
$pdf->Cell(0,1,"",0,1);
$pdf->Ln();
$pdf->Ln();
$pdf->Ln();
$pdf->Line(10,$pdf->GetY(),200,$pdf->GetY());
$pdf->Cell(0,2,"",0,1);
$pdf->Cell(175,5,"TOTAL    $max / 700",0,1,'R');
$qr = "Roll No: ".$row['roll']." | Enrollment: ".$row['enroll']." | Name: ".$row['sname']." | Father's name : ".$row['fname']." | DOB: ".$row['dob']." | Total Marks: $max / 700";
include('phpQR.php');
genQR($row['sid'],$qr);
$QR ="image/qr/" . $row['sid'].'.png';
$pdf->Cell(0,2,"",0,1);
$pdf->Line(10,$pdf->GetY(),200,$pdf->GetY());
$pdf->Cell(0,3,"",0,1);
$pdf->Cell(0,5,"RESULT / REMARKS    ".remarks($ia,$ue,$iap,$uep,$iam,$uem,$iapm,$uepm,$max,$code),0,1);
$pdf->Cell(0,3,"",0,1);
$pdf->Line(10,$pdf->GetY(),200,$pdf->GetY());
$pdf->Ln();
$pdf->Cell(0,15,"","",1);
$pdf->Cell(60,5,"M Ansari","",1,"C");
$pdf->Cell(60,5,"PREPARED BY","",0,'C');
$pdf->Cell(50,5,"CHECKED BY","",0,'C');
$pdf->Cell(80,5,"ASSTT. CONTROLLER OF EXAMINATIONS","",1,'C');
$pdf->Cell(0,4,"","",1);
$pdf->Cell(6,12);
$pdf->Image($QR,$pdf->GetX()+10,$pdf->GetY(),20,20);
$pdf->Cell(170,12,"","",1);
$pdf->Cell(0,4,"","",1);
$pdf->Cell(116,5,"Date of Result  ".date("d-m-Y"),"",0,'R');
$pdf->Cell(50,5,"  Date of Issue  ","",0,'C');

}
$date = date("m-Y");
$pdf->Output();
//$pdf->Output("marksheets/Marksheet-X-PRIVATE-$dob.pdf","F");
	}
















	else if($cls == "12")
	{

$QR = "image/qr.png";
//require('fpdf/fpdf.php');
//$conn = mysqli_connect("localhost","root","19011996","jmi_school");

$pdf = new FPDF();
/*----------------------ASSIGNMENT---------------------------*/
function cat($c)
{
	if($c == "PRIVATE")
		return $c;
	else if($c == "CR-DI")
		return $c."-PRIVATE";
	else if($c == "EX")
		return $c."-PRIVATE";
}	
function remarks($ia,$ue,$iap,$uep,$iam,$uem,$iapm,$uepm,$max,$code)
{
	$length = count($code);
	//check for practical
		//check in ia
		//check in ue
	//check for theory
		//check in ia
		//check in ue
	//check max

	//output value
	$countia = 0;
	$pos = 0;
	$gt = 0;
	$gtm = 0;
	for($i=0;$i<$length; $i++)
	{
		if($iapm[$i] > 0)
		{
			$v = round(($iap[$i]*100)/$iapm[$i]);
			if($v < 33)
				return "FAILED";
		}

		if($uepm[$i] > 0)
		{
			$v = round(($uep[$i]*100)/$uepm[$i]);
			if($v < 33)
				return "FAILED";
		}
		if($uem[$i] > 0)
		{
			$v = round(($ue[$i]*100)/$uem[$i]);
			if($v < 33)
			{
				$countia++;
				$pos = $i;
				continue;
			}
		}
		if($iam[$i] > 0)
		{
			$v = round(($ia[$i]*100)/$iam[$i]);
			if($v < 33)
			{
				$countia++;
				$pos = $i;
				continue;
			}
		}
		$tot = marks($ia[$i]) + marks($ue[$i]) + marks($iap[$i]) + marks($uep[$i]);
		$totm = $iam[$i] + $uem[$i] + $iapm[$i] + $uepm[$i];
		if($totm > 0)
		{
			$v = round(($tot*100)/$totm);
			if($v < 33)
			{
				$countia++;
				$pos = $i;
			}
		}
		$gt += $tot;
		$gtm += $totm;
	}
	if($countia == 1)
	{
		return "COMPTT in ".$code[$pos];
	}
	else if($countia > 1)
	{
		return "FAILED";
	}
	else
	{
		$percent = round(($gt * 100)/$gtm);
		if($percent >= 75)
			return "PASSED FIRST DIVISION WITH DISTN";
		else if($percent >=60 && $percent < 75)
			return "PASSED FIRST DIVISION";
		else if($percent >=48 && $percent < 60)
			return "PASSED SECOND DIVISION";
		else if($percent >=33 && $percent < 48)
			return "PASSED THIRD DIVISION";
	}

}
$table = "student$cls";
$q = "select distinct sid, sname, fname, roll, enroll, cat, dob, year from $table where roll='$roll'";
$retval = mysqli_query($conn,$q);
while($row = mysqli_fetch_array($retval,MYSQL_ASSOC))
{
	$s = "select s.subname as name, s.code as code, c.ia as ia, c.ue as ue, c.uep as uep, c.iap as iap, s.ia as iam, s.ue as uem, s.uep as uepm, s.iap as iapm from $table c, subject12 s where s.code = c.subject_code and s.private = c.private and c.sid = ".$row['sid']." and c.private = 'yes'";//" and c.total > 0";
	$retval2 = mysqli_query($conn,$s);
	$code = array();
	$subname = array();
	$ia = array();$iam = array();
	$ue = array();$uem = array();
	$iap = array();$iapm = array();
	$uep = array();$uepm = array();
	$c = 0;
	while($row2 = mysqli_fetch_array($retval2,MYSQL_ASSOC))
	{
		array_push($code, $row2['code']);
		array_push($subname, $row2['name']);
		array_push($ia, $row2['ia']);
		array_push($iap, $row2['iap']);
		array_push($iam, $row2['iam']);
		array_push($iapm, $row2['iapm']);
		array_push($ue, $row2['ue']);
		array_push($uep, $row2['uep']);
		array_push($uem, $row2['uem']);
		array_push($uepm, $row2['uepm']);
		$c++;
	}
	$PATH = "image/student/".$row['sid'].".JPG";

/*----------------------------------------------------------*/
$pdf->AddPage();
$pdf->SetFont('Arial','B',12);
$pdf->Cell(0,8,"",0,1,'C');
$pdf->Ln();
$pdf->Cell(0,5,"SENIOR SCHOOL CERTIFICATE (CLASS - XII) EXAMINATION ".$row['year'],0,1,'C');
$pdf->SetFont('Arial','',11);
$pdf->Ln();
$pdf->Cell(40,5,"",0,1);
$pdf->Cell(40,5,"NAME");$pdf->Cell(40,5,strtoupper($row['sname']));$pdf->Image($PATH,$pdf->GetX()+70,$pdf->GetY(),32,32);$pdf->Cell(40,2,"",0,1);
$pdf->Cell(40,5,"",0,1);
$pdf->Cell(40,5,"FATHER'S NAME");$pdf->Cell(40,5,strtoupper($row['fname']),0,1);$pdf->Cell(40,2,"",0,1);
$pdf->Cell(40,5,"ROLL NO");$pdf->Cell(40,5,strtoupper($row['roll']),0,1);$pdf->Cell(40,2,"",0,1);
$pdf->Cell(40,5,"ENROLLMENT NO");$pdf->Cell(40,5,strtoupper($row['enroll']),0,1);$pdf->Cell(40,2,"",0,1);
$pdf->Cell(40,5,"CATEGORY");$pdf->Cell(40,5,strtoupper(cat($row['cat'])),0,1);$pdf->Cell(40,2,"",0,1);
//$pdf->Cell(40,5,"DATE OF BIRTH");$pdf->Cell(40,5,"iksa",0,1);$pdf->Cell(40,2,"",0,1);
//$pdf->Cell(40,5,"");$pdf->Cell(40,5,"iksa",0,1);$pdf->Cell(40,2,"",0,1);
$pdf->Ln();
$w1=25;$w2=66;$w7=20;$w3=20;$w4=20;$w5=20;$w6=20;$w8=10;$w9=10;
$h1=$h2=$h3=$h4=$h5=$h6=$h7=5;

$pdf->Line(10,$pdf->GetY(),200,$pdf->GetY());
$pdf->Ln();
$pdf->Cell($w1,$h1,"CODE");$pdf->Cell($w2,$h2,"SUBJECT(S)/PAPER(S)");$pdf->Cell($w7,$h7,"Marks-UE"," ",0,"C");$pdf->Cell($w3,$h3,"Marks-IA","",0,"C");
$pdf->Cell($w4,$h4,"Marks-UEP"," ",0,"C");$pdf->Cell($w5,$h5,"Marks-IAP"," ",0,"C");$pdf->Cell($w6,$h6,"TOTAL"," ",0,"C");
$pdf->Ln();
$pdf->Cell($w1,$h1,"");$pdf->Cell($w2,$h2,"");
$pdf->Cell($w8,$h7,"MO"," ",0,"C");$pdf->Cell($w8,$h7,"MM"," ",0,"C");
$pdf->Cell($w8,$h7,"MO"," ",0,"C");$pdf->Cell($w8,$h7,"MM"," ",0,"C");
$pdf->Cell($w8,$h7,"MO"," ",0,"C");$pdf->Cell($w8,$h7,"MM"," ",0,"C");
$pdf->Cell($w8,$h7,"MO"," ",0,"C");$pdf->Cell($w8,$h7,"MM"," ",0,"C");
$pdf->Cell($w9,$h7,"MO"," ",0,"C");$pdf->Cell($w9,$h7,"MM"," ",0,"C");
$pdf->Ln();
$pdf->Cell(0,1,"",0,1);
$pdf->Cell(0,1,"",0,1);
$pdf->Line(10,$pdf->GetY(),200,$pdf->GetY());
$pdf->Cell(0,2,"",0,1);
//$c = 5;
$pdf->SetFont('Arial','',10);

$max = 0;
$maxm = 0;
for($i=0; $i<$c;$i++)
{
	$pdf->Ln();
	$pdf->Cell($w1,$h1,$code[$i]);$pdf->Cell($w2,$h2,$subname[$i]);
	$pdf->Cell($w8,$h1,ret($ue[$i],$uem[$i]),0,0,"C");$pdf->Cell($w8,$h1,ret($uem[$i],$uem[$i]),0,0,"C");
	$pdf->Cell($w8,$h1,ret($ia[$i],$iam[$i]),0,0,"C");$pdf->Cell($w8,$h1,ret($iam[$i],$iam[$i]),0,0,"C");
	$pdf->Cell($w8,$h1,ret($uep[$i],$uepm[$i]),0,0,"C");$pdf->Cell($w8,$h1,ret($uepm[$i],$uepm[$i]),0,0,"C");
	$pdf->Cell($w8,$h1,ret($iap[$i],$iapm[$i]),0,0,"C");$pdf->Cell($w8,$h1,ret($iapm[$i],$iapm[$i]),0,0,"C");
	$total = marks($ue[$i])+marks($ia[$i])+marks($iap[$i])+marks($uep[$i]);
	$max += $total;
	$maxm += 100;
	$pdf->Cell($w8,$h1,$total,0,0,"C");$pdf->Cell($w8,$h1,"100",0,0,"C");
	$pdf->Ln();
}
if($c < 7)
{
	for($i=0;$i<7-$c +1;$i++)
	{
		$sc = "";		$sn = "";		$fa = "";		$sa = "";		$tot = "";		$gp = "";
		$pdf->Ln();
		$pdf->Cell($w1,$h1,$sc);$pdf->Cell($w2,$h2,$sn);$pdf->Cell($w3,$h3,$fa,0,0,"C");
		$pdf->Cell($w4,$h4,$sa,0,0,"C");$pdf->Cell($w5,$h5,$tot,0,0,"C");$pdf->Cell($w6,$h6,$gp,0,0,"C");
		$pdf->Ln();
	}
}
$pdf->SetFont('Arial','',11);
$pdf->Ln();$pdf->Ln();$pdf->Ln();
$pdf->Ln();
$pdf->Cell(0,1,"",0,1);
$pdf->Ln();
$pdf->Ln();
$pdf->Ln();
$pdf->Line(10,$pdf->GetY(),200,$pdf->GetY());
$pdf->Cell(0,2,"",0,1);
$pdf->Cell(175,5,"TOTAL    $max / $maxm",0,1,'R');
$qr = "Roll No: ".$row['roll']." | Enrollment: ".$row['enroll']." | Name: ".$row['sname']." | Father's name : ".$row['fname']." | DOB: ".$row['dob']." | Total Marks: $max / $maxm";
include('phpQR.php');
genQR($row['sid'],$qr);
$QR ="image/qr/" . $row['sid'].'.png';
$pdf->Cell(0,2,"",0,1);
$pdf->Line(10,$pdf->GetY(),200,$pdf->GetY());
$pdf->Cell(0,3,"",0,1);
$pdf->Cell(0,5,"RESULT / REMARKS    ".remarks($ia,$ue,$iap,$uep,$iam,$uem,$iapm,$uepm,$max,$code),0,1);
$pdf->Cell(0,3,"",0,1);
$pdf->Line(10,$pdf->GetY(),200,$pdf->GetY());
$pdf->Ln();
$pdf->Cell(0,15,"","",1);
$pdf->Cell(60,5,"M Ansari","",1,"C");
$pdf->Cell(60,5,"PREPARED BY","",0,'C');
$pdf->Cell(50,5,"CHECKED BY","",0,'C');
$pdf->Cell(80,5,"ASSTT. CONTROLLER OF EXAMINATIONS","",1,'C');
$pdf->Cell(0,4,"","",1);
$pdf->Cell(6,12);
$pdf->Image($QR,$pdf->GetX()+10,$pdf->GetY(),20,20);
$pdf->Cell(170,12,"","",1);
$pdf->Cell(0,4,"","",1);
$pdf->Cell(116,5,"Date of Result  ".date("d-m-Y"),"",0,'R');
$pdf->Cell(50,5,"  Date of Issue  ","",0,'C');

}
$pdf->Output();
//$pdf->Output("Marksheet-XII-PRIVATE-".date("d-m-Y").".pdf","F");


	}
}
/*---------------------------------------/*---------------------------------------/*---------------------------------------

					Regular marksheets for Class X and XII

/*---------------------------------------/*---------------------------------------/*---------------------------------------*/







function printMarksheet($cls,$roll,$table)
{
include('connection-sql.php');	
if($cls == "10")
{
	
		
		$QR = "image/qr.png";
//require('fpdf/fpdf.php');
//include('conneection-sql.php');

	function grade($val, $max)
		{
			$val =($val/$max);
			$val *= 100;
			$val = round($val);
			if($val > 90 )
				return  'A1';
			else if($val > 80 && $val <= 90)
				return 'A2';
			else if($val >70 && $val<=80)
				return 'B1';
			else if($val >60 && $val<=70)
				return 'B2';
			else if($val >50 && $val<=60)
				return 'C1';
			else if($val >40 && $val<=50)
				return 'C2';
			else if($val >32 && $val<=40)
				return 'D';
			else if($val >21 && $val<=32)
				return 'E1';
			else
				return 'E2';
		}

	function gp($grade)
	{
	switch($grade)
		{
			case "A1": return 10;
			case "A2": return 9;
			case "B1": return 8;
			case "B2": return 7;
			case "C1": return 6;
			case "C2": return 5;
			case "D": return 4;
			case "E1": return 0;
			case "E2": return 0;
		}
	}

	function remarks($fa,$sa1,$sa2,$code)
	{
		$position = array();
		$length = count($code);
		$flag = 0;
		// 15 = 25% of SA1 + SA2 Maximum Marks
		for($i=0;$i<$length; $i++)
			if((($sa1[$i] + $sa1[$i]) < 15) || (($sa1[$i] + $sa2[$i] + $fa[$i]) < 33))
			{
				$flag++;
				array_push($position, $i);
			}
		if($flag == 0)
			return "PASSED";
		else if($flag > 3)
			return "NEIOP";
		else
		{
			$res = "EIOP";
			for($i=0;$i<count($position);$i++)
				$res = $res . " ".$code[$position[$i]]." (TM TT)  ";

			return $res;
		}
	}

		$pdf = new FPDF();
		$table = "student$cls";
		$q = "select distinct sid, sname, fname, roll, enroll, dob, cat, year from $table where roll = '$roll'";
		$retval = mysqli_query($conn,$q);
		while($row = mysqli_fetch_array($retval,MYSQL_ASSOC))
		{
			$s = "select s.subname as name, s.code as code, c.ia as ia, c.ue as ue, c.uep as uep, c.iap as iap, s.ia as iam, s.ue as uem, s.uep as uepm, s.iap as iapm from subject10 s,$table c where s.code = c.subject_code and c.private = s.private and s.private = 'no' and c.sid = ".$row['sid'];//." and c.total > 0";
			//echo "<br>".$s;
			$retval2 = mysqli_query($conn,$s);
			$code = array();
			$subname = array();
			$fa = array();$fam = array();
			$sa = array();$sam = array();
			$sa1 = array(); $sa2 = array();
			$total = array();$totm = array();
			$c = 0;
			while($row2 = mysqli_fetch_array($retval2,MYSQL_ASSOC))
			{
				array_push($code, $row2['code']);
				array_push($subname, $row2['name']);
				array_push($fa, marks($row2['uep']));
				array_push($sa, marks($row2['ia']) + marks($row2['ue']));
				array_push($sa1, marks($row2['ue']));
				array_push($sa2, marks($row2['ia']));
				array_push($fam, $row2['uepm']);
				array_push($sam, $row2['iam'] + 0 + $row2['uem'] + 0);
				array_push($total, $fa[$c] + $sa[$c]);
				array_push($totm, $fam[$c]+$sam[$c]);
				$c++;
			}
			
			$dob = date("d-m-Y",strtotime($row['dob']));
			$str = explode("-",$dob);

			include_once("testdate.php");

			$dinw = strtoupper(day($str[0]).month($str[1]).year($str[2]));
			
			$PATH = "image/student/".$row['sid'].".JPG";
			$pdf->AddPage();
			$pdf->SetFont('Arial','B',12);
			$pdf->Cell(0,8,"",0,1,'C');
			$pdf->Ln();
			$pdf->Cell(0,5,"SECONDARY SCHOOL CERTIFICATE (CLASS - X) EXAMINATION ".$row['year'],0,1,'C');
			$pdf->SetFont('Arial','',11);
			$pdf->Ln();
			$pdf->Cell(40,5,"",0,1);
			$pdf->Cell(40,5,"NAME");$pdf->Cell(40,5,strtoupper($row['sname']));$pdf->Image($PATH,$pdf->GetX()+70,$pdf->GetY(),32,32);$pdf->Cell(40,2,"",0,1);
			$pdf->Cell(40,5,"",0,1);
			$pdf->Cell(40,5,"FATHER'S NAME");$pdf->Cell(40,5,strtoupper($row['fname']),0,1);$pdf->Cell(40,2,"",0,1);
			$pdf->Cell(40,5,"ROLL NO");$pdf->Cell(40,5,$row['roll'],0,1);$pdf->Cell(40,2,"",0,1);
			$pdf->Cell(40,5,"ENROLLMENT NO");$pdf->Cell(40,5,$row['enroll'],0,1);$pdf->Cell(40,2,"",0,1);
			$pdf->Cell(40,5,"CATEGORY");$pdf->Cell(40,5,strtoupper($row['cat']),0,1);$pdf->Cell(40,2,"",0,1);
			$pdf->Cell(40,5,"DATE OF BIRTH");$pdf->Cell(40,5,$dob,0,1);$pdf->Cell(40,2,"",0,1);
			$pdf->Cell(40,5,"");$pdf->Cell(40,5,$dinw,0,1);$pdf->Cell(40,2,"",0,1);
			$pdf->Ln();
			$w1=30;$w2=77;$w3=17;$w4=17;$w5=29;$w6=17;
			$h1=$h2=$h3=$h4=$h5=$h6=5;

			$pdf->Line(10,$pdf->GetY(),200,$pdf->GetY());
			$pdf->Ln();
			$pdf->Cell($w1,$h1,"CODE");$pdf->Cell($w2,$h2,"SUBJECT(S)/PAPER(S)");$pdf->Cell($w3,$h3,"FA","",0,"C");
			$pdf->Cell($w4,$h4,"SA","",0,"C");$pdf->Cell($w5,$h5,"TOTAL","",0,"C");$pdf->Cell($w6,$h6,"GP","",0,"C");
			$pdf->Ln();
			$pdf->Cell(0,1,"",0,1);
			$pdf->Cell(0,1,"",0,1);
			$pdf->Line(10,$pdf->GetY(),200,$pdf->GetY());
			$pdf->Cell(0,2,"",0,1);
			$sum_gp=0;
			for($i=0; $i<$c;$i++)
			{
				$grade_fa = grade($fa[$i],$fam[$i]);
				$grade_sa = grade($sa[$i],$sam[$i]);
				$grade_tot = grade($total[$i],$totm[$i]);
				$gp = gp($grade_tot);
				$sum_gp += $gp;
				$pdf->Ln();
				$pdf->Cell($w1,$h1,$code[$i]);$pdf->Cell($w2,$h2,$subname[$i]);$pdf->Cell($w3,$h3,$grade_fa,0,0,"C");
				$pdf->Cell($w4,$h4,$grade_sa,0,0,"C");$pdf->Cell($w5,$h5,$grade_tot,0,0,"C");$pdf->Cell($w6,$h6,$gp,0,0,"C");
				$pdf->Ln();
			}

			for($i=0;$i<1;$i++)
			{
				$sc = "";		$sn = "";		$fa = "";		$sa = "";		$tot = "";		$gp = "";
		//$pdf->Ln();
		/*$pdf->Cell($w1,$h1,$sc);$pdf->Cell($w2,$h2,$sn);$pdf->Cell($w3,$h3,$fa,0,0,"C");
		$pdf->Cell($w4,$h4,$sa,0,0,"C");$pdf->Cell($w5,$h5,$tot,0,0,"C");$pdf->Cell($w6,$h6,$gp,0,0,"C");*/
				$pdf->Ln();
			}
			$sum_gp = round($sum_gp/7,2);

			
			$pdf->Ln();$pdf->Ln();$pdf->Ln();
			$pdf->Ln();
			$pdf->Cell(0,1,"",0,1);
			$pdf->Ln();
			$pdf->Ln();
			$pdf->Ln();
			$pdf->Line(10,$pdf->GetY(),200,$pdf->GetY());
			$pdf->Cell(0,2,"",0,1);
			$pdf->Cell(175,5,"CGPA    $sum_gp",0,1,'R');
			$qr = "Roll No: ".$row['roll']." | Enrollment: ".$row['enroll']." | Name: ".$row['sname']." | Father's name : ".$row['fname']." | DOB: ".$row['dob']." | Total Marks: $sum_gp";
include('phpQR.php');
genQR($row['sid'],$qr);
$QR ="image/qr/" . $row['sid'].'.png';
			$pdf->Cell(0,2,"",0,1);
			$pdf->Line(10,$pdf->GetY(),200,$pdf->GetY());
			$pdf->Cell(0,3,"",0,1);
			$pdf->Cell(50,5,"RESULT / REMARKS    ");$pdf->SetFont('Arial','',9);$pdf->Multicell(120,5,remarks($fa,$sa1,$sa2,$code),0,1);$pdf->SetFont('Arial','',11);
			$pdf->Cell(0,3,"",0,1);
			$pdf->Line(10,$pdf->GetY(),200,$pdf->GetY());
			$pdf->Ln();
			$pdf->Cell(0,10,"","",1);
			$pdf->Cell(60,5,"M Ansari","",1,"C");
			$pdf->Cell(60,5,"PREPARED BY","",0,'C');
			$pdf->Cell(50,5,"CHECKED BY","",0,'C');
			$pdf->Cell(80,5,"ASSTT. CONTROLLER OF EXAMINATIONS","",1,'C');
			$pdf->Cell(0,4,"","",1);
			$pdf->Cell(6,12);
			$pdf->Image($QR,$pdf->GetX()+10,$pdf->GetY(),20,20);
			$pdf->Cell(170,12,"","",1);
			$pdf->Cell(0,4,"","",1);
			$pdf->Cell(116,5,"Date of Result  ".date("d-m-Y"),"",0,'R');
			$pdf->Cell(50,5,"  Date of Issue  ","",0,'C');
//------------------------------------------------------------------]
			
		}
		$date = date("m-Y");
		$pdf->Output();
		//$pdf->Output("marksheets/Marksheet-X-$dob.pdf","F");
}














else if($cls == "12")
{
		
	$QR = "image/qr.png";
//require('fpdf/fpdf.php');
//	include("connection-sql.php");

	$pdf = new FPDF();
/*----------------------ASSIGNMENT---------------------------*/

function remarks($ia,$ue,$iap,$uep,$iam,$uem,$iapm,$uepm,$max,$code)
{
	$length = count($code);
	//check for practical
		//check in ia
		//check in ue
	//check for theory
		//check in ia
		//check in ue
	//check max

	//output value
	$countia = 0;
	$pos = 0;
	$max = $maxm = 0;
	for($i=0;$i<$length; $i++)
	{
		if($iapm[$i] > 0)
		{
			$v = (($iap[$i]*100)/$iapm[$i]);
			if($v < 33)
				return "FAILED";
		}

		if($uepm[$i] > 0)
		{
			$v = (($uep[$i]*100)/$uepm[$i]);
			if($v < 33)
				return "FAILED";
		}

		if($uem[$i] > 0)
		{
			$v = (($ue[$i]*100)/$uem[$i]);
			if($v < 33)
			{
				$countia++;
				$pos = $i;
				continue;
			}
		}

		if($iam[$i] > 0)
		{
			$v = (($ia[$i]*100)/$iam[$i]);
			if($v < 33)
			{
				$countia++;
				$pos = $i;
				continue;
			}
		}
		$tot = marks($ia[$i]) + marks($ue[$i]) + marks($iap[$i]) + marks($uep[$i]);
		$max += $tot;
		$maxm += 100;
		$totm = $iam[$i] + $uem[$i] + $iapm[$i] + $uepm[$i];
		if($totm > 0)
		{
			$v = (($tot*100)/$totm);
			if($v < 33)
			{
				$countia++;
				$pos = $i;
			}
		}
	}
	
	if($countia == 1)
	{
		return "COMPTT in ".$code[$pos];
	}
	else if($countia > 1)
	{
		return "FAILED";
	}
	else
	{
		
		$percent = ($max*100/$maxm);
		if($percent >= 75)
			return "PASSED FIRST DIVISION WITH DISTN";
		else if($percent >=60 && $percent < 75)
			return "PASSED FIRST DIVISION";
		else if($percent >=48 && $percent < 60)
			return "PASSED SECOND DIVISION";
		else if($percent >=33 && $percent < 48)
			return "PASSED THIRD DIVISION";
	}

}
$table = "student$cls";
$q = "select distinct sid, sname, fname, roll, enroll, cat, dob, year from $table where roll = '$roll'";
$retval = mysqli_query($conn,$q);
while($row = mysqli_fetch_array($retval,MYSQL_ASSOC))
{
	$s = "select s.subname as name, s.code as code, c.ia as ia, c.ue as ue, c.uep as uep, c.iap as iap, s.ia as iam, s.ue as uem, s.uep as uepm, s.iap as iapm from $table c, subject12 s where s.code = c.subject_code and sid = ".$row['sid']." and s.private = c.private and s.private = 'no'";//" and c.total > 0";
	$retval2 = mysqli_query($conn,$s);
	$code = array();
	$subname = array();
	$ia = array();$iam = array();
	$ue = array();$uem = array();
	$iap = array();$iapm = array();
	$uep = array();$uepm = array();
	$c = 0;
	while($row2 = mysqli_fetch_array($retval2,MYSQL_ASSOC))
	{
		array_push($code, $row2['code']);
		array_push($subname, $row2['name']);
		array_push($ia, $row2['ia']);
		array_push($iap, $row2['iap']);
		array_push($iam, $row2['iam']);
		array_push($iapm, $row2['iapm']);
		array_push($ue, $row2['ue']);
		array_push($uep, $row2['uep']);
		array_push($uem, $row2['uem']);
		array_push($uepm, $row2['uepm']);
		$c++;
	}

	$PATH = "image/student/".$row['sid'].".JPG";
/*----------------------------------------------------------*/
$pdf->AddPage();
$pdf->SetFont('Arial','B',12);
$pdf->Cell(0,8,"",0,1,'C');
$pdf->Ln();
$pdf->Cell(0,5,"SENIOR SCHOOL CERTIFICATE (CLASS - XII) EXAMINATION ".$row['year'],0,1,'C');
$pdf->SetFont('Arial','',11);
$pdf->Ln();
$pdf->Cell(40,5,"",0,1);
$pdf->Cell(40,5,"NAME");$pdf->Cell(40,5,strtoupper($row['sname']));$pdf->Image($PATH,$pdf->GetX()+70,$pdf->GetY(),32,32);$pdf->Cell(40,2,"",0,1);
$pdf->Cell(40,5,"",0,1);
$pdf->Cell(40,5,"FATHER'S NAME");$pdf->Cell(40,5,strtoupper($row['fname']),0,1);$pdf->Cell(40,2,"",0,1);
$pdf->Cell(40,5,"ROLL NO");$pdf->Cell(40,5,strtoupper($row['roll']),0,1);$pdf->Cell(40,2,"",0,1);
$pdf->Cell(40,5,"ENROLLMENT NO");$pdf->Cell(40,5,strtoupper($row['enroll']),0,1);$pdf->Cell(40,2,"",0,1);
$pdf->Cell(40,5,"CATEGORY");$pdf->Cell(40,5,strtoupper($row['cat']),0,1);$pdf->Cell(40,2,"",0,1);
//$pdf->Cell(40,5,"DATE OF BIRTH");$pdf->Cell(40,5,"iksa",0,1);$pdf->Cell(40,2,"",0,1);
//$pdf->Cell(40,5,"");$pdf->Cell(40,5,"iksa",0,1);$pdf->Cell(40,2,"",0,1);
$pdf->Ln();
$w1=25;$w2=66;$w7=20;$w3=20;$w4=20;$w5=20;$w6=20;$w8=10;$w9=10;
$h1=$h2=$h3=$h4=$h5=$h6=$h7=5;

$pdf->Line(10,$pdf->GetY(),200,$pdf->GetY());
$pdf->Ln();
$pdf->Cell($w1,$h1,"CODE");$pdf->Cell($w2,$h2,"SUBJECT(S)/PAPER(S)");$pdf->Cell($w7,$h7,"Marks-UE"," ",0,"C");$pdf->Cell($w3,$h3,"Marks-IA","",0,"C");
$pdf->Cell($w4,$h4,"Marks-UEP"," ",0,"C");$pdf->Cell($w5,$h5,"Marks-IAP"," ",0,"C");$pdf->Cell($w6,$h6,"TOTAL"," ",0,"C");
$pdf->Ln();
$pdf->Cell($w1,$h1,"");$pdf->Cell($w2,$h2,"");
$pdf->Cell($w8,$h7,"MO"," ",0,"C");$pdf->Cell($w8,$h7,"MM"," ",0,"C");
$pdf->Cell($w8,$h7,"MO"," ",0,"C");$pdf->Cell($w8,$h7,"MM"," ",0,"C");
$pdf->Cell($w8,$h7,"MO"," ",0,"C");$pdf->Cell($w8,$h7,"MM"," ",0,"C");
$pdf->Cell($w8,$h7,"MO"," ",0,"C");$pdf->Cell($w8,$h7,"MM"," ",0,"C");
$pdf->Cell($w9,$h7,"MO"," ",0,"C");$pdf->Cell($w9,$h7,"MM"," ",0,"C");
$pdf->Ln();
$pdf->Cell(0,1,"",0,1);
$pdf->Cell(0,1,"",0,1);
$pdf->Line(10,$pdf->GetY(),200,$pdf->GetY());
$pdf->Cell(0,2,"",0,1);
//$c = 5;
$pdf->SetFont('Arial','',10);

$max = 0;
$maxm = 0;
for($i=0;$i<$c;$i++)
{
	$pdf->Ln();
	$pdf->Cell($w1,$h1,$code[$i]);$pdf->Cell($w2,$h2,$subname[$i]);
	$pdf->Cell($w8,$h1,ret($ue[$i],$uem[$i]),0,0,"C");$pdf->Cell($w8,$h1,ret($uem[$i],$uem[$i]),0,0,"C");
	$pdf->Cell($w8,$h1,ret($ia[$i],$iam[$i]),0,0,"C");$pdf->Cell($w8,$h1,ret($iam[$i],$iam[$i]),0,0,"C");
	$pdf->Cell($w8,$h1,ret($uep[$i],$uepm[$i]),0,0,"C");$pdf->Cell($w8,$h1,ret($uepm[$i],$uepm[$i]),0,0,"C");
	$pdf->Cell($w8,$h1,ret($iap[$i],$iapm[$i]),0,0,"C");$pdf->Cell($w8,$h1,ret($iapm[$i],$iapm[$i]),0,0,"C");
	$total = marks($ia[$i]) + marks($ue[$i]) + marks($iap[$i]) + marks($uep[$i]);
	$max += $total;
	$maxm += 100;
	$pdf->Cell($w8,$h1,$total,0,0,"C");$pdf->Cell($w8,$h1,"100",0,0,"C");
	$pdf->Ln();
}
if($c < 7)
{
	for($i=0;$i<7-$c+1;$i++)
	{
		$sc = "";		$sn = "";		$fa = "";		$sa = "";		$tot = "";		$gp = "";
		$pdf->Ln();
		$pdf->Cell($w1,$h1,$sc);$pdf->Cell($w2,$h2,$sn);$pdf->Cell($w3,$h3,$fa,0,0,"C");
		$pdf->Cell($w4,$h4,$sa,0,0,"C");$pdf->Cell($w5,$h5,$tot,0,0,"C");$pdf->Cell($w6,$h6,$gp,0,0,"C");
		$pdf->Ln();
	}
}
$pdf->SetFont('Arial','',11);
$pdf->Ln();$pdf->Ln();$pdf->Ln();
$pdf->Ln();
$pdf->Cell(0,1,"",0,1);
$pdf->Ln();
$pdf->Ln();
$pdf->Ln();
$pdf->Line(10,$pdf->GetY(),200,$pdf->GetY());
$pdf->Cell(0,2,"",0,1);
$pdf->Cell(175,5,"TOTAL    $max / $maxm",0,1,'R');
$qr = "Roll No: ".$row['roll']." | Enrollment: ".$row['enroll']." | Name: ".$row['sname']." | Father's name : ".$row['fname']." | DOB: ".$row['dob']." | Total Marks: $max / $maxm";
include('phpQR.php');
genQR($row['sid'],$qr);
$QR ="image/qr/" . $row['sid'].'.png';
$pdf->Cell(0,2,"",0,1);
$pdf->Line(10,$pdf->GetY(),200,$pdf->GetY());
$pdf->Cell(0,3,"",0,1);
$pdf->Cell(0,5,"RESULT / REMARKS    ".remarks($ia,$ue,$iap,$uep,$iam,$uem,$iapm,$uepm,200,$code),0,1);
$pdf->Cell(0,3,"",0,1);
$pdf->Line(10,$pdf->GetY(),200,$pdf->GetY());
$pdf->Ln();
$pdf->Cell(0,15,"","",1);
$pdf->Cell(60,5,"M Ansari","",1,"C");
$pdf->Cell(60,5,"PREPARED BY","",0,'C');
$pdf->Cell(50,5,"CHECKED BY","",0,'C');
$pdf->Cell(80,5,"ASSTT. CONTROLLER OF EXAMINATIONS","",1,'C');
$pdf->Cell(0,4,"","",1);
$pdf->Cell(6,12);
$pdf->Image($QR,$pdf->GetX()+10,$pdf->GetY(),20,20);
$pdf->Cell(170,12,"","",1);
$pdf->Cell(0,4,"","",1);
$pdf->Cell(116,5,"Date of Result  ".date("d-m-Y"),"",0,'R');
$pdf->Cell(50,5,"  Date of Issue  ","",0,'C');

}
$pdf->Output();
//$pdf->Output("marksheets/Marksheet-XII-".date("d-m-Y").".pdf","F");

	}
}


?>