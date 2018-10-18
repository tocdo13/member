<?php
/******************************
COPY RIGHT BY NYN PORTAL - TCV
WRITTEN BY vuonggialong
******************************/

function update_module_list()
{
	if(URL::check(array('cmd'=>'update','PACKAGE_ID')) and DB::exists_id('PACKAGE',URL::get('package_id')))
	{
		require_once 'packages/core/includes/portal/package.php';
		$path = get_package_path(URL::get('package_id')).'/modules';
		if($dir = opendir($path))
		{
			$languages = DB::select_all('LANGUAGE');

			while($file = readdir($dir))
			{
				if($file!='.' and $file!='..' and is_dir($path.$file))
				{
					$module_name = $file;
					
					if(!DB::select('MODULE','NAME=\''.$module_name.'\''))
					{
						$extra = array();
						foreach($languages as $language)
						{
							$extra += array('TITLE_'.$language['ID']=>$module_name,'DESCRIPTION_'.$language['ID']=>$module_name);
						}
						DB::insert('MODULE',array('NAME'=>$module_name,'PACKAGE_ID'=>URL::get('package_id')));
					}
				}
			}
		}
	}
}
function generate_module_cache()
{
	require_once 'packages/core/includes/portal/layout.php';
	$modules = DB::select_all('MODULE');
	foreach($modules as $module)
	{
		$path = get_package_path($module['PACKAGE_ID']).'modules/'.$module['NAME'].'/layouts/';
		echo $path.'<br>';
		$dir = opendir($path);
		while($file=readdir($dir))
		{
			if($file!='.' and $file!='..' and file_exists($path.$file))
			{
				$layout = new Layout(str_replace('.php','',$file),array(),false,$path);
				$layout->generate($path.$file,'cache/modules/'.$module['NAME'].'/default/vietnamese/'.$file);
			}
		}
	}
}
function update_module_help()
{
	if(URL::check(array('cmd'=>'update_help','ID')) and $module=DB::exists_id('MODULE',URL::get('id')))
	{
		$languages = DB::select_all('LANGUAGE');
		DB::query('
			SELECT 
				PAGE.*
			FROM
				PAGE,BLOCK
			WHERE
				MODULE_ID= '.$module['ID'].'
				AND PAGE_ID = PAGE.ID'
		);
		$pages = DB::fetch_all();
		$package = DB::select('PACKAGE',$module['PACKAGE_ID']);
			DB::query('
				SELECT
					NAME as ID,
					VALUE_1,VALUE_2
				FROM 
					MODULE_WORD
				WHERE
					MODULE_ID = '.$module['ID']);
			$words = DB::fetch_all();
			DB::query('
				SELECT 
					MODULE.*,
					PAGE.NAME as PAGE_NAME,
					PAGE.TITLE_1 as PAGE_TITLE_1,
					PAGE.TITLE_2 as PAGE_TITLE_2
				FROM
					MODULE,BLOCK,PAGE
				WHERE
					MODULE.PACKAGE_ID = '.$module['PACKAGE_ID'].'
					AND MODULE_ID(+) = MODULE.ID
					AND PAGE_ID = PAGE.ID'
			);
			$related_modules = DB::fetch_all();
			foreach($languages as $language)
			{
				$st = '
	 <table width="468" border="1" cellpadding=5>
		<caption align="left">
		<strong>'.(($language['ID']==1)?'Thông tin chung':'General information').'
		</strong>
		</caption>
	  <tr>
		<td width="139"><strong>'.(($language['ID']==1)?'Tên module':'Module name').'</strong></td>
		<td width="313">'.$module['TITLE_'.$language['ID']].'</td>
	  </tr>
	  <tr>
		<td><strong>'.(($language['ID']==1)?'Thuộc gói':'Package').'</strong></td>
		<td>'.$package['TITLE_'.$language['ID']].'</td>
	  </tr>
	  <tr>
		<td><strong>'.(($language['ID']==1)?'Dùng trong trang':'Used in page').'</strong></td>
		<td>';
				$first = true;
				foreach($pages as $page)
				{
					if($first)
					{
						$first = false;
					}
					else
					{
						$st.= ', ';
					}
					$st .= '<a href="?page='.$page['NAME'].'">'.$page['TITLE_'.$language['ID']].'</a>';
				}
				$st.=
		'</td>
	  </tr>
	</table>
	<p> </p>
	<table width="471" border="1" cellpadding=5>
		<caption align="left">
		<strong>'.(($language['ID']==1)?'Các form':'Forms').'
		</strong>
		</caption>
	  <tr>
		<td width="139"><strong>'.(($language['ID']==1)?'Tên form':'Form name').'</strong></td>
		<td width="316"><strong>'.(($language['ID']==1)?'Ý nghĩa':'Description').'</strong></td>
	  </tr>
	  '.(isset($words['LIST_TITLE'])?'
	  <tr>
		<td>'.$words['LIST_TITLE']['VALUE_'.$language['ID']].'</td>
		<td> </td>
	  </tr>':'').
	  (isset($words['ADD_TITLE'])?'
	  <tr>
		<td>'.$words['ADD_TITLE']['VALUE_'.$language['ID']].'</td>
		<td> </td>
	  </tr>':'').
	  (isset($words['EDIT_TITLE'])?'
	  <tr>
		<td>'.$words['EDIT_TITLE']['VALUE'.$language['ID']].'</td>
		<td> </td>
	  </tr>':'').
	  (isset($words['DELETE_TITLE'])?'
	  <tr>
		<td>'.$words['DELETE_TITLE']['VALUE'.$language['ID']].'</td>
		<td> </td>
	  </tr>':'').
	  (isset($words['DELETE_SELECTED'])?'
	  <tr>
		<td>'.$words['DELETE_SELECTED']['VALUE'.$language['ID']].'</td>
		<td> </td>
	  </tr>':'').'
	</table>
	<p> </p>
	<table width="471" border="1" cellpadding=5>
	  <caption align="left">
	  <strong>'.(($language['ID']==1)?'Các module liên quan':'Related modules').'</strong>
	  </caption>
	  <tr>
		<td width="139"><strong>'.(($language['ID']==1)?'Tên module':'Name').'</strong></td>
		<td width="316"><strong>'.(($language['ID']==1)?'Tên trang':'Page').'</strong></td>
	  </tr>';
			  foreach($related_modules as $related_module)
			  {
				$st.='
	  <tr>
		<td><a href="<?php echo URL::build(\'help\');?>&id='.$related_module['ID'].'">'.$related_module['TITLE_'.$language['ID']].'</a></td>
		<td><a href="?page='.$related_module['PAGE_NAME'].'">'.$related_module['PAGE_TITLE_'.$language['ID']].'</a></td>
	  </tr>';
			  }
			  $st .= '
	</table>
	<p> </p>
	<table width="473" border="1" cellpadding=5>
		<caption align="left">
		<strong>'.(($language['ID']==1)?'Các trường nhập liệu':'Input fields').'</strong>
		</caption>
	  <tr>
		<td width="139"><strong>'.(($language['ID']==1)?'Tên trường':'Name').'</strong></td>
		<td width="139"><strong>'.(($language['ID']==1)?'Ý nghĩa':'Description').'</strong></td>
	  </tr>';
			  foreach($words as $key=>$word)
			  {
				if(strpos($key,'invalid')===false and $key!='object_not_exists'
				 and $key!='search' and strpos($key,'admin')===false and $key!='add' and $key!='edit' and $key!='delete'
				 and $key!='delete_selected' and $key!='add_item' and $key!='add_title' and $key!='edit_title' and $key!='list_title'
				 and $key!='list' and $key!='detail_title' and strpos($key,'confirm')===false)
				 {
					$st.='
	  <tr>
		<td>'.$word['VALUE_'.$language['ID']].'</td>
		<td> </td>
	  </tr>';
				}
				}
				$st.='
	</table>';
			DB::update('MODULE', array(
				'DESCRIPTION_'.$language['ID']=>$st
				),'ID='.$module['ID']
			);
		}
	}
}

function update_module_table()
{
	
	$modules = DB::fetch_all('
		SELECT 
			MODULE.* 
		FROM 
			MODULE ,PACKAGE
		WHERE 
			PACKAGE_ID = PACKAGE.ID AND 			
			'.IDStructure::child_cond(DB::fetch('SELECT STRUCTURE_ID FROM PACKAGE WHERE ID=\''.URL::get('package_id',1).'\'','STRUCTURE_ID'))
						
	);
	require_once 'packages/core/includes/portal/package.php';
	foreach($modules as $module)
	{
		$path = get_package_path($module['PACKAGE_ID']).'modules/'.$module['NAME'].'/forms';
		$is_service = false;
		if(@($dir = opendir($path)))
		{
			$tables = array();
			while($file=readdir($dir))
			{
				if($file!='.' and $file!='..' and file_exists($path.'/'.$file))
				{
					$content = file_get_contents($path.'/'.$file);
					if(preg_match_all('/select\(\'(\w+)\'/',$content,$found_tables))
					{
						foreach($found_tables[1] as $table)
						{
							$tables[$table] = 0;
						}
					}
					if(preg_match_all('/select\(PORTAL_PREFIX.\'(\w+)\'/',$content,$found_tables))
					{
						foreach($found_tables[1] as $table)
						{
							$tables[$table] = 1;
						}
						$is_service = true;
					}
				}
			}
			$old_tables = DB::fetch_all('SELECT MODULE_TABLE as ID FROM MODULE_TABLE WHERE MODULE_ID=\''.$module['ID'].'\'');
			foreach($tables as $table=>$table_is_service)
			{
				if(!isset($old_tables[$table]))
				{
					DB::insert('MODULE_TABLE',array('MODULE_ID'=>$module['ID'],'MODULE_TABLE'=>$table,'MULTI_SITE'=>$table_is_service));
				}
				else
				if($table_is_service)
				{
					DB::update('MODULE_TABLE',array('MULTI_SITE'=>1),'MODULE_ID=\''.$module['ID'].'\' and MODULE_TABLE=\''.$table.'\'');
				}
			}
			if($is_service)
			{
				DB::update_id('MODULE',array('TYPE'=>'SERVICE'),$module['ID']);
			}
		}
	}
}
?>