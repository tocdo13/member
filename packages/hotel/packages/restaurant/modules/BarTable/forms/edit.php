<?php
class BarTableForm extends Form
{
	function BarTableForm()
	{
		Form::Form('BarTableForm');
		$this->add('bar_table.code',new TextType(true,'miss_code',0,255));
		$this->add('bar_table.name',new TextType(true,'miss_name',0,255));
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
					DB::delete('bar_table','id=\''.$id.'\'');
				}
			}
			if(isset($_REQUEST['bar_table']))
			{				
				foreach($_REQUEST['bar_table'] as $key=>$record)
				{
					$record['portal_id'] = PORTAL_ID;
					if($record['id'] and DB::exists_id('bar_table',$record['id']))
					{
						$bar_table_id = $record['id'];
						unset($record['id']);
						DB::update('bar_table',$record,'id=\''.$bar_table_id.'\'');
					}
					else
					{
						unset($record['id']);
						$id = DB::insert('bar_table',$record);
					}
				}
			}
			Url::redirect_current();
		}
	}	
	function draw()
	{
		$this->map = array();
		$bars = DB::fetch_all('select id,name from bar where portal_id=\''.PORTAL_ID.'\' order by name');
		$bar_options = '';
		foreach($bars as $key=>$value){
			$bar_options .= '<option value="'.$key.'">'.$value['name'].'</option>';
		}
		$this->map['bar_options'] = $bar_options;
        $bar_areas = DB::fetch_all('SELECT bar_area.* FROM bar_area inner join bar on bar_area.bar_id=bar.id WHERE bar.portal_id=\''.PORTAL_ID.'\'');
		$bar_area_option = '';
        foreach($bar_areas as $k=>$v)
        {
            $bar_area_option .='<option value="'.$v['id'].'">'.$v['name'].'</option>';
        }
        $this->map['bar_area_options'] = $bar_area_option;
        if(!isset($_REQUEST['bar_table']))
		{
			$cond = ' 1=1 ';
			$sql = '
				SELECT
					bar_table.*
				FROM
					bar_table
				WHERE
					bar_table.portal_id = \''.PORTAL_ID.'\'
				ORDER BY
					bar_table.bar_id
				';
			$bar_table = DB::fetch_all($sql);
            
			foreach($bar_table as $key=>$value){
			     if($bar_table[$key]['bar_area_id'] =='')
                 {
    		          foreach($bar_areas as $k1=>$v1)
                      {
                        if($v1['name']==$value['table_group'] AND $v1['bar_id']==$value['bar_id'])
                        {
                            $bar_table[$key]['bar_area_id'] = $v1['id'];
                        }
                      }
                 }
			}
			$_REQUEST['bar_table'] = $bar_table;
		}
		$this->parse_layout('edit',$this->map);
	}
}
?>