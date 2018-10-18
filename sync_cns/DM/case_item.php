<?php 
    /**
     * ma vu viec
     * su dung de tach doanh thu cac dich vu theo vu viec khai bao trong product_service_cns
    **/
    function sync_case_item()
    {
        set_time_limit(-1);
        $product_service = DB::fetch_all("SELECT * FROM product_service_cns where sync_cns_case=0");
        foreach($product_service as $key=>$value)
        {
            $row = array();
            $row['ReferenceKey'] = $value['code'];
            $row['Code'] = $value['code'];
            $row['Name'] = $value['name'];
            $r = new HttpRequest(LINK_SYNC_CNS.'/api/CaseItems',HttpRequest::METH_POST);
            $r->addPostFields($row);
            try 
            {
                $r->send();
                if($r->getResponseCode()==200)
                {
                    DB::query("update product_service_cns set sync_cns_case=1 where id='".$value['id']."'");
                }
            } 
            catch (HttpException $ex) 
            {
                //System::debug($r);
            }
        }
        $id_vend = DB::fetch('select id from department where code=\'VENDING\'','id');
        if($id_vend and $id_vend!='')
        {
            $vend = DB::fetch_all('select * from department where parent_id='.$id_vend.' and sync_cns=0');
            foreach($vend as $k_v=>$v_v)
            {
                $row = array();
                $row['ReferenceKey'] = 'VEND_'.$v_v['code'];
                $row['Code'] = 'VEND_'.$v_v['code'];
                $row['Name'] = $v_v['name_1'];
                $r = new HttpRequest(LINK_SYNC_CNS.'/api/CaseItems',HttpRequest::METH_POST);
                $r->addPostFields($row);
                try 
                {
                    $r->send();
                    if($r->getResponseCode()==200)
                    {
                        DB::query("update department set sync_cns=1 where id='".$v_v['id']."'");
                    } 
                } 
                catch (HttpException $ex) 
                {
                    //System::debug($r);
                }
            }
        }
    }
?>