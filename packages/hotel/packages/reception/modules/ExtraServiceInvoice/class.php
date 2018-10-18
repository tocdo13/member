<?php 
class ExtraServiceInvoice extends Module
{
	public static $item = array();
	function ExtraServiceInvoice($row)
	{
	   /** manh: check last time **/
        if(Url::get('check_last_time')){
            $data = array('status'=>'','user'=>'','time'=>'');
            $last_time = DB::fetch('select last_time,lastest_edited_user_id as user_id from extra_service_invoice where id='.Url::get('id'));
            if($last_time['last_time']!=0 and $last_time['last_time']>Url::get('last_time')){
                $data = array('status'=>'error','user'=>$last_time['user_id'],'time'=>date('H:i:s d/m/Y',$last_time['last_time']));
            }
            echo json_encode($data);
            exit();
        }
        /** end manh **/
		Module::Module($row);
		switch (Url::get('cmd')){
			case 'add':
				if(User::can_add(false,ANY_CATEGORY)){//
					require_once 'forms/edit.php';
					$this->add_form(new EditExtraServiceInvoiceForm());
				}else{
					Url::access_denied();
				}
				break;
			case 'edit':
				if(User::can_edit(false,ANY_CATEGORY) and Url::get('id') and ExtraServiceInvoice::$item = DB::select('extra_service_invoice','ID ='.Url::iget('id'))){
					require_once 'forms/edit.php';
					$this->add_form(new EditExtraServiceInvoiceForm());
				}else{
					Url::access_denied();
				}
				break;
            case 'view_receipt':
				if(User::can_edit(false,ANY_CATEGORY) and Url::get('id') and ExtraServiceInvoice::$item = DB::select('extra_service_invoice','ID ='.Url::iget('id'))){
					require_once 'forms/service_receipt.php';
					$this->add_form(new ViewExtraServiceInvoiceForm());
				}else{
					Url::access_denied();
				}
				break;
			case 'delete':
				if(User::can_delete(false,ANY_CATEGORY)){
					if(Url::get('id') and DB::exists('SELECT ID FROM extra_service_invoice WHERE ID = '.Url::iget('id').'')){
                    $id = Url::iget('id');
                    if(DB::exists('
                                    SELECT
                                        mice_invoice_detail.invoice_id as id,
                                        mice_invoice_detail.mice_invoice_id,
                                        extra_service_invoice.id as invoice_id
                                    FROM
                                        mice_invoice_detail
                                        INNER JOIN extra_service_invoice_detail on mice_invoice_detail.invoice_id = extra_service_invoice_detail.id  AND mice_invoice_detail.type=\'EXTRA_SERVICE\'
                                        INNER JOIN extra_service on extra_service.id=extra_service_invoice_detail.service_id 
                                        INNER JOIN extra_service_invoice on extra_service_invoice_detail.invoice_id = extra_service_invoice.id
                                    WHERE
                                        extra_service_invoice.id =\''.$id.'\'
                        '))
                        {
                            $_SESSION['errors'] = 'Dịch vụ đã được tạo bill không thể xóa!';
                            Url::redirect_url('?page=extra_service_invoice&type='.Url::get('type').'');
                            return false;                              
                        }
                        if(DB::exists('
                                                SELECT
                                                    traveller_folio.invoice_id as id,
                                                    extra_service_invoice.id as invoice_id,
                                                    traveller_folio.folio_id
                                                FROM
                                                    traveller_folio
                                                    INNER JOIN extra_service_invoice_detail on extra_service_invoice_detail.id = traveller_folio.invoice_id  AND traveller_folio.type=\'EXTRA_SERVICE\'
                                                    INNER JOIN extra_service on extra_service.id=extra_service_invoice_detail.service_id 
                                                    INNER JOIN extra_service_invoice on extra_service_invoice_detail.invoice_id = extra_service_invoice.id
                                                WHERE
                                                    extra_service_invoice.id =\''.$id.'\'
                        '))
                        {
                            $_SESSION['errors'] = 'Dịch vụ đã được tạo folio không thể xóa!';
                            Url::redirect_url('?page=extra_service_invoice&type='.Url::get('type').'');
                            return false; 
                        }
                        $this->delete(Url::iget('id'));
                        
						Url::redirect_url('?page=extra_service_invoice&type='.Url::get('type').'');
					}
					if(Url::get('item_check_box')){
						$arr = Url::get('item_check_box');
                        $check = false;	
						for($i=0;$i<sizeof($arr);$i++){
				            $id = $arr[$i];
                            if(DB::exists('
                                        SELECT
                                            mice_invoice_detail.invoice_id as id,
                                            mice_invoice_detail.mice_invoice_id,
                                            extra_service_invoice.id as invoice_id
                                        FROM
                                            mice_invoice_detail
                                            INNER JOIN extra_service_invoice_detail on mice_invoice_detail.invoice_id = extra_service_invoice_detail.id  AND mice_invoice_detail.type=\'EXTRA_SERVICE\'
                                            INNER JOIN extra_service on extra_service.id=extra_service_invoice_detail.service_id 
                                            INNER JOIN extra_service_invoice on extra_service_invoice_detail.invoice_id = extra_service_invoice.id
                                        WHERE
                                            extra_service_invoice.id =\''.$id.'\'
                            '))
                            {
                                $check = true;
                            }
                            if(DB::exists('
                                                    SELECT
                                                        traveller_folio.invoice_id as id,
                                                        extra_service_invoice.id as invoice_id,
                                                        traveller_folio.folio_id
                                                    FROM
                                                        traveller_folio
                                                        INNER JOIN extra_service_invoice_detail on extra_service_invoice_detail.id = traveller_folio.invoice_id  AND traveller_folio.type=\'EXTRA_SERVICE\'
                                                        INNER JOIN extra_service on extra_service.id=extra_service_invoice_detail.service_id 
                                                        INNER JOIN extra_service_invoice on extra_service_invoice_detail.invoice_id = extra_service_invoice.id
                                                    WHERE
                                                        extra_service_invoice.id =\''.$id.'\'
                            '))
                            {
                                $check = true;
                            }
						}
                        if($check == true)
                        {
                            $_SESSION['errors'] = 'Dịch vụ đã được tạo Bill or Folio không thể xóa!'; 
                            Url::redirect_url('?page=extra_service_invoice&type='.Url::get('type').''); 
                            return false;                              
                        }
                        for($i=0;$i<sizeof($arr);$i++){
				            $id = $arr[$i];
                            $this->delete($arr[$i]);
                        }
						Url::redirect_url('?page=extra_service_invoice&type='.Url::get('type').'');
					}else{
						Url::redirect_url('?page=extra_service_invoice&type='.Url::get('type').'');
					}
				}else{
					Url::access_denied();
				}
				break;
			default:
				if(User::can_view(false,ANY_CATEGORY)){
					require_once 'forms/list.php';
					$this->add_form(new ListExtraServiceInvoiceForm());
				}else{
					Url::access_denied();
				}
				break;
		}
	}	
	function delete($id){
		$reservation_room_id = DB::fetch('select reservation_room_id from extra_service_invoice where id = '.$id.'','reservation_room_id');
        $reservation_id = DB::fetch('select reservation_id from reservation_room where id='.$reservation_room_id,'reservation_id');
		$bill_number = DB::fetch('select bill_number from extra_service_invoice where id='.$id,'bill_number');
        if(User::can_admin(false,ANY_CATEGORY) or DB::fetch('select status from reservation_room where id = '.$reservation_room_id.'','status')!='CHECKOUT'){
			$service_detail = DB::fetch_all('select id,name,price,quantity,in_date,used from extra_service_invoice_detail where invoice_id = '.$id.'');
            $log_action  = 'Delete';
            $log_title = 'Delete extra service invoice #'.$bill_number.'';
       	    $description = '<div>----------------------------
			<li>Lastest Modified User: '.Session::get('user_id').'</li>
			<li>Lastest Modified Time: '.date('d/m/Y H:i\'',time()).'</li>
			<ul></div>';
            $log_description = '<br>Bill number: '.DB::fetch('select id,bill_number from extra_service_invoice where id = '.$id.'','bill_number').'<br>';
			$log_description .= '<br>Total Amount: '.DB::fetch('select id,total_amount from extra_service_invoice where id = '.$id.'','total_amount').'<br>';
            $log_description .= '<br>Room name: '.DB::fetch('select extra_service_invoice.id,room.name as name from extra_service_invoice inner join reservation_room on extra_service_invoice.reservation_room_id = reservation_room.id
                                                                left join room on reservation_room.room_id = room.id where extra_service_invoice.id = '.$id.'','name').'<br>';
            foreach($service_detail as $key=>$record)
            {
                $service_name=$record['name'];
                $log_description .= 'Delete [Service: '.$service_name.', Price: '.System::display_number($record['price']).', Quantity: '.$record['quantity'].', Date: '.$record['in_date'].', Used: '.$record['used'].']<br>'; 
            }
            
            DB::delete('extra_service_invoice_detail','invoice_id = '.$id);
            DB::delete('extra_service_invoice_table','invoice_id = '.$id);
			DB::delete('extra_service_invoice','id = '.$id);
            
            $log_id = System::log($log_action,$log_title,$log_description,$id);
            System::history_log('RECODE',$reservation_id,$log_id);
            System::history_log('EXTRA_SERVICE',$id,$log_id);
            
		}else{
			echo '<script>alert("'.Portal::language('room_checked_out').'");</script>';
			exit();
		}
	
    }	
}
?>
