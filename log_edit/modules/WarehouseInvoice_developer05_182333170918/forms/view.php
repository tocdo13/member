<?php
class ViewWarehouseInvoiceForm extends Form
{
	function ViewWarehouseInvoiceForm()
	{
		Form::Form('ViewWarehouseInvoiceForm');
		$this->link_css(Portal::template('warehouse').'/css/invoice.css');
	}
	function draw()
	{
		require_once 'packages/core/includes/utils/currency.php';
		$this->map = array();
		$this->map['total_debit'] = 0;
		$this->map['total'] = 0;
		$this->map['total_discount'] = 0;
        $this->map['get_back_supplier_name'] = '';
		$item = WarehouseInvoice::$item;
        //System::debug($item);
		if($item)
        {
			if(Url::get('type')=='IMPORT')
            {
				$cond = 'wh_invoice.create_date <= \''.$item['create_date'].'\' '.($item['supplier_id']?'AND wh_invoice.supplier_id = '.$item['supplier_id']:'');
				$group_by = 'wh_invoice.id';
				$this->map['title'] = Portal::language('warehouse_import_invoice');
                $this->map['en_title'] = Portal::language('goods_receiving_note');				
			}
            else
            {
				$cond = 'wh_invoice.create_date <= \''.$item['create_date'].'\'
				'.($item['warehouse_id']?' AND wh_invoice.warehouse_id = '.$item['warehouse_id'].'':'').'
				';
				$group_by = 'wh_invoice.warehouse_id,wh_invoice.id';
				$this->map['title'] = Portal::language('warehouse_export_invoice');
                $this->map['en_title'] = Portal::language('goods_delivery_note');
                if($item['get_back_supplier']==1)
                {
                    $this->map['get_back_supplier_name'] = ($item['supplier_id']!='')?DB::fetch('select name from supplier where id='.$item['supplier_id'],'name'):'';
                }
			}
			$sql1 = '
				SELECT 
					wh_invoice.id,
					SUM(wh_invoice_detail.price*wh_invoice_detail.num) as total_amount
				FROM
					wh_invoice_detail
					INNER JOIN wh_invoice ON wh_invoice.id = wh_invoice_detail.invoice_id
					LEFT OUTER JOIN warehouse W1 ON W1.id = wh_invoice_detail.warehouse_id
					LEFT OUTER JOIN warehouse W2 ON W2.id = wh_invoice_detail.to_warehouse_id
				WHERE
					'.$cond.'
				GROUP BY 
					'.$group_by.'
			';
            if($wh_receiver_name=DB::fetch('select wh_receiver_name as id from wh_invoice where id='.Url::get('id'),'id'))
            {
                $this->map['wh_receiver_name']=DB::fetch('select name from wh_receiver where id='.$wh_receiver_name,'name');
            }
            $total_amount = DB::fetch($sql1,'total_amount');
			$this->map['supplier_name'] = ($item['supplier_id']!='')?DB::fetch('SELECT id,name FROM supplier WHERE id=\''.$item['supplier_id'].'\'','name'):'';
			$this->map['warehouse_name'] = DB::fetch('SELECT id,name FROM warehouse WHERE id=\''.$item['warehouse_id'].'\'','name');
			$this->map['staff_name'] = DB::fetch('SELECT id,name_1 FROM party WHERE user_id=\''.$item['user_id'].'\'','name_1');
			$item['create_date'] = Date_Time::convert_orc_date_to_date($item['create_date'],'/');
			$arr = explode('/',$item['create_date']);
			$this->map['year'] = $arr[2];
			$this->map['month'] = $arr[1];
			$this->map['day'] = $arr[0];
			$item['create_date'] = Date_Time::to_common_date($item['create_date']);
			$products = DB::fetch_all('
				SELECT
					wh_invoice_detail.*,
                    wh_invoice_detail.payment_price,
					product.name_'.Portal::language().' as name,unit.name_'.Portal::language().' as unit_name,
					W1.name as warehouse,
                    W2.name as to_warehouse
				FROM
					wh_invoice_detail
					INNER JOIN product ON product.id = wh_invoice_detail.product_id
					INNER JOIN unit ON unit.id = product.unit_id
                    INNER JOIN product_category ON product_category.id = product.category_id
					LEFT OUTER JOIN warehouse W1 ON W1.id = wh_invoice_detail.warehouse_id
					LEFT OUTER JOIN warehouse W2 ON W2.id = wh_invoice_detail.to_warehouse_id
				WHERE
					wh_invoice_detail.invoice_id=\''.$item['id'].'\'
                ORDER BY
                    wh_invoice_detail.id ASC    
			'
			);//and wh_invoice_detail.num !=0
            $this->map['to_warehouse_name'] = '';
			$i=0;
			foreach($products as $k=>$v)
            {
                
                if($v['num']==0)
                {
                    unset($products[$k]);
                }else
                {
                    if(!$this->map['to_warehouse_name'])
                        $this->map['to_warehouse_name'] = $v['to_warehouse'];
    				$products[$k]['i'] = ++$i;
    				/*if ($products[$k]['payment_price'] == 0)
                    {
                        $payment_amount = $v['price']*$v['num'];
                    }
                    else
                    {
                        $payment_amount = $v['payment_price'];
                    }*/
                    $payment_amount = $v['price']*$v['num'];
                    $products[$k]['payment_amount'] = $payment_amount;
                    if($v['num'] >= 1)
                    {
                        $products[$k]['number'] = $v['num'];
                        $products[$k]['price'] = $v['price'];
                        //$this->map['total'] += $payment_amount; 
                        
                    }
                    else 
                    {
                        $products[$k]['number'] = '0'.($v['num']);
                        $products[$k]['price'] = $v['price'];
                        $products[$k]['money_add'] =$v['money_add'];
                        //$this->map['total'] += $v['money_add'];
                    }
    				$products[$k]['payment_price'] = $v['price']*$v['num'];
                    $this->map['total'] += System::calculate_number($products[$k]['payment_price']);
                }
			}
            if(User::id()=='developer06')
            {
                //System::debug($products);
            }
			$this->map['products'] = $products;
		}
        $sql = "SELECT id, name_".Portal::language()." as name FROM party WHERE user_id='".User::id()."'";
        $user_name = DB::fetch($sql);
        $user_name = $user_name['name'];
        $this->map['user_name'] = $user_name;
        $this->map += $item;
		$currency = DB::select('currency','id=\'VND\'');
        $this->map['total_amount'] = $this->map['total']+ $this->map['tax'];
		$currency_by_letter = round($this->map['total'],2);
        //tân:chỗ này bắt buộc dùng hàm round thì mới đọc đúng tên tiếng việt vì trong currency_to_text là kiểu số còn System::display_number sẽ có
        //kiểu string trong đó. hiểu chưa. chưa thì đi mà tìm hiểu
        $this->map['total_by_letter'] = currency_to_text($currency_by_letter);
		$this->map['total'] = $this->map['total'];
        
		
		$layout = 'view';
		if($this->map['move_product']==1)
        {
				$layout = 'view_moved_product';
				$this->map['title'] = Portal::language('move_product');
		}
        
		$this->parse_layout($layout,array()+$this->map);
	}	
}
?>
