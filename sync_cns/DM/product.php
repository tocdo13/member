<?php 
    function sync_product()
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
                                        product.sync_cns_hh =0
                                    ');
        foreach($product as $key=>$value)
        {
            $row = array();
            $row['ReferenceKey'] = $value['id'];
            $row['Code'] = $value['id'];
            $row['Name'] = $value['name_1'];
            $row['Name2'] = $value['name_2'];
            $row['ProductCategoryCode'] = $value['category_code'];
            $row['ProductTypeCode'] = $value['type'];
            $row['Number'] = $value['id'];
            $row['Model'] = $value['id'];
            $row['Origin'] = '';
            $row['UnitCode'] = $value['unit_name'];
            $r = new HttpRequest(LINK_SYNC_CNS.'/api/Products',HttpRequest::METH_POST);
            $r->addPostFields($row);
            try 
            {
                
                $r->send();
                if($r->getResponseCode()==200)
                {
                    DB::query("update product set sync_cns_hh=1 where id='".$value['id']."'");
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