<?php
class ListExtraServiceInvoiceForm extends Form
{
	function ListExtraServiceInvoiceForm()
	{
		Form::Form('ListExtraServiceInvoiceForm');
		System::set_page_title(HOTEL_NAME);
		$this->link_css(Portal::template('hotel').'/css/style.css');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
	}
    function on_submit()
    {
        if(Url::get('saver')){
            
			if($this->check()){
				if(isset($_REQUEST['mi_product_group']))
				{	
					$array = array(
						'bill_number',
						'payment_type',
						'note',
                        'close'=>(Url::get('close')?1:0),
						'code'=>Url::get('code')?Url::get('code'):'',
						'total_amount'=>System::calculate_number(Url::get('total')),
						'total_before_tax'=>System::calculate_number(Url::get('total_amount')),
						'tax_rate'=>System::calculate_number(Url::get('tax_rate')),
						'service_rate'=>System::calculate_number(Url::get('service_rate')),
						'portal_id'=>PORTAL_ID				
					);
					if(Url::get('cmd')=='edit'){  
						$id = Url::iget('id');
						DB::update('extra_service_invoice',$array+array('lastest_edited_time'=>time(),'lastest_edited_user_id'=>Session::get('user_id')),'id='.Url::iget('id'));
						$log_action  = 'edit';
						$log_title = 'Edit extra service invoice #'.$id.'';
					}else{
						$id = DB::insert('extra_service_invoice',$array+array('reservation_room_id'=>Url::get('reservation_room_id'),'time'=>time(),'user_id'=>Session::get('user_id')));
						$log_action  = 'add';
						$log_title = 'Add extra service invoice #'.$id.'';
					}
					$invoice_id = $id; 
					$log_description = '<br>Payment type: '.Url::get('payment_type').'<br>';
					$log_description .= '<br>Payment method: '.DB::fetch('select id,name_'.Portal::language().' as name from payment_type where id = '.Url::get('payment_method_id').'','name').'<br>';
					$log_description .= '<br>Note: '.Url::get('note').'<br><hr><br>';
					$old_items = DB::select_all('extra_service_invoice_detail','invoice_id='.$invoice_id.'');
					foreach($_REQUEST['mi_product_group'] as $key=>$record)
					{
						$record['price']=str_replace(',','',$record['price']);
						$record['quantity']=str_replace(',','',$record['quantity']);
						$record['in_date'] = Date_Time::to_orc_date($record['in_date']);
						$record['used'] = isset($record['used'])?1:0;
						if($service = DB::fetch('SELECT name FROM extra_service WHERE id=\''.intval($record['service_id']).'\'')){
							$service_name = $service['name'];
						}else{
							$service_name = $record['service_id'];
							$record['service_id'] = DB::insert('extra_service',array('name'=>$service_name,'unit'=>$record['unit'],'price'=>$record['price']));
						}
						$record['name'] = $service_name;
						unset($record['payment_price']);
						$unit_name = $record['unit'];
						unset($record['unit']);
						$empty = true;
						foreach($record as $record_value)
						{
							if($record_value)
							{
								$empty = false;
							}
						}
						if(!$empty)
						{
							$record['invoice_id'] = $invoice_id;
							if($record['id'])
							{
								$id = $record['id'];
								if(isset($old_items[$id])){
									$old_items[$id]['not_delete'] = true;
								}	
								unset($record['id']);
								$log_description .= 'Edit [Service: '.$service_name.', Price: '.System::display_number($record['price']).', Quantity: '.$record['quantity'].', Unit: '.$unit_name.', Date: '.$record['in_date'].', Used: '.$record['used'].']<br>';
								DB::update('extra_service_invoice_detail',$record,'id=\''.$id.'\'');
							}
							else
							{
								if(DB::exists('SELECT id FROM extra_service WHERE id=\''.$record['service_id'].'\''))
								{
									if(isset($record['id'])){
										unset($record['id']);
									}
									$log_description .= 'Add [Service: '.$service_name.', Price: '.System::display_number($record['price']).', Quantity: '.$record['quantity'].', Unit: '.$unit_name.', Date: '.$record['in_date'].', Used: '.$record['used'].']<br>';
									$record['time'] = time();
									DB::insert('extra_service_invoice_detail',$record);
								}
							}
						}
					}
					foreach($old_items as $item)
					{
						if(!isset($item['not_delete']))
						{
							echo '<br>Delete row with ID: '.$item['id'].'<hr><br>';
							DB::delete('extra_service_invoice_detail','id=\''.$item['id'].'\'');
							$log_description .= '<br><hr>Delete row with ID: '.$item['id'].'<br>';
						}
					}		
					System::log($log_action,$log_title,$log_description,$id);// Edited in 06/01/2010
					{
						echo '<div id="progress"><img src="packages/core/skins/default/images/updating.gif" /> Updating to server...</div>';
						echo '<script>
						if(window.opener)
						{
							window.opener.history.go(0);
							window.close();
						}
						window.setTimeout("location=\''.URL::build_current(array('just_edited_id'=>$id)).'\'",2000);
						</script>';
						exit();
					}
				}else{
					$this->error('mi_product_group','there_is_not_any_service');				
				}
			}
		}
    }
	function draw()
	{
	   /**
       	$extra_service=DB::fetch_all('select * from extra_service_invoice_detail');
        foreach($extra_service as $key=>$value){
        $table_id= DB::insert('extra_service_invoice_table',array('invoice_id'=>$value['invoice_id'],'from_date'=>$value['in_date'],'to_date'=>$value['in_date']));
        DB::update('extra_service_invoice_detail',array('table_id'=>$table_id),'id='.$value['id'].'');
       }
       **/
       
		$this->map = array();
        //System::debug($_SESSION['errors']);
        if(isset($_SESSION['errors']))
        {
            $this->error('',$_SESSION['errors']);
            unset($_SESSION['errors']);
        }
		$item_per_page = 200;
        
        //Start Luu Nguyen Giap add portal
        if(Url::get('portal_id'))
        {
           $portal_id =  Url::get('portal_id');
        }
        else
        {
            $portal_id =PORTAL_ID;
        }
        if($portal_id!="ALL")
        {
            $cond ="  extra_service_invoice.portal_id ='".$portal_id."' ";
        }
        else
        {
            $cond=" 1=1 ";
        } 
        //End Luu Nguyen Giap add portal
		$cond .= '
			
			'.(Url::get('bill_number')?' AND extra_service_invoice.bill_number LIKE \'%'.Url::sget('bill_number').'%\'':'').'
			'.(Url::get('room_name')?' AND room.name LIKE \'%'.Url::sget('room_name').'%\'':'').'
			'.(Url::get('time')?' AND (extra_service_invoice.time >= '.Date_Time::to_time(Url::get('time')).' and extra_service_invoice.time < '.(Date_Time::to_time(Url::get('time'))+24*3600).')':'').'
			'.(Url::get('from_date')?' AND esid.in_date >= \''.Date_Time::to_orc_date(Url::get('from_date')).'\'':'').'
			'.(Url::get('to_date')?' AND esid.in_date <= \''.Date_Time::to_orc_date(Url::get('to_date')).'\'':'').'
			
			';
        
        $this->map['type'] = Url::get('type')?Url::get('type'):Url::get('check_type');
        $_REQUEST['type'] = $this->map['type'];
        if(Url::get('type'))
        {
           $cond .= ' and extra_service.type=\''.Url::get('type').'\'';
        }    
		$this->map['title'] =Url::get('type')=='SERVICE'?Portal::language('extra_service_invoice_list'):Portal::language('extra_room_charge_list');
		$sql = '
			SELECT
				count(*) AS acount
			FROM
				extra_service_invoice
				inner join extra_service_invoice_detail esid on esid.invoice_id = extra_service_invoice.id
				LEFT OUTER JOIN reservation_room on reservation_room.id = extra_service_invoice.reservation_room_id
                inner join extra_service on extra_service.id=esid.service_id 
				LEFT OUTER JOIN room on room.id = reservation_room.room_id
			WHERE
				'.$cond.'
		';
		require_once 'packages/core/includes/utils/paging.php';
		$this->map['total'] =  DB::fetch($sql,'acount');
		$this->map['paging'] =  paging($this->map['total'],$item_per_page,10);
		$sql = '
			SELECT * FROM
			(
				SELECT
					extra_service_invoice.*,room.name as room_name,MICE_INVOICE_DETAIL.id as mice_invoice,
					to_char(reservation_room.arrival_time,\'DD/MM/YYYY\') as arrival_date,to_char(reservation_room.departure_time,\'DD/MM/YYYY\') as departure_date,reservation_room.status,
					
					(SELECT sum(total_amount) as total FROM extra_service_invoice esi WHERE esi.id = extra_service_invoice.id) AS total,
					row_number() over (order by '.(Url::get('order_by')?Url::get('order_by'):'extra_service_invoice.time').' '.(Url::get('dir')?Url::get('dir'):'DESC').') as rownumber
				FROM
					extra_service_invoice
					inner join extra_service_invoice_detail esid on esid.invoice_id = extra_service_invoice.id
					inner join extra_service on extra_service.id=esid.service_id 
					LEFT OUTER JOIN reservation_room on reservation_room.id = extra_service_invoice.reservation_room_id
					LEFT OUTER JOIN room on room.id = reservation_room.room_id
                    LEFT JOIN MICE_INVOICE_DETAIL on MICE_INVOICE_DETAIL.INVOICE_ID=esid.id and MICE_INVOICE_DETAIL.type=\'EXTRA_SERVICE\' 
				WHERE
					'.$cond.'
                order by extra_service_invoice.id desc     	
			)
			WHERE
			 	rownumber > '.((page_no()-1)*$item_per_page).' and rownumber<='.((page_no())*$item_per_page).'
		';
		//payment_type.name_'.Portal::language().' as payment_method,
		//LEFT OUTER JOIN payment_type ON payment_type.id = extra_service_invoice.payment_method_id
		$items = DB::fetch_all($sql);
		$i = 1;
		foreach($items as $key=>$value){
			$items[$key]['i'] = $i++;
			$items[$key]['time'] = date('d/m/Y',$value['time']);
			$items[$key]['lastest_edited_time'] = $value['lastest_edited_time']?date('d/m/Y',$value['lastest_edited_time']):'';
			$items[$key]['total'] = System::display_number(round($value['total']));
			$items[$key]['payment_type'] = Portal::language(strtolower($value['payment_type']));
		}
		$this->map['items'] = $items;
        //System::debug($items);
        //Start : Luu Nguyen GIap add portal
        $this->map['portal_id_list'] = array('ALL'=>Portal::language('all'))+String::get_list(Portal::get_portal_list()); 
        //End   :Luu Nguyen GIap add portal
        
		$this->parse_layout('list',$this->map);
	}
      function update_data(){
    $detail=DB::fetch_all('select * from extra_service_invoice_detail');
    if(User::id()=='developer10'){
        foreach($detail as $key=>$value){
          $table_id=  DB::insert('extra_service_invoice_table',array(
          'from_date'=>$value['in_date'],'to_date'=>$value['in_date'],'invoice_id'=>$value['invoice_id']));
          DB::update('extra_service_invoice_detail',array('table_id'=>$table_id),'id='.$value['id'].'');
        }
    }
  } 	
}
?>