<?php
class ListSiteminderDashboardForm extends Form
{
	function ListSiteminderDashboardForm()
	{
		Form::Form('ListSiteminderDashboardForm');
	}
	function draw()
	{
	    $this->map = array();
        $sql = '
                SELECT
                    reservation_room.*,
                    siteminder_reservation.*,
                    reservation_room.id as id,
                    siteminder_booking_channel.name as channel_name,
                    reservation.note as reservation_note,
                    reservation.time as reservation_time
                FROM
                    reservation_room
                    inner join reservation on reservation.id=reservation_room.reservation_id
                    inner join customer on customer.id=reservation.customer_id
                    inner join siteminder_map_customer on siteminder_map_customer.customer_id=customer.id
                    inner join siteminder_booking_channel on siteminder_booking_channel.code=siteminder_map_customer.booking_channel_code
                    inner join siteminder_reservation on siteminder_reservation.reservation_id = reservation.id
                WHERE
                    1=1
                ORDER BY reservation.time DESC
                ';
        $this->map['items'] = DB::fetch_all($sql);
        foreach($this->map['items'] as $key=>$value){
            $this->map['items'][$key]['pay_note'] = '';
            $this->map['items'][$key]['guest_service_note'] = '';
            $UniqueID = $value['uniqueid'];
            if(is_file('packages/hotel/packages/siteminder/includes/dataRes/paynote_'.$UniqueID.'.php')){
                $this->map['items'][$key]['pay_note'] = file_get_contents('packages/hotel/packages/siteminder/includes/dataRes/paynote_'.$UniqueID.'.php');
            }
            if(is_file('packages/hotel/packages/siteminder/includes/dataRes/paynote_'.$UniqueID.'_RRID_'.$value['id'].'.php')){
                $this->map['items'][$key]['guest_service_note'] = file_get_contents('packages/hotel/packages/siteminder/includes/dataRes/paynote_'.$UniqueID.'_RRID_'.$value['id'].'.php');
            }
            if($value['modify_time']!=''){
                $this->map['items'][$key]['status'] = 'MODIFIED';
            }
            if($value['cancel_time']!=''){
                $this->map['items'][$key]['status'] = 'CANCEL';
            }
        }
        //System::debug($this->map);
		$this->parse_layout('list',$this->map);
	}
}
?>