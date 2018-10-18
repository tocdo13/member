<?php
class HousekeepingDailyReportReportForm extends Form{
	function HousekeepingDailyReportReportForm(){
		Form::Form('HousekeepingDailyReportReportForm');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');    
	}
	function draw(){
		if(URL::get('do_search'))
        {			
			$this->line_per_page = 999;//URL::get('line_per_page',999);
			require_once 'packages/core/includes/utils/time_select.php';
			require_once 'packages/core/includes/utils/lib/report.php';	
			$report = new Report;
			
            $from_day = Url::get('date_from');
		    $status_select = URL::get('status_select');
            //echo $status_select;
            $this->map['status_select'] = $status_select;
            $this -> map['floor'] = Url::get('floor');
            
			
			$cond = '';	
			if(Url::get('floor')){
				$arr = Url::get('floor');
				foreach($arr as $value){
					if($cond){
						$cond .=' or floor=\''.$value.'\'';
					}else{
						$cond .='floor=\''.$value.'\'';
					}
				}
			}
			if(!$cond){
				$cond = '1=1';
			}
			if(Url::get('hotel_id')){
				$cond.= ' and room.portal_id=\''.Url::get('hotel_id').'\'';
			}else{
				$cond.= ' and room.portal_id=\''.PORTAL_ID.'\'';
			}
			$sql = 'SELECT 
						room.* ,
						room_level.name AS room_level
					FROM 
						room 
						INNER JOIN room_level ON room.room_level_id = room_level.id
					WHERE
						'.$cond.'
					ORDER BY 
						room.name';
			$rooms = DB::fetch_all($sql);
			$date = Date_time::to_orc_date($from_day);
			$sql = 'SELECT 
						room_status.room_id as id,
						decode(reservation_room.customer_name,\' \',concat(traveller.first_name,traveller.last_name),reservation_room.customer_name) as customer_name,
						decode(traveller.gender,1,\''.Portal::language('male').'\',\''.Portal::language('female').'\') as gender,
						country.name_'.Portal::language().' as nationality,
						reservation_room.time_in,
						reservation_room.time_out,
						reservation_room.note,
                        reservation_room.departure_time,
						reservation_room.arrival_time,
						room_status.status as status,
						reservation_room.status  as real_status,
						room_status.house_status,
                        room_status.note as room_status_note,
                        room.name as room_name
					FROM 
						room_status
						LEFT OUTER JOIN reservation_room ON room_status.reservation_room_id=reservation_room.id
						LEFT OUTER JOIN room ON room.id=room_status.room_id 
						LEFT OUTER JOIN traveller ON traveller.id=reservation_room.traveller_id
						LEFT OUTER JOIN country ON country.id = traveller.nationality_id						
					WHERE 
						room_status.status <> \'CANCEL\'
						'.(Url::get('hotel_id')?' AND room.portal_id=\''.Url::get('hotel_id').'\'':' and room.portal_id=\''.PORTAL_ID.'\'').'
						AND in_date =\''.$date.'\' 
						';
			//System::debug($sql);
            $rooms_status = DB::fetch_all($sql);
            //System::debug($rooms_status);	
			$status = array(
				'READY'=>Portal::language('READY'),
				'OCCUPIED'=>Portal::language('OCCUPIED'),
				'BOOKED'=>Portal::language('BOOKED'),
				'REPAIR'=>Portal::language('REPAIR'),
				'CHECKOUT'=>Portal::language('CHECKOUT'),
				'HOUSEUSE'=>Portal::language('HOUSEUSE'),
				'CANCEL'=>Portal::language('CANCEL'),
				'CHECKIN'=>Portal::language('CHECKIN'),
                                'NOSHOW'=>Portal::language('NOSHOW'),
                'AVAILABLE'=>Portal::language('AVAILABLE')
			);	
            //$this->map['status_select_list']=$status;	
            //System::debug($rooms_status);		
			foreach($rooms as $key=>$value){
				$report->items[$key] = $value;
				if(isset($rooms_status[$value['id']]))
                {
					if($rooms_status[$value['id']]['house_status'] != '')
                    {
                            $report->items[$key]['status'] = $rooms_status[$value['id']]['house_status'];
							$report->items[$key]['customer_name'] = '';
							$report->items[$key]['gender'] = '';
							$report->items[$key]['nationality'] = '';
							$report->items[$key]['time_in'] ='';
							$report->items[$key]['time_out'] = '';
							$report->items[$key]['note'] = $rooms_status[$value['id']]['room_status_note'];
                            if($report->items[$key]['status'] =='DIRTY' && $rooms_status[$value['id']]['real_status'] == 'CHECKIN' )
                            {
                                $report->items[$key]['status'] = $status['OCCUPIED'];
                            }
					}
                    else
                    {
						$report->items[$key]['status'] = $status[$rooms_status[$value['id']]['status']];
                        if($rooms_status[$value['id']]['real_status'] == 'CHECKOUT')
                        {
                            $report->items[$key]['status'] = $status['READY'];
                            $report->items[$key]['customer_name'] = $rooms_status[$value['id']]['customer_name'];
    				        $report->items[$key]['gender'] = '';
    						$report->items[$key]['nationality'] = '';
    						$report->items[$key]['time_in'] = '';
    						$report->items[$key]['time_out'] = '';
    						$report->items[$key]['note'] = '';
                        }
                        else
                        {
        					if($rooms_status[$value['id']]['status'] == 'BOOKED')
                            {
        						$report->items[$key]['status'] = $status['READY'];
        					}
        					if($rooms_status[$value['id']]['real_status'] == 'CHECKIN')
                            {
        						$report->items[$key]['status'] = $status['OCCUPIED'];	
        					}
        					$report->items[$key]['customer_name'] = $rooms_status[$value['id']]['customer_name'];
        					$report->items[$key]['gender'] = $rooms_status[$value['id']]['gender'];
        					$report->items[$key]['nationality'] = $rooms_status[$value['id']]['nationality'];
        					$report->items[$key]['time_in'] = $rooms_status[$value['id']]['time_in'];
        					$report->items[$key]['time_out'] = $rooms_status[$value['id']]['time_out'];
        					$report->items[$key]['note'] = $rooms_status[$value['id']]['note'];
                            if($rooms_status[$value['id']]['real_status']=='')
                            {
                                $report->items[$key]['note'] = $rooms_status[$value['id']]['room_status_note'];
                            }
                        }
					}
                    
				}
                else
                {
					$report->items[$key]['status'] = $status['READY'];
					$report->items[$key]['customer_name'] = '';
					$report->items[$key]['gender'] = '';
					$report->items[$key]['nationality'] = '';
					$report->items[$key]['time_in'] ='';
					$report->items[$key]['time_out'] = '';
					$report->items[$key]['note'] = '';
				}
			}
			$this->print_all_pages($report);
		
        }
        else
        {
            $_REQUEST['date_from'] = date('d/m/Y');
			$this->map = array();
			$this->map['floors'] = DB::fetch_all('select room.floor as id, room.floor from room inner join room_level on room_level.id = room.room_level_id where room.portal_id=\''.PORTAL_ID.'\' AND (room_level.is_virtual is null OR room_level.is_virtual = 0) group by room.floor order by room.floor');
		    $_REQUEST['hotel_id'] = PORTAL_ID;
			$this->parse_layout('search',$this->map
            +array(
            'hotel_id_list'=>array(''=>Portal::language('all'))+String::get_list(Portal::get_portal_list())
            ));
            
        }
	}
	function print_all_pages(&$report){
		$count = 0;
		$total_page = 1;
		$pages = array();
		$this->line_per_page =999;
		foreach($report->items as $key=>$item)
		{
			if($count>=$this->line_per_page)
			{
				$count = 0;
				$total_page++;
			}
			$pages[$total_page][$key] = $item;
			$count+=ceil(strlen($item['note'])/18)+1;
		}		
		if(sizeof($pages)>0)
		{
			foreach($pages as $id=>$page)
			{
				$this->print_page($page, $report, $id, $total_page);
			}
		}
	}
	function print_page($items, &$report, $page_no,$total_page)
	{
		
		$this->parse_layout('header',
			array(
				'first_page'=>$page_no==1            
			)
		);		
		$this->parse_layout('report',
			array(
				'items'=>$items,                
			)+$this->map
		);
		$this->parse_layout('footer',array('first_page'=>$page_no==$total_page));
	}
}
?>