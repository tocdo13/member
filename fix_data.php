<?php
define('DEVELOPING',false);
define( 'ROOT_PATH', strtr(dirname( __FILE__ ) ."/",array('\\'=>'/')));
require_once ROOT_PATH.'packages/core/includes/system/config.php';
if(User::is_admin()){
	/* UPDATE GIA SAN PHAM NHA HANG
	$sql = '
		UPDATE RES_PRODUCT SET PRICE = PRICE*20000
	';
	DB::query($sql);
	/*
	// UPDATE GIA SAN PHAM BUONG
	$sql = '
		UPDATE HK_PRODUCT SET PRICE = PRICE*21000
	';
	DB::query($sql);
	$sql = '
		UPDATE room_level SET PRICE = PRICE*21000
	';
	DB::query($sql);
	$sql = '
		UPDATE ROOM_LEVEL_RATE SET rate = rate*21000
	';
	DB::query($sql);
	$sql = '
		UPDATE CUSTOMER_RATE_POLICY 
			SET 
				RATE_1_ADULT = RATE_1_ADULT*21000,
				RATE_2_ADULTS = RATE_2_ADULTS*21000,
				RATE_3_ADULTS = RATE_3_ADULTS*21000,
				RATE_4_ADULTS = RATE_4_ADULTS*21000,
				RATE_EXTRA_ADULTS = RATE_EXTRA_ADULTS*21000,
				RATE_1_CHILD = RATE_1_CHILD*21000,
				RATE_2_CHILDREN = RATE_2_CHILDREN*21000,
				RATE_3_CHILDREN = RATE_3_CHILDREN*21000,
				RATE_4_CHILDREN = RATE_4_CHILDREN*21000,
				RATE_EXTRA_CHILDREN = RATE_EXTRA_CHILDREN*21000
	';
	DB::query($sql);*/
	/*DB::query('
		DELETE FROM
			(SELECT
				reservation_traveller.*
			FROM
				reservation_traveller 
				LEFT OUTER JOIN reservation_room ON reservation_room.id = reservation_traveller.reservation_room_id
			WHERE
				reservation_room.id is null
				
		) rt
		
	');*/
	/*DB::query('
		DELETE FROM
			(SELECT
				reservation.id
			FROM
				reservation 
				LEFT OUTER JOIN reservation_room ON reservation_room.reservation_id = reservation.id
			WHERE
				reservation_room.id is null
		)');
	$sql = 'SELECT
				count(*) as acount
			FROM
				reservation 
				LEFT OUTER JOIN reservation_room ON reservation_room.reservation_id = reservation.id
			WHERE
				reservation_room.id is null';
	echo DB::fetch($sql,'acount');*/
	//DB::query('delete from reservation_room where status = \'CHECKOUT\'');
	//DB::query('delete from room_status where status = \'OCUPIED\'');
	/*DB::query('delete from reservation_room where status = \'CHECKIN\'');
	$sql = 'SELECT
				count(*) as acount
			FROM 
				reservation_room
			WHERE
				reservation_room.status = \'CHECKIN\'';
	echo DB::fetch($sql,'acount');*/
	//DB::query('truncate table reservation');
	//DB::query('truncate table reservation_room');
	//DB::query('truncate table reservation_traveller');
	//DB::query('truncate table reservation_room_service');
	//DB::query('truncate table traveller');
	//DB::query('truncate table traveller_comment');
	//DB::query('truncate table room_status');
	
	/*DB::query('truncate table TELEPHONE_REPORT_DAILY');
	//DB::query('truncate table res_product');	
	DB::query('truncate table bar_reservation');
	DB::query('truncate table bar_reservation_cancel');
	DB::query('truncate table bar_reservation_product');
	DB::query('truncate table bar_reservation_table');
	
	DB::query('truncate table HK_WH_INVOICE');
	DB::query('truncate table HK_WH_INVOICE_DETAIL');
	DB::query('truncate table HOUSEKEEPING_INVOICE');
	DB::query('truncate table HOUSEKEEPING_INVOICE_DETAIL');
	
	DB::query('truncate table KA_RESERVATION');
	DB::query('truncate table KA_RESERVATION_CANCEL');
	DB::query('truncate table KA_RESERVATION_PRODUCT');
	DB::query('truncate table KA_RESERVATION_ROOM');
	
	DB::query('truncate table HOUSEKEEPING_EQUIPMENT');
	DB::query('truncate table HOUSEKEEPING_EQUIPMENT_DAMAGED');
	
	DB::query('truncate table MASSAGE_RESERVATION');
	DB::query('truncate table MASSAGE_RESERVATION_ROOM');
	DB::query('truncate table MASSAGE_PRODUCT_CONSUMED');
	DB::query('truncate table MASSAGE_STAFF_ROOM');

	DB::query('truncate table SWIMMING_POOL_RESERVATION');
	DB::query('truncate table SWIMMING_POOL_RESERVATION_POOL');

	DB::query('truncate table TENNIS_RESERVATION');
	DB::query('truncate table TENNIS_RESERVATION_COURT');
	
	DB::query('truncate table SHOP_INVOICE');
	DB::query('truncate table SHOP_INVOICE_DETAIL');
	
	DB::query('truncate table WH_INVOICE');
	DB::query('truncate table WH_INVOICE_DETAIL');
	DB::query('truncate table pay_by_currency');
	
	DB::query('truncate table ROOM_PRODUCT');
	DB::query('truncate table ROOM_PRODUCT_DETAIL');
	
	DB::query('truncate table EXTRA_SERVICE_INVOICE');
	DB::query('truncate table EXTRA_SERVICE_INVOICE_DETAIL');
	
	DB::query('truncate table FORGOT_OBJECT');
	
	DB::query('truncate table night_audit');
	
	echo '<h3>Neway: Deleted all successfull...!</h3>';
	DB::query('truncate table log');*/
}
?>