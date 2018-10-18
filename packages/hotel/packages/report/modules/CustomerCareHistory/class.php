<?php 
class CustomerCareHistory extends Module
{
	function CustomerCareHistory ($row)
    {
        Module::Module($row);
        if(User::can_view(false,ANY_CATEGORY))
        {
            require_once 'forms/report.php';
            $this->add_form(new CustomerCareHistoryForm());
        }else
        {
            URL::access_denied();
        }
	}
}
?>