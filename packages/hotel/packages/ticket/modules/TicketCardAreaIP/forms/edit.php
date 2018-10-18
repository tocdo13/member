<?php
class EditTicketCardAreaIPForm extends Form
{
	function EditTicketCardAreaIPForm()
	{
		Form::Form('EditTicketCardAreaIPForm');
		$this->link_css('skins/default/bootstrap/css/bootstrap.min.css');
        $this->link_css('packages/core/skins/default/css/jquery.autocomplete.css');		
        $this->link_js('skins/default/bootstrap/js/bootstrap.js'); 	
        $this->link_js('packages/core/includes/js/multi_items.js');
        $this->link_js('packages/core/includes/js/jquery/jquery.autocomplete.js');
        
	}
    function on_submit(){
        if(isset($_POST['submit'])){
            //System::debug($_REQUEST); exit();
                $area_id = Url::get('id');
                
                
                foreach($_REQUEST['mi_ticket_card_area_ip'] as $key=>$value){
                    if(!empty($value['id'])){
                        DB::update("ticket_card_area_ip",array("ip"=>trim($value['ip'])),"id=".$value['id']);
                    }
                    else{
                        DB::insert("ticket_card_area_ip",array("ip"=>trim($value['ip']),'ticket_card_area_id'=>$area_id));
                    }
                }
                if(Url::get('deleted_ids') && Url::get('deleted_ids')!=""){
                    DB::delete("ticket_card_area_ip"," id IN (".Url::get('deleted_ids').")");
                }                              
        } 
        Url::redirect_current(array('portal_id','area_type'));       
    }
	function draw()
	{    
	   $this->map['ticket_card_area_ip_list'] = "";
		if(isset($_GET['id'])){
		  $id = $_GET['id'];
          $sql = "SELECT * FROM ticket_card_area WHERE id = ".$id." AND portal_id='".PORTAL_ID."'";
          $ticket_card_area = DB::fetch($sql);
          $this->map['code'] = $ticket_card_area['code'];
          $this->map['name'] = $ticket_card_area['name'];
          $sql = "SELECT
            *
            FROM 
            ticket_card_area_ip WHERE ticket_card_area_id=".$id." ORDER BY cast(REPLACE(ip,'.','') as int)";
           $result = DB::fetch_all($sql);
           $i = 1;
           foreach($result as $key=>$value){
                $result[$key]['stt'] = $i;
                $i++;
                $result["_".$key] = $result[$key];
                unset($result[$key]);
           }
           $_REQUEST['mi_ticket_card_area_ip'] = $result; 
           $sql = "SELECT id,ip,ticket_card_area_id FROM ticket_card_area_ip WHERE ticket_card_area_id!=".$id;
            $ticket_card_area_ip_list = DB::fetch_all($sql);
            $this->map['ticket_card_area_ip_list'] = String::array2js($ticket_card_area_ip_list);   
		}
        else{
            $sql = "SELECT id,ip,ticket_card_area_id FROM ticket_card_area_ip";
            $ticket_card_area_ip_list = DB::fetch_all($sql);
            $this->map['ticket_card_area_ip_list'] = String::array2js($ticket_card_area_ip_list);   
        }
       $this->parse_layout('edit',$this->map);
	}
}
?>