<?php 
class Villa extends Module
{
	function Villa($row)
	{
		Module::Module($row);
		require_once 'packages/hotel/includes/php/hotel.php';
		if(Url::get('id') and DB::exists('select id from room where id = '.Url::iget('id').'') and Url::get('cmd')=='delete' and User::can_delete(false,ANY_CATEGORY)){
			DB::delete('room','id=\''.Url::iget('id').'\'');
			if($minibar = DB::select('minibar','room_id = '.Url::iget('id').'')){
				$id = $minibar['id'];
				DB::delete('minibar','id=\''.$id.'\'');
				DB::delete('minibar_product','minibar_id=\''.$id.'\'');
				if($items = DB::select_all('housekeeping_invoice','minibar_id=\''.$id.'\'')){
					foreach($items as $value){
						DB::delete('housekeeping_invoice_detail','invoice_id='.$value['id'].'');
						DB::delete('housekeeping_invoice','id='.$value['id'].'');
					}
				}
			}
			Url::redirect_current();
		}elseif(User::can_edit(false,ANY_CATEGORY)){
			require_once 'forms/edit.php';
			$this->add_form(new VillaForm());
		}else{
			URL::access_denied();
		}
	}
}
?>