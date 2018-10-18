<?php
class CreateTravellerFolioForm extends Form{
	function CreateTravellerFolioForm(){
		Form::Form('CreateTravellerFolioForm');
		$this->link_css("packages/hotel/skins/default/css/invoice.css");		
		//$this->link_js('packages/core/includes/js/jquery/window/jquery.window.js');
		//$this->link_js("packages/core/includes/js/jquery.windows-engine.js");
		//$this->link_css("packages/hotel/skins/default/css/jquery.windows-engine.css");
		$this->link_js('packages/core/includes/js/jquery/window/jquery.window.js');
		$this->link_js('packages/hotel/packages/reception/modules/includes/jquery.windows-engine.js');
        $this->link_js('packages/hotel/packages/reception/modules/includes/common01.js');
		$this->link_css("packages/hotel/skins/default/css/jquery.windows-engine.css");
		$this->add('traveller_id',new IntType(true,'invalid_traveller_id','0','100000000000'));
		if(Url::get('cmd') != 'add_payment'){
			//$this->add('payment_type',new TextType(true,'invalid_payment_type',0,255));    
		}
	}
	function on_submit(){
		if(Url::get('action') == 1){
			if($this->check() and Url::get('traveller_id') and Url::get('traveller_id') != 0){
				$split_invoices = array();
				$rt_id = Url::get('traveller_id');
				if(Url::get('add_payment')){ 
					$rr_id = Url::get('add_payment');	  
					$t = 1;
				}else if(Url::get('rr_id')){
					$rr_id = Url::get('rr_id');		
					$t =0;
				}
				$check = 0;
				//$reservation_id = DB::fetch('select reservation_id from reservation_room where id='.$rr_id.'','reservation_id');
				$reservation_id = DB::fetch('select reservation_room.reservation_id 
									from reservation_room 
										INNER JOIN reservation_traveller ON reservation_room.id = reservation_traveller.reservation_room_id
									where reservation_traveller.id='.$rt_id.'','reservation_id');
				$travellers = $this->get_reservation_traveller($rr_id);
				$folio_id = Url::get('folio_id')?Url::get('folio_id'):'';
				$count = 0;
				if($folio_id!=''){
					$invoice_old = $this->get_traveller_folio($rt_id,$rr_id,$folio_id);
				}else{
					$invoice_old = array();	
				}
				$count = 0;
				$total_amount = 0;
				$total_deposit = 0; $total_room = 0;
				
					//--------------------------------------Thuc hien them moi hoa don tach-----------------------------------//
				$split_invoices = Url::get('traveller_folio');
				$add_paids = Url::get('add_paid');
				//System::Debug(Url::get('add_paid')); exit();
				$add_paid_old = array();
				if(Url::get('folio_id')){
					$add_paid_old = $this->get_add_paid($rt_id,Url::get('folio_id')); 
				}
				//System::Debug($add_paid_old);
				if(Url::get('cmd')!='add_payment'){
					if(!empty($add_paid_old)){
						foreach($add_paid_old as $d => $paid_old){					
							if(!isset($add_paids[$paid_old['id']])){
								DB::delete('traveller_folio',' reservation_traveller_id='.$rt_id.' AND add_payment=1 AND reservation_room_id='.$paid_old['id'].'');
							}
						}
					}
				}
				if(empty($add_paids) && empty($split_invoices)){
					if(Url::get('cmd')=='create_folio' && Url::get('folio_id')){
						DB::delete('folio',' id = '.Url::get('folio_id').'');
						DB::delete('traveller_folio',' folio_id = '.Url::get('folio_id').'');
						DB::delete('payment',' payment.type=\'RESERVATION\' AND payment.folio_id = '.Url::get('folio_id').'');
					}
				}else{
					if(!empty($split_invoices)){
						foreach($split_invoices as $k => $split){
							$count++;
							$split_invoices[$k]['invoice_id'] = $split['id'];
							$split_invoices[$k]['reservation_traveller_id'] = $rt_id;	
							$split_invoices[$k]['reservation_room_id'] =  $rr_id;
							$split_invoices[$k]['reservation_id'] =  $reservation_id;
							$split_invoices[$k]['amount'] = System::calculate_number($split['amount']);
							$split_invoices[$k]['foc'] =  Url::get('foc')?Url::get('foc'):'';
							$split_invoices[$k]['foc_all'] = (Url::get('foc_all')==1)?Url::get('foc_all'):0;
							$split_invoices[$k]['id'] = $rt_id.'_'.$split_invoices[$k]['type'].'_'.$split_invoices[$k]['invoice_id'].'_'.$t;	
							if(Url::get('rr_id') == $rr_id){
								$split_invoices[$k]['add_payment'] = 0;
							}else{
								$split_invoices[$k]['add_payment'] = 1;	
							} 
					}
					$total_amount =  System::calculate_number(Url::get('total_amount'));
					$total_payment =  System::calculate_number(Url::get('total_payment'));
					$service_amount = System::calculate_number(trim(Url::get('service_charge_amount')));
					$tax_amount = System::calculate_number(trim(Url::get('tax_amount')));
					if(Url::get('folio_id')){
						$folio_id = Url::get('folio_id');
						if(Url::get('add_payment')){ 
							$rr=Url::get('rr_id')?Url::get('rr_id'):'';
						}else if(Url::get('rr_id')){
							$rr =$rr_id;
						}
						
						if($count>0 && Url::get('cmd')=='create_folio'){
							DB::update('folio',array('lastest_edit_time'=>time(),'total'=>($total_payment),'tax_amount'=>$tax_amount,'service_amount'=>$service_amount,'reservation_room_id'=>$rr,'reservation_id'=>$reservation_id,'user_id'=>Session::get('user_id')),' id = '.Url::get('folio_id').'');	
						}else if(Url::get('cmd')=='add_payment'){
							$old_folio = DB::fetch('select * from folio where id = '.Url::get('folio_id').'');	
							$old_folio['total'] += $total_amount;
							$old_folio['service_amount'] += $service_amount;
							$old_folio['tax_amount'] += $tax_amount;
							DB::update('folio',$old_folio,' id='.Url::get('folio_id').'');
						}
					}else{
						$number = DB::fetch('select max(num) as num from folio where reservation_traveller_id = '.$rt_id.'','num');
						if($number && $number!=''){
							$number = $number+1;
						}else{
							$number =1;
						}
						if($count>0){
							$folio_id = DB::insert('folio',array('reservation_traveller_id'=>$rt_id,'reservation_room_id'=>$rr_id,'total'=>$total_payment,'create_time'=>time(),'portal_id'=>''.PORTAL_ID.'','num'=>$number,'tax_amount'=>$tax_amount,'service_amount'=>$service_amount,'reservation_id'=>$reservation_id,'user_id'=>Session::get('user_id')));	
						$check = 1;	
						}else{ return;
							}
					}
					if(!empty($invoice_old)){
						foreach($invoice_old as $k => $old){
							if(!isset($split_invoices[$old['id']])){
								DB::delete('traveller_folio',' id='.$old['traveller_folio_id'].'');	
							}
						}
					}
					if(!empty($split_invoices)){
						foreach($split_invoices as $key => $value){
							$value['folio_id'] = $folio_id;
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
					}
				}
			}
			if(Url::get('act')=='payment'){// Save v� th?c hi?n thanh to�n
				$tt = 'form.php?block_id=428&cmd=payment&id='.Url::get('rr_id').'&r_id='.$reservation_id.'&traveller_id='.Url::get('traveller_id').'&type=RESERVATION&act=create_folio&total_amount='.System::calculate_number(Url::get('total_payment')).'&folio_id='.$folio_id.'&portal_id='.PORTAL_ID.'';
			}else{
				if(Url::get('cmd')=='add_payment' && $check==1){
					$con = '&folio_id='.$folio_id;	
				}else{ $con='';}
				$tt = 'form.php?block_id='.Module::block_id().'&cmd=create_folio&rr_id='.Url::get('rr_id').'&traveller_id='.Url::get('traveller_id').''.$con;
			}
			echo '<script>window.location.href = \''.$tt.'\'</script>';
			}else{ 
					$this->error('traveller_id','you_have_to_select_traveller_id');
				}
		}
	}
	function draw(){	
		$not_selected = '';
		$item_type = '';
		$total_payment = 0;
		if(isset($GLOBALS['night_audit_date'])){
			$today_date = $GLOBALS['night_audit_time'];
		}else{
			$today_date = date('d/m/Y');
		}
		if(Url::get('add_payment') && Url::get('traveller_id')){
			$rr_id = Url::get('add_payment');
			$id = Url::get('add_payment');		
		}else{
			$rr_id =Url::iget('rr_id');		
			$id = Url::iget('rr_id');
		}
		$checkout_id = '';
		for($i=0;$i<6-strlen($id);$i++){
			$checkout_id .= '0';
		}
		$checkout_id .= $id;
		require_once 'packages/core/includes/utils/time_select.php';
		require_once 'packages/core/includes/utils/currency.php';
		$paid_group = 0;
       // Lay thong tin invoied da thanh toan cho phong khac
		$travel_id='';
		if(!Url::get('traveller_id')){
			$travel_id = DB::fetch('select reservation_traveller.id as traveller_id from reservation_traveller INNER JOIN reservation_room ON reservation_room.id = reservation_traveller.reservation_room_id WHERE reservation_room.id='.$rr_id.' AND reservation_traveller.status=\'CHECKIN\'','traveller_id');
			$_REQUEST['travellers_id'] = $travel_id;
			$_REQUEST['traveller_id'] = $travel_id;
		}else{
			$travel_id = Url::get('traveller_id');	
		}
		// L?y ra c�c b?n ghi d� thanh to�n, c?a kh�ch n�y ho?c c�c kh�ch c�ng ph�ng
		$folio_traveller = DB::fetch('select min(id) as id from folio where reservation_traveller_id = '.$travel_id.' AND reservation_room_id='.$rr_id.'','id');
		$folio_id = Url::get('folio_id')?Url::get('folio_id'):'';
		//$_REQUEST['folio_id'] = $folio_id;
		//(($folio_traveller=='')?'':$folio_traveller)
		$traveller_folios = array();
		$m = 0;
		if($folio_id && $folio_id !=''){
			$traveller_folios = DB::fetch_all('SELECT
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
											WHERE reservation_traveller_id= '.Url::get('traveller_id').' 
												AND traveller_folio.reservation_room_id = '.$rr_id.' 
												AND traveller_folio.folio_id='.$folio_id.' ');
			foreach($traveller_folios as $key => $invoice){
				$traveller_folios[$key]['id'] = $invoice['invoice_id'];
				$traveller_folios[$key]['date'] = date('d/m',Date_Time::to_time(Date_Time::convert_orc_date_to_date($invoice['date_use'],'/')));
			}
		}
		if($folio_id==''){
			//$cond = ' OR (reservation_traveller_id='.Url::get('traveller_id').')';	// AND traveller_folio.percent=100
			$cond2 = '';	
		}else{
			//$cond = ' AND 1>0';	
			$cond2 = 'AND traveller_folio.folio_id != '.$folio_id.'';
		}
		$folio_other = DB::fetch_all('SELECT 
										(traveller_folio.type || \'_\' || traveller_folio.invoice_id) as id
										,traveller_folio.type
										,traveller_folio.invoice_id
										,traveller_folio.description
										,traveller_folio.tax_rate
										,traveller_folio.service_rate
										,sum(traveller_folio.amount) as amount
										,sum(traveller_folio.percent) as percent 
									FROM traveller_folio 
									WHERE reservation_room_id='.$rr_id.' 
										 '.$cond2.' 
									GROUP BY 
										traveller_folio.invoice_id
										,traveller_folio.type
										,traveller_folio.tax_rate
										,traveller_folio.service_rate
										,traveller_folio.description
										,traveller_folio.date_use');
		foreach($folio_other as $key => $other){
			$folio_other[$key]['id'] = $other['invoice_id'];
		}
		// L?y ra c�c h�a don m� kh�ch n�y d� thanh t?o
		$folio = DB::fetch_all('select folio.id  
										,CONCAT(traveller.first_name,CONCAT(\' \',traveller.last_name)) as name
										,CONCAT(folio.reservation_traveller_id,CONCAT(\' \',folio.num)) as code
										,folio.num
								from 
									folio 
									inner join reservation_traveller on reservation_traveller.id = folio.reservation_traveller_id 
									left outer join traveller on reservation_traveller.traveller_id = traveller.id 
								where 
									1>0 AND folio.reservation_traveller_id = '.Url::get('traveller_id').'');
		//end
		//System::Debug($folio);
		$_REQUEST['total_payment'] = $total_payment;
//--------------------------------- thong tin ve` reservation------------------------------------------
		$sql='select 
				reservation_room.*,
				traveller.first_name,
				traveller.last_name,
				traveller.nationality_id,
				traveller.id as traveller_id,
				reservation_type.show_price,
				reservation_type.name as reservation_type_name,
				room.name as room_name,
				customer.address,
				customer.name as customer_name,
				customer.id as customer_id
			from 
				reservation_room 
				inner join reservation ON reservation.id = reservation_room.reservation_id
				inner join room on room.id=reservation_room.room_id
				left outer join customer on customer.id = reservation.customer_id
				left outer join reservation_type on reservation_type.id=reservation_room.reservation_type_id
				left outer join reservation_traveller on reservation_traveller.reservation_room_id=reservation_room.id
				left outer join traveller on reservation_traveller.traveller_id=traveller.id
				';
		//============================Thong tin hoa don moi-------------------------------//
		$others = $this->get_reservation_other($rr_id);
		$row = DB::fetch($sql.' where reservation_room.id='.$rr_id.'');
		$row['add_payment'] = 0;
		$add_payments = array();
		if(Url::get('cmd') == 'create_folio' && $folio_id!=''){
			$add_payments = $this->get_add_paid(Url::get('traveller_id'),Url::get('folio_id'));    
			if(!empty($add_payments)){
				$row['add_payment'] = 1;
			}
		}
		$row['paid_group'] = $paid_group;
		//------------------------------------------Kh�ch------------------------------------------------------------		
		$travellers = $this->get_reservation_traveller($rr_id);
		$travellers_ids = $travellers;
	//--------------------------------------lay exchange------------------------------------------------
		require_once 'packages/hotel/packages/reception/modules/CreateTravellerFolio/get_reservation_room.php';
		$items = get_reservation_room_detail($rr_id,$folio_other);
			$sql = '
			SELECT
				rt.id,CONCAT(t.first_name,CONCAT(\' \',t.last_name)) AS name
			FROM
				reservation_traveller rt
				INNER JOIN traveller t ON t.id = rt.traveller_id
			WHERE
				rt.reservation_room_id  = '.$row['id'].'
			ORDER BY
				t.first_name
		';
		$row['reservation_traveller_id_list'] = array('...')+String::get_list(DB::fetch_all($sql));
		
		$traveller_id[0] = '----Traveller----';
		$traveller_id = $traveller_id + String::get_list($travellers_ids);
		$others_id[0]  = '----Order----';
		$others_id = $others_id + String::get_list($others);
		//System::Debug($items);
		$this->parse_layout('split',$row+array(
			'items'=>$items,
			'items_js'=>String::array2js($items),
			'folio_other_js'=>String::array2js($folio_other),
			'traveller_folios_js'=>String::array2js($traveller_folios),
			'items_js'=>String::array2js($items),
			'travellers_id_list'=>$traveller_id,
			'travellers'=>$travellers_ids,
			'order_ids_list'=>$others_id,
			'add_payments'=>$add_payments,
			'folio'=>$folio
		));
}
function get_traveller_folio($rt_id,$rr_id,$folio_id){
		$cond = ' AND traveller_folio.folio_id = '.$folio_id.'';
		$invoice_old = DB::fetch_all('
			SELECT 
				CONCAT(traveller_folio.reservation_traveller_id,CONCAT(\'_\',CONCAT(traveller_folio.type,CONCAT(\'_\',CONCAT(traveller_folio.invoice_id,CONCAT(\'_\',traveller_folio.add_payment)))))) as id
				,traveller_folio.reservation_traveller_id
				,traveller_folio.type,traveller_folio.invoice_id
				,traveller_folio.id as traveller_folio_id 
				,traveller_folio.add_payment 
			FROM 
				traveller_folio 
			WHERE
				reservation_traveller_id = '.$rt_id.'
				AND traveller_folio.reservation_room_id = '.$rr_id.'
				'.$cond.'');
		return 	$invoice_old;	

}
 	function get_reservation_traveller($rr_id){
		$sql_traveller = 'SELECT 
					reservation_traveller.id
					,traveller.first_name || \' \' || traveller.last_name as name
				FROM 	
					reservation_traveller
					INNER JOIN traveller ON reservation_traveller.traveller_id = traveller.id
					INNER JOIN reservation_room ON reservation_traveller.reservation_room_id = reservation_room.id
				WHERE 
					reservation_room.id = '.$rr_id.'
					AND reservation_traveller.status=\'CHECKIN\'
					';
		$travellers = DB::fetch_all($sql_traveller);			
		return $travellers;	
	}
	function get_reservation_other($rr_id){
		return $items = DB::fetch_all('select reservation_room.id,CONCAT(reservation_room.id,CONCAT(\'_\',CONCAT(\''.Portal::language('room').'\',room.name))) as name 
									from reservation_room 
										INNER JOIN room ON reservation_room.room_id = room.id
									where reservation_room.status=\'CHECKIN\' 
										AND reservation_room.id <>\''.$rr_id.'\' 
										AND portal_id=\''.PORTAL_ID.'\'
										
										');
										//AND '.time().' > reservation_room.time_in AND '.time().' < reservation_room.time_out	
	}
	function get_add_paid($traveller_id,$folio_id){
		$add_rr = array();
		$adds = DB::fetch_all('SELECT 
						traveller_folio.id
						,traveller_folio.reservation_room_id as rr_id
						,traveller_folio.amount as add_amount
						,room.name as room_name
						,traveller_folio.reservation_traveller_id as rt_id
						,traveller_folio.tax_rate
						,traveller_folio.service_rate
						,traveller_folio.type
						,reservation_room.time_in
						,reservation_room.time_out
					FROM traveller_folio 
						INNER JOIN reservation_room ON reservation_room.id = traveller_folio.reservation_room_id 
						INNER JOIN room ON room.id = reservation_room.room_id 
					WHERE reservation_traveller_id = '.$traveller_id.' AND add_payment=1 AND folio_id='.$folio_id.'');
			if(!empty($adds)){
				$total_room = 0; $total_discount = 0; $total_add = 0; $total_deposit=0;
				$add_rr = array();
				foreach($adds as $k => $ad){
					if(!isset($total_room_add[$ad['rr_id']])){
						$total_room_add[$ad['rr_id']] = 0;
						$total_discount_add[$ad['rr_id']]= 0;
						$total_deposit_add[$ad['rr_id']]= 0;
						$total_other_add[$ad['rr_id']] = 0;
					}
				}
				foreach($adds as $a => $add){
					if($add['type'] == 'ROOM'){
						$total_room_add[$add['rr_id']] += $add['add_amount'];
					}else if($add['type'] == 'DISCOUNT'){
						$total_discount_add[$add['rr_id']] += $add['add_amount'];	
					}else if($add['type'] == 'DEPOSIT'){
						$total_deposit_add[$add['rr_id']] += $add['add_amount'];	
					}else{
						$service_add = ($add['add_amount']*$add['service_rate']/100);
						$tax_add = ($add['add_amount'] + $service_add)*$add['tax_rate']/100;
						$total_other_add[$add['rr_id']] += $add['add_amount'] + $service_add + $tax_add; 	
					}
					if(isset($add_rr[$add['rr_id']])){
						$add_rr[$add['rr_id']]['total_room'] = $total_room_add[$add['rr_id']];
						$add_rr[$add['rr_id']]['total_discount'] = $total_discount_add[$add['rr_id']];
						$add_rr[$add['rr_id']]['total_deposit'] = $total_deposit_add[$add['rr_id']];
						$add_rr[$add['rr_id']]['total_other'] = $total_other_add[$add['rr_id']];
					}else{
						$add_rr[$add['rr_id']] = $add;
						$add_rr[$add['rr_id']]['id'] = $add['rr_id'];
						$add_rr[$add['rr_id']]['total_room'] = $total_room_add[$add['rr_id']];
						$add_rr[$add['rr_id']]['total_discount'] = $total_discount_add[$add['rr_id']];
						$add_rr[$add['rr_id']]['total_deposit'] = $total_deposit_add[$add['rr_id']];
						$add_rr[$add['rr_id']]['total_other'] = $total_other_add[$add['rr_id']];
					}
					$add_rr[$add['rr_id']]['time_in'] = date('d/m',$add['time_in']);
					$add_rr[$add['rr_id']]['time_out'] = date('d/m',$add['time_out']);
				}
				foreach($add_rr as $b => $arr){
						$reservation_this = DB::fetch('SELECT id,foc,foc_all from reservation_room where id='.$arr['rr_id'].'');
						if($reservation_this['foc']!=''){
							$arr['total_room'] = 0;
						}
						$add_rr[$b]['id'] = $arr['rr_id'];
						$add_rr[$b]['decription'] = Portal::language('add_payment_for_room').' '.$arr['room_name'].'('.$arr['time_in'].' - '.$arr['time_out'].')';
						$total_room_aftex_discount = $arr['total_room'] - $arr['total_discount'];
						$service_aftex_discount = $total_room_aftex_discount * $arr['service_rate']/100;
						$tax_aftex_discount = ($total_room_aftex_discount + $service_aftex_discount)*$arr['tax_rate']/100;
						$add_rr[$b]['add_amount'] = System::display_number($total_room_aftex_discount + $service_aftex_discount + $tax_aftex_discount + $arr['total_other'] - $arr['total_deposit']);
						if($reservation_this['foc_all']==1){
							$add_rr[$b]['add_amount'] = 0;
						}
				}
			}
		return $add_rr;
	}
}
?>