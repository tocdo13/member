<?php
class AddGolfCaddieForm extends Form
{
	function AddGolfCaddieForm()
	{
		Form::Form('AddGolfCaddieForm');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
		$this->link_js('packages/core/includes/js/jquery/jquery.maskedinput.js');
	}
	function on_submit()
	{
	   $record = array(
                'first_name'=>trim(Url::get('first_name')),
                'last_name'=>trim(Url::get('last_name')),
                'gender'=>Url::get('gender'),
                'birth_date'=>Date_Time::to_orc_date(Url::get('birth_date')),
                'nationality_id'=>Url::get('nationality_id'),
                'passport'=>Url::get('passport'),
                'address'=>Url::get('address'),
                'email'=>Url::get('email'),
                'phone'=>Url::get('phone')
        );
        if($_FILES['file']['name'] != NULL)
        {
            $arr_type = explode('/',$_FILES['file']['type']);
            if($arr_type[0]=='image')
            {
                $time = time();
                $logo = $time.$_FILES['file']['name'];
                $path = ROOT_PATH."packages/hotel/packages/golf/modules/GolfCaddie/avata/";
                $tmp_name = $_FILES['file']['tmp_name'];
                $name = "avata_".$time.'.'.$arr_type[1];
                move_uploaded_file($tmp_name,$path.$name);
                $record['image_profile'] = $name;
            }
        }
        if(Url::get('cmd')=='add')
        {
            $record['portal_id'] = PORTAL_ID;
            $id = DB::insert('golf_caddie',$record);
        }
        else
        {
            DB::update('golf_caddie',$record,'id='.Url::get('id'));
            $id = Url::get('id');
        }
        Url::redirect('golf_caddie');
        
	}	
	function draw()
	{	
		$nationality_list = DB::fetch_all("SELECT id,name_1 as name FROM country ORDER BY (case when id in ('439') then 0 else 1 end),name_1");
        $this->map['nationality_id_list'] = array('1'=>Portal::language('select'))+String::get_list($nationality_list);
        
        $this->map['gender_list'] = array('1'=>Portal::language('male'),'0'=>Portal::language('female'));
        
        if(Url::get('cmd')=='edit')
        {
            $id = Url::get('id');
            $row = DB::fetch("SELECT golf_caddie.*,TO_CHAR(golf_caddie.birth_date,'DD/MM/YYYY') as birth_date FROM golf_caddie WHERE id=".$id);
            $this->map += $row;
        }
        else
        {
            $this->map['image_profile'] = '';
            $this->map['nationality_id'] = 439;
        }
        $this->parse_layout('add',$this->map);
	}
}
?>