<?php
 
class MinibarInvoiceApi extends Module
{
	function MinibarInvoiceApi($row)
	{
        Module::Module($row);
		require_once 'packages/core/includes/system/restful_api.php';
        require_once 'packages/hotel/packages/app/modules/MinibarInvoiceApi/api.php';
	}
}
?>