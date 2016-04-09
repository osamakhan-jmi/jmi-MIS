<?php
session_start();
if($_SESSION["type"]!="admin")
	header("Location:index.php");
require('fpdf/fpdf.php');
$PATH = "image/1.jpg";
		$QR = "image/qr/";
//require('fpdf/fpdf.php');
	include('connection-sql.php');
	//$conn = mysqli_connect("localhost","root","19011996","jmi_school");
	function cat($c)
	{
	switch($c)
	{
		case "1": return "REGULAR";
		case "2": return "CR-DI";
		case "3": return "EX";
	}
	}

	function marks($value)
{
	return ($value<0) ? 0 : $value;
}

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
		$q = "select distinct sid, sname, fname, roll, enroll, dob, cat from class10 where private='no'";
		$retval = mysqli_query($conn,$q);
		while($row = mysqli_fetch_array($retval,MYSQL_ASSOC))
		{
			$s = "select s.subname as name, s.code as code, c.sauth as auth, c.ia as ia, c.ue as ue, c.uep as uep, c.iap as iap, s.ia as iam, s.ue as uem, s.uep as uepm, s.iap as iapm,  c.total as total from subject10 s,class10 c where s.code = c.subject_code and c.private = s.private and s.private = 'no' and c.sid = ".$row['sid'];//." and c.total > 0";
			$retval2 = mysqli_query($conn,$s);
			$code = array();
			$subname = array();
			$fa = array();$fam = array();$sa1 = array();
			$sa = array();$sam = array();$sa2 = array();
			$total = array();$totm = array();
			$flag = true;
			$c = 0;
			while($row2 = mysqli_fetch_array($retval2,MYSQL_ASSOC))
			{
				array_push($code, $row2['code']);
				array_push($subname, $row2['name']);
				array_push($fa, marks($row2['uep']) + marks($row2['iap']));
				array_push($sa1,marks($row2['ue']));
				array_push($sa2,marks($row2['ia']));
				array_push($sa, marks($row2['ia']) + marks($row2['ue']));
				array_push($fam, $row2['uepm']+$row2['iapm']);
				array_push($sam, $row2['iam'] + 0 + $row2['uem'] + 0);
				array_push($total, $fa[$c] + $sa[$c]);
				array_push($totm, $fam[$c]+$sam[$c]);
				$c++;
				if(strcasecmp($row2['auth'],'yes') == 0)
					$flag = false;
			}
			if($flag == false)
				continue;
			$dob = date("d-m-Y",strtotime($row['dob']));
			$str = explode("-",$dob);

			include_once("testdate.php");

			$dinw = strtoupper(day($str[0]).month($str[1]).year($str[2]));
			
$PATH = "image/student/".$row['sid'].".JPG";
			$pdf->AddPage();
			$pdf->SetFont('Arial','B',12);
			$pdf->Cell(0,8,"",0,1,'C');
			$pdf->Ln();
			$pdf->Cell(0,5,"SECONDARY SCHOOL CERTIFICATE (CLASS - X) EXAMINATION ".date('Y'),0,1,'C');
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

			$qr = "Roll No: ".$row['roll']." | Enrollment: ".$row['enroll']." | Name: ".$row['sname']." | Father's name : ".$row['fname']." | DOB: ".$row['dob']." | Total Marks: ".$sum_gp;
			include('phpQR.php');
			$QR .=$row['sid'].'.png';
			genQR($row['sid'],$qr);
			$pdf->Ln();$pdf->Ln();$pdf->Ln();
			$pdf->Ln();
			$pdf->Cell(0,1,"",0,1);
			$pdf->Ln();
			$pdf->Ln();
			$pdf->Ln();
			$pdf->Line(10,$pdf->GetY(),200,$pdf->GetY());
			$pdf->Cell(0,2,"",0,1);
			$pdf->Cell(175,5,"CGPA    $sum_gp",0,1,'R');
			$pdf->Cell(0,2,"",0,1);
			$pdf->Line(10,$pdf->GetY(),200,$pdf->GetY());
			$pdf->Cell(0,3,"",0,1);
			$pdf->Cell(50,5,"RESULT / REMARKS    ");$pdf->SetFont('Arial','',9);$pdf->Multicell(120,5,remarks($fa,$sa1,
				$sa2,$code),0,1);$pdf->SetFont('Arial','',11);
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
?>