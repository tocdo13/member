<?php
class ImportProductForm extends Form
{
	function ImportProductForm()
	{
		Form::Form('ImportProductForm');
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
            if(isset($_SESSION['content']) AND !empty($_SESSION['content']))
            {
                //dua d? li?u v?o db
                foreach($_SESSION['content'] as $key=>$value)
                {
                    //neu co ma san pham
                    if(trim($_SESSION['content'][$key]['id']))
                    {
                        //neu ton tai ma san pham
                        if($row = DB::fetch('Select * from product where id = \''.$_SESSION['content'][$key]['id'].'\' '))
                        {
                            $_SESSION['update'][$key] = $_SESSION['content'][$key];
							$product_id = $_SESSION['content'][$key]['id'];
                            unset($_SESSION['content'][$key]['id']);
                            unset($_SESSION['content'][$key]['unit']);
                            unset($_SESSION['content'][$key]['category']);
							DB::update('product',$_SESSION['content'][$key],'id=\''.$product_id.'\'');
                        }
                        else // neu chua co ma sp
                        {
                            unset($_SESSION['content'][$key]['unit']);
                            unset($_SESSION['content'][$key]['category']);
                            DB::insert('product',$_SESSION['content'][$key]);
                        }
                    }
                }
                //dua xong xoa session
                unset($_SESSION['content']);
                //System::debug($_SESSION['error']);
                Url::redirect_current(array('cmd','action'=>'success'));       
            }
            else
                $this->error('upload_file_missing',Portal::language('re_upload'));
        } 
	}
	function draw()
	{

        $this->map = array();
        $this->map['title'] = Portal::language('import');
        $categories = DB::fetch_all('
		select
			id, product_category.name as name,structure_id
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
            require_once 'packages/core/includes/utils/PHPExcel/IOFactory.php';
			$objReader = new PHPExcel_Reader_Excel5();
			$objPHPExcel = $objReader->load($file);			
			$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
            //file chi co 1 sheet
            //xu ly du lieu va delete file
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
            //n?u c? d?ng l?i th? show ra
            if(!empty($_SESSION['update']))
            {
				//echo 1;
                $this->map['update'] = $_SESSION['update'];
                //x?a session l?i
                //unset($_SESSION['update']);
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
        $error = array();

        $i = 1;
        foreach($sheet as $rows=>$col)
        {
			if($rows == 1)
            {
				$header[$i] = array(
                                    'product_id'=>$col['A'],
                                    'product_name_1'=>$col['B'],
                                    'product_name_2'=>$col['C'],
                                    'unit'=>$col['D'],
                                    'type'=>$col['E'],
                                    'category'=>$col['F']
                                    );
			}
			else
			{
				$type = strtoupper($this->set_array($col,'E'));
				if(!$type)
                {
					$type = 'GOODS';
				}
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
				$category_code = $this->set_array($col,'F');
				$category = DB::fetch('select id,name from product_category where code=\''.$category_code.'\'');
                //bo qua dong nay neu ko dung cat
                //if(empty($category))
                    //continue;
                
				$row_content = array(
									'id'=> strtoupper($this->set_array($col,'A')),
									'name_1'=>$this->set_array($col,'B'),
									'name_2'=> $this->set_array($col,'C'),
									'unit_id'=> $unit_id,
									'unit'=>$unit,
									'type'=> $type,
									'category_id'=> $category['id'],
									'category'=>$category['name'],
									'status'=>'avaiable'
									);
                $check_error = true;
                $error[$i] = $row_content;
                $error[$i]['note'] = '';
                /** manh them thong bao loi **/
                    // kiem tra tinh hop le cua cac truong trong $row_content - loi thi dua vao Array error - khong thi dua vao Array content
                    if(DB::exists("SELECT id FROM product WHERE id='".$row_content['id']."'"))
                    {
                        $check_error = false;
                        $error[$i]['note'] .= '<span style="color: red;">- Trùng mã sản phẩm</span><br />';
                    }
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
        $result = array('header'=>$header,'content'=>$content,'preview'=>$preview,'error'=>$error);
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