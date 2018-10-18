<?php 
class MemberDiscount extends Module
{
	function MemberDiscount($row)
	{
		Module::Module($row);
        require_once 'packages/hotel/includes/php/hotel.php';
        switch(Url::get('cmd'))
        {
            case 'add':
                if(User::can_add(false,ANY_CATEGORY))
                {
                    require_once 'forms/edit.php';
		            $this->add_form(new EditMemberDiscountForm());
                }
                else
                    URL::access_denied();
                break;
            case 'edit':
                if(User::can_edit(false,ANY_CATEGORY) AND DB::exists("SELECT id from member_discount where id=".Url::get('id')))
                {
                    require_once('forms/edit.php');
                    $this -> add_form(new EditMemberDiscountForm());
                }
                else
                    URL::access_denied();
                break;
            case 'import':
				if(User::can_add(false,ANY_CATEGORY))
                {
					require_once 'forms/import.php';
					$this->add_form(new ImportMemberDiscountForm());
				}
                else
					Url::access_denied();
				break;
            case 'add_level':
                if(User::can_edit(false,ANY_CATEGORY) AND DB::exists("SELECT id from member_level where id=".Url::get('level_id')))
                {
                    require_once('forms/add_level.php');
                    $this -> add_form(new MemberLevelDiscountForm());
                }
                else
                    URL::access_denied();
                break;
            default:
                if(User::can_view(false,ANY_CATEGORY))
                {
                    require_once('forms/list.php');
                    $this ->add_form(new MemberDiscountForm());
                }
                else
                    URL::access_denied();
                break;
        }
	}
}
?>