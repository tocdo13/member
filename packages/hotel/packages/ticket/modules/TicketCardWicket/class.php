<?php 
class TicketCardWicket extends Module
{
	function TicketCardWicket($row)
	{
		Module::Module($row);
        if(User::can_view(false,ANY_CATEGORY))
        {
            switch (Url::get('cmd'))
            {
                case "edit":
                            if(isset($_GET['sales_id']) && !empty($_GET['sales_id'])){
                                $id = $_GET['sales_id'];
                                $sql = "SELECT id FROM ticket_card_sales WHERE id=".$id;
                                $result = DB::fetch($sql);
                                if(!empty($result) && User::can_edit(false,ANY_CATEGORY) ){
                                    require_once 'forms/edit.php';
                                    $this->add_form(new EditTicketCardWicketForm()); 
                                }
                                else{
                                    Url::access_denied();
                                }
                            }
                            else{
                                require_once 'forms/edit.php';
                                $this->add_form(new EditTicketCardWicketForm()); 
                            }
                            break;
                case "print":
                             if(isset($_GET['sales_id']) && !empty($_GET['sales_id'])){
                                $id = $_GET['sales_id'];
                                $sql = "SELECT id FROM ticket_card_sales WHERE id=".$id;
                                $result = DB::fetch($sql);
                                if(!empty($result) && User::can_view(false,ANY_CATEGORY) ){
                                    require_once 'forms/print.php';
                                    $this->add_form(new PrintTicketForm()); 
                                }
                                else{
                                    Url::access_denied();
                                }
                            }
                            break;           
                default :
                            require_once 'forms/list.php';
                            $this->add_form(new TicketCardWicketForm());
                            break;              
            }    
        }
	}	
}
?>