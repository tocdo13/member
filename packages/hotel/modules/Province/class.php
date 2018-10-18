<?php

class Province extends Module
{
	function Province($row)
    {
		Module::Module($row);
        switch(Url::get('cmd'))
        {
            case 'edit':
    			if(User::can_edit(false,ANY_CATEGORY))
                {
                    require_once 'forms/edit.php';
                    $this->add_form(new EditProvinceForm());
                }
                else
					Url::access_denied();
				break;  
            case 'add':
    			if(User::can_add(false,ANY_CATEGORY))
                {
                    require_once 'forms/add.php';
                    $this->add_form(new AddProvinceForm());
                }
                else
					Url::access_denied();
				break;
            case 'delete':
            	if(User::can_delete(false,ANY_CATEGORY))
                {
                    $this->delete_cmd();
                }
                else
					Url::access_denied();
                break;
                
            default:
            	if(User::can_view(false,ANY_CATEGORY))
                {
                    require_once 'forms/list.php';
                    $this->add_form(new ListProvinceForm());
                }
                else
					Url::access_denied();
                break;				
        }
	}
    
    function delete_cmd(){
        if( $id = Url::iget('id') and $items = DB::exists_id( 'province', $id ) )
        {
            DB::delete_id( 'province', $id );
            Url::redirect_current();
        }
    }

}

?>