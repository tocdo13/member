<?php
class FocRestaurantForm extends Form
{
	function FocRestaurantForm()
	{
		Form::Form('FocReportForm');
        $this->link_js('packages/core/includes/js/jquery/jquery.maskedinput.js');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
    	$this->link_js('packages/core/includes/js/jquery/datepicker.js');   
        $this->link_js('packages/core/includes/js/jquery/jquery.battatech.excelexport.js');  
	}
	function draw()
	{
        $this->map = array();
        
        $this->map['start_date'] = $_REQUEST['start_date'] = Url::get('start_date')?Url::get('start_date'):date('d/m/Y');
        $this->map['end_date'] = $_REQUEST['end_date'] = Url::get('end_date')?Url::get('end_date'):date('d/m/Y');
        
        $this->map['portal_id'] = $_REQUEST['portal_id'] = Url::get('portal_id')?Url::get('portal_id'):'';
        $this->map['portal_id_list'] = String::get_list(array(''=>array('id'=>'','name'=>Portal::language('all')))+Portal::get_portal_list());
        
        
        $start_time = Date_Time::to_time($this->map['start_date']);
        $end_time = Date_Time::to_time($this->map['end_date']) + 86400;
        
        
        $cond = '1=1';
        $cond .= ' AND bar_reservation.time_out>='.$start_time;
        $cond .= ' AND bar_reservation.time_out<'.$end_time;
        
        $cond_portal = '1=1';
        if(Url::get('portal_id')!=''){
            $cond_portal .= ' AND portal_id=\''.Url::get('portal_id').'\'';
            $cond .= ' AND bar_reservation.portal_id=\''.Url::get('portal_id').'\'';
        }
        $this->map['bar_list'] = DB::fetch_all('SELECT bar.* FROM bar WHERE '.$cond_portal);
        if(Url::get('bar_list')){
            $cond_bar = '';
            foreach(Url::get('bar_list') as $key=>$value){
                $this->map['bar_list'][$key]['checked'] = 'checked';
                $cond_bar .= $cond_bar==''?' bar.id='.$key:' OR bar.id='.$key;
            }
            $cond .= $cond_bar!=''?' AND ('.$cond_bar.')':'';
        }else{
            foreach($this->map['bar_list'] as $key=>$value){
                $this->map['bar_list'][$key]['checked'] = 'checked';
            }
        }
        
        $this->map['total_bill'] = 0;
        $this->map['total_foc'] = 0;
        $this->map['total_quantity'] = 0;
        
        // lay ra ban co khoan thanh toan = foc 
        $bar_reservation_foc = DB::fetch_all('
                                            SELECT
                                                payment.id,
                                                payment.amount,
                                                payment.exchange_rate,
                                                payment.bank_fee,
                                                bar_reservation.id as bar_reservation_id,
                                                bar_reservation.bar_id,
                                                bar_reservation.code,
                                                bar_reservation.time_out,
                                                CASE
                                                    WHEN bar_reservation.agent_name is null 
                                                        THEN customer.name
                                                    ELSE bar_reservation.agent_name
                                                END as customer_name,
                                                CASE
                                                    WHEN bar_reservation.receiver_name is null 
                                                        THEN CONCAT(CONCAT(traveller.first_name,\' \'),traveller.last_name)
                                                    ELSE bar_reservation.receiver_name
                                                END as receiver_name,
                                                bar_reservation.total,
                                                bar_reservation.note,
                                                party.name_'.Portal::language().' as reception_name
                                            FROM
                                                payment
                                                inner join bar_reservation on (bar_reservation.id=payment.bill_id and payment.type=\'BAR\' and payment.payment_type_id=\'FOC\')
                                                left join reservation_room on reservation_room.id = bar_reservation.reservation_room_id
                                                left join reservation on reservation_room.reservation_id=reservation.id
                                                left join customer on customer.id = reservation.customer_id
                                                left join reservation_traveller on bar_reservation.reservation_traveller_id = reservation_traveller.id
                                                left join traveller on reservation_traveller.traveller_id = traveller.id
                                                inner join bar on bar.id=bar_reservation.bar_id
                                                inner join party on party.user_id=payment.user_id
                                            where
                                                '.$cond.'
                                            ORDER BY
                                                bar_reservation.code DESC,bar_reservation.time_out
                                            ');
        //System::debug($bar_reservation_foc);
        $items = array();
        $cond_product = '';
        foreach($bar_reservation_foc as $key=>$value){
            if(!isset($items[$value['bar_reservation_id']])){
                $items[$value['bar_reservation_id']]['id'] = $value['bar_reservation_id'];
                $items[$value['bar_reservation_id']]['code'] = $value['code'];
                $items[$value['bar_reservation_id']]['bar_id'] = $value['bar_id'];
                $items[$value['bar_reservation_id']]['table_name'] = '';
                $items[$value['bar_reservation_id']]['time'] = date('H:i d/m/Y',$value['time_out']);
                $items[$value['bar_reservation_id']]['customer_name'] = $value['customer_name']==''?'Khách lẻ':$value['customer_name'];
                $items[$value['bar_reservation_id']]['receiver_name'] = $value['receiver_name'];
                $items[$value['bar_reservation_id']]['total'] = $value['total'];
                $this->map['total_bill'] += $value['total'];
                $items[$value['bar_reservation_id']]['total_foc'] = 0;
                $items[$value['bar_reservation_id']]['note'] = $value['note'];
                $items[$value['bar_reservation_id']]['reception_name'] = $value['reception_name'];
                $items[$value['bar_reservation_id']]['child']= array(); 
                $items[$value['bar_reservation_id']]['count_child']=0;
                $cond_product .= $cond_product!=''?' OR bar_reservation.id='.$value['bar_reservation_id']:' bar_reservation.id='.$value['bar_reservation_id'];
            }
            $items[$value['bar_reservation_id']]['total_foc'] += ($value['amount']*$value['exchange_rate']) + $value['bank_fee'];
            $this->map['total_foc'] += ($value['amount']*$value['exchange_rate']) + $value['bank_fee'];
        }
        
        $product = DB::fetch_all('
                                    SELECT
                                        bar_reservation_product.id,
                                        bar_reservation_product.product_id,
                                        bar_reservation_product.name,
                                        bar_reservation_product.quantity,
                                        bar_reservation_product.bar_reservation_id
                                    from
                                        bar_reservation_product
                                        inner join bar_reservation on bar_reservation.id=bar_reservation_product.bar_reservation_id
                                    where
                                        1=1 '.($cond_product!=''?' AND '.$cond_product:'').'
                                ');
        foreach($product as $key=>$value){
            if(isset($items[$value['bar_reservation_id']])){
                if($value['quantity']<1){
                    $value['quantity'] = '0'.$value['quantity'];
                }
                $items[$value['bar_reservation_id']]['child'][$key] = $value;
                $items[$value['bar_reservation_id']]['count_child']++;
                $this->map['total_quantity'] += $value['quantity'];
            }
        }
        
        $table = DB::fetch_all('
                                SELECT
                                    bar_reservation_table.id,
                                    bar_reservation_table.bar_reservation_id,
                                    bar_table.name
                                from
                                    bar_reservation_table
                                    inner join bar_reservation on bar_reservation.id=bar_reservation_table.bar_reservation_id
                                    inner join bar_table on bar_table.id=bar_reservation_table.table_id
                                where
                                    1=1 '.($cond_product!=''?' AND '.$cond_product:'').'
                                ');
        foreach($table as $key=>$value){
            if(isset($items[$value['bar_reservation_id']])){
                $items[$value['bar_reservation_id']]['table_name'] .= $items[$value['bar_reservation_id']]['table_name']==''?$value['name']:', '.$value['name'];
            }
        }
        
        $this->map['items'] = $items;
        
        //if(User::id()=='developer05')
            //System::debug($this->map);
        
        $this->parse_layout('report',$this->map);
	}
    function calc_time($string)
    {
        $arr = explode(':',$string);
        return $arr[0]*3600 + $arr[1]*60;
    }
}
?>
