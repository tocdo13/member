<?php
// doc gia tien tieng viet	
	function currency_to_text($number,$vnd=true)
	{
		/*"1,234,567"
		Mot trieu hai tram ba muoi bon ngan nam tram sau muoi bay*/
		$text_arr = array(
			0=>'kh&#244;ng',
			1=>'m&#7897;t',
			2=>'hai',
			3=>'ba',
			4=>'b&#7889;n',
			5=>'n&#259;m',
			6=>'s&#225;u',
			7=>'b&#7843;y',
			8=>'t&#225;m',
			9=>'ch&#237;n',
			10=>'m&#432;&#7901;i'
		);
		$unit_arr = array(
			1000000000=>'t&#7927;',
			1000000=>'tri&#7879;u',
			1000=>'ngh&#236;n',
			100=>'tr&#259;m',
			10=>'m&#432;&#417;i'
		);
		$number_arr =  str_split($number,1);
		$text = '';
		if($number<=10 and $number>0){
			$text .= ' '.$text_arr[$number];
		}else{
			foreach($unit_arr as $key=>$value){
				if($number>=$key){
					$floor = floor($number/$key);
					if(strlen($floor)==1){
						if($number<20){
							$text .= ' m&#432;&#7901;i';
						}else{
							$text .= ' '.$text_arr[$floor];
						}
					}else{
						$text .= currency_to_text($floor);
					}
					//if($number>=20)
					{
						$text .= ' '.$value;
					}
					if($number>=101 and $number%$key<10 and $number%$key>0){
						$text .= ' linh';
					}
					if($number==25 or $number==35 or $number==45 or $number==55 or $number==65 or $number==75 or $number==85 or $number==95){
						$text .= ' l&#259;m';
					}elseif($number>=21 and $number<=91){
						if(substr($number,1)==1){
							$text .= ' m&#7889;t';
						}
					}
					else
					{
						if(substr($number,1)!=1)
						{
							$text .= currency_to_text($number%$key);
						}
					}
					break;
				}
			}
		}
		return $text;
	}
?>