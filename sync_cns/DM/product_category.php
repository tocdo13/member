<?php
    function sync_product_category()
    {
        set_time_limit(-1);
        $product_category = DB::fetch_all('
                                            SELECT 
                                                product_category.*,
                                                product_category.code as id 
                                            FROM 
                                                product_category 
                                            WHERE 
                                                product_category.code is not null 
                                                and product_category.code!=\'ROOT\' 
                                                and product_category.sync_cns=0
                                            ');
        foreach($product_category as $key=>$value)
        {
            $row = array();
            $row['ReferenceKey'] = $value['code'];
            $row['Code'] = $value['code'];
            $row['Name'] = $value['name'];
            $r = new HttpRequest(LINK_SYNC_CNS.'/api/ProductCategorys',HttpRequest::METH_POST);
            $r->addPostFields($row);
            try 
            {
                $r->send();
                if($r->getResponseCode()==200)
                {
                    DB::query("update product_category set sync_cns=1 where code='".$value['code']."'");
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