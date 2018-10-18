<?php
class MassageReservationReportForm extends Form
{
	function MassageReservationReportForm()
	{
		Form::Form('MassageReservationReportForm');
		$this->link_css('packages/hotel/'.Portal::template('massage').'/css/style.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
        $this->link_js('packages/hotel/packages/reception/modules/includes/jquery.windows-engine.js');
		$this->link_css("packages/hotel/skins/default/css/jquery.windows-engine.css");
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		if(Url::get('item_check') and User::can_delete(false,ANY_CATEGORY))
        {
			$items = Url::get('item_check');
			foreach($items as $value)
            {
				if(DB::exists('select id from massage_reservation_room where id = '.$value))
                {
					DB::delete('massage_staff_room','reservation_room_id='.$value);				
					DB::delete('massage_product_consumed','reservation_room_id='.$value);				
					DB::delete('massage_reservation_room','id='.$value);
                    /**
                     * LDD: Xoa them trong bang payment
                     * Khong sua j khac dau Thu nhe
                     * =)) 
                    **/
                    DB::delete('payment','bill_id='.$value. ' and type = \'SPA\'');
					$aciton = 'delete';
					$title = 'Delete massage invoice';
					$description = 'Delete massage invoice id: '.$value;
					System::log($aciton,$title,$description,$value);
				}
			}
			//Url::redirect_current();
		}
	}
	function draw()
	{
		$this->map = array();
		$item_per_page = 100;
		require_once 'packages/core/includes/utils/paging.php';
		$cond = '
			1 = 1 and massage_reservation_room.portal_id=\''.PORTAL_ID.'\'
					'.(Url::get('date')?' AND massage_product_consumed.time_in >= '.Date_Time::to_time(Url::get('date')).' AND massage_product_consumed.time_in <= '.(Date_Time::to_time(Url::get('date'))+24*3600):'').'
					'.(Url::get('staff_id')?' AND massage_staff_room.staff_id = '.Url::iget('staff_id').'':'').'
					'.(Url::get('room_id')?' AND massage_room.id LIKE \'%'.Url::get('room_id').'%\'':'').'
					'.(Url::get('hotel_room')?' AND room.name LIKE \'%'.Url::get('hotel_room').'%\'':'').'
		';
		$sql = '
			SELECT
				count(*) AS acount
			FROM
				massage_product_consumed
				INNER JOIN room ON room.id = massage_product_consumed.room_id
				LEFT OUTER JOIN massage_reservation_room ON massage_reservation_room.id = massage_product_consumed.reservation_room_id
				INNER JOIN massage_room ON massage_room.id = massage_product_consumed.room_id
				LEFT OUTER JOIN massage_guest ON massage_guest.id = massage_reservation_room.guest_id
				LEFT OUTER JOIN massage_staff_room ON massage_staff_room.reservation_room_id = massage_reservation_room.id
			WHERE
				'.$cond.'
		';
		$this->map['total'] =  DB::fetch($sql,'acount');
		$this->map['paging'] =  paging($this->map['total'],$item_per_page,10);
		$sql = 'SELECT massage_staff.* FROM massage_staff';
		$staffs = DB::fetch_all($sql);
		$this->map['staff_id_list'] = array(''=>Portal::language('all'))+String::get_list($staffs,'full_name');
		$sql_r = 'SELECT massage_room.* FROM massage_room';
		$rooms = DB::fetch_all($sql_r);
		$this->map['room_id_list'] = array(''=>Portal::language('all'))+String::get_list($rooms,'name');
        $sql = '
			SELECT * FROM
			(
				SELECT 
					massage_product_consumed.*,
                    massage_guest.full_name as guest_name,
					massage_room.name as room_name,
                    reservation_room.status as reservation_room_status,
                    concat(TRAVELLER.FIRST_NAME, concat(\' \',concat(TRAVELLER.LAST_NAME,concat(\' \',massage_reservation_room.full_name))))  as TRAVELLER_name,
                    CASE
                        WHEN
                            massage_reservation_room.discount is null
                        THEN
                            (massage_product_consumed.price*massage_product_consumed.quantity)
                        ELSE
                            (massage_product_consumed.price*massage_product_consumed.quantity)- (massage_product_consumed.price*massage_product_consumed.quantity)*massage_reservation_room.discount*0.01
                    END total_amount,
                    massage_reservation_room.total_amount as total_amount_massage, 
                    room.name as hotel_room_name,
                    massage_reservation_room.package_id,
                    massage_reservation_room.hotel_reservation_room_id as ht_reservation_room_id,
                    reservation_room.reservation_id as mrr_id,
                    massage_reservation_room.id as massage_rr_id,
					ROW_NUMBER() OVER (ORDER BY massage_product_consumed.reservation_room_id desc ) as rownumber
				FROM
					massage_product_consumed
					LEFT OUTER JOIN massage_reservation_room ON massage_reservation_room.id = massage_product_consumed.reservation_room_id
					LEFT OUTER JOIN massage_room ON massage_room.id = massage_product_consumed.room_id
					LEFT OUTER JOIN massage_guest ON massage_guest.id = massage_reservation_room.guest_id
					LEFT OUTER JOIN massage_staff_room ON massage_staff_room.reservation_room_id = massage_reservation_room.id
                    LEFT OUTER JOIN reservation_room ON massage_reservation_room.hotel_reservation_room_id = reservation_room.id
                    left outer join TRAVELLER on reservation_room.TRAVELLER_ID = TRAVELLER.ID
                    LEFT OUTER JOIN room ON reservation_room.room_id = room.id
				WHERE
					'.$cond.'
				ORDER BY
					massage_product_consumed.reservation_room_id DESC
			)
			WHERE
			 	rownumber > '.((page_no()-1)*$item_per_page).' and rownumber<='.((page_no())*$item_per_page).'
		';
        //System::debug($sql);
        
		$reservation_room = DB::fetch_all($sql);
        //System::debug($reservation_room);
        $payment = DB::fetch_all('select bill_id id,amount from payment where type = \'SPA\'');
        $folio = DB::fetch_all('select invoice_id as id from traveller_folio where type = \'MASSAGE\'');
		$i = 0;
        $check=false;
		foreach($reservation_room as $key=>$value){
		      $reservation_room[$key]['first_items']=1;
              $reservation_room[$key]['count_items']=1;
              $reservation_room[$key]['key']=$key;
              $reservation_room[$key]['check_ht_room_same']=1;;
              $value['key']=$key;
		   if($check!=false and $check['reservation_room_id']==$value['reservation_room_id']){
		      $reservation_room[$key]['first_items']=0;
              $reservation_room[$check['key']]['count_items']++;
              $reservation_room[$key]['key']=$check['key'];
              if($value['ht_reservation_room_id']==$check['ht_reservation_room_id']){
                $reservation_room[$key]['check_ht_room_same']=0;
              }
		   }
           
			$i++;
			$reservation_room[$key]['i'] = $i;
			$reservation_room[$key]['class'] = 'reservation '.$value['status'];
			$reservation_room[$key]['total_amount'] = number_format($value['total_amount']);
            $reservation_room[$key]['check_payment'] = 0;
            if(isset($payment[$value['massage_rr_id']]) || isset($folio[$value['massage_rr_id']]))
            {
                $reservation_room[$key]['check_payment'] = 1;    
            }
            $check=$value;
		}
        //
		$this->map['items'] = $reservation_room;
		$this->parse_layout('report',$this->map);
	}
}
?>
