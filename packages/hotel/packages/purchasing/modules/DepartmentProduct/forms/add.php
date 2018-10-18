<?php 
class EditDepartmentProductForm extends Form{
    function EditDepartmentProductForm(){
        Form::Form('EditDepartmentProductForm');
        $this->link_css('packages/core/includes/js/jquery/window/css/jquery.window.4.04.css');
		$this->link_js('packages/core/includes/js/jquery/window/jquery.window.js');
        $this->link_js('packages/core/includes/js/jquery/jquery.mask.min.js');
        $this->link_js('packages/core/includes/js/multi_items.js');
        $this->link_js('packages/core/includes/js/jquery/datepicker.js');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
        $this->link_js('packages/core/includes/js/jquery/jquery.autocomplete.js');
		$this->link_css('packages/core/skins/default/css/jquery.autocomplete.css');
    }
    function on_submit()
    {
        
        if(isset($_REQUEST['btnSave']))
        {
                /** Kimtan them trường hợp portal bằng tất cả **/
                $sql="Select 
                    portal_department.id, 
                    portal_department.department_code as code, 
                    '--' || department.name_1 as name,
                    department.parent_id,
                    department.id as department_id
                    
                from 
                    portal_department
                    inner join department on department.code  = portal_department.department_code and portal_department.portal_id = '".PORTAL_ID."'
                where 
                    department.parent_id=0
                order by 
                    portal_department.id ";
            
                $departments = DB::fetch_all($sql);
            if(isset($_REQUEST['mi_group']))
            {
                foreach($_REQUEST['mi_group'] as $key=>$record)
                {
                    unset($record['name']);
                    unset($record['unit']);
                    $record['product_id'] = $record['code'];
                    unset($record['code']);
                    if($record['department']!='')
                    {
                        if($record['department']!='ALL')
                        {
                            $record['portal_department_id'] = $record['department'];
                            unset($record['department']);
                            
                            if($record['id']!="" and DB::exists_id('pc_department_product',$record['id']))
                            {
                                DB::update('pc_department_product',$record,'id=\''.$record['id'].'\'');
                            }
                            else
                            {
                                unset($record['id']);
                                if(!DB::exists('select id from pc_department_product where product_id=\''.$record['product_id'].'\' and portal_department_id='.$record['portal_department_id']))
                                    DB::insert('pc_department_product',$record);
                            }
                        }
                        else//Kim tan trường hợp portal = tất cả
                        {
                            foreach($departments as $key=>$val)
                            {
                                $record['portal_department_id'] = $val['id'];
                                unset($record['department']);
                                
                                if(isset($record['id']) and $record['id']!="" and DB::exists_id('pc_department_product',$record['id']))
                                {
                                    DB::update('pc_department_product',$record,'id=\''.$record['id'].'\'');
                                }
                                else
                                {
                                    unset($record['id']);
                                    if(!DB::exists('select id from pc_department_product where product_id=\''.$record['product_id'].'\' and portal_department_id='.$record['portal_department_id']))
                                        DB::insert('pc_department_product',$record);
                                }
                            }
                        }
                    }
                }
            }
            Url::redirect_current();
        }
    }
    function draw()
    {
        if(isset($_REQUEST['ids']))
        {
            
            $cond = ' 1>0 ';
            
            $sql = "SELECT pc_department_product.id,
                        pc_department_product.product_id as code,
                        product.name_1 as name,
                        unit.name_1 as unit,
                        portal_department.id as department
                FROM pc_department_product
                INNER JOIN product ON pc_department_product.product_id=product.id
                INNER JOIN unit ON unit.id=product.unit_id 
                INNER JOIN portal_department ON portal_department.id=pc_department_product.portal_department_id
                INNER JOIN department ON department.code=portal_department.department_code
                WHERE ".$cond." AND pc_department_product.id in (".$_REQUEST['ids'].")
                ORDER BY id
                ";
			$items = DB::fetch_all($sql);
			$_REQUEST['mi_group'] = $items;
            $this->map['title'] = 'Sửa khai báo hàng hóa cho bộ phận';
            
        }
        else
        {
            $this->map['title'] = 'Thêm khai báo hàng hóa cho bộ phận';
            
            
        }
        
        //2. hien thi danh sach cac bo phan 
        $sql="Select 
                portal_department.id, 
                portal_department.department_code as code, 
                '--' || department.name_1 as name,
                department.parent_id,
                department.id as department_id
                
            from 
                portal_department
                inner join department on department.code  = portal_department.department_code and portal_department.portal_id = '".PORTAL_ID."'
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
        
        //2. lay ra danh sach cac san pham cho tat ca cac bo phan
         
        $product_department = DB::fetch_all("SELECT product.id,
                                                product.name_1 as product_name,
                                                unit.name_1 as unit
                                            FROM product 
                                            INNER JOIN unit ON unit.id=product.unit_id
                                            
                                            ");
        $this->map['product_department'] = $product_department;
        /** chuyen mang tat ca product sang**/
        $sql = '
                    select 
                    product.id as id,
					product.name_'.Portal::language().' as name, 
                    unit.name_1 as unit,
					rownum
				from
                    product
                    INNER JOIN unit ON unit.id=product.unit_id
				order by
					product.id
			';
		$this->map['products'] = DB::fetch_all($sql);
        /** end chuyen mang tat ca product sang**/
        
		$this->parse_layout('add',$this->map);
    }
}
?>
