<?php 
    function sync_currency()
    {
        set_time_limit(-1);
        $currency = DB::fetch_all('
                                    SELECT 
                                        currency.* 
                                    FROM 
                                        currency 
                                    WHERE 
                                        currency.allow_payment=1 
                                        and currency.sync_cns=0
                                    ');
        foreach($currency as $key=>$value)
        {
            $row = array();
            $row['ReferenceKey'] = $value['id'];
            $row['Code'] = $value['id'];
            $row['Name'] = $value['name'];
            $r = new HttpRequest(LINK_SYNC_CNS.'/api/Currencys',HttpRequest::METH_POST);
            $r->addPostFields($row);
            try 
            {
                $r->send();
                if($r->getResponseCode()==200)
                {
                    DB::query('update currency set sync_cns=1 where id=\''.$value['id'].'\'');
                }
                //else
                    //System::debug($r);
                
            } 
            catch (HttpException $ex) 
            {
                //System::debug($r);
            }
        }
    }
?>