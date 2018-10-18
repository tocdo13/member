<?php
class ManageUserDB{
	function get_users()
	{
		return DB::fetch_all('
			SELECT
				account.id, party.full_name as name
			FROM
				account
				left outer join party on party.user_id = account.id
			WHERE 
				account.type=\'USER\'
			ORDER
				 by account.id
		');
	}
}
?>