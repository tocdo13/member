<?php
class listNationalForm extends Form
{
	function listNationalForm()
	{

		Form::Form('listNationalForm');
		$this->link_css(Portal::template('hotel').'/css/style.css');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
	}
	function on_submit(){
	   //System::debug($_REQUEST);
       //exit();
	}
	function draw()
	{
	   require_once 'packages/core/includes/utils/vn_code.php';
       $this->map['city_list'] = array("" => "----select-city----");
       $country = DB::fetch_all('
        Select zone.structure_id as id, zone.name_1 as name,id as id2
        from zone ORDER BY (case when zone.id in (16,23,27,30)  
                                then 0
                                else 1
                        end)
                    ,zone.name_1 ');
       foreach ($country as $id => $content) {
            if ((substr($content['id'], 3, 2) != "00")and ( substr($content['id'], 5, 2) == "00")) {
                $id_test = $content['id'];
                $content_test = $content['name'];
                $this->map['city_list'] += array("$id_test" => "$content_test");
            }
       }
       if(Url::get('search_na')=='national')
       {
           $cond_na = '';
           if(Url::get('national_search')!='')
           {
                $cond_na.=' and UPPER(FN_CONVERT_TO_VN(name_2)) LIKE \'%'.strtoupper(convert_utf8_to_latin(Url::get('national_search'),'utf-8')).'%\'';
           }
           $items = DB::fetch_all('select brief_name_1 as id,name_1 as name,structure_id from zone where substr(structure_id,4,2) = \'00\' '.$cond_na.' ORDER BY zone.id,zone.name_1');
           $this->map['titel'] = Portal::language('list_national');
       }
       if(Url::get('search_na')=='city')
       {
           $cond_ci = '';
           if(Url::get('national_search')!='')
           {
                $cond_ci.=' and UPPER(FN_CONVERT_TO_VN(name_2)) LIKE \'%'.strtoupper(convert_utf8_to_latin(Url::get('national_search'),'utf-8')).'%\'';
           }
           $items = DB::fetch_all('select brief_name_1 as id,name_1 as name,structure_id from zone where substr(structure_id,4,2) != \'00\' and substr(structure_id,6,2) = \'00\' '.$cond_ci.' ORDER BY (case when zone.id in (16,23,27,30)  
                                then 0
                                else 1
                        end)
                    ,zone.name_1');
           $this->map['titel'] = Portal::language('list_city_of_VN');
       }
       if(Url::get('search_na')=='district')
       {
           $cond_di = '';
           if(Url::get('national_search')!='')
           {
                $cond_di.=' and UPPER(FN_CONVERT_TO_VN(name_2)) LIKE \'%'.strtoupper(convert_utf8_to_latin(Url::get('national_search'),'utf-8')).'%\'';
           }
           if(Url::get('city')!='')
           {
                $id_city = substr(Url::get('city'), 0, 5);
                $cond_di.= ' and substr(structure_id,0,5) = \''.$id_city.'\'';
           }
           $items = DB::fetch_all('select brief_name_1 as id,name_1 as name,structure_id from zone where substr(structure_id,4,2) != \'00\' and substr(structure_id,6,2) != \'00\' and substr(structure_id,8,2) = \'00\' '.$cond_di.' ORDER BY zone.id,zone.name_1');
           $this->map['titel'] = Portal::language('list_district_of_VN');
       }
       $this->parse_layout('list_national',array('items'=>$items)+$this->map);
    }
    
}
?>
