<?php
class EditShopInvoiceForm extends Form
{
	function EditShopInvoiceForm()
	{
		Form::Form('EditShopInvoiceForm');
		$this->add('code',new TextType(true,'invalid_code',0,20));
		$this->add('agent_name',new TextType(false,'invalid_agent_name',0,255));
		$this->add('agent_address',new TextType(false,'invalid_agent_address',0,255)); 
		$this->add('shop_id',new IDType(true,'invalid_bar_id','shop')); 
		$this->link_css('packages/hotel/skins/default/css/suggestion.css');
		$this->link_js('packages/core/includes/js/jquery/jquery.autocomplete.js');				
		$this->link_js('packages/hotel/packages/giftshop/includes/js/update_price.js');
	}
	function on_submit()
	{
		if(URL::check(array('select_bar'=>0)))
		{
			if($this->check())
			{
				$total = String::convert_to_vnnumeric(Url::get('sum_total'));
				DB::update('shop_invoice', array(
					'agent_name', 
					'agent_address', 
					'shop_id','tax','tax_rate','total_before_tax'=>Url::get('sumary'),
					'total'=>$total,
					),'id='.Url::get('id')
				);
				
				//xoa cac ban ghi hien co trong bang bar_reservation_table va shop_invoice_detail
				DB::delete('shop_invoice_detail','shop_invoice_id=\''.Url::get('id').'\'');
				
				$list_product = array();
				if(Url::check('product__id') and Url::get('product__id')!='')
				{
					$list_product['product_id'] = Url::get('product__id');
					$list_product['quantity'] = Url::get('product__quantity');
					$list_product['price'] = Url::get('product__price');
					$list_product['discount_rate'] = Url::get('product__discount_rate');
					$list_product['quantity_discount'] = Url::get('product__quantity_discount');
					$list_product['discount'] = Url::get('product__discount');
					$sample = current($list_product);
					foreach($sample as $row=>$row_data)
					{
						$list_product['price'][$row] = String::convert_to_vnnumeric($list_product['price'][$row]);
						$blank = true;
						$item = array('shop_invoice_id'=>Url::get('id'));
						foreach($list_product as $column=>$column_data)
						{
							if($list_product[$column][$row])
							{
								$blank = false;
							}
							$item[$column] = $list_product[$column][$row];
						}
						if(!$blank)
						{
							DB::insert('shop_invoice_detail',$item);
						}
					}
				}
				$title = ''
					.substr(URL::get('id'),0,32).',  ' .substr(URL::get('time'),0,32).',  ';
				$description = ''
					.((URL::get('agent_name')!=$row['agent_name'])?'Change '.Portal::language('agent_name').' from '.substr($row['agent_name'],0,255).' to '.substr(URL::get('agent_name'),0,255).'<br>  ':'') 
					.((URL::get('agent_address')!=$row['agent_address'])?'Change '.Portal::language('agent_address').' from '.substr($row['agent_address'],0,255).' to '.substr(URL::get('agent_address'),0,255).'<br>  ':'') 
					.((URL::get('bar_id')!=$row['bar_id'])?'Change '.Portal::language('bar_id').' from <a href="?page=bar&id='.$row['bar_id'].'">#'.$row['bar_id'].'</a> to <a href="?page=bar&id='.URL::get('bar_id').'">#'.URL::get('').'</a><br>  ':'');
				System::log('edit',Portal::language('edit').' '.$title,$description,$_REQUEST['id']);
				Url::redirect('shop_invoice',array('id'));
			}
		}
	}	
	function draw()
	{	
		$row = DB::select('shop_invoice',Url::get('id'));
		//============================== product ===============================
		$product = ShopInvoiceDB::get_products();
		$i=0;
		$total_product = sizeof($product);
		foreach($product as $k1=>$g)
		{
			$product[$k1]['stt'] = $i++;
			if($i == $total_product)
			{
				$product[$k1]['last'] = 1;
			}
			else
			{
				$product[$k1]['last'] = 0;
			}
		}
		
		//danh sach bar
		DB::query('select id, name from shop order by name');
		$rows_list=DB::fetch_all();
		$list_shop[0]='-------';
		$list_shop=$list_shop+String::get_list($rows_list,'name');
		
		DB::query('
			select 
				shop_product.name_'.Portal::language().' as name, 
				shop_invoice_detail.product_id as id,
				shop_invoice_detail.quantity as quantity,
				shop_invoice_detail.quantity_discount,
				shop_invoice_detail.discount_rate,
				shop_invoice_detail.price, 
				unit.name_'.Portal::language().' as unit_name 
			from 
				shop_invoice_detail
				inner join shop_product on shop_product.id=shop_invoice_detail.product_id 
				inner join unit on unit.id = shop_product.unit_id 
			where 
				shop_invoice_detail.shop_invoice_id=\''.Url::get('id').'\''
		);
		$product_items = DB::fetch_all();
		$sumary = 0;
		foreach($product_items as $key=>$value)
		{
			$product_items[$key]['product__id'] = $value['id'];
			$product_items[$key]['product__name'] = $value['name'];
			$product_items[$key]['product__quantity'] = $value['quantity'];
			$product_items[$key]['product__price'] = System::display_number_report($value['price']);
			$product_items[$key]['product__total'] = System::display_number_report(($value['price']*$value['quantity']-$value['price']*$value['quantity_discount']) - ($value['quantity']-$value['quantity_discount'])*$value['price']*$value['discount_rate']/100);
			$product_items[$key]['product__unit'] = $value['unit_name'];
			$product_items[$key]['product__quantity_discount'] = $value['quantity_discount']?$value['quantity_discount']:0;
			$product_items[$key]['product__discount'] = $value['discount_rate']?$value['discount_rate']:0;
			$sumary += $value['quantity']*$value['price'];
		}
		$row['code'] = $row['id'];
		$row['sumary'] = System::display_number_report($row['total_before_tax']);
		$row['bar_fee'] = System::display_number_report($sumary*5/100);
		$row['sum_total'] = System::display_number_report($row['total']);
		$row['tax'] = System::display_number_report($row['tax']);
		$this->map = $row;
		
		$this->parse_layout('edit',$this->map+array(
			'date'=>date('d/m/Y',time()),
			'product'=>$product,
			'product_items'=>$product_items,
			'shop_id_list'=>$list_shop
		));
	}
}
?>