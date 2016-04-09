<?php
session_start();
if($_SESSION["type"]!="admin")
	header("Location:index.php");
$PATH = "image/1.jpg";
$tempQR = "image/qr/";
require('fpdf/fpdf.php');
include("connection-sql.php");

$pdf = new FPDF();
/*----------------------ASSIGNMENT---------------------------*/
function marks($value)
{
	return ($value<0) ? 0 : $value;
}
function ret($val,$valm)
{

	if($valm == 0) return ".";
	else if($val < 0) return "Ab";
	else return ($val);
}
function cat($c)
{
	switch($c)
	{
		case "1": return "REGULAR";
		case "2": return "CR-DI";
		case "3": return "EX";
	}
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
		$tot = marks($ia[$i]) + marks($ue[$i]) + marks($iap[$i]) + marks($uep[$i]);
		$totm = $iam[$i] + $uem[$i] + $iapm[$i] + $uepm[$i];
		$gt += $tot;
		$gtm += $totm;
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
		
		$percent = round(($gt * 100)/$gtm,2);
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

$q = "select distinct sid, sname, fname, roll, enroll, cat, dob from class12 where private='no' and stream='art'";
$retval = mysqli_query($conn,$q);
while($row = mysqli_fetch_array($retval,MYSQL_ASSOC))
{
	$s = "select s.subname as name, s.code as code, c.sauth as auth, c.ia as ia, c.ue as ue, c.uep as uep, c.iap as iap, s.ia as iam, s.ue as uem, s.uep as uepm, s.iap as iapm from class12 c, subject12 as s where s.code = c.subject_code and c.private = s.private and c.private = 'no' and sid = ".$row['sid'];//. " and c.total > 0";
	$retval2 = mysqli_query($conn,$s);
	$code = array();
	$subname = array();
	$ia = array();$iam = array();
	$ue = array();$uem = array();
	$iap = array();$iapm = array();
	$uep = array();$uepm = array();
	$flag = true;
	$c=0;
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
		if(strcasecmp($row2['auth'],'yes') == 0)
			$flag = false;
	}
	if($flag == false)
		continue;

	$PATH = "image/student/".$row['sid'].".JPG";
/*----------------------------------------------------------*/
$pdf->AddPage();
$pdf->SetFont('Arial','B',12);
$pdf->Cell(0,8,"",0,1,'C');
$pdf->Ln();
$pdf->Cell(0,5,"SENIOR SCHOOL CERTIFICATE (CLASS - XII) EXAMINATION ".date('Y'),0,1,'C');
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
	$total = marks($ue[$i])+marks($ia[$i])+marks($iap[$i])+marks($uep[$i]);
	$max += $total;
	$maxm += marks($uem[$i])+marks($iam[$i])+marks($iapm[$i])+marks($uepm[$i]);
	$pdf->Cell($w8,$h1,$total,0,0,"C");$pdf->Cell($w8,$h1,"100",0,0,"C");
	$pdf->Ln();
}
if($c < 7)
{
	for($i=0;$i<7-$c;$i++)
	{
		$sc = "";		$sn = "";		$fa = "";		$sa = "";		$tot = "";		$gp = "";
		$pdf->Ln();
		$pdf->Cell($w1,$h1,$sc);$pdf->Cell($w2,$h2,$sn);$pdf->Cell($w3,$h3,$fa,0,0,"C");
		$pdf->Cell($w4,$h4,$sa,0,0,"C");$pdf->Cell($w5,$h5,$tot,0,0,"C");$pdf->Cell($w6,$h6,$gp,0,0,"C");
		$pdf->Ln();
	}
}

$qr = "Roll No: ".$row['roll']." | Enrollment: ".$row['enroll']." | Name: ".$row['sname']." | Father's name : ".$row['fname']." | DOB: ".$row['dob']." | Total Marks: $max / $maxm";
include('phpQR.php');
genQR($row['sid'],$qr);
$QR =$tempQR.$row['sid'].'.png';
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


?>