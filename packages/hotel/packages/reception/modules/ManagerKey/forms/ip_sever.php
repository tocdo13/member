<?php
class IpSeverForm extends Form
{
	function IpSeverForm()
	{
		Form::Form('IpSeverForm');
        $this->link_js('packages/core/includes/js/multi_items.js');
	}	
    function on_submit()
	{
	   if(URL::get('deleted_ids'))
	   {
		  
			$ids = explode(',',URL::get('deleted_ids'));
			foreach($ids as $id)
			{
                DB::delete('manage_ipsever','id=\''.$id.'\'');
			}
	   }
	   //lay ra cac thong tin can thiet 
       if(isset($_REQUEST['mi_group']))
	   {
			foreach($_REQUEST['mi_group'] as $key=>$record)
			{
				if($record['id'] and DB::exists_id('manage_ipsever',$record['id']))
				{
					$id = $record['id'];
                    
					unset($record['id']);
                    unset($record['index']);
					DB::update('manage_ipsever',$record,'id=\''.$id.'\'');
				}
				else
				{
					unset($record['id']);
                    unset($record['index']);
					DB::insert('manage_ipsever',$record);
				}
			}
		}
        echo '<div id="progress"><img src="packages/core/skins/default/images/updating.gif" /> Updating to server...</div>';
	   echo '<script>
            window.setTimeout("location=\''.URL::build_current(array('cmd'=>'ip_sever')).'\'",2000);
            </script>';
        exit();
	}
	function draw()
	{
        //doc thong tin trong bang Manage_IPSever
        $result = DB::fetch_all("select * from manage_ipsever order by id");
        $i = 1;
        foreach($result as $key=>$value)
        {
            $result[$key]['index'] = $i;
            $i++;
        }
        $_REQUEST['mi_group'] = $result;
        //System::debug($_REQUEST['mi_group']);
        $this->parse_layout('ip_sever');
	}
}
?>