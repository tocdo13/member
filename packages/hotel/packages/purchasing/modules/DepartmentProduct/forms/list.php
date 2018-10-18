<?php 
class ListDepartmentProductForm extends Form
{
    function ListDepartmentProductForm()
    {
        Form::Form('ListDepartmentProductForm');
        $this->link_js('packages/core/includes/js/jquery/datepicker.js');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
    }
    function draw()
    {
        $cond = "1=1";
        $this->map = array();
        //System::debug($_REQUEST);
        if(isset($_REQUEST['department_id']) && $_REQUEST['department_id']!="")
        {
            $cond .=" AND portal_department.id=".$_REQUEST['department_id'];
        }
        if(isset($_REQUEST['product_code']) && $_REQUEST['product_code'] != '')
        {
            $cond .= ' AND pc_department_product.product_id LIKE \''.'%'.strtoupper($_REQUEST['product_code']).'%'.'\'';
        }
        if(isset($_REQUEST['product_name']) && $_REQUEST['product_name'] != '')
        {
            $cond .= 'AND product.name_1 LIKE \''.'%'.ucwords($_REQUEST['product_name']).'%'.'\'';
        }
        if(isset($_REQUEST['category_id']) && $_REQUEST['category_id'] != 1)
        {
            $cond .='and '.IDStructure::child_cond(DB::structure_id('product_category', URL::get('category_id',1)),false,'product_category.').'';
        }
        if(isset($_REQUEST['product_type']) && $_REQUEST['product_type'] != '')
        {
            $cond .=' and lower(product.type)=\''.strtolower($_REQUEST['product_type']).'\'';
        }
        $sql = "SELECT count(pc_department_product.id) as acount
                FROM pc_department_product
                    INNER JOIN product ON pc_department_product.product_id=product.id
                    INNER JOIN unit ON unit.id=product.unit_id 
                    INNER JOIN portal_department ON portal_department.id=pc_department_product.portal_department_id
                    INNER JOIN department ON department.code=portal_department.department_code
                    left outer join product_category on product.category_id = product_category.id
                WHERE ".$cond." AND portal_department.portal_id='".PORTAL_ID."'";
        $count = DB::fetch($sql,'acount');
        require_once 'packages/core/includes/utils/paging.php';
        $item_per_page = 300;
		$this->map['paging'] = paging($count,$item_per_page,10,false,'page_no',array('department_id', 'product_code', 'product_name'));
        $sql = '
                SELECT
                        * 
                FROM(
                    SELECT pc_department_product.id,
                        pc_department_product.product_id as code,
                        product.name_1 as name,
                        unit.name_1 as unit,
                        department.name_1 as department,
                        product.type,
                        product_category.name as category_id,
                        row_number() over ('.(URL::get('order_by')?'order by '.URL::get('order_by').(URL::get('order_dir')?' '.URL::get('order_dir'):''):'order by pc_department_product.id ASC').') as rownumber
                    FROM pc_department_product
                        INNER JOIN product ON pc_department_product.product_id=product.id
                        INNER JOIN unit ON unit.id=product.unit_id 
                        INNER JOIN portal_department ON portal_department.id=pc_department_product.portal_department_id
                        INNER JOIN department ON department.code=portal_department.department_code
                        left outer join product_category on product.category_id = product_category.id
                    WHERE '.$cond.' AND portal_department.portal_id=\''.PORTAL_ID.'\'
                    ORDER BY 
                        UPPER(product.name_1)                
                )    
                WHERE
                    rownumber > '.(page_no()-1)*$item_per_page.' and rownumber<='.(page_no()*$item_per_page).'
                ';
        $items = DB::fetch_all($sql);
        //System::debug($items);
        $i=1;
        foreach($items as $key=>$value)
        {
            $items[$key]['index'] = $i++;
        }
        $this->map['items'] = $items;
        
        //2. hien thi danh sach cac bo phan 
        $sql="Select 
                portal_department.id, 
                portal_department.department_code as code, 
                '--' || department.name_1 as name,
                department.parent_id,
                department.id as department_id
                
            from 
                portal_department
                inner join department on  department.code  = portal_department.department_code and portal_department.portal_id = '".PORTAL_ID."'
            where 
                department.parent_id=0
            order by 
                portal_department.id ";
        
        $departments = DB::fetch_all($sql);
        $result = array();
        foreach($departments as $key=>$row)
        {
             array_push($result,$departments[$key]);
             //2. tim nhung dong child $row['department_id']
             $sql = "select portal_department.id,
                        portal_department.department_code as code,
                        '----' || department.name_1 as name,
                        department.parent_id,
                        department.id as department_id
                        from portal_department
                        inner join department on department.code  = portal_department.department_code
                    where department.parent_id=".$row['department_id']." AND portal_department.portal_id='".PORTAL_ID."'";
             $items = DB::fetch_all($sql);
             foreach($items as $k=>$v)
             {
                array_push($result,$items[$k]);
             }
        }

        $department_list = '';
		foreach($result as $id => $value)
        {
            if(isset($_REQUEST['department_id']) && $_REQUEST['department_id']==$value['id'])
            {
                $department_list .= '<option value="'.$value['id'].'" selected="selected">'.$value['name'].'</option>';
            }
            else
            {
                $department_list .= '<option value="'.$value['id'].'">'.$value['name'].'</option>';
            }
		}
        $this->map['department_options'] = $department_list;
        DB::query('select
			id, product_category.name as name, structure_id
			from product_category
			order by structure_id
		');
		$category_id_list = String::get_list(DB::fetch_all());  
        $this->map['category_id_list'] = $category_id_list;
        $this->parse_layout('list',$this->map);
    }
}
?>
