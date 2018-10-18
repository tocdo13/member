<?php
class BarReservationCancelledReportForm extends Form
{
	function BarReservationCancelledReportForm()
	{
		Form::Form('BarReservationCancelledReportForm');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
	}
	function draw()
	{   
	    $this->map=array();
		if(URL::get('do_search'))
		{
			require_once 'packages/core/includes/utils/time_select.php';
	     	require_once 'packages/core/includes/utils/lib/report.php';
            $from_date_tan = Date_Time::to_time(Url::get('from_date_tan'));
            $to_date_tan = Date_Time::to_time(Url::get('to_date_tan'));
            $bars = DB::fetch_all('select * from bar');
			$bar_ids = '';
            $bar_name = '';
			foreach($bars as $k => $bar){
				if(Url::get('bar_id_'.$k)){
					$bar_ids .= ($bar_ids=='')?$k:(','.$k);	
                    $bar_name .= ($bar_name=='')?$bar['name']:(', '.$bar['name']);
				}
			}
            //System::debug($bar_ids);
            $bar_ids  = $bar_ids?$bar_ids:0;
            $_REQUEST['bar_name'] = $bar_name;
             $this->map['bar_name'] = $bar_name;
            $this->map['start_page'] = Url::iget('start_page')?Url::iget('start_page'):1;
            $this->line_per_page = URL::get('line_per_page',15);
            $cond = '
					1>0 '
					.(Url::get('hotel_id')?' and bar_reservation.portal_id=\''.Url::get('hotel_id').'\'':' and bar_reservation.portal_id=\''.PORTAL_ID.'\'')
			      .(UrL::get('bar_id')?' and bar_reservation.bar_id = '.URL::get('bar_id').'':'')   
            ;
			$fee_summary = DB::fetch('
				SELECT 
					sum(total) as grant_total_USD,sum(deposit) as grant_deposit
				FROM 
					bar_reservation 
				WHERE '.$cond.'
			');
			$sql = 'select 
					bar_reservation.id,
                    bar_reservation.code,
					bar_reservation.agent_name as customer_name,
					bar_reservation.arrival_time,
					bar_reservation.time,
					bar_reservation.cancel_time,
					bar_reservation.status,
					sum(bar_reservation.total) as total,
					bar_reservation.deposit,
					bar_reservation.user_id,
					bar_reservation.lastest_edited_user_id,
                    bar_reservation.note,
					ROWNUM as rownumber
				from 
					bar_reservation 
					LEFT OUTER JOIN bar_reservation_product ON bar_reservation_product.bar_reservation_id = bar_reservation.id
				where '.$cond.' AND bar_reservation.bar_id in ('.$bar_ids.')
					and (
							(
								bar_reservation.status=\'CANCEL\'
								AND bar_reservation.cancel_time >=\''.$from_date_tan.'\' and bar_reservation.cancel_time<\''.($to_date_tan+24*3600).'\'
							)
						)
				group by 
					bar_reservation.id
                    ,bar_reservation.code
					,bar_reservation.agent_name
					,bar_reservation.arrival_time
					,bar_reservation.time
					,bar_reservation.cancel_time
					,bar_reservation.total
					,bar_reservation.deposit
					,bar_reservation.status
					,bar_reservation.user_id
                    ,bar_reservation.note
					,bar_reservation.lastest_edited_user_id
					,ROWNUM
				order by 
					bar_reservation.id
			';
			DB::query($sql);
			require_once 'packages/core/includes/utils/lib/report.php';
			$report = new Report;
			$report->items = DB::fetch_all();
			$i = 1;
			foreach($report->items as $key=>$item)
			{
				$report->items[$key]['stt'] = $i++;
				$report->items[$key]['reservation_date'] = date('d/m/Y H:i',$item['arrival_time']);
				$report->items[$key]['cancel_date'] = date('d/m/Y H:i',(($item['status']=='CANCEL')?$item['cancel_time']:$item['time']));
				
				
				if($item['total']==0)
				{
					$report->items[$key]['total']='';
				}
				else
				{
					$report->items[$key]['total']=System::display_number($item['total']);
				} if($item['total']==0)
				{
					$report->items[$key]['total']='';
				}
				else
				{
					$report->items[$key]['total']=System::display_number($item['total']);
				} 
			}
			$this->print_all_pages($report,$fee_summary);
		}
		else
		{
			if(Url::get('hotel_id'))
             {
                 if(Url::get('hotel_id')!='ALL')
                 {
                     $bars = DB::fetch_all("select id,name FROM bar where portal_id='".Url::get('hotel_id')."'");
                 }
                 else
                 {
                    $bars = DB::select_all('bar',false); 
                 }
             }
             else
             {
                $bars = DB::select_all('bar',false); 
             }
            $this->parse_layout('search',
				array(
				'bar_id' => URL::get('bar_id',''),
				'bar_id_list' =>String::get_list(DB::select_all('bar',false)), 
				'hotel_id_list'=>array(''=>Portal::language('all'))+String::get_list(Portal::get_portal_list()),
				'bars' =>$bars, 
				'hotel_id_list'=>array(''=>Portal::language('all'))+String::get_list(Portal::get_portal_list())
			     
				
                
                )
			);	
		}			
	}
	function print_all_pages(&$report,$fee_summary)
	{
		$count = 0;
		$total_page = 1;
		$pages = array();
		$to_day=Date_Time::day_of_month(date('m'),date('Y'));
		$status="0";
		foreach($report->items as $key=>$item)
		{
			if($count>=$this->line_per_page)
			{
				$count = 0;
				$total_page++;
			}
			$pages[$total_page][$key] = $item;
			$count++;
		}
		if(sizeof($pages)>0)
		{
			$this->group_function_params = array(
				    'deposit'=>0, 'deposit_count'=>0, 
					'total'=>0, 'total_count'=>0,
				);
			foreach($pages as $page_no=>$page)
			{
				$this->print_page($page, $report, $page_no,$total_page,$fee_summary);
			}
		}
		else
		{
			$this->parse_layout('header',
			get_time_parameters()+
				array(
					'page_no'=>1,
					'total_page'=>1,
				)+$this->map
			);
			$this->parse_layout('footer',array(
				'page_no'=>1,
				'total_page'=>1
			)+$this->map
            );
		}
	}
	function print_page($items, &$report, $page_no,$total_page,$fee_summary)
	{
		$status="0";
		$last_group_function_params = $this->group_function_params;
		foreach($items as $item)
		{
		    if($temp=System::calculate_number($item['deposit']))
			{
				$this->group_function_params['deposit'] += $temp;
			}
			$this->group_function_params['deposit_count'] ++; 
			if($temp=System::calculate_number($item['total']))
			{
				$this->group_function_params['total'] += $temp;
			}
			$this->group_function_params['total_count'] ++;
		}
		if($page_no>=$this->map['start_page'])
		{
        $this->parse_layout('header',
			array(
				'page_no'=>$page_no,
				'total_page'=>$total_page
			)+$this->map
		);
		$this->parse_layout('report',
			(($page_no==$total_page)?$fee_summary:array())
			+array(
				'items'=>$items,
				'last_group_function_params'=>$last_group_function_params,
				'group_function_params'=>$this->group_function_params,
				'page_no'=>$page_no,
				'total_page'=>$total_page,
			)+$this->map
		);
		$this->parse_layout('footer',array(				
			'page_no'=>$page_no,
			'total_page'=>$total_page,
		)+$this->map
        );
	}
    }
}
?>