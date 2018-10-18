<?php
class MemberLevelDiscountForm extends Form
{
	function MemberLevelDiscountForm()
	{
		Form::Form('MemberLevelDiscountForm');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
	}
	function on_submit()
	{
        if(Url::get('save_stay') OR Url::get('save'))
        {
            if(Url::get('delete_ids')!='')
            {
                $list_delete = DB::fetch_all("SELECT 
                                                member_level_discount.*,
                                                TO_CHAR(member_level_discount.start_date,'DD/MM/YYYY') as start_date,
                                                TO_CHAR(member_level_discount.end_date,'DD/MM/YYYY') as end_date,
                                                member_discount.title,
                                                member_discount.code as member_discount_code 
                                            FROM 
                                                member_level_discount 
                                                inner join member_discount on member_discount.code=member_level_discount.member_discount_code 
                                            WHERE
                                                member_level_discount.id in (".Url::get('delete_ids').")
                                                ");
                $description_delete = '';
                foreach($list_delete as $key=>$value)
                {
                    $description_delete .= '<b>DELETE member level discount: id#'.$value['id'].'</b><br/>';
                    $description_delete .= 'detail: <b>Code:</b> '.$value['member_discount_code'].'<b>Title:</b> '.$value['title'].'<b>start date:</b> '.$value['start_date'].'<b>end date:</b> '.$value['end_date'].'<br/>';
                }
                DB::delete('member_level_discount','id in ('.Url::get('delete_ids').')');
                System::log('DELETE','DELETE discount in level ID: #'.Url::get('level_id'),$description_delete,Url::get('level_id'));
            }
            if(isset($_REQUEST['items']))
            {
                $description = '';
                foreach($_REQUEST['items'] as $key=>$value)
                {
                    $value['start_date'] = Date_Time::to_orc_date($value['start_date']);
                    $value['end_date'] = Date_Time::to_orc_date($value['end_date']);
                    $value['member_level_id'] = Url::get('level_id');
                    if($value['id']!='(auto)')
                    {
                        $discount = DB::fetch("SELECT * FROM member_discount WHERE code='".$value['member_discount_code']."'");
                        $description .= '<b>Edit member level discount: id#'.$value['id'].'</b><br/>';
                        $description .= 'detail: <b>Code:</b> '.$value['member_discount_code'].'<b>Title:</b> '.$discount['title'].'<b>start date:</b> '.$value['start_date'].'<b>end date:</b> '.$value['end_date'].'<br/>';
                        $id = $value['id'];
                        unset($value['id']);
                        DB::update("member_level_discount",$value,'id='.$id);
                    }
                    else
                    {
                        $discount = DB::fetch("SELECT * FROM member_discount WHERE code='".$value['member_discount_code']."'");
                        unset($value['id']);
                        $id = DB::insert("member_level_discount",$value);
                        $description .= '<b>Add member level discount: id#'.$id.'</b><br/>';
                        $description .= 'detail: <b>Code:</b> '.$value['member_discount_code'].'<b>Title:</b> '.$discount['title'].'<b>start date:</b> '.$value['start_date'].'<b>end date:</b> '.$value['end_date'].'<br/>';
                    }
                }
                System::log('EDIT','Edit discount in level ID: #'.Url::get('level_id'),$description,Url::get('level_id'));
            }
            if(Url::get('save_stay'))
            {
                 Url::redirect('member_level',array());
            }
            else
            {
                 Url::redirect('member_discount',array('cmd'=>'add_level','level_id'=>Url::get('level_id')));
            }
        }
	}	
	function draw()
	{
         $this->map = array();
         
         $list_discount = DB::fetch_all("SELECT 
                                            member_discount.*
                                            ,TO_CHAR(member_discount.start_date,'DD/MM/YYYY') as start_date
                                            ,TO_CHAR(member_discount.end_date,'DD/MM/YYYY') as end_date
                                        FROM 
                                            member_discount 
                                        WHERE
                                            member_discount.end_date >='".Date_Time::to_orc_date(date('d/m/Y'))."'
                                            OR
                                            member_discount.end_date is null
                                        ");
         $this->map['list_discount'] = $list_discount;
         $this->map['list_discount_js'] = String::array2js($list_discount);
         $list_member_discount = DB::fetch_all("SELECT member_level_discount.*,TO_CHAR(member_level_discount.start_date,'DD/MM/YYYY') as start_date,TO_CHAR(member_level_discount.end_date,'DD/MM/YYYY') as end_date,member_discount.title FROM member_level_discount inner join member_discount on member_discount.code=member_level_discount.member_discount_code WHERE member_level_discount.member_level_id=".Url::get('level_id'));
         $this->map['items'] = $list_member_discount;
         
         $this->parse_layout('add_level',$this->map);
	}
}
?>
