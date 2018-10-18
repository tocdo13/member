<?php 
class ArrivalReport extends module{
    function ArrivalReport($row){
        Module::Module($row);
        if(User::can_view()){
            require_once 'db.php';
            require_once 'forms/report.php';
            $this->add_form(new ArrivalReportForm());
        }else{
            Url::access_denied();
        }
    }
}
?>