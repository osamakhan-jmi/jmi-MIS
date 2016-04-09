<?php
//--------------------------------------------------------
		//Rules for grade
		function grade($value, $max)
		{
			$val = $value/$max;
			$val = $val*100;
			$val = round($val,2);
			if($val > 91 )
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
			$val = round($val,2);
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


?>