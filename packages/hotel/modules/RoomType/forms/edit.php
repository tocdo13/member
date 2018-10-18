<?php
class EditRoomTypeForm extends Form
{
	function EditRoomTypeForm()
	{
		Form::Form();
		$this->link_css(Portal::template('hotel').'/css/style.css');
		$this->add('name',new TextType(true,'miss_name',0,255));
	}
	function on_submit(){
		if($this->check()){
			$array = array(
				'name',
				'brief_name'
			);
			if(Url::get('cmd')=='edit'){
				$id = Url::iget('id');
				DB::update('room_type',$array,'id='.Url::iget('id'));
			}else{
				$id = DB::insert('room_type',$array);
			}
			Url::redirect_current();
		}
	}
	function draw()
	{
		$this->map = array();
		$item = RoomType::$item;
		if($item){
			foreach($item as $key=>$value){
				if(!isset($_REQUEST[$key])){
					$_REQUEST[strtoupper($key)] = $value;
				}
			}
		}
		$this->map['title'] = (Url::get('cmd')=='add')?Portal::language('add_room_type'):Portal::language('edit_room_type');
		$this->parse_layout('edit',$this->map);
	}	
}
?>