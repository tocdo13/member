<?php
class MassageUserRevenueReportForm extends Form
{
	function MassageUserRevenueReportForm()
	{
		Form::Form('MassageUserRevenueReportForm');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
	}
	function draw()
	{
		$this->map['date'] = date('d/m/Y');
		$this->map['total_amount'] = 0;
		$this->map['user_id'] = Session::get('user_id');
		$sql = '
			SELECT
				massage_reservation_room.id,massage_reservation_room.total_amount
			FROM
				massage_reservation_room
				INNER JOIN massage_product_consumed ON massage_product_consumed.reservation_room_id = massage_reservation_room.id
			WHERE
				massage_reservation_room.user_id = \''.$this->map['user_id'].'\' and massage_reservation_room.portal_id=\''.PORTAL_ID.'\'
				AND massage_product_consumed.status =\'CHECKOUT\'
				AND (massage_product_consumed.time >= '.Date_Time::to_time($this->map['date']).' AND massage_product_consumed.time < '.(Date_Time::to_time($this->map['date'])+24*3600).')
		';
		$items = DB::fetch_all($sql);
		foreach($items as $key=>$value){
			$this->map['total_amount'] += $value['total_amount'];
		}
		$this->map['total_amount'] = System::display_number($this->map['total_amount']);
		$this->parse_layout('report',$this->map);
	}
}
?>