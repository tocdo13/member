<?php
class MaterialUseReport extends Module
{
    function MaterialUseReport($row)
    {
        Module::Module($row);
        if(User::can_view(false,ANY_CATEGORY))
        {
            require_once 'forms/report.php';
            $this->add_form(new MaterialUseReportForm);
        }
        else
        {
            Url::access_denied();
        }
    }
}

?>