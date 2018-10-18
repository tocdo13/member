<?php 
class MemberLevelPolicies extends Module
{
	function MemberLevelPolicies($row)
	{
		Module::Module($row);
		require_once 'packages/hotel/includes/php/hotel.php';
        if(Url::get('level_id'))
        {
    		require_once 'forms/edit.php';
    		$this->add_form(new MemberLevelPoliciesForm());
        }
        else
        {
            URL::access_denied();
        }
	}
}
?>