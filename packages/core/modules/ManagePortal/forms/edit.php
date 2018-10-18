<?php
class EditManagePortalForm extends Form
{
	function EditManagePortalForm()
	{
		Form::Form('EditManagePortalForm');
		$this->add('id',new TextType(true,'id_is_required',0,255));
		if(URL::get('cmd')=='edit')
		{
			$this->add('id',new IDType(true,'object_not_exists','account'));
		}
		elseif(URL::get('cmd')=='add')
		{
			
		//	echo 1;exit();
			$this->add('id',new UniqueType(true,'duplicate_portal_id','ACCOUNT','ID'));
		}
		$this->add('name_1',new TextType(true,'miss_portal_name',0,255)); 
	}
	function on_submit()
	{
		if(URL::get('cmd')=='edit')
		{
			$row = DB::select('account',$_REQUEST['id']);
		}
		if($this->check() and URL::get('confirm_edit'))
		{
			if(Url::get('id') and preg_match('/[^a-zA-Z0-9_#]/', Url::get('id')) == 0)
			{
				$portal_id = $_REQUEST['id'];		
				$account_new_row = array(
					'id'=>$portal_id,
					'type'=>'PORTAL',
					'cache_privilege'=>'',
				)+(URL::get('password')?array('password'=>User::encode_password($_REQUEST['password'])):array());
				$party_new_row = 
					array(
						'zone_id', 
						'type'=>'PORTAL',
						'status'=>'SHOW',
						'name_1'
					);	
				if(URL::get('cmd')=='edit')
				{
					DB::update('party', $party_new_row,'user_id=\''.$portal_id.'\' and type=\'PORTAL\'');
					DB::update('account', $account_new_row,'id=\''.$portal_id.'\'');
				}
				else
				{
					require_once 'packages/core/includes/system/si_database.php';
					DB::insert('account', $account_new_row);
					$id = DB::insert('party', $party_new_row+array('user_id'=>$portal_id));
				}
				///////////////////////////Tao cac thu muc////////////////////////////////////////
				if(!is_dir('cache/portal/'.str_replace('#','',$portal_id).'')){
					mkdir('cache/portal/'.str_replace('#','',$portal_id).'');
				}
				if(!is_dir('cache/portal/'.str_replace('#','',$portal_id).'/user')){
					mkdir('cache/portal/'.str_replace('#','',$portal_id).'/user');
				}
				if(!is_dir('cache/portal/'.str_replace('#','',$portal_id).'/PA18')){
					mkdir('cache/portal/'.str_replace('#','',$portal_id).'/PA18');
				}
				if(!is_dir('cache/portal/'.str_replace('#','',$portal_id).'/config')){
					mkdir('cache/portal/'.str_replace('#','',$portal_id).'/config');
				}
                /** manh them **/
                if(!is_dir('cache/data/'.str_replace('#','',$portal_id))){
					mkdir('cache/data/'.str_replace('#','',$portal_id));
				}
                if(!is_dir('resources/interfaces/images/'.str_replace('#','',$portal_id))){
					mkdir('resources/interfaces/images/'.str_replace('#','',$portal_id));
				}
                /** end manh **/
				if(!file_exists('cache/portal/'.str_replace('#','',$portal_id).'/config/config.php')){
					copy('cache/portal/default/config/config.php','cache/portal/'.str_replace('#','',$portal_id).'/config/config.php');
				}
                if(!file_exists('cache/portal/'.str_replace('#','',$portal_id).'/config/date.php')){
					copy('cache/portal/default/config/date.php','cache/portal/'.str_replace('#','',$portal_id).'/config/date.php');
				}
				
				//if(!file_exists('cache/portal/'.str_replace('#','',$id).'/user/admin.php'))
				{
					$path = 'cache/portal/'.str_replace('#','',$portal_id).'/user/admin.php';
					$hand = fopen($path,'w+');
					fwrite($hand,'$this->groups=array (3 => "'.$portal_id.'");$this->actions=array();');
					fclose($hand);
				}
				Url::redirect_current();
			}
			else
			{
				$this->error('id','invalid_portal_name_characterssss');
			}
		}
	}	
	function draw()
	{
		$row = DB::fetch('
			select
				party.id,party.name_1,party.zone_id
			from
				party
				inner join account on account.id = party.user_id
			where
				party.user_id=\''.URL::sget('id').'\' and account.type=\'PORTAL\'
		');
		if(URL::get('cmd')=='edit' and $row and $account = DB::fetch('select account.id,to_char(account.create_date,\'dd/mm/YYYY\') as create_date,account.is_active,account.is_block from account where id =\''.URL::sget('id').'\''))
		{
			$row['id'] = $account['id'];
			foreach($row as $key=>$value)
			{
				if(is_string($value) and !isset($_POST[$key]))
				{
					$_REQUEST[$key] = $value;
				}
			}
			$edit_mode = true;
		}
		else
		{
			$edit_mode = false;
		}
		$zone_id_list = String::get_list(DB::fetch_all('
			SELECT
				ID, ZONE.NAME_'.Portal::language().' AS name
			FROM
				ZONE
			WHERE
				'.IDStructure::direct_child_cond('1010000000000000000').' 
			ORDER BY 
				STRUCTURE_ID
			'
		));
		$db_items = DB::fetch_all('
			SELECT
				ACCOUNT.*
			FROM
				ACCOUNT 
			WHERE
				TYPE=\'GROUP\' 
			ORDER BY ID
		');
		$group_options = '';
		foreach($db_items as $item)
		{
			$group_options .= '<option value="'.$item['id'].'">'.$item['id'].'</option>';
		}
		
		$this->parse_layout('edit',
			($edit_mode?$row:array())+
			array(
				'zone_id_list'=>$zone_id_list, 
				'group_options'=>$group_options,
			)
		);
	}
}
?>
