<?php
    function sync_unit()
    {
        set_time_limit(-1);
        $unit = DB::fetch_all('
                                SELECT 
                                    unit.*,
                                    unit.name_1 as id 
                                FROM 
                                    unit 
                                WHERE 
                                    unit.sync_cns=0
                                    AND unit.name_1 is not null 
                                ');
        foreach($unit as $key=>$value)
        {
            $row = array();
            $row['ReferenceKey'] = $value['id'];
            $row['Code'] = $value['id'];
            $row['Name'] = $value['id'];
            $r = new HttpRequest(LINK_SYNC_CNS.'/api/ProductUnits',HttpRequest::METH_POST);
            $r->addPostFields($row);
            try 
            {
                $r->send();
                if($r->getResponseCode()==200)
                {
                    DB::query("update unit set sync_cns=1 where name_1='".$value['id']."'");
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