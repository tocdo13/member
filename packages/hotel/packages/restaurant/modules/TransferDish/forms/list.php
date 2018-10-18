<?php
class SplitTableForm extends Form
{
	function SplitTableForm()
	{
		Form::Form('SplitTableForm');	
	}		
	function on_submit()
	{
		if(Url::get('acction') == 1){	
			if(Url::get('bars')){
				Session::set('bar_id',Url::get('bars'));
			}
			$_REQUEST['bar_id'] = Session::get('bar_id');
			//Url::redirect_current(array('bar_id'=>Session::get('bar_id'),'status'));	
		}else{
			if(Url::get('confirm')==1 and Url::get('cmd')!='change_table')
			{
				if(!isset($_REQUEST['from_code']))
				{
					$this->error('from_code','select_from_code');
					return;
				}
				if(!isset($_REQUEST['to_code']))
				{
					$this->error('to_code','select_to_code');
					return;				
				}
				if($_REQUEST['to_code'] == $_REQUEST['from_code'])
				{
					$this->error('to_code','duplicate_code');
					return;
				}
				if(isset($_REQUEST['from_code']) and $_REQUEST['from_code'] and Url::get('save'))
				{
					if(DB::select_id('bar_reservation',intval(Url::get('from_code'))))
					{
						$br_from_code = DB::fetch('select * from bar_reservation where id='.Url::get('from_code').'');
						$br_to_code = DB::fetch('select * from bar_reservation where id='.Url::get('to_code').'');
						$total_new = $br_from_code['total'] + $br_to_code['total'];
						$total_before_tax_new = $br_from_code['total_before_tax'] + $br_to_code['total_before_tax'];
						DB::update_id('BAR_RESERVATION',array('last_time'=>time(),'lastest_edited_user_id'=>User::id(),'total'=>$total_new,'total_before_tax'=>$total_before_tax_new),Url::get('to_code'));
						DB::delete_id('bar_reservation',intval(Url::get('from_code')));
						//DB::query('update bar_reservation_product set bar_reservation_id ='.Url::get('to_code').' where bar_reservation_id='.Url::get('from_code'));
						//DB::query('update bar_reservation_table set bar_reservation_id ='.Url::get('to_code').' where bar_reservation_id='.Url::get('from_code'));
						$product_1 =DB::fetch_all('select bar_reservation_product.product_id as id,bar_reservation_product.quantity,bar_reservation_product.id as brp_id,bar_reservation_product.product_name from bar_reservation_product
						where bar_reservation_id ='.Url::get('from_code').'');
						$product_2 =  DB::fetch_all('select bar_reservation_product.* from bar_reservation_product
						where bar_reservation_id ='.Url::get('to_code').'');	
						foreach($product_2 as $p =>$product){
							if(isset($product_1[$product['product_id']]) && (($product['product_id']!='FOUTSIDE' && $product['product_id']!='DOUTSIDE') || ($product_1[$product['product_id']]['product_name']!=$product['product_name']))){
								DB::query('delete from bar_reservation_product where id = '.$product_1[$product['product_id']]['brp_id'].'');
								DB::query('update bar_reservation_product set quantity ='.($product_1[$product['product_id']]['quantity']+$product['quantity']).' where id='.$p.'');
							}
						}
						foreach($product_1 as $p1 =>$prd){
							if(!isset($product_2[$p1])){
								//DB::query('delete from bar_reservation_product where id = '.$product_1[$product['product_id']]['brp_id'].'');
								DB::query('update bar_reservation_product set bar_reservation_id ='.Url::get('to_code').' where id='.$prd['brp_id'].'');
							}
						}
						DB::query('update bar_reservation_table set bar_reservation_id ='.Url::get('to_code').' where bar_reservation_id='.Url::get('from_code'));
					}
					if(!$this->is_error())
					{
						echo '<script>alert("'.Portal::language('table_split_success').'");window.close();</script>';
					}	
				}
			}	
		}
	}
	function draw()
	{

		$from_table  = array();
		$cond = '1=1';
		require_once 'packages/hotel/packages/restaurant/includes/table.php';
		$cond_admin = Table::get_privilege_bar();
		$bars = DB::fetch_all('SELECT * FROM BAR where 1=1 '.$cond_admin.' ORDER BY ID ASC');
		$list_bars[0] = '----Select_Bar----';
		$list_bars = $list_bars + String::get_list($bars,'name');
		if(Session::get('bar_id') && isset($bars[Session::get('bar_id')])){
			$_REQUEST['bars'] = Session::get('bar_id');
		}
		if($from_table = SplitTableDB::get_table_use())
		{
			/*$ids = '0';
			foreach($from_table as $key=>$value)
			{
				$ids.=','.$value['table_id'];
			}
			$cond = 'BAR_TABLE.ID NOT IN ('.$ids.')';
			*/
		}
		if(!(Url::get('from_code') && $order_menu = SplitTableDB::get_order_menu(Url::iget('from_code'))))
		{
			$order_menu = array();			
		}
		$tables = SplitTableDB::get_bar_table(Url::iget('from_code'));
		$to_tables = SplitTableDB::get_table($cond);
		$tables_option = '<option value="">'.Portal::language('none').'</option>';
		foreach($to_tables as $key=>$value)
		{
			$tables_option.='<option value="'.$key.'">'.$value['name'].'</option>';
		}
		$this->parse_layout('list',array(
			'from_code_list'=>array('')+String::get_list($from_table),
			'to_code_list'=>array('')+String::get_list($from_table),
			'bars_list'=>$list_bars			
		));
	}
}
?>
