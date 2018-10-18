<?php
class KaraokeReservationCancelledReportForm extends Form
{
	function KaraokeReservationCancelledReportForm()
	{
		Form::Form('KaraokeReservationCancelledReportForm');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css'); 
	}
	function draw()
	{   
	    $this->map=array();
		if(URL::get('do_search'))
		{
			require_once 'packages/core/includes/utils/time_select.php';
	     	require_once 'packages/core/includes/utils/lib/report.php';
            $karaokes = DB::fetch_all('select * from karaoke');
			$karaoke_ids = '';
            $karaoke_name = '';
			foreach($karaokes as $k => $karaoke){
				if(Url::get('karaoke_id_'.$k)){
					$karaoke_ids .= ($karaoke_ids=='')?$k:(','.$k);	
                    $karaoke_name .= ($karaoke_name=='')?$karaoke['name']:(', '.$karaoke['name']);
				}
			}
            //System::debug($karaoke_ids);
            $karaoke_ids  = $karaoke_ids?$karaoke_ids:0;
            $_REQUEST['karaoke_name'] = $karaoke_name;
            $this->map['karaoke_name'] = $karaoke_name;
        	
            $from_day = Url::get('date_from');
            $to_day = Url::get('date_to');
			
            $this->line_per_page = URL::get('line_per_page',15);
			
            $cond = '
					1>0 '
					.(Url::get('hotel_id')?' and karaoke_reservation.portal_id=\''.Url::get('hotel_id').'\'':' and karaoke_reservation.portal_id=\''.PORTAL_ID.'\'')
			      .(UrL::get('karaoke_id')?' and karaoke_reservation.karaoke_id = '.URL::get('karaoke_id').'':'')   
            ;
			$fee_summary = DB::fetch('
				SELECT 
					sum(total) as grant_total_USD,sum(deposit) as grant_deposit
				FROM 
					karaoke_reservation 
				WHERE '.$cond.'
			');
			$sql = 'select * from(
				select 
					karaoke_reservation.id,
					karaoke_reservation.agent_name as customer_name,
					karaoke_reservation.arrival_time,
					karaoke_reservation.time,
					karaoke_reservation.cancel_time,
					karaoke_reservation.status,
					sum(karaoke_reservation.total) as total,
					karaoke_reservation.deposit,
					karaoke_reservation.user_id,
					karaoke_reservation.lastest_edited_user_id,
                    karaoke_reservation.note,
					ROWNUM as rownumber
				from 
					karaoke_reservation 
					LEFT OUTER JOIN karaoke_reservation_product ON karaoke_reservation_product.karaoke_reservation_id = karaoke_reservation.id
				where '.$cond.' AND karaoke_reservation.karaoke_id in ('.$karaoke_ids.')
					and (
							(
								karaoke_reservation.status=\'CANCEL\'
								AND karaoke_reservation.cancel_time >=\''.Date_Time::to_time($from_day).'\' and karaoke_reservation.cancel_time<\''.(Date_Time::to_time($from_day)+24*3600).'\'
							)
						)
				group by 
					karaoke_reservation.id
					,karaoke_reservation.agent_name
					,karaoke_reservation.arrival_time
					,karaoke_reservation.time
					,karaoke_reservation.cancel_time
					,karaoke_reservation.total
					,karaoke_reservation.deposit
					,karaoke_reservation.status
					,karaoke_reservation.user_id
                    ,karaoke_reservation.note
					,karaoke_reservation.lastest_edited_user_id
					,ROWNUM
				order by 
					karaoke_reservation.id
			)
			where
				rownumber > '.((URL::get('start_page')-1)*$this->line_per_page).' and rownumber<='.((URL::get('no_of_page'))*$this->line_per_page).'';
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
				$report->items[$key]['code'] = '';
				for($j=0;$j<6-strlen($item['id']);$j++)
				{
					$report->items[$key]['code'] .= '0';
				}
				$report->items[$key]['code'].=$item['id'];
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
		    $_REQUEST['date_from'] = date('d/m/Y');
            $_REQUEST['date_to'] = date('d/m/Y');
			$this->parse_layout('search',
				array(
				'karaoke_id' => URL::get('karaoke_id',''),
				'karaoke_id_list' =>String::get_list(DB::select_all('karaoke',false)), 
				'hotel_id_list'=>array(''=>Portal::language('all'))+String::get_list(Portal::get_portal_list()),
			
				'karaokes' =>DB::select_all('karaoke',false), 
				'hotel_id_list'=>array(''=>Portal::language('all'))+String::get_list(Portal::get_portal_list())
			     
				
                
                )
			);	
		}			
	}
	function print_all_pages(&$report,$fee_summary)
	{
	    $from_day = Url::get('date_from');
        $to_day = Url::get('date_to');
		$count = 0;
		$total_page = 1;
		$pages = array();
        
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
                    'to_date'=>$to_day,
                    'from_date'=>$from_day,
					'page_no'=>1,
					'total_page'=>1,
				)
			);
			$this->parse_layout('footer',array(
				'page_no'=>1,
				'total_page'=>1
			));
		}
	}
	function print_page($items, &$report, $page_no,$total_page,$fee_summary)
	{
        $from_day = Url::get('date_from');
        $to_day = Url::get('date_to');	
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
		$this->parse_layout('header',
			array(
				'page_no'=>$page_no,
				'total_page'=>$total_page,
				'to_date'=>$to_day,
                'from_date'=>$from_day
			)
		);
		$this->parse_layout('report',
			(($page_no==$total_page)?$fee_summary:array())
			+array(
				'items'=>$items,
				'last_group_function_params'=>$last_group_function_params,
				'group_function_params'=>$this->group_function_params,
				'page_no'=>$page_no,
				'total_page'=>$total_page,
			)
		);
		$this->parse_layout('footer',array(				
			'page_no'=>$page_no,
			'total_page'=>$total_page,
		));
	}
}
?>