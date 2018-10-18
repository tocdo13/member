<?php
class GuestStatusReportForm extends Form{
	function GuestStatusReportForm(){
		Form::Form('GuestStatusReportForm');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
        $this->link_js('packages/core/includes/js/jquery/datepicker.js'); 
	}
	function draw()
    {
        $this->map = array();
        $this->map['line_per_page'] = Url::iget('line_per_page')?Url::iget('line_per_page'):32;
        
        $this->map['no_of_page'] = Url::iget('no_of_page')?Url::iget('no_of_page'):50;
        
        $this->map['start_page'] = Url::iget('start_page')?Url::iget('start_page'):1;
        
        $this->map['date'] = Url::get('date')?Url::get('date'):date('d/m/Y');
        $_REQUEST['date'] = $this->map['date'];
        $cond =' 1=1 ';
        
        $date = Date_Time::to_orc_date($this->map['date']);
         
        $this->map['portal_id_list'] = array('ALL'=>Portal::language('all'))+String::get_list(Portal::get_portal_list());
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
            $cond.=' AND reservation.portal_id = \''.$portal_id.'\' '; 
        }
        
        //Lay room_status.room_id làm id, tuy nhien 1 phong trong 1 ngay co the co > 1 record
        //Neu se order by theo id ASC, de khi lay, no se lay ban ghi cuoi cung (tuc la trang thai moi nhat cua phong)
        
        //Chuyen thanh lay room_name lam id de ksort result => dc cac phong lien mach
        //Sửa : trong th check out check in ngay cần lấy ra cả 2 nên nối thêm r - r làm id
		$sql = 'SELECT 
					room.name || \'-\' || reservation_room.id  as id,
					reservation_room.time_in,
					reservation_room.time_out,
					reservation_room.departure_time,
					reservation_room.arrival_time,
					room_status.status as status,
					reservation_room.status as reservation_room_status,
                    reservation_room.id as reservation_room_id,
                    room.name as room_name,
                    room_level.name as room_level_name,
                    customer.name as customer_name,
                    reservation_room.change_room_to_rr,
                    reservation_room.change_room_from_rr
				FROM 
					room_status
					INNER JOIN reservation_room ON room_status.reservation_room_id=reservation_room.id
                    INNER JOIN reservation ON  reservation.id = reservation_room.reservation_id
					INNER JOIN room ON room.id=room_status.room_id
                    INNER JOIN room_level ON room.room_level_id = room_level.id
                    LEFT JOIN customer on customer.id = reservation.customer_id				
				WHERE
                    '.$cond.' 
					AND reservation_room.status <> \'CANCEL\'
                    AND reservation_room.status <> \'BOOKED\'
					AND room_status.in_date =\''.$date.'\' 
					AND room_status.status !=\'AVAILABLE\'
                ORDER BY
                    room_status.id
                    ';
		//System::debug($sql);
        $rooms_status = DB::fetch_all($sql);
        //System::debug($rooms_status);				
		foreach($rooms_status as $key=>$value)
        {
            if($value['reservation_room_status']=='CHECKOUT')
            {
                $rooms_status[$key]['status'] = 'CHECKOUT';
            }
            if($value['change_room_from_rr'] !='')
            {
                $change_room_from_rr = explode(',',$value['change_room_from_rr']);
                $rooms_status[$key]['change_room_from_rr'] = $change_room_from_rr[sizeof($change_room_from_rr) - 1];
                $room = DB::fetch('select reservation_room.id,room.name from reservation_room inner join room on room.id = reservation_room.room_id where reservation_room.id = '.$rooms_status[$key]['change_room_from_rr']);
                $rooms_status[$key]['change_room_from_rr'] = $room['name'];
            }  
            $sql = '
                    Select 
                        reservation_traveller.id,
                        traveller.first_name || \' \' || traveller.last_name as fullname,
                        country.name_'.Portal::language().' as country_name,
                        DECODE(traveller.gender,1,\''.Portal::language('male').'\',\''.Portal::language('female').'\') as gender
                    From
                        reservation_traveller
                        inner join traveller on reservation_traveller.traveller_id = traveller.id
                        left outer join country on traveller.nationality_id = country.id
                    Where
                        reservation_traveller.reservation_room_id = '.$value['reservation_room_id'].'
                    ';
            
            //Lay khach trong phong        
			$rooms_status[$key]['customer'] = DB::fetch_all($sql);
            
            if(empty($rooms_status[$key]['customer']))
            {
                $rooms_status[$key]['customer'] = array('1'=>array('id'=>1,'fullname'=>$value['customer_name'],'country_name'=>'','gender'=>'',));
            }
            //System::debug($rooms_status[$key]['customer']);
            $rooms_status[$key]['fullname'] = '';
            $rooms_status[$key]['gender'] = '';
            $rooms_status[$key]['country_name'] = '';
            
            //ke? ô
            $i = 1;
            foreach($rooms_status[$key]['customer'] as $k=>$v)
            {
                
                if($i<count($rooms_status[$key]['customer']))
                {
                    $rooms_status[$key]['fullname'].= $v['fullname'].'<br /><hr noshade color="#CCC" size="1px" style="margin:5px -5px";/>';
                    $rooms_status[$key]['gender'].= $v['gender'].'<br /><hr noshade color="#CCC" size="1px" style="margin:5px -5px"/>';
                    $rooms_status[$key]['country_name'].= $v['country_name'].'<br /><hr noshade color="#CCC" size="1px" style="margin:5px -5px"/>';
                }
                    
                else
                {
                    $rooms_status[$key]['fullname'].= $v['fullname'];
                    $rooms_status[$key]['gender'].= $v['gender'];
                    $rooms_status[$key]['country_name'].= $v['country_name'];
                }
                $i++;
                    
            }
            //$rooms_status[$key]['num_guest'] = count($rooms_status[$key]['customer']);
                
		}
        ksort($rooms_status);
		$this->print_all_pages($rooms_status);
	}
    
	function print_all_pages($rooms_status){
		$count = 0;
		$total_page = 1;
		$pages = array();
		foreach($rooms_status as $key=>$item)
		{
			if($count>=$this->map['line_per_page'])
			{
				$count = 0;
				$total_page++;
			}
			$pages[$total_page][$key] = $item;
			$count++;
		}		
		if(sizeof($pages)>0)
		{
		    $this->map['real_total_page']=count($pages);
            $this->map['real_page_no'] = 1;   
			foreach($pages as $page_no=>$page)
			{
				$this->print_page($page, $page_no, $total_page);
			}
		}
        else
		{
			$this->parse_layout('report',
				array(
					'page_no'=>0,
					'total_page'=>0,
                    'has_no_data'=>true
				)+$this->map
			);
		}
	}
	function print_page($items, $page_no,$total_page)
	{
	   if($page_no >= $this->map['start_page'])
       {
            $this->parse_layout('report',
			array(
    				'items'=>$items,
    				'page_no'=>$page_no,
    				'total_page'=>$total_page,
			)+$this->map
		  );
       }
		
	}
}
?>