<?php
class EditPurchasesProposedForm extends Form
{
	function EditPurchasesProposedForm()
	{
		Form::Form('EditPurchasesProposedForm');
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
        if(Url::get('confirm') OR Url::get('confirming') OR Url::get('cancel'))
        {
            if(Url::get('confirm'))
            {
                $status = 'CONFIRM';
            }
            elseif(Url::get('confirming'))
            {
                $status = 'CONFIRMING';
            }
            else
            {
                $status = 'CANCEL';
            }
            $purchases = array(
                                'status'=>$status,
                                'confirm_user'=>User::id(),
                                'confirm_time'=>(Date_Time::to_time(date('d/m/Y'))+$this->calc_time(date('H:i'))),
                                'last_edit_user'=>User::id(),
                                'last_edit_time'=>(Date_Time::to_time(date('d/m/Y'))+$this->calc_time(date('H:i'))),
                                'description'=>Url::get('description')
                                );
            DB::update('purchases_proposed',$purchases,'id='.Url::get('id'));
            echo "<script>";
            echo "window.opener.location.reload();";
            echo "window.close()";
            echo "</script>";
        }
        else
        {
             $purchases = array('description'=>Url::get('description'));
             DB::update('purchases_proposed',$purchases,'id='.Url::get('id'));
             if(Url::get('save'))
             {
                Url::redirect_current(array('cmd'=>'edit','id'=>Url::get('id')));
             }
             else
             {
                Url::redirect_current(array('cmd'=>'list'));
             }
        }
        
	}
	function draw()
	{
	   if(Url::get('id') AND DB::exists('SELECT id from purchases_proposed where id='.Url::get('id')))
       {
            $this->map = DB::fetch('SELECT * from purchases_proposed where id='.Url::get('id'));
            $this->map['create_date'] = date('d/m/Y',$this->map['create_time']);
            $this->map['creater'] = DB::fetch('SELECT party.name_'.Portal::language().' as name from purchases_proposed inner join party on party.user_id=purchases_proposed.creater where purchases_proposed.creater=\''.$this->map['creater'].'\'','name');
            $this->map['confirm_user'] = DB::fetch('select party.name_'.Portal::language().' as name, party.description_1 as department from party where user_id=\''.User::id().'\'','name');
            $this->map['status_check'] = $this->map['status'];
            if($this->map['status']=='CONFIRMING')
            {
                $this->map['status'] = Portal::language('confirming');
            }
            if($this->map['status']=='CONFIRM')
            {
                $this->map['status'] = Portal::language('confirm_1');
            }
            if($this->map['status']=='CANCEL')
            {
                $this->map['status'] = Portal::language('cancel');
            }
            $_REQUEST += $this->map;
            $this->parse_layout('edit',$this->map);
       }
       else
       {
            Url::redirect_current(array('cmd'=>'list'));
       }
	}
    function calc_time($string)
    {
        $arr = explode(':',$string);
        return $arr[0]*3600 + $arr[1]*60;
    }	
}
?>
