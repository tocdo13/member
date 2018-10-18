<?php
class EditMinibarProductForm extends Form
{
	function EditMinibarProductForm()
	{
		Form::Form('EditMinibarProductForm');
		$this->link_css(Portal::template('hotel').'/css/room.css');
		$this->add('minibar_product.norm_quantity',new FloatType(true,'invalid_norm_quantity','0','100000000000')); 
		if(URL::get('cmd')!='add')
		{
			$this->add('minibar_product.norm_quantity',new FloatType(true,'invalid_norm_quantity','0','100000000000')); 
		}
		//$this->add('minibar_product.price',new FloatType(true,'invalid_price','0','100000000000'));
		$this->add('minibar_product.product_id',new TextType(false,'invalid_product_id',0,255));
		$this->link_js('packages/core/includes/js/multi_items.js');
		$this->link_css('packages/core/skins/default/css/jquery.autocomplete.css');
		$this->link_js('packages/core/includes/js/jquery/jquery.autocomplete.js');
        @$this->link_js('cache/data/'.strtolower(str_replace('#','',PORTAL_ID)).'/MINIBAR_default.js?v='.time());
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
		if($this->check() and URL::get('confirm_edit'))
		{
            //Xoa tung item va click save
			if(URL::get('deleted_ids'))
			{
				$ids = explode(',',URL::get('deleted_ids'));
				foreach($ids as $id)
				{
					DB::delete_id('minibar_product',$id);
				}
			}
            
			$ids = array();
            //ton tai cac item
			if(isset($_REQUEST['mi_minibar_product']))
			{
                //System::debug($_REQUEST['mi_minibar_product']);
                //xu l� tung items
				foreach($_REQUEST['mi_minibar_product'] as $key=>$record)
				{
					$record['price'] = $record['price']?$record['price']:0;
					$record['price'] = str_replace(',','',$record['price']);
					$record['portal_id'] = PORTAL_ID;
					
                    //Neu (auto) tuc la dang them moi, cong khong thi la edit )
                    if($record['id']=='(auto)')
					{
						$record['id']='';
					}
                    
                    //neu l� edit, va da ton tai id th� update
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
						if(URL::get('minibar_id'))
						{
							$record['minibar_id']=URL::get('minibar_id');
							//neu san pham ton tai
                            if(DB::exists('select id from product_price_list where product_id = \''.$record['product_id'].'\' and portal_id=\''.PORTAL_ID.'\' and department_code =\'MINIBAR\' '))
							{
                                //delete de dam bao 1 san pham chi co 1 trong 1 minibar thuoc 1 portal
								DB::delete('minibar_product','minibar_id = \''.$record['minibar_id'].'\' and product_id = \''.$record['product_id'].'\' and portal_id=\''.PORTAL_ID.'\'');
								$ids[] = DB::insert('minibar_product',$record);
							}
						}
						else//neu khong thi la them tat ca cac minibar
						{
							$minibars = DB::fetch_all('
								select
									minibar.id
								from
									minibar
									inner join room on room.id=minibar.room_id
								where
									portal_id = \''.PORTAL_ID.'\'
								order by
									floor,minibar.name'
							);
							foreach($minibars as $minibar)
							{
								$record['minibar_id'] = $minibar['id'];
								if(DB::exists('select id from product_price_list where product_id = \''.$record['product_id'].'\' and portal_id=\''.PORTAL_ID.'\' and department_code =\'MINIBAR\' '))
								{
								    //delete de dam bao 1 san pham chi co 1 trong 1 minibar thuoc 1 portal
									DB::delete('minibar_product','minibar_id = \''.$record['minibar_id'].'\' and product_id = \''.$record['product_id'].'\' and portal_id=\''.PORTAL_ID.'\' ');
									DB::insert('minibar_product',$record);
								}
							}
						}
					}
				}
				if (isset($ids) and sizeof($ids))
				{
					$ids[] = $_REQUEST['selected_ids'].=','.join(',',$ids);
                    System::debug($ids);
				}
			}
			Url::redirect_current(array('minibar_id'));
		}
	}	
	function draw()
	{
		if((URL::get('minibar_id') and $minibar=DB::select('minibar','id=\''.URL::get('minibar_id').'\'')) or URL::get('cmd')=='add')
		{
			$paging = '';
			if(!isset($_REQUEST['mi_minibar_product']))
			{
				if(URL::get('cmd')!='add')
				{
					$cond = ' 1>0 '.(URL::get('minibar_id')?' and minibar_id=\''.URL::get('minibar_id').'\'':'');
					$item_per_page = 1000;
					DB::query('
						select count(*) as acount
						from 
							minibar_product
							left outer join minibar on minibar.id=minibar_product.minibar_id
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
								'.(URL::get('order_by')?'order by '.URL::get('order_by').(URL::get('order_dir')?' '.URL::get('order_dir'):''):'').'
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
			//$db_items = DB::select_all('minibar',false,'id');
			$db_items = DB::fetch_all('
				select
					minibar.id
				from
					minibar
					inner join room on room.id=minibar.room_id
				where
					portal_id = \''.PORTAL_ID.'\'
				order by
					floor,minibar.name
			');
			$minibar_id_options = '';
			foreach($db_items as $item)
			{
				$minibar_id_options .= '<option value="'.$item['id'].'">'.$item['id'].'</option>';
			}
			//MinibarProduct::get_js_variables_data();			
			$this->parse_layout('edit',
				array(
				'paging'=>$paging,
				'name'=>isset($minibar)?$minibar['name']:'',
				'minibar_id_options' => $minibar_id_options
				)
			);
		}
		else //List minibar
		{
            //Lấy các minibar, status = 1 nếu trong minibar đã khai báo định mức
            //class_room_floor dùng bên layout để chỉnh nút check all
			$minibars = DB::fetch_all('
				select
					minibar.*,
					room.floor,
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
					floor,minibar.name
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