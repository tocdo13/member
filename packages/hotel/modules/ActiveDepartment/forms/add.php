<?php
class AddActiveDepartmentForm extends Form
{
    function AddActiveDepartmentForm()
    {
        Form::Form('AddActiveDepartmentForm');
    }
    
    function on_submit()
    {
        
        if(Url::get('portal_id'))
        {
            $portal_id = Url::get('portal_id');
        }
        else
            $portal_id = PORTAL_ID;
        $old_dep = DB::fetch_all('Select department_code as id,0 as not_delete from  portal_department where portal_id = \''.$portal_id.'\'');
        //DB::delete('portal_department','portal_id = \''.$portal_id.'\'');
        
        require_once 'packages/hotel/includes/php/module.php';
        
        if(Url::get('selected_ids') && is_array(Url::get('selected_ids')))
        {
            //System::debug($_REQUEST);
            //System::debug($old_dep);
            //exit();
            foreach(Url::get('selected_ids') as $key=>$value)
            {
                if(isset($old_dep[$value]))
                {
                    $old_dep[$value]['not_delete'] = 1;
                    $row = array(
                                'department_code'=> $value,
                                'warehouse_id' =>Url::get('warehouse_id_'.$value),
                                'warehouse_id_2' =>Url::get('warehouse_id_2_'.$value)?Url::get('warehouse_id_2_'.$value):'',
                                'warehouse_pc_id'=>Url::get('warehouse_pc_id_'.$value)?Url::get('warehouse_pc_id_'.$value):''
                            );
                    DB::update('portal_department',$row,'department_code=\''.$value.'\'');   
                    /** manh them phan update lai thong tin module phan quyen cua ban hang **/
                    if(DB::fetch('select * from department where id = (Select parent_id from department where code = \''.$value.'\')','code') == 'VENDING')
                    {
                        if(DB::fetch('select * from portal_department where department_code=\''.$value.'\'','privilege')==''){
                            $department = DB::fetch('select * from department where code = \''.$value.'\'');
                            $module_name = update_module('VENDING',$value,$department['name_1'],'vending','Danh sách khu vực');
                            DB::update('portal_department',array('privilege'=>$module_name),'department_code=\''.$value.'\'');  
                        }else{
                            $privilege = DB::fetch('select * from portal_department where department_code=\''.$value.'\'','privilege');
                            $module_id = DB::fetch('select id from module where name=\''.$privilege.'\'','id');
                            $department = DB::fetch('select * from department where code = \''.$value.'\'');
                            DB::update('category',array('name_1'=>$department['name_1'],'name_2'=>$department['name_1']),'module_id='.$module_id);
                        }
                    }
                    /** end Manh **/
                }
                else
                {
                    $row = array(
                                'department_code'=> $value,
                                'portal_id'=> $portal_id,
                                'warehouse_id' =>Url::get('warehouse_id_'.$value),
                                'warehouse_id_2' =>Url::get('warehouse_id_2_'.$value)?Url::get('warehouse_id_2_'.$value):'',
                                'warehouse_pc_id'=>Url::get('warehouse_pc_id_'.$value)?Url::get('warehouse_pc_id_'.$value):''
                            );
                    //BP nao co cha la vending thi add vao category de phan quuyen
                    if(DB::fetch('select * from department where id = (Select parent_id from department where code = \''.$value.'\')','code') == 'VENDING')
                    {
                        $department = DB::fetch('select * from department where code = \''.$value.'\'');
                        $module_name = update_module('VENDING',$value,$department['name_1'],'vending','Danh sách khu vực');
                        $row += array('privilege'=>$module_name);
                    }
                    
                    //System::debug($row);
                    //insert cac department dc active vao portal
                    $id = DB::insert('portal_department',$row);
                    
                    
                    
                    /**
                     * Khong can lay con nua vi da list het ra toan bo
                     */
                    /*
                    //tu code -> lay ra id cua department
                    $parent_id = DB::fetch('Select id from department where code = \''.$value.'\'','id');
                    //L?y c�c dept con va insert
                    $child = DB::fetch_all('Select code as id from department where parent_id = '.$parent_id);
                    //System::debug($child);
                    if(!empty($child))
                    {
                        foreach($child as $key=>$value)
                        {
                            $row_child = array(
                                            'department_code'=> $key,
                                            'portal_id'=> $portal_id,
                                        );
                            //insert cac department dc active vao portal
                            DB::insert('portal_department',$row_child);
                        }
                    }
                    */
                    /**
                     * Khong can lay con nua vi da list het ra toan bo
                     */
                }
            }
            foreach($old_dep as $k=>$v)
            {
                if($v['not_delete']==0)
                {
                    DB::delete('portal_department','department_code = \''.$k.'\' and portal_id = \''.$portal_id.'\'');
                }
            }
        }
        if(Url::get('portal_id'))
            Url::redirect('active_department');
    }
    
