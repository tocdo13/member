<?php
class CommdoorForm extends Form
{
	function CommdoorForm()
	{
		Form::Form('CommdoorForm');
        $this->link_js('packages/core/includes/js/multi_items.js');
	}	
    function on_submit()
	{
	   if(URL::get('deleted_ids'))
	   {
		  
			$ids = explode(',',URL::get('deleted_ids'));
			foreach($ids as $id)
			{
                DB::delete('manage_commdoor','id=\''.$id.'\'');
			}
	   }
       //lay ra cac thong tin can thiet    
       if(isset($_REQUEST['mi_group']))
	   {
			foreach($_REQUEST['mi_group'] as $key=>$record)
			{
				if($record['id_data'] and DB::exists_id('manage_commdoor',$record['id_data']))
				{
					$id = $record['id_data'];
                    
					unset($record['id']);
                    unset($record['id_data']);
					DB::update('manage_commdoor',$record,'id=\''.$id.'\'');
				}
				else
				{
					unset($record['id']);
                    unset($record['id_data']);
					DB::insert('manage_commdoor',$record);
				}
			}
            $str = " var commdoor_js=";
    		$str.= String::array2js($_REQUEST['mi_group']);
    		$str.= '';
    		$f = fopen('packages/hotel/packages/reception/modules/ManagerKeyOrbita/commdoor.js','w+');
    		fwrite($f,$str);
    		fclose($f);
		}
        
        echo '<div id="progress"><img src="packages/core/skins/default/images/updating.gif" /> Updating to server...</div>';
        echo '<script>
            window.setTimeout("location=\''.URL::build_current(array('cmd'=>'commdoor')).'\'",2000);
            </script>';
        exit();
        
	}
	function draw()
	{
        //doc thong tin trong bang Manage_IPSever
        $result = DB::fetch_all("select stt as id, id as id_data, stt, name from manage_commdoor order by stt");
        for($i =1; $i <=8; $i++)
        {
            if(!isset($result[$i]))
            {
                $_REQUEST['mi_group'][$i] = array("id"=>$i, "id_data"=>"","stt"=>$i,"name"=>"");
            }
            else
            {
                $_REQUEST['mi_group'][$i] = $result[$i];
            }
        }
        
        $this->parse_layout('commdoor',array());
	}
}
?>