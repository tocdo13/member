<?php
class EditReservationTypeForm extends Form
{
	function EditReservationTypeForm()
	{
		Form::Form();
		$this->link_css(Portal::template('hotel').'/css/style.css');
		$this->add('name',new TextType(true,'miss_name',0,255));
	}
	function on_submit(){
		if($this->check()){
			$array = array(
				'name',
				'show_price'=>Url::check('show_price')?1:0,
			);
			if(Url::get('cmd')=='edit'){
				$id = Url::iget('id');
				DB::update('reservation_type',$array,'id='.Url::iget('id'));
			}else{
				$id = DB::insert('reservation_type',$array);
			}
			Url::redirect_current();
		}
	}
	function draw()
	{
		$this->map = array();
		$item = ReservationType::$item;
		if($item){
			foreach($item as $key=>$value){
				if(!isset($_REQUEST[$key])){
					$_REQUEST[strtoupper($key)] = $value;
				}
			}
		}
		$this->map['title'] = (Url::get('cmd')=='add')?Portal::language('add_reservation_type'):Portal::language('edit_reservation_type');
		$this->parse_layout('edit',$this->map);
	}	
}
?>