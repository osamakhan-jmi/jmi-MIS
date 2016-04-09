<?php

	function year($var)
	{
		$c = 0;
		$flag = 0;
		$v = array();
		$val = "";
		$var = (int)$var;
		while($var > 0)
		{
			$r = $var % 10;
			$var = $var / 10;
			$c = $c + 1;
			array_push($v,$r);
		}

			switch($v[3])
			{
				case 1: $val.="nineteen hundred ";break;
				case 2: $val.="two thousand ";break; 
			}

			if($v[1] == 1)
			{
				switch($v[0])
				{
					case 1: $val.="eleven ";break;
					case 2: $val.="twelve ";break; 
					case 3: $val.="thirteen ";break; 
					case 4: $val.="fourteen ";break; 
					case 5: $val.="fifteen ";break; 
					case 6: $val.="sixteen ";break; 
					case 7: $val.="seventeen ";break; 
					case 8: $val.="eighteen ";break; 
					case 9: $val.="nineteen ";break; 
				}
				$flag = 1;
			}
			else
			{
				switch($v[1])
				{
					case 2: $val.="twenty ";break; 
					case 3: $val.="thirty ";break; 
					case 4: $val.="forty ";break; 
					case 5: $val.="fifty ";break; 
					case 6: $val.="sixty ";break; 
					case 7: $val.="seventy ";break; 
					case 8: $val.="eighty ";break; 
					case 9: $val.="ninety ";break; 
				}
			}
		
		if($flag == 0)
		{
			switch($v[0])
				{
					case 1: $val.="one";break;
					case 2: $val.="two";break; 
					case 3: $val.="three";break; 
					case 4: $val.="four";break; 
					case 5: $val.="five";break; 
					case 6: $val.="six";break; 
					case 7: $val.="seven";break; 
					case 8: $val.="eight";break; 
					case 9: $val.="nine";break; 
				}
		}
		return  $val;
	}

	function month($var)
	{
		switch($var){
		case 1: return  " January ";break;
		case 2: return  " February ";break;
		case 3: return  " March ";break;
		case 4: return  " April ";break;
		case 5: return  " May ";break;
		case 6: return  " June ";break;
		case 7: return  " July ";break;
		case 8: return  " August ";break;
		case 9: return  " September ";break;
		case 10: return  " October ";break;
		case 11: return  " November ";break;
		case 12: return  " December ";break;
		}
	}

	function day($var)
	{
		$c = 0;
		$flag = 0;
		$v = array();
		$val = "";
		$var = (int)$var;
		while($var > 0)
		{
			$r = $var % 10;
			$var = $var / 10;
			$c = $c + 1;
			array_push($v,$r);
		}
		
			if($v[1] == 0)
			{
				switch($v[0])
				{
					case 1: $val.=" first";break;
					case 2: $val.=" second";break; 
					case 3: $val.=" third";break; 
					case 4: $val.=" fourth";break; 
					case 5: $val.=" fifth";break; 
					case 6: $val.=" sixth";break; 
					case 7: $val.=" seventh";break; 
					case 8: $val.=" eighth";break; 
					case 9: $val.=" ninth";break; 
				}
			}
			else if($v[1] == 1)
			{
				switch($v[0])
				{	
					case 0: $val.="tenth ";break;
					case 1: $val.="eleventh ";break;
					case 2: $val.="twelfth ";break; 
					case 3: $val.="thirteenth ";break; 
					case 4: $val.="fourteenth ";break; 
					case 5: $val.="fifteenth ";break; 
					case 6: $val.="sixteenth ";break; 
					case 7: $val.="seventeenth ";break; 
					case 8: $val.="eighteenth ";break; 
					case 9: $val.="nineteenth ";break; 
				}
			}
			else
			{
				switch($v[1])
				{
					case 2: $val.="twent";break; 
					case 3: $val.="thirt";break;
				}
			
				switch($v[0])
				{
					case 0: $val.="ieth";break;
					case 1: $val.="y first";break;
					case 2: $val.="y second";break; 
					case 3: $val.="y third";break; 
					case 4: $val.="y fourth";break; 
					case 5: $val.="y fifth";break; 
					case 6: $val.="y sixth";break; 
					case 7: $val.="y seventh";break; 
					case 8: $val.="y eighth";break; 
					case 9: $val.="y ninth";break; 
				}
			}
		return  $val;
	}

/*for($i=1;$i<32;$i++)
{
	$dob = "$i-1-2015";
	$str = explode("-",$dob);

	$dinw = strtoupper
	(
		day($str[0]).
		month($str[1]).
		year($str[2])
	);
	echo $dob." ---- ".$dinw."<br>";
}*/
?>