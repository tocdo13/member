<?php
class KaraokeForm extends Form{
	function KaraokeForm()
    {
		Form::Form('KaraokeForm');
    	$this->add('karaoke.code',new TextType(true,'code',0,255));
		$this->add('karaoke.name',new TextType(true,'name',0,255));
		$this->link_js('packages/core/includes/js/multi_items.js');
        require_once 'packages/hotel/includes/php/module.php';
	}
	function check_karaoke($name=false,$code=false,$cond ='1=1')
    {
		$kt=false;
		if($code)
        {
			if(DB::exists('select * from karaoke where karaoke.code =\''.$code.'\' AND '.$cond.' and portal_id = \''.PORTAL_ID.'\''))
            {
				$this->error('code',Portal::language('dupplicated_code').' : '.$code,false);
				$kt=true;
            }
		}
		if($name)
        {
			if(DB::exists('select * from karaoke where karaoke.name =\''.$name.'\' AND '.$cond.' and portal_id = \''.PORTAL_ID.'\''))
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
					//DB::delete('karaoke','id=\''.$id.'\'');
					DB::delete('karaoke_table','karaoke_id=\''.$id.'\'');
                    delete_module('karaoke',$id);
				}
			}
			if(isset($_REQUEST['mi_karaoke']))
            {
                //System::debug($_REQUEST['mi_karaoke']);
                //exit();
				foreach($_REQUEST['mi_karaoke'] as $key=>$record)
                {
					if($record['id'] and DB::exists_id('karaoke',$record['id']))
                    {
						//$module_name = $this->updateModule($record['name']);
                        $module_name = update_module('KARAOKE',$record['code'],$record['name'],'karaoke','KB karaoke');
						$karaoke_id = $record['id'];
                        unset($record['no']);
						$record['code'] =strtoupper($record['code']);
						$record['privilege'] = $module_name;
                        $record['warehouse_id'] = DB::fetch('Select warehouse_id from portal_department where department_code = \''.$record['department_id'].'\' and portal_id = \''.PORTAL_ID.'\' ','warehouse_id');
						if(!isset($record['full_rate'])){
							$record['full_rate'] = 0;	
						}
						if(!isset($record['full_charge'])){
							$record['full_charge'] = 0;	
						}
						$record['portal_id'] = PORTAL_ID;
                        if(!$this->check_karaoke($record['name'],$record['code'],'1=1 AND id <>'.$karaoke_id))
                        {
                            DB::update('karaoke',$record,'id=\''.$karaoke_id.'\'');
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
                        $module_name = update_module('KARAOKE',$record['code'],$record['name'],'karaoke','KB karaoke');
						$record['code'] =strtoupper($record['code']);
						$record['privilege'] = $module_name;
                        $record['warehouse_id'] = DB::fetch('Select warehouse_id from department where code = \''.$record['department_id'].'\' ','warehouse_id');
						$record['portal_id'] = PORTAL_ID;
     					if(!$this->check_karaoke($record['name'],$record['code'],'1=1'))
                        {
						   $id = DB::insert('karaoke',$record);
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
		if(!isset($_REQUEST['mi_karaoke']))
        {
			$cond = ' 1>0 ';
			$sql = '
				SELECT
					karaoke.*
				FROM
					karaoke where portal_id=\''.PORTAL_ID.'\' order by karaoke.id';
			$karaokes = DB::fetch_all($sql);
			//System::Debug($karaokes);
			$i=1;
			foreach($karaokes as $key => $value)
            {
				$karaokes[$key]['no'] = $i;
				$i++;
			}
			$_REQUEST['mi_karaoke'] = $karaokes;
		}
        $department_id = DB::fetch('select id from department where code=\'KARAOKE\'','id');
        $departments = DB::fetch_all('
            SELECT
                code as id,name_'.Portal::language().' as name
			FROM
                department
            WHERE
                (department.parent_id='.$department_id.' OR department.code=\'KARAOKE\')
			ORDER BY
                department.code
			'
		);		
		$department_id_options = '';
		foreach($departments as $k => $item)
		{
			$department_id_options .= '<option value="'.$item['id'].'">'.$item['name'].'</option>';
		} 	
        //System::debug($_REQUEST);	
        //list area
        $db_items = DB::select_all('area_group','portal_id = \''.PORTAL_ID.'\'','name_'.Portal::language());
		$area_options = '';
		foreach($db_items as $item)
		{
			$area_options .= '<option value="'.$item['id'].'">'.$item['name_'.Portal::language()].'</option>';
		}
        $this->map['area_options'] = $area_options;	
		$this->parse_layout('edit',$this->map + array('department_id_options'=>$department_id_options));
	}
    
    //bỏ không dùng
	function updateModule($name)
    {
		$name = trim($name);
		require_once 'packages/core/includes/utils/vn_code.php';	
		$module_name = 'KARAOKE'.str_replace('-','',convert_utf8_to_url_rewrite($name));
		if($module = DB::fetch('select id,name from module where name = \''.$module_name.'\'')){
			$module_id = $module['id'];
		}else{
			$module_id = DB::insert('module',array(
			'name'=>$module_name,
			'package_id'=>20,
			'path'=>'packages/hotel/packages/karaoke/modules/'.$module_name.'/',
			'title_1'=>$module_name));//25: package warehousing
		}
		if(!DB::exists('select id,name_1 from category where upper(name_1) = \''.strtoupper($name).'\'')){
			require_once 'packages/core/includes/system/si_database.php';
			DB::insert('category',array(
				'name_1'=>$name,
				'name_2'=>$name,
				'is_visible'=>1,
				'type'=>'MODERATOR',
				'structure_id'=>si_child('category',structure_id('category',43)),//285,330
				'portal_id'=>PORTAL_ID,
				'status'=>'SHOW',
				'name_id'=>convert_utf8_to_url_rewrite($name),
				'check_privilege'=>1,
				'group_name_1'=>'Quyền',
				'group_name_2'=>'Privilege',
				'module_id'=>$module_id
			));
		}
		return $module_name;
	}
}
?>