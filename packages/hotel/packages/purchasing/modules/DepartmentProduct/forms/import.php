<?php
class ImportSupplierPriceForm extends Form
{
	function ImportSupplierPriceForm()
	{
		Form::Form('ImportSupplierPriceForm');
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
                $pc_sup_price = array(); 
                foreach($_SESSION['content'] as $key=>$value)
                {
                    if(isset($_SESSION['content'][$key]['product_id']))
                    {
                        $pc_sup_price['product_id'] = $_SESSION['content'][$key]['product_id'];
                    }
                    else
                    {
                        $pc_sup_price['product_id'] = DB::fetch('select id as product_id from product where UPPER(FN_CONVERT_TO_VN(name_1) = \''.strtoupper(convert_utf8_to_latin($_SESSION['content'][$key]['product_id'])).'\'','product_id');
                    }
/** kim oanh comment **/
                    $pc_sup_price['portal_department_id'] = DB::fetch('select id as portal_department_id from portal_department where department_code = \''.$_SESSION['content'][$key]['portal_department_id'].'\' and portal_id = \''.PORTAL_ID.'\'','portal_department_id');
 /** **/ 
 		    /** kim oanh **/ /** manh comment code cua oanh tra lai code cua anh giap **/
                    //$pc_sup_price['portal_department_id'] = DB::fetch('select id as portal_department_id from department where code = \''.$_SESSION['content'][$key]['portal_department_id'].'\'','portal_department_id');
                    /** kim oanh  **/     
                    
                           
                    DB::insert('pc_department_product',$pc_sup_price);
                    //System::debug($pc_sup_price);
                    //exit(); 
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
                                    'product_id'=>isset($col['B'])?$col['B']:'',
                                    'product_name'=>isset($col['C'])?$col['C']:'',
                                    'portal_department_id'=>isset($col['D'])?$col['D']:'',
                                    );
            }
            else
            {
                if($col['A']=='' AND $col['B']=='' AND $col['C']=='' AND $col['D']=='')
                {
                
                }
                else
                {
                    $row_content = array(
                    				'stt'=> $this->set_array($col,'A'),
                                    'product_id'=> $this->set_array($col,'B'),
                                    'product_name'=> $this->set_array($col,'C'),
                                    'portal_department_id'=> $this->set_array($col,'D'),
                                    );
                    $check_error = true;
                    $error[$i] = $row_content;
                    $error[$i]['note'] = '';
                        // kiem tra tinh hop le cua cac truong trong $row_content - loi thi dua vao Array error - khong thi dua vao Array content
                    /** check mã và tên sp */
                    if($row_content['product_id']=='' and $row_content['product_name']=='')
                    {
                        $check_error = false;
                        $error[$i]['note'] .= '<span style="color: red;">- chưa nhập mã, tên sản phẩm</span><br />';
                    }
                    elseif($row_content['product_id']!='' and $row_content['product_name']!='')
                    {
                        if(!DB::fetch('select * from product where id = \''.$row_content['product_id'].'\' and UPPER(FN_CONVERT_TO_VN(name_1)) = \''.mb_strtoupper($row_content['product_name'],'utf-8').'\''))
                        {
                            //$check_error = false;
                            //$error[$i]['note'] .= '<span style="color: red;">- mã sản phẩm và tên sản phẩm không khớp!</span><br />';
                        }
                    }
                    elseif($row_content['product_id']!='' and $row_content['product_name']=='')
                    {
                        if(!DB::fetch('select * from product where id = \''.$row_content['product_id'].'\''))
                        {
                            $check_error = false;
                            $error[$i]['note'] .= '<span style="color: red;">- mã sản phẩm không tồn tại!</span><br />';
                        }
                    }
                    elseif($row_content['product_id']=='' and $row_content['product_name']!='')
                    {
                        if(!DB::fetch('select * from product where id = \''.$row_content['product_name'].'\''))
                        {
                            $check_error = false;
                            $error[$i]['note'] .= '<span style="color: red;">- Tên sản phẩm không tồn tại!</span><br />';
                        }
                    }
                    /** end check mã và tên sp */
                    /** check bộ phận */
                    if($row_content['portal_department_id']=='')
                    {
                        $check_error = false;
                        $error[$i]['note'] .= '<span style="color: red;">- chưa nhập bộ phận!</span><br />';
                    }
                    else
                    {
                        if(!DB::fetch('select * from portal_department where department_code = \''.$row_content['portal_department_id'].'\''))
                        {
                            $check_error = false;
                            $error[$i]['note'] .= '<span style="color: red;">- mã bộ phận không tồn tại!</span><br />';
                        }
                    }
                    /** end check bộ phận */
                    if($check_error==true)
                    {
                        if(DB::exists('select id from pc_department_product where product_id=\''.$row_content['product_id'].'\' and portal_department_id='.DB::fetch('select id from portal_department where department_code = \''.$row_content['portal_department_id'].'\' and portal_id=\''.PORTAL_ID.'\'','id').''))
                        {
                            $check_error = false;
                            $error[$i]['note'] .= '<span style="color: red;">- Trung san pham!</span><br />';
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
