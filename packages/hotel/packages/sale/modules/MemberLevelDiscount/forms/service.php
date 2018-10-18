<?php
class ServiceAccessControlForm extends Form
{
	function ServiceAccessControlForm()
	{
		Form::Form('ServiceAccessControlForm');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
        $this->link_js('packages/core/includes/js/jquery/jquery.maskedinput.js');
	}
    function on_submit()
    {
        if(Url::get('check_in'))
        {
            require_once 'packages/hotel/includes/member.php';
            $array = array(
                            'time'=>time(),
                            'in_date'=>Date_Time::to_orc_date(date('d/m/Y')),
                            'member_code'=>validate_member_code(Url::get('member_code')),
                            'creater'=>User::id()
                            );
            DB::insert('access_control_history',$array);
            Url::redirect('access_control');
        }
    }	
	function draw()
	{
	   require_once 'packages/hotel/includes/member.php';
	   $this->map = array();
       if(Url::get('member_code') AND DB::exists("SELECT id FROM traveller WHERE member_code='".validate_member_code(Url::get('member_code'))."'"))
       {
            $this->map += DB::fetch("
                                    SELECT
                                        traveller.*,
                                        to_char(traveller.birth_date,'DD/MM/YYYY') as birth_date,
                                        to_char(traveller.member_create_date,'DD/MM/YYYY') as member_create_date,
                                        member_level.def_name as member_level_def_name,
                                        member_level.name as member_level_name,
                                        member_level.logo as member_level_logo,
                                        country.name_1 as country_name
                                    FROM
                                        traveller
                                        inner join member_level on member_level.id=traveller.member_level_id
                                        left join country on country.id = traveller.nationality_id
                                    WHERE
                                        member_code='".validate_member_code(Url::get('member_code'))."'
                                    ");
            $to_day = Date_Time::to_orc_date(date('d/m/Y'));
            $this->map['items'] = DB::fetch_all("
                                    SELECT
                                    member_level_discount.id,
                                    member_discount.code,
                                    member_discount.title,
                                    member_discount.description,
                                    to_char(member_level_discount.start_date,'DD/MM/YYYY') as start_date,
                                    to_char(member_level_discount.end_date,'DD/MM/YYYY') as end_date
                                FROM
                                    member_level_discount
                                    inner join member_discount on member_discount.code=member_level_discount.member_discount_code
                                WHERE
                                    member_level_discount.member_level_id=".$this->map['member_level_id']."
                                    AND member_level_discount.start_date<='".$to_day."'
                                    AND member_level_discount.end_date>='".$to_day."'
                                    ");
            $this->map['history'] = DB::fetch_all("
                                    SELECT
                                    access_control_history.*
                                    FROM
                                    access_control_history
                                    WHERE
                                    access_control_history.member_code='".validate_member_code(Url::get('member_code'))."'
                                    AND
                                    access_control_history.in_date = '".Date_Time::to_orc_date(date('d/m/Y'))."'
                                    ORDER BY access_control_history.time DESC
                                    ");
            
       }
	   $this->parse_layout('list',$this->map);
	}
}
?>