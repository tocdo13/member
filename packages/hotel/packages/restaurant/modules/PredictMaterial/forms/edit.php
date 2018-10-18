<?php
/**
 * Copy Right by TCV.JSC
 * Written by Kid 1412
**/ 
class PredictMaterialForm extends Form
{
	function PredictMaterialForm()
	{
		Form::Form('PredictMaterialForm');
        $this->link_css('packages/core/skins/default/css/jquery.autocomplete.css');
		$this->link_js('packages/core/includes/js/multi_items.js');
		$this->link_js('packages/core/includes/js/jquery/jquery.autocomplete.js'); 
        $this->link_js('packages/core/includes/js/jquery/jquery.battatech.excelexport.js');
	}
	function draw()
	{
        if(Url::get('act')==1)
        {
            if(isset($_REQUEST['mi_predict_material']))
            {
                $remain_product = array();
                $product='(id=\'0\'';
                $product_material = '(product_id=\'0\'';
                foreach($_REQUEST['mi_predict_material'] as $key=>$value)
    			{					
    				$product.=' or id=\''.$value['code'].'\'';
                    $product_material.=' or product_id=\''.$value['code'].'\'';
    			}
                $product.=')';
                $product_material.=')';
                /** start:nhung san pham co dinh muc lay ra cac nguyen lieu dinh muc luon**/
                $sql_product_material=
                    '
                        SELECT 
                            product_material.material_id as id, 
                            product_material.product_id, 
                            product_material.quantity 
                        FROM 
                            product_material 
                        WHERE '.$product_material.'                      
                    ';
                $product_material_arr = DB::fetch_all($sql_product_material);
                $cond_product_invoice='(product_id=\'0\'';
                $cond_product='(product_id=\'0\'';
                foreach($product_material_arr as $key=>$value)
                {
                    $type= DB::fetch('select id,type from product where id=\''.$value['product_id'].'\'');
                    if($type['type']=='PRODUCT')
                    {
                        $cond_product.=' or (product_id=\''.$value['id'].'\' and warehouse_id=63)';
                        $cond_product_invoice.=' or (product_id=\''.$value['id'].'\' and wh_invoice.warehouse_id=63)';
                    }
                    else if($type['type']=='DRINK')
                    {
                        $cond_product.=' or (product_id=\''.$value['id'].'\' and warehouse_id=64)';
                        $cond_product_invoice.=' or (product_id=\''.$value['id'].'\' and wh_invoice.warehouse_id=63)';
                    }
                }
                
                /** end:nhung san pham co dinh muc lay ra cac nguyen lieu dinh muc luon**/
                
                /** start: nhung san pham khong co dinh muc**/
                $sql_product=
                    '
                        SELECT id FROM product WHERE '.$product.
                        'MINUS
                        SELECT distinct product_id FROM product_material WHERE '.$product_material.' 
                                           
                    ';
                $product_arr = DB::fetch_all($sql_product);
                foreach($product_arr as $key=>$value)
                {
                    $cond_product.=' or (product_id=\''.$value['id'].'\' and warehouse_id=64)';
                    $cond_product_invoice.=' or (product_id=\''.$value['id'].'\' and wh_invoice.warehouse_id=64)';
                }
                $cond_product.=')';
                $cond_product_invoice.=')';
                /** end: nhung san pham khong co dinh muc**/
                
                foreach($_REQUEST['mi_predict_material'] as $key=>$value)
    			{
                    if($value['type'] =='PRODUCT' || $value['type'] =='DRINK')
                    {
                        if($material = DB::fetch_all('
                                                        SELECT 
                                                            product_material.material_id as id, 
                                                            product_material.product_id, 
                                                            product_material.quantity,
                                                            product.name_1 as product_name 
                                                        FROM product_material
                                                            INNER JOIN product ON product.id = product_material.product_id
                                                        WHERE product_material.product_id = \''.$value['code'].'\'   
                                                                           
                                                    ')
                        )
                        {
            				foreach($material as $k=>$v)
                            {
                                if(isset($remain_product[$v['id']]))
                                {
                    				
                				    $remain_product[$v['id']]['request_quantity'] = $remain_product[$v['id']]['request_quantity'] + ($v['quantity']*$value['quantity']);
                    			}
                                else
                                {
                                    $remain_product[$v['id']]['id'] = $v['id'];
                                    $remain_product[$v['id']]['product_name'] = $v['product_name'];
                                    $remain_product[$v['id']]['request_quantity'] = $v['quantity']*$value['quantity'];
                    			    $remain_product[$v['id']]['remain_quantity'] =  0;
                                } 
                            }
                        }
                        else
                        {
                            /*if(isset($remain_product[$value['code']]))
                            {
                                $remain_product[$value['code']]['request_quantity'] +=  $value['quantity'];
                            }
                            else
                            {
                                $remain_product[$value['code']]['id'] = $value['code'];
                                $remain_product[$value['code']]['request_quantity'] =  $value['quantity'];
                                $remain_product[$value['code']]['remain_quantity'] =  0;
                            }*/
                        }
                    }
                    else
                    {
                        if(isset($remain_product[$value['code']]))
                        {
                            $remain_product[$value['code']]['request_quantity'] +=  $value['quantity'];
                        }
                        else
                        {
                            $remain_product[$value['code']]['product_name'] = $value['product_name'];
                            $remain_product[$value['code']]['id'] = $value['code'];
                            $remain_product[$value['code']]['request_quantity'] =  $value['quantity'];
                            $remain_product[$value['code']]['remain_quantity'] =  0;
                        }
                    }
    			}
                
                /** start:tinh so luong của san pham qua cac lan nhap xuat truoc ngay in_date **/      
                $sql_invoice = '
                    SELECT 
                		wh_invoice_detail.id,
                        wh_invoice_detail.num,
                        wh_invoice.id as invoice_id,
                        wh_invoice_detail.product_id,
                        wh_invoice.type
                	FROM
                		wh_invoice_detail
                		INNER JOIN wh_invoice on wh_invoice.id = wh_invoice_detail.invoice_id
                	WHERE
                        '.$cond_product_invoice.' 
                		and wh_invoice.create_date <=\''.Date_Time::to_orc_date(date('d/m/Y')).'\'
                        and wh_invoice.portal_id = \''.PORTAL_ID.'\'
                ';
                $product_invoice = DB::fetch_all($sql_invoice);
                foreach($product_invoice as $key=>$value)
                {
                    if(isset($remain_product[$value['product_id']]))
                    {
        				if($value['type']=='IMPORT')
                        {
        					$remain_product[$value['product_id']]['remain_quantity'] += $value['num'];
                        }
        				else
                        {  
      						$remain_product[$value['product_id']]['remain_quantity'] -=  $value['num'];
                        }
        			}
                    else
                    {
                        /*$remain_product[$value['product_id']]['id'] = $value['product_id'];
                        $remain_product[$value['product_id']]['remain_quantity'] = $value['num'];*/
        			}
                }
                /** end:tinh so luong của san pham qua cac lan nhap xuat truoc ngay in_date **/
                
                /** start:tinh so ton dau ki của san pham => so ton cuoi ki **/
                $sql_start_term_remain = '
                    SELECT
                        wh_start_term_remain.product_id as id,
                        SUM(
                            CASE 
                                WHEN wh_start_term_remain.quantity >0 THEN wh_start_term_remain.quantity
                                ELSE 0 
                            END
                        ) as remain_number,
                        product.name_'.Portal::language().' as product_name
                    FROM
                        wh_start_term_remain
                        inner join product on product.id = wh_start_term_remain.product_id
                    WHERE
                        '.$cond_product.'
                        and wh_start_term_remain.portal_id = \''.PORTAL_ID.'\'
                    GROUP BY
                        wh_start_term_remain.product_id,
                        product.name_1                  
                ';
                $start_term_remain = DB::fetch_all($sql_start_term_remain);
                foreach($start_term_remain as $key=>$value)
                {
                    if(isset($remain_product[$value['id']]))
                    {
                        $remain_product[$value['id']]['remain_quantity'] = ($value['remain_number']?$value['remain_number']:0)+($remain_product[$value['id']]['remain_quantity']?$remain_product[$value['id']]['remain_quantity']:0);
                        $remain_product[$value['id']]['product_name'] = $value['product_name'];
                    }
                }
                foreach($remain_product as $key=>$value)
                {
                    if(($value['request_quantity'] - $value['remain_quantity'])>0)
                    {
                        $remain_product[$value['id']]['shopping'] = $value['request_quantity'] - $value['remain_quantity'];
                    }
                    else
                    {
                        $remain_product[$value['id']]['shopping'] = 0;
                    }
                }
                /** end:tinh so ton dau ki của san pham => so ton cuoi ki**/
            }
            else
            {
                $remain_product = array();
            }
        }
        else
        {
            $remain_product = array();
            $sql ='
				SELECT 
					bar_reservation_product.product_id as id,
                    product.type,
                    bar_reservation_product.product_id as code,
                    bar_reservation_product.name as product_name,
                    SUM(bar_reservation_product.quantity - bar_reservation_product.quantity_discount) as quantity
				FROM 
					bar_reservation_product 
                    inner join bar_reservation on bar_reservation_product.bar_reservation_id = bar_reservation.id
				    inner join product on product.id = bar_reservation_product.product_id and product.type !=\'SERVICE\'
                WHERE
                    bar_reservation.portal_id=\''.PORTAL_ID.'\'
                    and (bar_reservation.status =\'BOOKED\' or bar_reservation.status =\'CHECKIN\')
                    and from_unixtime(bar_reservation.arrival_time)=\''.Date_Time::convert_time_to_ora_date(time()).'\'
                GROUP BY 
                    bar_reservation_product.product_id,
                    product.type,
                    bar_reservation_product.product_id,
                    bar_reservation_product.name	
		     ';
    		$mi_predict_material = DB::fetch_all($sql);
            $i=1;
    		foreach($mi_predict_material as $key=>$value)
    		{
    		    $mi_predict_material[$key]['stt'] = $i++; 
    		} 
            $_REQUEST['mi_predict_material'] = $mi_predict_material;
        }
        
        $this->map = array();
        
        
                $sql ='select  
                    product.id as id,
                    product.id as product_id,
                    product.name_'.Portal::language().' as product_name, 
                    product.type
				from 	
                    product
				where
                    (product.type =\'PRODUCT\' or product.type =\'GOODS\' or product.type =\'DRINK\')
                    and product.status=\'avaiable\' 
			';
				
		$this->map['products'] = DB::fetch_all($sql);  
		$this->parse_layout('edit',array('remain_quantity_product'=>$remain_product)+$this->map);
	}
}
?>