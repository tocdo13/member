<?php
$GLOBALS['report_header_rows'] = array();
class Report
{
	var $items;
	function get_split_values()
	{
		$this->split_samples = array();
		foreach($this->split_columns as $split)
		{
			$this->split_samples[$split] = array();
			switch($split)
			{
			case 'day':
				for($i=1;$i<=31;$i++)
				{
					$this->split_samples[$split][$i] = $i;
				}
				break;
			case 'month':
				for($i=1;$i<=12;$i++)
				{
					$this->split_samples[$split][$i] = $i;
				}
				break;
			default:
				foreach($this->items as $item)
				{
					$this->split_samples[$split][$item[$split]] = $item[$split];
				}
				break;
			}
		}
	}
	function split_column($primary_column)
	{
		$this->total_all_row = 0;
		$column_count = array();
		$count = sizeof($this->split_values);
		foreach($this->split_columns as $split_column)
		{
			$column_count[$split_column] = $count;
			$count *= sizeof($this->split_samples[$split_column]);
		}
		$sample = array();
		$this->split_total_columns = array();
		for($i=0;$i<$count;$i++)
		{
			$sample[$i] = '';
			$this->split_total_columns[$i] = 0;
		}
		$this->split_total_columns_sample = $this->split_total_columns;
		$reverse_values = array();
		foreach($this->split_columns as $split_column)
		{
			$reverse_values[$split_column] = array_flip(array_values($this->split_samples[$split_column]));
		}
		$all_items = array();

		foreach($this->items as $item)
		{
			if(!isset($all_items[$item[$primary_column]]))
			{
				$all_items[$item[$primary_column]] = $item+array('total_of_row'=>0,'_splits'=>$sample);
			}
			$index = 0;
			$multi = sizeof($this->split_values);
			foreach($this->split_columns as $split_column)
			{
				$item[$split_column] = intval($item[$split_column]);
				$index = $index*$multi+$reverse_values[$split_column][isset($item[$split_column])?$item[$split_column]:$split_column];
				$multi *= sizeof($this->split_samples[$split_column]);
			}
			$temp = System::calculate_number($item[reset($this->split_values)]);
			if($temp)
			{
				$all_items[$item[$primary_column]]['total_of_row']+=$temp;
				$this->total_all_row +=$temp;
			}
			foreach($this->split_values as $key=>$split_value)
			{
				if($temp=System::calculate_number($item[$split_value]))
				{
					$this->split_total_columns[$index]+=$temp;
				}
				$all_items[$item[$primary_column]]['_splits'][$index++] = $item[$split_value];
			}
		}
		$this->items = $all_items;
	}
	function show_split_columns($split_columns,$split_samples,$split_values,$colspan,$sample=false, $rowspan=1)
	{
		$st = '';
		foreach($split_samples[reset($split_columns)] as $value)
		{
			$st .= '<th '.(($rowspan>1)?' rowspan="'.$rowspan.'" ':'').'colspan="'.$colspan.'" class="report_table_header" align="center">'.$value.'</th>';
		}
		$colspan*=sizeof($split_samples[reset($split_columns)]);
		if($sample)
		{
			$st .= '</tr><tr>';
			if(reset($GLOBALS['report_header_rows']))
			{
				$st .= array_shift($GLOBALS['report_header_rows']);
			}
			foreach($split_samples[current($split_columns)] as $value)
			{
				$st .= $sample;
			}
		}
		if(sizeof($split_columns)>1)
		{
			array_shift($split_columns);
			return Report::show_split_columns($split_columns, $split_samples,$split_values,$colspan,$st,$rowspan-1);
		}
		else
		{
			return $st;
		}
	}
	function get_array_depth($labels)
	{
		$max = 1;
		if(!is_array($labels))
		{
			return 0;
		}
		foreach($labels as $key=>$label)
		{
			if(is_array($label))
			{
				if($max<=Report::get_array_depth($label))
				{
					$max = Report::get_array_depth($label)+1;
				}
			}
		}
		return $max;
	}
	function get_array_width($labels)
	{
		$width = 0;
		if(!is_array($labels))
		{
			return 0;
		}
		foreach($labels as $key=>$label)
		{
			if(is_array($label))
			{
				$width += Report::get_array_width($label);
			}
			else
			{
				$width++;
			}
		}
		return $width;
	}
	function make_header($labels,$rowspan,$show_column_number=false)
	{
		$labels = str_replace(array('('),array('\'=>('),$labels);
		$labels = '('.$labels.')';
		$labels = str_replace(array('(',',',')'),array('array(\'','\',\'','\')'),$labels);
		$labels = str_replace(array(')\''),array(')'),$labels);
		eval('$labels='.$labels.';');
		$level = Report::get_array_depth($labels);
		
		for($i=0;$i<$level;$i++)
		{
			$rows[$i] = '';
		}
		$GLOBALS['report_header_rowspan'] = max($rowspan,$level);
		Report::make_sub_header($labels, $rows, 0, max($rowspan,$level));
		$st = array_shift($rows);
		$GLOBALS['report_header_rows'] = $rows;
		return $st;
	}
	function make_sub_header($labels, &$rows, $level, $rowspan)
	{
		foreach($labels as $label=>$sub_labels)
		{
			if(!is_array($sub_labels))
			{
				$rows[$level] .= '<th rowspan="'.($rowspan-$level).'" class="report_table_header">[[.'.$sub_labels.'.]]</th>';
			}
			else
			{
				$sub_level = Report::get_array_depth($label);
				$rows[$level] .= '<th colspan="'.Report::get_array_width($sub_labels).'" class="report_table_header">[[.'.$label.'.]]</th>';
				Report::make_sub_header($sub_labels, $rows, $level+1, $rowspan);
			}
		}
	}
	function display_date_params()
	{
		if(URL::get('year'))
		{
			$year = get_time_parameter('year', date('Y'), $end_year);
			$month = get_time_parameter('month', date('m'), $end_month);
			$day = get_time_parameter('day', date('d'), $end_day);
			$end_year = URL::get('end_year',$end_year);
			$end_month = URL::get('end_month',$end_month);
			$end_day = URL::get('end_day',$end_day);
		}
		else
		{
			$year = URL::get('from_year');
			$end_year = URL::get('end_year',URL::get('to_year',URL::get('from_year')));
			$month = URL::get('from_month');
			$end_month = URL::get('end_month',URL::get('to_month',URL::get('from_month')));
			$day = URL::get('from_day');
			$end_day = URL::get('end_day',URL::get('to_day',URL::get('from_day')));
		}
		if(URL::get('day')||URL::get('from_day'))
		{
			if($end_day!=$day)
			{
				echo 'T&#7915; ng&#224;y '.$day.'/'.$month.'/'.$year.' &#273;&#7871;n ng&#224;y '.$end_day.'/'.$end_month.'/'.$end_year;
			}
			else
			{
				echo 'Ng&#224;y '.$day.'/'.$month.'/'.$year;
			}
		}
		else
		if(URL::get('day')||URL::get('from_day'))
		{
			if($end_day!=$day)
			{
				echo 'T&#7915; ng&#224;y '.$day.'/'.$month.'/'.$year.' &#273;&#7871;n ng&#224;y '.$end_day.'/'.$end_month.'/'.$end_year;
			}
			else
			{
				echo 'Ng&#224;y '.$day.'/'.$month.'/'.$year;
			}
		}
		else
		if(URL::get('month')||URL::get('from_month'))
		{
			if($end_month!=$month)
			{
				if($end_year!=$year)
				{
					echo 'T&#7915; th&#225;ng '.$month.'/'.$year.' &#273;&#7871;n th&#225;ng '.$end_month.'/'.$end_year;
				}
				else
				{
					switch(URL::get('month'))
					{
					case '1-3':echo 'Qu&#253; I n&#259;m '.$year;break;
					case '4-6':echo 'Qu&#253; II n&#259;m '.$year;break;
					case '7-9':echo 'Qu&#253; III n&#259;m '.$year;break;
					case '10-12':echo 'Qu&#253; IV n&#259;m '.$year;break;
					default:echo 'T&#7915; th&#225;ng '.$month.' &#273;&#7871;n th&#225;ng '.$end_month.' n&#259;m '.$year;
					}
				}
			}
			else
			{
				echo 'Th&#225;ng '.$month.' n&#259;m '.$year;
			}
		}
		else
		if(URL::get('year')||URL::get('from_year'))
		{
			if($end_year!=$year)
			{
				echo 'T&#7915; n&#259;m '.$year.' &#273;&#7871;n n&#259;m '.$end_year;
			}
			else
			{
				echo 'N&#259;m '.$year;
			}
		}

	}
	function count($account_id, $type, $date, $start_date=false, $corresponding = false)
	{
		$type=str_replace(array('debit','credit'),array('IF(voucher_detail.amount<0,abs(voucher_detail.amount),0)','IF(voucher_detail.amount>=0,voucher_detail.amount,0)'),$type);
		$cond = 'voucher.date<"'.Date_Time::to_sql_date($date).'"
				'.($start_date?'and voucher.date>="'.Date_Time::to_sql_date($start_date).'"':'');
		if(strpos($account_id,','))
		{
			$cond .= ' and account_id in ('.$account_id.')';
		}
		else
		{
			$cond .= ' and account_id = "'.$account_id.'"';
		}
		
		if(strpos($corresponding,','))
		{
			$cond .= ' and related_ids in ('.$corresponding.')';
		}
		else
		if($corresponding)
		{
			$cond .= ' and related_ids = "'.$corresponding.'"';
		}
		
		return DB::fetch('
			select
				sum('.$type.') as total
			from
				voucher_detail
				inner join voucher on voucher_id = voucher.id
			where
				'.$cond.'
				'.(is_disabled_accounting()?' and disabled=0':'').'
			','total'
		);
	}
}
function is_disabled_accounting()
{
	return !isset($_SESSION['enabled_accounting']) or $_SESSION['enabled_accounting']!=1;
}
?>