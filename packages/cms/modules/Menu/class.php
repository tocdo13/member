<?php
class Menu extends Module
{
	function Menu($row)
	{
		Module::Module($row);
        
        if(Url::get('change_language')){
            DB::update('session_user',array('language_id'=>Url::iget('change_language')),'user_id = \''.Session::get('user_id').'\'');
            echo 'successful!';
            exit();
        }
        
		require_once 'db.php';
		if(Url::get('page_id'))
        {
			$_SESSION['home_page'] = Url::get('page_id');
		}
		if(!isset($_SESSION['home_page']))
        {
			$_SESSION['home_page'] = '';
		}
        require_once 'forms/list.php';
        $this->add_form(new MenuForm());
	}
}
?>
