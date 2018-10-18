<?php
class ListMemberTravellerForm extends Form
{
    function ListMemberTravellerForm()
    {
        Form::Form('ListMemberTravellerForm');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
		$this->link_js('packages/core/includes/js/jquery/jquery.maskedinput.js');
        $this->link_js('packages/core/includes/js/jquery/media/js/jquery.dataTables.js');
        $this->link_css('packages/core/includes/js/jquery/media/css/jquery.dataTables.css');                        
    }
    function draw()
    {
        if(Url::get("check_delete")){/** kiểm tra lệnh xóa **/
            $list_delete = explode("_",Url::get("list_delete"));
            unset($list_delete[0]);
            foreach($list_delete as $id_delete=>$value_delete){
                DB::delete('traveller','id='.$list_delete[$id_delete]);
            }
        }
        require_once 'packages/core/includes/utils/vn_code.php';
        $cond = "1=1";
        $arr_page = array();
        /** nếu search theo mã thành viên **/
        if(Url::get('member_code') AND (Url::get('member_code')!='')){
            $this->map['member_code'] = Url::get('member_code');
            $_REQUEST['member_code'] = Url::get('member_code');
            $arr_page['member_code'] = Url::get('member_code');
            $cond .= "AND (traveller.member_code = ".Url::get('member_code').")";
        }
        /** nếu search theo ngày tạo mã **/
        if(Url::get('create_date') AND (Url::get('create_date')!='')){
            $this->map['create_date'] = Url::get('create_date');
            $_REQUEST['create_date'] = Url::get('create_date');
            $arr_page['create_date'] = Url::get('create_date');
            $create_date = Date_Time::to_orc_date(Url::get('create_date'));
            $cond .= "AND (traveller.member_create_date = '".$create_date."')";
        }
        /** nếu search theo điểm **/
        if(Url::get('point_from')!=''){
            $this->map['point_from'] = Url::get('point_from');
            $_REQUEST['point_from'] = Url::get('point_from');
            $arr_page['point_from'] =  Url::get('point_from');
            if(Url::get('point_to')!=''){
                $this->map['point_to'] = Url::get('point_to');
                $_REQUEST['point_to'] = Url::get('point_to');
                $arr_page['point_to'] =  Url::get('point_to');
                $cond .= "AND (traveller.point>=".Url::get('point_from')." AND traveller.point<=".Url::get('point_to').")";
            }else{
                $cond .= "AND (traveller.point>=".Url::get('point_from').")";
            }
        }else{
            if(Url::get('point_to')!=''){
                $this->map['point_to'] = Url::get('point_to');
                $_REQUEST['point_to'] = Url::get('point_to');
                $arr_page['point_to'] =  Url::get('point_to');
                $cond .= "AND (traveller.point<=".Url::get('point_to').")";
            }
        }
        /** nếu search theo hạng thành viên**/
        $list_member_level = DB::fetch_all("SELECT id,name FROM member_level ORDER BY name");
        $this->map['member_level_list'] = array('all'=>'---'.Portal::language('all').'---') + String::get_list($list_member_level);
        if(Url::get('member_level') AND (Url::get('member_level')!='all')){
            $this->map['member_level'] = Url::get('member_level');
            $_REQUEST['member_level'] = Url::get('member_level');
            $arr_page['member_level'] =  Url::get('member_level');
            $cond .= "AND (traveller.member_level_id = ".Url::get('member_level').")";
        }
        /** nếu search theo tên khách **/
        if(Url::get('full_name') AND Url::get('full_name')!=''){
            $this->map['full_name'] = Url::get('full_name');
            $_REQUEST['full_name'] = Url::get('full_name');
            //$cond .= "AND (UPPER(traveller.first_name) LIKE '%".strtoupper(Url::get('full_name'))."%' AND UPPER(traveller.last_name) LIKE '%".strtoupper(Url::get('full_name'))."%')";
            $cond .= 'AND ((LOWER(FN_CONVERT_TO_VN(concat(concat(traveller.first_name,\' \'),traveller.last_name))) LIKE \'%'.convert_utf8_to_latin(mb_strtolower(Url::get('full_name'),'utf-8')).'%\'))';
        }
        /** nếu search theo hộ chiếu/cmnd **/
        if(Url::get('passport') AND Url::get('passport')!=''){
            $this->map['passport'] = Url::get('passport');
            $_REQUEST['passport'] = Url::get('passport');
            $arr_page['passport'] =  Url::get('passport');
            $cond .= "AND (UPPER(traveller.passport) LIKE '%".strtoupper(Url::get('passport'))."%')";
        }
        /** nếu search theo quốc tịch **/
        $list_report_country = DB::fetch_all("SELECT id,name_2 as name FROM country WHERE country.selected_report = 1 ORDER BY name_2");
        $this->map['country_list'] = array('all'=>'---'.Portal::language('all').'---','other'=>Portal::language('country_other')) + String::get_list($list_report_country);
        if(Url::get('country') AND (Url::get('country')!='all')){
            $this->map['country'] = Url::get('country');
            $_REQUEST['country'] = Url::get('country');
            $arr_page['country'] =  Url::get('country');
            if(Url::get('country')=='other'){
                $cond .= "AND (traveller.nationality_id = 1)";
            }else{
                $cond .= "AND (traveller.nationality_id = ".Url::get('country').")";
            }
        }
        /** nếu search theo giới tính **/
        $this->map['gender_list'] = array('all'=>'---'.Portal::language('all').'---','1'=>'Nam','0'=>'Nữ');
        if((Url::get('gender')!='all') AND (Url::get('gender')!='')){
            $this->map['gender'] = Url::get('gender');
            $_REQUEST['gender'] = Url::get('gender');
            $arr_page['gender'] =  Url::get('gender');
            $cond .= "AND (traveller.gender = ".Url::get('gender').")";
        }
        /** nếu search theo email **/
        if(Url::get('email') AND Url::get('email')!=''){
            $this->map['email'] = Url::get('email');
            $_REQUEST['email'] = Url::get('email');
            $arr_page['email'] =  Url::get('email');
            $cond .= "AND (traveller.email = '".Url::get('email')."')";
        }
        $this->map['email_traveller_list'] = array('all'=>'---'.Portal::language('all').'---','YES'=>Portal::language('email_complate'),'NO'=>Portal::language('no_email'));
        if((Url::get('email_traveller')!='all') AND (Url::get('email_traveller')!=''))
        {
            $this->map['email_traveller'] = Url::get('email_traveller');
            $_REQUEST['email_traveller'] = Url::get('email_traveller');
            $arr_page['email_traveller'] =  Url::get('email_traveller');
            if(Url::get('email_traveller')=='YES')
                $cond .= "AND (traveller.email is not null)";
            else
                $cond .= "AND (traveller.email is null)";
        }
        /** nếu search theo phone **/
        if(Url::get('phone') AND Url::get('phone')!=''){
            $this->map['phone'] = Url::get('phone');
            $_REQUEST['phone'] = Url::get('phone');
            $arr_page['phone'] =  Url::get('phone');
            $cond .= "AND (traveller.phone = '".Url::get('phone')."')";
        }
        $ORCL = '
			SELECT 
				count(*) as id
			FROM 
				traveller
                left join country on traveller.nationality_id=country.id
                left join member_level on member_level.id=traveller.member_level_id
			WHERE '.$cond.'
		';
		$count = DB::fetch($ORCL);
        $item_per_page = 20;
        if(Url::get('page_no')){
            $page_no = Url::get('page_no');
        }else{
            $page_no = 1;
        }
        $page_name = 'traveller&cmd=list_member';
		require_once 'packages/user/modules/log/paging_new.php';
		$paging = paging_new($count['id'],$item_per_page,$page_name,$arr_page,$page_no);
        //echo $cond;
        /** end $cond **/
        $sql = "
            SELECT * FROM
                (
                SELECT 
                    traveller.id,
                    concat(concat(traveller.first_name,' '),traveller.last_name) as full_name,
                    traveller.passport,
                    traveller.gender,
                    country.name_2,
                    traveller.email,
                    traveller.phone,
                    traveller.member_code,
                    traveller.member_create_date,
                    traveller.point,
                    traveller.point_user,
                    member_level.def_name,
                    row_number() over (order by NVL(traveller.member_code,0) DESC) as rownumber
                FROM
                    traveller
                    left join country on traveller.nationality_id=country.id
                    left join member_level on member_level.id=traveller.member_level_id
                WHERE
                    ".$cond."
                ORDER BY NVL(traveller.member_code,0) DESC, traveller.first_name ASC, traveller.last_name ASC
                )
            WHERE
                rownumber > ".(($page_no-1)*$item_per_page)." and rownumber<=".(($page_no)*$item_per_page)."
                ";
        $report = DB::fetch_all($sql);
        foreach($report as $id=>$value){
            $report[$id]['member_create_date'] = Date_Time::convert_orc_date_to_date($value['member_create_date'],"/");
        }
        $this->map['list_items'] = String::array2js($report);
        $this->parse_layout('list_member',array('items'=>$report,'paging'=>$paging)+$this->map);
    }
}
?>