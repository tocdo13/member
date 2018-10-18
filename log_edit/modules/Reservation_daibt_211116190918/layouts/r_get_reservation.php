<?php
	define( 'ROOT_PATH', strtr(dirname( __FILE__ ) ."/",array('\\'=>'/')));
	require_once 'packages/core/includes/system/config.php';
	header("content-type: application/x-javascript");
	$sql = '
		select
			reservation_room.id
			,reservation_room.price
			,reservation_room.status as old_status
			,reservation_room.status
			,reservation_room.adult
			,reservation_room.child
			,reservation_room.time_in
			,reservation_room.time_out
			,reservation_room.arrival_time
			,reservation_room.departure_time
			,reservation_room.total_amount
			,reservation_room.reduce_balance
			,reservation_room.reduce_amount
			,reservation_room.deposit
			,reservation_room.tax_rate ,reservation_room.service_rate
			,reservation_room.room_level_id
			,room_level.brief_name as room_level_name
			,reservation_room.room_id
			,reservation_room.room_id AS room_id_old
			,CASE WHEN room.name is null THEN reservation_room.temp_room ELSE room.name END room_name
			,CASE WHEN room.name is null THEN reservation_room.temp_room ELSE room.name END room_name_old
			,reservation_room.traveller_id
			,reservation_room.reservation_id
			,reservation_room.foc
			,reservation_room.foc_all
			,reservation_room.reservation_type_id
			,reservation_room.confirm
			,reservation_room.closed
			,reservation_room.early_checkin
			,reservation_room.early_arrival_time
			,reservation_room.verify_dayuse
			,reservation_room.net_price
			,reservation_room.extra_bed
			,to_char(reservation_room.extra_bed_from_date,\'DD/MM/YYYY\') as extra_bed_from_date
			,to_char(reservation_room.extra_bed_to_date,\'DD/MM/YYYY\') as extra_bed_to_date
			,reservation_room.extra_bed_rate
			,reservation_room.baby_cot
			,to_char(reservation_room.baby_cot_from_date,\'DD/MM/YYYY\') as baby_cot_from_date
			,to_char(reservation_room.baby_cot_to_date,\'DD/MM/YYYY\') as baby_cot_to_date
			,reservation_room.baby_cot_rate
			,reservation_room.net_price
		from
			reservation_room
			left outer join room on room.id=reservation_room.room_id
			left outer join room_level on room_level.id=reservation_room.room_level_id
			left outer join room_status on room_status.reservation_room_id=reservation_room.id
			left outer join payment_type on payment_type.id=reservation_room.payment_type_id
		where
			reservation_room.reservation_id='.Url::iget('id').'
			'.(URL::get('reservation_room_id')?' and reservation_room.id=\''.URL::get('reservation_room_id').'\'':'').'
		order by
			reservation_room.time_in asc';
	$mi_reservation_room = DB::fetch_all($sql);
	$sql_traveller = '
				select
					reservation_traveller.id
					,reservation_traveller.id as reservation_traveller_id
					,reservation_traveller.pa18
					,reservation_traveller.reservation_room_id
					,reservation_room.reservation_id
					,reservation_traveller.status
					,to_char(reservation_traveller.EXPIRE_DATE_OF_VISA,\'DD/MM/YYYY\') as visa_expired
					,traveller.first_name ,traveller.last_name
					,DECODE(traveller.gender,1,\'Nữ\',\'Nam\') as gender,
					to_char(traveller.birth_date,\'DD/MM/YYYY\') as birth_date,
					traveller.passport ,traveller.visa ,reservation_traveller.special_request as note ,
					traveller.phone ,traveller.fax ,traveller.address ,traveller.email
					,country.code_1 as nationality_id
					,country.name_'.Portal::language().' as nationality_name
					,CASE WHEN reservation_room.room_id is not null THEN room.name ELSE reservation_room.temp_room END as mi_traveller_room_name
					,CASE WHEN reservation_room.room_id is not null THEN concat(room.id,concat(\'-\',to_char(reservation_room.departure_time,\'DD/MM/YYYY\'))) ELSE concat(reservation_room.temp_room,concat(\'-\',to_char(reservation_room.departure_time,\'DD/MM/YYYY\'))) END as traveller_room_id
					,DECODE(reservation_room.traveller_id,reservation_traveller.traveller_id,1,0) as traveller_id
					,traveller.id as traveller_id_
					,guest_type.name as traveller_level_name
				from
					reservation_traveller
					inner join traveller on traveller.id=reservation_traveller.traveller_id
					left outer join reservation_room on reservation_room.id=reservation_traveller.reservation_room_id
					left outer join room on reservation_room.room_id=room.id
					left outer join country on traveller.nationality_id=country.id
					INNER JOIN guest_type ON guest_type.id = traveller.traveller_level_id
				where
					reservation_room.reservation_id='.Url::iget('id').'
				order by
						reservation_traveller.id asc
					';//.(URL::get('reservation_room_id')?' and reservation_room.id=\''.URL::get('reservation_room_id').'\'':'').'
			$mi_travellers = DB::fetch_all($sql_traveller);
	$room_status = DB::fetch_all('
		SELECT
			CONCAT(reservation_room_id,CONCAT(\'_\',in_date)) AS id,
			change_price,reservation_room_id,in_date,room_id,room_status.closed_time
		FROM
			room_status
		WHERE
			reservation_id='.Url::iget('id').' AND status<>\'AVAILABLE\' AND status<>\'CANCEL\'
		ORDER BY
			in_date
	');
	$change_price_arr = array();
	require_once 'packages/hotel/packages/reception/modules/includes/get_reservation.php';
	$reservation_arr = get_reservation(Url::iget('id'),false,true);
	foreach($mi_reservation_room as $key=>$value)
	{
		$mi_reservation_room[$key]['time_in'] = date('H:i',$value['time_in']);
		$mi_reservation_room[$key]['time_out'] = date('H:i',$value['time_out']);
		$mi_reservation_room[$key]['time_in_in'] = $value['time_in'];
		$mi_reservation_room[$key]['time_out_out'] = $value['time_out'];
		$mi_reservation_room[$key]['arrival_time'] = Date_Time::convert_orc_date_to_date($value['arrival_time'],'/');
		$mi_reservation_room[$key]['early_arrival_time'] = Date_Time::convert_orc_date_to_date($value['early_arrival_time'],'/');
		$mi_reservation_room[$key]['departure_time'] = Date_Time::convert_orc_date_to_date($value['departure_time'],'/');
		$mi_reservation_room[$key]['departure_time_old'] = $mi_reservation_room[$key]['departure_time'];
		$mi_reservation_room[$key]['reduce_amount'] = System::display_number($value['reduce_amount']);
		$mi_reservation_room[$key]['extra_bed_rate'] = System::display_number($value['extra_bed_rate']);
		$mi_reservation_room[$key]['baby_cot_rate'] = System::display_number($value['baby_cot_rate']);
		$mi_reservation_room[$key]['price'] = System::display_number($value['price']);
		$mi_reservation_room[$key]['adult'] = $value['adult'];
		$mi_reservation_room[$key]['child'] = $value['child'];
		$mi_reservation_room[$key]['total_amount'] = System::display_number($value['total_amount']);
		$mi_reservation_room[$key]['reduce_balance'] = System::display_number($value['reduce_balance']);
		$mi_reservation_room[$key]['deposit'] = $value['deposit'];
		$change_price_arr = array();
		$change_price_closed_time_arr = array();
		foreach($room_status as $k=>$v){
			if($v['reservation_room_id'] == $value['id']){
				if($v['in_date']<>$value['departure_time']){
					$change_price_arr[Date_Time::convert_orc_date_to_date($v['in_date'],'/')] = $v['change_price'];
				}
				$change_price_closed_time_arr[Date_Time::convert_orc_date_to_date($v['in_date'],'/')] = $v['closed_time'];
			}
		}
		$mi_reservation_room[$key]['change_price_arr'] = $change_price_arr;
		$mi_reservation_room[$key]['change_price_closed_time_arr'] = $change_price_closed_time_arr;
		$pay_by_currency_arr = DB::fetch_all('SELECT currency_id as id,amount,bill_id FROM pay_by_currency WHERE bill_id = '.$key.' AND type=\'RESERVATION\'');
		foreach($pay_by_currency_arr as $k=>$v){
			$pay_by_currency_arr[$k]['amount'] = System::display_number($v['amount']);
		}
		$mi_reservation_room[$key]['currency_arr'] = $pay_by_currency_arr;
		$mi_reservation_room[$key]['service_arr'] = DB::fetch_all('SELECT service_id as id,amount,reservation_room_id FROM reservation_room_service WHERE reservation_room_id='.$key.' ORDER BY type');
		//
		$first = true;
		$last = false;
		$stt = 0;
		foreach($mi_travellers as $t =>$traveller){
			if($traveller['reservation_room_id'] == $key && $traveller['status']=='CHECKIN'){
				if($first)
				{
					$mi_reservation_room[$key]['list_traveller'] = '<fieldset class="traveller_compact"><legend class="sub-title">'.Portal::language('traveller').' - '.$traveller['mi_traveller_room_name'].':</legend>';
					$mi_reservation_room[$key]['list_traveller'] .= '<table width="100%" class="tbl_traveller_compact" cellpadding="2" border="1" bordercolor="#CCCCCC" style="border-collapse:collapse;"><tr bgcolor="#f0f0f0">';
					$mi_reservation_room[$key]['list_traveller'] .= '<th width="20" align="center">'.Portal::language('stt').'</th>';
					$mi_reservation_room[$key]['list_traveller'] .= '<th width="150">'.Portal::language('full_name').'</th>';
					$mi_reservation_room[$key]['list_traveller'] .= '<th width="80">'.Portal::language('passpost').'</th>';
					$mi_reservation_room[$key]['list_traveller'] .= '<th width="80">'.Portal::language('visa').'</th>';
					$mi_reservation_room[$key]['list_traveller'] .= '<th width="80">'.Portal::language('visa_expired').'</th>';
					$mi_reservation_room[$key]['list_traveller'] .= '<th width="50">'.Portal::language('gender').'</th>';
					$mi_reservation_room[$key]['list_traveller'] .= '<th width="80">'.Portal::language('birth_date').'</th>';
					$mi_reservation_room[$key]['list_traveller'] .= '<th>'.Portal::language('nationaltity').'</th>';
					$mi_reservation_room[$key]['list_traveller'] .= '<th width="100">'.Portal::language('traveller_level').'</th><th width="70"></th></tr>';
				}
				$traveller_old_id = $t;
				$stt++;
				$first = false;
				$mi_reservation_room[$key]['list_traveller'].= '<tr bgcolor="#ffffff"><td align="center">'.$stt.'.</td>';
				$mi_reservation_room[$key]['list_traveller'].= '<td><a target="_blank" href="'.Url::build('traveller',array('id'=>$traveller['traveller_id_'])).'">'.$traveller['first_name'].' '.$traveller['last_name'].'</a></td>';
				$mi_reservation_room[$key]['list_traveller'].= '<td>'.$traveller['passport'].'</td>';
				$mi_reservation_room[$key]['list_traveller'].= '<td>'.$traveller['visa'].'</td>';
				$mi_reservation_room[$key]['list_traveller'].= '<td>'.$traveller['visa_expired'].'</td>';
				$mi_reservation_room[$key]['list_traveller'].= '<td>'.$traveller['gender'].'</td>';
				$mi_reservation_room[$key]['list_traveller'].= '<td>'.$traveller['birth_date'].'</td>';
				$mi_reservation_room[$key]['list_traveller'].= '<td>'.$traveller['nationality_name'].'</td>';
				$mi_reservation_room[$key]['list_traveller'].= '<td>'.$traveller['traveller_level_name'].'</td>';
				$mi_reservation_room[$key]['list_traveller'].= '<td><input name="folio_'.$traveller['id'].'" type="button" id="folio_'.$traveller['id'].'" onclick="windowOpenUrlTraveller(\''.$traveller['reservation_room_id'].'\',\''.$traveller['reservation_id'].'\',\''.$traveller['id'].'\');" value="Folio" title="Create folio"></td>';
				$mi_reservation_room[$key]['list_traveller'].= '<tr>';
			}
		}
		if(isset($mi_reservation_room[$key]['list_traveller']))
		{
			$mi_reservation_room[$key]['list_traveller'].='</fieldset>';
		}
		//
	}
	$mi_reservation_room;
	echo 'var mi_reservation_room_arr = '.String::array2js($mi_reservation_room).';';
	DB::close();
?>