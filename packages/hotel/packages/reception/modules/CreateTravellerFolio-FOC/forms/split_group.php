<?php
class CreateGroupFolioForm extends Form
{
	function CreateGroupFolioForm()
	{
		Form::Form('CreateGroupFolioForm');
		$this->link_css("packages/hotel/skins/default/css/invoice.css");
		$this->link_js('packages/hotel/packages/reception/modules/includes/jquery.windows-engine.js');
        $this->link_js('packages/hotel/packages/reception/modules/includes/common01.js');
		$this->link_css("packages/hotel/skins/default/css/jquery.windows-engine.css");
		//$this->add('customer_id',new IDType(true,'customer_id_not_exitst','customer_id'));
		$this->link_js('cache/data/'.str_replace('#','',PORTAL_ID).'/list_traveller_folio.js?v='.time()); 	
	}
	function on_submit(){
		if(Url::get('customer_id') && Url::get('action')==1 && Url::get('id')){
			$split_invoices = array();
			$invoice_old = array();
			$id=Url::get('id');	
			$customer_id = Url::get('customer_id');
			if($customer_id == ''){
				echo "<script>";
				echo "alert('Customer id is not exitst! Can not create folio.');";	
				echo "</script>";
				return;
			}
			$t= 2;$total_amount=0;
			if($id != '' and $customer_id != ''){
				if(Url::get('folio_id')){
					$folio_id = Url::get('folio_id');
					$invoice_old = $this->get_traveller_folio($id,$customer_id,$folio_id);
				}
			}
			$split_invoices = Url::get('traveller_folio');
			//--------------------------------------Thuc hien them moi hoa don tach-----------------------------------//
			if(!empty($split_invoices)){
				foreach($split_invoices as $key => $split){
					$split_invoices[$key]['amount'] = System::calculate_number($split['amount']);
					$split_invoices[$key]['reservation_id'] =$id;
					$split_invoices[$key]['reservation_room_id'] = $split['rr_id'];
					$split_invoices[$key]['reservation_traveller_id'] =$customer_id;
					$split_invoices[$key]['add_payment'] = 2;	
					$split_invoices[$key]['invoice_id'] = $split['id'];
					$split_invoices[$key]['foc'] =  Url::get('foc_'.$split['rr_id'])?Url::get('foc_'.$split['rr_id']):'';
					$split_invoices[$key]['foc_all'] = (Url::get('foc_all_'.$split['rr_id'])==1)?Url::get('foc_all_'.$split['rr_id']):0;
					$split_invoices[$key]['id'] = $customer_id.'_'.$split['type'].'_'.$split_invoices[$key]['invoice_id'].'_'.$t;
				}	
				//System::Debug($split_invoices); exit();
						// Tiền phòng của từng phòng đã bao gồm phí dịch vụ và thuế
				$total_amount = System::calculate_number(trim(Url::get('total_amount')));
				$total_payment = System::calculate_number(trim(Url::get('total_payment')));
				$service_amount = System::calculate_number(trim(Url::get('service_charge_amount')));
				$tax_amount = System::calculate_number(trim(Url::get('tax_amount')));
				$number='';
				if(Url::get('folio_id')){
					$folio_id = Url::get('folio_id');
					DB::update('folio',array('lastest_edit_time'=>time(),'total'=>$total_payment,'tax_amount'=>$tax_amount,'service_amount'=>$service_amount,'user_id'=>Session::get('user_id')),' id = '.Url::get('folio_id').'');	
				}else{
					$number = DB::fetch('select max(num) as num from folio where customer_id = '.$customer_id.' AND reservation_id='.$id.'','num');
					if($number && $number!=''){
						$number = $number+1;
					}else{
						$number =1;
					}
					$folio_id = DB::insert('folio',array('customer_id'=>$customer_id,'total'=>$total_payment,'create_time'=>time(),'portal_id'=>''.PORTAL_ID.'','num'=>$number,'tax_amount'=>$tax_amount,'service_amount'=>$service_amount,'reservation_id'=>$id,'user_id'=>Session::get('user_id')));	    
				}
				if(!empty($invoice_old)){
					foreach($invoice_old as $k => $old){
						if(!isset($split_invoices[$old['id']])){
							DB::delete('traveller_folio','id='.$old['traveller_folio_id'].'');	
						}
					}
				}
				foreach($split_invoices as $key => $value){
					$value['folio_id'] = $folio_id;
					unset($value['rr_id']);
					$value['date_use'] = Date_Time::to_orc_date($value['date'].'/'.date('Y'));
					unset($value['date']);
					if(isset($invoice_old[$key]) and $invoice_old[$key]['id'] == $value['id']){
						unset($value['id']);
						DB::update('traveller_folio',$value,'id='.$invoice_old[$key]['traveller_folio_id'].'');	
					}else{//($invoice_old[$key]['invoice_id'] == ''){
						unset($value['id']);
						DB::insert('traveller_folio',$value);	
					}
				}
				//exit();
			}else{
				if(Url::get('folio_id')){
					DB::delete('folio',' id = '.Url::get('folio_id').'');
					DB::delete('traveller_folio',' folio_id = '.Url::get('folio_id').' AND add_payment=2 AND reservation_traveller_id ='.$customer_id.'');
				}
			}
			if(Url::get('act')=='payment'){// Save và th?c hi?n thanh toán
				$tt = 'form.php?block_id=428&cmd=payment&id='.Url::get('id').'&customer_id='.$customer_id.'&type=RESERVATION&act=group_folio&total_amount='.System::calculate_number(Url::get('total_payment')).'&folio_id='.$folio_id.'&portal_id='.PORTAL_ID.'';
			}else{
				$tt = 'form.php?block_id='.Module::block_id().'&cmd=group_folio&id='.Url::get('id').'&customer_id='.$customer_id;
			}						
			
			echo '<script>window.location.href = \''.$tt.'\'
			</script>';		
		}
	}
	function draw()
	{	
		//require_once 'packages/hotel/packages/reception/modules/includes/get_reservation.php';
		$this->map = array();
		$id = Url::iget('id');
		//$this->map = get_reservation(Url::iget('id'),'',true);
		//$reservation_paid = $this->get_reservation_paid(Url::iget('id'));
		//System::Debug($reservation_paid);
		//System::Debug($this->map);
		//$row = array(); 
		$reservation_rooms = DB::fetch_all('select 
										reservation_room.id
										,reservation_room.time_in
										,reservation_room.time_out
										,reservation_room.status
										,reservation_room.reservation_id
										,reservation_room.room_id
										,reservation_room.foc
										,reservation_room.foc_all
										,room.name as room_name
										,reservation.customer_id
										,customer.name as customer_name
										,reservation.deposit as group_deposit
									FROM reservation_room
										 INNER JOIN reservation ON reservation_room.reservation_id = reservation.id
										 LEFT OUTER JOIN customer ON customer.id = reservation.customer_id
										 INNER JOIN room ON reservation_room.room_id = room.id
									WHERE reservation_room.reservation_id = '.$id.'	AND reservation_room.status<>\'CANCEL\'			
								'); 
		require_once 'packages/hotel/packages/reception/modules/CreateTravellerFolio/get_reservation_room.php';
		$arr_ids=''; $i=0;
		$customer_id = 0;
		if(Url::get('folio_id')){
			$cond = ' AND traveller_folio.folio_id <> '.Url::get('folio_id').'';
		}else{
			$cond = '';	
		}
		foreach($reservation_rooms as $k => $rr){
			$arr_ids .= (($i==0)?'':',').$k;
			$i++;
		}
		$folio_other = DB::fetch_all('SELECT 
								(traveller_folio.type || \'_\' || traveller_folio.invoice_id) as id
								,traveller_folio.type
								,traveller_folio.invoice_id
								,sum(traveller_folio.percent) as percent 
								,sum(traveller_folio.amount) as amount
							FROM traveller_folio 
							WHERE (reservation_id='.$id.' OR (reservation_room_id in ('.$arr_ids.') AND add_payment=1)) '.$cond.' 
							GROUP BY traveller_folio.invoice_id,traveller_folio.type');
		foreach($folio_other as $key =>$folio){
			$folio_other[$key]['id'] = $folio['invoice_id'];
		}
		foreach($reservation_rooms as $k => $rr){
			$customer_id = $rr['customer_id'];
			$customer_name = $rr['customer_name'];
			$reservation_rooms[$k]['room_name'] = Portal::language('room').':'.$rr['room_name'];
			$reservation_rooms[$k]['items'] = get_reservation_room_detail($k,$folio_other);
			if($rr['group_deposit']>0){
				$percent = 100;$status = 0;
				$amount =$rr['group_deposit'];
				$items['DEPOSIT_GROUP_'.$id]['net_amount'] = System::display_number($amount);
				if(isset($folio_other['DEPOSIT_GROUP_'.$id])){
					if($folio_other['DEPOSIT_GROUP_'.$id]['percent']==100 || $folio_other['DEPOSIT_GROUP_'.$id]['amount'] ==$amount){
						$status = 1;
					}else{
						$percent = 100 - $folio_other['DEPOSIT_GROUP_'.$id]['percent'];
						$amount = $amount - $folio_other['DEPOSIT_GROUP_'.$id]['amount'];
					}
				}           
				$items['DEPOSIT_GROUP_'.$id]['id'] = $id;
				$items['DEPOSIT_GROUP_'.$id]['type'] = 'DEPOSIT_GROUP';
				$items['DEPOSIT_GROUP_'.$id]['service_rate'] = 0;
				$items['DEPOSIT_GROUP_'.$id]['tax_rate'] = 0;
				$items['DEPOSIT_GROUP_'.$id]['date'] = '';
				$items['DEPOSIT_GROUP_'.$id]['rr_id'] = $id;
				$items['DEPOSIT_GROUP_'.$id]['percent'] = $percent;
				$items['DEPOSIT_GROUP_'.$id]['status'] = $status;
				$items['DEPOSIT_GROUP_'.$id]['amount'] = System::display_number($amount);
				$items['DEPOSIT_GROUP_'.$id]['description'] = Portal::language('deposit_for_group');
				$reservation_rooms['GROUP']['id'] = $id;
				$reservation_rooms['GROUP']['rr_id'] = $id;
				$reservation_rooms['GROUP']['tax_rate'] = 0;
				$reservation_rooms['GROUP']['service_rate'] = 0;
				$reservation_rooms['GROUP']['foc'] = '';
				$reservation_rooms['GROUP']['foc_all'] = 0;
				$reservation_rooms['GROUP']['room_name'] = Portal::language('deposit_for_group');
				//$reservation_rooms['DEPOSIT'][''] = 0;
				$reservation_rooms['GROUP']['items'] = $items;
			}
			//System::Debug($reservation_rooms[$k]['items']);
		}
		//System::Debug($reservation_rooms);
		$_REQUEST['arr_ids']=$arr_ids;
		if(Url::get('id')){
			$id = Url::get('id');	
		}
		$this->map['traveller_folios_js'] = '{}';
		$_REQUEST['customer_id'] = $customer_id;
		if(Url::get('cmd') == 'group_folio' && $customer_id){ 
			$m = 0;
			if(Url::get('folio_id')){
				$order_splits = DB::fetch_all('SELECT
													(traveller_folio.type || \'_\' || traveller_folio.invoice_id) as id 
													,traveller_folio.invoice_id
													,traveller_folio.type
													,traveller_folio.date_use
													,traveller_folio.description
													,traveller_folio.amount
													,traveller_folio.percent
													,traveller_folio.reservation_room_id as rr_id
													,traveller_folio.tax_rate
													,traveller_folio.service_rate
											FROM traveller_folio 
											WHERE reservation_traveller_id= '.Url::get('customer_id').' 
												AND traveller_folio.reservation_id = '.$id.' 
												AND traveller_folio.folio_id='.Url::get('folio_id').' ');
				foreach($order_splits as $i => $order){
					$order_splits[$i]['id'] = $order['invoice_id'];
					$order_splits[$i]['date'] = date('d/m',Date_Time::to_time(Date_Time::convert_orc_date_to_date($order['date_use'],'/')));
				}
				$this->map['traveller_folios_js'] = String::array2js($order_splits);
			}
		}	
		$this->map['folios'] = $reservation_rooms;
			$dir_string = 'cache/data/'.str_replace('#','',PORTAL_ID).'';
			if(!is_dir($dir_string)){
				mkdirj($dir_string);	
			}
			$str = " var items_js=";
			$str.= String::array2js($reservation_rooms);
			$str.= '';
			$f = fopen($dir_string.'/list_traveller_folio.js','w+');
			fwrite($f,$str);
			fclose($f);
		$this->map['folio_other_js'] = String::array2js($folio_other);
		$this->map['customer_name'] = $customer_name;
		$this->map['customer_id'] = $customer_id;
		$this->map['folio'] = $this->get_folio_other($id,$customer_id);
		$this->parse_layout('split_group',$this->map);	
	}
	function get_traveller_folio($id,$customer_id,$folio_id){
		$invoice_old = DB::fetch_all('
			SELECT 
				CONCAT(traveller_folio.reservation_traveller_id,CONCAT(\'_\',CONCAT(traveller_folio.type,CONCAT(\'_\',CONCAT(\'RE\',CONCAT(traveller_folio.invoice_id,CONCAT(\'_\',traveller_folio.add_payment))))))) as id
				,traveller_folio.reservation_traveller_id as customer_id
				,traveller_folio.type,traveller_folio.invoice_id
				,traveller_folio.id as traveller_folio_id 
				,traveller_folio.add_payment 
			FROM 
				traveller_folio 
			WHERE
				reservation_traveller_id = '.$customer_id.'
				AND traveller_folio.reservation_id = '.$id.'
				AND traveller_folio.folio_id='.$folio_id.'');  
		return 	$invoice_old;	  
 	}
	function get_folio_other($reservation_id,$customer_id){
		return $folio = DB::fetch_all('select folio.id  

										,customer.name as name
										,CONCAT(folio.customer_id,CONCAT(\' \',folio.num)) as code
										,folio.num
								from 
									folio 
									inner join reservation on reservation.id = folio.reservation_id 
									inner join customer on reservation.customer_id = customer.id 
								where 
									folio.reservation_id = '.$reservation_id.' AND folio.customer_id = '.$customer_id.'');	
	}
}
?>