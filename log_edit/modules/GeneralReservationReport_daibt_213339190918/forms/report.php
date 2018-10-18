<?php
class GeneralReservationReportForm extends Form{
	function GeneralReservationReportForm(){
		Form::Form('GeneralReservationReportForm');
        $this->link_js('packages/core/includes/js/jquery/datepicker.js'); 
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/jquery.battatech.excelexport.js');
	}
    function draw()
    {
        $this->map = array();
        $from_date = (Url::get('from_day'))?Url::get('from_day'):date('01/m/Y');
        $to_date = (Url::get('to_day'))?Url::get('to_day'):date('t/m/Y',time());
        $time_from_day =Date_Time::to_time($from_date);
        $time_to_day = Date_Time::to_time($to_date);
        $this->map['time_from_day'] = $time_from_day;
        $this->map['time_to_day'] = $time_to_day;
        $this->map['from_date'] = $from_date;
        $this->map['to_date'] = $to_date;
        $from_day = Date_Time::to_orc_date($from_date);
        $end_day = Date_Time::to_orc_date($to_date);
        $this->map['re_code'] = Url::get('re_code')?Url::get('re_code'):'';
        $users = DB::fetch_all('select account.id,party.full_name as name from account INNER JOIN party on party.user_id = account.id AND party.type=\'USER\' WHERE account.type=\'USER\' ORDER BY account.id');
        $this->map['create_user_list'] = array(''=>Portal::language('all'))+String::get_list($users);
        $this->map['create_user'] = Url::get('create_user')?Url::get('create_user'):'';
        $this->map['cut_of_date'] = (Url::get('cut_of_date'))?Url::get('cut_of_date'):'';
        $this->map['customer'] = Url::get('customer_ids',''); 
        $portal_id = (Url::get('portal_id'))?Url::get('portal_id'):'ALL';
        $cond_portal = '';
        $cond_portal_1 = '';
        if($portal_id !='ALL')
        {
            $cond_portal .= ' AND reservation.portal_id=\''.Url::get('portal_id').'\'';
            $cond_portal_1 .= ' AND room.portal_id=\''.Url::get('portal_id').'\'';    
        }
        $room_level_id = (Url::get('room_level'))?Url::get('room_level'):'ALL';
        $cond_room_level = '';
        if($room_level_id !='ALL')
        {
            $cond_room_level .= ' AND room_level.id=\''.$room_level_id.'\'';   
        }
        $cond = ' AND 1=1';
        if($this->map['re_code']!='')
        {
             $cond.= 'AND reservation.id='.$this->map['re_code'];
        }
        if($this->map['customer']!='')
        {
             $cond .= 'AND (reservation.customer_id in ('.$this->map['customer'].'))';
        }
        if($this->map['cut_of_date']!='')
        {
             $cond.= 'AND reservation.cut_of_date=\''.Date_Time::to_orc_date($this->map['cut_of_date']).'\'';
        }
        if($this->map['create_user']!='')
        {
             $cond .= 'AND (party.user_id=\''.$this->map['create_user'].'\')';
        }
        $total_room = DB::fetch('Select count(room.id) as total from room inner join room_level on room_level.id = room.room_level_id Where room_level.is_virtual = 0  '.$cond_portal_1.$cond_room_level.'  ','total');
        /** ----------------------------lay thong tin phong------------------------------------ **/
        $room_status = array();
        $recode_info = array(); /** mang lay thong tin phong **/
        $time_from_day =Date_Time::to_time(Date_Time::convert_orc_date_to_date($from_day,'/'));
        $time_to_day =Date_Time::to_time(Date_Time::convert_orc_date_to_date($end_day,'/'));
        $room_level = DB::fetch_all('select * from room_level where is_virtual=0');
        for($i=$time_from_day ; $i<= $time_to_day; $i +=24*3600)
        {
            foreach($room_level as $key=>$value)
            {
                $k = $key.'_'.date('d/m/Y',$i);
                $room_status[$k]['id'] = $k;
                $room_status[$k]['in_date'] = date('d/m/Y',$i);
                $room_status[$k]['repair_room'] = 0; /** phong hong **/
                $room_status[$k]['occ_room'] = 0; /** phong luu+den**/
            }
        }
        //System::debug($room_status);exit();
        /** Tinh phong Repair **/
        $sql='
			SELECT 
			     	count(rs.room_id) as total, 
                    rs.in_date,
                    rs.house_status,
					concat(rs.in_date,rs.house_status) || room_level.id as id,
                    room_level.id as room_level_id
			FROM 
					room_status rs
                    inner join room on rs.room_id = room.id
                    inner join room_level on room.room_level_id = room_level.id
			WHERE 
					DATE_TO_UNIX(rs.in_date) >= DATE_TO_UNIX(\''.$from_day.'\') AND DATE_TO_UNIX(rs.in_date) <=DATE_TO_UNIX(\''.$end_day.'\')
                    and rs.house_status = \'REPAIR\'
                    and room_level.is_virtual = 0
                    '.$cond_portal_1.$cond_room_level.'
			GROUP 
					BY rs.house_status,
                    rs.in_date,
                    room_level.id
                    ';
        $rooms = DB::fetch_all($sql);
		foreach($rooms as $key => $value)
        {   
			$id = $value['room_level_id'].'_'.Date_Time::convert_orc_date_to_date($value['in_date'],'/');
			if($value['house_status'] == 'REPAIR')
            {
				$room_status[$id]['repair_room'] += $value['total'];
			}
		}
		/** Tinh phong den,di,luu **/
		$sql2='
				SELECT 
					rs.id as id,  
					rs.in_date,
                    case
                        when rs.in_date = rr.arrival_time
                        then 
                            (case
                             when rr.net_price = 0
                             then ((CHANGE_PRICE*(1-NVL(rr.REDUCE_BALANCE,0)/100.0)-NVL(rr.REDUCE_AMOUNT,0))*(1+NVL(rr.SERVICE_RATE,0)/100.0))*(1 + NVL(rr.TAX_RATE,0)/100.0)
                             else
                              ((((CHANGE_PRICE/(1+NVL(rr.SERVICE_RATE,0)/100.0))/(1 + NVL(rr.TAX_RATE,0)/100.0))*(1-NVL(rr.REDUCE_BALANCE,0)/100.0)-NVL(rr.REDUCE_AMOUNT,0))*(1+NVL(rr.SERVICE_RATE,0)/100.0))*(1 + NVL(rr.TAX_RATE,0)/100.0)
                            end) 
                        else
                            (case
                             when rr.net_price = 0
                             then (CHANGE_PRICE*(1-NVL(rr.REDUCE_BALANCE,0)/100.0)*(1+NVL(rr.SERVICE_RATE,0)/100.0))*(1 + NVL(rr.TAX_RATE,0)/100.0)
                             else
                              ((((CHANGE_PRICE/(1+NVL(rr.SERVICE_RATE,0)/100.0))/(1 + NVL(rr.TAX_RATE,0)/100.0))*(1-NVL(rr.REDUCE_BALANCE,0)/100.0))*(1+NVL(rr.SERVICE_RATE,0)/100.0))*(1 + NVL(rr.TAX_RATE,0)/100.0)
                            end)
                    end as change_price,
                    --customer_group.name as customer_group,
                    customer.name as customer_name,
                    reservation.booker,
                    reservation.phone_booker,
                    reservation.email_booker,
                    reservation.cut_of_date,
                    reservation.deposit,
                    reservation.note as group_note,
                    date_to_unix(rs.in_date) as time_indate,
					rs.status,
                    rs.reservation_id,
                    rr.id as rr_id,
                    rr.arrival_time, 
                    rr.status as reservation_status,
					rr.departure_time,
                    rr.foc_all,
                    rr.foc,
                    rr.time_in,
                    rr.time_out,
                    nvl(room_level.is_virtual,0) as is_virtual,
                    nvl(rr.change_room_from_rr,0) as change_room_from_rr,
                    nvl(rr.change_room_to_rr,0) as change_room_to_rr,
                    from_unixtime(rr.old_arrival_time) as old_arrival_time,
                    rr.old_arrival_time as time_old,
                    reservation.id as r_id,
                    rr.room_level_id as room_level_id,
                    room_level.name as room_level_name,
                    room.id as room_id,
                    nvl(rr.deposit,0) as deposit_room,
                    room.name as room_name
				FROM 
					room_status rs
                    inner JOIN reservation_room rr on rr.id = rs.reservation_room_id
                    inner join reservation on rr.reservation_id = reservation.id
                    left join customer on reservation.customer_id = customer.id
                    --left join customer_group on customer.group_id = customer_group.id
                    left join room on rr.room_id = room.id
                    left join room_level on room_level.id = rr.room_level_id
                    left join party on reservation.user_id=party.user_id
				WHERE 
					DATE_TO_UNIX(rs.in_date) >= DATE_TO_UNIX(\''.$from_day.'\') 
                    AND DATE_TO_UNIX(rs.in_date) <=DATE_TO_UNIX(\''.$end_day.'\')
                    '.$cond_portal.$cond.'
				    and rr.status != \'CANCEL\' 
                    and rr.status != \'NOSHOW\'
                    and room_level.is_virtual = 0
                    and rs.reservation_id != 0
                ORDER BY 
                    rs.reservation_id,
                    rs.in_date
                ';
        $room_in_outs = DB::fetch_all($sql2);
        $recode_room = array(); /** mang chua so phong cua tung hang phong trong recode **/
        $rr_check = array(); /** mang check reservation phu phu hop cua tung recode **/
        foreach($room_in_outs as $key=>$value)
        {
            $id = $value['room_level_id'].'_'.Date_Time::convert_orc_date_to_date($value['in_date'],'/');
            $r_id = $value['reservation_id'];
            $rr_id = $value['rr_id'];
            $date = Date_Time::convert_orc_date_to_date($value['in_date'],'/');
            if(!isset($recode_info[$r_id]))
            {
                $recode_info[$r_id]['id'] = $r_id;
                $recode_info[$r_id]['revenue'] = 0;
                $recode_info[$r_id]['customer_name'] = $value['customer_name'];
                $recode_info[$r_id]['booker'] = $value['booker'];
                $recode_info[$r_id]['phone_booker'] = $value['phone_booker'];
                $recode_info[$r_id]['email_booker'] = $value['email_booker'];
                $recode_info[$r_id]['cut_of_date'] = Date_Time::convert_orc_date_to_date($value['cut_of_date'],'/');
                $recode_info[$r_id]['deposit'] = $value['deposit'];
                $recode_info[$r_id]['group_note'] = $value['group_note'];
                $recode_info[$r_id]['num_room'][$date]['room_count'] = 0;
                $recode_info[$r_id]['check'] = false;
                $recode_info[$r_id]['room_level_info'] = '';
            }
            else
            {
                if(!isset($recode_info[$r_id]['num_room'][$date]))
                    $recode_info[$r_id]['num_room'][$date]['room_count'] = 0;
            }
        	$check = false;
            if($value['change_room_from_rr']==0 and $value['change_room_to_rr']==0) /** th khong lien quan toi doi phong **/
            {
                if($value['in_date']==$value['arrival_time']) /** phong den truong hop binh thuong **/
                {
                    $check=true;
                }
                if($value['time_in']<$value['time_indate'] and $value['time_out']>=($value['time_indate']+86400)) /** phong luu truong hop binh thuong **/
                {
                    $check=true;
                }
            }
            elseif($value['change_room_from_rr']==0 and $value['change_room_to_rr']!=0) /** Truong hop phong chang dau cua doi phong**/
            {
                if($value['in_date']==$value['arrival_time'])
                {
                    /** chang dau nhung neu doi di luon trong ngay thi khong dc tinh phong den => dep_time phai lon hon in_date (vi tinh theo hang phong can nhu vay)**/
                    if($value['time_out']>=($value['time_indate']+86400))
                    {
                        $check=true;
                    }  
                }
                if($value['time_in']<$value['time_indate'] and $value['time_out']>=($value['time_indate']+86400))
                {
                    $check=true;
                }
            }
            elseif($value['change_room_from_rr']!=0 and $value['change_room_to_rr']==0) /** Truong hop phong chang cuoi cua doi phong**/
            {
                if($value['time_old']<$value['time_indate'] and $value['time_out']>=($value['time_indate']+86400))
                {
                    $check=true;
                }
                /** chang cuoi cua doi phong nhung co ngay den = ngay den old = ngay xet thi dc tinh la phong den trong ngay (vi tinh theo hang phong can nhu vay)**/
                if($value['arrival_time']==$value['in_date'] and $value['arrival_time'] == $value['old_arrival_time'])
                {
                    $check=true;
                }
            }
            elseif($value['change_room_from_rr']!=0 and $value['change_room_to_rr']!=0) /** Truong hop phong chang giua cua doi phong**/
            {
                if($value['time_old']<$value['time_indate'] and $value['time_out']>=($value['time_indate']+86400))
                {
                    $check=true;
                }
                /** chang giua cua doi phong nhung co ngay den = ngay den old = ngay xet va ngay di > ngay xet thi dc tinh la phong den trong ngay (vi tinh theo hang phong can nhu vay)**/
                if($value['arrival_time']==$value['in_date'] and $value['arrival_time'] == $value['old_arrival_time'] and $value['time_out']>=($value['time_indate']+86400))
                {
                    $check=true;
                }
            }
            if($check==true)
            {
                $room_status[$id]['occ_room'] += 1;
                $recode_info[$r_id]['num_room'][$date]['room_count'] += 1;
                if($value['foc_all']==0 and $value['foc']=='')
                {
                    $recode_info[$r_id]['revenue'] += $value['change_price'];
                }
                $recode_info[$r_id]['check'] = true;
                if(!isset($rr_check[$r_id]['reservation_room'][$rr_id]))
                {
                    $rr_check[$r_id]['reservation_room'][$rr_id]['id'] = $rr_id;
                    $rr_check[$r_id]['reservation_room'][$rr_id]['room_level_id'] = $value['room_level_id'];
                    $rr_check[$r_id]['reservation_room'][$rr_id]['room_level_name'] = $value['room_level_name'];
                    $rr_check[$r_id]['reservation_room'][$rr_id]['deposit_room'] = $value['deposit_room'];
                }
            }
            if($recode_info[$r_id]['check']==false)
            {
                unset($recode_info[$r_id]);
            }    
        }
        //System::debug($recode_info);
        /** --------------------------------lay thong tin li-------------------------------- **/
        $portal_id_2 =' AND 0 = 0';
        if(Url::get('portal_id'))
        {
            if(Url::get('portal_id') !='ALL')
                $portal_id_2 .= ' AND EXTRA_SERVICE_INVOICE.portal_id=\''.Url::get('portal_id').'\'';
            else
                $portal_id_2 .= ' AND 1 = 1';
        }
        $cond_li='';
        $cond_li.=' and (extra_service.code=\'LATE_CHECKIN\')';
        $sql = 'select
                    esid.id,
                    esid.quantity+nvl(esid.change_quantity,0) as quantity,
                    esid.price,
                    esid.in_date,
                    rr.room_level_id,
                    rr.foc_all,
                    CASE
            			WHEN 
            				extra_service_invoice.net_price =0 or extra_service_invoice.net_price = NULL
            			THEN
            				((esid.quantity+nvl(esid.change_quantity,0))*esid.price) + (((esid.quantity+nvl(esid.change_quantity,0))*esid.price)*extra_service_invoice.service_rate*0.01) + ((((esid.quantity+nvl(esid.change_quantity,0))*esid.price) + (((esid.quantity+nvl(esid.change_quantity,0))*esid.price)*extra_service_invoice.service_rate*0.01))*extra_service_invoice.tax_rate*0.01)
            			ELSE
            				((esid.quantity+nvl(esid.change_quantity,0))*esid.price)
                    END as change_price,
                    --customer_group.name as customer_group,
                    customer.name as customer_name,
                    reservation.booker,
                    reservation.phone_booker,
                    reservation.email_booker,
                    reservation.cut_of_date,
                    reservation.deposit,
                    reservation.note as group_note,
                    rr.reservation_id as reservation_id,
                    rr.id as rr_id,
                    rr.room_level_id as room_level_id,
                    room_level.name as room_level_name,
                    extra_service_invoice.service_rate,
                    rr.deposit as deposit_room,
                    extra_service.code
                from
                    extra_service_invoice_detail esid
                    inner join extra_service on esid.service_id = extra_service.id
                    inner join extra_service_invoice on extra_service_invoice.id = esid.invoice_id
                    inner join reservation_room rr on rr.id = extra_service_invoice.reservation_room_id
                    inner join reservation on rr.reservation_id = reservation.id
                    left join room_level on rr.room_level_id = room_level.id
                    left join customer on reservation.customer_id = customer.id
                    --left join customer_group on customer.group_id = customer_group.id
                    left join party on reservation.user_id=party.user_id
                where
                    room_level.is_virtual = 0
                    AND rr.status != \'CANCEL\' AND rr.status != \'NOSHOW\'
                    '.$cond_li.'
                    AND esid.in_date >= \''.$from_day.'\' 
				    AND esid.in_date <= \''.$end_day.'\'
                     '.$portal_id_2.$cond.'
                     ';
        $items_li = DB::fetch_all($sql);
        $ei_lo_li = array();
        if (!empty($items_li))
        {
            foreach ($items_li as $key => $value)
            {
                $r_id = $value['reservation_id'];
                $date = Date_Time::convert_orc_date_to_date($value['in_date'],'/');
                if(!isset($recode_info[$r_id]))
                {
                    $recode_info[$r_id]['id'] = $r_id;
                    $recode_info[$r_id]['revenue'] = 0;
                    $recode_info[$r_id]['customer_name'] = $value['customer_name'];
                    $recode_info[$r_id]['booker'] = $value['booker'];
                    $recode_info[$r_id]['phone_booker'] = $value['phone_booker'];
                    $recode_info[$r_id]['email_booker'] = $value['email_booker'];
                    $recode_info[$r_id]['cut_of_date'] = Date_Time::convert_orc_date_to_date($value['cut_of_date'],'/');
                    $recode_info[$r_id]['deposit'] = $value['deposit'];
                    $recode_info[$r_id]['group_note'] = $value['group_note'];
                    $recode_info[$r_id]['num_room'][$date]['room_count'] = 0;
                    $recode_info[$r_id]['room_level_info'] = '';
                }
                else
                {
                    if(!isset($recode_info[$r_id]['num_room'][$date]))
                        $recode_info[$r_id]['num_room'][$date]['room_count'] = 0;
                }
                $quantity = 0;
                if(!isset($rr_check[$r_id]['reservation_room'][$rr_id]))
                {
                    $rr_check[$r_id]['reservation_room'][$rr_id]['id'] = $rr_id;
                    $rr_check[$r_id]['reservation_room'][$rr_id]['room_level_id'] = $value['room_level_id'];
                    $rr_check[$r_id]['reservation_room'][$rr_id]['room_level_name'] = $value['room_level_name'];
                    $rr_check[$r_id]['reservation_room'][$rr_id]['deposit_room'] = $value['deposit_room'];
                }
                $quantity = $value['quantity'];
                $recode_info[$r_id]['num_room'][$date]['room_count'] += 1;
                if($items_li[$key]['foc_all']==0)
                {
                    $total_amount_ei = $value['change_price'];
                }
                else
                {
                    $total_amount_ei = 0;
                }
                $recode_info[$r_id]['revenue'] += $total_amount_ei;
                $id = $value['room_level_id'].'_'.Date_Time::convert_orc_date_to_date($value['in_date'],'/');
                if (!isset($ei_lo_li[$id]))
                {
                    $ei_lo_li[$id]['quantity'] = $quantity;
                }
                else
                {
                    $ei_lo_li[$id]['quantity'] += $quantity;
                }
            }
        }
        foreach($rr_check as $key=>$val) /** lay so phong cua tung hang phong thoa man trong tung recode**/
        {
            foreach($val['reservation_room'] as $k=>$v)
            {
                if(!isset($recode_room[$key]['room_level'][$v['room_level_id']]))
                {
                    $recode_room[$key]['room_level'][$v['room_level_id']]['id'] = $v['room_level_id'];
                    $recode_room[$key]['room_level'][$v['room_level_id']]['room_level_name'] = $v['room_level_name'];
                    $recode_room[$key]['room_level'][$v['room_level_id']]['count'] = 1;
                }
                else
                {
                    $recode_room[$key]['room_level'][$v['room_level_id']]['count'] += 1;
                }
                if(isset($recode_info[$key]))
                {
                    if($v['deposit_room']!='')
                    {
                        $recode_info[$key]['deposit'] += $v['deposit_room'];
                    }
                }
            }
        }
        foreach($recode_room as $k=>$v)
        {
            if(isset($recode_info[$k]))
            {
                foreach($v['room_level'] as $k1=>$v1)
                {
                    $recode_info[$k]['room_level_info'].= $v1['room_level_name'].': '.$v1['count'].'&#13';
                }
            }
        }
        //System::debug($recode_info);
        $items= array();
        $this->map['total_avail_room'] = 0;
        $this->map['total_repair_room'] = 0;
        $this->map['total_room_soild'] = 0;
        $room_level = DB::fetch_all('select room_level.*,0 as room_soild from room_level where is_virtual=0');
        $total_day = array();
        for($i=$time_from_day ; $i<= $time_to_day; $i +=24*3600)
        {
            $day = date('d/m/Y',$i);
            $total_day[$day]['id'] = $day;
            $total_day[$day]['in_date'] = $day;
            $total_day[$day]['room_soild'] = 0;
            $total_day[$day]['repair_room'] = 0;
            $total_day[$day]['avail_room'] = $total_room;
            $total_day[$day]['oc'] = 0;
            $this->map['total_avail_room'] += $total_room;
            foreach($room_level as $key=>$value)
            {
                $k = $key.'_'.date('d/m/Y',$i);
                $items[$k]['id'] = $key.'_'.date('d/m/Y',$i);
                $items[$k]['in_date'] = date('d/m/Y',$i);
                $items[$k]['room_level_id'] = $key;
                $items[$k]['room_soild'] = 0;
                $items[$k]['occ_room'] = 0;
                $items[$k]['repair_room'] = 0;
                $items[$k]['li_room'] = 0;
                if(isset($room_status[$k]))
                {
                    $items[$k]['repair_room'] += $room_status[$k]['repair_room']; 
                    $items[$k]['occ_room'] += $room_status[$k]['occ_room'];
                } 
                if(isset($ei_lo_li[$k])) 
                {
                    $items[$k]['li_room'] += $ei_lo_li[$k]['quantity'];
                }
                $items[$k]['room_soild'] = $items[$k]['occ_room']+$items[$k]['li_room']; /** tong so phong ban= phong den+phong o+phong li **/
                $room_level[$key]['room_soild'] += $items[$k]['room_soild'];
                $total_day[$day]['room_soild'] += $items[$k]['room_soild'];
                $total_day[$day]['repair_room'] += $items[$k]['repair_room'];
                $this->map['total_repair_room'] += $items[$k]['repair_room'];
                $this->map['total_room_soild'] += $items[$k]['room_soild'];
            }
            /** cong xuat phong = phong ban/(phong ks co-phong hong- phong HU)*100 **/
            $total_day[$day]['oc'] = round($total_day[$day]['room_soild']/($total_day[$day]['avail_room']-$total_day[$day]['repair_room'])*100,2);
            /** doan nay de tinh tong **/
        }
        $this->map['total_oc'] = round($this->map['total_room_soild']/($this->map['total_avail_room']-$this->map['total_repair_room'])*100,2);
        $deparment = DB::fetch("SELECT description_1 as des from party where user_id='".User::id()."'",'des');
        
        $l_customer = DB::fetch_all($sql='
                           select customer.id,customer.name from customer order by customer.name
        '); 
        $this->map['customer_js'] = String::array2js(explode(',',Url::get('customer_id_')));            
        $customer = '<div id="checkboxes_customer">';
        foreach($l_customer as $key=>$value)
        {                
            $customer .= '<label for="customer_'.$value['id'].'">';    
            $customer .= '<input name="customer_'.$value['id'].'" type="checkbox" id="customer_'.$value['id'].'" flag="'.$value['id'].'" class="customer" onclick="get_ids(\'customer\');"/>'.$value['name'].'</label>';                                    
        }   
        $customer .= '</div>';            
        $this->map['list_customer'] = $customer;
        $this->map['portal_id_list'] = array('ALL'=>Portal::language('all')) + String::get_list(Portal::get_portal_list());
        $this->parse_layout('report',array(
            'items'=>$items,
            'total_day'=>$total_day,
            'room_level'=>$room_level,
            'recode_info'=>$recode_info
            )+$this->map);	
    }
}
?>
