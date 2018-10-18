<?php
class ManageUserForm extends Form
{
	function ManageUserForm()
	{
		Form::Form('ManageUserForm');
		$this->add('id',new IDType(true,'object_not_exists','ACCOUNT'));
		$this->link_css(Portal::template('core').'/css/admin.css');
	}
	function on_submit()
	{
		if($this->check() and URL::get('confirm'))
		{
			$this->delete($this,$_REQUEST['id']);
			require_once 'packages/core/includes/system/update_privilege.php';
			make_privilege_cache();
			Url::redirect_current(array(  
	     'join_date_start','join_date_end',  'active'=>isset($_GET['active'])?$_GET['active']:'', 'block'=>isset($_GET['block'])?$_GET['block']:'',  'user_id'=>isset($_GET['user_id'])?$_GET['user_id']:'', 
	));
		}
	}
	function draw()
	{
		DB::query('
			SELECT 
				ACCOUNT.ID,ACCOUNT.PASSWORD,ACCOUNT.IS_BLOCK,
				ACCOUNT.IS_ACTIVE,ACCOUNT.CREATE_DATE AS JOIN_DATE,
				PARTY.EMAIL ,PARTY.NAME_'.Portal::language().' AS FULL_NAME,
				PARTY.GENDER,PARTY.ADDRESS,PARTY.EMAIL,PARTY.PHONE AS PHONE_NUMBER,
				PARTY.BIRTH_DAY,
				ZONE.NAME_'.Portal::language().' AS ZONE_ID 
			FROM 
			 	ACCOUNT,PARTY,ZONE				
			WHERE
				ACCOUNT.ID(+) = PARTY.USER_ID AND PARTY.ZONE_ID(+) = ZONE.ID
				AND ACCOUNT.ID = \''.URL::sget('id').'\'
		');
		if($row = DB::fetch())
		{
			$row['gender'] = $row['gender']?Portal::language('male'):Portal::language('female');     
		}
		DB::query('
			SELECT
				ACCOUNT_PRIVILEGE.ID
				,ACCOUNT_PRIVILEGE.PARAMETERS
				,PRIVILEGE.id PRIVILEGE_ID_NAME
			FROM
				ACCOUNT_PRIVILEGE,PRIVILEGE
			WHERE
				ACCOUNT_PRIVILEGE.ACCOUNT_ID=\''.$_REQUEST['id'].'\''
		);
		$row['user_privilege_items'] = DB::fetch_all();
		$this->parse_layout('detail',$row);
	}
	function delete(&$form,$id)
	{
		DB::delete('account_related', 'child_id=\''.$id.'\' or parent_id=\''.$id.'\'');
		DB::delete('account_privilege', 'account_id=\''.$id.'\'');
		DB::delete('account_setting', 'account_id=\''.$id.'\'');
		if($party = DB::select('party','user_id=\''.$id.'\' and type=\'USER\''))
		{
			if(file_exists($party['image_url']))
			{
				@unlink($party['image_url']);
			}
			DB::delete('party', 'user_id=\''.$id.'\' and type=\'USER\'');
		}
		DB::delete_id('account', $id);
	}
}
?>