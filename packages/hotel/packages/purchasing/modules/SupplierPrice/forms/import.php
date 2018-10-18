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
        set_time_limit(-1);
        require_once 'packages/core/includes/utils/vn_code.php';
        //import to db
        if(Url::get('save'))
        {
            if(isset($_SESSION['content']) AND !empty($_SESSION['content']))
            {
                $pc_sup_price = array(); 
                foreach($_SESSION['content'] as $key=>$value)
                {
                    if(isset($_SESSION['content'][$key]['code_product']))
                    {
                        $pc_sup_price['product_id'] = $_SESSION['content'][$key]['code_product'];
                    }
                    else
                    {
                        $pc_sup_price['product_id'] = DB::fetch('select id as product_id from product where UPPER(FN_CONVERT_TO_VN(name_1) = \''.strtoupper(convert_utf8_to_latin($_SESSION['content'][$key]['name_product'])).'\'','product_id');
                    }
                    $pc_sup_price['supplier_id'] = DB::fetch('select id as supplier_id from supplier where code = \''.$_SESSION['content'][$key]['supplier_id'].'\'','supplier_id');
                    $pc_sup_price['starting_date'] = Date_Time::to_orc_date($_SESSION['content'][$key]['starting_date']);
                    $pc_sup_price['ending_date'] = Date_Time::to_orc_date($_SESSION['content'][$key]['ending_date']);
                    $pc_sup_price['price'] = $_SESSION['content'][$key]['price'];
                    $pc_sup_price['tax'] = $_SESSION['content'][$key]['tax'];
                    $pc_sup_price['price_after_tax'] = $_SESSION['content'][$key]['price_after_tax'];
                    DB::insert('pc_sup_price',$pc_sup_price);
                }
                unset($_SESSION['content']);
                Url::redirect_current(array('cmd','action'=>'success'));      
            }
            else
                $this->error('upload_file_missing',Portal::language('re_upload'));
        }
    }
	function draw()
    {
        set_time_limit(-1);
        $this->map = array();
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
	   set_time_limit(-1);
		require_once 'packages/core/includes/utils/upload_file.php';
		$dir = 'excel';
		update_upload_file('path',$dir);
		return Url::get('path');
	}
    
    function parse_sheet($sheet)
	{
	   set_time_limit(-1);
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
                                    'code_product'=>isset($col['B'])?$col['B']:'',
                                    'name_product'=>isset($col['C'])?$col['C']:'',
                                    'supplier_id'=>isset($col['D'])?$col['D']:'',
                                    'starting_date'=>isset($col['E'])?$col['E']:'',
                                    'ending_date'=>isset($col['F'])?$col['F']:'',
                                    'price'=>isset($col['G'])?$col['G']:'',
                                    'tax'=>isset($col['H'])?$col['H']:'',
                                    'price_after_tax'=> isset($col['I'])?$col['I']:'',
                                    );
            }
            else
            {
                if($col['A']=='' AND $col['B']=='' AND $col['C']=='' AND $col['D']=='' AND $col['E']=='' AND $col['F']=='' AND $col['G']=='' AND $col['H']=='' AND $col['I']=='')
                {
                
                }
                else
                {
                    $row_content = array(
                    				'stt'=> $this->set_array($col,'A'),
                                    'code_product'=> $this->set_array($col,'B'),
                                    'name_product'=> $this->set_array($col,'C'),
                                    'supplier_id'=> $this->set_array($col,'D'),
                                    'starting_date'=> $this->set_array($col,'E'),
                                    'ending_date'=> $this->set_array($col,'F'),
                                    'price'=> $this->set_array($col,'G'),
                                    'tax'=> $this->set_array($col,'H'),
                                    'price_after_tax'=> $this->set_array($col,'I'),
                                    );
                    $check_error = true;
                    $error[$i] = $row_content;
                    $error[$i]['note'] = '';
                        // kiem tra tinh hop le cua cac truong trong $row_content - loi thi dua vao Array error - khong thi dua vao Array content
                    /** check mã và tên sp */
                    if($row_content['code_product']=='' and $row_content['name_product']=='')
                    {
                        $check_error = false;
                        $error[$i]['note'] .= '<span style="color: red;">- chưa nhập mã, tên sản phẩm</span><br />';
                    }
                    elseif($row_content['code_product']!='' and $row_content['name_product']=='')
                    {
                        if(!DB::fetch('select * from product where id = \''.$row_content['code_product'].'\''))
                        {
                            $check_error = false;
                            $error[$i]['note'] .= '<span style="color: red;">- mã sản phẩm không tồn tại!</span><br />';
                        }
                    }
                    elseif($row_content['code_product']=='' and $row_content['name_product']!='')
                    {
                        if(!DB::fetch('select * from product where name_1 = \''.$row_content['name_product'].'\''))
                        {
                            $check_error = false;
                            $error[$i]['note'] .= '<span style="color: red;">- Tên sản phẩm không tồn tại!</span><br />';
                        }
                    }
                    foreach($sheet as $rows1=>$col1)
                    {
                        if($rows1 != 1 AND $rows1!=$rows)
                        {
                            $row_content1 = array(
                                				'stt'=> $this->set_array($col1,'A'),
                                                'code_product'=> $this->set_array($col1,'B'),
                                                'name_product'=> $this->set_array($col1,'C'),
                                                'supplier_id'=> $this->set_array($col1,'D'),
                                                'starting_date'=> $this->set_array($col1,'E'),
                                                'ending_date'=> $this->set_array($col1,'F'),
                                                'price'=> $this->set_array($col1,'G'),
                                                'tax'=> $this->set_array($col1,'H'),
                                                'price_after_tax'=> $this->set_array($col1,'I'),
                                                );
                            if($row_content['code_product']==$row_content1['code_product'] 
                                AND $row_content['supplier_id']==$row_content1['supplier_id']
                                AND ( $row_content['starting_date']=='' OR $row_content['ending_date']=='' OR $row_content1['starting_date']=='' OR $row_content1['ending_date']=='' 
                                        OR
                                      ($row_content['starting_date']<=$row_content1['ending_date'] AND $row_content1['starting_date']<=$row_content['ending_date'])  
                                    )
                                )
                                {
                                    $check_error = false;
                                    $error[$i]['note'] .= '<span style="color: red;">- Trùng sản phẩm!</span><br />';
                                }
                        }
                    }
                    /** end check mã và tên sp */
                    /** check nha cung cap **/
                    if($row_content['supplier_id']=='' OR !DB::exists('select id from supplier where code=\''.$row_content['supplier_id'].'\''))
                    {
                        $check_error = false;
                        $error[$i]['note'] .= '<span style="color: red;">- Nhà cung cấp không hợp lệ!</span><br />';
                    }
                    /** end **/
                    /** check giá */
                    if($row_content['price']=='')
                    {
                        $check_error = false;
                        $error[$i]['note'] .= '<span style="color: red;">- chưa nhập giá!</span><br />';
                    }
                    elseif($row_content['price']!='' and is_numeric($row_content['price'])!=1)
                    {
                        $check_error = false;
                        $error[$i]['note'] .= '<span style="color: red;">- sai định dạng giá</span><br />';
                    }
                    /** end check giá */
                    /** thanh tien */
                    if($row_content['price_after_tax']=='')
                    {
                        $check_error = false;
                        $error[$i]['note'] .= '<span style="color: red;">- chưa nhập thành tiền!</span><br />';
                    }
                    elseif($row_content['price_after_tax']!='' and is_numeric($row_content['price_after_tax'])!=1)
                    {
                        $check_error = false;
                        $error[$i]['note'] .= '<span style="color: red;">- sai định dạng thành tiền</span><br />';
                    }
                    /** endthanh tien */
                    /** check ngay bắt đầu ngày kết thúc */
                    if($row_content['starting_date']=='')
                    {
                        
                    }
                    elseif(strpos("/",$row_content['starting_date']))
                    {
                        
                        $check_error = false;
                        $error[$i]['note'] .= '<span style="color: red;">- định dạng từ ngày sai</span><br />';
                    }
                    elseif(!strpos("/",$row_content['starting_date']))
                    {
                        $arr = explode("/",$row_content['starting_date']);
                        //echo sizeof($arr);
                        if(sizeof($arr)==3)
                        {
                            if($arr[0]<0 OR $arr[0]>31 OR $arr[1]>12 OR $arr[1]<0 OR $arr[2]<1970)
                            {
                                $check_error = false;
                                $error[$i]['note'] .= '<span style="color: red;">- định dạng từ ngày sai</span><br />';
                            }
                        }
                        else
                        {
                            $check_error = false;
                            $error[$i]['note'] .= '<span style="color: red;">- định dạng từ ngày sai</span><br />';
                        }
                        
                    }
                    if($row_content['ending_date']=='')
                    {
                        
                    }
                    elseif(strpos("/",$row_content['ending_date']))
                    {
                        
                        $check_error = false;
                        $error[$i]['note'] .= '<span style="color: red;">- định dạng đến ngày sai</span><br />';
                    }
                    elseif(!strpos("/",$row_content['ending_date']))
                    {
                        $arr = explode("/",$row_content['ending_date']);
                        //echo sizeof($arr);
                        if(sizeof($arr)==3)
                        {
                            if($arr[0]<0 OR $arr[0]>31 OR $arr[1]>12 OR $arr[1]<0 OR $arr[2]<1970)
                            {
                                $check_error = false;
                                $error[$i]['note'] .= '<span style="color: red;">- định dạng đến ngày sai</span><br />';
                            }
                        }
                        else
                        {
                            $check_error = false;
                            $error[$i]['note'] .= '<span style="color: red;">- định dạng đến ngày sai</span><br />';
                        }
                        
                    }
                    /** end check ngay bắt đầu ngày kết thúc */
                    if($check_error==true)
                    {
                        $check_conflix = $this->check_conflix($row_content['code_product'],DB::fetch('select id from supplier where code=\''.$row_content['supplier_id'].'\'','id'),$row_content['starting_date'],$row_content['ending_date']);
                        if(!$check_conflix)
                        {
                            $check_error = false;
                            $error[$i]['note'] .= '<span style="color: red;">- trùng sản phẩm</span><br />';
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
        set_time_limit(-1);
        if(isset($col[$index]))
            return trim($col[$index]);
        else
            return $default;
    }
    function check_conflix($product_id,$supplier_id,$start_date,$end_date,$id=false)
    {
        $check = true;
        $cond = '';
        if($id)
        {
            $cond = ' AND id!='.$id;
        }
        if($start_date!='' AND $end_date==''
            AND DB::exists('select id from pc_sup_price where product_id=\''.$product_id.'\' AND supplier_id='.$supplier_id.' and (ending_date is null OR ending_date>=\''.Date_time::to_orc_date($start_date,'/').'\')'.$cond)
            )
        {
            $check = false;
        }
        if($start_date=='' AND $end_date!=''
            AND DB::exists('select id from pc_sup_price where product_id=\''.$product_id.'\' AND supplier_id='.$supplier_id.' and (ending_date is null OR ending_date<=\''.Date_time::to_orc_date($start_date,'/').'\')'.$cond)
            )
        {
            $check = false;
        }
        if($start_date!='' AND $end_date!=''
            AND DB::exists('select id from pc_sup_price where product_id=\''.$product_id.'\' AND supplier_id='.$supplier_id.' and (ending_date is null OR (ending_date>=\''.Date_time::to_orc_date($start_date,'/').'\' and starting_date<=\''.Date_time::to_orc_date($end_date,'/').'\'))'.$cond)
            )
        {
            $check = false;
        }
        if($start_date=='' AND $end_date==''
            AND DB::exists('select id from pc_sup_price where product_id=\''.$product_id.'\' AND supplier_id='.$supplier_id.' and (ending_date is null)'.$cond)
            )
        {
            $check = false;
        }
        return $check;
    }
}
?>