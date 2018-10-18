<?php
class WarehouseRemainTermForm extends Form
{
	function WarehouseRemainTermForm()
	{
		Form::Form('WarehouseRemainTermForm');
        $this->link_css('packages/core/skins/default/css/jquery.autocomplete.css');
		$this->link_js('packages/core/includes/js/jquery/jquery.autocomplete.js');        
	}
    function on_submit()
    {
        set_time_limit(-1);
        if(Url::get('warehouse_remain_term'))
        {
            require_once 'packages/hotel/packages/warehousing/includes/php/warehouse.php';
            if(Url::get('portal_id'))
            {
               $portal_id =  Url::get('portal_id');
            }
            else
            {
                $portal_id =PORTAL_ID;
            }
            $warehouse = DB::fetch_all('select * from warehouse');
            foreach($warehouse as $k=>$v)
            {
                if(!DB::exists('select * from wh_remain_date where status = 1 and warehouse_id='.$k.' and portal_id = \''.$portal_id.'\' and term_date=\''.Date_time::to_orc_date(Url::get('date')).'\''))
                {
                    //sp ton cua tung kho
                    $products = $this->get_remain_products_huyen_chu($k,$portal_id);
                    //exit();
                    foreach($products as $product_id=>$v)
                    {
                        $product_arr = array(
                            'warehouse_id'=>$k,
                            'product_id'=>$v['id'],
                            'quantity'=>$v['remain_invoice'],
                            'portal_id'=>$portal_id,
                            'total_start_term_price'=>$v['remain_money'],
                            'start_term_price'=>($v['remain_invoice']>0)?$v['remain_money']/$v['remain_invoice']:0,
                            'term_date'=>Date_time::to_orc_date(Url::get('date'))
                        );
                        DB::insert('wh_remain_date_detail',$product_arr); 
                    }
                    DB::update('wh_remain_date',array('status'=>0,'end_date'=>Date_time::to_orc_date(Url::get('date'))),'status=1 and warehouse_id='.$k.' and portal_id = \''.$portal_id.'\'');
                    DB::insert('wh_remain_date',array(
                            'term_date'=>Date_time::to_orc_date(Url::get('date')),
                            'status'=>1,
                            'portal_id'=>$portal_id,
                            'start_date'=>Date_time::to_orc_date(Url::get('date')),
                            'warehouse_id'=>$k,
                        )
                    );
                    
                }
            }
        }
        else
        {
            Url::redirect_current(array('portal_id','date','warehouse_id'));
        }
    }
	function draw()
	{
        require_once 'packages/hotel/packages/warehousing/includes/php/warehouse.php';
        require_once 'packages/hotel/packages/warehousing/modules/WarehouseReport/forms/options.php';
		$this->map = array();
        $this->map['portal_id_list'] = String::get_list(Portal::get_portal_list()); 
        $warehouse = get_warehouse(true);
        $this->map['warehouse_id_list'] = array(''=>Portal::language('select_warehouse'))+String::get_list($warehouse);
        $this->map['title'] = Portal::language('Report_options');
        $this->map['date'] = Url::sget('date')?Url::sget('date'):date('d/m/Y');
        $_REQUEST['date'] = $this->map['date'];
        if(!Url::get('warehouse_id'))
        {
            $a = $this->get_remain_products_huyen_chu('68','#default'); 
            System::debug($a);
            $this->parse_layout('options',$this->map);
        }
        else
        {
            $this->parse_layout('report',$this->map); 
        }  
	}
    function get_remain_products_huyen_chu($warehouse_id,$portal_id)
    {
        $cond = $invoice_cond = '1=1';
        $warehouse_cond = '';
        if($warehouse_id)
        {
        	//$invoice_cond.=' and wh_invoice.warehouse_id='.$warehouse_id.'';
        	$cond.=' and wh_remain_date_detail.warehouse_id='.$warehouse_id;
            $warehouse_cond.=' and wh_invoice_detail.warehouse_id='.$warehouse_id.' ';
        }
        //dung cho bao cao ton tong hop
        $cond_2 = '';
        $invoice_cond.=' AND wh_invoice.create_date <\''.Date_Time::to_orc_date(Url::get('date')).'\'';
        if(Url::get('product_id'))
        {
            $invoice_cond.=' AND upper(wh_invoice_detail.product_id) =\''.strtoupper(Url::sget('product_id')).'\'';
        }
        if($warehouse_id)
        {
            $wh_remain_date = DB::fetch_all('select * from wh_remain_date where  warehouse_id='.$warehouse_id.' and portal_id = \''.$portal_id.'\'');
        }
        $cond_term_date = ' ';
        if(isset($wh_remain_date))
        {
            foreach($wh_remain_date as $key=>$value)
            {
                if($value['end_date'] != '' and Date_Time::to_orc_date(Url::get('date'))< $value['end_date'] and Date_Time::to_orc_date(Url::get('date'))>= $value['term_date'])
                {
                    //$invoice_cond.= ' AND wh_invoice.create_date >=\''.$value['term_date'].'\'';
                    $cond_term_date = ' AND wh_remain_date_detail.term_date =\''.$value['term_date'].'\'';
                }
                if($value['end_date']=='' and Date_Time::to_orc_date(Url::get('date'))>= $value['term_date'])
                {
                    //$invoice_cond.= ' AND wh_invoice.create_date >=\''.$value['term_date'].'\'';
                    $cond_term_date = ' AND wh_remain_date_detail.term_date =\''.$value['term_date'].'\'';
                }
            }
            $cond_2 .= $cond_term_date; 
        }
        
        
        $sql_start = '
            SELECT
                wh_remain_date_detail.product_id as id,
                product.name_'.Portal::language().' as product_name,
                unit.name_'.Portal::language().' as unit_name,
                wh_remain_date_detail.warehouse_id,
                SUM(
                    CASE 
                        WHEN wh_remain_date_detail.quantity >0 THEN wh_remain_date_detail.quantity
                        ELSE 0 
                    END
                ) as remain_number,
                wh_remain_date_detail.total_start_term_price as remain_money,
                wh_remain_date_detail.start_term_price
            FROM
                wh_remain_date_detail
                INNER JOIN product on product.id = wh_remain_date_detail.product_id
                INNER JOIN unit on unit.id = product.unit_id
                INNER JOIN product_category ON product_category.id = product.category_id
            WHERE
                '.$cond.$cond_2.'
                and wh_remain_date_detail.portal_id = \''.$portal_id.'\'
            GROUP BY
                wh_remain_date_detail.product_id,
                wh_remain_date_detail.total_start_term_price,
                product.name_'.Portal::language().',
                unit.name_'.Portal::language().',
                wh_remain_date_detail.warehouse_id,
                wh_remain_date_detail.start_term_price
                
        ';
        $start_term = DB::fetch_all($sql_start);
        //System::debug($start_term);
        $product_invoice = array();
        $sql_invoice = '
            SELECT 
        		wh_invoice_detail.id,
                wh_invoice_detail.to_warehouse_id,
                wh_invoice_detail.num,
                wh_invoice.id as invoice_id,
                (CASE
                    WHEN money_add is null and (wh_invoice_detail.payment_price is not null and wh_invoice_detail.payment_price != 0) THEN wh_invoice_detail.payment_price
                    WHEN money_add is null and (wh_invoice_detail.payment_price is null or wh_invoice_detail.payment_price = 0) THEN wh_invoice_detail.price * wh_invoice_detail.num
                    ELSE wh_invoice_detail.money_add
                 END
                ) as payment_price,
                wh_invoice_detail.product_id,
                wh_invoice.type
        	FROM
        		wh_invoice_detail
        		INNER JOIN wh_invoice on wh_invoice.id = wh_invoice_detail.invoice_id
        		INNER JOIN product on product.id = wh_invoice_detail.product_id
                INNER JOIN unit on unit.id = product.unit_id 
                INNER JOIN product_category ON product_category.id = product.category_id
        	WHERE
        		'.$invoice_cond.$warehouse_cond.'
                and wh_invoice_detail.product_id = \'BIATB\'
        ';
        //System::debug($sql_invoice);exit();
        $product_invoice = DB::fetch_all($sql_invoice); 
        System::debug($product_invoice);
        $items = $product_invoice;
        $old_items = array();
    	if(is_array($items))
    	{
    		$xx = 0;
            foreach($items as $key=>$value)
            {
    			$xx++;
                $product_id = $value['product_id'];
    			if(isset($old_items[$product_id]))
                {
    				if($value['type']=='IMPORT' or $value['to_warehouse_id'] == $warehouse_id)
                    {
    					$old_items[$product_id]['remain_invoice'] =  System::calculate_number(round($old_items[$product_id]['remain_invoice'], 8)) + System::calculate_number(round($value['num'],8));
                        $old_items[$product_id]['remain_money'] =  System::calculate_number($old_items[$product_id]['remain_money']) + System::calculate_number($value['payment_price']);
                        $a .= '+'.$value['num'];
                    }
    				else
                    {
                        if($value['type']=='EXPORT' and $value['to_warehouse_id'] != $warehouse_id)
                        {
    						$old_items[$product_id]['remain_invoice'] =  System::calculate_number(round($old_items[$product_id]['remain_invoice'], 8)) - System::calculate_number(round($value['num'],8));
                            $old_items[$product_id]['remain_money'] =  System::calculate_number($old_items[$product_id]['remain_money']) - System::calculate_number($value['payment_price']);
                            $a .= '-'.$value['num'];
                        }
                    }
    			}
                else
                {
                    $old_items[$product_id]['id'] = $value['product_id'];
                    $old_items[$product_id]['remain_invoice'] = 0;
                    $old_items[$product_id]['remain_money'] = 0;
    				if($value['type']=='IMPORT' or $value['to_warehouse_id'] == $warehouse_id)
                    {
    					$old_items[$product_id]['remain_invoice'] =  System::calculate_number(round($value['num'],8));
                        $old_items[$product_id]['remain_money'] =  System::calculate_number($value['payment_price']);
                        $a = $value['num'];
                    }
                    //PX ma kho xuat den khong phai la kho can tinh(tranh th tao PX ma tu kho A den kho A)
    				if($value['type']=='EXPORT' and $value['to_warehouse_id'] != $warehouse_id)
                    {
    					$old_items[$product_id]['remain_invoice'] =  - System::calculate_number(round($value['num'],8));
                        $old_items[$product_id]['remain_money'] =   - System::calculate_number($value['payment_price']);
                        $a = '-'.$value['num'];
                    }
                    //if(isset($start_term[$product_id]))
                    //{
                        //$old_items[$product_id]['remain_invoice'] += $start_term[$product_id]['remain_number'];
                        //$old_items[$product_id]['remain_money'] += $start_term[$product_id]['remain_money'];
                    //}
                    
    			}
    		}
            echo $a;
    	}
        System::debug($old_items);exit();
        $product_invoice = $old_items;
        return $product_invoice;
    }	
}
?>
