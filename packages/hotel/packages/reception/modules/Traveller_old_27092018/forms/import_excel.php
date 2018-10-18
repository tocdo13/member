<?php
class ImportExcelTravellerForm extends Form
{
    function ImportExcelTravellerForm()
    {
        Form::Form('ImportExcelTravellerForm');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
		$this->link_js('packages/core/includes/js/jquery/jquery.maskedinput.js');
    }
    function on_submit()
    {
        require_once 'packages/hotel/includes/member.php';
        if(Url::get('save') AND isset($_REQUEST['payment']))
        {
            $array_log = array();
            foreach($_REQUEST['payment'] as $key=>$value)
            {
                $value['first_name'] = trim($value['first_name']);
                $value['last_name'] = trim($value['last_name']);
                $value['gender'] = $value['gender']==1?1:0;
                $value['birth_date'] = Date_Time::to_orc_date($value['birth_date']);
                $value['releases_date'] = Date_Time::to_orc_date($value['releases_date']);
                $value['effective_date'] = Date_Time::to_orc_date($value['effective_date']);
                $value['expiration_date'] = Date_Time::to_orc_date($value['expiration_date']);
                $value['group_traveller_id'] = System::calculate_number($value['group_traveller_id']);
                $value['status_traveller_id'] = 1;
                $value['is_traveller'] = 0;
                if($value['member_code']=='')
                {
                    $value['member_create_date'] = '';
                    $value['point'] = '';
                    $value['point_user'] = '';
                    $value['member_level_id'] = '';
                    $value['is_parent_id'] = '';
                    $value['releases_date'] = '';
                    $value['effective_date'] = '';
                    $value['expiration_date'] = '';
                }
                else
                {
                    $value['member_create_date'] = Date_Time::to_orc_date($value['member_create_date']);
                    $value['member_level_id'] = DB::fetch("SELECT member_level.id FROM member_level WHERE UPPER(member_level.def_name)='".strtoupper($value['member_level_id'])."'","id");
                    $value['point_level'] = $value['point_user'];
                }
                $id=DB::insert('traveller',$value);
                $array_log[$id] = $value;
            }
            $description = '<b>Insert Traveller in excel</b><br/>';
            foreach($array_log as $key_1=>$value_1)
            {
                $description.= '<p>Make traveller id: #'.$key_1.'</p>';
                foreach($value_1 as $k=>$v)
                {
                    $description.= $k.' :'.$v;
                }
                $description.= '<br/>';
            }
            System::log('ADD','Insert Traveller in excel',$description,$id);
            Url::redirect('traveller',array('cmd'=>'list_member'));
        }
	}	
	function draw()
	{
	   $this->map = array();
       if(Url::get('do_upload'))
       {
            $file = $this->save_file('path_file');
            require_once 'packages/core/includes/utils/PHPExcel/IOFactory.php';
			$objReader = new PHPExcel_Reader_Excel5();
			$objPHPExcel = $objReader->load($file);	
			$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
            $data = $this->parse_sheet($sheetData);
            $this->map['list_payment'] = $data['list_payment'];
            $this->map['error_payment'] = $data['error_payment'];
            @unlink($file);
       }
       
	   $this->parse_layout('import_excel',$this->map);
	}
    function parse_sheet($sheet)
	{
	   require_once 'packages/hotel/includes/member.php';
        require_once 'packages/core/includes/utils/vn_code.php';
        $list_payment = array();
        $error_payment = array();
        $i = 1;
        foreach($sheet as $key=>$value)
        {
            $errors = '';
            if($key!=1)
            {
                if($value['A']=='')
                {
                    $errors .= "Nhập thiếu Họ (tên đệm)<br/>"; 
                }
                if($value['B']=='')
                {
                    $errors .= "Nhập thiếu Tên <br/>";
                }
                if($value['C']!=1 AND $value['C']!=2)
                {
                    $errors .= "Nhập sai giới tính (giới tính chỉ nhập 1 hoặc 2: trong đó 1 là nam, 2 là nữ) <br/>";
                }
                if($value['I']=='')
                {
                    $errors .= "Chưa nhập mã Thành viên <br/>";
                }
                if($value['J']=='')
                {
                    $errors .= "Chưa nhập mã từ <br/>";
                }elseif($value['J']!='' AND DB::exists("SELECT id FROM traveller WHERE member_code='".$value['J']."'"))
                {
                    $errors .= "Trùng mã từ <br/>";
                }
                if($value['K']!='' AND !DB::exists("SELECT id FROM group_traveller WHERE id='".$value['K']."'"))
                {
                    $errors .= "Mã nhóm khách hàng không tồn tại <br/>";
                }
                if($value['M']=='')
                {
                    $errors .= "Chưa nhập mã Hạng thẻ <br/>";
                }
                elseif($value['M']!='' AND !DB::exists("SELECT id FROM member_level WHERE UPPER(member_level.def_name)='".strtoupper(trim($value['M']))."'"))
                {
                    $errors .= "Mã hạng thẻ không tồn tại <br/>";
                }
                if($value['P']=='')
                {
                    $errors .= "Chưa Ngày phát hành thẻ thành viên <br/>";
                }elseif($value['P']!='')
                {
                    $date_arr = explode('/',$value['P']);
                    if(sizeof($date_arr)!=3)
                        $errors .= "Định dạng Ngày phát hành thẻ thành viên không đúng <br/>";
                    else
                    {
                        if($date_arr[0]<1 OR $date_arr[0]>31)
                            $errors .= "Định dạng Ngày phát hành thẻ thành viên không đúng <br/>";
                        elseif($date_arr[1]<1 OR $date_arr[1]>12)
                            $errors .= "Định dạng Ngày phát hành thẻ thành viên không đúng <br/>";
                        elseif($date_arr[2]<1970)
                            $errors .= "Định dạng Ngày phát hành thẻ thành viên không đúng <br/>";
                    }
                }
                if($value['R']=='')
                {
                    $errors .= "Chưa Ngày có hiệu lực thẻ thành viên <br/>";
                }elseif($value['R']!='')
                {
                    $date_arr = explode('/',$value['R']);
                    if(sizeof($date_arr)!=3)
                        $errors .= "Định dạng Ngày có hiệu lực thẻ thành viên không đúng <br/>";
                    else
                    {
                        if($date_arr[0]<1 OR $date_arr[0]>31)
                            $errors .= "Định dạng Ngày có hiệu lực thẻ thành viên không đúng <br/>";
                        elseif($date_arr[1]<1 OR $date_arr[1]>12)
                            $errors .= "Định dạng Ngày có hiệu lực thẻ thành viên không đúng <br/>";
                        elseif($date_arr[2]<1970)
                            $errors .= "Định dạng Ngày có hiệu lực thẻ thành viên không đúng <br/>";
                    }
                }
                /** Kimtan them **/
                foreach($sheet as $rows1=>$col1)
                {
                    if($rows1!=$key)
                    {
                        if($col1['J']==$value['J'] || $col1['I']==$value['I'])
                        {
                            if($col1['J']==$value['J'] and $value['J']!='')
                            {
                                $errors .= "Trùng mã từ trong file excel <br/>";
                            }
                            if($col1['I']==$value['I'] and $value['I']!='')
                            {
                                $errors .= "Trùng mã thành viên trong file excel <br/>";
                            }
                        }
                    }  
                }
                /** end Kimtan them **/
                if($errors=='')
                {
                    $list_payment[$key] = $value;
                    $list_payment[$key]['id'] = $key;
                }
                else
                {
                    $error_payment[$key] = $value;
                    $error_payment[$key]['error'] = $errors;
                }
            }
        }
        $result = array('list_payment'=>$list_payment,'error_payment'=>$error_payment);
        return $result;    
	}
    function save_file($file)
	{
		require_once 'packages/core/includes/utils/upload_file.php';
		$dir = 'excel';
		update_upload_file('path',$dir);
		return Url::get('path');
	}
    function set_array($col,$index,$default='')
    {
        if(isset($col[$index]))
            return trim($col[$index]);
        else
            return $default;
    }
}
?>
