<?php
class EditProductPriceForm extends Form
{
	function EditProductPriceForm()
	{
		Form::Form('EditProductPriceForm');
 		$this->link_css('packages/hotel/packages/warehousing/skins/default/css/style.css');
		$this->link_css('packages/core/skins/default/css/jquery.autocomplete.css');
 		$this->link_js('packages/core/includes/js/jquery/jquery.autocomplete.js');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
        $this->link_js('packages/core/includes/js/jquery/datepicker.js');
        $this->link_js('packages/core/includes/js/multi_items.js');
        $this->add('product_group.product_id',new TextType(true,'miss_product_code',0,50));
		$this->add('product_group.price',new TextType(true,'miss_price',0,50));
        $this->add('product_group.unit_id',new TextType(true,'unit_is_required',0,255));
        $this->add('product_group.category_id',new TextType(true,'category_is_required',0,255));
        $this->add('product_group.type',new TextType(true,'type_is_required',0,255));
	}
    
	function on_submit()
    {
        //Khi nhan save moi thuc hien vi form co the submit qua onchange cua portal_list
        if(Url::get('save'))
        {
    		if($this->check())
            {
                //Neu xoa multi row thi xoa trong bang
                if(URl::get('group_deleted_ids'))
                {
                	$group_deleted_ids = explode(',',URl::get('group_deleted_ids'));
                    $selected_ids = Url::get('group_deleted_ids');
                    $bar_reservation_product = DB::fetch_all('select price_id as id from bar_reservation_product where '.((sizeof($group_deleted_ids)>1)?'price_id in (\''.$selected_ids.'\')':'price_id=\''.$selected_ids.'\'').'');
                	foreach($group_deleted_ids as $delete_id)
                	{
                		if(isset($bar_reservation_product[$delete_id]))
                        {
                            echo '<script>';
                			echo 'alert(" Đã tồn tại dữ liệu trong hóa đơn. Không thể xóa !");';
                			echo '</script>';
                            return false;
                        }
                        DB::delete_id('product_price_list',$delete_id);
                	}
                    ProductPriceDB::export_cache();
                    //Url::redirect_current(array('type','portal_id'=>Url::sget('portal_id')));
                }
                
                if(isset($_REQUEST['mi_product_group']))
                {
                	foreach($_REQUEST['mi_product_group'] as $key=>$record)
                	{
                        unset($record['units_id']); 
                        unset($record['unit']);
                        unset($record['categorys_id']);
                        unset($record['category']); 
                        unset($record['types_id']); 
                		//
                        //unset($record['unit_id']);
                        //unset($record['name']);
                		$record['price'] = System::calculate_number($record['price']);
                		$record['start_date'] = Date_Time::to_orc_date($record['start_date']);
                		$record['end_date'] = Date_Time::to_orc_date($record['end_date']);
                		
                		
                        //kiem tra product_id neu day du du lieu thi insert
                        $empty = true;
                		foreach($record as $k=>$record_value)
                		{
                		  //System::debug( $record_value);
                			if($record_value)
                			{
                				$empty = false;
                			}
                		}
                		
                        if(!$empty)
                		{
                            if(!DB::exists_id('product',$record['product_id']))
                            {
                                $row = array(
                                                'id'=>strtoupper($record['product_id']),
                                                'name_1'=>$record['name'],
                                                'name_2'=>$record['name'],
                                                'category_id'=>$record['category_id'],
                                                'unit_id'=>$record['unit_id'],
                                                'type'=>$record['type'],
                                                'status'=>'avaiable',
                                            );
                                if(!$record['unit_id'])
                                {
                                    $this->error('product_group.unit','miss_unit');
                                    return;  
                                }
                                if(!$record['category_id'])
                                {
                                    $this->error('product_group.category_id','miss_category');
                                    return;  
                                }
                                if(!$record['type'])
                                {
                                    $this->error('product_group.type','miss_type');
                                    return;  
                                }
                                DB::insert('product',$row);
                            }
                            unset($record['unit_id']);
                            unset($record['type']);
                            unset($record['category_id']); 
                            unset($record['name']);
                            $record['product_id'] = mb_strtoupper($record['product_id']);
                            if($this->check_conflict_time(Url::sget('department_code'),$record['id'],$record['product_id'],$record['start_date'],$record['end_date'])!=""){
                                echo "<script>
                                        alert('Mã sản phẩm ".$record['product_id']." trong khoảng thời gian từ ".(empty($record['start_date'])?'???':Date_Time::convert_orc_date_to_date($record['start_date']))." đến ".(empty($record['end_date'])?'???':Date_Time::convert_orc_date_to_date($record['end_date']))." xung đột với ".$this->check_conflict_time(Url::sget('department_code'),$record['id'],$record['product_id'],$record['start_date'],$record['end_date'])."');
                                        </script>";
                                return;        
                            }
                            
                            if(Url::get('cmd')=='add')
                            {
                                
                                $record['department_code'] = Url::sget('department_code');
                                $record['portal_id'] = Url::sget('portal_id')?Url::sget('portal_id'):PORTAL_ID;
                                 //THANH chuc nang them moi nhieu gia cho 1 san pham thuoc 1 bo phan 
                                if(empty($record['id'])){
                                    unset($record['id']);
                                    $id_in = DB::insert('product_price_list',$record);                                    
                                }
                                else{
                                    $id_up = DB::update_id('product_price_list',$record,$product_price['id']);
                                }
                                
                                //end THANH 
                            }
                            else
                                if(Url::get('cmd')=='edit')
                                {
                                    DB::update('product_price_list',$record,'id=\''.$record['id'].'\'');
                                }
                		}
                	}
                    ProductPriceDB::export_cache();
                    Url::redirect_current( array('portal_id'=>Url::sget('portal_id'),'product_id','product_name','category_id','type','department_code'=>Url::get('department_code') ) );
                }
                else
                {
                	return;
                }
                //Url::redirect_current(array('type','cmd'=>'cache'));
                
    		}
        }

	}
    
    
	function draw()
	{
		$this->map = array();
		if(
            (Url::get('department_code') and $row = DB::select('department','code=\''.Url::get('department_code').'\'') and Url::get('cmd')!='add') 
            or Url::get('id')
        )
        {
			if(!isset($_REQUEST['mi_product_group']))
			{
                $cond = '1=1 ';
                $selected_ids = URL::get('id');
                //chon nhieu checkbox
                if(is_string(URL::get('id')) and strpos(Url::get('id'),','))
    			{
                    $cond.=' AND product_price_list.id IN ('.$selected_ids.')';
    			}
    			else//khong chon check box ma chon theo bo phan hoac chon 1 checkbox
                {
                    //chon 1 checkbox
                    if(Url::iget('id'))
        			{
        				$selected_ids = URL::get('selected_ids');
                        $cond.=' AND product_price_list.id ='.Url::iget('id');
        			}   
                }
                
                //tim theo bo phan
                if(isset($row))
                {
                    $cond.=' AND product_price_list.department_code=\''.$row['code'].'\' ';
                }
				$sql = '
        				SELECT
    						product_price_list.id,
    						product.id as product_id,
    						product_price_list.price,
							product_price_list.position,
                            product_price_list.product_pipeline,
    						TO_CHAR(product_price_list.start_date,\'dd/mm/YYYY\') as start_date,
    						TO_CHAR(product_price_list.end_date,\'dd/mm/YYYY\') as end_date,
                            product_price_list.use_time,
    						product.name_'.Portal::language().' as name,
                            product.type,
                            unit.id as unit_id,
    						unit.name_'.Portal::language().' as unit,
                            product.category_id,
                            product_category.name as category
        				FROM
    						product_price_list
    						INNER JOIN product ON product_price_list.product_id = product.id
    						Left JOIN unit ON unit.id = product.unit_id
                            Left join product_category on product.category_id = product_category.id
    				    WHERE
        					'.$cond.'
        				';
                
                $mi_product_group = DB::fetch_all($sql);
                //System::debug($sql);
                //System::debug($mi_product_group);
				foreach($mi_product_group as $key=>$value)
				{
					$mi_product_group[$key]['price'] = System::display_number($value['price']);
				}
				$_REQUEST['mi_product_group'] = $mi_product_group;
			} 
		}
        //System::debug($_REQUEST['mi_product_group']);
		require_once 'packages/hotel/includes/php/product.php';
		if(Url::get('cmd')=='add')
            $this->map['title'] = Portal::language('add_price');
        if(Url::get('cmd')=='edit')
            $this->map['title'] = Portal::language('edit_price');
        $this->map['products'] = Product::get_product();
		//System::Debug($this->map['products']);
		//$this->map['unit_id_options'] = Product::get_unit('',true);
        
        $portal_id = '';
        $portal_id = Url::get('portal_id')?Url::get('portal_id'):PORTAL_ID;
        //echo $portal_id;
		$department = DB::fetch_all(' select 
                                            portal_department.id as portal_department_id,
                                            portal_department.department_code as id,
                                            department.name_'.Portal::language().' as name ,
                                            portal_department.portal_id
                                        from 
                                            portal_department 
                                            inner join department on department.code = portal_department.department_code
                                        where 
                                            portal_department.PORTAL_ID = \''.$portal_id.'\'
                                        order by
                                            department.id   
                                    '
                                    );
        
        //$this->map['department_code_list'] = String::get_list($department);
        $department_list = ProductPriceDB::get_department($portal_id);
        $this->map['portal_id_list'] = String::get_list(Portal::get_portal_list());	
        $categorys = ProductPriceDB::get_category();	
		//System::debug($_REQUEST);
        $types = ProductPriceDB::get_types();
        $units = ProductPriceDB::get_units();
        
        $sql = 'select
    			id, product_category.name as name,structure_id
    			from product_category
    			order by structure_id
		          ';
		$categories = DB::fetch_all($sql);
        $this->map['categories_js'] = String::array2js($categories);
        
        DB::query('select id, name_'.Portal::language().' as name from unit order by name');
		$db_items = DB::fetch_all();
        $this->map['unit_js'] = String::array2js($db_items);
		$unit_id_options = '';
        foreach($db_items as $item)
		{
			$unit_id_options .= '<option value="'.$item['id'].'">'.$item['name'].'</option>';
		} 
        
		 
        $this->parse_layout('edit',$this->map+array('portal_id'=>$portal_id,
                                                    'units'=>$units,'types'=>$types,
                                                    'categorys'=>$categorys,
                                                    'department_list'=>$department_list,
                                                    'categories_id_list'=>array('--'=>'')+String::get_list($categories),
                                                    'unit_id_options' =>$unit_id_options,
                                                    )
                                                    );
    }   

    function check_conflict_time($department_code,$id,$product_id,$start_date,$end_date)
    {
        if(empty($start_date))
        {
            $start_date = 0;
        }
        else
        {
            $start_date = Date_Time::to_time(Date_Time::convert_orc_date_to_date($start_date,'/'));
        }
        
        if(empty($end_date))
        {
            $end_date = 99999999999;
        }
        else
        {
            $end_date = Date_Time::to_time(Date_Time::convert_orc_date_to_date($end_date,'/'));
        }
        $sql = "SELECT id,product_id,start_date, end_date FROM product_price_list WHERE product_id='".$product_id."' AND department_code='".$department_code."'";
        
        $result = DB::fetch_all($sql);
        //System::debug($result);
        //exit();
        foreach($result as $key=>$value){    
            if(!empty($value['start_date'])){
              $start_date_temp_string =  Date_Time::convert_orc_date_to_date($value['start_date'],'/'); 
              $start_date_temp = Date_Time::to_time(Date_Time::convert_orc_date_to_date($value['start_date'],'/'));              
            }
            else{
               $start_date_temp = 0;
                $start_date_temp_string = "???";
            }
            if(!empty($value['end_date'])){
              $end_date_temp_string =  Date_Time::convert_orc_date_to_date($value['end_date'],'/'); 
              $end_date_temp = Date_Time::to_time(Date_Time::convert_orc_date_to_date($value['end_date'],'/'));              
            }
            else{
               $end_date_temp = 99999999999;
                $end_date_temp_string = "???";
            }
                if($value['id']==$id){
                    continue;
                }
                if($start_date<=$end_date_temp && $end_date >= $start_date_temp){
                  return "Mã : ".$value['product_id']." đã được cài đặt trong khoảng thời gian từ ".$start_date_temp_string." --> ".$end_date_temp_string;  
                }
        }        
        return '';
    }
}
?>
