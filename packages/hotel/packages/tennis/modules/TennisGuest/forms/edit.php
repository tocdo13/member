<?php
class EditTennisGuestForm extends Form
{
	function EditTennisGuestForm()
	{
		Form::Form();
		$this->link_css(Portal::template('hotel').'/css/style.css');
		$this->add('full_name',new TextType(true,'miss_full_name',0,255));
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
	}
	function on_submit(){
		if($this->check()){
			$array = array(
				'code',		
				'full_name',
				'gender',
				'phone',
				'address',
				'email',
				'category',
				'note'
			);
			if(Url::get('cmd')=='edit'){
				$id = Url::iget('id');
				DB::update('tennis_guest',$array,'id='.Url::iget('id'));
			}else{
				$id = DB::insert('tennis_guest',$array);
			} 
			Url::redirect_current();
		}
	}
	function draw()
	{
		$this->map = array();
		$this->map['gender_list']=array(
			'1'=>Portal::language('female'),
			'0'=>Portal::language('male'),			
		);
		$this->map['category_list']=array(
			'COMMON'=>Portal::language('COMMON'),
			'VIP'=>Portal::language('VIP')
		);
		$item = TennisGuest::$item;
		if($item){
			foreach($item as $key=>$value){
				if(!isset($_REQUEST[$key])){
					$_REQUEST[strtoupper($key)] = $value;
				}
			}
		}
		$this->map['title'] = (Url::get('cmd')=='add')?Portal::language('add_TennisGuest'):Portal::language('edit_TennisGuest');
		$this->parse_layout('edit',$this->map);
	}	
}
?>