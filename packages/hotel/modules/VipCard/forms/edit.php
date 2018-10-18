<?php
class EditVipCardForm extends Form
{
	function EditVipCardForm()
	{
		Form::Form();
		$this->link_css(Portal::template('hotel').'/css/style.css');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
		$this->add('code',new UniqueType(true,'invalid_code','vip_card','code'));
		$this->add('card_holder',new TextType(true,'miss_card_holder',0,255));
		$this->add('card_type_id',new IDType(true,'miss_card_type','vip_card_type'));
	}
	function on_submit(){
		if(Url::get('save')){
			if($this->check()){	
				$array = array(
					'code',
					'card_holder',
					'discount_percent',
					'discount_amount',
					'card_type_id',
					'join_date'=>Date_Time::to_orc_date(Url::get('join_date')),
					'note'			
				);
				if(Url::get('cmd')=='edit'){
					$id = Url::iget('id');
					DB::update('VIP_CARD',$array,'ID='.Url::iget('id'));
				}else{
					$id = DB::insert('VIP_CARD',$array);
				} 
				Url::redirect_current();
			}
		}
	}
	function draw()
	{
		$this->map = array();
		$item = VipCard::$item;
		if($item){
			$item['join_date'] = Date_Time::convert_orc_date_to_date($item['join_date'],'/');		
			foreach($item as $key=>$value){
				if(!isset($_REQUEST[$key])){
					$_REQUEST[strtoupper($key)] = $value;
				}
			}
		}else{
			if(!isset($_REQUEST['code'])){
				$_REQUEST['code'] = time();
			}
		}
		//if(!isset($_REQUEST['discount_percent']))
		{
			$_REQUEST['discount_percent'] = Url::get('card_type_id')?DB::fetch('SELECT * FROM VIP_CARD_TYPE WHERE ID = '.Url::iget('card_type_id'),'discount_percent'):'';
		}
		//if(!isset($_REQUEST['discount_amount']))
		{
			$_REQUEST['discount_amount'] = Url::get('card_type_id')?DB::fetch('SELECT * FROM VIP_CARD_TYPE WHERE ID = '.Url::iget('card_type_id'),'discount_amount'):'';
		}
		$this->map['title'] = (Url::get('cmd')=='add')?Portal::language('add_vip_card'):Portal::language('edit_vip_card');
		$this->map['card_type_id_list'] = array(''=>Portal::language('select'))+String::get_list(DB::select_all('VIP_CARD_TYPE'));
		$this->parse_layout('edit',$this->map);
	}	
}
?>
