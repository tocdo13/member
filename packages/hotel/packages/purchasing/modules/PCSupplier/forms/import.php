<?php
class ImportSupplierForm extends Form
{
	function ImportSupplierForm()
	{
		Form::Form('ImportSupplierForm');
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
                $new_pc_supplier = array(); 
                foreach($_SESSION['content'] as $key=>$value)
                {
                    $new_pc_supplier['code'] = strtoupper($_SESSION['content'][$key]['code']);
                    $new_pc_supplier['name'] = trim($_SESSION['content'][$key]['name']);
                    $new_pc_supplier['fax'] = $_SESSION['content'][$key]['fax'];
                    $new_pc_supplier['tax_code'] = $_SESSION['content'][$key]['tax_code'];
                    $new_pc_supplier['mobile'] = $_SESSION['content'][$key]['mobile'];
                    $new_pc_supplier['address'] = $_SESSION['content'][$key]['address'];
                    $new_pc_supplier['email'] = $_SESSION['content'][$key]['email'];
                    $new_pc_supplier['contact_person_name'] = $_SESSION['content'][$key]['contact_person_name'];
                    $new_pc_supplier['contact_person_phone'] = $_SESSION['content'][$key]['contact_person_phone'];
                    $new_pc_supplier['contact_person_mobile'] = $_SESSION['content'][$key]['contact_person_mobile'];
                    $new_pc_supplier['contact_person_email'] = $_SESSION['content'][$key]['contact_person_email'];
                     DB::insert('supplier',$new_pc_supplier);
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
        if(Url::get('do_upload'))
		{
            $file = $this->save_file('path_file');  
            //System::debug($file);
            require_once 'packages/core/includes/utils/PHPExcel/IOFactory.php';
            //kiem tra file excel co dinh dang .xls hay .xlsx
            $file_type = explode(".",$file);
            //System::debug($file_type);
            $file_type = $file_type[1];

            if($file_type=='xls')
            {
                $objReader = new PHPExcel_Reader_Excel5();
            }
            else if($file_type=='xlsx')
            {
                $objReader = new PHPExcel_Reader_Excel2007();
            }
            
            $objPHPExcel = $objReader->load($file);         
            $sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
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
                                    'code'=>isset($col['B'])?$col['B']:'',
                                    'name'=>isset($col['C'])?$col['C']:'',
                                    'phone'=>isset($col['D'])?$col['D']:'',
                                    'fax'=>isset($col['E'])?$col['E']:'',
                                    'mobile'=>isset($col['F'])?$col['F']:'',
                                    'email'=>isset($col['G'])?$col['G']:'',
                                    'tax_code'=>isset($col['H'])?$col['H']:'',
                                    'address'=>isset($col['I'])?$col['I']:'',
                                    'contact_person_name'=>isset($col['J'])?$col['J']:'',
                                    'contact_person_phone'=>isset($col['K'])?$col['K']:'',
                                    'contact_person_mobile'=>isset($col['L'])?$col['L']:'',
                                    'contact_person_email'=>isset($col['M'])?$col['M']:'',
                                    );
            }
            else
            {
                if($col['A']=='' AND $col['B']=='' AND $col['C']=='' AND $col['D']=='' AND $col['E']=='' AND $col['F']=='' AND $col['G']=='' AND $col['H']=='' AND $col['I']=='' AND $col['J']=='' AND $col['K']=='' AND $col['L']=='' AND $col['M']=='')
                {
                
                }
                else
                {
                    $row_content = array(
                                    'stt'=> $this->set_array($col,'A'),
                                    'code'=> $this->set_array($col,'B'),
                                    'name'=> $this->set_array($col,'C'),
                                    'phone'=> $this->set_array($col,'D'),
                    				'fax'=> $this->set_array($col,'E'),
                    				'mobile'=> $this->set_array($col,'F'),
                    				'email'=> $this->set_array($col,'G'),
                    				'tax_code'=> $this->set_array($col,'H'),
                    				'address'=> $this->set_array($col,'I'),
                    				'contact_person_name'=> $this->set_array($col,'J'),
                                    'contact_person_phone'=> $this->set_array($col,'K'),
                                    'contact_person_mobile'=> $this->set_array($col,'L'),
                                    'contact_person_email'=> $this->set_array($col,'M'),
                                    );
                    $check_error = true;
                    $error[$i] = $row_content;
                    $error[$i]['note'] = '';
                        // kiem tra tinh hop le cua cac truong trong $row_content - loi thi dua vao Array error - khong thi dua vao Array content
                    foreach($sheet as $rows1=>$col1) 
                    {
                        if($rows!=$rows1)
                        {
                            if($col['B']==$col1['B'] and $col['B']!='')
                            {
                                $check_error = false;
                                $error[$i]['note'] .= '<span style="color: red;">- Mã nhà cung cấp trùng nhau trong file excel</span><br />';
                            }
                            if($col1['H']==$col['H'] || $col1['G']==$col['G'])
                            {
                                if($col1['H']==$col['H'] and $col['H']!='')
                                {
                                    $check_error = false;
                                    $error[$i]['note'] .= '<span style="color: red;">-trùng mã số thuế trong file excel</span><br />';
                                }
                                if($col1['G']==$col['G'] and $col['G']!='')
                                {
                                    $check_error = false;
                                    $error[$i]['note'] .= '<span style="color: red;">-trùng email nhà cung cấp trong file excel</span><br />';
                                }
                            }
                        }
                    }  
                    if($row_content['name']=='')
                    {
                        $check_error = false;
                        $error[$i]['note'] .= '<span style="color: red;">- chưa nhập tên nhà cung cấp</span><br />';
                    }
                    elseif(DB::exists('select id from supplier where name=\''.$row_content['name'].'\''))
                    {
                        $check_error = false;
                        $error[$i]['note'] .= '<span style="color: red;">- Trùng Tên Nhà cung cấp</span><br />';
                    }
                    if($row_content['code']=='')
                    {
                        $check_error = false;
                        $error[$i]['note'] .= '<span style="color: red;">- chưa nhập mã nhà cung cấp</span><br />';
                    }
                    elseif(DB::exists('Select * from supplier where code = \''.$row_content['code'].'\' '))
                    {
                        $check_error = false;
                        $error[$i]['note'] .= '<span style="color: red;">- mã nhà cung cấp đã tồn tại</span><br />';
                    }
                    if($row_content['phone']!='' and is_numeric($row_content['phone'])!=1)
                    {
                        $check_error = false;
                        $error[$i]['note'] .= '<span style="color: red;">- sai định dạng số ĐT NCC</span><br />';
                    }
                    if($row_content['mobile']!='' and is_numeric($row_content['mobile'])!=1)
                    {
                        $check_error = false;
                        $error[$i]['note'] .= '<span style="color: red;">- sai định dạng số mobile NCC</span><br />';
                    }
                    if($row_content['contact_person_phone']!='' and is_numeric($row_content['contact_person_phone'])!=1)
                    {
                        $check_error = false;
                        $error[$i]['note'] .= '<span style="color: red;">- sai định dạng số ĐT người LH </span><br />';
                    }
                    if($row_content['contact_person_mobile']!='' and is_numeric($row_content['contact_person_mobile'])!=1)
                    {
                        $check_error = false;
                        $error[$i]['note'] .= '<span style="color: red;">- sai định dạng số mobile người LH</span><br />';
                    }
                    if($row_content['email']!='')
                    {
                        if(filter_var($row_content['email'],FILTER_VALIDATE_EMAIL))
                        {
                            if(DB::fetch('Select * from supplier where email = \''.$row_content['email'].'\' '))
                            {
                                $check_error = false;
                                $error[$i]['note'] .= '<span style="color: red;">- email NCC đã tồn tại</span><br />';
                            }
                        }
                        else
                        {
                            $check_error = false;
                            $error[$i]['note'] .= '<span style="color: red;">- email NCC không hợp lệ</span><br />';
                        }
                    }
                    if($row_content['contact_person_email']!='')
                    {
                        if(filter_var($row_content['contact_person_email'],FILTER_VALIDATE_EMAIL))
                        {
                            if(DB::fetch('Select * from supplier where contact_person_email = \''.$row_content['contact_person_email'].'\' '))
                            {
                                $check_error = false;
                                $error[$i]['note'] .= '<span style="color: red;">- email người LH đã tồn tại</span><br />';
                            }
                        }
                        else
                        {
                            $check_error = false;
                            $error[$i]['note'] .= '<span style="color: red;">- email người LH không hợp lệ</span><br />';
                        }
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