<?php 
class RevenAndExpenItem extends module{
    function RevenAndExpenItem($row){
        Module::Module($row);
        if(User::can_view(false,ANY_CATEGORY)){
            require_once 'forms/report.php';
            $this->add_form(new RevenAndExpenItemForm());
        }else{
            Url::access_denied();
        }
    }
}
?>