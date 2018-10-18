<?php 
class PackageSale extends Module
{
	function PackageSale($row)
	{
		Module::Module($row);
        if(User::can_view(false,ANY_CATEGORY))
        {
            switch (Url::get('cmd')){
    			case 'add':
					require_once 'forms/edit.php';
					$this->add_form(new EditExtraServiceInvoiceForm());
    				break;
    			case 'edit':
					require_once 'forms/edit.php';
					$this->add_form(new EditExtraServiceInvoiceForm());
    				
    				break;
                case 'delete':
                    $this->delete_package_sale(Url::get('delete_ids'));
                    Url::redirect_current();
                    break;
    			default:
					require_once 'forms/list.php';
					$this->add_form(new ListPackageSaleForm());
    				break;
    		}
        }
        else
        {
		      Url::access_denied();	
        }
	}
    
    public function delete_package_sale($id)
    {
        DB::delete('package_sale','id in ('.$id.')');
        DB::delete('package_sale_detail','package_sale_id in ('.$id.')');
    }		
}
?>
