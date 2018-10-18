<?php
class SplitBarInvoiceForm extends Form{
	function SplitBarInvoiceForm(){
		Form::Form('SplitBarInvoiceForm');
		$this->link_css("packages/hotel/skins/default/css/invoice.css");	
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');		
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');	
		$this->link_js('packages/core/includes/js/jquery/window/jquery.window.js');
		$this->link_js('packages/hotel/packages/reception/modules/includes/jquery.windows-engine.js');
        $this->link_js('packages/hotel/packages/reception/modules/includes/common01.js');
		$this->link_css("packages/hotel/skins/default/css/jquery.windows-engine.css");
		$this->add('traveller_id',new IntType(true,'invalid_traveller_id','0','100000000000'));
	}
	function on_submit(){
		if(Url::get('action') == 1){
			
		}
	}
	function draw(){	
		$in_time = Date_Time::to_time(date('d/m/Y',time()));
		$cond = ' AND  bar_reservation.status=\'CHECKIN\'
					AND bar_reservation.arrival_time>=\''.$in_time.'\' AND bar_reservation.arrival_time < \''.($in_time+86400).'\'';
		if(Session::get('bar_id')){
			$cond .= ' AND bar_table.bar_id = '.Session::get('bar_id').'';
			$_REQUEST['bar_id'] = Session::get('bar_id');
		}
		if(Url::get('invoice_id')){
			$cond .= ' AND bar_reservation.id = '.Url::get('invoice_id').'';		
		}
		if(Url::get('in_date')){
			$in_time = Date_Time::to_time(Url::get('in_date'));	
		}
		$sql = 'SELECT
        				count(bar_reservation.id) AS acount
        			FROM
        				bar_reservation
						INNER JOIN bar_reservation_table ON bar_reservation.id = bar_reservation_table.bar_reservation_id
						INNER JOIN  bar_table ON bar_reservation_table.table_id = bar_table.id
        			WHERE 
                        1>0 '.$cond.'  
        		  ';
        require_once 'packages/core/includes/utils/paging.php';
		$item_per_page = 100;
		$this->map['total'] =  DB::fetch($sql,'acount');
		$this->map['paging'] =  paging($this->map['total'],$item_per_page,10,false);
		$this->map['bars'] = $this->get_total_bars(false);
		$split_type  = '<option value="">--------</option> <option value="PRODUCT">PRODUCT</option>';
		$split_type .= '<option value="QUANTITY">QUANTITY</option>';
		$split_type .= '<option value="RATE">RATE</option>';
		$this->map['split_type_options'] = $split_type;
		$sql = '
		SELECT * FROM
        	(	SELECT 
					bar_reservation.id
					,bar_reservation.code
					,bar_reservation.arrival_time
					,bar_reservation.departure_time
					,bar_reservation.total
					,bar_reservation.total_before_tax
					,bar_reservation.tax_rate
					,bar_reservation.bar_fee_rate
					,bar_table.name as table_name
					,ROWNUM as rownumber  
				FROM bar_reservation
					INNER JOIN bar_reservation_table ON bar_reservation.id = bar_reservation_table.bar_reservation_id
					INNER JOIN  bar_table ON bar_reservation_table.table_id = bar_table.id
					INNER JOIN bar_reservation_product ON bar_reservation.id = bar_reservation_product.bar_reservation_id
				WHERE
					1>0 '.$cond.'
				ORDER BY bar_reservation.id DESC
				)
			WHERE
			 rownumber > '.((page_no()-1)*$item_per_page).' and rownumber<='.((page_no())*$item_per_page).'
                ';      
		$items = DB::fetch_all($sql);
		$stt = 0;
		foreach($items as $k=> $item){
			$stt++;
			$items[$k]['stt'] = $stt;
			$items[$k]['row_class'] = ($stt%2);	
		}
		$this->map['items'] = $items;
		$this->parse_layout('split',$this->map);
	}
	function get_total_bars($bar_id = false){
		//-------- Phan quyen Bar-------------//
		require_once 'packages/hotel/packages/restaurant/includes/table.php';
		$cond_admin = Table::get_privilege_bar();
		$bars = array();
		if(User::is_admin() || $cond_admin){
			$bars = DB::fetch_all('SELECT * FROM BAR where 1=1 '.$cond_admin.' and portal_id = \''.PORTAL_ID.'\' ORDER BY ID ASC');
		}
		return $bars;
	}
}
?>