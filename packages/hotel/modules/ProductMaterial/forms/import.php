<?php
class ImportProductMaterialForm extends Form
{
	function ImportProductMaterialForm()
	{
		Form::Form('ImportProductMaterialForm');
	}	
    
	function on_submit()
    {  
        require_once 'packages/core/includes/utils/vn_code.php';
        //import to db
        if(Url::get('save'))
        {
            //Session d? luu c?c san pham loi~
            $_SESSION['error'] = array();
            //d?c d? li?u
            if(isset($_SESSION['content']))
            {
                //System::debug($_SESSION['content']);
                //exit();
                //dua d? li?u v?o db
                foreach($_SESSION['content'] as $key=>$value)
                {
                    //neu co ma san pham
                    if(trim($_SESSION['content'][$key]['product_id']))
                    {
                        //neu ton tai ma san pham
                        if($row = DB::fetch('Select product.* 
                                                from product 
                                                inner join unit on product.unit_id = unit.id 
                                                --- Nam89vn Edit import with where product.type = drink
                                                where (product.type = \'PRODUCT\' OR  product.type = \'DRINK\')
                                                      and product.id = \''.$_SESSION['content'][$key]['product_id'].'\' '))
                        {
                            if(is_array($value['materials']))
                            {
                                $check = true;
                                foreach($value['materials'] as $k => $material)
                                {
                                    if(!$row_material = DB::fetch('Select product.* from product inner join unit on product.unit_id = unit.id where (product.type = \'MATERIAL\' or product.type = \'DRINK\') and product.id = \''.$material['material_code'].'\' '))
                                    {
                                        $_SESSION['error'][$key] = $_SESSION['content'][$key];
                                        $_SESSION['error'][$key]['error_desc'] = $material['material_code'].' '.Portal::language('do_not_exist').' '.Portal::language('or_is_not').' Nguyên vật liệu';;
                                        $check = false;
                                        break;   
                                    }
                                }
                                if($check)
                                {
                                    foreach($value['materials'] as $k => $material)
                                    {
                                        $record['product_id'] = $_SESSION['content'][$key]['product_id'];
                                        $record['material_id'] = $material['material_code'];
                                        $record['quantity'] = System::calculate_number($material['quantity']);
                                        $record['portal_id'] = PORTAL_ID;
                                        DB::insert('product_material', $record);
                                    }
                                    
                                }
                            }    
                        }
                        else // neu chua co ma sp
                        {
                            $_SESSION['error'][$key] = $_SESSION['content'][$key];
                            $_SESSION['error'][$key]['error_desc'] = $_SESSION['content'][$key]['product_id'].' '.Portal::language('do_not_exist').' '.Portal::language('or_is_not').' PRODUCT';
                            
                        }
                    }
                }
                
                //dua xong xoa session
                unset($_SESSION['content']);
                
                if(empty($_SESSION['error']))
                    Url::redirect_current();       
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
        
        //upload file anh preview
        if(Url::get('do_upload'))
		{
            $file = $this->save_file('path_file');	
            require_once 'packages/core/includes/utils/PHPExcel/IOFactory.php';
			$objReader = new PHPExcel_Reader_Excel5();
			$objPHPExcel = $objReader->load($file);			
			$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);

            //file ch? c? 1 sheet
            //x? l? d? li?u v? delete  file
            $this->result = $this->parse_sheet($sheetData);
            //System::debug($this->result);
            
            @unlink($file);
            //Luu vao session
            $_SESSION['content'] = $this->result['content'];
            $_SESSION['preview'] = $this->result['preview'];
            
            if(is_array($_SESSION['preview']))
            {
                foreach($_SESSION['preview'] as $key => $value)
                {
                    $_SESSION['preview'][$key]['desc_materials'] = '';
                    if(is_array($value['materials']))
                    {
                        foreach($value['materials'] as $k => $material)
                        {
                            $_SESSION['preview'][$key]['desc_materials'] .= $_SESSION['preview'][$key]['desc_materials'] ?  ', '.$material['material_name'].' ('.$material['quantity'].' '.$material['unit_name'].')' : $material['material_name'].' ('.$material['quantity'].' '.$material['unit_name'].')';;
                        }
                    }
                    
                }
            }
            //System::debug($_SESSION['preview']);
            //System::debug($_SESSION['content']);
            $this->parse_layout('import', $this->map+array('preview'=>$_SESSION['preview']));
		}
        else
        {
            //n?u c? d?ng l?i th? show ra
            if(!empty($_SESSION['error']))
            {
                $this->map['error'] = $_SESSION['error'];
                foreach($this->map['error'] as $key => $value)
                {
                    $this->map['error'][$key]['desc_materials'] = '';
                    if(is_array($value['materials']))
                    {
                        foreach($value['materials'] as $k => $material)
                        {
                            $this->map['error'][$key]['desc_materials'] .= $this->map['error'][$key]['desc_materials'] ?  ', '.$material['material_name'].' - '.$material['material_code'].' ('.$material['quantity'].' '.$material['unit_name'].')' : $material['material_name'].' - '.$material['material_code'].' ('.$material['quantity'].' '.$material['unit_name'].')';;
                        }
                    }
                    
                }
                //x?a session l?i
                unset($_SESSION['error']);
                $this->parse_layout( 'import',$this->map+array('error'=>$this->map['error']) );
            }
            else
            {
                $_SESSION['error'] = array();
                $this->parse_layout('import',$this->map+array());
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
        $content = array();
        $preview = array();
        $materials = DB::fetch_all('select product.id, unit.name_1 as unit_name from product inner join unit on product.unit_id = unit.id where product.type = \'MATERIAL\'');
        $i = 0;
        foreach($sheet as $rows=>$col)
        {
			//$category_code = $this->set_array($col,'F');
			//$category = DB::fetch('select id,name from product_category where code=\''.$category_code.'\'');			
            if($rows != 1)
            {
                /** START: Daund Kiem tra nguyen vat lieu khi upload */
                $check_array = array('id'=> strtoupper($this->set_array($col,'C')));
                $check_material = DB::exists("SELECT id FROM product where id = '".$check_array['id']."'");
                if(($check_array['id'] != $check_material['id']))
                {
                    $col_C = '<span style="color: red;">- Nguyên vật liệu không tồn tại '.$check_array['id'].'</span>';
                }else
                {
                    $col_C = ( isset($materials[strtoupper($this->set_array($col,'C'))])?$materials[strtoupper($this->set_array($col,'C'))]['unit_name']:'' );
                }
                /** END: Daund Kiem tra nguyen vat lieu khi upload */
                if($this->set_array($col,'A'))
                {
                    $j=1;
                    $i++;
                    $content[$i] = array(
                                        'product_id'=> strtoupper($this->set_array($col,'A')),
                                        'product_name_1'=> $this->set_array($col,'B'),
                                        'materials'=>array(
                                                            $j=>array(
                                                                'material_code'=> strtoupper($this->set_array($col,'C')),
                                                                'material_name'=> $this->set_array($col,'D'),
                                                                'quantity'=> System::calculate_number($this->set_array($col,'E')),
                                                                'unit_name'=> $col_C,
                                                            ),
                                                            
                                                        ),
    
                                        );
                }
                else
                {
                    if($this->set_array($col,'C'))
                    {
                        $content[$i]['materials'][$j] = array(
                                                        'material_code'=> strtoupper($this->set_array($col,'C')),
                                                        'material_name'=> $this->set_array($col,'D'),
                                                        'quantity'=> System::calculate_number($this->set_array($col,'E')),
                                                        'unit_name'=> $col_C,
                                                    );
                        
                    }
                    
                    
                }
                $j++;

                if($i<=10)
                    $preview[$i] = $content[$i];
            }
                
        }
        $result = array('content'=>$content,'preview'=>$preview);
        //System::debug($result);
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
