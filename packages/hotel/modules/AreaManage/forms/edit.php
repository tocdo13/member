<?php
class AreaManageForm extends Form{
	function AreaManageForm()
    {
		Form::Form('AreaManageForm');
		$this->add('area.name_1',new TextType(true,'name_1',0,255));
        $this->add('area.code',new TextType(true,'code',0,255));
		$this->link_js('packages/core/includes/js/multi_items.js');
	}
	function on_submit()
    {
		if($this->check())
        {		
			if(URL::get('deleted_ids'))
            {
				$ids = explode(',',URL::get('deleted_ids'));
				foreach($ids as $id)
                {
					DB::delete('area_group','id='.$id.'');
				}
			}
			if(isset($_REQUEST['mi_area']))
            {	
				foreach($_REQUEST['mi_area'] as $key=>$record)
                {
                    $record['name_1'] = strtoupper($record['name_1']);
                    $record['name_2'] = strtoupper($record['name_2']);
                    $record['code'] = strtoupper($record['code']);
					if($record['id'] and DB::exists_id('area_group',$record['id']))
                    {
						$area_id = $record['id'];
                        unset($record['no']);
						$record['portal_id'] = PORTAL_ID;
                        DB::update('area_group',$record,'id='.$area_id.'');
					}
                    else
                    {
						unset($record['no']);
						unset($record['id']);
						$record['portal_id'] = PORTAL_ID;
                        $id = DB::insert('area_group',$record);
					}
				}
			}
			Url::redirect_current();
		}
	}	
	function draw()
    {
		if(!isset($_REQUEST['mi_area']))
        {
			$cond = ' 1>0 ';
			$sql = '
				SELECT
					area_group.*
				FROM
					area_group where portal_id=\''.PORTAL_ID.'\' order by area_group.id';
			$areas = DB::fetch_all($sql);
			//System::Debug($bars);
			$i=1;
			foreach($areas as $key => $value)
            {
				$areas[$key]['no'] = $i;
				$i++;
			}
			$_REQUEST['mi_area'] = $areas;
            //System::debug($_REQUEST['mi_area']);
		}	
		$this->parse_layout('edit',array());
	}
}
?>