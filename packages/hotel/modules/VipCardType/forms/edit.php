<?php
class EditVipCardTypeForm extends Form
{
	function EditVipCardTypeForm()
	{
		Form::Form();
		$this->link_css(Portal::template('hotel').'/css/style.css');
		$this->add('name',new TextType(true,'miss_name',0,255));
	}
	function on_submit(){
		if($this->check()){
			$array = array(
				'name',
				'discount_percent'
			);
			if(Url::get('cmd')=='edit'){
				$id = Url::iget('id');
				DB::update('VIP_CARD_TYPE',$array,'id='.Url::iget('id'));
			}else{
				$id = DB::insert('VIP_CARD_TYPE',$array);
			}
			Url::redirect_current();
		}
	}
	function draw()
	{
		$this->map = array();
		$item = VipCardType::$item;
		if($item){
			foreach($item as $key=>$value){
				if(!isset($_REQUEST[$key])){
					$_REQUEST[strtoupper($key)] = $value;
				}
			}
		}
		$this->map['title'] = (Url::get('cmd')=='add')?Portal::language('add_vip_card_type'):Portal::language('edit_vip_card_type');
		$this->parse_layout('edit',$this->map);
	}	
}
?>