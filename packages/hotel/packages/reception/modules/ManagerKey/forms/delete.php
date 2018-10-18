<?php
class deleteKeyForm extends Form
{
	function deleteKeyForm()
	{
		Form::Form('deleteKeyForm');
	}
	function on_submit()
	{
	   //echo '<div id="progress" style="position:fixed; top:60px; right:'.(Url::get('width')/2 - 64).'px;" ><img src="packages/core/skins/default/images/updating.gif" /> Proccessing...</div>';
	}	
	function draw()
	{
        require_once 'packages/hotel/packages/reception/modules/ManagerKey/db.php';
        if(isset($_REQUEST['delete']))
        {
            $encoder =  $_REQUEST['reception_id'];
            $str_encoder = explode("_",$encoder);
            $ip_port = DB::fetch("select * from manage_ipsever where id=".$str_encoder[0]);
            
            $result = ReadKey("3-1-000201-1009300900-1009300900-1",$ip_port['ip'],$ip_port['port']);
            //thuc hien update thong tin delete xuong csdl theo cardNo
            $this->map = array();
            
            if($result==-1)
            {
                //$this->map['result'] = -1;
            }
            else
            {
                sleep(3);
                $s = explode("-",$result);//1-1-1009300900-1009300900
                $cardNo = $s[0];
                $delete_time = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));
               // $delete_note = $_REQUEST['txtNote'];
                DB::update('manage_key',array('delete_user'=>User::id(),'delete_time'=>$delete_time),'id='.$cardNo);
                $result = 1; 
            }
            $this->load($result);
        }
        else
        {
             $this->load();
        }
        
        
	}
    function load($result=0)
    {
        $this->map = array();
        //lay ra thong tin encoder 
        $this->map['result']= $result;
        
         $db_items = DB::fetch_all("select 
                                        id || '_' || ip as id, 
                                        reception as name
                                    from manage_ipsever 
                                    order by reception desc");
		$reception_id_options = '';
		foreach($db_items as $item)
		{
			$reception_id_options .= '<option value="'.$item['id'].'">'.$item['name'].'</option>';
		}
        $this->map['reception_id'] = $reception_id_options;
        //end lay ra thong tin encoder
        
        $this->parse_layout('show_delete',$this->map);
    }
}
?>