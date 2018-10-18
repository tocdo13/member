<?php 
class BarArea extends Module
{
	function BarArea($row)
	{
		Module::Module($row);
        if(User::can_edit(false,ANY_CATEGORY))
        {
			require_once 'forms/edit.php';
			$this->add_form(new BarAreaForm());
		}
        else
        {
			URL::access_denied();
		}
	}	
}
?>