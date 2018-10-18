<?php
class TelephoneLib
{
	function get_data()
	{
		require_once 'packages/core/includes/system/mssql_database.php';
		$data = MSSQL_DB::fetch_all('select * from TC_CuocGoi');
		System::debug($data);
	}
}
?>