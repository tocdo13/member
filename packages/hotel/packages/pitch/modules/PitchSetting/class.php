<?php 
class PitchSetting extends Module
{
	function PitchSetting($row)
	{
		Module::Module($row);
		$_REQUEST['bar_id'] = Session::get('bar_id');
		if(User::can_view(false,ANY_CATEGORY)){
        switch (Url::get('cmd'))
            {
    			case 'add_match':
                    require_once 'forms/add_match.php';
                    $this->add_form(new AddMatchForm());
                    break;
                default:
                    require_once 'forms/list.php';
                    $this->add_form(new PitchSettingForm());
            }			
		}else{
			URL::access_denied();
		}
	}
}
?>