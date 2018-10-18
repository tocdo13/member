<?php
class ListGolfCaddieForm extends Form
{
    function ListGolfCaddieForm()
    {
        Form::Form('ListGolfCaddieForm');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
		$this->link_js('packages/core/includes/js/jquery/jquery.maskedinput.js');                       
    }
    function draw()
    {
        
        require_once 'packages/core/includes/utils/vn_code.php';
        $cond = "1=1";
        $arr_page = array();
        /** nếu search theo tên khách **/
        if(Url::get('full_name') AND Url::get('full_name')!=''){
            $this->map['full_name'] = Url::get('full_name');
            $_REQUEST['full_name'] = Url::get('full_name');
            $cond .= 'AND ((LOWER(FN_CONVERT_TO_VN(concat(concat(TRIM(golf_caddie.first_name),\' \'),TRIM(golf_caddie.last_name)))) LIKE \'%'.convert_utf8_to_latin(mb_strtolower(trim(Url::get('full_name')),'utf-8')).'%\'))';
        }
        
        /** nếu search theo hộ chiếu/cmnd **/
        if(Url::get('passport') AND Url::get('passport')!=''){
            $this->map['passport'] = Url::get('passport');
            $_REQUEST['passport'] = Url::get('passport');
            $arr_page['passport'] =  Url::get('passport');
            $cond .= "AND (UPPER(golf_caddie.passport) LIKE '%".strtoupper(Url::get('passport'))."%')";
        }
        /** nếu search theo quốc tịch **/
        $list_report_country = DB::fetch_all("SELECT id,name_2 as name FROM country WHERE country.selected_report = 1 ORDER BY name_2");
        $this->map['country_list'] = array('all'=>'---'.Portal::language('all').'---','other'=>Portal::language('country_other')) + String::get_list($list_report_country);
        if(Url::get('country') AND (Url::get('country')!='all')){
            $this->map['country'] = Url::get('country');
            $_REQUEST['country'] = Url::get('country');
            $arr_page['country'] =  Url::get('country');
            if(Url::get('country')=='other'){
                $cond .= "AND (golf_caddie.nationality_id = 1)";
            }else{
                $cond .= "AND (golf_caddie.nationality_id = ".Url::get('country').")";
            }
        }
        /** nếu search theo giới tính **/
        $this->map['gender_list'] = array('all'=>'---'.Portal::language('all').'---','1'=>'Nam','0'=>'Nữ');
        if((Url::get('gender')!='all') AND (Url::get('gender')!='')){
            $this->map['gender'] = Url::get('gender');
            $_REQUEST['gender'] = Url::get('gender');
            $arr_page['gender'] =  Url::get('gender');
            $cond .= "AND (golf_caddie.gender = ".Url::get('gender').")";
        }
        /** nếu search theo email **/
        if(Url::get('email') AND Url::get('email')!=''){
            $this->map['email'] = Url::get('email');
            $_REQUEST['email'] = Url::get('email');
            $arr_page['email'] =  Url::get('email');
            $cond .= "AND (golf_caddie.email = '".Url::get('email')."')";
        }
        /** nếu search theo phone **/
        if(Url::get('phone') AND Url::get('phone')!=''){
            $this->map['phone'] = Url::get('phone');
            $_REQUEST['phone'] = Url::get('phone');
            $arr_page['phone'] =  Url::get('phone');
            $cond .= "AND (golf_caddie.phone = '".Url::get('phone')."')";
        }
        $ORCL = '
			SELECT 
				count(*) as id
			FROM 
				golf_caddie
                left join country on golf_caddie.nationality_id=country.id
			WHERE '.$cond.'
		';
		$count = DB::fetch($ORCL);
        $item_per_page = 20;
        if(Url::get('page_no')){
            $page_no = Url::get('page_no');
        }else{
            $page_no = 1;
        }
        $page_name = 'golf_caddie';
		require_once 'packages/user/modules/Log/paging_new.php';
		$paging = paging_new($count['id'],$item_per_page,$page_name,$arr_page,$page_no);
        //echo $cond;
        /** end $cond **/
        $sql = "
            SELECT * FROM
                (
                SELECT 
                    golf_caddie.id,
                    concat(concat(golf_caddie.first_name,' '),golf_caddie.last_name) as full_name,
                    golf_caddie.passport,
                    golf_caddie.gender,
                    country.name_2,
                    golf_caddie.email,
                    golf_caddie.phone,
                    CASE
                        WHEN golf_caddie.image_profile is null 
                        THEN
                            CASE
                                WHEN golf_caddie.gender=1
                                THEN 'no_avata_boy.jpg'
                                ELSE 'no_avatar_girl.jpg'
                                END
                        ELSE
                            golf_caddie.image_profile
                    END image_profile,
                    row_number() over (order by golf_caddie.first_name ASC, golf_caddie.last_name ASC) as rownumber
                FROM
                    golf_caddie
                    left join country on golf_caddie.nationality_id=country.id
                WHERE
                    ".$cond."
                ORDER BY golf_caddie.first_name ASC, golf_caddie.last_name ASC
                )
            WHERE
                rownumber > ".(($page_no-1)*$item_per_page)." and rownumber<=".(($page_no)*$item_per_page)."
                ";
        $report = DB::fetch_all($sql);
        $this->map['list_items'] = String::array2js($report);
        $this->parse_layout('list',array('items'=>$report,'paging'=>$paging)+$this->map);
        
    }
}
?>