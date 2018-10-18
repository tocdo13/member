<?php
class OccupancyHoldingReportForm extends Form
{
	function OccupancyHoldingReportForm()
	{
		Form::Form('OccupancyHoldingReportForm');
		$this->link_js("packages/core/includes/js/jquery/datepicker.js");
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_css("skins/default/report.css");
	}
	function draw()
	{
		require_once 'packages/core/includes/utils/time_select.php';
		require_once 'packages/core/includes/utils/lib/report.php';
		$time = time();
		if(Url::get('from_date') and Url::get('from_date')!="")
		{
			$time = Date_time::to_time(Url::get('from_date'));
		}
		$start_date = Date_time::to_orc_date(date('1/m/Y',$time));
		$end_date = Date_time::to_orc_date(date('1/m/Y',strtotime(date('m/1/Y',$time))+3*31*24*3600));
		
        
        //Start Luu Nguyen Giap add portal
        if(Url::get('portal_id'))
        {
            $portal_id = Url::get('portal_id');
        }
        else
        {
            $portal_id = PORTAL_ID;
			$_REQUEST['portal_id'] = PORTAL_ID;
        }
        if($portal_id != 'ALL')
        {
            $cond=" AND room.portal_id = '".$portal_id. "'";
            $cond_tan=" AND reservation.portal_id = '".$portal_id. "'";
            $cond_invoice = " AND extra_service_invoice.portal_id = '".$portal_id. "'";
            
        }
        else
        {
            $cond ='';
            $cond_tan = '';
            $cond_invoice =" AND extra_service_invoice.portal_id = '".PORTAL_ID. "'";
        }
        
        /** manh comment de dem lai so phong theo lich su
        $room_count = DB::fetch('select count(*) as acount 
        from room inner join room_level on room_level.id = room.room_level_id 
        where room_level.is_virtual is null or room_level.is_virtual <> 1 '.$cond,'acount');        
        **/   
        
        //End Luu Nguyen Giap add portal
        $repair_rooms = DB::fetch_all('select 
                                        to_char(room_status.in_date,\'yyyy-mm-dd\') as id, 
                                        count(*) as acount 
                                    from 
                                        room_status 
                                        inner join room on room_status.room_id = room.id
                                    where
                                        room_status.house_status = \'REPAIR\'
                     					and room_status.in_date >= \''.$start_date.'\'
                    					and room_status.in_date <= \''.$end_date.'\'
                                        '.$cond.'
                                    group by 
                                        room_status.in_date                                         
        ');
        //System::debug($repair_rooms);
		$sql = 'select 
					count(*) as acount
					,to_char(in_date,\'yyyy-mm-dd\') as in_date
					,room_status.id 
					,room_status.status
                    ,NVL(room_level.is_virtual,0) as is_virtual
                    ,reservation_room.change_room_from_rr
				from 
					room_status
					inner join reservation_room on reservation_room.id = room_status.reservation_room_id
                    inner join reservation on reservation_room.reservation_id = reservation.id
                    inner join room_level on room_level.id = reservation_room.room_level_id
					left join room on room.id = reservation_room.room_id
				where 
                    (room_level.is_virtual is null or room_level.is_virtual <> 1)
                        and
    			    (reservation_room.status !=\'CANCEL\' and reservation_room.status !=\'NOSHOW\')
 					and room_status.in_date>=\''.$start_date.'\'
					and room_status.in_date<=\''.$end_date.'\'
                    and (
                        reservation_room.departure_time > room_status.in_date
                        or 
                        (
                          reservation_room.departure_time = reservation_room.arrival_time 
                          and 
                            (
                              (reservation_room.change_room_from_rr is null and reservation_room.change_room_to_rr is null and reservation_room.time_in>=(date_to_unix(room_status.in_date)+(6*3600)))
                              or
                              ( reservation_room.change_room_from_rr is not null and reservation_room.change_room_to_rr is null
                                and from_unixtime(reservation_room.old_arrival_time) = reservation_room.departure_time
                                and reservation_room.old_arrival_time >= (date_to_unix(room_status.in_date)+(6*3600))
                              )
                            )
                        )
                    
                    )
                    --and (reservation_room.departure_time > room_status.in_date or (reservation_room.departure_time = reservation_room.arrival_time and reservation_room.time_in>=(date_to_unix(room_status.in_date)+(6*3600))))
                    --and (
                          --reservation_room.change_room_to_rr is null 
                          --or (reservation_room.change_room_to_rr is not null and from_unixtime(reservation_room.old_arrival_time)!=in_date)
                        --)
                    '.$cond_tan.' 
				group by
					in_date
					,room_status.id 
					,room_status.status
                    ,is_virtual
                    ,change_room_from_rr
				order by 
					in_date ASC';
        
		$items = DB::fetch_all($sql);
        /** kieu fix TH ngay 08-06-2016(báo cáo không khop với báo cáo chiem dung phong)) **/
        $reservation_virtual=DB::fetch_all('select reservation_room.id from reservation_room
                                                inner join room on reservation_room.room_id=room.id
                                                inner join room_level on room_level.id=room.room_level_id
                                            where
                                                room_level.is_virtual=1
                                                and reservation_room.change_room_to_rr is not null
                                            ');
        $rsolds = array();
        foreach($items as $key=>$value)
		{
		     if(isset($reservation_virtual[$value['change_room_from_rr']])){
		      //System::debug($items[$key]);
		      unset($items[$key]);
		     }
        }
        /** END kieu fix TH ngay 08-06-2016(báo cáo không khop với báo cáo chiem dung phong)) **/     
		foreach($items as $key=>$value)
		{
		     if(isset($reservation_virtual[$value['change_room_from_rr']])){
		      unset($items[$key]);
		     } 
			if(!isset($rsolds[$value['in_date']]))
			{
				$rsolds[$value['in_date']] = array('id'=>$value['in_date'],'acount'=>1);
			}
			else
			{			
				$rsolds[$value['in_date']]['acount'] ++;
			}
		}
       //System::debug($rsolds);
        /** ngay 29/8/17 lay them so luong li **/
        $orcl = '
                SELECT
                    esid.id,
                    to_char(esid.in_date,\'yyyy-mm-dd\') as in_date,
                    esid.quantity+nvl(esid.change_quantity,0) as quantity,
                    es.code
                FROM
                    extra_service_invoice_detail esid
                    INNER JOIN  extra_service_invoice esi ON esid.invoice_id=esi.id
                    INNER JOIN  reservation_room rr ON rr.id = esi.reservation_room_id
                    INNER JOIN  reservation r on rr.reservation_id = r.id
                    INNER JOIN  extra_service es ON es.id = esid.service_id
                    left JOIN  room_level on room_level.id = rr.room_level_id
                WHERE
                    (
                    es.code = \'LATE_CHECKIN\'
                    )
                    and rr.status !=\'CANCEL\' and rr.status !=\'NOSHOW\'
                    AND (room_level.is_virtual is null or room_level.is_virtual = 0)
                    AND esid.in_date >= \''.$start_date.'\' 
                    AND esid.in_date <= \''.$end_date.'\' 
                    AND esi.portal_id=\''.$portal_id.'\'
                ORDER BY esid.in_date
                ';
            $extra_ser_room = DB::fetch_all($orcl);
            foreach($extra_ser_room as $key => $value)
            {
                
                $night = $value['quantity'];
                if(!isset($rsolds[$value['in_date']]))
    			{
    				$rsolds[$value['in_date']] = array('id'=>$value['in_date'],'acount'=>$night);
    			}
    			else
    			{			
    				$rsolds[$value['in_date']]['acount'] +=$night;
    			}
            }
        /** and ngay 29/8/17 lay them so luong li **/
		$time = strtotime($start_date);
		$month = false;
		while($time<strtotime($end_date))
		{
			if($month!=date('M-y',$time))
			{
				$month = date('M-y',$time);
				$months[$month] = array(
					'name'=>$month,
					'total'=>0,
					'rsold'=>0,
					'percent'=>0,
					'count'=>0,
					'days'=>array()
				);
			}
            /** manh tinh lai tong so phong theo lich su **/
            $room_count = 0;
            $portal = Url::get('portal_id')?Url::get('portal_id'):PORTAL_ID;
            if($portal !='ALL')
            {
                //System::debug('select max(in_date) as in_date from room_history where in_date<=\''.Date_Time::to_orc_date(date('d/m/Y',$time)).'\' and portal_id=\''.$portal.'\'');
                if($his_in_date = DB::fetch('select max(in_date) as in_date from room_history where in_date<=\''.Date_Time::to_orc_date(date('d/m/Y',$time)).'\' and portal_id=\''.$portal.'\'','in_date'))
                {
                    $room_count = DB::fetch('select 
                                                count(rhd.room_id) as total_room 
                                            from
                                                room_history_detail rhd
                                                inner join room_history rh on rh.id=rhd.room_history_id
                                                inner join room_level on room_level.id = rhd.room_level_id
                                            where
                                                rh.in_date=\''.$his_in_date.'\'
                                                and rh.portal_id=\''.$portal.'\'
                                                and rhd.close_room=1
                                                and room_level.is_virtual = 0
                                                ','total_room');
                                                
                }
                elseif($his_in_date = DB::fetch('select min(in_date) as in_date from room_history where in_date>\''.Date_Time::to_orc_date(date('d/m/Y',$time)).'\' and portal_id=\''.$portal.'\'','in_date'))
                {
                    $room_count = DB::fetch('select 
                                                count(rhd.room_id) as total_room 
                                            from
                                                room_history_detail rhd
                                                inner join room_history rh on rh.id=rhd.room_history_id
                                                inner join room_level on room_level.id = rhd.room_level_id
                                            where
                                                rh.in_date=\''.$his_in_date.'\'
                                                and rh.portal_id=\''.$portal.'\'
                                                and rhd.close_room=1
                                                and room_level.is_virtual = 0
                                                ','total_room');
                                                //echo 2;
                }
            }
            else
            {
                $list_portal = Portal::get_portal_list();
                $total_room_all = 0;
                foreach($list_portal as $key=>$value)
                {
                    if($his_in_date = DB::fetch('select max(in_date) as in_date from room_history where in_date<=\''.Date_Time::to_orc_date(date('d/m/Y',$time)).'\' and portal_id=\''.$value['id'].'\'','in_date'))
                    {
                        $total_room_all += DB::fetch('select 
                                                    count(rhd.room_id) as total_room 
                                                from
                                                    room_history_detail rhd
                                                    inner join room_history rh on rh.id=rhd.room_history_id
                                                    inner join room_level on room_level.id = rhd.room_level_id
                                                where
                                                    rh.in_date=\''.$his_in_date.'\'
                                                    and rh.portal_id=\''.$value['id'].'\'
                                                    and rhd.close_room=1
                                                    and room_level.is_virtual = 0
                                                    ','total_room');
                    }
                    elseif($his_in_date = DB::fetch('select min(in_date) as in_date from room_history where in_date>\''.Date_Time::to_orc_date(date('d/m/Y',$time)).'\' and portal_id=\''.$value['id'].'\'','in_date'))
                    {
                        $total_room_all += DB::fetch('select 
                                                    count(rhd.room_id) as total_room 
                                                from
                                                    room_history_detail rhd
                                                    inner join room_history rh on rh.id=rhd.room_history_id
                                                    inner join room_level on room_level.id = rhd.room_level_id
                                                where
                                                    rh.in_date=\''.$his_in_date.'\'
                                                    and rh.portal_id=\''.$value['id'].'\'
                                                    and rhd.close_room=1
                                                    and room_level.is_virtual = 0
                                                    ','total_room');
                    }
                }
                if($total_room_all!=0)
                {
                    $room_count = $total_room_all;
                }
            }
            /** End Manh **/
			$months[$month]['days'][date('d',$time)] = array(
				'week_day'=>date('D',$time),
				'title_bgcolor'=>(date('D',$time)=='Sun')?'#CCCCCC':'',
				'id'=>date('j',$time),
				'total'=>$room_count-(isset($repair_rooms[date('Y-m-d',$time)])?$repair_rooms[date('Y-m-d',$time)]['acount']:0),
				'rsold'=>isset($rsolds[date('Y-m-d',$time)])?$rsolds[date('Y-m-d',$time)]['acount']:0,
				'percent'=>''
			);
			if($months[$month]['days'][date('d',$time)]['total'])
			{
				$percent = (($months[$month]['days'][date('d',$time)]['rsold']/$months[$month]['days'][date('d',$time)]['total'])*100);
				if($percent>75)
				{
					$months[$month]['days'][date('d',$time)]['bgcolor'] = '#CCCCCC';
				}
				else
				{
					$months[$month]['days'][date('d',$time)]['bgcolor'] = 'white';
				}
				$months[$month]['days'][date('d',$time)]['percent'] = number_format($percent,1);
				//$months[$month]['percent'] += $percent;
				$months[$month]['count']++;
				$months[$month]['total'] += $months[$month]['days'][date('d',$time)]['total'];
				$months[$month]['rsold'] += $months[$month]['days'][date('d',$time)]['rsold'];
			}
			$time += 24*3600;
		}
		foreach($months as $id=>$month)
		{
			if($month['count'])
			{
				//$months[$id]['percent']=number_format($month['percent']/$month['count'],2);
                $months[$id]['percent'] = round(($month['rsold'])/$month['total']*100,2);
			}
		}
        //Start Luu Nguyen GIap add portal
        $this->map['portal_id_list'] = array('ALL'=>Portal::language('all'))+String::get_list(Portal::get_portal_list()); 
        //End Luu Nguyen GIap add portal
        
		$this->parse_layout('report',$this->map + 
			array(
				'months'=>$months
			)
		);
	}
}
?>
