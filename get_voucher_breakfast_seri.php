<?php
    date_default_timezone_set('Asia/Saigon');//Define default time for global system
    define( 'ROOT_PATH', strtr(dirname( __FILE__ ) ."/",array('\\'=>'/')));
    require_once 'packages/core/includes/system/config.php';
          
    $str_date = Url::get('str_date');
    
    $arr_data = Url::get('arr_data');       
            
    $sql = "SELECT * FROM voucher_breakfast WHERE to_char(in_date,'DD/MM/YYYY') IN(".$str_date.") ORDER BY voucher_breakfast.reservation_room_id,id";
    $result = DB::fetch_all($sql);

    $array_count_old_data = array();
    
    foreach($result as $key=>$value)
    {
        if(!isset($array_count_old_data[$value['reservation_room_id']]))
        {
            $array_count_old_data[$value['reservation_room_id']] = array();
            if(!isset($array_count_old_data[$value['reservation_room_id']][Date_Time::convert_orc_date_to_date($value['in_date'],"/")]))
            {
               $array_count_old_data[$value['reservation_room_id']][Date_Time::convert_orc_date_to_date($value['in_date'],"/")] = array();
               $array_count_old_data[$value['reservation_room_id']][Date_Time::convert_orc_date_to_date($value['in_date'],"/")]['adult'] = 0;
               $array_count_old_data[$value['reservation_room_id']][Date_Time::convert_orc_date_to_date($value['in_date'],"/")]['child'] = 0;                              
               if($value['is_child']==0)
               {
                  $array_count_old_data[$value['reservation_room_id']][Date_Time::convert_orc_date_to_date($value['in_date'],"/")]['adult']++;
               }
               else
               {
                  $array_count_old_data[$value['reservation_room_id']][Date_Time::convert_orc_date_to_date($value['in_date'],"/")]['child']++;
               }
            }
        }
        else
        {
            if(!isset($array_count_old_data[$value['reservation_room_id']][Date_Time::convert_orc_date_to_date($value['in_date'],"/")]))
            {
               $array_count_old_data[$value['reservation_room_id']][Date_Time::convert_orc_date_to_date($value['in_date'],"/")] = array();
               $array_count_old_data[$value['reservation_room_id']][Date_Time::convert_orc_date_to_date($value['in_date'],"/")]['adult'] = 0;
               $array_count_old_data[$value['reservation_room_id']][Date_Time::convert_orc_date_to_date($value['in_date'],"/")]['child'] = 0; 
               if($value['is_child']==0)
               {
                  $array_count_old_data[$value['reservation_room_id']][Date_Time::convert_orc_date_to_date($value['in_date'],"/")]['adult']++;
               }
               else
               {
                  $array_count_old_data[$value['reservation_room_id']][Date_Time::convert_orc_date_to_date($value['in_date'],"/")]['child']++;
               }
            }
            else
            {
               if($value['is_child']==0)
               {
                  $array_count_old_data[$value['reservation_room_id']][Date_Time::convert_orc_date_to_date($value['in_date'],"/")]['adult']++;
               }
               else
               {
                  $array_count_old_data[$value['reservation_room_id']][Date_Time::convert_orc_date_to_date($value['in_date'],"/")]['child']++;
               }
            }
        }
    }
    //$sql = "SELECT voucher_breakfast.reservation_room_id as id FROM voucher_breakfast WHERE to_char(in_date,'DD/MM/YYYY') IN(".$str_date.") ORDER BY voucher_breakfast.reservation_room_id";
    //$old_data = DB::fetch_all($sql);
    //System::debug($array_count_old_data); //exit();
    $id_temp = "";
    foreach($arr_data as $k=>$v)
    {
        
        $v['date'] = trim($v['date'],"'");
        $date_cut = str_replace("/","",$v['date']);
        
        $str_date_temp = Date_Time::to_orc_date($v['date']);
        
        $sql = "SELECT MAX(voucher_id) as id FROM voucher_breakfast WHERE in_date='".$str_date_temp."'";
        $max_id = DB::fetch($sql);
        
        $start_id = $max_id['id']==null ? 1 : $max_id['id']+1;
        
        $barcode = $date_cut.str_pad($start_id,5,"0",STR_PAD_LEFT);
        if(empty($array_count_old_data))
        {
            $id = DB::insert("voucher_breakfast", array("voucher_id"=>$start_id,"barcode"=>$barcode,"in_date"=>$str_date_temp,"reservation_room_id"=>$v['reservation_room_id'],"is_child"=>$v['is_child'],"guest_name"=>$v['guest_name'],"date_use"=>$str_date_temp));
            $arr_data[$k]['barcode'] = $barcode;
            $arr_data[$k]['no'] = str_pad($start_id,5,"0",STR_PAD_LEFT);      
            //System::debug(1);               
        }
        else
        {
            if(!isset($array_count_old_data[$v['reservation_room_id']][$v['date']]))
            {
                //System::debug($v['reservation_room_id']);
                //System::debug($v['date']);
                $id = DB::insert("voucher_breakfast", array("voucher_id"=>$start_id,"barcode"=>$barcode,"in_date"=>$str_date_temp,"reservation_room_id"=>$v['reservation_room_id'],"is_child"=>$v['is_child'],"guest_name"=>$v['guest_name'],"date_use"=>$str_date_temp));
                $arr_data[$k]['barcode'] = $barcode;
                $arr_data[$k]['no'] = str_pad($start_id,5,"0",STR_PAD_LEFT);
                //System::debug(2);
            }
            else
            { 
               if($v['is_child']==0)
               { 
                   if($array_count_old_data[$v['reservation_room_id']][$v['date']]['adult']==0)
                   {
                        $id = DB::insert("voucher_breakfast", array("voucher_id"=>$start_id,"barcode"=>$barcode,"in_date"=>$str_date_temp,"reservation_room_id"=>$v['reservation_room_id'],"is_child"=>$v['is_child'],"guest_name"=>$v['guest_name'],"date_use"=>$str_date_temp));
                        $arr_data[$k]['barcode'] = $barcode;
                        $arr_data[$k]['no'] = str_pad($start_id,5,"0",STR_PAD_LEFT);
                        //System::debug(3);
                   }
                   else
                   {
                        $check = true;
                        foreach($result as $key=>$value)
                        {
                          //System::debug("=============");   
//                           System::debug($value['reservation_room_id']);   
//                           System::debug($v['reservation_room_id']);  
//                           System::debug($value['in_date']);  
//                           System::debug($v['date']);  
//                           System::debug($value['is_child']);  
//                           System::debug($v['is_child']);  
//                           System::debug($value['guest_name']);  
//                           System::debug($v['guest_name']);  
//                          System::debug("=============");                              
                          if($value['reservation_room_id'] == $v['reservation_room_id'] && Date_Time::convert_orc_date_to_date($value['in_date'],"/") == $v['date'] && $v['is_child'] == $value['is_child'] && $v['guest_name'] == $value['guest_name'])
                          {
                            if(strpos((string) $id_temp, (string) $key)===false)
                            {
                                $arr_data[$k]['barcode'] = $value['barcode'];
                                $arr_data[$k]['no'] = str_pad($value['voucher_id'],5,"0",STR_PAD_LEFT);
                                $id_temp.=($key.",");
                                $array_count_old_data[$value['reservation_room_id']][$v['date']]['adult']--;
                                //System::debug(4);
                                $check = false;
                                DB::update("voucher_breakfast",array("reprint"=>$value['reprint']+1)," barcode='".$value['barcode']."' AND id=".$value['id']);
                                $arr_data[$k]['reprint'] = $value['reprint']+1;
                                break;
                            }
                          }
                       }
                       if($check)  // Check truong hop doi ten khach
                       {
                            $id = DB::insert("voucher_breakfast", array("voucher_id"=>$start_id,"barcode"=>$barcode,"in_date"=>$str_date_temp,"reservation_room_id"=>$v['reservation_room_id'],"is_child"=>$v['is_child'],"guest_name"=>$v['guest_name'],"date_use"=>$str_date_temp));
                            $arr_data[$k]['barcode'] = $barcode;
                            $arr_data[$k]['no'] = str_pad($start_id,5,"0",STR_PAD_LEFT);
                       } 
                   }
               }
               else
               {
                   if($array_count_old_data[$v['reservation_room_id']][$v['date']]['child']==0)
                   {
                        $id = DB::insert("voucher_breakfast", array("voucher_id"=>$start_id,"barcode"=>$barcode,"in_date"=>$str_date_temp,"reservation_room_id"=>$v['reservation_room_id'],"is_child"=>$v['is_child'],"guest_name"=>$v['guest_name'],"date_use"=>$str_date_temp));
                        $arr_data[$k]['barcode'] = $barcode;
                        $arr_data[$k]['no'] = str_pad($start_id,5,"0",STR_PAD_LEFT);
                        //System::debug(5);
                   }
                   else
                   {
                        $check = true;
                        foreach($result as $key=>$value)
                        {
                          if($value['reservation_room_id'] == $v['reservation_room_id'] && Date_Time::convert_orc_date_to_date($value['in_date'],"/") == $v['date'] && $v['is_child'] == $value['is_child'] && $v['guest_name'] == $value['guest_name'])
                          {
                            if(strpos((string) $id_temp, (string) $key)===false)
                            {
                                $arr_data[$k]['barcode'] = $value['barcode'];
                                $arr_data[$k]['no'] = str_pad($value['voucher_id'],5,"0",STR_PAD_LEFT);
                                $id_temp.=($key.",");
                                $array_count_old_data[$value['reservation_room_id']][$v['date']]['child']--;
                                //System::debug(6);
                                DB::update("voucher_breakfast",array("reprint"=>$value['reprint']+1)," barcode='".$value['barcode']."' AND id=".$value['id']);
                                $arr_data[$k]['reprint'] = $value['reprint']+1;
                                break;
                            }
                          }
                       }
                       if($check) // Check truong hop doi ten khach
                       {
                            $id = DB::insert("voucher_breakfast", array("voucher_id"=>$start_id,"barcode"=>$barcode,"in_date"=>$str_date_temp,"reservation_room_id"=>$v['reservation_room_id'],"is_child"=>$v['is_child'],"guest_name"=>$v['guest_name'],"date_use"=>$str_date_temp));
                            $arr_data[$k]['barcode'] = $barcode;
                            $arr_data[$k]['no'] = str_pad($start_id,5,"0",STR_PAD_LEFT);
                       }  
                   }
               } 
            }
        }
    }
    //System::debug($array_count_old_data); exit();
    echo json_encode($arr_data);
    
?>
