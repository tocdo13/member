<?php 
    function sync_fee_item()
    {
        set_time_limit(-1);
        $product = DB::fetch_all('
                                    SELECT 
                                        product.*,
                                        product_category.code as category_code,
                                        unit.name_1 as unit_name 
                                    FROM 
                                        product 
                                        left join product_category on product_category.id=product.category_id 
                                        left join unit on unit.id=product.unit_id 
                                    WHERE 
                                        product.sync_cns_vt=0 AND (product.type!=\'GOODS\' AND product.type!=\'PRODUCT\' AND product.type!=\'DRINK\')
                                    ');
        foreach($product as $key=>$value)
        {
            $row = array();
            $row['ReferenceKey'] = $value['id'];
            $row['Code'] = $value['id'];
            $row['Name'] = $value['name_1'];
            $row['type'] = $value['type'];
            $r = new HttpRequest(LINK_SYNC_CNS.'/api/FeeItems',HttpRequest::METH_POST);
            $r->addPostFields($row);
            try 
            {
                $r->send();
                if($r->getResponseCode()==200)
                {
                    DB::query("update product set sync_cns_vt=1 where id='".$value['id']."'");
                }
                //else
                    //System::debug($r);
            } 
            catch (HttpException $ex) 
            {
                //System::debug($r);
            }
        }
        
        $ticket = DB::fetch_all("SELECT * FROM ticket WHERE sync_cns_fee=0");
        foreach($ticket as $k_t=>$v_t)
        {
            $row = array();
            $row['ReferenceKey'] = 'TICKET_'.$v_t['code'];
            $row['Code'] = 'TICKET_'.$v_t['code'];
            $row['Name'] = $v_t['name'];
            $row['type'] = 'SERVICE';
            $r = new HttpRequest(LINK_SYNC_CNS.'/api/FeeItems',HttpRequest::METH_POST);
            $r->addPostFields($row);
            try 
            {
                $r->send();
                if($r->getResponseCode()==200)
                {
                    DB::query("update ticket set sync_cns_fee=1 where id='".$v_t['id']."'");
                }
                //else
                    //System::debug($r);
            } 
            catch (HttpException $ex) 
            {
                //System::debug($r);
            }
        }
        
        $product_service = DB::fetch_all("SELECT * FROM product_service_cns where sync_cns_fee=0");
            
        foreach($product_service as $k=>$v)
        {
            $row = array();
            $row['ReferenceKey'] = $v['code'];
            $row['Code'] = $v['code'];
            $row['Name'] = $v['name'];
            $row['type'] = 'SERVICE';
            $r = new HttpRequest(LINK_SYNC_CNS.'/api/FeeItems',HttpRequest::METH_POST);
            $r->addPostFields($row);
            try 
            {
                $r->send();
                if($r->getResponseCode()==200)
                {
                    DB::query("update product_service_cns set sync_cns_fee=1 where id='".$v['id']."'");
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