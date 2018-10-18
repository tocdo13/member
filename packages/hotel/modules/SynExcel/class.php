<?php
class SynExcel extends Module
{
	function SynExcel($row)
	{
		Module::Module($row);
		if(User::can_view(false,ANY_CATEGORY))
		{
            switch(Url::get('cmd'))
            {
                case "wh_export":
                {
                    require_once 'forms/warehouse.php';
                    $this->add_form(new SynExcelWarehouseForm());break;
                }
                case "reven":
                {
                    require_once 'forms/reven.php';
                    $this->add_form(new SynExcelRevenForm());break;
                }
                default :
                {
                    require_once 'forms/reven.php';
                    $this->add_form(new SynExcelRevenForm());break;
                }
            }
					
		}
		else
		{
			Url::access_denied();
		}	
	}
}
?>
