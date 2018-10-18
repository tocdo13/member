<?php 
class SplitTableDB
{
	function check_karaoke_reservation($table_id)
	{
		$in_date = date('d/m/Y');
		return DB::fetch('
			SELECT 
				karaoke_RESERVATION.ID
			FROM
				karaoke_RESERVATION
				INNER JOIN karaoke_RESERVATION_TABLE ON karaoke_RESERVATION_TABLE.karaoke_RESERVATION_ID = 	karaoke_RESERVATION.ID
			WHERE
				karaoke_RESERVATION_TABLE.TABLE_ID = '.$table_id.'	
				AND karaoke_RESERVATION.STATUS <> \'CHECKOUT\' 
				AND karaoke_RESERVATION.STATUS <> \'CANCEL\'
				AND karaoke_reservation.arrival_time >='.Date_Time::to_time($in_date).' 
				AND karaoke_reservation.arrival_time <'.(Date_Time::to_time($in_date)+24*3600).'
		','id');
	}
	function get_table_use($in_date = false)
	{
		if(!$in_date)
		{
			$in_date = date('d/m/Y');
		}
		$cond = (Session::get('karaoke_id'))?' AND karaoke_TABLE.karaoke_id = '.Session::get('karaoke_id').'':'';
		$karaoke_reservation = DB::fetch_all('
			SELECT
				karaoke_RESERVATION.ID,
				CONCAT(karaoke_RESERVATION.ID,\' \') as name
			FROM
				karaoke_RESERVATION
				INNER JOIN karaoke_RESERVATION_TABLE ON karaoke_RESERVATION_TABLE.karaoke_RESERVATION_ID = 	karaoke_RESERVATION.ID
				INNER JOIN karaoke_TABLE ON karaoke_TABLE.ID = karaoke_RESERVATION_TABLE.TABLE_ID
			WHERE
				karaoke_RESERVATION.STATUS = \'CHECKIN\' 
				AND karaoke_reservation.arrival_time >='.Date_Time::to_time($in_date).' 
				AND karaoke_reservation.arrival_time <'.(Date_Time::to_time($in_date)+24*3600).'
				'.$cond.'
			ORDER BY
				karaoke_TABLE.NAME');		
		$table_use = DB::fetch_all('
			SELECT
				karaoke_RESERVATION_TABLE.ID
				,karaoke_RESERVATION_TABLE.TABLE_ID
				,karaoke_RESERVATION.ID as karaoke_reservation_id
				,karaoke_TABLE.name as karaoke_name
			FROM
				karaoke_RESERVATION
				INNER JOIN karaoke_RESERVATION_TABLE ON karaoke_RESERVATION_TABLE.karaoke_RESERVATION_ID = 	karaoke_RESERVATION.ID
				INNER JOIN karaoke_TABLE ON karaoke_TABLE.ID = karaoke_RESERVATION_TABLE.TABLE_ID
			WHERE
				karaoke_RESERVATION.STATUS = \'CHECKIN\' 
				AND karaoke_reservation.arrival_time >='.Date_Time::to_time($in_date).' 
				AND karaoke_reservation.arrival_time <'.(Date_Time::to_time($in_date)+24*3600).'
				'.$cond.'
			ORDER BY
				karaoke_TABLE.NAME');
				
		foreach($table_use as $key => $value){
			if(isset($karaoke_reservation[$value['karaoke_reservation_id']]) and ($value['karaoke_reservation_id'] == $karaoke_reservation[$value['karaoke_reservation_id']]['id'])){
				$karaoke_reservation[$value['karaoke_reservation_id']]['name']	.= '-'.$value['karaoke_name'];
			}
		}
		return $karaoke_reservation;
	}
	function get_table($cond='1=1')
	{		
	$cond .= (Session::get('karaoke_id'))?' AND karaoke_TABLE.karaoke_id = '.Session::get('karaoke_id').'':'';
		return DB::fetch_all('
			SELECT
				karaoke_TABLE.ID
				,karaoke_TABLE.NAME
			FROM
				karaoke_TABLE
			WHERE '.$cond.'
				AND karaoke_TABLE.ID not in(
					SELECT 
						karaoke_reservation_table.table_id
					FROM 	
						karaoke_reservation_table
						INNER JOIN karaoke_reservation ON  karaoke_reservation_table.karaoke_reservation_id = karaoke_reservation.id
					WHERE 
						karaoke_reservation.status = \'CHECKIN\' or karaoke_reservation.status = \'BOOKED\' or karaoke_reservation.status = \'RESERVATION\'
				)		
			ORDER BY
				karaoke_TABLE.NAME	
		');
	}
	function get_karaoke_table($reservation_id = 0)
	{		
		return DB::fetch_all('
			SELECT
				karaoke_RESERVATION_TABLE.ID
				,karaoke_TABLE.NAME
				,karaoke_TABLE.ID AS TABLE_ID
			FROM
				karaoke_RESERVATION_TABLE
				INNER JOIN karaoke_TABLE ON karaoke_TABLE.ID = karaoke_RESERVATION_TABLE.TABLE_ID
			WHERE
				karaoke_RESERVATION_TABLE.karaoke_RESERVATION_ID = '.$reservation_id.'
			ORDER BY
				karaoke_TABLE.NAME	
		');
	}	
	function get_order_menu($karaoke_reservation_id)
	{
		return DB::fetch_all('
			SELECT
				karaoke_RESERVATION_PRODUCT.ID
				,PRODUCT.NAME_'.Portal::language().' as name
				,karaoke_RESERVATION_PRODUCT.QUANTITY
			FROM
				karaoke_RESERVATION_PRODUCT
				LEFT OUTER JOIN PRODUCT ON PRODUCT.ID = karaoke_RESERVATION_PRODUCT.PRODUCT_ID
			WHERE
				karaoke_RESERVATION_ID=	'.$karaoke_reservation_id.'
		');
	}
	static function get_code_in_day($arrival_time,$karaoke_id){
		$time =  Date_Time::to_time(date('d/m/Y',$arrival_time));
		$karaoke_code = DB::fetch('select code from karaoke where id = '.$karaoke_id.'','code');
		$sql = 'SELECT code from karaoke_reservation where id=(select max(id) from karaoke_reservation where arrival_time >='.$time.' and arrival_time <'.($time + (24*3600)).' and (status=\'CHECKIN\' or status=\'CHECKOUT\')) ';
		$code = DB::fetch($sql,'code');
		if($code){	
			$location_max = strrpos($code,'-') +1;
			$code_max = substr($code,$location_max) + 1;
			if(strlen($code_max) == 1){
				$code_max = '0'.$code_max;	
			}	
		}else{
			$code_max = '01';	
		}
		$code_new = $karaoke_code.'-'.date('d',$arrival_time).date('m',$arrival_time).date('y',$arrival_time).'-'.$code_max;		
		//echo $code_new; //exit();
		return $code_new;
	}
}
?>