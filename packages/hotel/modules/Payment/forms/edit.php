<?php
class EditPaymentForm extends Form{
	function EditPaymentForm(){
		Form::Form('EditPaymentForm');
		$this->link_js('packages/core/includes/js/multi_items.js');
		$this->link_js('packages/hotel/packages/restaurant/includes/js/update_price_new.js');
		$this->link_js('packages/hotel/packages/reception/modules/includes/jquery.windows-engine.js');
        $this->link_js('packages/hotel/packages/reception/modules/includes/common01.js');
		$this->link_css("packages/hotel/skins/default/css/jquery.windows-engine.css");
        $this->link_css('packages/hotel/skins/default/css/w3c.css');
        $this->link_css('packages/hotel/skins/default/css/font-awesome-4.7.0/css/font-awesome.min.css');
		$this->add('payment.amount',new FloatType(false,'invalid_amount','0','100000000000'));
		$this->add('payment.payment_type_id',new TextType(true,'miss_payment_type_id',0,255)); 
	}
	function on_submit()
    {
        require_once 'packages/hotel/includes/member.php';
        /** Oanh add **/
        if(Url::get('folio_id'))
        {
            if(DB::exists('select * from folio where id = '.Url::get('folio_id').' and (code =\'\' or code is null) '))
            {
                $max = DB::fetch('select max(folio.code) as id from folio','id');
                if(!$max)
                {
                    $max = 1;
                }
                else
                {
                    $max++;
                }
                DB::update('folio',array('payment_time'=>time(),'code'=>$max),"id=".Url::get('folio_id'));
            }
         }
        /** End Oanh **/
		if($this->check())
        {
            
			$deposit = 0;
			if(isset($_REQUEST['mi_payment']) and Url::get('type') and Url::get('id') )
            {
				$i=1;
				$ids='0';
				foreach($_REQUEST['mi_payment'] as $key=>$record)
                {
					if($record['id']!='(auto)')
                    {
						$ids .= ($ids=='')?$record['id']:','.$record['id'];
					}
				}
				$cond2 = '';
				if(Url::get('type')=='RESERVATION')
                {
					if(Url::get('cmd')=='deposit')
                    {
                        if(Url::get('act')=='group')
						  $cond2 .= '  AND payment.type_dps =\'GROUP\' and payment.folio_id is null';	
                        else
                          $cond2 .= '  AND payment.type_dps =\'ROOM\' and payment.folio_id is null';	  
					}
                    else if(Url::get('folio_id'))
                    {
						$cond2 .= '  AND payment.folio_id ='.Url::iget('folio_id').'';	
					}
					DB::query('delete from payment where payment.id not in ('.$ids.') AND payment.type=\''.Url::get('type').'\' AND payment.bill_id ='.Url::iget('id').''.$cond2);
				}
                else
                {
					DB::query('delete from payment where payment.id not in ('.$ids.') AND payment.type=\''.Url::get('type').'\' AND payment.bill_id ='.Url::iget('id').'');
				}
                $point = 0;
                $point_user = 0;
                //giap.ln them truong hop ghi lai lan 2 reservation_id la rong
                if(!Url::get('r_id') && Url::get('id'))
                {
                    $rr_object = DB::fetch('SELECT id,reservation_id FROM reservation_room WHERE id='.Url::get('id'));
                    if(empty($rr_object)==false)
                    {
                        $reservation_id= $rr_object['reservation_id'];
                    }
                }
       
                //end 
                
				foreach($_REQUEST['mi_payment'] as $key=>$record)
                {
					if($record['amount']>0)
                    {
						$record['bill_id'] = Url::iget('id');
						$record['type'] = Url::get('type');
						$record['portal_id'] = PORTAL_ID;
						$record['amount'] = System::calculate_number($record['amount']);
						if(ALLOW_CREDIT_CARD_TYPE==1)
                        {
							$record['bank_fee'] = System::calculate_number($record['bank_fee']) - $record['amount'];
						}
                        else
                        {
							$record['bank_fee'] = 0;	
						}
						unset($record['paid']);
						//TH thanh toán
						if(Url::get('traveller_id'))
                        {
							$record['reservation_room_id'] = Url::iget('id')?Url::iget('id'):'';	
							$record['reservation_id'] = Url::iget('r_id')?Url::iget('r_id'):$reservation_id;//giap.ln add
							$record['reservation_traveller_id'] = Url::get('traveller_id');
						}
                        else if(Url::get('customer_id'))
                        {
							$record['reservation_id'] =Url::iget('id')?Url::iget('id'):'';	
							$record['customer_id'] = Url::get('customer_id');	
						}//end
						//TH đặt cọc
						if(Url::get('act')=='group')
                        {
							$record['type_dps'] = 'GROUP';
							$record['reservation_id'] = Url::iget('id')?Url::iget('id'):'';	
						}
                        else if(Url::get('act')=='traveller')
                        {
							$record['type_dps'] = 'ROOM';
							$record['reservation_room_id'] = Url::iget('id')?Url::iget('id'):'';	
							$record['reservation_id'] =Url::iget('r_id')?Url::iget('r_id'):$reservation_id;	
						}
						//end
						if(Url::get('folio_id'))
                        {
							$record['folio_id']=Url::get('folio_id');
						}
						if(Url::get('cmd')=='deposit')
                        {
							$record['description']=($record['description']=='')?(' Đặt cọc lần '.$i):$record['description'];
							$i++;
							if(Url::get('act') || (Url::get('cmd')=='deposit' && Url::get('type')=='BAR'))
                            {
								//$deposit += round($record['amount'] * $record['exchange_rate'],2);
							}
                            if(Url::get('act') || (Url::get('cmd')=='deposit' && Url::get('type')=='KARAOKE'))
                            {
								//$deposit += round($record['amount'] * $record['exchange_rate'],2);
							}
							if((Url::get('act') || (Url::get('cmd')=='deposit')) && $record['amount']>0)
                            {
								if(HOTEL_CURRENCY == 'USD')
                                {
									$deposit += round($record['amount'] / $record['exchange_rate'],2);
                                    //echo 	
								}
                                else
                                {
									$deposit += round($record['amount'] * $record['exchange_rate'],2);
								}
							}
						}
						if(Url::get('cmd')=='deposit' && Url::get('type')=='BAR')
                        {
							$record['type_dps'] = 'BAR';	
						}
                        if(Url::get('cmd')=='deposit' && Url::get('type')=='KARAOKE')
                        {
							$record['type_dps'] = 'KARAOKE';	
						}
                        //dat coc cho ban ve
                        if(Url::get('cmd')=='deposit' && Url::get('type')=='TICKET')
                        {
							$record['type_dps'] = 'TICKET';	
						}
                        //thanh toan cho ban hang
                        if(Url::get('cmd')=='deposit' && Url::get('type')=='VEND')
                        {
							$record['type_dps'] = 'VEND';	
						}
                        if(Url::get('cmd')=='deposit' && Url::get('type')=='MICE')
                        {
							$record['type_dps'] = 'MICE';	
						}
						if($record['id']=='(auto)')
                        {
							$record['id']=false;
						}
                        
                        
                        
                        /** manh log **/
                        $type = '';
                        $title = '';
                        $payment_type = DB::fetch("SELECT name_1 as name FROM payment_type Where payment_type.def_code='".$record['payment_type_id']."'");
                        $description = '<h3>Chi tiết</h3>
                                        Diễn giải: '.$record['description'].'
                                         | Thời gian: '.$record['time'].'
                                         | Loại thanh toán: '.$payment_type['name'].'
                                         | Loại tiền: '.$record['currency_id'].'
                                         | Số tiền: '.$record['amount'].'
                                        ';
                        /** end manh **/
						if($record['id'] and DB::exists_id('payment',$record['id']))
                        {
							unset($record['time']);
							unset($record['user_id']);
							DB::update('payment',$record,'id='.$record['id']);
                            $id_payment_new = $record['id'];
                            if(Url::get('cmd')=='deposit')
                            {
                                $type='Edit DEPOSIT '.$record['type'];
                                if(isset($_REQUEST['act']) AND (Url::get('act')=='traveller' OR Url::get('act')=='group'))
                                {
                                    if(Url::get('act')=='traveller')
                                    {
                                        $room = DB::fetch("SELECT room.name as name, reservation_room.reservation_id as reservation_id from room inner join reservation_room on reservation_room.room_id=room.id Where reservation_room.id=".$_REQUEST['id']);
                                        $title = 'Sửa Đặt cọc phòng '.$room['name'].' Recode #'.$room['reservation_id'];
                                        
                                    }
                                    else
                                    {
                                        $group = DB::fetch("SELECT customer.name FROM customer WHERE customer.id=".$_REQUEST['customer_id']);
                                        $title = 'Sửa Đặt cọc nhóm đoàn '.$group['name'].' Recode #'.$_REQUEST['id'];
                                    }
                                }
                                else
                                {
                                    $title = 'Sửa Đặt cọc cho '.$_REQUEST['type'].' code #'.$_REQUEST['id'];
                                    
                                }
                            }
                            else
                            {
                                if(!isset($_REQUEST['act'])) $_REQUEST['act'] = '';
                                $type='Edit'.$_REQUEST['act'].' '.$record['type'];
                                if($_REQUEST['type']=='RESERVATION')
                                {
                                    $title = 'Sửa Thanh toán cho Folio No.F'.$_REQUEST['folio_id'];
                                }
                                else
                                {
                                    $title = 'Sửa Thanh toán cho '.$record['type'].'No.F'.$_REQUEST['id'];
                                }
                            }
						}
                        else
                        {
							unset($record['id']);
							$record['time'] = time();
							$record['user_id'] = Session::get('user_id');
							$id_payment_new = DB::insert('payment',$record);
                            
                            if(Url::get('cmd')=='deposit')
                            {
                                $type='ADD DEPOSIT '.$record['type'];
                                if(isset($_REQUEST['act']) AND (Url::get('act')=='traveller' OR Url::get('act')=='group'))
                                {
                                    if(Url::get('act')=='traveller')
                                    {
                                        $room = DB::fetch("SELECT room.name as name, reservation_room.reservation_id as reservation_id from room inner join reservation_room on reservation_room.room_id=room.id Where reservation_room.id=".$_REQUEST['id']);
                                        $title = 'Thêm Đặt cọc phòng '.$room['name'].' Recode #'.$room['reservation_id'];
                                        
                                    }
                                    else
                                    {
                                        $group = DB::fetch("SELECT customer.name FROM customer WHERE customer.id=".$_REQUEST['customer_id']);
                                        $title = 'Thêm Đặt cọc nhóm đoàn '.$group['name'].' Recode #'.$_REQUEST['id'];
                                    }
                                }
                                else
                                {
                                    $title = 'Thêm Đặt cọc cho '.$_REQUEST['type'].' code #'.$_REQUEST['id'];
                                    
                                }
                            }
                            else
                            {
                                if(!isset($_REQUEST['act'])) $_REQUEST['act'] = '';
                                $type='ADD'.$_REQUEST['act'].' '.$record['type'];
                                if($_REQUEST['type']=='RESERVATION')
                                {
                                    if($folio_code = DB::fetch('select code from folio where id='.$_REQUEST['folio_id'],'code')){
                                        $title = 'Thêm Thanh toán cho Folio No.F'.str_pad($folio_code,6,"0",STR_PAD_LEFT);
                                    }else{
                                        $title = 'Thêm Thanh toán cho Folio Ref'.str_pad($_REQUEST['folio_id'],6,"0",STR_PAD_LEFT);
                                    }
                                    
                                }
                                else
                                {
                                    $title = 'Thêm Thanh toán cho '.$record['type'].'No.F'.str_pad($_REQUEST['id'],6,"0",STR_PAD_LEFT);
                                }
                            }
						}
                        $log_id = System::log($type,$title,$description,str_pad($_REQUEST['id'],6,"0",STR_PAD_LEFT));
                        if(Url::get('type')=='RESERVATION'){
                            $reservation_id = Url::get('id');
                            if((Url::get('cmd')=='deposit' and Url::get('act')!='group') OR (Url::get('cmd')!='deposit' and Url::get('folio_id') and Url::get('act')!='group_folio')){
                                $reservation_id = Url::get('r_id');
                            }
                            System::history_log('RECODE',$reservation_id,$log_id);
                        }
                        
					}
				}
/*------------------------Send Mail -------------------------------------*/  
                
                if(file_exists('cache/portal/default/config/config_email.php'))
                {
                    require_once ('cache/portal/default/config/config_email.php');
                }
                              
                if(Url::get('type')=='RESERVATION' && Url::get('folio_id') && ROOM_INVOICE==1)
                {
                    $check_edit = DB::fetch('select check_edit from folio where id='.Url::get('folio_id'));
                    if($check_edit['check_edit']==1)
                    {
                        require_once 'packages/hotel/includes/Email/room/send_mail_creart_folio.php';
                        
                        CreatFileTextFolioRoom();
                        
                        DB::update('folio',array('check_edit'=>'0'),'id='.Url::get('folio_id'));
                    }       
                }
                
                if(Url::get('type')=='BAR' && BAR_INVOICE==1)
                {
                    $check_edit = DB::fetch('select check_edit from bar_reservation where id='.Url::get('id'));
                    if($check_edit['check_edit']==1)
                    {
                        require_once 'packages/hotel/includes/Email/bar/send_mail.php';
                        SendMailBar();
                        DB::update('bar_reservation',array('check_edit'=>0),'id='.Url::get('id'));
                    } 
                    //giap.ln update amount_pay_with_room khi co thanh toan
                    $restaurant = DB::fetch("SELECT * FROM bar_reservation WHERE id=".Url::get('id'));
                    if($restaurant['reservation_room_id']!='' && $restaurant['reservation_room_id']!=0)
                    {
                        //lay ra tong so tien da thanh toan 
                        $res_payed = DB::fetch_all("SELECT * FROM payment WHERE bill_id=".Url::get('id')." AND type='BAR'");
                        $res_payed_amount =0;
                        foreach($res_payed as $row)
                        {
                            $res_payed_amount +=$row['amount'];
                        }
                        $amount_remain = $restaurant['total'] - $res_payed_amount;
                        if($amount_remain<0)
                            $amount_remain =0;
                        DB::update('bar_reservation',array('amount_pay_with_room'=>$amount_remain),'id='.Url::get('id'));
                    }
                    //end update amount_pay_with_room    
                }
                
                if(Url::get('type')=='SPA' && SPA_INVOICE==1)
                {
                    $check_edit = DB::fetch('select check_edit from massage_reservation_room where id='.Url::get('id'));
                    if($check_edit['check_edit']==1)
                    {
                        require_once 'packages/hotel/includes/Email/spa/send_mail.php';                    

                
                        SendMailMassage();
                        DB::update('massage_reservation_room',array('check_edit'=>0),'id='.Url::get('id'));
                    } 
                    //giap.ln update lai so tien chuyen ve phong 
                    $spa = DB::fetch("SELECT * FROM massage_reservation_room WHERE id=".Url::get('id'));
                    if($spa['hotel_reservation_room_id']!='' && $spa['hotel_reservation_room_id']!=0)
                    {
                        //lay ra tong so tien da thanh toan 
                        $spa_payed = DB::fetch_all("SELECT * FROM payment WHERE bill_id=".Url::get('id')." AND type='SPA'");
                        $spa_payed_amount =0;
                        foreach($spa_payed as $row)
                        {
                            $spa_payed_amount +=$row['amount'];
                        }
                        $amount_remain = $spa['total_amount'] - $spa_payed_amount;
                        if($amount_remain<0)
                            $amount_remain =0;
                        DB::update('massage_reservation_room',array('amount_pay_with_room'=>$amount_remain),'id='.Url::get('id'));
                    }
                    //end update so tien chuyen ve phong neu co
                     
                }
                
			}
            else 
            if(Url::get('type') and Url::get('id'))
            {
                $cond2 = '';
                if(Url::get('type')=='RESERVATION')
                {
                    $title = '';
                    $reservation_id = Url::get('id');
                    if((Url::get('cmd')=='deposit' and Url::get('act')!='group') OR (Url::get('cmd')!='deposit' and Url::get('folio_id') and Url::get('act')!='group_folio')){
                        $reservation_id = Url::get('r_id');
                    }
					if(Url::get('cmd')=='deposit')
                    {
                        if(Url::get('act')=='group'){
						  $cond2 .= '  AND payment.type_dps =\'GROUP\' and payment.folio_id is null';
                          $title = 'DELETE deposit Group Recode #'.$reservation_id;	
                        }else{ 
                          $cond2 .= '  AND payment.type_dps =\'ROOM\' and payment.folio_id is null';
                          $title = 'DELETE deposit reservation Room id'.Url::get('id').' from Recode #'.$reservation_id;
                        } 	  
					}
                    else if(Url::get('folio_id'))
                    {
						$cond2 .= '  AND payment.folio_id ='.Url::iget('folio_id').'';	
                        if($folio_code = DB::fetch('select code from folio where id='.Url::iget('folio_id'),'code')){
                            $title = 'DELETE payment folio: No.F'.str_pad($folio_code,6,"0",STR_PAD_LEFT);
                        }else{
                            $title = 'DELETE payment folio: Ref'.str_pad(Url::iget('folio_id'),6,"0",STR_PAD_LEFT);
                        }
                        
					}
                    //echo $cond2; die;
					DB::query('delete from payment where payment.type=\''.Url::get('type').'\' AND payment.bill_id ='.Url::iget('id').''.$cond2);
                    $log_id = System::log('DELETE',$title,$title,$reservation_id);
                    System::history_log('RECODE',$reservation_id,$log_id);
				}
                else
                {
					DB::delete('payment',' type=\''.Url::get('type').'\' AND bill_id='.Url::get('id').'');
				}
				
			}
			if(Url::get('cmd')=='deposit')
            {
				if(Url::get('type')=='RESERVATION')
                {
					if(Url::get('act')=='traveller' && Url::get('id'))
                    {
						DB::update('reservation_room',array('deposit'=>$deposit,'deposit_date'=>Date_Time::to_orc_date(date('d/m/Y'))),' id= '.Url::get('id').'');
					}
					if(Url::get('act')=='group' && Url::get('id'))
                    {
						DB::update('reservation',array('deposit'=>$deposit,'deposit_date'=>Date_Time::to_orc_date(date('d/m/Y'))),' id= '.Url::get('id').'');
					}
				}
                else if(Url::get('type')=='BAR' && Url::get('id'))
                {
					DB::update('bar_reservation',array('deposit'=>$deposit),' id= '.Url::get('id').'');	
				}
                else if(Url::get('type')=='KARAOKE' && Url::get('id'))
                {
					DB::update('karaoke_reservation',array('deposit'=>$deposit),' id= '.Url::get('id').'');	
				}
                //else if(Url::get('type')=='TICKET' && Url::get('id'))
//                {
//					DB::update('ticket_reservation',array('deposit'=>$deposit,'deposit_date'=>Date_Time::convert_time_to_ora_date(time())),' id= '.Url::get('id').'');	
//				}
			}	
			$con = '';
			if(Url::get('folio_id'))
            {
				$con = '&folio_id='.Url::get('folio_id');
			}
			if(Url::get('act')){   
				$con .= '&act='.Url::get('act');
			}
			if(Url::get('traveller_id'))
            {
				$con .= '&traveller_id='.Url::get('traveller_id');
			}
			if(Url::get('customer_id'))
            {
				$con .= '&customer_id='.Url::get('customer_id');	
			}
            if(Url::get('member_code'))
            {
				$con .= '&member_code='.Url::get('member_code');	
			}
			//exit();
            //cap nhat lai total va deposit cho ticket_reservation
            if(Url::get('type') == 'TICKET')
            {
               $sql = 'select * from payment where bill_id = '.Url::get('id').' and type = \'TICKET\'';
               $payment_record = DB::fetch_all($sql);
               $total_deposit = 0;
               $total_paid = 0;
               //System::debug($payment_record);
               //exit();
               foreach($payment_record as $key => $value)
               {
                   if($value['type_dps'] == 'TICKET')
                   {
                        $total_deposit += $value['amount'];
                   }
                   else
                   {
                        $total_paid += $value['amount'];
                   }
               }
               if(($total_deposit + $total_paid) >= Url::get('total_amount'))
               {
                    $paid = 1;
               }
               else
               {
                    $paid = 0;
               }
               DB::update('ticket_reservation',array('total_paid'=>$total_paid,'deposit'=>$total_deposit,'payment_status'=>$paid),' id = '.Url::get('id')); 
            }
            //cap nhat lai total va deposit cho vending_reservation
            if(Url::get('type') == 'VEND')
            {
               $sql = 'select * from payment where bill_id = '.Url::get('id').' and type = \'VEND\'';
               $payment_record = DB::fetch_all($sql);
               $total_deposit = 0;
               $total_paid = 0;
               //System::debug($payment_record);
               //exit();
               foreach($payment_record as $key => $value)
               {
                   if($value['type_dps'] == 'VEND')
                   {
                        $total_deposit += $value['amount'];
                   }
                   else
                   {
                        $total_paid += $value['amount'];
                   }
               }
               if(($total_deposit + $total_paid) >= Url::get('total_amount'))
               {
                    $paid = 1;
               }
               else
               {
                    $paid = 0;
               }
               DB::update('ve_reservation',array('total_paid'=>$total_paid,'deposit'=>$total_deposit,'payment_status'=>$paid),' id = '.Url::get('id')); 
               
               $ve = DB::fetch("SELECT department_id,department_code FROM ve_reservation WHERE ID=".Url::get('id'));
               echo '<script>window.parent.location.href=(\'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/'.Url::$root.'?page=automatic_vend&cmd=edit&id='.Url::get('id').'&department_id='.$ve['department_id'].'&department_code='.$ve['department_code'].'\');
				</script>';
            }
			if(Url::get('type')=='BAR')
            {
				$bar_id = (Url::get('bar_id'))?Url::get('bar_id'):(Session::get('bar_id')?Session::get('bar_id'):'');
				
                $tt = 'form.php?block_id='.Module::block_id().'&id='.Url::get('id').'&type='.Url::get('type').'&total_amount='.Url::get('total_amount').'&cmd='.Url::get('cmd').$con;
				echo '
				<script src="packages/core/includes/js/jquery/jquery.min.1.4.2.js" type="text/javascript"></script>
				<script type="text/javascript" src="packages/core/includes/js/common.js"></script>
				';
                if(Url::get('cmd')=='deposit')
                {
					echo '
				<script>
							if($("deposit")){
								$("deposit").value=number_format('.$deposit.');
							}}
							</script>';	
                            
				}
                //giap.ln hien thi duong dan package hay khong
                //$restaurant = DB::fetch("SELECT * FROM bar_reservation WHERE id=".Url::get('id'));
//                if($restaurant['package_id']!='')
//                {
//                    echo '<script>window.parent.location.href=(\'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/'.Url::$root.'?page=touch_bar_restaurant&cmd=edit&package_id='.$restaurant['package_id'].'&rr_id='.$restaurant['reservation_room_id'].'&id='.Url::get('id').'&table_id='.Url::get('table_id').'&bar_area_id='.Url::get('bar_area_id').'&bar_id='.$bar_id.'\');
//				</script>';
//                }
//                else
//                {
//                    echo '<script>window.parent.location.href=(\'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/'.Url::$root.'?page=touch_bar_restaurant&cmd=edit&id='.Url::get('id').'&table_id='.Url::get('table_id').'&bar_area_id='.Url::get('bar_area_id').'&bar_id='.$bar_id.'\');
//				</script>';
//                }
                //end giap.ln 
				
                if(Url::get('action')=='save_stay')
                {
                    if(Url::get('cmd')=='payment')
                    {
                        echo '<script>window.location.href=\'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/'.Url::$root.'?page=touch_bar_restaurant&cmd=edit&id='.Url::get('id').'&table_id='.Url::get('table_id').'&bar_area_id='.Url::get('bar_area_id').'&bar_id='.$bar_id.'\'</script>';
                    }
					else
                    {
                        echo '<script>window.parent.location.reload();</script>';
                    }
				}
                elseif(Url::get('action')=='save')
                {
					echo '<script>window.location.href = \''.$tt.'\';</script>';		
				}
                
                
				//Url::redirect_current(array('bar_id'=>Session::get('bar_id'),'cmd','id'));
			}
            else if(Url::get('type')=='KARAOKE')
            {
				$karaoke_id = (Url::get('karaoke_id'))?Url::get('karaoke_id'):(Session::get('karaoke_id')?Session::get('karaoke_id'):'');
				if(Url::get('cmd')=='deposit')
                {
					echo '
				<script>
							if($("deposit")){
								$("deposit").value=number_format('.$deposit.');
							}}
							</script>';	
				}
				echo '<script>window.parent.location.href=(\'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/'.Url::$root.'?page=karaoke_touch&cmd=edit&id='.Url::get('id').'&karaoke_id='.$karaoke_id.'\');
				</script>';
				//Url::redirect_current(array('karaoke_id'=>Session::get('karaoke_id'),'cmd','id'));
			}
            else if(Url::get('type')=='BILL_MICE')
            {
				//System::debug($_REQUEST); exit();
                if(DB::exists('SELECT id From mice_invoice Where id='.Url::get('id')))
                {
                    $mice_invoice = DB::fetch('SELECT * From mice_invoice Where id='.Url::get('id'));
                    if($mice_invoice['payment_time']=='')
                    {
                        $max_bill = DB::fetch("SELECT max(TO_NUMBER(mice_invoice.bill_id)) as bill from mice_invoice","bill");
                        if(!$max_bill)
                            $max_bill = 0;
                        
                        $max_bill++;
                        DB::update("mice_invoice",array('payment_time'=>time(),'bill_id'=>$max_bill),"id=".Url::get('id'));
                    }
                }
                
                if(Url::get('action')=='save_stay')
                {
                    $tt = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/'.Url::$root.'?page=mice_reservation&cmd=invoice&id='.Url::get('mice_id').'&invoice_id='.Url::get('id');
                    //echo $tt; exit();
                    echo '<script>window.location.href = (\''.$tt.'\');</script>';
                }
                elseif(Url::get('action')=='save_and_view_folio')
                {
					$hh = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/'.Url::$root.'?page=mice_reservation&cmd=invoice&id='.Url::get('mice_id').'&invoice_id='.Url::get('id');
                    $tt = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/'.Url::$root.'?page=mice_reservation&cmd=bill_new&invoice_id='.Url::get('id');
                    echo '<script>
                    window.location.href = \''.$hh.'\';
                    window.open(\''.$tt.'\');
                    </script>';
				}
                else
                {
                    $con = '&mice_id='.Url::get('mice_id');
                    $tt = 'form.php?block_id='.Module::block_id().'&id='.Url::get('id').'&type='.Url::get('type').'&total_amount='.Url::get('total_amount').'&cmd='.Url::get('cmd').$con;
                    
                    echo '<script>window.location.href = \''.$tt.'\';</script>';
                }
			}
            else if(Url::get('type')=='SPA')
            {
				$tt = 'form.php?block_id='.Module::block_id().'&id='.Url::get('id').'&type='.Url::get('type').'&total_amount='.Url::get('total_amount').'&cmd='.Url::get('cmd').$con;
				echo '
				<script src="packages/core/includes/js/jquery/jquery.min.1.4.2.js" type="text/javascript"></script>
				<script type="text/javascript" src="packages/core/includes/js/common.js"></script>
				';
				if(Url::get('act')=='traveller')
                {
					echo '
				<script>
						input_count_r_r = window.parent.input_count;
						for(i=101;i<=input_count_r_r;i++){
							if(window.parent.document.getElementById("deposit_"+i) && window.parent.document.getElementById("id_"+i).value=='.Url::get('id').'){
								window.parent.document.getElementById("deposit_"+i).value=number_format('.$deposit.');
							}}
							</script>';
				}
				if(Url::get('action')=='save_stay')
                {
					$tt = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/'.Url::$root.'?page=spa_order&cmd=edit&id='.Url::get('id');
                    echo '<script>
                                window.parent.location.reload();
                        </script>';
				}
                else
                {
					echo '<script>window.location.href = \''.$tt.'\';</script>';		
				}
			}
            else
            {
                
				$tt = 'form.php?block_id='.Module::block_id().'&id='.Url::get('id').'&type='.Url::get('type').'&total_amount='.Url::get('total_amount').(Url::get('fast')?'&fast=1':'').'&cmd='.Url::get('cmd').$con;
				echo '
				<script src="packages/core/includes/js/jquery/jquery.min.1.4.2.js" type="text/javascript"></script>
				<script type="text/javascript" src="packages/core/includes/js/common.js"></script>
				';
				if(Url::get('act')=='traveller')
                {
					echo '
                            <script>
        						input_count_r_r = window.parent.input_count;
        						for(i=101;i<=input_count_r_r;i++){
        							if(window.parent.document.getElementById("deposit_"+i) && window.parent.document.getElementById("id_"+i).value=='.Url::get('id').'){
        								window.parent.document.getElementById("deposit_"+i).value=number_format('.$deposit.');
        							}}
							</script>';
				}
				if(Url::get('action')=='save_stay')
                {
					echo '<script> window.parent.location.reload();</script>';
				}
                elseif(Url::get('action')=='save_and_view_folio')
                {
					if(Url::get('act') == 'group_folio')
                    {
                        $hh = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/'.Url::$root.'form.php?block_id='.BLOCK_CREATE_FOLIO.'?&cmd=group_folio&customer_id='.Url::get('customer_id').'&id='.Url::get('id').'&folio_id='.Url::get('folio_id').'&portal_id='.PORTAL_ID;
                        $tt = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/'.Url::$root.'?page=view_traveller_folio&cmd=group_invoice&customer_id='.Url::get('customer_id').'&id='.Url::get('id').'&folio_id='.Url::get('folio_id').'&portal_id='.PORTAL_ID;
                    }else
                    {
                        $hh = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/'.Url::$root.'form.php?block_id='.BLOCK_CREATE_FOLIO.'?&cmd=create_folio&traveller_id='.Url::get('traveller_id').'&rr_id='.Url::get('id').'&folio_id='.Url::get('folio_id').'&portal_id='.PORTAL_ID;
                        $tt = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/'.Url::$root.'?page=view_traveller_folio&cmd=invoice&traveller_id='.Url::get('traveller_id').'&folio_id='.Url::get('folio_id').'&portal_id='.PORTAL_ID;
                    }
                    echo '<script>
                    window.location.href = \''.$hh.'\';
                    window.open(\''.$tt.'\');
                    </script>';
				}
                else
                {
					echo '<script>window.location.href = \''.$tt.'\';</script>';		
				}
			}    
            exit();
		}
        else
        {
			$this->error('amount','miss_payment');
			return;
		}
	}	
	function draw()
	{	
		//System::debug($_REQUEST);
        require_once 'packages/hotel/includes/php/hotel.php';
        require_once 'packages/hotel/includes/member.php';
		$items_arr = ''; 
        $total_paid = 0;
		$paging = '';
		$this->map = array();
		$count = 0;
		$rt_id = 0;
        $rr_id=0;
		//echo 'thuy'.Url::get('id').'_'.Url::get('type').'_'.Url::get('total_amount');
        /** du lieu quan ly thanh vien de tich diem **/
        $member_code = Url::get('member_code')?Url::get('member_code'):"";
        $_REQUEST['member_code'] = $member_code;
        $this->map['member_code'] = $member_code;
        
        
        /** dieu kien ban dau de ly ra du lieu  **/
		$cond = 'payment.bill_id = '.Url::iget('id').' AND payment.type = \''.Url::get('type').'\'';
		if(Url::get('type')=='BAR')
        {
			if(Url::get('cmd')=='deposit')
            {
				$cond .= ' AND (payment.type_dps = \'BAR\' AND payment.folio_id is null)';
			}
		}
        if(Url::get('type')=='KARAOKE')
        {
			if(Url::get('cmd')=='deposit')
            {
				$cond .= ' AND (payment.type_dps = \'KARAOKE\' AND payment.folio_id is null)';
			}
		}
        if(Url::get('type')=='TICKET')
        {
			if(Url::get('cmd')=='deposit')
            {
				$cond .= ' AND (payment.type_dps = \'TICKET\' AND payment.folio_id is null)';
			}
		}
        if(Url::get('type')=='VEND')
        {
            $check_payment = DB::fetch('select reservation_room_id as id from ve_reservation where id = '.Url::get('id').'','id');
            if($check_payment !=0)
            {
                $_REQUEST['check_payment'] = 1;
            }
            else
            {
                if(isset($_REQUEST['check_payment'])){
                    unset($_REQUEST['check_payment']);
                }
            }
			if(Url::get('cmd')=='deposit')
            {
				$cond .= ' AND (payment.type_dps = \'VEND\' AND payment.folio_id is null)';
			}
		}
        else
        {
			
		}
		if(!isset($_REQUEST['mi_payment']))
        {
			$cond .= Url::get('folio_id')?(' AND (payment.folio_id='.Url::get('folio_id').')'):'';
			//OR payment.folio_id is null
			if(Url::get('cmd')=='deposit'){
				if(Url::get('act')=='group'){
					$cond .= ' AND (payment.type_dps = \'GROUP\' AND payment.folio_id is null)';
 				}else if(Url::get('act')=='traveller'){
					$cond .= ' AND (payment.type_dps = \'ROOM\' AND payment.folio_id is null)';	
				}
			}
			DB::query('
				select 
					count(*) as acount
				from 
					payment
				where 
					'.$cond.'
			');
			$count = DB::fetch(); 
			$mi_payment = DB::fetch_all('SELECT payment.*,ROWNUM as rownumber FROM payment WHERE '.$cond.' ORDER BY id ASC ');
            foreach($mi_payment as $k=>$v){
                $current_payment_type = $mi_payment[$k]['currency_id'];
                $current_money = $mi_payment[$k]['exchange_rate'];
            }
            
            //System::Debug($mi_payment);
			if(!empty($mi_payment)){
				foreach($mi_payment as $key=>$value){
					$mi_payment[$key]['amount'] = System::display_number($value['amount']);
					$value['bank_fee'] = ($value['bank_fee']=='')?0:$value['bank_fee'];
					$mi_payment[$key]['bank_fee'] = System::display_number($value['amount']+$value['bank_fee']);
					$mi_payment[$key]['time'] = date('H:i\' d/m/Y',$value['time']);
					$mi_payment[$key]['paid'] = true;
					$total_paid += round($value['amount']*$value['exchange_rate'],2);
                    if($value['payment_point']=='on'){
                        $mi_payment[$key]['payment_point'] = true;
                    }else{
                        $mi_payment[$key]['payment_point'] = false;
                    }
				}
			}else{
				if(HOTEL_CURRENCY == 'USD'){
					$mi_payment[1]['currency_id'] = 'USD';	
				}else{
					$mi_payment[1]['currency_id'] = 'VND';
				}
				if(Url::get('cmd')=='deposit'){
					$mi_payment[1]['amount'] = 0;
					$mi_payment[1]['time'] = date('H:i\' d/m/Y',time());
					$mi_payment[1]['payment_type_id'] = 'CREDIT_CARD';
					$mi_payment[1]['credit_card_id'] = '1';
					$mi_payment[1]['reservation_room_id'] = '';
					$mi_payment[1]['bank_fee'] = '';
					$mi_payment[1]['paid'] = false;
				}else{
				    if(Url::get('total_amount') < 0 && Url::get('cmd')!='deposit')
                    {
                        $mi_payment[1]['amount'] = System::display_number(-Url::get('total_amount'));
    					$mi_payment[1]['time'] = date('H:i\' d/m/Y',time());
    					$mi_payment[1]['payment_type_id'] = 'REFUND';
    					$mi_payment[1]['reservation_room_id'] = '';
    					//$mi_payment[1]['exchange_rate'] = '21000';
    					$mi_payment[1]['bank_fee'] = '';
    					$mi_payment[1]['paid'] = false;
    					
                    }
					else
                    {
                        $mi_payment[1]['amount'] = System::display_number(Url::get('total_amount'));
    					$mi_payment[1]['time'] = date('H:i\' d/m/Y',time());
    					$mi_payment[1]['payment_type_id'] = 'CASH';
    					$mi_payment[1]['reservation_room_id'] = '';
    					//$mi_payment[1]['exchange_rate'] = '21000';
    					$mi_payment[1]['bank_fee'] = '';
    					$mi_payment[1]['paid'] = false;
                    }
                    $total_paid = Url::get('total_amount');
				}
				
			}
			//System::Debug($mi_payment);
			$_REQUEST['mi_payment'] = $mi_payment;
		}	
        
        /** START - Dat - payment old **/
        $payment_old = 0;
        if(!empty($mi_payment)){
            foreach($mi_payment as $key => $value)
            {
                if($value['payment_type_id'] == 'REFUND')
                    $payment_old -= System::calculate_number($value['amount']);
                else
                    $payment_old += System::calculate_number($value['amount']);
            }
        }
        /** START - Dat - payment old **/
        
		$credit_card_options='<option value="">----</option>';
		$credit_cards = DB::fetch_all('select * from credit_card');
		foreach($credit_cards as $k => $credit){
			$credit_card_options .='<option value='.$credit['id'].'>'.$credit['name'].'</option>';
		}
		$this->map['credit_card_options'] = $credit_card_options;
		//------------------Get payment_type-------------------------------//
        if((Url::get('total_amount') - $payment_old) < 0 and Url::get('cmd')!='deposit')
            $payment_type_options = '<option disabled="disabled" value="">----</option>';
        else
            $payment_type_options = '<option value="">----</option>';
		$con = '';
		if(Url::get('cmd')=='deposit'){
			$con = ' AND  def_code<>\'DEBIT\' AND def_code<>\'FOC\' AND def_code<>\'REFUND\'';	
		}
		if(Url::get('type')=='BAR')
        {
			$payment_type =DB::fetch_all('select def_code as id,name_'.Portal::language().' as name from payment_type where (apply=\'ALL\' OR (apply=\'BAR\')) AND def_code is not null AND def_code<>\'ROOM CHARGE\' '.$con);
		}else if(Url::get('type')=='KARAOKE')
        {
			$payment_type =DB::fetch_all('select def_code as id,name_'.Portal::language().' as name from payment_type where (apply=\'ALL\' OR (apply=\'KARAOKE\')) AND def_code is not null AND def_code<>\'ROOM CHARGE\' '.$con);
		}else
        {
			$payment_type =DB::fetch_all('select def_code as id,name_'.Portal::language().' as name from payment_type where (apply=\'ALL\' OR (apply=\'ALL\')) AND def_code is not null '.$con);
		}
        
		foreach($payment_type as $key => $pmt){
		    if(!User::can_admin($this->get_module_id('PrivilegePaymentBank'),ANY_CATEGORY) and $pmt['id'] == 'BANK'){
		          $payment_type_options .= '<option disabled="disabled" value="'.$pmt['id'].'">'.Portal::language($pmt['name']).'</option>';
		    }else{
		          if((Url::get('total_amount') - $payment_old) < 0 and $pmt['id'] != 'REFUND' and Url::get('cmd')!='deposit')
                    $payment_type_options .= '<option disabled="disabled" value="'.$pmt['id'].'">'.Portal::language($pmt['name']).'</option>';
                else
                    $payment_type_options .= '<option value="'.$pmt['id'].'">'.Portal::language($pmt['name']).'</option>';
		    }
		}
		$this->map['payment_type_options'] = $payment_type_options;
		//------------------END-------------------------------//
        
		//------------------Get currency-------------------------------//
		$this->map['default_exchange_rate'] = DB::fetch('SELECT exchange FROM currency WHERE id = \''.HOTEL_CURRENCY.'\'','exchange');
		$currencies = DB::select_all('currency','allow_payment=1','id DESC');
        if(isset($current_money)){
            foreach($currencies as $key=>$value){
                if($value['id']==$current_payment_type){
                    $currencies[$key]['exchange'] = $current_money;
                }
            }
        }
        
        
		$currency_options = '';
		foreach($currencies as $value){
			$currency_options .= '<option value="'.$value['id'].'">'.$value['name'].'</option>';	
		}
		$this->map['obj_payment'] = '';	
		if(Url::get('act')=='traveller'){
			$this->map['obj_payment'] = Portal::language('room').' '.DB::fetch('select room.name from reservation_room INNER join room ON reservation_room.room_id = room.id
					Where reservation_room.id = '.Url::get('id').'','name');
		}else if(Url::get('act')=='group'){
			$this->map['obj_payment'] =Portal::language('customer').' '.DB::fetch('select customer.name from reservation INNER join customer ON reservation.customer_id = customer.id
					Where reservation.id = '.Url::get('id').'','name');
		}
		//------------------END-------------------------------//
		$this->map['items'] = $items_arr;
		$this->map['currency_options'] = $currency_options;
		$this->map['currency']=$currencies;
		$this->map['payment_type']=$payment_type;
		$this->map['credit_cards']=$credit_cards;
		$this->map['credit_cards_js']=String::array2js($credit_cards);
		$this->map['count']=$count;
		$this->map['total_paid']=$total_paid;
		$this->map['total_amount'] = (Url::get('total_amount')?Url::get('total_amount'):0);
		$this->map['currencies'] = String::array2js($currencies);
		
        $point_user_refund = 0;
        $_REQUEST['point_user_refund'] = $point_user_refund;
		$this->parse_layout('edit',$this->map);
	}   
	function get_item($items){    
		$item_detail = explode('{',Url::get('items_'.$items));
		$detail = preg_replace("/\'|}/","",$item_detail);
		$item = explode(":",$detail[1]);
		$t = 0;
		for($k=1;$k<count($item);$k++){	
			if($k < count($item)-1){	
				$tt = strrpos($item[$k],',');
				$item_cate[$t] = substr($item[$k],0,$tt);

			}else{
				$item_cate[$t] = $item[$k];
			}
			$items_k[$t.'_'.$items] = $item_cate[$t];
			$t++;
		}
		return $items_k;
	}
}
?>