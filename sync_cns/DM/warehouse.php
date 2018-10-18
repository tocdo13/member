<?php 
    function sync_warehouse()
    {
        set_time_limit(-1);
        $warehouse = DB::fetch_all('
                                    SELECT 
                                        warehouse.* 
                                    FROM 
                                        warehouse 
                                    WHERE 
                                        warehouse.code is not null 
                                        and warehouse.sync_cns=0
                                    ');
        foreach($warehouse as $key=>$value)
        {
            $row = array();
            $row['ReferenceKey'] = $value['code'];
            $row['Code'] = $value['code'];
            $row['Name'] = $value['name'];
            $r = new HttpRequest(LINK_SYNC_CNS.'/api/Warehouses',HttpRequest::METH_POST);
            $r->addPostFields($row);
            try 
            {
                $r->send();
                if($r->getResponseCode()==200)
                {
                    DB::query("update warehouse set sync_cns=1 where id='".$value['id']."'");
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