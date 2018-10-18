<?php 
class SiteminderRoomRate extends Module
{
	function SiteminderRoomRate($row)
	{
	    if(Url::get('status')=='SAVEROOMTYPE' and Url::get('act')){
	       if(Url::get('act')=='ADD'){
	           $check = true;
               if(Url::get('room_level_id')!='' and DB::exists('select id from siteminder_room_type where room_level_id='.Url::get('room_level_id'))){
                    $check = false;
               }
               if($check){
                    $array = array('type_name'=>Url::get('type_name'),'portal_id'=>PORTAL_ID,'overbook_quantity'=>Url::get('overbook_quantity'),'auto_set_avail'=>Url::get('auto_set_avail'));
                    if(Url::get('room_level_id')!=''){
                        $room_level = DB::fetch('select * from room_level where id='.Url::get('room_level_id'));
                        $array += array('room_level_id'=>Url::get('room_level_id'),'inventory_type_code'=>$room_level['brief_name'],'room_type_code'=>$room_level['brief_name']);
                    }
                    $room_type_id = DB::insert('siteminder_room_type',$array);
                    if(Url::get('availability_default') and Url::get('start_date') and Url::get('end_date') and Url::get('room_level_id')!=''){
                        for($i=Date_Time::to_time(Url::get('start_date'));$i<=Date_Time::to_time(Url::get('end_date'));$i+=86400){
                            $time_data = array(
                                                'siteminder_room_type_id'=>$room_type_id,
                                                'in_date'=>Date_Time::to_orc_date(date('d/m/Y',$i)),
                                                'availability'=>Url::get('availability_default'),
                                                'time'=>$i
                                                );
                            DB::insert('siteminder_room_type_time',$time_data);
                        }
                        $SOAPBody = "<OTA_HotelAvailNotifRQ xmlns=\"http://www.opentravel.org/OTA/2003/05\" EchoToken=\"".$this->GetEchoToken()."\" TimeStamp=\"".$this->GetTimeStamp()."\" Version=\"1\">";
                            $SOAPBody .= "<POS>";
                                $SOAPBody .= "<Source>";
                                  $SOAPBody .= "<RequestorID Type=\"22\" ID=\"".SITEMINDER_REQUESTOR_ID."\"/>";
                                $SOAPBody .= "</Source>";
                            $SOAPBody .= "</POS>";
                            $SOAPBody .= "<AvailStatusMessages HotelCode=\"".SITEMINDER_HOTELCODE."\">";
                            $inventory_type_code = $room_level['brief_name'];
                                $SOAPBody .= "<AvailStatusMessage BookingLimit=\"".Url::get('availability_default')."\">";
                                  $SOAPBody .= "<StatusApplicationControl Start=\"".date('Y-m-d',Date_Time::to_time(Url::get('start_date')))."\" End=\"".date('Y-m-d',Date_Time::to_time(Url::get('end_date')))."\" InvTypeCode=\"".$inventory_type_code."\"/>";
                                $SOAPBody .= "</AvailStatusMessage>";
                            $SOAPBody .= "</AvailStatusMessages>";
                            $SOAPBody .= "</OTA_HotelAvailNotifRQ>";
                        
                        $id_soap = DB::insert('siteminder_soap_body',array('type'=>'AVAIL','time'=>time()));
                        $path = ROOT_PATH.'packages/hotel/packages/siteminder/includes/dataXml/SOAP_'.$id_soap.'.xml';
                        $this->save_file($path,$SOAPBody);
                    }
                    echo 'Success!';
               }else{
                    echo 'conflict room level';
               }
	       }else{
	           $check = true;
               if(Url::get('room_level_id')!='' and DB::exists('select id from siteminder_room_type where room_level_id='.Url::get('room_level_id').' and id!='.Url::get('id'))){
                    $check = false;
               }
               if($check){
                    $array = array('type_name'=>Url::get('type_name'),'portal_id'=>PORTAL_ID,'overbook_quantity'=>Url::get('overbook_quantity'),'auto_set_avail'=>Url::get('auto_set_avail'));
                    if(Url::get('room_level_id')!=''){
                        $room_level = DB::fetch('select * from room_level where id='.Url::get('room_level_id'));
                        $array += array('room_level_id'=>Url::get('room_level_id'),'inventory_type_code'=>$room_level['brief_name'],'room_type_code'=>$room_level['brief_name']);
                    }
                    DB::update('siteminder_room_type',$array,'id='.Url::get('id'));
                    echo 'Success!';
               }else{
                    echo 'conflict room level';
               }
	       }
           exit();
	    }elseif(Url::get('status')=='GETROOMTYPE' and Url::get('id')){
           echo json_encode(DB::fetch('select * from siteminder_room_type where id='.Url::get('id')));
           exit();
	    }elseif(Url::get('status')=='DELETEROOMTYPE' and Url::get('id')){
	       $room_rate = DB::fetch_all('select id from siteminder_room_rate where siteminder_room_type_id='.Url::get('id'));
           foreach($room_rate as $key=>$value){
                $room_rate_ota = DB::fetch_all('select id from siteminder_room_rate_ota where siteminder_room_rate_id='.$value['id']);
                foreach($room_rate_ota as $k=>$v){
                    DB::delete('siteminder_ota_cta_time','siteminder_room_rate_ota_id='.$v['id']);
                    DB::delete('siteminder_ota_ctd_time','siteminder_room_rate_ota_id='.$v['id']);
                    DB::delete('siteminder_ota_min_stay_time','siteminder_room_rate_ota_id='.$v['id']);
                    DB::delete('siteminder_ota_max_stay_time','siteminder_room_rate_ota_id='.$v['id']);
                    DB::delete('siteminder_ota_rates_time','siteminder_room_rate_ota_id='.$v['id']);
                    DB::delete('siteminder_ota_stop_sell_time','siteminder_room_rate_ota_id='.$v['id']);
                    
                    DB::delete('siteminder_room_rate_ota_over','siteminder_room_rate_ota_id='.$v['id']);
                }
                DB::delete('siteminder_room_rate_ota','siteminder_room_rate_id='.$value['id']);
                DB::delete('siteminder_room_rate_time','siteminder_room_rate_id='.$value['id']);
                DB::delete('siteminder_room_rate_over','siteminder_room_rate_id='.$value['id']);
           }
           DB::delete('siteminder_room_rate','siteminder_room_type_id='.Url::get('id'));
           DB::delete('siteminder_room_type_time','siteminder_room_type_id='.Url::get('id'));
           DB::delete('siteminder_room_type','id='.Url::get('id'));
           echo 'Success!';
           exit();
	    }elseif(Url::get('status')=='SAVEROOMRATE'){
	       $array = array(
                        'siteminder_room_type_id'=>Url::get('room_type_id'),
                        'rate_plan_code'=>Url::get('rate_plan_code'),
                        'rate_name'=>Url::get('rate_name'),
                        'availability'=>Url::get('availability'),
                        'auto_set_avail'=>Url::get('auto_set_avail'),
                        'overbook_quantity'=>Url::get('overbook_quantity'),
                        'default_min_stay'=>Url::get('default_min_stay'),
                        'default_stop_sell'=>Url::get('default_stop_sell'),
                        'rate_config_manual'=>Url::get('rate_config_manual'),
                        'manual_rack_rate'=>Url::get('manual_rack_rate'),
                        'rate_config_derive'=>Url::get('rate_config_derive'),
                        'derive_from_rate_id'=>Url::get('derive_from_rate_id'),
                        'daily_rate'=>Url::get('daily_rate'),
                        'amount_adjust'=>Url::get('amount_adjust'),
                        'amount_inc'=>Url::get('amount_inc'),
                        'amount_dec'=>Url::get('amount_dec'),
                        'percent_adjust'=>Url::get('percent_adjust'),
                        'percent_inc'=>Url::get('percent_inc'),
                        'percent_dec'=>Url::get('percent_dec')
                        );
            if(Url::get('act')=='ADD'){
                require_once 'packages/core/includes/system/si_database.php';
    			if(Url::get('derive_from_rate_id')) 
    			{   //  add dieu kien 
                    $array += array('structure_id'=>si_child('siteminder_room_rate',structure_id('siteminder_room_rate',Url::get('derive_from_rate_id'))));                  				
    			}
    			else
    			{
                    $array+= array('structure_id'=>si_child('siteminder_room_rate',structure_id('siteminder_room_rate',1)));
    			}
                $room_rate_id = DB::insert('siteminder_room_rate',$array);
                if(Url::get('start_date') and Url::get('end_date')){
                    for($i=Date_Time::to_time(Url::get('start_date'));$i<=Date_Time::to_time(Url::get('end_date'));$i+=86400){
                        $check = true;
                        if(Url::get('rate_config_derive')==1){
                            $parent_id = Url::get('derive_from_rate_id');
                            if($rates_parent = DB::fetch('select rates from siteminder_room_rate_time where time='.$i.' and siteminder_room_rate_id='.$parent_id)){
                                $rates = $rates_parent;
                                $daily_rate = Url::get('daily_rate');
                                $percent_inc = Url::get('percent_inc');
                                $percent_adjust = Url::get('percent_adjust');
                                $amount_inc = Url::get('amount_inc');
                                $amount_adjust = Url::get('amount_adjust');
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
                            }else{
                                $check = false;
                            }
                        }else{
                            $rates = Url::get('manual_rack_rate');
                        }
                        if($check){
                            $time_data = array(
                                            'siteminder_room_rate_id'=>$room_rate_id,
                                            'in_date'=>Date_Time::to_orc_date(date('d/m/Y',$i)),
                                            'rates'=>$rates,
                                            'time'=>$i
                                            );
                            DB::insert('siteminder_room_rate_time',$time_data);
                        }
                    }
                    if(Url::get('rate_config_derive')!=1 and Url::get('rate_plan_code')){
                        $rates = Url::get('manual_rack_rate');
                        if($room_type = DB::fetch('select * from siteminder_room_type where inventory_type_code!=\'\' and inventory_type_code is not null and id='.Url::get('room_type_id'))){
                            $SOAPBody = "<OTA_HotelRateAmountNotifRQ xmlns=\"http://www.opentravel.org/OTA/2003/05\" EchoToken=\"".$this->GetEchoToken()."\" TimeStamp=\"".$this->GetTimeStamp()."\" Version=\"1\">";
                                $SOAPBody .= "<POS>";
                                    $SOAPBody .= "<Source>";
                                      $SOAPBody .= "<RequestorID Type=\"22\" ID=\"".SITEMINDER_REQUESTOR_ID."\"/>";
                                    $SOAPBody .= "</Source>";
                                $SOAPBody .= "</POS>";
                                $SOAPBody .= "<RateAmountMessages HotelCode=\"".SITEMINDER_HOTELCODE."\">";
                                    $SOAPBody .= "<RateAmountMessage>";
                                      $SOAPBody .= "<StatusApplicationControl InvTypeCode=\"".$room_type['inventory_type_code']."\" RatePlanCode=\"".Url::get('rate_plan_code')."\" />";
                                      $SOAPBody .= "<Rates>";
                                        $SOAPBody .= "<Rate CurrencyCode=\"USD\" Start=\"".date('Y-m-d',Date_Time::to_time(Url::get('start_date')))."\" End=\"".date('Y-m-d',Date_Time::to_time(Url::get('end_date')))."\" Mon=\"1\" Tue=\"1\" Weds=\"1\" Thur=\"1\" Fri=\"1\" Sat=\"1\" Sun=\"1\">";
                                            $SOAPBody .= "<BaseByGuestAmts>";
                                            $SOAPBody .= "<BaseByGuestAmt AmountAfterTax=\"".$rates."\"/>";
                                            $SOAPBody .= "</BaseByGuestAmts>";
                                        $SOAPBody .= "</Rate>";
                                      $SOAPBody .= "</Rates>";
                                    $SOAPBody .= "</RateAmountMessage>";
                                $SOAPBody .= "</RateAmountMessages>";
                            $SOAPBody .= "</OTA_HotelRateAmountNotifRQ>";
                            
                            $id_soap = DB::insert('siteminder_soap_body',array('type'=>'RATES','time'=>time()));
                            $path = ROOT_PATH.'packages/hotel/packages/siteminder/includes/dataXml/SOAP_'.$id_soap.'.xml';
                            $this->save_file($path,$SOAPBody);
                        }
                        
                    }
                }
            }else{
                $old_value = DB::select('siteminder_room_rate','id=\''.Url::get('rate_id').'\'');
                DB::update('siteminder_room_rate',$array,'id='.Url::get('rate_id'));
                if($old_value['structure_id']!=ID_ROOT)
    			{
    			    require_once 'packages/core/includes/system/si_database.php';
    				if (Url::get('derive_from_rate_id'))
    				{
    					$parent = DB::select('siteminder_room_rate',Url::get('derive_from_rate_id'));	
                        //System::debug($parent); exit();				
    					if($parent['structure_id']!=$old_value['structure_id'])   
    					{
                            si_move('siteminder_room_rate',$old_value['structure_id'],$parent['structure_id']);
    					}
    				}else{
                        si_move('siteminder_room_rate',$old_value['structure_id'],ID_ROOT);
    				}
    			}
                if(Url::get('availability')=='LINKED'){
                    DB::delete('siteminder_rate_avail_time','siteminder_room_rate_id='.Url::get('rate_id').' and in_date>=\''.Date_time::to_orc_date(date('d/m/Y')).'\'');
                }
            }
            echo 'Success!';
            exit();
	    }elseif(Url::get('status')=='DELETEROOMRATE' and Url::get('id')){
           $room_rate_ota = DB::fetch_all('select id from siteminder_room_rate_ota where siteminder_room_rate_id='.Url::get('id'));
            foreach($room_rate_ota as $k=>$v){
                DB::delete('siteminder_ota_cta_time','siteminder_room_rate_ota_id='.$v['id']);
                DB::delete('siteminder_ota_ctd_time','siteminder_room_rate_ota_id='.$v['id']);
                DB::delete('siteminder_ota_min_stay_time','siteminder_room_rate_ota_id='.$v['id']);
                DB::delete('siteminder_ota_max_stay_time','siteminder_room_rate_ota_id='.$v['id']);
                DB::delete('siteminder_ota_rates_time','siteminder_room_rate_ota_id='.$v['id']);
                DB::delete('siteminder_ota_stop_sell_time','siteminder_room_rate_ota_id='.$v['id']);
                
                DB::delete('siteminder_room_rate_ota_over','siteminder_room_rate_ota_id='.$v['id']);
            }
            DB::delete('siteminder_room_rate_ota','siteminder_room_rate_id='.Url::get('id'));
            DB::delete('siteminder_room_rate_time','siteminder_room_rate_id='.Url::get('id'));
            DB::delete('siteminder_rate_avail_time','siteminder_room_rate_id='.Url::get('id'));
            DB::delete('siteminder_rate_cta_time','siteminder_room_rate_id='.Url::get('id'));
            DB::delete('siteminder_rate_ctd_time','siteminder_room_rate_id='.Url::get('id'));
            DB::delete('siteminder_rate_max_stay_time','siteminder_room_rate_id='.Url::get('id'));
            DB::delete('siteminder_rate_min_stay_time','siteminder_room_rate_id='.Url::get('id'));
            DB::delete('siteminder_rate_stop_sell_time','siteminder_room_rate_id='.Url::get('id'));
            DB::delete('siteminder_room_rate_over','siteminder_room_rate_id='.Url::get('id'));
            DB::delete('siteminder_room_rate','id='.Url::get('id'));
           echo 'Success!';
           exit();
	    }elseif(Url::get('status')=='GETROOMRATEOVER' and Url::get('id')){
	       $data = DB::fetch('select 
                                siteminder_room_rate.rate_name,
                                siteminder_room_type.type_name 
                            from 
                                siteminder_room_rate 
                                inner join siteminder_room_type on siteminder_room_type.id=siteminder_room_rate.siteminder_room_type_id
                            where
                                siteminder_room_rate.id='.Url::get('id').'
                            ');
            $data['rate_over'] = DB::fetch_all('select 
                                                    siteminder_room_rate_over.*,
                                                    to_char(siteminder_room_rate_over.from_date,\'DD/MM/YYYY\') as from_date, 
                                                    to_char(siteminder_room_rate_over.to_date,\'DD/MM/YYYY\') as to_date 
                                                from siteminder_room_rate_over 
                                                WHERE siteminder_room_rate_over.siteminder_room_rate_id='.Url::get('id').'
                                                ');
            foreach($data['rate_over'] as $key=>$value){
                $value['description'] = '';
                if($value['daily_rate']==1){
                    $value['description'] .= '<u>Keep rates the same</u>';
                }elseif($value['daily_rate']==2){
                    if($value['percent_inc']==1)
                        $value['description'] .= ' Increase'.$value['percent_adjust'].'%';
                    else
                        $value['description'] .= ' Decrease'.$value['percent_adjust'].'%';
                }elseif($value['daily_rate']==3){
                    if($value['amount_inc']==1)
                        $value['description'] .= ' Increase'.$value['amount_adjust'].'$';
                    else
                        $value['description'] .= ' Decrease'.$value['amount_adjust'].'$';
                }elseif($value['daily_rate']==4){
                    if($value['amount_inc']==1)
                        $value['description'] .= ' Increase'.$value['amount_adjust'].'$';
                    else
                        $value['description'] .= ' Decrease'.$value['amount_adjust'].'$';
                    if($value['percent_inc']==1)
                        $value['description'] .= ' Increase'.$value['percent_adjust'].'%';
                    else
                        $value['description'] .= ' Decrease'.$value['percent_adjust'].'%';
                }elseif($value['daily_rate']==5){
                    if($value['percent_inc']==1)
                        $value['description'] .= ' Increase'.$value['percent_adjust'].'%';
                    else
                        $value['description'] .= ' Decrease'.$value['percent_adjust'].'%';
                    if($value['amount_inc']==1)
                        $value['description'] .= ' Increase'.$value['amount_adjust'].'$';
                    else
                        $value['description'] .= ' Decrease'.$value['amount_adjust'].'$';
                }
                $data['rate_over'][$key]['description'] = $value['description'];
            }
            echo json_encode($data);
            exit();
	    }elseif(Url::get('status')=='SAVERATEOVER'){
	       if(DB::exists('select id from siteminder_room_rate_over where siteminder_room_rate_id='.Url::get('rate_id').' AND from_date<=\''.Date_Time::to_orc_date(Url::get('from_date')).'\' AND to_date>=\''.Date_Time::to_orc_date(Url::get('to_date')).'\' '.(Url::get('act')=='ADD'?'':' AND id!='.Url::get('RateOverrideId')))){
	           echo 'Conflict Derived Rate Overrides!';
	       }else{
	           $array = array(
                            'siteminder_room_rate_id'=>Url::get('rate_id'),
                            'from_date'=>Date_Time::to_orc_date(Url::get('from_date')),
                            'to_date'=>Date_Time::to_orc_date(Url::get('to_date')),
                            'daily_rate'=>Url::get('daily_rate'),
                            'amount_adjust'=>Url::get('amount_adjust'),
                            'amount_inc'=>Url::get('amount_inc'),
                            'amount_dec'=>Url::get('amount_dec'),
                            'percent_adjust'=>Url::get('percent_adjust'),
                            'percent_inc'=>Url::get('percent_inc'),
                            'percent_dec'=>Url::get('percent_dec')
                            );
               if(Url::get('act')=='ADD'){
                    DB::insert('siteminder_room_rate_over',$array);
               }else{
                    DB::update('siteminder_room_rate_over',$array,'id='.Url::get('RateOverrideId'));
               }
               echo 'Success!';
	       }
           exit();
	    }elseif(Url::get('status')=='DELETERATEOVER' and Url::get('id')){
            DB::delete('siteminder_room_rate_over','id='.Url::get('id'));
            echo 'Success!';
            exit();
	    }elseif(Url::get('status')=='MOVEROOMRATE'){
	       $data = array('count_room_type'=>0,'value'=>array());
           $data['value'] = DB::fetch_all('select * from siteminder_room_type where portal_id=\''.PORTAL_ID.'\' ORDER BY type_name');
           $data['count_room_type'] = sizeof($data['value']);
           echo json_encode($data);
           exit();
	    }elseif(Url::get('status')=='SAVEMOVEROOMRATE' and Url::get('room_rate_id') and Url::get('room_type_id')){
	       DB::update('siteminder_room_rate',array('siteminder_room_type_id'=>Url::get('room_type_id')),'id='.Url::get('room_rate_id'));
	       echo 'Success!';
           exit();
	    }elseif(Url::get('status')=='GETCHANNEL'){
	       $data = array('count_channel'=>0,'value'=>array());
           $data['value'] = DB::fetch_all(' SELECT
                                                siteminder_map_customer.*,
                                                siteminder_booking_channel.code as id,
                                                siteminder_map_customer.id as siteminder_map_customer_id,
                                                siteminder_booking_channel.name as booking_channel_name,
                                                customer.name as customer_name
                                            FROM
                                                siteminder_map_customer
                                                inner join siteminder_booking_channel on siteminder_booking_channel.code=siteminder_map_customer.booking_channel_code
                                                inner join customer on customer.id=siteminder_map_customer.customer_id
                                            WHERE
                                                siteminder_map_customer.portal_id=\''.PORTAL_ID.'\'
                                            ORDER BY
                                                siteminder_booking_channel.code
                                            ');
           $maped = DB::fetch_all('select siteminder_ota_code as id from siteminder_room_rate_ota where siteminder_room_rate_id='.Url::get('rate_id'));
           foreach($data['value'] as $key=>$value){
                if(isset($maped[$value['id']])){
                    unset($data['value'][$key]);
                }
           }
           $data['count_channel'] = sizeof($data['value']);
           echo json_encode($data);
           exit();
	    }elseif(Url::get('status')=='CONNECTCHANNEL' and Url::get('rate_id') and Url::get('ota_code')){
	       
	       if(DB::exists('select id from siteminder_room_rate_ota where siteminder_room_rate_id='.Url::get('rate_id').' and siteminder_ota_code=\''.Url::get('ota_code').'\'')){
	           echo json_encode(array('messenge'=>'conflict','id'=>''));
	       }else{
	           $rate_ota_id =  DB::insert('siteminder_room_rate_ota',array(
                                                                            'siteminder_room_rate_id'=>Url::get('rate_id'),
                                                                            'siteminder_ota_code'=>Url::get('ota_code'),
                                                                            'manual_derive'=>'DERIVE',
                                                                            'daily_rate'=>1,
                                                                            'amount_inc'=>1,
                                                                            'percent_inc'=>1,
                                                                            'sent_rates'=>1,
                                                                            'sent_stop_sell'=>1,
                                                                            'sent_cta'=>1,
                                                                            'sent_ctd'=>1,
                                                                            'sent_min_stay'=>1,
                                                                            'sent_max_stay'=>1
                                                                            ));
                echo json_encode(array('messenge'=>'Success!','id'=>$rate_ota_id));
	       }
           exit();
	    }elseif(Url::get('status')=='EDITRATEOTA'){
	       $data = array();
           $data = DB::fetch('
                                SELECT
                                    siteminder_room_rate_ota.*,
                                    siteminder_room_rate.rate_name,
                                    siteminder_room_type.type_name,
                                    siteminder_booking_channel.name as booking_channel_name
                                FROM
                                    siteminder_room_rate_ota
                                    inner join siteminder_room_rate on siteminder_room_rate.id=siteminder_room_rate_ota.siteminder_room_rate_id
                                    inner join siteminder_room_type on siteminder_room_type.id=siteminder_room_rate.siteminder_room_type_id
                                    inner join siteminder_booking_channel on siteminder_booking_channel.code=siteminder_room_rate_ota.siteminder_ota_code
                                WHERE
                                    siteminder_room_rate_ota.id='.Url::get('rate_ota_id').'
                            ');
           echo json_encode($data);
           exit();
	    }elseif(Url::get('status')=='SAVERATEOTA'){
           DB::update('siteminder_room_rate_ota',array(
                                                        'Manual_derive'=>Url::get('Manual_derive'),
                                                        'daily_rate'=>Url::get('daily_rate'),
                                                        'amount_adjust'=>Url::get('amount_adjust'),
                                                        'amount_inc'=>Url::get('amount_inc'),
                                                        'amount_dec'=>Url::get('amount_dec'),
                                                        'percent_adjust'=>Url::get('percent_adjust'),
                                                        'percent_inc'=>Url::get('percent_inc'),
                                                        'percent_dec'=>Url::get('percent_dec'),
                                                        'sent_rates'=>Url::get('sent_rates'),
                                                        'sent_stop_sell'=>Url::get('sent_stop_sell'),
                                                        'sent_cta'=>Url::get('sent_cta'),
                                                        'sent_ctd'=>Url::get('sent_ctd'),
                                                        'sent_min_stay'=>Url::get('sent_min_stay'),
                                                        'sent_max_stay'=>Url::get('sent_max_stay')
                                                        ),'id='.Url::get('rate_ota_id'));
           echo 'Success!';
           exit();
	    }elseif(Url::get('status')=='DELETERATEOTA'){
	        DB::delete('siteminder_ota_cta_time','siteminder_room_rate_ota_id='.Url::get('id'));
            DB::delete('siteminder_ota_ctd_time','siteminder_room_rate_ota_id='.Url::get('id'));
            DB::delete('siteminder_ota_min_stay_time','siteminder_room_rate_ota_id='.Url::get('id'));
            DB::delete('siteminder_ota_max_stay_time','siteminder_room_rate_ota_id='.Url::get('id'));
            DB::delete('siteminder_ota_rates_time','siteminder_room_rate_ota_id='.Url::get('id'));
            DB::delete('siteminder_ota_stop_sell_time','siteminder_room_rate_ota_id='.Url::get('id'));
           DB::delete('siteminder_room_rate_ota_over','siteminder_room_rate_ota_id='.Url::get('id'));
           DB::delete('siteminder_room_rate_ota','id='.Url::get('id'));
           echo 'Success!';
           exit();
	    }elseif(Url::get('status')=='GETRATEOTAOVER' and Url::get('id')){
	       $data = DB::fetch('select 
                                siteminder_booking_channel.code,
                                siteminder_booking_channel.name,
                                siteminder_room_rate.rate_name,
                                siteminder_room_type.type_name 
                            from 
                                siteminder_room_rate_ota
                                inner join siteminder_booking_channel on siteminder_booking_channel.code=siteminder_room_rate_ota.siteminder_ota_code
                                inner join siteminder_room_rate on siteminder_room_rate.id=siteminder_room_rate_ota.siteminder_room_rate_id
                                inner join siteminder_room_type on siteminder_room_type.id=siteminder_room_rate.siteminder_room_type_id
                            where
                                siteminder_room_rate_ota.id='.Url::get('id').'
                            ');
            $data['rate_over'] = DB::fetch_all('select 
                                                    siteminder_room_rate_ota_over.*,
                                                    to_char(siteminder_room_rate_ota_over.from_date,\'DD/MM/YYYY\') as from_date, 
                                                    to_char(siteminder_room_rate_ota_over.to_date,\'DD/MM/YYYY\') as to_date 
                                                from siteminder_room_rate_ota_over 
                                                WHERE siteminder_room_rate_ota_over.siteminder_room_rate_ota_id='.Url::get('id').'
                                                ');
            foreach($data['rate_over'] as $key=>$value){
                $value['description'] = '';
                if($value['daily_rate']==1){
                    $value['description'] .= '<u>Keep rates the same</u>';
                }elseif($value['daily_rate']==2){
                    if($value['percent_inc']==1)
                        $value['description'] .= ' Increase'.$value['percent_adjust'].'%';
                    else
                        $value['description'] .= ' Decrease'.$value['percent_adjust'].'%';
                }elseif($value['daily_rate']==3){
                    if($value['amount_inc']==1)
                        $value['description'] .= ' Increase'.$value['amount_adjust'].'$';
                    else
                        $value['description'] .= ' Decrease'.$value['amount_adjust'].'$';
                }elseif($value['daily_rate']==4){
                    if($value['amount_inc']==1)
                        $value['description'] .= ' Increase'.$value['amount_adjust'].'$';
                    else
                        $value['description'] .= ' Decrease'.$value['amount_adjust'].'$';
                    if($value['percent_inc']==1)
                        $value['description'] .= ' Increase'.$value['percent_adjust'].'%';
                    else
                        $value['description'] .= ' Decrease'.$value['percent_adjust'].'%';
                }elseif($value['daily_rate']==5){
                    if($value['percent_inc']==1)
                        $value['description'] .= ' Increase'.$value['percent_adjust'].'%';
                    else
                        $value['description'] .= ' Decrease'.$value['percent_adjust'].'%';
                    if($value['amount_inc']==1)
                        $value['description'] .= ' Increase'.$value['amount_adjust'].'$';
                    else
                        $value['description'] .= ' Decrease'.$value['amount_adjust'].'$';
                }
                $data['rate_over'][$key]['description'] = $value['description'];
            }
            echo json_encode($data);
            exit();
	    }elseif(Url::get('status')=='SAVERATEOTAOVER'){
	       if(DB::exists('select id from siteminder_room_rate_ota_over where siteminder_room_rate_ota_id='.Url::get('rate_ota_id').' AND from_date<=\''.Date_Time::to_orc_date(Url::get('from_date')).'\' AND to_date>=\''.Date_Time::to_orc_date(Url::get('to_date')).'\' '.(Url::get('act')=='ADD'?'':' AND id!='.Url::get('RateOverrideId')))){
	           echo 'Conflict Derived Rate Overrides!';
	       }else{
	           $array = array(
                            'siteminder_room_rate_ota_id'=>Url::get('rate_ota_id'),
                            'from_date'=>Date_Time::to_orc_date(Url::get('from_date')),
                            'to_date'=>Date_Time::to_orc_date(Url::get('to_date')),
                            'daily_rate'=>Url::get('daily_rate'),
                            'amount_adjust'=>Url::get('amount_adjust'),
                            'amount_inc'=>Url::get('amount_inc'),
                            'amount_dec'=>Url::get('amount_dec'),
                            'percent_adjust'=>Url::get('percent_adjust'),
                            'percent_inc'=>Url::get('percent_inc'),
                            'percent_dec'=>Url::get('percent_dec')
                            );
               if(Url::get('act')=='ADD'){
                    DB::insert('siteminder_room_rate_ota_over',$array);
               }else{
                    DB::update('siteminder_room_rate_ota_over',$array,'id='.Url::get('RateOverrideId'));
               }
               echo 'Success!';
	       }
           exit();
	    }elseif(Url::get('status')=='DELETERATEOTAOVER' and Url::get('id')){
           DB::delete('siteminder_room_rate_ota_over','id='.Url::get('id'));
           echo 'Success!';
           exit();
	    }elseif(Url::get('status')=='GETITEMSRATE'){
	       $data = array();
           $siteminder_room_type = DB::fetch_all('select 
                                                        siteminder_room_type.*, 
                                                        room_level.brief_name as room_level_code,
                                                        room_level.name as room_level_name
                                                    from  
                                                        siteminder_room_type
                                                        left join room_level on room_level.id=siteminder_room_type.room_level_id
                                                    where siteminder_room_type.portal_id=\''.PORTAL_ID.'\'
                                                    ');
            
            $siteminder_room_rate = DB::fetch_all('select 
                                                        siteminder_room_rate.*
                                                    from 
                                                        siteminder_room_rate
                                                        inner join siteminder_room_type on siteminder_room_type.id=siteminder_room_rate.siteminder_room_type_id 
                                                    where 
                                                        siteminder_room_type.portal_id=\''.PORTAL_ID.'\'
                                                    ');
            foreach($siteminder_room_rate as $key=>$value){
                if(!isset($siteminder_room_rate[$key]['status']))
                    $siteminder_room_rate[$key]['status'] = 0;
                
                if(Url::get('rate_id') and Url::get('rate_id')==$value['id']){
                    $start_idstruct = $value['structure_id'];
                    $end_idstruct = IDStructure::next($start_idstruct);
                    foreach($siteminder_room_rate as $k=>$v){
                        if($v['structure_id']>$start_idstruct and $v['structure_id']<$end_idstruct and $v['id']!=$value['id']){
                            $siteminder_room_rate[$k]['status'] = 1;
                        }
                    }
                }
            }
            foreach($siteminder_room_rate as $key=>$value){
                $check = true;
                if(Url::get('rate_id') and Url::get('rate_id')==$value['id']){
                    $check = false;
                }
                if(isset($siteminder_room_type[$value['siteminder_room_type_id']]) and $check and $value['status']==0){
                    if(!isset($data[$value['siteminder_room_type_id']]))
                        $data[$value['siteminder_room_type_id']] = $siteminder_room_type[$value['siteminder_room_type_id']];
                    $data[$value['siteminder_room_type_id']]['child'][$key] = $value;
                }
            }
           echo json_encode($data);
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
                    $this->add_form(new ListSiteminderRoomRateForm());
				}
				break;
			}
        }else{
            Url::access_denied();
        }
	}
    function GetEchoToken()
	{
        /**
        * Get EchoToken
        * 
        * @return EchoToken with GUID : https://en.wikipedia.org/wiki/Universally_unique_identifier
        */
        if (function_exists('com_create_guid') === true)
        {
            return trim(com_create_guid(), '{}');
        }
        return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
	}
    
    function GetTimeStamp()
	{
        /**
        * Get TimeStamp
        * 
        * @return TimeStamp with Timezone GMT+7
        */
        //return gmdate(DATE_ATOM,time());
        return date('Y-m-d').'T'.date('H:i:s').'+07:00';
	}
    function save_file($file,$content)
    {
    	$handler = fopen($file,'w+');
    	fwrite($handler,$content);
    	fclose($handler);
    }
}
?>