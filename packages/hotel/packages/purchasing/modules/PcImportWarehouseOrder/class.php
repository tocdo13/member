<?php 
class PcImportWarehouseOrder extends Module
{
	function PcImportWarehouseOrder($row)
	{
		Module::Module($row);
		switch (Url::get('cmd'))
        {
			case 'add':
				if(User::can_add(false,ANY_CATEGORY))
                {
                    require_once 'forms/edit.php';
					$this->add_form(new EditPcImportWarehouseOrderForm());
				}
                else
					Url::access_denied();
				break;
            case 'edit':
				if(User::can_edit(false,ANY_CATEGORY))
                {
                    require_once 'forms/edit.php';
					$this->add_form(new EditPcImportWarehouseOrderForm());
				}
                else
					Url::access_denied();
				break;
            case 'view_order':
				if(User::can_view(false,ANY_CATEGORY))
                {
                    require_once 'forms/view_order.php';
					$this->add_form(new ViewOrderPcImportWarehouseOrderForm());
				}
                else
					Url::access_denied();
				break;
            case 'view_handover':
				if(User::can_view(false,ANY_CATEGORY))
                {
                    require_once 'forms/view_handover.php';
					$this->add_form(new ViewHandoverPcImportWarehouseOrderForm());
				}
                else
					Url::access_denied();
				break;
			default:
				if(User::can_view(false,ANY_CATEGORY))
                {
					require_once 'forms/list.php';
					$this->add_form(new ListPcImportWarehouseOrderForm());
				}
                else
					Url::access_denied();
				break;
		}
	}	
}
?>