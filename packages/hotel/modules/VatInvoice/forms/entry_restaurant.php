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
                    'customer_name'=>Url::get('customer_name'),
                    'customer_address'=>Url::get('customer_address'),
                    'tax_code'=>Url::get('tax_code'),
                    'arrival_time'=>Url::get('arrival_time')?Date_Time::to_orc_date(Url::get('arrival_time')):'' ,
                    'departure_time'=>Url::get('departure_time')?Date_Time::to_orc_date(Url::get('departure_time')):'',
                    'line_per_page'=>Url::get('line_per_page'),
                    'portal_id'=>PORTAL_ID,
                    );
        $department = Url::get('department');
        $cond = $this->generate_cond('vat_invoice',$department);
        //Neu ko co id => link den tu form dat ban : co b_r_id
        if(!Url::iget('id'))
            $record = DB::fetch('Select * from vat_invoice where '.$cond);
        else
            $record = DB::fetch('Select * from vat_invoice where id = '.Url::get('id'));
        //Neu sua
        if( $record )
        {
            $row['last_print_user'] = Session::get('user_id');
            $row['last_print_time'] = time();
            $row['is_modify'] = 1;
            DB::update_id('vat_invoice',$row,$record['id']);
        }
        else//Neu them moi
        {
            $row['bar_reservation_id'] = Url::get('b_r_id');
            $row['print_user'] = Session::get('user_id');
            $row['print_time'] = time();
            $row['is_modify'] = 0;
            DB::insert('vat_invoice',$row);
        }
        Url::redirect_current(array('cmd'=>'view_restaurant','department','bar_reservation_id','customer_name','customer_address','tax_code','arrival_time','departure_time','line_per_page','is_modify'=>$row['is_modify']));
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
        
        $department = Url::get('department');
        $cond = $this->generate_cond('vat_invoice',$department);
        //Neu ko co id => link den tu form dat ban : co b_r_id
        if(!Url::iget('id'))
        {
            if( $record = DB::fetch('Select * from vat_invoice where '.$cond) )
                $this->map['note_print'] = Portal::language('reprint').' ('.Portal::language('first_time').': '.date('d/m/Y H:i',$record['print_time']).')';
            else
                $this->map['note_print'] = Portal::language('first_time');
            $this->map['line_per_page'] = 25;
            $cond = $this->generate_cond('bar_reservation',$department);
            //r_id : ma res
            //r_r_id ma res_room
            $sql = 'select
                        bar_reservation.id,
                        bar_reservation.id as bar_reservation_id,
                        bar_reservation.receiver_name as customer_name,
                        to_char(FROM_UNIXTIME(bar_reservation.arrival_time), \'DD/MM/YYYY\') as arrival_time,
                        to_char(FROM_UNIXTIME(bar_reservation.departure_time), \'DD/MM/YYYY\' ) as departure_time
                    from 
                        bar_reservation
                    where 
                        '.$cond.' 
                    '
                    ;
            $items = DB::fetch($sql);
            //System::debug($items);
            foreach($items as $key=>$value)
            {
                $_REQUEST[$key] = $value;
            }  
        }
        else
        {
            if( $items = DB::fetch(' Select * from vat_invoice where id = '.Url::get('id').' ') )
            {
                $this->map['line_per_page'] = $items['line_per_page'];
                $this->map['note_print'] = Portal::language('reprint').' ('.Portal::language('first_time').': '.date('d/m/Y H:i',$items['print_time']).')';
                $items['arrival_time'] = $items['arrival_time']?date('d/m/Y',strtotime($items['arrival_time'])):'';
                $items['departure_time'] = $items['departure_time']?date('d/m/Y',strtotime($items['departure_time'])):'';
                foreach($items as $key=>$value)
                {
                    $_REQUEST[$key] = $value;
                }
            }
        }
		$this->parse_layout('entry_restaurant',$this->map);
	}
    
    function generate_cond($table,$department)
    {
        $cond = ' 1=1 ';
        if($table == 'vat_invoice')
            $cond.= ' and '.$table.'.bar_reservation_id = '.Url::get('b_r_id');
        else
            $cond.= ' and '.$table.'.id = '.Url::get('b_r_id');
        if($table == 'vat_invoice')
            $cond .= ' and vat_invoice.department = \''.$department.'\' ';  
        return $cond;
    }
}
?>