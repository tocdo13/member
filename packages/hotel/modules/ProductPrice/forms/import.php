<?php
class ImportProductPriceForm extends Form
{
	function ImportProductPriceForm()
	{
		Form::Form('ImportProductPriceForm');
	}
    
    function on_submit()
    {  
        require_once 'packages/core/includes/utils/vn_code.php';
        //import to db
        if(Url::get('save'))
        {
            $_SESSION['change'] = array();
            if (User::is_admin())
            {
                //System::debug($_SESSION['content']);
                //exit();
            }
            if(isset($_SESSION['content']) AND !empty($_SESSION['content']))
            {
                foreach($_SESSION['content'] as $key=>$value)
                {
                    //neu co ma san pham
                    if(trim($_SESSION['content'][$key]['product_id']))
                    {
                        $_SESSION['content'][$key]['department_code'] = Url::sget('department_code');
                        $_SESSION['content'][$key]['portal_id'] = Url::sget('portal_id')?Url::sget('portal_id'):PORTAL_ID;
                        //neu ton tai ma san pham
                        if($row = DB::fetch('Select * from product where id = \''.$_SESSION['content'][$key]['product_id'].'\' '))
                        {
                           // System::debug($row);
                            //neu sp da dc cap nhat gia
                            if($id = DB::fetch('select 
                                                    * 
                                                from 
                                                    product_price_list
                                                where 
                                                    product_id = \''.$_SESSION['content'][$key]['product_id'].'\' 
                                                    and department_code = \''.$_SESSION['content'][$key]['department_code'].'\' 
                                                    and portal_id = \''.$_SESSION['content'][$key]['portal_id'].'\' '))
                            {
                                $_SESSION['change'][$key] = $id;
                                $_SESSION['change'][$key]['price'] = System::display_number($id['price']);
                                $_SESSION['change'][$key]['product_name_1'] = $_SESSION['content'][$key]['product_name_1'];
                                $_SESSION['change'][$key]['product_name_2'] = $_SESSION['content'][$key]['product_name_2'];
                                $_SESSION['change'][$key]['unit'] = $_SESSION['content'][$key]['unit'];
                                $_SESSION['change'][$key]['type'] = $_SESSION['content'][$key]['type'];
                                $_SESSION['change'][$key]['category'] = $_SESSION['content'][$key]['category'];
                                $_SESSION['change'][$key]['new_price'] = System::display_number($_SESSION['content'][$key]['price']);
                                
                                unset($_SESSION['content'][$key]['product_name_1']);
                                unset($_SESSION['content'][$key]['product_name_2']);
                                unset($_SESSION['content'][$key]['unit']);
                                unset($_SESSION['content'][$key]['type']);
                                unset($_SESSION['content'][$key]['category']);
								unset($_SESSION['content'][$key]['category_id']);
                                
                                DB::update_id('product_price_list',$_SESSION['content'][$key],$id['id']);
                            }
                            else
                            {
                                unset($_SESSION['content'][$key]['product_name_1']);
                                unset($_SESSION['content'][$key]['product_name_2']);
                                unset($_SESSION['content'][$key]['unit']);
                                unset($_SESSION['content'][$key]['type']);
                                unset($_SESSION['content'][$key]['category']);
								unset($_SESSION['content'][$key]['category_id']);
                                DB::insert('product_price_list',$_SESSION['content'][$key]);
                            }
                                
                        }
                        else // neu chua co ma sp
                        {
                            $new_product = array(); 
                            //KID SUA DE IMPORT DUOC THAY CAU LENH SQL DA CMT BANG CAI BEN DUOI
                            //$sql = 'Select 
//                                        * 
//                                    from 
//                                        unit 
//                                    where 
//                                        (UPPER(fn_convert_to_vn(unit.name_1))) like \'%\' || UPPER((\''.trim(($_SESSION['content'][$key]['unit'])).'\')) || \'%\' 
//                                        OR (UPPER(fn_convert_to_vn(unit.name_2))) like \'%\' || UPPER((\''.trim(($_SESSION['content'][$key]['unit'])).'\')) || \'%\'';
                            
                            $sql = 'Select * from unit where (UPPER(unit.name_1)) = UPPER(\''.trim(($_SESSION['content'][$key]['unit'])).'\') OR (UPPER(unit.name_2)) = UPPER(\''.trim(($_SESSION['content'][$key]['unit'])).'\') ';
                            //END SUA
                            $unit = DB::fetch($sql);
                            //giap.ln edit(insert list product from excel)
                            if(empty($unit))
                            {
                                $row_unit = array('name_1'=>trim(($_SESSION['content'][$key]['unit'])),'name_2'=>trim(($_SESSION['content'][$key]['unit'])),'value'=>1);
                                $new_product['unit_id'] = DB::insert('unit',$row_unit);
                            }
                            else
                            {
                                $new_product['unit_id'] = $unit['id'];
                            }
                            //end giap.ln
                            if($category = DB::fetch('Select * from product_category where UPPER((product_category.name)) like \'%\' || UPPER((\''.trim(($_SESSION['content'][$key]['category'])).'\')) || \'%\''))
                            {
                                $new_product['category_id'] = $category['id'];
                                
                                $new_product['id'] = $_SESSION['content'][$key]['product_id'];
                                $new_product['name_1'] = $_SESSION['content'][$key]['product_name_1'];
                                $new_product['name_2'] = $_SESSION['content'][$key]['product_name_2'];
                                $new_product['type'] = $_SESSION['content'][$key]['type'];
                                $new_product['status'] = 'avaiable';
                                
                                
                                DB::insert('product',$new_product);
                                
                                unset($_SESSION['content'][$key]['product_name_1']);
                                unset($_SESSION['content'][$key]['product_name_2']);
                                unset($_SESSION['content'][$key]['unit']);
                                unset($_SESSION['content'][$key]['type']);
                                unset($_SESSION['content'][$key]['category']);
								unset($_SESSION['content'][$key]['category_id']);
                                //System::debug($_SESSION['content'][$key]);
                                
                                DB::insert('product_price_list',$_SESSION['content'][$key]);   
                            }
                        }
                    }
                }
                unset($_SESSION['content']);
                
                //System::debug($_SESSION['error']);
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
        $this->map['title'] = Portal::language('import');
        $portal_id = '';
        $portal_id = Url::get('portal_id')?Url::get('portal_id'):PORTAL_ID;
        //echo $portal_id;
		$department = DB::fetch_all(' select 
                                            portal_department.id as portal_department_id,
                                            portal_department.department_code as id,
                                            department.name_'.Portal::language().' as name ,
                                            portal_department.portal_id
                                        from 
                                            portal_department 
                                            inner join department on department.code = portal_department.department_code
                                        where 
                                            portal_department.PORTAL_ID = \''.$portal_id.'\'
                                        order by
                                            department.id   
                                    '
                                    );
        $department_list = ProductPriceDB::get_department($portal_id);
        $this->map['portal_id_list'] = String::get_list(Portal::get_portal_list());
        
        //upload file anh preview
        if(Url::get('do_upload'))
		{
            $file = $this->save_file('path_file');	
            require_once 'packages/core/includes/utils/PHPExcel/IOFactory.php';
			$objReader = new PHPExcel_Reader_Excel5();
			$objPHPExcel = $objReader->load($file);			
			$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
            $this->result = $this->parse_sheet($sheetData);
            @unlink($file);
            //Luu vao session
            $_SESSION['header'] = $this->result['header'];
            $_SESSION['content'] = $this->result['content'];
            $_SESSION['preview'] = $this->result['preview'];
            $_SESSION['error'] = $this->result['error'];
            $this->parse_layout('import', $this->map+array('department_list'=>$department_list,'preview'=>$_SESSION['preview'],'error'=>$_SESSION['error']));
		}
        else
        {
            if(!empty($_SESSION['change']))
            {
                $this->map['change'] = $_SESSION['change'];
                unset($_SESSION['change']);
                $this->parse_layout( 'import',$this->map+array('department_list'=>$department_list,'change'=>$this->map['change']) );
            }
            else
            {
                $_SESSION['change'] = array();
                $this->parse_layout('import',$this->map+array('department_list'=>$department_list));
            }       
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
			$category_code = $this->set_array($col,'F');
			$category = DB::fetch('select id,name from product_category where code=\''.$category_code.'\'');			
            if($rows == 1)
            {
                $header[$i] = array(
                                    'product_id'=>$col['A'],
                                    'product_name_1'=>$col['B'],
                                    'product_name_2'=>$col['C'],
                                    'unit'=>$col['D'],
                                    'type'=>$col['E'],
                                    'category'=>$col['F'],
                                    'price'=>$col['G'],
                                    );
            }
            else
            {
                $row_content = array(
                                    'product_id'=> strtoupper($this->set_array($col,'A')),
                                    'product_name_1'=> $this->set_array($col,'B'),
                                    'product_name_2'=> $this->set_array($col,'C'),
                                    'unit'=> $this->set_array($col,'D'),
                                    'type'=> strtoupper($this->set_array($col,'E')),
									'category_id'=>$category['id'],
                                    'category'=> $category['name'],
                                    'price'=> System::calculate_number($this->set_array($col,'G')),
                                    );
                                    
                $check_error = true;
                $error[$i] = $row_content;
                $error[$i]['note'] = '';
                /** manh them thong bao loi **/
                    // kiem tra tinh hop le cua cac truong trong $row_content - loi thi dua vao Array error - khong thi dua vao Array content
                    if($row_content['type']!='GOODS' AND $row_content['type']!='PRODUCT' AND $row_content['type']!='EQUIPMENT' AND $row_content['type']!='SERVICE' AND $row_content['type']!='TOOL' AND $row_content['type']!='MATERIAL' AND $row_content['type']!='DRINK')
                    {
                        $check_error = false;
                        $error[$i]['note'] .= '<span style="color: red;">- Loại sản phẩm không đúng</span><br />';
                    }
                    if(empty($category))
                    {
                        $check_error = false;
                        $error[$i]['note'] .= '<span style="color: red;">- Danh mục không tồn tại</span><br />';
                    }
                    if($check_error==true)
                    {
                        unset($error[$i]);
                        $content[$i]=$row_content;
                        if($i<=10)
					       $preview[$i] = $content[$i];
                    }
                /** end manh **/
				$i++;
            }
                
        }
        $result = array('header'=>$header, 'content'=>$content,'preview'=>$preview,'error'=>$error);
        //System::debug($preview);
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