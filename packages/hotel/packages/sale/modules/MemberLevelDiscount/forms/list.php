<?php
class MemberLevelDiscountForm extends Form
{
	function MemberLevelDiscountForm()
	{
		Form::Form('MemberLevelDiscountForm');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
        $this->link_js('packages/core/includes/js/jquery/jquery.maskedinput.js');
        $this->link_js('packages/core/includes/js/jquery/jquery.battatech.excelexport.js');
	}	
	function draw()
	{
	   require_once 'packages/hotel/includes/member.php';
       require_once 'packages/core/includes/utils/vn_code.php';
       $cond = '1=1';
	   $this->map = array();
       $pin_service_list = array(''=>array('id'=>'','name'=>Portal::language('select')));
       $pin_service_list += DB::fetch_all("
                                            SELECT 
                                                department.code as id,
                                                department.name_".Portal::language()." as name
                                            FROM 
                                                department
                                            WHERE
                                                department.check_access_control = 1
                                        ");
       $this->map['access_pin_service_list'] = String::get_list($pin_service_list);
       $member_level_list = array(''=>array('id'=>'','name'=>Portal::language('select')));
       $member_level_list += DB::fetch_all("
                                            SELECT 
                                                member_level.id as id,
                                                member_level.name as name
                                            FROM 
                                                member_level
                                        ");
       
       $this->map['member_level_list'] = String::get_list($member_level_list);
       
       $this->map['is_parent_list'] = array(''=>Portal::language('select'),'PARENT'=>Portal::language('parent_card'),'SON'=>Portal::language('son_card'));
       
       $_REQUEST['from_date'] = Url::get('from_date')?Url::get('from_date'):date('d/m/Y');
       $_REQUEST['to_date'] = Url::get('to_date')?Url::get('to_date'):date('d/m/Y');
       
       $cond .= ' AND member_level_discount.start_date<=\''.Date_Time::to_orc_date($_REQUEST['to_date']).'\' AND member_level_discount.end_date>=\''.Date_Time::to_orc_date($_REQUEST['from_date']).'\'';
       $cond .= Url::get('access_pin_service')?' AND member_discount.access_pin_service_code=\''.Url::get('access_pin_service').'\'':'';
       $cond .= Url::get('member_level')?' AND member_level_discount.member_level_id='.Url::get('member_level').'':'';
       $cond .= Url::get('is_parent')?' AND member_discount.is_parent=\''.Url::get('is_parent').'\'':'';
       $this->map['items'] = DB::fetch_all("
                                    SELECT
                                        member_level_discount.*
                                        ,TO_CHAR(member_level_discount.start_date,'DD/MM/YYYY') as start_date
                                        ,TO_CHAR(member_level_discount.end_date,'DD/MM/YYYY') as end_date
                                        ,member_level.name as member_level_name
                                        ,department.name_".Portal::language()." as access_control_name
                                        ,member_discount.code as member_discount_code
                                        ,member_discount.title as member_discount_title
                                        ,member_discount.description as member_discount_description
                                        ,member_discount.num_people as member_discount_num_people
                                        ,member_discount.operator as member_discount_operator
                                        ,member_discount.is_parent as member_discount_is_parent
                                    FROM
                                        member_level_discount
                                        inner join member_level on member_level_discount.member_level_id=member_level.id
                                        inner join member_discount on member_level_discount.member_discount_code=member_discount.code
                                        inner join department on department.code=member_discount.access_pin_service_code
                                    WHERE
                                        ".$cond."
                                    ORDER BY member_discount.access_pin_service_code,member_level_discount.start_date DESC, member_level_discount.end_date DESC
                                    ");
       //System::debug($this->map);
        $this->parse_layout('list',$this->map);
	}
}
?>