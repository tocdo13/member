<?php 
class ProductPrice extends Module
{
    public static $item = array();
	function ProductPrice($row)
	{
        if(Url::get('check_delete')){
            
            $check = 0;
            $bar_reservation_product=DB::fetch_all('select price_id as id from bar_reservation_product where price_id=\''.Url::get('id_check').'\'');
            if(isset($bar_reservation_product[Url::get('id_check')]))
            {
                $check = 1;
            }
            echo $check;
            exit();
        }
        require_once 'db.php';
		Module::Module($row);
		switch (Url::get('cmd'))
        {
            case 'cache':
				if(User::can_add(false,ANY_CATEGORY))
                {
                    ProductPriceDB::export_cache();
                    Url::redirect_current(array('portal_id'=>Url::get('portal_id')));
                }
					
                else
					Url::access_denied();
				break;
			
            case 'add':
				if(User::can_add(false,ANY_CATEGORY))
                {
					require_once 'forms/edit.php';
					$this->add_form(new EditProductPriceForm());
				}
                else
					Url::access_denied();
				break;
                
            case 'import':
				if(User::can_add(false,ANY_CATEGORY))
                {
					require_once 'forms/import.php';
					$this->add_form(new ImportProductPriceForm());
				}
                else
					Url::access_denied();
				break;
			
            case 'edit':
				if(User::can_edit(false,ANY_CATEGORY))
                {
					require_once 'forms/edit.php';
					$this->add_form(new EditProductPriceForm());
				}
                else
                    Url::access_denied();
				break;
                
            case 'delete':
				if(User::can_delete(false,ANY_CATEGORY))
                {
					$this->delete_cmd();
				}
                else
                    Url::access_denied();
				break;
                
            case 'copy':
				if(User::can_add(false,ANY_CATEGORY))
                {
					require_once 'forms/copy.php';
					$this->add_form(new CopyProductPriceForm());
				}
                else
					Url::access_denied();
				break;
            
            default:
				if(User::can_view(false,ANY_CATEGORY))
                {
					require_once 'forms/list.php';
					$this->add_form(new ListProductPriceForm());
				}
                else
					Url::access_denied();
				break;
		}
	}
    
    function delete_cmd()
    {
        DB::delete_id('product_price_list',Url::iget('id')) ;
        ProductPriceDB::export_cache();
        Url::redirect_current();
        
    }
}
?>