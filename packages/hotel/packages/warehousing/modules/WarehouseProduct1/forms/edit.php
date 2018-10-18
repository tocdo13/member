<?php
class EditWarehouseProductForm extends Form
{
	function EditWarehouseProductForm()
	{
		Form::Form();
		$this->link_css(Portal::template('hotel').'/css/style.css');
		$this->add('id',new UniqueType(true,'miss_code','wh_product','id'));
		$this->add('name_1',new TextType(true,'miss_vietnamese_name',0,255));
		$this->add('name_2',new TextType(false,'miss_english_name',0,255));		
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
	}
	function on_submit(){
		if($this->check()){
			$array = array(
				'id'=>Url::get('id'),
				'category_id'=>Url::get('category_id'),
				'name_1'=>Url::get('name_1'),
				'name_2'=>Url::get('name_2'),
				'type'=>Url::get('type'),
				'unit_id'=>Url::get('unit_id'),
				'price'=>str_replace('','',Url::get('price')),
				'start_term_quantity'=>str_replace('','',Url::get('start_term_quantity'))
			);
			if(Url::get('cmd')=='edit'){
				$id = Url::iget('id');
				DB::update('wh_product',$array,'id=\''.Url::sget('id').'\'');
			}else{
				System::debug($array);
				$id = DB::insert('wh_product',$array);
			} 
			Url::redirect_current(array('action'));
		}
	}
	function draw()
	{
		$this->map = array();
		$item = WarehouseProduct::$item;
		if($item){
			foreach($item as $key=>$value){
				if(!isset($_REQUEST[$key])){
					$_REQUEST[($key)] = $value;
				}
			}
		}
		$this->map['title'] = (Url::get('cmd')=='add')?Portal::language('add_product'):Portal::language('edit_product');
		$units = DB::fetch_all('SELECT id,name_'.Portal::language().' as name FROM unit');
		$this->map['type_list'] = array(
			"GOODS"=>Portal::language('goods'),
			"PRODUCT"=>Portal::language('product'),
			"MATERIAL"=>Portal::language('material'),
			"EQUIPMENT"=>Portal::language('equipment'),
			"SERVICE"=>Portal::language('service'),
			"TOOL"=>Portal::language('tool')
		);
		$this->map['category_id_list'] = String::get_list(DB::fetch_all('select wh_product_category.id,wh_product_category.name,wh_product_category.structure_id from wh_product_category order by wh_product_category.structure_id')); 
		$this->map['unit_id_list'] =  array(''=>Portal::language('select'))+String::get_list($units);
		$this->parse_layout('edit',$this->map);
	}	
}
?>