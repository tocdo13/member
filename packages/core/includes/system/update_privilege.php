<?php
/******************************
COPY RIGHT BY NYN PORTAL - TCV
WRITTEN BY vuonggialong
******************************/

function make_privilege_cache()
{
	DB::query('UPDATE ACCOUNT SET CACHE_PRIVILEGE=\'\'');
}
?>