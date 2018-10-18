<?php
class RoomFocastTypeFormByYear extends Form
{
	function RoomFocastTypeFormByYear()
	{
		Form::Form('RoomFocastTypeFormByYear');
		$this->link_js("packages/core/includes/js/jquery/datepicker.js");
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_css("skins/default/report.css");
	}
	function draw()
	{	   
        if(1==1)
        {
            require_once 'packages/core/includes/utils/time_select.php';
    		require_once 'packages/core/includes/utils/lib/report.php';
    		$end_time = Url::get('year')?Url::get('year'):date('Y') + 1;
            $start_time = $end_time - 2;
            $this->map = array();
    		$sql = '
    			select 
    				count(*) as acount
    				,room_level.id as id 
    				,room_level.name as name
    			from 
    				room_level 
    				left join room on room_level.id = room.room_level_id
    			where 
    				(room_level.is_virtual is null or room_level.is_virtual <> 1)
                    and room_level.portal_id = \''.PORTAL_ID.'\'
    			group by 
    				room_level.id
    				,room_level.name
                order by 
                    room_level.name';
    		$room_level_count = DB::fetch_all($sql);	
    		$sql = 'select 
                    id,
                    in_date,
                    count(acount) as acount
                    from
                    (
                        select 
                        count(room_status.id) as acount ,
                        to_char(in_date,\'YYYY\') as in_date ,
                        reservation_room.room_level_id || \'_\' || to_char(room_status.in_date,\'YYYY\') as id 
                        from 
                        room_status inner join reservation_room on reservation_room.id = room_status.reservation_room_id
                        inner join room_level on room_level.id = reservation_room.room_level_id 
                        left join room on room.id = reservation_room.room_id	 
                        where 
                        (room_level.is_virtual is null or room_level.is_virtual <> 1) 
                        and (reservation_room.status !=\'CANCEL\') 
                        and room_status.in_date>=\'01-JAN-'.$start_time.'\'
                        and room_status.in_date<=\'31-DEC-'.$end_time.'\'
                        and reservation_room.departure_time >= room_status.in_date group by room_status.in_date ,reservation_room.room_level_id 
                        ORDER BY 
                        reservation_room.room_level_id
                    )
                    group by 
                    id,
                    in_date
                    order by id
                        ';	
                        /*
                        and extract(year from to_date(room_status.in_date,\'DD/MM/YYYY\'))>=\''.$start_time.'\'
                        and extract(year from to_date(room_status.in_date,\'DD/MM/YYYY\'))<=\''.$end_time.'\'
                        */
    		$items = DB::fetch_all($sql);
            //System::debug($sql);
            //echo $sql;
            //System::debug($items);
            //Tính VD
            
            $sql_vd = '
                        Select
                            room_level.id as id,
                            room_level.brief_name, 
                            to_char( extra_service_invoice_detail.in_date, \'DD/MM\' ) as in_date,
                            Sum(extra_service_invoice_detail.quantity) as quantity 
                        from
                            reservation_room
                            inner join extra_service_invoice on extra_service_invoice.reservation_room_id = reservation_room.id
                            inner join extra_service_invoice_detail on extra_service_invoice.id = extra_service_invoice_detail.invoice_id
                            inner join room_level on room_level.id = reservation_room.room_level_id
                        where
                            extra_service_invoice.portal_id = \''.PORTAL_ID.'\'
                            and extra_service_invoice.payment_type = \'ROOM\'
                            and extra_service_invoice_detail.in_date>=\'01-JAN-'.$start_time.'\'
                            and extra_service_invoice_detail.in_date<=\'31-DEC-'.$end_time.'\'
                            
                        GROUP BY
                            room_level.id,
                            room_level.brief_name, 
                            to_char( extra_service_invoice_detail.in_date, \'DD/MM\' )
                        ORDER BY
                            to_char( extra_service_invoice_detail.in_date, \'DD/MM\' )
                        ';
                        /*
                        and extract(year from to_date(extra_service_invoice_detail.in_date,\'DD/MM/YYYY\'))>=\''.$start_time.'\'
                            and extract(year from to_date(extra_service_invoice_detail.in_date,\'DD/MM/YYYY\'))>=\''.$end_time.'\'
                        */
            //System::debug($sql_vd);
            $ex_items = DB::fetch_all($sql_vd);
            //System::debug($ex_items);
            //System::debug($items);
    		$months = array();
    		$days = array();
    		$arr = array();
            //System::debug($room_level_count);
    		foreach($room_level_count as $room_level_id=>$room_level_value)
    		{
    			$months[$room_level_id] = $room_level_value;
                $months[$room_level_id]['acount'] = $room_level_value['acount']*365;
    			$months[$room_level_id]['days'] = array();
    			$begin = $start_time;
    			while($begin<=$end_time)
    			{
    				$day = $begin;
    				$days[$day]['day'] = $day;	
     				$months[$room_level_id]['days'][$day]['day'] = $day;				
    				$sub_id = $room_level_id.'_'.$begin;
    				if(isset($items[$sub_id]) and $items[$sub_id]['in_date'] == $begin)
    				{
    					if(isset($days[$day]['total']))
    					{		
    						$days[$day]['total'] += $items[$sub_id]['acount'];
    					}	
    					else
    					{
    						$days[$day]['total'] = $items[$sub_id]['acount'];
    					}
    					$months[$room_level_id]['days'][$day]['resold'] = $items[$sub_id]['acount'];
    				}
    				else
    				{
    					if(!isset($days[$day]['total']))
    					{
    						$days[$day]['total'] = 0;
    					}
    					$months[$room_level_id]['days'][$day]['resold'] = 0;
    				}
    				$months[$room_level_id]['days'][$day]['total'] = $room_level_value['acount']*365;
                    //---Cong them VD
                    
                    if( isset($ex_items[$room_level_id]) and $day==$ex_items[$room_level_id]['in_date'])
                    {
                        $months[$room_level_id]['days'][$day]['resold'] += $ex_items[$room_level_id]['quantity'];
                        $days[$day]['total'] +=  $ex_items[$room_level_id]['quantity'];
                    }
                        
                    
                    //---    
    				$begin += (1);                                                                                     
    			}
    		}
           // System::debug($months);
            $this->parse_layout('by_year',
    			array(
    				'months'=>$months,
    				'days'=>$days
    			)
    		);
        }	   
        //xem theo nam
        	   
	}
}
?>