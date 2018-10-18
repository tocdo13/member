<?php
class ListManageDepartmentForm extends Form
{
    function ListManageDepartmentForm()
    {
        Form::Form('ListManageDepartmentForm');
    }
    
    function on_submit()
    {
        $selected_ids = Url::get('selected_ids');
        //System::debug($selected_ids);exit();
        if(!empty($selected_ids))
        {
			$check = true;
            foreach($selected_ids as $id)
			{
                if(DB::fetch('select * from department where id=\''.$id.'\' and code in (\'SPA\',\'GOLF\',\'SALES\',\'HK\',\'AMENITIES\',\'MINIBAR\',\'LAUNDRY\',\'RES\',\'REC\',\'BANQUET\',\'VENDING\',\'KARAOKE\',\'WH\',\'TICKET\')'));
                {
                    $check = false;
                }
            }
            if($check==false)
            {
                echo '<script>
                        alert(\''.Portal::language('khong_the_xoa_cac_ma_mac_dinh').'\');
                        window.location="?page=manage_department";
                      </script>  
                ';
                exit();
            }
        }   
        if(!empty($selected_ids))
        {
			foreach($selected_ids as $id)
			{
				if($department=DB::select('department','id=\''.$id.'\''))
				{
				    DB::delete_id( 'department', $id );
                    DB::delete('product_price_list','department_code = \''.$department['code'].'\'');
                    DB::delete('portal_department','department_code = \''.$department['code'].'\'');
                    //Lay ra cac ma con cua dept vua xoa
                    $child_code = DB::fetch_all('Select * from department where parent_id = '.$id);
                    //System::debug($child_code);
                    //exit();
                    if(!empty($child_code))
                    {
                        foreach($child_code as $key=>$value)
                        {
                            //xoa het cac con
                            DB::delete_id( 'department', $value['id'] );
                            DB::delete('portal_department','department_code = \''.$value['code'].'\'');
                            DB::delete('product_price_list','department_code = \''.$value['code'].'\'');
                        }
                    }
				}
			}
            
        } 
    }
    function draw()
    {

        $languages = DB::select_all('language');
        $this->map['department'] = DB::fetch_all('Select 
                                                        department.*
                                                    from 
                                                        department
                                                    where 
                                                        department.parent_id = 0');
        $this->map['department_list'] = array('0'=>Portal::language('none'))+String::get_list($this->map['department']);
        foreach($this->map['department'] as $key => $value)
        {
            $this->map['department'][$key]['child'] =  DB::fetch_all('Select 
                                                                            department.*
                                                                        from 
                                                                            department
                                                                        where 
                                                                            department.parent_id !=0 and department.parent_id = '.$value['id']);
            
        }
        //System::debug($this->map['department']);
        
        
        //$this->map['items']=ManageDepartmentDB::select_all_department();
        $this->parse_layout('list', $this->map+array(
                                                'portal_id_list'=>array(''=>Portal::language('all'))+String::get_list(Portal::get_portal_list()),
                                                'languages'=>$languages,
                                                )
                            );
    }
}

?>
