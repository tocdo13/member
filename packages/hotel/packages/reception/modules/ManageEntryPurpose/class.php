<?php 
class ManageEntryPurpose extends Module
{
	function ManageEntryPurpose($row)
	{
		Module::Module($row);
		require_once 'packages/hotel/includes/php/hotel.php';
		if(Url::get('id') and DB::exists('select id from entry_purposes where id = '.Url::iget('id').'') and Url::get('cmd')=='delete' and User::can_delete(false,ANY_CATEGORY)){
			DB::delete('entry_purposes','id=\''.Url::iget('id').'\'');
			Url::redirect_current();
		}elseif(User::can_edit(false,ANY_CATEGORY)){
			require_once 'forms/edit.php';
			$this->add_form(new ManageEntryPurposeForm());
		}else{
			URL::access_denied();
		}
	}
}
?>