<?php
    define( 'ROOT_PATH', strtr(dirname( __FILE__ ) ."/",array('\\'=>'/')));
    set_include_path(ROOT_PATH);
    require_once 'packages/core/includes/system/config.php';
    
    function get_amenities_used()
    {
        $status_auto='';
        $status_auto= DB::fetch('SELECT id from amenities_used where status_auto =1 and create_date =\''.Date_Time::to_orc_date(date('d/m/Y')).'\' ','id');
           
        if($status_auto==''){
            $row = array(
                        'portal_id'=>PORTAL_ID,
                        'user_id'=>User::id(),
                        'time'=>time(),
                        'create_date'=>Date_Time::to_orc_date(date('d/m/Y')) ,
                        'note'=>'',
                        'STATUS_AUTO'=>1
         );
          $amen_used_id=DB::insert('amenities_used',$row); 
             
        }else{
            $amen_used_id =$status_auto;         
           // DB::delete('delete * from amenities_used_detail where amenities_used_id="'.$amen_used_id.'"');
            DB::delete( 'amenities_used_detail', ' amenities_used_id = '.$amen_used_id );
           
        }
        //code by nguyen hoang nam 
        $sql = 'select  ra.id,ra.product_id,ra.norm_quantity,tmp1.room_id,tmp1.nums,(ra.norm_quantity*tmp1.nums) as tong
                    from (
                    select room_id ,count(room_id)as nums
                    from(
                        select
                        rs.id ,rs.status as rs_status,rs.reservation_room_id,rs.in_date,
                            rr.reservation_id,
                            rr.status,rr.room_id,rr.time_in,rr.time_out,rr.arrival_time,rr.departure_time
                            from room_status rs
                            left join  reservation_room  rr on rs.reservation_room_id=rr.id
                            where  rs.status=\'OCCUPIED\' and rs.in_date=\''.Date_Time::to_orc_date(date('d/m/Y')).'\'
                            and rs.id not in (
                                  select rs.id 
                                  from room_status rs
                                  left join  reservation_room  rr on rs.reservation_room_id=rr.id
                                  where  rs.status=\'OCCUPIED\' and rr.arrival_time<\''.Date_Time::to_orc_date(date('d/m/Y')).'\'
                                   and rr.departure_time= \''.Date_Time::to_orc_date(date('d/m/Y')).'\'
                                   and rs.in_date=\''.Date_Time::to_orc_date(date('d/m/Y')).'\' 
                            )
                         ) tmp 
                        group by room_id
                     ) tmp1 inner join room_amenities ra on tmp1.room_id = ra.room_id  
                     order by room_id'; 
                                  
         $room_aminities=DB::fetch_all($sql);
        
         foreach($room_aminities as $key=>$room_a){
            
             $record = array(
                            'amenities_used_id'=>$amen_used_id,
                            'portal_id'=>PORTAL_ID,
                            'room_id'=>$room_a['room_id'],
                            'product_id'=>$room_a['product_id'],
                            'quantity'=>$room_a['tong']
                            );
                DB::insert('amenities_used_detail',$record);
         }
              
                 //Nếu không còn detail thì xóa bản ghi chính
            if(!DB::exists('Select * from amenities_used_detail where amenities_used_id = '.$amen_used_id ) )
            {
                $check=0;
               // echo $amen_used_id.'--';
               DB::delete_id('amenities_used',$amen_used_id);
            } 
            else//Neu ton tai thi tao phieu xuat
            {
                //start: KID SUA DE TU DONG PHIEU XUAT
                require_once 'packages/hotel/includes/php/product.php';
                $warehouse_id = DB::fetch('Select * from portal_department where department_code = \'AMENITIES\' and portal_id = \''.PORTAL_ID.'\' ','warehouse_id');
              // echo $warehouse_id.'----'.$amen_used_id;
                DeliveryOrders::get_delivery_orders($amen_used_id,'AMENITIES',$warehouse_id);
                $check=1;
                //end
            }
         
           
      return $check;
    }
    /** trả về dữ liệu cho hàm gọi **/
    switch($_REQUEST['data'])
    {
        case "get_amenities_used":
        {
            
            echo json_encode(get_amenities_used()); break;
        }
        default: echo '';break;
    }
    
    function calc_time($string)
    {
        $arr = explode(':',$string);
        return $arr[0]*3600 + $arr[1]*60;
    }
    
?>