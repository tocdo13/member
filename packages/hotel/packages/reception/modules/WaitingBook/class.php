<?php 
    class WaitingBook extends Module
    {
        function WaitingBook($row){
            Module::Module($row);
            
           	if(User::can_view(false,ANY_CATEGORY))
            {
            switch(URL::get('cmd')){
                case 'add':
                   require_once 'forms/edit.php';
                   $this ->add_form(new EditWaitingBook());
                   Break;
                
                case 'edit':
                   require_once 'forms/edit.php';
                   $this ->add_form(new EditWaitingBook());
                    Break;
                case 'booking_confirm':
                    require_once 'forms/booking_cf.php';
                    $this->add_form(new BookingConfirmWaitingBook());
                    Break;
                case 'delete':
                    if(Url::get('id')){
                        $id = Url::get('id');
                        DB::delete('waiting_book','ID = '.$id);
                        DB::delete('waiting_information','waiting_book_id = '.$id);
                    }
                    if(Url::get('item_check_box'))
                    {
						$arr = Url::get('item_check_box');	
						for($i=0;$i<sizeof($arr);$i++){
							DB::delete('waiting_book','ID = '.$arr[$i]);  
                            DB::delete('waiting_information','waiting_book_id = '.$arr[$i]); 
						}
					}
                    Url::redirect_current();
                    break;    
                default:
                
                    require_once('forms/list.php');
                    $this -> add_form(new WaitingBookList());
                Break;
                
            }
        }
        else{
			URL::access_denied();
		}
    }
    
}
                   
?>
