<?php 
class TennisDailySummary extends Module
{
	public static $item = array();
	function TennisDailySummary($row)
	{
		Module::Module($row);
		if(URL::get('reset'))
		{
			URL::redirect_current();
		}
		else
		{
			if(User::can_view(false,ANY_CATEGORY))
			{
				switch(Url::get('cmd')){
					case 'delete':
						if(User::can_delete(false,ANY_CATEGORY) and Url::get('id') and $row = DB::fetch('SELECT * FROM MASSAGE_RESERVATION_COURT WHERE ID = '.Url::iget('id').'')){
							DB::delete('tennis_staff_court','reservation_court_id = '.$row['id']);
							DB::delete('tennis_reservation_court','id = '.$row['id']);
							DB::delete('tennis_product_consumed','reservation_court_id = '.$row['id']);
							DB::delete('tennis_product_hired','reservation_court_id = '.$row['id']);
							echo '<script>
									window.close();
									if(window.opener)
									{
										window.opener.history.go(0);
									}
								  </script> ';
							exit();
						}
						break;
					case 'add':
						if(Url::get('court_id') and DB::exists('SELECT id FROM tennis_court WHERE id = '.Url::iget('court_id').'')){
							require_once 'forms/edit.php';
							$this->add_form(new TennisEditForm());
						}else{
							Url::redirect_current();
						}						
						break;
					case 'edit':
						if(Url::get('court_id') and DB::exists('SELECT id FROM tennis_court WHERE id = '.Url::iget('court_id').'') and TennisDailySummary::$item = DB::fetch('select tennis_reservation_court.*,tennis_guest.code,tennis_guest.full_name from tennis_reservation_court left outer join tennis_guest on tennis_guest.id = guest_id where tennis_reservation_court.id='.Url::iget('id'))){
							require_once 'forms/edit.php';
							$this->add_form(new TennisEditForm());
						}else{
							Url::redirect_current();
						}						
						break;									
					case 'invoice':
						if(User::can_view(false,ANY_CATEGORY) and Url::get('id') and TennisDailySummary::$item = DB::fetch('select tennis_reservation_court.*,tennis_guest.code,tennis_guest.full_name from tennis_reservation_court left outer join tennis_guest on tennis_guest.id = guest_id where tennis_reservation_court.id='.Url::iget('id'))){
							require_once 'forms/invoice.php';
							$this->add_form(new TennisInvoiceForm());
						}else{
							Url::access_denied();
						}
						break;		
					default:
						require_once 'forms/report.php';
						$this->add_form(new TennisDailySummaryReportForm());
						break;					
				}
			}
			else
			{
				URL::access_denied();
			}
		}
	}
}
?>