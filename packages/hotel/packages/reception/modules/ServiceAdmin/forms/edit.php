<?php
class EditServiceAdminForm extends Form
{
	function EditServiceAdminForm()
	{
		Form::Form('EditServiceAdminForm');
		$this->link_css(Portal::template('hotel').'/css/style.css');
		$this->add('name',new TextType(true,'miss_name',0,255));
	}
	function on_submit(){
		if($this->check()){	
			$array = array(
				'name',
				'type'
			);
			if(Url::get('cmd')=='edit'){
				$id = Url::iget('id');
				$log_action = 'edit';// Edited in 28/02/2011
				DB::update('service',$array,'id='.Url::iget('id'));
				$log_description .= 'Edit service: '.Url::get('name').' | type: '.Url::get('type').'';
			}else{
				$log_action = 'add';// Edited in 28/02/2011
				$id = DB::insert('service',$array);
				$log_description .= 'Add service: '.Url::get('name').' | type: '.Url::get('type').'';
			} 
			$log_title = 'Service: #'.$id.'';
			System::log($log_action,$log_title,$log_description,$id);// Edited in 28/02/2011
			Url::redirect_current();
		}
	}
	function draw()
	{
		$this->map = array();
		$item = ServiceAdmin::$item;
		if($item){
			foreach($item as $key=>$value){
				if(!isset($_REQUEST[$key])){
					$_REQUEST[strtoupper($key)] = $value;
				}
			}
		}
		$this->map['type_list'] = array(
			'SERVICE'=>Portal::language('service'),	
			'ROOM'=>Portal::language('room_amount')
		);
		$this->map['title'] = (Url::get('cmd')=='add')?Portal::language('add_service'):Portal::language('edit_service');
		$this->parse_layout('edit',$this->map);
	}	
}
?>
