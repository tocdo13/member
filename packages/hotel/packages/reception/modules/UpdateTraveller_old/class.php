<?php 
class UpdateTraveller extends Module
{
	function UpdateTraveller($row)
	{
	   
	    //start:KID them ham check neu da tao folio boi khach thi khong duoc xoa khach
        if(Url::get('traveller_delete'))
        {
            $traveller_del = explode(',',Url::get('traveller_delete'));       
            if(Url::get('reservation_traveller_id'))//(Url::get('reservation_traveller_id') && $traveller_del[1]!="" && Url::get('reservation_traveller_id') == $traveller_del[1])
            {
                $is_folio = DB::fetch('select is_folio from reservation_traveller where id ='.Url::get('reservation_traveller_id'));
                echo $is_folio['is_folio'];
                exit();
                
            }
            
        }   
        //end:  KID them ham check neu da tao folio boi khach thi khong duoc xoa khach 
		Module::Module($row);
		switch(URL::get('cmd'))
				{
		    case 'import':
              	if(Url::get('r_id')){
              	    
					require_once 'forms/import.php';
					$this->add_form(new ImportTravellerForm());break;	
				}	    
			case 'group_traveller':
				if(Url::get('r_id')){
					require_once 'forms/list.php';
					$this->add_form(new UpdateTravellerForm());break;	
				}
			case 'change_guest':
					//if(Url::get('rr_id')){
						require_once 'forms/change_traveller.php';
						$this->add_form(new UpdateTravellerChangeForm());break;	
					//}else{
						//exit();
						//Url::redirect_current();
					//}
			case 'checkout_guest':
					if(Url::get('rr_id') && Url::get('traveller_id') && Url::get('r_id')){
						DB::update('reservation_traveller',array('status'=>'CHECKOUT','departure_time'=>time(),'departure_date'=>Date_Time::to_orc_date(date('d/m/y'))),' id='.Url::get('traveller_id').'');
						DB::query('update reservation_room set reservation_room.adult=reservation_room.adult-1 where id='.Url::get('rr_id').'');
						$tt = 'form.php?block_id='.Module::block_id().'&r_id='.Url::get('r_id').'&rr_id='.Url::get('rr_id').'';
						echo '<script>window.location.href = \''.$tt.'\';
					</script>';	
					}			
			default:
				if(Url::get('rr_id'))
				{
					require_once 'forms/list.php';
					$this->add_form(new UpdateTravellerForm());break;	
				}
		}
	}
}
?>