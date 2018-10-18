<?php 
class MemberLevel extends Module
{
	function MemberLevel($row)
	{
		Module::Module($row);
		require_once 'packages/hotel/includes/php/hotel.php';
        switch(Url::get('cmd'))
        {
            case 'add':
                if(User::can_add(false,ANY_CATEGORY))
                {
                    require_once 'forms/edit.php';
		            $this->add_form(new EditMemberLevelForm());
                }
                else
                    URL::access_denied();
                break;
            case 'edit':
                if(User::can_edit(false,ANY_CATEGORY) AND DB::exists("SELECT id from member_level where id=".Url::get('id')))
                {
                    require_once('forms/edit.php');
                    $this -> add_form(new EditMemberLevelForm());
                }
                else
                    URL::access_denied();
                break;
            default:
                if(User::can_view(false,ANY_CATEGORY))
                {
                    require_once('forms/list.php');
                    $this ->add_form(new MemberLevelForm());
                }
                else
                    URL::access_denied();
                break;
        }
		
	}
}
?>
