<?php
class EditMinibarProductForm extends Form
{
	function EditMinibarProductForm()
	{
		Form::Form('EditMinibarProductForm');
		$this->link_css(Portal::template('hotel').'/css/room.css');
        $this->add('minibar_product.product_id',new TextType(true,'invalid_product_id',0,255));
		$this->add('minibar_product.norm_quantity',new FloatType(true,'invalid_norm_quantity','0','100000000000'));
		$this->link_js('packages/core/includes/js/multi_items.js');
		$this->link_css('packages/core/skins/default/css/jquery.autocomplete.css');
		$this->link_js('packages/core/includes/js/jquery/jquery.autocomplete.js');
        @$this->link_js('cache/data/'.strtolower(str_replace('#','',PORTAL_ID)).'/MINIBAR_'.strtolower(str_replace('#','',PORTAL_ID)).'.js?v='.time());
	}
	function on_submit()
	{
        if(Url::get('act')=='remove')
        {
            if( $selected_ids = Url::get('selected_ids') and is_array($selected_ids) )
            {
                $cond = ' and ( ';
                foreach($selected_ids as $id)
                {
                    $cond .= '  minibar_id=\''.$id.'\' or ';
                }
                $cond .= ' 1<0 )';
                DB::query( 'delete from minibar_product where portal_id=\''.PORTAL_ID.'\''.$cond );
            }
        }
	   
		if($this->check() and Url::get('confirm_edit'))
		{
            //Xoa tung item va click save
			if(Url::get('deleted_ids'))
			{
				$ids = explode(',',Url::get('deleted_ids'));
				foreach($ids as $id)
				{
					DB::delete_id('minibar_product',$id);
				}
			}
            
			$ids = array();
            $minibars = array();
            //ton tai cac item
			if(isset($_REQUEST['mi_minibar_product']))
			{
				foreach($_REQUEST['mi_minibar_product'] as $key=>$record)
				{
					$record['price'] = $record['price']?$record['price']:0;
					$record['price'] = str_replace(',','',$record['price']);
					$record['norm_quantity'] = str_replace(',','',$record['norm_quantity']);
					$record['position'] = str_replace(',','',$record['position']);
                    $record['quantity'] = $record['quantity']?$record['quantity']:0;
					$record['portal_id'] = PORTAL_ID;
                    
					if($record['id'] and DB::exists_id('minibar_product',$record['id']))
					{
						DB::update('minibar_product',$record,'id='.$record['id']);
						$ids[] = $record['id'];
					}
					else//neu khong ton tai thi insert
					{
						unset($record['id']);
						$record['in_date'] = Date_Time::to_orc_date(date('d/m/Y'));
                        
                        //neu co requert nay thi tuc la them moi vao 1 minibar
						if(Url::get('minibar_id'))
						{
							$record['minibar_id']=Url::get('minibar_id');
							//Nếu có tồn tại sản phẩm trong phần giá
                            if(DB::exists('select id from product_price_list where product_id = \''.$record['product_id'].'\' and portal_id=\''.PORTAL_ID.'\' and department_code =\'MINIBAR\' '))
							{
                                //Nếu sản phẩm này đã đc khai báo định mức thì cộng dồn vào
                                if( $row = DB::fetch('Select * from minibar_product where minibar_id = \''.$record['minibar_id'].'\' and product_id = \''.$record['product_id'].'\' and portal_id=\''.PORTAL_ID.'\'') )
                                {
                                    $record['norm_quantity'] = $record['norm_quantity'] + $row['norm_quantity'];
                                    DB::update_id('minibar_product',$record,$row['id']); 
                                }
                                else
                                    DB::insert('minibar_product',$record);
							}
						}
						else//neu khong thi la them tat ca cac minibar. hoặc thêm từng level
						{
                            if( empty($minibars) )
                                $minibars = DB::fetch_all('
								select
									minibar.id
								from
									minibar
									inner join room on room.id=minibar.room_id
                                    inner join room_level on room_level.id = room.room_level_id
								where
									room.portal_id = \''.PORTAL_ID.'\'
                                    '.( Url::get('room_level_id')? 'and room_level_id = '.Url::get('room_level_id') : '' ).'
								order by
									floor,minibar.name'
                                );
                            //Tao bien nay de qua vong lap khong bi cong don $record['norm_quantity'] 
                            $norm_quantity = $record['norm_quantity'];
							foreach($minibars as $minibar)
							{
								$record['minibar_id'] = $minibar['id'];
								if(DB::exists('select id from product_price_list where product_id = \''.$record['product_id'].'\' and portal_id=\''.PORTAL_ID.'\' and department_code =\'MINIBAR\' '))
								{		    
                                    if( $row = DB::fetch('Select * from minibar_product where minibar_id = \''.$record['minibar_id'].'\' and product_id = \''.$record['product_id'].'\' and portal_id=\''.PORTAL_ID.'\'') )
                                    {
                                        $record['norm_quantity'] = $norm_quantity + $row['norm_quantity'];
                                        DB::update_id('minibar_product',$record,$row['id']); 
                                    }
                                    else
                                        DB::insert('minibar_product',$record);
								}
							}
						}
					}
				}
			}
			Url::redirect_current(array('minibar_id'));
		}
	}	
	function draw()
	{
		if((Url::get('minibar_id') and $minibar=DB::select('minibar','id=\''.Url::get('minibar_id').'\'')) or Url::get('cmd')=='add')
		{
			$paging = '';
			if(!isset($_REQUEST['mi_minibar_product']))
			{
                //Click vào từng minibar
				if(Url::get('cmd')!='add')
				{
					$cond = ' 1>0 '.(Url::get('minibar_id')?' and minibar_id=\''.Url::get('minibar_id').'\'':'');
					$item_per_page = 1000;
					DB::query('
						select count(*) as acount
						from 
							minibar_product
							left outer join minibar on minibar.id = minibar_product.minibar_id
						where '.$cond.'
					');
					$count = DB::fetch();
					require_once 'packages/core/includes/utils/paging.php';
					$paging = paging($count['acount'],$item_per_page);
					
					$sql = '
						SELECT * from
						(
							SELECT temp.*,ROWNUM as rownumber FROM
							(
								SELECT 
									minibar_product.*									
								FROM 
									minibar_product
									left outer join minibar on minibar.id=minibar_product.minibar_id
								WHERE 
									'.$cond.'
							) temp
						)
						WHERE rownumber > '.((page_no()-1)*$item_per_page).' and rownumber < '.(page_no()*$item_per_page).'
					';
					$mi_minibar_product = DB::fetch_all($sql);
					foreach($mi_minibar_product as $key=>$value)
					{
						$mi_minibar_product[$key]['norm_quantity'] = System::display_number($value['norm_quantity']);
						$mi_minibar_product[$key]['price'] = System::display_number($value['price']);   
					}
					$_REQUEST['mi_minibar_product'] = $mi_minibar_product;
				}
				else
				{
					$_REQUEST['mi_minibar_product'] = array();
				}
			}
            //System::debug($_REQUEST['mi_minibar_product']);
			//Lấy các minibar
			$db_items = DB::fetch_all('
				select
					minibar.id
				from
					minibar
					inner join room on room.id=minibar.room_id
				where
					portal_id = \''.PORTAL_ID.'\'
				order by
					position,minibar.name
			');
			$minibar_id_options = '';
			foreach($db_items as $item)
			{
				$minibar_id_options .= '<option value="'.$item['id'].'">'.$item['id'].'</option>';
			}
            
            //Lấy các hạng phòng có minibar
            DB::query('
                select		
                    id, 
                    room_level.name as name
    			from 
    				room_level
                where
					room_level.portal_id = \''.PORTAL_ID.'\'
    			order 
    				by room_level.name
    		');
    		$room_level_id_list = String::get_list(DB::fetch_all());
            		
			$this->parse_layout('edit',
				array(
				'paging'=>$paging,
				'name'=>isset($minibar)?$minibar['name']:'',
				'minibar_id_options' => $minibar_id_options,
                'room_level_id_list'=>$room_level_id_list,
				)
			);
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
        	$minibars = DB::fetch_all('
				select
					minibar.*,
					case
                        when room.floor = \'PA\'
                        then room.floor
                        else '.$floor_1.'
                    end as floor,
					room.name as room_name,
					DECODE(minibar_product.minibar_id,null,0,1) as status,
                    room_level.brief_name as room_level_brief_name,
                    replace(room.floor, \' \', \'\') as class_room_floor
				from
					minibar
					inner join room on room.id=minibar.room_id
					left outer join minibar_product on minibar_product.minibar_id = minibar.id
                    inner join room_level on room.room_level_id = room_level.id
				where
					room.portal_id = \''.PORTAL_ID.'\'					
				order by
					floor,room.position,room.name
			');
            
            //Sắp xếp minibar theo các tầng	
			$floors = array();
			$last_floor = false;			
			foreach($minibars as $key=>$minibar)
			{
                //Gán tầng
				if(!$last_floor or $last_floor!=$minibar['floor'])
				{
					$floors[$minibar['floor']]=
						array(
							'name'=>$minibar['floor'],
                            'class_room_floor'=>$minibar['class_room_floor'],
							'minibars'=>array()
						);
					$last_floor = $minibar['floor'];
					$i = 1;
				}
                //Gán minibar vào các tầng
				$floors[$last_floor]['minibars'][$i] = $minibar;
				$i++;
			}
            //System::debug($floors);
			$this->parse_layout('minibars',
				array(
				'floors'=>$floors
				)
			);
		}
	}
}
?>