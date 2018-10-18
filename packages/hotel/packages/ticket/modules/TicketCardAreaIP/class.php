<?php 
class TicketCardAreaIP extends Module
{
	function TicketCardAreaIP($row)
	{
		Module::Module($row);
        if(User::can_view(false,ANY_CATEGORY))
        {
            switch (Url::get('cmd'))
            {
                case "edit":
                            if(isset($_GET['id']) && !empty($_GET['id'])){
                                $id = $_GET['id'];
                                $sql = "SELECT id FROM ticket_card_area WHERE id=".$id;
                                $result = DB::fetch($sql);
                                if(!empty($result) && User::can_edit(false,ANY_CATEGORY) ){
                                    require_once 'forms/edit.php';
                                    $this->add_form(new EditTicketCardAreaIPForm()); 
                                }
                                else{
                                    Url::access_denied();
                                }
                            }
                            elsE{
                                Url::redirect_current();
                            }
                            break;
                default :
                            require_once 'forms/list.php';
                            $this->add_form(new TicketCardAreaIPForm());
                            break;              
            }    
        }
	}	
}
?>