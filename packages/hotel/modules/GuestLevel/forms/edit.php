<?php
class EditGuestLevelForm extends Form
{
	function EditGuestLevelForm()
	{
		Form::Form();
		$this->link_css(Portal::template('hotel').'/css/style.css');
		$this->add('name',new TextType(true,'miss_name',0,255));
	}
	function on_submit(){
		if($this->check()){
			$array = array(
				'name',
                'group_name',
                'is_online',
				'position'
			);
			if(Url::get('cmd')=='edit'){
				$id = Url::iget('id');
				DB::update('guest_type',$array,'id='.Url::iget('id'));
			}else{
				$id = DB::insert('guest_type',$array);
			}
			Url::redirect_current();
		}
	}
	function draw()
	{
		$this->map = array();
		$item = GuestLevel::$item;
		if($item){
			foreach($item as $key=>$value){
				if(!isset($_REQUEST[$key])){
					$_REQUEST[strtoupper($key)] = $value;
				}
			}
		}
		$this->map['title'] = (Url::get('cmd')=='add')?Portal::language('add_guest_type'):Portal::language('edit_guest_type');
		$this->map['group_name_list'] = array('WALK_IN'=>Portal::language('walk_in'),'TRAVEL'=>Portal::language('travel'));
        $this->map['is_online_list'] = array('1'=>Portal::language('yes'),'0'=>Portal::language('No'));
        $this->parse_layout('edit',$this->map);
	}	
}
?>