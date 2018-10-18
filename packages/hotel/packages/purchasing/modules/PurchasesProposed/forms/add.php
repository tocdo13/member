<?php
class AddPurchasesProposedForm extends Form
{
	function AddPurchasesProposedForm()
	{
		Form::Form('AddPurchasesProposedForm');
		$this->link_css(Portal::template('hotel').'/css/style.css');
		$this->link_js('packages/core/includes/js/multi_items.js');
		$this->link_js('packages/core/includes/js/jquery/jquery.autocomplete.js');
		$this->link_css('packages/core/skins/default/css/jquery.autocomplete.css');
        $this->link_js('packages/core/includes/js/jquery/jquery.maskedinput.js');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
	}
	function on_submit()
    {
        if(Url::get('save') OR Url::get('save_stay'))
        {
            $purchases = array(
                                'portal_id'=>PORTAL_ID,
                                'creater'=>User::id(),
                                'create_time'=>(Date_Time::to_time(date('d/m/Y'))+$this->calc_time(date('H:i'))),
                                'department'=>Url::get('department'),
                                'status'=>'CONFIRMING',
                                'description'=>Url::get('description')
                                );
            DB::insert('purchases_proposed',$purchases);
            if(Url::get('save_stay'))
            {
                Url::redirect_current(array('cmd'=>'add'));
            }
        }
        Url::redirect_current(array('cmd'=>'list'));
	}
	function draw()
	{
	   $this->map['create_date'] = date('d/m/Y');
       $user = DB::fetch('select party.name_'.Portal::language().' as name, party.description_1 as department from party where user_id=\''.User::id().'\'');
       $this->map['creater'] = $user['name'];
       $this->map['department'] = $user['department'];
       $_REQUEST += $this->map;
	   $this->parse_layout('add',$this->map);
	}
    function calc_time($string)
    {
        $arr = explode(':',$string);
        return $arr[0]*3600 + $arr[1]*60;
    }	
}
?>
