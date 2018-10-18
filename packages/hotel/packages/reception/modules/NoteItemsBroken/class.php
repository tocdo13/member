<?php 
class NoteItemsBroken extends Module
{
	function NoteItemsBroken($row)
	{
	
			Module::Module($row);
            if(User::can_view(false,ANY_CATEGORY))
			{
				require_once 'forms/edit.php';
				$this->add_form(new NoteItemsBrokenForm());
			}
			else
			{
				URL::access_denied();
			}
	}
}
?>