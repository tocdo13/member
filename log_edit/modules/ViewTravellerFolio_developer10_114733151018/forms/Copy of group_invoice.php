<?php
class ViewGroupInvoiceForm  extends Form
{
	function ViewGroupInvoiceForm(){
		Form::Form('ViewGroupInvoiceForm');
		$this->link_css("packages/hotel/skins/default/css/invoice.css");
	}
	function get_items($customer_id,$id,$folio_id){
		if(HOTEL_CURRENCY == 'VND'){
			$this->map['exchange_currency_id'] = 'USD';
		}else{
			$this->map['exchange_currency_id'] = 'VND';	
		}
		$this->map['exchange_rate'] = DB::fetch('select id,exchange from currency where id=\''.$this->map['exchange_currency_id'].'\'','exchange');
		// phai lay thong tin trong bang pay_by_currency
		//$hihi = DB::fetch_all('select * from traveller_folio where add_payment=2');
		//System::Debug($hihi);
		$sql ='SELECT
		            tf.id, 
					tf.reservation_traveller_id,
					tf.type, tf.invoice_id,
					tf.amount, tf.percent,
					tf.reservation_room_id,
					tf.add_payment,
					tf.reservation_id,
					customer.id as customer_id,
					customer.address, 
					customer.name as customer_name,
					room.name as room_name,
					to_char(rr.arrival_time,\'DD/MM/YYYY\') as arrival_time,
					to_char(rr.departure_time,\'DD/MM/YYYY\') as departure_time ,
					(rr.departure_time - rr.arrival_time ) as night
		       FROM
			      traveller_folio tf
				  left outer join reservation_room rr on tf.reservation_room_id = rr.id
				  left outer join room on rr.room_id = room.id
				  left outer join customer on customer.id = tf.reservation_traveller_id 
			   WHERE
			    ( tf.reservation_traveller_id =\''.$customer_id.'\') and (tf.add_payment = \'2\') and ( tf.reservation_id =\''.$id.'\')
				AND (tf.folio_id='.$folio_id.' ) 
				order by tf.id
			   ';
		$items = DB::fetch_all($sql);
		//System::Debug($items);
      $traveller_id ='';     
		$rr = array();
		foreach($items as $k=>$v){
			if(!isset($this->map['guest'])){
				 $this->map['guest'] = $v['customer_name'];
				$this->map['address'] = $v['address'];
				$order_id='';
				for($i=0;$i<6-strlen($v['reservation_id']);$i++)
				{
					$order_id .= '0';
				}
				$this->map['reservation_id'] =$order_id.$v['reservation_id'];
			 
			}
			if(!isset($rr[$v['reservation_room_id']])){
				if($traveller_id){
					$traveller_id .= ($traveller_id=='')?$v['reservation_room_id']:(','.$v['reservation_room_id']);
				}
				$rr[$v['reservation_room_id']]['id'] = $v['reservation_room_id'];
				$rr[$v['reservation_room_id']]['arrival_time'] = $v['arrival_time'];
				$rr[$v['reservation_room_id']]['departure_time'] = $v['departure_time'];
				$rr[$v['reservation_room_id']]['room_name'] = $v['room_name'];
				$rr[$v['reservation_room_id']]['night'] = $v['night'];
				$rr[$v['reservation_room_id']]['total_amount'] =0;
				if($v['type'] == 'ROOM'){
					$rr[$v['reservation_room_id']]['total_amount'] +=$v['amount'];
					if(!isset($rr[$v['reservation_room_id']]['ROOM'])){
					    $rr[$v['reservation_room_id']]['ROOM'] = $v['amount'];
					}else{
						$rr[$v['reservation_room_id']]['amount'] += $v['amount'];
					}
				}
				if($v['type'] == 'BAR'){
					$rr[$v['reservation_room_id']]['total_amount'] +=$v['amount'];
					if(!isset($rr[$v['reservation_room_id']]['BAR'])){
					  $rr[$v['reservation_room_id']]['BAR'] = $v['amount'];
					}else{
					   $rr[$v['reservation_room_id']]['BAR'] += $v['amount'];
					}
				}
				if($v['type'] == 'LAUNDRY'){
					$rr[$v['reservation_room_id']]['total_amount'] +=$v['amount'];
					if(!isset($rr[$v['reservation_room_id']]['LAUNDRY'])){
					   $rr[$v['reservation_room_id']]['LAUNDRY'] = $v['amount'];
					}else{
						$rr[$v['reservation_room_id']]['LAUNDRY'] += $v['amount'];
					}
				}
				if($v['type'] == 'MINIBAR'){
					$rr[$v['reservation_room_id']]['total_amount'] +=$v['amount'];
					if(!isset($rr[$v['reservation_room_id']]['MINIBAR'])){
					   $rr[$v['reservation_room_id']]['MINIBAR'] = $v['amount'];
					}else{
						$rr[$v['reservation_room_id']]['MINIBAR'] += $v['amount'];
					}
				}
				if(($v['type'] == 'SERVICE')||($v['type']=='ROOM_SERVICE')||($v['type']=='EXTRA_SERVICE')){
					$rr[$v['reservation_room_id']]['total_amount'] +=$v['amount'];
					if(!isset($rr[$v['reservation_room_id']]['SERVICE'])){
					   $rr[$v['reservation_room_id']]['SERVICE'] = $v['amount'];
					}else{
						$rr[$v['reservation_room_id']]['SERVICE'] += $v['amount'];
					}
				}
				if(($v['type']=='EQUIPMENT')){
					$rr[$v['reservation_room_id']]['total_amount'] +=$v['amount'];
					if(!isset($rr[$v['reservation_room_id']]['EQUIPMENT'])){
					   $rr[$v['reservation_room_id']]['EQUIPMENT'] = $v['amount'];
					}else{
						$rr[$v['reservation_room_id']]['EQUIPMENT'] += $v['amount'];
					}
				}
				if(($v['type']=='DEPOSIT')){
					$rr[$v['reservation_room_id']]['total_amount'] -=$v['amount'];
					if(!isset($rr[$v['reservation_room_id']]['DEPOSIT'])){
					   $rr[$v['reservation_room_id']]['DEPOSIT'] = $v['amount'];
					}else{
						$rr[$v['reservation_room_id']]['DEPOSIT'] += $v['amount'];
					}
				}
				if(($v['type']=='DISCOUNT')){
					$rr[$v['reservation_room_id']]['total_amount'] -=$v['amount'];
					if(!isset($rr[$v['reservation_room_id']]['DISCOUNT'])){
					   $rr[$v['reservation_room_id']]['DISCOUNT'] = $v['amount'];
					}else{
						$rr[$v['reservation_room_id']]['DISCOUNT'] += $v['amount'];
					}
				}
			}else{
				if($v['type'] == 'ROOM'){
					$rr[$v['reservation_room_id']]['total_amount'] +=$v['amount'];
					if(!isset($rr[$v['reservation_room_id']]['ROOM'])){
					    $rr[$v['reservation_room_id']]['ROOM'] = $v['amount'];
					}else{
						$rr[$v['reservation_room_id']]['ROOM'] += $v['amount'];
					}
				}
				if($v['type'] == 'BAR'){
					$rr[$v['reservation_room_id']]['total_amount'] +=$v['amount'];
					if(!isset($rr[$v['reservation_room_id']]['BAR'])){
					  $rr[$v['reservation_room_id']]['BAR'] = $v['amount'];
					}else{
					   $rr[$v['reservation_room_id']]['BAR'] += $v['amount'];
					}
				}
				if($v['type'] == 'LAUNDRY'){
					$rr[$v['reservation_room_id']]['total_amount'] +=$v['amount'];
					if(!isset($rr[$v['reservation_room_id']]['LAUNDRY'])){
					   $rr[$v['reservation_room_id']]['LAUNDRY'] = $v['amount'];
					}else{
						$rr[$v['reservation_room_id']]['LAUNDRY'] += $v['amount'];
					}
				}
				if($v['type'] == 'MINIBAR'){
					$rr[$v['reservation_room_id']]['total_amount'] +=$v['amount'];
					if(!isset($rr[$v['reservation_room_id']]['MINIBAR'])){
					   $rr[$v['reservation_room_id']]['MINIBAR'] = $v['amount'];
					}else{
						$rr[$v['reservation_room_id']]['MINIBAR'] += $v['amount'];
					}
				}
				if(($v['type'] == 'SERVICE')||($v['type']=='ROOM_SERVICE')||($v['type']=='EXTRA_SERVICE')){
					$rr[$v['reservation_room_id']]['total_amount'] +=$v['amount'];
					if(!isset($rr[$v['reservation_room_id']]['SERVICE'])){
					   $rr[$v['reservation_room_id']]['SERVICE'] = $v['amount'];
					}else{
						$rr[$v['reservation_room_id']]['SERVICE'] += $v['amount'];
					}
				}
				if(($v['type']=='EQUIPMENT')){
					$rr[$v['reservation_room_id']]['total_amount'] +=$v['amount'];
					if(!isset($rr[$v['reservation_room_id']]['EQUIPMENT'])){
					   $rr[$v['reservation_room_id']]['EQUIPMENT'] = $v['amount'];
					}else{
						$rr[$v['reservation_room_id']]['EQUIPMENT'] += $v['amount'];
					}
				}
				if(($v['type']=='DISCOUNT')){
					$rr[$v['reservation_room_id']]['total_amount'] -=$v['amount'];
					if(!isset($rr[$v['reservation_room_id']]['DISCOUNT'])){
					   $rr[$v['reservation_room_id']]['DISCOUNT'] = $v['amount'];
					}else{
						$rr[$v['reservation_room_id']]['DISCOUNT'] += $v['amount'];
					}
				}
				if(($v['type']=='DEPOSIT')){
					$rr[$v['reservation_room_id']]['total_amount'] +=$v['amount'];
					if(!isset($rr[$v['reservation_room_id']]['DEPOSIT'])){
					   $rr[$v['reservation_room_id']]['DEPOSIT'] = $v['amount'];
					}else{
						$rr[$v['reservation_room_id']]['DEPOSIT'] += $v['amount'];
					}
				}
			}
		}
		// lay thong tin khach hang 
		$travellers = array();
		if($traveller_id){
			$sql_traveller ='
								SELECT
								traveller.id,
								concat(traveller.first_name,concat(\' \', traveller.last_name)) as full_name,
								 case
								     when gender =\'1\' then \''.Portal::language('Man').'\'
									 else \''.Portal::language('Woman').'\'
								  end gender,
								 traveller.address,
								 traveller.email,
								 traveller.phone,
								 traveller.visa,
								 reservation_traveller.reservation_room_id
								FROM
								traveller 
								inner join reservation_traveller on reservation_traveller.traveller_id = traveller.id
								where 
								   reservation_traveller.reservation_room_id in ('.$traveller_id.')';
							$travellers = DB::fetch_all($sql_traveller);				
		}
		foreach($travellers as $k=>$vl){
			if(isset($rr[$vl['reservation_room_id']])){
				
				$rr[$vl['reservation_room_id']]['travaller_id']=$vl['full_name'];
			}
	    }
		return $rr;
	}
	function draw(){
		$this->map= array();
		if(Url::get('id')){
			$id = Url::get('id');
			$this->map['deposit'] = 0;
			$customer_id = Url::get('customer_id');
			$folio_id = Url::get('folio_id');
			$folios = DB::fetch('select * from folio where id='.$folio_id.'');
			$this->map['items'] =	$this->get_items($customer_id,$id,$folio_id);
			foreach($this->map['items'] as $k => $item){
				if($k=='' && $item['DEPOSIT']){
					$this->map['deposit'] += $item['DEPOSIT'];	
				}
			}
			System::Debug($this->map['items']);
			$this->map['service_amount'] = $folios['service_amount'];
			$this->map['tax_amount'] = $folios['tax_amount'];
			$this->map['total'] = $folios['total'];
		   $this->parse_layout('group_invoice',$this->map);
		}
	}
}
?>