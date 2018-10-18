<?php
class ImportCustomerForm extends Form
{
	function ImportCustomerForm()
	{
		Form::Form('ImportCustomerForm');
        $this->link_js('packages/core/includes/js/multi_items.js');
		$this->link_js('packages/hotel/packages/restaurant/includes/js/update_price_new.js');
		$this->link_js('packages/hotel/packages/reception/modules/includes/jquery.windows-engine.js');
        $this->link_js('packages/hotel/packages/reception/modules/includes/common01.js');
		$this->link_css("packages/hotel/skins/default/css/jquery.windows-engine.css");
	}
    
    function on_submit()
    {  
        require_once 'packages/core/includes/utils/vn_code.php';
        
        //import to db
        if(Url::get('save'))
        {
            if(isset($_SESSION['content']) AND !empty($_SESSION['content']))
            {
                $new_customer = array(); 
                foreach($_SESSION['content'] as $key=>$value)
                {
                    $new_customer['name'] = $_SESSION['content'][$key]['name'];
                    $new_customer['def_name'] = $_SESSION['content'][$key]['name_def'];
                    $new_customer['address'] = $_SESSION['content'][$key]['address'];
                    $new_customer['user_id'] = User::id();
                    $new_customer['portal_id'] = PORTAL_ID;
                    if($_SESSION['content'][$key]['country']!='')
                    {
                        $new_customer['country'] = DB::fetch('Select * from zone where UPPER(FN_CONVERT_TO_VN(brief_name_1)) LIKE \'%'.strtoupper(convert_utf8_to_latin($_SESSION['content'][$key]['country'],'utf-8')).'%\'','structure_id');
                        if($_SESSION['content'][$key]['country']=='VNM')
                        {
                            $new_customer['city'] = DB::fetch('Select * from zone where UPPER(FN_CONVERT_TO_VN(brief_name_1)) LIKE \'%'.strtoupper(convert_utf8_to_latin($_SESSION['content'][$key]['city'],'utf-8')).'%\'','structure_id');
                            $new_customer['district'] = DB::fetch('Select * from zone where UPPER(FN_CONVERT_TO_VN(brief_name_1)) LIKE \'%'.strtoupper(convert_utf8_to_latin($_SESSION['content'][$key]['district'],'utf-8')).'%\'','structure_id');
                        }
                        else
                        {
                            $new_customer['city'] = '';
                            $new_customer['district'] = '';
                        }
                    }
                    else
                    {
                        $new_customer['country'] = '';
                        $new_customer['city'] = '';
                        $new_customer['district'] = '';
                    }
                    if($_SESSION['content'][$key]['group_id']!='')
                    $new_customer['group_id'] = DB::fetch('Select id from CUSTOMER_GROUP where UPPER(FN_CONVERT_TO_VN(name)) = \''.strtoupper(convert_utf8_to_latin($_SESSION['content'][$key]['group_id'],'utf-8')).'\'','id');
                    else
                    $new_customer['group_id'] = '';
                    
                    if($_SESSION['content'][$key]['sectors_id']!='')
                    $new_customer['sectors_id'] = DB::fetch('Select id from sectors where UPPER(FN_CONVERT_TO_VN(name)) = \''.strtoupper(convert_utf8_to_latin($_SESSION['content'][$key]['sectors_id'],'utf-8')).'\'','id');
                    else
                    $new_customer['sectors_id'] = '';
                    
                    $new_customer['mobile'] = $_SESSION['content'][$key]['mobile'];
                    $new_customer['fax'] = $_SESSION['content'][$key]['fax'];
                    $new_customer['email'] = $_SESSION['content'][$key]['email'];
                    $new_customer['tax_code'] = $_SESSION['content'][$key]['tax_code'];
                    $new_customer['bank_code'] = $_SESSION['content'][$key]['bank_code'];
                    
                    if($_SESSION['content'][$key]['bank_id']!='')
                    $new_customer['bank_id'] = DB::fetch('Select id from bank where UPPER(FN_CONVERT_TO_VN(name)) = \''.strtoupper(convert_utf8_to_latin($_SESSION['content'][$key]['bank_id'],'utf-8')).'\'','id');
                    else
                    $new_customer['bank_id'] = '';
                    
                    if($_SESSION['content'][$key]['status']!='')
                    {
                        if( strtoupper(convert_utf8_to_latin($_SESSION['content'][$key]['status'],'utf-8'))=='DANG GIAO DICH')
                        $new_customer['status'] = 'dang_giao_dich';
                        elseif( strtoupper(convert_utf8_to_latin($_SESSION['content'][$key]['status'],'utf-8'))=='TIEM NANG')
                        $new_customer['status'] = 'tiem_nang';
                        elseif( strtoupper(convert_utf8_to_latin($_SESSION['content'][$key]['status'],'utf-8'))=='NGUNG GIAO DICH')
                        $new_customer['status'] = 'ngung_giao_dich';
                        else
                        $new_customer['status'] = '';
                    }
                    
                    $new_customer['creart_date'] = Date_Time::to_orc_date($_SESSION['content'][$key]['creart_date']);
                    $new_customer['note'] = $_SESSION['content'][$key]['note']!=''?$_SESSION['content'][$key]['note']:'';
                    $new_customer['sale_code'] = $_SESSION['content'][$key]['sale_id'];
                    
                    $max = DB::fetch_all('select max(customer.id) as id from customer');
                    foreach($max as $id_max=>$code_max){
                        $code_max['id'] += 1;
                        if($code_max['id']>0 AND $code_max['id']<10){
                            $max_array['code'] = "000".$code_max['id'];
                        }
                        elseif($code_max['id']>9 AND $code_max['id']<100){
                            $max_array['code'] = "00".$code_max['id'];
                        }
                        elseif($code_max['id']>99 AND $code_max['id']<1000){
                            $max_array['code'] = "0".$code_max['id'];
                        }
                        else{
                            $max_array['code'] = $code_max['id'];
                        }
                    }
                    $new_customer['code'] = $max_array['code'];
                    //System::debug($new_customer);exit();
                    DB::insert('customer',$new_customer);
                }
                unset($_SESSION['content']);
                Url::redirect_current(array('cmd','action'=>'success'));      
            }
            //Neu ko the upload loi file
            else
                $this->error('upload_file_missing',Portal::language('re_upload'));
        }
    }
	function draw()
    {
        $this->map = array();
        //echo $portal_id;
        //upload file anh preview
        $national =DB::fetch_all('select brief_name_1 as id,name_1 as name,structure_id from zone where substr(structure_id,4,2) = \'00\' ORDER BY zone.id,zone.name_1');
        $this->map['national_id'] = $national;
        $city =DB::fetch_all('select brief_name_1 as id,name_1 as name,structure_id from zone where substr(structure_id,4,2) != \'00\' and substr(structure_id,6,2) = \'00\' ORDER BY zone.id,zone.name_1');
        $this->map['city_id'] = $city;
        $district =DB::fetch_all('select brief_name_1 as id,name_1 as name,structure_id from zone where substr(structure_id,4,2) != \'00\' and substr(structure_id,6,2) != \'00\' and substr(structure_id,8,2) = \'00\' ORDER BY zone.id,zone.name_1');
        $this->map['district_id'] = $district;
        //System::debug($this->map['district_id']);exit();
        if(Url::get('do_upload'))
		{
            $file = $this->save_file('path_file');	
            require_once 'packages/core/includes/utils/PHPExcel/IOFactory.php';
			$objReader = new PHPExcel_Reader_Excel5();
			$objPHPExcel = $objReader->load($file);			
			$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
            //System::debug($sheetData);
            $this->result = $this->parse_sheet($sheetData);
            @unlink($file);
            //Luu vao session
            $_SESSION['header'] = $this->result['header'];
            $_SESSION['content'] = $this->result['content'];
            $_SESSION['preview'] = $this->result['preview'];
            $_SESSION['error'] = $this->result['error'];
            $this->parse_layout('import', $this->map+array('preview'=>$_SESSION['preview'],'error'=>$_SESSION['error']));
		}
        else
        {
           $this->parse_layout('import',$this->map);  
        }
    }
    function save_file($file)
	{
		require_once 'packages/core/includes/utils/upload_file.php';
		$dir = 'excel';
		update_upload_file('path',$dir);
		return Url::get('path');
	}
    
