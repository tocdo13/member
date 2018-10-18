<?php 
class RevenAndExpenGroup extends module{
    function RevenAndExpenGroup($row){
        Module::Module($row);
        if(User::can_view(false,ANY_CATEGORY)){
            require_once 'forms/report.php';
            $this->add_form(new RevenAndExpenGroupForm());
        }else{
            Url::access_denied();
        }
    }
}
?>