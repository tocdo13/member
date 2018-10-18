<?php 
class KaraokeTableMap extends Module
{
	function KaraokeTableMap($row)
	{
		Module::Module($row);
		if(Url::get('karaoke_id'))
		{
			Session::set('karaoke_id',intval(Url::get('karaoke_id')));
			$sql_group='SELECT 
                        REPLACE(LOWER(FN_CONVERT_TO_VN(karaoke_table.table_group)),\' \',\'_\') as id
                        ,karaoke_table.table_group as name 
                    FROM karaoke_table 
                        INNER JOIN karaoke on karaoke.id=karaoke_table.karaoke_id 
                    WHERE karaoke_table.karaoke_id = '.Session::get('karaoke_id').' GROUP BY karaoke_table.table_group ORDER BY karaoke_table.table_group';		
			$groups = DB::fetch_all($sql_group);
			$name_group = '';
			foreach($groups as $k => $gr){
				$name_group = $gr['id'];
				break;
			}
			Session::set('group',$name_group);
		}else if(!Session::is_set('karaoke_id')){
			$karaoke = DB::fetch('select min(id) as id from karaoke where 1>0 and portal_id=\''.PORTAL_ID.'\'','id');	
			$karaoke_id = $karaoke;	
            $karaoke_code = DB::fetch('select karaoke.* from karaoke where id='.$karaoke_id.' and portal_id=\''.PORTAL_ID.'\'');	
			if($karaoke_id){
				Session::set('karaoke_id',$karaoke['id']);
			}else{
				Session::set('karaoke_id','');
			}  
		}
		$_REQUEST['karaoke_id'] = Session::get('karaoke_id');
		if(User::can_view(false,ANY_CATEGORY)){
			require_once 'forms/list.php';
			$this->add_form(new EditTableMapForm());
		}else{
			URL::access_denied();
		}
	}
}
?>