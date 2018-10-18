<?php
class ListModeratorForm extends Form
{
	function ListModeratorForm()
	{
		Form::Form('ListModeratorForm');
		$this->link_css('skins/default/css/cms.css');
	}
	function on_submit()
	{			
		if(URL::get('cmd')=='delete')
		{
			$this->deleted_selected_ids();
		}
		Url::redirect_current(array('user_id'));		
		
	}
	function draw()
	{		
		$this->get_just_edited_id();
		$this->get_select_condition();
		$this->get_paging();
		$items = ModeratorDB::get_items($this->cond, $this->item_per_page);
        //System::debug($items);
		$this->parse_layout('list',$this->just_edited_id+
			array(
				'items'=>$items,
				'paging'=>$this->paging,
				'total_page'=>$this->total_item,
			)
		);
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
		if(URL::get('selected_ids'))
		{
			foreach(URL::get('selected_ids') as $id)
			{
				if($privilege = DB::fetch_all('select id from privilege where account_id=\''.$id.'\''))
				{
					DB::delete('privilege','account_id=\''.$id.'\'');
					foreach($privilege as $privilege_id=>$privi)
					{
						DB::delete('privilege_module','privilege_id='.$privilege_id);
					}
					DB::delete('account_privilege','account_id=\''.$id.'\'');
					DB::update_id('account',array('cache_privilege'=>''),$id);
				}			
			}
			Url::redirect_current();
		}
	}	
	function get_paging()
	{
		if (Url::get('item_per_page'))
		{
			$this->item_per_page = Url::get('item_per_page');
		}else
		{
			$this->item_per_page = 1000;
		}
		$this->total_item = ModeratorDB::get_item_count($this->cond);
		require_once 'packages/core/includes/utils/paging.php';
		$this->paging = paging($this->total_item,$this->item_per_page,4);
	}
	function get_select_condition()
	{
		if(URL::get('user_id') and User::is_admin())
		{
			
			$this->cond = ' account_id=\''.URL::get('user_id').'\'';
		}
		else
		{
			$portal_id=Url::get('portal_id')?addslashes(Url::get('portal_id')):str_replace('#','',PORTAL_ID);
			$this->cond = '
					1 = 1 '
				.((URL::get('cmd')=='delete' and is_array(URL::get('selected_ids')))?' and privilege.id in (\''.join(URL::get('selected_ids'),'\',\'').'\')':'')
			;
		}
	}
	
}
?>