    function parse_sheet($sheet)
	{
        //System::debug($sheet);
        require_once 'packages/core/includes/utils/vn_code.php';
        $header = array();
        $content = array();
        $preview = array();
        $error = array();
        $i = 1;
        foreach($sheet as $rows=>$col)
        {
			if($rows == 1)
            {
                $header[$i] = array(
                                    'stt'=>isset($col['A'])?$col['A']:'',
                                    'name'=>isset($col['B'])?$col['B']:'',
                                    'name_def'=>isset($col['C'])?$col['C']:'',
                                    'address'=>isset($col['D'])?$col['D']:'',
                                    'country'=>isset($col['E'])?$col['E']:'',
                                    'city'=>isset($col['F'])?$col['F']:'',
                                    'district'=>isset($col['G'])?$col['G']:'',
                                    'group_id'=>isset($col['H'])?$col['H']:'',
                                    'sectors_id'=>isset($col['I'])?$col['I']:'',
                                    'mobile'=>isset($col['J'])?$col['J']:'',
                                    'fax'=>isset($col['K'])?$col['K']:'',
                                    'email'=>isset($col['L'])?$col['L']:'',
                                    'tax_code'=>isset($col['M'])?$col['M']:'',
                                    'bank_code'=>isset($col['N'])?$col['N']:'',
                                    'bank_id'=>isset($col['O'])?$col['O']:'',
                                    'status'=>isset($col['P'])?$col['P']:'',
                                    'creart_date'=>isset($col['Q'])?$col['Q']:'',
                                    'note'=>isset($col['R'])?$col['R']:'',
                                    'sale_id'=>isset($col['S'])?$col['S']:'',
                                    );
            }
            else
            {
                if($col['A']=='' AND $col['B']=='' AND $col['C']=='' AND $col['D']=='' AND $col['E']=='' AND $col['F']=='' AND $col['G']=='' AND $col['H']=='' AND $col['I']=='' AND $col['J']=='' AND $col['K']=='' AND $col['L']=='' AND $col['M']=='' AND $col['N']=='' AND $col['O']=='' AND $col['P']=='' AND $col['Q']=='' AND $col['R']=='')
                {
                
                }
                else
                {
                  
                    $row_content = array(
                                        'stt'=> $this->set_array($col,'A'),
                                        'name'=> $this->set_array($col,'B'),
                                        'name_def'=> $this->set_array($col,'C'),
                                        'address'=> $this->set_array($col,'D'),
                                        'country'=> $this->set_array($col,'E'),
                                        'city'=> $this->set_array($col,'F'),
                                        'district'=> $this->set_array($col,'G'),
                                        'group_id'=> $this->set_array($col,'H'),
                                        'sectors_id'=> $this->set_array($col,'I'),
                                        'mobile'=> $this->set_array($col,'J'),
                                        'fax'=> $this->set_array($col,'K'),
                                        'email'=> $this->set_array($col,'L'),
                                        'tax_code'=> $this->set_array($col,'M'),
                                        'bank_code'=> $this->set_array($col,'N'),
                                        'bank_id'=> $this->set_array($col,'O'),
                                        'status'=> $this->set_array($col,'P'),
                                        'creart_date'=> $this->set_array($col,'Q'),
                                        'note'=> $this->set_array($col,'R'),
                                        'sale_id'=> $this->set_array($col,'S'),
                                        
                                        );
                    //System::debug($row_content);                             
                    $check_error = true;
                    $error[$i] = $row_content;
                    $error[$i]['note'] = '';
                        // kiem tra tinh hop le cua cac truong trong $row_content - loi thi dua vao Array error - khong thi dua vao Array content
                     foreach($sheet as $rows1=>$col1)
                    {
                        if($rows1!=$rows)
                        {
                            if($col1['M']==$col['M'] || $col1['L']==$col['L'])
                            {
                                if($col1['M']==$col['M'] and $col['M']!='')
                                {
                                    $check_error = false;
                                    $error[$i]['note'] .= '<span style="color: red;">-trùng mã số thuế trong file excel</span><br />';
                                }
                                if($col1['L']==$col['L'] and $col['L']!='')
                                {
                                    $check_error = false;
                                    $error[$i]['note'] .= '<span style="color: red;">-trùng email trong file excel</span><br />';
                                }
                            }
                        }  
                    } 
                    /** check tên mà vã khách  **/
                    if($row_content['name']=='')
                    {
                        $check_error = false;
                        $error[$i]['note'] .= '<span style="color: red;">- chưa nhập tên khách hàng</span><br />';
                    }
                    /** check quốc tịch và tỉnh thành quận huyện **/
                    if($row_content['country']=='')
                    {
                        
                    }
                    elseif(!DB::fetch('Select * from zone where brief_name_1 = \''.$row_content['country'].'\' '))
                    {
                        $check_error = false;
                        $error[$i]['note'] .= '<span style="color: red;">- quốc tịch không tồn tại</span><br />';
                    }
                    elseif($row_content['country']=='VNM')
                    {
                        if($row_content['city']=='')
                        {
                            
                        }
                        elseif(!DB::fetch('Select * from zone where UPPER(FN_CONVERT_TO_VN(brief_name_1)) LIKE \'%'.strtoupper(convert_utf8_to_latin($row_content['city'],'utf-8')).'\' and zone.structure_id < 1020000000000000000 and zone.structure_id > 1010000000000000000'))
                        {
                            $check_error = false;
                            $error[$i]['note'] .= '<span style="color: red;">- thành phố không tồn tại hoặc không thuộc Việt Nam</span><br />';
                        }
                        
                        if($row_content['district']=='')
                        {
                            
                        }
                        elseif(!DB::fetch('Select * from zone where UPPER(FN_CONVERT_TO_VN(brief_name_1)) LIKE \'%'.strtoupper(convert_utf8_to_latin($row_content['city'],'utf-8')).'\' and zone.structure_id < 1020000000000000000 and zone.structure_id > 1010000000000000000'))
                        {
                            $check_error = false;
                            $error[$i]['note'] .= '<span style="color: red;">- Quận/huyện không tồn tại hoặc không thuộc Việt Nam</span><br />';
                        }
                    }
                    
                    /** check loai **/
                    if($row_content['group_id']=='')
                    {
                        $check_error = false;
                        $error[$i]['note'] .= '<span style="color: red;">-chưa nhập loại</span><br />';
                    }
                    elseif(!DB::fetch('Select * from CUSTOMER_GROUP where UPPER(FN_CONVERT_TO_VN(name)) = \''.strtoupper(convert_utf8_to_latin($row_content['group_id'],'utf-8')).'\''))
                    {
                        $check_error = false;
                        $error[$i]['note'] .= '<span style="color: red;">- loại không tồn tại</span><br />';
                    }
                    /** check ngành nghề **/
                    if($row_content['sectors_id']!='' and !DB::fetch('Select * from sectors where UPPER(FN_CONVERT_TO_VN(name)) = \''.strtoupper(convert_utf8_to_latin($row_content['sectors_id'],'utf-8')).'\''))
                    {
                        $check_error = false;
                        $error[$i]['note'] .= '<span style="color: red;">- ngành nghề không tồn tại</span><br />';
                    }
                    /** check ngân hàng **/
                    if($row_content['bank_id']!='' and !DB::fetch('Select * from bank where UPPER(FN_CONVERT_TO_VN(name)) = \''.strtoupper(convert_utf8_to_latin($row_content['bank_id'],'utf-8')).'\''))
                    {
                        $check_error = false;
                        $error[$i]['note'] .= '<span style="color: red;">- ngân hàng không tồn tại</span><br />';
                    }
                    /** check số di động **/
                    if(0)//$row_content['mobile']!='' and is_numeric($row_content['mobile'])!=1
                    {
                        $check_error = false;
                        $error[$i]['note'] .= '<span style="color: red;">- sai định dạng số di động</span><br />';
                    }
                    /** check mã sale **/
                    if($row_content['sale_id']!='' 
                        and 
                        !DB::fetch('Select * from account
                                    inner join portal_department on account.portal_department_id = portal_department.id 
                                    where 
                                    portal_department.department_code = \'SALES\' 
                                    and UPPER(account.id) = \''.strtoupper($row_content['sale_id']).'\'')
                    )
                    {
                        $check_error = false;
                        $error[$i]['note'] .= '<span style="color: red;">- mã sale không đúng</span><br />';
                    }
                    /** check email**/
                    if($row_content['email']!='')
                    {
                        if(filter_var($row_content['email'],FILTER_VALIDATE_EMAIL))
                        {
                            if(DB::fetch('Select * from customer where email = \''.$row_content['email'].'\' '))
                            {
                                $check_error = false;
                                $error[$i]['note'] .= '<span style="color: red;">- email đã tồn tại</span><br />';
                            }
                        }
                        else
                        {
                            //$check_error = false;
                            //$error[$i]['note'] .= '<span style="color: red;">- email không hợp lệ</span><br />';
                        }
                    }
                    
                    /** check mã số thuế**/
                    if($row_content['tax_code']!='' and DB::fetch('Select * from customer where tax_code = \''.$row_content['tax_code'].'\' '))
                    {
                        $check_error = false;
                        $error[$i]['note'] .= '<span style="color: red;">- mã số thuế đã tồn tại</span><br />';
                    }
                    
                    if($row_content['creart_date']=='')
                    {
                        
                    }
                    elseif(strpos("/",$row_content['creart_date']))
                    {
                        
                        $check_error = false;
                        $error[$i]['note'] .= '<span style="color: red;">- định dạng ngày cấp sai</span><br />';
                    }
                    elseif(!strpos("/",$row_content['creart_date']))
                    {
                        $arr = explode("/",$row_content['creart_date']);
                        //echo sizeof($arr);
                        if(sizeof($arr)==3 AND Date_Time::to_orc_date($row_content['creart_date']))
                        {
                        }
                        else
                            $error[$i]['note'] .= '<span style="color: red;">- định dạng ngày thành lập sai</span><br />';
                        
                    }
                    if($check_error==true)
                    {
                        unset($error[$i]);
                        $content[$i]=$row_content;
                        if($i<=10)
					       $preview[$i] = $content[$i];
                    }
    				$i++;
                }
            }
                
        }
        //System::debug($preview);
        $result = array('header'=>$header, 'content'=>$content,'preview'=>$preview,'error'=>$error);
        return $result;
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
