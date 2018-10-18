<?php
class WarehouseReportOptionsForm extends Form
{
	function WarehouseReportOptionsForm()
	{
		Form::Form('WarehouseReportOptionsForm');
		$this->link_css(Portal::template('hotel').'/css/report.css');
		$this->link_css(Portal::template('hotel').'/css/report.css');
		$this->link_css('packages/hotel/packages/warehousing/skins/default/css/style.css');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_css('packages/core/skins/default/css/jquery.autocomplete.css');
		$this->link_js('packages/core/includes/js/jquery/jquery.autocomplete.js');
        $this->link_js('packages/core/includes/js/jquery/jquery.battatech.excelexport.js');
		$this->add('date_from',new DateType(true,'invalid_date_from'));
		$this->add('date_to',new DateType(true,'invalid_date_to',0,255));
	}
	function draw()
	{
		//System::debug($_REQUEST);
        $this->map = array();
        require_once 'packages/hotel/packages/warehousing/includes/php/warehouse.php';
        $warehouses = get_warehouse(true);
         //trung add: $this->map['suppliers']
        $this->map['suppliers'] = DB::fetch_all('
			SELECT
				supplier.code as id,
                supplier.id as supplier_id,
                supplier.name,
                supplier.address,
                supplier.tax_code
			FROM
				supplier
			ORDER BY
				supplier.code
		');
        
		if(Url::get('warehouse_id'))
			$this->map['warehouse'] = DB::fetch('select name from warehouse where id = '.Url::iget('warehouse_id').'','name');
        else
			$this->map['warehouse'] = Portal::language('All');
		$this->map['total_payment'] = 0;
        
		$this->map['title'] = Portal::language('Report_options');
        
		if(Url::get('date_from'))
			$this->map['date_from'] = Url::get('date_from');
        else
            $this->map['date_from'] = date('d/m/Y');
		$_REQUEST['date_from'] = $this->map['date_from'];
        
        if(Url::get('date_to'))
			$this->map['date_to'] = Url::get('date_to');
        else
            $this->map['date_to'] = date('d/m/Y');
        $_REQUEST['date_to'] = $this->map['date_to'];
            
		$this->map['year'] = date('Y',time());
		$this->map['month'] = date('m',time());
		$this->map['day'] = date('d',time());
        
		$layout = 'options';
        /**
         * Xuat nhap ton 
        **/
        if(Url::get('store_remain'))
        {
			$layout = 'store_remain';
			$this->map['title'] = Portal::language('store_remain_report');
			$this->map['products'] = $this->get_store_remain_products();
            
            foreach($this->map['products'] as $key => $value)
            {
                //Tien co the bi le => set lai neu So nhap = so xuat => tien = 0;    
                if(round($value['start_term_quantity'])<=0)
                {
                    $products[$key]['total_start_term_money']=0;
                }
                if(($value['import_number'] == $value['export_number']) &&  $value['money_add'] == 0)
                {
                    //$this->map['products'][$key]['total_export_money'] = $this->map['products'][$key]['total_import_money'];
                }
                
                if($value['start_term_quantity']==0 and $value['remain_number']==0 and $value['import_number']==0 and $value['export_number']==0)
                {
                    unset($this->map['products'][$key]);
                }
                //Neu chi xem sp ton am
                if(Url::get('negative_number')==1 and $value['remain_number']>0)
                {
                    unset($this->map['products'][$key]);
                }
            }
            $grand_total2 = array();
            $grand_total2['grand_total_start_term_money'] =0;
            $grand_total2['grand_total_import_money'] = 0;
            $grand_total2['grand_total_import_money_total'] = 0;
            $grand_total2['grand_total_export_money'] =0;
            foreach($this->map['products'] as $k=>$v)
            {
                if($v['remain_number']<=0)
                {
                    $total_import_money = 0;
                }
                else
                {
                    $total_import_money = $v['total_import_money'];
                }
                $grand_total2['grand_total_start_term_money'] += $v['total_start_term_money'];
                $grand_total2['grand_total_import_money_total'] += $total_import_money;
                $grand_total2['grand_total_import_money'] += $v['total_import_money'];
                $grand_total2['grand_total_export_money'] += $v['total_export_money'];
            }
            $this->map['grand_total2'] = $grand_total2;
            if (User::is_admin())
            {
                //System::debug($grand_total2);
            }
		}
        /**
         * The kho 
        **/
        else
            if(Url::get('store_card'))
            {
    			$layout = 'store_card';
    			$this->map['start_remain'] = 0;
    			$this->map['end_remain'] = 0;
    			$this->map['import_total'] = 0;
    			$this->map['export_total'] = 0;
    			$this->map['have_item'] = false;
    			$this->map['products'] = $this->get_store_card();
    			if(!$this->map['have_item'])
                {
    				echo '<div class=\'notice\'>'.Portal::language('has_no_item').'</div>';
    				exit();
    			}
    		}
            /**
             * BC xuat chuyen kho
            **/
            else
                if(Url::get('warehouse_export'))
                {
        			$layout = 'warehouse_export';
        			$this->map['total_amount'] = 0;
        			$this->map['total_quantity'] = 0;
        			$this->map['title'] = Portal::language('warehouse_export_report');
        			$this->map['products'] = $this->get_warehouse_export();
        		}
                else
                if(Url::get('warehouse_import'))
                {
        			$this->map['total_amount'] = 0;
        			$this->map['total_quantity'] = 0;
        			$layout = 'warehouse_import';
        			$this->map['title'] = Portal::language('warehouse_import_report');
        			$this->map['back_products'] = array();			
        			$this->map['products'] = $this->get_warehouse_import();
                    //System::debug($this->map['products']);
        		}
                else
                if(Url::get('warehouse_export_supplier'))
                {
                    $layout = 'warehouse_export_supplier';
        			$this->map['total_amount'] = 0;
        			$this->map['total_quantity'] = 0;
        			$this->map['title'] = Portal::language('warehouse_export_remain_supplier');
        			$this->map['products'] = $this->get_warehouse_export_supplier();
                }
        $this->map['supplier'] = '';
		if(Url::get('supplier_id'))
			$this->map['supplier'] = DB::fetch('SELECT id,name FROM supplier WHERE id='.Url::iget('supplier_id').'','name');	
        else
            $this->map['supplier'] = Portal::language('all');
		$this->map['product_arr'] = DB::fetch_all('SELECT id,name_'.Portal::language().' as name FROM product');
		$this->map['total_payment'] = number_format($this->map['total_payment']);
		$this->map['supplier_id_list'] = array(''=>'ALL')+String::get_list(DB::select_all('supplier','','code'));
		
        //$this->map['warehouse_id_list'] = array(''=>'--------'.Portal::language('select').'---------')+String::get_list(DB::select_all('warehouse',IDStructure::child_cond(ID_ROOT).' and structure_id !=\''.ID_ROOT.'\' ','structure_id'));
        $this->map['warehouse_id_list'] = array(''=>'--------'.Portal::language('select').'---------')+String::get_list($warehouses);
        $this->map['warehouse_to_id_list'] = array(''=>'--------'.Portal::language('select').'---------')+String::get_list($warehouses);
		
        if((Url::get('date_from') and Url::get('date_to')) or (!Url::get('store_remain') and !Url::get('store_card')))
			$this->parse_layout($layout,$this->map);
        else
			echo '<div class=\'notice\'>'.Portal::language('has_no_date_duration').'</div>';
	}
    
    /**
     * bo cau query OR wh_invoice_detail.to_warehouse_id='.Url::iget('warehouse_id').'
     * o phan the? kho va xuat nhap ton
     */
	function get_store_card()
    {
		if(Url::get('code') and $row = DB::select('product','name_1 = \''.Url::get('code').'\' or name_2 = \''.Url::get('code').'\''))
        {
            $this->map['have_item'] =  true;
			$this->map['code'] = $row['id'];
			$this->map['name'] = $row['name_'.Portal::language()];
			$old_cond = 'wh_invoice_detail.product_id = \''.$row['id'].'\' AND
					(
					(wh_invoice.type=\'IMPORT\' AND wh_invoice_detail.warehouse_id='.Url::iget('warehouse_id').') OR 
					(wh_invoice.type=\'EXPORT\' AND (wh_invoice_detail.warehouse_id='.Url::iget('warehouse_id').'))
					)
					'.(Url::get('date_from')?' AND wh_invoice.create_date <\''.Date_Time::to_orc_date(Url::get('date_from')).'\'':'').'
			';
            $wh_remain_date = DB::fetch_all('select * from wh_remain_date where  warehouse_id='.Url::iget('warehouse_id').' and portal_id = \''.PORTAL_ID.'\'');
            $new_cond = '';
            foreach($wh_remain_date as $key=>$value)
            {
                if($value['end_date'] != '' and Date_Time::to_time(Url::get('date_from'))< Date_Time::to_time(Date_Time::convert_orc_date_to_date($value['term_date'],'/')) and Date_Time::to_time(Url::get('date_from'))>= Date_Time::to_time(Date_Time::convert_orc_date_to_date($value['term_date'],'/')))
                {
                    $old_cond.= ' AND wh_invoice.create_date >=\''.$value['term_date'].'\'';
                    $new_cond.= ' AND wh_remain_date_detail.term_date =\''.$value['term_date'].'\'';
                }
                if($value['end_date']=='' and Date_Time::to_time(Url::get('date_from'))>= Date_Time::to_time(Date_Time::convert_orc_date_to_date($value['term_date'],'/')))
                {
                    $old_cond.= ' AND wh_invoice.create_date >=\''.$value['term_date'].'\'';
                    $new_cond.= ' AND wh_remain_date_detail.term_date =\''.$value['term_date'].'\'';
                }
            }	
			$sql = '
				SELECT
					wh_invoice_detail.id,
                    wh_invoice_detail.product_id,
                    wh_invoice_detail.num,
                    wh_invoice.type,
					wh_invoice_detail.to_warehouse_id,
                    wh_invoice_detail.warehouse_id
				FROM
					wh_invoice_detail
					INNER JOIN wh_invoice ON wh_invoice.id= wh_invoice_detail.invoice_id
				WHERE
					'.$old_cond.' 
                    and wh_invoice.portal_id = \''.PORTAL_ID.'\'
				ORDER BY
					wh_invoice.id,wh_invoice.create_date,wh_invoice.time
			';
			$items = DB::fetch_all($sql);
			$old_items = array();
			foreach($items as $key=>$value)
            {
				$product_id = $value['product_id'];
				if($value['type']=='IMPORT' or $value['to_warehouse_id'] == Url::get('warehouse_id'))
                {
					if(isset($old_items[$product_id]['import_number']))
						$old_items[$product_id]['import_number'] += $value['num'];
                    else
						$old_items[$product_id]['import_number'] = $value['num'];
                }
                else
                    if($value['type']=='EXPORT' and $value['to_warehouse_id'] != Url::get('warehouse_id'))
                    {
    					if(isset($old_items[$product_id]['export_number']))
    						$old_items[$product_id]['export_number'] += $value['num'];
                        else
                        {
                            $old_items[$product_id]['export_number'] = $value['num'];
                        }
    				}
			}
            $sql = '
			SELECT
				wh_start_term_remain.id,
                wh_start_term_remain.warehouse_id,
				wh_start_term_remain.product_id,
                CASE 
                    WHEN wh_start_term_remain.quantity >0 THEN wh_start_term_remain.quantity
                    ELSE 0 
                END as quantity
			FROM
				wh_start_term_remain
			WHERE	
				wh_start_term_remain.product_id = \''.$row['id'].'\' AND warehouse_id='.Url::iget('warehouse_id').'
                and wh_start_term_remain.portal_id = \''.PORTAL_ID.'\'
			';
			if($product = DB::fetch($sql))
            {
                $this->map['start_remain'] = $product['quantity'];	
            }	
            else
            {
                $this->map['start_remain'] = 0;
            }
            if($new_cond!='')
            {
                $sql = '
				SELECT
					wh_remain_date_detail.id,
                    wh_remain_date_detail.warehouse_id,
					wh_remain_date_detail.product_id,
                    wh_remain_date_detail.quantity
				FROM
					wh_remain_date_detail
				WHERE	
					wh_remain_date_detail.product_id = \''.$row['id'].'\' AND warehouse_id='.Url::iget('warehouse_id').'
                    and wh_remain_date_detail.portal_id = \''.PORTAL_ID.'\'
                    '.$new_cond.'
    			';
                if(User::id()=='developer06')
                {
                    //System::debug($sql);
                }
    			if($product = DB::fetch($sql))
    				$this->map['start_remain'] += $product['quantity'];	
            }
            if(User::id()=='developer06')
            {
                //System::debug($this->map['start_remain']);
            }
			$cond = 'wh_invoice_detail.product_id = \''.$row['id'].'\' AND
				(
				(wh_invoice.type=\'IMPORT\' AND wh_invoice_detail.warehouse_id='.Url::iget('warehouse_id').') OR 
				(wh_invoice.type=\'EXPORT\' AND (wh_invoice_detail.warehouse_id='.Url::iget('warehouse_id').'))
				)
				'.(Url::get('date_from')?' AND wh_invoice.create_date >=\''.Date_Time::to_orc_date(Url::get('date_from')).'\'':'').'
				'.(Url::get('date_to')?' AND wh_invoice.create_date <=\''.Date_Time::to_orc_date(Url::get('date_to')).'\'':'').'
			';
			$sql = '
				SELECT
					wh_invoice.*,
					DECODE(wh_invoice.type,\'IMPORT\',wh_invoice.bill_number,\'\') AS import_invoice_code,
					DECODE(wh_invoice.type,\'EXPORT\',wh_invoice.bill_number,\'\') AS export_invoice_code,
					wh_invoice_detail.num,wh_invoice_detail.warehouse_id,wh_invoice_detail.to_warehouse_id
				FROM
					wh_invoice
					INNER JOIN wh_invoice_detail ON wh_invoice_detail.invoice_id = wh_invoice.id
				WHERE
					'.$cond.'
                    and wh_invoice.portal_id = \''.PORTAL_ID.'\'
				ORDER BY
					wh_invoice.id,wh_invoice.create_date,wh_invoice.time
			';
			$items = DB::fetch_all($sql);
			if(isset($old_items[$row['id']]))
            {
				if(isset($old_items[$row['id']]['import_number']))
					$this->map['start_remain'] += round($old_items[$row['id']]['import_number'],2);
				if(isset($old_items[$row['id']]['export_number']))
					$this->map['start_remain'] -= round($old_items[$row['id']]['export_number'],2);			
			}
			$remain = $this->map['start_remain'];
			foreach($items as $key=>$value)
            {
				$items[$key]['create_date'] = Date_Time::convert_orc_date_to_date($value['create_date'],'/');
				if($value['type']=='IMPORT' or $value['to_warehouse_id'] == Url::get('warehouse_id'))
					$items[$key]['import_number'] = $value['num'];
                else
					$items[$key]['import_number'] = 0;
				if($value['type']=='EXPORT' and $value['to_warehouse_id'] != Url::get('warehouse_id'))
                {
                    $items[$key]['export_number'] = $value['num'];   
                }
                else
					$items[$key]['export_number'] = 0;
				$this->map['end_remain'] += round($items[$key]['import_number'],2) - round($items[$key]['export_number'],2);
				$remain = round($remain,2) + round($items[$key]['import_number'],2) - round($items[$key]['export_number'],2);
                if(User::id()=='developer06')
                {
                    //echo $remain.'</br>';
                }
				$items[$key]['remain'] = $remain;
				$this->map['import_total'] += $items[$key]['import_number'];
				$this->map['export_total'] += $items[$key]['export_number'];
                if($items[$key]['export_number'] < 1)
                {
                    $items[$key]['export_number'] = '0'.$items[$key]['export_number'];
                }
			}
			$this->map['end_remain'] += $this->map['start_remain'];
			return $items;
		}
	}
 
    static function get_store_remain_products()
    {
		$cond = '1=1 AND
				(
				(wh_invoice.type=\'IMPORT\' AND wh_invoice_detail.warehouse_id='.Url::iget('warehouse_id').') OR 
				(wh_invoice.type=\'EXPORT\' AND (wh_invoice_detail.warehouse_id='.Url::iget('warehouse_id').'))
				)
				'.(Url::get('date_from')?' AND wh_invoice.create_date >=\''.Date_Time::to_orc_date(Url::get('date_from')).'\'':'').'
				'.(Url::get('date_to')?' AND wh_invoice.create_date <=\''.Date_Time::to_orc_date(Url::get('date_to')).'\'':'').'
		';    //'.(Url::get('warehouse_id')?' AND wh_invoice.warehouse_id = '.Url::get('warehouse_id'):'').'
		      //'.(Url::get('warehouse_id')?' AND '.IDStructure::child_cond(DB::structure_id('warehouse',Url::iget('warehouse_id'))).'':'').'
		$old_cond = '1=1 AND
				(
				(wh_invoice.type=\'IMPORT\' AND wh_invoice_detail.warehouse_id='.Url::iget('warehouse_id').') OR 
				(wh_invoice.type=\'EXPORT\' AND (wh_invoice_detail.warehouse_id='.Url::iget('warehouse_id').'))
				)
				'.(Url::get('date_from')?' AND wh_invoice.create_date <\''.Date_Time::to_orc_date(Url::get('date_from')).'\'':'').'
		';
        $wh_remain_date = DB::fetch_all('select * from wh_remain_date where  warehouse_id='.Url::iget('warehouse_id').' and portal_id = \''.PORTAL_ID.'\'');
        $new_cond = '';
        foreach($wh_remain_date as $key=>$value)
        {
            if($value['end_date'] != '' and Date_Time::to_time(Url::get('date_from'))< Date_Time::to_time(Date_Time::convert_orc_date_to_date($value['term_date'],'/')) and Date_Time::to_time(Url::get('date_from'))>= Date_Time::to_time(Date_Time::convert_orc_date_to_date($value['term_date'],'/')))
            {
                $new_cond.= ' AND wh_remain_date_detail.term_date =\''.$value['term_date'].'\'';
                $old_cond.= ' AND wh_invoice.create_date >=\''.$value['term_date'].'\'';
            }
            if($value['end_date']=='' and Date_Time::to_time(Url::get('date_from'))>= Date_Time::to_time(Date_Time::convert_orc_date_to_date($value['term_date'],'/')))
            {
                $new_cond.= ' AND wh_remain_date_detail.term_date =\''.$value['term_date'].'\'';
                $old_cond.= ' AND wh_invoice.create_date >=\''.$value['term_date'].'\'';
            }
        }
        //Lay cac PN PX phat sinh tu kho truoc khoang tg dinh xem
		$sql = '
			SELECT
				wh_invoice_detail.*,
                (CASE
                    WHEN money_add is null and wh_invoice_detail.payment_price != 0 and wh_invoice_detail.payment_price is not null THEN wh_invoice_detail.payment_price
                    WHEN money_add is null and (wh_invoice_detail.payment_price = 0 or wh_invoice_detail.payment_price is null) THEN wh_invoice_detail.price * wh_invoice_detail.num
                    ELSE wh_invoice_detail.money_add
                 END
                ) as total_money,
                wh_invoice.type
			FROM
				wh_invoice_detail
				INNER JOIN wh_invoice ON wh_invoice.id= wh_invoice_detail.invoice_id
                INNER JOIN product on wh_invoice_detail.product_id = product.id
                INNER JOIN unit on product.unit_id = unit.id
                INNER JOIN product_category on product.category_id = product_category.id
			WHERE
				'.$old_cond.'
                and wh_invoice.portal_id = \''.PORTAL_ID.'\'
			ORDER BY
				wh_invoice_detail.product_id	
		';
		$items = DB::fetch_all($sql);
		$old_items = array();
		if(is_array($items))
		{
			foreach($items as $key=>$value)
            {
				$product_id = $value['product_id'];
				if(isset($old_items[$product_id]))
                {
					if($value['type']=='IMPORT' or $value['to_warehouse_id'] == Url::get('warehouse_id'))
                    {
						$old_items[$product_id]['import_number'] += $value['num'];
                        $old_items[$product_id]['total_import_money'] += $value['total_money'];
                    }
					else
                        if($value['type']=='EXPORT' and $value['to_warehouse_id'] != Url::get('warehouse_id'))
                        {
    						$old_items[$product_id]['export_number'] += $value['num'];
                            $old_items[$product_id]['total_export_money'] += $value['total_money'];
                        }
				}
                else
                {
					$old_items[$product_id]['import_number'] = 0;
					$old_items[$product_id]['export_number'] = 0;
                    $old_items[$product_id]['total_import_money'] = 0;
					$old_items[$product_id]['total_export_money'] = 0;
                    //PN kho hoac PX nhung ma kho den la kho can tinh
					if($value['type']=='IMPORT' or $value['to_warehouse_id'] == Url::get('warehouse_id'))
                    {
						$old_items[$product_id]['import_number'] = $value['num'];
                        $old_items[$product_id]['total_import_money'] = $value['total_money'];
                    }
                    //PX ma kho xuat den khong phai la kho can tinh(tranh th tao PX ma tu kho A den kho A)
					if($value['type']=='EXPORT' and $value['to_warehouse_id'] != Url::get('warehouse_id'))
                    {
						$old_items[$product_id]['export_number'] = $value['num'];
                        $old_items[$product_id]['total_export_money'] = $value['total_money'];
                    }
				}
			}
		}
		$sql = '
			SELECT
				wh_invoice_detail.id,
                wh_invoice_detail.product_id,
				product.name_'.Portal::language().' as name,
                unit.name_'.Portal::language().' as unit,
				wh_invoice_detail.num,
                wh_invoice.type,
                wh_start_term_remain.quantity as start_term_quantity,
				product.category_id,
                wh_invoice_detail.warehouse_id,
                wh_invoice_detail.to_warehouse_id,
                wh_invoice_detail.price,
                DECODE(wh_invoice_detail.money_add,null,0,wh_invoice_detail.money_add) as money_add,
                (CASE
                    WHEN (wh_invoice_detail.payment_price != 0 and wh_invoice_detail.payment_price is not null) THEN wh_invoice_detail.payment_price
                    ELSE wh_invoice_detail.price * wh_invoice_detail.num
                 END
                ) as total_money
			FROM
				wh_invoice_detail
                INNER JOIN wh_invoice ON wh_invoice.id= wh_invoice_detail.invoice_id
                INNER JOIN product on wh_invoice_detail.product_id = product.id
                INNER JOIN unit on product.unit_id = unit.id
                INNER JOIN product_category on product.category_id = product_category.id
				LEFT OUTER JOIN wh_start_term_remain ON wh_start_term_remain.product_id = product.id
			WHERE
				'.$cond.'
                and wh_invoice.portal_id = \''.PORTAL_ID.'\'
			ORDER BY
				wh_invoice_detail.product_id	
		';
		$items = DB::fetch_all($sql);
        if (User::is_admin())
        {
            //System::debug($items);
        }
        
        $wh_remain_date_detail = array();
        if($new_cond!='')
        {
            $sql = '
    		SELECT
    			product.id,
                product.id as product_id,
                product.name_'.Portal::language().' as name,
    			unit.name_'.Portal::language().' as unit,
                wh_remain_date_detail.quantity as start_term_quantity,
    			wh_remain_date_detail.quantity as remain_number,
    			0 as import_number,
                0 as export_number,
                0 as total_import_money,
                0 as total_export_money,
                0 as money_add,
                product_category.name as category_id,
                wh_remain_date_detail.start_term_price,
                wh_remain_date_detail.TOTAL_START_TERM_PRICE as total_start_term_money, 
    			rownum
    		FROM
    			product
    			LEFT JOIN wh_remain_date_detail ON wh_remain_date_detail.product_id = product.id '.$new_cond.'
                INNER JOIN unit on product.unit_id = unit.id
                INNER JOIN product_category on product.category_id = product_category.id	
    		WHERE
                warehouse_id='.Url::iget('warehouse_id').'
    		ORDER BY
    			product_category.name,product.id
    	  ';
          $wh_remain_date_detail = DB::fetch_all($sql);
        }
        
        //Lay ton dau ki
        $sql = '
		SELECT
			product.id,
            product.id as product_id,
            product.name_'.Portal::language().' as name,
			unit.name_'.Portal::language().' as unit,
            CASE 
                    WHEN wh_start_term_remain.quantity >0 THEN wh_start_term_remain.quantity
                    ELSE 0 
                END as start_term_quantity,
			CASE 
                    WHEN wh_start_term_remain.quantity >0 THEN wh_start_term_remain.quantity
                    ELSE 0 
                END as remain_number,
			0 as import_number,
            0 as export_number,
            0 as total_import_money,
            0 as total_export_money,
            0 as money_add,
            product_category.name as category_id,
            wh_start_term_remain.start_term_price,
            wh_start_term_remain.TOTAL_START_TERM_PRICE as total_start_term_money, 
			rownum
		FROM
			product
			LEFT JOIN wh_start_term_remain ON wh_start_term_remain.product_id = product.id
            INNER JOIN unit on product.unit_id = unit.id
            INNER JOIN product_category on product.category_id = product_category.id	
		WHERE
            warehouse_id='.Url::iget('warehouse_id').'
		ORDER BY
			product_category.name,product.id
	  ';
		$products = DB::fetch_all($sql);
        foreach($products as $k=>$v)
        {
            if(isset($wh_remain_date_detail[$k]))
            {
                $products[$k]['start_term_quantity'] = $products[$k]['start_term_quantity']+$wh_remain_date_detail[$k]['start_term_quantity'];
                $products[$k]['remain_number'] = $products[$k]['remain_number']+$wh_remain_date_detail[$k]['remain_number'];
                $products[$k]['total_start_term_money'] = $products[$k]['total_start_term_money']+$wh_remain_date_detail[$k]['total_start_term_money'];
            }
        }
		$i = 0;
		$new_products = $products;
		foreach($items as $key=>$value)
        {
			$product_id = $value['product_id'];
			if(isset($new_products[$product_id]['id']))
            {
                $new_products[$product_id]['money_add'] += $value['money_add'];
				if($value['type']=='IMPORT' or $value['to_warehouse_id'] == Url::get('warehouse_id'))
                {
                    $new_products[$product_id]['import_number'] += $value['num'];
                    $new_products[$product_id]['total_import_money'] += $value['total_money'] + $value['money_add'];
                }
				if($value['type']=='EXPORT' and $value['to_warehouse_id'] != Url::get('warehouse_id'))
                {
                    $new_products[$product_id]['export_number'] += $value['num'];
                    $new_products[$product_id]['total_export_money'] += $value['total_money'];
                }
				$new_products[$product_id]['remain_number'] = $new_products[$product_id]['import_number'] - $new_products[$product_id]['export_number'];
				$new_products[$product_id]['remain_number'] += $new_products[$product_id]['start_term_quantity'];
			   
            }
            else
            {
                $new_products[$product_id]['money_add'] = 0;
				$new_products[$product_id]['start_term_quantity'] = 0;
                $new_products[$product_id]['total_start_term_money'] = 0;
				$new_products[$product_id]['id'] = $product_id;
				$new_products[$product_id]['product_id'] = $product_id;
				$new_products[$product_id]['unit'] = $value['unit'];
				$new_products[$product_id]['name'] = $value['name'];
				$new_products[$product_id]['import_number'] = 0;
				$new_products[$product_id]['export_number'] = 0;
                $new_products[$product_id]['total_import_money'] = 0;
				$new_products[$product_id]['total_export_money'] = 0;
				if($value['type']=='IMPORT' or $value['to_warehouse_id'] == Url::get('warehouse_id'))
                {
                    $new_products[$product_id]['import_number'] = $value['num'];
                    $new_products[$product_id]['total_import_money'] = $value['total_money'] + $value['money_add'];
                }
				if($value['type']=='EXPORT' and $value['to_warehouse_id'] != Url::get('warehouse_id'))
                {
                    $new_products[$product_id]['export_number'] = $value['num'];
                    $new_products[$product_id]['total_export_money'] += $value['total_money'];
                }
				$new_products[$product_id]['remain_number'] = $new_products[$product_id]['import_number'] - $new_products[$product_id]['export_number'];
			    
            }
            
		}
		foreach($new_products as $key=>$value)
        {
			$product_id = $value['product_id'];
			if(isset($old_items[$product_id]['import_number']))
            {
				$new_products[$product_id]['start_term_quantity'] += System::calculate_number($old_items[$product_id]['import_number']) - System::calculate_number($old_items[$product_id]['export_number']);
				$new_products[$product_id]['remain_number'] +=  System::calculate_number($old_items[$product_id]['import_number']) - System::calculate_number($old_items[$product_id]['export_number']);
                $new_products[$product_id]['total_start_term_money'] +=  System::calculate_number($old_items[$product_id]['total_import_money']) - System::calculate_number($old_items[$product_id]['total_export_money']);
			}
			if(!isset($value['category_id']))
				$new_products[$product_id]['category_id'] = '...';
		}
		return $new_products;
	}	
	function get_warehouse_export()
    {
		$cond = ' wh_invoice.type = \'EXPORT\' AND wh_invoice_detail.warehouse_id = '.Url::get('warehouse_id').'
			'.(Url::get('date_from')?' AND wh_invoice.create_date >=\''.Date_Time::to_orc_date(Url::get('date_from')).'\'':'').'
			'.(Url::get('date_to')?' AND wh_invoice.create_date <=\''.Date_Time::to_orc_date(Url::get('date_to')).'\'':'').'
		';
        if(Url::get('warehouse_to_id')!='')
        {
            $cond .= 'and warehouse.id = \''.Url::get('warehouse_to_id').'\'';
        }
        else
        {
            $cond .='and 1=1';
        }

		$sql = '
			SELECT
				wh_invoice_detail.product_id,
				product.name_'.Portal::language().' as name,
				wh_invoice_detail.id,
				wh_invoice_detail.price,
                SUM 
                (CASE
                    WHEN (wh_invoice_detail.payment_price != 0 and wh_invoice_detail.payment_price is not null) THEN wh_invoice_detail.payment_price
                    ELSE wh_invoice_detail.price * wh_invoice_detail.num
                 END
                ) as total_payment_price,
				unit.name_'.Portal::language().' as unit_name,
				SUM(wh_invoice_detail.num) as quantity,
                warehouse.name as warehouse_name,
                warehouse.id as warehouse_id,
                wh_invoice.move_product,
                wh_invoice.get_back_supplier
			FROM
				wh_invoice_detail
				INNER JOIN wh_invoice ON wh_invoice.id = wh_invoice_detail.invoice_id
                INNER JOIN product on wh_invoice_detail.product_id = product.id
                INNER JOIN unit on product.unit_id = unit.id
                INNER JOIN product_category on product.category_id = product_category.id
                LEFT JOIN warehouse on warehouse.id = wh_invoice_detail.to_warehouse_id
			WHERE
				'.$cond.'
                and wh_invoice.portal_id = \''.PORTAL_ID.'\'
			GROUP BY
				wh_invoice_detail.product_id,
				product.name_'.Portal::language().',
				wh_invoice_detail.id,
                wh_invoice_detail.price,
                wh_invoice_detail.payment_price,
				unit.name_'.Portal::language().',
                warehouse.name,
				wh_invoice.move_product,
                wh_invoice.get_back_supplier,
                warehouse.id
			ORDER BY
				wh_invoice_detail.product_id
		';
		$items = DB::fetch_all($sql);
		$i = 0;
        //System::debug($items);
		foreach($items as $key=>$value)
        {
			$items[$key]['i'] = ++$i;
			$items[$key]['amount'] = System::display_number($value['total_payment_price']);
			$this->map['total_quantity'] += round ($value['quantity'], 2);
			$this->map['total_amount'] += round($value['total_payment_price'], 2);
			$items[$key]['quantity'] = $value["quantity"];//System::display_number_report($value['quantity']);
			$items[$key]['price'] = System::display_number($value['price']);
            $items[$key]['note']='';        
            if($items[$key]['move_product']==1)
                $items[$key]['note']=Portal::language('move_product');
                
            if($items[$key]['get_back_supplier']==1)
                $items[$key]['note']=Portal::language('returned_supplier');
		}
		$this->map['total_amount'] = System::display_number($this->map['total_amount']);
		$this->map['total_quantity'] = System::display_number($this->map['total_quantity']);
		return $items;
	}
    function get_warehouse_export_supplier()
    {
        $cond2 = ' wh_invoice.type = \'EXPORT\'
			'.(Url::get('supplier_id')?' AND wh_invoice.supplier_id = '.Url::get('supplier_id'):'').'
			'.(Url::get('date_from')?' AND wh_invoice.create_date >=\''.Date_Time::to_orc_date(Url::get('date_from')).'\'':'').'
			'.(Url::get('date_to')?' AND wh_invoice.create_date <=\''.Date_Time::to_orc_date(Url::get('date_to')).'\'':'').'
		';
		$sql = '
			SELECT
				wh_invoice_detail.id,
				product.name_'.Portal::language().' as name,
				wh_invoice_detail.product_id,
				wh_invoice_detail.price,
                (CASE
                    WHEN (wh_invoice_detail.payment_price != 0 and wh_invoice_detail.payment_price is not null) THEN wh_invoice_detail.payment_price
                    ELSE wh_invoice_detail.price * wh_invoice_detail.num
                 END
                ) as payment_price,
				unit.name_'.Portal::language().' as unit_name,
				wh_invoice_detail.num as quantity,
				wh_invoice.create_date,
				wh_invoice.bill_number,
                wh_invoice.invoice_number,
                supplier.name as supplier
			FROM
				wh_invoice_detail
				INNER JOIN wh_invoice ON wh_invoice.id = wh_invoice_detail.invoice_id
                INNER JOIN product on wh_invoice_detail.product_id = product.id
                INNER JOIN unit on product.unit_id = unit.id
                INNER JOIN product_category on product.category_id = product_category.id
                LEFT JOIN supplier on wh_invoice.supplier_id = supplier.id
			WHERE
				'.$cond2.'
                and wh_invoice.portal_id = \''.PORTAL_ID.'\'
                and get_back_supplier = 1
			ORDER BY
				wh_invoice.create_date,wh_invoice_detail.id
		';
		$back_arr_by_date = array();
		$back_items = DB::fetch_all($sql);
        //System::debug($sql);
		$i=0;
        foreach($back_items as $key=>$value)
        {
			$back_items[$key]['i'] = ++$i;
			$back_items[$key]['amount'] = System::display_number($value['payment_price']);
			$this->map['total_quantity'] += round ($value['quantity'], 2);
			$this->map['total_amount'] += round($value['payment_price'], 2);
			$back_items[$key]['quantity'] = $value["quantity"];//System::display_number_report($value['quantity']);
			$back_items[$key]['price'] = System::display_number($value['price']);
			$back_items[$key]['create_date'] = Date_Time::convert_orc_date_to_date($value['create_date'],'/');
			if(!isset($back_arr_by_date[$back_items[$key]['create_date']]))
				$back_arr_by_date[$back_items[$key]['create_date']] = $value['price']*$value['quantity'];
            else
				$back_arr_by_date[$back_items[$key]['create_date']] += $value['price']*$value['quantity'];
		}
		$this->map['back_products'] = $back_items;
		$this->map['back_arr_by_date'] = $back_arr_by_date;
        $this->map['total_amount'] = System::display_number($this->map['total_amount']);
		$this->map['total_quantity'] = System::display_number($this->map['total_quantity']);
		return $back_items;
    }	
	function get_warehouse_import()
    {
		$cond = ' wh_invoice.type = \'IMPORT\'
			'.(Url::get('supplier_id')?' AND wh_invoice.supplier_id = '.Url::get('supplier_id'):'').'
			'.(Url::get('date_from')?' AND wh_invoice.create_date >=\''.Date_Time::to_orc_date(Url::get('date_from')).'\'':'').'
			'.(Url::get('date_to')?' AND wh_invoice.create_date <=\''.Date_Time::to_orc_date(Url::get('date_to')).'\'':'').'
		';
		$sql = '
			SELECT
				wh_invoice_detail.product_id,
				product.name_'.Portal::language().' as name,
				wh_invoice_detail.id,
				wh_invoice_detail.price,
                (CASE
                    WHEN (wh_invoice_detail.payment_price != 0 and wh_invoice_detail.payment_price is not null) THEN wh_invoice_detail.payment_price
                    ELSE wh_invoice_detail.price * wh_invoice_detail.num
                 END
                ) as payment_price,
				unit.name_'.Portal::language().' as unit_name,                
                wh_invoice_detail.num as quantity,
                wh_invoice.create_date,
				wh_invoice.bill_number,
                wh_invoice.invoice_number,
                supplier.name as supplier
			FROM
				wh_invoice_detail
				INNER JOIN wh_invoice ON wh_invoice.id = wh_invoice_detail.invoice_id
                INNER JOIN product on wh_invoice_detail.product_id = product.id
                INNER JOIN unit on product.unit_id = unit.id
                INNER JOIN product_category on product.category_id = product_category.id
                INNER join supplier on wh_invoice.supplier_id = supplier.id
			WHERE
				'.$cond.'
                and wh_invoice.portal_id = \''.PORTAL_ID.'\'
			ORDER BY
				wh_invoice.create_date,wh_invoice_detail.id
		';
		$items = DB::fetch_all($sql);
		$i = 0;
		$arr_by_date = array();
        $quantity_by_date = array();
		foreach($items as $key=>$value)
        {
			$items[$key]['i'] = ++$i;
			$items[$key]['amount'] = System::display_number($value['payment_price']);
            $this->map['total_quantity'] += $value['quantity'];
			$this->map['total_amount'] += round($value['payment_price']);
			if($value["quantity"]<1)
            $items[$key]['quantity'] = '0'.$value["quantity"];//System::display_number_report($value['quantity']);
            else
            $items[$key]['quantity'] = $value["quantity"];
			$items[$key]['price'] = System::display_number($value['price']);
			$items[$key]['create_date'] = Date_Time::convert_orc_date_to_date($value['create_date'],'/');
			if(!isset($arr_by_date[$items[$key]['create_date']]))
                $arr_by_date[$items[$key]['create_date']] = round($value['price']*$value['quantity']);
            else
                $arr_by_date[$items[$key]['create_date']] += round($value['price']*$value['quantity']);
            if(!isset($quantity_by_date[$items[$key]['create_date']]))
                $quantity_by_date[$items[$key]['create_date']] = round ($value['quantity'], 2);
            else
                $quantity_by_date[$items[$key]['create_date']] += round ($value['quantity'], 2);
		}
        //System::debug($items);
		$this->map['arr_by_date'] = $arr_by_date;
        $this->map['quantity_by_date'] = $quantity_by_date;
        $cond = ' wh_invoice.type = \'EXPORT\'
			'.(Url::get('supplier_id')?' AND wh_invoice.supplier_id = '.Url::get('supplier_id'):'').'
			'.(Url::get('date_from')?' AND wh_invoice.create_date >=\''.Date_Time::to_orc_date(Url::get('date_from')).'\'':'').'
			'.(Url::get('date_to')?' AND wh_invoice.create_date <=\''.Date_Time::to_orc_date(Url::get('date_to')).'\'':'').'
		';
		$this->map['total_before_tax'] = $this->map['total_amount']/(1.1);
		$this->map['shipping_fee'] = 0;
		$this->map['grand_total'] = 0;
		if(Url::get('shipping_fee'))
			$this->map['shipping_fee'] = Url::get('shipping_fee');
		$this->map['commission'] = 0;
		$this->map['total_commission'] = 0;
		if(Url::get('commission'))
        {
			$this->map['commission'] = 	Url::get('commission');
			$this->map['total_commission'] = $this->map['total_before_tax']*$this->map['commission']/100;
		}
		$this->map['total_after_commission'] = $this->map['total_before_tax'] - $this->map['total_commission'];
		$this->map['total_before_tax_commission'] = $this->map['total_after_commission']*(1.1);
		$this->map['grand_total'] = $this->map['total_before_tax_commission'] + $this->map['shipping_fee'];
		$this->map['total_commission'] = System::display_number($this->map['total_commission']);
		$this->map['total_before_tax_commission'] = System::display_number($this->map['total_before_tax_commission']);
		$this->map['total_after_commission'] =  System::display_number($this->map['total_after_commission']);
		$this->map['total_before_tax'] = System::display_number($this->map['total_before_tax']);
		$this->map['shipping_fee'] = System::display_number($this->map['shipping_fee']);
		$this->map['total_amount'] = System::display_number($this->map['total_amount']);
		$this->map['total_quantity'] = System::display_number($this->map['total_quantity']);
		$this->map['grand_total'] = System::display_number($this->map['grand_total']);
		return $items;
	}	
}
?>
