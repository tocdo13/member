<?php
class ListSiteminderRoomRateForm extends Form
{
	function ListSiteminderRoomRateForm()
	{
		Form::Form('ListSiteminderRoomRateForm');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
        $this->link_js('packages/core/includes/js/jquery/datepicker.js');
	}
    function on_submit(){
        
    }
	function draw()
	{
	    $this->map = array();
        
        $room_level = DB::fetch_all('select room_level.* from room_level where portal_id=\''.PORTAL_ID.'\'');
        
        $siteminder_room_type = DB::fetch_all('select 
                                                    siteminder_room_type.*, 
                                                    room_level.brief_name as room_level_code,
                                                    room_level.name as room_level_name
                                                from  
                                                    siteminder_room_type
                                                    left join room_level on room_level.id=siteminder_room_type.room_level_id
                                                where siteminder_room_type.portal_id=\''.PORTAL_ID.'\'
                                                ORDER BY siteminder_room_type.type_name
                                                ');
        
        $siteminder_room_rate = DB::fetch_all('select 
                                                    a1.*,
                                                    a2.rate_name as from_rate_name,
                                                    b2.type_name as from_type_name
                                                from 
                                                    siteminder_room_rate a1
                                                    inner join siteminder_room_type b1 on b1.id=a1.siteminder_room_type_id 
                                                    left join siteminder_room_rate a2 on a2.id=a1.derive_from_rate_id
                                                    left join siteminder_room_type b2 on b2.id=a2.siteminder_room_type_id 
                                                where 
                                                    b1.portal_id=\''.PORTAL_ID.'\'
                                                ORDER BY
                                                    a1.rate_name
                                                ');
        
        $siteminder_room_rate_over = DB::fetch_all('select 
                                                    siteminder_room_rate_over.* 
                                                from 
                                                    siteminder_room_rate_over
                                                    inner join siteminder_room_rate on siteminder_room_rate.id= siteminder_room_rate_over.siteminder_room_rate_id
                                                    inner join siteminder_room_type on siteminder_room_type.id=siteminder_room_rate.siteminder_room_type_id 
                                                where 
                                                    siteminder_room_type.portal_id=\''.PORTAL_ID.'\'');
        
        $channel = DB::fetch_all('
                                    SELECT
                                        siteminder_room_rate_ota.*,
                                        siteminder_booking_channel.code as ota_code,
                                        siteminder_booking_channel.name as ota_name,
                                        customer.code as customer_code,
                                        customer.name as customer_name,
                                        siteminder_room_rate.rate_name,
                                        siteminder_room_type.type_name
                                    FROM    
                                        siteminder_room_rate_ota
                                        inner join siteminder_room_rate on siteminder_room_rate.id=siteminder_room_rate_ota.siteminder_room_rate_id
                                        inner join siteminder_room_type on siteminder_room_type.id=siteminder_room_rate.siteminder_room_type_id
                                        inner join siteminder_booking_channel on siteminder_booking_channel.code=siteminder_room_rate_ota.siteminder_ota_code
                                        left join siteminder_map_customer on (siteminder_booking_channel.code=siteminder_map_customer.booking_channel_code and siteminder_map_customer.portal_id=\''.PORTAL_ID.'\')
                                        left join customer on (customer.id=siteminder_map_customer.customer_id and customer.portal_id=\''.PORTAL_ID.'\')
                                    WHERE 
                                        siteminder_room_type.portal_id=\''.PORTAL_ID.'\'
                                ');
        //System::debug($channel);
        $channel_over = DB::fetch_all('
                                        SELECT
                                            siteminder_room_rate_ota_over.*
                                        FROM    
                                            siteminder_room_rate_ota_over
                                            inner join siteminder_room_rate_ota on siteminder_room_rate_ota.id=siteminder_room_rate_ota_over.siteminder_room_rate_ota_id
                                            inner join siteminder_room_rate on siteminder_room_rate.id=siteminder_room_rate_ota.siteminder_room_rate_id
                                            inner join siteminder_room_type on siteminder_room_type.id=siteminder_room_rate.siteminder_room_type_id
                                            inner join siteminder_booking_channel on siteminder_booking_channel.code=siteminder_room_rate_ota.siteminder_ota_code
                                            left join siteminder_map_customer on (siteminder_booking_channel.code=siteminder_map_customer.booking_channel_code and siteminder_map_customer.portal_id=\''.PORTAL_ID.'\')
                                            left join customer on (customer.id=siteminder_map_customer.customer_id and customer.portal_id=\''.PORTAL_ID.'\')
                                        WHERE 
                                            siteminder_room_type.portal_id=\''.PORTAL_ID.'\'
                                        ');
        
        foreach($siteminder_room_type as $key=>$value){
            $siteminder_room_type[$key]['child'] = array();
            $siteminder_room_type[$key]['description'] = ' <span class="w3-text-red">*</span>';
            if($value['room_level_id']!=''){
                $siteminder_room_type[$key]['description'] = ' <span class="RoomLevelInfo"> Map To Room Level: <b>'.$value['room_level_code'].' - '.$value['room_level_name'].'</b></span>';
            }
        }
        
        foreach($channel_over as $key=>$value){
            if(isset($channel[$value['siteminder_room_rate_ota_id']])){
                $channel[$value['siteminder_room_rate_ota_id']]['des'] = ' <b><i class="fa fa-fw fa-calendar-plus-o"></i> Overrides applied</b> ';
            }
        }
        foreach($channel as $key=>$value){
            if(isset($siteminder_room_rate[$value['siteminder_room_rate_id']])){
                $value['description'] = '';
                if($value['manual_derive']=='DERIVE'){
                    $value['description'] = 'Rate Amount derived from <b>'.$value['type_name'].' / '.$value['rate_name'].'</b>';
                    if($value['daily_rate']==1){
                        $value['description'] .= '<b> Keep rates the same</b>';
                    }elseif($value['daily_rate']==2){
                        if($value['percent_inc']==1)
                            $value['description'] .= ' <span class="w3-text-green"> +'.$value['percent_adjust'].'% </span>';
                        else
                            $value['description'] .= ' <span class="w3-text-green"> -'.$value['percent_adjust'].'% </span>';
                    }elseif($value['daily_rate']==3){
                        if($value['amount_inc']==1)
                            $value['description'] .= ' <span class="w3-text-green"> +$'.$value['amount_adjust'].' </span>';
                        else
                            $value['description'] .= ' <span class="w3-text-green"> -$'.$value['amount_adjust'].' </span>';
                    }elseif($value['daily_rate']==4){
                        if($value['amount_inc']==1)
                            $value['description'] .= ' <span class="w3-text-green"> +$'.$value['amount_adjust'].' </span>';
                        else
                            $value['description'] .= ' <span class="w3-text-green"> -$'.$value['amount_adjust'].' </span>';
                        if($value['percent_inc']==1)
                            $value['description'] .= ' <span class="w3-text-green"> +'.$value['percent_adjust'].'% </span>';
                        else
                            $value['description'] .= ' <span class="w3-text-green"> -'.$value['percent_adjust'].'% </span>';
                    }elseif($value['daily_rate']==5){
                        if($value['percent_inc']==1)
                            $value['description'] .= ' <span class="w3-text-green"> +'.$value['percent_adjust'].'% </span>';
                        else
                            $value['description'] .= ' <span class="w3-text-green"> -'.$value['percent_adjust'].'% </span>';
                        if($value['amount_inc']==1)
                            $value['description'] .= ' <span class="w3-text-green"> +$'.$value['amount_adjust'].' </span>';
                        else
                            $value['description'] .= ' <span class="w3-text-green"> -$'.$value['amount_adjust'].' </span>';
                    }
                }
                if(isset($value['des']))
                    $value['description'] .= $value['des'];
                
                $siteminder_room_rate[$value['siteminder_room_rate_id']]['child'][$key] = $value;
            }
        }
        
        foreach($siteminder_room_rate_over as $key=>$value){
            if(isset($siteminder_room_rate[$value['siteminder_room_rate_id']])){
                $siteminder_room_rate[$value['siteminder_room_rate_id']]['des'] = ' <b><i class="fa fa-fw fa-calendar-plus-o"></i> Overrides applied</b> ';
            }
        }
        foreach($siteminder_room_rate as $key=>$value){
            $value['description'] = '';
            if($value['rate_config_derive']==1){
                $value['description'] = 'Rate Amount derived from <b>'.$value['from_type_name'].' / '.$value['from_rate_name'].'</b>';
                if($value['daily_rate']==1){
                    $value['description'] .= ' <b>Keep rates the same</b>';
                }elseif($value['daily_rate']==2){
                    if($value['percent_inc']==1)
                        $value['description'] .= ' <span class="w3-text-green"> +'.$value['percent_adjust'].'% </span>';
                    else
                        $value['description'] .= ' <span class="w3-text-green"> -'.$value['percent_adjust'].'% </span>';
                }elseif($value['daily_rate']==3){
                    if($value['amount_inc']==1)
                        $value['description'] .= ' <span class="w3-text-green"> +$'.$value['amount_adjust'].' </span>';
                    else
                        $value['description'] .= ' <span class="w3-text-green"> -$'.$value['amount_adjust'].' </span>';
                }elseif($value['daily_rate']==4){
                    if($value['amount_inc']==1)
                        $value['description'] .= ' <span class="w3-text-green"> +$'.$value['amount_adjust'].' </span>';
                    else
                        $value['description'] .= ' <span class="w3-text-green"> -$'.$value['amount_adjust'].' </span>';
                    if($value['percent_inc']==1)
                        $value['description'] .= ' <span class="w3-text-green"> +'.$value['percent_adjust'].'% </span>';
                    else
                        $value['description'] .= ' <span class="w3-text-green"> -'.$value['percent_adjust'].'% </span>';
                }elseif($value['daily_rate']==5){
                    if($value['percent_inc']==1)
                        $value['description'] .= ' <span class="w3-text-green"> +'.$value['percent_adjust'].'% </span>';
                    else
                        $value['description'] .= ' <span class="w3-text-green"> -'.$value['percent_adjust'].'% </span>';
                    if($value['amount_inc']==1)
                        $value['description'] .= ' <span class="w3-text-green"> +$'.$value['amount_adjust'].' </span>';
                    else
                        $value['description'] .= ' <span class="w3-text-green"> -$'.$value['amount_adjust'].' </span>';
                }
            }
            if(isset($value['des']))
                $value['description'] .= $value['des'];
            else
                $value['des'] = '';
            if(!isset($value['child']))
                $value['child'] = array();
            
            if(isset($siteminder_room_type[$value['siteminder_room_type_id']])){
                $siteminder_room_type[$value['siteminder_room_type_id']]['child'][$key] = $value;
            }
        }
        $this->map['room_level'] = $room_level;
        $this->map['room_level_js'] = String::array2js($room_level);
        $this->map['items'] = $siteminder_room_type;
        $this->map['items_js'] = String::array2js($siteminder_room_type);
        
        //System::debug($siteminder_room_type);
        
        $this->parse_layout('list',$this->map);
	}
}
?>