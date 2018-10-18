<?php 
class SwimmingPoolDailySummary extends Module
{
	public static $item = array();
	function SwimmingPoolDailySummary($row)
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
						if(User::can_delete(false,ANY_CATEGORY) and Url::get('id') and $row = DB::fetch('SELECT * FROM swimming_pool_reservation_pool WHERE ID = '.Url::iget('id').'')){
							DB::delete('swimming_pool_staff_pool','reservation_pool_id = '.$row['id']);
							DB::delete('swimming_pool_reservation_pool','id = '.$row['id']);
							DB::delete('swimming_pool_product_consumed','reservation_pool_id = '.$row['id']);
							DB::delete('swimming_pool_product_hired','reservation_pool_id = '.$row['id']);
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
						if(Url::get('swimming_pool_id') and DB::exists('SELECT id FROM swimming_pool WHERE id = '.Url::iget('swimming_pool_id').'')){
							require_once 'forms/edit.php';
							$this->add_form(new TennisEditForm());
						}else{
							Url::redirect_current();
						}						
						break;
					case 'edit':
						if(Url::get('swimming_pool_id') and DB::exists('SELECT id FROM swimming_pool WHERE id = '.Url::iget('swimming_pool_id').'') and SwimmingPoolDailySummary::$item = DB::fetch('select swimming_pool_reservation_pool.*,swimming_pool_guest.code,swimming_pool_guest.full_name from swimming_pool_reservation_pool left outer join swimming_pool_guest on swimming_pool_guest.id = guest_id where swimming_pool_reservation_pool.id='.Url::iget('id'))){
							require_once 'forms/edit.php';
							$this->add_form(new TennisEditForm());
						}else{
							Url::redirect_current();
						}						
						break;									
					case 'invoice':
						if(User::can_view(false,ANY_CATEGORY) and Url::get('id') and SwimmingPoolDailySummary::$item = DB::fetch('select swimming_pool_reservation_pool.*,swimming_pool_guest.code,swimming_pool_guest.full_name from swimming_pool_reservation_pool left outer join swimming_pool_guest on swimming_pool_guest.id = guest_id where swimming_pool_reservation_pool.id='.Url::iget('id'))){
							require_once 'forms/invoice.php';
							$this->add_form(new TennisInvoiceForm());
						}else{
							Url::access_denied();
						}
						break;		
					default:
						require_once 'forms/report.php';
						$this->add_form(new SwimmingPoolDailySummaryReportForm());
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