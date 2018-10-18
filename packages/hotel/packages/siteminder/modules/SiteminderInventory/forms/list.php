<?php
class ListSiteminderInventoryForm extends Form
{
	function ListSiteminderInventoryForm()
	{
		Form::Form('ListSiteminderInventoryForm');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
        $this->link_css('packages/hotel/packages/siteminder/skins/css/jquery.webui-popover.min.css');
        $this->link_js('packages/core/includes/js/jquery/datepicker.js');
        $this->link_js('packages/hotel/packages/siteminder/skins/js/jquery.webui-popover.min.js');
        
	}
    function on_submit(){
        //System::debug($_REQUEST); die;
        if(Url::get('act')=='SAVE_BULK'){
            require_once 'packages/hotel/packages/reception/modules/includes/reservation.php';
            // Quick Update
            if(Url::get('bulk_type')=='AVAIL' and Url::get('bulkrange') and (Url::get('bulkroomtype') or Url::get('bulkroomrate'))){
                /**
                 * @UpdateAvailRoomTye
                 * @data : 
                 *  ----- Url::get('bulkrange') : time
                 *  ----- Url::get('bulkroomtype') : room type array
                **/
                // get all roomType
                $siteminder_room_type = DB::fetch_all('select 
                                                            siteminder_room_type.*,
                                                            NVL(siteminder_room_type.overbook_quantity,0) as overbook_quantity,
                                                            room_level.brief_name as room_level_code,
                                                            room_level.name as room_level_name
                                                        from  
                                                            siteminder_room_type
                                                            inner join room_level on room_level.id=siteminder_room_type.room_level_id
                                                        where 
                                                            siteminder_room_type.portal_id=\''.PORTAL_ID.'\'
                                                        ORDER BY 
                                                            siteminder_room_type.type_name
                                                        ');
                $siteminder_room_rate = DB::fetch_all('select 
                                                            siteminder_room_rate.*,
                                                            NVL(siteminder_room_rate.overbook_quantity,0) as overbook_quantity,
                                                            siteminder_room_type.inventory_type_code,
                                                            siteminder_room_type.room_level_id
                                                        from 
                                                            siteminder_room_rate
                                                            inner join siteminder_room_type on siteminder_room_type.id=siteminder_room_rate.siteminder_room_type_id
                                                            inner join room_level on room_level.id=siteminder_room_type.room_level_id
                                                        where 
                                                            siteminder_room_type.portal_id=\''.PORTAL_ID.'\'
                                                        ');
                // get all roomRate
                $siteminder_room_rate_time = DB::fetch_all('select 
                                                            siteminder_rate_avail_time.*,
                                                            siteminder_room_type.id as siteminder_room_type_id
                                                        from 
                                                            siteminder_rate_avail_time
                                                            inner join siteminder_room_rate on siteminder_room_rate.id=siteminder_rate_avail_time.siteminder_room_rate_id
                                                            inner join siteminder_room_type on siteminder_room_type.id=siteminder_room_rate.siteminder_room_type_id
                                                        where 
                                                            siteminder_room_type.portal_id=\''.PORTAL_ID.'\'
                                                            and siteminder_rate_avail_time.time>='.Date_Time::to_time(date('d/m/Y')).'
                                                        ');
                $type_rate_avail = array();
                foreach($siteminder_room_rate_time as $keyAvail=>$valueAvail){
                    $avail_rate = $valueAvail['availability'];
                    $avail_rate_time = $valueAvail['time'];
                    if(isset($_REQUEST['bulkroomrate'][$valueAvail['siteminder_room_rate_id']])){
                        foreach($_REQUEST['bulkrange'] as $keyRangeR=>$valueRangeR){
                            for($i=Date_Time::to_time($valueRangeR['from_date']);$i<=Date_Time::to_time($valueRangeR['to_date']);$i+=86400){
                                if($avail_rate_time==$i){
                                    $date = getdate($i);
                                    $date['weekday'] = strtoupper(substr($date['weekday'],0,3));
                                    if(isset($valueRange[$date['weekday']]))
                                        $avail_rate = Url::get('bulk_text');
                                }
                            }
                        }
                    }
                    
                    if(!isset($type_rate_avail[$valueAvail['siteminder_room_type_id']]['timeline'][$valueAvail['time']])){
                        $type_rate_avail[$valueAvail['siteminder_room_type_id']]['timeline'][$valueAvail['time']]['avail'] = $avail_rate;
                    }else{
                        $type_rate_avail[$valueAvail['siteminder_room_type_id']]['timeline'][$valueAvail['time']]['avail'] += $avail_rate;
                    }
                    
                }
                // get Avail
                $availability = Url::get('bulk_text');
                $data_xml = array();
                $data_rate_xml = array();
                // update room type
                foreach($siteminder_room_type as $key=>$value){
                    if(isset($_REQUEST['bulkroomtype'][$value['id']])){
                        $overbook_quantity = 0; //$value['overbook_quantity'];
                        foreach($_REQUEST['bulkrange'] as $keyRange=>$valueRange){
                            /** check phong trong trong khoang thoi gian nay - so luong phong trong nay da check voi ca inventory cu **/
                            $room_levels = check_availability('','rl.id='.$value['room_level_id'],Date_Time::to_time($valueRange['from_date']),(Date_Time::to_time($valueRange['to_date'])+86400));
                            /** inventory cu **/
                            $availability_old = DB::fetch_all('select siteminder_room_type_time.*,siteminder_room_type_time.time as id from siteminder_room_type_time where time>='.Date_Time::to_time($valueRange['from_date']).' and time<='.(Date_Time::to_time($valueRange['to_date'])+86400).' and siteminder_room_type_id='.$value['id']);
                            //System::debug($availability_old);
                            $room_level_id = $value['room_level_id'];
                            for($i=Date_Time::to_time($valueRange['from_date']);$i<=Date_Time::to_time($valueRange['to_date']);$i+=86400){
                                if($i>=Date_Time::to_time(date('d/m/Y'))){
                                    $date = getdate($i);
                                    $date['weekday'] = strtoupper(substr($date['weekday'],0,3));
                                    $availability_real = $room_levels[$room_level_id]['day_items'][$i]['number_room_quantity'] + (isset($availability_old[$i]['availability'])?$availability_old[$i]['availability']:0);
                                    $rate_avail = isset($type_rate_avail[$value['id']]['timeline'][$i])?$type_rate_avail[$value['id']]['timeline'][$i]['avail']:0;
                                    
                                    if( isset($valueRange[$date['weekday']]) AND (($availability_real-$overbook_quantity+$rate_avail)>=$availability) ){ //$rate_avail
                                        if($type_time_id = DB::fetch('select id from siteminder_room_type_time where siteminder_room_type_id='.$value['id'].' and time='.$i.'','id')){
                                            DB::update('siteminder_room_type_time',array('availability'=>($availability<=0?0:$availability),'status'=>0),'id='.$type_time_id);
                                        }else{
                                            DB::insert('siteminder_room_type_time',array('siteminder_room_type_id'=>$value['id'],'in_date'=>Date_Time::to_orc_date(date('d/m/Y',$i)),'time'=>$i,'availability'=>($availability<=0?0:$availability)));
                                        }
                                        $data_xml[$value['inventory_type_code']]['id'] = $value['inventory_type_code'];
                                        $data_xml[$value['inventory_type_code']]['timeline'][$i]['time'] = $i;
                                        $data_xml[$value['inventory_type_code']]['timeline'][$i]['availability'] = ($availability<=0?0:$availability);
                                    }
                                }
                            } // time
                        } // end $_REQUEST['bulkrange']
                    } // end isset($_REQUEST['bulkroomtype'][$value['id']])
                } // end $siteminder_room_type
                
                
                if(isset($_REQUEST['bulkroomrate'])){
                    $siteminder_room_type_time = DB::fetch_all('select 
                                                                    siteminder_room_type_time.*
                                                                from  
                                                                    siteminder_room_type_time
                                                                    inner join siteminder_room_type on siteminder_room_type.id=siteminder_room_type_time.siteminder_room_type_id
                                                                    inner join room_level on room_level.id=siteminder_room_type.room_level_id
                                                                where 
                                                                    siteminder_room_type.portal_id=\''.PORTAL_ID.'\'
                                                                    and siteminder_room_type_time.time>='.Date_Time::to_time(date('d/m/Y')).'
                                                                ');
                    foreach($siteminder_room_type_time as $keyRType=>$valueRType){
                        if(!isset($type_rate_avail[$valueRType['siteminder_room_type_id']]['timeline'][$valueRType['time']])){
                            $type_rate_avail[$valueRType['siteminder_room_type_id']]['timeline'][$valueRType['time']]['avail'] = $valueRType['availability'];
                        }else{
                            $type_rate_avail[$valueRType['siteminder_room_type_id']]['timeline'][$valueRType['time']]['avail'] += $valueRType['availability'];
                        }
                    }
                    // update room rate
                    foreach($siteminder_room_rate as $key=>$value){
                        if(isset($_REQUEST['bulkroomrate'][$value['id']])){
                            $overbook_quantity = $value['overbook_quantity'];
                            foreach($_REQUEST['bulkrange'] as $keyRange=>$valueRange){
                                /** check phong trong trong khoang thoi gian nay **/
                                $room_levels = check_availability('','rl.id='.$value['room_level_id'],Date_Time::to_time($valueRange['from_date']),(Date_Time::to_time($valueRange['to_date'])+86400));
                                $room_level_id = $value['room_level_id'];
                                for($i=Date_Time::to_time($valueRange['from_date']);$i<=Date_Time::to_time($valueRange['to_date']);$i+=86400){
                                    if($i>=Date_Time::to_time(date('d/m/Y'))){
                                        $date = getdate($i);
                                        $date['weekday'] = strtoupper(substr($date['weekday'],0,3));
                                        $availability_real = $room_levels[$room_level_id]['day_items'][$i]['number_room_quantity'];
                                        $rate_avail = isset($type_rate_avail[$value['siteminder_room_type_id']]['timeline'][$i])?$type_rate_avail[$value['siteminder_room_type_id']]['timeline'][$i]['avail']:0;
                                        if( isset($valueRange[$date['weekday']]) AND (($availability_real-$overbook_quantity+$rate_avail)>=($availability)) ){
                                            if($rate_time_id = DB::fetch('select id from siteminder_rate_avail_time where siteminder_room_rate_id='.$value['id'].' and time='.$i.'','id')){
                                                DB::update('siteminder_rate_avail_time',array('availability'=>($availability<=0?0:$availability),'status'=>0),'id='.$rate_time_id);
                                            }else{
                                                DB::insert('siteminder_rate_avail_time',array('siteminder_room_rate_id'=>$value['id'],'in_date'=>Date_Time::to_orc_date(date('d/m/Y',$i)),'time'=>$i,'availability'=>($availability<=0?0:$availability)));
                                            }
                                            $data_rate_xml[$value['id']]['id'] = $value['id'];
                                            $data_rate_xml[$value['id']]['rate_plan_code'] = $value['rate_plan_code'];
                                            $data_rate_xml[$value['id']]['inventory_type_code'] = $value['inventory_type_code'];
                                            $data_rate_xml[$value['id']]['timeline'][$i]['time'] = $i;
                                            $data_rate_xml[$value['id']]['timeline'][$i]['availability'] = ($availability<=0?0:$availability);
                                        }
                                    }
                                } // time
                            } // end $_REQUEST['bulkrange']
                        } // end isset($_REQUEST['bulkroomrate'][$value['id']])
                    } // end $siteminder_room_rate
                }
                
                
                /** get xml flie **/
                $data_map = array();
                foreach($data_xml as $keyXml=>$valueXml){
                    ksort($valueXml['timeline']);
                    $data_map[$valueXml['id']]['id'] = $valueXml['id'];
                    $key = 0;
                    $timeline = 0;
                    $avail = 'XX';
                    foreach($valueXml['timeline'] as $keyTime=>$valueTime){
                        if($timeline==Date_Time::to_time(date('d/m/Y',$valueTime['time']-86400)) and $avail==$valueTime['availability']){
                            $data_map[$valueXml['id']]['timeline'][$key]['EndTime'] = $valueTime['time'];
                        }else{
                            $key++;
                            $data_map[$valueXml['id']]['timeline'][$key]['StartTime'] = $valueTime['time'];
                            $data_map[$valueXml['id']]['timeline'][$key]['EndTime'] = $valueTime['time'];
                            $data_map[$valueXml['id']]['timeline'][$key]['Avail'] = $valueTime['availability'];
                        }
                        $avail=$valueTime['availability'];
                        $timeline = $valueTime['time'];
                    }
                }
                if(sizeof($data_map)>0){
                    $SOAPBody = "<OTA_HotelAvailNotifRQ xmlns=\"http://www.opentravel.org/OTA/2003/05\" EchoToken=\"".$this->GetEchoToken()."\" TimeStamp=\"".$this->GetTimeStamp()."\" Version=\"1\">";
                        $SOAPBody .= "<POS>";
                            $SOAPBody .= "<Source>";
                              $SOAPBody .= "<RequestorID Type=\"22\" ID=\"".SITEMINDER_REQUESTOR_ID."\"/>";
                            $SOAPBody .= "</Source>";
                        $SOAPBody .= "</POS>";
                        $SOAPBody .= "<AvailStatusMessages HotelCode=\"".SITEMINDER_HOTELCODE."\">";
                    foreach($data_map as $keyMap=>$valueMap){
                        $inventory_type_code = $valueMap['id'];
                        if(isset($valueMap['timeline'])){
                            foreach($valueMap['timeline'] as $kTime=>$vTime){
                                $SOAPBody .= "<AvailStatusMessage BookingLimit=\"".$vTime['Avail']."\">";
                                  $SOAPBody .= "<StatusApplicationControl Start=\"".date('Y-m-d',$vTime['StartTime'])."\" End=\"".date('Y-m-d',$vTime['EndTime'])."\" InvTypeCode=\"".$inventory_type_code."\"/>";
                                $SOAPBody .= "</AvailStatusMessage>";
                            }
                        }
                    }
                    $SOAPBody .= "</AvailStatusMessages>";
                        $SOAPBody .= "</OTA_HotelAvailNotifRQ>";
                    
                    $id_soap = DB::insert('siteminder_soap_body',array('type'=>'AVAIL','time'=>time(),'portal_id'=>PORTAL_ID));
                    $path = ROOT_PATH.'packages/hotel/packages/siteminder/includes/dataXml/SOAP_'.$id_soap.'.xml';
                    $this->save_file($path,$SOAPBody);
                }
                /////////////////////////////////////////////////////////////////////////////
                $data_map = array();
                foreach($data_rate_xml as $keyXml=>$valueXml){
                    ksort($valueXml['timeline']);
                    $data_map[$valueXml['id']]['id'] = $valueXml['id'];
                    $data_map[$valueXml['id']]['rate_plan_code'] = $valueXml['rate_plan_code'];
                    $data_map[$valueXml['id']]['inventory_type_code'] = $valueXml['inventory_type_code'];
                    $key = 0;
                    $timeline = 0;
                    $avail = 'XX';
                    foreach($valueXml['timeline'] as $keyTime=>$valueTime){
                        if($timeline==Date_Time::to_time(date('d/m/Y',$valueTime['time']-86400)) and $avail==$valueTime['availability']){
                            $data_map[$valueXml['id']]['timeline'][$key]['EndTime'] = $valueTime['time'];
                        }else{
                            $key++;
                            $data_map[$valueXml['id']]['timeline'][$key]['StartTime'] = $valueTime['time'];
                            $data_map[$valueXml['id']]['timeline'][$key]['EndTime'] = $valueTime['time'];
                            $data_map[$valueXml['id']]['timeline'][$key]['Avail'] = $valueTime['availability'];
                        }
                        $avail=$valueTime['availability'];
                        $timeline = $valueTime['time'];
                    }
                }
                if(sizeof($data_map)>0){
                    $SOAPBody = "<OTA_HotelAvailNotifRQ xmlns=\"http://www.opentravel.org/OTA/2003/05\" EchoToken=\"".$this->GetEchoToken()."\" TimeStamp=\"".$this->GetTimeStamp()."\" Version=\"1\">";
                        $SOAPBody .= "<POS>";
                            $SOAPBody .= "<Source>";
                              $SOAPBody .= "<RequestorID Type=\"22\" ID=\"".SITEMINDER_REQUESTOR_ID."\"/>";
                            $SOAPBody .= "</Source>";
                        $SOAPBody .= "</POS>";
                        $SOAPBody .= "<AvailStatusMessages HotelCode=\"".SITEMINDER_HOTELCODE."\">";
                    foreach($data_map as $keyMap=>$valueMap){
                        $inventory_type_code = $valueMap['inventory_type_code'];
                        $rate_plan_code = $valueMap['rate_plan_code'];
                        if(isset($valueMap['timeline'])){
                            foreach($valueMap['timeline'] as $kTime=>$vTime){
                                $SOAPBody .= "<AvailStatusMessage BookingLimit=\"".$vTime['Avail']."\">";
                                  $SOAPBody .= "<StatusApplicationControl Start=\"".date('Y-m-d',$vTime['StartTime'])."\" End=\"".date('Y-m-d',$vTime['EndTime'])."\" InvTypeCode=\"".$inventory_type_code."\" RatePlanCode=\"".$rate_plan_code."\"/>";
                                $SOAPBody .= "</AvailStatusMessage>";
                            }
                        }
                    }
                    $SOAPBody .= "</AvailStatusMessages>";
                        $SOAPBody .= "</OTA_HotelAvailNotifRQ>";
                    
                    $id_soap = DB::insert('siteminder_soap_body',array('type'=>'AVAIL','time'=>time(),'portal_id'=>PORTAL_ID));
                    $path = ROOT_PATH.'packages/hotel/packages/siteminder/includes/dataXml/SOAP_'.$id_soap.'.xml';
                    $this->save_file($path,$SOAPBody);
                }
                
                /** end get xml **/
            }elseif((Url::get('bulk_type')=='RATES' or Url::get('bulk_type')=='CTA' or Url::get('bulk_type')=='CTD' or Url::get('bulk_type')=='MINSTAY' or Url::get('bulk_type')=='MAXSTAY' or Url::get('bulk_type')=='STOPSELL') and Url::get('bulkrange') and (Url::get('bulkroomrate') or Url::get('bulkOTA') or Url::get('bulkroomrateOTA'))){
                /**
                 * @UpdateRate-CTA-CTD-MinStay-MaxStay-StopSell
                 * @data : 
                 *  ----- Url::get('bulkrange') : time
                 *  ----- Url::get('bulkroomrate') or Url::get('bulkOTA') : room rate array || ota array
                **/
                // get all roomRate
                $siteminder_room_rate = DB::fetch_all('select 
                                                            siteminder_room_rate.*,
                                                            siteminder_room_type.inventory_type_code
                                                        from 
                                                            siteminder_room_rate
                                                            inner join siteminder_room_type on siteminder_room_type.id=siteminder_room_rate.siteminder_room_type_id
                                                        where 
                                                            siteminder_room_type.portal_id=\''.PORTAL_ID.'\'
                                                        ORDER BY
                                                            structure_id DESC
                                                        ');
                // get room rate over ==> xet Rates
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
                // get OTA 
                $channel = DB::fetch_all('
                                            SELECT
                                                siteminder_room_rate_ota.*,
                                                siteminder_booking_channel.code as ota_code,
                                                siteminder_booking_channel.name as ota_name,
                                                customer.code as customer_code,
                                                customer.name as customer_name,
                                                siteminder_room_rate.rate_name,
                                                siteminder_room_rate.rate_plan_code,
                                                siteminder_room_type.type_name,
                                                siteminder_room_type.inventory_type_code
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
                // get OTA ==> get Rate
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
                // apply OTA to RoomRate
                foreach($channel as $key=>$value){
                    if(isset($siteminder_room_rate[$value['siteminder_room_rate_id']])){
                        $siteminder_room_rate[$value['siteminder_room_rate_id']]['child'][$key] = $value;
                    }
                }
                
                if(Url::get('bulk_type')=='RATES'){
                    //System::debug($_REQUEST); die;
                    /**
                     * @Update-Rates : update table : siteminder_room_rate_time && siteminder_ota_rates_time
                    **/
                    // get rate
                    $rates = Url::get('bulk_text');
                    $data_room_rate_xml = array();
                    $data_OTA_xml = array();
                    $siteminder_room_rate_partent = array();
                    $room_rate_level = array();
                    // xet rate to partent + get data $room_rate_level
                    foreach($siteminder_room_rate as $key=>$value){
                        if($value['rate_config_derive']==0 and Url::get('bulkroomrate') and isset($_REQUEST['bulkroomrate'][$value['id']])){
                            $siteminder_room_rate_partent[$key] = $value;
                            foreach($_REQUEST['bulkrange'] as $keyRange=>$valueRange){
                                for($i=Date_Time::to_time($valueRange['from_date']);$i<=Date_Time::to_time($valueRange['to_date']);$i+=86400){
                                    $date = getdate($i);
                                    $date['weekday'] = strtoupper(substr($date['weekday'],0,3));
                                    if( isset($valueRange[$date['weekday']]) ){
                                        if($rate_time_id = DB::fetch('select id from siteminder_room_rate_time where siteminder_room_rate_id='.$value['id'].' and time='.$i.'','id')){
                                            DB::update('siteminder_room_rate_time',array('rates'=>$rates,'status'=>0),'id='.$rate_time_id);
                                        }else{
                                            DB::insert('siteminder_room_rate_time',array('siteminder_room_rate_id'=>$value['id'],'in_date'=>Date_Time::to_orc_date(date('d/m/Y',$i)),'time'=>$i,'rates'=>$rates));
                                        }
                                        $data_room_rate_xml[$value['id']]['id'] = $value['id'];
                                        $data_room_rate_xml[$value['id']]['rate_plan_code'] = $value['rate_plan_code'];
                                        $data_room_rate_xml[$value['id']]['inventory_type_code'] = $value['inventory_type_code'];
                                        $data_room_rate_xml[$value['id']]['timeline'][$i]['time'] = $i;
                                        $data_room_rate_xml[$value['id']]['timeline'][$i]['rates'] = $rates;
                                        $siteminder_room_rate_partent[$key]['timeline'][$i]['rates'] = $rates;
                                    }
                                } // end time
                            } // end $_REQUEST['bulkrange']
                            
                            // xet OTA
                            if(isset($value['child'])){
                                foreach($value['child'] as $keyOTA=>$valueOTA){
                                    if( $valueOTA['manual_derive']=='DERIVE' ){ //Url::get('bulkOTA') and isset($_REQUEST['bulkOTA'][$valueOTA['ota_code']])
                                        
                                        foreach($_REQUEST['bulkrange'] as $keyRange=>$valueRange)
                                        {
                                            for($i=Date_Time::to_time($valueRange['from_date']);$i<=Date_Time::to_time($valueRange['to_date']);$i+=86400)
                                            {
                                                $ratesOTA = $rates;
                                                $date = getdate($i);
                                                $date['weekday'] = strtoupper(substr($date['weekday'],0,3));
                                                if( isset($valueRange[$date['weekday']]) ){
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
                                                            $ratesOTA = $ratesOTA + (($percent_adjust*$ratesOTA)/100);
                                                        else
                                                            $ratesOTA = $ratesOTA - (($percent_adjust*$ratesOTA)/100);
                                                    }elseif($daily_rate==3){
                                                        if($amount_inc==1)
                                                            $ratesOTA = $ratesOTA + $amount_adjust;
                                                        else
                                                            $ratesOTA = $ratesOTA - $amount_adjust;
                                                    }elseif($daily_rate==4){
                                                        if($amount_inc==1)
                                                            $ratesOTA = $ratesOTA + $amount_adjust;
                                                        else
                                                            $ratesOTA = $ratesOTA - $amount_adjust;
                                                        if($percent_inc==1)
                                                            $ratesOTA = $ratesOTA + (($percent_adjust*$ratesOTA)/100);
                                                        else
                                                            $ratesOTA = $ratesOTA - (($percent_adjust*$ratesOTA)/100);
                                                    }elseif($daily_rate==5){
                                                        if($percent_inc==1)
                                                            $ratesOTA = $ratesOTA + (($percent_adjust*$ratesOTA)/100);
                                                        else
                                                            $ratesOTA = $ratesOTA - (($percent_adjust*$ratesOTA)/100);
                                                        if($amount_inc==1)
                                                            $ratesOTA = $ratesOTA + $amount_adjust;
                                                        else
                                                            $ratesOTA = $ratesOTA - $amount_adjust;
                                                    }
                                                    if($ota_time_id = DB::fetch('select id from siteminder_ota_rates_time where siteminder_room_rate_ota_id='.$valueOTA['id'].' and time='.$i.'','id')){
                                                        DB::update('siteminder_ota_rates_time',array('rates'=>$ratesOTA,'status'=>0),'id='.$ota_time_id);
                                                    }else{
                                                        DB::insert('siteminder_ota_rates_time',array('siteminder_room_rate_ota_id'=>$valueOTA['id'],'in_date'=>Date_Time::to_orc_date(date('d/m/Y',$i)),'time'=>$i,'rates'=>$ratesOTA));
                                                    }
                                                    /*
                                                    $data_OTA_xml[$valueOTA['id']]['id'] = $valueOTA['id'];
                                                    $data_OTA_xml[$valueOTA['id']]['ota_code'] = $valueOTA['ota_code'];
                                                    $data_OTA_xml[$valueOTA['id']]['rate_plan_code'] = $value['rate_plan_code'];
                                                    $data_OTA_xml[$valueOTA['id']]['inventory_type_code'] = $value['inventory_type_code'];
                                                    $data_OTA_xml[$valueOTA['id']]['timeline'][$i]['time'] = $i;
                                                    $data_OTA_xml[$valueOTA['id']]['timeline'][$i]['rates'] = $ratesOTA;
                                                    */
                                                } // end weekday
                                            } // end for time
                                        } // end foreach range
                                    } // end check $_REQUEST
                                } // end foreach OTA
                            }// end check OTA
                        }elseif($value['rate_config_derive']==1){ // end IF check partent
                            $level = IDStructure::level($value['structure_id']);
                            $room_rate_level[$level]['id'] = $level;
                            $room_rate_level[$level]['child'][$key] = $siteminder_room_rate[$key];
                        }
                    } // end $siteminder_room_rate
                    ksort($room_rate_level);
                    // xet rate room rate child with $room_rate_level
                    foreach($room_rate_level as $key=>$value){
                        foreach($value['child'] as $keyChild=>$valueChild){ // room rate
                            $parent_id = $valueChild['derive_from_rate_id'];
                            if($valueChild['rate_config_derive']==1 and isset($siteminder_room_rate_partent[$parent_id])){
                                $siteminder_room_rate_partent[$keyChild] = $valueChild;
                                foreach($_REQUEST['bulkrange'] as $keyRange=>$valueRange){
                                    for($i=Date_Time::to_time($valueRange['from_date']);$i<=Date_Time::to_time($valueRange['to_date']);$i+=86400){
                                        $date = getdate($i);
                                        $date['weekday'] = strtoupper(substr($date['weekday'],0,3));
                                        if( isset($valueRange[$date['weekday']]) ){
                                            $rate_parent = $siteminder_room_rate_partent[$parent_id]['timeline'][$i]['rates'];
                                            //$rates = $rate_parent;
                                            
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
                                                    $rate_parent = $rate_parent + (($percent_adjust*$rate_parent)/100);
                                                else
                                                    $rate_parent = $rate_parent - (($percent_adjust*$rate_parent)/100);
                                            }elseif($daily_rate==3){
                                                if($amount_inc==1)
                                                    $rate_parent = $rate_parent + $amount_adjust;
                                                else
                                                    $rate_parent = $rate_parent - $amount_adjust;
                                            }elseif($daily_rate==4){
                                                if($amount_inc==1)
                                                    $rate_parent = $rate_parent + $amount_adjust;
                                                else
                                                    $rate_parent = $rate_parent - $amount_adjust;
                                                if($percent_inc==1)
                                                    $rate_parent = $rate_parent + (($percent_adjust*$rate_parent)/100);
                                                else
                                                    $rate_parent = $rate_parent - (($percent_adjust*$rate_parent)/100);
                                            }elseif($daily_rate==5){
                                                if($percent_inc==1)
                                                    $rate_parent = $rate_parent + (($percent_adjust*$rate_parent)/100);
                                                else
                                                    $rate_parent = $rate_parent - (($percent_adjust*$rate_parent)/100);
                                                if($amount_inc==1)
                                                    $rate_parent = $rate_parent + $amount_adjust;
                                                else
                                                    $rate_parent = $rate_parent - $amount_adjust;
                                            }
                                            if($rate_time_id = DB::fetch('select id from siteminder_room_rate_time where siteminder_room_rate_id='.$valueChild['id'].' and time='.$i.'','id')){
                                                DB::update('siteminder_room_rate_time',array('rates'=>$rate_parent,'status'=>0),'id='.$rate_time_id);
                                            }else{
                                                DB::insert('siteminder_room_rate_time',array('siteminder_room_rate_id'=>$valueChild['id'],'in_date'=>Date_Time::to_orc_date(date('d/m/Y',$i)),'time'=>$i,'rates'=>$rate_parent));
                                            }
                                            /*
                                            $data_room_rate_xml[$valueChild['id']]['id'] = $valueChild['id'];
                                            $data_room_rate_xml[$valueChild['id']]['rate_plan_code'] = $valueChild['rate_plan_code'];
                                            $data_room_rate_xml[$valueChild['id']]['inventory_type_code'] = $valueChild['inventory_type_code'];
                                            $data_room_rate_xml[$valueChild['id']]['timeline'][$i]['time'] = $i;
                                            $data_room_rate_xml[$valueChild['id']]['timeline'][$i]['rates'] = $rate_parent;
                                            */
                                            $siteminder_room_rate_partent[$keyChild]['timeline'][$i]['rates'] = $rate_parent;
                                        } // end weekday
                                    } // end for time
                                } // end time range
                                // xet OTA
                                if(isset($valueChild['child'])){
                                    foreach($valueChild['child'] as $keyOTA=>$valueOTA){
                                        if( $valueOTA['manual_derive']=='DERIVE' ){ //Url::get('bulkOTA') and isset($_REQUEST['bulkOTA'][$valueOTA['ota_code']]) 
                                            foreach($_REQUEST['bulkrange'] as $keyRange=>$valueRange){
                                                for($i=Date_Time::to_time($valueRange['from_date']);$i<=Date_Time::to_time($valueRange['to_date']);$i+=86400){
                                                    $date = getdate($i);
                                                    $date['weekday'] = strtoupper(substr($date['weekday'],0,3));
                                                    if( isset($valueRange[$date['weekday']]) ){
                                                        $rate_parent = $siteminder_room_rate_partent[$keyChild]['timeline'][$i]['rates'];
                                                        //$rates = $rate_parent;
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
                                                                    $rate_parent = $rate_parent + (($percent_adjust*$rate_parent)/100);
                                                                else
                                                                    $rate_parent = $rate_parent - (($percent_adjust*$rate_parent)/100);
                                                            }elseif($daily_rate==3){
                                                                if($amount_inc==1)
                                                                    $rate_parent = $rate_parent + $amount_adjust;
                                                                else
                                                                    $rate_parent = $rate_parent - $amount_adjust;
                                                            }elseif($daily_rate==4){
                                                                if($amount_inc==1)
                                                                    $rate_parent = $rate_parent + $amount_adjust;
                                                                else
                                                                    $rate_parent = $rate_parent - $amount_adjust;
                                                                if($percent_inc==1)
                                                                    $rate_parent = $rate_parent + (($percent_adjust*$rate_parent)/100);
                                                                else
                                                                    $rate_parent = $rate_parent - (($percent_adjust*$rate_parent)/100);
                                                            }elseif($daily_rate==5){
                                                                if($percent_inc==1)
                                                                    $rate_parent = $rate_parent + (($percent_adjust*$rate_parent)/100);
                                                                else
                                                                    $rate_parent = $rate_parent - (($percent_adjust*$rate_parent)/100);
                                                                if($amount_inc==1)
                                                                    $rate_parent = $rate_parent + $amount_adjust;
                                                                else
                                                                    $rate_parent = $rate_parent - $amount_adjust;
                                                            }
                                                        }
                                                        if($ota_time_id = DB::fetch('select id from siteminder_ota_rates_time where siteminder_room_rate_ota_id='.$valueOTA['id'].' and time='.$i.'','id')){
                                                            DB::update('siteminder_ota_rates_time',array('rates'=>$rate_parent,'status'=>0),'id='.$ota_time_id);
                                                        }else{
                                                            DB::insert('siteminder_ota_rates_time',array('siteminder_room_rate_ota_id'=>$valueOTA['id'],'in_date'=>Date_Time::to_orc_date(date('d/m/Y',$i)),'time'=>$i,'rates'=>$rate_parent));
                                                        }
                                                        /*
                                                        $data_OTA_xml[$valueOTA['id']]['id'] = $valueOTA['id'];
                                                        $data_OTA_xml[$valueOTA['id']]['ota_code'] = $valueOTA['ota_code'];
                                                        $data_OTA_xml[$valueOTA['id']]['rate_plan_code'] = $valueChild['rate_plan_code'];
                                                        $data_OTA_xml[$valueOTA['id']]['inventory_type_code'] = $valueChild['inventory_type_code'];
                                                        $data_OTA_xml[$valueOTA['id']]['timeline'][$i]['time'] = $i;
                                                        $data_OTA_xml[$valueOTA['id']]['timeline'][$i]['rates'] = $rate_parent;
                                                        */
                                                    } // end weekday
                                                } // end for time
                                            } // end foreach range
                                        } // end check $_REQUEST
                                    } // end foreach OTA
                                }// end check OTA
                            } // end if check partent
                        } // end $value['child'] = room rate
                    } // end $room_rate_level
                    
                    // OTA MANUAL
                    if(isset($_REQUEST['bulkroomrateOTA'])){
                        foreach($channel as $keyOTARate=>$valueOTARate){
                            if(isset($_REQUEST['bulkroomrateOTA'][$valueOTARate['id']])){
                                foreach($_REQUEST['bulkrange'] as $keyRange=>$valueRange){
                                    for($i=Date_Time::to_time($valueRange['from_date']);$i<=Date_Time::to_time($valueRange['to_date']);$i+=86400){
                                        $date = getdate($i);
                                        $date['weekday'] = strtoupper(substr($date['weekday'],0,3));
                                        if( isset($valueRange[$date['weekday']]) ){
                                            $rateOTA = $rates;
                                            if($ota_time_id = DB::fetch('select id from siteminder_ota_rates_time where siteminder_room_rate_ota_id='.$valueOTARate['id'].' and time='.$i.'','id')){
                                                DB::update('siteminder_ota_rates_time',array('rates'=>$rateOTA,'status'=>0),'id='.$ota_time_id);
                                            }else{
                                                DB::insert('siteminder_ota_rates_time',array('siteminder_room_rate_ota_id'=>$valueOTARate['id'],'in_date'=>Date_Time::to_orc_date(date('d/m/Y',$i)),'time'=>$i,'rates'=>$rateOTA));
                                            }
                                            
                                            $data_OTA_xml[$valueOTARate['id']]['id'] = $valueOTARate['id'];
                                            $data_OTA_xml[$valueOTARate['id']]['ota_code'] = $valueOTARate['ota_code'];
                                            $data_OTA_xml[$valueOTARate['id']]['rate_plan_code'] = $valueOTARate['rate_plan_code'];
                                            $data_OTA_xml[$valueOTARate['id']]['inventory_type_code'] = $valueOTARate['inventory_type_code'];
                                            $data_OTA_xml[$valueOTARate['id']]['timeline'][$i]['time'] = $i;
                                            $data_OTA_xml[$valueOTARate['id']]['timeline'][$i]['rates'] = $rateOTA;
                                            
                                        } // end weekday
                                    } // end for time
                                } // end foreach range
                            }
                        }
                    }
                    // END OTA MANUAL
                    
                    /** get xml flie Room Rate **/
                    $data_map = array();
                    foreach($data_room_rate_xml as $keyXml=>$valueXml){
                        ksort($valueXml['timeline']);
                        $data_map[$valueXml['id']]['id'] = $valueXml['id'];
                        $data_map[$valueXml['id']]['rate_plan_code'] = $valueXml['rate_plan_code'];
                        $data_map[$valueXml['id']]['inventory_type_code'] = $valueXml['inventory_type_code'];
                        $key = 0;
                        $timeline = 0;
                        $rate = 'XX';
                        foreach($valueXml['timeline'] as $keyTime=>$valueTime){
                            if($timeline==Date_Time::to_time(date('d/m/Y',$valueTime['time']-86400)) and $rate==$valueTime['rates']){
                                $data_map[$valueXml['id']]['timeline'][$key]['EndTime'] = $valueTime['time'];
                            }else{
                                $key++;
                                $data_map[$valueXml['id']]['timeline'][$key]['StartTime'] = $valueTime['time'];
                                $data_map[$valueXml['id']]['timeline'][$key]['EndTime'] = $valueTime['time'];
                                $data_map[$valueXml['id']]['timeline'][$key]['rates'] = $valueTime['rates'];
                            }
                            $rate=$valueTime['rates'];
                            $timeline = $valueTime['time'];
                        }
                    }
                    if(sizeof($data_map)>0){
                        $SOAPBody = "<OTA_HotelRateAmountNotifRQ xmlns=\"http://www.opentravel.org/OTA/2003/05\" EchoToken=\"".$this->GetEchoToken()."\" TimeStamp=\"".$this->GetTimeStamp()."\" Version=\"1\">";
                            $SOAPBody .= "<POS>";
                                $SOAPBody .= "<Source>";
                                  $SOAPBody .= "<RequestorID Type=\"22\" ID=\"".SITEMINDER_REQUESTOR_ID."\"/>";
                                $SOAPBody .= "</Source>";
                            $SOAPBody .= "</POS>";
                            $SOAPBody .= "<RateAmountMessages HotelCode=\"".SITEMINDER_HOTELCODE."\">";
                                foreach($data_map as $keyMap=>$valueMap){
                                    $inventory_type_code = $valueMap['inventory_type_code'];
                                    $rate_plan_code = $valueMap['rate_plan_code'];
                                    if(isset($valueMap['timeline'])){
                                        foreach($valueMap['timeline'] as $kTime=>$vTime){
                                            $SOAPBody .= "<RateAmountMessage>";
                                              $SOAPBody .= "<StatusApplicationControl InvTypeCode=\"".$inventory_type_code."\" RatePlanCode=\"".$rate_plan_code."\" />";
                                              $SOAPBody .= "<Rates>";
                                                $SOAPBody .= "<Rate CurrencyCode=\"".SITEMINDER_HOTELCURRENCY."\" Start=\"".date('Y-m-d',$vTime['StartTime'])."\" End=\"".date('Y-m-d',$vTime['EndTime'])."\" Mon=\"1\" Tue=\"1\" Weds=\"1\" Thur=\"1\" Fri=\"1\" Sat=\"1\" Sun=\"1\">";
                                                    $SOAPBody .= "<BaseByGuestAmts>";
                                                    $SOAPBody .= "<BaseByGuestAmt AmountAfterTax=\"".$vTime['rates']."\"/>";
                                                    $SOAPBody .= "</BaseByGuestAmts>";
                                                $SOAPBody .= "</Rate>";
                                              $SOAPBody .= "</Rates>";
                                            $SOAPBody .= "</RateAmountMessage>";
                                        }
                                    }
                                }
                            $SOAPBody .= "</RateAmountMessages>";
                        $SOAPBody .= "</OTA_HotelRateAmountNotifRQ>";
                        
                        $id_soap = DB::insert('siteminder_soap_body',array('type'=>'RATES','time'=>time(),'portal_id'=>PORTAL_ID));
                        $path = ROOT_PATH.'packages/hotel/packages/siteminder/includes/dataXml/SOAP_'.$id_soap.'.xml';
                        $this->save_file($path,$SOAPBody);
                    }
                    /** end get xml **/
                    
                    /** get xml flie OTA **/
                    $data_map = array();
                    foreach($data_OTA_xml as $keyXml=>$valueXml){
                        ksort($valueXml['timeline']);
                        $data_map[$valueXml['id']]['id'] = $valueXml['id'];
                        $data_map[$valueXml['id']]['rate_plan_code'] = $valueXml['rate_plan_code'];
                        $data_map[$valueXml['id']]['inventory_type_code'] = $valueXml['inventory_type_code'];
                        $data_map[$valueXml['id']]['ota_code'] = $valueXml['ota_code'];
                        $key = 0;
                        $timeline = 0;
                        $rate = 'XX';
                        foreach($valueXml['timeline'] as $keyTime=>$valueTime){
                            if($timeline==Date_Time::to_time(date('d/m/Y',$valueTime['time']-86400)) and $rate==$valueTime['rates']){
                                $data_map[$valueXml['id']]['timeline'][$key]['EndTime'] = $valueTime['time'];
                            }else{
                                $key++;
                                $data_map[$valueXml['id']]['timeline'][$key]['StartTime'] = $valueTime['time'];
                                $data_map[$valueXml['id']]['timeline'][$key]['EndTime'] = $valueTime['time'];
                                $data_map[$valueXml['id']]['timeline'][$key]['rates'] = $valueTime['rates'];
                            }
                            $rate=$valueTime['rates'];
                            $timeline = $valueTime['time'];
                        }
                    }
                    if(sizeof($data_map)>0){
                        $SOAPBody = "<OTA_HotelRateAmountNotifRQ xmlns=\"http://www.opentravel.org/OTA/2003/05\" EchoToken=\"".$this->GetEchoToken()."\" TimeStamp=\"".$this->GetTimeStamp()."\" Version=\"1\">";
                            $SOAPBody .= "<POS>";
                                $SOAPBody .= "<Source>";
                                  $SOAPBody .= "<RequestorID Type=\"22\" ID=\"".SITEMINDER_REQUESTOR_ID."\"/>";
                                $SOAPBody .= "</Source>";
                            $SOAPBody .= "</POS>";
                            $SOAPBody .= "<RateAmountMessages HotelCode=\"".SITEMINDER_HOTELCODE."\">";
                                foreach($data_map as $keyMap=>$valueMap){
                                    $inventory_type_code = $valueMap['inventory_type_code'];
                                    $rate_plan_code = $valueMap['rate_plan_code'];
                                    if(isset($valueMap['timeline'])){
                                        foreach($valueMap['timeline'] as $kTime=>$vTime){
                                            $SOAPBody .= "<RateAmountMessage>";
                                              $SOAPBody .= "<StatusApplicationControl InvTypeCode=\"".$inventory_type_code."\" RatePlanCode=\"".$rate_plan_code."\">";
                                                  $SOAPBody .= "<DestinationSystemCodes>";
                                                      $SOAPBody .= "<DestinationSystemCode>".$valueMap['ota_code']."</DestinationSystemCode>";
                                                  $SOAPBody .= "</DestinationSystemCodes>";
                                              $SOAPBody .= "</StatusApplicationControl>";
                                              $SOAPBody .= "<Rates>";
                                                $SOAPBody .= "<Rate CurrencyCode=\"".SITEMINDER_HOTELCURRENCY."\" Start=\"".date('Y-m-d',$vTime['StartTime'])."\" End=\"".date('Y-m-d',$vTime['EndTime'])."\" Mon=\"1\" Tue=\"1\" Weds=\"1\" Thur=\"1\" Fri=\"1\" Sat=\"1\" Sun=\"1\">";
                                                    $SOAPBody .= "<BaseByGuestAmts>";
                                                    $SOAPBody .= "<BaseByGuestAmt AmountAfterTax=\"".$vTime['rates']."\"/>";
                                                    $SOAPBody .= "</BaseByGuestAmts>";
                                                $SOAPBody .= "</Rate>";
                                              $SOAPBody .= "</Rates>";
                                            $SOAPBody .= "</RateAmountMessage>";
                                        }
                                    }
                                }
                            $SOAPBody .= "</RateAmountMessages>";
                        $SOAPBody .= "</OTA_HotelRateAmountNotifRQ>";
                        $id_soap = DB::insert('siteminder_soap_body',array('type'=>'RATES','time'=>time(),'portal_id'=>PORTAL_ID));
                        $path = ROOT_PATH.'packages/hotel/packages/siteminder/includes/dataXml/SOAP_'.$id_soap.'.xml';
                        $this->save_file($path,$SOAPBody);
                    }
                    /** end get xml **/
                    
                }elseif(Url::get('bulk_type')=='STOPSELL' OR Url::get('bulk_type')=='CTA' OR Url::get('bulk_type')=='CTD'){ // END RATES
                    // get STOPSELL
                    $data = isset($_REQUEST['bulk_checkbox'])?1:0;
                    $data_room_rate_xml = array();
                    $data_OTA_xml = array();
                    foreach($siteminder_room_rate as $key=>$value){
                        if(isset($_REQUEST['bulkroomrate'][$value['id']])){
                            
                            $check_all = true;
                            foreach($value['child'] as $keyOTACheck=>$valueOTACheck){
                                if(!isset($_REQUEST['bulkOTA'][$valueOTACheck['ota_code']])){
                                    $check_all = false;
                                }
                            }
                            // update All OTA
                            if($check_all){
                                foreach($_REQUEST['bulkrange'] as $keyRange=>$valueRange){
                                    for($i=Date_Time::to_time($valueRange['from_date']);$i<=Date_Time::to_time($valueRange['to_date']);$i+=86400){
                                        $date = getdate($i);
                                        $date['weekday'] = strtoupper(substr($date['weekday'],0,3));
                                        if( isset($valueRange[$date['weekday']]) ){
                                            if(Url::get('bulk_type')=='STOPSELL'){
                                                if($r_r_time_id = DB::fetch('select id from siteminder_rate_stop_sell_time where siteminder_room_rate_id='.$value['id'].' and time='.$i.'','id'))
                                                    DB::update('siteminder_rate_stop_sell_time',array('stop_sell'=>$data,'status'=>0),'id='.$r_r_time_id);
                                                else
                                                    DB::insert('siteminder_rate_stop_sell_time',array('siteminder_room_rate_id'=>$value['id'],'in_date'=>Date_Time::to_orc_date(date('d/m/Y',$i)),'time'=>$i,'stop_sell'=>$data));
                                                
                                                $data_room_rate_xml[$value['id']]['id'] = $value['id'];
                                                $data_room_rate_xml[$value['id']]['rate_plan_code'] = $value['rate_plan_code'];
                                                $data_room_rate_xml[$value['id']]['inventory_type_code'] = $value['inventory_type_code'];
                                                $data_room_rate_xml[$value['id']]['timeline'][$i]['time'] = $i;
                                                $data_room_rate_xml[$value['id']]['timeline'][$i]['status'] = $data;
                                            }
                                            if(Url::get('bulk_type')=='CTA'){
                                                if($r_r_time_id = DB::fetch('select id from siteminder_rate_cta_time where siteminder_room_rate_id='.$value['id'].' and time='.$i.'','id'))
                                                    DB::update('siteminder_rate_cta_time',array('cta'=>$data,'status'=>0),'id='.$r_r_time_id);
                                                else
                                                    DB::insert('siteminder_rate_cta_time',array('siteminder_room_rate_id'=>$value['id'],'in_date'=>Date_Time::to_orc_date(date('d/m/Y',$i)),'time'=>$i,'cta'=>$data));
                                                    
                                                $data_room_rate_xml[$value['id']]['id'] = $value['id'];
                                                $data_room_rate_xml[$value['id']]['rate_plan_code'] = $value['rate_plan_code'];
                                                $data_room_rate_xml[$value['id']]['inventory_type_code'] = $value['inventory_type_code'];
                                                $data_room_rate_xml[$value['id']]['timeline'][$i]['time'] = $i;
                                                $data_room_rate_xml[$value['id']]['timeline'][$i]['status'] = $data;
                                            }
                                            if(Url::get('bulk_type')=='CTD'){
                                                if($r_r_time_id = DB::fetch('select id from siteminder_rate_ctd_time where siteminder_room_rate_id='.$value['id'].' and time='.$i.'','id'))
                                                    DB::update('siteminder_rate_ctd_time',array('ctd'=>$data,'status'=>0),'id='.$r_r_time_id);
                                                else
                                                    DB::insert('siteminder_rate_ctd_time',array('siteminder_room_rate_id'=>$value['id'],'in_date'=>Date_Time::to_orc_date(date('d/m/Y',$i)),'time'=>$i,'ctd'=>$data));
                                            
                                                $data_room_rate_xml[$value['id']]['id'] = $value['id'];
                                                $data_room_rate_xml[$value['id']]['rate_plan_code'] = $value['rate_plan_code'];
                                                $data_room_rate_xml[$value['id']]['inventory_type_code'] = $value['inventory_type_code'];
                                                $data_room_rate_xml[$value['id']]['timeline'][$i]['time'] = $i;
                                                $data_room_rate_xml[$value['id']]['timeline'][$i]['status'] = $data;
                                            }
                                        }
                                    }
                                }
                            }
                            foreach($value['child'] as $keyOTA=>$valueOTA){
                                if(isset($_REQUEST['bulkOTA'][$valueOTA['ota_code']])){
                                    foreach($_REQUEST['bulkrange'] as $keyRange=>$valueRange){
                                        for($i=Date_Time::to_time($valueRange['from_date']);$i<=Date_Time::to_time($valueRange['to_date']);$i+=86400){
                                            $date = getdate($i);
                                            $date['weekday'] = strtoupper(substr($date['weekday'],0,3));
                                            if( isset($valueRange[$date['weekday']]) ){
                                                if(Url::get('bulk_type')=='STOPSELL'){
                                                    if($ota_time_id = DB::fetch('select id from siteminder_ota_stop_sell_time where siteminder_room_rate_ota_id='.$valueOTA['id'].' and time='.$i.'','id'))
                                                        DB::update('siteminder_ota_stop_sell_time',array('stop_sell'=>$data,'status'=>($check_all)?1:0),'id='.$ota_time_id);
                                                    else
                                                        DB::insert('siteminder_ota_stop_sell_time',array('siteminder_room_rate_ota_id'=>$valueOTA['id'],'status'=>($check_all)?1:0,'in_date'=>Date_Time::to_orc_date(date('d/m/Y',$i)),'time'=>$i,'stop_sell'=>$data));
                                                    
                                                    if(!$check_all){    
                                                        $data_OTA_xml[$valueOTA['id']]['id'] = $valueOTA['id'];
                                                        $data_OTA_xml[$valueOTA['id']]['ota_code'] = $valueOTA['ota_code'];
                                                        $data_OTA_xml[$valueOTA['id']]['rate_plan_code'] = $value['rate_plan_code'];
                                                        $data_OTA_xml[$valueOTA['id']]['inventory_type_code'] = $value['inventory_type_code'];
                                                        $data_OTA_xml[$valueOTA['id']]['timeline'][$i]['time'] = $i;
                                                        $data_OTA_xml[$valueOTA['id']]['timeline'][$i]['status'] = $data;
                                                    }
                                                }
                                                if(Url::get('bulk_type')=='CTA'){
                                                    if($ota_time_id = DB::fetch('select id from siteminder_ota_cta_time where siteminder_room_rate_ota_id='.$valueOTA['id'].' and time='.$i.'','id'))
                                                        DB::update('siteminder_ota_cta_time',array('cta'=>$data,'status'=>($check_all)?1:0),'id='.$ota_time_id);
                                                    else
                                                        DB::insert('siteminder_ota_cta_time',array('siteminder_room_rate_ota_id'=>$valueOTA['id'],'status'=>($check_all)?1:0,'in_date'=>Date_Time::to_orc_date(date('d/m/Y',$i)),'time'=>$i,'cta'=>$data));
                                                    
                                                    if(!$check_all){    
                                                        $data_OTA_xml[$valueOTA['id']]['id'] = $valueOTA['id'];
                                                        $data_OTA_xml[$valueOTA['id']]['ota_code'] = $valueOTA['ota_code'];
                                                        $data_OTA_xml[$valueOTA['id']]['rate_plan_code'] = $value['rate_plan_code'];
                                                        $data_OTA_xml[$valueOTA['id']]['inventory_type_code'] = $value['inventory_type_code'];
                                                        $data_OTA_xml[$valueOTA['id']]['timeline'][$i]['time'] = $i;
                                                        $data_OTA_xml[$valueOTA['id']]['timeline'][$i]['status'] = $data;
                                                    }
                                                }
                                                if(Url::get('bulk_type')=='CTD'){
                                                    if($ota_time_id = DB::fetch('select id from siteminder_ota_ctd_time where siteminder_room_rate_ota_id='.$valueOTA['id'].' and time='.$i.'','id'))
                                                        DB::update('siteminder_ota_ctd_time',array('ctd'=>$data,'status'=>($check_all)?1:0),'id='.$ota_time_id);
                                                    else
                                                        DB::insert('siteminder_ota_ctd_time',array('siteminder_room_rate_ota_id'=>$valueOTA['id'],'status'=>($check_all)?1:0,'in_date'=>Date_Time::to_orc_date(date('d/m/Y',$i)),'time'=>$i,'ctd'=>$data));
                                                    
                                                    if(!$check_all){    
                                                        $data_OTA_xml[$valueOTA['id']]['id'] = $valueOTA['id'];
                                                        $data_OTA_xml[$valueOTA['id']]['ota_code'] = $valueOTA['ota_code'];
                                                        $data_OTA_xml[$valueOTA['id']]['rate_plan_code'] = $value['rate_plan_code'];
                                                        $data_OTA_xml[$valueOTA['id']]['inventory_type_code'] = $value['inventory_type_code'];
                                                        $data_OTA_xml[$valueOTA['id']]['timeline'][$i]['time'] = $i;
                                                        $data_OTA_xml[$valueOTA['id']]['timeline'][$i]['status'] = $data;
                                                    }
                                                }
                                            }
                                        }
                                    } // end foreach bulkrange
                                }
                            } // end foreach OTA
                        }
                    } // end foreach $siteminder_room_rate
                    
                    /** get xml flie Room Rate **/
                    $data_map = array();
                    foreach($data_room_rate_xml as $keyXml=>$valueXml){
                        ksort($valueXml['timeline']);
                        $data_map[$valueXml['id']]['id'] = $valueXml['id'];
                        $data_map[$valueXml['id']]['rate_plan_code'] = $valueXml['rate_plan_code'];
                        $data_map[$valueXml['id']]['inventory_type_code'] = $valueXml['inventory_type_code'];
                        $key = 0;
                        $timeline = 0;
                        $status = 'XX';
                        foreach($valueXml['timeline'] as $keyTime=>$valueTime){
                            if($timeline==Date_Time::to_time(date('d/m/Y',$valueTime['time']-86400)) and $status==$valueTime['status']){
                                $data_map[$valueXml['id']]['timeline'][$key]['EndTime'] = $valueTime['time'];
                            }else{
                                $key++;
                                $data_map[$valueXml['id']]['timeline'][$key]['StartTime'] = $valueTime['time'];
                                $data_map[$valueXml['id']]['timeline'][$key]['EndTime'] = $valueTime['time'];
                                $data_map[$valueXml['id']]['timeline'][$key]['status'] = $valueTime['status'];
                            }
                            $status=$valueTime['status'];
                            $timeline = $valueTime['time'];
                        }
                    }
                    if(sizeof($data_map)>0){
                        $SOAPBody = "<OTA_HotelAvailNotifRQ xmlns=\"http://www.opentravel.org/OTA/2003/05\" Version=\"1.0\" TimeStamp=\"".$this->GetTimeStamp()."\" EchoToken=\"".$this->GetEchoToken()."\">";
                          $SOAPBody .= "<POS>";
                            $SOAPBody .= "<Source>";
                              $SOAPBody .= "<RequestorID Type=\"22\" ID=\"".SITEMINDER_REQUESTOR_ID."\"/>";
                            $SOAPBody .= "</Source>";
                          $SOAPBody .= "</POS>";
                          $SOAPBody .= "<AvailStatusMessages HotelCode=\"".SITEMINDER_HOTELCODE."\">";
                            foreach($data_map as $keyMap=>$valueMap){
                                    $inventory_type_code = $valueMap['inventory_type_code'];
                                    $rate_plan_code = $valueMap['rate_plan_code'];
                                    if(isset($valueMap['timeline'])){
                                        foreach($valueMap['timeline'] as $kTime=>$vTime){
                                            $SOAPBody .= "<AvailStatusMessage>";
                                              $SOAPBody .= "<StatusApplicationControl Start=\"".date('Y-m-d',$vTime['StartTime'])."\" End=\"".date('Y-m-d',$vTime['EndTime'])."\" InvTypeCode=\"".$inventory_type_code."\" RatePlanCode=\"".$rate_plan_code."\"/>";
                                              if(Url::get('bulk_type')=='CTA')
                                                $SOAPBody .= "<RestrictionStatus Restriction=\"Arrival\" Status=\"".($vTime['status']==1?'Close':'Open')."\" />";
                                              elseif(Url::get('bulk_type')=='CTD')
                                                $SOAPBody .= "<RestrictionStatus Restriction=\"Departure\" Status=\"".($vTime['status']==1?'Close':'Open')."\" />";
                                              else
                                                $SOAPBody .= "<RestrictionStatus Status=\"".($vTime['status']==1?'Close':'Open')."\" />";
                                            $SOAPBody .= "</AvailStatusMessage>";
                                        }
                                    }
                                }
                          $SOAPBody .= "</AvailStatusMessages>";
                        $SOAPBody .= "</OTA_HotelAvailNotifRQ>";
                        
                        $id_soap = DB::insert('siteminder_soap_body',array('type'=>'STOP-CTA-CTD','time'=>time(),'portal_id'=>PORTAL_ID));
                        $path = ROOT_PATH.'packages/hotel/packages/siteminder/includes/dataXml/SOAP_'.$id_soap.'.xml';
                        $this->save_file($path,$SOAPBody);
                    }
                    /** end get xml **/
                    
                    /** get xml flie OTA **/
                    $data_map = array();
                    foreach($data_OTA_xml as $keyXml=>$valueXml){
                        ksort($valueXml['timeline']);
                        $data_map[$valueXml['id']]['id'] = $valueXml['id'];
                        $data_map[$valueXml['id']]['rate_plan_code'] = $valueXml['rate_plan_code'];
                        $data_map[$valueXml['id']]['inventory_type_code'] = $valueXml['inventory_type_code'];
                        $data_map[$valueXml['id']]['ota_code'] = $valueXml['ota_code'];
                        $key = 0;
                        $timeline = 0;
                        $status = 'XX';
                        foreach($valueXml['timeline'] as $keyTime=>$valueTime){
                            if($timeline==Date_Time::to_time(date('d/m/Y',$valueTime['time']-86400)) and $status==$valueTime['status']){
                                $data_map[$valueXml['id']]['timeline'][$key]['EndTime'] = $valueTime['time'];
                            }else{
                                $key++;
                                $data_map[$valueXml['id']]['timeline'][$key]['StartTime'] = $valueTime['time'];
                                $data_map[$valueXml['id']]['timeline'][$key]['EndTime'] = $valueTime['time'];
                                $data_map[$valueXml['id']]['timeline'][$key]['status'] = $valueTime['status'];
                            }
                            $status=$valueTime['status'];
                            $timeline = $valueTime['time'];
                        }
                    }
                    if(sizeof($data_map)>0){
                        $SOAPBody = "<OTA_HotelAvailNotifRQ xmlns=\"http://www.opentravel.org/OTA/2003/05\" Version=\"1.0\" TimeStamp=\"".$this->GetTimeStamp()."\" EchoToken=\"".$this->GetEchoToken()."\">";
                          $SOAPBody .= "<POS>";
                            $SOAPBody .= "<Source>";
                              $SOAPBody .= "<RequestorID Type=\"22\" ID=\"".SITEMINDER_REQUESTOR_ID."\"/>";
                            $SOAPBody .= "</Source>";
                          $SOAPBody .= "</POS>";
                          $SOAPBody .= "<AvailStatusMessages HotelCode=\"".SITEMINDER_HOTELCODE."\">";
                            foreach($data_map as $keyMap=>$valueMap){
                                    $inventory_type_code = $valueMap['inventory_type_code'];
                                    $rate_plan_code = $valueMap['rate_plan_code'];
                                    if(isset($valueMap['timeline'])){
                                        foreach($valueMap['timeline'] as $kTime=>$vTime){
                                            $SOAPBody .= "<AvailStatusMessage>";
                                              $SOAPBody .= "<StatusApplicationControl Start=\"".date('Y-m-d',$vTime['StartTime'])."\" End=\"".date('Y-m-d',$vTime['EndTime'])."\" InvTypeCode=\"".$inventory_type_code."\" RatePlanCode=\"".$rate_plan_code."\">";
                                                  $SOAPBody .= "<DestinationSystemCodes>";
                                                      $SOAPBody .= "<DestinationSystemCode>".$valueMap['ota_code']."</DestinationSystemCode>";
                                                  $SOAPBody .= "</DestinationSystemCodes>";
                                              $SOAPBody .= "</StatusApplicationControl>";
                                              if(Url::get('bulk_type')=='CTA')
                                                $SOAPBody .= "<RestrictionStatus Restriction=\"Arrival\" Status=\"".($vTime['status']==1?'Close':'Open')."\" />";
                                              elseif(Url::get('bulk_type')=='CTD')
                                                $SOAPBody .= "<RestrictionStatus Restriction=\"Departure\" Status=\"".($vTime['status']==1?'Close':'Open')."\" />";
                                              else
                                                $SOAPBody .= "<RestrictionStatus Status=\"".($vTime['status']==1?'Close':'Open')."\" />";
                                            $SOAPBody .= "</AvailStatusMessage>";
                                        }
                                    }
                                }
                          $SOAPBody .= "</AvailStatusMessages>";
                        $SOAPBody .= "</OTA_HotelAvailNotifRQ>";
                        $id_soap = DB::insert('siteminder_soap_body',array('type'=>'STOP-CTA-CTD','time'=>time(),'portal_id'=>PORTAL_ID));
                        $path = ROOT_PATH.'packages/hotel/packages/siteminder/includes/dataXml/SOAP_'.$id_soap.'.xml';
                        $this->save_file($path,$SOAPBody);
                    }
                    /** end get xml **/
                    
                }elseif(Url::get('bulk_type')=='MINSTAY' OR Url::get('bulk_type')=='MAXSTAY'){
                    // get STOPSELL
                    $data = Url::get('bulk_text');
                    $data_room_rate_xml = array();
                    $data_OTA_xml = array();
                    foreach($siteminder_room_rate as $key=>$value){
                        if(isset($_REQUEST['bulkroomrate'][$value['id']])){
                            $check_all = true;
                            foreach($value['child'] as $keyOTACheck=>$valueOTACheck){
                                if(!isset($_REQUEST['bulkOTA'][$valueOTACheck['ota_code']])){
                                    $check_all = false;
                                }
                            }
                            // update All OTA
                            if($check_all){
                                foreach($_REQUEST['bulkrange'] as $keyRange=>$valueRange){
                                    for($i=Date_Time::to_time($valueRange['from_date']);$i<=Date_Time::to_time($valueRange['to_date']);$i+=86400){
                                        $date = getdate($i);
                                        $date['weekday'] = strtoupper(substr($date['weekday'],0,3));
                                        if( isset($valueRange[$date['weekday']]) ){
                                            if(Url::get('bulk_type')=='MINSTAY'){
                                                if($r_r_time_id = DB::fetch('select id from siteminder_rate_min_stay_time where siteminder_room_rate_id='.$value['id'].' and time='.$i.'','id'))
                                                    DB::update('siteminder_rate_min_stay_time',array('min_stay'=>$data,'status'=>0),'id='.$r_r_time_id);
                                                else
                                                    DB::insert('siteminder_rate_min_stay_time',array('siteminder_room_rate_id'=>$value['id'],'in_date'=>Date_Time::to_orc_date(date('d/m/Y',$i)),'time'=>$i,'min_stay'=>$data));
                                            
                                                $data_room_rate_xml[$value['id']]['id'] = $value['id'];
                                                $data_room_rate_xml[$value['id']]['rate_plan_code'] = $value['rate_plan_code'];
                                                $data_room_rate_xml[$value['id']]['inventory_type_code'] = $value['inventory_type_code'];
                                                $data_room_rate_xml[$value['id']]['timeline'][$i]['time'] = $i;
                                                $data_room_rate_xml[$value['id']]['timeline'][$i]['data'] = $data;
                                            }
                                            if(Url::get('bulk_type')=='MAXSTAY'){
                                                if($r_r_time_id = DB::fetch('select id from siteminder_rate_max_stay_time where siteminder_room_rate_id='.$value['id'].' and time='.$i.'','id'))
                                                    DB::update('siteminder_rate_max_stay_time',array('max_stay'=>$data,'status'=>0),'id='.$r_r_time_id);
                                                else
                                                    DB::insert('siteminder_rate_max_stay_time',array('siteminder_room_rate_id'=>$value['id'],'in_date'=>Date_Time::to_orc_date(date('d/m/Y',$i)),'time'=>$i,'max_stay'=>$data));
                                            
                                                $data_room_rate_xml[$value['id']]['id'] = $value['id'];
                                                $data_room_rate_xml[$value['id']]['rate_plan_code'] = $value['rate_plan_code'];
                                                $data_room_rate_xml[$value['id']]['inventory_type_code'] = $value['inventory_type_code'];
                                                $data_room_rate_xml[$value['id']]['timeline'][$i]['time'] = $i;
                                                $data_room_rate_xml[$value['id']]['timeline'][$i]['data'] = $data;
                                            }
                                        }
                                    }
                                }
                            }
                            foreach($value['child'] as $keyOTA=>$valueOTA){
                                if(isset($_REQUEST['bulkOTA'][$valueOTA['ota_code']])){
                                    foreach($_REQUEST['bulkrange'] as $keyRange=>$valueRange){
                                        for($i=Date_Time::to_time($valueRange['from_date']);$i<=Date_Time::to_time($valueRange['to_date']);$i+=86400){
                                            $date = getdate($i);
                                            $date['weekday'] = strtoupper(substr($date['weekday'],0,3));
                                            if( isset($valueRange[$date['weekday']]) ){
                                                if(Url::get('bulk_type')=='MINSTAY'){
                                                    if($ota_time_id = DB::fetch('select id from siteminder_ota_min_stay_time where siteminder_room_rate_ota_id='.$valueOTA['id'].' and time='.$i.'','id'))
                                                        DB::update('siteminder_ota_min_stay_time',array('min_stay'=>$data,'status'=>0),'id='.$ota_time_id);
                                                    else
                                                        DB::insert('siteminder_ota_min_stay_time',array('siteminder_room_rate_ota_id'=>$valueOTA['id'],'in_date'=>Date_Time::to_orc_date(date('d/m/Y',$i)),'time'=>$i,'min_stay'=>$data));
                                                
                                                    if(!$check_all){    
                                                        $data_OTA_xml[$valueOTA['id']]['id'] = $valueOTA['id'];
                                                        $data_OTA_xml[$valueOTA['id']]['ota_code'] = $valueOTA['ota_code'];
                                                        $data_OTA_xml[$valueOTA['id']]['rate_plan_code'] = $value['rate_plan_code'];
                                                        $data_OTA_xml[$valueOTA['id']]['inventory_type_code'] = $value['inventory_type_code'];
                                                        $data_OTA_xml[$valueOTA['id']]['timeline'][$i]['time'] = $i;
                                                        $data_OTA_xml[$valueOTA['id']]['timeline'][$i]['data'] = $data;
                                                    }
                                                }
                                                if(Url::get('bulk_type')=='MAXSTAY'){
                                                    if($ota_time_id = DB::fetch('select id from siteminder_ota_max_stay_time where siteminder_room_rate_ota_id='.$valueOTA['id'].' and time='.$i.'','id'))
                                                        DB::update('siteminder_ota_max_stay_time',array('max_stay'=>$data,'status'=>0),'id='.$ota_time_id);
                                                    else
                                                        DB::insert('siteminder_ota_max_stay_time',array('siteminder_room_rate_ota_id'=>$valueOTA['id'],'in_date'=>Date_Time::to_orc_date(date('d/m/Y',$i)),'time'=>$i,'max_stay'=>$data));
                                                
                                                    if(!$check_all){    
                                                        $data_OTA_xml[$valueOTA['id']]['id'] = $valueOTA['id'];
                                                        $data_OTA_xml[$valueOTA['id']]['ota_code'] = $valueOTA['ota_code'];
                                                        $data_OTA_xml[$valueOTA['id']]['rate_plan_code'] = $value['rate_plan_code'];
                                                        $data_OTA_xml[$valueOTA['id']]['inventory_type_code'] = $value['inventory_type_code'];
                                                        $data_OTA_xml[$valueOTA['id']]['timeline'][$i]['time'] = $i;
                                                        $data_OTA_xml[$valueOTA['id']]['timeline'][$i]['data'] = $data;
                                                    }
                                                }
                                            }
                                        }
                                    } // end foreach bulkrange
                                }
                            } // end foreach OTA
                        }
                    } // end foreach $siteminder_room_rate
                    
                    //System::debug($data_OTA_xml);
                    //System::debug($data_room_rate_xml); die;
                    
                    /** get xml flie Room Rate **/
                    $data_map = array();
                    foreach($data_room_rate_xml as $keyXml=>$valueXml){
                        ksort($valueXml['timeline']);
                        $data_map[$valueXml['id']]['id'] = $valueXml['id'];
                        $data_map[$valueXml['id']]['rate_plan_code'] = $valueXml['rate_plan_code'];
                        $data_map[$valueXml['id']]['inventory_type_code'] = $valueXml['inventory_type_code'];
                        $key = 0;
                        $timeline = 0;
                        $datas = 'XX';
                        foreach($valueXml['timeline'] as $keyTime=>$valueTime){
                            if($timeline==Date_Time::to_time(date('d/m/Y',$valueTime['time']-86400)) and $datas==$valueTime['data']){
                                $data_map[$valueXml['id']]['timeline'][$key]['EndTime'] = $valueTime['time'];
                            }else{
                                $key++;
                                $data_map[$valueXml['id']]['timeline'][$key]['StartTime'] = $valueTime['time'];
                                $data_map[$valueXml['id']]['timeline'][$key]['EndTime'] = $valueTime['time'];
                                $data_map[$valueXml['id']]['timeline'][$key]['data'] = $valueTime['data'];
                            }
                            $datas=$valueTime['data'];
                            $timeline = $valueTime['time'];
                        }
                    }
                    if(sizeof($data_map)>0){
                        $SOAPBody = "<OTA_HotelAvailNotifRQ xmlns=\"http://www.opentravel.org/OTA/2003/05\" Version=\"1.0\" TimeStamp=\"".$this->GetTimeStamp()."\" EchoToken=\"".$this->GetEchoToken()."\">";
                          $SOAPBody .= "<POS>";
                            $SOAPBody .= "<Source>";
                              $SOAPBody .= "<RequestorID Type=\"22\" ID=\"".SITEMINDER_REQUESTOR_ID."\"/>";
                            $SOAPBody .= "</Source>";
                          $SOAPBody .= "</POS>";
                          $SOAPBody .= "<AvailStatusMessages HotelCode=\"".SITEMINDER_HOTELCODE."\">";
                            foreach($data_map as $keyMap=>$valueMap){
                                    $inventory_type_code = $valueMap['inventory_type_code'];
                                    $rate_plan_code = $valueMap['rate_plan_code'];
                                    if(isset($valueMap['timeline'])){
                                        foreach($valueMap['timeline'] as $kTime=>$vTime){
                                            $SOAPBody .= "<AvailStatusMessage>";
                                              $SOAPBody .= "<StatusApplicationControl Start=\"".date('Y-m-d',$vTime['StartTime'])."\" End=\"".date('Y-m-d',$vTime['EndTime'])."\" InvTypeCode=\"".$inventory_type_code."\" RatePlanCode=\"".$rate_plan_code."\"/>";
                                              if(Url::get('bulk_type')=='MINSTAY'){
                                                    $SOAPBody .= "<LengthsOfStay>";
                                                        $SOAPBody .= "<LengthOfStay MinMaxMessageType=\"SetMinLOS\" Time=\"".(($vTime['data']==0 || $vTime['data']=='')?999:$vTime['data'])."\"/>";
                                                      $SOAPBody .= "</LengthsOfStay>";
                                              }
                                              else{
                                                    $SOAPBody .= "<LengthsOfStay>";
                                                        $SOAPBody .= "<LengthOfStay MinMaxMessageType=\"SetMaxLOS\" Time=\"".(($vTime['data']==0 || $vTime['data']=='')?999:$vTime['data'])."\"/>";
                                                      $SOAPBody .= "</LengthsOfStay>";
                                              }
                                            $SOAPBody .= "</AvailStatusMessage>";
                                        }
                                    }
                                }
                          $SOAPBody .= "</AvailStatusMessages>";
                        $SOAPBody .= "</OTA_HotelAvailNotifRQ>";
                        
                        $id_soap = DB::insert('siteminder_soap_body',array('type'=>'MINMAXSTAY','time'=>time(),'portal_id'=>PORTAL_ID));
                        $path = ROOT_PATH.'packages/hotel/packages/siteminder/includes/dataXml/SOAP_'.$id_soap.'.xml';
                        $this->save_file($path,$SOAPBody);
                    }
                    /** end get xml **/
                    
                    /** get xml flie OTA **/
                    $data_map = array();
                    //System::debug($data_OTA_xml); //die;
                    foreach($data_OTA_xml as $keyXml=>$valueXml){
                        ksort($valueXml['timeline']);
                        $data_map[$valueXml['id']]['id'] = $valueXml['id'];
                        $data_map[$valueXml['id']]['rate_plan_code'] = $valueXml['rate_plan_code'];
                        $data_map[$valueXml['id']]['inventory_type_code'] = $valueXml['inventory_type_code'];
                        $data_map[$valueXml['id']]['ota_code'] = $valueXml['ota_code'];
                        $key = 0;
                        $timeline = 0;
                        $datas = 'XX';
                        foreach($valueXml['timeline'] as $keyTime=>$valueTime){
                            if($timeline==Date_Time::to_time(date('d/m/Y',$valueTime['time']-86400)) and $datas==$valueTime['data']){
                                $data_map[$valueXml['id']]['timeline'][$key]['EndTime'] = $valueTime['time'];
                            }else{
                                $key++;
                                $data_map[$valueXml['id']]['timeline'][$key]['StartTime'] = $valueTime['time'];
                                $data_map[$valueXml['id']]['timeline'][$key]['EndTime'] = $valueTime['time'];
                                $data_map[$valueXml['id']]['timeline'][$key]['data'] = $valueTime['data'];
                            }
                            $datas=$valueTime['data'];
                            $timeline = $valueTime['time'];
                        }
                    }
                    //System::debug($data_map); die;
                    if(sizeof($data_map)>0){
                        $SOAPBody = "<OTA_HotelAvailNotifRQ xmlns=\"http://www.opentravel.org/OTA/2003/05\" Version=\"1.0\" TimeStamp=\"".$this->GetTimeStamp()."\" EchoToken=\"".$this->GetEchoToken()."\">";
                          $SOAPBody .= "<POS>";
                            $SOAPBody .= "<Source>";
                              $SOAPBody .= "<RequestorID Type=\"22\" ID=\"".SITEMINDER_REQUESTOR_ID."\"/>";
                            $SOAPBody .= "</Source>";
                          $SOAPBody .= "</POS>";
                          $SOAPBody .= "<AvailStatusMessages HotelCode=\"".SITEMINDER_HOTELCODE."\">";
                            foreach($data_map as $keyMap=>$valueMap){
                                    $inventory_type_code = $valueMap['inventory_type_code'];
                                    $rate_plan_code = $valueMap['rate_plan_code'];
                                    if(isset($valueMap['timeline'])){
                                        foreach($valueMap['timeline'] as $kTime=>$vTime){
                                            $SOAPBody .= "<AvailStatusMessage>";
                                              $SOAPBody .= "<StatusApplicationControl Start=\"".date('Y-m-d',$vTime['StartTime'])."\" End=\"".date('Y-m-d',$vTime['EndTime'])."\" InvTypeCode=\"".$inventory_type_code."\" RatePlanCode=\"".$rate_plan_code."\">";
                                              $SOAPBody .= "<DestinationSystemCodes>";
                                                      $SOAPBody .= "<DestinationSystemCode>".$valueMap['ota_code']."</DestinationSystemCode>";
                                                  $SOAPBody .= "</DestinationSystemCodes>";
                                              $SOAPBody .= "</StatusApplicationControl>";
                                              if(Url::get('bulk_type')=='MINSTAY'){
                                                    $SOAPBody .= "<LengthsOfStay>";
                                                        $SOAPBody .= "<LengthOfStay MinMaxMessageType=\"SetMinLOS\" Time=\"".(($vTime['data']==0 || $vTime['data']=='')?999:$vTime['data'])."\"/>";
                                                      $SOAPBody .= "</LengthsOfStay>";
                                              }
                                              else{
                                                    $SOAPBody .= "<LengthsOfStay>";
                                                        $SOAPBody .= "<LengthOfStay MinMaxMessageType=\"SetMaxLOS\" Time=\"".(($vTime['data']==0 || $vTime['data']=='')?999:$vTime['data'])."\"/>";
                                                      $SOAPBody .= "</LengthsOfStay>";
                                              }
                                            $SOAPBody .= "</AvailStatusMessage>";
                                        }
                                    }
                                }
                          $SOAPBody .= "</AvailStatusMessages>";
                        $SOAPBody .= "</OTA_HotelAvailNotifRQ>";
                        $id_soap = DB::insert('siteminder_soap_body',array('type'=>'MINMAXSTAY','time'=>time(),'portal_id'=>PORTAL_ID));
                        $path = ROOT_PATH.'packages/hotel/packages/siteminder/includes/dataXml/SOAP_'.$id_soap.'.xml';
                        $this->save_file($path,$SOAPBody);
                    }
                    /** end get xml **/
                }
                
            }
            //Url::redirect('siteminder_inventory',array('in_date'=>Url::get('in_date'),'step'=>Url::get('step')));
        }// END SAVE BULK
        elseif(Url::get('act')=='SAVE'){
            // bo chuc nang save
            /**
            if(Url::get('room_type')){
                foreach(Url::get('room_type') as $key=>$value){
                    $room_type_id = $key;
                    foreach($value['timeline'] as $keyTimeline=>$valueTimeline){
                        $time = $keyTimeline;
                        $availability = $valueTimeline['availability'];
                        if($type_time_id = DB::fetch('select id from siteminder_room_type_time where siteminder_room_type_id='.$room_type_id.' and time='.$time.'','id')){
                            $availability_old = DB::fetch('select availability from siteminder_room_type_time where id='.$type_time_id.'','availability');
                            if($availability_old!=$availability){
                                DB::update('siteminder_room_type_time',array('availability'=>$availability,'status'=>0),'id='.$type_time_id);
                            }
                        }else{
                            DB::insert('siteminder_room_type_time',array('siteminder_room_type_id'=>$room_type_id,'in_date'=>Date_Time::to_orc_date(date('d/m/Y',$time)),'time'=>$time,'availability'=>$availability));
                        }
                    }
                }
            }
            
            if(Url::get('room_rate')){
                foreach(Url::get('room_rate') as $key=>$value){
                    $room_rate_id = $key;
                    foreach($value['timeline'] as $keyTimeline=>$valueTimeline){
                        $time = $keyTimeline;
                        $rates = $valueTimeline['rates'];
                        if($rate_time_id = DB::fetch('select id from siteminder_room_rate_time where siteminder_room_rate_id='.$room_rate_id.' and time='.$time.'','id')){
                            $rates_old = DB::fetch('select rates from siteminder_room_rate_time where id='.$rate_time_id.'','rates');
                            if($rates_old!=$rates){
                                DB::update('siteminder_room_rate_time',array('rates'=>$rates,'status'=>0),'id='.$rate_time_id);
                            }
                        }else{
                            DB::insert('siteminder_room_rate_time',array('siteminder_room_rate_id'=>$room_rate_id,'in_date'=>Date_Time::to_orc_date(date('d/m/Y',$time)),'time'=>$time,'rates'=>$rates));
                        }
                    }
                }
            }
            
            if(Url::get('room_rate_ota')){
                foreach(Url::get('room_rate_ota') as $key=>$value){
                    $ota_id = $key;
                    foreach($value['timeline'] as $keyTimeline=>$valueTimeline){
                        $time = $keyTimeline;
                        $rates = $valueTimeline['rates'];
                        $min_stay = $valueTimeline['min_stay'];
                        $max_stay = $valueTimeline['max_stay'];
                        $stop_sell = isset($valueTimeline['stop_sell'])?1:0;
                        $cta = isset($valueTimeline['cta'])?1:0;
                        $ctd = isset($valueTimeline['ctd'])?1:0;
                        // rate
                        if($rate_time_id = DB::fetch('select id from siteminder_ota_rates_time where siteminder_room_rate_ota_id='.$ota_id.' and time='.$time.'','id')){
                            DB::update('siteminder_ota_rates_time',array('rates'=>$rates,'status'=>0),'id='.$rate_time_id);
                        }else{
                            DB::insert('siteminder_ota_rates_time',array('siteminder_room_rate_ota_id'=>$ota_id,'in_date'=>Date_Time::to_orc_date(date('d/m/Y',$time)),'time'=>$time,'rates'=>$rates));
                        }
                        // min stay
                        if($min_stay_time_id = DB::fetch('select id from siteminder_ota_min_stay_time where siteminder_room_rate_ota_id='.$ota_id.' and time='.$time.'','id')){
                            DB::update('siteminder_ota_min_stay_time',array('min_stay'=>$min_stay,'status'=>0),'id='.$min_stay_time_id);
                        }else{
                            DB::insert('siteminder_ota_min_stay_time',array('siteminder_room_rate_ota_id'=>$ota_id,'in_date'=>Date_Time::to_orc_date(date('d/m/Y',$time)),'time'=>$time,'min_stay'=>$min_stay));
                        }
                        // max stay
                        if($max_stay_time_id = DB::fetch('select id from siteminder_ota_max_stay_time where siteminder_room_rate_ota_id='.$ota_id.' and time='.$time.'','id')){
                            DB::update('siteminder_ota_max_stay_time',array('max_stay'=>$max_stay,'status'=>0),'id='.$max_stay_time_id);
                        }else{
                            DB::insert('siteminder_ota_max_stay_time',array('siteminder_room_rate_ota_id'=>$ota_id,'in_date'=>Date_Time::to_orc_date(date('d/m/Y',$time)),'time'=>$time,'max_stay'=>$max_stay));
                        }
                        // stop sell
                        if($stop_sell_time_id = DB::fetch('select id from siteminder_ota_stop_sell_time where siteminder_room_rate_ota_id='.$ota_id.' and time='.$time.'','id')){
                            DB::update('siteminder_ota_stop_sell_time',array('stop_sell'=>$stop_sell,'status'=>0),'id='.$stop_sell_time_id);
                        }else{
                            DB::insert('siteminder_ota_stop_sell_time',array('siteminder_room_rate_ota_id'=>$ota_id,'in_date'=>Date_Time::to_orc_date(date('d/m/Y',$time)),'time'=>$time,'stop_sell'=>$stop_sell));
                        }
                        // cta
                        if($cta_time_id = DB::fetch('select id from siteminder_ota_cta_time where siteminder_room_rate_ota_id='.$ota_id.' and time='.$time.'','id')){
                            DB::update('siteminder_ota_cta_time',array('cta'=>$cta,'status'=>0),'id='.$cta_time_id);
                        }else{
                            DB::insert('siteminder_ota_cta_time',array('siteminder_room_rate_ota_id'=>$ota_id,'in_date'=>Date_Time::to_orc_date(date('d/m/Y',$time)),'time'=>$time,'cta'=>$cta));
                        }
                        // ctd
                        if($ctd_time_id = DB::fetch('select id from siteminder_ota_ctd_time where siteminder_room_rate_ota_id='.$ota_id.' and time='.$time.'','id')){
                            DB::update('siteminder_ota_ctd_time',array('ctd'=>$ctd,'status'=>0),'id='.$ctd_time_id);
                        }else{
                            DB::insert('siteminder_ota_ctd_time',array('siteminder_room_rate_ota_id'=>$ota_id,'in_date'=>Date_Time::to_orc_date(date('d/m/Y',$time)),'time'=>$time,'ctd'=>$ctd));
                        }
                    }
                }
            }
            **/
            //Url::redirect('siteminder_inventory',array('in_date'=>Url::get('in_date'),'step'=>Url::get('step')));
        }
        Url::redirect('siteminder_inventory',array('in_date'=>Url::get('in_date'),'step'=>0));
    }
	function draw()
	{
	    /**
         * @DeleteData : xoa du lieu cu
         * */
        DB::delete('siteminder_ota_cta_time','in_date<\''.Date_Time::to_orc_date(date('d/m/Y')).'\'');
        DB::delete('siteminder_ota_ctd_time','in_date<\''.Date_Time::to_orc_date(date('d/m/Y')).'\'');
        DB::delete('siteminder_ota_max_stay_time','in_date<\''.Date_Time::to_orc_date(date('d/m/Y')).'\'');
        DB::delete('siteminder_ota_min_stay_time','in_date<\''.Date_Time::to_orc_date(date('d/m/Y')).'\'');
        DB::delete('siteminder_ota_rates_time','in_date<\''.Date_Time::to_orc_date(date('d/m/Y')).'\'');
        DB::delete('siteminder_ota_stop_sell_time','in_date<\''.Date_Time::to_orc_date(date('d/m/Y')).'\'');
        DB::delete('siteminder_rate_avail_time','in_date<\''.Date_Time::to_orc_date(date('d/m/Y')).'\'');
        DB::delete('siteminder_rate_cta_time','in_date<\''.Date_Time::to_orc_date(date('d/m/Y')).'\'');
        DB::delete('siteminder_rate_ctd_time','in_date<\''.Date_Time::to_orc_date(date('d/m/Y')).'\'');
        DB::delete('siteminder_rate_max_stay_time','in_date<\''.Date_Time::to_orc_date(date('d/m/Y')).'\'');
        DB::delete('siteminder_rate_min_stay_time','in_date<\''.Date_Time::to_orc_date(date('d/m/Y')).'\'');
        DB::delete('siteminder_rate_stop_sell_time','in_date<\''.Date_Time::to_orc_date(date('d/m/Y')).'\'');
        DB::delete('siteminder_room_rate_time','in_date<\''.Date_Time::to_orc_date(date('d/m/Y')).'\'');
        DB::delete('siteminder_room_type_time','in_date<\''.Date_Time::to_orc_date(date('d/m/Y')).'\'');
        /** End delete Data **/ 
	    $this->map = array();
        
        $this->map['in_date'] = $_REQUEST['in_date'] = Url::get('in_date')?Url::get('in_date'):date('d/m/Y');
        $this->map['step'] = $_REQUEST['step'] = Url::get('step')?Url::get('step'):0;
        $start_date = date('d/m/Y',(($this->map['step']*7*86400)+Date_Time::to_time($this->map['in_date'])));
        if(Date_Time::to_time($start_date)<Date_Time::to_time(date('d/m/Y'))){
            $start_date = date('d/m/Y');
            $this->map['step'] = $_REQUEST['step'] = 0;
        }
        $this->map['in_date'] = $_REQUEST['in_date'] = $start_date;
        $end_date = date('d/m/Y',(Date_Time::to_time($start_date)+14*86400)-86400);
        
        $in_date = getdate(Date_Time::to_time($start_date));
        //$this->map['in_date'] = (strtoupper($in_date['month'])!='JULY'?substr($in_date['month'],0,3):$in_date['month']).' '.$in_date['mday'].', '.$in_date['year'];
        
        $timeline = array();
        for($i=Date_Time::to_time($start_date);$i<=Date_Time::to_time($end_date);$i+=86400){
            $timeline[$i] = getdate($i);
            if(strtoupper($timeline[$i]['month'])!='JULY'){
                $timeline[$i]['month'] = substr($timeline[$i]['month'],0,3);
            }
            $timeline[$i]['weekday'] = substr($timeline[$i]['weekday'],0,3);
            $timeline[$i]['mday'] = date('d',$i);
            $timeline[$i]['id'] = $i;
            $timeline[$i]['time'] = $i;
            $timeline[$i]['day'] = date('d/m/Y',$i);
            $timeline[$i]['availability'] = 0;
            $timeline[$i]['availability_real'] = 0; // from room level
            $timeline[$i]['availability_bg'] = '#FFAB2D';
            $timeline[$i]['rates'] = 0;
            $timeline[$i]['stop_sell'] = 0;
            $timeline[$i]['cta'] = 0;
            $timeline[$i]['ctd'] = 0;
            $timeline[$i]['min_stay'] = 0;
            $timeline[$i]['max_stay'] = 0;
            $timeline[$i]['background'] = '#FFFFFF';
            $timeline[$i]['background-top'] = '#F1F1F1';
            if(strtoupper($timeline[$i]['weekday'])=='MON' or strtoupper($timeline[$i]['weekday'])=='TUE'){
                $timeline[$i]['background'] = '#FFEDE3';
                $timeline[$i]['background-top'] = '#CCCCCC';
            }
            $timeline[$i]['data_status'] = 0;
        }
        $this->map['timeline'] = $timeline;
        
		require_once 'packages/hotel/packages/reception/modules/includes/reservation.php';
		$room_levels = check_availability('',' 1>0',Date_Time::to_time($start_date),(Date_Time::to_time($end_date)+86400));
        
        $siteminder_room_type = DB::fetch_all('select 
                                                    siteminder_room_type.*, 
                                                    room_level.brief_name as room_level_code,
                                                    room_level.name as room_level_name
                                                from  
                                                    siteminder_room_type
                                                    inner join room_level on room_level.id=siteminder_room_type.room_level_id
                                                where siteminder_room_type.portal_id=\''.PORTAL_ID.'\'
                                                ORDER BY siteminder_room_type.type_name
                                                ');
        $siteminder_room_rate = DB::fetch_all('select 
                                                    a1.*,
                                                    NVL(a1.manual_rack_rate,0) as manual_rack_rate,
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
                                                    a1.structure_id DESC
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
                                                    AND siteminder_room_rate_over.from_date<=\''.Date_Time::to_orc_date($end_date).'\'
                                                    AND siteminder_room_rate_over.to_date>=\''.Date_Time::to_orc_date($start_date).'\'
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
                                            AND siteminder_room_rate_ota_over.from_date<=\''.Date_Time::to_orc_date($end_date).'\'
                                            AND siteminder_room_rate_ota_over.to_date>=\''.Date_Time::to_orc_date($start_date).'\'
                                        ');
        foreach($siteminder_room_type as $key=>$value){
            $siteminder_room_type[$key]['child'] = array();
            $siteminder_room_type[$key]['timeline'] = $timeline;
            $siteminder_room_type[$key]['is_set_avail'] = 0;
            foreach($timeline as $keyTimeline=>$valueTimeline){
                if(isset($room_levels[$value['room_level_id']]['day_items'][$keyTimeline]['number_room_quantity']))
                    $siteminder_room_type[$key]['timeline'][$keyTimeline]['availability_real'] = $room_levels[$value['room_level_id']]['day_items'][$keyTimeline]['number_room_quantity'];
            }
        }
        $this->map['Channel_OTA'] = array();
        foreach($channel as $key=>$value){
            $value['timeline'] = $timeline;
            if(isset($siteminder_room_rate[$value['siteminder_room_rate_id']])){
                $siteminder_room_rate[$value['siteminder_room_rate_id']]['child'][$key] = $value;
            }
            if(!isset($this->map['Channel_OTA'][$value['ota_code']])){
                $this->map['Channel_OTA'][$value['ota_code']]['id'] = $value['ota_code'];
                $this->map['Channel_OTA'][$value['ota_code']]['ota_code'] = $value['ota_code'];
                $this->map['Channel_OTA'][$value['ota_code']]['ota_name'] = $value['ota_name'];
            }
        }
        $room_rate_level = array();
        foreach($siteminder_room_rate as $key=>$value){
            $level = IDStructure::level($value['structure_id']);
            $siteminder_room_rate[$key]['level'] = IDStructure::level($value['structure_id']);
            $siteminder_room_rate[$key]['timeline'] = $timeline;
            $room_rate_level[$level]['id'] = $level;
            $room_rate_level[$level]['child'][$key] = $siteminder_room_rate[$key];
        }
        ksort($room_rate_level);
        foreach($room_rate_level as $key=>$value){
            // get room rate
            foreach($value['child'] as $keyChild=>$valueChild){
                /** xet room rate default **/
                if($valueChild['rate_config_derive']==1){
                    // rates derive get time = rooms parent
                    /** lap mang thoi gian xem du lieu **/
                    foreach($timeline as $keyTimeline=>$valueTimeline){
                        $parent_id = $valueChild['derive_from_rate_id'];
                        $rate_parent = $siteminder_room_rate[$parent_id]['timeline'][$keyTimeline]['rates'];
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
                                if($valueTimeline['time']>=$from_time and $valueTimeline['time']<=$to_time){
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
                        $siteminder_room_rate[$valueChild['id']]['timeline'][$keyTimeline]['rates'] = $rates;
                    } // end foreach: timelime
                }else{
                    // rates manual get time = rooms rack rate
                    foreach($timeline as $keyTimeline=>$valueTimeline){
                        $siteminder_room_rate[$valueChild['id']]['timeline'][$keyTimeline]['rates'] = $valueChild['manual_rack_rate'];
                    }
                }
                /** xet OTA avail + rates default **/
                if(isset($siteminder_room_rate[$valueChild['id']]['child'])){
                    $OTA_Channel = $siteminder_room_rate[$valueChild['id']]['child'];
                    foreach($OTA_Channel as $keyOTA=>$valueOTA){
                        if($valueOTA['siteminder_room_rate_id']==$valueChild['id']){
                            foreach($timeline as $keyTimeline=>$valueTimeline){
                                if($valueOTA['manual_derive']=='DERIVE'){
                                    $rate_parent = $siteminder_room_rate[$valueChild['id']]['timeline'][$keyTimeline]['rates'];
                                    $rates = $rate_parent;
                                    
                                    /////////
                                    $daily_rate = $valueOTA['daily_rate'];
                                    $percent_inc = $valueOTA['percent_inc'];
                                    $percent_adjust = $valueOTA['percent_adjust'];
                                    $amount_inc = $valueOTA['amount_inc'];
                                    $amount_adjust = $valueOTA['amount_adjust'];
                                    
                                    foreach($channel_over as $keyOVER=>$valueOVER){
                                        if($valueOTA['id']==$valueOVER['siteminder_room_rate_ota_id']){
                                            $from_time = Date_Time::to_time($valueOVER['from_date']);
                                            $to_time = Date_Time::to_time($valueOVER['to_date']);
                                            if($valueTimeline['time']>=$from_time and $valueTimeline['time']<=$to_time){
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
                                    $siteminder_room_rate[$valueChild['id']]['child'][$keyOTA]['timeline'][$keyTimeline]['rates'] = $rates;
                                }else{
                                    $siteminder_room_rate[$valueChild['id']]['child'][$keyOTA]['timeline'][$keyTimeline]['rates'] = $siteminder_room_rate[$valueChild['id']]['timeline'][$keyTimeline]['rates'];
                                }
                                $siteminder_room_rate[$valueChild['id']]['child'][$keyOTA]['timeline'][$keyTimeline]['stop_sell'] = $siteminder_room_rate[$valueChild['id']]['default_stop_sell'];
                                if($siteminder_room_rate[$valueChild['id']]['default_stop_sell']==1)
                                    $siteminder_room_rate[$valueChild['id']]['child'][$keyOTA]['timeline'][$keyTimeline]['background'] = '#FFAB2D';
                                $siteminder_room_rate[$valueChild['id']]['child'][$keyOTA]['timeline'][$keyTimeline]['min_stay'] = $siteminder_room_rate[$valueChild['id']]['default_min_stay'];
                            }
                        }
                    }
                }else{
                    $siteminder_room_rate[$valueChild['id']]['child'] = array();
                }
                /** gan vao room type **/
                if(isset($siteminder_room_type[$valueChild['siteminder_room_type_id']])){
                    $siteminder_room_type[$valueChild['siteminder_room_type_id']]['child'][$keyChild] = $siteminder_room_rate[$valueChild['id']];
                    if($valueChild['availability']!='MANAGED')
                        $siteminder_room_type[$valueChild['siteminder_room_type_id']]['is_set_avail'] = 1;
                }
            }
        }
        
        //System::debug($siteminder_room_type);
        
        $room_type_time = DB::fetch_all('
                                        SELECT
                                            siteminder_room_type_time.*,
                                            NVL(siteminder_room_type_time.availability,0) as availability
                                        FROM
                                            siteminder_room_type_time
                                            inner join siteminder_room_type on siteminder_room_type.id=siteminder_room_type_time.siteminder_room_type_id
                                            inner join room_level on room_level.id=siteminder_room_type.room_level_id
                                        WHERE 
                                            siteminder_room_type.portal_id=\''.PORTAL_ID.'\'
                                            AND siteminder_room_type_time.in_date>=\''.Date_Time::to_orc_date($start_date).'\'
                                            AND siteminder_room_type_time.in_date<=\''.Date_Time::to_orc_date($end_date).'\'
                                        ');
        foreach($room_type_time as $key=>$value){
            if(isset($siteminder_room_type[$value['siteminder_room_type_id']]['timeline'][$value['time']])){
                $siteminder_room_type[$value['siteminder_room_type_id']]['timeline'][$value['time']]['availability'] = $value['availability'];
                $siteminder_room_type[$value['siteminder_room_type_id']]['timeline'][$value['time']]['data_status'] = 1;
                if($value['availability']>0)
                    $siteminder_room_type[$value['siteminder_room_type_id']]['timeline'][$value['time']]['availability_bg'] = 'none';
            }
        }
        $room_rate_time = DB::fetch_all('
                                        SELECT
                                            siteminder_room_rate_time.*,
                                            siteminder_room_type.id as siteminder_room_type_id
                                        FROM
                                            siteminder_room_rate_time
                                            inner join siteminder_room_rate on siteminder_room_rate.id=siteminder_room_rate_time.siteminder_room_rate_id
                                            inner join siteminder_room_type on siteminder_room_type.id=siteminder_room_rate.siteminder_room_type_id
                                            inner join room_level on room_level.id=siteminder_room_type.room_level_id
                                        WHERE
                                            siteminder_room_type.portal_id=\''.PORTAL_ID.'\'
                                            AND siteminder_room_rate_time.in_date>=\''.Date_Time::to_orc_date($start_date).'\'
                                            AND siteminder_room_rate_time.in_date<=\''.Date_Time::to_orc_date($end_date).'\'
                                        ');
        foreach($room_rate_time as $key=>$value){
            $room_type_id = $value['siteminder_room_type_id'];
            $room_rate_id = $value['siteminder_room_rate_id'];
            if(isset($siteminder_room_type[$room_type_id]['child'][$room_rate_id]['timeline'][$value['time']])){
                $siteminder_room_type[$room_type_id]['child'][$room_rate_id]['timeline'][$value['time']]['rates'] = $value['rates'];
                $siteminder_room_type[$room_type_id]['child'][$room_rate_id]['timeline'][$value['time']]['data_status'] = 1;
            }
        }
        
        $room_rate_avail_time = DB::fetch_all('
                                        SELECT
                                            siteminder_rate_avail_time.*,
                                            siteminder_room_type.id as siteminder_room_type_id
                                        FROM
                                            siteminder_rate_avail_time
                                            inner join siteminder_room_rate on siteminder_room_rate.id=siteminder_rate_avail_time.siteminder_room_rate_id
                                            inner join siteminder_room_type on siteminder_room_type.id=siteminder_room_rate.siteminder_room_type_id
                                            inner join room_level on room_level.id=siteminder_room_type.room_level_id
                                        WHERE
                                            siteminder_room_type.portal_id=\''.PORTAL_ID.'\'
                                            AND siteminder_rate_avail_time.in_date>=\''.Date_Time::to_orc_date($start_date).'\'
                                            AND siteminder_rate_avail_time.in_date<=\''.Date_Time::to_orc_date($end_date).'\'
                                        ');
        foreach($room_rate_avail_time as $key=>$value){
            $room_type_id = $value['siteminder_room_type_id'];
            $room_rate_id = $value['siteminder_room_rate_id'];
            if(isset($siteminder_room_type[$room_type_id]['child'][$room_rate_id]['timeline'][$value['time']])){
                $siteminder_room_type[$room_type_id]['child'][$room_rate_id]['timeline'][$value['time']]['availability'] = $value['availability'];
                $siteminder_room_type[$room_type_id]['child'][$room_rate_id]['timeline'][$value['time']]['data_status'] = 1;
                if($value['availability']>0)
                    $siteminder_room_type[$room_type_id]['child'][$room_rate_id]['timeline'][$value['time']]['availability_bg'] = 'none';
            }
        }
        
        
        $room_rate_ota_time_rates = DB::fetch_all('
                                            SELECT
                                                siteminder_ota_rates_time.*,
                                                siteminder_room_rate.id as siteminder_room_rate_id,
                                                siteminder_room_type.id as siteminder_room_type_id
                                            FROM
                                                siteminder_ota_rates_time
                                                inner join siteminder_room_rate_ota on siteminder_room_rate_ota.id=siteminder_ota_rates_time.siteminder_room_rate_ota_id
                                                inner join siteminder_room_rate on siteminder_room_rate.id=siteminder_room_rate_ota.siteminder_room_rate_id
                                                inner join siteminder_room_type on siteminder_room_type.id=siteminder_room_rate.siteminder_room_type_id
                                                inner join room_level on room_level.id=siteminder_room_type.room_level_id
                                            WHERE
                                                siteminder_room_type.portal_id=\''.PORTAL_ID.'\'
                                                AND siteminder_ota_rates_time.in_date>=\''.Date_Time::to_orc_date($start_date).'\'
                                                AND siteminder_ota_rates_time.in_date<=\''.Date_Time::to_orc_date($end_date).'\'
                                            ');
        
        foreach($room_rate_ota_time_rates as $key=>$value){
            $room_type_id = $value['siteminder_room_type_id'];
            $room_rate_id = $value['siteminder_room_rate_id'];
            $room_rate_ota_id = $value['siteminder_room_rate_ota_id'];
            if(isset($siteminder_room_type[$room_type_id]['child'][$room_rate_id]['child'][$room_rate_ota_id]['timeline'][$value['time']])){
                $siteminder_room_type[$room_type_id]['child'][$room_rate_id]['child'][$room_rate_ota_id]['timeline'][$value['time']]['rates'] = $value['rates'];
                $siteminder_room_type[$room_type_id]['child'][$room_rate_id]['child'][$room_rate_ota_id]['timeline'][$value['time']]['data_status'] = 1;
            }
        }
        
        $room_rate_ota_time_stop_sell = DB::fetch_all('
                                            SELECT
                                                siteminder_ota_stop_sell_time.*,
                                                siteminder_room_rate.id as siteminder_room_rate_id,
                                                siteminder_room_type.id as siteminder_room_type_id
                                            FROM
                                                siteminder_ota_stop_sell_time
                                                inner join siteminder_room_rate_ota on siteminder_room_rate_ota.id=siteminder_ota_stop_sell_time.siteminder_room_rate_ota_id
                                                inner join siteminder_room_rate on siteminder_room_rate.id=siteminder_room_rate_ota.siteminder_room_rate_id
                                                inner join siteminder_room_type on siteminder_room_type.id=siteminder_room_rate.siteminder_room_type_id
                                                inner join room_level on room_level.id=siteminder_room_type.room_level_id
                                            WHERE
                                                siteminder_room_type.portal_id=\''.PORTAL_ID.'\'
                                                AND siteminder_ota_stop_sell_time.in_date>=\''.Date_Time::to_orc_date($start_date).'\'
                                                AND siteminder_ota_stop_sell_time.in_date<=\''.Date_Time::to_orc_date($end_date).'\'
                                            ');
        
        foreach($room_rate_ota_time_stop_sell as $key=>$value){
            $room_type_id = $value['siteminder_room_type_id'];
            $room_rate_id = $value['siteminder_room_rate_id'];
            $room_rate_ota_id = $value['siteminder_room_rate_ota_id'];
            if(isset($siteminder_room_type[$room_type_id]['child'][$room_rate_id]['child'][$room_rate_ota_id]['timeline'][$value['time']])){
                $siteminder_room_type[$room_type_id]['child'][$room_rate_id]['child'][$room_rate_ota_id]['timeline'][$value['time']]['stop_sell'] = $value['stop_sell'];
            }
        }
        
        $room_rate_ota_time_cta = DB::fetch_all('
                                            SELECT
                                                siteminder_ota_cta_time.*,
                                                siteminder_room_rate.id as siteminder_room_rate_id,
                                                siteminder_room_type.id as siteminder_room_type_id
                                            FROM
                                                siteminder_ota_cta_time
                                                inner join siteminder_room_rate_ota on siteminder_room_rate_ota.id=siteminder_ota_cta_time.siteminder_room_rate_ota_id
                                                inner join siteminder_room_rate on siteminder_room_rate.id=siteminder_room_rate_ota.siteminder_room_rate_id
                                                inner join siteminder_room_type on siteminder_room_type.id=siteminder_room_rate.siteminder_room_type_id
                                                inner join room_level on room_level.id=siteminder_room_type.room_level_id
                                            WHERE
                                                siteminder_room_type.portal_id=\''.PORTAL_ID.'\'
                                                AND siteminder_ota_cta_time.in_date>=\''.Date_Time::to_orc_date($start_date).'\'
                                                AND siteminder_ota_cta_time.in_date<=\''.Date_Time::to_orc_date($end_date).'\'
                                            ');
        
        foreach($room_rate_ota_time_cta as $key=>$value){
            $room_type_id = $value['siteminder_room_type_id'];
            $room_rate_id = $value['siteminder_room_rate_id'];
            $room_rate_ota_id = $value['siteminder_room_rate_ota_id'];
            if(isset($siteminder_room_type[$room_type_id]['child'][$room_rate_id]['child'][$room_rate_ota_id]['timeline'][$value['time']])){
                $siteminder_room_type[$room_type_id]['child'][$room_rate_id]['child'][$room_rate_ota_id]['timeline'][$value['time']]['cta'] = $value['cta'];
            }
        }
        
        $room_rate_ota_time_ctd = DB::fetch_all('
                                            SELECT
                                                siteminder_ota_ctd_time.*,
                                                siteminder_room_rate.id as siteminder_room_rate_id,
                                                siteminder_room_type.id as siteminder_room_type_id
                                            FROM
                                                siteminder_ota_ctd_time
                                                inner join siteminder_room_rate_ota on siteminder_room_rate_ota.id=siteminder_ota_ctd_time.siteminder_room_rate_ota_id
                                                inner join siteminder_room_rate on siteminder_room_rate.id=siteminder_room_rate_ota.siteminder_room_rate_id
                                                inner join siteminder_room_type on siteminder_room_type.id=siteminder_room_rate.siteminder_room_type_id
                                                inner join room_level on room_level.id=siteminder_room_type.room_level_id
                                            WHERE
                                                siteminder_room_type.portal_id=\''.PORTAL_ID.'\'
                                                AND siteminder_ota_ctd_time.in_date>=\''.Date_Time::to_orc_date($start_date).'\'
                                                AND siteminder_ota_ctd_time.in_date<=\''.Date_Time::to_orc_date($end_date).'\'
                                            ');
        
        foreach($room_rate_ota_time_ctd as $key=>$value){
            $room_type_id = $value['siteminder_room_type_id'];
            $room_rate_id = $value['siteminder_room_rate_id'];
            $room_rate_ota_id = $value['siteminder_room_rate_ota_id'];
            if(isset($siteminder_room_type[$room_type_id]['child'][$room_rate_id]['child'][$room_rate_ota_id]['timeline'][$value['time']])){
                $siteminder_room_type[$room_type_id]['child'][$room_rate_id]['child'][$room_rate_ota_id]['timeline'][$value['time']]['ctd'] = $value['ctd'];
            }
        }
        
        $room_rate_ota_time_min_stay = DB::fetch_all('
                                            SELECT
                                                siteminder_ota_min_stay_time.*,
                                                siteminder_room_rate.id as siteminder_room_rate_id,
                                                siteminder_room_type.id as siteminder_room_type_id
                                            FROM
                                                siteminder_ota_min_stay_time
                                                inner join siteminder_room_rate_ota on siteminder_room_rate_ota.id=siteminder_ota_min_stay_time.siteminder_room_rate_ota_id
                                                inner join siteminder_room_rate on siteminder_room_rate.id=siteminder_room_rate_ota.siteminder_room_rate_id
                                                inner join siteminder_room_type on siteminder_room_type.id=siteminder_room_rate.siteminder_room_type_id
                                                inner join room_level on room_level.id=siteminder_room_type.room_level_id
                                            WHERE
                                                siteminder_room_type.portal_id=\''.PORTAL_ID.'\'
                                                AND siteminder_ota_min_stay_time.in_date>=\''.Date_Time::to_orc_date($start_date).'\'
                                                AND siteminder_ota_min_stay_time.in_date<=\''.Date_Time::to_orc_date($end_date).'\'
                                            ');
        
        foreach($room_rate_ota_time_min_stay as $key=>$value){
            $room_type_id = $value['siteminder_room_type_id'];
            $room_rate_id = $value['siteminder_room_rate_id'];
            $room_rate_ota_id = $value['siteminder_room_rate_ota_id'];
            if(isset($siteminder_room_type[$room_type_id]['child'][$room_rate_id]['child'][$room_rate_ota_id]['timeline'][$value['time']])){
                $siteminder_room_type[$room_type_id]['child'][$room_rate_id]['child'][$room_rate_ota_id]['timeline'][$value['time']]['min_stay'] = $value['min_stay'];
            }
        }
        
        $room_rate_ota_time_max_stay = DB::fetch_all('
                                            SELECT
                                                siteminder_ota_max_stay_time.*,
                                                siteminder_room_rate.id as siteminder_room_rate_id,
                                                siteminder_room_type.id as siteminder_room_type_id
                                            FROM
                                                siteminder_ota_max_stay_time
                                                inner join siteminder_room_rate_ota on siteminder_room_rate_ota.id=siteminder_ota_max_stay_time.siteminder_room_rate_ota_id
                                                inner join siteminder_room_rate on siteminder_room_rate.id=siteminder_room_rate_ota.siteminder_room_rate_id
                                                inner join siteminder_room_type on siteminder_room_type.id=siteminder_room_rate.siteminder_room_type_id
                                                inner join room_level on room_level.id=siteminder_room_type.room_level_id
                                            WHERE
                                                siteminder_room_type.portal_id=\''.PORTAL_ID.'\'
                                                AND siteminder_ota_max_stay_time.in_date>=\''.Date_Time::to_orc_date($start_date).'\'
                                                AND siteminder_ota_max_stay_time.in_date<=\''.Date_Time::to_orc_date($end_date).'\'
                                            ');
        
        foreach($room_rate_ota_time_max_stay as $key=>$value){
            $room_type_id = $value['siteminder_room_type_id'];
            $room_rate_id = $value['siteminder_room_rate_id'];
            $room_rate_ota_id = $value['siteminder_room_rate_ota_id'];
            if(isset($siteminder_room_type[$room_type_id]['child'][$room_rate_id]['child'][$room_rate_ota_id]['timeline'][$value['time']])){
                $siteminder_room_type[$room_type_id]['child'][$room_rate_id]['child'][$room_rate_ota_id]['timeline'][$value['time']]['max_stay'] = $value['max_stay'];
            }
        }
        
        $this->map['items'] = $siteminder_room_type;
        $this->map['items_js'] = String::array2js($siteminder_room_type);
        
        //System::debug($siteminder_room_type);
        $this->parse_layout('list',$this->map);
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