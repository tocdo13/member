<?php
class ListMemberTravellerForm extends Form
{
    function ListMemberTravellerForm()
    {
        Form::Form('ListMemberTravellerForm');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
		$this->link_js('packages/core/includes/js/jquery/jquery.maskedinput.js');                       
    }
    function draw()
    {
        require_once 'packages/hotel/includes/member.php';
        if(Url::get("check_delete"))
        {/** kiểm tra lệnh xóa **/
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
            $cond .= 'AND (traveller.member_code like \'%'.validate_member_code(Url::get('member_code')).'%\')';
        }
        /** nếu search theo mã thành viên **/
        if(Url::get('traveller_code') AND (Url::get('traveller_code')!='')){
            $this->map['traveller_code'] = Url::get('traveller_code');
            $_REQUEST['traveller_code'] = Url::get('traveller_code');
            $arr_page['traveller_code'] = Url::get('traveller_code');
            $cond .= 'AND (traveller.traveller_code like \'%'.(Url::get('traveller_code')).'%\')';
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
        /** nếu search theo hạng thành viên**/
        $this->map['card_type_list'] = array('all'=>'---'.Portal::language('all').'---','PARENT'=>Portal::language('parent_card'),'SON'=>Portal::language('son_card'));
        if(Url::get('card_type') AND (Url::get('card_type')!='all')){
            $this->map['card_type'] = Url::get('card_type');
            $_REQUEST['card_type'] = Url::get('card_type');
            $arr_page['card_type'] =  Url::get('card_type');
            if(Url::get('card_type')=='PARENT')
            $cond .= "AND (traveller.is_parent_id is null)";
            else
            $cond .= "AND (traveller.is_parent_id is not null)";
        }
        /** nếu search theo nhóm thành viên**/
        $list_group_traveller = DB::fetch_all("SELECT id,name FROM group_traveller ORDER BY name");
        $this->map['group_traveller_list'] = array('all'=>'---'.Portal::language('all').'---') + String::get_list($list_group_traveller);
        if(Url::get('group_traveller') AND (Url::get('group_traveller')!='all')){
            $this->map['group_traveller'] = Url::get('group_traveller');
            $_REQUEST['group_traveller'] = Url::get('group_traveller');
            $arr_page['group_traveller'] =  Url::get('group_traveller');
            $cond .= "AND (traveller.group_traveller_id = ".Url::get('group_traveller').")";
        }
        
        /** nếu search theo trạng thái thành viên**/
        $list_status_traveller = DB::fetch_all("SELECT id,name FROM status_traveller ORDER BY name");
        $this->map['status_traveller_list'] = array('all'=>'---'.Portal::language('all').'---') + String::get_list($list_status_traveller);
        if(Url::get('status_traveller') AND (Url::get('status_traveller')!='all')){
            $this->map['status_traveller'] = Url::get('status_traveller');
            $_REQUEST['status_traveller'] = Url::get('status_traveller');
            $arr_page['status_traveller'] =  Url::get('status_traveller');
            $cond .= "AND (traveller.status_traveller_id = ".Url::get('status_traveller').")";
        }
        
        /** nếu search theo tên khách **/
        if(Url::get('full_name') AND Url::get('full_name')!=''){
            $this->map['full_name'] = Url::get('full_name');
            $_REQUEST['full_name'] = Url::get('full_name');
            //$cond .= "AND (UPPER(traveller.first_name) LIKE '%".strtoupper(Url::get('full_name'))."%' AND UPPER(traveller.last_name) LIKE '%".strtoupper(Url::get('full_name'))."%')";
            $cond .= 'AND ((LOWER(FN_CONVERT_TO_VN(concat(concat(TRIM(traveller.first_name),\' \'),TRIM(traveller.last_name)))) LIKE \'%'.convert_utf8_to_latin(mb_strtolower(trim(Url::get('full_name')),'utf-8')).'%\'))';
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
			WHERE '.$cond.'  AND traveller.is_traveller != 1
		';
		$count = DB::fetch($ORCL);
        $item_per_page = 20;
        if(Url::get('page_no')){
            $page_no = Url::get('page_no');
        }else{
            $page_no = 1;
        }
        $page_name = 'traveller&cmd=list_member';
		require_once 'packages/user/modules/Log/paging_new.php';
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
                    traveller.plot_code,
                    traveller.phone,
                    traveller.member_code,
                    traveller.member_create_date,
                    traveller.point,
                    traveller.point_user,
                    traveller.is_parent_id,
                    traveller.traveller_code,
                    member_level.def_name,
                    traveller.group_traveller_id,
                    group_traveller.name as group_traveller_name,
                    status_traveller.name as status_traveller_name,
                    CASE
                        WHEN traveller.image_profile is null 
                        THEN
                            CASE
                                WHEN traveller.gender=1
                                THEN 'no_avata_boy.jpg'
                                ELSE 'no_avatar_girl.jpg'
                                END
                        ELSE
                            traveller.image_profile
                    END image_profile,
                    row_number() over (order by NVL(traveller.member_code,0) DESC) as rownumber
                FROM
                    traveller
                    left join country on traveller.nationality_id=country.id
                    left join member_level on member_level.id=traveller.member_level_id
                    left join group_traveller on group_traveller.id=traveller.group_traveller_id
                    left join status_traveller on status_traveller.id=traveller.status_traveller_id
                WHERE
                    ".$cond." AND traveller.is_traveller != 1
                ORDER BY NVL(traveller.member_code,0) DESC, traveller.first_name ASC, traveller.last_name ASC
                )
            WHERE
                rownumber > ".(($page_no-1)*$item_per_page)." and rownumber<=".(($page_no)*$item_per_page)."
                ";
        $report = DB::fetch_all($sql);
        foreach($report as $id=>$value)
        {
            $report[$id]['member_create_date'] = Date_Time::convert_orc_date_to_date($value['member_create_date'],"/");
        }
        $this->map['list_items'] = String::array2js($report);
        $this->parse_layout('list_member',array('items'=>$report,'paging'=>$paging)+$this->map);
        /** Kimtan them **/
        if(Url::get('export_file_excel'))
        {
            $this->export_file_excel($cond);
        }
        /** Kimtan them **/
    }
    function export_file_excel($cond)
    {
        require_once ROOT_PATH.'packages/core/includes/utils/PHPExcel.php';		
  		require_once ROOT_PATH.'packages/core/includes/utils/PHPExcel/RichText.php';
  		include ROOT_PATH.'packages/core/includes/utils/PHPExcel/IOFactory.php';
        
        $objPHPExcel = new PHPExcel();
		$objPHPExcel->setActiveSheetIndex(0);
        
        //tieu de
        $i = 1;
        $objPHPExcel->getActiveSheet()->setCellValue('A'.$i, 'STT');
		$objPHPExcel->getActiveSheet()->setCellValue('B'.$i, 'Họ tên');
		$objPHPExcel->getActiveSheet()->setCellValue('C'.$i, 'Ngày sinh');
		$objPHPExcel->getActiveSheet()->setCellValue('D'.$i, 'Giới tính');
		$objPHPExcel->getActiveSheet()->setCellValue('E'.$i, 'Quốc tịch');
		$objPHPExcel->getActiveSheet()->setCellValue('F'.$i, 'HChiếu/CMND');
		$objPHPExcel->getActiveSheet()->setCellValue('G'.$i, 'Địa chỉ');
        $objPHPExcel->getActiveSheet()->setCellValue('H'.$i, 'Email');
		$objPHPExcel->getActiveSheet()->setCellValue('I'.$i, 'Đện thoại');
		$objPHPExcel->getActiveSheet()->setCellValue('J'.$i, 'Fax');
		$objPHPExcel->getActiveSheet()->setCellValue('K'.$i, 'Mã lô');
		$objPHPExcel->getActiveSheet()->setCellValue('L'.$i, 'Mã từ');
        $objPHPExcel->getActiveSheet()->setCellValue('M'.$i, 'Mã thành viên');
		$objPHPExcel->getActiveSheet()->setCellValue('N'.$i, 'Mã thành viên chính');
        $objPHPExcel->getActiveSheet()->setCellValue('O'.$i, 'Ngày phát hành');
        $objPHPExcel->getActiveSheet()->setCellValue('P'.$i, 'Ngày hiệu lực');
        $objPHPExcel->getActiveSheet()->setCellValue('Q'.$i, 'Ngày hết hạn');
        $objPHPExcel->getActiveSheet()->setCellValue('R'.$i, 'Hạng thành viên');
        $objPHPExcel->getActiveSheet()->setCellValue('S'.$i, 'Nhóm thành viên');
        $objPHPExcel->getActiveSheet()->setCellValue('T'.$i, 'Trạng thái');
        $objPHPExcel->getActiveSheet()->setCellValue('U'.$i, 'Ghi chú');
        $export = DB::fetch_all("
            SELECT 
                    traveller.id,
                    concat(concat(traveller.first_name,' '),traveller.last_name) as full_name,
                    traveller.passport,
                    traveller.gender,
                    to_char(traveller.birth_date,'DD/MM/YYYY') as birth_date,
                    traveller.address,
                    country.name_2,
                    traveller.email,
                    traveller.plot_code,
                    traveller.fax,
                    traveller.phone,
                    traveller.member_code,
                    traveller.is_parent_id,
                    to_char(traveller.member_create_date,'DD/MM/YYYY') as member_create_date,
                    traveller.point,
                    traveller.note,
                    to_char(traveller.releases_date,'DD/MM/YYYY') as releases_date,
                    to_char(traveller.effective_date,'DD/MM/YYYY') as effective_date,
                    to_char(traveller.expiration_date,'DD/MM/YYYY') as expiration_date,
                    traveller.point_user,
                    traveller.is_parent_id,
                    traveller.traveller_code,
                    member_level.def_name,
                    traveller.group_traveller_id,
                    group_traveller.name as group_traveller_name,
                    status_traveller.name as status_traveller_name,
                    row_number() over (order by NVL(traveller.member_code,0) DESC) as rownumber
                FROM
                    traveller
                    left join country on traveller.nationality_id=country.id
                    left join member_level on member_level.id=traveller.member_level_id
                    left join group_traveller on group_traveller.id=traveller.group_traveller_id
                    left join status_traveller on status_traveller.id=traveller.status_traveller_id
                WHERE
                    ".$cond."
                ORDER BY NVL(traveller.member_code,0) DESC, traveller.first_name ASC, traveller.last_name ASC
        ");
        $i++;
		foreach($export as $key=>$value)
		{
		    $value['gender']=$value['gender']?Portal::language('male'):Portal::language('female');
		    //$value['birth_date']=$value['birth_date']?Date_time::convert_orc_date_to_date($value['birth_date'],'/'):'';
            //$value['releases_date']=$value['releases_date']?Date_time::convert_orc_date_to_date($value['releases_date'],'/'):'';
            //$value['effective_date']=$value['effective_date']?Date_time::convert_orc_date_to_date($value['effective_date'],'/'):'';
            //$value['expiration_date']=$value['expiration_date']?Date_time::convert_orc_date_to_date($value['expiration_date'],'/'):'';
            $value['member_code']=$value['member_code']?$value['member_code']:'No code';
            //gÃ¡n cÃ¡c truong
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$i, $i-1);
    		$objPHPExcel->getActiveSheet()->setCellValue('B'.$i, $value['full_name']);
    		$objPHPExcel->getActiveSheet()->setCellValue('C'.$i, $value['birth_date']);
    		$objPHPExcel->getActiveSheet()->setCellValue('D'.$i, $value['gender']);
    		$objPHPExcel->getActiveSheet()->setCellValue('E'.$i, $value['name_2']);
    		$objPHPExcel->getActiveSheet()->setCellValue('F'.$i, $value['passport']);
    		$objPHPExcel->getActiveSheet()->setCellValue('G'.$i, $value['address']);
            $objPHPExcel->getActiveSheet()->setCellValue('H'.$i, $value['email']);
    		$objPHPExcel->getActiveSheet()->setCellValue('I'.$i, $value['phone']);
    		$objPHPExcel->getActiveSheet()->setCellValue('J'.$i, $value['fax']);
    		$objPHPExcel->getActiveSheet()->setCellValue('K'.$i, $value['plot_code']);
    		$objPHPExcel->getActiveSheet()->setCellValue('L'.$i, $value['member_code']);
    		$objPHPExcel->getActiveSheet()->setCellValue('M'.$i, $value['traveller_code']);
            $objPHPExcel->getActiveSheet()->setCellValue('N'.$i, $value['is_parent_id']);
            $objPHPExcel->getActiveSheet()->setCellValue('O'.$i, $value['releases_date']);
            $objPHPExcel->getActiveSheet()->setCellValue('P'.$i, $value['effective_date']);
            $objPHPExcel->getActiveSheet()->setCellValue('Q'.$i, $value['expiration_date']);
            $objPHPExcel->getActiveSheet()->setCellValue('R'.$i, $value['def_name']);
            $objPHPExcel->getActiveSheet()->setCellValue('S'.$i, $value['group_traveller_name']);
            $objPHPExcel->getActiveSheet()->setCellValue('T'.$i, $value['status_traveller_name']);
            $objPHPExcel->getActiveSheet()->setCellValue('U'.$i, $value['note']);
            $i++;
        }
        //System::debug($export);exit();
        $fileName = "Ds_Thanh_vien".".xls";
        //System::debug($objPHPExcel); exit();
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save($fileName);
		if(file_exists($fileName))
        {
			echo '<script>';
			echo 'window.location.href = \''.$fileName.'\';';
			echo ' </script>';
		}else{
			echo '<script>';
			echo 'alert(" Export dá»¯ liá»‡u khÃ´ng thÃ nh cÃ´ng !");';
			echo '</script>';
		}
        //System::debug($export);exit();
    }
}
?>