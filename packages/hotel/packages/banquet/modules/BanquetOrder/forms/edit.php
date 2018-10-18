<?php
class EditBanquetOrderForm extends Form
{
	function EditBanquetOrderForm()
	{
		Form::Form('EditBanquetOrderForm');
		$this->add('full_name',new TextType(true,'invalid_agent_name',0,255));
		$this->add('party_type',new IntType(true,'miss_banquet_type','0','100000000000'));		
		$this->add('product.product_id',new TextType(true,'miss_product_id',0,255));
        $this->add('checkin_hour',new TextType(true,'checkin_hour',1,5));
        $this->add('checkout_hour',new TextType(true,'checkout_hour',1,5)); 
        $this->add('checkin_date',new TextType(true,'checkin_date',1,20)); 
		$this->link_css('skins/default/restaurant.css');		 
		$this->link_css('packages/core/skins/default/css/jquery.autocomplete.css');
		$this->link_js('packages/core/includes/js/jquery/jquery.autocomplete.js');
		$this->link_js('packages/core/includes/js/multi_items.js');
		$this->link_js('packages/hotel/packages/restaurant/includes/js/update_price_party.js');
		$this->link_js('cache/data/default/NH01_default.js');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
		$this->link_js('packages/core/includes/js/jquery/jquery.maskedinput.js');
		
	}
	function on_submit()
	{
	}	
	function draw()
	{
		require_once 'packages/hotel/packages/restaurant/includes/table.php';
		require_once 'packages/hotel/includes/php/hotel.php';
        $this->map['party_category_list'] = array(
			'ROOM_PRICE'=>Portal::language('room_price'),
			'FULL_PRICE'=>Portal::language('full_price')
		);
        $this->map['party_type_list'] = array(''=>Portal::language('Choose_party_type')) + String::get_list(DB::select_all("party_type"));
        $this->map['date'] =  date('d/m/Y');	
		$this->map['time_type_list'] = array(
    			'DAY'=>Portal::language('one_day'),
    			'HALF_DAY'=>Portal::language('half_day')
    		);
        $this->map['status_list'] =  array(
			'BOOKED'=>Portal::language('booked'),
			'CHECKIN'=>Portal::language('checkin'),
			'CHECKOUT'=>Portal::language('checkout'),
			);
		$this->parse_layout('edit',$this->map);
    }
}
?>