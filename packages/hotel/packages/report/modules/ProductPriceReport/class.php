<?php 
class ProductPriceReport extends Module
{
    public static $item = array();
	function ProductPriceReport($row)
	{
        require_once 'db.php';
		Module::Module($row);
		switch (Url::get('cmd'))
        {
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
}
?>