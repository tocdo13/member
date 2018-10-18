<?php
  define( 'ROOT_PATH', strtr(dirname( __FILE__ ) ."/",array('\\'=>'/')));
    require_once 'packages/core/includes/system/config.php';
    //require_once 'packages/hotel/includes/php/product.php';    
    //require_once 'packages/hotel/packages/warehousing/includes/php/warehouse.php';        
    if(Url::get('q'))
    {
        if(Url::get('customer'))
        {
            $items = DB::fetch_all('
                select 
                    customer.code as id
                    ,customer.name
                from
                    customer
                where
                    UPPER(customer.name) LIKE \''.strtoupper(Url::sget('q')).'%\'
                    and customer.portal_id =\''.PORTAL_ID.'\'
                order by
                    customer.name
            ');
        //$items = String::get_list($items);
        foreach($items as $key=>$value){
            echo strtoupper($value['name']).'|'.$value['id']."\n";
        }
        }
        DB::close();
    }
    elseif(Url::get('rate_code')==1)
    {
        $is_rate = Url::get('is_rate');
        $room_level_id = Url::get('room_level_id');
        $customer_id = Url::get('customer_id');
        //lay ra khoang thoi gian khach o phong 
        $arrival_time =Date_Time::to_time(Url::get('arrival_time')) ;
        $departure_time = Date_Time::to_time(Url::get('departure_time'));
        
        //lay ra danh sach rate code theo nguon khach va hang phong 
        
        $sql ="SELECT rate_code_time.*,
                    
                    rate_room_level.price
                FROM    
                    rate_code 
                INNER JOIN rate_code_time ON rate_code_time.rate_code_id = rate_code.id    
                INNER JOIN rate_room_level ON rate_code_time.id=rate_room_level.rate_code_time_id
                INNER JOIN rate_customer_group ON rate_customer_group.rate_code_id=rate_code.id
                WHERE 
                    rate_room_level.room_level_id=$room_level_id
                    AND rate_customer_group.customer_id=$customer_id
                    AND ($departure_time>DATE_TO_UNIX(rate_code_time.start_date)
                    AND $arrival_time<=DATE_TO_UNIX(rate_code_time.end_date))
                    
                ORDER BY rate_code_time.priority asc";
        $items = DB::fetch_all($sql);
        $result = array();
        /***Tao mang array co dang cau truc: Voi moi ngay se lay ra don gia cho ngay do, 
             va ngay do co su dung rate code hay khong(is_rate_code))***/
        //lay ra muc gia chung khi co su dung rate code
        $price =0; 
        foreach($items as $key=>$value)
        {
            $price = $value['price'];
            break;
        }
        //$result['price_common'] = $price;
        $in_date = $arrival_time;
        //lay ra gia ngay dau tien khach o phong 
        $first_day = true;
        $i = 0;
        $j = 0;
        while($in_date<=$departure_time)
        {
            $date = date('d/m/Y',$in_date);
            $result[$date] = array();
            $result[$date]['in_date'] = $date;
            //lay ra muc gia, va co su dung rate code hay khong
            get_price_rate_code($arrival_time,$in_date,$items,$result);
            if($result[$date]['price']==0){
                $j++;
            }
            if($first_day)
            {
                $result['price_common'] = $result[$date]['price'];
                $result['is_rate_code'] = $result[$date]['is_rate_code'];                
                $first_day =false;
            }
            $in_date +=86400;
            $i++;
        }
        if($i==$j || $is_rate==0){
            unset($result);
            $result = array();
            $first_day = true;
            $sql = "SELECT * FROM room_level WHERE id=".Url::get('room_level_id');
            $price = DB::fetch($sql);
            $price = $price['price'];
            $in_date = $arrival_time;
           while($in_date<=$departure_time)
            {
                $date = date('d/m/Y',$in_date);
                $result[$date] = array();
                $result[$date]['in_date'] = $date; 
                $result[date('d/m/Y',$in_date)]['is_rate_code'] = 0;
                $result[date('d/m/Y',$in_date)]['price'] = $price;
                if($first_day)
                {
                    $result['price_common'] = $result[$date]['price'];
                    $result['is_rate_code'] = $result[$date]['is_rate_code'];                    
                    $first_day =false;
                }
                
                $in_date +=86400;
            }
        }
        //System::debug($result); exit();
        echo json_encode($result);
        
        DB::close();
           
    }
    else if(Url::get('cmd') && Url::get('cmd')=='get_room_level_price')
    {
        $sql = "SELECT * FROM room_level WHERE id=".Url::get('room_level_id');
        $price = DB::fetch($sql);
        $price = $price['price'];
        echo $price;
    }
    
    function get_price_rate_code($arrival_time,$in_date,$items,&$result)
    {
        $is_rate_code = false;
        $is_max_priority = false;
        
        foreach($items as $key=>$value)
        {
            $start_date = Date_Time::to_time(Date_Time::convert_orc_date_to_date($value['start_date'],"/"));
            $end_date = Date_Time::to_time(Date_Time::convert_orc_date_to_date($value['end_date'],"/"));
            if($is_rate_code!=$value['id'])
            {
                if($in_date>=$start_date && $in_date<=$end_date)//kiem tra trong khoang thoi gian
                {
                    
                    if($is_max_priority<$value['priority'] ||$is_max_priority===false)//kiem tra muc uu tien 
                    {
                        $frequency = $value['frequency'];
                        switch($frequency)
                        {
                            case 'd':
                                $is_rate_code = $value['id'];
                                $is_max_priority = $value['priority'];
                                break;
                            case 'w':
                                if(check_date_in_week($value['weekly'],$in_date))
                                {
                                    $is_rate_code = $value['id'];
                                    $is_max_priority = $value['priority'];
                                }
                                break;
                        }
                        
                    }
                }
            }
          }
        
        
        if($is_rate_code!=false)
        {
            $result[date('d/m/Y',$in_date)]['is_rate_code'] = $is_rate_code;
            $result[date('d/m/Y',$in_date)]['price'] = $items[$is_rate_code]['price'];
        }
        else {
            $result[date('d/m/Y',$in_date)]['is_rate_code'] = 0;
            $result[date('d/m/Y',$in_date)]['price'] = 0; 
        }
    }
    function check_date_in_week($weekly,$in_date)
    {
        $arr = explode(",",$weekly);
        //lay ra thu trong tuan 
        $jd=cal_to_jd(CAL_GREGORIAN,date('m',$in_date),date('d',$in_date),date('Y',$in_date));
        $day=jddayofweek($jd,0);
        if($day==0)
            $day =1;
        else
            $day++;
        if(in_array($day,$arr))
            return true;
        return false;
    }
?>
