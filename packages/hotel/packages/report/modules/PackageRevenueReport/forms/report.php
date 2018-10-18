<?php
class PackageRevenueReportForm extends Form
{
	function PackageRevenueReportForm()
	{
		Form::Form('PackageRevenueReportForm');
        $this->link_js('packages/core/includes/js/jquery/jquery.maskedinput.js');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
    	$this->link_js('packages/core/includes/js/jquery/datepicker.js');     
	}
	function draw()
	{
        require_once 'packages/core/includes/utils/time_select.php';
		require_once 'packages/core/includes/utils/lib/report.php';
        $this->map = array();
        
        //giap.ln thuc hien t?o lap dieu kien tim kiem
        $cond ='1=1';
        if(Url::get('package_sale_id'))
        {
            $cond .=' AND package_sale.id='.Url::get('package_sale_id');
        } 
        if(Url::get('customer_name'))
        {
            $cond .=' AND customer.name LIKE \'%'.Url::get('customer_name').'%\'';
        }
        
        if(Url::get('recode'))
        {
            $cond .=" AND reservation_room.reservation_id=".Url::get('recode');
        }
        
        if(Url::get('room_id'))
        {
            $cond .=" AND reservation_room.room_id=".Url::get('room_id');
        }
        
        if(Url::get('from_date'))
        {
            $dates = explode("/",Url::get('from_date'));
            $from_date = mktime(0,0,0,$dates[1],$dates[0],$dates[2]);
            $cond .=" AND date_to_unix(reservation_room.arrival_time)>=$from_date";
        }
        
        if(Url::get('to_date'))
        {
            $dates = explode("/",Url::get('to_date'));
            $to_date = mktime(0,0,0,$dates[1],$dates[0],$dates[2]);
            $cond .=" AND date_to_unix(reservation_room.departure_time)<=$to_date";
        }
        //end tao lap dieu kien tim kiem 
        
        $sql="SELECT reservation_room.id || '_' || reservation_traveller.id as id,
                reservation_room.id as reservation_room_id,
                package_sale.name as package_name,
                customer.name as customer_name,
                reservation.id as reservation_id,
                room.name as room_name,
                traveller.first_name || ' ' || traveller.last_name as traveller_name,
                reservation_room.arrival_time,
                reservation_room.departure_time,
                package_sale.total_amount,
                package_sale.code as package_code
                
                FROM reservation_room 
                INNER JOIN reservation ON reservation.id=reservation_room.reservation_id
                LEFT JOIN customer ON customer.id=reservation.customer_id
                INNER JOIN package_sale ON package_sale.id=reservation_room.package_sale_id
                INNER JOIN room ON room.id=reservation_room.room_id
                INNER JOIN room_level ON room.room_level_id=room_level.id
                LEFT JOIN reservation_traveller ON reservation_traveller.reservation_room_id=reservation_room.id
                LEFT OUTER JOIN traveller ON traveller.id=reservation_traveller.traveller_id
                WHERE ".$cond."
                AND room_level.is_virtual!=1
                ORDER BY  package_sale.code,customer_name,reservation_id,reservation_room_id,room.name,reservation_room.arrival_time desc";
                
        $items = DB::fetch_all($sql);
        
        $i =1;
        $is_row_span_package = false;
        $key_package_code = false;
        
        $is_row_span_customer = false;
        $key_customer = false;
        
        $is_row_span_recode = false;
        $key_recode = false;
        
        $is_row_span_room = false;
        $key_room = false;
        
        
        
        foreach($items as $key=>$value)
        {
            
            $items[$key]['arrival_time'] = Date_Time::convert_orc_date_to_date($value['arrival_time'],"/");
            $items[$key]['departure_time'] = Date_Time::convert_orc_date_to_date($value['departure_time'],"/");
            $items[$key]['total_amount'] = System::display_number($value['total_amount']);
            if($is_row_span_package!=$value['package_code'])
            {
                $key_package_code = $key;
                $is_row_span_package = $value['package_code'];
                $items[$key]['index'] = $i++;
                $items[$key_package_code]['row_span_package'] = 1;
                
                $is_row_span_customer = $value['customer_name'];
                $key_customer = $key;
                $items[$key_customer]['row_span_customer'] = 1;
                
                $is_row_span_recode = $value['reservation_id'];
                $key_recode = $key;
                $items[$key_recode]['row_span_recode'] = 1;
                
                $is_row_span_room = $value['reservation_room_id'];
                $key_room = $key;
                $items[$key_room]['row_span_room'] = 1;
                
            }
            else
            {
                if($is_row_span_customer!=$value['customer_name'])
                {
                    $is_row_span_customer = $value['customer_name'];
                    $key_customer = $key;
                    $items[$key_customer]['row_span_customer'] = 1;
                    
                    $is_row_span_recode = $value['reservation_id'];
                    $key_recode = $key;
                    $items[$key_recode]['row_span_recode'] = 1;
                    
                    $is_row_span_room = $value['reservation_room_id'];
                    $key_room = $key;
                    $items[$key_room]['row_span_room'] = 1;
                }
                else
                {
                    $items[$key_customer]['row_span_customer']++;
                    $items[$key]['row_span_customer'] = 0;
                    
                    if($is_row_span_recode!=$value['reservation_id'])
                    {
                        $is_row_span_recode = $value['reservation_id'];
                        $key_recode = $key;
                        $items[$key_recode]['row_span_recode'] = 1;
                        
                        $is_row_span_room = $value['reservation_room_id'];
                        $key_room = $key;
                        $items[$key_room]['row_span_room'] = 1;
                    }
                    else
                    {
                        $items[$key_recode]['row_span_recode']++;
                        $items[$key]['row_span_recode'] = 0;
                        if($is_row_span_room!=$value['reservation_room_id'])
                        {
                            $is_row_span_room = $value['reservation_room_id'];
                            $key_room = $key;
                            $items[$key_room]['row_span_room'] = 1;
                        }
                        else
                        {
                            $items[$key_room]['row_span_room'] ++;
                            $items[$key]['row_span_room'] = 0;
                        }
                    }
                }
                $items[$key_package_code]['row_span_package'] ++;
                $items[$key]['row_span_package'] = 0;
            }
        }
        
        
        $this->map['items'] = $items;
        
        //giap.ln chuc nang thong ke tom tat goi package
        $sql ="SELECT package_sale.id,
                package_sale.code,
                package_sale.name,
                package_sale.total_amount as price,
                count(reservation_room.id) as num_rr_id
                
                FROM package_sale
                INNER JOIN reservation_room ON reservation_room.package_sale_id=package_sale.id
                INNER JOIN room ON room.id=reservation_room.room_id
                INNER JOIN room_level ON room_level.id=room.room_level_id
                INNER JOIN reservation ON reservation.id=reservation_room.reservation_id
                LEFT JOIN customer ON customer.id=reservation.customer_id
                
                WHERE ".$cond."
                AND room_level.is_virtual!=1
                GROUP BY package_sale.id,package_sale.code,package_sale.name,package_sale.total_amount";
        $package_summary  = DB::fetch_all($sql);
        
        $index = 1;
        $total_amount = 0;
        foreach($package_summary as $key=>$value)
        {
            $package_summary[$key]['index'] = $index++; 
            $package_summary[$key]['total_amount'] = $value['price']*$value['num_rr_id'];
            $package_summary[$key]['price'] = System::display_number($value['price']);
            $total_amount +=$package_summary[$key]['total_amount']; 
             
            $package_summary[$key]['total_amount'] = System::display_number($package_summary[$key]['total_amount']);
        } 
        $this->map['package_summary'] = $package_summary;
        $this->map['total_amount'] = System::display_number($total_amount);
        //end thong ke tom tat 
        
        //giap.ln hien thi danh sach goi package
        $package_options = '<option value="0">'.Portal::language('all').'</option>';
        $packages = DB::fetch_all("SELECT id,name FROM package_sale ORDER BY name");
        foreach($packages as $key=>$value)
        {
            if(Url::get('package_sale_id') && $value['id']==Url::get('package_sale_id'))
            {
                $package_options .="<option value='".$value['id']."' selected='selected'>".$value['name']."</option>";
            }
            else
            {
                $package_options .="<option value='".$value['id']."'>".$value['name']."</option>";
            }
        }
        $this->map['package_options'] = $package_options;
        //end hien thi danh sach goi pacakge 
        
        //giap.ln hien thi danh sach phong
        $room_options = '<option value="0">'.Portal::language('all').'</option>';
        $rooms = DB::fetch_all("SELECT room.id,room.name FROM room INNER JOIN room_level ON room.room_level_id=room_level.id  WHERE room.portal_id='".PORTAL_ID."' AND room_level.is_virtual!=1 ORDER BY room.name");
        foreach($rooms as $key=>$value)
        {
            if(Url::get('room_id') && Url::get('room_id')==$value['id'])
            {
                $room_options .='<option value="'.$value['id'].'" selected="selected">'.$value['name'].'</option>';
            }
            else
            {
                $room_options .='<option value="'.$value['id'].'">'.$value['name'].'</option>';
            }
        }
        $this->map['room_options'] = $room_options;
        //end hien thi danh sach phong 
        $this->parse_layout('report',$this->map);
	}
	
   
}
?>
