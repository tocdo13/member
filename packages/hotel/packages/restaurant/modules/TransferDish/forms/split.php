<?php
class SplitTableForm extends Form
{
	function SplitTableForm()
	{
		Form::Form('SplitTableForm');	
        $this->link_css('packages/core/includes/js/jquery/keyboard/style.css');
		//$this->link_js('packages/core/includes/js/multi_items.js');
		$this->link_js('packages/core/includes/js/jquery/keyboard/keyboard.js');
        $this->link_css('packages/hotel/skins/default/css/restaurant.css');
	}		
	function check_in_bar()
	{
		require_once 'packages/hotel/packages/restaurant/includes/table.php';
		$check = false;
		$bar_reservation_id = Url::iget('from_code');
        echo $bar_reservation_id;
		$bar_table = SplitTableDB::get_bar_table(Url::iget('from_code'));
		$split_table = Url::get('split_table');	
        //System::debug($split_table);
		if(Url::get('new_table_id') and Url::get('split_table')){
		  
			if(!isset($_REQUEST['order']) or empty($_REQUEST['order'])){
				return false;	
			}
			$new_table_ids = explode(",",Url::get('new_table_id'));
                        $new_table_ids = array_unique($new_table_ids); 
                        echo $new_table_ids;
                        /*
			for($j=0;$j<count($new_table_ids);$j++){			
				$table_new = DB::fetch('select * from bar_reservation_table INNER JOIN bar_reservation on bar_reservation.id = bar_reservation_table.bar_reservation_id where table_id ='.$new_table_ids[$j].' AND bar_reservation.status=\'CHECKIN\'');
				if($table_new){
						$check = true;						
						return false;
						//break;	
				}
			}
            */
			if(!$check)
			{
				$item = DB::fetch('select * from BAR_RESERVATION where id ='.Url::iget('from_code'));
				unset($item['id']);
				unset($item['code']);
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
				    echo 1;
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
		//$bar_reservation_id = $this->check_in_bar();
        require_once 'packages/hotel/packages/restaurant/includes/table.php';
        $new_table_id = Url::get('new_table_id');
        $array_bar_reservation_id =DB::fetch("
                                            select 
                                            BAR_RESERVATION_ID, 
                                            ARRIVAL_TIME
                                            from bar_reservation_table
                                            inner join bar_reservation on BAR_RESERVATION_ID = bar_reservation.id
                                            where table_id = ".$new_table_id." and bar_reservation.status = 'CHECKIN' 
                                            order by ARRIVAL_TIME desc");
        foreach($array_bar_reservation_id as $value)
        {
		  $bar_reservation_id = $value;
          break;
		}
       
		if(!$bar_reservation_id)
        {
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
        /** mạnh log tách bàn **/
        $table_old = DB::fetch("SELECT bar_table.id,bar_table.code,bar_table.name FROM bar_reservation_table inner join bar_table on bar_table.id=bar_reservation_table.table_id WHERE bar_reservation_table.bar_reservation_id=".$_REQUEST['from_code']);
        $title = 'Transfer Dish Table - From Table: '.$table_old['name'];
        $description = 'Transfer Dish Table - From Table: '.$table_old['name'];
        if($_REQUEST['split_table']){
            foreach($_REQUEST['split_table'] as $id){
                $table_new = DB::fetch("SELECT id,code,name FROM bar_table WHERE id=".$_REQUEST['to_table_'.$id]);
                $title .= ' To Table: '.$table_new['name'];
                $description .= ' To Table: '.$table_new['name'];
            }
        }
        $description .= "User id: ".User::id()."<br />"."Date Time: ".date('d/m/Y H:i:s')."<br />";
        $description .= "List Transfer Dish Product: "."<br />";
        $description .= "<hr /><br />";
		$cancel = true;
		$total = 0;
        $current_date = Date_Time::to_time(date("d/m/Y H:i:s"));        
        if(isset($_REQUEST['order']))
        {
                //echo 111;exit();
		  foreach($_REQUEST['order'] as $key)
		  {
		    $product = DB::fetch("SELECT product.id,product.name_1 as name FROM product inner join bar_reservation_product on bar_reservation_product.product_id=product.id WHERE bar_reservation_product.id=".$key);
            $description .= "Product ID: ".$product['id']." - Product NAME: ".$product['name']." - Product Quantity:".$_REQUEST['quantity'][$key]."<br />";   
			if(isset($_REQUEST['quantity'][$key]) and isset($_REQUEST['quantity_before'][$key]))
			{
				if($_REQUEST['quantity'][$key] >= $_REQUEST['quantity_before'][$key])
				{
                    
                    
                    //chuyen toan bo san pham tu ban cu sang ban chuyen
					$sql = "SELECT bar_reservation_product.product_id FROM bar_reservation_product WHERE bar_reservation_product.id = $key";                   
                    $result = DB::fetch($sql);                    
                    if($result['product_id']=='FOUTSIDE' || $result['product_id']=='DOUTSIDE' || $result['product_id']=='SOUTSIDE')
                    {
                        DB::update_id('bar_reservation_product',array('bar_reservation_id'=>$bar_reservation_id),$key);
                    }
                    else
                    {
                        $result = $result['product_id'];
                        $sql = "SELECT * FROM bar_reservation_product WHERE bar_reservation_product.bar_reservation_id=$bar_reservation_id AND product_id='$result'";
                        $result = DB::fetch($sql);
                        if(!empty($result)){
                            $sql = "SELECT * FROM bar_reservation_product WHERE bar_reservation_product.id=$key";
                            $current_product = DB::fetch($sql);
                            $new_quantity = $result['quantity']+$_REQUEST['quantity_before'][$key];
                            $result['complete']=($result['complete']!='')?$result['complete']:0;
                            $new_complete = $result['complete']+$_REQUEST['complete'][$key];
                            $new_printed = $result['printed'] + $_REQUEST['printed_before'][$key];
                            $new_quantity_discount = $result['quantity_discount'] + $current_product['quantity_discount'];
                            $new_printed_cancel = $result['printed_cancel'] + $current_product['printed_cancel'];
                            
                            DB::update_id('bar_reservation_product',array('quantity'=>$new_quantity,'complete'=>$new_complete,'printed'=>$new_printed,'quantity_discount'=>$new_quantity_discount,'printed_cancel'=>$new_printed_cancel),$result['id']);
                            DB::delete_id('bar_reservation_product',$key);
                        }
                        else{
                            DB::update_id('bar_reservation_product',array('bar_reservation_id'=>$bar_reservation_id),$key);
                        }
                    }
                    
				}
				elseif($item = DB::exists_id('BAR_RESERVATION_PRODUCT',$key))
				{
					//echo System::debug($_REQUEST);
                    //System::debug($item);exit();
                    $cancel = false;
					//chuyen 1 phan san pham tu ban cu sang ban moi
					unset($item['id']);
					$item['bar_reservation_id'] = $bar_reservation_id;
                    // Thanh add phần tách số lượng món và số lượng món hoàn thành
					$item['complete']=($item['complete']!='')?$item['complete']:0;
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
					$total += $item['price'] * ($item['quantity'] - $item['quantity_discount']) - (($item['price'] * ($item['quantity'] - $item['quantity_discount']))*$item['discount_rate']);
					
                    $sql = "SELECT bar_reservation_product.product_id,bar_reservation_product.price FROM bar_reservation_product WHERE bar_reservation_product.id = $key";                   
                    $result = DB::fetch($sql);
                    if($result['product_id']=='FOUTSIDE' || $result['product_id']=='DOUTSIDE' || $result['product_id']=='SOUTSIDE')
                    {
                        //DB::update_id('bar_reservation_product',array('bar_reservation_id'=>$bar_reservation_id),$key);
                        $sql = "SELECT bar_reservation_product.id, bar_reservation_product.product_id, bar_reservation_product.quantity, bar_reservation_product.complete FROM bar_reservation_product WHERE bar_reservation_product.bar_reservation_id=$bar_reservation_id AND price=".$result['price']." AND product_id='".$result['product_id']."'";
                        $result = DB::fetch($sql); // to
                        if(!empty($result)){
                            
                            $sql = "SELECT * FROM bar_reservation_product WHERE bar_reservation_product.id=$key";
                            $current_product = DB::fetch($sql); // from
                            $current_product['complete']=($current_product['complete']!='')?$current_product['complete']:0;
                            $item['complete']=($result['complete']!='')?$result['complete']:0;
                            if($_REQUEST['quantity'][$key]>=($current_product['quantity']-$current_product['complete'])){
                                $new_complete = $result['complete'] + ($_REQUEST['quantity'][$key] - ($current_product['quantity']-$current_product['complete']));
                                $current_complete = $current_product['complete'] - ($_REQUEST['quantity'][$key] - ($current_product['quantity']-$current_product['complete']));
                            }
                            else{
                                $new_complete = $result['complete'];
                                $current_complete = $current_product['complete'];
                            }
                            $new_quantity = $result['quantity']+$_REQUEST['quantity'][$key];
                            
                            DB::update_id('bar_reservation_product',array('quantity'=>$new_quantity,'complete'=>$new_complete),$result['id']);
                        }
                        else{
                            DB::insert('BAR_RESERVATION_PRODUCT',$item);
                        }
                        //cap nhap lai so luong san pham cua ban chuyen
					   DB::update_id('BAR_RESERVATION_PRODUCT',array('quantity'=>($_REQUEST['quantity_before'][$key]-$_REQUEST['quantity'][$key]),'printed'=>($_REQUEST['quantity_before'][$key]-$_REQUEST['quantity'][$key]),'complete'=>$current_complete),$key);
                    }
                    else
                    {
                        $result = $result['product_id'];
                        $sql = "SELECT bar_reservation_product.id, bar_reservation_product.product_id, bar_reservation_product.quantity, bar_reservation_product.complete FROM bar_reservation_product WHERE bar_reservation_product.bar_reservation_id=$bar_reservation_id AND product_id='$result'";
                        $result = DB::fetch($sql); // to
                        if(!empty($result)){
                            
                            $sql = "SELECT * FROM bar_reservation_product WHERE bar_reservation_product.id=$key";
                            $current_product = DB::fetch($sql); // from
                            $current_product['complete']=($current_product['complete']!='')?$current_product['complete']:0;
                            $item['complete']=($result['complete']!='')?$result['complete']:0;                                                        
                            if($_REQUEST['quantity'][$key]>=($current_product['quantity']-$current_product['complete'])){
                                $new_complete = $result['complete'] + ($_REQUEST['quantity'][$key] - ($current_product['quantity']-$current_product['complete']));
                                $current_complete = $current_product['complete'] - ($_REQUEST['quantity'][$key] - ($current_product['quantity']-$current_product['complete']));
                            }
                            else{
                                $new_complete = $result['complete'];
                                $current_complete = $current_product['complete'];
                            }
                            $new_quantity = $result['quantity']+$_REQUEST['quantity'][$key];
                            
                            DB::update_id('bar_reservation_product',array('quantity'=>$new_quantity,'complete'=>$new_complete),$result['id']);
                        }
                        else{
                            DB::insert('BAR_RESERVATION_PRODUCT',$item);
                        }
                        //cap nhap lai so luong san pham cua ban chuyen
					   DB::update_id('BAR_RESERVATION_PRODUCT',array('quantity'=>($_REQUEST['quantity_before'][$key]-$_REQUEST['quantity'][$key]),'printed'=>($_REQUEST['quantity_before'][$key]-$_REQUEST['quantity'][$key]),'complete'=>$current_complete),$key);
                    }   					
				}	
			}
            }	
		}
        System::log('Transfer Dish',$title,$description,$_REQUEST['from_code']);
        Table::updateTotalBar($bar_reservation_id);
		Table::updateTotalBar(Url::iget('from_code'));
        /*
		if(($cancel==true) and (count($_REQUEST['order'])==count($_REQUEST['quantity_before'])))
		{
			DB::delete_id('BAR_RESERVATION',Url::iget('from_code'));
			DB::delete('BAR_RESERVATION_TABLE','BAR_RESERVATION_ID='.Url::iget('from_code'));
		}else{// cap nhat lai total cho bar_reservation
			Table::updateTotalBar($bar_reservation_id);
			Table::updateTotalBar(Url::iget('from_code'));
		}
        */
        if(!$this->is_error())
		{
			echo '<script>alert("'.Portal::language('table_split_success').'");</script>';
            echo '<script>window.parent.location.reload();
				</script>';
            
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
        //System::debug($from_table); 
		if(!(Url::get('from_code') && $order_menu = SplitTableDB::get_order_menu(Url::iget('from_code'))))
		{
			$order_menu = array();			
		}
		$tables = SplitTableDB::get_bar_table(Url::iget('from_code'));
		$to_tables = DB::fetch_all('
			         SELECT 
						bar_reservation_table.table_id as id,
                        BAR_TABLE.name
					FROM 	
						bar_reservation_table
						INNER JOIN bar_reservation ON  bar_reservation_table.bar_reservation_id = bar_reservation.id
                        inner join BAR_TABLE on bar_reservation_table.table_id=BAR_TABLE.id
					WHERE 
                        '.$cond.'
						AND bar_reservation.status = \'CHECKIN\'
                        AND bar_reservation.id !='.Url::iget('from_code').'
                        AND BAR_TABLE.BAR_ID = '.Session::get('bar_id').' 
                        AND BAR_TABLE.portal_id = \''.PORTAL_ID.'\'
                        --AND BAR_TABLE.ID != '.Url::iget('from_code').'
		              ');
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
