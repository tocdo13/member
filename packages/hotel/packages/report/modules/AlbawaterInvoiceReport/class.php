<?php 
class AlbawaterInvoiceReport extends Module
{
	function AlbawaterInvoiceReport($row){
		   Module::Module($row);
			if(User::can_view(false,ANY_CATEGORY))
            {
                require_once 'db.php';
				require_once 'forms/report.php';
				$this->add_form(new AlbawaterInvoiceReportForm());
			}
            else
            {
				URL::access_denied();
			}
	}
}
?>