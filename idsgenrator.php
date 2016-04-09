<?php

function year()
	{
		$m = date("m");
		$y = date("y");

		if($m >= 4 && $m <= 12)
			$y = $y % 100;
		else
			$y = ($y-1)%100;
		return $y;
	}

	function course($class,$sub,$spr)
	{
		if($class == 10&&$spr=="NO")
			return "CSC";
		else if($class == 10&&$spr=="YES")
			return "CSCP";
		else if($class==12&&$sub=="ART"&&$spr=="YES")
			return "CSSP";
		else if($class==12&&$spr=="NO")
			return "CSS";
	}

	function digits($class,$stream,$val,$place)
	{
		$str = $val;
		if($place == "enroll" || $class== 10)
		{
			switch(strlen($str))
			{
				case 1: return "000".$str;
				case 2: return "00".$str;
				case 3: return "0".$str;
				case 4: return $str;
			}
		}
		else
		{
			if($stream == "ART")
				$v=0;
			else if($stream == 'SCIENCE')
				$v=1;
			else
				$v=2;
			switch(strlen($str))
			{
				case 1: return $v."00".$str;
				case 2: return $v."0".$str;
				case 3: return $v.$str;
			}

		}
	}

?>