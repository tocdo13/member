<?php
if(User::is_login())
{
	if (Session::is_set('user_id'))
	{
		$id=Session::get('user_id');
		DB::update('account',array('last_online_time'=>time()),'id=\''.$id.'\'');
		setcookie('user_id',"",time()-3600);
		Session::destroy('user_id');
	}
	if(URL::check('href'))
	{
		URL::redirect_url($_REQUEST['href']);
	}
	else
	{
		URL::redirect_url('?page=home');
	}
}
else
{
	URL::redirect_url('?page=home');
}
?>
