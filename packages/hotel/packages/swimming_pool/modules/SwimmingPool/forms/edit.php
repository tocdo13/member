<?php
class EditSwimmingPoolForm extends Form
{
	function EditSwimmingPoolForm()
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
				DB::update('swimming_pool',$array,'id='.Url::iget('id'));
			}else{
				$id = DB::insert('swimming_pool',$array);
			} 
			Url::redirect_current();
		}
	}
	function draw()
	{
		$this->map = array();
		$item = SwimmingPool::$item;
		if($item){
			foreach($item as $key=>$value){
				if(!isset($_REQUEST[$key])){
					$_REQUEST[strtoupper($key)] = $value;
				}
			}
		}
		$this->map['title'] = (Url::get('cmd')=='add')?Portal::language('add_swimming_pool'):Portal::language('edit_swimming_pool');
		$this->parse_layout('edit',$this->map);
	}	
}
?>