<?php
class ReservePackageForm extends Form
{
	function ReservePackageForm()
	{
		Form::Form('ReservePackageForm');
	}
	function draw()
	{  
        $this->map = array();
        //$department = DB::fetch_all("SELECT id,name_1 as name FROM department WHERE parent_id=0 ORDER BY id");
       // $this->map['department_id_list'] = array(''=>Portal::language('All')) +  String::get_list($department);
        
        //1. tim dieu kien loc theo bo phan 
        $cond ='';
        $title =Portal::language('list_service_package ');
        switch(Url::get('cmd'))
        {
            case 'res':
            {
                $cond =" department.code='RES'";
                $title .=  Portal::language('restaurant ');
                break;
            }
            case 'spa':
            {
                $cond =" department.code='SPA'";
                $title .='Spa';
                break;
            }
            case 'vend':
            {
                $cond =" department.code='VENDING'";
                $title .=Portal::language('veding ');
                break;
            }
            case 'party':
            {
                $cond =" department.code='BANQUET'";
                $title .= Portal::language('banquet' );
                break;
            }
            case 'kar':
            {
                $cond =" department.code='KARAOKE'";
                $title .='Karaoke';
                break;
            }
        }
        $this->map['title'] = $title;
        $sql="SELECT  	package_sale_detail.id || '_' || reservation_room.id as id,
                    	reservation_room.arrival_time as in_date,
                    	reservation_room.reservation_id,
                    	reservation_room.id as reservation_room_id,
                    	package_sale.name,
                    	package_sale_detail.quantity,
                    	package_sale_detail.quantity_used,
                   	package_sale_detail.price,
                   	package_sale_detail.price*package_sale_detail.quantity as total_amount,
                   	package_sale_detail.note,
                   	reservation_room.status as status_reservation,
                    	room.name as room_name,
                    	package_sale_detail.id as package_sale_detail_id,
                    	reservation_room.id as reservation_room_id,
 		    	package_service.unit 
                FROM 	
			package_sale_detail
                	INNER JOIN package_sale ON package_sale.id=package_sale_detail.package_sale_id
                	INNER JOIN reservation_room ON reservation_room.package_sale_id=package_sale.id
                	INNER JOIN room ON room.id=reservation_room.room_id
               	 	INNER JOIN package_service ON package_service.id=package_sale_detail.service_id
                	INNER JOIN department ON package_service.department_id=department.id
			INNER JOIN package_service ON department.id = package_service.department_id                

                WHERE ".$cond."
                ORDER BY reservation_room.time_in desc";
        $items = DB::fetch_all($sql);
        
        
        $i = 1;
        foreach($items as $key=>$value)
        {
            $items[$key]['index'] = $i++;
            $items[$key]['in_date'] = Date_Time::convert_orc_date_to_date($value['in_date'],"/");
            $items[$key]['price'] = System::display_number($value['price']);
            $items[$key]['total_amount'] = System::display_number($value['total_amount']);
            $ids = explode("_",$value['id']);
            
            $package_id = $ids[0];
            $rr_id = $ids[1];
            
            switch(Url::get('cmd'))
            {
                case 'res':
                {
                    $sql="SELECT bar_reservation.id,bar_reservation.bar_id,bar_reservation.package_id,
                        bar_reservation_table.table_id,
                        bar_table.bar_area_id,
                        bar_reservation.reservation_room_id
                        FROM bar_reservation 
                        INNER JOIN bar_reservation_table ON bar_reservation.id=bar_reservation_table.bar_reservation_id
                        INNER JOIN bar_table ON bar_table.id=bar_reservation_table.table_id
                        WHERE bar_reservation.package_id=$package_id AND bar_reservation.reservation_room_id=$rr_id ";
                                    
                    $items[$key]['bars'] = DB::fetch_all($sql);
                    //giap.ln tinh so luong dat ban da su dung: cho 1 package va 1 reservation
                    $sql = "SELECT count(*) as num FROM bar_reservation WHERE package_id=$package_id AND reservation_room_id=$rr_id";
                    $num_res = DB::fetch($sql);
                    
                    $items[$key]['quantity_used'] = $num_res['num'];
                    if($items[$key]['quantity_used']==0)
                        $items[$key]['quantity_used'] ='';
                    //end giap.ln 
                    break;
                }
                case 'spa':
                {
                    $sql="SELECT massage_reservation_room.id,
                            massage_reservation_room.package_id,
                            massage_reservation_room.hotel_reservation_room_id
                            FROM massage_reservation_room
                            WHERE massage_reservation_room.package_id=$package_id AND massage_reservation_room.hotel_reservation_room_id=$rr_id";
                    $items[$key]['massages'] = DB::fetch_all($sql);
                    
                    //giap.ln tinh so luong package spa da su dung
                    $sql = "SELECT count(*) as num FROM massage_reservation_room WHERE package_id=$package_id AND hotel_reservation_room_id=$rr_id";
                    $num_spa = DB::fetch($sql);
                    
                    $items[$key]['quantity_used'] = $num_spa['num'];
                    if($items[$key]['quantity_used']==0)
                        $items[$key]['quantity_used'] ='';
                    //end giap.ln 
                    break;
                }
            }
        }
        //System::debug($items);
        $this->map['items'] = $items;
		$this->parse_layout('list',$this->map);
	}	
}
?>