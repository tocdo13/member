<?php 
class PackageService extends Module
{
	public static $item = array();
	function PackageService($row)
	{
	   
		Module::Module($row);
		switch (Url::get('cmd')){
			case 'add':
                
				if(User::can_add(false,ANY_CATEGORY)){//
					require_once 'forms/edit.php';
					$this->add_form(new EditExtraServiceInvoiceForm());
				}else{
					Url::access_denied();
				}
				break;
			case 'edit':
				if(User::can_edit(false,ANY_CATEGORY) and Url::get('id')){
					require_once 'forms/edit.php';
					$this->add_form(new EditExtraServiceInvoiceForm());
				}else{
					Url::access_denied();
				}
				break;
            case 'delete': 
                   if (Url::get('delete_ids')){
                    $this->delete(Url::get('delete_ids'));    
                        Url::redirect_current();
                    } else {
                        Url::redirect_current();
                    }
                    break;
			default:
				if(User::can_view(false,ANY_CATEGORY)){
					require_once 'forms/list.php';
					$this->add_form(new ListExtraServiceInvoiceForm());
				}else{
					Url::access_denied();
				}
				break;
		}
	}
    
    	function delete($id){
            DB::delete('package_service','id in ('.$id.')');
	   }		
}
?>
