<?php 
class TicketCardTypes extends Module
{
	function TicketCardTypes($row)
	{
		Module::Module($row);
        if(User::can_view(false,ANY_CATEGORY))
        {
            switch (Url::get('cmd'))
            {
                case "edit":
                            if(isset($_GET['id']) && !empty($_GET['id'])){
                                $id = $_GET['id'];
                                $sql = "SELECT id FROM ticket_card_types WHERE id=".$id;
                                $result = DB::fetch($sql);
                                if(!empty($result) && User::can_edit(false,ANY_CATEGORY) ){
                                    require_once 'forms/edit.php';
                                    $this->add_form(new EditTicketCardTypesForm()); 
                                }
                                else{
                                    Url::access_denied();
                                }
                            }
                            else{
                                require_once 'forms/edit.php';
                                $this->add_form(new EditTicketCardTypesForm()); 
                            }
                            break;
                case "delete":
                           if(isset($_GET['delete_id']) && !empty($_GET['delete_id'])){
                               $id = $_GET['delete_id'];
                                $sql = "SELECT id FROM ticket_card_types WHERE id=".$id;
                                $result = DB::fetch($sql);
                                if(!empty($result) && User::can_delete(false,ANY_CATEGORY) ){
                                    DB::delete_id('ticket_card_types',$id);
                                }
                                else{
                                    Url::access_denied();
                                }
                           }
                           require_once 'forms/list.php';
                           $this->add_form(new TicketCardTypesForm());
                           break;
                default :
                            require_once 'forms/list.php';
                            $this->add_form(new TicketCardTypesForm());
                            break;              
            }    
        }
	}	
}
?>