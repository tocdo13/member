<?php
class ManagePortalForm extends Form
{
	function ManagePortalForm()
	{
		Form::Form('ManagePortalForm');
		$this->add('id',new IDType(true,'object_not_exists','ACCOUNT'));
		$this->link_css(Portal::template('core').'/css/admin.css');
	}
	function on_submit()
	{
		if($this->check() and URL::get('confirm'))
		{
			if(Url::get('id')!='#default' and User::is_admin())
			{
				$this->delete($this,$_REQUEST['id']);
				require_once 'packages/core/includes/system/update_privilege.php';
				make_privilege_cache();
				Url::redirect_current(array(  
			 'join_date_start','join_date_end',  'active'=>isset($_GET['active'])?$_GET['active']:'', 'block'=>isset($_GET['block'])?$_GET['block']:'',  'user_id'=>isset($_GET['user_id'])?$_GET['user_id']:'', 
		));
			}
			else
			{
				URL::access_denied();			
			}
		}
	}
	function draw()
	{
		DB::query('
			SELECT 
				ACCOUNT.ID,ACCOUNT.PASSWORD,ACCOUNT.IS_BLOCK,
				ACCOUNT.IS_ACTIVE,ACCOUNT.CREATE_DATE AS JOIN_DATE,
				PARTY.EMAIL ,PARTY.NAME_'.Portal::language().' AS FULL_NAME,
				PARTY.GENDER,PARTY.ADDRESS,PARTY.EMAIL,PARTY.PHONE AS PHONE_NUMBER,
				PARTY.BIRTH_DAY,
				ZONE.NAME_'.Portal::language().' AS ZONE_ID 
			FROM 
			 	ACCOUNT,PARTY,ZONE				
			WHERE
				ACCOUNT.ID(+) = PARTY.USER_ID AND PARTY.ZONE_ID(+) = ZONE.ID
				AND ACCOUNT.ID = \''.URL::sget('id').'\'
		');
		if($row = DB::fetch())
		{
			$row['gender'] = $row['gender']?Portal::language('male'):Portal::language('female');     
		}
		DB::query('
			SELECT
				ACCOUNT_PRIVILEGE.ID
				,ACCOUNT_PRIVILEGE.PARAMETERS
				,PRIVILEGE.id PRIVILEGE_ID_NAME
			FROM
				ACCOUNT_PRIVILEGE,PRIVILEGE
			WHERE
				ACCOUNT_PRIVILEGE.ACCOUNT_ID=\''.$_REQUEST['id'].'\''
		);
		$row['user_privilege_items'] = DB::fetch_all();
		$this->parse_layout('detail',$row);
	}
	function delete(&$form,$portal_id)
	{
		//echo 1;exit();
		// khong nen dung tinh nang nay vi rat nguy hiem
		DB::delete('bar','portal_id=\''.addslashes($portal_id).'\'');
		$bar_reservation = DB::select_all('bar_reservation','portal_id=\''.addslashes($portal_id).'\'');
		foreach($bar_reservation as $key=>$value)
		{
			DB::delete('bar_reservation_product','bar_reservation_id='.$key);
			DB::delete('bar_reservation_table','bar_reservation_id='.$key);			
		}
		DB::delete('bar_reservation','portal_id=\''.addslashes($portal_id).'\'');
		$bars = DB::select_all('bar','portal_id=\''.addslashes($portal_id).'\'');
		foreach($bars as $key=>$value)
		{
			DB::delete('bar_table','bar_id='.$key);
		}
		DB::delete('bar','portal_id=\''.addslashes($portal_id).'\'');		
		DB::delete('customer_rate_policy','portal_id=\''.addslashes($portal_id).'\'');
		$extra_service_invoices = DB::select_all('extra_service_invoice','portal_id=\''.addslashes($portal_id).'\'');
		foreach($extra_service_invoices as $key=>$value)
		{
			DB::delete('extra_service_invoice_detail','invoice_id='.$key);
		}
		$extra_service_invoices = DB::delete('extra_service_invoice','portal_id=\''.addslashes($portal_id).'\'');
		$folios = DB::select_all('folio','portal_id=\''.addslashes($portal_id).'\'');
		foreach($folios as $key=>$value)
		{
			DB::delete('traveller_folio','folio_id='.$key);
		}
		DB::delete('folio','portal_id=\''.addslashes($portal_id).'\'');
		DB::delete('forgot_object','portal_id=\''.addslashes($portal_id).'\'');
		DB::delete('hk_product','portal_id=\''.addslashes($portal_id).'\'');
		DB::delete('housekeeping_equipment','portal_id=\''.addslashes($portal_id).'\'');
		DB::delete('housekeeping_equipment_damaged','portal_id=\''.addslashes($portal_id).'\'');
		$housekeeping_invoices = DB::select_all('housekeeping_invoice','portal_id=\''.addslashes($portal_id).'\'');
		foreach($housekeeping_invoices as $key=>$value)
		{
			DB::delete('housekeeping_invoice_detail','invoice_id='.$key);
		}
		DB::delete('housekeeping_invoice','portal_id=\''.addslashes($portal_id).'\'');
		DB::delete('log','portal_id=\''.addslashes($portal_id).'\'');
		DB::delete('massage_guest','portal_id=\''.addslashes($portal_id).'\'');
		DB::delete('massage_product','portal_id=\''.addslashes($portal_id).'\'');
		$massage_reservation_room = DB::select_all('massage_reservation_room','portal_id=\''.addslashes($portal_id).'\'');
		foreach($massage_reservation_room as $key=>$value)
		{
			DB::delete('massage_reservation','id='.$value['reservation_id']);
			DB::delete('massage_product_consumed','reservation_room_id='.$value['id']);
			DB::delete('massage_staff_room','reservation_room_id='.$value['id']);
		}
		DB::delete('massage_reservation_room','portal_id=\''.addslashes($portal_id).'\'');
		DB::delete('massage_room','portal_id=\''.addslashes($portal_id).'\'');
		DB::delete('massage_staff','portal_id=\''.addslashes($portal_id).'\'');
		
		DB::delete('res_product','portal_id=\''.addslashes($portal_id).'\'');
		DB::delete('res_product_price','portal_id=\''.addslashes($portal_id).'\'');
		
		DB::delete('payment','portal_id=\''.addslashes($portal_id).'\'');
		
		//------------- Xoa minibar product -------------------------
		DB::delete('minibar_product','portal_id=\''.addslashes($portal_id).'\'');
		
		$reservations = DB::select_all('reservation','portal_id=\''.addslashes($portal_id).'\'');
		foreach($reservations as $key=>$value)
		{
			$reservation_rooms = DB::select_all('reservation_room','reservation_id='.$key);
			foreach($reservation_rooms as $r_r_id=>$reservation_room)
			{
				DB::delete('reservation_room_service','reservation_room_id='.$r_r_id);
				DB::delete('reservation_traveller','reservation_room_id='.$r_r_id);
				DB::delete('room_status','reservation_room_id='.$r_r_id);
			}
			DB::delete('reservation_room','reservation_id='.$key);
		}
		DB::delete('reservation','portal_id=\''.addslashes($portal_id).'\'');
		$room_types = DB::select_all('room_type','portal_id=\''.addslashes($portal_id).'\'');
		foreach($room_types as $key=>$value)
		{
			$rooms = DB::select_all('room','room_type_id='.$key);
			foreach($rooms as $room_id=>$room)
			{
				DB::delete('room_product','room_id='.$room_id);
				DB::delete('room_product_detail','room_id='.$room_id);
				DB::delete('telephone_number','room_id='.$room_id);
				DB::delete('minibar','room_id='.$room_id);
			}
			DB::delete('room','room_type_id='.$key);
		}
		DB::delete('room','portal_id=\''.addslashes($portal_id).'\'');
		DB::delete('room_type','portal_id=\''.addslashes($portal_id).'\'');
		$room_levels = DB::select_all('room_level','portal_id=\''.addslashes($portal_id).'\'');
		foreach($room_levels as $key=>$value)
		{
			DB::delete('room_level_rate','room_level_id='.$key);
		}
		DB::delete('swimming_pool_product','portal_id=\''.addslashes($portal_id).'\'');
		DB::delete('telephone_report_daily','portal_id=\''.addslashes($portal_id).'\'');
		DB::delete('tennis_product','portal_id=\''.addslashes($portal_id).'\'');
		DB::delete('tour','portal_id=\''.addslashes($portal_id).'\'');
		// ----------------- warehouse ------------------------------
		$warehouse_invoices = DB::select_all('wh_invoice','portal_id=\''.addslashes($portal_id).'\'');
		foreach($warehouse_invoices as $key=>$value)
		{
			DB::delete('wh_invoice_detail','invoice_id='.$key);
		}
		DB::delete('wh_invoice','portal_id=\''.addslashes($portal_id).'\'');
		DB::delete('wh_product','portal_id=\''.addslashes($portal_id).'\'');
		DB::delete('wh_product_price','portal_id=\''.addslashes($portal_id).'\'');
		DB::delete('wh_start_term_remain','portal_id=\''.addslashes($portal_id).'\'');
		
		DB::delete('night_audit','portal_id=\''.addslashes($portal_id).'\'');
		
		DB::delete('privilege','portal_id=\''.addslashes($portal_id).'\'');
		DB::delete('privilege_moderator','portal_id=\''.addslashes($portal_id).'\'');
		DB::delete('privilege_module','portal_id=\''.addslashes($portal_id).'\'');
		DB::delete('session_user','portal_id=\''.addslashes($portal_id).'\'');
		
		DB::delete('account','id=\''.addslashes($portal_id).'\' AND type=\'PORTAL\'');
		DB::delete('account_privilege','portal_id=\''.addslashes($portal_id).'\'');
		DB::delete('account_related','parent_id=\''.addslashes($portal_id).'\'');		
		DB::delete('party','user_id=\''.addslashes($portal_id).'\' AND type=\'PORTAL\'');
		DB::delete('party','portal_id=\''.addslashes($portal_id).'\'');
	}
}
?>