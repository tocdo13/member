<?php 
class KaraokeSplitInvoice extends Module
{
	function KaraokeSplitInvoice($row)
	{
		Module::Module($row);
		if(Url::get('karaoke_id') && Url::get('karaoke_id') !=0){
			Session::set('karaoke_id',intval(Url::get('karaoke_id')));	
		}
		switch(URL::get('cmd'))
				{
			case 'create_folio':
				if(Url::get('rr_id'))
				{
					require_once 'forms/split.php';
					$this->add_form(new CreateTravellerFolioForm());break;	
				}
			default:
				require_once 'forms/split.php';
				$this->add_form(new SplitKaraokeInvoiceForm());break;	
		}
	}
}
?>