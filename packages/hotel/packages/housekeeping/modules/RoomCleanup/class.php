<?php 
class RoomCleanup extends Module
{
	function RoomCleanup($row)
	{
		Module::Module($row);
        
        switch(Url::get('cmd'))
        {
            case 'add':
                if(User::can_add(false,ANY_CATEGORY))
                {
					require_once 'forms/add.php';
					$this->add_form(new AddForm());
				}
                else
					Url::access_denied();
				break;
            case 'edit':
                if(User::can_edit(false,ANY_CATEGORY))
                {
					require_once 'forms/edit.php';
					$this->add_form(new EditForm());
				}
                else
					Url::access_denied();
				break;
            case 'delete':
                if(User::can_delete(false,ANY_CATEGORY))
                {
					$this->delete();
				}
                else
					Url::access_denied();
				break;
            default:
            	if(User::can_view(false,ANY_CATEGORY))
                {
					require_once 'forms/list.php';
					$this->add_form(new ListForm());
				}
                else
					Url::access_denied();
				break;
        }
	}
    
    function delete()
    {
        if( $id = Url::iget('id') and $items = DB::exists_id( 'room_cleanup', $id ) )
        {
            DB::delete_id( 'room_cleanup', $id );
            Url::redirect_current();
        }
        
    }
}
?>