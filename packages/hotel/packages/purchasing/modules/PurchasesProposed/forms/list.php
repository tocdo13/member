<?php
class ListPurchasesProposedForm extends Form
{
	function ListPurchasesProposedForm()
	{
		Form::Form('ListPurchasesProposedForm');
		$this->link_css(Portal::template('hotel').'/css/style.css');
        $this->link_css('packages/core/skins/default/css/jquery.autocomplete.css');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
        $this->link_js('packages/core/includes/js/jquery/jquery.autocomplete.js');
        $this->link_js('packages/core/includes/js/jquery/datepicker.js');
	}
	function draw()
	{
       $user = DB::fetch('select party.name_'.Portal::language().' as name, party.description_1 as department from party where user_id=\''.User::id().'\'');
       $cond = ' (purchases_proposed.department=\''.$user['department'].'\')';
       $this->map=array();
	   if(Url::get('creater'))
       {
            $cond .= ' AND (purchases_proposed.creater=\''.Url::get('creater').'\')';
            $this->map['creater'] = Url::get('creater');
       }
       if(Url::get('confirm_user'))
       {
            $cond .= ' AND (purchases_proposed.confirm_user=\''.Url::get('confirm_user').'\')';
            $this->map['confirm_user'] = Url::get('confirm_user');
       }
       if(Url::get('from_date'))
       {
            $cond .= ' AND (purchases_proposed.create_time>='.(Date_Time::to_time(Url::get('from_date'))+$this->calc_time('00:00')).')';
            $this->map['from_date'] = Url::get('from_date');
       }
       if(Url::get('to_date'))
       {
            $cond .= ' AND (purchases_proposed.create_time<='.(Date_Time::to_time(Url::get('to_date'))+$this->calc_time('23:59')).')';
            $this->map['to_date'] = Url::get('to_date');
       }
       if(Url::get('status'))
       {
            $cond .= ' AND (purchases_proposed.status=\''.Url::get('status').'\')';
            $this->map['status'] = Url::get('status');
       }
       $cond .= 'AND (purchases_proposed.status=\'CONFIRMING\' OR purchases_proposed.status=\'CONFIRM\' OR purchases_proposed.status=\'CANCEL\')';
	   $items = DB::fetch_all('SELECT purchases_proposed.* from purchases_proposed where '.$cond.' order by id');
       $i=1;
       foreach($items as $key=>$value)
       {
            $items[$key]['creater'] = DB::fetch('SELECT party.name_'.Portal::language().' as name from purchases_proposed inner join party on party.user_id=purchases_proposed.creater where purchases_proposed.creater=\''.$value['creater'].'\'','name');
            $items[$key]['confirm_user'] = DB::fetch('SELECT party.name_'.Portal::language().' as name from purchases_proposed inner join party on party.user_id=purchases_proposed.confirm_user where purchases_proposed.confirm_user=\''.$value['confirm_user'].'\'','name');
            $items[$key]['adjustment'] = DB::fetch('SELECT party.name_'.Portal::language().' as name from purchases_proposed inner join party on party.user_id=purchases_proposed.adjustment where purchases_proposed.adjustment=\''.$value['adjustment'].'\'','name');
            $items[$key]['create_time'] = date('H:i d/m/Y',$value['create_time']);
            $items[$key]['confirm_time'] = $value['confirm_time']!=''?date('H:i d/m/Y',$value['confirm_time']):'';
            $items[$key]['adjustment_time'] = $value['adjustment_time']!=''?date('H:i d/m/Y',$value['adjustment_time']):'';
            $items[$key]['stt'] = $i++;
       }
       $creater = DB::fetch_all('SELECT party.user_id as id, party.NAME_'.Portal::language().' as name FROM party ');
       $this->map['creater_list'] = array(''=>'--ALL--') + String::get_list($creater);
       $this->map['confirm_user_list'] = array(''=>'--ALL--') + String::get_list($creater);
       $this->map['status_list'] = array(''=>'--ALL--','CONFIRMING'=>Portal::language('confirming'),'CONFIRM'=>Portal::language('confirm_1'),'CANCEL'=>Portal::language('cancel'));
       $this->parse_layout('list',array('items'=>$items)+$this->map);
	}
    function calc_time($string)
    {
        $arr = explode(':',$string);
        return $arr[0]*3600 + $arr[1]*60;
    }	
}
?>