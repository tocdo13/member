<?php 
class SplitTableDB
{
	function check_bar_reservation($table_id)
	{
		$in_date = date('d/m/Y');
		return DB::fetch('
			SELECT 
				BAR_RESERVATION.ID
			FROM
				BAR_RESERVATION
				INNER JOIN BAR_RESERVATION_TABLE ON BAR_RESERVATION_TABLE.BAR_RESERVATION_ID = 	BAR_RESERVATION.ID
			WHERE
				BAR_RESERVATION_TABLE.TABLE_ID = '.$table_id.'	
				AND BAR_RESERVATION.STATUS <> \'CHECKOUT\' 
				AND BAR_RESERVATION.STATUS <> \'CANCEL\'
				AND bar_reservation.arrival_time >='.Date_Time::to_time($in_date).' 
				AND bar_reservation.arrival_time <'.(Date_Time::to_time($in_date)+24*3600).'
		','id');
	}
	function get_table_use($in_date = false)
	{
		if(!$in_date)
		{
			$in_date = date('d/m/Y');
		}
		$cond = (Session::get('bar_id'))?' AND BAR_TABLE.bar_id = '.Session::get('bar_id').'':'';
		$bar_reservation = DB::fetch_all('
			SELECT
				BAR_RESERVATION.ID,
				CONCAT(BAR_RESERVATION.ID,\' \') as name
			FROM
				BAR_RESERVATION
				INNER JOIN BAR_RESERVATION_TABLE ON BAR_RESERVATION_TABLE.BAR_RESERVATION_ID = 	BAR_RESERVATION.ID
				INNER JOIN BAR_TABLE ON BAR_TABLE.ID = BAR_RESERVATION_TABLE.TABLE_ID
			WHERE
				BAR_RESERVATION.STATUS = \'CHECKIN\' 
				AND bar_reservation.arrival_time >='.Date_Time::to_time($in_date).' 
				AND bar_reservation.arrival_time <'.(Date_Time::to_time($in_date)+24*3600).'
				'.$cond.'
			ORDER BY
				BAR_TABLE.NAME');		
		$table_use = DB::fetch_all('
			SELECT
				BAR_RESERVATION_TABLE.ID
				,BAR_RESERVATION_TABLE.TABLE_ID
				,BAR_RESERVATION.ID as bar_reservation_id
				,BAR_TABLE.name as bar_name
			FROM
				BAR_RESERVATION
				INNER JOIN BAR_RESERVATION_TABLE ON BAR_RESERVATION_TABLE.BAR_RESERVATION_ID = 	BAR_RESERVATION.ID
				INNER JOIN BAR_TABLE ON BAR_TABLE.ID = BAR_RESERVATION_TABLE.TABLE_ID
			WHERE
				BAR_RESERVATION.STATUS = \'CHECKIN\' 
				AND bar_reservation.arrival_time >='.Date_Time::to_time($in_date).' 
				AND bar_reservation.arrival_time <'.(Date_Time::to_time($in_date)+24*3600).'
				'.$cond.'
			ORDER BY
				BAR_TABLE.NAME');
				
		foreach($table_use as $key => $value){
			if(isset($bar_reservation[$value['bar_reservation_id']]) and ($value['bar_reservation_id'] == $bar_reservation[$value['bar_reservation_id']]['id'])){
				$bar_reservation[$value['bar_reservation_id']]['name']	.= '-'.$value['bar_name'];
			}
		}
		return $bar_reservation;
	}
	function get_table($cond='1=1')
	{		
	$cond .= (Session::get('bar_id'))?' AND BAR_TABLE.bar_id = '.Session::get('bar_id').'':'';
		return DB::fetch_all('
			SELECT
				BAR_TABLE.ID
				,BAR_TABLE.NAME
			FROM
				BAR_TABLE
			WHERE '.$cond.'
				AND BAR_TABLE.ID not in(
					SELECT 
						bar_reservation_table.table_id
					FROM 	
						bar_reservation_table
						INNER JOIN bar_reservation ON  bar_reservation_table.bar_reservation_id = bar_reservation.id
					WHERE 
						bar_reservation.status = \'CHECKIN\' or bar_reservation.status = \'BOOKED\' or bar_reservation.status = \'RESERVATION\'
				)		
			ORDER BY
				BAR_TABLE.NAME	
		');
	}
	function get_bar_table($reservation_id = 0)
	{		
		return DB::fetch_all('
			SELECT
				BAR_RESERVATION_TABLE.ID
				,BAR_TABLE.NAME
				,BAR_TABLE.ID AS TABLE_ID
			FROM
				BAR_RESERVATION_TABLE
				INNER JOIN BAR_TABLE ON BAR_TABLE.ID = BAR_RESERVATION_TABLE.TABLE_ID
			WHERE
				BAR_RESERVATION_TABLE.BAR_RESERVATION_ID = '.$reservation_id.'
			ORDER BY
				BAR_TABLE.NAME	
		');
	}	
	function get_order_menu($bar_reservation_id)
	{
	   $sql = '
			SELECT
				BAR_RESERVATION_PRODUCT.ID
				-- start: KID thay (1)->(2) de hien thi ten cua cac extra
                --,PRODUCT.NAME_'.Portal::language().' as name (1)
                ,CASE 
                WHEN (BAR_RESERVATION_PRODUCT.PRODUCT_ID = \'SOUTSIDE\' OR BAR_RESERVATION_PRODUCT.PRODUCT_ID = \'FOUTSIDE\' OR BAR_RESERVATION_PRODUCT.PRODUCT_ID = \'DOUTSIDE\')
				THEN BAR_RESERVATION_PRODUCT.NAME
                ELSE PRODUCT.NAME_'.Portal::language().' 
                END as name --(2)
                --end: KID thay (1)->(2) de hien thi ten cua cac extra
				,BAR_RESERVATION_PRODUCT.QUANTITY
                ,BAR_RESERVATION_PRODUCT.printed,
                BAR_RESERVATION_PRODUCT.product_id
			FROM
				BAR_RESERVATION_PRODUCT
				LEFT OUTER JOIN PRODUCT ON PRODUCT.ID = BAR_RESERVATION_PRODUCT.PRODUCT_ID
			WHERE
				BAR_RESERVATION_ID=	'.$bar_reservation_id.' AND BAR_RESERVATION_PRODUCT.bar_set_menu_id is NULL
		';
       $result = DB::fetch_all($sql);
       
       $sql = "SELECT * FROM bar_set_menu";
       $set_menu = DB::fetch_all($sql);
       foreach($result as $key=>$value)
       {
         foreach($set_menu as $k=>$v)
         {
            if($value['product_id']==$v['code'])
            {
                unset($result[$key]);
                break;
            }
         }
       } 
       
		return $result;
	}
	static function get_code_in_day($arrival_time,$bar_id){
		$time =  Date_Time::to_time(date('d/m/Y',$arrival_time));
		$bar_code = DB::fetch('select code from bar where id = '.$bar_id.'','code');
		$sql = 'SELECT code from bar_reservation where id=(select max(id) from bar_reservation where arrival_time >='.$time.' and arrival_time <'.($time + (24*3600)).' and (status=\'CHECKIN\' or status=\'CHECKOUT\')) ';
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
		$code_new = $bar_code.'-'.date('d',$arrival_time).date('m',$arrival_time).date('y',$arrival_time).'-'.$code_max;		
		//echo $code_new; //exit();
		return $code_new;
	}
}
?>