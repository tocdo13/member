<?php
class CreateGroupFolioForm extends Form
{
	function CreateGroupFolioForm()
	{
		Form::Form('CreateGroupFolioForm');
		$this->link_css("packages/hotel/skins/default/css/invoice.css");
		$this->link_js('packages/hotel/packages/reception/modules/includes/jquery.windows-engine.js');
        $this->link_js('packages/hotel/packages/reception/modules/includes/common01.js');
		$this->link_css("packages/hotel/skins/default/css/jquery.windows-engine.css");
        $this->link_css('packages/hotel/skins/default/css/w3c.css');
        $this->link_css('packages/hotel/skins/default/css/font-awesome-4.7.0/css/font-awesome.min.css');
		//$this->add('customer_id',new IDType(true,'customer_id_not_exitst','customer_id'));
		$this->link_js('cache/data/'.str_replace('#','',PORTAL_ID).'/list_traveller_folio.js?v='.time()); 	
    }
	function on_submit()
    {
        if(Url::get('folio_id'))
        {
            $this->log_delete_folio($_REQUEST,Url::get('folio_id'));
        }else/** Daund add: check double ban ghi traveller folio & xoa ten khach */
        {
            $check = true;
            if(isset($_REQUEST['traveller_folio']) && !empty($_REQUEST['traveller_folio']))
            {
                foreach($_REQUEST['traveller_folio'] as $key => $value)
                {
                    $amount = DB::fetch('SELECT sum(amount) as amount FROM traveller_folio WHERE invoice_id=\''.$value['id'].'\' AND type=\''.$value['type'].'\' AND reservation_room_id = \''.$value['rr_id'].'\' AND date_use =\''.Date_Time::to_orc_date($value['full_date']).'\'','amount');
                    //System::debug($value);exit();
                    if((System::calculate_number($_REQUEST['total_'.$value['type'].'_'.$value['rr_id']]) - ((System::calculate_number($value['amount']) + $amount)) <= 0) && $amount && $value['percent']==100)
                    {
                        $check = false;                    
                    }              
                }
            }
            //System::debug($check);
            if($check == false)
            {
                $this->error('', ' Recode nay da duoc tao hoa don truoc do, vui long kiem tra lai. Xin cam on!');
                return false;
            } 
            /** check xoa ten khach */           
            $check_traveller = 1;
            if(isset($_REQUEST['travellers_id']) && $_REQUEST['travellers_id'] != 0)
            {
                if(!DB::exists('SELECT reservation_traveller.* FROM reservation_traveller inner join reservation_room on reservation_traveller.reservation_room_id = reservation_room.id inner join reservation on reservation_room.reservation_id = reservation.id WHERE reservation.id=\''.$_REQUEST['id'].'\' AND reservation_traveller.id = \''.$_REQUEST['travellers_id'].'\''))
                {
                    $check_traveller = 0;                    
                } 
            } 
            //System::debug($check_traveller);exit();
            if($check_traveller == 0)
            {
                $this->error('', ' Ten khach nay da duoc xoa truoc do, vui long kiem tra lai. Xin cam on!');
                return false;
            }          
        }/** End */
        //System::debug($_REQUEST); exit();
/*--------------------------send mail--------------------------*/  
        if(file_exists('cache/portal/default/config/config_email.php'))
        {
            require_once ('cache/portal/default/config/config_email.php');
        }      
        if(Url::get('folio_id')!='' && ROOM_INVOICE=='1')
        {
            $array_folio_before = DB::fetch('select * from folio where id='.Url::get('folio_id'));
                unset($array_folio_before['member_code']);
                unset($array_folio_before['member_level_id']);
                unset($array_folio_before['create_member_date']);
                unset($array_folio_before['user_id']);
                unset($array_folio_before['check_send_mail']);
                unset($array_folio_before['lastest_edit_time']);
                unset($array_folio_before['create_time']);
            $array_folio_traveller_before = DB::fetch_all('select * from traveller_folio where folio_id='.Url::get('folio_id'));           
        }
/*--------------------------send mail--------------------------*/        
		if(Url::get('customer_id') && Url::get('action')==1 && Url::get('id')){
		      
              /** Manh khoi tao log folio **/
                $type = '';
                $title = '';
                $description = '';
                /** end khoi tao log **/
			$split_invoices = array();
			$invoice_old = array();
			$id=Url::get('id');	
			$customer_id = Url::get('customer_id');
            /** manh log **/
            $customer = DB::fetch("SELECT def_name as name FROM customer WHERE customer.id=".Url::get('customer_id'));
            $description_customer = "<p>Tên Công ty: ".$customer['name']."</p>";
            /** end manh **/
			if($customer_id == '')
            {
				echo "<script>";
				echo "alert('Customer id is not exitst! Can not create folio.');";	
				echo "</script>";
				return;
			}
			$t= 2;$total_amount=0;
			if($id != '' and $customer_id != '')
            {
				if(Url::get('folio_id'))
                {
					$folio_id = Url::get('folio_id');
					$invoice_old = $this->get_traveller_folio($id,$customer_id,$folio_id);
				}
			}
			$split_invoices = Url::get('traveller_folio');
			//--------------------------------------Thuc hien them moi hoa don tach-----------------------------------//
            if(!empty($split_invoices))
            {
                /** Manh Log **/
                $detail_description = "<h3>Chi tiết</h3><hr />";
                /** end manh **/
				foreach($split_invoices as $key => $split)
                {
					/** THL**/
					$split_invoices[$key]['amount'] = System::calculate_number($split['amount'])/(1+$split['service_rate']/100)/(1+$split['tax_rate']/100);
					$split_invoices[$key]['total_amount'] = System::calculate_number($split['amount']); 
					/** THL**/
					$split_invoices[$key]['reservation_id'] =$id;
					$split_invoices[$key]['reservation_room_id'] = $split['rr_id'];
					$split_invoices[$key]['reservation_traveller_id'] =$customer_id;
					$split_invoices[$key]['add_payment'] = 2;	
					$split_invoices[$key]['invoice_id'] = $split['id'];
					$split_invoices[$key]['foc'] =  Url::get('foc_'.$split['rr_id'])?Url::get('foc_'.$split['rr_id']):'';
					$split_invoices[$key]['foc_all'] = (Url::get('foc_all_'.$split['rr_id'])==1)?Url::get('foc_all_'.$split['rr_id']):0;
					$split_invoices[$key]['id'] = $customer_id.'_'.$split['type'].'_'.$split_invoices[$key]['invoice_id'].'_'.$t;
				    /** Manh log  **/
                    $detail_description .= "<p>"." Loại: ".$split['type']." Chi tiết: ".$split['description']." Ngày: ".$split['full_date']." Số tiền: ".$split['amount']." Phí dịch vụ ".$split['service_rate']." Thuế: ".$split['tax_rate']."</p>";
                    /** end Manh **/
                }	
				//System::Debug($split_invoices); exit();
						// Tiền phòng của từng phòng đã bao gồm phí dịch vụ và thuế
				$total_amount = System::calculate_number(trim(Url::get('total_amount')));
				$total_payment = System::calculate_number(trim(Url::get('total_payment')));
				$service_amount = System::calculate_number(trim(Url::get('service_charge_amount')));
				$tax_amount = System::calculate_number(trim(Url::get('tax_amount')));
				$number='';
				if(Url::get('folio_id'))
                {
                    /** truong hop edit log - manh **/
                    $type = "Edit Group Folio";
                    if($folio_code = DB::fetch('select code from folio where id='.Url::get('folio_id'),'code')){
                        $title = "Edit Group Folio id: #No.F".str_pad($folio_code,6,"0",STR_PAD_LEFT)."";
                        $description = "<p>Mã hóa đơn: No.F".str_pad($folio_code,6,"0",STR_PAD_LEFT)."</p><hr />";
                    }else{
                        $title = "Edit Group Folio id: #Ref".str_pad(Url::get('folio_id'),6,"0",STR_PAD_LEFT)."";
                        $description = "<p>Mã hóa đơn: Ref".str_pad(Url::get('folio_id'),6,"0",STR_PAD_LEFT)."</p><hr />";
                    }
                    $description .= $description_customer;
                    $description .= $detail_description;
                    /** end manh **/
                    $member_code = Url::get("member_code")?Url::get("member_code"):"";
                    $member_level_id = Url::get("member_level_id")?Url::get("member_level_id"):"";
                    $create_member_date = Url::get("create_member_date")?Url::get("create_member_date"):"";
					$folio_id = Url::get('folio_id');
					DB::update('folio',array('last_time'=>time(),'lastest_user_id'=>User::id(),'lastest_edit_time'=>time(),'member_code'=>$member_code,'member_level_id'=>$member_level_id,'create_member_date'=>$create_member_date,'total'=>$total_payment,'tax_amount'=>$tax_amount,'reservation_traveller_id'=>Url::get('traveller_id')?Url::get('traveller_id'):'','service_amount'=>$service_amount,'user_id'=>Session::get('user_id')),' id = '.Url::get('folio_id').'');	
				    /**  Manh log **/
                    $log_id = System::log($type,$title,$description,str_pad(Url::get('folio_id'),6,"0",STR_PAD_LEFT)) ; 
                    System::history_log('RECODE',$id,$log_id);
                    System::history_log('FOLIO',Url::get('folio_id'),$log_id);
                    /** end manh **/
                }
                else
                {
					$number = DB::fetch('select max(num) as num from folio where customer_id = '.$customer_id.' AND reservation_id='.$id.'','num');
					if($number && $number!='')
                    {
						$number = $number+1;
					}
                    else
                    {
						$number =1;
					}
                    $member_code = Url::get("member_code")?Url::get("member_code"):"";
                    $member_level_id = Url::get("member_level_id")?Url::get("member_level_id"):"";
                    $create_member_date = Url::get("create_member_date")?Url::get("create_member_date"):"";
					$folio_id = DB::insert('folio',array('last_time'=>time(),'lastest_user_id'=>User::id(),'customer_id'=>$customer_id,'member_code'=>$member_code,'member_level_id'=>$member_level_id,'create_member_date'=>$create_member_date,'total'=>$total_payment,'reservation_traveller_id'=>Url::get('traveller_id')?Url::get('traveller_id'):'','create_time'=>time(),'portal_id'=>''.PORTAL_ID.'','num'=>$number,'tax_amount'=>$tax_amount,'service_amount'=>$service_amount,'reservation_id'=>$id,'user_id'=>Session::get('user_id'),'check_edit'=>'1'));	    
				    //start:KID them update reservation_traveller de kiem tra is_folio
                    if(Url::get('traveller_id'))
                    {
                        DB::update('reservation_traveller',array('is_folio'=>1),'id='.Url::get('traveller_id'));
                    }
                    //end:KID them update reservation_traveller de kiem tra is_folio
                    /** truong hop add log - manh **/
                    $type = "ADD Group Folio";
                    $title = "ADD Group Folio id: Ref#".str_pad($folio_id,6,"0",STR_PAD_LEFT)."";
                    $description = "<p>Mã hóa đơn: Ref".str_pad($folio_id,6,"0",STR_PAD_LEFT)."</p><hr />";
                    $description .= $description_customer;
                    $description .= $detail_description;
                    $log_id = System::log($type,$title,$description,str_pad($folio_id,6,"0",STR_PAD_LEFT));
                    System::history_log('RECODE',$id,$log_id);
                    System::history_log('FOLIO',$folio_id,$log_id);
                    /** end manh **/
                }
				if(!empty($invoice_old))
                {
					foreach($invoice_old as $k => $old)
                    {
						if(!isset($split_invoices[$old['id']]))
                        {
                            $traveller_folio = DB::fetch("SELECT * from traveller_folio where id=".$old['traveller_folio_id']);
							DB::delete('traveller_folio','id='.$old['traveller_folio_id'].'');	
						}
					}
				}
				foreach($split_invoices as $key => $value)
                {
					$value['folio_id'] = $folio_id;
					unset($value['rr_id']);
                    if($value['date']!='')
                    {
					   $value['date_use'] = Date_Time::to_orc_date($value['full_date']);
                    }
                    else
                    {
                        $value['date_use'] = '';
                    }                  
					unset($value['date']); 
                    unset($value['full_date']);
                    
                    if($value['type']=='DEPOSIT_GROUP')
                    {
                        unset($value['reservation_room_id']);
                    }
					if(isset($invoice_old[$key]) and $invoice_old[$key]['id'] == $value['id'])
                    {
						unset($value['id']);
						DB::update('traveller_folio',$value,'id='.$invoice_old[$key]['traveller_folio_id'].'');	
					}
                    else
                    {//($invoice_old[$key]['invoice_id'] == ''){
						unset($value['id']);
						DB::insert('traveller_folio',$value);	
					}
				}
			}
            else
            {
				if(Url::get('folio_id'))
                {
					DB::delete('folio',' id = '.Url::get('folio_id').'');
					DB::delete('traveller_folio',' folio_id = '.Url::get('folio_id').' AND add_payment=2 AND reservation_traveller_id ='.$customer_id.'');
                    DB::delete('payment',' payment.type=\'RESERVATION\' AND payment.folio_id = '.Url::get('folio_id').'');
                    //start:KID them update reservation_traveller de kiem tra is_folio
                    if(Url::get('traveller_id'))
                    {
                        DB::update('reservation_traveller',array('is_folio'=>0),'id='.Url::get('traveller_id'));
                    }
                    //end:KID them update reservation_traveller de kiem tra is_folio
				}
			}
/*--------------------------send mail--------------------------*/            
            if(Url::get('folio_id')!='' && ROOM_INVOICE=='1')
            {
                $array_folio_after = DB::fetch('select * from folio where id='.Url::get('folio_id'));
                    unset($array_folio_after['member_code']);
                    unset($array_folio_after['member_level_id']);
                    unset($array_folio_after['create_member_date']);
                    unset($array_folio_after['user_id']);
                    unset($array_folio_after['check_send_mail']);
                    unset($array_folio_after['lastest_edit_time']);
                    unset($array_folio_after['create_time']);   
                $array_folio_traveller_after = DB::fetch_all('select * from traveller_folio where folio_id='.Url::get('folio_id'));
                                
                if(!($array_folio_before==$array_folio_after && $array_folio_traveller_before==$array_folio_traveller_after))
                    DB::update('folio',array('check_edit'=>'1','last_time'=>time(),'lastest_user_id'=>User::id()),'id='.Url::get('folio_id')); 
            }
/*--------------------------send mail--------------------------*/            
			if(Url::get('act')=='payment')
            {// Save và th?c hi?n thanh toán
                    $create_time = DB::fetch("SELECT create_time FROM folio WHERE id=".$folio_id,"create_time");
                    $get_member_code = Url::get('member_code')?Url::get('member_code'):"";
                    $tt = 'form.php?block_id=428&cmd=payment&id='.Url::get('id').'&member_code='.$get_member_code.(Url::get('fast')?'&fast=1':'').'&create_time_bill='.$create_time.'&customer_id='.$customer_id.'&type=RESERVATION&act=group_folio&total_amount='.System::calculate_number(Url::get('total_payment')).'&folio_id='.$folio_id.'&portal_id='.PORTAL_ID.'';
			}
            else
            {
                if(Url::get('act')=='save_and_print' and isset($folio_id) and $folio_id!=''){
                    $tt = 'form.php?block_id='.Module::block_id().'&cmd=group_folio&customer_id='.$customer_id.'&id='.Url::get('id').'&folio_id='.$folio_id.'&traveller_id='.Url::get('traveller_id').'&check_payment='.(Url::get('check_payment')?Url::get('check_payment'):0).(Url::get('fast')?'&fast=1':'');
                    echo '<script>window.open(\'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/'.Url::$root.'?page=view_traveller_folio&cmd=group_invoice&customer_id='.$customer_id.'&id='.Url::get('id').'&folio_id='.$folio_id.'\');</script>';
                }
                else{
				    $tt = 'form.php?block_id='.Module::block_id().'&cmd=group_folio&id='.Url::get('id').'&customer_id='.$customer_id.(Url::get('fast')?'&fast=1':'');
                }
			}						
			
			echo '<script>window.location.href = \''.$tt.'\' </script>';
            exit();
		}
	}
	function draw()
	{	
		$this->map = array();
		$id = Url::iget('id');
        /** start: KID them de check cac truong hop khong duoc xoa **/
        $traveller_folio_check = array();
        if(Url::get('folio_id'))
        {
            $traveller_folio_check =DB::fetch_all('select traveller_folio.invoice_id as id,
                                            reservation_room.status,
                                            traveller_folio.type
                                            from traveller_folio
                                            inner join reservation_room on reservation_room.id = traveller_folio.reservation_room_id
                                            where 
                                            traveller_folio.type!=\'DEPOSIT_GROUP\' and traveller_folio.reservation_id='.$id
                                            );
            $traveller_folio_check +=DB::fetch_all('select traveller_folio.invoice_id as id,
                                            \' \' as status,
                                            traveller_folio.type
                                            from traveller_folio
                                            where
                                            traveller_folio.type=\'DEPOSIT_GROUP\' and 
                                            traveller_folio.reservation_id='.$id
                                            );
            $res_room = DB::fetch_all('select reservation_room.id from reservation_room where reservation_room.status=\'CHECKIN\' and reservation_room.reservation_id='.$id);
            foreach($traveller_folio_check as $ke =>$val)
            {
                if($val['type']=='DEPOSIT_GROUP')
                {
                    if($res_room)
                    {
                        $val['status'] = 'CHECKIN';
                    }
                    else
                    {
                        $val['status'] = 'CHECKOUT';
                    }
                }
            }
        }
        $this->map['traveller_folio_check'] = String::array2js($traveller_folio_check);
        /** end: KID them de check cac truong hop khong duoc xoa **/
		$reservation_rooms = DB::fetch_all('select 
										reservation_room.id
										,reservation_room.time_in
										,reservation_room.time_out
										,reservation_room.status
										,reservation_room.reservation_id
										,reservation_room.room_id
										,reservation_room.foc
										,reservation_room.foc_all
                                        ,reservation_room.change_room_from_rr
                                        ,reservation_room.change_room_to_rr
										,room.name as room_name
										,reservation.customer_id
										,customer.name as customer_name
										,reservation.deposit as group_deposit
									FROM reservation_room
										 INNER JOIN reservation ON reservation_room.reservation_id = reservation.id
										 LEFT OUTER JOIN customer ON customer.id = reservation.customer_id
										 INNER JOIN room ON reservation_room.room_id = room.id
									WHERE reservation_room.reservation_id = '.$id.'	AND reservation_room.status<>\'CANCEL\'	AND reservation_room.status<>\'NOSHOW\'			
								'); 
		require_once 'packages/hotel/packages/reception/modules/CreateTravellerFolio/get_reservation_room.php';
		$arr_ids=''; $i=0;
		$customer_id = 0;
		if(Url::get('folio_id'))
        {
			$cond = ' AND traveller_folio.folio_id <> '.Url::get('folio_id').'';
		}
        else
        {
			$cond = '';	
		}
		foreach($reservation_rooms as $k => $rr)
        {
			$arr_ids .= (($i==0)?'':',').$k;
			$i++;
		}
        if(empty($arr_ids))
        {
            echo('chưa có phòng không thể tạo folio nhóm');
            exit();
        }
        //start: KID them tinh balance
        $this->map['balance'] = 0;
        if(Url::get('folio_id'))
             $this->map['balance'] += $this->payment(Url::get('folio_id'));
        //end
		$others = DB::fetch_all('SELECT 
										(traveller_folio.folio_id || \'_\' || traveller_folio.type || \'_\' || traveller_folio.invoice_id) as id
										,traveller_folio.type
										,traveller_folio.invoice_id
										,traveller_folio.description
										,traveller_folio.tax_rate
										,traveller_folio.service_rate
										,traveller_folio.amount as amount
                                        ,traveller_folio.total_amount as total_amount
										,traveller_folio.percent as percent 
										,traveller_folio.folio_id
										,folio.reservation_id
										,folio.customer_id
										,folio.reservation_traveller_id as traveller_id
                                        ,folio.code as folio_code -- oanh add
									FROM traveller_folio 
										inner join folio ON folio.id = traveller_folio.folio_id
									WHERE 1>0 AND (traveller_folio.reservation_id='.$id.' OR (traveller_folio.reservation_room_id in ('.$arr_ids.') AND add_payment=1)) '.$cond.'');
		$folio_other = array();
		foreach($others as $k => $val)
        {
			if(isset($folio_other[$val['type'].'_'.$val['invoice_id']]))
            {
				$folio_other[$val['type'].'_'.$val['invoice_id']]['amount'] += $val['amount'];
                $folio_other[$val['type'].'_'.$val['invoice_id']]['total_amount'] += $val['total_amount'];
				$folio_other[$val['type'].'_'.$val['invoice_id']]['percent'] += $val['percent'];
			}
            else
            {
				$folio_other[$val['type'].'_'.$val['invoice_id']]['id'] = $val['invoice_id'];
				$folio_other[$val['type'].'_'.$val['invoice_id']] = $val;
				$folio_other[$val['type'].'_'.$val['invoice_id']]['href'] = '';
			}
			if($val['customer_id'] == '')
            {
				 if(isset($val['folio_code'])){
                    $folio_other[$val['type'].'_'.$val['invoice_id']]['href'] .= '<br><a target="_blank" href="'.'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/'.Url::$root.'?page=view_traveller_folio&folio_id='.$val['folio_id'].'&traveller_id='.$val['traveller_id'].''.'">:No.F'.str_pad($val['folio_code'],6,"0",STR_PAD_LEFT).' - Recode:'.$val['reservation_id'].'</a>';
			     }
                 else
                 {
                    $folio_other[$val['type'].'_'.$val['invoice_id']]['href'] .= '<br><a target="_blank" href="'.'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/'.Url::$root.'?page=view_traveller_folio&folio_id='.$val['folio_id'].'&traveller_id='.$val['traveller_id'].''.'">Ref: '.str_pad($val['folio_id'],6,"0",STR_PAD_LEFT).' - Recode:'.$val['reservation_id'].'</a>';
                                     
                 }                                  
            }
            else
                if(isset($val['folio_code']))
                {      
                    $folio_other[$val['type'].'_'.$val['invoice_id']]['href'] .= '<br><a target="_blank" href="'.'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/'.Url::$root.'?page=view_traveller_folio&cmd=group_invoice&folio_id='.$val['folio_id'].'&customer_id='.$val['customer_id'].''.'">No.F: '.str_pad($val['folio_code'],6,"0",STR_PAD_LEFT).' - Recode:'.$val['reservation_id'].'</a>';
                }
                else
                {
                    $folio_other[$val['type'].'_'.$val['invoice_id']]['href'] .= '<br><a target="_blank" href="'.'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/'.Url::$root.'?page=view_traveller_folio&cmd=group_invoice&folio_id='.$val['folio_id'].'&customer_id='.$val['customer_id'].''.'">Ref_F: '.str_pad($val['folio_id'],6,"0",STR_PAD_LEFT).' - Recode:'.$val['reservation_id'].'</a>';
                }                                         
     
		}	
        
		foreach($reservation_rooms as $k => $rr)
        {
			$customer_id = $rr['customer_id'];
			$customer_name = $rr['customer_name'];
			$reservation_rooms[$k]['room_name'] = Portal::language('room').':'.$rr['room_name'];
			$reservation_rooms[$k]['items'] = get_reservation_room_detail($k,$folio_other);
            
			if($rr['group_deposit']>0)
            {
				$percent = 100;$status = 0;
				$amount =$rr['group_deposit'];
				if(isset($folio_other['DEPOSIT_GROUP_'.$id]))
                {
					if($folio_other['DEPOSIT_GROUP_'.$id]['percent']==100 and $folio_other['DEPOSIT_GROUP_'.$id]['amount'] ==$amount)
                    {
						$status = 1;
					}
                    else
                    {
						$percent = 100 - $folio_other['DEPOSIT_GROUP_'.$id]['percent'];
						$amount = $amount - $folio_other['DEPOSIT_GROUP_'.$id]['amount'];
					}
				}   
                //System::debug($folio_other);
                $items['DEPOSIT_GROUP_'.$id]['net_amount'] = System::display_number($amount);        
				$items['DEPOSIT_GROUP_'.$id]['id'] = $id;
				$items['DEPOSIT_GROUP_'.$id]['type'] = 'DEPOSIT_GROUP';
				$items['DEPOSIT_GROUP_'.$id]['service_rate'] = 0;
				$items['DEPOSIT_GROUP_'.$id]['tax_rate'] = 0;
				$items['DEPOSIT_GROUP_'.$id]['date'] = '';
				$items['DEPOSIT_GROUP_'.$id]['rr_id'] = $id;
				$items['DEPOSIT_GROUP_'.$id]['percent'] = $percent;
				$items['DEPOSIT_GROUP_'.$id]['status'] = $status;
				$items['DEPOSIT_GROUP_'.$id]['amount'] = number_format($amount,2);
				$items['DEPOSIT_GROUP_'.$id]['description'] = Portal::language('deposit_for_group');
				$reservation_rooms['GROUP']['id'] = $id;
				$reservation_rooms['GROUP']['rr_id'] = $id;
				$reservation_rooms['GROUP']['tax_rate'] = 0;
				$reservation_rooms['GROUP']['service_rate'] = 0;
				$reservation_rooms['GROUP']['foc'] = '';
				$reservation_rooms['GROUP']['foc_all'] = 0;
				$reservation_rooms['GROUP']['room_name'] = Portal::language('deposit_for_group');
				//$reservation_rooms['DEPOSIT'][''] = 0;
				$reservation_rooms['GROUP']['items'] = $items;
                $reservation_rooms['GROUP']['change_room_from_rr'] = $rr['change_room_from_rr'];
                $reservation_rooms['GROUP']['change_room_to_rr'] = $rr['change_room_to_rr'];
			}
		}
      //tieu binh                     
        $all_service = array();      
        foreach($reservation_rooms as $val){
            foreach($val['items'] as $v){
                $all_service[$v['type']]= $v['type'];
            }
        }
        
      //end 
		foreach($reservation_rooms as $k => $rr)
        {
			foreach($rr['items'] as $key =>$item)
            {
				if($item['status'] == 1)
                {
					if(isset($folio_other[$key]) && $folio_other[$key]['percent'] == 100)
                    {
						$reservation_rooms[$k]['items'][$key]['href'] = $folio_other[$key]['href'];	
					}
				}
                if ($item['date'] != '')
                {
                    $reservation_rooms[$k]['items'][$key]['date'] = date('d/m', Date_time::to_time($item['date']));
                }
                $reservation_rooms[$k]['items'][$key]['full_date'] = $item['date'];
                
                /** THL **/
                $reservation_rooms[$k]['items'][$key]['amount'] = (number_format(round(System::calculate_number($item['amount'])*(1+$item['service_rate']/100)*(1+$item['tax_rate']/100),2))); //daund lam tron
                $reservation_rooms[$k]['items'][$key]['net_amount'] = $reservation_rooms[$k]['items'][$key]['amount'];
                /** THL **/
			}
		}
                
		$_REQUEST['arr_ids']=$arr_ids;
		if(Url::get('id'))
        {
			$id = Url::get('id');	
		}
		$this->map['traveller_folios_js'] = '{}';
		$_REQUEST['customer_id'] = $customer_id;
        // khach
        // Lay thong tin invoied da thanh toan cho phong khac
		$travel_id='';
        if(Url::get('traveller_id'))
        {
			$travel_id = Url::get('traveller_id');
            $_REQUEST['travellers_id'] = $travel_id;
			$_REQUEST['traveller_id'] = $travel_id;	
		}
        $travellers = $this->get_reservation_traveller($id);
        $_REQUEST['member'] = array();
        foreach($travellers as $tra=>$veller)
        {
            $_REQUEST['member'][$veller['id']]['create_member_date'] = Date_Time::to_orc_date(date('d/m/Y'));
            $member_traveller = DB::fetch("SELECT member_code,member_level_id FROM traveller inner join reservation_traveller on reservation_traveller.traveller_id=traveller.id WHERE reservation_traveller.id=".$veller['id']);
            $_REQUEST['member'][$veller['id']]['member_code'] = isset($member_traveller['member_code'])?$member_traveller['member_code']:'';
            $_REQUEST['member'][$veller['id']]['member_level_id'] = isset($member_traveller['member_level_id'])?$member_traveller['member_level_id']:'';
        }
        $this->map['member_list'] = String::array2js($_REQUEST['member']);
        $traveller_id[0] = '----Traveller----';
		$traveller_id = $traveller_id + String::get_list($travellers);
        $this->map['travellers_id_list'] = $traveller_id;
		if(Url::get('cmd') == 'group_folio' && $customer_id)
        { 
			$m = 0;
			if(Url::get('folio_id'))
            {
				$order_splits = DB::fetch_all('SELECT
													(traveller_folio.type || \'_\' || traveller_folio.invoice_id) as id 
													,traveller_folio.invoice_id
													,traveller_folio.type
													,traveller_folio.date_use
													,traveller_folio.description
													,traveller_folio.amount
													,traveller_folio.percent
													,traveller_folio.reservation_room_id as rr_id
													,traveller_folio.tax_rate
													,traveller_folio.service_rate
                                                    ,reservation_room.status
											FROM traveller_folio
                                                 left join reservation_room on traveller_folio.reservation_room_id = reservation_room.id     
											WHERE 
                                                traveller_folio.reservation_id = '.$id.' 
												AND traveller_folio.folio_id='.Url::get('folio_id').' ');
				foreach($order_splits as $i => $order)
                {
					$order_splits[$i]['id'] = $order['invoice_id'];
					$order_splits[$i]['date'] = date('d/m',Date_Time::to_time(Date_Time::convert_orc_date_to_date($order['date_use'],'/')));
                    $order_splits[$i]['full_date'] = date('d/m/Y',Date_Time::to_time(Date_Time::convert_orc_date_to_date($order['date_use'],'/')));
				}
                /** THL **/
                foreach($order_splits as $key => $value)
                {
                    $order_splits[$key]['amount'] = (number_format($value['amount']*(1+$value['service_rate']/100)*(1+$value['tax_rate']/100),2));
                }
                /** THL **/
				$this->map['traveller_folios_js'] = String::array2js($order_splits);
                /** manh them de lay thong tin thanh vien **/
                $info_member = DB::fetch('SELECT id,member_code,member_level_id,create_member_date FROM folio WHERE id='.Url::get('folio_id').'');
                //System::debug($info_member);
                $_REQUEST['member_code'] = $info_member['member_code'];
                $_REQUEST['member_level_id'] = $info_member['member_level_id'];
                $_REQUEST['create_member_date'] = $info_member['create_member_date'];
                /** end manh **/
 			}
		}
        
        /** START DOI PHONG Dat gom cac khoan cua cac phong chang cu sang phong moi**/
       
        foreach($reservation_rooms as $key => $value)
        {
            if($key!='GROUP' and !$value['change_room_to_rr'] and $value['change_room_from_rr'])
            {
                $reservation_rooms[$key]['room_name'] .= " <span style=\"color:red;\">(".Portal::language('change_room').": ".Portal::language('from')." ";
                $arr_rr_ids = explode(',',$value['change_room_from_rr']);
                foreach($arr_rr_ids as $k => $v)
                {
                    $reservation_rooms[$key]['room_name'] .= $reservation_rooms[$v]['room_name'].">";
                    if(isset($reservation_rooms[$v]))
                    {
                        $reservation_rooms[$key]['items'] += $reservation_rooms[$v]['items'];
                        unset($reservation_rooms[$v]);
                    }
                }
                $reservation_rooms[$key]['room_name'] = substr($reservation_rooms[$key]['room_name'],0,strlen($reservation_rooms[$key]['room_name'])-1);
                $reservation_rooms[$key]['room_name'] .= ")</span>";
            }
        }
        /** END DOI PHONG Dat gom cac khoan cua cac phong chang cu sang phong moi**/
        foreach($reservation_rooms as $key => $value)
        {
            krsort($reservation_rooms[$key]['items']);
        }
		$this->map['folios'] = $reservation_rooms;
         
		$dir_string = 'cache/data/'.str_replace('#','',PORTAL_ID).'';
		if(!is_dir($dir_string)){
            mkdir($dir_string);	
		}
		$str = " var items_js=";
		$str.= String::array2js($reservation_rooms);
		$str.= '';
		$f = fopen($dir_string.'/list_traveller_folio.js','w+');
		fwrite($f,$str);
		fclose($f);
        
		$this->map['folio_other_js'] = String::array2js($folio_other);
        $this->map['customer_name'] = $customer_name;
		$this->map['customer_id'] = $customer_id;
        
        /** manh comment cho nay phuc vu cho viec xem hoa don linh hoat ben quoc hoi **/
        /*
        if(Url::get('traveller_id'))
        {
            $this->map['folio'] = $this->get_folio_traveller_folio_other($id,Url::get('traveller_id')); 
        }
        else
        {
            $this->map['folio'] = $this->get_folio_other($id,$customer_id);
        }
        */
        
        $this->map['folio'] = $this->get_folio_other($id,$customer_id);
        
        /** end manh **/
        
        
        $this->map['all_service']=$all_service;
        $this->map['change_traveller_folio_list'] = array(''=>Portal::language('select_traveller'))+String::get_list(DB::fetch_all('select 
                                                                                                                        reservation_traveller.id,
                                                                                                                        traveller.first_name || \' \' || traveller.last_name || \' - '.Portal::language('room').' \' || room.name  as name 
                                                                                                                    from 
                                                                                                                        reservation_traveller 
                                                                                                                        inner join traveller on reservation_traveller.traveller_id=traveller.id
                                                                                                                        inner join reservation_room on reservation_room.id=reservation_traveller.reservation_room_id
                                                                                                                        inner join room on room.id=reservation_room.room_id
                                                                                                                    where
                                                                                                                        reservation_room.status=\'CHECKIN\'
                                                                                                                        and reservation_room.reservation_id='.$id.'
                                                                                                                        '));
        $list_folio_room_in_recode = DB::fetch_all('select 
                                                        folio.id as id
                                                        ,folio.code as folio_code  
                                                        ,folio.reservation_traveller_id
                                                        ,reservation_traveller.reservation_room_id
                										,CONCAT(traveller.first_name,CONCAT(\' \',traveller.last_name)) as name
                										,CONCAT(folio.reservation_traveller_id,CONCAT(\' \',folio.num)) as code
                										,folio.num
                                                        ,folio.total
                                                        ,room.name as room_name
                                                        ,sum(payment.amount*payment.exchange_rate) as amount 
                    								from 
                    									folio 
                    									inner join reservation_traveller on reservation_traveller.id = folio.reservation_traveller_id
                                                        inner join reservation_room on reservation_room.id=reservation_traveller.reservation_room_id
                                                        inner join room on room.id=reservation_room.room_id
                    									inner join traveller on reservation_traveller.traveller_id = traveller.id
                                                        left join payment ON payment.folio_id = folio.id 
                    								where 
                    									1>0 AND reservation_room.reservation_id = '.$id.' 
                                                        and folio.customer_id is null
                                                   GROUP BY folio.id
                                                         ,folio.code 
                                                         ,room.name
                                                         ,CONCAT(traveller.first_name,CONCAT(\' \',traveller.last_name))
                                                         ,CONCAT(folio.reservation_traveller_id,CONCAT(\' \',folio.num))  
                                                         ,folio.num
                                                         ,folio.total     
                                                         ,folio.reservation_traveller_id
                                                         ,reservation_traveller.reservation_room_id     
                                                ');
        $this->map['list_folio_room_in_recode'] = $list_folio_room_in_recode;
        $this->map['last_time'] = time();
        $this->parse_layout('split_group',$this->map);	
	}
	function get_traveller_folio($id,$customer_id,$folio_id)
    {
		$invoice_old = DB::fetch_all('
			SELECT 
				CONCAT(traveller_folio.reservation_traveller_id,CONCAT(\'_\',CONCAT(traveller_folio.type,CONCAT(\'_\',CONCAT(\'RE\',CONCAT(traveller_folio.invoice_id,CONCAT(\'_\',traveller_folio.add_payment))))))) as id
				,traveller_folio.reservation_traveller_id as customer_id
				,traveller_folio.type,traveller_folio.invoice_id
				,traveller_folio.id as traveller_folio_id 
				,traveller_folio.add_payment 
			FROM 
				traveller_folio 
			WHERE
				reservation_traveller_id = '.$customer_id.'
				AND traveller_folio.reservation_id = '.$id.'
				AND traveller_folio.folio_id='.$folio_id.'');  
		return 	$invoice_old;	  
 	}
	function get_folio_other($reservation_id,$customer_id)
    {
		 $folio = DB::fetch_all('
			select folio.id 
					,customer.name as name
					,CONCAT(folio.customer_id,CONCAT(\' \',folio.num)) as code
					,folio.num
                    ,folio.reservation_traveller_id as traveller_id
                    ,traveller.first_name || \' \' || traveller.last_name as traveller_name
                    ,room.name as room_name
                    ,folio.total
                    ,0 as amount 
                    ,folio.code as folio_code
			from 
				folio 
				inner join reservation on reservation.id = folio.reservation_id 
				inner join customer on reservation.customer_id = customer.id 
                LEFT JOIN reservation_traveller ON folio.reservation_traveller_id = reservation_traveller.id
                LEFT JOIN traveller ON reservation_traveller.traveller_id = traveller.id
                left join reservation_room on reservation.id = reservation_room.reservation_id
                left join room on reservation_room.room_id = room.id
			where 
				folio.reservation_id = '.$reservation_id.' AND folio.customer_id = '.$customer_id.'
            ORDER by
                folio.code,folio.id
                ');	
        $payment = DB::fetch_all('select payment.* from 
                                            payment 
                                            inner join folio ON payment.folio_id = folio.id 
                                            where folio.reservation_id = '.$reservation_id.' 
                                            AND folio.customer_id = '.$customer_id.'');
        foreach($payment as $key=>$value) {
            if(isset($folio[$value['folio_id']])){
                if($value['payment_type_id']!='REFUND'){
                    $folio[$value['folio_id']]['amount'] += $value['amount']*$value['exchange_rate'];
                }else {
                    $folio[$value['folio_id']]['amount'] -= $value['amount']*$value['exchange_rate'];
                }
            }
        }
        return $folio;
	}
    //
    function get_folio_traveller_folio_other($reservation_id,$traveller_id)
    {
            $folio = DB::fetch_all('
			select folio.id
					,customer.name as name
					,CONCAT(folio.customer_id,CONCAT(\' \',folio.num)) as code
					,folio.num
                    ,folio.reservation_traveller_id as traveller_id
                    ,folio.reservation_traveller_id as traveller_id
                    ,traveller.first_name || \' \' || traveller.last_name as traveller_name
                    ,room.name as room_name
                    ,folio.total
                    ,0 as amount    
                    ,folio.code as folio_code                
			from 
				folio 
				inner join reservation on reservation.id = folio.reservation_id 
				inner join customer on reservation.customer_id = customer.id 
                LEFT JOIN reservation_traveller ON folio.reservation_traveller_id = reservation_traveller.id
                LEFT JOIN traveller ON reservation_traveller.traveller_id = traveller.id
                left join reservation_room on reservation.id = reservation_room.reservation_id
                left join room on reservation_room.room_id = room.id
			where 
                folio.reservation_room_id is null and
				folio.reservation_id = '.$reservation_id.' AND folio.reservation_traveller_id = '.$traveller_id.'
            ORDER by
                folio.code,folio.id
                ');	
        $payment = DB::fetch_all('select payment.* from payment inner join folio ON payment.folio_id = folio.id 
                                where
                                    folio.reservation_room_id is null and 
                                    folio.reservation_id = '.$reservation_id.' AND folio.reservation_traveller_id = '.$traveller_id.'');
        foreach($payment as $key=>$value) {
            if(isset($folio[$value['folio_id']])){
                if($value['payment_type_id']!='REFUND'){
                    $folio[$value['folio_id']]['amount'] += $value['amount']*$value['exchange_rate'];
                }else {
                    $folio[$value['folio_id']]['amount'] -= $value['amount']*$value['exchange_rate'];
                }
            }
        }
        return $folio;
	}
    function get_reservation_traveller($id)
    {
		$sql_traveller = 'SELECT 
					reservation_traveller.id
					,room.name || \' \' || traveller.first_name || \' \' || traveller.last_name as name
				FROM 	
					reservation_traveller
					INNER JOIN traveller ON reservation_traveller.traveller_id = traveller.id
					INNER JOIN reservation ON reservation_traveller.reservation_id = reservation.id
                    INNER JOIN reservation_room ON reservation_traveller.reservation_room_id = reservation_room.id
                    LEFT JOIN room ON reservation_room.room_id = room.id
				WHERE 
					reservation.id = '.$id.'
					AND (reservation_traveller.status = \'CHECKIN\' or reservation_traveller.status = \'CHECKOUT\')
					';
		$travellers = DB::fetch_all($sql_traveller);			
		return $travellers;	
	}
    //start: KID them ham nay tinh balance
    function payment($folio_id)
    {
        $payment = DB::fetch('select sum(amount*exchange_rate) as amount from payment where folio_id ='.$folio_id);
        return $payment['amount'];
        
    }
    //end
    function log_delete_folio($data,$folio)
    {
        if(!isset($data['traveller_folio']))
        {
            /** xóa toàn bộ folio **/
            $reservation_id = DB::fetch('select reservation_id from folio where id='.$folio,'reservation_id');
            if($folio_code = DB::fetch('select code from folio where id='.$folio,'code')){
                $log_id = System::log('DELETE','DELETE GROUP FOLIO ID #No.F'.str_pad($folio_code,6,"0",STR_PAD_LEFT),"User id delete: ".User::id()."<br/> Time: ".date('H:i d/m/Y'),$folio);
            }else{
                $log_id = System::log('DELETE','DELETE GROUP FOLIO ID #Ref'.str_pad($folio,6,"0",STR_PAD_LEFT),"User id delete: ".User::id()."<br/> Time: ".date('H:i d/m/Y'),$folio);
            }
            
            System::history_log('RECODE',$reservation_id,$log_id);
            System::history_log('FOLIO',$folio,$log_id);
        }
        else
        {
            $trave_folio_new = $data['traveller_folio'];
            $trave_folio_old = DB::fetch_all("SELECT * from traveller_folio where folio_id=".$folio);
            $description = "User id delete: ".User::id()."<br/> Time: ".date('H:i d/m/Y')."<br/> List traveller folio delete:<br/>";
            $check=false;
            foreach($trave_folio_old as $key=>$value)
            {
                if(!isset($trave_folio_new[$value['invoice_id']."_".$value['type']]))
                {
                    $check=true;
                    $description .= "Type: ".$value['type']." Invoice Id: ".$value['invoice_id']." Date: ".Date_Time::convert_orc_date_to_date($value['date_use'],'/')." Amount: ".System::display_number($value['total_amount'])." Service Rate: ".$value['service_rate']." Tax Rate: ".$value['tax_rate'];
                }
            }
            if($check==true)
            {
                $reservation_id = DB::fetch('select reservation_id from folio where id='.$folio,'reservation_id');
                if($folio_code = DB::fetch('select code from folio where id='.$folio,'code')){
                    $log_id = System::log('DELETE','DELETE TRAVELLER FOLIO ID #No.F'.str_pad($folio_code,6,"0",STR_PAD_LEFT),$description,$folio);
                }else{
                    $log_id = System::log('DELETE','DELETE TRAVELLER FOLIO ID #Ref'.str_pad($folio,6,"0",STR_PAD_LEFT),$description,$folio);
                }
                
                System::history_log('RECODE',$reservation_id,$log_id);
                System::history_log('FOLIO',$folio,$log_id);
            }
        }
    }
}
?>
