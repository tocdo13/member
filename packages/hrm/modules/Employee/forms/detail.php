<?php
class EmployeeForm extends Form
{
	function EmployeeForm()
	{
		Form::Form('EmployeeForm');
		$adb->link_css(Portal::template('core').'/css/admin.css');
	}
	function on_submit()
	{
		$adb->delete($this,$_REQUEST['id']);
		Url::redirect_current();
	}
	function draw()
	{
		$adb->query('
			SELECT 
				USERINFO.USERID AS id,USERINFO.*
			FROM 
			 	USERINFO
			WHERE
				USERINFO.USERID = '.URL::get('id').'
		');
		$row = $adb->fetch();
		$adb->parse_layout('detail',$row);
	}
	function delete(&$form,$id)
	{
		$adb->delete('USERINFO', 'USERID='.$id.'');
	}
}
?>