<?php
class CloneLib
{
	static function clone_portal($old_portal, $new_name, $overwrite_setting = true)
	{
		$from = str_replace('#','',$old_portal['PORTAL_ID']);
		$to = str_replace('#','',$new_name);
		if(!DB::select('ACCOUNT','ID=\'#'.$to.'\''))
		{
			DB::insert('ACCOUNT',array(
				'id' => '#'.$to,
				'TYPE' => 'PORTAL',
				'IS_ACTIVE' =>1,
				'CREATE_DATE' => date('Y-m-d',time())									
			));
		}
		unset($old_portal['id']);
		$old_portal['PORTAL_ID'] = '#'.$to;
		$old_portal['USER_ID'] = Session::get('user_id');
		$old_portal['TIME'] = time();
		if($party = DB::select('PARTY','TYPE=\'PORTAL\' and PORTAL_ID=\'#'.$to.'\''))
		{
			DB::update('PARTY',array('IMAGE_URL'=>$old_portal['IMAGE_URL']),'ID=\''.$party['id'].'\'');
		}
		else
		{
			DB::insert('PARTY',$old_portal);
		}
		$pages = DB::fetch_all('
			SELECT
				*
			FROM
				PAGE
			WHERE
				PARAMS=\'portal='.$from.'\'
				and PACKAGE_ID<>278
		');
		$old_pages = DB::fetch_all('
			SELECT
				*
			FROM
				PAGE
			WHERE
				PARAMS=\'portal='.$to.'\'
		');
		if($old_pages)
		{
			foreach($old_pages as $page_)
			{
				CloneLib::unclone_page($page_);
			}
		}
		//System::debug($old_pages);exit();		
		foreach($pages as $page)
		{
			//CloneLib::un_clone_page($page);
			$new_params = str_replace('portal='.$from,'portal='.$to,$page['params']);
			if(!DB::select('PAGE','NAME=\''.$page['name'].'\' and PARAMS=\''.$new_params.'\''))
			{
				CloneLib::clone_page($page,$page['name'],$new_params);
			}
		}
		if($overwrite_setting)
		{
			CloneLib::copy_account_setting($from, $to);
		}
		//CloneLib::copy_menu($from, $to);
		CloneLib::copy_category($from, $to);
		CloneLib::copy_type($from, $to);
		CloneLib::copy_folder('resources/'.$from,'resources/'.$to);
		CloneLib::copy_folder('packages/enterprises/packages/example/skins/default/images/'.$from,'packages/enterprises/packages/example/skins/default/images/'.$to);
		//CloneLib::copy_service($from, $to);
	}
	static function copy_menu($from,$to)
	{
		if($menus = DB::select_all('MENU','PORTAL_ID=\'#'.$from.'\''))
		{
			foreach($menus as $id=>$menu)
			{
				unset($menu['id']);
				$menu['PORTAL_ID'] = '#'.$to;
				$new_id = DB::insert('MENU',$menu);
				DB::query('
					INSERT INTO
						MENU_ITEM(MENU_ID, TITLE_1,TOOLTIP_1,TITLE_2, TOOLTIP_2,HREF,PARAMS,STRUCTURE_ID,IMAGE_URL,CONDITION,POSITION,LEVEL)
					SELECT
						"'.$new_id.'", TITLE_1, TOOLTIP_1,TITLE_2,TOOLTIP_2, HREF, PARAMS,STRUCTURE_ID,IMAGE_URL,CONDITION,POSITION, LEVEL
					FROM
						MENU_ITEM
					WHERE
						MENU_ID=\''.$id.'\'
				');
				DB::query('
					UPDATE
						BLOCK_SETTING,BLOCK,PAGE
					SET
						BLOCK_SETTING.VALUE=\''.$new_id.'\'
					WHERE
						BLOCK_ID=BLOCK.ID
						and PAGE_ID=PAGE.ID
						and PAGE.PARAMS=\'portal='.$to.'\'
						and SETTING_ID=\'5333_menu_id\'
						and BLOCK_SETTING.VALUE=\''.$id.'\'
				');
			}
		}
	}
	static function copy_type($from,$to)
	{
		DB::query('
			INSERT INTO
				PORTAL_TYPE(TYPE,PORTAL_ID,BRIEF,TEMPLATE_ID)
			SELECT
				TYPE, \'#'.$to.'\', BRIEF, TEMPLATE_ID
			FROM
				PORTAL_TYPE
			WHERE
				PORTAL_ID=\'#'.$from.'\'
		');
	}
	static function update_setting($from,$to, $setting)
	{
		if($value = DB::fetch('
			SELECT
				VALUE
			FROM
				ACCOUNT_SETTING
			WHERE
				ACCOUNT_ID=\'#'.$from.'\'
				and SETTING_ID =\''.$setting.'\'
		','value'))
		{
			DB::update('ACCOUNT_SETTING',array('VALUE'=>$value),'ACCOUNT_ID=\'#'.$to.'\' and SETTING_ID = \''.$setting.'\'');
		}
	}
	static function copy_category($from,$to)
	{
		if(DB::select('CATEGORY','PORTAL_ID=\'#'.$to.'\''))
		{
			DB::query('DELETE FROM CATEGORY WHERE PORTAL_ID=\'#'.$to.'\'');
		}
		DB::query('
			INSERT INTO
				CATEGORY(IS_VISIBLE,NAME_1,NAME_2,NAME_3,DESCRIPTION_1,DESCRIPTION_2,DESCRIPTION_3,TYPE, STRUCTURE_ID,IMAGE_URL,ICON_URL,URL,ADMIN_URL,TOTAL_ITEM, TEMPLATE_ID,PORTAL_ID,ORIGINAL_ID,STATUS)
			SELECT
				IS_VISIBLE, NAME_1,NAME_2,NAME_3, DESCRIPTION_1, DESCRIPTION_2, DESCRIPTION_3, TYPE,STRUCTURE_ID, IMAGE_URL,ICON_URL,URL,ADMIN_URL, TOTAL_ITEM, TEMPLATE_ID, \'#'.$to.'\', ORIGINAL_ID,STATUS
			FROM
				CATEGORY
			WHERE
				PORTAL_ID=\'#'.$from.'\'
		');
		//CloneLib::update_navigation($from,$to);
	}
	static function update_navigation($from,$to)
	{
		$categories = DB::fetch_all('
			SELECT
				BLOCK_SETTING.*, CATEGORY.STRUCTURE_ID
			FROM
				BLOCK_SETTING,BLOCK,PAGE,CATEGORY
			WHERE
				PAGE.PARAMS=\'portal='.$from.'\'
				and SETTING_ID = \'5333_category_id\'
				AND BLOCK.ID = BLOCK_ID
				AND PAGE.ID = BLOCK.PAGE_ID
				AND CATEGORY.ID = BLOCK_SETTING.VALUE
		');
		foreach($categories as $category)
		{
			if($new_category_id = DB::fetch('
				SELECT
					ID
				FROM
					CATEGORY
				WHERE
					STRUCTURE_ID=\''.$category['structure_id'].'\'
					and PORTAL_ID=\'#'.$to.'\'
			','id'))
			{
				DB::query('
					UPDATE
						BLOCK_SETTING, BLOCK, PAGE
					SET
						BLOCK_SETTING.VALUE = \''.$new_category_id.'\'
					WHERE
						BLOCK.ID= BLOCK_ID
						and PAGE.ID= BLOCK.PAGE_ID
						and PAGE.PARAMS=\'portal='.$to.'\'
						and SEETING_ID = \'5333_category_id\'
						and BLOCK.VALUE = \''.$category['value'].'\'
				');
			}
		}
	}
	static function clone_page($page, $new_name, $new_params)
	{
		$old_page_id = $page['id'];
		$page['name'] = $new_name;
		$page['params'] = $new_params;
		unset($page['id']);
		if($new_page_id=DB::insert('PAGE', $page))
		{
			if($blocks = DB::fetch_all('SELECT * FROM BLOCK WHERE PAGE_ID='.$old_page_id.' ORDER BY container_id'))
			{
				$match_blocks = array();
				foreach($blocks as $old_block_id=>$block)
				{
					if($block['container_id'] and isset($match_blocks[$block['container_id']]))
					{
						$block['container_id'] = $match_blocks[$block['container_id']];
					}
					unset($block['id']);
					$block['page_id'] = $new_page_id;
					if($new_block_id=DB::insert('BLOCK',$block))
					{
						$match_blocks[$old_block_id] = $new_block_id;
						//DB::query('INSERT INTO BLOCK_SETTING(BLOCK_ID, VALUE, SETTING_ID) VALUES(SELECT '.$new_block_id.',VALUE, SETTING_ID FROM BLOCK_SETTING) WHERE BLOCK_ID='.$old_block_id);
					}
				}
			}
			return true;
		}
	}
	static function copy_account_setting($from, $to)
	{
		if(DB::fetch('SELECT ID,VALUE FROM ACCOUNT_SETTING WHERE ACCOUNT_ID=\'#'.$to.'\''))
		{
			DB::query('DELETE FROM ACCOUNT_SETTING WHERE ACCOUNT_ID=\'#'.$to.'\'');
		}
		DB::query('
			INSERT INTO 
				ACCOUNT_SETTING(ACCOUNT_ID, VALUE, SETTING_ID) 
			SELECT 
				\'#'.$to.'\',
				VALUE, 
				SETTING_ID 
			FROM 
				ACCOUNT_SETTING 
			WHERE 
				ACCOUNT_ID=\'#'.$from.'\'
		');
		$settings = DB::fetch_all('
			SELECT
				ACCOUNT_SETTING.ID,ACCOUNT_SETTING.VALUE
			FROM
				ACCOUNT_SETTING,SETTING
			WHERE
				ACCOUNT_SETTING.ACCOUNT_ID=\'#'.$to.'\' and SETTING.TYPE=\'IMAGE\'
				AND SETTING.ID = ACCOUNT_SETTING.SETTING_ID
				'				
		);
		foreach($settings as $key=>$value)
		{
			DB::update('ACCOUNT_SETTING',array('VALUE'=>str_replace('resources/'.$from,'resources/'.$to,$value['VALUE'])),'ID='.$key);
		}		
	}
	static function copy_service($from, $to)
	{
		DB::query('
			INSERT INTO
				SERVICE_LEASE(SERVICE_ID,PORTAL_ID,START_DATE,END_DATE,PRICE,TOTAL,NOTE,STATUS) 
			SELECT 
				SERVICE_ID, 
				\'#'.$to.'\',START_DATE,END_DATE,PRICE,TOTAL, NOTE,STATUS
			FROM 
				SERVICE_LEASE
			WHERE 
				PORTAL_ID=\'#'.$from.'\'
		');
	}
	function copy_folder($dir_name,$copy_to)
	{
		if(!is_dir($copy_to))
		{
			mkdir($copy_to);
			if(is_dir($dir_name))
			{
				$dir_handle = opendir($dir_name);
				while($file = readdir($dir_handle))
				{ 
					if ($file != "." && $file != ".." && $file!='items' && $file!='upload')
					{
						if (!is_dir($dir_name."/".$file))
						{
							copy($dir_name."/".$file,$copy_to."/".$file);
						}
						else 
						{
							CloneLib::copy_folder($dir_name."/".$file,$copy_to."/".$file); 
						} 
					}
				}
				
			}
		}
	}
	static function unclone_portal($id)
	{
		$id=str_replace('#','',$id);
		$pages = DB::fetch_all('
			SELECT
				*
			FROM
				PAGE
			WHERE
				PARAMS=\'portal='.$id.'\'
		');
		foreach($pages as $page)
		{
			CloneLib::unclone_page($page);
		}
		CloneLib::unclone_service($id);
		CloneLib::unclone_type($id);
		CloneLib::unclone_menu($id);
	}
	static function unclone_page($page)
	{
		if($blocks = DB::fetch_all('SELECT * FROM BLOCK WHERE PAGE_ID='.$page['id']))
		{
			foreach($blocks as $block_id=>$block)
			{
				DB::delete('BLOCK_SETTING','BLOCK_ID='.$block_id);
				DB::delete('BLOCK','ID='.$block_id);
			}
		}
		DB::delete('PAGE','ID='.$page['id']);
	}
	static function unclone_service($id)
	{
		DB::delete('SERVICE_LEASE','PORTAL_ID=\'#'.$id.'\'');
	}
	static function unclone_type($id)
	{
		DB::delete('PORTAL_TYPE','PORTAL_ID=\'#'.$id.'\'');
	}
	static function unclone_menu($id)
	{
		DB::query('
			DELETE FROM
				MENU_ITEM
			USING
				MENU,MENU_ITEM
			WHERE
				MENU.ID=MENU_ID
				and PORTAL_ID=\'#'.$id.'\'
		');
		DB::delete('MENU','PORTAL_ID=\'#'.$id.'\'');
	}
}
?>