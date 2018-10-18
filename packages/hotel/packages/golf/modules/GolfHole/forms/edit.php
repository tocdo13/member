<?php
class GolfHoleForm extends Form
{
	function GolfHoleForm()
	{
		Form::Form('GolfHoleForm');
		$this->link_js('packages/core/includes/js/multi_items.js');
	}
	function on_submit()
	{	
		if(URL::get('deleted_ids'))
		{
			$ids =URL::get('deleted_ids');
            if(User::can_delete(false,ANY_CATEGORY))
                $this->delete_holes($ids);
		}
        
		if(isset($_REQUEST['mi_hole']))
		{
			foreach($_REQUEST['mi_hole'] as $key=>$record)
			{
				if($record['id'] and DB::exists_id('golf_hole',$record['id']))
				{
					$hole_id = $record['id'];
					unset($record['id']);
                    $record['portal_id'] = PORTAL_ID;
                    DB::update('golf_hole',$record,'id=\''.$hole_id.'\'');
				}
				else
				{
				    unset($record['id']);
                    $record['portal_id'] = PORTAL_ID;
                    $id = DB::insert('golf_hole',$record);
				}
			}
		}
		Url::redirect_current();
		
	}	
	function draw()
	{	   
        $module_id = module::$current->data['module_id'];
        $page_id = Module::$current->data['page_id'];
        $page_name = DB::fetch("SELECT * FROM page WHERE id=".$page_id,'name');
        
        $structure_id = DB::fetch('SELECT * FROM category WHERE module_id='.$module_id.' AND url=\'?page='.$page_name.'\'','structure_id');

        $this->map = array();
        $this->map['module_id'] = $module_id;
        $this->map['structure_id']= $structure_id;
		if(!isset($_REQUEST['mi_hole']))
		{
			$sql="SELECT * 
                    FROM golf_hole
                    WHERE portal_id='".PORTAL_ID."'
                    ORDER BY id ";
            $holes = DB::fetch_all($sql);
			$_REQUEST['mi_hole'] = $holes;
		}		
		$this->parse_layout('edit',$this->map);
	}
	function delete_holes($ids)
    {
        DB::delete('golf_package_rate','golf_hole_id in ('.$ids.')');
        DB::delete('golf_rent_rate','golf_hole_id in ('.$ids.')');
		DB::delete('golf_hole','id in ('.$ids.')');
	}
}
?>