<?php
class AutoReadKeyForm extends Form
{
	function AutoReadKeyForm()
	{
		Form::Form('AutoReadKeyForm');
        $this->link_js('packages/hotel/packages/reception/modules/ManagerKeyOrbita/door_ids.js');
        $this->link_js('packages/hotel/packages/reception/modules/ManagerKeyOrbita/commdoor.js');
	}
    
	function draw()
	{
        if(isset($_REQUEST['go']) and isset($_REQUEST['reception_id']))
        {
            $arr_recep = explode("_",$_REQUEST['reception_id']);
            $reception_id = $arr_recep[0];
            $ip_port = DB::fetch("select * from manage_ipsever where id=".$reception_id);
            
            $this->parse_layout('auto_read',array("ip"=>$ip_port['ip'],"port"=>$ip_port['port_websocket']));
        }
        else
        {
            $db_items = DB::fetch_all("select 
                                            id || '_' || ip  as id, 
                                            reception as name
                                        from manage_ipsever
                                        order by reception desc");
    		$reception_id_options = '';
            
    		foreach($db_items as $item)
    		{
                if(isset($_REQUEST['reception_id']))
                {
                    $reception_id_options .= '<option value="'.$item['id'].'">'.$item['name'].'</option>';
                }
                else
                    $reception_id_options .= '<option value="'.$item['id'].'">'.$item['name'].'</option>';
    		}
            $this->map['reception_id'] = $reception_id_options;
            $this->parse_layout('select_reception',$this->map);
        }
	}
}
?>