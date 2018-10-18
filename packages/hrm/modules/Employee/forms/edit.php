<?php
class EditEmployeeForm extends Form
{
	function EditEmployeeForm()
	{
		Form::Form('EditEmployeeForm');
		$this->add('USERID',new TextType(true,'id_is_required',0,255));
		$this->add('NAME',new TextType(true,'full_name_is_required',0,255)); 
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
		$this->link_css('packages/core/skins/default/css/jquery/datepicker.css');
	}
	function on_submit()
	{
		if(URL::get('cmd')=='edit')
		{
			$row = $adb->select('USERINFO','USERID = '.$_REQUEST['id']);
		}
		if($this->check() and URL::get('confirm_edit'))
		{
			$account_new_row = array(
				'NAME'=>Url::get('NAME'),
				'Gender'=>Url::get('Gender'),
				'SSN'=>Url::get('SSN'),
				'OPHONE'=>Url::get('OPHONE'),
				'TITLE'=>Url::get('TITLE'),
				'BIRTHDAY'=>Url::get('BIRTHDAY')?Url::get('BIRTHDAY'):0,
				'HIREDDAY'=>Url::get('HIREDDAY')?Url::get('HIREDDAY'):0,
				'CardNo'=>Url::get('CardNo'),
				'PAGER'=>Url::get('PAGER'),
				'street'=>Url::get('street'),
				'DEFAULTDEPTID'=>Url::iget('DEFAULTDEPTID')
			);
			if(URL::get('cmd')=='edit'){
				$id = $_REQUEST['id'];
				$adb->update('USERINFO', $account_new_row,'USERID = '.$id.'');
			}else{
				$id = $adb->insert('USERINFO', array('USERID'=>Url::get('USERID'),'Badgenumber'=>Url::get('USERID'))+$account_new_row);
			}
			Url::redirect_current();
		}
	}	
	function draw()
	{
		$this->map = array();
		$row = @$adb->fetch('
			select
				USERINFO.USERID AS ID,USERINFO.*
			from
				USERINFO
			where
				USERINFO.USERID='.URL::iget('id').'
		');
		if(URL::get('cmd')=='edit' and $row)
		{	
			$row['BIRTHDAY'] = str_replace(' 00:00:00','',$row['BIRTHDAY']);
			$row['HIREDDAY'] = str_replace(' 00:00:00','',$row['HIREDDAY']);
			foreach($row as $key=>$value)
			{
				if(is_string($value) and !isset($_POST[$key]))
				{
					$_REQUEST[$key] = $value;
				}
			}
			$edit_mode = true;
			$this->map += $row;
		}
		else
		{
			$_REQUEST['USERID'] = $adb->fetch('SELECT USERID FROM USERINFO ORDER BY USERID DESC','USERID')+1;
			$edit_mode = false;
		}
		$this->map['privilege_list'] =  array(
			-1=>Portal::language('Invalid'),
			0=>Portal::language('User'),
			1=>Portal::language('Enroller'),
			2=>Portal::language('Manager'),
			3=>Portal::language('Suppervisor')
		);
		$this->map['DEFAULTDEPTID_list'] = String::get_list($adb->fetch_all('SELECT DEPTID AS id, DEPTNAME AS name FROM DEPARTMENTS ORDER BY SUPDEPTID'));
		$this->parse_layout('edit',$this->map);
	}
}
?>
