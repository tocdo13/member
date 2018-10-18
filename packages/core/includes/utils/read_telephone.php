<?php
class CRD
{
	function get_content($file_name)
	{
		$chunksize = 1*(1024*1024);
		$handle = @fopen($file_name, 'rb');
		if ($handle === false) 
		{
			return false;
		}
		$rows = array();
		while (!feof($handle)) 
		{
			$buffer = fread($handle, $chunksize);
			if(preg_match_all('/Ok:(.*)/',$buffer,$matches))
			{
				foreach($matches[1] as $key=>$value)
				{
					if($array = CRD::parse_content(explode(' ',$value)))
					{
						$rows[] = $array;
					}
				}
			}
		}
		fclose($handle);
		return $rows;	
	}
	function parse_content($array)
	{
		$field = array(1=>'HDate',2=>'Time'	,3=>'T',4=>'Ext',5=>'CO',6=>'Dial_Number',7=>'Ring_Duration',8=>'Date',9=>'Detail',10=>'Extend');
		if(is_array($array) and count($array)>10)
		{
			$row = array();
			$i = 1;
			foreach($array as $key=>$value)
			{
				if($value!="")
				{
					$row[$field[$i++]] = $value;
				}
			}
			if(isset($row['Ring_Duration']))
			{
				$row['Ring_Duration_Acct_code'] = CRD::get_ring_duration($row['Ring_Duration']);
			}
			return $row;
		}
		return false;		
	}	
	function get_ring_duration($time)
	{
		if(preg_match_all('/([0-9]*)([0-9]*)([0-9]*)/',$time,$matches))
		{
			if($matches[0] and isset($matches[0][0]) and isset($matches[0][2]) and isset($matches[0][4]))
			{
				return intval($matches[0][4]) + 60*intval($matches[0][2]) + 3600*intval($matches[0][0]) ;
			}
		}
		return 0;
	}
}	
?>