<?php
class SplitTableForm extends Form
{
	function SplitTableForm()
	{
		Form::Form('SplitTableForm');	
	}		
	function check_in_bar()
	{
		if(!($bar_reservation_id = SplitTableDB::check_bar_reservation(Url::iget('to_table'))))
		{
			$item = DB::fetch('select * from bar_reservation where id ='.Url::iget('from_table'));
			$item['code'] = $item['code']+1; 
			unset($item['id']);
			$bar_reservation_id = DB::insert('bar_reservation',$item);			
			$bar_rt = DB::fetch('select * from bar_reservation_table where bar_reservation_id='.Url::iget('from_table'));
			unset($bar_rt['id']);
			$bar_rt['bar_reservation_id'] = $bar_reservation_id;
			$bar_rt['table_id'] = Url::iget('to_table');
			DB::insert('bar_reservation_table',$bar_rt);
		}	
		return $bar_reservation_id;
	}
	function split_table()
	{
		$bar_reservation_id = $this->check_in_bar();
		$cancel = true;
		foreach($_REQUEST['order'] as $key)
		{
			if(isset($_REQUEST['quantity'][$key]) and isset($_REQUEST['quantity_before'][$key]))
			{
				if($_REQUEST['quantity'][$key]>=$_REQUEST['quantity_before'][$key])
				{
					//chuyen toan bo san pham tu ban cu sang ban chuyen
					DB::update_id('bar_reservation_product',array('bar_reservation_id'=>$bar_reservation_id),$key);					
				}
				elseif($item = DB::exists_id('bar_reservation_product',$key))
				{
					$cancel = false;
					//chuyen 1 phan san pham tu ban cu sang ban moi
					unset($item['id']);
					$item['bar_reservation_id'] = $bar_reservation_id;
					$item['quantity'] = $_REQUEST['quantity'][$key];
					DB::insert('bar_reservation_product',$item);	
					//cap nhap lai so luong san pham cua ban chuyen
					DB::update_id('bar_reservation_product',array('quantity'=>($_REQUEST['quantity_before'][$key]-$_REQUEST['quantity'][$key])),$key);					
				}
			}	
		}
		if(($cancel==true) and (count($_REQUEST['order'])==count($_REQUEST['quantity_before'])))
		{
			DB::delete_id('bar_reservation',Url::iget('from_table'));
			DB::delete('bar_reservation_table','bar_reservation_id='.Url::iget('from_table'));
		}
		System::log('split_table','Split table from bar reservation id: \''.Url::iget('from_table').'\' to bar reservation id: \''.$bar_reservation_id.'\'','',Url::iget('from_table'));
	}
	function on_submit()
	{
		if(Url::get('confirm')==1 and Url::get('cmd')!='change_table')
		{
			if(!isset($_REQUEST['from_table']))
			{
				$this->error('from_table','select_from_table');
			}
			if(isset($_REQUEST['order']) && is_array($_REQUEST['order']) && Url::get('from_table') && Url::get('to_table'))
			{
				$from_table = Url::iget('from_table');
				$to_table = Url::iget('to_table');
				$this->split_table();
				if(!$this->is_error())
				{
					echo '<script>alert("'.Portal::language('table_split_success').'");location="'.Url::build_current().'"</script>';
				}	
			}
		}	
	}
	function draw()
	{	
		$from_table  = array();
		$cond = '1=1 and portal_id=\''.PORTAL_ID.'\'';
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
		$to_tables = SplitTableDB::get_table($cond);	
		if(!(Url::get('from_table') && $order_menu = SplitTableDB::get_order_menu(Url::iget('from_table'))))
		{
			$order_menu = array();			
		}
		$this->parse_layout('report',array(
			'from_table_list'=>array('')+String::get_list($from_table),
			'to_table_list'=>String::get_list($to_tables),
			'orders'=>$order_menu
		));
	}
}
?>
