<?php
class SplitKaraokeInvoiceForm extends Form{
	function SplitKaraokeInvoiceForm(){
		Form::Form('SplitKaraokeInvoiceForm');
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
		$cond = ' AND  karaoke_reservation.status=\'CHECKIN\'
					AND karaoke_reservation.arrival_time>=\''.$in_time.'\' AND karaoke_reservation.arrival_time < \''.($in_time+86400).'\'';
		if(Session::get('karaoke_id')){
			$cond .= ' AND karaoke_table.karaoke_id = '.Session::get('karaoke_id').'';
			$_REQUEST['karaoke_id'] = Session::get('karaoke_id');
		}
		if(Url::get('invoice_id')){
			$cond .= ' AND karaoke_reservation.id = '.Url::get('invoice_id').'';		
		}
		if(Url::get('in_date')){
			$in_time = Date_Time::to_time(Url::get('in_date'));	
		}
		$sql = 'SELECT
        				count(karaoke_reservation.id) AS acount
        			FROM
        				karaoke_reservation
						INNER JOIN karaoke_reservation_table ON karaoke_reservation.id = karaoke_reservation_table.karaoke_reservation_id
						INNER JOIN  karaoke_table ON karaoke_reservation_table.table_id = karaoke_table.id
        			WHERE 
                        1>0 '.$cond.'  
        		  ';
        require_once 'packages/core/includes/utils/paging.php';
		$item_per_page = 100;
		$this->map['total'] =  DB::fetch($sql,'acount');
		$this->map['paging'] =  paging($this->map['total'],$item_per_page,10,false);
		$this->map['karaokes'] = $this->get_total_karaokes(false);
		$split_type  = '<option value="">--------</option> <option value="PRODUCT">PRODUCT</option>';
		$split_type .= '<option value="QUANTITY">QUANTITY</option>';
		$split_type .= '<option value="RATE">RATE</option>';
		$this->map['split_type_options'] = $split_type;
		$sql = '
		SELECT * FROM
        	(	SELECT 
					karaoke_reservation.id
					,karaoke_reservation.code
					,karaoke_reservation.arrival_time
					,karaoke_reservation.departure_time
					,karaoke_reservation.total
					,karaoke_reservation.total_before_tax
					,karaoke_reservation.tax_rate
					,karaoke_reservation.karaoke_fee_rate
					,karaoke_table.name as table_name
					,ROWNUM as rownumber  
				FROM karaoke_reservation
					INNER JOIN karaoke_reservation_table ON karaoke_reservation.id = karaoke_reservation_table.karaoke_reservation_id
					INNER JOIN  karaoke_table ON karaoke_reservation_table.table_id = karaoke_table.id
					INNER JOIN karaoke_reservation_product ON karaoke_reservation.id = karaoke_reservation_product.karaoke_reservation_id
				WHERE
					1>0 '.$cond.'
				ORDER BY karaoke_reservation.id DESC
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
	function get_total_karaokes($karaoke_id = false){
		//-------- Phan quyen Karaoke-------------//
		require_once 'packages/hotel/packages/karaoke/includes/table.php';
		$cond_admin = Table::get_privilege_karaoke();
		$karaokes = array();
		if(User::is_admin() || $cond_admin){
			$karaokes = DB::fetch_all('SELECT * FROM karaoke where 1=1 '.$cond_admin.' and portal_id = \''.PORTAL_ID.'\' ORDER BY ID ASC');
		}
		return $karaokes;
	}
}
?>