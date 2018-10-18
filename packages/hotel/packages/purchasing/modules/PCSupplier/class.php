<?php 
class PCSupplier extends Module
{
	public static $item = array();
	function PCSupplier($row)
	{
		Module::Module($row);
        if(User::can_view(false,ANY_CATEGORY))
        {
            switch (Url::get('cmd'))
            {
    			case 'add':
    				require_once 'forms/edit.php';
    				$this->add_form(new EditSupplierForm());
    				break;
    			case 'edit':
    				if(User::can_edit(false,ANY_CATEGORY) and Url::get('id') and PCSupplier::$item = DB::select('supplier','id = '.Url::iget('id')))
                    {
    					require_once 'forms/edit.php';
    					$this->add_form(new EditSupplierForm());
    				}
                    else
    					Url::access_denied();
    				break;
                case 'import':
    				if(User::can_add(false,ANY_CATEGORY))
                    {
    					require_once 'forms/import.php';
    					$this->add_form(new ImportSupplierForm());
    				}
                    else
    					Url::access_denied();
    				break;    
    			case 'delete':
                    if(!DB::exists('select id from pc_order where pc_order.pc_supplier_id='.Url::iget('id')))
                    {
                        DB::delete('supplier','id = '.Url::iget('id'));
                        DB::delete('pc_sup_price','supplier_id = '.Url::iget('id'));
                    }
                    Url::redirect_current();
    				break;
                //Oanh add
                case 'delete_group':
                if(Url::get('id_select'))
                {
                    $id_select=explode(',',Url::get('id_select'));
                    foreach($id_select  as $key)
                    {
                        if(!DB::exists('select id from pc_order where pc_order.pc_supplier_id='.$key))
                        {
                           DB::delete('supplier','id='.$key.'');
                           DB::delete('pc_sup_price','supplier_id = '.$key);
                        }  
                    }
                }
                Url::redirect_current(); 
    			break;
                //end Oanh
                default:
					require_once 'forms/list.php';
					$this->add_form(new ListSupplierForm());
    				break;
    		}
        }
        else
            Url::access_denied();
		
	}	
}
?>
