<?php 
class ExtraServiceAdmin extends Module
{
	public static $item = array();
	function ExtraServiceAdmin($row)
	{
		Module::Module($row);
		switch (Url::get('cmd')){
			case 'add':
				if(User::can_add(false,ANY_CATEGORY)){
					require_once 'forms/edit.php';
					$this->add_form(new EditExtraServiceAdminForm());
				}else{
					Url::access_denied();
				}
				break;
			case 'edit':
				if(User::can_edit(false,ANY_CATEGORY) and Url::get('id') and ExtraServiceAdmin::$item = DB::select('extra_service','ID = '.Url::iget('id'))){
					require_once 'forms/edit.php';
					$this->add_form(new EditExtraServiceAdminForm());
				}else{
					Url::access_denied();
				}
				break;
			case 'delete':
				if(User::can_delete(false,ANY_CATEGORY)){
				    if(Url::get('id') and DB::exists('SELECT ID FROM extra_service_invoice_detail WHERE SERVICE_ID = '.Url::iget('id').'')){
        				echo  '
            				<script>alert("'.Portal::language('service_exists_in_invoce').'");window.location="'.Url::build_current().'";</script>;
            			';
            			exit();
				    }else{
				      if(Url::get('id') and DB::exists('SELECT ID FROM extra_service WHERE ID = '.Url::iget('id').'')){
						$this->delete(Url::iget('id'));
						Url::redirect('notice',array('cmd'=>'delete','href'=>'?page='.Url::get('page')));
					}
					if(Url::get('item_check_box')){
						$arr = Url::get('item_check_box');	
						for($i=0;$i<sizeof($arr);$i++){
							$this->delete($arr[$i]);
						}
						Url::redirect('notice',array('cmd'=>'delete','href'=>'?page='.Url::get('page')));
					}else{
						Url::redirect_current();
					}   
				    }
				}else{
					Url::access_denied();
				}
				break;
			default:
				if(User::can_view(false,ANY_CATEGORY)){
					require_once 'forms/list.php';
					$this->add_form(new ListExtraServiceAdminForm());
				}else{
					Url::access_denied();
				}
				break;
		}
	}
	function delete($id){
		if($items=DB::fetch_all('SELECT ID,SERVICE_ID,INVOICE_ID FROM EXTRA_SERVICE_INVOICE_DETAIL WHERE SERVICE_ID = '.$id.'')){
			echo '<LINK rel="stylesheet" href="packages/core/skins/default/css/global.css" type="text/css">
				<LINK rel="stylesheet" href="packages/hotel/skins/default/css/style.css" type="text/css">
			';
			echo '<div class="warning-box">';
			echo '<div class="title">'.Portal::language('delete_confirm').'</div>';
			echo '<div class="content"><h3>'.Portal::language('reservation_with_this_extra_service').'</h3>';
			echo '<ul>';
			$bill_number = 0;
			foreach($items as $key=>$value){
				echo '<li><a target="_blank" href="'.Url::build('extra_service_invoice',array('id'=>$value['id'],'cmd'=>'edit')).'">'.Portal::language('view').' #'.$value['id'].'</a> | <a target="_blank" class="delete-link" href="'.Url::build('extra_service_invoice',array('id'=>$value['id'],'cmd'=>'delete')).'">'.Portal::language('delete').'</a></li>';
				$bill_number = $value['invoice_id'];
			}
			echo '</ul>';
			echo '<div class="notice">'.Portal::language('are_you_sure').'? <a href="'.Url::build_all().'&action=continue"><strong>'.Portal::language('sure').'</strong></a> | <a href="'.Url::build_current().'">'.Portal::language('back').'</a></div>';
			echo '</div>';
			echo '</div>';
			if(Url::get('action')!='continue'){
				exit();
			}
			DB::delete('EXTRA_SERVICE_INVOICE','id='.$bill_number);
			DB::delete('EXTRA_SERVICE_INVOICE_DETAIL','SERVICE_ID='.$id); 
		}
		System::log('delete','Delete extra service '.DB::fetch('SELECT id,name FROM extra_service WHERE id = '.$id,'name'),'',$id);// Edited in 28/01/2010
		DB::delete('extra_service','ID = '.$id);
	}		
}
?>