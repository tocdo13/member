<?php
class EditTennisCourtForm extends Form
{
	function EditTennisCourtForm()
	{
		Form::Form();
		$this->link_css(Portal::template('hotel').'/css/style.css');
		$this->add('category',new TextType(true,'miss_category',0,255));		
		$this->add('name',new TextType(true,'miss_name',0,255));
	}
	function on_submit(){
		if($this->check()){
			$array = array(
				'name',
				'POSITION',
				'category'
			);
			if(Url::get('cmd')=='edit'){
				$id = Url::iget('id');
				DB::update('tennis_court',$array,'id='.Url::iget('id'));
			}else{
				$id = DB::insert('tennis_court',$array);
			} 
			Url::redirect_current();
		}
	}
	function draw()
	{
		$this->map = array();
		$item = TennisCourt::$item;
		if($item){
			foreach($item as $key=>$value){
				if(!isset($_REQUEST[$key])){
					$_REQUEST[strtoupper($key)] = $value;
				}
			}
		}
		$this->map['title'] = (Url::get('cmd')=='add')?Portal::language('add_court'):Portal::language('edit_court');
		$this->parse_layout('edit',$this->map);
	}	
}
?>