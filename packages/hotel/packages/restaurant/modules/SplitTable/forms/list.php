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
				    /** M?nh thêm log ph?n ghép bàn **/
                    $from_table = DB::fetch("SELECT bar_table.id,bar_table.code,bar_table.name FROM bar_table
                                                                        inner join bar_reservation_table on bar_reservation_table.table_id=bar_table.id
                                                                        inner join bar_reservation on bar_reservation.id=bar_reservation_table.bar_reservation_id
                                                                         WHERE bar_reservation.id=".$_REQUEST['from_code']);
                    //System::debug($from_table);
                    
                    $to_table = DB::fetch("SELECT bar_table.id,bar_table.code,bar_table.name FROM bar_table 
                                                                        inner join bar_reservation_table on bar_reservation_table.table_id=bar_table.id
                                                                        inner join bar_reservation on bar_reservation.id=bar_reservation_table.bar_reservation_id
                                                                        WHERE bar_reservation.id=".$_REQUEST['to_code']);
                    //System::debug($to_table);
                    $title = "Coupling Table - From Table NAME: ".$from_table['name']." - To Table NAME: ".$to_table['name'];
					$decription = "User ID: ".User::id()."<br />"."Date Time: ".date('d/m/Y H:i:s')."<br />";
                    $decription .= "Coupling Table - From Table ID: ".$_REQUEST['from_code']." Table NAME: ".$from_table['name']." - To Table ID: ".$_REQUEST['to_code']." Table NAME: ".$to_table['name'];
                    System::log('Coupling',$title,$decription,$_REQUEST['from_code']);
                    /** end m?nh **/
                    if(DB::select_id('bar_reservation',intval(Url::get('from_code'))))
					{
					   
                        $current_date = Date_Time::to_time(date("d/m/Y H:i:s"));
					    $br_from_code = DB::fetch('select * from bar_reservation where id='.Url::get('from_code').'');
						$br_to_code = DB::fetch('select * from bar_reservation where id='.Url::get('to_code').'');
                        
						$total_new = $br_from_code['total'] + $br_to_code['total'];
                        $total_deposit = $br_from_code['deposit'] + $br_to_code['deposit'];
						$total_before_tax_new = $br_from_code['total_before_tax'] + $br_to_code['total_before_tax'];
						DB::update_id('BAR_RESERVATION',array('last_time'=>time(),'lastest_edited_user_id'=>User::id(),'total'=>$total_new,'total_before_tax'=>$total_before_tax_new,'discount_percent'=>0,'deposit'=>$total_deposit),Url::get('to_code'));
						DB::delete_id('bar_reservation',intval(Url::get('from_code')));
						//DB::query('update bar_reservation_product set bar_reservation_id ='.Url::get('to_code').' where bar_reservation_id='.Url::get('from_code'));
						//DB::query('update bar_reservation_table set bar_reservation_id ='.Url::get('to_code').' where bar_reservation_id='.Url::get('from_code'));
						$product_1 =DB::fetch_all('select bar_reservation_product.product_id || \'_\' || bar_reservation_product.name || \'_\' || bar_reservation_product.bar_set_menu_id as id,bar_reservation_product.quantity_discount,bar_reservation_product.quantity,bar_reservation_product.printed,bar_reservation_product.id as brp_id,bar_reservation_product.product_name, bar_reservation_product.complete from bar_reservation_product
						where bar_reservation_id ='.Url::get('from_code').'');
						$product_2 =  DB::fetch_all('select bar_reservation_product.* from bar_reservation_product
						where bar_reservation_id ='.Url::get('to_code').'');
                        //System::DEBUG($product_1);	
                        //System::DEBUG($product_2);	
                        
						foreach($product_2 as $p =>$product){
						    $id_temp = $product['product_id'].'_'.$product['name'].'_'.$product['bar_set_menu_id'];
							if(isset($product_1[$id_temp])){								
                                DB::query('delete from bar_reservation_product where id = '.$product_1[$id_temp]['brp_id'].'');
								DB::query('update bar_reservation_product set quantity ='.($product_1[$id_temp]['quantity']+$product['quantity']).', printed='.($product_1[$id_temp]['printed']+$product['printed']).', quantity_discount='.($product_1[$id_temp]['quantity_discount']+$product['quantity_discount']).', complete='.($product_1[$id_temp]['complete']+$product['complete']).', last_edit_time='.$current_date.' where id='.$p.'');
							}
						}
                        
						foreach($product_1 as $p1 =>$prd){
						    $arr = explode('_',$p1);  
							if(!isset($product_2[$arr[0]])){
							    //System::DEBUG($prd['brp_id']); 
								//DB::query('delete from bar_reservation_product where id = '.$product_1[$product['product_id']]['brp_id'].'');
								DB::query('update bar_reservation_product set bar_reservation_id ='.Url::get('to_code').' where id='.$prd['brp_id'].'');
							}
						}
                        //exit();
                        $list_product = DB::fetch_all('select bar_reservation_product.id from bar_reservation_product where bar_reservation_product.bar_reservation_id='.Url::get('to_code'));
                        foreach($list_product as $id=>$content)
                        {
                            DB::update('bar_reservation_product',array('discount_rate'=>0),'id='.$content['id']);
                        }
                        
                        $set_product_1 =DB::fetch_all('select bar_reservation_set_product.product_id || \'_\' || bar_reservation_set_product.name || \'_\' || bar_reservation_set_product.bar_set_menu_id as id,bar_reservation_set_product.quantity_discount,bar_reservation_set_product.quantity,bar_reservation_set_product.printed,bar_reservation_set_product.id as brp_id,bar_reservation_set_product.product_name from bar_reservation_set_product
						where bar_reservation_id ='.Url::get('from_code').'');
						$set_product_2 =  DB::fetch_all('select bar_reservation_set_product.* from bar_reservation_set_product
						where bar_reservation_id ='.Url::get('to_code').'');	
						foreach($set_product_2 as $p =>$product){
						      $id_temp = $product['product_id'].'_'.$product['name'].'_'.$product['bar_set_menu_id'];
							if(isset($set_product_1[$id_temp])){
								DB::query('delete from bar_reservation_set_product where id = '.$set_product_1[$id_temp]['brp_id'].'');
								DB::query('update bar_reservation_set_product set quantity ='.($set_product_1[$id_temp]['quantity']+$product['quantity']).', printed='.($set_product_1[$id_temp]['printed']+$product['printed']).', quantity_discount='.($set_product_1[$id_temp]['quantity_discount']+$product['quantity_discount']).' where id='.$p.'');
							}
						}
						foreach($set_product_1 as $p1 =>$prd){
						    $arr = explode('_',$p1);  
							if(!isset($set_product_2[$arr[0]])){
								//DB::query('delete from bar_reservation_product where id = '.$product_1[$product['product_id']]['brp_id'].'');
								DB::query('update bar_reservation_set_product set bar_reservation_id ='.Url::get('to_code').' where id='.$prd['brp_id'].'');
							}
						}
                        $list_set_product = DB::fetch_all('select bar_reservation_set_product.id from bar_reservation_set_product where bar_reservation_set_product.bar_reservation_id='.Url::get('to_code'));
                        foreach($list_set_product as $id=>$content)
                        {
                            DB::update('bar_reservation_set_product',array('discount_rate'=>0),'id='.$content['id']);
                        }
                        
						DB::query('update bar_reservation_table set bar_reservation_id ='.Url::get('to_code').' where bar_reservation_id='.Url::get('from_code'));
                        DB::query('update payment set bill_id ='.Url::get('to_code').' where type = \'BAR\' and bill_id='.Url::get('from_code'));
					}
					if(!$this->is_error())
					{
                       
                        //luu nguyen giap chuyen ve trang table map khi tach ghep ban xong 
                        echo '<script>alert("'.Portal::language('table_split_success').'");</script>';
                        //manh cho chuyen ve ban duoc ghep den, sau dó m?i chuy?n v? table_map
                        $table_navigation = DB::fetch('SELECT bar_reservation.id,bar_reservation_table.table_id,bar_table.bar_id,bar_table.bar_area_id FROM bar_reservation inner join bar_reservation_table on bar_reservation_table.bar_reservation_id=bar_reservation.id inner join bar_table on bar_reservation_table.table_id=bar_table.id WHERE bar_reservation.id='.Url::get('to_code'));
                        echo "<script>";
                        if(USE_DISPLAY && USE_DISPLAY==1){
                            echo 'window.location="?page=touch_bar_restaurant&cmd=edit&id='.$table_navigation['id'].'&table_id='.$table_navigation['table_id'].'&bar_area_id='.$table_navigation['bar_area_id'].'&bar_id='.$table_navigation['bar_id'].'&on_load=1&target=split";';
                        }
                        else{
                            echo 'window.location="?page=touch_bar_restaurant&cmd=edit&id='.$table_navigation['id'].'&table_id='.$table_navigation['table_id'].'&bar_area_id='.$table_navigation['bar_area_id'].'&bar_id='.$table_navigation['bar_id'].'&on_load=1";';
                        }    
                        
                        echo "</script>";
                        // enh manh. sorry anh Giap
                        //Url::redirect('table_map');
                        //end luu nguyen giap window.close();
					}	
				}
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
		$this->parse_layout('list',array(
			'from_code_list'=>array('')+String::get_list($from_table),
			'to_code_list'=>array('')+String::get_list($from_table),
            'to_code_js'=>String::array2js($from_table),
			'bars_list'=>$list_bars			
		));
	}
}
?>
