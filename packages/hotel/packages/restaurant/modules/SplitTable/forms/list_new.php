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
						DB::update_id('BAR_RESERVATION',array('total'=>$total_new,'total_before_tax'=>$total_before_tax_new,'discount_percent'=>0,'deposit'=>$total_deposit),Url::get('to_code'));
						DB::delete_id('bar_reservation',intval(Url::get('from_code')));
						//DB::query('update bar_reservation_product set bar_reservation_id ='.Url::get('to_code').' where bar_reservation_id='.Url::get('from_code'));
						//DB::query('update bar_reservation_table set bar_reservation_id ='.Url::get('to_code').' where bar_reservation_id='.Url::get('from_code'));
						$product_1 =DB::fetch_all('select concat(concat(bar_reservation_product.product_id,\'_\'),bar_reservation_product.name) as id,bar_reservation_product.quantity_discount,bar_reservation_product.quantity,bar_reservation_product.printed,bar_reservation_product.id as brp_id,bar_reservation_product.product_name, bar_reservation_product.complete from bar_reservation_product
						where bar_reservation_id ='.Url::get('from_code').'');
						$product_2 =  DB::fetch_all('select bar_reservation_product.* from bar_reservation_product
						where bar_reservation_id ='.Url::get('to_code').'');	
						foreach($product_2 as $p =>$product){
							if(isset($product_1[$product['product_id'].'_'.$product['name']]) && (($product['product_id']!='FOUTSIDE' && $product['product_id']!='DOUTSIDE') || ($product_1[$product['product_id'].'_'.$product['name']]['product_name']!=$product['product_name']))){
								DB::query('delete from bar_reservation_product where id = '.$product_1[$product['product_id'].'_'.$product['name']]['brp_id'].'');
								DB::query('update bar_reservation_product set quantity ='.($product_1[$product['product_id'].'_'.$product['name']]['quantity']+$product['quantity']).', printed='.($product_1[$product['product_id'].'_'.$product['name']]['printed']+$product['printed']).', quantity_discount='.($product_1[$product['product_id'].'_'.$product['name']]['quantity_discount']+$product['quantity_discount']).', complete='.($product_1[$product['product_id'].'_'.$product['name']]['complete']+$product['complete']).', last_edit_time='.$current_date.' where id='.$p.'');
							}
						}
						foreach($product_1 as $p1 =>$prd){
						    $arr = explode('_',$p1);  
							if(!isset($product_2[$arr[0]])){
								//DB::query('delete from bar_reservation_product where id = '.$product_1[$product['product_id']]['brp_id'].'');
								DB::query('update bar_reservation_product set bar_reservation_id ='.Url::get('to_code').' where id='.$prd['brp_id'].'');
							}
						}
                        $list_product = DB::fetch_all('select bar_reservation_product.id from bar_reservation_product where bar_reservation_product.bar_reservation_id='.Url::get('to_code'));
                        foreach($list_product as $id=>$content)
                        {
                            DB::update('bar_reservation_product',array('discount_rate'=>0),'id='.$content['id']);
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
        if(isset($_REQUEST['id'])){$id = $_REQUEST['id'];}else{ $id='';};
		$cond_admin = $this->get_privilege_bar($id);
        if(isset($_REQUEST['bar_area_id']))
        {
            $bars_area = DB::fetch('SELECT bar_area.id, bar_area.name FROM bar_area WHERE bar_area.id =\''.$_REQUEST['bar_area_id'].'\'');
            $_REQUEST['bars_area'] = $bars_area['id'];
        }
        $bars_area = DB::fetch_all('SELECT bar_area.id, bar_area.name FROM bar_area where 1=1 '.$cond_admin.' ORDER BY ID ASC');
        $list_bars_area[0] = '----Select----';
        $list_bars_area = $list_bars_area + String::get_list($bars_area);
        $from_table = $this->get_table_use();
		$this->parse_layout('list',array(
			'from_code_list'=>array('')+String::get_list($from_table),
			'to_code_list'=>array('')+String::get_list($from_table),
			'bars_area_list'=>$list_bars_area			
		));
	}
    
    function get_table_use($in_date = false)
	{
		if(!$in_date)
		{
			$in_date = date('d/m/Y');
		}
		$cond = (Session::get('bar_id'))?' AND BAR_TABLE.bar_id = '.Session::get('bar_id').'':'';
        $cond_new = (isset($_REQUEST['bars_area']))?' AND bar_area.id=\''.$_REQUEST['bars_area'].'\'':' AND bar_area.id=\'\'';
        $bar_reservation = DB::fetch_all('
			SELECT
				BAR_RESERVATION.ID,
				CONCAT(BAR_RESERVATION.ID,\' \') as name
			FROM
				bar_reservation
                INNER JOIN bar_reservation_table on bar_reservation_table.bar_reservation_id = bar_reservation.id
                INNER JOIN bar_table on bar_reservation_table.table_id = bar_table.id
                INNER JOIN bar_area on bar_area.id = bar_table.bar_area_id
				INNER JOIN bar on bar_reservation.bar_id = bar.id
				LEFT OUTER JOIN party on party.user_id = bar_reservation.checked_out_user_id AND party.type=\'USER\'
			WHERE
                bar_reservation.status = \'CHECKIN\' 
				AND bar_reservation.arrival_time >='.Date_Time::to_time($in_date).' 
				AND bar_reservation.arrival_time <'.(Date_Time::to_time($in_date)+24*3600).'
                '.$cond_new.'
            ORDER BY
				BAR_TABLE.NAME
		');	
		$table_use = DB::fetch_all('
			SELECT
				BAR_RESERVATION_TABLE.ID
				,BAR_RESERVATION_TABLE.TABLE_ID
				,BAR_RESERVATION.ID as bar_reservation_id
				,BAR_TABLE.name as bar_name
			FROM
				BAR_RESERVATION
				INNER JOIN BAR_RESERVATION_TABLE ON BAR_RESERVATION_TABLE.BAR_RESERVATION_ID = 	BAR_RESERVATION.ID
				INNER JOIN BAR_TABLE ON BAR_TABLE.ID = BAR_RESERVATION_TABLE.TABLE_ID
			WHERE
				BAR_RESERVATION.STATUS = \'CHECKIN\' 
				AND bar_reservation.arrival_time >='.Date_Time::to_time($in_date).' 
				AND bar_reservation.arrival_time <'.(Date_Time::to_time($in_date)+24*3600).'
				'.$cond.'
			ORDER BY
				BAR_TABLE.NAME');
				
		foreach($table_use as $key => $value){
			if(isset($bar_reservation[$value['bar_reservation_id']]) and ($value['bar_reservation_id'] == $bar_reservation[$value['bar_reservation_id']]['id'])){
				$bar_reservation[$value['bar_reservation_id']]['name']	.= '-'.$value['bar_name'];
			}
		}
		return $bar_reservation;
	}
    
    function get_privilege_bar($id)
    {
        $cond = '1=1';
        if(isset($id))
        {
            $cond .= ' and bar_reservation.id =\''.$id.'\'';
        }
		$bars_area = DB::fetch_all('
                SELECT
				bar_area.id,
				bar.privilege
			FROM
				bar_reservation
                INNER JOIN bar_reservation_table on bar_reservation_table.bar_reservation_id = bar_reservation.id
                INNER JOIN bar_table on bar_reservation_table.table_id = bar_table.id
                INNER JOIN bar_area on bar_area.id = bar_table.bar_area_id
				INNER JOIN bar on bar_reservation.bar_id = bar.id
				LEFT OUTER JOIN party on party.user_id = bar_reservation.checked_out_user_id AND party.type=\'USER\'
			WHERE
                '.$cond.'
        ');
		$cond = ' and (';
		//$cond = '';
		$i = 1;
		foreach($bars_area as $key => $value)
        {	
			if($i == 1)
            {
				$cond .= ' bar_area.id = '.$value['id'].'';
			}
            else
            {
				$cond .= ' or bar_area.id = '.$value['id'].'';
			}
			$i++;
		}
		$cond .= ')';
		if($i>1)
        {
			return $cond;
		}
        else
        {
			return false;
		}
		return $cond;
	}
}
?>