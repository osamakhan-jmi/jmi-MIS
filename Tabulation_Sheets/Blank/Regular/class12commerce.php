<?php
//	INITIALIZATIONS
	require('../../../fpdf/fpdf.php');
	$pdf = new FPDF('L','mm',"A3");
	$pdf->SetAutoPageBreak(true, 1);
	include("../../../connection-sql.php");
///////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////
if($_GET)
{
	$cat = $_GET['cat'];

//  Remarks CLASS XII SCIENCE	
function remarks($ia,$ue,$iap,$uep,$iam,$uem,$iapm,$uepm,$code)
{
	$length = count($code);
	
	$countia = 0;
	$pos = 0;
	$gt = 0;
	$gtm = 0;
	
	for($i=0;$i<$length; $i++)
	{
		//echo "iteration $i<br>";
		if($iapm[$i] > 0)
		{//echo "in ia marks <br>";
			$v = round(($iap[$i]*100)/$iapm[$i]);
			if($v < 33)
				return "FAILED";
		}

		if($uepm[$i] > 0)
		{//echo "in ue marks <br>";
			$v = round(($uep[$i]*100)/$uepm[$i]);
			if($v < 33)
				return "FAILED";
		}
		$tot = $ia[$i] + $ue[$i] + $iap[$i] + $uep[$i];
		$totm = $iam[$i] + $uem[$i] + $iapm[$i] + $uepm[$i];
		$gt += $tot;
		$gtm += $totm;
		//echo "error: ".$gtm."<br><br>";
		if($totm > 0)
		{
			//echo "in total marks <br>";
			$v = round(($tot*100)/$totm);
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
		
		$percent = round(($gt*100)/$gtm,2);
		if($percent >= 75)
			return "PASSED FIRST DIVISION WITH DISTN";
		else if($percent >=60 && $percent < 75)
			return "PASSED FIRST DIVISION";
		else if($percent >=48 && $percent < 60)
			return "PASSED SECOND DIVISION";
		else if($percent >=33 && $percent <48)
			return "PASSED THIRD DIVISION";
	}
}
///////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////

//  NEW - PAGE HEADERS 
function NewPage($pdf)
{
	$pdf->AddPage();
	$pdf->SetFont('Arial','B',13);
	$pdf->Cell(0,8,"ANNUAL EXAMINATION ".date('Y'),"",1,'R');
	$pdf->Cell(0,8,"JAMIA MILLIA ISLAMIA, NEW DELHI","",1,'C');
	$pdf->Cell(0,8,"TABULATION SHEET","",1,'C');
	$pdf->Cell(0,8,"SENIOR SCHOOL CERTIFICATE ( CLASS - XII )","",1,'C');
	$pdf->Cell(320,8,"","",0,'C');$pdf->Cell(80,8,"XII (COMMERCE)","",1,'C');
	$pdf->Cell(0,2,"","",1,'C');
	$pdf->SetFont('Arial','B',9);
//------------------------------------------------------------------------------------------------;
	$pdf->Cell(25,7,"ROLL NO","LRTB",0,'C');
	$pdf->Cell(45,7,"NAME OF CANDIDATE","LRTB",0,'C');
	$pdf->Cell(30,7,"ENROLLMENT","LRTB",0,'C');
	$pdf->SetFont('Arial','B',9);
	for($i=0;$i<5;$i++)
		$pdf->Cell(46,7,"SUBJECT ".($i+1),"LRTB",0,"C");
	/*for($i=0;$i<6;$i++)
		$pdf->Cell(24,7,"CSSS03","LRTB",0,"C");*/
	$pdf->SetFont('Arial','B',9);
	$pdf->Cell(14,7,"TOTAL","LRTB",0,'C');
	$pdf->Cell(57,7,"REMARKS","LRTB",1,'C');
//------------------------------------------------------------------------------------------------;
	$pdf->Cell(25,7,"","LRTB",0,'C');
	$pdf->Cell(45,7,"$cat","LRTB",0,'C');
	$pdf->Cell(30,7,"","LRTB",0,'C');
	$fieldArray=array("CODE","UE","IA","TM");
	for($i=0;$i<20;$i++)
	{
		if($i%4 == 0)
		$pdf->Cell(13,7,$fieldArray[$i%4],"LRTB",0,"C");
		else
		$pdf->Cell(11,7,$fieldArray[$i%4],"LRTB",0,"C");
	}
	/*for($i=0;$i<6;$i++)
		$pdf->Cell(24,7,"CODE","LRTB",0,"C");*/
	$pdf->Cell(14,7,"","LRTB",0,'C');
	$pdf->Cell(57,7,"","LRTB",1,'C');
//------------------------------------------------------------------------------------------------;
	$pdf->Cell(0,2,"","TB",1,'C');
}
///////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////


//  BOTTOM PAGE 
function BottomPageCommerce($pdf)
{
	$pdf->Cell(0,2,"","",1,'C');
	$pdf->SetFont('Arial','',7);
	$height1 = 58; $width = 7;
	$pdf->Cell(0,$width,"","",1);
	$pdf->Cell(0,$width,"","",1);
	$pdf->Cell($height1,$width,"CSSA01 ECONOMICS ECO","LRTB",0);$pdf->Cell($height1,$width,"CSSA07 ENGLISH(CORE) ENG","LRTB",1);
	$pdf->SetFont('Arial','',7);$pdf->Cell($height1,$width,"CSSA09 URDU LITERATURE URD","LRTB",0);$pdf->Cell($height1,$width,"CSSA12 HINDI LITERATURE HIN","LRTB",1);
	$pdf->SetFont('Arial','',7);$pdf->Cell($height1,$width,"CSSA13 HOMESCIENCE HOM","LRTB",0);$pdf->Cell($height1,$width,"CSSA14 MULTIMEDIA AND WEB MMT","LRTB",0);
	$pdf->SetFont('Arial','',12);$pdf->Cell(60,$width,"","LRTB",0,'C');$pdf->Cell(40,$width,"Tabulator I","LRTB",0,'C');$pdf->Cell(40,$width,"Tabulator II","LRTB",0,'C');$pdf->Cell(40,$width,"Tabulation","LRTB",0,'C');$pdf->Cell(40,$width,"Asstt Controller","LRTB",0,'C');$pdf->Cell(40,$width,"Controller","LRTB",1,'C');
	$pdf->SetFont('Arial','',7);$pdf->Cell($height1,$width,"CSSC01 BUSINESS STUDIES BST","LRTB",0);$pdf->Cell($height1,$width,"CSSC02 ACCOUNTANCY ACC","LRTB",0);
	$pdf->SetFont('Arial','',12);$pdf->Cell(60,$width,"","LRTB",0,'C');$pdf->Cell(40,$width,"","LRTB",0,'C');$pdf->Cell(40,$width,"","LRTB",0,'C');$pdf->Cell(40,$width,"Incharge","LRTB",0,'C');$pdf->Cell(40,$width,"of Examination","LRTB",0,'C');$pdf->Cell(40,$width,"of Examination","LRTB",1,'C');
	$pdf->SetFont('Arial','',7);$pdf->Cell($height1,$width,"CSSC03 MARKETING AND SALESMANSHIP MKT","LRTB",0);$pdf->Cell($height1,$width,"CSSS03 MATHEMATICS MAT","LRTB",0);$pdf->SetFont('Arial','',10);$pdf->Cell(200,$width,"Date Of Issue: ".date('d-m-Y'),"LRTB",1,'R');

}
///////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////

//   VALUES TO BE PRODUCED
function Values($pdf,$values)
{
	$len = count($values);
	$pdf->SetFont('Arial','',8);
	$pdf->Cell(25,10,$values[0],"LRTB",0,'C');
	$pdf->Cell(45,10,$values[1],"LRTB",0,'C');
	$pdf->Cell(30,10,$values[2],"LRTB",0,'C');
	//$fieldArray=array("","100/100","100/100","100/100");
	for($i=0;$i<20;$i++)
	{
		$fieldArray[0] = "CSC".(100+($i/4)+1);
		if($i%4 == 0)
		$pdf->Cell(13,10,$values[3+$i],"LRTB",0,"C");
		else
		$pdf->Cell(11,10,$values[3+$i],"LRTB",0,"C");
	}	
	$pdf->Cell(14,10,$values[$len - 2],"LRTB",0,'C');
	$pdf->Cell(57,10,strtoupper($values[$len-1]),"LRTB",1,'C');
}
///////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////

//	MAIN CALCULATION 
	
	$q = "select sid, sname,roll, enroll from class12 where stream = 'commerce' and cat='$cat' and private='no' group by sid";
	$retval = mysqli_query($conn,$q);
	$max = mysqli_num_rows($retval);
	$countOfLinesPrinted = 0;
	$totalLinesPrinted = 0;
	$allValues = array();
	//   QUERY PROCESSING
	while($row = mysqli_fetch_array($retval,MYSQL_ASSOC))
	{
		$values = array();
		$code = array();
		array_push($values,$row['roll']);
		array_push($values,$row['sname']);
		array_push($values,$row['enroll']);
		$s = "select s.code as code, c.ia as ia, c.ue as ue, c.sauth as auth, c.uep as uep, c.iap as iap, s.ia as iam, s.ue as uem, s.uep as uepm, s.iap as iapm from class12 c, subject12 as s where s.code = c.subject_code and sid = ".$row['sid']. " and s.private = c.private and c.private = 'no'";//" and c.total > 0 ";
		$retval2 = mysqli_query($conn,$s);
		//echo mysqli_num_rows($retval2)."<br>";
		while($row2 = mysqli_fetch_array($retval2,MYSQL_ASSOC))
		{
			array_push($values,$row2['code']);
			array_push($values,"");
			array_push($values,"");
			array_push($values,"");

		}
		array_push($values,"");
		array_push($values,"");
		array_push($allValues,$values);
	}
///////////////////////////////////////////////////////////////////////////////////////

//	PRINT VALUES
	$length = count($allValues);
	if($length == 0){
		echo "NO ENTRIES YET!!";
		exit;
	}
	$NoOfPage = $length / 16;
	if($NoOfPage * 16 < $length)
		$NoOfPage++;

	for($i = 0;$i < $NoOfPage; $i++)
	{
		NewPage($pdf);
		for($j = 0;$j < 16;$j++)
		{
			if($length >= ($i*16+$j+1))
				Values($pdf,$allValues[$i*16+$j]);
			else
				$pdf->Cell(0,10,"","",1);
		}
		BottomPageCommerce($pdf);
	}
///////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////
	$pdf->Output();
	$pdf->Output("F","doctest.pdf");
}
?>