    function draw()
    {
        
        if(Url::get('cmd')=='edit')
        {
            if(Url::get('portal_id'))
            {
                $portal_id = Url::get('portal_id');
            }
            else
                $portal_id = PORTAL_ID;
            $this->map['portal_department'] = DB::fetch_all('Select * from portal_department where portal_id = \''.$portal_id.'\'');
            //Dung mang js de check dept nao dc active o ben layout
            $this->map['portal_department_js'] = String::array2js($this->map['portal_department']);
            //echo Url::get('portal_id');
            
            //Lay cac kho trong portal
            $warehouse_id_list = array(''=>Portal::language('select_warehouse'))+String::get_list(ActiveDepartmentDB::get_warehouse($portal_id));
            
            //System::debug($warehouse_id_list);
            $warehouse_id = '';
    		foreach($warehouse_id_list as $id => $value){
    			$warehouse_id .= '<option value="'.$id.'">'.$value.'</option>';	
    		}
            $this->map['department'] = DB::fetch_all('Select 
                                                            department.id as department_id, 
                                                            department.code as id, 
                                                            department.name_'.Portal::language().' as name, 
                                                            portal_department.warehouse_id,
                                                            portal_department.warehouse_id_2,
                                                            portal_department.warehouse_pc_id
                                                        from 
                                                            department
                                                            left join portal_department on  department.code  = portal_department.department_code and portal_department.portal_id = \''.$portal_id.'\'
                                                        where 
                                                            department.parent_id = 0
                                                        order by 
                                                            department.id
                                                    ');
            
            foreach($this->map['department'] as $key => $value)
            {
                //unset($_REQUEST['warehouse_id_'.$value['id']]);
                $_REQUEST['warehouse_id_'.$value['id']] = $value['warehouse_id']?$value['warehouse_id']:'';
                $_REQUEST['warehouse_id_2_'.$value['id']] = $value['warehouse_id_2']?$value['warehouse_id_2']:'';
                $_REQUEST['warehouse_pc_id_'.$value['id']] = $value['warehouse_pc_id']?$value['warehouse_pc_id']:'';
                
                $this->map['department'][$key]['child'] =  DB::fetch_all('Select 
                                                                                department.id as department_id, 
                                                                                department.code as id, 
                                                                                department.name_'.Portal::language().' as name, 
                                                                                portal_department.warehouse_id,
                                                                                portal_department.warehouse_id_2,
                                                                                portal_department.warehouse_pc_id 
                                                                            from 
                                                                                department
                                                                                left join portal_department on department.code = portal_department.department_code and portal_department.portal_id = \''.$portal_id.'\'
                                                                            where 
                                                                                department.parent_id !=0 
                                                                                and department.parent_id = '.$value['department_id'].'
                                                                            ');
                
                foreach($this->map['department'][$key]['child'] as $k => $v)
                {
                    //unset($_REQUEST['warehouse_id_'.$v['id']]);
                    $_REQUEST['warehouse_id_'.$v['id']] = $v['warehouse_id']?$v['warehouse_id']:'';
                    $_REQUEST['warehouse_id_2_'.$v['id']] = $v['warehouse_id_2']?$v['warehouse_id_2']:'';
                    $_REQUEST['warehouse_pc_id_'.$v['id']] = $v['warehouse_pc_id']?$v['warehouse_pc_id']:'';
                }
            }
            
            //$this->map['department_js'] = String::array2js($this->map['department']);  
               
            //System::debug($this->map['portal_department_js']);   
            $this->map['title'] = Portal::language('edit_department');
            $this->map['warehouse_id']=$warehouse_id;
            //System::debug($this->map['warehouse_id']);
            //System::debug($_REQUEST);
            $this->parse_layout('add', $this->map);
        }
    }
}

?>
