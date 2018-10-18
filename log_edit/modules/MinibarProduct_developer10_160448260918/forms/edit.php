<?php
class EditMinibarProductForm extends Form
{
	function EditMinibarProductForm()
	{
		Form::Form('EditMinibarProductForm');
		$this->link_css(Portal::template('hotel').'/css/room.css');
        $this->add('minibar_product.product_id',new TextType(true,'invalid_product_id',0,255));
		//$this->add('minibar_product.norm_quantity',new FloatType(true,'invalid_norm_quantity','0','100000000000'));
		$this->link_js('packages/core/includes/js/multi_items.js');
		$this->link_css('packages/core/skins/default/css/jquery.autocomplete.css');
		$this->link_js('packages/core/includes/js/jquery/jquery.autocomplete.js');
        @$this->link_js('cache/data/'.strtolower(str_replace('#','',PORTAL_ID)).'/MINIBAR_'.strtolower(str_replace('#','',PORTAL_ID)).'.js?v='.time());
	}
	function on_submit()
	{
	   if(Url::get('deleteall')==1){
	       DB::delete('minibar_product','1=1');
	   }
       $minibar=DB::fetch_all('select room_id as id,id as minibar_id from minibar');
	   if(isset($_REQUEST['mi_room_amenities']))
		{
            $rooms=explode(',',Url::get('rooms'));
			foreach($_REQUEST['mi_room_amenities'] as $key=>$record)
			{
                $record['quantity'] = isset($record['quantity'])?$record['quantity']:0;
				$record['portal_id'] = PORTAL_ID;
				$record['in_date'] = Date_Time::to_orc_date(date('d/m/Y'));
                
                if(DB::exists('select id from product_price_list where product_id = \''.$record['product_id'].'\' and portal_id=\''.PORTAL_ID.'\' and department_code =\'MINIBAR\' '))
				{
                    foreach($rooms as $room){
                        $record['minibar_id']=$minibar[$room]['minibar_id'];
                        
                        if( $row = DB::fetch('Select * from minibar_product where minibar_id = \''.$minibar[$room]['minibar_id'].'\' and product_id = \''.$record['product_id'].'\' and portal_id=\''.PORTAL_ID.'\'') )
                        {
                            //echo  'Select * from minibar_product where minibar_id = \''.$minibar[$room]['minibar_id'].'\' and product_id = \''.$record['product_id'].'\' and portal_id=\''.PORTAL_ID.'\'';exit();
                            if(Url::get('cmd')=='add'){
                                $record['norm_quantity'] = $_REQUEST['mi_room_amenities'][$key]['norm_quantity'] + $row['norm_quantity'];
                            }else{
                                $record['norm_quantity'] = $row['norm_quantity']-$_REQUEST['mi_room_amenities'][$key]['norm_quantity'];
                            }
                            DB::update_id('minibar_product',$record,$row['id']); 
                        }
                        else
                            if(Url::get('cmd')=='add'){
                                DB::insert('minibar_product',$record);
                            }   
                    }
				}    
			}
		}
        Url::redirect_current();
	}	
	function draw()
	{
		if(Url::get('cmd')=='add' or Url::get('cmd')=='delete')
		{
		  $_REQUEST['mi_room_amenities'] = array();
	       $this->parse_layout('edit',array());
		}
		else //List minibar sang form minibar
		{
            //Lấy các minibar, status = 1 nếu trong minibar đã khai báo định mức
            //class_room_floor dùng bên layout để chỉnh nút check all
			if(Portal::language()==1)
                {
                    $floor_1 ='room.floor';
                    
                }
                else
                {
                    $floor_1 ='\'Floor \'||substr(room.floor,6)';
                    
                }
            $rooms= DB::fetch_all('
				select
                    room.id,
					case
                        when room.floor = \'PA\'
                        then room.floor
                        else '.$floor_1.'
                    end as floor,
					room.name as room_name,
                    
					DECODE(room_amenities.room_id,null,0,1) as status,
                    room_level.brief_name as room_level_brief_name,
                    replace(room.floor, \' \', \'\') as class_room_floor
				from
                    room 
					left outer join room_amenities on room_amenities.room_id = room.id
                    inner join room_level on room.room_level_id = room_level.id
				where
					room.portal_id = \''.PORTAL_ID.'\'					
				order by
					floor,room.name
			');
            
            //Sắp xếp minibar theo các tầng	
			$floors = array();
			$last_floor = false;			
			foreach($rooms as $key=>$room)
			{
                //Gán tầng
				if(!$last_floor or $last_floor!=$room['floor'])
				{
					$floors[$room['floor']]=
						array(
							'name'=>$room['floor'],
                            'class_room_floor'=>$room['class_room_floor'],
							'rooms'=>array()
						);
					$last_floor = $room['floor'];
					$i = 1;
				}
                //Gán minibar vào các tầng
				$floors[$last_floor]['rooms'][$i] = $room;
				$i++;
			}
            //System::debug($floors);
			$this->parse_layout('rooms',
				array(
				'floors'=>$floors
				)
			);
		}
	}
}
?>