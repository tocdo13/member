<?php

class Api extends Module
{
	function Api($row)
    {
		Module::Module($row);
        if(User::can_view(false,ANY_CATEGORY))
                {
                    require_once 'forms/list.php';
                    $this->add_form(new ListBarNoteForm());
                }
                else
					Url::access_denied();
	}
}

?>