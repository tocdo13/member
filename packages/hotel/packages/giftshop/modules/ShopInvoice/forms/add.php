<?php
class AddShopInvoiceForm extends Form
{
	function AddShopInvoiceForm()
	{
		Form::Form('AddShopInvoiceForm');
		$this->add('agent_name',new TextType(true,'invalid_agent_name',0,255));
		$this->add('agent_address',new TextType(false,'invalid_agent_address',0,255)); 
		$this->add('shop_id',new IDType(true,'invalid_shop_id','shop')); 
		$this->link_css('packages/hotel/skins/default/css/suggestion.css');
		$this->link_js('packages/core/includes/js/jquery/jquery.autocomplete.js');
	}
	function on_submit()
	{
		if(Url::get('save')){
			if(URL::check(array('select_bar'=>0)))
			{
				if($this->check())
				{
					$total = String::convert_to_vnnumeric(Url::get('sum_total'));
					$id = DB::insert('shop_invoice', 
						array(
							'shop_id','agent_name','agent_address','time'=>time(),
							'tax','tax_rate','total_before_tax'=>Url::get('sumary'),'total'=>$total
					));
					$list_product = array();
					if(Url::check('product__id') and Url::get('product__id')!='')
					{
						$list_product['product_id'] = Url::get('product__id');
						$list_product['quantity'] = Url::get('product__quantity');
						$list_product['price'] = String::convert_to_vnnumeric(Url::get('product__price'));
						$list_product['discount_rate'] = Url::get('product__discount_rate');
						$list_product['quantity_discount'] = Url::get('product__quantity_discount');
						$list_product['discount'] = Url::get('product__discount');
						$sample = current($list_product);
						foreach($sample as $row=>$row_data)
						{
							$blank = true;
							$item = array('shop_invoice_id'=>$id);
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
					$title = ''.substr(URL::get('code'),0,32).',  ' .substr(URL::get('status'),0,32).',  '     .substr(URL::get('time'),0,32).',  ';
					$description = ''
					.Portal::language('code').':'.substr(URL::get('code'),0,255).'<br>  ' 
					.Portal::language('agent_name').':'.substr(URL::get('agent_name'),0,255).'<br>  ' 
					.Portal::language('agent_address').':'.substr(URL::get('agent_address'),0,255).'<br>  ' 
					.Portal::language('shop_id').':'.URL::get('shop_id').'<br>  ';
					System::log('add',Portal::language('add').' '.$title,$description,$id);
					Url::redirect('shop_invoice',array('id'=>$id));
				}
			}
		}
	}
	function draw()
	{
		require_once 'packages/hotel/packages/restaurant/includes/table.php';
		DB::query('select id from shop_invoice order by id desc');
		$res = DB::fetch();
		$current_code = $res['id']+1;
		
		//============================== currency ================================
		$curr = HOTEL_CURRENCY;
		$currency=DB::select('currency','name=\''.$curr.'\'');
		
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
		$product_row['product_items']=array();
		
		//danh sach shop
		DB::query('select id, name from shop order by name');
		$rows_list = DB::fetch_all();
		$list_shop[0]='-------';
		$list_shop=$list_shop+String::get_list($rows_list,'name');

		$this->parse_layout('add',$product_row+
			array(
				'curr'=>$curr,
				'current_code'=>$current_code,
				'date'=>date('d/m/Y',time()),
				'product'=>$product,
				'shop_id_list'=>$list_shop
			)
		);
	}
}
?>