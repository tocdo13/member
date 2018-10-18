<?php
class ListProductForm extends Form
{
	function ListProductForm()
	{
		Form::Form('ListProductForm');
	}
	function on_submit()
	{
		if(Url::get('upload')){
			Url::redirect_current();
		}
	}
	function draw()
	{
		$languages = DB::select_all('language');
		$action='';
        
		if(Url::check('action'))
		{
			$action=Url::get('action');
		}
		if(is_array(URL::get('selected_ids')))
		{
			$_REQUEST['selected_ids'] = implode(',',URL::get('selected_ids'));
		}
        require_once 'packages/core/includes/utils/vn_code.php';
		$cond = URL::get('selected_ids')?(strpos(URL::get('selected_ids'),',')?' product.id in (\''.str_replace(',','\',\'',URL::get('selected_ids')).'")':' product.id=\''.URL::get('selected_ids').'\''):
				' 1>0 '
				.((URL::get('category_id')and URL::get('category_id')!=1)?'
					and '.IDStructure::child_cond(DB::structure_id('product_category', URL::get('category_id',1)),false,'product_category.').'
				':'')  
				.(URL::get('type')?' and lower(product.type)=\''.strtolower(URL::sget('type')).'\'':'')  
				.(URL::get('code')?' and lower(product.id) LIKE \'%'.strtolower(addslashes(URL::get('code'))).'%\'':'')
				.(URL::get('name')?' AND FN_CONVERT_TO_VN(lower(product.name_'.Portal::language().')) LIKE lower(\'%'.trim(convert_utf8_to_latin(Url::sget('name'))).'%\')':'')
		;
		$cond .= '';
		$item_per_page = 100;
		DB::query('
			select 
				count(*) as acount
			from 
				product 
                inner join unit on product.unit_id = unit.id
                LEFT OUTER JOIN product_category on product.category_id = product_category.id
			where 
				'.$cond.'
		');
		$count = DB::fetch();
		require_once 'packages/core/includes/utils/paging.php';
		$paging = paging($count['acount'],$item_per_page,5,false,'page_no',array( 'portal_id'=>Url::get('portal_id'),'type','category_id','code','name' ) );
		$sql = '
		select * from(
			select 
				product.id
				,product.status
				,product.type
				,product.name_'.Portal::language().' as name 
                ,product.name_1
                ,product.name_2
				,product_category.name as category_id 
				,unit.name_'.Portal::language().' as unit_id
				,row_number() over (order by product_category.structure_id,product.id) as rownumber
			from 
			 	product 
                left outer join unit on product.unit_id = unit.id
                left outer join product_category on product.category_id = product_category.id
			where 
				'.$cond.'
			)
			where
				rownumber > '.((page_no()-1)*$item_per_page).' and rownumber<='.((page_no())*$item_per_page).'
		';
		$items = DB::fetch_all($sql);
		DB::query('select
			id, product_category.name as name, structure_id
			from product_category
			order by structure_id
		');
		$category_id_list = String::get_list(DB::fetch_all());  
		$i=1;
		foreach ($items as $key=>$value)
		{
			$items[$key]['i']=$i++;
		}
		$just_edited_id['just_edited_ids'] = array();
		if (UrL::get('selected_ids'))
		{
			if(is_string(UrL::get('selected_ids')))
			{
				if (strstr(UrL::get('selected_ids'),','))
				{
					$just_edited_id['just_edited_ids']=explode(',',UrL::get('selected_ids'));
				}
				else
				{
					$just_edited_id['just_edited_ids']=array('0'=>UrL::get('selected_ids'));
				}
			}
		}
		$this->parse_layout('list',$just_edited_id+
			array(
				'items'=>$items,
				'paging'=>$paging,
				'category_id_list' => $category_id_list,
				'category_id' => URL::get('category_id',''),
				'action'=>$action,
				'total'=>$count['acount'],
				'portal_id_list'=>array(''=>Portal::language('all'))+String::get_list(Portal::get_portal_list())
			)
		);
        if(Url::get('export_excel'))
        {
            $this->export_file_excel();
        }
	}
    function export_file_excel()
            {
                require_once ROOT_PATH.'packages/core/includes/utils/PHPExcel.php';		
          		require_once ROOT_PATH.'packages/core/includes/utils/PHPExcel/RichText.php';
          		include ROOT_PATH.'packages/core/includes/utils/PHPExcel/IOFactory.php';
                
                $objPHPExcel = new PHPExcel();
        		$objPHPExcel->setActiveSheetIndex(0);
                require_once 'packages/core/includes/utils/vn_code.php';
        		$cond = isset($_REQUEST['selected_ids'])?(strpos($_REQUEST['selected_ids'],',')?' product.id in (\''.str_replace(',','\',\'',$_REQUEST['selected_ids']).'")':' product.id=\''.$_REQUEST['selected_ids'].'\''):
        				' 1>0 '
        				.(($_REQUEST['category_id']and $_REQUEST['category_id']!=1)?'
        					and '.IDStructure::child_cond(DB::structure_id('product_category', $_REQUEST['category_id'] ),false,'product_category.').'
        				':'')  
        				.($_REQUEST['type']?' and lower(product.type)=\''.strtolower($_REQUEST['type']).'\'':'')  
        				.($_REQUEST['code']?' and lower(product.id) LIKE \'%'.strtolower(addslashes($_REQUEST['code'])).'%\'':'')
        				.($_REQUEST['name']?' AND FN_CONVERT_TO_VN(lower(product.name_'.Portal::language().')) LIKE lower(\'%'.trim(convert_utf8_to_latin($_REQUEST['name'])).'%\')':'')
        		;
        		$cond .= '';
                $items = DB::fetch_all('select 
                    				product.id
                    				,product.status
                    				,product.type
                    				,product.name_'.Portal::language().' as name 
                                    ,product.name_1
                                    ,product.name_2
                    				,product_category.name as category_id 
                    				,unit.name_'.Portal::language().' as unit_id
                    			from 
                    			 	product 
                                    left outer join unit on product.unit_id = unit.id
                                    left outer join product_category on product.category_id = product_category.id
                                where 
				                    '.$cond.'
                                order by product_category.name
                    			');
                $i=1;
                foreach($items as $key=>$value)
        		{
        		   
                    $objPHPExcel->getActiveSheet()->setCellValue('A'.$i, $value['id']);
            		$objPHPExcel->getActiveSheet()->setCellValue('B'.$i, $value['name_1']);
            		$objPHPExcel->getActiveSheet()->setCellValue('C'.$i, $value['name_2']);
            		$objPHPExcel->getActiveSheet()->setCellValue('D'.$i, $value['unit_id']);
            		$objPHPExcel->getActiveSheet()->setCellValue('E'.$i, $value['type']);
            		$objPHPExcel->getActiveSheet()->setCellValue('F'.$i, $value['category_id']);
                    $i++;
                }
                //System::debug($export);exit();
                $fileName = "product.xls";
                //System::debug($objPHPExcel); exit();
                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
                $objWriter->save($fileName);
        		if(file_exists($fileName))
                {
        			echo '<script>';
                    echo 'window.location.href = \''.$fileName.'\';';
        			echo ' </script>';
        		}else{
        			echo '<script>';
        			echo 'alert(" Export dữ liệu không thành công !");';
        			echo '</script>';
        		}
                //System::debug($export);exit();
            }
}
?>