<?php
class AreaForm extends Form{
	function AreaForm()
    {
		Form::Form('AreaForm');
    	$this->add('area.code',new TextType(true,'code',0,255));
		$this->add('area.name',new TextType(true,'name',0,255));
		$this->link_js('packages/core/includes/js/multi_items.js');
        require_once 'packages/hotel/includes/php/module.php';
	}
	function check_area($name=false,$code=false,$cond ='1=1')
    {
		$kt=false;
		if($code)
        {
			if(DB::exists('select * from ticket_area where ticket_area.code =\''.$code.'\' AND '.$cond.' and portal_id = \''.PORTAL_ID.'\''))
            {
				$this->error('code',Portal::language('dupplicated_code').' : '.$code,false);
				$kt=true;
            }
		}
		if($name)
        {
			if(DB::exists('select * from ticket_area where ticket_area.name =\''.$name.'\' AND '.$cond.' and portal_id = \''.PORTAL_ID.'\''))
            {	
				$this->error('name',Portal::language('dupplicated_name').' : '.$name,false);
				$kt =true;
		    }
		}
		if($kt)
        {
			return true;
		}
		return false;
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
                    DB::delete('ticket_area_type',' ticket_area_id = '.$id);
                    delete_module('ticket_area',$id);
				}
			}
			if(isset($_REQUEST['mi_area']))
            {	
				foreach($_REQUEST['mi_area'] as $key=>$record)
                {
					if($record['id'] and DB::exists_id('ticket_area',$record['id']))
                    {
						//$module_name = $this->updateModule($record['name']);
                        $module_name = update_module('TICKET',$record['code'],$record['name'],'ticket','Danh sách quầy vé');
						$area_id = $record['id'];
                        unset($record['no']);
						$record['code'] =strtoupper($record['code']);
                        $record['printer_name'] = trim($record['printer_name']);
						$record['privilege'] = $module_name;
						$record['portal_id'] = PORTAL_ID;
                        if(!$this->check_area($record['name'],$record['code'],'1=1 AND id <>'.$area_id))
                        {
                            DB::update('ticket_area',$record,'id='.$area_id.'');
                        }
                        else
                        {
                            return;
                        }
					}
                    else
                    {
						unset($record['no']);
						unset($record['id']);
						//$module_name = $this->updateModule($record['name']);
                        $module_name = update_module('TICKET',$record['code'],$record['name'],'ticket','Danh sách quầy vé');
						$record['code'] =strtoupper($record['code']);
						$record['privilege'] = $module_name;
						$record['portal_id'] = PORTAL_ID;
     					if(!$this->check_area($record['name'],$record['code'],'1=1'))
                        {
						   $id = DB::insert('ticket_area',$record);
						}
                        else
                        {
							return;
						}
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
					ticket_area.*
				FROM
					ticket_area where portal_id=\''.PORTAL_ID.'\' order by ticket_area.id';
			$areas = DB::fetch_all($sql);
			//System::Debug($areas);
			$i=1;
			foreach($areas as $key => $value)
            {
				$areas[$key]['no'] = $i;
				$i++;
			}
			$_REQUEST['mi_area'] = $areas;
		}	
        //list area
        $db_items = DB::select_all('area_group','portal_id = \''.PORTAL_ID.'\'','name_'.Portal::language());
		$area_options = '';
		foreach($db_items as $item)
		{
			$area_options .= '<option value="'.$item['id'].'">'.$item['name_'.Portal::language()].'</option>';
		}		
		$this->map['area_options'] = $area_options;
		$this->parse_layout('edit',$this->map+array());
	}
}
?>