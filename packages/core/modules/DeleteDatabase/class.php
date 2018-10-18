<?php
class DeleteDatabase extends Module
{
    function DeleteDatabase($row)
    {
        Module::Module($row);
        if(User::id()=='developer04' || User::id()=='developer03')
		{
		      
            require_once 'forms/list.php';
			$this->add_form(new ListDatabaseForm());
        }
        else
        {
            Url::access_denied();
        }
    }
}
?>