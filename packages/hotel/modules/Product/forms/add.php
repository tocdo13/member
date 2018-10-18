<?php
class AddProductForm extends Form
{
	function AddProductForm()
	{
		Form::Form('AddProductForm');
		//$this->add('product.id',new TextType(true,'product_id_is_required',0,16));
		$this->add('product.id',new UniqueType(true,'duplicate_product_id','product','id'));
		$this->add('type',new SelectType(false,'invalid_type',array('GOODS'=>'GOODS','DRINK'=>'DRINK','PRODUCT'=>'PRODUCT','MATERIAL'=>'MATERIAL','EQUIPMENT'=>'EQUIPMENT','SERVICE'=>'SERVICE','TOOL'=>'TOOL')));
		$languages = DB::select_all('language');
		foreach($languages as $language)
		{
			$this->add('product.name_'.$language['id'],new TextType(true,'invalid_name_'.$language['id'],0,2000)); 
		}
		$this->add('product.unit_id',new TextType(true,'invalid_unit_id',0,255));
	}
	function on_submit()
	{
		if($this->check())
		{
            require_once 'packages/core/includes/utils/upload_file.php';
			if(isset($_REQUEST['mi_product']))
			{
				foreach($_REQUEST['mi_product'] as $key=>$record)
				{
				    $cond = strtoupper($record['id']);
				    if(DB::fetch("SELECT * FROM product WHERE product.id='".$cond.'\'')){
				        $this->error('','Mã sản phẩm đã trùng!');
                        return false;
				    }
					$record['type'] = URL::get('type');
					if(Url::get('page')=='warehouse_product')
					{
						$record['start_term_quantity'] = $record['start_term_quantity'];
					}
					else
					{
						unset($record['start_term_quantity']);
					}
					//Duc them
					$record['status'] = 'avaiable';
					// end
					$record['category_id'] = Url::get('category_id');
					if(isset($record['id'])){
						$record['id'] = strtoupper($record['id']);
					}
                    $record['image_url'] = update_mi_upload_image('product',$key,'image_url','resources/default/products',true,'id',true,120,120);
					DB::insert('product',$record);
				}
			}

			if(Url::get('action')=='select_product')
			{
				Url::redirect_current(array('category_id','code','name','type','action'=>Url::get('action')));
			}
			else
			{
				Url::redirect_current(array('category_id','code','name','type'));
			}
		}
	}
	function draw()
	{
		$languages = DB::select_all('language');
		DB::query('select
			id, product_category.name as name,structure_id
			from product_category
			order by structure_id
		');
		$categories = DB::fetch_all(); 
		$category_id_options = '';
		DB::query('select id, name_'.Portal::language().' as name from unit order by name');
		$db_items = DB::fetch_all();
        //system::debug($db_items);
        $this->map['unit_js'] = String::array2js($db_items);
		$unit_id_options = '';
		foreach($db_items as $item)
		{
			$unit_id_options .= '<option value="'.$item['id'].'">'.$item['name'].'</option>';
		} 
        
        $db_items = DB::fetch_all("select id, name from PRINTER order by name");
		$print_id_options = '';
		foreach($db_items as $item)
		{
			$print_id_options .= '<option value="'.$item['id'].'">'.$item['name'].'</option>';
		} 

		$this->parse_layout('add',$this->map+
			array(
			'exchange_rate' => DB::fetch('SELECT id,exchange FROM currency WHERE id=\'VND\'','exchange'),		
			'languages'=>$languages,
			'category_id_list'=>String::get_list($categories),
			'category_id'=>1, 
			'unit_id_options' => $unit_id_options,
            'unit_js'=>String::array2js($db_items),
            'print_id_options' => $print_id_options,
			'portal_id_list'=>String::get_list(Portal::get_portal_list())
			)
		);
        
	}
}
?>