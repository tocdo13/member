<?php
class VatInvoiceEntryForm extends Form
{
	function VatInvoiceEntryForm()
	{
		Form::Form('VatInvoiceEntryForm');	
	}
    function on_submit()
    {
        /**
         * Luu cac th�ng tin in
         */
        $row = array(
                    'department'=>Url::get('department'),
                    'type'=>Url::get('type'),
                    'customer_name'=>Url::get('customer_name'),
                    'guest_name'=>Url::get('guest_name'),
                    'num_people'=>Url::get('num_people'),
                    'customer_address'=>Url::get('customer_address'),
                    'tax_code'=>Url::get('tax_code'),
                    'arrival_time'=>Url::get('arrival_time')?Date_Time::to_orc_date(Url::get('arrival_time')):'' ,
                    'departure_time'=>Url::get('departure_time')?Date_Time::to_orc_date(Url::get('departure_time')):'',
                    'room_name'=>Url::get('room_name'),
                    'room_price'=>Url::get('room_price')?System::calculate_number(Url::get('room_price')):'' ,
                    'service_rate'=>Url::get('service_rate'),
                    'line_per_page'=>Url::get('line_per_page'),
                    'portal_id'=>PORTAL_ID,
                    );
        $department = Url::get('department');
        $type = Url::get('type');
        $cond = $this->generate_cond('vat_invoice',$type,$department);
        //Neu ko co id => link den tu form dat phong : c� r_id va r_r_id
        if(!Url::iget('id'))
            $record = DB::fetch('Select * from vat_invoice where '.$cond);
        else
            $record = DB::fetch('Select * from vat_invoice where id = '.Url::get('id'));
        
        //So TT cua hd VAT
        $code = '';
        //Neu sua
        if( $record )
        {
            $row['last_print_user'] = Session::get('user_id');
            $row['last_print_time'] = time();
            $row['is_modify'] = 1;
            if(!$record['code'])
            {
                $row['code'] = date('Y',$record['print_time']).'-'.$record['id'];
                $code = $row['code'];
            }
            else
                $code = $record['code'];
            DB::update_id('vat_invoice',$row,$record['id']);
        }
        else//Neu them moi
        {
            $row['reservation_id'] = Url::get('r_id');
            $row['reservation_room_id'] = Url::get('r_r_id');
            $row['print_user'] = Session::get('user_id');
            $row['print_time'] = time();
            $row['is_modify'] = 0;
            $id = DB::insert('vat_invoice',$row);
            $code = date('Y').'-'.$id;
            DB::update_id('vat_invoice',array('code'=>$code),$id);
        }
        Url::redirect_current(array('cmd'=>'view','department','type','reservation_id','reservation_room_id','customer_name','customer_address','tax_code','arrival_time','departure_time','room_name','room_price','service_rate','line_per_page','is_modify'=>$row['is_modify'],'num_people','guest_name','code'=>$code));
    }  
	function draw()
	{
        $this->map = array();
        //dua vao input text
        $this->map['day'] = date('d');
        $this->map['month'] = date('m');
        $this->map['year'] = date('Y');
        $this->map['time'] = date('H:i',time());
        $this->map['title'] = Portal::language('info');
        //Dua vao input hidden
        $_REQUEST['date_print'] = date('d/m/Y');
        $_REQUEST['time_print'] = time();
        $type = Url::get('type');
        $department = Url::get('department');
        $cond = $this->generate_cond('vat_invoice',$type,$department);
        //Neu ko co id => link den tu form dat phong : c� r_id va r_r_id
        if(!Url::iget('id'))
        {
            if( $record = DB::fetch('Select * from vat_invoice where '.$cond) )
                $this->map['note_print'] = Portal::language('reprint').' ('.Portal::language('first_time').': '.date('d/m/Y H:i',$record['print_time']).')';
            else
                $this->map['note_print'] = Portal::language('first_time');
            $this->map['line_per_page'] = 22;
            $cond = $this->generate_cond('reservation_room',$type,$department);
            //r_id : ma res
            //r_r_id ma res_room
            $sql = 'select
                        reservation_room.id,
                        reservation_room.id as reservation_room_id,
                        reservation.id as reservation_id,
                        room.name as room_name,
                        reservation_room.price as room_price,
                        to_char(reservation_room.arrival_time,\'DD/MM/YYYY\') as arrival_time, 
                        to_char(reservation_room.departure_time,\'DD/MM/YYYY\') as departure_time,
                        reservation_room.time_in, 
                        reservation_room.time_out,
                        DECODE(
                        concat(traveller.first_name || \' \' || traveller.last_name, customer.name),            \' \',\'\',
                                                                                                                traveller.first_name || \' \' || traveller.last_name, traveller.first_name || \' \' || traveller.last_name,
                                                                                                                customer.name
                        )  as customer_name,
                        DECODE(
                        concat(traveller.first_name || \' \' || traveller.last_name, customer.name),            \' \',\'\',
                                                                                                                traveller.first_name || \' \' || traveller.last_name, traveller.address,
                                                                                                                customer.address
                        )  as customer_address,
                        concat(traveller.first_name || \' \' || traveller.last_name,\'\') as guest_name,
                        customer.tax_code,
                        reservation_room.net_price,
                        reservation_room.tax_rate,
                        reservation_room.service_rate,
                        reservation_room.adult as num_people,
                        reservation_room.child,
                        COALESCE(reservation_room.reduce_balance,0) as reduce_balance,
                        COALESCE(reservation_room.reduce_amount,0) as reduce_amount
                    from 
                        reservation_room 
                        inner join reservation on reservation.id = reservation_room.reservation_id
                        inner join room on reservation_room.room_id = room.id
                        left outer join traveller on reservation_room.traveller_id = traveller.id
                        left outer join customer on reservation.customer_id = customer.id
                    Where 
                        '.$cond.'
                    ORDER BY reservation_room.id asc
                    ';
            //echo $sql;
            if($type=='SINGLE')
            {
                $items = DB::fetch($sql);
                if($items['net_price']==1)
                {
                    $items['room_price'] = System::display_number(round($items['room_price']/1.155));    
                }
                else
                {
                    $items['room_price'] = System::display_number($items['room_price']); 
                }
                $items['num_people']+= $items['child']?$items['child']:0;
                foreach($items as $key=>$value)
                {
                    $_REQUEST[$key] = $value;
                }
            }
            else
            {
                $items = DB::fetch_all($sql);
                foreach($items as $key=>$value)
                {
                    //echo  $value['room_price'];
                    if($value['net_price']==1)
                    {
                        $items[$key]['room_price'] = System::display_number(round($value['room_price']/1.155));    
                    }
                    else
                    {
                        $items[$key]['room_price'] = System::display_number($value['room_price']); 
                    }
                    //echo $items[$key]['room_price'];
                    //Chi can tao 1 lan request
                    $_REQUEST['room_price'] = $items[$key]['room_price'];
                    $_REQUEST['num_people'] = 0;
                    $_REQUEST['reservation_id'] = $value['reservation_id'];
                    $_REQUEST['customer_name'] = $value['customer_name'];
                    $_REQUEST['customer_address'] = $value['customer_address'];
                    $_REQUEST['tax_code'] = $value['tax_code'];
                    break;
                }
               // echo $items[$key]['room_price'];
                $num_people = 0;
                foreach($items as $key=>$value)
                {
                    $num_people+= $value['child']+$value['num_people'];
                }
                $_REQUEST['num_people']+= $num_people;
            }  
        }
        else
        {
            if( $items = DB::fetch(' Select * from vat_invoice where id = '.Url::get('id').' ') )
            {
                $this->map['line_per_page'] = $items['line_per_page'];
                $this->map['note_print'] = Portal::language('reprint').' ('.Portal::language('first_time').': '.date('d/m/Y H:i',$items['print_time']).')';
                $items['room_price'] = $items['room_price']?System::display_number($items['room_price']):'';
                $items['arrival_time'] = $items['arrival_time']?date('d/m/Y',strtotime($items['arrival_time'])):'';
                $items['departure_time'] = $items['departure_time']?date('d/m/Y',strtotime($items['departure_time'])):'';
                foreach($items as $key=>$value)
                {
                    $_REQUEST[$key] = $value;
                }
            }
        }
		$this->parse_layout('entry_reception',$this->map);
	}
    
    function generate_cond($table,$type,$department)
    {
        $cond = ' 1=1 ';
        if($type == 'SINGLE')//HD tung phong
        {
            $cond .= ' and '.$table.'.reservation_id = '.Url::get('r_id');
            if($table == 'vat_invoice')
                $cond.= ' and '.$table.'.reservation_room_id = '.Url::get('r_r_id');
            else
                $cond.= ' and '.$table.'.id = '.Url::get('r_r_id');
        }
        else//Hd ca nhom
            $cond .= ' and '.$table.'.reservation_id = '.Url::get('r_id');
        if($table == 'vat_invoice')
            $cond .= ' and vat_invoice.type = \''.$type.'\' and vat_invoice.department = \''.$department.'\' ';  
        return $cond;
    }
}
?>