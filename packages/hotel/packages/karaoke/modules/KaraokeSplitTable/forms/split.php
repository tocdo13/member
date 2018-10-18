<?php
class SplitTableForm extends Form
{
	function SplitTableForm()
	{
		Form::Form('SplitTableForm');	
	}		
	function check_in_karaoke()
	{
		require_once 'packages/hotel/packages/karaoke/includes/table.php';
		$check = false;
		$karaoke_reservation_id = Url::iget('from_code');
		$karaoke_table = SplitTableDB::get_karaoke_table(Url::iget('from_code'));
		$split_table = Url::get('split_table');	
		if(Url::get('new_table_id') and Url::get('split_table')){
			if(!isset($_REQUEST['order']) or empty($_REQUEST['order'])){
				return false;	
			}
			$new_table_ids = explode(",",Url::get('new_table_id'));
                        $new_table_ids = array_unique($new_table_ids); 
			for($j=0;$j<count($new_table_ids);$j++){			
				$table_new = DB::fetch('select * from karaoke_reservation_table INNER JOIN karaoke_reservation on karaoke_reservation.id = karaoke_reservation_table.karaoke_reservation_id where table_id ='.$new_table_ids[$j].' AND karaoke_reservation.status=\'CHECKIN\'');
				if($table_new){
						$check = true;						
						return false;
						//break;	
				}
			}
			if(!$check)
			{
				$item = DB::fetch('select * from karaoke_RESERVATION where id ='.Url::iget('from_code'));
				unset($item['id']);
				unset($item['code']);
				$karaoke_reservation_id = DB::insert('karaoke_RESERVATION',$item);
				$code = Table::get_code_karaoke_reservation($karaoke_reservation_id);
				DB::update_id('karaoke_RESERVATION',array('code'=>$code),$karaoke_reservation_id);
				// Xu ly tach ban - tach hoa don
				foreach($split_table as $value){
					$split = $value;
				}
				$karaoke_rt = DB::fetch('select * from karaoke_RESERVATION_TABLE where id='.$split);
				unset($karaoke_rt['id']);
				for($i=0;$i<count($new_table_ids);$i++){
					$karaoke_rt['table_id'] = $new_table_ids[$i];
					$karaoke_rt['karaoke_reservation_id'] = $karaoke_reservation_id;
					//DB::delete_id('karaoke_RESERVATION_TABLE',$value);
					DB::insert('karaoke_RESERVATION_TABLE',$karaoke_rt);
				}
				
			}
		}else { echo '<script>alert("'.Portal::language('no_table_selected').'");location="'.Url::build_current(array('type'=>'split')).'"</script>'; return false;}
		return $karaoke_reservation_id;
	}
	function split_table()
	{
		$karaoke_reservation_id = $this->check_in_karaoke();
		if(!$karaoke_reservation_id){
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
					DB::update_id('karaoke_RESERVATION_PRODUCT',array('karaoke_RESERVATION_ID'=>$karaoke_reservation_id),$key);					
				}
				elseif($item = DB::exists_id('karaoke_RESERVATION_PRODUCT',$key))
				{
					$cancel = false;
					//chuyen 1 phan san pham tu ban cu sang ban moi
					unset($item['id']);
					$item['karaoke_reservation_id'] = $karaoke_reservation_id;
					$item['quantity'] = $_REQUEST['quantity'][$key];
					$total += $item['price'] * ($item['quantity'] - $item['quantity_discount']) - (($item['price'] * ($item['quantity'] - $item['quantity_discount']))*$item['discount_rate']);
					DB::insert('karaoke_RESERVATION_PRODUCT',$item);	
					//cap nhap lai so luong san pham cua ban chuyen
					DB::update_id('karaoke_RESERVATION_PRODUCT',array('quantity'=>($_REQUEST['quantity_before'][$key]-$_REQUEST['quantity'][$key])),$key);					
				}	
			}	
		}
		if(($cancel==true) and (count($_REQUEST['order'])==count($_REQUEST['quantity_before'])))
		{
			DB::delete_id('karaoke_RESERVATION',Url::iget('from_code'));
			DB::delete('karaoke_RESERVATION_TABLE','karaoke_RESERVATION_ID='.Url::iget('from_code'));
		}else{// cap nhat lai total cho karaoke_reservation
			Table::updateTotalKaraoke($karaoke_reservation_id);
			Table::updateTotalKaraoke(Url::iget('from_code'));
		}
        if(!$this->is_error())
		{
			echo "<script>alert('".Portal::language('table_split_success')."');window.location='".Url::build('karaoke_table_map',array())."';</script>";
		}
	}
	function on_submit()
	{
		if(Url::get('acction') == 1){	
			if(Url::get('karaokes')){
				Session::set('karaoke_id',Url::get('karaokes'));
			}
			$_REQUEST['karaoke_id'] = Session::get('karaoke_id');
			//Url::redirect_current(array('karaoke_id'=>Session::get('karaoke_id'),'status'));	
		}else if(Url::get('confirm')==1 and Url::get('cmd')!='change_table'){
			if(!isset($_REQUEST['from_code'])){
				$this->error('from_code','select_from_table');
			}if(isset($_REQUEST['from_code']) and $_REQUEST['from_code'] and Url::get('save')){
				$this->split_table();			
				//Url::redirect('table_map');
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
		//System::Debug($from_table);
		$this->parse_layout('split',array(
			'from_code_list'=>array('')+String::get_list($from_table),
			'orders'=>$order_menu,
			'tables_option'=>$tables_option,
			'tables'=>$tables,
			'karaokes_list'=>$list_karaokes			
		));
	}
}
?>
