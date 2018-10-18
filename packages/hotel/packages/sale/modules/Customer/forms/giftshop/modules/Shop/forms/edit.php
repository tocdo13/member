<?php
class EditShopForm extends Form
{
	function EditShopForm()
	{
		Form::Form();
		$this->link_css(Portal::template('hotel').'/css/style.css');
		$this->add('code',new UniqueType(true,'miss_code','shop','code'));		
		$this->add('name',new TextType(true,'miss_name',0,255));
	}
	function on_submit(){
		if($this->check()){
			$array = array(
				'code',
				'name'
			);
			if(Url::get('cmd')=='edit'){
				$id = Url::iget('id');
				DB::update('shop',$array,'id='.Url::iget('id'));
			}else{
				$id = DB::insert('shop',$array);
			} 
			Url::redirect_current();
		}
	}
	function draw()
	{
		$this->map = array();
		$item = Shop::$item;
		if($item){
			foreach($item as $key=>$value){
				if(!isset($_REQUEST[$key])){
					$_REQUEST[strtoupper($key)] = $value;
				}
			}
		}
		$this->map['title'] = (Url::get('cmd')=='add')?Portal::language('add_shop'):Portal::language('edit_shop');
		$this->parse_layout('edit',$this->map);
	}	
}
?>