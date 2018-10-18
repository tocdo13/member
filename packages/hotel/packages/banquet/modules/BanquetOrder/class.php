<?php 
class BanquetOrder extends Module
{
	function BanquetOrder($row)
	{
	   //exit();
		Module::Module($row);
		require_once 'db.php';
		if(User::can_view(false,ANY_CATEGORY))
		{
			switch(URL::get('cmd'))
			{
				case 'delete':
					require_once 'forms/delete.php';
					$this->add_form(new DeleteBanquetOrderForm());break;
				case 'edit':
					require_once 'forms/edit.php';
					$this->add_form(new EditBanquetOrderForm());break;
				case 'detail':
					require_once 'forms/detail.php';
					$this->add_form(new DetailBanquetForm());break;
				case 'cancel':
					$this->cancel();break;					
				case 'add':
					require_once 'forms/edit.php';
					$this->add_form(new EditBanquetOrderForm());break;
                case 'view_contact':
					require_once 'forms/view_contact.php';
					$this->add_form(new ViewContact());break;
                case '1':
					require_once 'forms/wedding.php';
					$this->add_form(new Wedding());break;
                case '2':
					require_once 'forms/birthday.php';
					$this->add_form(new Birthday());break;
                case '3':
					require_once 'forms/meeting.php';
					$this->add_form(new Meeting());break;
                case '4':
					require_once 'forms/meeting_company.php';
					$this->add_form(new MeetingCompany());break;
                case '5':
					require_once 'forms/company.php';
					$this->add_form(new Company());break;        
				default: 
					if(URL::check('id') and DB::exists_id('party_reservation',Url::iget('id')))
					{
						require_once 'forms/detail.php';
						$this->add_form(new DetailBanquetForm());
					}
					else
					{
						require_once 'forms/list.php';
						$this->add_form(new ListBanquetOrderForm());
					}
					break;
			}
		}
		else
		{
			URL::access_denied();
		}
	}
	function cancel()
	{
		if(Url::get('id') and DB::select('party_reservation','id='.Url::iget('id')))
		{
			DB::update('party_reservation',array('status'=>'CANCEL'),'id='.Url::iget('id'));
		}
		Url::redirect_current();
	}
}
?>