<?php
	define( 'ROOT_PATH', strtr(dirname( __FILE__ ) ."/",array('\\'=>'/')));
	require_once 'packages/core/includes/system/config.php';
	$messages = DB::fetch_all('
	select * from (
		select
			chat.*,
			ROWNUM as rownumber
		from
			chat
		where
			time>='.(URL::get('time')-3600*24).'
		order by
			time
	)
	where
		rownumber >0 and rownumber <100
	');
	$user = Session::get('user_id');
	if(sizeof($messages)==0)
	{
		$messages = DB::fetch_all('
			select * from (
				select
					chat.*,
					ROWNUM as rownumber
				from
					chat
				where
					time>='.(time()-24*3600).'
				order by
					time
			)
			where
				rownumber >0 and rownumber <500	
		');
		$messages = array_reverse($messages);
		foreach($messages as $id=>$message)
		{
			echo '<strong>'.(($message['user_id']==Session::get('user_id'))?'<font color="blue">':'<font color="#999999">').$message['user_id'].(($message['user_id']==Session::get('user_id'))?'</font>':'</font>').'</strong>: '.$message['message'].'<br>';
		}
	}
	else
	{
		foreach($messages as $id=>$message)
		{
			echo '<strong>'.(($message['user_id']==Session::get('user_id'))?'<font color="blue">':'').$message['user_id'].(($message['user_id']==Session::get('user_id'))?'</font>':'').'</strong>: '.$message['message'].'<br>';
		}
	}
	DB::close();
?>