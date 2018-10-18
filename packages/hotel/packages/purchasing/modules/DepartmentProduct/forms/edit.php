<?php
class DepartmentProductForm extends Form
{
	function DepartmentProductForm()
	{
		Form::Form('DepartmentProductForm');
		//$this->add('room.name',new TextType(true,'invalid_name',0,255));
		$this->link_js('packages/core/includes/js/multi_items.js');
        
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
        $this->link_js('packages/core/includes/js/jquery/jquery.autocomplete.js');
		$this->link_css('packages/core/skins/default/css/jquery.autocomplete.css');
	}
	function on_submit()
	{
       if(Url::get('deleted_ids'))
       {
            $ids = "(".Url::get('deleted_ids').")";
            DB::delete('pc_department_product','id in '.$ids);
       }
	   if(!Url::get('search'))
       {
           $portal_department_id  =Url::get('department_id'); 
           
           foreach($_REQUEST['products'] as $key=>$value)
           {
                $row = array();
                $row['portal_department_id'] = $portal_department_id;
                $row['product_id'] = $value['code'];
                if(isset($value['id']) && $value['id']!="")
                {
                    //update theo id
                    DB::update('pc_department_product',$row,'id='.$value['id']);
                }    
                else
                {
                    //insert 
                    DB::insert('pc_department_product',$row);
                }
           }
       }
	}	
	function draw()
	{
		$this->map = array();
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
        
        $default = array('id'=>0,'code'=>'CHOOSE_DEPARTMENT','name'=>Portal::language('select_department'));
        $result = array();
        
        array_push($result,$default);
        $parent_id = false;
        
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
        $this->map['department_list'] = $department_list;
        
        
        //2. lay ra danh sach cac san pham cho tat ca cac bo phan
         
        $product_department = DB::fetch_all("SELECT pc_sup_price.id,
                                                pc_sup_price.product_id,
                                                product.name_1 as product_name,
                                                unit.name_1 as unit,
                                                pc_sup_price.price,
                                                supplier.name as supplier_name
                                                
                                            FROM pc_sup_price
                                            INNER JOIN supplier ON supplier.id=pc_sup_price.supplier_id
                                            INNER JOIN product ON product.id=pc_sup_price.product_id
                                            INNER JOIN unit ON unit.id=product.unit_id
                                            ORDER BY pc_sup_price.id
                                            ");
        $this->map['product_department'] = $product_department;
        
        //3. hien thi danh sach products 
        $cond = " 1=1 ";
        if(Url::get('department_id'))
        {
            $cond .=" AND (pc_department_product.portal_department_id=".Url::get('department_id')." 
                        OR 
                    department.parent_id in(SELECT department.id
                    FROM department,portal_department
                    WHERE department.code=portal_department.department_code AND portal_department.portal_id='".PORTAL_ID."'
                        AND portal_department.id=".Url::get('department_id')."))";
        }
        $sql = "SELECT pc_department_product.id,
                        pc_department_product.product_id as code,
                        product.name_1 as name,
                        unit.name_1 as unit,
                        supplier.name as supplier,
                        pc_sup_price.price,
                        department.name_1 as department
                FROM pc_department_product
                INNER JOIN pc_sup_price ON pc_sup_price.product_id=pc_department_product.product_id
                INNER JOIN supplier ON supplier.id=pc_sup_price.supplier_id
                INNER JOIN product ON pc_sup_price.product_id=product.id
                INNER JOIN unit ON unit.id=product.unit_id 
                INNER JOIN portal_department ON portal_department.id=pc_department_product.portal_department_id
                INNER JOIN department ON department.code=portal_department.department_code
                WHERE ".$cond." AND portal_department.portal_id='".PORTAL_ID."'
                ORDER BY id
                ";
        $items = DB::fetch_all($sql);
        
        $i=1;
        foreach($items as $key=>$value)
        {
            $items[$key]['index'] = $i++;
            $items[$key]['price'] = System::display_number($value['price']);
        }
        $_REQUEST['products'] = $items;
		$this->parse_layout('edit',$this->map);
	}
}
?>