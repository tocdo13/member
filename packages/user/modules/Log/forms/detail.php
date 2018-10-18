<?php
class LogForm extends Form
{
	function LogForm()
	{
		Form::Form("LogForm");
		$this->add('id',new IDType(true,'object_not_exists','log'));
	}
	function draw()
	{
		DB::query('
			select 
				`log`.id
				,FROM_UNIXTIME(log.time,"%d/%m/%Y") as time ,`log`.type ,`log`.description ,`log`.parameter ,`log`.note ,`log`.title 
				

				,user.user_name as user_id 

				,module.name as module_id 
			from 
			 	`log`
				

				left outer join `user` on `user`.id=`log`.user_id 

				left outer join `module` on `module`.id=`log`.module_id 
			where
				`log`.id = '.URL::get('id'));
		if($row = DB::fetch())
		{
			$this->parse_layout('detail',$row);
		}
	}
}
?>