<?php
class SplitTableForm extends Form
{
	function SplitTableForm()
	{
		Form::Form('SplitTableForm');	
	}		
	function check_in_bar()
	{
		require_once 'packages/hotel/packages/restaurant/includes/table.php';
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
                $item['deposit'] = 0;
				$bar_reservation_id = DB::insert('BAR_RESERVATION',$item);
				$code = Table::get_code_bar_reservation($bar_reservation_id);
				DB::update_id('BAR_RESERVATION',array('last_time'=>time(),'lastest_edited_user_id'=>User::id(),'code'=>$code),$bar_reservation_id);
				// Xu ly tach ban - tach hoa don
				foreach($split_table as $value){
					$split = $value;
				}
				$bar_rt = DB::fetch('select * from BAR_RESERVATION_TABLE where id='.$split);
				unset($bar_rt['id']);
				for($i=0;$i<count($new_table_ids);$i++){
					$bar_rt['table_id'] = $new_table_ids[$i];
					$bar_rt['bar_reservation_id'] = $bar_reservation_id;
                    //$bar_rt['deposit'] = 0;
					//DB::delete_id('BAR_RESERVATION_TABLE',$value);
					DB::insert('BAR_RESERVATION_TABLE',$bar_rt);
				}
				
			}
		}else { echo '<script>alert("'.Portal::language('no_table_selected').'");location="'.Url::build_current(array('type'=>'split')).'"</script>'; return false;}
		return $bar_reservation_id;
	}
	function split_table()
	{
       
       $quantity_discount = $_REQUEST['quantity_discount'];
       $quantity_discount_before = $_REQUEST['quantity_discount_before'];
       unset($_REQUEST['quantity_discount']);
       unset($_REQUEST['quantity_discount_before']);
		$bar_reservation_id = $this->check_in_bar();
        if(!$bar_reservation_id){
			echo '<script>alert("'.Portal::language('can_not_change').'");location="'.Url::build_current(array('type'=>'split')).'"</script>';	
			return false;
		}
        /** mạnh thêm stt mới vào sản phâm rkhi thực hiện tách bàn **/
        $list_product_old = DB::fetch_all("SELECT id,stt FROM bar_reservation_product WHERE bar_reservation_product.bar_reservation_id = ".$_REQUEST['from_code']."");
        $stt_new_1 = 0;
        $stt_new_2 = 0;
        foreach($list_product_old as $key1=>$value1){
            if(isset($_REQUEST['order'][$value1['id']])){
                $stt_new_1++;
                $bar_reservation_product_id = $value1['id'];
                DB::update('bar_reservation_product',array('stt'=>$stt_new_1),'id = '.$bar_reservation_product_id.'');
            }else{
                $stt_new_2++;
                $bar_reservation_product_id = $value1['id'];
                DB::update('bar_reservation_product',array('stt'=>$stt_new_2),'id = '.$bar_reservation_product_id.'');
            }
        }
        /** end stt **/
        /** ************************************************************* **/
		/** mạnh log tách bàn **/
        $table_old = DB::fetch("SELECT bar_table.id,bar_table.code,bar_table.name FROM bar_reservation_table inner join bar_table on bar_table.id=bar_reservation_table.table_id WHERE bar_reservation_table.bar_reservation_id=".$_REQUEST['from_code']);
        $title = 'Split Table - From Table: '.$table_old['name'];
        $description = 'Split Table - From Table: '.$table_old['name'];
        if($_REQUEST['split_table']){
            foreach($_REQUEST['split_table'] as $id){
                $table_new = DB::fetch("SELECT id,code,name FROM bar_table WHERE id=".$_REQUEST['to_table_'.$id]);
                $title .= ' To Table: '.$table_new['name'];
                $description .= ' To Table: '.$table_new['name'];
            }
        }
        $description .= "User id: ".User::id()."<br />"."Date Time: ".date('d/m/Y H:i:s')."<br />";
        $description .= "List Split Product: "."<br />";
        $description .= "<hr /><br />";
        $cancel = true;
		$total = 0;
        $current_date = Date_Time::to_time(date("d/m/Y H:i:s"));
		foreach($_REQUEST['order'] as $key)
		{
		    $product = DB::fetch("SELECT product.id,product.name_1 as name FROM product inner join bar_reservation_product on bar_reservation_product.product_id=product.id WHERE bar_reservation_product.id=".$key);
            $description .= "Product ID: ".$product['id']." - Product NAME: ".$product['name']." - Product Quantity:".$_REQUEST['quantity'][$key]."<br />"; 
			if(isset($_REQUEST['quantity'][$key]) and isset($_REQUEST['quantity_before'][$key]))
			{
				if($_REQUEST['quantity'][$key] >= $_REQUEST['quantity_before'][$key])
				{
					//chuyen toan bo san pham tu ban cu sang ban chuyen
					DB::update_id('BAR_RESERVATION_PRODUCT',array('BAR_RESERVATION_ID'=>$bar_reservation_id,'last_edit_time'=>$current_date),$key);					
				}
				elseif($item = DB::exists_id('BAR_RESERVATION_PRODUCT',$key))
				{
					$cancel = false;
					//chuyen 1 phan san pham tu ban cu sang ban moi
					unset($item['id']);
					$item['bar_reservation_id'] = $bar_reservation_id;
                    // Thanh add phần tách số lượng món và số lượng món hoàn thành
					
                    if($item['complete']>($item['quantity']-$_REQUEST['quantity'][$key])){
                        
                        $current_complete = ($item['quantity']-$_REQUEST['quantity'][$key]);
                        $item['complete'] -= $current_complete;
                    }
                    else{
                        $current_complete = $item['complete'];  
                        $item['complete'] = 0;
                    }
                    $item['quantity'] = $_REQUEST['quantity'][$key];
                    // end
                    $item['last_edit_time'] = $current_date;
                    $item['printed'] = $_REQUEST['quantity'][$key];
                    $item['quantity_discount'] = $quantity_discount[$key];
					$total += $item['price'] * ($item['quantity'] - $item['quantity_discount']) - (($item['price'] * ($item['quantity'] - $item['quantity_discount']))*$item['discount_rate']);
					DB::insert('BAR_RESERVATION_PRODUCT',$item);
					//cap nhap lai so luong san pham cua ban chuyen
					DB::update_id('BAR_RESERVATION_PRODUCT',array('quantity'=>($_REQUEST['quantity_before'][$key]-$_REQUEST['quantity'][$key]),'quantity_discount'=>($quantity_discount_before[$key]-$quantity_discount[$key]),'printed'=>($_REQUEST['quantity_before'][$key]-$_REQUEST['quantity'][$key]),'complete'=>$current_complete,'last_edit_time'=>$current_date),$key);					
				}	
			}	
		}
        System::log('Split',$title,$description,$_REQUEST['from_code']);
		//System::debug($cancel);
        //System::debug($_REQUEST);exit();
        if(($cancel==true) and (count( DB::fetch_all("SELECT id FROM bar_reservation_product WHERE bar_set_menu_id IS NULL and bar_reservation_id=".Url::iget('from_code')))==0))
		{
			DB::delete_id('BAR_RESERVATION',Url::iget('from_code'));
			DB::delete('BAR_RESERVATION_TABLE','BAR_RESERVATION_ID='.Url::iget('from_code'));
		}else{// cap nhat lai total cho bar_reservation
			Table::updateTotalBar($bar_reservation_id);
			Table::updateTotalBar(Url::iget('from_code'));
		}
        if(!$this->is_error())
		{
			echo '<script>alert("'.Portal::language('table_split_success').'");window.close();</script>';
		}
	}
	function on_submit()
	{
		if(Url::get('acction') == 1)
        {	
			if(Url::get('bars'))
            {
				Session::set('bar_id',Url::get('bars'));
			}
			$_REQUEST['bar_id'] = Session::get('bar_id');
			//Url::redirect_current(array('bar_id'=>Session::get('bar_id'),'status'));	
		}
        else if(Url::get('confirm')==1 and Url::get('cmd')!='change_table')
        {
			if(!isset($_REQUEST['from_code']))
            {
				$this->error('from_code','select_from_table');
			}
            if(isset($_REQUEST['from_code']) and $_REQUEST['from_code'] and Url::get('save'))
            {
				$this->split_table();			
				Url::redirect('table_map',array('type'=>'split','from_code'=>$_REQUEST['from_code'],'to_code'=>$_REQUEST['from_code']+1,'bar_reservation_id'=>$_REQUEST['from_code']));
			}
		}
	}
	function draw()
	{
        if(isset($_REQUEST['exit']))
        {
             Url::redirect('table_map');
        }	
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
		//System::Debug($from_table);
		$this->parse_layout('split',array(
			'from_code_list'=>array('')+String::get_list($from_table),
			'orders'=>$order_menu,
			'tables_option'=>$tables_option,
			'tables'=>$tables,
			'bars_list'=>$list_bars			
		));
	}
}
?>
