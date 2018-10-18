<?php
require_once 'packages/core/includes/system/database.php';
require_once 'packages/core/includes/system/url.php';
require_once 'cache/privilege.php';
class Ajax
{
	function encode($st)
	{
		return strtr($st,array(']]>'=>']]&gt;','ร '=>'a','รร'=>'a'));
	}
	function array_to_xml($items)
	{
		$return_value = '';
		foreach($items as $key=>$value)
		{
			if(is_numeric($key))
			{
				$key = 'item_'.$key;
			}
			$return_value .= '
	<'.$key.'>';
			if(is_array($value))
			{
				$return_value .= Ajax::array_to_xml($value);
			}
			else
			{
				if($value==htmlentities($value,ENT_QUOTES) and $value!='')
				{
					$return_value .= Ajax::encode($value);
				}
				else
				{
					$return_value .= '<![CDATA['.Ajax::encode($value).']]>';
				}
			}
			$return_value .= '</'.$key.'>';
		}
		return $return_value;
	}
	function to_xml($items,$item_name, $return = false)
	{
		$return_value = '<?xml version="1.0" standalone="yes"?><'.$item_name.'s>';
		foreach($items as $item)
		{
			$return_value .= '
	<'.$item_name.'>
		'.Ajax::array_to_xml($item).'
	</'.$item_name.'>';
		}
		$return_value .= '
</'.$item_name.'s>';

		//$f = fopen('test.xml','w+');
		//fwrite($f, $return_value);
		//fclose($f);
		if($return)
		{
			return $return_value;
		}
		else
		{
			DB::close();
			header('Content-Type: text/xml'); 
			echo $return_value;
		}
	}
	function is_admin()
	{
		if(isset(Session::get('user_id')))
		{
			return DB::select('user_group','user_id="'.Session::get('user_id').'" and group_id=3');
		}
	}
	function have_right($right)
	{
		if(isset(Session::get('user_id')))
		{
			if(DB::select('user_privilege','user_id="'.Session::get('user_id').'" and privilege_id='.$right))
			{
				return true;
			}
			else
			{
				if(DB::query('
					select 
						user_group.id
					from
						user_group
						inner join group_privilege on group_privilege.group_id = user_group.group_id
					where
						user_id = "'.Session::get('user_id').'"
						and privilege_id='.$right
				))
				{
					return DB::fetch();
				}
			}						
		}
	}
}
?>