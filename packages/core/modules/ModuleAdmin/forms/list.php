<?php
class ListModuleAdminForm extends Form
{
	function ListModuleAdminForm()
	{
		Form::Form('ListModuleAdminForm');
		$this->link_css(Portal::template('core').'/css/admin.css');
		$this->link_css(Portal::template('core').'/css/category.css');
	}
	function on_submit()
	{
		if(URL::get('confirm'))
		{
			foreach(URL::get('selected_ids') as $id)
			{
			}
			require_once 'detail.php';
			foreach(URL::get('selected_ids') as $id)
			{
				ModuleAdminForm::delete($this,$id);
				if($this->is_error())
				{
					return;
				}
			}
			Url::redirect_current(array('package_id'=>isset($_GET['package_id'])?$_GET['package_id']:'', 
	'name'=>isset($_GET['name'])?$_GET['name']:'',  
	  ));
		}
	}
	function draw()
	{
		//System::debug(DB::fetch('SELECT STRUCTURE_ID FROM PACKAGE WHERE ID=\''. URL::get('package_id',1).'\'','STRUCTURE_ID'));exit();
		$languages = DB::select_all('LANGUAGE');
		$cond = ' 1 >0 '
			.(!URL::get('type')?'':' and MODULE.TYPE=\''.URL::get('type').'\'')
			.(URL::get('package_id')?'
					and '.IDStructure::child_cond(DB::structure_id('package',intval(Url::get('package_id',1))),false,'PACKAGE.').' ':'') 
			.(URL::get('name')?' and MODULE.NAME LIKE \'%'.URL::get('name').'%\'':'')  
			.((URL::get('cmd')=='delete' and is_array(URL::get('selected_ids')))?' and MODULE.ID in ('.join(URL::get('selected_ids'),',').')':'')
		;
		$item_per_page = 50;
		DB::query('
			SELECT 
				count(*) AS ACOUNT
			FROM 
				MODULE,PACKAGE
			WHERE
			 '.$cond.'
			'.(URL::get('order_by')?'ORDER BY '.URL::get('order_by').(URL::get('order_dir')?' '.URL::get('order_dir'):''):'').'
			AND  PACKAGE.ID (+)= MODULE.PACKAGE_ID
		');
		$count = DB::fetch();
		require_once 'packages/core/includes/utils/paging.php';
		$paging = paging($count['acount'],$item_per_page);
		DB::query('
		   select * from 
		   (
			select 
				module.id
				,module.name ,module.use_dblclick 
				,module.title_'.Portal::language().' as title ,module.description_'.Portal::language().' as description
				,package.name as package_id 
				 ,ROWNUM as rownumber
			from 
			 	module,package
			where 
				'.$cond.'
				and  package.id(+)=module.package_id 
			'.(URL::get('order_by')?'order by '.URL::get('order_by').(URL::get('order_dir')?' '.URL::get('order_dir'):''):' order by module.id desc').'			
		) 
		where 
			 rownumber > '.((page_no()-1)*$item_per_page).' and rownumber<='.((page_no())*$item_per_page).'
		');
		$items = DB::fetch_all();
		DB::query('
			select
				id,name as name
				,structure_id
			from
				package
			order by 
				structure_id');
		$packages = DB::fetch_all();
		require_once 'packages/core/includes/utils/category.php';
		category_indent($packages);
		$i=1;
		foreach ($items as $key=>$value)
		{
			$items[$key]['i']=$i++;
			if (Url::check('page_id'))
			{
				$items[$key]['href']=Url::build('edit_page',array('module_id'=>$value['id'],'id'=>$_REQUEST['page_id'],'region','after','replace','href','container_id'));
			}
			else 
			{
				$items[$key]['href']=Url::build_current(array('cmd'=>'edit','package_id','name','id'=>$value['id']));
			}
			$items[$key]['page_name'] = $item = DB::fetch('
				select 
					page.name 
				from 
					block,page 
				where 
					module_id=\''.$items[$key]['id'].'\'
					and page.id = block.page_id
				','name');
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