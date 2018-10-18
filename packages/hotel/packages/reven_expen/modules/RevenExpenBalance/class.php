<?php 
class RevenExpenBalance extends module{
    function RevenExpenBalance($row){
        Module::Module($row);
        if(User::can_view(false,ANY_CATEGORY)){
            require_once 'forms/report.php';
            $this->add_form(new RevenExpenBalanceForm());
        }else{
            Url::access_denied();
        }
    }
}
?>