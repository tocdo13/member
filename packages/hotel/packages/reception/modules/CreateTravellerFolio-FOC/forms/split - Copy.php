<?php
class CreateTravellerFolioForm extends Form{
	function CreateTravellerFolioForm(){
		Form::Form('CreateTravellerFolioForm');
		$this->link_css("packages/hotel/skins/default/css/invoice.css");		
		$this->link_js('packages/core/includes/js/jquery/window/jquery.window.js');
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
				$item_type = Url::get('item_type');
				$rt_id = Url::get('traveller_id');
				if(Url::get('add_payment')){
					$rr_id = Url::get('add_payment');	
					$t = 1;
				}else if(Url::get('rr_id')){
					$rr_id = Url::get('rr_id');		
					$t =0;
				}
				$travellers = $this->get_reservation_traveller($rr_id);
				$invoice_old = $this->get_traveller_folio($rt_id,$rr_id);
				$item_type = explode(",",$item_type);
					//--------------------------------------Thuc hien them moi hoa don tach-----------------------------------//
					for($i=0;$i<count($item_type);$i++){	
						$item_type[$i] = substr($item_type[$i],0,strrpos($item_type[$i],'_'));
						if($item_type[$i] != ''){	
							$key = $rt_id.'_'.substr($item_type[$i],0,strrpos($item_type[$i],'_')).'_'.substr($item_type[$i],strrpos($item_type[$i],'_')+1).'_'.$t;	
							$split_invoices[$key]['type'] = substr($item_type[$i],0,strrpos($item_type[$i],'_'));
							$split_invoices[$key]['invoice_id'] = substr($item_type[$i],strrpos($item_type[$i],'_')+1);
							$split_invoices[$key]['reservation_room_id'] = $rr_id;
							$split_invoices[$key]['reservation_traveller_id']	= $rt_id;										
							$split_invoices[$key]['amount'] = System::calculate_number(trim(Url::get('amount_'.$item_type[$i].'_input')));
							$split_invoices[$key]['percent'] = Url::get('percent_'.$item_type[$i].'_input');
							$split_invoices[$key]['id'] = $rt_id.'_'.$split_invoices[$key]['type'].'_'.$split_invoices[$key]['invoice_id'].'_'.$t;	
							if(Url::get('rr_id') == $rr_id){
								$split_invoices[$key]['add_payment'] = 0;
							}else{
								$split_invoices[$key]['add_payment'] = 1;	
							}
						}
					}
					foreach($invoice_old as $k => $old){
						if(!isset($split_invoices[$old['id']])){
							DB::delete('traveller_folio','id='.$old['traveller_folio_id'].'');	
						}
					}
					foreach($split_invoices as $key => $value){
						if(isset($invoice_old[$key]) and $invoice_old[$key]['id'] == $value['id']){
							unset($value['id']);
							DB::update('traveller_folio',$value,'id='.$invoice_old[$key]['traveller_folio_id'].'');	
						}else{//($invoice_old[$key]['invoice_id'] == ''){
							unset($value['id']);
							DB::insert('traveller_folio',$value);	
						}
					}
/*					$payment_method = DB::fetch_all('select id, name,code from payment_method order by id');
					foreach($payment_method as $m=> $method){
						if(Url::get('input_pmt_'.$m)){
						if(Url::get('input_pmt_'.$m)>0){
							if($pmt = DB::fetch('select * from pay_by_method where bill_id='.$rr_id.' AND credit_card_id='.$method['id'].' AND type=\'RESERVATION\'')){
								DB::update('pay_by_method',array('amount'=>System::calculate_number(Url::get('input_pmt_'.$m))),'id='.$pmt['id']);
							}else{
								DB::insert('pay_by_method',array('bill_id'=>$rr_id,'amount'=>System::calculate_number(Url::get('input_pmt_'.$m)),'type'=>'RESERVATION','credit_card_id'=>$method['id'],'code'=>$method['code'],'reservation_traveller_id'=>Url::get('traveller_id')));
							}
						}
						}else{
							if($pmt_old = DB::fetch('select * from pay_by_method where bill_id='.$rr_id.' AND credit_card_id='.$method['id'].' AND type=\'RESERVATION\'')){
								
								DB::delete('pay_by_method','id='.$pmt_old['id'].'');	
							//}
						}	
						}
					}				
*/					$tt = 'form.php?block_id='.Module::block_id().'&cmd=create_folio&rr_id='.Url::get('rr_id').'&traveller_id='.Url::get('traveller_id');
					echo '<script>window.location.href = \''.$tt.'\'
					</script>';
			}else{
					$this->error('traveller_id','you_have_to_select_traveller_id');
				}
		}else{
		}
	}
	function draw(){	
		//echo Url::get('customer_id');
		$total_payment = 0;
		if(isset($GLOBALS['night_audit_date'])){
			$today_date = $GLOBALS['night_audit_time'];
		}else{
			$today_date = date('d/m/Y');
		}
		if(Url::get('add_payment') && Url::get('traveller_id')){
			$rr_id = Url::iget('add_payment');
			$id = Url::iget('add_payment');		
			//$_REQUEST['traveller_id'] = Url::get('traveller_id');
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
			$travel_id = DB::fetch('select reservation_traveller.id as traveller_id from reservation_traveller INNER JOIN reservation_room ON reservation_room.id = reservation_traveller.reservation_room_id WHERE reservation_room.id='.$rr_id.' ','traveller_id');
			$_REQUEST['travellers_id'] = $travel_id;
			$_REQUEST['traveller_id'] = $travel_id;
		}
		if(Url::get('traveller_id') || $travel_id!=''){
				$itm = '';
				$typ = ''; $pmt = '';
				$m = 0;
				$order_splits = DB::fetch_all('SELECT traveller_folio.*,CONCAT(traveller_folio.type,CONCAT(\'_\',traveller_folio.invoice_id)) as key FROM traveller_folio WHERE reservation_traveller_id= '.Url::get('traveller_id').' AND traveller_folio.reservation_room_id = '.$rr_id.' ');
				foreach($order_splits as $i=> $split){
					$itm .= ','.$split['key'].'_'.$split['percent'];
					$total_payment += ($split['percent'] * $split['amount'] * 0.01);
				}
				$folio_other = DB::fetch_all('SELECT CONCAT(traveller_folio.type,CONCAT(\'_\',traveller_folio.invoice_id)) as id,traveller_folio.type,traveller_folio.invoice_id,sum(traveller_folio.percent) as percent FROM traveller_folio WHERE reservation_room_id='.$rr_id.' and reservation_traveller_id <> '.Url::get('traveller_id').' GROUP BY traveller_folio.invoice_id,traveller_folio.type');
				if($folio_other != ''){
					foreach($folio_other as $k=> $other){
						$typ .= ','.$other['id'].'_'.$other['percent'];
					}
				}
				$_REQUEST['not_selected'] = $typ;
				$_REQUEST['item_type'] = $itm;
				$paid_group = DB::fetch('SElECT sum(amount) as total from traveller_folio where reservation_room_id = '.$rr_id.' AND add_payment=2','total');
				$depsit = DB::fetch('select amount as depsit from traveller_folio where reservation_room_id = '.$rr_id.' AND add_payment=2 AND type=\'DEPOSIT\'' ,'depsit');
				$paid_group = $paid_group - ($depsit*2);
			}
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
		$others = DB::fetch_all('select reservation_room.id,CONCAT(reservation_room.id,CONCAT(\'_\',CONCAT(\''.Portal::language('room').'\',room.name))) as name from reservation_room INNER JOIN room ON reservation_room.room_id = room.id where reservation_room.status=\'CHECKIN\' and reservation_room.id <>\''.$rr_id.'\'');
		if(Url::get('cmd') == 'create_folio'){
			$add_payments = DB::fetch_all('SELECT traveller_folio.reservation_room_id as id,sum(traveller_folio.amount) as add_amount,room.name as room_name,traveller_folio.reservation_traveller_id FROM traveller_folio INNER JOIN reservation_room ON reservation_room.id = traveller_folio.reservation_room_id INNER JOIN room ON room.id = reservation_room.room_id WHERE reservation_traveller_id = '.Url::iget('traveller_id').' AND add_payment=1 GROUP BY traveller_folio.reservation_room_id,room.name,traveller_folio.reservation_traveller_id');
			if(!empty($add_payments)){
				foreach($add_payments as $a => $add){
					$add_payments[$a]['decription'] = Portal::language('add_payment_for_room').' '.$add['room_name'];
					$add_payments[$a]['add_amount'] = System::display_number($add['add_amount']);
				}
			}
		}else{
			$add_payments = array();
		}
		$row = DB::fetch($sql.' where reservation_room.id='.$rr_id.'');
		//System::Debug($row);
		$row['paid_group'] = $paid_group;
		//------------------------------------------Khách------------------------------------------------------------		
		$travellers = $this->get_reservation_traveller($rr_id);
/*		if(count($travellers) <= 1){
			echo '<script>alert(\'Chi co 1 khach, ko tach duoc hoa don\');
				window.setTimeout("location=\''.Url::build('reservation',array('cmd'=>'invoice','hk_invoice','room_invoice','bar_invoice','other_invoice','phone_invoice','extra_service_invoice','swimming_pool_invoice','included_deposit','included_related_total','total_amount','price','deposit','reduce_balance','reduce_amount','time_in','time_out','departure_time','arrival_time','tax_rate','service_rate','id','shop_invoice')).'\'",1000);
			</script>';		
		}else{
*/			$travellers_ids = $travellers;
	//--------------------------------------lay exchange------------------------------------------------
	
			if(HOTEL_CURRENCY == 'VND'){
				$row['exchange_currency_id'] = 'USD';
			}else{
				$row['exchange_currency_id'] = 'VND';	
			}
			$row['exchange_rate'] = DB::fetch('select id,exchange from currency where id=\''.$row['exchange_currency_id'].'\'','exchange');
	//--------------------------------------------------------------------------------------------------		
			$row['discount_total']=0;
			$fromtime=Url::get('arrival_time')?Date_Time::to_time(Url::get('arrival_time')):Date_Time::to_time(date('d/m/Y',$row['time_in']));
			$totime=Url::get('departure_time')?Date_Time::to_time(Url::get('departure_time')):Date_Time::to_time(date('d/m/Y',$row['time_out']));
	//-------------------------------------------------------------------------------------------------------
			if($nationality=DB::exists_id('country',$row['nationality_id'])){
				$row['nationality']=$nationality['name_'.Portal::language()];
			}else{
				$row['nationality']='';
			}
	//-----------------------------------Ngay den va ngay di-------------------------------------------------		
			$arr_time = $row['arrival_time'];
			$dep_time = $row['departure_time'];
			$row['time_arrival']=date('d/m/Y',$fromtime);//str_replace('-','/',Date_Time::convert_orc_date_to_date($row['arrival_time']));
			$row['time_departure']=date('d/m/Y',$totime);
			$room_result = $row;
	//-------------------------------------------------------------------------------------------------------
			$row_number = 0;
			$total_room_price=0;
			$restaurant_total = 0;
			$minibar_total = 0;
			$laundry_total = 0;
			$compensated_total = 0;
			$phone_total = 0;			
			$service_total = 0;
			$total = 0;
			$tax_total = 0;
			$service_charge_total = 0;
			$total_items = 0;
			$condition = '1=1';
			$check=false;
	//----------------------------------------Tien phong`----------------------------------------------------
			$row['currency_id'] = HOTEL_CURRENCY;
			$row['total_amount'] = 0;
			$row['room_price'] = Url::get('price')?System::display_number(Url::get('price')):System::display_number($row['price']);
			//if(defined('FULL_RATE')){
				//$row['room_price'] = $row['room_price']/(1.155);
			//}
			if($row['show_price'] == 0){
				$row['room_price'] = $row['reservation_type_name'];
			} 
			if($row['foc']){
				$row['room_price'] = 'FOC';
			} 
			if($row['foc_all']){
				$row['room_price'] = 'FOC';
			} 
			//if(Url::get('included_deposit')){
			$row['deposit'] = Url::get('deposit')?Url::get('deposit'):$row['deposit'];
			//}else{
				//$row['deposit'] = 0;
			//}
			$row['reduce_balance'] = Url::get('reduce_balance')?floatval(Url::get('reduce_balance')):$row['reduce_balance'];
			//if(defined('FULL_RATE')){
				//$row['tax_rate'] = 10;
				//$row['service_rate'] = 5;
			//}else{
				$row['tax_rate'] = Url::get('tax_rate')?Url::get('tax_rate'):$row['tax_rate'];
				$row['service_rate'] = Url::get('service_rate')?Url::get('service_rate'):$row['service_rate'];
			//}
			$row['total_massage_amount'] = 0;
			$row['total_tennis_amount'] = 0;
			$row['total_swimming_pool_amount'] = 0;
			$row['total_karaoke_amount'] = 0;
			$row['total_shop_amount'] = 0;
			$day = array(); // lay danh sach ngay` o khach san
			$n = 1;
			$from = $fromtime;
			$to = $totime;
			$d=$from;
			$bar_charge = 0; //tong tien` su dung dich vu bar
			$sql = '
				SELECT 
					to_char(room_status.in_date,\'DD/MM/YYYY\') as id,room_status.change_price,room.name as room_name,room_status.in_date,room_status.id as room_status_id,reservation_room.tax_rate,reservation_room.service_rate
				FROM 
					room_status
					inner join room on room.id=room_status.room_id
					INNER JOIN reservation_room ON reservation_room.id = room_status.reservation_room_id
				WHERE 
					reservation_room_id=\''.$row['id'].'\' 
					AND room_id=\''.$row['room_id'].'\' AND room_status.change_price > 0
				ORDER BY 
					room_name,in_date';
			$room_statuses = DB::fetch_all($sql);//'.((USE_NIGHT_AUDIT==1)?'AND (room_status.closed_time > 0 OR reservation_room.arrival_time = reservation_room.departure_time)':'').'
			$j = 0;
			$itm = '';
			foreach($room_statuses as $k=>$v){
				$tt = ($row['reduce_balance']?(100 - $row['reduce_balance'])*$v['change_price']/100:$v['change_price']);
				$add_service = $v['service_rate'] * $tt/100;
				$add_tax = ($add_service+$tt)*$v['tax_rate']/100;
				$room_statuses[$k]['full_price'] = $tt + $add_service + $add_tax;
				$room_statuses[$k]['change_price'] = $tt;// + $add_service + $add_tax;
				$itm .= ($j==0)?'':',';
				$itm .= $v['room_status_id'];
				$j++;
				//if(defined('FULL_RATE')){
					//$room_statuses[$k]['change_price'] = $v['change_price']/(1.155);
				//}
			}
			$_REQUEST['all_room_rate_id'] = $itm;
			$i=0;
			$holidays = DB::fetch_all('select id,name,charge,in_date from holiday');
			$holiday = array();
			foreach($holidays as $key=>$value){
				$k = Date_Time::convert_orc_date_to_date($value['in_date'],'/');
				$holiday[$k]['id'] = $k;
				$holiday[$k]['name'] = $value['name'];
				$holiday[$k]['charge'] = $value['charge'];
			}
			
			while($d>=$from and $d<=$to){
				$ni = 3;
				$day[$n]['date'] = date('d/m',$d);
				if($row['foc_all']){
					$day[$n]['foc_all'] = 'FOC';
				}else{
					$day[$n]['foc_all'] = 0;	
				}
				$room_price = 0;
				$row['extra_services'] = DB::fetch_all('
					select 
						extra_service_invoice_detail.*,(extra_service_invoice_detail.quantity*extra_service_invoice_detail.price) as amount,
						0 as service_charge_amount,
						0 as tax_amount
					from 
						extra_service_invoice_detail
						inner join extra_service_invoice on extra_service_invoice.id = extra_service_invoice_detail.invoice_id
					where 
						extra_service_invoice.reservation_room_id='.$id.'
						AND extra_service_invoice_detail.used = 1
						AND extra_service_invoice_detail.in_date = \''.Date_Time::to_orc_date(date('d/m/Y',$d)).'\'
				');
				//if(Url::get('extra_service_invoice')){
				if(!empty($row['extra_services'])){	
					foreach($row['extra_services'] as $s_key=>$s_value){
						$total += $s_value['amount'];
						$row['extra_services'][$s_key]['full_amount'] = System::display_number($s_value['amount']);
					}
					$day[$n]['extra_services'] = $row['extra_services'];
				}else{
					$day[$n]['extra_services'] = 0;
				}
				//if(Url::get('room_invoice')){
					if(($d<=$to) or ($from==$to)){
						if(isset($room_statuses[date('d/m/Y',$d)]['change_price'])){
							$room_price += $room_statuses[date('d/m/Y',$d)]['change_price'];
							$day[$n]['full_price'] = ($room_statuses[date('d/m/Y',$d)]['full_price']);
							$day[$n]['change_price'] = ($room_statuses[date('d/m/Y',$d)]['change_price']);
							$day[$n]['room_status_id'] = $room_statuses[date('d/m/Y',$d)]['room_status_id'];
						}
						if($room_price>0){
							if($row['show_price'] == 0){
								$room_price_label = $row['reservation_type_name'];
							}else{
								$room_price_label = System::display_number($room_price);
							}
							if($row['foc']){
								$room_price_label = 'FOC';
							}
							if($row['foc_all']){
								$room_price_label = 'FOC';
							}
							$day[$n]['room_price'] = $room_price_label;
							$day[$n]['full_price'] = System::display_number($day[$n]['full_price']);
							$day[$n]['change_price'] = System::display_number($day[$n]['change_price']);
							//$day[$n]['room_reduce_balance']=(str_replace(',','',$room_price)*($row['reduce_balance']/100));
							//$day[$n]['room_service_rate']=(str_replace(',','',$room_price-$day[$n]['room_reduce_balance'])*($row['service_rate']/100));
							$day[$n]['service_charge_amount'] = $room_price*($row['service_rate']/100);
							$tax_amount = ($room_price + $day[$n]['service_charge_amount'])*($row['tax_rate']/100);
							$day[$n]['tax_amount'] = round($tax_amount,2);
							if($row['show_price'] == 1 and !$row['foc']){
								$total_room_price+=$room_price;
							}
						}
					}
				//}
				if($total_room_price==0 and Url::get('total_amount')){
					//$total_room_price = Url::get('total_amount');
				}
				$row['total_room_price_before_discount']=System::display_number($total_room_price);
	//----------------------------------------/Tien phong-----------------------------------------------------			
	//----------------------------------------Tien dich vu----------------------------------------------------
				//if(URL::get('hk_invoice')){
					$sql_l='
						SELECT 
							housekeeping_invoice.*
						FROM 
							housekeeping_invoice
						WHERE 
							housekeeping_invoice.reservation_room_id='.$id.' 
							AND housekeeping_invoice.minibar_id=\''.$row['room_id'].'\'
							AND housekeeping_invoice.type=\'LAUNDRY\'
							AND (housekeeping_invoice.time>='.$d.' AND housekeeping_invoice.time<'.($d+24*3600).') 
					';// chu y giat la va minibar khac nhau o minibar_id
					$sql_m='
						SELECT 
							housekeeping_invoice.*
						FROM 
							housekeeping_invoice
							inner join minibar on housekeeping_invoice.minibar_id = minibar.id
						WHERE 
							housekeeping_invoice.reservation_room_id='.$id.' AND
							minibar.room_id=\''.$row['room_id'].'\' AND
							type=\'MINIBAR\' AND
							(housekeeping_invoice.time>='.$d.' and housekeeping_invoice.time<'.($d+24*3600).') 
					';
					$sql_compensated_item='
						SELECT 
							housekeeping_invoice.*
						FROM 
							housekeeping_invoice
						WHERE
							housekeeping_invoice.reservation_room_id='.$id.' AND
							housekeeping_invoice.minibar_id=\''.$row['room_id'].'\' AND
							housekeeping_invoice.type=\'EQUIP\' AND
							(housekeeping_invoice.time>='.$d.' and housekeeping_invoice.time<'.($d+24*3600).') 
					';// Voi truong hop cua hoa don den bu thi truong minibar_id tuong ung voi ID cua phong
				//}
		//-----------------------------------------minibar------------------------------------------------------------
				//if(URL::get('hk_invoice')){
					$minibar_charge=0;
					$minibar_tax_rate=0;
					$minibar_express_rate=0;
					$minibar_discount=0;
					$minibar_total_before_tax=0;
					$minibar_total_tax=0;
					$minibar_total_service_charge=0;
					if($minibars = DB::fetch_all($sql_m)){
						foreach($minibars as $k=>$minibar){				
							$minibar_details = DB::fetch_all('
								SELECT 
									HK_I_D.id,HK_I_D.price,HK_I_D.quantity,
									hk_product.name_1 as name,unit.name_1 AS unit_name,
									(HK_I_D.price * HK_I_D.quantity) AS amount
								FROM 
									housekeeping_invoice_detail HK_I_D 
									INNER JOIN hk_product ON hk_product.code = HK_I_D.product_id
									LEFT OUTER JOIN unit ON unit.id = hk_product.unit_id
								WHERE 
									HK_I_D.invoice_id = '.$k.'
								ORDER BY 
									hk_product.name_1');
							$minibars[$k]['minibar_details'] = $minibar_details;	
							$minibars[$k]['row_number'] = ++$row_number;
							$minibar_charge+=$minibar['total'];				
							$minibar_tax_rate+=$minibar['tax_rate'];
							$minibar_express_rate+=$minibar['express_rate'];
							$minibar_discount+=$minibar['discount'];
							$minibar_total_before_tax+=$minibar['total_before_tax'];
							$minibars[$k]['tax_amount'] = ($minibar_total_before_tax + $minibar['total_before_tax']*$minibar['fee_rate']/100)*($minibar_tax_rate/100);
							$minibar_total_tax += $minibars[$k]['tax_amount'];
							$original_total = $minibar['total_before_tax'];//(1+($minibar['fee_rate']/100));
							$minibars[$k]['service_charge_amount'] = $original_total*$minibar['fee_rate']/100;
							$service_charge_total += $minibars[$k]['service_charge_amount'];
							$minibars[$k]['original_total'] = $original_total;
						}			
					}
					//------------ Tong tien minibar truoc thue --------------
					//if(FULL_RATE){
						$minibar_total+= $minibar_charge;
						$total = $total + $minibar_charge;
						$tax_total+= $minibar_total_tax;						
					/*}else{
						$minibar_total+= $minibar_total_before_tax;
						$total = $total + $minibar_total_before_tax;
					}*/
					//$row['discount_total']+=$minibar_discount;
					$day[$n]['minibars']= $minibars;//System::display_number($minibar_charge);
					$day[$n]['minibar_tax_rate']=System::display_number($minibar_total_tax);
		//--------------------------------------------laundry--------------------------------------------------------
					DB::query($sql_l);
					$laundry_charge=0;
					$laundry_tax_rate=0;
					$laundry_express_rate=0;
					$laundry_discount=0;
					$laundry_total_before_tax=0;
					$laundry_total_tax=0;
					$laundry_total_service_charge=0;
					$day[$n]['laundrys'] = array();
					if($laundrys = DB::fetch_all()){
						foreach($laundrys as $k=>$laundry){	
							$laundry_details = DB::fetch_all('
								SELECT 
									HK_I_D.id,HK_I_D.price,HK_I_D.quantity,
									hk_product.name_1 as name,unit.name_1 AS unit_name,
									(HK_I_D.price * HK_I_D.quantity) AS amount
								FROM 
									housekeeping_invoice_detail HK_I_D 
									INNER JOIN hk_product ON hk_product.code = HK_I_D.product_id
									LEFT OUTER JOIN unit ON unit.id = hk_product.unit_id
								WHERE 
									HK_I_D.invoice_id = '.$k.'
								ORDER BY 
									hk_product.name_1');
							$laundrys[$k]['laundry_details'] = $laundry_details;
							$laundrys[$k]['row_number'] = ++$row_number;
							$laundry_charge += $laundry['total'];
							$laundry_tax_rate += $laundry['tax_rate'];
							$laundry_express_rate += $laundry['express_rate'];
							$laundry_discount += $laundry['discount'];
							$laundry_total_before_tax += $laundry['total_before_tax'];
							$laundrys[$k]['tax_amount'] = ($laundry_total_before_tax)*($laundry_tax_rate/100);
							$laundry_total_tax += $laundrys[$k]['tax_amount'];

							$original_total = $laundry['total_before_tax']/(1+($laundry['fee_rate']/100));
							$laundrys[$k]['service_charge_amount'] = $original_total*$laundry['fee_rate']/100;
							$service_charge_total += $laundrys[$k]['service_charge_amount'];
							$laundrys[$k]['original_total'] = $original_total;
						}
						$day[$n]['laundrys'] = $laundrys;
					}
					//if(FULL_RATE){
						$laundry_total += $laundry_charge;
						$total += $laundry_charge;
						$tax_total += $laundry_total_tax;
					/*}else
					{
						$laundry_total += $laundry_total_before_tax;
						$total += $laundry_total_before_tax;
					}*/
					//$row['discount_total']+=$laundry_discount
					$day[$n]['laundrys']=$laundrys;
					$day[$n]['laundry_tax_rate']=System::display_number($laundry_total_tax);
	//--------------------------------------------/laundry--------------------------------------------------------
					DB::query($sql_compensated_item);
					$compensated_item_charge=0;
					$compensated_item_tax_rate=0;
					$compensated_item_express_rate=0;
					$compensated_item_discount=0;
					$compensated_item_total_before_tax=0;
					$compensated_item_total_tax=0;
					if($compensated_items = DB::fetch_all($sql_compensated_item)){
						foreach($compensated_items as $k=>$compensated_item){
							$item_details = DB::fetch_all('
								SELECT 
									HK_I_D.id,HK_I_D.price,HK_I_D.quantity,
									hk_product.name_1 as name,unit.name_1 AS unit_name,
									(HK_I_D.price * HK_I_D.quantity) AS amount 
								FROM 
									housekeeping_invoice_detail HK_I_D 
									INNER JOIN hk_product ON hk_product.code = HK_I_D.product_id
									LEFT OUTER JOIN unit ON unit.id = hk_product.unit_id
								WHERE 
									HK_I_D.invoice_id = '.$k.'
								ORDER BY 
									hk_product.name_1');
							$compensated_items[$k]['item_details'] = $item_details;
							$compensated_items[$k]['row_number'] = ++$row_number;
							$compensated_item_charge+= round($compensated_item['total'],ROUND_PRECISION);
							$compensated_item_tax_rate+=$compensated_item['tax_rate'];
							$compensated_item_express_rate+=$compensated_item['express_rate'];
							$compensated_item_discount+=$compensated_item['discount'];				
							$compensated_item_total_before_tax+=round($compensated_item['total_before_tax'],ROUND_PRECISION);
							$compensated_items[$k]['tax_amount'] = ($compensated_item_total_before_tax)*($compensated_item_tax_rate/100); 
							$compensated_item_total_tax += $compensated_items[$k]['tax_amount'];
							$compensated_items[$k]['original_total'] = $compensated_item_total_before_tax;
							$compensated_items[$k]['service_charge_amount'] = 0;
						}
						$day[$n]['compensated_items'] = $compensated_items;
						//if(FULL_RATE){
							$compensated_total += $compensated_item_charge;
							$total += $compensated_item_charge;
							$tax_total += $compensated_item_total_tax;
						/*}else
						{
							$compensated_total += $compensated_item_charge;
							$total += $compensated_item_charge;
						}*/
					}
					$total += $compensated_item_charge;
					$tax_total += $compensated_item_total_tax;
	//-----------------------------------------------------------------------------------------------------------			
				//}	
				//if(URL::get('bar_invoice')){
					//su dung dich vu bar theo ngay
					$sql = '
						select 
							bar_reservation.id, bar_reservation.payment_result, 
							bar_reservation.time_out, bar_reservation.total, bar_reservation.prepaid,
							bar_reservation.total_before_tax, bar_reservation.total_before_tax, bar_reservation.tax_rate,bar_reservation.bar_fee_rate,
							\''.(Portal::language('restaurant')).'\' AS bar_name ,
							bar_reservation.deposit as bar_deposit
						from 
							bar_reservation 
						where 
							reservation_room_id=\''.$rr_id.'\' 
							and (bar_reservation.ARRIVAL_TIME>='.$d.' and bar_reservation.ARRIVAL_TIME<='.($d+24*3600).') 
							 and (bar_reservation.status=\'CHECKOUT\' or bar_reservation.status=\'CHECKIN\')
					';
					if($bar_resrs = DB::fetch_all($sql)){
						foreach($bar_resrs as $bk=>$reser){
							$bar_resrs[$bk]['row_number'] = ++$row_number;
							$bar_resrs[$bk]['bar_reservation_id'] = $reser['id'];
							$bar_resrs[$bk]['bar_chrg_tax'] = 0;
							$bar_resrs[$bk]['bar_chrg'] = $reser['total'];
							$bar_resrs[$bk]['original_total'] = $reser['total_before_tax'];//(1+($reser['bar_fee_rate']/100));
							$bar_resrs[$bk]['tax_amount'] = $reser['total'] - ($reser['total_before_tax']*$reser['bar_fee_rate']/100) - $reser['total_before_tax'];
							$tax_total += $bar_resrs[$bk]['tax_amount'];
							$original_total = $reser['total_before_tax'];//(1+($reser['bar_fee_rate']/100));
							$bar_resrs[$bk]['service_charge_amount'] = $original_total*$reser['bar_fee_rate']/100;
							$service_charge_total += $bar_resrs[$bk]['service_charge_amount'];
							$total += $reser['total'];
							$bar_resrs[$bk]['total'] = System::display_number($reser['total']);	
							$ni+=2;
							$row['deposit'] += $reser['bar_deposit'];
						}
						$day[$n]['bar_resers'] = $bar_resrs;
						$day[$n]['bar_name'] = $reser['bar_name'];
						$day[$n]['bar_charge'] = 1;
						
					}
					else{
						$day[$n]['bar_charge'] = 0;
					}
					//$row['discount_total']=System::display_number($row['discount_total']);
				//}
				$day[$n]['extra'] = '';
				if(date('w',$d) == 5 and EXTRA_CHARGE_ON_SUNDAY > 0){
					$day[$n]['extra'] .= '( + '.System::display_number(EXTRA_CHARGE_ON_SUNDAY).' '.HOTEL_CURRENCY.' '.Portal::language('friday').')';
				}
				$day[$n]['saturday_charge'] = '';
				if(date('w',$d) == 6 and EXTRA_CHARGE_ON_SATURDAY > 0){
					$day[$n]['extra'] .=  '( + '.System::display_number(EXTRA_CHARGE_ON_SATURDAY).' '.HOTEL_CURRENCY.' '.Portal::language('saturday').')';
				}
				if(isset($holiday[date('d/m/Y',$d)])){
					$day[$n]['extra'] .= '( + '.System::display_number($holiday[date('d/m/Y',$d)]['charge']).' '.HOTEL_CURRENCY.' '.$holiday[date('d/m/Y',$d)]['name'].')';
				}
				$total_items+= $ni;
				$day[$n]['num_items'] = $ni;
				$n++;
				$d=$d+(3600*24);
			}
	//-------------------------------------------------Other services----------------------------------------
			$all_services = DB::fetch_all('
				select 
					service_id as id,reservation_room_service.amount,reservation_room_id,service.name,service.type,
					0 as service_charge_amount, 0 as tax_amount,reservation_room_service.id as room_service_id
				from 
					reservation_room_service 
					inner join service on service.id = service_id 
				where 
					reservation_room_id= '.$id.'
			');
			$row['services'] = $all_services;
			//if(Url::get('other_invoice')){
				foreach($row['services'] as $s_key=>$s_value){
					if($s_value['type']=='SERVICE'){
						$total += $s_value['amount'];
						$row['services'][$s_key]['amount'] = System::display_number($s_value['amount']);
					}	
				}
			//}
			//if(Url::get('room_invoice')){
				foreach($row['services'] as $s_key=>$s_value){
					if($s_value['type']=='ROOM'){
						$total += $s_value['amount'];
						$row['services'][$s_key]['amount'] = System::display_number($s_value['amount']);
					}	
				}
			//}
	//----------------------------------------/Other services------------------------------------------------		
			if(URL::get('massage_invoice')){
				$sql_massage='
					SELECT 
						massage_reservation_room.hotel_reservation_room_id,sum(massage_reservation_room.total_amount) as total_amount
					FROM 
						massage_reservation_room
					WHERE
						massage_reservation_room.hotel_reservation_room_id='.$id.'
					GROUP BY
						massage_reservation_room.hotel_reservation_room_id
				';
				if($row['total_massage_amount'] = DB::fetch($sql_massage,'total_amount') and HAVE_MASSAGE){
					$row['row_number'] = ++$row_number;
					$total += $row['total_massage_amount'];
				}else{
					$row['total_massage_amount'] = 0;
				}
			}
			if(URL::get('tennis_invoice')){
				$sql_tennis='
					SELECT 
						tennis_reservation_court.hotel_reservation_room_id,sum(tennis_reservation_court.total_amount) as total_amount
					FROM 
						tennis_reservation_court
					WHERE
						tennis_reservation_court.hotel_reservation_room_id='.$id.'
					GROUP BY
						tennis_reservation_court.hotel_reservation_room_id
				';
				if($row['total_tennis_amount'] = DB::fetch($sql_tennis,'total_amount') and HAVE_TENNIS){

					$row['row_number'] = ++$row_number;
					$total += $row['total_tennis_amount'];				
				}else{
					$row['total_tennis_amount'] = 0;
				}
			}
			if(URL::get('swimming_pool_invoice')){
				$sql_swimming_pool='
					SELECT 
						swimming_pool_reservation_pool.hotel_reservation_room_id,sum(swimming_pool_reservation_pool.total_amount) as total_amount
					FROM 
						swimming_pool_reservation_pool
					WHERE
						swimming_pool_reservation_pool.hotel_reservation_room_id='.$id.'
					GROUP BY
						swimming_pool_reservation_pool.hotel_reservation_room_id
				';
				if($row['total_swimming_pool_amount'] = DB::fetch($sql_swimming_pool,'total_amount') and HAVE_SWIMMING){
					$row['row_number'] = ++$row_number;
					$total += $row['total_swimming_pool_amount'];
				}else{
					$row['total_swimming_pool_amount'] = 0;
				}
			}
			if(URL::get('karaoke_invoice')){
				$sql_karaoke='
					SELECT 
						KA_RESERVATION.RESERVATION_ROOM_ID,sum(KA_RESERVATION.TOTAL) as total_amount
					FROM 
						KA_RESERVATION
					WHERE
						KA_RESERVATION.RESERVATION_ROOM_ID='.$id.'
					GROUP BY
						KA_RESERVATION.RESERVATION_ROOM_ID
				';
				if($row['total_karaoke_amount'] = DB::fetch($sql_karaoke,'total_amount') and HAVE_KARAOKE){
					$total += $row['total_karaoke_amount'];
				}else{
					$row['total_karaoke_amount'] = 0;
				}
			}
			if(URL::get('shop_invoice')){
				$sql_shop='
					SELECT 
						shop_invoice.RESERVATION_ROOM_ID,sum(shop_invoice.TOTAL) as total_amount
					FROM 
						shop_invoice
					WHERE
						shop_invoice.RESERVATION_ROOM_ID='.$id.'
					GROUP BY
						shop_invoice.RESERVATION_ROOM_ID
				';
				if($row['total_shop_amount'] = DB::fetch($sql_shop,'total_amount')){
					$total += $row['total_shop_amount'];
				}else{
					$row['total_shop_amount'] = 0;
				}
			}
			$total_phone = 0;
			if(Url::get('phone_invoice')){
				//----------------------------------------Tien dien thoai-------------------------------------------------
				$sql_p = '
					SELECT
						SUM(telephone_report_daily.price) AS total
					FROM
						telephone_report_daily
						inner join telephone_number on telephone_number.phone_number = telephone_report_daily.phone_number_id
					WHERE
						telephone_report_daily.hdate >='.($row['time_in']).' and telephone_report_daily.hdate <= '.($row['time_out']).'
						AND telephone_number.room_id = '.$row['room_id'].'
					GROUP BY
						telephone_number.room_id
						
				';
				if($phone = DB::fetch($sql_p)){
					$row['row_number'] = ++$row_number;
					if($row['exchange_currency_id']=='VND'){
						$total_phone = round($phone['total']/$row['exchange_rate'],2);
					}else{
						$total_phone = $phone['total'];
					}
				}
				//----------------------------------------/Tien dien thoai-------------------------------------------------
				$total += $total_phone;
				//$total-=str_replace(',','',$row['deposit']);
			}
			$row['service_rate_total'] = 0;
			$row['room_tax_total'] = 0;
			//if(Url::get('room_invoice')){
				$total_room_price_ = $total_room_price;
				$row['service_rate_total'] = $total_room_price_*($row['service_rate']/100);
				$total_room_price += $row['service_rate_total'];
				$row['room_tax_total']= ($total_room_price)*($row['tax_rate']/100);
				$total_room_price += System::calculate_number($row['room_tax_total']);
				$tax_total += $row['room_tax_total'];
				$service_charge_total += $row['service_rate_total'];
				$total += $total_room_price;
				$row['discount_total'] = round($total_room_price*($row['reduce_balance']/100),2);
				$row['discount_total'] = System::display_number($row['discount_total']);
				$row['reduce_amount'] = System::display_number($row['reduce_amount']);
				$row['total_room_price'] = System::display_number($total_room_price);
			//}
			$row['total_phone'] = $total_phone;
			$row['total_items'] = $total_items;
			//thong tin cuoi cung cua check out
			require_once 'packages/core/includes/utils/lib/report.php';
			$report = new Report;
			$report->items = $day;
			$curr='';
			$total_by_text = '';
			$current_total = $total;
			$row['current_total'] = System::display_number($current_total);
			$related_total = 0;
			$related_rooms_arr = array();
			if($related_rooms = DB::fetch_all('select reservation_room.id,room.name from reservation_room inner join room on room.id = reservation_room.room_id where related_rr_id = '.$row['id'].'')){
				require_once 'packages/hotel/packages/reception/modules/Reservation/get_reservation.php';
				foreach($related_rooms as $k=>$v){
					$arr = get_reservation($row['reservation_id'],$v['id']);
					$related_rooms_arr[$v['id']]['id'] = $v['id'];
					$related_rooms_arr[$v['id']]['name'] = $v['name'];
					$related_total += $arr['total'];
					$related_rooms_arr[$v['id']]['total_amount'] = System::display_number($arr['total']);
					$related_rooms_arr[$v['id']]['row_number'] = ++$row_number;
				}
			}
			$row['related_rooms_arr'] = $related_rooms_arr;
			$total += $related_total;
			$row['total_bank_fee'] = 0;
			$bank_fee_percen = BANK_FEE_PERCEN; // % phi giao dich
			if($row['payment_type_id'] == 3 or Url::get('def_code')=='CREDIT_CARD'){ // thanh quan qua the, chuyen khoan
				if($row['deposit']>0){
					$row['total_bank_fee'] = round(($total-$row['deposit'])*$bank_fee_percen/100,ROUND_PRECISION);
					$row['total_with_bank_fee'] = $total - $row['deposit'] + $row['total_bank_fee'];
				}else{
					$row['total_bank_fee'] = round($total*$bank_fee_percen/100,ROUND_PRECISION);
					$row['total_with_bank_fee'] = $total + $row['total_bank_fee'];				
				}
			}else{
				$row['total_with_bank_fee'] = $total;	
			}
			$row['bank_fee_percen'] = $bank_fee_percen;
			////////////////////////////////////////////////////////////////////////////////////////////////
			if($row['total_with_bank_fee'] == $total){
				$total_ = $total - $row['deposit'];
			}else{
				$row_number = $row_number + 2;
				$total_ = $row['total_with_bank_fee'];
			}
			if($row['exchange_currency_id']=='USD'){
				$row['total_in_other_currency'] = round($total_/$row['exchange_rate'],2);
			}else{
				$row['total_in_other_currency'] = $total_*$row['exchange_rate'];
			}
			////////////////////////////////////////////////////////////////////////////////////////////////
			if(HOTEL_CURRENCY=='VND'){
				$total = round($total,0);
			}else{
				$total = round($total,2);
			}
			////////////////////////////////////////////////////////////////////////////////////////////////
			$payment_type = DB::fetch('SELECT id,name_1,name_2 FROM payment_type WHERE ID = '.$row['payment_type_id']);
			$row['payment_type_name_1'] = $payment_type['name_1'];
			$row['payment_type_name_2'] = $payment_type['name_2'];
			////////////////////////////////////////////////////////////////////////////////////////////////
			if($row['deposit'] > 0){
				$row_number = $row_number + 2;
			}
			$row['total_row_number'] = $row_number + 5;
			////////////////////////////////////////////////////////////////////////////////////////////////
			if(HOTEL_CURRENCY == 'VND'){
				//$total = System::vnd_round(System::calculate_number($total));
			}
			if(HOTEL_CURRENCY == 'VND'){
				//$row['service_rate_total'] = System::vnd_round($row['service_rate_total']);
				//$row['room_tax_total'] = System::vnd_round($row['room_tax_total']);
			}
			$total_before_tax = $total - $service_charge_total - $tax_total;
			$total = $total;
			$row['total_before_tax'] = $total_before_tax;
			$row['tax_total'] = $tax_total;
			$row['service_charge_total'] = $service_charge_total;
			$row['deposit'] =System::Display_number($row['deposit']);
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
		/*$this->parse_layout('header',$room_result+$row+array(
			'available'=>0,
			'exchange_rate1'=>$row['exchange_rate'],
			'checkout_id'=>$checkout_id,
			'description'=>$description
		));*/
		$sql = '
			SELECT
				currency.*,pay_by_currency.amount,pay_by_currency.exchange_rate
			FROM
				currency
				INNER JOIN pay_by_currency ON pay_by_currency.currency_id = currency.id AND pay_by_currency.type=\'RESERVATION\'
			WHERE
				pay_by_currency.bill_id = '.$rr_id.' AND currency.allow_payment = 1 AND pay_by_currency.amount>0
		';
		$currencies=DB::fetch_all($sql);
		$currencies[1]['id'] = 1;
		$currencies[1]['name'] = 'VND';
		$currencies[1]['amount'] = System::calculate_number($total);
		foreach($currencies as $key=>$value){
			if(isset($value['exchange_rate'])){
				if($value == 1){
					$currencies[1]['amount'] -= $value['amount']*$value['exchange_rate'];
				}else{
					$currencies[1]['amount'] -= System::vnd_round($value['amount']*$value['exchange_rate']);
				}
			}
		}
		if($currencies[1]['amount']<=0){
			//unset($currencies[1]);
		}
		$traveller_id[0] = '----Traveller----';
		$traveller_id = $traveller_id + String::get_list($travellers_ids);
		$others_id[0]  = '----Order----';
		$others_id = $others_id + String::get_list($others);
		$traveller_folio = DB::fetch_all('select traveller_folio.reservation_traveller_id as id,CONCAT(traveller.first_name,CONCAT(\' \',traveller.last_name)) as name from traveller_folio inner join reservation_traveller on reservation_traveller.id = traveller_folio.reservation_traveller_id inner join traveller on reservation_traveller.traveller_id = traveller.id where traveller_folio.reservation_room_id = '.$rr_id.'');
		/*$payment_method = DB::fetch_all('select id, name,code from payment_method order by id');
		$pay_by_method = DB::fetch_all('select 
											payment_method.id as id
											,payment_method.name
											,pay_by_method.amount
										from pay_by_method
										inner join payment_method on pay_by_method.currency_id=payment_method.id
										WHERE bill_id = '.$row['id'].'
												AND type=\'RESERVATION\'');
		//danh sach room
		foreach($payment_method as $t => $method){
			if(isset($pay_by_method[$t])){
				$payment_method[$t]['amount'] = System::display_number($pay_by_method[$t]['amount']);	
				$payment_method[$t]['name'] = strtoupper($method['name']);
			}else{
				$payment_method[$t]['amount'] = 0;
				$payment_method[$t]['name'] = strtoupper($method['name']);
			}
		}*/
		//System::Debug($row);
		//System::Debug($row);
		$this->parse_layout('split',$row+array(
			'live_day'=>$report->items,
			'tax_total'=>$tax_total,
			'curr'=>$curr,
			'sum_total'=>$total,
			'sum_total_by_text'=>$total_by_text,
			'currencies'=>$currencies,
			'travellers_id_list'=>$traveller_id,
			'travellers'=>$travellers_ids,
			'traveller_folio'=>$traveller_folio,
			'order_ids_list'=>$others_id,
			'add_payments'=>$add_payments
		));
}
function get_traveller_folio($rt_id,$rr_id){
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
				AND traveller_folio.reservation_room_id = '.$rr_id.'');
		return 	$invoice_old;	

}
 	function get_reservation_traveller($rr_id){
		$sql_traveller = 'SELECT 
					reservation_traveller.id,CONCAT(traveller.first_name,traveller.last_name) as name
				FROM 	
					reservation_traveller
					INNER JOIN traveller ON reservation_traveller.traveller_id = traveller.id
					INNER JOIN reservation_room ON reservation_traveller.reservation_room_id = reservation_room.id
				WHERE 
					reservation_room.id = '.$rr_id.'';
		$travellers = DB::fetch_all($sql_traveller);			
		return $travellers;	
	}
}
?>