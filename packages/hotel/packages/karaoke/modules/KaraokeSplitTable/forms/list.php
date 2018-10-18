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
			if(Url::get('karaokes')){
				Session::set('karaoke_id',Url::get('karaokes'));
			}
			$_REQUEST['karaoke_id'] = Session::get('karaoke_id');
			//Url::redirect_current(array('karaoke_id'=>Session::get('karaoke_id'),'status'));	
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
					if(DB::select_id('karaoke_reservation',intval(Url::get('from_code'))))
					{
						$br_from_code = DB::fetch('select * from karaoke_reservation where id='.Url::get('from_code').'');
						$br_to_code = DB::fetch('select * from karaoke_reservation where id='.Url::get('to_code').'');
						$total_new = $br_from_code['total'] + $br_to_code['total'];
						$total_before_tax_new = $br_from_code['total_before_tax'] + $br_to_code['total_before_tax'];
						DB::update_id('karaoke_RESERVATION',array('total'=>$total_new,'total_before_tax'=>$total_before_tax_new),Url::get('to_code'));
						DB::delete_id('karaoke_reservation',intval(Url::get('from_code')));
						//DB::query('update karaoke_reservation_product set karaoke_reservation_id ='.Url::get('to_code').' where karaoke_reservation_id='.Url::get('from_code'));
						//DB::query('update karaoke_reservation_table set karaoke_reservation_id ='.Url::get('to_code').' where karaoke_reservation_id='.Url::get('from_code'));
						$product_1 =DB::fetch_all('select karaoke_reservation_product.product_id as id,karaoke_reservation_product.quantity,karaoke_reservation_product.id as brp_id,karaoke_reservation_product.product_name from karaoke_reservation_product
						where karaoke_reservation_id ='.Url::get('from_code').'');
						$product_2 =  DB::fetch_all('select karaoke_reservation_product.* from karaoke_reservation_product
						where karaoke_reservation_id ='.Url::get('to_code').'');	
						foreach($product_2 as $p =>$product){
							if(isset($product_1[$product['product_id']]) && (($product['product_id']!='FOUTSIDE' && $product['product_id']!='DOUTSIDE') || ($product_1[$product['product_id']]['product_name']!=$product['product_name']))){
								DB::query('delete from karaoke_reservation_product where id = '.$product_1[$product['product_id']]['brp_id'].'');
								DB::query('update karaoke_reservation_product set quantity ='.($product_1[$product['product_id']]['quantity']+$product['quantity']).' where id='.$p.'');
							}
						}
						foreach($product_1 as $p1 =>$prd){
							if(!isset($product_2[$p1])){
								//DB::query('delete from karaoke_reservation_product where id = '.$product_1[$product['product_id']]['brp_id'].'');
								DB::query('update karaoke_reservation_product set karaoke_reservation_id ='.Url::get('to_code').' where id='.$prd['brp_id'].'');
							}
						}
						//DB::query('update karaoke_reservation_table set karaoke_reservation_id ='.Url::get('to_code').' where karaoke_reservation_id='.Url::get('from_code'));
                        DB::query('delete from karaoke_reservation_table where karaoke_reservation_id='.Url::get('from_code'));
					}
					if(!$this->is_error())
					{
						//echo "<script>alert('".Portal::language('table_split_success')."');window.location='http://localhost:8081/nhap/?page=karaoke_table_map&karaoke_id=".$br_to_code['karaoke_id']."';</script>";
                        echo "<script>alert('".Portal::language('table_split_success')."');window.location='".Url::build('karaoke_table_map',array('karaoke_id'=>$br_to_code['karaoke_id']))."';</script>";
                        //window.close();window.open('http://localhost:8081/nhap/?page=karaoke_table_map&karaoke_id=".$_SESSION['karaoke_id']."');
                        //Url::build('karaoke_table_map',array('karaoke_id'=>$br_to_code['karaoke_id']));
                        //Url::build_page('karaoke_table_map',array('karaoke_id'=>$br_to_code['karaoke_id']));
                        //Url::redirect('karaoke_table_map',array('karaoke_id'=>$br_to_code['karaoke_id']));
                        
					}	
				}
			}	
		}
	}
	function draw()
	{	
		$from_table  = array();
		$cond = '1=1';
		require_once 'packages/hotel/packages/karaoke/includes/table.php';
		$cond_admin = Table::get_privilege_karaoke();
		$karaokes = DB::fetch_all('SELECT * FROM karaoke where 1=1 '.$cond_admin.' ORDER BY ID ASC');
		$list_karaokes[0] = '----Select_Karaoke----';
		$list_karaokes = $list_karaokes + String::get_list($karaokes,'name');
		if(Session::get('karaoke_id') && isset($karaokes[Session::get('karaoke_id')])){
			$_REQUEST['karaokes'] = Session::get('karaoke_id');
		}
		if($from_table = SplitTableDB::get_table_use())
		{
			/*$ids = '0';
			foreach($from_table as $key=>$value)
			{
				$ids.=','.$value['table_id'];
			}
			$cond = 'karaoke_TABLE.ID NOT IN ('.$ids.')';
			*/
		}
		if(!(Url::get('from_code') && $order_menu = SplitTableDB::get_order_menu(Url::iget('from_code'))))
		{
			$order_menu = array();			
		}
		$tables = SplitTableDB::get_karaoke_table(Url::iget('from_code'));
		$to_tables = SplitTableDB::get_table($cond);
		$tables_option = '<option value="">'.Portal::language('none').'</option>';
		foreach($to_tables as $key=>$value)
		{
			$tables_option.='<option value="'.$key.'">'.$value['name'].'</option>';
		}
		$this->parse_layout('list',array(
			'from_code_list'=>array('')+String::get_list($from_table),
			'to_code_list'=>array('')+String::get_list($from_table),
			'karaokes_list'=>$list_karaokes			
		));
	}
}
?>
