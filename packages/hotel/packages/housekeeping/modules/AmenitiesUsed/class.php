<?php 
class AmenitiesUsed extends Module
{
	function AmenitiesUsed($row)
	{
		Module::Module($row);
        
        switch(Url::get('cmd'))
        {
            case 'add':
                if(User::can_add(false,ANY_CATEGORY))
                {
					require_once 'forms/add.php';
					$this->add_form(new AddAmenitiesUsedForm());
				}
                else
					Url::access_denied();
				break;
            case 'edit':
                if(User::can_edit(false,ANY_CATEGORY))
                {
					require_once 'forms/edit.php';
					$this->add_form(new EditAmenitiesUsedForm());
				}
                else
					Url::access_denied();
				break;
            case 'detail':
                if(User::can_view(false,ANY_CATEGORY))
                {
					require_once 'forms/detail.php';
					$this->add_form(new DetailAmenitiesUsedForm());
				}
                else
					Url::access_denied();
				break;
            case 'delete':
                if(User::can_delete(false,ANY_CATEGORY))
                {
					$this->delete();
				}
                else
					Url::access_denied();
				break;
            default:
            	if(User::can_view(false,ANY_CATEGORY))
                {
					require_once 'forms/list.php';
					$this->add_form(new ListAmenitiesUsedForm());
				}
                else
					Url::access_denied();
				break;
        }
	}
    
    function delete()
    {
        if( $id = Url::iget('id') and $items = DB::exists_id( 'amenities_used', $id ) )
        {
            DB::delete_id( 'amenities_used', $id );
            DB::delete( 'amenities_used_detail', ' amenities_used_id = '.$id );
            require_once 'packages/hotel/includes/php/product.php';
            DeliveryOrders::delete_delivery_order($id,'AMENITIES');
            Url::redirect_current();
        }
        
    }
}
?>