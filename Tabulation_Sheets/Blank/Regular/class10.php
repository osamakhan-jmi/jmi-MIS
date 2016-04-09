<?php
require('../../../fpdf/fpdf.php');
	$pdf = new FPDF();
if($_GET)
{
	$cat = $_GET['cat'];
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
function absent($value)
{
	if($value == -1) return "Ab";
	else return $value;
}
function marks($value)
{
	if($value < 0)
		$value++;
	return $value;
}
function NewPage($pdf,$cat)
{
	$pdf->AddPage("L","A3");
	$pdf->SetFont('Arial','B',13);
	$pdf->Cell(0,8,"ANNUAL EXAMINATION ".date('Y'),"",1,'R');
	$pdf->Cell(0,8,"JAMIA MILLIA ISLAMIA, NEW DELHI","",1,'C');
	$pdf->Cell(0,8,"TABULATION SHEET","",1,'C');
	$pdf->Cell(0,8,"SECONDARY SCHOOL CERTIFICATE ( CLASS - X )","",1,'C');
	$pdf->Cell(320,8,"","",0,'C');$pdf->Cell(80,8,"X (REGULAR)","",1,'C');
	$pdf->Cell(0,2,"","",1,'C');
	$pdf->SetFont('Arial','B',8);
	//------------------------------------------------------------------------------------------------;
	$pdf->Cell(15,7,"ROLL NO","LRTB",0,'C');
	$pdf->Cell(35,7,"NAME OF CANDIDATE","LRTB",0,'C');
	//$pdf->Cell(15,7,"ENROLL","LRTB",0,'C');
	$pdf->SetFont('Arial','B',8);
	$arr = array("CSSA01","CSSA07","CSSA09","CSSA12","CSSS03","CSSA14","CSSC01");
	for($i=1;$i<8;$i++)
		$pdf->Cell(40,7,"Subject $i","LRTB",0,'C');
	$pdf->SetFont('Arial','B',8);
	$pdf->Cell(15,7,"TOTAL","LRTB",0,'C');
	$pdf->Cell(50,7,"REMARKS","LRTB",1,'C');
	//-----------------------------------------------------------------------------------------------;
	$pdf->Cell(15,7,"ENROLL","LRTB",0,'C');
	$pdf->Cell(35,7,strtoupper($cat),"LRTB",0,'C');
	//$pdf->Cell(15,7,"","LRTB",0,'C');
	$pdf->SetFont('Arial','B',7);
	$arr = array("CSSA01","CSSA07","CSSA09","CSSA12","CSSS03","CSSA14","CSSC01");
	for($i=1;$i<8;$i++)
	{
		$pdf->Cell(10,7,"CODE","LRTB",0,'C');$pdf->Cell(10,7,"SA2","LRTB",0,'C');$pdf->Cell(10,7,"SA","LRTB",0,'C');$pdf->Cell(10,7,"TOT","LRTB",0,'C');//$pdf->Cell(8,7,"TM","LRTB",0,'C');
	}
	$pdf->SetFont('Arial','B',8);
	$pdf->Cell(15,7,"","LRTB",0,'C');
	$pdf->Cell(50,7,"","LRTB",1,'C');
	//-----------------------------------------------------------------------------------------------;
	$pdf->Cell(15,7,"","LRTB",0,'C');
	$pdf->Cell(35,7,"","LRTB",0,'C');
	//$pdf->Cell(15,7,"","LRTB",0,'C');
	$pdf->SetFont('Arial','B',7);
	$arr = array("CSSA01","CSSA07","CSSA09","CSSA12","CSSS03","CSSA14","CSSC01");
	for($i=1;$i<8;$i++)
	{
		$pdf->Cell(10,7,"","LRTB",0,'C');$pdf->Cell(10,7,"SA1","LRTB",0,'C');$pdf->Cell(10,7,"FA","LRTB",0,'C');$pdf->Cell(10,7,"","LRTB",0,'C');//$pdf->Cell(10,7,"100","LRTB",0,'C');
	}
	$pdf->SetFont('Arial','B',8);
	$pdf->Cell(15,7,"","LRTB",0,'C');
	$pdf->Cell(50,7,"","LRTB",1,'C');
	//--------------------------------------------------------------------;
	$pdf->Cell(15,7,"","LRTB",0,'C');
	$pdf->Cell(35,7,"","LRTB",0,'C');
	//$pdf->Cell(15,7,"","LRTB",0,'C');
	$pdf->SetFont('Arial','B',7);
	
	for($i=1;$i<8;$i++)
	{
		$pdf->Cell(10,7,"","LRTB",0,'C');$pdf->Cell(10,7,"30","LRTB",0,'C');$pdf->Cell(10,7,"70","LRTB",0,'C');$pdf->Cell(10,7,"100","LRTB",0,'C');
	}
	$pdf->SetFont('Arial','B',8);
	$pdf->Cell(15,7,"","LRTB",0,'C');
	$pdf->Cell(50,7,"","LRTB",1,'C');
	//----------------------------------------------------------------------;
	$pdf->Cell(15,7,"","LRTB",0,'C');
	$pdf->Cell(35,7
		,"","LRTB",0,'C');
	///$pdf->Cell(15,7,"","LRTB",0,'C');
	$pdf->SetFont('Arial','B',7);
	
	for($i=1;$i<8;$i++)
	{
		$pdf->Cell(10,7,"","LRTB",0,'C');$pdf->Cell(10,7,"30","LRTB",0,'C');$pdf->Cell(10,7,"40","LRTB",0,'C');$pdf->Cell(10,7,"","LRTB",0,'C');
	}
	$pdf->SetFont('Arial','B',8);
	$pdf->Cell(15,7,"","LRTB",0,'C');
	$pdf->Cell(50,7,"","LRTB",1,'C');
}


function BottomPage($pdf)
{
	$pdf->Cell(0,2,"","",1,'C');
	$pdf->SetFont('Arial','',7);
	$height1 = 56; $width = 7;
	$pdf->Cell(0,$width,"","",1);
	$pdf->Cell(0,$width,"","",1);
	$pdf->Cell($height1,$width,"CIS001 ISLAMIAT 1","LRTB",0);$pdf->Cell($height1,$width,"CLH002 HINDU ETHICS HET","LRTB",1);
	//$pdf->Cell($height1,$width,"CSSA03 GEOGRAPHY GEO","LRTB",1);
	//$pdf->Cell($height1,$width,"CSSA04 HISTORY HIS","LRTB",0);$pdf->Cell($height1,$width,"CSSA05 ISLAMIC STUDIES IST","LRTB",1);
	$pdf->Cell($height1,$width,"CLU003 ELEMENTARY URDU EUR","LRTB",0);$pdf->Cell($height1,$width,"CSC101 ADVANCE URDU AUR","LRTB",1);
	$pdf->SetFont('Arial','',7);$pdf->Cell($height1,$width,"CSC102 HINDI-A HNA","LRTB",0);$pdf->Cell($height1,$width,"CSC103 HINDI-B HNB","LRTB",0);
	$pdf->SetFont('Arial','',12);$pdf->Cell(60,$width,"","",0,'C');$pdf->Cell(40,$width,"Tabulator I","",0,'C');$pdf->Cell(40,$width,"Tabulator II","",0,'C');$pdf->Cell(40,$width,"Tabulation","",0,'C');$pdf->Cell(40,$width,"Asstt Controller","",0,'C');$pdf->Cell(40,$width,"Controller","",1,'C');
	
	$pdf->SetFont('Arial','',7);$pdf->Cell($height1,$width,"CSC104 ENGLISH ENG","LRTB",0);$pdf->Cell($height1,$width,"CSC105 MATHEMATICS MAT","LRTB",0);
	$pdf->SetFont('Arial','',12);$pdf->Cell(60,$width,"","",0,'C');$pdf->Cell(40,$width,"","",0,'C');$pdf->Cell(40,$width,"","",0,'C');$pdf->Cell(40,$width,"Incharge","",0,'C');$pdf->Cell(40,$width,"of Examination","",0,'C');$pdf->Cell(40,$width,"of Examination","",1,'C');
	$pdf->SetFont('Arial','',7);
	$pdf->Cell($height1,$width,"CSC106 SCIENCE AND TECHNOLOGY SCI","LRTB",0);$pdf->Cell($height1,$width,"CSC107 SOCIAL SCIENCE SOC","LRTB",1);
	$pdf->Cell($height1,$width,"CSC108 HOME SCIENCE HOM","LRTB",0);$pdf->Cell($height1,$width,"CSC109 GENERAL SCIENCE GSC","LRTB",0);$pdf->SetFont('Arial','',10);$pdf->Cell(200,$width,"Date Of Issue: ".date('d-m-Y'),"",1,'R');

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

function grade_point($value, $max)
		{
			$val = $value / $max;
			$val = $val *100;
			$val = round($val);
			if($val > 91 )
				return  10;
			else if($val > 80 && $val <= 90)
				return 9;
			else if($val >70 && $val<=80)
				return 8;
			else if($val >60 && $val<=70)
				return 7;
			else if($val >50 && $val<=60)
				return 6;
			else if($val >40 && $val<=50)
				return 5;
			else if($val >32 && $val<=40)
				return 4;
			else if($val >21 && $val<=32)
				return 0;
			else
				return 0;
		}


function Values($pdf,$values)
{
	$len = count($values);
	$pdf->SetFont('Arial','',7);
	$pdf->Cell(15,10,$values[0],"LRTB",0,'C');
	$pdf->SetFont('Arial','',9);
	$pdf->Cell(35,10,$values[1],"LRTB",0,'C');
	$pdf->SetFont('Arial','',7);
	//$pdf->Cell(15,10,$values[2],"LRTB",0,'C');
	for($i=0;$i<28;$i++)
	{
		//$fieldArray[0] = "CSC".(100+($i/4)+1);
		if($i%5 == 0)
		$pdf->Cell(10,10,$values[2+$i],"LRTB",0,"C");
		elseif($i %5 == 4)
			$pdf->Cell(10,10,$values[2+$i],"LRTB",0,"C");
		else
		$pdf->Cell(10,10,$values[2+$i],"LRTB",0,"C");
	}	
	$pdf->Cell(15,10,$values[$len - 2],"LRTB",0,'C');
	$pdf->Cell(50,10,strtoupper($values[$len-1]),"LRTB",1,'C');
}

include("../../../connection-sql.php");
$q = "select sid, sname, roll, enroll from class10 where private='no' and cat='".$cat."' group by sid";
$retval = mysqli_query($conn,$q);
$allR1 = array();
$allR2 = array();
while( $row = mysqli_fetch_array($retval, MYSQL_ASSOC))
{
		$values = array();
		$ROW1 = array($row['roll'],$row['sname']);//,$row['roll']);
		$ROW2 = array($row['enroll'],"");
		$fa = array();
		$sa1 = array();
		$sa2 = array();
		$code = array();
		$gp_total = array();
		$total_marks = 0;
		$s = "select s.code as code, c.ia as ia, c.ue as ue, c.sauth as auth, c.uep as uep, c.iap as iap, s.ia as iam, s.ue as uem, s.uep as uepm, s.iap as iapm from class10 c, subject10 as s where s.code = c.subject_code and sid = ".$row['sid']. " and s.private = c.private and c.private = 'no'";//" and c.total > 0 ";
		$retval2 = mysqli_query($conn,$s);
		//echo mysqli_num_rows($retval2)."<br>";
		while($row2 = mysqli_fetch_array($retval2,MYSQL_ASSOC))
		{		
			$total = marks($row2['ue'])+marks($row2['ia'])+marks($row2['uep']);
			//REMARKS 
			array_push($code,$row2['code']);
			array_push($fa,$row2['uep']);
			array_push($sa1,$row2['ue']);
			array_push($sa2,$row2['ia']);
			//ROW 1
			array_push($ROW1,$row2['code']);
			array_push($ROW1,"");
			array_push($ROW1,"");
			array_push($ROW1,"");
			//ROW 2
			array_push($ROW2,"");
			array_push($ROW2,"");
			array_push($ROW2,"");
			array_push($ROW2,"");
			
			$total_marks += marks($row2['ue'])+marks($row2['ia'])+marks($row2['iap'])+marks($row2['uep']);
		}
		
		$remark = remarks($fa,$sa1,$sa2,$code);
		array_push($ROW1,"");
		array_push($ROW1,"");
		array_push($ROW2,"");
		array_push($ROW2,"");
		array_push($allR1,$ROW1);
		array_push($allR2,$ROW2);
		//echo json_encode($allValues);
		//Values($pdf,$values);
	}
	$MaxRow = 6;
	$length = count($allR1);
	if($length == 0){
		echo "NO ENTRIES YET!!";
		exit;
	}
	$NoOfPage = $length / $MaxRow;
	if($NoOfPage * $MaxRow < $length)
		$NoOfPage++;
	for($i=0;$i<$NoOfPage;$i++)
	{
		NewPage($pdf,$cat);
		for($j=0;$j<$MaxRow;$j++)
		{
			if($length >= ($i*$MaxRow+$j+1))
			{
				$index = $i*$MaxRow+$j;
				Values($pdf,$allR1[$index]);
				Values($pdf,$allR2[$index]);
			}	
			else
			{
				$pdf->Cell(0,10,"","",1);
				$pdf->Cell(0,10,"","",1);
			}
		}
		BottomPage($pdf);
	}
	
	/*NewPage($pdf);
	BottomPage($pdf);*/
	$pdf->Output();
}
?>