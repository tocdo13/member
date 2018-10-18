<?php 
class CustomerRateCommission extends Module
{
	public static $item = array();
	function CustomerRateCommission($row)
	{
		Module::Module($row);
		switch (Url::get('cmd'))
        {
			case 'copy':
				if(User::can_add(false,ANY_CATEGORY))
                {
					if(Url::get('customer_id'))
                    {
						require_once 'forms/copy.php';
						$this->add_form(new CopyCustomerRateCommissionForm());
					}
                    else
                    {
						Url::redirect('customer');
					}
				}
                else
                {
					Url::access_denied();
				}
				break;
			case 'add':
				if(User::can_add(false,ANY_CATEGORY))
                {//false,ANY_CATEGORY
					require_once 'forms/edit.php';
					$this->add_form(new EditCustomerRateCommissionForm());
				}
                else
                {
					Url::access_denied();
				}
				break;
			case 'edit':
				if(User::can_edit(false,ANY_CATEGORY) and Url::get('customer_id') and 
                    CustomerRateCommission::$item = DB::select('customer','id = '.Url::iget('customer_id')))
                {
					require_once 'forms/edit.php';
					$this->add_form(new EditCustomerRateCommissionForm());
				}
                else
                {
					Url::access_denied();
				}
				break;
			case 'delete':
				if(User::can_delete(false,ANY_CATEGORY))
                {
					if(Url::get('id') and DB::exists('SELECT id FROM customer_rate_commission 
                                                      WHERE id = '.Url::iget('id').''))
                    {
						DB::delete('customer_rate_commission','id = '.Url::iget('id'));
						//Url::redirect('notice',array('cmd'=>'delete','href'=>'?page='.Url::get('page')));
						Url::redirect_current(array('customer_id'));
					}
					if(Url::get('item_check_box'))
                    {
						$arr = Url::get('item_check_box');	
						for($i=0;$i<sizeof($arr);$i++)
                        {
							DB::delete('customer_rate_commission','id = '.$arr[$i]);
						}
						//Url::redirect('notice',array('cmd'=>'delete','href'=>'?page='.Url::get('page')));
						Url::redirect_current(array('customer_id'));
					}
                    else
                    {
						Url::redirect_current(array('customer_id'));
					}
				}
                else
                {
					Url::access_denied();
				}
				break;
			default:
				if(User::can_add(false,ANY_CATEGORY))
                {
					if(Url::get('customer_id')){
						require_once 'forms/list.php';
						$this->add_form(new ListCustomerRateCommissionForm());
					}
                    else
                    {
						Url::redirect('customer');
					}
			    }
                else
                {
					Url::access_denied();
				}
				break;
		}
	}	
}
?>