<?php
class ImportStartTermRemainForm extends Form
{
	function ImportStartTermRemainForm()
	{
		Form::Form('ImportStartTermRemainForm');
	}	
    
	function on_submit()
	{
        require_once 'packages/core/includes/utils/vn_code.php';
        //import to db
        if(Url::get('save'))
        {
            //Session de luu cac san pham loi
            $_SESSION['error'] = array();
            //doc du lieu
            if(isset($_SESSION['content']))
            {
                //dua du lieu vao db
                foreach($_SESSION['content'] as $key=>$value)
                {
                    //neu co ma san pham
                    if(isset($value) and trim($value['id']))
                    {
                        //neu ton tai ma san pham
                        if($row = DB::fetch('Select * from product where id = \''.$_SESSION['content'][$key]['id'].'\' '))
                        {
                            if($old_id = DB::fetch('SELECT * FROM wh_start_term_remain WHERE product_id=\''.$_SESSION['content'][$key]['id'].'\' and warehouse_id ='.$_SESSION['content'][$key]['warehouse_id'].' and portal_id = \''.PORTAL_ID.'\' ','id'))
                            {
								DB::update_id('wh_start_term_remain',array('quantity'=>$_SESSION['content'][$key]['quantity'],
                                                                            'total_start_term_price'=>$_SESSION['content'][$key]['total_start_term_price'],
                                                                            'start_term_price'=>($_SESSION['content'][$key]['total_start_term_price']/$_SESSION['content'][$key]['quantity'])
                                                                            ),$old_id);
							}
                            else
                            {
                                $record['warehouse_id'] = $value['warehouse_id'];
                                $record['product_id'] = $value['id'];
                                $record['quantity'] = $value['quantity'];
                                $record['portal_id'] = PORTAL_ID;
                                $record['total_start_term_price'] = $value['total_start_term_price'];
                                if($value['quantity']!=0){
                                    $record['start_term_price'] = $value['total_start_term_price']/$value['quantity'];
                                }
                                else{
                                    $record['start_term_price'] = $value['total_start_term_price'];
                                }
                                DB::insert('wh_start_term_remain',$record);   
                                unset($record); 
                            }
                        }
                        else // neu chua co ma sp
                        {
                            DB::insert('product',array('name_1'=>$value['name_1'],'name_2'=>$value['name_2'],'id'=>$value['id'],'category_id'=>$value['category_id'],'unit_id'=>$value['unit_id'],'type'=>$value['type'],'status'=>'avaiable'));
                            $record['warehouse_id'] = $value['warehouse_id'];
                            $record['product_id'] = $value['id'];
                            $record['quantity'] = $value['quantity'];
                            $record['portal_id'] = PORTAL_ID;
                            $record['total_start_term_price'] = $value['total_start_term_price'];
                            $record['start_term_price'] = $value['total_start_term_price']/$value['quantity'];
                            DB::insert('wh_start_term_remain',$record);   
                            unset($record); 
                        }
                    }
                }
                unset($_SESSION['content']);
                Url::redirect_current(array('cmd','action'=>'success'));   
            }
            //Neu khong thi up lai file
            else
                $this->error('upload_file_missing',Portal::language('re_upload'));
        } 
	}
	function draw()
	{
        //System::debug($_SESSION['error']);
        $this->map = array();
        $this->map['title'] = Portal::language('import');
        $categories = DB::fetch_all('
		select
			id, 
            product_category.name as name,
            structure_id
		from
			product_category
        where
            structure_id != \''.ID_ROOT.'\'
		order by
			structure_id
		');
		$this->map['category_id_list'] = String::get_list($categories);
        
        //upload file anh preview
        if(Url::get('do_upload'))
		{   
            $file = $this->save_file('path_file');
            //System::debug($file);
            require_once 'packages/core/includes/utils/PHPExcel/IOFactory.php';
			$objReader = new PHPExcel_Reader_Excel5();
            //System::debug($objReader);exit();	
			$objPHPExcel = $objReader->load($file);	
            	
			$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
            //file chi co 1 sheet
            //xu ly du lieu va delete file
            //System::debug($sheetData);
            //exit();
            $this->result = $this->parse_sheet($sheetData);
            @unlink($file);
            //Luu vao session
            $_SESSION['header'] = $this->result['header'];
            $_SESSION['content'] = $this->result['content'];
            $_SESSION['preview'] = $this->result['preview'];
            $_SESSION['errors'] = $this->result['errors'];
            //System::debug($_SESSION['preview']);exit();
            $this->parse_layout('import', $this->map+array('preview'=>$_SESSION['preview'],'errors' => $_SESSION['errors']));
		}
        else
        {
            if(!empty($_SESSION['update']))
            {
                $this->map['update'] = $_SESSION['update'];
                $this->parse_layout( 'import',$this->map+array('update'=>$this->map['update']) );
            }
            else
            {
                $_SESSION['update'] = array();
                $this->parse_layout('import',$this->map);
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
        require_once 'packages/core/includes/utils/vn_code.php';
        $header = array();
        $content = array();
        $preview = array();
        $errors = array();
        $i = 1;
        $j = 1;
        foreach($sheet as $rows=>$col)
        {
            //System::debug($col); 
			if($rows == 1)
            {
				$header[$i] = array(
                                    'product_id'=>$col['A'],
                                    'product_name_1'=>$col['B'],
                                    'product_name_2'=>$col['C'],
                                    'unit'=>$col['E'],
                                    'type'=>$col['F'],
                                    'category'=>$col['G']
                                    );
			}
			else
			{
			    $product_id = $this->set_array($col,'A');
			    if($product_id != '' and $product_id != 'MÃ')
                {
                    // loai hang hoa eg. GOOD, Product ...
    				$type = strtoupper($this->set_array($col,'E'));
    				if(!$type)
                    {
    					$type = 'GOODS';
    				}
                    $type_arr = array('GOODS','PRODUCT','MATERIAL','DRINK','SERVICE','EQUIPMENT','TOOL');
                    if(!in_array($type,$type_arr))
                    {
                        $type = '';
                    }
                    // Don vi tinh
    				$unit = $this->set_array($col,'D');
    				if(!$unit)
    				{
    					$unit_id = 97;
    					$unit = Portal::language('piece');
    				}
    				else
    				{
    					if($row = DB::fetch('Select * from unit where (UPPER(unit.name_1)) = UPPER(\''.trim(($unit)).'\') OR (UPPER(unit.name_2)) = UPPER(\''.trim(($unit)).'\') '))
    					{
                            //System::debug($row);
    						$unit_id = $row['id'];
    						$unit = $row['name_1'];
                            //exit();
    					}
    					else
    					{
    						$unit_id = DB::insert('unit',array('name_1'=>$unit,'name_2'=>$unit,'value'=>1));
    					}
    				}
                    // danh muc san pham	
    				$category_code = $this->set_array($col,'F');
                    if($category_code)
                    {
                        $category = DB::fetch('select id,name from product_category where code=\''.$category_code.'\'');
                        if(empty($category))
                        {
                            $category['id'] = '';
                            $category['name'] = '';
                        }   
                    }
                    else
                    {
                        $category['id'] = '';
                        $category['name'] = '';
                    }
                        
                    // warehouse_id
                    $warehouse_code = $this->set_array($col,'G');
                    if($warehouse_code)
                    {
                        $warehouse_id = DB::fetch('select id from warehouse where code = \''.$warehouse_code.'\'','id');
                    }
                    else
                    {
                        $warehouse_id = '';
                    }
                    //start_term_remain_quantity
                    
                    $start_term_quantity = $this->set_array($col,'H');
                    /*
                    if(!$start_term_quantity)
                    {
                        $start_term_quantity = 0;
                    }
                    */
                    //tong tien dau ki
                    $start_term_price = $this->set_array($col,'I');
                    /*
                    if(!$start_term_price)
                    {
                        $start_term_price = 0;
                    }
                    */
                    $id = strtoupper($this->set_array($col,'A'));
                    $name_1 = $this->set_array($col,'B');
                    $name_2 = $this->set_array($col,'C');
                    if($name_2 == '')
                    {
                        $name_2 = $name_1;
                    }
    				$content[$i] = array(
    									'id'=> $id,
    									'name_1'=>$name_1,
    									'name_2'=> $name_2,
    									'unit_id'=> $unit_id,
    									'unit'=>$unit,
    									'type'=> $type,
                                        'warehouse_id'=> $warehouse_id,
                                        'warehouse_code' => $warehouse_code,
                                        'quantity'=> $start_term_quantity,
                                        'total_start_term_price'=> $start_term_price,
                                        'START_TERM_PRICE' =>$start_term_quantity != 0? $start_term_price/$start_term_quantity : 0,
    									'category_id'=> $category['id'],
    									'category'=>$category['name'],
                                        'row_number'=>$rows,
    									'status'=>'avaiable'
    									);
             
                    //System::debug($content[$i]);
                    //echo $start_term_quantity."*";
                    if($id == '' or $name_1 == '' or $name_2 == '' or $unit == '' or $unit_id == '' or $type == '' or $warehouse_id == '' or $start_term_quantity == '' or $start_term_price == '' or $category['id'] == '')
                    {
                        if($id == '')
                        {
                            if(!isset($errors[$j]))
                            {
                                $errors[$j] = $content[$i];
                                $errors[$j]['note'] = 'Invalid product_id';       
                            }
                            else
                            {
                                $errors[$j]['note'] .= ', product_id';
                            }
                        }
                        if($name_1 == '')
                        {
                            if(!isset($errors[$j]))
                            {
                                $errors[$j] = $content[$i];
                                $errors[$j]['note'] = 'Invalid name_1';       
                            }
                            else
                            {
                                $errors[$j]['note'] .= ', name_1';
                            }
                        }
                        if($name_2 == '')
                        {
                            if(!isset($errors[$j]))
                            {
                                $errors[$j] = $content[$i];
                                $errors[$j]['note'] = 'Invalid name_2';       
                            }
                            else
                            {
                                $errors[$j]['note'] .= ', name_2';
                            }
                        }
                        if($unit == '')
                        {
                            if(!isset($errors[$j]))
                            {
                                $errors[$j] = $content[$i];
                                $errors[$j]['note'] = 'Invalid unit';       
                            }
                            else
                            {
                                $errors[$j]['note'] .= ', unit';
                            }
                        }
                        if($unit_id == '')
                        {
                            if(!isset($errors[$j]))
                            {
                                $errors[$j] = $content[$i];
                                $errors[$j]['note'] = 'Invalid unit_id';       
                            }
                            else
                            {
                                $errors[$j]['note'] .= ', unit_id';
                            }
                        }
                        if($type == '')
                        {
                            if(!isset($errors[$j]))
                            {
                                $errors[$j] = $content[$i];
                                $errors[$j]['note'] = 'Invalid type';       
                            }
                            else
                            {
                                $errors[$j]['note'] .= ', type';
                            }
                        }
                        if($warehouse_id == '')
                        {
                            if(!isset($errors[$j]))
                            {
                                $errors[$j] = $content[$i];
                                $errors[$j]['note'] = 'Invalid warehouse_id';       
                            }
                            else
                            {
                                $errors[$j]['note'] .= ', warehouse_id';
                            }
                        }
                        if($start_term_quantity == '')
                        {
                            if(!isset($errors[$j]))
                            {
                                $errors[$j] = $content[$i];
                                $errors[$j]['note'] = 'Invalid quantity';       
                            }
                            else
                            {
                                $errors[$j]['note'] .= ', quantity';
                            }
                        }
                        if($start_term_price == '')
                        {
                            if(!isset($errors[$j]))
                            {
                                $errors[$j] = $content[$i];
                                $errors[$j]['note'] = 'Invalid start_term_price';       
                            }
                            else
                            {
                                $errors[$j]['note'] .= ', start_term_price';
                            }
                        }
                        if($category['id'] == '')
                        {
                            if(!isset($errors[$j]))
                            {
                                $errors[$j] = $content[$i];
                                $errors[$j]['note'] = 'Invalid category';       
                            }
                            else
                            {
                                $errors[$j]['note'] .= ', category_id';
                            }
                        }
                        $j += 1;
                        unset($content[$i]);
                        $i -= 1;
                    }
    				if($i<=10 and isset($content[$i]))
    					$preview[$i] = $content[$i];
    				$i++;
                }
			}    
        }
        //exit();
        $result = array('header'=>$header,'content'=>$content,'preview'=>$preview,'errors' => $errors);
        //System::debug($result);exit();
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