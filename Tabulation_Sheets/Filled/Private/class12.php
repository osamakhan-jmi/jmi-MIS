
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
function remarks($ia,$ue,$iap,$uep,$iam,$uem,$iapm,$uepm,$count,$code)
{
	$length = $count;
	
	$countia = 0;
	$pos = 0;
	$gt = 0;
	$gtm = 0;
	
	for($i=0;$i<$length; $i++)
	{
		//echo "iteration $i<br>";
		if($iapm[$i] > 0)
		{echo "in ia marks <br>";
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
		$tot = marks($ue[$i]) + marks($uep[$i]);
		$totm = marks($uem[$i]) + marks($uepm[$i]);
		$gt += $tot;
		$gtm += $totm;
		//echo "error: ".$gtm."<br><br>";
		if($uem[$i] > 0)
		{
			if(($ue[$i] * 100)/$uem[$i] < 33)
			{
				$countia++;
				$pos = $i;
				continue;	
			}
			
		}
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


function NewPage($pdf,$cat)
{
	$pdf->AddPage("L","A3");
	$pdf->SetFont('Arial','B',13);
	$pdf->Cell(0,8,"ANNUAL EXAMINATION ".date('Y'),"",1,'R');
	$pdf->Cell(0,8,"JAMIA MILLIA ISLAMIA, NEW DELHI","",1,'C');
	$pdf->Cell(0,8,"TABULATION SHEET","",1,'C');
	$pdf->Cell(0,8,"SENIOR SCHOOL CERTIFICATE ( CLASS - XII )","",1,'C');
	$pdf->Cell(320,8,"","",0,'C');$pdf->Cell(80,8,"XII (PRIVATE)","",1,'C');
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

function BottomPageArts($pdf)
{
	$pdf->Cell(0,2,"","",1,'C');
	$pdf->SetFont('Arial','',7);
	$height1 = 56; $width = 7;
	$pdf->Cell($height1,$width,"CIS001 ISLAMIAT 1","LRTB",0);$pdf->Cell($height1,$width,"CSSA01 ECONOMICS ECO","LRTB",1);
	$pdf->Cell($height1,$width,"CSSA02 FINE ARTS ART","LRTB",0);$pdf->Cell($height1,$width,"CSSA03 GEOGRAPHY GEO","LRTB",1);
	$pdf->Cell($height1,$width,"CSSA04 HISTORY HIS","LRTB",0);$pdf->Cell($height1,$width,"CSSA05 ISLAMIC STUDIES IST","LRTB",1);
	$pdf->Cell($height1,$width,"CSSA06 ARABIC ARA","LRTB",0);$pdf->Cell($height1,$width,"CSSA07 ENGLISH(CORE) ENG","LRTB",0);
	$pdf->SetFont('Arial','',12);$pdf->Cell(60,$width,"","",0,'C');$pdf->Cell(40,$width,"Tabulator I","",0,'C');$pdf->Cell(40,$width,"Tabulator II","",0,'C');$pdf->Cell(40,$width,"Tabulation","",0,'C');$pdf->Cell(40,$width,"Asstt Controller","",0,'C');$pdf->Cell(40,$width,"Controller","",1,'C');
	$pdf->SetFont('Arial','',7);$pdf->Cell($height1,$width,"CSSA08 PERSIAN PER","LRTB",0);$pdf->Cell($height1,$width,"CSSA09 URDU LITERATURE URD","LRTB",0);
	$pdf->SetFont('Arial','',12);$pdf->Cell(60,$width,"","",0,'C');$pdf->Cell(40,$width,"","",0,'C');$pdf->Cell(40,$width,"","",0,'C');$pdf->Cell(40,$width,"Incharge","",0,'C');$pdf->Cell(40,$width,"of Examination","",0,'C');$pdf->Cell(40,$width,"of Examination","",1,'C');
	$pdf->SetFont('Arial','',7);$pdf->Cell($height1,$width,"CSSA10 POLITICAL SCIENCE POL","LRTB",0);$pdf->Cell($height1,$width,"CSSA11 SOCIOLOGY SOC","LRTB",1);
	$pdf->Cell($height1,$width,"CSSA12 HINDI LITERATURE HIN","LRTB",0);$pdf->Cell($height1,$width,"CSSA13 HOMESCIENCE HOM","LRTB",1);
	$pdf->Cell($height1,$width,"CSSA14 MULTIMEDIA AND WEB MMT","LRTB",0);$pdf->Cell($height1,$width,"CSSS03 MATHEMATICS MAT","LRTB",0);$pdf->SetFont('Arial','',10);$pdf->Cell(200,$width,"Date Of Issue: ".date('d-m-Y'),"LRTB",1,'R');

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
$q = "select sid, sname, roll, enroll from class12 where private='yes' and cat='$cat' group by sid";
$retval = mysqli_query($conn,$q);
$allValues = array();
while( $row = mysqli_fetch_array($retval, MYSQL_ASSOC))
{
		$values = array();
		$code = array();
		array_push($values,$row['roll']);
		array_push($values,$row['sname']);
		array_push($values,$row['enroll']);
		$ia=array();$iap=array();
		$ue=array();$uep=array();
		$iam=array();$iapm=array();
		$uem=array();$uepm=array();
		$authGranted =true;
		$total = 0;
		$totalm = 0;
		$gt = 0;
		$gtm = 0;
		$s = "select s.code as code, c.ia as ia, c.ue as ue, c.sauth as auth, c.uep as uep, c.iap as iap, s.ia as iam, s.ue as uem, s.uep as uepm, s.iap as iapm from class12 c, subject12 as s where s.code = c.subject_code and sid = ".$row['sid']. " and s.private = c.private and c.private = 'yes'";//" and c.total > 0 ";
		$retval2 = mysqli_query($conn,$s);
		//echo mysqli_num_rows($retval2)."<br>";
		$c=0;
		while($row2 = mysqli_fetch_array($retval2,MYSQL_ASSOC))
		{
			array_push($values,$row2['code']);
			array_push($code,$row2['code']);
			array_push($ia,$row2['ia']);
			array_push($iam,$row2['iam']);
			array_push($iap,$row2['iap']);
			array_push($iapm,$row2['iapm']);
			array_push($ue,$row2['ue']);
			array_push($uep,$row2['uep']);
			array_push($uem,$row2['uem']);
			array_push($uepm,$row2['uepm']);
			/*if(($row2['uem']+$row2['uepm']) == 0)
				array_push($values,"-/-");
			else
				array_push($values,($row2['ue']+$row2['uep'])."/".($row2['uem']+$row2['uepm']));

			if(($row2['iam']+$row2['iapm']) == 0)
				array_push($values,"-/-");
			else
				array_push($values,($row2['ia']+$row2['iap'])."/".($row2['iam']+$row2['iapm']));

			if(($row2['uem']+$row2['iam']+$row2['iapm']+$row2['uepm']) == 0)
				array_push($values,"-/-");
			else	
				array_push($values,($row2['ue']+$row2['ia']+$row2['iap']+$row2['uep'])."/".($row2['uem']+$row2['iam']+$row2['iapm']+$row2['uepm']));
			$total += $row2['ue']+$row2['ia']+$row2['iap']+$row2['uep'];
			$totalm += $row2['uem']+$row2['iam']+$row2['iapm']+$row2['uepm'];*/

			if($row2['ue'] < 0)
				array_push($values,"Ab");
			else 
				array_push($values,$row2['ue']."/".$row2['uem']);

			if($row2['uepm'] == 0)
				array_push($values,"--/--");
			else if($row2['ue'] < 0)
				array_push($values,"Ab");
			else 
				array_push($values,$row2['uep']."/".$row2['uepm']);

			$total = marks($row2['ue']) + marks($row2['uep']);
			$totalm = marks($row2['uem']) + marks($row2['uepm']);
			array_push($values,$total."/".$totalm);
			$gt += $total;
			$gtm += $totalm;
			$c++;
		}
		
		if($c < 7)
		{
			$lag = 7 - $c;
			for($i=0;$i<$lag*4;$i++)
			{
				array_push($values,"NA");
			}
		}

		//echo "CODES: ".json_encode($code);echo "<br><br>";
		$remark = remarks($ia,$ue,$iap,$uep,$iam,$uem,$iapm,$uepm,$c,$code);
		array_push($values,"$gt/$gtm");
		array_push($values,$remark);
		array_push($allValues,$values);
		//echo json_encode($allValues);
		//Values($pdf,$values);
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
		BottomPageArts($pdf);
	}
	
	$pdf->Output();
}
?>