<?php
class AddTravellerForm extends Form
{
	function AddTravellerForm()
	{
		Form::Form('AddTravellerForm');
		$this->add('first_name',new TextType(true,'invalid_first_name',0,255)); 
		$this->add('last_name',new TextType(true,'invalid_last_name',0,255)); 
		$this->add('gender',new SelectType(true,'invalid_gender',array('1'=>'male','0'=>'female'))); 
		$this->add('birth_date',new DateType(true,'invalid_birth_date')); 
		$this->add('passport',new TextType(false,'invalid_passport',0,255)); 
		$this->add('address',new TextType(false,'invalid_address',0,255)); 
		$this->add('email',new TextType(false,'invalid_email',0,255)); 
		$this->add('phone',new TextType(false,'invalid_phone',0,255)); 
		$this->add('fax',new TextType(false,'invalid_fax',0,255)); 
		$this->add('note',new TextType(false,'invalid_note',0,200000)); 
		$this->add('nationality_id',new IDType(true,'invalid_nationality_id','country'));
        $this->add('traveller.traveller_level_id',new TextType(true,'invalid_traveller_level',0,255)); 
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
		$this->link_js('packages/core/includes/js/jquery/jquery.maskedinput.js');	
        $this->link_css('packages/core/skins/default/css/jquery.autocomplete.css');	
        $this->link_js('packages/core/includes/js/jquery/jquery.autocomplete.js');
	}
	function on_submit()
	{
	   require_once 'packages/hotel/includes/member.php';
       
	   $record = array(
                'first_name'=>trim(Url::get('first_name')),
                'last_name'=>trim(Url::get('last_name')),
                'gender'=>Url::get('gender'),
                'birth_date'=>Date_Time::to_orc_date(Url::get('birth_date')),
                'releases_date'=>Date_Time::to_orc_date(Url::get('releases_date')),
                'effective_date'=>Date_Time::to_orc_date(Url::get('effective_date')),
                'expiration_date'=>Date_Time::to_orc_date(Url::get('expiration_date')),
                'nationality_id'=>Url::get('nationality_id'),
                'passport'=>Url::get('passport'),
                'address'=>Url::get('address'),
                'email'=>Url::get('email'),
                'plot_code'=>Url::get('plot_code'),
                'phone'=>Url::get('phone'),
                'fax'=>Url::get('fax'),
                'note'=>Url::get('note'),
                'province_id'=>Url::get('province_id'),
                'group_traveller_id'=>Url::get('group_traveller_id'),
                'status_traveller_id'=>Url::get('status_traveller_id'),     
                'is_parent_id'=>Url::get('is_parent_id'),
                'traveller_code'=>Url::get('traveller_code'),
                'is_traveller'=>0
        );
        if($_FILES['file']['name'] != NULL)
        {
            $arr_type = explode('/',$_FILES['file']['type']);
            if($arr_type[0]=='image')
            {
                $time = time();
                $logo = $time.$_FILES['file']['name'];
                $path = ROOT_PATH."packages/hotel/packages/reception/modules/Traveller/avata/";
                $tmp_name = $_FILES['file']['tmp_name'];
                $name = "avata_".$time.'.'.$arr_type[1];
                move_uploaded_file($tmp_name,$path.$name);
                $record['image_profile'] = $name;
            }
        }
        if(Url::get('cmd')=='edit')
        {
            $member_old = DB::fetch("SELECT member_code,member_level_id,point FROM traveller WHERE id=".Url::get('id'));
            $member_code_old = $member_old['member_code'];
            $member_level_id_old = $member_old['member_level_id'];
            $point_old = $member_old['point'];
        }
        if( (Url::get('member_code')!='' AND Url::get('cmd')=='add') OR ( Url::get('member_code')!='' AND Url::get('cmd')=='edit' AND $member_code_old!=Url::get('member_code') ) )
        {
            $record += array(
                'member_code'=>$member_code,
                'member_create_date'=>Date_Time::to_orc_date(date('d/m/Y'))
            );
        }
        if( (Url::get('cmd')=='add' and Url::get('member_level_id')!='' ) OR ( Url::get('cmd')=='edit' AND $member_level_id_old!=Url::get('member_level_id') ) )
        {
            $record['member_level_id'] = Url::get('member_level_id');
            if(Url::get('cmd')=='edit' AND $member_level_id_old!=Url::get('member_level_id'))
            {
                $record['point_level'] = $point_old;
            }
        }
        $record_old = array();
        if(Url::get('cmd')=='add')
        {
            $key = 'ADD';
            $id = DB::insert('traveller',$record);
        }
        else
        {
            $record_old = DB::fetch("SELECT * FROM traveller WHERE id=".Url::get('id'));
            $key = 'EDIT';
            DB::update('traveller',$record,'id='.Url::get('id'));
            $id = Url::get('id');
        }
        /**log **/
        $this->log_traveller($record,$id,$key,$record_old);
        
        if(Url::get('act')=='traveller_package')
        {
            echo "<script>";
            echo "opener.document.getElementById('traveller_id').value='".$id."';";
            echo "opener.document.getElementById('traveller_key').value='".Url::get('first_name')." ".Url::get('last_name')."';";
            echo "window.close();";
            echo "</script>";
        }
        
        if(Url::get('act')=='rr_traveller')
        {
            $index = Url::get('index');
            echo "<script>";
            echo "opener.document.getElementById('traveller_id_".$index."').value='".$id."';";
            echo "opener.document.getElementById('traveller_name_".$index."').value='".Url::get('first_name')." ".Url::get('last_name')."';";
            echo "opener.document.getElementById('passport_".$index."').value='".Url::get('passport')."';";
            if(Url::get('gender')==1)
            {
                echo "opener.document.getElementById('sex_".$index."').value='Nam';";
            }
            else
            {
                echo "opener.document.getElementById('sex_".$index."').value='Nữ';";
            }
            echo "opener.document.getElementById('email_".$index."').value='".Url::get('email')."';";
            echo "window.close();";
            echo "</script>";
            exit();
        }
        if(Url::get('site')=='mice')
        {
            $index = Url::get('index');
            echo "<script>";
            echo "opener.document.getElementById('traveller_id_".$index."').value='".$id."';";
            echo "opener.document.getElementById('traveller_name_".$index."').value='".Url::get('first_name')." ".Url::get('last_name')."';";
            echo "window.close();";
            echo "</script>";
            exit();
        }
        if(Url::get("check_edit") AND (Url::get("check_edit")=='on'))
        {
            if(Url::get('cmd')=='add')
                $tt = '?page=traveller&cmd=add';
            else
                $tt = '?page=traveller&cmd=edit&id='.Url::get('id');
            echo '<script>window.location.href = \''.$tt.'\'</script>';
            exit();
        }
        else
        {
            $tt = '?page=traveller&cmd=list_member';
            echo '<script>window.location.href = \''.$tt.'\'</script>';
            exit();
        }
        
	}	
	function draw()
	{	
		$nationality_list = DB::fetch_all("SELECT id,name_1 as name FROM country ORDER BY (case when id in ('439') then 0 else 1 end),name_1");
        $this->map['nationality_id_list'] = array('1'=>Portal::language('select'))+String::get_list($nationality_list);
        $traveller_list = DB::fetch_all("SELECT id,name FROM guest_type ORDER BY id");
        $this->map['traveller_level_id_list'] = array(''=>Portal::language('select'))+String::get_list($traveller_list);
        
        $group_traveller_list = DB::fetch_all("SELECT id,name FROM group_traveller ORDER BY id");
        $this->map['group_traveller_id_list'] = array(''=>Portal::language('select'))+String::get_list($group_traveller_list);
        
        $status_traveller_list = DB::fetch_all("SELECT id,name FROM status_traveller ORDER BY id");
        $this->map['status_traveller_id_list'] = array(''=>Portal::language('select'))+String::get_list($status_traveller_list);
        
        $this->map['gender_list'] = array('1'=>Portal::language('male'),'0'=>Portal::language('female'));
        $province_list = DB::fetch_all("SELECT id,name FROM province ORDER BY id");
        $this->map['province_id_list'] = array(''=>Portal::language('select'))+String::get_list($province_list);
        $lever_member = DB::fetch_all("SELECT id,name FROM member_level ORDER BY min_point");
        $this->map['lever_member_id_option'] = '<option value="">'.Portal::language('select').'</option>';
        foreach($lever_member as $key=>$value)
        {
            $this->map['lever_member_id_option'] .= '<option value="'.$value['id'].'">'.$value['name'].'</option>';
        }
        if(Url::get('id'))
            $cond_is_parent = ' AND id!='.Url::get('id');
        else
            $cond_is_parent = '';
        $is_parent = DB::fetch_all("SELECT id,concat(concat(first_name,' '),last_name) as name,traveller_code FROM traveller WHERE is_parent_id is null AND traveller_code is not null ".$cond_is_parent." ORDER BY first_name, last_name");
        $this->map['is_parent_id_option'] = '';
        foreach($is_parent as $key=>$value)
        {
            $this->map['is_parent_id_option'] .= '<option value="'.$value['traveller_code'].' -*- '.$value['name'].'"></option>';
        }
        $this->map['is_vn_list'] = array('0'=>Portal::language('Alien'),'1'=>Portal::language('Overseas_Vietnamese'),'2'=>Portal::language('Viet_nam'));
        $this->map['is_staff_list'] = array(''=>Portal::language('select'),'AB'=>Portal::language('anbien'),'ZK'=>Portal::language('zoka'));
        if(Url::get('cmd')=='edit')
        {
            $id = Url::get('id');
            $row = DB::fetch("SELECT traveller.*,TO_CHAR(traveller.birth_date,'DD/MM/YYYY') as birth_date,TO_CHAR(traveller.releases_date,'DD/MM/YYYY') as releases_date,TO_CHAR(traveller.effective_date,'DD/MM/YYYY') as effective_date,TO_CHAR(traveller.expiration_date,'DD/MM/YYYY') as expiration_date FROM traveller WHERE id=".$id);
            $this->map += $row;
        }
        else
        {
            $this->map['is_parent_id'] = '';
	        $this->map['member_level_id'] = '';
            $this->map['image_profile'] = '';
            $this->map['nationality_id'] = 439;
        }
        $this->parse_layout('add',$this->map);
	}
    function log_traveller($recode,$id,$key,$record_old)
    {
        if($key=='ADD')
        {
            $description = '';
            foreach($recode as $key=>$value)
            {
                $description .= '<p><b>'.$key.'</b>: '.$value.'</p>';
            }
            $log_title = 'Make traveller '.$recode['first_name'].' '.$recode['last_name'].' #ID: '.$id;
        }
        else
        {
            $description = '';
            foreach($recode as $key=>$value)
            {
                if($record_old[$key]!=$value)
                $description .= '<p><b>'.$key.'</b>: '.$record_old[$key].'<b> change to :</b>'.$value.'</p>';
            }
            $log_title = 'Update traveller '.$recode['first_name'].' '.$recode['last_name'].' #ID: '.$id;
        }
        if($description !='')
        System::log($key,$log_title,$description,$id);
    }
}
?>