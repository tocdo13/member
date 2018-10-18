<?php

class ManageDepartment extends Module
{
	function ManageDepartment($row)
    {
		Module::Module($row);
		require_once 'db.php';
        switch(Url::get('cmd'))
        {
            case 'edit':  
            case 'add':
    			if(User::can_admin(false,ANY_CATEGORY))
                {
                    require_once 'forms/add.php';
                    $this->add_form(new AddManageDepartmentForm());
                }
                else
					Url::access_denied();
				break;
            case 'delete':
            	if(User::can_admin(false,ANY_CATEGORY))
                {
                    require_once 'forms/delete.php';
                    $this->add_form(new DeleteManageDepartmentForm());
                }
                else
					Url::access_denied();
                break;
            case 'delete_id':
            	if(User::can_admin(false,ANY_CATEGORY))
                {
                    $this->delete_cmd();
                }
                else
					Url::access_denied();
                break;
                
            default:
            	if(User::can_admin(false,ANY_CATEGORY))
                {
                    require_once 'forms/list.php';
                    $this->add_form(new ListManageDepartmentForm());
                }
                else
					Url::access_denied();
                break;				
        }
	}
    
    function delete_cmd(){
        if( $id = Url::iget('id') and $items = DB::exists_id( 'department', $id ) )
        {
            $dept_code = DB::fetch('Select * from department where id= '.$id);
            DB::delete('portal_department','department_code = \''.$dept_code['code'].'\'');
            DB::delete('product_price_list','department_code = \''.$dept_code['code'].'\'');
            DB::delete_id( 'department', $id );
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
            Url::redirect('manage_department');
        }
    }

}

?>