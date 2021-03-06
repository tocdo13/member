<?php
class EditTennisProductForm extends Form
{
	function EditTennisProductForm()
	{
		Form::Form();
		$this->link_css(Portal::template('hotel').'/css/style.css');
		$this->add('category',new TextType(true,'miss_category',0,255));		
		$this->add('code',new UniqueType(true,'invalid_code','code','tennis_product'));
		$this->add('name',new TextType(true,'miss_name',0,255));
		$this->add('price',new FloatType(true,'invalid_price'));
	}
	function on_submit(){
		if($this->check()){
			$array = array(
				'category',			
				'code',
				'name',
				'price'=>str_replace(',','',Url::get('price'))
			);
			if(Url::get('cmd')=='edit'){
				$id = Url::iget('id');
				DB::update('tennis_product',$array,'id='.Url::iget('id'));
			}else{
				$id = DB::insert('tennis_product',$array);
			} 
			Url::redirect_current();
		}
	}
	function draw()
	{
		$this->map = array();
		$item = TennisProduct::$item;
		if($item){
			foreach($item as $key=>$value){
				if(!isset($_REQUEST[$key])){
					$_REQUEST[strtoupper($key)] = $value;
				}
			}
		}
		$this->map['title'] = (Url::get('cmd')=='add')?Portal::language('add_product'):Portal::language('edit_product');
		$this->parse_layout('edit',$this->map);
	}	
}
?>