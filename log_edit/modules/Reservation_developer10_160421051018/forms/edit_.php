﻿<?php
class EditReservationForm extends Form
{
	function EditReservationForm()
	{
		Form::Form('EditReservationForm');
        //giap.ln add switch Be-tech keys, Adel, SalTo, HUNERF keys
        define("IS_BETECH",1);
        define("IS_ADEL",2);
        define("IS_SALTO",3);
        define("IS_HUNERF",4);
        
        //end giap.ln
		//$this->add('id',new IDType(true,'object_not_exists','reservation'));
		$this->link_css('packages/core/includes/js/jquery/window/css/jquery.window.4.04.css');
		$this->link_js('packages/core/includes/js/jquery/window/jquery.window.js');
		$this->link_js('packages/core/includes/js/jquery/ui/jquery.ui.widget.js');
		$this->link_js('packages/core/includes/js/jquery/ui/jquery.ui.mouse.js');
		$this->link_js('packages/core/includes/js/jquery/ui/jquery.ui.draggable.js');
		$this->link_js('packages/core/includes/js/jquery/ui/jquery.ui.resizable.js');
		$this->link_js('packages/core/includes/js/picker.js');
		$this->link_js('packages/core/includes/js/jquery/jquery.mask.min.js');
		$this->link_js('packages/hotel/includes/js/suggest.js');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
		$this->link_js('packages/hotel/packages/reception/modules/includes/reservation_table.js');
		$this->link_js('packages/hotel/includes/js/ajax.js');
		$this->link_js('packages/core/includes/js/multi_items.js');    
        $this->link_js('packages/core/includes/js/jquery/jquery.autocomplete.js');
		$this->link_js('packages/core/includes/js/tooltip.js');
		//$this->link_js('r_get_reservation_traveller.php?id='.Url::get('id'),false);
		$this->link_css('packages/hotel/'.Portal::template('reception').'/css/style.css');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_css(Portal::template('hotel').'/css/style.css');
		$this->link_css('packages/core/skins/default/css/jquery.autocomplete.css');
		//Dung cho folio
		$this->link_js('packages/hotel/packages/reception/modules/includes/jquery.windows-engine.js');
		$this->link_css("packages/hotel/skins/default/css/jquery.windows-engine.css");
		//end
		//$this->link_js('r_get_reservation.php?id='.Url::get('id'),false);
		$this->add('note',new TextType(false,'invalid_note',0,200000));
		$this->add('customer_id',new TextType(true,'vui long nhap ten nguon khach',0,255));
		$this->add('tour_id',new TextType(false,'tour_id',0,255));
		$this->add('reservation_room.id',new TextType(false,'invalid_reservation_id',0,255));
		$this->add('reservation_room.room_name',new TextType(false,'miss_room',0,255));
		$this->add('reservation_room.room_id',new TextType(false,'invalid_room_id',0,255,'status','CHECKIN'));
		$this->add('reservation_room.room_level_id',new TextType(true,'invalid_room_level_id',0,255));
		$this->add('reservation_room.room_level_name',new TextType(true,'invalid_room_level_id',0,255));
		$this->add('reservation_room.price',new FloatType(true,'invalid_price','0','100000000000'));
		$this->add('reservation_room.time_in',new TextType(true,'time_in',0,5));
		$this->add('reservation_room.time_out',new TextType(true,'time_out',0,5));
		$this->add('reservation_room.arrival_time',new DateType(true,'arrival_time'));
		$this->add('reservation_room.adult',new IntType(false,'invalid_adult','0','1000'));
		$this->add('reservation_room.child',new IntType(false,'invalid_child','0','1000'));
		$this->add('reservation_room.departure_time',new DateType(true,'departure_time'));
		$this->add('reservation_room.note',new TextType(false,'invalid_note',0,255));
		$this->add('reservation_room.total_amount',new FloatType(false,'invalid_total_amount','0','100000000000'));
		$this->add('reservation_room.reduce_balance',new FloatType(false,'invalid_reduce_balance','0','100'));
		$this->add('reservation_room.deposit',new FloatType(false,'invalid_deposit','0','100000000000'));
		$this->add('reservation_room.reason',new TextType(false,'invalid_reason',0,255));
		$this->add('reservation_room.tax_rate',new FloatType(false,'invalid_tax_rate','0','100'));
		$this->add('reservation_room.service_rate',new FloatType(false,'invalid_service_rate','0','100'));
		//require_once 'cache/config/payment.php';
	}
	function on_submit()
	{
        /** manh chan loi checkout som phat sinh luoc do gia ngay cuoi **/
        foreach($_REQUEST['mi_reservation_room'] as $key_1=>$value_1)
        {
            if($value_1['arrival_time']!=$value_1['departure_time'])
            {
                unset($_REQUEST['mi_reservation_room'][$key_1]['change_price_arr'][$value_1['departure_time']]);
            }
            unset($_REQUEST['mi_reservation_room'][$key_1]['package_sale_name']);
        }
        /** end manh **/
        /** Mạnh xử lý lỗi phòng trùng nhau **/
        $check_price = false;
       $stt_id = 0;
       foreach($_REQUEST['mi_reservation_room'] as $mi_id=>$mi_value){
            if($mi_value['room_level_name']!='PA')
            {
                if($mi_value['price']>0){
                    $check_price = false;
                }else{
                    $check_price = true;
                }
            }
            if($mi_value['status']!='CANCEL'){
                $stt_id ++;
                $time_in = explode(':',$mi_value['time_in']);
                $time_out = explode(':',$mi_value['time_out']);
                $check_room[$stt_id]['id'] = $mi_id;
                $check_room[$stt_id]['name'] = $mi_value['room_name'];
                $check_room[$stt_id]['arrival_time'] = Date_Time::to_time($mi_value['arrival_time'])+($time_in[0]*3600+$time_in[1]*60);
                $check_room[$stt_id]['departure_time'] = Date_Time::to_time($mi_value['departure_time'])+($time_out[0]*3600+$time_out[1]*60);
            }
       }
       
       $check = false;
       for($k=1;$k<$stt_id;$k++){
            for($l=$k+1;$l<=$stt_id;$l++){
                if($check_room[$k]['name']==$check_room[$l]['name']){
                    if(($check_room[$k]['arrival_time']>$check_room[$l]['departure_time']) OR ($check_room[$k]['departure_time']<$check_room[$l]['arrival_time'])){
                        //$check = false;
                    }else{
                        $check = true;
                    }
                }
            }
       }
       
       if($check == true){
            echo "<script>";
            echo    'if(alert("phòng trùng nhau")){return false;}';
            echo "</script>";
            $this->add('reservation_room.room_name',new TextType(true,'phòng trùng nhau',255,255));
            
       }
       /** end Mạnh xử lý lỗi phòng trùng nhau **/
       
        if($_REQUEST['confirm'])
        {
            URL::build_current(array('confirm'=>$_REQUEST['confirm']));
        }
        else
        {
            if($this->check())
    		{
    			require_once 'packages/hotel/packages/reception/modules/includes/reservation.php';
                $description_room = "";
    			$id = Url::iget('id');
    			$old_reservation = DB::select('reservation','id='.$id);
    			$old_tour_name = $old_reservation['tour_id']?DB::fetch('select id,name from tour where id = '.$old_reservation['tour_id'].'','name'):'';
    			$old_customer_name = $old_reservation['customer_id']?DB::fetch('select id,name from customer where id = '.$old_reservation['customer_id'].'','name'):'';
    			$old_reservation_room = DB::select_all('reservation_room','reservation_id='.$id.(URL::get('reservation_room_id')?' and id=\''.URL::get('reservation_room_id').'\'':''));
    			$live = array();
    			if(isset($_REQUEST['mi_reservation_room']))
                {
    				$reservation_room_arr = array();
    				foreach($_REQUEST['mi_reservation_room'] as $key=>$record)
    				{
    				    $description_room .= Portal::language("room_level")." : ".$record['room_level_name']."";
    					$reservation_room_arr[$record['id']] = $record;
    					if($record['id'] and isset($old_reservation_room[$record['id']]))
    					{
    						$live[$record['id']] = true;
    					}
    				}
    			}
                
    			if(User::can_admin(false,ANY_CATEGORY))
                {
    				foreach($old_reservation_room as $reservation_room)
    				{
    					if(!isset($live[$reservation_room['id']]))
    					{
    					       $description_delete = '';
                               $folio_delete = DB::fetch_all('SELECT folio.id,folio.total FROM folio WHERE reservation_room_id = '.$reservation_room['id'].'');
                               foreach($folio_delete as $id_folio=>$value_folio)
                               {
                                    $description_delete .= "
                                                    delete folio: #".$value_folio['id']."<br/>
                                                    total folio: ".$value_folio['total']."
                                                    ";
                               }
    						DB::delete('reservation_traveller','reservation_room_id=\''.$reservation_room['id'].'\'');
    						DB::delete('reservation_room','id=\''.$reservation_room['id'].'\'');
    						DB::update('room_status',array('reservation_id'=>0,'status'=>'AVAILABLE'),'reservation_room_id=\''.$reservation_room['id'].'\'');
    						DB::delete('payment',' reservation_room_id = '.$reservation_room['id'].'');
    						DB::delete('folio',' reservation_room_id = '.$reservation_room['id'].'');
    						DB::delete('traveller_folio','  reservation_room_id = '.$reservation_room['id'].'');
                            if(isset($reservation_room['room_id']) AND $reservation_room['room_id']!='')
                                $room = DB::fetch("SELECT name FROM room WHERE room.id=".$reservation_room['room_id']);
                            $room_name = '';
                            if(isset($room['name']))
                            {
                                $room_name = $room['name'];
                            }
                            $description_delete .= "
                                                    user delete: ".User::id()."<br/>
                                                    room name: ".$room_name."<br />
                                                    note: ".$reservation_room['note']."
                                                    price: ".$reservation_room['price']." from ".Date_Time::convert_orc_date_to_date($reservation_room['arrival_time'])." to ".Date_Time::convert_orc_date_to_date($reservation_room['departure_time'])."
                                                    ";
                            
                            System::log('Edit','DELETE reservation room #'.$reservation_room['id']." reservation #".$reservation_room['reservation_id'],$description_delete,$id);
    					}
    				}
    			}
    			$valid_room_array = reservation_check_conflict($this);
                check_rooms_repair($this);
                
    			if(!empty($valid_room_array))
                {
    				foreach($valid_room_array as $key=>$value)
                    {
    					if(!$value and !Url::get('customer_id'))
                        {
    						//$this->error('room_'.$key,Portal::language('Has_no_person_in_room').' '.DB::fetch('select id,name from room where id='.$key.'','name'),false);
    					}
    				}
    			}
                
    			if($this->is_error())
    			{
    				return;
    			}
                  
    			reservation_check_permission($this, $id, $old_reservation_room);
    			if($this->is_error())
    			{
    				return;
    			}
                
                $title = 'Edit reservation <a target=\'_blank\' href=\'?page=reservation&cmd=edit&id='.$id.'\'>#'.$id.'</a>';
    			$description = ''
    				.Portal::language('rcode').': '.$id
    				.((Url::get('booking_code') and Url::iget('booking_code') != $old_reservation['booking_code'])?' | '.Portal::language('change').' '.Portal::language('booking_code').' '.$old_reservation['booking_code'].' '.Portal::language('to').': '.Url::get('booking_code').'<br>':'<br>')
    				.((Url::get('tour_id') and Url::iget('tour_id') != $old_reservation['tour_id'])?Portal::language('change').' '.Portal::language('tour').' '.$old_tour_name.' '.Portal::language('to').' :<a target="_blank" href="?page=customer&id='.URL::get('customer_id').'">'.DB::fetch('select name from tour where id=\''.URL::get('tour_id').'\'','name').'</a><br>':'')
    				.((Url::get('customer_id') and Url::iget('customer_id') != $old_reservation['customer_id'])?Portal::language('change').' '.Portal::language('customer').' '.$old_customer_name.' '.Portal::language('to').' '.':<a target="_blank" href="?page=customer&id='.URL::get('customer_id').'">'.DB::fetch('select name from customer where id=\''.URL::get('customer_id').'\'','name').'</a><br>':'')
    				.((Url::get('note') and Url::get('note') != $old_reservation['note'])?Portal::language('change').' '.Portal::language('note').': '.substr(Url::get('note'),0,255).'<br>':'')
    				.'<u class="title">'.Portal::language('room_info').':</u>';
    			$customer_name = URL::get('customer_id')?DB::fetch('select name from customer where id=\''.URL::get('customer_id').'\'','name'):'';
    			$description .= "<br />".$description_room;
                
                update_reservation_room($this,$id, $title, $description, $customer_name,$change_status,$old_reservation_room);
                $new_reservation_room =  DB::select_all('reservation_room','reservation_id='.$id.(URL::get('reservation_room_id')?' and id=\''.URL::get('reservation_room_id').'\'':''));
                
                if($this->is_error())
    			{
    				return;
    			}
                if($this->is_error())
    			{
    				return;
    			}
    			DB::update_id('reservation',
    				array(
        					'customer_id',
        					'tour_id',
        					'note'=>str_replace('"','',Url::get('note')),
        					'color',
                            'booker',
                            'phone_booker',
                            'payment_type1',
        					'payment'=>str_replace(',','',Url::get('payment')),
        					'booking_code',
        					'lastest_user_id'=>Session::get('user_id'),
        					'lastest_time'=>time(),
                            'is_rate_code'=>isset($_REQUEST['is_rate_code'])?1:0
        				),$id
    			);
    			if($row = DB::select('folio','reservation_id='.$id." and customer_id is not null") and $old_reservation['customer_id']!=Url::get('customer_id'))
    			{
                    if(Url::get('customer_id'))
                    {
                        DB::update('folio',array('customer_id'=>Url::get('customer_id')),'reservation_id='.$id);
        				DB::update('traveller_folio',array('reservation_traveller_id'=>Url::get('customer_id')),'folio_id='.$row['id'].' AND reservation_id='.$id);
                    }
                    else
                    {
                        DB::delete('folio','reservation_id='.$id);
                        DB::delete('traveller_folio','folio_id='.$row['id'].' AND reservation_id='.$id);
                    }
    			}
    			$description .= '<div>----------------------------
    				<li>Lastest Modified User: '.Session::get('user_id').'</li>
    				<li>Lastest Modified Time: '.date('d/m/Y H:i\'',time()).'</li>
    			<ul></div>';
    			System::log('Edit',$title,$description,$id);
    			if(Url::get('save'))
                {
                    $have_alert = false;
                    foreach($_REQUEST['mi_reservation_room'] as $key => $value)
                    {
                        if($value['status'] != 'CANCEL' and $value['status'] != 'CHECKOUT')
                        {
                            $have_alert = true;
                            break;
                        }
                    }
                    $location = URL::build('room_map',array('just_edited_id'=>$id));
                    if(Url::get('layout')=='list')
                    {
                        $location = URL::build_current(array('year','month','day', 'status', 'room_id'));
                    }
                    if($have_alert and !DB::fetch_all('select reservation_traveller.id from reservation_traveller where reservation_id = '.$id))
                    echo '<script>
                            var r = confirm("Ban co muon them khach khong?");
                            if(r==true)
                            {
                               var newdiv = document.createElement("div");
                               newdiv.setAttribute("id","progress");
                               newdiv.innerHTML = "<img src = \'packages/core/skins/default/images/updating.gif\'/>Updating room status to server...";
                               ni = document.getElementsByTagName("html")[0];
                               ni.appendChild(newdiv);
                               if(window.opener && (window.opener.year || window.opener.night_audit))
                				{
                					window.opener.history.go(0);
                					window.close();
                				} 
                                window.setTimeout("location=\''.URL::build_current(array('cmd','id','r_r_id','year','month','day', 'status', 'room_id','layout','adddd_guest'=>'yes',)).'\'",2000);
                               // var elem = document.getElementsByClassName("add-order-button")[1];
    //                            if (typeof elem.onclick == "function") {
    //                                elem.onclick.apply(elem);
    //                            }
                            }
                            else
                            {
                                
                               if(window.opener)
                			   {
                				  window.close();
                			   }
                               var newdiv = document.createElement("div");
                               newdiv.setAttribute("id","progress");
                               newdiv.innerHTML = "<img src = \'packages/core/skins/default/images/updating.gif\'/>Updating room status to server...";
                               ni = document.getElementsByTagName("html")[0];
                               ni.appendChild(newdiv);
               				   window.setTimeout("location=\''.$location.'\'",1000);  
                            }
                         </script>';
                    else 
                    echo '<script>
                            if(window.opener)
                			   {
                				  window.close();
                			   }
                               var newdiv = document.createElement("div");
                               newdiv.setAttribute("id","progress");
                               newdiv.innerHTML = "<img src = \'packages/core/skins/default/images/updating.gif\'/>Updating room status to server...";
                               ni = document.getElementsByTagName("html")[0];
                               ni.appendChild(newdiv);
               				   window.setTimeout("location=\''.$location.'\'",1000);  
                         </script>';
    			
    				exit();
    			}
                else
                {
    				echo '<div id="progress"><img src="packages/core/skins/default/images/updating.gif" /> Updating room status to server...</div>';
    				echo '<script>
                				if(window.opener && (window.opener.year || window.opener.night_audit))
                				{
                					window.opener.history.go(0);
                					window.close();
                				}
                				window.setTimeout("location=\''.URL::build_current(array('cmd','id','r_r_id','year','month','day', 'status', 'room_id','layout')).'\'",1000);
    				     </script>';
    				exit();
    			}
    		}
        }
	}
	function draw()
	{
		$this->map = array();
        //start:KID them xu ly lay exchange_rate
        $currency_id = (HOTEL_CURRENCY == 'VND')?'USD':'VND';
		$this->map['exchange_rate'] = DB::fetch('select id,exchange from currency where id=\''.$currency_id.'\'','exchange');
        //end:KID them xu ly lay exchange_rate
		$sql = '
				select
					service.id,service.name
				from
					service
				order by
					service.name
		      ';
		$this->map['services'] = DB::fetch_all($sql);
		$currencies = DB::select_all('currency','allow_payment=1','name');
		foreach($currencies as $key=>$value){
			$currencies[$key]['name'] = ($key=='USD')?'Credit card':$value['name'];
		}
		$this->map['currencies'] = $currencies;
		$sql = '
    			select
    				reservation.*,customer.name as full_name,tour.name as tour_name,reservation.tour_id,reservation.customer_id
    			from
    			 	reservation
    				left outer join customer on customer.id=reservation.customer_id
    				left outer join tour on tour.id=reservation.tour_id
    			where
    				reservation.id = '.Url::iget('id').'
		      ';
		$row = DB::fetch($sql);
		$row['payment'] =  System::display_number($row['payment']);
		$row['cut_of_date'] = Date_Time::convert_orc_date_to_date($row['cut_of_date'],'/');
		foreach($row as $key=>$value)
		{
			if(!isset($_REQUEST[$key]))
			{
				$_REQUEST[$key] = $value;
			}
		}
		if(!isset($_REQUEST['mi_reservation_room']))
		{
			$sql = '
				select
					reservation_room.id as id
					,reservation_room.price
                    ,reservation_room.usd_price
					,reservation_room.status as old_status
					,reservation_room.status
					,reservation_room.adult
					,reservation_room.child
					,reservation_room.time_in
					,reservation_room.time_out
					,reservation_room.arrival_time
					,reservation_room.departure_time
					,reservation_room.total_amount
					,reservation_room.reduce_balance
					,reservation_room.reduce_amount
					,reservation_room.deposit
					,reservation_room.tax_rate ,reservation_room.service_rate
					,reservation_room.room_level_id
					,room_level.brief_name as room_level_name
                    ,room_level.num_people
					,reservation_room.room_id
					,reservation_room.room_id AS room_id_old
					,CASE WHEN room.name is null THEN reservation_room.temp_room ELSE room.name END room_name
					,CASE WHEN room.name is null THEN reservation_room.temp_room ELSE room.name END room_name_old
					,reservation_room.traveller_id
					,reservation_room.reservation_id
                    ,room_status.house_status
					,reservation_room.foc
					,reservation_room.foc_all
                    ,reservation_room.note_change_room
                    ,reservation_room.change_room_from_rr
                    ,reservation_room.change_room_to_rr
					,reservation_room.reservation_type_id
					,reservation_room.confirm
                    ,reservation_room.breakfast
					,reservation_room.closed
					,reservation_room.early_arrival_time
					,reservation_room.verify_dayuse
					,reservation_room.net_price
					,reservation_room.extra_bed
					,to_char(reservation_room.extra_bed_from_date,\'DD/MM/YYYY\') as extra_bed_from_date
					,to_char(reservation_room.extra_bed_to_date,\'DD/MM/YYYY\') as extra_bed_to_date
					,reservation_room.extra_bed_rate
					,reservation_room.baby_cot
					,to_char(reservation_room.baby_cot_from_date,\'DD/MM/YYYY\') as baby_cot_from_date
					,to_char(reservation_room.baby_cot_to_date,\'DD/MM/YYYY\') as baby_cot_to_date
					,reservation_room.baby_cot_rate
					,reservation_room.net_price
					,reservation_room.early_checkin
					,reservation_room.late_checkout
                    ,reservation_room.COMMISSION_RATE
                    ,reservation_room.note
                    ,reservation_room.package_sale_id
                    ,package_sale.name as package_sale_name
				from
					reservation_room
					left outer join room on room.id=reservation_room.room_id
					left outer join room_level on room_level.id=reservation_room.room_level_id
					left outer join room_status on room_status.reservation_room_id=reservation_room.id
					left outer join payment_type on payment_type.id=reservation_room.payment_type_id
                    left outer join package_sale on package_sale.id=reservation_room.package_sale_id
				where
					reservation_room.reservation_id='.Url::iget('id').'
					'.(URL::get('reservation_room_id')?' and reservation_room.id=\''.URL::get('reservation_room_id').'\'':'').'
				order by
					room_level.name,room.name asc';
			$mi_reservation_room = DB::fetch_all($sql);
			$sql_traveller = '
						select
							reservation_traveller.id
							,reservation_traveller.id as reservation_traveller_id
							,reservation_traveller.pa18
							,reservation_traveller.reservation_room_id
							,reservation_room.reservation_id
							,reservation_traveller.status
							,to_char(reservation_traveller.EXPIRE_DATE_OF_VISA,\'DD/MM/YYYY\') as visa_expired
							,traveller.first_name ,traveller.last_name
							,DECODE(traveller.gender,0,\'Nữ\',\'Nam\') as gender,
							to_char(traveller.birth_date,\'DD/MM/YYYY\') as birth_date,
							traveller.passport ,reservation_traveller.visa_number as visa ,reservation_traveller.special_request as note ,
							traveller.phone ,traveller.fax ,traveller.address ,traveller.email
							,country.code_1 as nationality_id
							,country.name_'.Portal::language().' as nationality_name
							,CASE WHEN reservation_room.room_id is not null THEN room.name ELSE reservation_room.temp_room END as mi_traveller_room_name
							,CASE WHEN reservation_room.room_id is not null THEN concat(room.id,concat(\'-\',to_char(reservation_room.departure_time,\'DD/MM/YYYY\'))) ELSE concat(reservation_room.temp_room,concat(\'-\',to_char(reservation_room.departure_time,\'DD/MM/YYYY\'))) END as traveller_room_id
							,DECODE(reservation_room.traveller_id,reservation_traveller.traveller_id,1,0) as traveller_id
							,traveller.id as traveller_id_
							,guest_type.name as traveller_level_name
						from
							reservation_traveller
							inner join traveller on traveller.id=reservation_traveller.traveller_id
							left outer join reservation_room on reservation_room.id=reservation_traveller.reservation_room_id
							left outer join room on reservation_room.room_id=room.id
							left outer join country on traveller.nationality_id=country.id
							LEFT JOIN guest_type ON guest_type.id = traveller.traveller_level_id
						where
							reservation_room.reservation_id='.Url::iget('id').'
						order by
								reservation_traveller.id asc
							';//.(URL::get('reservation_room_id')?' and reservation_room.id=\''.URL::get('reservation_room_id').'\'':'').'
					$mi_travellers = DB::fetch_all($sql_traveller);
            $sql="SELECT
					CONCAT(reservation_room_id,CONCAT('_',in_date)) AS id,
					change_price,
                    reservation_room_id,
                    in_date,room_id,room_status.closed_time
					,room.name as room_name
                    --giap.ln add truong hop reservation co su dung package tien phong 
                    ,room_status.is_package_room
                    --giap.ln 
                    
				FROM
					room_status
					left outer join room ON room.id = room_status.room_id
				WHERE
					reservation_id=".Url::iget('id')." AND status<>'AVAILABLE' AND status<>'CANCEL'
				ORDER BY
					in_date";
			$room_status = DB::fetch_all($sql);
            
			$change_price_arr = array();
			require_once 'packages/hotel/packages/reception/modules/includes/get_reservation.php';
			$reservation_arr = get_reservation(Url::iget('id'),false,true);
			if(isset($_REQUEST['mi_reservation_room']))
            {
				$old_reservation_room = $_REQUEST['mi_reservation_room'];
			}
			$change_all_net_price = 1;
			foreach($mi_reservation_room as $key=>$value)
			{
			    if($value['room_id'] != ''){
    			    //if(DB::fetch('select * from room_status where (status = \'OCCUPIED\' or status = \'AVAILABLE\') AND house_status = \'DIRTY\' AND room_id = '.$value['room_id'].' AND TO_CHAR(in_date,\'ddmmyyyy\') = \''.date('dmY').'\''))
                    if(DB::fetch('select * from room_status where status<>\'CANCEL\' and house_status = \'DIRTY\' AND room_id = '.$value['room_id'].' AND TO_CHAR(in_date,\'ddmmyyyy\') = \''.date('dmY').'\''))
                    {
                        $mi_reservation_room[$key]['house_status'] = 'dirty';
                    }
                }
				if($value['net_price']==0)
                {
					$change_all_net_price = 0;
				}
				$mi_reservation_room[$key]['time_in'] = date('H:i',$value['time_in']);
				$mi_reservation_room[$key]['time_out'] = date('H:i',$value['time_out']);
				$mi_reservation_room[$key]['time_in_in'] = $value['time_in'];
				$mi_reservation_room[$key]['time_out_out'] = $value['time_out'];
                
				$mi_reservation_room[$key]['arrival_time'] = Date_Time::convert_orc_date_to_date($value['arrival_time'],'/');
                //echo $mi_reservation_room[$key]['arrival_time'];
				$mi_reservation_room[$key]['early_arrival_time'] = Date_Time::convert_orc_date_to_date($value['early_arrival_time'],'/');
				$mi_reservation_room[$key]['departure_time'] = Date_Time::convert_orc_date_to_date($value['departure_time'],'/');
				$mi_reservation_room[$key]['departure_time_old'] = $mi_reservation_room[$key]['departure_time'];
				$mi_reservation_room[$key]['reduce_amount'] = System::display_number($value['reduce_amount']);
				$mi_reservation_room[$key]['extra_bed_rate'] = System::display_number($value['extra_bed_rate']);
				$mi_reservation_room[$key]['baby_cot_rate'] = System::display_number($value['baby_cot_rate']);
				$mi_reservation_room[$key]['price'] = System::display_number($value['price']);
                $mi_reservation_room[$key]['usd_price'] = System::display_number($value['usd_price']);
				$mi_reservation_room[$key]['adult'] = $value['adult']?$value['adult']:$value['num_people'];
				$mi_reservation_room[$key]['child'] = $value['child'];
				$mi_reservation_room[$key]['total_amount'] = System::display_number($value['total_amount']);
				$mi_reservation_room[$key]['reduce_balance'] = System::display_number($value['reduce_balance']);
				$mi_reservation_room[$key]['deposit'] = $value['deposit'];
                $mi_reservation_room[$key]['note_change_room'] = $value['note_change_room'];
				if(isset($reservation_arr['items'][$key]))
                {
					$mi_reservation_room[$key]['check_payment'] = $reservation_arr['items'][$key]['check_payment'];
				}
				$change_price_arr = array();
				$change_price_closed_time_arr = array();
				foreach($room_status as $k=>$v)
                {
					if($v['reservation_room_id'] == $value['id'])
                    {
						if($v['in_date']<>$value['departure_time'] or (Date_Time::convert_orc_date_to_date($v['in_date'],'/')==Date_Time::convert_orc_date_to_date($value['departure_time'],'/') and Date_Time::convert_orc_date_to_date($value['arrival_time'],'/')==Date_Time::convert_orc_date_to_date($value['departure_time'],'/')))
                        {
							$change_price_arr[Date_Time::convert_orc_date_to_date($v['in_date'],'/')] = $v['change_price'];
							$change_price_arr[Date_Time::convert_orc_date_to_date($v['in_date'],'/').'_room_name'] = $v['room_name'];
                            $change_price_arr[Date_Time::convert_orc_date_to_date($v['in_date'],'/').'_is_package_room'] = $v['is_package_room'];
						}
						$change_price_closed_time_arr[Date_Time::convert_orc_date_to_date($v['in_date'],'/')] = $v['closed_time'];
					}
				}
				$mi_reservation_room[$key]['change_price_arr'] = $change_price_arr;
				$mi_reservation_room[$key]['change_price_closed_time_arr'] = $change_price_closed_time_arr;
				$pay_by_currency_arr = DB::fetch_all('SELECT currency_id as id,amount,bill_id FROM pay_by_currency WHERE bill_id = '.$key.' AND type=\'RESERVATION\'');
				foreach($pay_by_currency_arr as $k=>$v){
					$pay_by_currency_arr[$k]['amount'] = System::display_number($v['amount']);
				}
				$mi_reservation_room[$key]['currency_arr'] = $pay_by_currency_arr;
				$mi_reservation_room[$key]['service_arr'] = DB::fetch_all('SELECT service_id as id,amount,reservation_room_id FROM reservation_room_service WHERE reservation_room_id='.$key.' ORDER BY type');
				//
				$first = true;
				$last = false;
				$stt = 0;
				foreach($mi_travellers as $t =>$traveller){
					if($traveller['reservation_room_id'] == $key && ($traveller['status']=='CHECKIN' or ($traveller['status']=='CHECKOUT' and User::can_admin(false,ANY_CATEGORY)) )){
						if($first)
						{
							$mi_reservation_room[$key]['list_traveller'] = '<fieldset class="traveller_compact"><legend class="sub-title">'.Portal::language('traveller').' - '.$traveller['mi_traveller_room_name'].':</legend>';
							$mi_reservation_room[$key]['list_traveller'] .= '<table width="100%" class="tbl_traveller_compact" cellpadding="2" border="1" bordercolor="#CCCCCC" style="border-collapse:collapse;"><tr bgcolor="#f0f0f0" style="font-size:11px;">';
							$mi_reservation_room[$key]['list_traveller'] .= '<th width="20" align="center">'.Portal::language('stt').'</th>';
							$mi_reservation_room[$key]['list_traveller'] .= '<th width="150">'.Portal::language('full_name').'</th>';
							$mi_reservation_room[$key]['list_traveller'] .= '<th width="80">'.Portal::language('passpost').'</th>';
							$mi_reservation_room[$key]['list_traveller'] .= '<th width="80">'.Portal::language('visa').'</th>';
							$mi_reservation_room[$key]['list_traveller'] .= '<th width="80">'.Portal::language('visa_expired').'</th>';
							$mi_reservation_room[$key]['list_traveller'] .= '<th width="50">'.Portal::language('gender').'</th>';
							$mi_reservation_room[$key]['list_traveller'] .= '<th width="80">'.Portal::language('birth_date').'</th>';
							$mi_reservation_room[$key]['list_traveller'] .= '<th width="150">'.Portal::language('nationaltity').'</th>';
							$mi_reservation_room[$key]['list_traveller'] .= '<th>'.Portal::language('traveller_level').'</th><th width="70"></th></tr>';
						}
						$traveller_old_id = $t;
						$stt++;
						$first = false;
						$mi_reservation_room[$key]['list_traveller'].= '<tr bgcolor="#FFFFFF" style="height:20px;"><td align="center">'.$stt.'.</td>';
						$mi_reservation_room[$key]['list_traveller'].= '<td><a target="_blank" href="'.Url::build('traveller',array('id'=>$traveller['traveller_id_'])).'">'.$traveller['first_name'].' '.$traveller['last_name'].'</a></td>';
						$mi_reservation_room[$key]['list_traveller'].= '<td>'.$traveller['passport'].'</td>';
						$mi_reservation_room[$key]['list_traveller'].= '<td>'.$traveller['visa'].'</td>';
						$mi_reservation_room[$key]['list_traveller'].= '<td>'.$traveller['visa_expired'].'</td>';
						$mi_reservation_room[$key]['list_traveller'].= '<td>'.$traveller['gender'].'</td>';
						$mi_reservation_room[$key]['list_traveller'].= '<td>'.$traveller['birth_date'].'</td>';
						$mi_reservation_room[$key]['list_traveller'].= '<td>'.$traveller['nationality_name'].'</td>';
						$mi_reservation_room[$key]['list_traveller'].= '<td>'.$traveller['traveller_level_name'].'</td>';
						$mi_reservation_room[$key]['list_traveller'].= '<td><input name="folio_'.$traveller['id'].'" type="button" id="folio_'.$traveller['id'].'" onclick="windowOpenUrlTraveller(\''.$traveller['reservation_room_id'].'\',\''.$traveller['reservation_id'].'\',\''.$traveller['id'].'\');" value="Folio" title="Create folio" class="folio_button"></td>';
						$mi_reservation_room[$key]['list_traveller'].= '<tr>';
					}
				}
				$mi_reservation_room[$key]['count_guest'] = $stt;
				if(isset($mi_reservation_room[$key]['list_traveller']))
				{
					$mi_reservation_room[$key]['list_traveller'].='</fieldset>';
				}
				//
			}
            //giap.ln add key items
           
            if(defined('IS_KEY') && IS_KEY==1)
            {
                $mi_reservation_room = $this->choose_sever_keys($mi_reservation_room);
            }
            //end giap.ln 
            
           foreach($mi_reservation_room as $k=>$v){
             $mi_reservation_room[$k]['key_1']=$v['id'];
             $mi_reservation_room[$v['room_level_name'].'_'.$v['room_name'].'_'.$v['id']]=$v;
             unset($mi_reservation_room[$k]);
            }
            
			$_REQUEST['mi_reservation_room'] = $mi_reservation_room;
            //System::debug($mi_reservation_room)
			if($change_all_net_price == 1){
				$_REQUEST['change_all_net_price'] = 1;
			}
		}
        
		if($row['customer_id'])
		{
			$_REQUEST['customer_name'] = DB::fetch('select name from customer where id=\''.$row['customer_id'].'\'','name');
			$_REQUEST['customer_id'] = $row['customer_id'];
		}
		$this->map += $row;
		$this->map +=array(
			'payment_types'=>DB::fetch_all('select def_code as id, name_'.Portal::language().' as name from payment_type where '.IDStructure::direct_child_cond(ID_ROOT).' order by name'),
			'nationalities'=>DB::fetch_all('select code_1 as id, name_'.Portal::language().' as name from country where 1=1 order by name_'.Portal::language().''),
			'vip_card_list'=>array(''=>'')+String::get_list(DB::fetch_all('select code as id,discount_percent as name from vip_card order by code')),
			);
		$holidays = DB::fetch_all('select id,name,charge,in_date from holiday');
		$holiday = array();
		foreach($holidays as $key=>$value){
			$k = Date_Time::convert_orc_date_to_date($value['in_date'],'/');
			$holiday[$k]['id'] = $k;
			$holiday[$k]['name'] = $value['name'];
			$holiday[$k]['charge'] = $value['charge'];
		}
		$this->map['holidays'] = String::array2js($holiday);
		$reservation_types = DB::fetch_all('select id,name from reservation_type order by position');
		$reservation_type_options = '';
		foreach($reservation_types as $key=>$value){
			$reservation_type_options .= '<option value="'.$key.'">'.$value['name'].'</option>';
		}
		$this->map['reservation_type_options'] = $reservation_type_options;
        
        //START-DAT BỔ SUNG PHẦN HOA HỒNG TẤT CẢ
        
        $start_date = 0;
        $end_date = 0;
        if(isset($_REQUEST['mi_reservation_room']))
		{
		     foreach($_REQUEST['mi_reservation_room'] as $key_add=>$value_add){
                $_REQUEST['mi_reservation_room'][$key_add]['time_in_in'] = Date_Time::to_time($value_add['arrival_time']);
                $_REQUEST['mi_reservation_room'][$key_add]['time_out_out'] = Date_Time::to_time($value_add['departure_time']);
		     }
              
            foreach($_REQUEST['mi_reservation_room'] as $key => $value)
            {
                if(!$start_date)
                    $start_date = $value['time_in_in'];
                if(!$start_date or $start_date > $value['time_in_in'])
                    $start_date = $value['time_in_in'];
                
                if(!$end_date)
                    $end_date = $value['time_out_out'];
                if(!$end_date or $end_date < $value['time_out_out'])
                    $end_date = $value['time_out_out'];
                
                /** manh them luoc do gia extra_bed **/
                if(isset($value['extra_bed']) AND $value['extra_bed']==1)
                {
                    $extra_bed_detail = DB::fetch_all("SELECT 
                                                        extra_service_invoice_detail.id
                                                        ,extra_service_invoice_detail.in_date
                                                        ,extra_service_invoice_detail.price
                                                        FROM 
                                                            extra_service_invoice_detail
                                                            inner join extra_service_invoice on extra_service_invoice.id=extra_service_invoice_detail.invoice_id
                                                            inner join reservation_room on reservation_room.id=extra_service_invoice.reservation_room_id
                                                        WHERE
                                                            reservation_room.id=".$value['id']."
                                                            AND extra_service_invoice.use_extra_bed=1
                                                        ORDER BY
                                                            extra_service_invoice_detail.in_date
                                                        ");
                    $_REQUEST['mi_reservation_room'][$key]['change_price_extra_bed_arr'] = array();
                    foreach($extra_bed_detail as $id_extra=>$value_extra)
                    {
                        $_REQUEST['mi_reservation_room'][$key]['change_price_extra_bed_arr'][Date_Time::convert_orc_date_to_date($value_extra['in_date'],'/')] = $value_extra['price'];
                    }
                }
                /** end manh **/
            }
            
            $start_date = date('d/m/Y',$start_date);
            $end_date = date('d/m/Y',$end_date);
        }

        //END-DAT BỔ SUNG PHẦN HOA HỒNG TẤT CẢ
        // KID THÊM HOA HỒNG TẤT CẢ
        if($row['customer_id'])
        {
            $commission_rate = DB::fetch_all('select id,commission_rate, start_date, end_date 
                                              from customer_rate_commission crc
                                              where crc.customer_id = '.$row['customer_id'].' and crc.start_date <= \''.Date_time::to_orc_date($start_date).'\' AND crc.end_date >= \''.Date_time::to_orc_date($end_date).'\' 
                                              order by id');
            $commission_rate_options = '';
    		foreach($commission_rate as $key=>$value){
    			$commission_rate_options .= '<option value="'.$value['commission_rate'].'">'.Date_time::convert_orc_date_to_date($value['start_date'],'/').' - '.Date_time::convert_orc_date_to_date($value['end_date'],'/').' : '.$value['commission_rate'].'</option>';
    		}
    		$this->map['commission_rate_options'] = $commission_rate_options;
            
            
        }
		$def_codes_options = '
			<option value="">'.Portal::language('select').'</option>
			<option value="CASH">'.Portal::language('pay_now').'</option>
			<option value="DEBIT">'.Portal::language('debit').'</option>
			<option value="CREDIT_CARD">'.Portal::language('credit_card').'</option>
		';
		$guest_types = DB::fetch_all('select id,name from guest_type order by position');
		$guest_type_options = '<option value="">'.Portal::language('select').'</option>';
		foreach($guest_types as $key=>$value){
			$guest_type_options .= '<option value="'.$key.'">'.$value['name'].'</option>';
		}
        
		$this->map['traveller_level_options'] = $guest_type_options;
		$this->map['def_codes_options'] = $def_codes_options;
		$this->map['verify_dayuse_options'] = '';
		$this->map['verify_dayuse_options'] .= '<option value="">0</option>';
		$this->map['verify_dayuse_options'] .= '<option value="5">+0.5</option>';
		$this->map['verify_dayuse_options'] .= '<option value="10">+1</option>';
        $this->map['payment_type1_list'] = array(''=>'','By company'=>'By company','By the guest'=>'By the guest','Cash'=>'Cash','Credit card'=>'Credit card','Bank transfer'=>'Bank transfer','Travel agency'=>'Travel agency','Other'=>'Other');
		//$this->map['verify_dayuse_options'] .= '<option value="-5">- 0.5</option>';
        //System::debug($this->map);
        //luu nguyen giap add change_room_Status

        $is_change_room_status = DB::fetch_all("select id,change_room_status from account where id='".User::id()."'");
        $_REQUEST['is_change_room_status']  = $is_change_room_status[User::id()]['change_room_status'];
        
        $is_change_checkIn_book = DB::fetch_all("select id,change_checkin_book from account where id='".User::id()."'");
        $_REQUEST['is_change_checkin_book']  = $is_change_checkIn_book[User::id()]['change_checkin_book'];
        //end
        /** manh them danh sach loai phong **/
        $this->map['room_level_options'] = '<option value="ALL">ALL</option>';
        $room_level = DB::fetch_all("SELECT * FROM room_level WHERE portal_id='".PORTAL_ID."'");
        //System::debug($room_level);
        foreach($room_level as $key_level=>$value_level)
        {
            $this->map['room_level_options'] .= '<option value="'.$value_level['brief_name'].'">'.$value_level['brief_name'].'</option>';
        }
        /** end manh **/
	 /** giap.ln them truong hop hien thi danh sach package co trong khoang arrival time **/
        $this->map['package_sale_options'] = '<option value="">--SELECT--</option>';
        
        foreach($_REQUEST['mi_reservation_room'] as $id_r=>$row)
        {
            /** manh them auto_late_checkin_price**/
            if($row['status'] != 'CANCEL')
                $_REQUEST['mi_reservation_room'][$id_r]['auto_late_checkin_price'] = $row['change_price_arr'][$row['arrival_time']];
            /** end manh **/
            $arrival_time = explode("/",$row['arrival_time']);
            //break;
        }
        $arrival_time = mktime(0,0,0,$arrival_time[1],$arrival_time[0],$arrival_time[2]);
        $package_sales = DB::fetch_all("SELECT * FROM package_sale WHERE (DATE_TO_UNIX(start_date)<=$arrival_time AND DATE_TO_UNIX(end_date)>=$arrival_time AND end_date is not null AND start_date is not null ) OR start_date is null OR end_date is null");
        foreach($package_sales as $row)
        {
            $this->map['package_sale_options'] .= '<option value="'.$row['id'].'">'.$row['name'].'</option>';
        }
     /** end giap.ln **/
		$this->parse_layout('edit',$this->map);
	}
	function fix($value){
		//----------------------fix error------------------------------------------
		if(isset($value['traveller_id']) and $value['traveller_id'] and $value['status']=='CHECKIN' or $value['status']=='CHECKOUT'){
			if(!DB::exists('select * from reservation_traveller where traveller_id ='.$value['traveller_id'].' and reservation_room_id='.$value['id'])){
				//echo 'update traveller_id...';
				DB::insert('reservation_traveller',array(
					'traveller_id'=>$value['traveller_id'],
					'reservation_room_id'=>$value['id'],
				));
			}
			$start = Date_Time::to_time(date('d/m/Y',$value['time_in']));
			$end = Date_Time::to_time(date('d/m/Y',$value['time_out']));
			for($i=$start;$i<=$end;$i+=24*3600){
				$status = ($value['status']=='CHECKIN' or $value['status']=='CHECKOUT')?'OCCUPIED':$value['status'];
				$price = ($end==$start)?$value['price']:(($i==$end)?0:$value['price']);
				$sql='select * from room_status where in_date=\''.Date_Time::to_orc_date(date('d/m/Y',$i)).'\' and room_id='.$value['room_id'].' and status=\''.$status.'\'';
				if(!DB::exists($sql)){
					//echo 'update room_status table...';
					DB::insert('room_status',array(
						'in_date'=>Date_Time::to_orc_date(date('d/m/Y',$i)),
						'room_id'=>$value['room_id'],
						'status'=>$status,
						'change_price'=>$price,
						'reservation_id'=>$value['reservation_id']
					));
				}
			}
		}
		//----------------------/fix error------------------------------------------
	}
    //giap.ln add 
    function choose_sever_keys($mi_reservation_room)
    {
        $reservation_arr = array();
        switch(SERVER_KEY)
        {
            //giap.ln add code for Be-tech manage key
            case IS_BETECH:
            {
                $reservation_arr = $this->betech_keys($mi_reservation_room);
                break;
            }  
            //end Be-tech keys
            //add code for Adel manage key
            case IS_ADEL:
            {
                $reservation_arr = $this->adel_keys($mi_reservation_room);
                break;
            }
            //end Adel
            case IS_SALTO:
            {
                $reservation_arr = $this->salto_keys($mi_reservation_room);
                break;
            }
            case IS_HUNERF:
            {
                $reservation_arr = $this->hune_keys($mi_reservation_room);
                break;
            }
            default:
                break;
        }
        return $reservation_arr;
        
    }
    //this function meaning:
    //count key according reservation
    function hune_keys($mi_reservation_room)
    {
        foreach($mi_reservation_room as $key=>$value)
        {
            $sql ='SELECT count(*) as num
                    FROM manage_key_hune k 
                    WHERE k.checkout_user is null AND  k.reservation_room_id='.$mi_reservation_room[$key]['id'];
            //chinh la reservation_room_id
            $key_items = DB::fetch($sql);
            
            if(isset($key_items['num']) && $key_items['num']!=0)
                $mi_reservation_room[$key]['total_key'] = $key_items['num'];
            else
                $mi_reservation_room[$key]['total_key'] = '';
        }
        return $mi_reservation_room;
    } 
    function betech_keys($mi_reservation_room)
    {
        foreach($mi_reservation_room as $key=>$value)
        {
            $sql ='SELECT count(*) as num
                    FROM manage_key k 
                    WHERE k.delete_user is null AND  k.reservation_room_id='.$mi_reservation_room[$key]['id'].'
                    AND k.guestsn >=ALL(SELECT key.guestsn
                                    FROM manage_key key
                                    WHERE key.delete_user is null AND key.reservation_room_id='.$mi_reservation_room[$key]['id'].')';
            
            //chinh la reservation_room_id
            $key_items = DB::fetch($sql);
            
            if(isset($key_items['num']) && $key_items['num']!=0)
                $mi_reservation_room[$key]['total_key'] = $key_items['num'];
            else
                $mi_reservation_room[$key]['total_key'] = '';
        }
        return $mi_reservation_room;
    }
    
    function adel_keys($mi_reservation_room)
    {
        foreach($mi_reservation_room as $key=>$value)
        {
            $sql ='SELECT count(*) as num
                    FROM manage_key_adel k 
                    WHERE k.delete_user is null AND  k.reservation_room_id='.$mi_reservation_room[$key]['id'];
            
            //chinh la reservation_room_id
            $key_items = DB::fetch($sql);
            
            if(isset($key_items['num']) && $key_items['num']!=0)
            {
                $_REQUEST['is_late'] = 1;
                $mi_reservation_room[$key]['total_key'] = $key_items['num'];
            }
            else
            {
                $_REQUEST['is_late'] = 0;
                $mi_reservation_room[$key]['total_key'] = '';
            }
                
        }
        return $mi_reservation_room;
    }
    function get_html_keys($traveller_id,$reservation_room_id)
    {
        $html ='';
        if(IS_KEY)
        {
            switch(SEVER_KEY)
            {
                case IS_ADEL:
                {
                    $url = '?page=manager_key_adel&cmd=create&resevation_room_id='.$reservation_room_id.'&trav_id='.$traveller_id.'&portal='.PORTAL_ID;
                    $html.='<span class="multi-input" style="width:15px;float:left;margin-left:5px;">';
                    $html .='<img id="traveller_key_'.$traveller_id.'" src="skins/default/images/key.gif" title="Tạo khóa từ" target="_blank" onclick="window.open(\''.$url.'\')" />';    
                    $html .='</span>';
                    break;
                }
                default:
                    break;
            } 
        }
        return $html;
    }
    function salto_keys($mi_reservation_room)
    {
        foreach($mi_reservation_room as $key=>$value)
        {
            $sql ="SELECT sum(number_keys) as num
                FROM manage_key_salto
                WHERE reservation_room_id=".$mi_reservation_room[$key]['id'];
            
            //chinh la reservation_room_id
            $key_items = DB::fetch($sql);
            
            if(isset($key_items['num']) && $key_items['num']!=0)
                $mi_reservation_room[$key]['total_key'] = $key_items['num'];
            else
                $mi_reservation_room[$key]['total_key'] = '';
        }
        return $mi_reservation_room;
    }
    //giap.ln end
}
?>