<?php
class EditTicketCardTypesForm extends Form
{
	function EditTicketCardTypesForm()
	{
		Form::Form('EditTicketCardTypesForm');
		$this->link_css('skins/default/bootstrap/css/bootstrap.min.css');
        $this->link_css('packages/core/skins/default/css/jquery.autocomplete.css');		
        $this->link_js('skins/default/bootstrap/js/bootstrap.js'); 	
        $this->link_js('packages/core/includes/js/multi_items.js');
        $this->link_js('packages/core/includes/js/jquery/jquery.autocomplete.js');
        
	}
    function on_submit(){
        if(isset($_POST['submit'])){
            //System::debug($_REQUEST); exit();
              $code = trim(Url::get('code'));
              $name = trim(Url::get('name'));
              
              $area = Url::get('area');
              $hidden = Url::get('hidden')?1:0;
              $price = System::calculate_number(Url::get('price'));
              
              $area_type = Url::get('area_type') ? Url::get('area_type') : "";
              
              if(Url::get('id')){
                    $ticket_card_types_id = Url::get('id');
                    DB::update('ticket_card_types',array('code'=>$code,'name'=>$name,'price'=>$price,"hidden"=>$hidden)," id = ".$ticket_card_types_id);
                    $ids = "";
                    foreach($area as $key=>$value){
                        $ids.=$value.",";
                    }
                    if(strlen($ids)>0){
                        $ids = substr($ids,0,strlen($ids)-1);
                    }
                    DB::delete('ticket_card_types_details'," ticket_card_area_id NOT IN (".$ids.") AND ticket_card_types_id=".$ticket_card_types_id);
                    $sql = "SELECT * FROM ticket_card_types_details WHERE ticket_card_area_id IN (".$ids.") AND ticket_card_types_id=".$ticket_card_types_id;
                    $current_ticket_card_types_details = DB::fetch_all($sql);
                    
                    foreach($area as $key=>$value){
                        foreach($current_ticket_card_types_details as $k=>$v){
                            if($v['ticket_card_area_id']==$value){
                                unset($area[$key]);
                                break;                              
                            }
                        }
                    }
                    foreach($area as $key=>$value){
                        DB::insert('ticket_card_types_details',array('ticket_card_types_id'=>$ticket_card_types_id,'ticket_card_area_id'=>$value));
                    }
              }
              else{                 
                  $ticket_card_types_id = DB::insert('ticket_card_types',array('code'=>$code,'name'=>$name,'price'=>$price,'portal_id'=>PORTAL_ID,'hidden'=>$hidden,"area_type"=>$area_type));                
                  foreach($area as $key=>$value){
                    DB::insert('ticket_card_types_details',array('ticket_card_types_id'=>$ticket_card_types_id,'ticket_card_area_id'=>$value));
                  }
              }                            
        }
        Url::redirect_current(array('portal_id','area_type'));        
    }
	function draw()
	{    
		$this->map = array();
        $sql = "SELECT id, name, 0 as checked FROM ticket_card_area WHERE hide=0 ".( Url::get('area_type') ? " AND ticket_card_area.area_type='".Url::get('area_type')."'" : "" )." ORDER BY name";
        $ticket_card_area = DB::fetch_all($sql);
        $ticket_card_type_list = "";
        
        $cond_area = Url::get('area_type') ? " AND ticket_card_types.area_type='".Url::get('area_type')."'" : "";
        
        if(isset($_GET['id'])){
		  $id = $_GET['id'];
          $sql = "SELECT * FROM ticket_card_types WHERE id = ".$id." AND portal_id='".PORTAL_ID."'".$cond_area;
          $ticket_card_types = DB::fetch($sql);
          $this->map['code'] = $ticket_card_types['code'];
          $this->map['name'] = $ticket_card_types['name'];
          $this->map['price'] = $ticket_card_types['price'];
          $this->map['hidden'] = $ticket_card_types['hidden'];
          $sql = "SELECT
            *
            FROM 
            ticket_card_types_details WHERE ticket_card_types_id=".$id." ORDER BY ticket_card_types_details.id";
           $ticket_card_types_details = DB::fetch_all($sql);
           foreach($ticket_card_area as $key=>$value){
                foreach($ticket_card_types_details as $k=>$v){
                    if($v['ticket_card_area_id']==$value['id']){
                        $ticket_card_area[$key]['checked'] = 1;
                    }
                }
           }
           $sql = "SELECT * FROM ticket_card_types WHERE id!=".$id;
           $ticket_card_type_list = DB::fetch_all($sql);
           $this->map['ticket_card_type_list'] = String::array2js($ticket_card_type_list);
		}
        else{
            $sql = "SELECT * FROM ticket_card_types WHERE 1=1 ".$cond_area;
           $ticket_card_type_list = DB::fetch_all($sql);
           $this->map['ticket_card_type_list'] = String::array2js($ticket_card_type_list);
        }    
        $this->map['ticket_card_area'] = $ticket_card_area;   
       $this->parse_layout('edit',$this->map);
	}
}
?>