<?php
function make_export($map)
	{
		$report = array();
		$report['total_record'] = $map['total'];
		$report['item_per_page'] = Url::get('item_per_page',30);
		$items = $map['data_sheet'];
		$i=0;
		foreach($items as $key=>$value)
		{
			$items[$key] = array('0'=>$i++)+$value;
			if(isset($items[$key]['time']))
			{
				$items[$key]['time'] = date('d/m/Y',$value['time']);
			}	
			unset($items[$key]['id']);
		}
		$report['data_sheet'] = $items;
		// $title[-1] : title of sheet
		// $title[-2] : format width column of sheet
		// $title[-3] : format text center or left of cell
		// $title[-4] : column need total
		if(isset($map['positions']))
		{
			$report['positions'] = $map['positions'];
		}
		else
		{
			$report['positions'] = array(
					'0'=>array(
						'0'=>Portal::language('ke_toan_truong')
						,'1'=>Portal::language('giam_doc')
					),
					'1'=>array(
						'0'=>0
						,'1'=>4
					)
			);		
		}	
		$report['title_data_sheet'] = $map['title'];
		$report['report_name'] = ($map['revenue_report']);		
		$report['from_date'] = $map['from_date'];	
		$report['to_date'] = $map['to_date'];	
		$report['revenue_by'] = $map['revenue_by'];	
		$report['width'] = $map['width'];	
		if(isset($map['currency']))
		{
				$report['currency'] = $map['currency'];
		}		
		else
		{
			$report['currency'] = array(
				'0'=>0
				,'1'=>0
				,'2'=>0
				,'3'=>0
				,'4'=>0
				,'5'=>1
				,'6'=>1
			);
		}
		Session::set('report',$report);
	}
?>