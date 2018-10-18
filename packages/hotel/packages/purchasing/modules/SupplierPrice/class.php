<?php 
class SupplierPrice extends Module
{
	public static $item = array();
	function SupplierPrice($row)
	{
		Module::Module($row);
        if(User::can_view(false,ANY_CATEGORY))
        {
            switch (Url::get('cmd'))
            {
    			case 'add':
    			case 'edit':
                    if(User::can_add(false,ANY_CATEGORY))
                    {
                        require_once 'forms/add.php';
					   $this->add_form(new EditSupplierPriceForm());
                    }
    				break;
                case 'import':
    				if(User::can_add(false,ANY_CATEGORY))
                    {
    					require_once 'forms/import.php';
    					$this->add_form(new ImportSupplierPriceForm());
    				}
                    else
    					Url::access_denied();
    				break;  
                case 'delete':
                    
                    $this->delete_supplier(Url::get('id'));
                    require_once 'forms/list.php';
					$this->add_form(new ListSupplierPriceForm());
                    break;
                case 'delete_group':
                    if(Url::get('item_check_box'))
                        $this->delete_group(Url::get('item_check_box'));
                    require_once 'forms/list.php';
					$this->add_form(new ListSupplierPriceForm());
                    break;
                default:
                    require_once 'forms/list.php';
					$this->add_form(new ListSupplierPriceForm());
                    break;
    			
    		}
        }
        else
            Url::access_denied();
		
	}
    
    function delete_supplier($id)
    {
        DB::delete('pc_sup_price','id='.$id);
    }
    function delete_group($arr)
    {
        $str = "(";
        foreach($arr as $row)
        {
            $str .=$row.',';
        }
        $str = substr($str,0,strlen($str)-1);
        $str .=")";
        DB::delete('pc_sup_price','id in '.$str);
    }	
}
?>