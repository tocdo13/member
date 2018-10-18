<?php
class BanquetReservationCancelledReportForm extends Form
{
	function BanquetReservationCancelledReportForm()
	{
		Form::Form('BanquetReservationCancelledReportForm');
                $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
	}
	function draw()
	{
		if(URL::get('do_search'))
		{
			require_once 'packages/core/includes/utils/time_select.php';
			$from_day = Url::get('date_from');
            $to_day = Url::get('date_to');
			$this->line_per_page = URL::get('line_per_page',15);
            
            //Start Luu Nguyen Giap add portal
            if(Url::get('portal_id'))
            {
               $portal_id =  Url::get('portal_id');
            }
            else
            {
                $portal_id =PORTAL_ID;
            }
            if($portal_id!="ALL")
            {
                $cond ="  party_reservation.portal_id ='".$portal_id."' ";
            }
            else
            {
                $cond=" 1=1 ";
            } 
            //End Luu Nguyen Giap add portal
            
	
			$fee_summary = DB::fetch('
				SELECT 
					sum(total) as grant_total_USD,sum(deposit) as grant_deposit
				FROM 
					party_reservation 
				WHERE '.$cond.'
			');
			$sql = 'select * from(
				select 
					party_reservation.id,
					party_reservation.full_name as customer_name,
					party_reservation.checkin_time,
					party_reservation.cancel_time,
					party_reservation.status,
					sum(party_reservation.total) as total,
					party_reservation.deposit,
					party_reservation.user_id,
					party_reservation.lastest_edited_user_id,
                    party_reservation.note,
					ROWNUM as rownumber
				from 
				party_reservation
					left outer join party_reservation_detail on party_reservation_detail.party_reservation_id = party_reservation.id
					left outer join party_type on party_type.id = party_reservation.party_type
				where '.$cond.'
					and (
							(
								party_reservation.status=\'CANCEL\'
									AND party_reservation.cancel_time >=\''.Date_Time::to_time($from_day).'\' and party_reservation.cancel_time<\''.(Date_Time::to_time($to_day)+24*3600).'\'
							)
						)
				group by 
					party_reservation.id
					,party_reservation.full_name
					,party_reservation.checkin_time
					,party_reservation.cancel_time
					,party_reservation.total
					,party_reservation.deposit
					,party_reservation.status
					,party_reservation.user_id
                    ,party_reservation.note
					,party_reservation.lastest_edited_user_id
					,ROWNUM
				order by 
					party_reservation.id
			)
			where
				rownumber > '.((URL::get('start_page')-1)*$this->line_per_page).' and rownumber<='.((URL::get('no_of_page'))*$this->line_per_page).'';
			DB::query($sql);
            //System::debug($sql);
			require_once 'packages/core/includes/utils/lib/report.php';
			$report = new Report;
			$report->items = DB::fetch_all();
			$i = 1;
			foreach($report->items as $key=>$item)
			{
				$report->items[$key]['stt'] = $i++;
				$report->items[$key]['reservation_date'] = date('d/m/Y H:i',$item['checkin_time']);
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
            //System::debug($report->items);
			$this->print_all_pages($report,$fee_summary);
		}
		else
		{
		    $_REQUEST['date_from']=date('d/m/Y');
            $_REQUEST['date_to']=date('d/m/Y');
			$this->parse_layout('search',
				array(
				'party_id' => URL::get('party_id',''),
				'party_id_list' =>String::get_list(DB::select_all('party',false)), 
				'portal_id_list'=>array(''=>Portal::language('all'))+String::get_list(Portal::get_portal_list())
				)
			);	
		}			
	}
	function print_all_pages(&$report,$fee_summary)
	{
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
		    $from_day = Url::get('date_from');
            $to_day = Url::get('date_to');
			$this->parse_layout('header',
			get_time_parameters()+
				array(
					'page_no'=>1,
					'total_page'=>1,
                    'to_date'=>$to_day,
                    'from_date'=>$from_day					
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