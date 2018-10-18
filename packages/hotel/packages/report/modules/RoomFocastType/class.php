<?php 
class RoomFocastType extends Module
{
	function RoomFocastType($row)
	{
		Module::Module($row);
		if(URL::get('reset'))
		{
			URL::redirect_current();
		}
		else
		{
			if(User::can_view(false,ANY_CATEGORY))
			{
    			switch(URL::get('cmd'))
    			{
    				case 'by_year':
    					require_once 'forms/by_year.php';
    					$this->add_form(new RoomFocastTypeFormByYear());break;
    				case 'by_day':
    					require_once 'forms/report.php';
    					$this->add_form(new RoomFocastTypeFormByMonth());break;       
    				default: 
    					//Kimtan:cho link thẳng vào xem theo ngày không cần lựa chọn nữa
                        //require_once 'forms/option.php';
    					//$this->add_form(new RoomFocastTypeForm());
                        require_once 'forms/report.php';
    					$this->add_form(new RoomFocastTypeFormByMonth());
                        //end KimTan
                        break;
    			}
			}
			else
			{
				URL::access_denied();
			}
		}
	}
}
?>