<?php 
class ManageGate extends Module
{
	function ManageGate($row)
	{
		Module::Module($row);
		require_once 'packages/hotel/includes/php/hotel.php';
		if(Url::get('code') and DB::exists('select id from gate where code = \''.Url::iget('code').'\'') and Url::get('cmd')=='delete' and User::can_delete(false,ANY_CATEGORY)){
			DB::delete('gate','code=\''.Url::iget('id').'\'');
			Url::redirect_current();
		}elseif(User::can_edit(false,ANY_CATEGORY)){
			require_once 'forms/edit.php';
			$this->add_form(new ManageGateForm());
		}else{
			URL::access_denied();
		}
	}
}
?>