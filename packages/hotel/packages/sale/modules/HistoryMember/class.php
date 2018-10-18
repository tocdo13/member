<?php 
class HistoryMember extends Module
{
	function HistoryMember($row)
	{
		Module::Module($row);
        if(User::can_view(false,ANY_CATEGORY)){
    		require_once 'packages/hotel/includes/php/hotel.php';
    		require_once 'forms/list.php';
    		$this->add_form(new HistoryMemberForm());
        }
	}
}
?>