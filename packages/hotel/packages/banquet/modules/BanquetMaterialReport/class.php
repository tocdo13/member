<?php
class BanquetMaterialReport extends Module
{
    function BanquetMaterialReport($row)
    {
        Module::Module($row);
        if(User::can_view(false,ANY_CATEGORY))
        {
            require_once 'forms/report.php';
            $this->add_form(new BanquetMaterialReportForm);
        }
        else
        {
            Url::access_denied();
        }
    }
}

?>