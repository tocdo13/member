<?php
class SplitTableForm extends Form
{
	function SplitTableForm()
	{
		Form::Form('SplitTableForm');
	}		
	function check_in_bar()
	{
		//require_once 'packages/hotel/packages/restaurant/includes/table.php';
		$check = false;
		$bar_reservation_id = Url::iget('from_code');
		$bar_table = SplitTableDB::get_bar_table(Url::iget('from_code'));
		$split_table = Url::get('split_table');	
		if(Url::get('new_table_id') and Url::get('split_table')){
			if(!isset($_REQUEST['order']) or empty($_REQUEST['order'])){
				return false;	
			}
			$new_table_ids = explode(",",Url::get('new_table_id'));
                        $new_table_ids = array_unique($new_table_ids); 
			for($j=0;$j<count($new_table_ids);$j++){			
				$table_new = DB::fetch('select * from bar_reservation_table INNER JOIN bar_reservation on bar_reservation.id = bar_reservation_table.bar_reservation_id where table_id ='.$new_table_ids[$j].' AND bar_reservation.status=\'CHECKIN\'');
				if($table_new){
						$check = true;						
						return false;
						//break;	
				}
			}
			if(!$check)
			{
				$item = DB::fetch('select * from BAR_RESERVATION where id ='.Url::iget('from_code'));
				unset($item['id']);
				unset($item['code']);
				$item['code'] = SplitTableDB::get_code_in_day(time(),Session::get('bar_id'));
				$bar_reservation_id = DB::insert('BAR_RESERVATION',$item);
				//DB::update_id('BAR_RESERVATION',array('code'=>$code),$bar_reservation_id);
				// Xu ly tach ban - tach hoa don
				foreach($split_table as $value){
					$split = $value;
				}
				$bar_rt = DB::fetch('select * from BAR_RESERVATION_TABLE where id='.$split);
				unset($bar_rt['id']);
				for($i=0;$i<count($new_table_ids);$i++){
					$bar_rt['table_id'] = $new_table_ids[$i];
					$bar_rt['bar_reservation_id'] = $bar_reservation_id;
					//DB::delete_id('BAR_RESERVATION_TABLE',$value);
					DB::insert('BAR_RESERVATION_TABLE',$bar_rt);
				}
				
			}
		}else { echo '<script>alert("'.Portal::language('no_table_selected').'");location="'.Url::build_current(array('type'=>'split')).'"</script>'; return false;}
		return $bar_reservation_id;
	}
	function split_table()
	{
		$bar_reservation_id = $this->check_in_bar();
		if(!$bar_reservation_id){
			echo '<script>alert("'.Portal::language('can_not_change').'");location="'.Url::build_current(array('type'=>'split')).'"</script>';	
			return false;
		}
		$cancel = true;
		$total = 0;
		//if(isset($_REQUEST['order']) and !empty($_REQUEST['order'])){
		foreach($_REQUEST['order'] as $key)
		{
			if(isset($_REQUEST['quantity'][$key]) and isset($_REQUEST['quantity_before'][$key]))
			{
				if($_REQUEST['quantity'][$key] >= $_REQUEST['quantity_before'][$key])
				{
					//chuyen toan bo san pham tu ban cu sang ban chuyen
					DB::update_id('BAR_RESERVATION_PRODUCT',array('BAR_RESERVATION_ID'=>$bar_reservation_id),$key);					
				}
				elseif($item = DB::exists_id('BAR_RESERVATION_PRODUCT',$key))
				{
					$cancel = false;
					//chuyen 1 phan san pham tu ban cu sang ban moi
					unset($item['id']);
					$item['bar_reservation_id'] = $bar_reservation_id;
					$item['quantity'] = $_REQUEST['quantity'][$key];
					$total += $item['price'] * ($item['quantity'] - $item['quantity_discount']) - (($item['price'] * ($item['quantity'] - $item['quantity_discount']))*$item['discount_rate']);
					DB::insert('BAR_RESERVATION_PRODUCT',$item);	
					//cap nhap lai so luong san pham cua ban chuyen
					DB::update_id('BAR_RESERVATION_PRODUCT',array('quantity'=>($_REQUEST['quantity_before'][$key]-$_REQUEST['quantity'][$key])),$key);					
				}	
			}	
		}
		if(($cancel==true) and (count($_REQUEST['order'])==count($_REQUEST['quantity_before'])))
		{
			DB::delete_id('BAR_RESERVATION',Url::iget('from_code'));
			DB::delete('BAR_RESERVATION_TABLE','BAR_RESERVATION_ID='.Url::iget('from_code'));
		}else{// cap nhat lai total cho bar_reservation
			$br_old = DB::fetch('select * from bar_reservation where id='.Url::iget('from_code').'');
			if($br_old['bar_fee_rate'] != '' and $br_old['bar_fee_rate'] != 0){
				$total +=$total*($br_old['bar_fee_rate']/100);		
			}$total_before_tax = $total;
			if($br_old['tax_rate'] != '' and $br_old['tax_rate'] != 0){
				$total +=$total*($br_old['tax_rate']/100);		
			}	
			$total_old = $br_old['total'];
			$total_before_tax_old = $br_old['total_before_tax'];
			unset($br_old['total']);
			unset($br_old['total_before_tax']);
			$br_old['total'] = $total_old - $total;
			$br_old['total_before_tax'] = $total_before_tax_old - $total_before_tax;
			DB::update_id('BAR_RESERVATION',array('total'=>$total,'total_before_tax'=>$total_before_tax),$bar_reservation_id);
			DB::update_id('BAR_RESERVATION',array('total'=>$br_old['total'],'total_before_tax'=>$br_old['total_before_tax']),Url::iget('from_code'));
		}
	}
	function on_submit()
	{
		if(Url::get('confirm')==1 and Url::get('cmd')!='change_table')
		{
			if(!isset($_REQUEST['from_code']))
			{
				$this->error('from_code','select_from_table');
			}
			if(isset($_REQUEST['from_code']) and $_REQUEST['from_code'] and Url::get('save'))
			{
				$this->split_table();			
				//Url::redirect('table_map');
			}
		}	
	}
	function draw()
	{	
		$from_table  = array();
		$cond = '1=1';
		//$bars = $this->get_total_bars(false);
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
		//System::Debug($from_table);
		$this->parse_layout('split',array(
			'from_code_list'=>array('')+String::get_list($from_table),
			'orders'=>$order_menu,
			'tables_option'=>$tables_option,
			'tables'=>$tables,
			'orders_js'=>String::array2js($order_menu)
		));
	}
	function get_total_bars($bar_id = false){
		//-------- Phan quyen Bar-------------//
		require_once 'packages/hotel/packages/restaurant/includes/table.php';
		$cond_admin = Table::get_privilege_bar();
		$bars = DB::fetch_all('SELECT * FROM BAR where 1=1 '.$cond_admin.' and portal_id = \''.PORTAL_ID.'\' ORDER BY ID ASC');
		return $bars;
	}
}
?>
