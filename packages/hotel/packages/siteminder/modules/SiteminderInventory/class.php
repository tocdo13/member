<?php 
class SiteminderInventory extends Module
{
	function SiteminderInventory($row)
	{
	    if(Url::get('status')=='UPDATERATES' and Url::get('room_rate_id') and Url::get('time') and Url::get('rates_value')){
	           $data_return = array('room_rate'=>array(),'ota'=>array());
	           $siteminder_room_rate = DB::fetch_all('select 
                                                            siteminder_room_rate.*
                                                        from 
                                                            siteminder_room_rate
                                                            inner join siteminder_room_type on siteminder_room_type.id=siteminder_room_rate.siteminder_room_type_id
                                                        where 
                                                            siteminder_room_type.portal_id=\''.PORTAL_ID.'\'
                                                        ORDER BY
                                                            structure_id DESC
                                                        ');
                $siteminder_room_rate_over = DB::fetch_all('select 
                                                            siteminder_room_rate_over.*,
                                                            to_char(siteminder_room_rate_over.from_date,\'DD/MM/YYYY\') as from_date,
                                                            to_char(siteminder_room_rate_over.to_date,\'DD/MM/YYYY\') as to_date
                                                        from 
                                                            siteminder_room_rate_over
                                                            inner join siteminder_room_rate on siteminder_room_rate.id= siteminder_room_rate_over.siteminder_room_rate_id
                                                            inner join siteminder_room_type on siteminder_room_type.id=siteminder_room_rate.siteminder_room_type_id 
                                                        where 
                                                            siteminder_room_type.portal_id=\''.PORTAL_ID.'\'
                                                            ');
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
                                                inner join siteminder_map_customer on (siteminder_booking_channel.code=siteminder_map_customer.booking_channel_code and siteminder_map_customer.portal_id=\''.PORTAL_ID.'\')
                                                inner join customer on (customer.id=siteminder_map_customer.customer_id and customer.portal_id=\''.PORTAL_ID.'\')
                                            WHERE 
                                                siteminder_room_type.portal_id=\''.PORTAL_ID.'\'
                                        ');
                $channel_over = DB::fetch_all('
                                                SELECT
                                                    siteminder_room_rate_ota_over.*,
                                                    to_char(siteminder_room_rate_ota_over.from_date,\'DD/MM/YYYY\') as from_date,
                                                    to_char(siteminder_room_rate_ota_over.to_date,\'DD/MM/YYYY\') as to_date
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
                foreach($channel as $key=>$value){
                    if(isset($siteminder_room_rate[$value['siteminder_room_rate_id']])){
                        $siteminder_room_rate[$value['siteminder_room_rate_id']]['child'][$key] = $value;
                    }
                }
                $room_rate_level = array();
                $siteminder_room_rate_partent = array();
                foreach($siteminder_room_rate as $key=>$value){
                    $level = IDStructure::level($value['structure_id']);
                    $room_rate_level[$level]['id'] = $level;
                    $room_rate_level[$level]['child'][$key] = $siteminder_room_rate[$key];
                    if(Url::get('room_rate_id')==$value['id']){
                        $siteminder_room_rate_partent[$value['id']] = $value;
                        $siteminder_room_rate_partent[$value['id']]['timeline'][Url::get('time')]['rates'] = Url::get('rates_value');
                        if(isset($value['child'])){
                            foreach($value['child'] as $keyOTA=>$valueOTA){
                                if(true){//if( Url::get('bulkOTA') and isset($_REQUEST['bulkOTA'][$valueOTA['ota_code']]) ){
                                    //foreach($_REQUEST['bulkrange'] as $keyRange=>$valueRange){
                                        //for($i=Date_Time::to_time($valueRange['from_date']);$i<=Date_Time::to_time($valueRange['to_date']);$i+=86400){
                                            $i = Url::get('time');
                                            //$date = getdate($i);
                                            //$date['weekday'] = strtoupper(substr($date['weekday'],0,3));
                                            //if( isset($valueRange[$date['weekday']]) ){
                                                $rate_parent = Url::get('rates_value');
                                                $rates = $rate_parent;
                                                if($valueOTA['manual_derive']=='DERIVE'){
                                                    $daily_rate = $valueOTA['daily_rate'];
                                                    $percent_inc = $valueOTA['percent_inc'];
                                                    $percent_adjust = $valueOTA['percent_adjust'];
                                                    $amount_inc = $valueOTA['amount_inc'];
                                                    $amount_adjust = $valueOTA['amount_adjust'];
                                                    
                                                    foreach($channel_over as $keyOVER=>$valueOVER){
                                                        if($valueOTA['id']==$valueOVER['siteminder_room_rate_ota_id']){
                                                            $from_time = Date_Time::to_time($valueOVER['from_date']);
                                                            $to_time = Date_Time::to_time($valueOVER['to_date']);
                                                            if($i>=$from_time and $i<=$to_time){
                                                                $daily_rate = $valueOVER['daily_rate'];
                                                                $percent_inc = $valueOVER['percent_inc'];
                                                                $percent_adjust = $valueOVER['percent_adjust'];
                                                                $amount_inc = $valueOVER['amount_inc'];
                                                                $amount_adjust = $valueOVER['amount_adjust'];
                                                            }
                                                        }
                                                    }
                                                    if($daily_rate==2){
                                                        if($percent_inc==1)
                                                            $rates = $rates + (($percent_adjust*$rates)/100);
                                                        else
                                                            $rates = $rates - (($percent_adjust*$rates)/100);
                                                    }elseif($daily_rate==3){
                                                        if($amount_inc==1)
                                                            $rates = $rates + $amount_adjust;
                                                        else
                                                            $rates = $rates - $amount_adjust;
                                                    }elseif($daily_rate==4){
                                                        if($amount_inc==1)
                                                            $rates = $rates + $amount_adjust;
                                                        else
                                                            $rates = $rates - $amount_adjust;
                                                        if($percent_inc==1)
                                                            $rates = $rates + (($percent_adjust*$rates)/100);
                                                        else
                                                            $rates = $rates - (($percent_adjust*$rates)/100);
                                                    }elseif($daily_rate==5){
                                                        if($percent_inc==1)
                                                            $rates = $rates + (($percent_adjust*$rates)/100);
                                                        else
                                                            $rates = $rates - (($percent_adjust*$rates)/100);
                                                        if($amount_inc==1)
                                                            $rates = $rates + $amount_adjust;
                                                        else
                                                            $rates = $rates - $amount_adjust;
                                                    }
                                                    $data_return['ota'][$valueOTA['id']]['timeline'][$i]['rates'] = $rates;
                                                }
                                                
                                            //} // end weekday
                                        //} // end for time
                                    //} // end foreach range
                                } // end check $_REQUEST
                            } // end foreach OTA
                        }// end check OTA
                    }
                }
                // xet rate 
                foreach($room_rate_level as $key=>$value){
                    foreach($value['child'] as $keyChild=>$valueChild){ // room rate
                        $parent_id = $valueChild['derive_from_rate_id'];
                        if($valueChild['rate_config_derive']==1 and isset($siteminder_room_rate_partent[$parent_id])){
                            $siteminder_room_rate_partent[$keyChild] = $valueChild;
                            //foreach($_REQUEST['bulkrange'] as $keyRange=>$valueRange){
                                //for($i=Date_Time::to_time($valueRange['from_date']);$i<=Date_Time::to_time($valueRange['to_date']);$i+=86400){
                                    $i = Url::get('time');
                                    //$date = getdate($i);
                                    //$date['weekday'] = strtoupper(substr($date['weekday'],0,3));
                                    //if( isset($valueRange[$date['weekday']]) ){
                                        $rate_parent = $siteminder_room_rate_partent[$parent_id]['timeline'][$i]['rates'];
                                        $rates = $rate_parent;
                                        
                                        /////////
                                        $daily_rate = $valueChild['daily_rate'];
                                        $percent_inc = $valueChild['percent_inc'];
                                        $percent_adjust = $valueChild['percent_adjust'];
                                        $amount_inc = $valueChild['amount_inc'];
                                        $amount_adjust = $valueChild['amount_adjust'];
                                        
                                        foreach($siteminder_room_rate_over as $keyOVER=>$valueOVER){
                                            if($valueChild['id']==$valueOVER['siteminder_room_rate_id']){
                                                $from_time = Date_Time::to_time($valueOVER['from_date']);
                                                $to_time = Date_Time::to_time($valueOVER['to_date']);
                                                if($i>=$from_time and $i<=$to_time){
                                                    $daily_rate = $valueOVER['daily_rate'];
                                                    $percent_inc = $valueOVER['percent_inc'];
                                                    $percent_adjust = $valueOVER['percent_adjust'];
                                                    $amount_inc = $valueOVER['amount_inc'];
                                                    $amount_adjust = $valueOVER['amount_adjust'];
                                                }
                                            }
                                        }
                                        
                                        if($daily_rate==2){
                                            if($percent_inc==1)
                                                $rates = $rates + (($percent_adjust*$rates)/100);
                                            else
                                                $rates = $rates - (($percent_adjust*$rates)/100);
                                        }elseif($daily_rate==3){
                                            if($amount_inc==1)
                                                $rates = $rates + $amount_adjust;
                                            else
                                                $rates = $rates - $amount_adjust;
                                        }elseif($daily_rate==4){
                                            if($amount_inc==1)
                                                $rates = $rates + $amount_adjust;
                                            else
                                                $rates = $rates - $amount_adjust;
                                            if($percent_inc==1)
                                                $rates = $rates + (($percent_adjust*$rates)/100);
                                            else
                                                $rates = $rates - (($percent_adjust*$rates)/100);
                                        }elseif($daily_rate==5){
                                            if($percent_inc==1)
                                                $rates = $rates + (($percent_adjust*$rates)/100);
                                            else
                                                $rates = $rates - (($percent_adjust*$rates)/100);
                                            if($amount_inc==1)
                                                $rates = $rates + $amount_adjust;
                                            else
                                                $rates = $rates - $amount_adjust;
                                        }
                                        $data_return['room_rate'][$valueChild['id']]['timeline'][$i]['rates'] = $rates;
                                        $siteminder_room_rate_partent[$keyChild]['timeline'][$i]['rates'] = $rates;
                                    //} // end weekday
                                //} // end for time
                            //} // end time range
                            // xet OTA
                            if(isset($valueChild['child'])){
                                foreach($valueChild['child'] as $keyOTA=>$valueOTA){
                                    if(true){//if( Url::get('bulkOTA') and isset($_REQUEST['bulkOTA'][$valueOTA['ota_code']]) ){
                                        //foreach($_REQUEST['bulkrange'] as $keyRange=>$valueRange){
                                            //for($i=Date_Time::to_time($valueRange['from_date']);$i<=Date_Time::to_time($valueRange['to_date']);$i+=86400){
                                                $i = Url::get('time');
                                                //$date = getdate($i);
                                                //$date['weekday'] = strtoupper(substr($date['weekday'],0,3));
                                                //if( isset($valueRange[$date['weekday']]) ){
                                                    $rate_parent = $siteminder_room_rate_partent[$keyChild]['timeline'][$i]['rates'];
                                                    $rates = $rate_parent;
                                                    if($valueOTA['manual_derive']=='DERIVE'){
                                                        $daily_rate = $valueOTA['daily_rate'];
                                                        $percent_inc = $valueOTA['percent_inc'];
                                                        $percent_adjust = $valueOTA['percent_adjust'];
                                                        $amount_inc = $valueOTA['amount_inc'];
                                                        $amount_adjust = $valueOTA['amount_adjust'];
                                                        
                                                        foreach($channel_over as $keyOVER=>$valueOVER){
                                                            if($valueOTA['id']==$valueOVER['siteminder_room_rate_ota_id']){
                                                                $from_time = Date_Time::to_time($valueOVER['from_date']);
                                                                $to_time = Date_Time::to_time($valueOVER['to_date']);
                                                                if($i>=$from_time and $i<=$to_time){
                                                                    $daily_rate = $valueOVER['daily_rate'];
                                                                    $percent_inc = $valueOVER['percent_inc'];
                                                                    $percent_adjust = $valueOVER['percent_adjust'];
                                                                    $amount_inc = $valueOVER['amount_inc'];
                                                                    $amount_adjust = $valueOVER['amount_adjust'];
                                                                }
                                                            }
                                                        }
                                                        if($daily_rate==2){
                                                            if($percent_inc==1)
                                                                $rates = $rates + (($percent_adjust*$rates)/100);
                                                            else
                                                                $rates = $rates - (($percent_adjust*$rates)/100);
                                                        }elseif($daily_rate==3){
                                                            if($amount_inc==1)
                                                                $rates = $rates + $amount_adjust;
                                                            else
                                                                $rates = $rates - $amount_adjust;
                                                        }elseif($daily_rate==4){
                                                            if($amount_inc==1)
                                                                $rates = $rates + $amount_adjust;
                                                            else
                                                                $rates = $rates - $amount_adjust;
                                                            if($percent_inc==1)
                                                                $rates = $rates + (($percent_adjust*$rates)/100);
                                                            else
                                                                $rates = $rates - (($percent_adjust*$rates)/100);
                                                        }elseif($daily_rate==5){
                                                            if($percent_inc==1)
                                                                $rates = $rates + (($percent_adjust*$rates)/100);
                                                            else
                                                                $rates = $rates - (($percent_adjust*$rates)/100);
                                                            if($amount_inc==1)
                                                                $rates = $rates + $amount_adjust;
                                                            else
                                                                $rates = $rates - $amount_adjust;
                                                        }
                                                        $data_return['ota'][$valueOTA['id']]['timeline'][$i]['rates'] = $rates;
                                                    }
                                                    
                                                //} // end weekday
                                            //} // end for time
                                        //} // end foreach range
                                    } // end check $_REQUEST
                                } // end foreach OTA
                            }// end check OTA
                        } // end if check partent
                    } // end $value['child'] = room rate
                } // end $room_rate_level
                
               echo json_encode($data_return);
               exit(); 
	    }
		Module::Module($row);
        if(User::can_edit(false,ANY_CATEGORY))
        {
            switch(URL::get('cmd'))
			{
                case 'add':
    				require_once 'forms/edit.php';
                    $this->add_form(new EditSiteminderRoomRateForm());
                    break;
                case 'edit':
    				require_once 'forms/edit.php';
                    $this->add_form(new EditSiteminderRoomRateForm());
                    break;
    			default:
				{
					require_once 'forms/list.php';
                    $this->add_form(new ListSiteminderInventoryForm());
				}
				break;
			}
        }else{
            Url::access_denied();
        }
	}
}
?>