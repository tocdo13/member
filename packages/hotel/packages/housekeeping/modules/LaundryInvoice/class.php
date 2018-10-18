<?php 
class LaundryInvoice extends Module
{
	function LaundryInvoice($row)
	{
	    /** manh: check last time **/
        if(Url::get('check_last_time')){
            $data = array('status'=>'','user'=>'','time'=>'');
            $last_time = DB::fetch('select last_time,lastest_user_id as user_id from housekeeping_invoice where id='.Url::get('id'));
            if($last_time['last_time']!=0 and $last_time['last_time']>Url::get('last_time')){
                $data = array('status'=>'error','user'=>$last_time['user_id'],'time'=>date('H:i:s d/m/Y',$last_time['last_time']));
            }
            echo json_encode($data);
            exit();
        }
        /** end manh **/
		Module::Module($row);
		require_once 'db.php';
		if(User::can_view(false,ANY_CATEGORY))
		{

			if((Url::check(array('delete_selected','selected_ids')) or(Url::check(array('confirm1','selected_ids')) )) and is_array(Url::get('selected_ids')) and sizeof(Url::get('selected_ids'))>0 and User::can_delete(false,ANY_CATEGORY))
			{
                //Xoa nhieu
				if(sizeof(Url::get('selected_ids'))>1)
				{
					require_once 'forms/delete_selected.php';
					$this->add_form(new DeleteSelectedLaundryInvoiceForm());
				}
				else//Xoa 1
				{
					$ids = Url::get('selected_ids');
					$_REQUEST['id'] = $ids[0];
					require_once 'forms/delete.php';
					$this->add_form(new DeleteLaundryInvoiceForm());
				}
			}
			else
    			if(
    				(
                        (
                            (   Url::check(array('cmd'=>'delete')) and User::can_delete(false,ANY_CATEGORY)     )
        					or 
                            (   Url::check(array('cmd'=>'edit')) and User::can_edit(false,ANY_CATEGORY)     )
        					and 
                                Url::check('id') and DB::exists_id('housekeeping_invoice',$_REQUEST['id'])
                        )
                    )
    				or
    				(   Url::check(array('cmd'=>'add')) and User::can_add(false,ANY_CATEGORY)   )
                    or
    				(   Url::check(array('cmd'=>'detail')) and User::can_add(false,ANY_CATEGORY)   )
    				or 
                        !Url::check('cmd')
    			)
    			{
    				switch(Url::get('cmd'))
    				{
        				case 'delete':
        					require_once 'forms/delete.php';
        					$this->add_form(new DeleteLaundryInvoiceForm());break;
        				case 'edit':
        					require_once 'forms/edit.php';
        					$this->add_form(new EditLaundryInvoiceForm());break;
        				case 'add':
        					require_once 'forms/add.php';
        					$this->add_form(new AddLaundryInvoiceForm());break;
                        case 'detail':
        					require_once 'forms/detail.php';
        					$this->add_form(new LaundryInvoiceForm());break;
                        default : 
                            require_once 'forms/list.php';
       						$this->add_form(new ListLaundryInvoiceForm());break;
        				//default:
//        					if(Url::check('id') and DB::exists_id('housekeeping_invoice',$_REQUEST['id']))
//        					{
//        						require_once 'forms/detail.php';
//        						$this->add_form(new LaundryInvoiceForm());
//        					}
//        					else
//        					{
//        						require_once 'forms/list.php';
//        						$this->add_form(new ListLaundryInvoiceForm());
//        					}
//        					break;
    				}
    			}
    			else
    			{
    				Url::redirect_current();
    			}
		}
		else
		{
			Url::access_denied();
		}
	}
}
?>