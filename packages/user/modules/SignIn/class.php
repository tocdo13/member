<?php
class SignIn extends Module
{
	function SignIn($row)
	{
		Module::Module($row);
		if(User::is_login())
		{
			if($data = Session::get('user_data') and $data['home_page'])
			{
				Url::redirect(str_replace('?page=','',$data['home_page']));
			}
			else
			{
				Url::redirect('home');
			}
		}
		else
		{
			require_once 'forms/sign_in.php';
			$this->add_form(new SignInForm);
		}
	}
}
?>