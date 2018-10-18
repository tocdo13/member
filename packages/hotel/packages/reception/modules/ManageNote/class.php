<?php

class ManageNote extends Module
{
	function ManageNote($row)
    {
		Module::Module($row);
        switch(Url::get('cmd'))
        {
            case 'add':
            case 'edit':
    			if(User::can_edit(false,ANY_CATEGORY))
                {
                    require_once 'forms/edit.php';
                    $this->add_form(new EditNoteForm());
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
                    $this->add_form(new ListNoteForm());
                }
                else
					Url::access_denied();
                break;				
        }
	}
    
    function delete_cmd(){
        if( $id = Url::iget('id') and $items = DB::exists_id( 'reservation_note', $id ) )
        {
            DB::delete_id( 'reservation_note', $id );
            Url::redirect_current();
        }
    }

}

?>