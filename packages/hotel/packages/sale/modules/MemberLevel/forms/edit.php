<?php
class EditMemberLevelForm extends Form
{
	function EditMemberLevelForm()
	{
		Form::Form('EditMemberLevelForm');
	}
	function on_submit()
	{
	   //$hotel_list = DB::fetch_all("select * from hotel where is_active!=1");
	   $logo = Url::get('logo');
       $data = array(
                    'name'=>Url::get('name'),
                    'def_name'=>Url::get('def_name'),
                    'min_point'=>Url::get('min_point'),
                    'max_point'=>Url::get('max_point'),
                    'num_people'=>Url::get('num_people')
                    );
       if($_FILES['file']['name'] != NULL)
       {
            $arr_type = explode('/',$_FILES['file']['type']);
            if($arr_type[0]=='image')
            {
                $time = time();
                $logo = $time.$_FILES['file']['name'];
                $path = ROOT_PATH."packages/hotel/packages/sale/skins/img/logo_member_level/";
                $tmp_name = $_FILES['file']['tmp_name'];
                $name = $time.$_FILES['file']['name'];
                move_uploaded_file($tmp_name,$path.$name);
                $data['logo']='http://'.$_SERVER['HTTP_HOST'].'/'.Url::$root.'packages/hotel/packages/sale/skins/img/logo_member_level/'.$logo;
            }
       }
       $logo = 'http://'.$_SERVER['HTTP_HOST'].'/'.Url::$root.'packages/hotel/packages/sale/skins/img/logo_member_level/'.$logo;
       $logo = str_replace("/","\\",$logo);
       $data_old = array();
       if(Url::get('id'))
       {
            $data_old = DB::fetch('SELECT * FROM member_level WHERE id='.Url::get('id'));
            $id = Url::get('id');
            DB::update('member_level',$data,'id='.Url::get('id'));
       }
       else
       {
            $id = DB::insert('member_level',$data);
       }
       /**log **/
        $this->log_traveller($data,$id,$key,$data_old);
       if(Url::get('save_stay'))
       {
            Url::redirect('member_level',array());
                
       }
       else
       {
            Url::redirect('member_level',array('cmd'=>'edit','id'=>$id));
       }
	}	
	function draw()
	{
	   $this->map = array(
                        'name'=>'',
                        'def_name'=>'',
                        'min_point'=>0,
                        'max_point'=>0,
                        'logo'=>'',
                        'num_people'=>''
                        );
       
	   if(Url::get('cmd')=='edit')
       {
            $this->map = DB::fetch("SELECT * FROM member_level WHERE id=".Url::get('id'));
       }
	   $this->parse_layout('edit',$this->map);
	}
    function log_traveller($recode,$id,$key,$recode_old)
    {
        if($key=='ADD')
        {
            $description = '';
            foreach($recode as $key=>$value)
            {
                $description .= '<p><b>'.$key.'</b>: '.$value.'</p>';
            }
            $log_title = 'Make member level '.$recode['code'].' #ID: '.$id;
        }
        else
        {
            $description = '';
            foreach($recode as $key=>$value)
            {
                if($recode_old[$key]!=$value)
                $description .= '<p><b>'.$key.'</b>: '.$recode_old[$key].'<b> change to </b>:'.$value.'</p>';
            }
            $log_title = 'Update member level '.$recode['code'].' #ID: '.$id;
        }
        if($description !='')
        System::log($key,$log_title,$description,$id);
    }
}
?>