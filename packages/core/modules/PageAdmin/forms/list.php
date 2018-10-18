<?php
class ListPageAdminForm extends Form
{
	function ListPageAdminForm()
	{
		Form::Form('ListPageAdminForm');
		$this->link_css(Portal::template('core').'/css/admin.css');
		$this->link_css(Portal::template('core').'/css/category.css');
	}
	function on_submit()
	{
		if(URL::get('confirm'))
		{
			require_once 'detail.php';
			foreach(URL::get('selected_ids') as $id)
			{
				PageAdminForm::delete($this,$id);
				if($this->is_error())
				{
					return;
				}
			}
			Url::redirect_current(array('portal_id','package_id'=>isset($_GET['package_id'])?$_GET['package_id']:'',  
	'name'=>isset($_GET['name'])?$_GET['name']:'',    
	  ));
		}
	}
	function draw()
	{
		$languages = DB::select_all('LANGUAGE');
		$cond = ' 1>0'
				.(URL::get('package_id')?'
					and '.IDStructure::child_cond(DB::fetch('SELECT STRUCTURE_ID FROM PACKAGE WHERE ID=\''. URL::get('package_id',15).'\'','structure_id'),false,'package.').'
				':'')  
				.(URL::get('name')?' and PAGE.NAME LIKE \'%'.URL::get('name').'%\'':'')    
			.((URL::get('cmd')=='delete' and is_array(URL::get('selected_ids')))?' and PAGE.ID in ('.join(URL::get('selected_ids'),',').')':'')
			.(URL::get('portal_id')?
				((URL::get('portal_id')=='default')?' and PARAMS not like \'portal=%\'':
				' and PARAMS like \'PORTAL='.URL::get('portal_id').'%\''):'')
		;
		$item_per_page =30;
		$sql = '
			SELECT 
				count(*) as total
			FROM
				PAGE,PACKAGE
			WHERE '.$cond.'
				AND PACKAGE.ID(+)=PAGE.package_id
		';
		$count = DB::fetch($sql,'total');
		require_once 'packages/core/includes/utils/paging.php';
		$paging = paging($count,$item_per_page);
		$sql = 
		'SELECT * FROM
		(
		  SELECT 
		   PAGE.ID,
		   PAGE.NAME,
		   PAGE.READ_ONLY,
		   PAGE.SHOW, 
		   PAGE.CACHABLE,
		   PAGE.CACHE_PARAM,
		   PAGE.PARAMS,
		   PAGE.TITLE_'.Portal::language().' as TITLE,
		   PAGE.DESCRIPTION_'.Portal::language().' as DESCRIPTION,
		   PACKAGE.NAME as package_id,
		   count(PAGE.NAME)-1 as is_sibling,
		   count(*) as TOTAL,
		   ROWNUM as rownumber
		  FROM 
			PAGE,PACKAGE
		  WHERE 
		   '.$cond.'
		   AND PAGE.package_id=PACKAGE.ID
		  GROUP BY
		   PAGE.NAME,
		   PAGE.ID,
		   PAGE.READ_ONLY,
		   PAGE.SHOW, 
		   PAGE.CACHABLE,
		   PAGE.CACHE_PARAM,
		   PAGE.PARAMS,
		   PAGE.TITLE_'.Portal::language().',
		   PAGE.DESCRIPTION_'.Portal::language().',
		   PACKAGE.NAME,
		   ROWNUM
 			'.(URL::get('order_by')?' ORDER BY '.URL::get('order_by').(URL::get('order_dir')?' '.URL::get('order_dir'):''):' ORDER BY  PAGE.NAME').'
		)
		WHERE
			 rownumber > '.((page_no()-1)*$item_per_page).' and rownumber<='.((page_no())*$item_per_page).'
		';
		DB::query($sql);				
		$items = DB::fetch_all();		
		foreach($items as $id=>$item)
		{
			if($item['is_sibling'])
			{
				$items[$id]['href'] = Url::build_current(array('cmd'=>'list_sibling','package_id','name'=>$item['NAME']));
			}
			else
			{
				$items[$id]['href'] = Url::build('edit_page',array('id'=>$item['id']));
			}
		}
		DB::query('
			SELECT
				ID,
				NAME
				,STRUCTURE_ID
			FROM
				PACKAGE
			ORDER BY
				STRUCTURE_ID');
		$packages = DB::fetch_all();
		require_once 'packages/core/includes/utils/category.php';
		category_indent($packages);
		$i=1;
		foreach ($items as $key=>$value)
		{
			$items[$key]['i']=$i++;
		}
		$just_edited_id['just_edited_ids'] = array();
		if (UrL::get('selected_ids'))
		{
			if(is_string(UrL::get('selected_ids')))
			{
				if (strstr(UrL::get('selected_ids'),','))
				{
					$just_edited_id['just_edited_ids']=explode(',',UrL::get('selected_ids'));
				}
				else
				{
					$just_edited_id['just_edited_ids']=array('0'=>UrL::get('selected_ids'));
				}
			}
		}
		$this->parse_layout('list',$just_edited_id+
			array(
				'items'=>$items,
				'paging'=>$paging,
				'packages'=>$packages,  
			)
		);
	}
}
?>