<?php 
class MemberLevelDiscount extends Module
{
	function MemberLevelDiscount($row)
	{
		Module::Module($row);
        require_once 'packages/hotel/includes/php/hotel.php';
        switch (Url::get('cmd')){
            case 'service':
				if(User::can_view(false,ANY_CATEGORY))
                {
					require_once 'forms/service.php';
					$this->add_form(new ServiceMemberLevelDiscountForm());
				}
                else
					Url::access_denied();
				break;
            default:
				if(User::can_view(false,ANY_CATEGORY)){
					require_once 'forms/list.php';
					$this->add_form(new MemberLevelDiscountForm());
				}else{
					Url::access_denied();
				}
				break;
        }
	}
}
?>