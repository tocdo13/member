<?php
 
class EquipmentInvoiceApi extends Module
{
	function EquipmentInvoiceApi($row)
	{
        Module::Module($row);
		require_once 'packages/core/includes/system/restful_api.php';
        require_once 'packages/hotel/packages/app/modules/EquipmentInvoiceApi/api.php';
	}
}
?>