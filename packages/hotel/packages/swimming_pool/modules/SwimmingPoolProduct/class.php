<?php 
class SwimmingPoolProduct extends Module
{
	public static $item = array();
	function SwimmingPoolProduct($row)
	{
		Module::Module($row);
		switch (Url::get('cmd')){
			case 'add':
				if(User::can_add(false,ANY_CATEGORY)){//
					require_once 'forms/edit.php';
					$this->add_form(new EditSwimmingPoolProductForm());
				}else{
					Url::access_denied();
				}
				break;
			case 'edit':
				if(User::can_edit(false,ANY_CATEGORY) and Url::get('id') and SwimmingPoolProduct::$item = DB::select('swimming_pool_product','id = '.Url::iget('id'))){
					require_once 'forms/edit.php';
					$this->add_form(new EditSwimmingPoolProductForm());
				}else{
					Url::access_denied();
				}
				break;
			case 'delete':
				if(User::can_delete(false,ANY_CATEGORY)){
					if(Url::get('id') and DB::exists('SELECT ID FROM swimming_pool_product WHERE ID = '.Url::iget('id').'')){
						$this->delete(Url::iget('id'));
					}
					if(Url::get('item_check_box')){
						$arr = Url::get('item_check_box');	
						for($i=0;$i<sizeof($arr);$i++){
							$this->delete($arr[$i]);
						}
					}
					Url::redirect_current();	
				}else{
					Url::access_denied();
				}
				break;
			default:
				if(User::can_view(false,ANY_CATEGORY)){
					require_once 'forms/list.php';
					$this->add_form(new ListSwimmingPoolProductForm());
				}else{
					Url::access_denied();
				}
				break;
		}
	}
	function delete($id){
		if($items=DB::fetch_all('SELECT swimming_pool_product_consumed.* FROM swimming_pool_product_consumed inner join swimming_pool_reservation_pool ON swimming_pool_reservation_pool.id = reservation_product_id WHERE product_id ='.$id.'')){
			echo '<LINK rel="stylesheet" href="packages/core/skins/default/css/global.css" type="text/css">
				<LINK rel="stylesheet" href="packages/hotel/skins/default/css/style.css" type="text/css">
			';
			echo '<div class="warning-box">';
			echo '<div class="title">'.Portal::language('delete_confirm').'</div>';
			echo '<div class="content"><h3>'.Portal::language('reservations_use_this_product').'</h3>';
			echo '<ul>';
			foreach($items as $key=>$value){
				echo '<li><a target="_blank" href="'.Url::build('swimming_pool_daily_summary',array('id'=>$value['reservation_product_id'],'cmd'=>'edit','product_id'=>$value['product_id'])).'">'.Portal::language('view').' '.$value['reservation_product_id'].'</a></li>';
			}
			echo '</ul>';
			echo '<div class="notice">'.Portal::language('are_you_sure').'? <a href="'.Url::build_all().'&action=continue"><strong>'.Portal::language('sure').'</strong></a> | <a href="'.Url::build_current().'">'.Portal::language('back').'</a></div>';
			echo '</div>';
			echo '</div>';
			if(Url::get('action')!='continue'){
				exit();
			}
		}
		DB::delete('swimming_pool_product','id = '.$id);
	}	
}
?>