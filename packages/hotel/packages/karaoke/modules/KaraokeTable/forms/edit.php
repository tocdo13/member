<?php
class KaraokeTableForm extends Form
{
	function KaraokeTableForm()
	{
		Form::Form('KaraokeTableForm');
		$this->add('karaoke_table.code',new TextType(true,'miss_code',0,255));
		$this->add('karaoke_table.name',new TextType(true,'miss_name',0,255));
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
					DB::delete('karaoke_table','id=\''.$id.'\'');
				}
			}
			if(isset($_REQUEST['karaoke_table']))
			{				
				foreach($_REQUEST['karaoke_table'] as $key=>$record)
				{
					$record['portal_id'] = PORTAL_ID;
					if($record['id'] and DB::exists_id('karaoke_table',$record['id']))
					{
						$karaoke_table_id = $record['id'];
						unset($record['id']);
						DB::update('karaoke_table',$record,'id=\''.$karaoke_table_id.'\'');
					}
					else
					{
						unset($record['id']);
						$id = DB::insert('karaoke_table',$record);
					}
				}
			}
			Url::redirect_current();
		}
	}	
	function draw()
	{
		$this->map = array();
		$karaokes = DB::fetch_all('select id,name from karaoke where portal_id=\''.PORTAL_ID.'\' order by name');
		$karaoke_options = '';
		foreach($karaokes as $key=>$value){
			$karaoke_options .= '<option value="'.$key.'">'.$value['name'].'</option>';
		}
		$this->map['karaoke_options'] = $karaoke_options;
		if(!isset($_REQUEST['karaoke_table']))
		{
			$cond = ' 1=1 ';
			$sql = '
				SELECT
					karaoke_table.*
				FROM
					karaoke_table
				WHERE
					karaoke_table.portal_id = \''.PORTAL_ID.'\'
				ORDER BY
					karaoke_table.karaoke_id
				';
			$karaoke_table = DB::fetch_all($sql);
			foreach($karaoke_table as $key=>$value){
			}
			$_REQUEST['karaoke_table'] = $karaoke_table;
		}
        //System::debug($_REQUEST['karaoke_table']);
        //exit();
		$this->parse_layout('edit',$this->map);
	}
}
?>