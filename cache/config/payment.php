<?php
define('PAY_FOR_ROOM',1);
define('PAY_FOR_MINIBAR',2);
define('PAY_FOR_LAUNDRY',3);
define('PAY_FOR_FB',4);
define('PAY_FOR_SPA',5);
define('CASH',2);
define('CREDIT_CARD',3);
define('DIRECT_PAYMENT',1);
define('DEPOSIT_PAYMENT',2);
$block_ids = DB::fetch_all('select block.id,module.name 
							from block 
							inner join module ON module.id = block.module_id 
						where module.name=\'CreateTravellerFolio\' OR module.name=\'Payment\' OR module.name=\'Reservation\'');
foreach($block_ids as $k =>$block){
	if($block['name'] == 'CreateTravellerFolio'){
		define('CREATE_FOLIO',$block['id']);	
	}
	if($block['name'] == 'Payment'){
		define('PAYMENT',$block['id']);	
	}
}

?>