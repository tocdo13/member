<?php
class ListProductMaterialForm extends Form
{
	function ListProductMaterialForm()
	{
		Form::Form('ListProductMaterialForm');	
	}
	function draw()
	{
        $this->map = array();
        $cond = ' 1=1 ';
        if(Url::get('search'))
        {
            if(Url::get('product_id'))
                $cond .= ' AND UPPER(product.id) LIKE UPPER(\'%'.Url::sget('product_id').'%\') ';
            if(Url::get('product_name'))
            {
                require_once 'packages/core/includes/utils/vn_code.php';
                $cond .= ' AND FN_CONVERT_TO_VN(UPPER(product.name_'.Portal::language().')) LIKE UPPER(\'%'.trim(convert_utf8_to_latin(Url::sget('product_name'))).'%\') ';
            }
            
            if(Url::get('category_id') && Url::get('category_id') !=1)
            {
                //if(User::is_admin())
                //System::debug(Url::get('category_id'));
                //$cond .= ' AND product.category_id = \''.Url::get('category_id').'\' ';
                $cat = DB::fetch('select * from product_category where id = '.Url::get('category_id'));
                //System::debug($cat);
                //echo IDStructure::child_cond($cat['structure_id'],true);
                $cond .= ' AND '.IDStructure::child_cond($cat['structure_id'],false);
            }
                
            if(Url::get('type'))
                $cond .= ' AND product.type = \''.Url::get('type').'\' ';
        }
        
        $cond .= ' and ( type=\'PRODUCT\' or type=\'DRINK\')';
		//$cond .= ' and  type=\'PRODUCT\'';
		$item_per_page = 100;		
		
        //Khong co dinh muc
        if(Url::get('no_material'))
        {
            DB::query('
    			select 
                    count(*) as acount
    			from 
                	product 
                    inner join unit on product.unit_id = unit.id
                    inner join product_category on product.category_id = product_category.id
                    left join product_material on product_material.product_id = product.id 
    			where 
                    '.$cond.' and product_material.product_id IS NULL
    		');
            
            $count = DB::fetch();
    		require_once 'packages/core/includes/utils/paging.php';
    		$paging = paging($count['acount'],$item_per_page,10,false,'page_no',array('product_id','product_name','category_id','type','no_material'));					
    
    		$products = DB::fetch_all('
                                		select * from (
                                			select
                                				product.id,
                                                product.name_'.Portal::language().' as name,
                                                product.id as product_id,
                                                product.type,
                                                unit.name_'.Portal::language().' as unit,
                                                product_category.name as category,
                                                ROWNUM as rownumber
                                			from
                        				        product 
                                                inner join unit on product.unit_id = unit.id
                                                inner join product_category on product.category_id = product_category.id
                                                left join product_material on product_material.product_id = product.id
                                			where
                                				'.$cond.' and product_material.product_id IS NULL
                                			order by
                                				product.name_'.Portal::language().'
                                		)
                                		where
                                			rownumber > '.((page_no()-1)*$item_per_page).' and rownumber <= '.(page_no()*$item_per_page).'
                                		');
            $stt = 1;
            
    		foreach($products as $key=>$value)
    		{
                $products[$key]['stt'] = $stt++;
                $products[$key]['product_material'] = '';
    		}
            
        }
        else //Tat ca
        {
            DB::query('
    			select 
                    count(*) as acount
    			from 
                	product 
                    inner join unit on product.unit_id = unit.id
                    inner join product_category on product.category_id = product_category.id
    			where 
                    '.$cond.'
    		');
    		
    		$count = DB::fetch();
    		require_once 'packages/core/includes/utils/paging.php';
    		$paging = paging($count['acount'],$item_per_page,10,false,'page_no',array('product_id','product_name','category_id','type'));					
    
    		$products = DB::fetch_all('
                                		select * from (
                                			select
                                				product.id,
                                                product.name_'.Portal::language().' as name,
                                                product.id as product_id,
                                                product.type,
                                                unit.name_'.Portal::language().' as unit,
                                                product_category.name as category,
                                                ROWNUM as rownumber
                                			from
                        				        product 
                                                inner join unit on product.unit_id = unit.id
                                                inner join product_category on product.category_id = product_category.id
                                			where
                                				'.$cond.'
                                			order by
                                				product.name_'.Portal::language().'
                                		)
                                		where
                                			rownumber > '.((page_no()-1)*$item_per_page).' and rownumber <= '.(page_no()*$item_per_page).'
                                		');
            $stt = 1;
    		foreach($products as $key=>$value)
    		{
                $products[$key]['stt'] = $stt++;
    			//$products[$key]['price'] = System::display_number_report($value['price']);
    			$product_material = DB::fetch_all('
                                				select
                                					product_material.id,
                                                    product_material.product_id,
                                                    product_material.material_id,
                                                    product_material.quantity,
                                					product.name_'.Portal::language().' as name,
                                					unit.name_'.Portal::language().' as unit
                                				from
                                					product_material
                                					inner join product on product.id = product_material.material_id
                                					inner join unit on product.unit_id = unit.id
                                                    inner join product_category on product.category_id = product_category.id
                                				where
                                					product_material.product_id=\''.$key.'\'
                                                    and portal_id = \''.PORTAL_ID.'\'
                                			');
    			$product_material_str = '';
                /** START: Daund gop lai & tinh tong nvl khi hien thi */
                //System::debug($product_material);
                $product_material_arr = array();
                foreach($product_material as $k => $val)
                {
                    $id = 'PRODUCT_MATERIAL_' . $val['product_id'] . '_' . $val['material_id'];
                    if(!isset($product_material_arr[$id]))
                    {
                        $product_material_arr[$id]['id'] = $val['id'];
                        $product_material_arr[$id]['product_id'] = $val['product_id'];
                        $product_material_arr[$id]['material_id'] = $val['material_id'];
                        $product_material_arr[$id]['name'] = $val['name'];
                        $product_material_arr[$id]['unit'] = $val['unit'];
                        if($val['quantity']<1)
                        {
                            $product_material_arr[$id]['quantity'] = '0'.$val['quantity'];
                        }
                        else
                        {
                            $product_material_arr[$id]['quantity'] = $val['quantity'];
                        }                                                 
                    }else
                    {
                        $product_material_arr[$id]['quantity'] += $val['quantity'];
                    }
                }
                //System::debug($product_material_arr);
                /** END: Daund tinh gom lai & tong nvl khi hien thi */
    			foreach($product_material_arr as $id=>$product)
    			{
    			    //$qua=number_format($product['quantity'],3,'.',',');
                    $material_id = $product['id'];
                    /** daund cmt vì đã gom và tính tổng nvl bên trên if($product['quantity']<1)
    				    $product_material_str.=','.$product['name'].'(0'.$product['quantity'].' '.$product['unit'].')';
                    else*/
                    $product_material_str.=','.$product['name'].'('.$product['quantity'].' '.$product['unit'].')';  
    			    //System::debug(number_format($product['quantity'],3,'.',',')) ;
                }
    			$products[$key]['product_material'] = substr($product_material_str,1);
    		}
        }
		//System::debug($products);
        $this->map['category_id_list'] = String::get_list(DB::fetch_all('Select * from product_category order by structure_id'));
		$this->parse_layout('list',array('items'=>$products,'paging'=>$paging)+$this->map);
	    if(Url::get('export') == 'excel')
        {
            $product = DB::fetch('select
                                            product.name_1 as name,
                                            unit.name_'.Portal::language().' as unit,
                                            product.id
                                      from
                                            product inner join unit on product.unit_id = unit.id
                                      where       
                                            product.id=\''.Url::get('id').'\'');
            //System::debug($product); exit();
            $product_material = DB::fetch_all('
                                				select
                                					product_material.id,
                                                    product_material.product_id,
                                                    product_material.material_id,
                                                    product_material.quantity,
                                                    rownum,
                                					product.name_'.Portal::language().' as name,
                                					unit.name_'.Portal::language().' as unit
                                				from
                                					product_material
                                					inner join product on product.id = product_material.material_id
                                					inner join unit on product.unit_id = unit.id
                                                    inner join product_category on product.category_id = product_category.id
                                				where
                                					product_material.product_id=\''.Url::get('id').'\'
                                                    and portal_id = \''.PORTAL_ID.'\'
                                			');
            //System::debug($product_material);exit();
			$this->export_excel($product_material, $product);
            
		}
        /** START: Daund Xuất excel trong ĐỊNH MỨC NGUYÊN VẬT LIỆU CHO SẢN PHẨM */
        if(Url::get('cmd')=='export_excel')
        {
            $products_all = DB::fetch_all('
                                		select * from (
                                			select
                                				product.id,
                                                product.name_'.Portal::language().' as name,
                                                product.id as product_id,
                                                product.type,
                                                unit.name_'.Portal::language().' as unit,
                                                product_category.name as category,
                                                ROWNUM as rownumber
                                			from
                        				        product 
                                                inner join unit on product.unit_id = unit.id
                                                inner join product_category on product.category_id = product_category.id
                                			where
                                				'.$cond.'
                                			order by
                                				product.name_'.Portal::language().'
                                		)');
    		foreach($products_all as $key=>$value)
    		{
    			$product_material = DB::fetch_all('
                                				select
                                					product_material.id,
                                                    product_material.product_id,
                                                    product_material.material_id,
                                                    product_material.quantity,
                                					product.name_'.Portal::language().' as name,
                                					unit.name_'.Portal::language().' as unit
                                				from
                                					product_material
                                					inner join product on product.id = product_material.material_id
                                					inner join unit on product.unit_id = unit.id
                                                    inner join product_category on product.category_id = product_category.id
                                				where
                                					product_material.product_id=\''.$key.'\'
                                                    and portal_id = \''.PORTAL_ID.'\'
                                			');
    			foreach($product_material as $id=>$product)
    			{
                    $material_id = $product['id'];
                    if(!isset($products_all[$key]['child'][$material_id]))
                    {
                        $products_all[$key]['child'][$material_id]['product_id'] = $product['product_id'];
                        $products_all[$key]['child'][$material_id]['material_id'] = $product['material_id'];
                        $products_all[$key]['child'][$material_id]['material_name'] = $product['name'];
                        $products_all[$key]['child'][$material_id]['material_quantity'] = $product['quantity'];
                    }
                }
                if(!isset($products_all[$key]['child']))
                {
                    $products_all[$key]['child']['id']['product_id'] = '';
                    $products_all[$key]['child']['id']['material_id'] = '';
                    $products_all[$key]['child']['id']['material_name'] = '';
                    $products_all[$key]['child']['id']['material_quantity'] = '';
                }
    		}
            //System::debug($products_all);
            $this->export_file_excel($products_all);
		    
        }
        /** END: Daund Xuất excel trong ĐỊNH MỨC NGUYÊN VẬT LIỆU CHO SẢN PHẨM */   
    }
    function export_excel($reports, $product)
    {
		require_once 'packages/core/includes/utils/PHPExcel.php';
        require_once 'packages/core/includes/utils/PHPExcel/IOFactory.php';
       //require_once 'packages/core/includes/utils/PHPExcel/Writer/Excel5.php';
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        $FontColor = new PHPExcel_Style_Color();
        $FontColor->setRGB("0000f7");
        $sheet = $objPHPExcel->getActiveSheet();
        
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth('5');
        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth('15');
        $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth('5');
        $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth('5');
        
        $objPHPExcel->getActiveSheet()->setCellValue('A1' , '');
        $objPHPExcel->getActiveSheet()->mergeCells('A1:D10');
        $objPHPExcel->getActiveSheet()->getStyle('A1:D10')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A1:D10')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('A1:D10')->getFont()->setSize(12);
        $objPHPExcel->getActiveSheet()->getStyle('A1:D10')->getAlignment()->setHorizontal('center');
        $objPHPExcel->getActiveSheet()->getStyle('A1:D10')->getAlignment()->setVertical('center');
        
        $objDrawing = new PHPExcel_Worksheet_Drawing();
        $objDrawing->setName('PHPExcel logo');
        $objDrawing->setDescription('forever_green_resort');
        $objDrawing->setPath('resources/interfaces/images/default/logo.jpg');
        $objDrawing->setWidthAndHeight('220','200');
        $objDrawing->setCoordinates('A1');
        $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
        
        $objPHPExcel->getActiveSheet()->setCellValue('E1', 'XÂY DỰNG ĐỊNH MỨC TIÊU HAO NGUYÊN VẬT LIỆU');
        $objPHPExcel->getActiveSheet()->mergeCells('E1:L2');
        //$objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow(1,10,4,10);
        $objPHPExcel->getActiveSheet()->getStyle('E1:L2')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('E1:L2')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('E1:L2')->getFont()->setSize(14);
        $objPHPExcel->getActiveSheet()->getStyle('E1:L2')->getAlignment()->setHorizontal('center');
        $objPHPExcel->getActiveSheet()->getStyle('E1:L2')->getAlignment()->setVertical('center');       
		
		$objPHPExcel->getActiveSheet()->setCellValue('E3' , 'Số hiệu: LH-DMNVL-001');
        $objPHPExcel->getActiveSheet()->mergeCells('E3:H3');
        $objPHPExcel->getActiveSheet()->getStyle('E3:H3')->getFont()->setBold(false);
        $objPHPExcel->getActiveSheet()->getStyle('E3:H3')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('E3:H3')->getFont()->setSize(12);
        $objPHPExcel->getActiveSheet()->getStyle('E3:H3')->getAlignment()->setHorizontal('left');
        $objPHPExcel->getActiveSheet()->getStyle('E3:H3')->getAlignment()->setVertical('center');
        
        $objPHPExcel->getActiveSheet()->setCellValue('I3' , 'Ngày ban hành: ');
        $objPHPExcel->getActiveSheet()->mergeCells('I3:L3');
        $objPHPExcel->getActiveSheet()->getStyle('I3:L3')->getFont()->setBold(false);
        $objPHPExcel->getActiveSheet()->getStyle('I3:L3')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('I3:L3')->getFont()->setSize(12);
        $objPHPExcel->getActiveSheet()->getStyle('I3:L3')->getAlignment()->setHorizontal('left');
        $objPHPExcel->getActiveSheet()->getStyle('I3:L3')->getAlignment()->setVertical('center');
        
        $objPHPExcel->getActiveSheet()->setCellValue('E4' , 'Lần hiệu chỉnh: ');
        $objPHPExcel->getActiveSheet()->mergeCells('E4:F6');
        $objPHPExcel->getActiveSheet()->getStyle('E4:F6')->getFont()->setBold(false);
        $objPHPExcel->getActiveSheet()->getStyle('E4:F6')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('E4:F6')->getFont()->setSize(12);
        $objPHPExcel->getActiveSheet()->getStyle('E4:F6')->getAlignment()->setHorizontal('left');
        $objPHPExcel->getActiveSheet()->getStyle('E4:F6')->getAlignment()->setVertical('top');
        
        $objPHPExcel->getActiveSheet()->setCellValue('G4' , 'Bộ phận lưu trữ:');
        $objPHPExcel->getActiveSheet()->mergeCells('G4:J6');
        $objPHPExcel->getActiveSheet()->getStyle('G4:J6')->getFont()->setBold(false);
        $objPHPExcel->getActiveSheet()->getStyle('G4:J6')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('G4:J6')->getFont()->setSize(12);
        $objPHPExcel->getActiveSheet()->getStyle('G4:J6')->getAlignment()->setHorizontal('left');
        $objPHPExcel->getActiveSheet()->getStyle('G4:J6')->getAlignment()->setVertical('top');
        
        $objPHPExcel->getActiveSheet()->setCellValue('K4' , 'Trang:');
        $objPHPExcel->getActiveSheet()->mergeCells('K4:L6');
        $objPHPExcel->getActiveSheet()->getStyle('K4:L6')->getFont()->setBold(false);
        $objPHPExcel->getActiveSheet()->getStyle('K4:L6')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('K4:L6')->getFont()->setSize(12);
        $objPHPExcel->getActiveSheet()->getStyle('K4:L6')->getAlignment()->setHorizontal('left');
        $objPHPExcel->getActiveSheet()->getStyle('K4:L6')->getAlignment()->setVertical('top');
        
        $objPHPExcel->getActiveSheet()->setCellValue('E7' , 'Người biên soạn');
        $objPHPExcel->getActiveSheet()->mergeCells('E7:F10');
        $objPHPExcel->getActiveSheet()->getStyle('E7:F10')->getFont()->setBold(false);
        $objPHPExcel->getActiveSheet()->getStyle('E7:F10')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('E7:F10')->getFont()->setSize(12);
        $objPHPExcel->getActiveSheet()->getStyle('E7:F10')->getAlignment()->setHorizontal('center');
        $objPHPExcel->getActiveSheet()->getStyle('E7:F10')->getAlignment()->setVertical('top');
        
        $objPHPExcel->getActiveSheet()->setCellValue('G7' , 'Người kiểm tra');
        $objPHPExcel->getActiveSheet()->mergeCells('G7:I10');
        $objPHPExcel->getActiveSheet()->getStyle('G7:I10')->getFont()->setBold(false);
        $objPHPExcel->getActiveSheet()->getStyle('G7:I10')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('G7:I10')->getFont()->setSize(12);
        $objPHPExcel->getActiveSheet()->getStyle('G7:I10')->getAlignment()->setHorizontal('center');
        $objPHPExcel->getActiveSheet()->getStyle('G7:I10')->getAlignment()->setVertical('top');
        
        $objPHPExcel->getActiveSheet()->setCellValue('J7' , 'Người phê duyệt');
        $objPHPExcel->getActiveSheet()->mergeCells('J7:L10');
        $objPHPExcel->getActiveSheet()->getStyle('J7:L10')->getFont()->setBold(false);
        $objPHPExcel->getActiveSheet()->getStyle('J7:L10')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('J7:L10')->getFont()->setSize(12);
        $objPHPExcel->getActiveSheet()->getStyle('J7:L10')->getAlignment()->setHorizontal('center');
        $objPHPExcel->getActiveSheet()->getStyle('J7:L10')->getAlignment()->setVertical('top');
        
        $objPHPExcel->getActiveSheet()->setCellValue('B12' , '- Sản phẩm xây dựng định mức: ');
        $objPHPExcel->getActiveSheet()->mergeCells('B12:L12');
        $objPHPExcel->getActiveSheet()->getStyle('B12:L12')->getFont()->setBold(false);
        $objPHPExcel->getActiveSheet()->getStyle('B12:L12')->getFont()->setSize(12);
        $objPHPExcel->getActiveSheet()->getStyle('B12:L12')->getAlignment()->setHorizontal('left');
        $objPHPExcel->getActiveSheet()->getStyle('B12:L12')->getAlignment()->setVertical('center');
        
        $objPHPExcel->getActiveSheet()->setCellValue('C13' , 'Mã sản phẩm: '.$product['id']);
        $objPHPExcel->getActiveSheet()->mergeCells('C13:L13');
        $objPHPExcel->getActiveSheet()->getStyle('C13:L13')->getFont()->setBold(false);
        $objPHPExcel->getActiveSheet()->getStyle('C13:L13')->getFont()->setSize(12);
        $objPHPExcel->getActiveSheet()->getStyle('C13:L13')->getAlignment()->setHorizontal('left');
        $objPHPExcel->getActiveSheet()->getStyle('C13:L13')->getAlignment()->setVertical('center');
        
        $objPHPExcel->getActiveSheet()->setCellValue('C14' , 'Tên sản phẩm: '.$product['name']);
        $objPHPExcel->getActiveSheet()->mergeCells('C14:G14');
        $objPHPExcel->getActiveSheet()->getStyle('C14:G14')->getFont()->setBold(false);
        $objPHPExcel->getActiveSheet()->getStyle('C14:G14')->getFont()->setSize(12);
        $objPHPExcel->getActiveSheet()->getStyle('C14:G14')->getAlignment()->setHorizontal('left');
        $objPHPExcel->getActiveSheet()->getStyle('C14:G14')->getAlignment()->setVertical('center');
        
        $objPHPExcel->getActiveSheet()->setCellValue('H14' , 'Đơn vị tính: '.$product['unit']);
        $objPHPExcel->getActiveSheet()->mergeCells('H14:L14');
        $objPHPExcel->getActiveSheet()->getStyle('H14:L14')->getFont()->setBold(false);
        $objPHPExcel->getActiveSheet()->getStyle('H14:L14')->getFont()->setSize(12);
        $objPHPExcel->getActiveSheet()->getStyle('H14:L14')->getAlignment()->setHorizontal('left');
        $objPHPExcel->getActiveSheet()->getStyle('H14:L14')->getAlignment()->setVertical('center');
        
        $objPHPExcel->getActiveSheet()->setCellValue('B16' , '- 1 đơn vị sản phẩm trên tiêu hao các nguyên vật liệu theo định mức sau:');
        $objPHPExcel->getActiveSheet()->mergeCells('B16:L16');
        $objPHPExcel->getActiveSheet()->getStyle('B16:L16')->getFont()->setBold(false);
        $objPHPExcel->getActiveSheet()->getStyle('B16:L16')->getFont()->setSize(12);
        $objPHPExcel->getActiveSheet()->getStyle('B16:L16')->getAlignment()->setHorizontal('left');
        $objPHPExcel->getActiveSheet()->getStyle('B16:L16')->getAlignment()->setVertical('center');
        
        $objPHPExcel->getActiveSheet()->setCellValue('B18' , 'STT');
        $objPHPExcel->getActiveSheet()->mergeCells('B18:B20');
        $objPHPExcel->getActiveSheet()->getStyle('B18:B20')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('B18:B20')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('B18:B20')->getFont()->setSize(12);
        $objPHPExcel->getActiveSheet()->getStyle('B18:B20')->getAlignment()->setHorizontal('center');
        $objPHPExcel->getActiveSheet()->getStyle('B18:B20')->getAlignment()->setVertical('center');
        
        $objPHPExcel->getActiveSheet()->setCellValue('C18' , 'Mã NVL, hàng hóa');
        $objPHPExcel->getActiveSheet()->mergeCells('C18:D20');
        $objPHPExcel->getActiveSheet()->getStyle('C18:D20')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('C18:D20')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('C18:D20')->getFont()->setSize(12);
        $objPHPExcel->getActiveSheet()->getStyle('C18:D20')->getAlignment()->setHorizontal('center');
        $objPHPExcel->getActiveSheet()->getStyle('C18:D20')->getAlignment()->setVertical('center');
        
        $objPHPExcel->getActiveSheet()->setCellValue('E18' , 'Tên NVL, hàng hóa');
        $objPHPExcel->getActiveSheet()->mergeCells('E18:H20');
        $objPHPExcel->getActiveSheet()->getStyle('E18:H20')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('E18:H20')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('E18:H20')->getFont()->setSize(12);
        $objPHPExcel->getActiveSheet()->getStyle('E18:H20')->getAlignment()->setHorizontal('center');
        $objPHPExcel->getActiveSheet()->getStyle('E18:H20')->getAlignment()->setVertical('center');
        
        $objPHPExcel->getActiveSheet()->setCellValue('I18' , 'Đvt');
        $objPHPExcel->getActiveSheet()->mergeCells('I18:I20');
        $objPHPExcel->getActiveSheet()->getStyle('I18:I20')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('I18:I20')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('I18:I20')->getFont()->setSize(12);
        $objPHPExcel->getActiveSheet()->getStyle('I18:I20')->getAlignment()->setHorizontal('center');
        $objPHPExcel->getActiveSheet()->getStyle('I18:I20')->getAlignment()->setVertical('center');
        
        $objPHPExcel->getActiveSheet()->setCellValue('J18' , 'Định mức');
        $objPHPExcel->getActiveSheet()->mergeCells('J18:J20');
        $objPHPExcel->getActiveSheet()->getStyle('J18:J20')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('J18:J20')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('J18:J20')->getFont()->setSize(12);
        $objPHPExcel->getActiveSheet()->getStyle('J18:J20')->getAlignment()->setHorizontal('center');
        $objPHPExcel->getActiveSheet()->getStyle('J18:J20')->getAlignment()->setVertical('center');
        
        $objPHPExcel->getActiveSheet()->setCellValue('K18' , 'Ghi chú');
        $objPHPExcel->getActiveSheet()->mergeCells('K18:K20');
        $objPHPExcel->getActiveSheet()->getStyle('K18:K20')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('K18:K20')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('K18:K20')->getFont()->setSize(12);
        $objPHPExcel->getActiveSheet()->getStyle('K18:K20')->getAlignment()->setHorizontal('center');
        $objPHPExcel->getActiveSheet()->getStyle('K18:K20')->getAlignment()->setVertical('center');
        
        $j = 21;
        foreach($reports as $key => $report)
        {
            $objPHPExcel->getActiveSheet()->setCellValue('B'.$j , $report['rownum']);
            $objPHPExcel->getActiveSheet()->getStyle('B'.$j)->getAlignment()->setWrapText(true);
            $objPHPExcel->getActiveSheet()->getStyle('B'.$j)->getAlignment()->setHorizontal('center');
            $objPHPExcel->getActiveSheet()->getStyle('B'.$j)->getAlignment()->setVertical('center'); 
            $objPHPExcel->getActiveSheet()->getStyle('B'.$j)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            
            $objPHPExcel->getActiveSheet()->setCellValue('C'.$j , $report['product_id']);
            $objPHPExcel->getActiveSheet()->mergeCells('C'.$j.':D'.($j));
            $objPHPExcel->getActiveSheet()->getStyle('C'.$j)->getAlignment()->setWrapText(true);
            $objPHPExcel->getActiveSheet()->getStyle('C'.$j)->getAlignment()->setHorizontal('center');
            $objPHPExcel->getActiveSheet()->getStyle('C'.$j)->getAlignment()->setVertical('center'); 
            $objPHPExcel->getActiveSheet()->getStyle('C'.$j.':D'.($j))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            
            $objPHPExcel->getActiveSheet()->setCellValue('E'.$j , $report['name']);
            $objPHPExcel->getActiveSheet()->mergeCells('E'.$j.':H'.($j));
            $objPHPExcel->getActiveSheet()->getStyle('E'.$j)->getAlignment()->setWrapText(true);
            $objPHPExcel->getActiveSheet()->getStyle('E'.$j)->getAlignment()->setHorizontal('center');
            $objPHPExcel->getActiveSheet()->getStyle('E'.$j)->getAlignment()->setVertical('center'); 
            $objPHPExcel->getActiveSheet()->getStyle('E'.$j.':H'.($j))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            
            $objPHPExcel->getActiveSheet()->setCellValue('I'.$j , $report['unit']);
            $objPHPExcel->getActiveSheet()->getStyle('I'.$j)->getAlignment()->setWrapText(true);
            $objPHPExcel->getActiveSheet()->getStyle('I'.$j)->getAlignment()->setHorizontal('center');
            $objPHPExcel->getActiveSheet()->getStyle('I'.$j)->getAlignment()->setVertical('center'); 
            $objPHPExcel->getActiveSheet()->getStyle('I'.$j)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            
            $objPHPExcel->getActiveSheet()->setCellValue('J'.$j , $report['quantity']);
            $objPHPExcel->getActiveSheet()->getStyle('J'.$j)->getAlignment()->setWrapText(true);
            $objPHPExcel->getActiveSheet()->getStyle('J'.$j)->getAlignment()->setHorizontal('center');
            $objPHPExcel->getActiveSheet()->getStyle('J'.$j)->getAlignment()->setVertical('center'); 
            $objPHPExcel->getActiveSheet()->getStyle('J'.$j)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            
            $objPHPExcel->getActiveSheet()->setCellValue('K'.$j , '');
            $objPHPExcel->getActiveSheet()->getStyle('K'.$j)->getAlignment()->setWrapText(true);
            $objPHPExcel->getActiveSheet()->getStyle('K'.$j)->getAlignment()->setHorizontal('center');
            $objPHPExcel->getActiveSheet()->getStyle('K'.$j)->getAlignment()->setVertical('center'); 
            $objPHPExcel->getActiveSheet()->getStyle('K'.$j.':K'.($j))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $j += 1;     
        }
        $objPHPExcel->getActiveSheet()->getStyle('A'.(1).':L'.($j+1))->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $fileName = "export/Dinh_muc_tieu_hao_nguyen_vat_lieu_cho_san_pham_".$product['id']."_".$product['name'].".xls";
        require_once 'packages/core/includes/utils/vn_code.php';
        $fileName = convert_utf8_to_latin($fileName);
        //System::debug($objPHPExcel); exit();
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save($fileName);
        
		if(file_exists($fileName))
        {
			echo '<script>';
			echo 'window.location.href = \''.$fileName.'\';';
			echo '</script>';
		}else{
			echo '<script>';
			echo 'alert(" Export dữ liệu không thành công !");';
			echo '</script>';
		}
	}
    /** START: Daund Xuất excel trong ĐỊNH MỨC NGUYÊN VẬT LIỆU CHO SẢN PHẨM */
    function export_file_excel($products)
    {
        require_once ROOT_PATH.'packages/core/includes/utils/PHPExcel.php';		
  		include ROOT_PATH.'packages/core/includes/utils/PHPExcel/IOFactory.php';
        require_once ROOT_PATH.'packages/core/includes/utils/PHPExcel/RichText.php';
        $objPHPExcel = new PHPExcel();
        $row = 1;
        $objPHPExcel->getActiveSheet()->setCellValue('A'.$row, 'MÃ');
        $objPHPExcel->getActiveSheet()->setCellValue('B'.$row, 'TÊN MÓN ĂN');
        $objPHPExcel->getActiveSheet()->setCellValue('C'.$row, 'MÃ NVL');
        $objPHPExcel->getActiveSheet()->setCellValue('D'.$row, 'TÊN NVL');
        $objPHPExcel->getActiveSheet()->setCellValue('E'.$row, 'SL');
        $objPHPExcel->getActiveSheet()->setCellValue('F'.$row, '');
        $objPHPExcel->getActiveSheet()->setCellValue('G'.$row, '');
        $objPHPExcel->getActiveSheet()->setCellValue('H'.$row, '');
        $objPHPExcel->getActiveSheet()->setCellValue('I'.$row, '');
        $objPHPExcel->getActiveSheet()->setCellValue('J'.$row, '');
        $objPHPExcel->getActiveSheet()->setCellValue('K'.$row, '');
        $objPHPExcel->getActiveSheet()->setCellValue('L'.$row, '');
        $objPHPExcel->getActiveSheet()->setCellValue('M'.$row, '');
        $objPHPExcel->getActiveSheet()->setCellValue('N'.$row, '');
        $objPHPExcel->getActiveSheet()->setCellValue('O'.$row, '');
        $objPHPExcel->getActiveSheet()->setCellValue('P'.$row, '');
        $objPHPExcel->getActiveSheet()->setCellValue('Q'.$row, '');
        $objPHPExcel->getActiveSheet()->setCellValue('R'.$row, '');
        $objPHPExcel->getActiveSheet()->setCellValue('S'.$row, '');
        $objPHPExcel->getActiveSheet()->setCellValue('T'.$row, '');
        $objPHPExcel->getActiveSheet()->setCellValue('U'.$row, '');
        $objPHPExcel->getActiveSheet()->setCellValue('V'.$row, '');
        $objPHPExcel->getActiveSheet()->setCellValue('W'.$row, '');
        $objPHPExcel->getActiveSheet()->setCellValue('X'.$row, '');
        $objPHPExcel->getActiveSheet()->setCellValue('Y'.$row, '');
        $objPHPExcel->getActiveSheet()->setCellValue('Z'.$row, '');
        $objPHPExcel->getActiveSheet()->setCellValue('AA'.$row,'');
        $objPHPExcel->getActiveSheet()->setCellValue('AB'.$row,'');
        $objPHPExcel->getActiveSheet()->setCellValue('AC'.$row,'');
        $objPHPExcel->getActiveSheet()->setCellValue('AD'.$row,'');
        $objPHPExcel->getActiveSheet()->setCellValue('AE'.$row,'');
        $objPHPExcel->getActiveSheet()->setCellValue('AF'.$row,'');
        $objPHPExcel->getActiveSheet()->setCellValue('AG'.$row,'');
        $objPHPExcel->getActiveSheet()->setCellValue('AH'.$row,'');
        $objPHPExcel->getActiveSheet()->setCellValue('AI'.$row,'');
        $row =2;
        $row_i =2;
        foreach($products as $key => $val)
        {
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$row_i, $val['product_id']);
            $objPHPExcel->getActiveSheet()->setCellValue('B'.$row_i, $val['name']);
            foreach($val['child'] as $k => $v)
            {
                $objPHPExcel->getActiveSheet()->setCellValue('C'.$row, $v['material_id']);
                $objPHPExcel->getActiveSheet()->setCellValue('D'.$row, $v['material_name']);
                $objPHPExcel->getActiveSheet()->setCellValue('E'.$row, $v['material_quantity']);
                $objPHPExcel->getActiveSheet()->setCellValue('F'.$row, '');
                $objPHPExcel->getActiveSheet()->setCellValue('G'.$row, '');
                $objPHPExcel->getActiveSheet()->setCellValue('H'.$row, '');
                $objPHPExcel->getActiveSheet()->setCellValue('I'.$row, '');
                $objPHPExcel->getActiveSheet()->setCellValue('J'.$row, '');
                $objPHPExcel->getActiveSheet()->setCellValue('K'.$row, '');
                $objPHPExcel->getActiveSheet()->setCellValue('L'.$row, '');
                $objPHPExcel->getActiveSheet()->setCellValue('M'.$row, '');
                $objPHPExcel->getActiveSheet()->setCellValue('N'.$row, '');
                $objPHPExcel->getActiveSheet()->setCellValue('O'.$row, '');
                $objPHPExcel->getActiveSheet()->setCellValue('P'.$row, '');
                $objPHPExcel->getActiveSheet()->setCellValue('Q'.$row, '');
                $objPHPExcel->getActiveSheet()->setCellValue('R'.$row, '');
                $objPHPExcel->getActiveSheet()->setCellValue('S'.$row, '');
                $objPHPExcel->getActiveSheet()->setCellValue('T'.$row, '');
                $objPHPExcel->getActiveSheet()->setCellValue('U'.$row, '');
                $objPHPExcel->getActiveSheet()->setCellValue('V'.$row, '');
                $objPHPExcel->getActiveSheet()->setCellValue('W'.$row, '');
                $objPHPExcel->getActiveSheet()->setCellValue('X'.$row, '');
                $objPHPExcel->getActiveSheet()->setCellValue('Y'.$row, '');
                $objPHPExcel->getActiveSheet()->setCellValue('Z'.$row, '');
                $objPHPExcel->getActiveSheet()->setCellValue('AA'.$row,'');
                $objPHPExcel->getActiveSheet()->setCellValue('AB'.$row,'');
                $objPHPExcel->getActiveSheet()->setCellValue('AC'.$row,'');
                $objPHPExcel->getActiveSheet()->setCellValue('AD'.$row,'');
                $objPHPExcel->getActiveSheet()->setCellValue('AE'.$row,'');
                $objPHPExcel->getActiveSheet()->setCellValue('AF'.$row,'');
                $objPHPExcel->getActiveSheet()->setCellValue('AG'.$row,'');
                $objPHPExcel->getActiveSheet()->setCellValue('AH'.$row,'');
                $objPHPExcel->getActiveSheet()->setCellValue('AI'.$row,'');
                $row++;
                $row_i++;
            }
        }
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');             
        $objWriter->save('dmnvl-export-to-excel.xls');
        echo "<script>";
        echo 'window.location.href = \'dmnvl-export-to-excel.xls\';';;
        echo "</script>"; 
    } 
    /** END: Daund Xuất excel trong ĐỊNH MỨC NGUYÊN VẬT LIỆU CHO SẢN PHẨM */
}
?>
