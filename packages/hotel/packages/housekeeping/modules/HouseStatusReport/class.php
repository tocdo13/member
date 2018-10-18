<?php 
//Module: thong ke so luong phong theo loai va chon trong mot khoan thoi gian
//Written by: khoand
//Date: 22/03/2011
class HouseStatusReport extends Module
{
	function HouseStatusReport($row)
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
				
				require_once 'forms/report.php';
				$this->add_form(new HouseStatusReportForm());
			}
			else
			{
				URL::access_denied();
			}
		}
	}
}
?>