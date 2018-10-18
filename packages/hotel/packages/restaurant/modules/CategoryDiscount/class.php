<?php 
class CategoryDiscount extends Module
{
	function CategoryDiscount($row){
		   Module::Module($row);
		   if(Url::get('bar_id') and User::can_admin(false,ANY_CATEGORY) and $row =DB::fetch('select id,code as code from bar where id='.intval(Url::get('bar_id'))))
			{
				Session::set('bar_id',intval(Url::get('bar_id')));
				Session::set('bar_code',$row['code']);
			}
			else if(!Session::is_set('bar_id'))
			{
				require_once 'packages/hotel/includes/php/hotel.php';
				$bar = DB::fetch('select min(id) as id from bar','id');	
				$bar_id = $bar;
				$bar_code = DB::fetch('select code as code from bar where id='.$bar_id,'code');	
				if($bar_id)
				{
					Session::set('bar_id',$bar_id);
					Session::set('bar_code',$bar_code);
				}
				else
				{
					Session::set('bar_id','');
					Session::set('bar_code','');
				}
			}        
			$bar_id = Session::get('bar_id');
			$_REQUEST['bar_id'] = Session::get('bar_id');
			if(User::can_view(false,ANY_CATEGORY)){
				require_once 'forms/list.php';
				$this->add_form(new CategoryDiscountForm());
			}else{
				URL::access_denied();
			}
	}
}
?>