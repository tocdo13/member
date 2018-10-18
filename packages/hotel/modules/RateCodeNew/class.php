<?php 
class RateCodeNew extends Module
{
	function RateCodeNew($row)
	{
		Module::Module($row);
			if(User::can_view(false,ANY_CATEGORY))
			{
			     switch(Url::get('cmd')){
			         case "edit":
                                   if(User::can_edit(false,ANY_CATEGORY)){
                                        require_once 'forms/edit.php';
    					               $this->add_form(new RateCodeNewEditForm());
                                   }
                                   else{
                                    Url::access_denied();
                                   } 
                                   break;
                     case "delete":
                                    if(User::can_delete(false,ANY_CATEGORY)){
                                      $this->deleteRateCode(); 
                                      Url::redirect('rate_code_new'); 
                                    }
                                    else{
                                        Url::access_denied();
                                    }
                                    break;                  
                     default:
                                    require_once 'forms/list.php';
					                $this->add_form(new RateCodeNewForm());       
			     }
                        
			}
			else
			{
				URL::access_denied();
			}
	}
    public function deleteRateCode(){
        $r_id = $_GET['r_id'];
        
        $sql = "SELECT * FROM rate_code_time WHERE rate_code_id IN($r_id)";
        $rate_code_time_list = DB::fetch_all($sql);
        
        foreach($rate_code_time_list as $key => $value){
            DB::delete("rate_room_level"," rate_code_time_id=$key");
        }
        
        DB::delete("rate_customer_group"," rate_code_id IN ($r_id)");
        DB::delete("rate_code_time", " rate_code_id IN($r_id)");
        DB::delete("rate_code"," id IN($r_id)");
        
    }
		
}
?>