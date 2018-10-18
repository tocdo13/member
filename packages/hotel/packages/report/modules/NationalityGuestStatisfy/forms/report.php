<?php
class NationalityGuestStatisfyForm extends Form
{
	function NationalityGuestStatisfyForm()
	{
		Form::Form('NationalityGuestStatisfyForm');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
	}
	function draw()
	{
        $this->map = array();
        
        $this->map['to_date'] = Url::get('to_date')?Url::get('to_date'):date('d/m/Y');
        $_REQUEST['to_date'] = $this->map['to_date'];
        
        $this->map['from_date'] = Url::get('from_date')?Url::get('from_date'):date('1/m/Y');
        $_REQUEST['from_date'] = $this->map['from_date'];
        
		$cond = ' 1=1 ';
        $this->map['portal_id_list'] = array('ALL'=>Portal::language('all')) + String::get_list(Portal::get_portal_list());
        $_REQUEST['portal_id_list'] = array('ALL'=>Portal::language('all')) + String::get_list(Portal::get_portal_list());
        if(Url::get('portal_id') AND Url::get('portal_id')!='ALL'){
            $cond .= 'AND reservation.portal_id=\''.Url::get('portal_id').'\'';
        }
        $cond_from_date = $_REQUEST['from_date'];
        $cond_to_date = $_REQUEST['to_date'];
		$cond .= "
            AND (reservation_traveller.arrival_date<='".Date_Time::to_orc_date($cond_to_date)."' 
                and reservation_traveller.departure_date>='".Date_Time::to_orc_date($cond_from_date)."'
                )
               ";
        /** lay ra danh sach quoc gia duoc chon **/
        $orc = '
            SELECT DISTINCT
                traveller.nationality_id as id,
                country.name_'.Portal::language().' as name
            from
                reservation_traveller 
                inner join reservation_room on reservation_traveller.RESERVATION_ROOM_ID = RESERVATION_ROOM.ID
                inner join reservation on reservation.id = reservation_traveller.reservation_id
                left join room_level on RESERVATION_ROOM.room_level_id = room_level.id
                left join traveller on reservation_traveller.traveller_id = traveller.id
                left join country on traveller.nationality_id = country.id
            where'.$cond.'
                AND (reservation_traveller.status = \'CHECKIN\' OR reservation_traveller.status=\'CHECKOUT\')
                and (room_level.is_virtual=0 or room_level.is_virtual is NULL )
                and reservation_room.status != \'CANCEL\'
        ';
        $country = DB::fetch_all($orc);
        //System::debug($country);
        /** khoi tao gia tri ban dau cho cac quoc gia khong duoc chon **/
        $items['other'] = array(
                        'country_name'=>'country_other',
                        'count_traveller'=>0,
                        'count_male'=>0,
                        'count_famale'=>0,
                        'count_visitor'=>0,
                        'count_visitor_vietnam'=>0,
                        'count_vietnam'=>0
                    );
        foreach($country as $key=>$content)
        {
            /** khoi tao gia tri ban dau cho cac quoc gia duoc chon **/
            $items[$key] = array(
                        'country_name'=>$content['name'],
                        'count_traveller'=>0,
                        'count_male'=>0,
                        'count_famale'=>0,
                        'count_visitor'=>0,
                        'count_visitor_vietnam'=>0,
                        'count_vietnam'=>0
                    );
        }
        // lay tat ca cac khach o trong khoang thoi gian duoc xem.
        $sql = '
                select 
                    reservation_traveller.id,
                    traveller.is_vn, --oanh add
                    traveller.nationality_id,
                    traveller.gender,
                    traveller.first_name || traveller.last_name as full_name,
                    traveller.traveller_level_id,
                    DATE_TO_UNIX(reservation_traveller.arrival_date) as arrival_date,
                    reservation_traveller.arrival_date as traveller_arrival_date,
                    DATE_TO_UNIX(reservation_traveller.departure_date) as departure_date,
                    reservation_traveller.departure_date as traveller_departure_date,
                    country.name_'.Portal::language().' as name,
                    country.selected_report,
                    reservation_room.change_room_to_rr,
                    reservation.portal_id,
                    reservation_traveller.reservation_id as reservation_id
                from
                    reservation_traveller 
                    inner join reservation_room on reservation_traveller.RESERVATION_ROOM_ID = RESERVATION_ROOM.ID
                    inner join reservation on reservation.id = reservation_traveller.reservation_id
                    left join room_level on RESERVATION_ROOM.room_level_id = room_level.id
                    left join traveller on reservation_traveller.traveller_id = traveller.id
                    left join country on traveller.nationality_id = country.id
                where'.$cond.'
                    AND (reservation_traveller.status = \'CHECKIN\' OR reservation_traveller.status=\'CHECKOUT\')
                    and (room_level.is_virtual=0 or room_level.is_virtual is NULL )
                    and reservation_room.status != \'CANCEL\'
                ';
                //echo $sql;
        $traveller = DB::fetch_all($sql);
        //System::debug($traveller);
        $from_date = Date_Time::to_time(URL::get('from_date'));
        $to_date = Date_Time::to_time(URL::get('to_date'));
        foreach($traveller as $id=>$value)
        {
            $traveller[$id]['count_traveller']=0;
            $count = 0;
            $id_country = '';
            if(!empty($value['change_room_to_rr']) AND ($value['arrival_date']==$value['departure_date']))
            {
                $traveller[$id]['count_traveller']=0;
            }
            elseif(empty($value['change_room_to_rr']) AND ($value['arrival_date']==$value['departure_date']))
            {
                $traveller[$id]['count_traveller']=1;
            }
            else
            {
                if($value['departure_date']<=$to_date)
                {
                    if($value['arrival_date']<=$from_date)
                    {
                        for($i=$from_date;$i<$value['departure_date'];$i+=24*3600)
                        {
                            $traveller[$id]['count_traveller']+=1;
                        }
                    }
                    else
                    {
                        for($i=$value['arrival_date'];$i<$value['departure_date'];$i+=24*3600)
                        {
                            $traveller[$id]['count_traveller']+=1;
                        }
                    }
                }
                else
                {
                    if($value['arrival_date']<=$from_date)
                    {
                        for($i=$from_date;$i<=$to_date;$i+=24*3600)
                        {
                            $traveller[$id]['count_traveller']+=1;
                        }
                    }
                    else
                    {
                        for($i=$value['arrival_date'];$i<=$to_date;$i+=24*3600)
                        {
                            $traveller[$id]['count_traveller']+=1;
                        }
                    }
                }
            }
            //system::debug($traveller);
            $count = $traveller[$id]['count_traveller'];
            if($value['selected_report']==1)
            {
                $items[$id_country]['full_name'] = $value['full_name'];
                $id_country = $value['nationality_id'];
                /** Oanh add **/
                //$Viet_nam_in_foreign = $value['is_vn'];
                //System::debug($Viet_nam_in_foreign);
                /** End oanh **/
                $items[$id_country]['count_traveller'] += $count;
                if($value['gender']==1)
                {
                    $items[$id_country]['count_male'] += $count;
                }
                else
                {
                    $items[$id_country]['count_famale'] += $count;
                }
                /** oanh comment vì lấy điều kiện người việt nam ở nước ngoài sai
                if($value['traveller_level_id']==3)
                {
                    $items[$id_country]['count_visitor_vietnam'] += $count;
                }
                 End Oanh **/
                /** oanh add **/
                if($id_country==439 and $value['is_vn']==3)
                {
                   $items[$id_country]['count_visitor_vietnam'] += $count ;
                }
                /** End Oanh **/
                else
                {
                    if($id_country==439 and $value['is_vn']==2)
                    {
                        $items[$id_country]['count_vietnam'] += $count;
                    }
                    else
                    {
                        $items[$id_country]['count_visitor'] += $count;
                    }
                }
            }
            else
            {
                $items['other']['count_traveller'] += $count;
                if($value['gender']==1)
                {
                    $items['other']['count_male'] += $count;                
                }
                else
                {
                    $items['other']['count_famale'] += $count;
                }
                if($value['traveller_level_id']==3)
                {
                    $items['other']['count_visitor_vietnam'] += $count;
                }
                else
                {
                    if($id_country==439)
                    {
                        $items['other']['count_vietnam'] += $count;
                    }
                    else
                    {
                        $items['other']['count_visitor'] += $count;
                    }
                }
            }
            
        }
        //System::debug($items);
        foreach($items as $id_item=>$value_item)
        {
            if(!isset($value_item['count_traveller']) or $value_item['count_traveller']==0)
            {
                unset($items[$id_item]);
            }
            
        }
        
        ksort($items);
        /** tinh tong **/
        $this->map['total_traveller']=0;
        $this->map['total_male']=0;
        $this->map['total_famale']=0;
        $this->map['total_visitor']=0;
        $this->map['total_visitor_vietnam']=0;
        $this->map['total_vietnam']=0;
        foreach($items as $code=>$item)
        {
            $this->map['total_traveller']+=$item['count_traveller'];
            $this->map['total_male']+=$item['count_male'];
            $this->map['total_famale']+=$item['count_famale'];
            $this->map['total_visitor']+=$item['count_visitor'];
            $this->map['total_visitor_vietnam']+=$item['count_visitor_vietnam'];
            $this->map['total_vietnam']+=$item['count_vietnam'];
        }
        //System::debug($items);
        $this->parse_layout('report',array('items'=>$items)+$this->map);
	}
}
?>
