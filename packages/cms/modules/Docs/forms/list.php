<?php
class ListPortalCategoryForm extends Form
{
	function ListPortalCategoryForm()
	{
		Form::Form('ListPortalCategoryForm');
	}
	function on_submit()
	{
		if(URL::get('confirm'))
		{ 
			$this->deleted_selected_ids();
		}else{
			$this->update();
		}
	}
	function draw()
	{
		$portal_id='#default';	
		$this->get_just_edited_id();
		$this->get_items($portal_id);
		$items=PortalCategoryDB::check_categories($this->items);
		$this->map['items'] = $items;
		$this->parse_layout('list',$this->just_edited_id+$this->map);
	}	
	function get_just_edited_id()
	{
		$this->just_edited_id['just_edited_ids'] = array();
		if (UrL::get('selected_ids'))
		{
			if(is_string(UrL::get('selected_ids')))
			{
				if (strstr(UrL::get('selected_ids'),','))
				{
					$this->just_edited_id['just_edited_ids']=explode(',',UrL::get('selected_ids'));
				}
				else
				{
					$this->just_edited_id['just_edited_ids']=array('0'=>UrL::get('selected_ids'));
				}
			}
		}
	}
	function deleted_selected_ids()
	{
		foreach(URL::get('selected_ids') as $id)
		{
		}
		require_once 'detail.php';
		foreach(URL::get('selected_ids') as $id)
		{
			if($id and $category=DB::fetch('select id,structure_id from category where id='.intval($id)) and User::can_edit(false,$category['structure_id']))
			{
				DB::delete_id('category',$id);
			}	
			if($this->is_error())
			{
				return;
			}
		}
		Url::redirect_current(Module::$current->redirect_parameters);
	}
	function get_items($portal_id)
	{
		$this->get_select_condition($portal_id);
		$items = DB::fetch_all('
			select 
				category.id 
				,category.structure_id
				,category.status
				,category.group_name_1
				,category.group_name_2				
				,category.icon_url
				,category.type 
                ,category.url
				,category.name_'.Portal::language().' as name 
				,category.description_'.Portal::language().' as description
				,category.check_privilege
				,module.name as module_name
                ,module.id as module_id
                ,doc_module.account_id
			from 
			 	category
				left outer join module on module.id = category.module_id
                left outer join doc_module on module.id = doc_module.module_id
			where
				 '.$this->cond.'
                 and category.status <> \'HIDE\'
                 and category.type = \'FUNCTION\'
			order by 
				category.structure_id
		',false);
		foreach($items as $key=>$value){
            if($value['structure_id'] == ID_ROOT)
            {
                unset($items[$key]);
                continue;
            }
            $class = "";
            $parent_id = $value['structure_id'];
            do
            {
                if($parent_id != $value['structure_id'])
                    $class .= " ".$parent_id;
                $parent_id = IDStructure::parent($parent_id);
            }
            while($parent_id);
            $items[$key]['class'] = $class;
			if($value['type']=='FUNCTION'){
				$items[$key]['type'] = '<span style="color:#000;">'.Portal::language('function').'</span>';
			}elseif($value['type']=='MODERATOR'){
				$items[$key]['type'] = '<span style="color:#F00;">'.Portal::language('use_for_moderation').'</span>';
			}else{
				$items[$key]['type'] = '<span style="color:#0F0;">'.Portal::language('use_for_help').'</span>';
			}
		}
        $this->items = $items;
		require_once 'packages/core/includes/utils/category.php';
		category_indent($this->items,false);
	}
	function get_select_condition($portal_id)
	{
		$this->cond = '
				category.type!=\'PORTAL\'' 
			.((URL::get('cmd')=='delete' and is_array(URL::get('selected_ids')))?' and category.id in (\''.join(URL::get('selected_ids'),'\',\'').'\')':'')
		;
	}
	function update(){
		foreach($_REQUEST as $key=>$value){
			if(ereg("status_",$key)){
				$arr = explode('_',$key);
				DB::update('category',array('status'=>$value),'id='.$arr[1]);
			}
			if(ereg("module_",$key)){
				$arr = explode('_',$key);
				if($module = DB::fetch('select id from module where name=\''.$value.'\'')){
					DB::update('category',array('module_id'=>$module['id']),'id='.$arr[1]);
				}
			}
			if(ereg("groupname1_",$key)){
				$arr = explode('_',$key);
				DB::update('category',array('group_name_1'=>$value),'id='.$arr[1]);
			}
			if(ereg("groupname2_",$key)){
				$arr = explode('_',$key);
				DB::update('category',array('group_name_2'=>$value),'id='.$arr[1]);
			}
		}
		Url::redirect_current();
	}
}
?>