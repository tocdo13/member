<?php 
class Chatting extends Module
{
	function Chatting($row)
	{
		Module::Module($row);
        require_once 'forms/list.php';
        $this->add_form(new ChattingForm());         
    }	
}
?>