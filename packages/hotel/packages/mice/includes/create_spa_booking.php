<?php
/*hàm trả mảng error.nếu mảng error rỗng thì book thành công ngược lại là lỗi.
Các tham số:
    - $info : thông tin chung
                + spa_parner_id             : đối tác
                + spa_sale_id               : saler 
                + note                      : ghi chú
                + net_price                 : thuế,phí : 1 hoặc 0
                + discount_percent          : giảm giá phần trăm
                + discount_amount           : giảm giá số tiền
                + tax_rate                  : thuế phí
                + service_rate              : phí dịch vụ
                + total_before_tax_service  :tổng tiền trước thuế phí
                + total_amount              :tổng tiền sau thuế phí
                + exchange_rate             :tỉ giá
                + contact_name              :tên người liên hệ
                + contact_phone             :điện thoại người liên hệ
                + contact_email             :email người liên hệ
                + portal_id                 :portal
                
    - $services: Mảng các dịch vụ
                +in_date                    :ngày    
                + price_id                  :id của bảng product_price_list mà có product là sản phẩm đã chọn
                + time_in_hour              : giờ vào 
                + time_out_hour             : giờ ra
                + spa_room_id               : id phòng  lấy trong bảng spa_room
                + staff_ids                 :chuỗi id nhân viên (id1,id2,id3,....)
                + customer_code             :mã KH lấy trong bảng spa_customer
                + customer_name             :tên khách hàng
                + quantity                  : số lượng 
                + edit_price                : sửa giá  
                + status                    :Trạng thái book
    - $prducts :mảng các Hàng hóa
                + in_date                   : ngày    
                + checkin_time              : giờ vào
                + checkout_time             : giờ ra 
                + price_id                  : id của bảng product_price_list mà có product là sản phẩm đã chọn
                + edit_price                : sửa giá  
                + amount                    :tổng tiền
                + quantity                  : số lượng
*/
 function create_spa_booking($mice_id,$info,$services,$prducts,$type){
    $error=array();
    require_once 'packages/hotel/packages/massage/modules/SpaOrder/db.php';
    $old_order_detail = array();
    //check config
    $stt_id = 0;    
    if($services){
        foreach($services as $key=>$record){
            if($record['time_in_hour']==''||strlen($record['time_in_hour'])>255){
                    array_push($error,'miss time in - '.$record['name'].'');
                }
                if($record['time_out_hour']==''||strlen($record['time_out_hour'])>255){
                    array_push($error,'miss time out - '.$record['name'].'');
                }
                if($record['quantity']==''||strlen($record['quantity'])>255){
                    array_push($error,'miss quantity');
                }
                if($record['price_id']==''||strlen($record['price_id'])>255){
                    array_push($error,'miss service code');
                }
            }
        }
        if($prducts){
            foreach($prducts as $key=>$record){
                 if($record['price_id']==''||strlen($record['price_id'])>255){
                    array_push($error,'miss product code ');
                } 
            }
            foreach($prducts as $key=>$record){
                 if($record['quantity']==''||strlen($record['quantity'])>255){
                    array_push($error,'miss quantity product');
                } 
            }
        }
        if(count($error)==0){
            $stt_id = 0;
            if($services){
            foreach($services as $key=>$record){
              $stt_id ++;
              $time_in = explode(':',$record['time_in_hour']);
              $time_out = explode(':',$record['time_out_hour']);
              $check_room[$stt_id]['id'] = $key;
              $check_room[$stt_id]['time_in'] = Date_Time::to_time($record['in_date'])+($time_in[0]*3600+$time_in[1]*60);
              $check_room[$stt_id]['time_out'] = Date_Time::to_time($record['in_date'])+($time_out[0]*3600+$time_out[1]*60);
              $check_room[$stt_id]['customer_code'] = $record['customer_code'];
              $check_room[$stt_id]['spa_room_id'] = $record['spa_room_id'];
              $checkin_time = Date_Time::to_time($record['in_date'])+($time_in[0]*3600+$time_in[1]*60);
              $checkout_time = Date_Time::to_time($record['in_date'])+($time_out[0]*3600+$time_out[1]*60);
              /** kiem tra phong co thuoc dich vu khong **/  
              $prduct_id=DB::fetch('select product_id from product_price_list where id='.$record['price_id'].''); 
              if($record['spa_room_id']!='' and !DB::fetch('select * from spa_room where spa_room.id = '.$record['spa_room_id'].' and spa_room.id in (select spa_room_id from spa_room_service where service_id = \''.$prduct_id['product_id'].'\')'))
                {
                    array_push($error,Portal::language('room_not_in_!'));
                }
                /** kiem tra nhan vien co thuoc dich vu khong **/
              if($record['staff_ids']!=''){
                    $arr = explode(',',$record['staff_ids']);
                    foreach($arr as $k=>$v){
                        if(!DB::fetch('select * from hrm_staff where hrm_staff.id='.$v.' and hrm_staff.id in (select spa_staff_id from spa_staff_service where service_id = \''.$prduct_id['product_id'].'\')')){
                            array_push($error,Portal::language('nhan_vien_'.DB::fetch('select last_name || \'\' || first_name as name from hrm_staff where id = '.$v.'','name').'_not_in_!'));
                        }
                    }
                }
                if($record['staff_ids'] != '') 
                {
                    $arr = explode(',',$record['staff_ids']);
                    foreach($arr as $k=>$v){
                        $order_config=DB::fetch_all('select spa_order_detail.* from spa_order_detail 
                                                inner join spa_order_detail_staff on spa_order_detail_staff.spa_order_detail_id = spa_order_detail.id
                                                where spa_order_detail_staff.hrm_staff_id = '.$v.' 
                                                and spa_order_detail.checkin_time<'.$checkout_time.'
                                                and spa_order_detail.checkout_time>'.$checkin_time.'
                                                and spa_order_detail.checkout_time is not null
                                                ');
                        $order = array();
                        foreach($order_config as $k1=>$v1)
                        {
                            if(!isset($order[$v1['spa_order_id']]))
                            {
                                array_push($error,'nhan vien '.DB::fetch('select last_name || \'\' || first_name as name from hrm_staff where id = '.$v.'','name').'xung_dot_thoi_gian');
                                $order[$v1['spa_order_id']] = $v1['spa_order_id'];
                            }
                                            
                        }
                    }
                }
                            
                /** check phong co khoang thoi gian config **/
                if($record['spa_room_id'] != '') 
                {
                    $order_config=DB::fetch_all('select * from spa_order_detail 
                                            where spa_room_id = '.$record['spa_room_id'].' 
                                            and checkin_time<'.$checkout_time.'
                                            and checkout_time>'.$checkin_time.'
                                            and spa_order_detail.checkout_time is not null
                                            ');
                    $order = array();
                    foreach($order_config as $k=>$v)
                    {
                        if(!isset($order[$v['spa_order_id']]))
                        {
                            array_push($error,'phong spa xung_dot_thoi_gian');
                            $order[$v['spa_order_id']] = $v['spa_order_id'];
                        }
                    }  
                }
                            /** check khách hàng có các kho?ng th?i gian trùng nhau **/
                            /*if($_REQUEST['customer_id'] != '') 
                            {
                                 $order_config=DB::fetch_all('select spa_order_detail.* from spa_order_detail 
                                            where spa_order_detail.spa_customer_id = '.$_REQUEST['customer_id'].' 
                                            and spa_order_detail.checkin_time<'.$checkout_time.'
                                            and spa_order_detail.checkout_time>'.$checkin_time.'
                                            and spa_order_detail.checkout_time is not null
                                            ');
                                $order = array();
                                foreach($order_config as $k=>$v)
                                {
                                    if(!isset($order[$v['spa_order_id']]))
                                    {
                                        $form->error('','customer_'.$_REQUEST['customer_first_name'].' '.$_REQUEST['customer_last_name'].' xung_dot_thoi_gian_<a target=\'_blank\' href=\'?page=spa_order&cmd=edit&id='.$v['spa_order_id'].'\'>Spa #'.$v['spa_order_id'].'</a>.',false);
                                        $order[$v['spa_order_id']] = $v['spa_order_id'];
                                    }
                                }  
                            }*/
                        
                    
                }
                /** check service detail co khoang thoi gian thoi gian trung nhau **/
                $check = false;
                $check_r = false;
                for($k=1;$k<$stt_id;$k++){
                    for($l=$k+1;$l<=$stt_id;$l++){
                        if( $check_room[$k]['customer_code']!=''&&$check_room[$l]['customer_code']!=''&& ($check_room[$k]['customer_code']==$check_room[$l]['customer_code']))
                        {
                            if(($check_room[$k]['time_in']>=$check_room[$l]['time_out']) OR ($check_room[$k]['time_out']<=$check_room[$l]['time_in'])){
                            }
                            else{
                                $check = true;
                            }
                        }
                        if($check_room[$k]['spa_room_id']!=''&&$check_room[$l]['spa_room_id']!=''&&($check_room[$k]['spa_room_id']==$check_room[$l]['spa_room_id']))
                        {
                            
                            if(($check_room[$k]['time_in']>=$check_room[$l]['time_out']) OR ($check_room[$k]['time_out']<=$check_room[$l]['time_in'])){
                            }
                            else{
                                $check_r = true;
                            }
                        }
                    }
                }
                if($check == true){
                   array_push($error,Portal::language('thoi gian các dich vu trung nhau !'));
               }
               if($check_r == true){
                   array_push($error,Portal::language('thoi gian cac phong trung nhau !'));
               }
               /** end check service detail co khoang thoi gian thoi gian trung nhau **/
            }
            
            if(count($error)==0){
                //exit();
                if(($services or $prducts)&& $type){
                    $info['total_before_tax_service']=$info['total_before_tax'];
                    unset($info['total_before_tax']);
                    $id = DB::insert('spa_order',$info+array('checkin_time'=>time(),'checkin_user'=>Session::get('user_id'),'mice_reservation_id'=>$mice_id));
                    //---------------------------------------insert service-------------------------------------------
              if($services!=''){
                    foreach($services as $key=>$record){
                        $record['spa_order_id'] = $id;
                        $record['price_id'] = $record['price_id'];
                        unset($record['product_id']);
                        unset($record['code']);
                        unset($record['name']);
                        unset($record['price']);
                        $time_in = 0;
            			$time_out = 0;
            			if($record['time_in_hour'] and $record['time_out_hour'])
            			{
            				$arr = explode(':',$record['time_in_hour']);
            				$time_in = Date_Time::to_time($record['in_date'])+ intval($arr[0])*3600+intval($arr[1])*60;
            				$arr = explode(':',$record['time_out_hour']);
            				$time_out = Date_Time::to_time($record['in_date'])+ intval($arr[0])*3600+intval($arr[1])*60;
            			}
                        $record['checkin_time'] = $time_in;
            			$record['checkout_time'] = $time_out;
                        unset($record['time_in_hour']);
            			unset($record['time_out_hour']);
            			unset($record['in_date']);
                        unset($record['spa_room_name']);
                        unset($record['staff_name']);
                        $staff_ids = $record['staff_ids'];
                        //$record['hrm_staff_id'] = Url::get('staff_id');
                        unset($record['staff_ids']);
                        $record['edit_price'] = System::calculate_number($record['edit_price']);
                        $amount=DB::fetch('select  price from product_price_list where id='.$record['price_id'].'');
                        $amount=$amount['price']*$record['quantity'];
                        if($record['customer_code']!='')
                        {
                            if($row=DB::fetch('select * from spa_customer where code = '.$record['customer_code']))
                            {
                            $customer_id = $row['id'];
                            }
                            else
                            {
                               $customer_id = DB::insert('spa_customer',array(
                                                            'first_name'=>$record['customer_first_name'],
                                                            'last_name'=>$record['customer_last_name'],
                                                            'code'=>$record['customer_code']
                                                            ));
                            }
                        }else{
                            if($record['customer_name']!=''){
                                $id_max = DB::fetch('select max(id) as ad from spa_customer');
                               $customer_id=  BD::insert('spa_customer',array('last_name'=>$record['customer_name'],'code'=>'CUS_'.($id_max['id']+1).''));
                            }
                        }
                        if(isset($customer_id)){
                        $record['spa_customer_id'] = $customer_id;    
                        }  
                        unset($record['customer_id']);
                        unset($record['customer_name']);
                        unset($record['customer_first_name']);
                        unset($record['customer_last_name']);
                        unset($record['customer_code']);
                        if($record['status']=='CHECKIN')
                        {
                            $record['checkin_user'] = Session::get('user_id');
                            $record['checkin_time'] = time();
                        }
                        unset($record['id']);
        				$order_detail_id=DB::insert('spa_order_detail',$record);
                        if($record['spa_room_id']!='')
                        {
                            DB::insert('spa_room_status',array('room_id'=>$record['spa_room_id'],'order_detail_id'=>$order_detail_id,'status'=>$record['status'],'start_time'=>$record['checkin_time'],'end_time'=>$record['checkout_time']));
                        }
                        if($staff_ids!='')
                        {
                            $arr = explode(',',$staff_ids);
                            //System::debug($arr);
                            //exit();
                            foreach($arr as $k=>$v)
                            {
                                DB::insert('SPA_ORDER_DETAIL_STAFF',array('spa_order_detail_id'=>$order_detail_id,'hrm_staff_id'=>$v));
                            }
                        }  
                    }
                }
                //---------------------------------------insert product-------------------------------------------
                if($prducts!=''){
                    foreach($prducts as $key=>$record){
                        $record['spa_order_id'] = $id;
                        unset($record['p_code']);
                        unset($record['product_id']);
                        unset($record['code']);
                        unset($record['in_date']);
                        unset($record['name']);
                        unset($record['price']);
                        unset($record['id']);
                        $record['checkin_time'] = Date_time::to_time(date('d/m/Y'));
            			$record['checkout_time'] = '';
                        $record['edit_price'] = System::calculate_number($record['edit_price']);
                        $record['amount'] = System::calculate_number($record['amount']);
                        //System::debug($record);exit();
        				DB::insert('spa_order_detail',$record);
                    }
               }
               if( !DB::exists('Select * from spa_order_detail where spa_order_id = '.$id ) )
                    DB::delete_id('spa_order',$id);
                /** xuat kho tu dong cho san pham **/
                require_once 'packages/hotel/includes/php/product.php';
                $warehouse_id = DB::fetch('Select * from portal_department where department_code = \'SPA\' and portal_id = \''.PORTAL_ID.'\' ','warehouse_id');
                if($warehouse_id['id'])
                {
                    DeliveryOrders::get_delivery_orders($id,'SPA',$warehouse_id);
                }
                return $error ;              
               } 
            else{
                return $error;
            } 
          }else{
            return $error;
      } 
}
}
/*
Hàm lấy ra các dịch vụ trả về mảng $services
*/
function get_service($spa_room_id=false,$spa_staff_id=false){
    $cond='';
    if($spa_room_id==''){
        $cond.=' and product.id in(select service_id from spa_room_service where spa_room_service.spa_room_id = '.$spa_room_id.'';
    }
    if($spa_staff_id==''){
        $cond.=' and product.id in (select service_id from spa_staff_service where spa_staff_service.spa_staff_id in ('.$spa_staff_id.'))';
    }
    $cond.=' AND product.type = \'SERVICE\'';
    $sql ="SELECT id,structure_id 
                    FROM product_category 
                    WHERE code='SPA_SERVICE'";
    $row = DB::fetch($sql);
    if($row)
    {
    $parent_structure_id = $row['structure_id'];
    $sql = "SELECT id,name 
        FROM product_category
        WHERE ".IDStructure::direct_child_cond($parent_structure_id,true); 
    $product_category = DB::fetch_all($sql);
    $str_ids = '';
    foreach($product_category as $value)
    {
        if($str_ids=='')
        $str_ids = $value['id'];
        else
        $str_ids .=','.$value['id'];
    }
    $cond.=' and product.category_id in ('.$str_ids.')';
    }
   return  $services = DB::fetch_all('
        select 
            product_price_list.id,
            product_price_list.product_id as code,
            product.name_'.Portal::language().' as name
				from
				product_price_list
                INNER JOIN product on product.id = product_price_list.product_id
				where
				(UPPER(product_price_list.product_id) LIKE \'%'.strtoupper(Url::sget('q')).'%\'
				OR ((LOWER(FN_CONVERT_TO_VN(product.name_2)) like \'%'.convert_utf8_to_latin(mb_strtolower(Url::sget('q'),'utf-8')).'%\' OR LOWER(FN_CONVERT_TO_VN(product.name_1)) like \'%'.convert_utf8_to_latin(mb_strtolower(Url::sget('q'),'utf-8')).'%\')))						
                AND product_price_list.portal_id=\''.PORTAL_ID.'\'
                AND product_price_list.department_code = \'SPA\' 
                --giap.ln hien thi ra nhung product trong khoang start_date, end_date
                AND (product_price_list.start_date is null OR (product_price_list.start_date is not null AND product_price_list.start_date<=\''.Date_Time::convert_time_to_ora_date(time()).'\'))
                AND (product_price_list.end_date is null OR (product_price_list.end_date is not null AND product_price_list.end_date>=\''.Date_Time::convert_time_to_ora_date(time()).'\'))
                --end giap.ln
                AND product.status = \'avaiable\'
                 '.$cond.'
				product_price_list.product_id
            ');    
}
/*
Hàm lấy ra các dịch vụ trả về mảng $product
*/
function get_product(){
    $cond='';
    $cond.=' AND product.type = \'GOODS\'';
    $sql ="SELECT id,structure_id 
                    FROM product_category 
                    WHERE code='SPA_PRODUCT'";
    $row = DB::fetch($sql);
    if($row)
    {
    $parent_structure_id = $row['structure_id'];
    $sql = "SELECT id,name 
        FROM product_category
        WHERE ".IDStructure::direct_child_cond($parent_structure_id,true); 
    $product_category = DB::fetch_all($sql);
    $str_ids = '';
    foreach($product_category as $value)
    {
        if($str_ids=='')
        $str_ids = $value['id'];
        else
        $str_ids .=','.$value['id'];
    }
    $cond.=' and product.category_id in ('.$str_ids.')';
    }
   return  $products = DB::fetch_all('
        select 
            product_price_list.id,
            product_price_list.product_id as code,
            product.name_'.Portal::language().' as name
				from
				product_price_list
                INNER JOIN product on product.id = product_price_list.product_id
				where
				(UPPER(product_price_list.product_id) LIKE \'%'.strtoupper(Url::sget('q')).'%\'
				OR ((LOWER(FN_CONVERT_TO_VN(product.name_2)) like \'%'.convert_utf8_to_latin(mb_strtolower(Url::sget('q'),'utf-8')).'%\' OR LOWER(FN_CONVERT_TO_VN(product.name_1)) like \'%'.convert_utf8_to_latin(mb_strtolower(Url::sget('q'),'utf-8')).'%\')))						
                AND product_price_list.portal_id=\''.PORTAL_ID.'\'
                AND product_price_list.department_code = \'SPA\' 
                --giap.ln hien thi ra nhung product trong khoang start_date, end_date
                AND (product_price_list.start_date is null OR (product_price_list.start_date is not null AND product_price_list.start_date<=\''.Date_Time::convert_time_to_ora_date(time()).'\'))
                AND (product_price_list.end_date is null OR (product_price_list.end_date is not null AND product_price_list.end_date>=\''.Date_Time::convert_time_to_ora_date(time()).'\'))
                --end giap.ln
                AND product.status = \'avaiable\'
                 '.$cond.'
				product_price_list.product_id
            ');    
}
/*
Hàm lấy ra các nhân viên trả về mảng $list_staff 
*/
function get_staff($service_id=false,$time_in_hour,$time_out_hour,$in_date){
       $time_start = explode(':',$time_in_hour);
       $time_end = explode(':',$time_out_hour);
       $start_time = Date_time::to_time($in_date)+($time_start[0]*3600+$time_start[1]*60);
       $end_time = Date_time::to_time($in_date)+($time_end[0]*3600+$time_end[1]*60);
       $cond='1>0 ';
       if($service_id!='')
       {
            $cond.=' and HRM_STAFF.id in (select spa_staff_id from spa_staff_service where service_id = \''.$service_id.'\')';
       }
       
       $sql='SELECT HRM_STAFF.first_name || HRM_STAFF.last_name || HRM_REGISTER_WORK_SHIFT.id as id,
                           HRM_STAFF.id as staff_id,
                           HRM_STAFF.code,
                           HRM_STAFF.first_name || HRM_STAFF.last_name as staff_name,
                           hrm_work_shift.name as work_shift_name,
                           nvl(HRM_REGISTER_WORK_SHIFT.time_in,hrm_work_shift.start_time) as start_hour,
                           nvl(HRM_REGISTER_WORK_SHIFT.time_out,hrm_work_shift.end_time) as end_hour
                   FROM
                           HRM_REGISTER_WORK_SHIFT
                           inner join HRM_STAFF on HRM_REGISTER_WORK_SHIFT.hrm_staff_id = hrm_staff.id  
                           inner join hrm_work_shift on HRM_REGISTER_WORK_SHIFT.hrm_work_shift_id = hrm_work_shift.id
                   WHERE
                           '.$cond.'
                           and hrm_work_shift.start_time <= \''.$time_in_hour.'\' and hrm_work_shift.end_time >= \''.$time_out_hour.'\'
                           and hrm_work_shift.start_time <= \''.$time_out_hour.'\' and hrm_work_shift.end_time >= \''.$time_out_hour.'\'
                           and  HRM_STAFF.hrm_department_id = \'SPA\'
                           and HRM_REGISTER_WORK_SHIFT.in_date = \''.Date_time::to_orc_date($in_date).'\'
                           order by HRM_STAFF.id
                           ';
       
       $list_staff=DB::fetch_all($sql);
       foreach($list_staff as $k=>$v)
       {
            if($v['start_hour']>$time_in_hour or $time_in_hour>=$v['end_hour'])
            {
                unset($list_staff[$k]);
            }
            if($v['start_hour']>=$time_out_hour or $time_out_hour>$v['end_hour'])
            {
                unset($list_staff[$k]);
            }
       }
       $sql='select 
                    spa_order_detail.id
                    ,spa_order_detail_staff.hrm_staff_id
                    ,spa_order_detail.checkin_time
                    ,spa_order_detail.checkout_time
             from 
                    spa_order_detail
                    inner join spa_order_detail_staff on spa_order_detail_staff.spa_order_detail_id = spa_order_detail.id
             where
                    from_unixtime(spa_order_detail.checkin_time) = \''.Date_time::to_orc_date($in_date).'\'
                    and spa_order_detail.checkout_time > '.$start_time.' 
                    and spa_order_detail.checkin_time < '.$end_time.'
                    order by spa_order_detail_staff.hrm_staff_id,spa_order_detail.checkin_time
       '; 
       $staff_config = DB::fetch_all($sql);   
       //System::debug($staff_detail);       
       foreach($list_staff as $k=>$v)
       {
           foreach($staff_config as $key=>$value)
           {
                if($v['staff_id']==$value['hrm_staff_id'])
                unset($list_staff[$k]);
           }
       }
    return $list_staff;
}
?>