<?php

function get_banquet_status()
{
    return array(
            ''=>Portal::language('All_status'),
			'BOOKED'=>Portal::language('booked'),
			'CHECKIN'=>Portal::language('checkin'),
			'CHECKOUT'=>Portal::language('checkout'),
			'CANCEL'=>Portal::language('cancel')
			);
}
	function get_banquet_room($portal_id=PORTAL_ID){
		$sql = '
			select 
				party_room.*
			from 
				party_room
            where
                portal_id = \''.$portal_id.'\'
			order by
				party_room.id
		';
		$banquet_rooms = DB::fetch_all($sql);
		return $banquet_rooms;
	}
	function get_party_type($portal_id=PORTAL_ID){
		$sql = '
			select 
				party_type.*
			from 
				party_type
            where
                portal_id = \''.$portal_id.'\'
			order by
				party_type.id
		';
		$party_type = DB::fetch_all($sql);
		return $party_type;
	}
?>