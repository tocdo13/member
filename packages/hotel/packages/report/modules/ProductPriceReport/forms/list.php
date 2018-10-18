<?php
class ListProductPriceForm extends Form
{
	function ListProductPriceForm()
	{
		Form::Form('ListProductPriceForm');
        $this->link_js('packages/core/includes/js/jquery/datepicker.js');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
	}
	function draw()
    {
        $this->map = array();
        $this->map['line_per_page'] = Url::iget('line_per_page')?Url::iget('line_per_page'):99999;
        $this->map['no_of_page'] = Url::iget('no_of_page')?Url::iget('no_of_page'):99999;
        $this->map['start_page'] = Url::iget('start_page')?Url::iget('start_page'):1;
        $cond = ' 1 = 1';
        $portal_id = Url::get('portal_id')?Url::get('portal_id'):PORTAL_ID;
        
		$department = DB::fetch_all(' select 
                                            code as id, department.name_'.Portal::language().' as name, id as department_id
                                        from 
                                            department 
                                        where  department.code = \'RES\' order by department.id '
                                    );
        foreach($department as $key=>$value)
        {
            $department_child =  DB::fetch_all(' select 
                                                        code as id, department.name_'.Portal::language().' as name, id as department_id
                                                    from 
                                                        department 
                                                    where  department.parent_id = '.$value['department_id'].' order by department.id '
                                                );
            $department+= $department_child;
        }
        
        $this->map['department_code_list'] = array(''=>Portal::language('select_department'))+String::get_list($department);
        $cond .= ' AND product_price_list.portal_id = \''.$portal_id.'\' ';
        
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
        if(Url::get('department_code'))
            $cond .= ' AND product_price_list.department_code = \''.Url::get('department_code').'\' ';
        else
        {
            $dept_code = array_keys($department);
            if(!empty($dept_code))
            {
                $dept_cond = '';
                foreach($dept_code as $code)
                {
                    $dept_cond.= $dept_cond? ' OR product_price_list.department_code = \''.$code.'\' ' : ' product_price_list.department_code = \''.$code.'\' ';
                }
                $cond .= ' AND ( '.$dept_cond.' ) '; 
            }

        }
                  
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
        					row_number() OVER (ORDER BY product_price_list.department_code ASC) AS rownumber
        				FROM
        					product_price_list
        					INNER JOIN product ON product.id = product_price_list.product_id
        					INNER JOIN unit on unit.id = product.unit_id
                            INNER JOIN department on product_price_list.department_code = department.code
        				WHERE
                            '.$cond.'
        				ORDER BY
        					product_price_list.department_code
        			)
        			WHERE
        			 	rownumber > '.(($this->map['start_page']-1)*$this->map['line_per_page']).' AND rownumber<='.($this->map['no_of_page']*$this->map['line_per_page']).'	
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
        $this->map['category_id_list'] = String::get_list(DB::fetch_all('Select * from product_category order by structure_id'));
		$this->map['items'] = $items;
        $this->map['portal_id_list'] = array(''=>Portal::language('select_portal'))+String::get_list(Portal::get_portal_list());
		$this->print_all_pages($items);
        //$this->parse_layout('list',$this->map+array('portal_id'=>$portal_id,'department_list'=>$department_list));
	}
    
    function print_all_pages($items)
	{
		$count = 0;
		$total_page = 1;
		$pages = array();
		foreach($items as $key=>$item)
		{
			if($count>=$this->map['line_per_page'])
			{
				$count = 0;
				$total_page++;
			}
			$pages[$total_page][$key] = $item;
			$count++;
		}
        
		if(sizeof($pages)>0)
		{
			foreach($pages as $page_no=>$page)
			{
				$this->print_page($page, $page_no,$total_page);
			}
		}
		else
		{
			$this->parse_layout('list',
				array(
					'page_no'=>0,
					'total_page'=>0,
                    'has_no_data'=>true
				)+$this->map
			);
		}
	}
    function print_page($items, $page_no, $total_page)
	{	
		$this->parse_layout('list',array(
				'items'=>$items,
				'page_no'=>$page_no,
				'total_page'=>$total_page,
			)+$this->map
		);
	}
    
    function get_search_cond_portal($portal_id)
    {
        $cond = ' AND product_price_list.portal_id = \''.$portal_id.'\' ';
        return $cond;
    }
}
?>