<?php
class ListProductPriceForm extends Form
{
	function ListProductPriceForm()
	{
		Form::Form('ListProductPriceForm');
        $this->link_js('packages/core/includes/js/jquery/datepicker.js');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
	}
    
    function on_submit()
    {  
        if(Url::get('act')=='edit_selected')
        {
       	  	if(is_array(URL::get('selected_ids')))
    		{
    			$selected_ids = implode(',',URL::get('selected_ids'));
    			Url::redirect_current(array('cmd'=>'edit','id'=>$selected_ids,'portal_id'=>Url::sget('portal_id')));
    		}
        }
        
        if(Url::get('cmd')=='delete_selected' and User::can_delete(false,ANY_CATEGORY) and Url::get('selected_ids'))
        {
       	  	$selected_ids = Url::get('selected_ids');
            $bar_reservation_product = DB::fetch_all('select price_id as id from bar_reservation_product where '.((sizeof($selected_ids)>1)?'price_id in (\''.join($selected_ids,'\',\'').'\')':'price_id=\''.reset($selected_ids).'\'').'');
            if(is_array(URL::get('selected_ids')))
    		{
    			$selected_ids = URL::get('selected_ids');
                foreach($selected_ids as $value)
                {
                    if(isset($bar_reservation_product[$value]))
                    {
                        echo '<script>';
            			echo 'alert(" Đã tồn tại dữ liệu trong hóa đơn. Không thể xóa !");';
            			echo '</script>';
                        return false;
                    }
                    DB::delete_id('product_price_list',$value);
                }
    		}
            else
            {
                if(isset($bar_reservation_product[Url::get('selected_ids')]))
                {
                    echo '<script>';
        			echo 'alert(" Đã tồn tại dữ liệu trong hóa đơn. Không thể xóa !");';
        			echo '</script>';
                    return false;
                }
                DB::delete_id('product_price_list',Url::get('selected_ids'));
            }
            ProductPriceDB::export_cache();
            Url::redirect('notice',array('cmd'=>'delete','href'=>'?page='.Url::get('page')));
        }
    }
	function draw()
    {
        $this->map = array();
        $cond = '';
        //get portal
        $portal_id = '';
        $portal_id = Url::get('portal_id')?Url::get('portal_id'):PORTAL_ID;
        //echo $portal_id;
        //get department trong 1 portal
		$department = DB::fetch_all(' select 
                                            portal_department.id as portal_department_id,
                                            code as id,
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
        
        //$this->map['department_code_list'] = array(''=>Portal::language('select_department'))+String::get_list($department);
		
        $item_per_page = 100;
        
        //mac dinh search theo 1 portal
        $cond .= $this->get_search_cond_portal($portal_id);
        
        //
        //if(Url::get('search'))
        {
            if(Url::get('product_id'))
                $cond .= ' AND UPPER(product_price_list.product_id) LIKE UPPER(\'%'.Url::sget('product_id').'%\') ';
            if(Url::get('product_name'))
            {
                require_once 'packages/core/includes/utils/vn_code.php';
                $cond .= ' AND FN_CONVERT_TO_VN(UPPER(product.name_'.Portal::language().')) LIKE UPPER(\'%'.trim(convert_utf8_to_latin(Url::sget('product_name'))).'%\') ';
            }    
            if(Url::get('category_id') && Url::get('category_id') !=1)
            {
                //$cond.= ' and '.IDStructure::child_cond(DB::structure_id('product_category', URL::get('category_id',1)),false,'product_category.').'';
                $category_id_cond = ' AND product.category_id in ( '.Url::get('category_id');
                $structure_id = DB::fetch('select * from product_category where id='.Url::get('category_id'),'structure_id');
                $product_arr = DB::fetch_all('select * from product_category where '.IDStructure::direct_child_cond($structure_id,true));
                foreach($product_arr as $k=>$v)
                {
                    $category_id_cond.= ','.$k;
                }
                $category_id_cond.= ' )';
                $cond .= $category_id_cond;
            }
                
            if(Url::get('type'))
                $cond .= ' AND product.type = \''.Url::get('type').'\' ';
            if(Url::get('start_date'))
                $cond .= ' AND product_price_list.start_date >= \''.Date_Time::to_orc_date(Url::get('start_date')).'\' ';
            if(Url::get('end_date'))
                $cond .= ' AND product_price_list.end_date <= \''.Date_Time::to_orc_date(Url::get('end_date')).'\' ';
            if(Url::get('department_code'))
                $cond .= ' AND product_price_list.department_code = \''.Url::get('department_code').'\' ';
        }
        
        
        //Neu search theo bo phan
        if(Url::get('act')=='search_department')
            $cond .= $this->get_search_cond_department();
        
        //echo $cond;
		
		$sql =    '
        			SELECT
        				count(*) AS acount
        			FROM
        				product_price_list
        				INNER JOIN product ON product.id = product_price_list.product_id
                        INNER JOIN department on product_price_list.department_code = department.code
        			WHERE 
                        1=1 '.$cond.'
                    ORDER BY
                        department.code
        		  ';
		//Phan trang
        require_once 'packages/core/includes/utils/paging.php';
		$this->map['total'] =  DB::fetch($sql,'acount');
		$this->map['paging'] =  paging( $this->map['total'],$item_per_page,10,false,'page_no',array( 'department_code','portal_id'=>Url::get('portal_id'),'type','category_id','product_id','product_name','start_date','end_date' ) );       
               
        $sql = '
        			SELECT * FROM
        			(
        				SELECT
        					product_price_list.id,
        					product_price_list.price,
        					TO_CHAR(product_price_list.start_date,\'dd/mm/YYYY\') as start_date,
        					TO_CHAR(product_price_list.end_date,\'dd/mm/YYYY\') as end_date,
        					product.name_'.Portal::language().' as product_name,
        					product.id as product_id,
        					unit.name_'.Portal::language().' as unit,
                            product_price_list.department_code,
                            department.name_'.Portal::language().' as department_name,
                            product_price_list.portal_id,
                            product.type,
                            product_category.name as category_name,
        					row_number() OVER (ORDER BY product_price_list.department_code ASC) AS rownumber
        				FROM
        					product_price_list
        					INNER JOIN product ON product.id = product_price_list.product_id
        					INNER JOIN unit on unit.id = product.unit_id
                            INNER JOIN department on product_price_list.department_code = department.code
                            Left join product_category on product_category.id = product.category_id
        				WHERE
                            1=1 '.$cond.'
        				ORDER BY
        					product_price_list.department_code
        			)
        			WHERE
        			 	rownumber > '.((page_no()-1)*$item_per_page).' and rownumber<='.((page_no())*$item_per_page).'	
                ';
		//Lay du lieu
        $items = DB::fetch_all($sql);
        //System::debug($items);
		$i = 1;
        foreach($items as $key=>$value)
        {
			$items[$key]['i'] = $i++;
			$items[$key]['price'] = System::display_number($value['price']);
		}
        
        $department_list = '<option value="">'.Portal::language('select_department').'</option>'.ProductPriceDB::get_department($portal_id);
        if(Url::get('export_to_excel'))
        {
            $this -> export_to_excel();
        }
        $this->map['category_id_list'] = String::get_list(DB::fetch_all('Select * from product_category order by structure_id'));
		$this->map['items'] = $items;
        $this->map['portal_id_list'] = array(''=>Portal::language('select_portal'))+String::get_list(Portal::get_portal_list());
		$this->parse_layout('list',$this->map+array('portal_id'=>$portal_id,'department_list'=>$department_list));
	}
    
    function export_to_excel()
    {
        require_once ROOT_PATH.'packages/core/includes/utils/PHPExcel.php';		
  		require_once ROOT_PATH.'packages/core/includes/utils/PHPExcel/RichText.php';
  		include ROOT_PATH.'packages/core/includes/utils/PHPExcel/IOFactory.php';
        
        $objPHPExcel = new PHPExcel();
		$objPHPExcel->setActiveSheetIndex(0);
        
        $i = 1;
        $objPHPExcel->getActiveSheet()->setCellValue('A'.$i, 'Mã sản phẩm');
		$objPHPExcel->getActiveSheet()->setCellValue('B'.$i, 'Tên(VN)');
		$objPHPExcel->getActiveSheet()->setCellValue('C'.$i, 'Tên tiếng anh');
		$objPHPExcel->getActiveSheet()->setCellValue('D'.$i, 'Đơn vị tính');
		$objPHPExcel->getActiveSheet()->setCellValue('E'.$i, 'Loại');
		$objPHPExcel->getActiveSheet()->setCellValue('F'.$i, 'Danh mục');
		$objPHPExcel->getActiveSheet()->setCellValue('G'.$i, 'Đơn giá');
        $objPHPExcel->getActiveSheet()->setCellValue('H'.$i, 'Bộ Phận');
        
        $cond = '1=1 ';
        if(Url::get('selected_ids'))
        {
            $product = Url::get('selected_ids');
            
            $product_id='';
            for($i=0;$i<count($product);$i++)
            {
                $product_id .= $product[$i].',';
                
            }
            $cond = 'product_price_list.id IN('.rtrim($product_id,',').' ) ';
            //system::debug($cond);die();
        }
        
        if(Url::get('product_id'))
                $cond .= ' AND UPPER(product_price_list.product_id) LIKE UPPER(\'%'.Url::sget('product_id').'%\') ';
        if(Url::get('product_name'))
        {
            require_once 'packages/core/includes/utils/vn_code.php';
            $cond .= ' AND FN_CONVERT_TO_VN(UPPER(product.name_'.Portal::language().')) LIKE UPPER(\'%'.trim(convert_utf8_to_latin(Url::sget('product_name'))).'%\') ';
        }    
        if(Url::get('category_id') && Url::get('category_id') !=1)
            $cond .= ' AND product.category_id = \''.Url::get('category_id').'\' ';
        if(Url::get('type'))
            $cond .= ' AND product.type = \''.Url::get('type').'\' ';
        if(Url::get('start_date'))
            $cond .= ' AND product_price_list.start_date >= \''.Date_Time::to_orc_date(Url::get('start_date')).'\' ';
        if(Url::get('end_date'))
            $cond .= ' AND product_price_list.end_date <= \''.Date_Time::to_orc_date(Url::get('end_date')).'\' ';
        if(Url::get('department_code'))
            $cond .= ' AND product_price_list.department_code = \''.Url::get('department_code').'\' ';        
        $export = DB::fetch_all(' 
                                SELECT
            					product_price_list.id,
            					product_price_list.price,
            					product.name_1,
                                product.name_2,
                                product_price_list.department_code,
                                product.name_'.Portal::language().' as product_name,
            					product.id as product_id,
            					unit.name_'.Portal::language().' as unit,
                                department.name_'.Portal::language().' as department_name,
                                product_price_list.portal_id,
                                product.type,
                                product_category.name as category_name,
            					row_number() OVER (ORDER BY product_price_list.department_code ASC) AS rownumber
            				FROM
            					product_price_list
            					INNER JOIN product ON product.id = product_price_list.product_id
            					INNER JOIN unit on unit.id = product.unit_id
                                INNER JOIN department on product_price_list.department_code = department.code
                                Left join product_category on product_category.id = product.category_id
            				WHERE
                                '.$cond.'
            				ORDER BY
            					product_price_list.department_code
                                ');
         $i++;
         foreach($export as $key=>$value)    
         {
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$i, $value['product_id']);
    		$objPHPExcel->getActiveSheet()->setCellValue('B'.$i, $value['name_1']);
    		$objPHPExcel->getActiveSheet()->setCellValue('C'.$i, $value['name_2']);
    		$objPHPExcel->getActiveSheet()->setCellValue('D'.$i, $value['unit']);
    		$objPHPExcel->getActiveSheet()->setCellValue('E'.$i, $value['type']);
    		$objPHPExcel->getActiveSheet()->setCellValue('F'.$i, $value['category_name']);
    		$objPHPExcel->getActiveSheet()->setCellValue('G'.$i, System::display_number($value['price']));
            $objPHPExcel->getActiveSheet()->setCellValue('H'.$i, $value['department_name']);
            $i++;
         }
         $fileName = "product_price_export_".(str_replace('/','-',Date('H-i-d-m-y'))).".xls";
         //echo $fileName;die();
        //System::debug($objPHPExcel); exit();
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save($fileName);
		if(file_exists($fileName))
        {
			echo '<script>';
			echo 'window.location.href = \''.$fileName.'\';';
			echo ' </script>';
		}
        else
        {
			echo '<script>';
			echo 'alert(" Export dữ liệu không thành công !");';
			echo '</script>';
		}
        Url::build_current();
    }
    
    function get_search_cond_portal($portal_id)
    {
        $cond = ' AND product_price_list.portal_id = \''.$portal_id.'\' ';
        return $cond;
    }
    
    function get_search_cond_department()
    {
        $cond = '';
        if(Url::get('department_code'))
            $cond.=' AND product_price_list.department_code = \''.Url::get('department_code').'\' ';
        return $cond;
    }
}
?>
