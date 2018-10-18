<?php
class EditStartTermRemainForm extends Form
{
	function EditStartTermRemainForm()
	{
		Form::Form('EditStartTermRemainForm');
		$this->link_css('packages/hotel/packages/warehousing/skins/default/css/style.css');
		$this->link_css('packages/core/skins/default/css/jquery.autocomplete.css');
		$this->link_js('packages/core/includes/js/jquery/jquery.autocomplete.js');
		$this->link_js('packages/core/includes/js/multi_items.js');
		//$this->add('wh_product.product_id',new TextType(true,'miss_code',0,50));
		//$this->add('wh_product.quantity',new TextType(true,'miss_quantity',0,50));
        $this->add('product_group.product_id',new TextType(true,'miss_code',0,50));
		$this->add('product_group.quantity',new FloatType(true,'miss_quantity',0,9999999999));
        $this->add('product_group.unit',new TextType(true,'unit_is_required',0,255));
        $this->add('product_group.category_id',new TextType(true,'category_is_required',0,255));
        $this->add('product_group.type',new TextType(true,'type_is_required',0,255));
	}
	function on_submit()
    {
		if($this->check())
        {
            //System::debug($_REQUEST);
            //exit();
			if(URl::get('group_deleted_ids'))
			{
				$group_deleted_ids = explode(',',URl::get('group_deleted_ids'));
				foreach($group_deleted_ids as $delete_id)
				{
					DB::delete_id('wh_start_term_remain',$delete_id);
				}
			}
            	
			if(isset($_REQUEST['mi_product_group']))
			{	
				foreach($_REQUEST['mi_product_group'] as $key=>$record)
				{
				    $record['product_id'] = strtoupper($record['product_id']);
                    $record['total_start_term_price'] = System::calculate_number($record['total_start_term_price']);
                    //Neu nhap 1 san pham moi ko co trong product thi them moi vao san pham
					if(!DB::exists('select * from product where upper(id) = \''.$record['product_id'].'\''))
                    {
                        DB::insert('product',array('name_1'=>$record['name'],'name_2'=>$record['name'],'id'=>$record['product_id'],'category_id'=>$record['category_id'],'unit_id'=>$record['unit_id'],'type'=>$record['type'],'status'=>'avaiable'));
                    }
                    
					$record['quantity']=str_replace(',','',$record['quantity']);
                    $record['start_term_price'] = $record['total_start_term_price']/$record['quantity'];
                    
					unset($record['name']);
                	unset($record['unit']);
                    unset($record['unit_id']);
                    unset($record['units_id']);
                    unset($record['category']);
                    unset($record['category_id']);
                    unset($record['categorys_id']);
                    unset($record['type']);
                    unset($record['types_id']);
					$record['portal_id'] = PORTAL_ID;
					$empty = true;
					foreach($record as $record_value)
					{
						if($record_value)
						{
							$empty = false;
						}
					}
					if(!$empty)
					{
						$record['warehouse_id'] = Url::iget('warehouse_id');
						if($record['id'])
						{
							DB::update('wh_start_term_remain',$record,'id=\''.$record['id'].'\'');
						}
						else
						{
							unset($record['id']);
							if(DB::exists('SELECT id FROM product WHERE id=\''.$record['product_id'].'\''))
                            {
                                if( $old_id = DB::fetch('SELECT * FROM wh_start_term_remain WHERE product_id=\''.$record['product_id'].'\' and warehouse_id ='.$record['warehouse_id'].' and portal_id = \''.$record['portal_id'].'\' ','id'))
                                {
    								DB::update_id('wh_start_term_remain',array('quantity'=>$record['quantity']),$old_id);
    							}
                                else
                                {
                                    DB::insert('wh_start_term_remain',$record);    
                                }
								
							}
						}
					}
				}
			}
            else
            {
				return;
			}
			
			Url::redirect_current(array('type'));
		}
	}
    
	function draw()
	{
		$this->map = array();
        require_once 'packages/hotel/packages/warehousing/includes/php/warehouse.php';
        $warehouses = get_warehouse(true);
		$item = StartTermRemain::$item;
		if($item){
			foreach($item as $key=>$value){
				if(!isset($_REQUEST[$key])){
					$_REQUEST[$key] = $value;
				}
			}
			if(!isset($_REQUEST['mi_product_group']))
			{
				$sql = '
					SELECT
						wh_start_term_remain.*,
						product.name_'.Portal::language().' as name,
						unit.name_'.Portal::language().' as unit,
                        unit.id as unit_id,
                        product_category.id as category_id,
                        product_category.name as category,
                        product.type
					FROM
						wh_start_term_remain
						INNER JOIN product ON product.id = wh_start_term_remain.product_id
						INNER JOIN unit ON unit.id = product.unit_id
                        INNER JOIN product_category on product_category.id = product.category_id 
					WHERE
						wh_start_term_remain.id=\''.$item['id'].'\' and wh_start_term_remain.portal_id=\''.PORTAL_ID.'\'
				';
				$mi_product_group = DB::fetch_all($sql);
				foreach($mi_product_group as $k=>$v)
                {
					$mi_product_group[$k]['quantity'] = round($v['quantity'],4);
				}
				$_REQUEST['mi_product_group'] = $mi_product_group;
			} 
		}
		$this->map['warehouse_id_list'] = String::get_list($warehouses);
		$this->map['title'] = Portal::language('start_term_remain');
		/*
        $this->map['products'] = DB::fetch_all('SELECT 
                                                    wh_product.code as id,
                                                    wh_product.name_'.Portal::language().' as name,
                                                    unit.name_'.Portal::language().' as unit 
                                                FROM 
                                                    wh_product 
                                                    INNER JOIN unit ON unit.id = wh_product.unit_id 
                                                where 
                                                    wh_product.portal_id=\''.PORTAL_ID.'\'');
        */
        
		$this->map['products'] = DB::fetch_all('SELECT 
                                                    product.id as id,
                                                    product.name_'.Portal::language().' as name,
                                                    unit.name_'.Portal::language().' as unit,
                                                    unit.id as unit_id,
                                                    product_category.id as category_id,
                                                    product_category.name as category,
                                                    product.type 
                                                FROM 
                                                    product 
                                                    INNER JOIN unit ON unit.id = product.unit_id
                                                    INNER JOIN product_category on product_category.id = product.category_id 
                                                where 
                                                    product.status=\'avaiable\'
                                                    and product.type != \'SERVICE\'    
                                                ');
        //System::debug($this->map['products']);
        require_once 'packages/hotel/packages/warehousing/modules/WarehouseInvoice/db.php';                                        
        $types = WarehouseInvoiceDB::get_types();
        $this->map['types_id'] = $types;
        $units = WarehouseInvoiceDB::get_units();
        $this->map['units_id'] = $units;
        $categorys = WarehouseInvoiceDB::get_category();
        $this->map['categorys_id'] = $categorys;
        
		$this->parse_layout('edit',$this->map);
	}	
}
?>