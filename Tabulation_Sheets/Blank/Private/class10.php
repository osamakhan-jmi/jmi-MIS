<?php
require('../../../fpdf/fpdf.php');
	$pdf = new FPDF();
if($_GET)
{
	$cat = $_GET['cat'];
function marks($value)
{
	return ($value < 0)?0:$value;
}
function remarks($ia,$ue,$iap,$uep,$iam,$uem,$iapm,$uepm,$code)
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
	$pos = array();

	for($i=0;$i<$length; $i++)
	{
		$tot = marks($ue[$i]);
		$totm = 100;
		if($totm > 0)
		{
			$v = round(($tot*100)/$totm);
			if($v < 33)
			{
				$countia++;
				array_push($pos,$i);
			}
		}
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
		$percent = round($max/5);
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

function NewPage($pdf,$cat)
{
	$pdf->AddPage("L","A3");
	$pdf->SetFont('Arial','B',13);
	$pdf->Cell(0,8,"ANNUAL EXAMINATION ".date('Y'),"",1,'R');
	$pdf->Cell(0,8,"JAMIA MILLIA ISLAMIA, NEW DELHI","",1,'C');
	$pdf->Cell(0,8,"TABULATION SHEET","",1,'C');
	$pdf->Cell(0,8,"SENIOR SCHOOL CERTIFICATE ( CLASS - X )","",1,'C');
	$pdf->Cell(320,8,"","",0,'C');$pdf->Cell(80,8,"X (PRIVATE)","",1,'C');
	$pdf->Cell(0,2,"","",1,'C');
	$pdf->SetFont('Arial','B',8);
	//------------------------------------------------------------------------------------------------;
	$pdf->Cell(20,7,"ROLL NO","LRTB",0,'C');
	$pdf->Cell(35,7,"NAME OF CANDIDATE","LRTB",0,'C');
	$pdf->Cell(20,7,"ENROLL","LRTB",0,'C');
	$pdf->SetFont('Arial','B',8);
	$arr = array("CSSA01","CSSA07","CSSA09","CSSA12","CSSS03","CSSA14","CSSC01");
	for($i=1;$i<8;$i++)
		$pdf->Cell(37,7,"Subject $i","LRTB",0,'C');
	$pdf->SetFont('Arial','B',8);
	$pdf->Cell(15,7,"TOTAL","LRTB",0,'C');
	$pdf->Cell(50,7,"REMARKS","LRTB",1,'C');
	//-----------------------------------------------------------------------------------------------;
	$pdf->Cell(20,7,"","LRTB",0,'C');
	$pdf->Cell(35,7,"$cat","LRTB",0,'C');
	$pdf->Cell(20,7,"","LRTB",0,'C');
	$pdf->SetFont('Arial','B',7);
	$arr = array("CSSA01","CSSA07","CSSA09","CSSA12","CSSS03","CSSA14","CSSC01");
	for($i=1;$i<8;$i++)
	{
		$pdf->Cell(10,7,"CODE","LRTB",0,'C');$pdf->Cell(9,7,"UE","LRTB",0,'C');$pdf->Cell(9,7,"IA","LRTB",0,'C');$pdf->Cell(9,7,"TOT","LRTB",0,'C');
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

function Values($pdf,$values)
{
	$len = count($values);
	$pdf->SetFont('Arial','',7);
	$pdf->Cell(20,10,$values[0],"LRTB",0,'C');
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(35,10,$values[1],"LRTB",0,'C');
	$pdf->SetFont('Arial','',7);
	$pdf->Cell(20,10,$values[2],"LRTB",0,'C');
	for($i=0;$i<28;$i++)
	{
		//$fieldArray[0] = "CSC".(100+($i/4)+1);
		if($i%4 == 0)
		$pdf->Cell(10,10,$values[3+$i],"LRTB",0,"C");
		else
		$pdf->Cell(9,10,$values[3+$i],"LRTB",0,"C");
	}	
	$pdf->Cell(15,10,$values[$len - 2],"LRTB",0,'C');
	$pdf->Cell(50,10,strtoupper($values[$len-1]),"LRTB",1,'C');
}

include("../../../connection-sql.php");
$q = "select sid, sname, roll, enroll from class10 where private='yes' and cat='$cat' group by sid";
$retval = mysqli_query($conn,$q);
$allValues = array();
while( $row = mysqli_fetch_array($retval, MYSQL_ASSOC))
{
		$values = array();
		$code = array();
		array_push($values,$row['roll']);
		array_push($values,$row['sname']);
		array_push($values,$row['enroll']);
		
		$s = "select s.code as code, c.ia as ia, c.ue as ue, c.sauth as auth, c.uep as uep, c.iap as iap, s.ia as iam, s.ue as uem, s.uep as uepm, s.iap as iapm from class10 c, subject10 as s where s.code = c.subject_code and sid = ".$row['sid']. " and s.private = c.private and c.private = 'yes'";//" and c.total > 0 ";
		$retval2 = mysqli_query($conn,$s);
		//echo mysqli_num_rows($retval2)."<br>";
		while($row2 = mysqli_fetch_array($retval2,MYSQL_ASSOC))
		{
			array_push($values,$row2['code']);
			array_push($values,"");
			array_push($values,"");
			array_push($values,"");
		}

		//echo "CODES: ".json_encode($code);echo "<br><br>";
		array_push($values,"");array_push($values,"");
		array_push($allValues,$values);
	}
	$MaxRow = 15;
	$length = count($allValues);
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
				Values($pdf,$values);
			else
				$pdf->Cell(0,10,"","",1);
		}
		BottomPage($pdf);
	}
	
	

	$pdf->Output();
}
?>