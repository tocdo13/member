<?php
class CreateTravellerFolioForm extends Form{
	function CreateTravellerFolioForm(){
		Form::Form('CreateTravellerFolioForm');
		$this->link_css("packages/hotel/skins/default/css/invoice.css");		
		//$this->link_js('packages/core/includes/js/jquery/window/jquery.window.js');
		//$this->link_js("packages/core/includes/js/jquery.windows-engine.js");
		//$this->link_css("packages/hotel/skins/default/css/jquery.windows-engine.css");
		$this->link_js('packages/core/includes/js/jquery/window/jquery.window.js');
		$this->link_js('packages/hotel/packages/reception/modules/includes/jquery.windows-engine.js');
        $this->link_js('packages/hotel/packages/reception/modules/includes/common01.js');
		$this->link_css("packages/hotel/skins/default/css/jquery.windows-engine.css");
        $this->link_css('packages/hotel/skins/default/css/w3c.css');
        $this->link_css('packages/hotel/skins/default/css/font-awesome-4.7.0/css/font-awesome.min.css');
		$this->add('traveller_id',new IntType(true,'invalid_traveller_id','0','100000000000'));
		if(Url::get('cmd') != 'add_payment'){
			//$this->add('payment_type',new TextType(true,'invalid_payment_type',0,255));    
		}
	}
	function on_submit()
    {
        if(Url::get('folio_id'))
        {
            $this->log_delete_folio($_REQUEST,Url::get('folio_id'));
        }else/** Daund add: check double ban ghi traveller folio & xoa ten khach */
        {
            $check = true;
            //System::debug($_REQUEST);
            if(isset($_REQUEST['traveller_folio']) && !empty($_REQUEST['traveller_folio']))
            {
                foreach($_REQUEST['traveller_folio'] as $key => $value)
                {
                    $amount = DB::fetch('SELECT sum(amount) as amount FROM traveller_folio WHERE invoice_id=\''.$value['id'].'\' AND type=\''.$value['type'].'\' AND reservation_room_id = \''.$value['rr_id'].'\' AND date_use =\''.Date_Time::to_orc_date($value['full_date']).'\'','amount');
                    //System::debug(System::calculate_number($_REQUEST['amount_'.$value['type'].'_'.$value['id']]) - ((System::calculate_number($value['amount']) + $amount)));exit();
                    if((System::calculate_number($_REQUEST['amount_'.$value['type'].'_'.$value['id']]) - ((System::calculate_number($value['amount']) + $amount)) <= 0) && $amount && $value['percent']==100)
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
            if(isset($_REQUEST['traveller_id']) && $_REQUEST['traveller_id'] != 0)
            {
                //echo 'SELECT * FROM reservation_traveller WHERE id = \''.$_REQUEST['traveller_id'].'\' AND reservation_room_id = \''.Url::get('rr_id').'\'';
                if(!DB::exists('SELECT * FROM reservation_traveller WHERE id = \''.$_REQUEST['traveller_id'].'\' AND reservation_room_id = \''.Url::get('rr_id').'\''))
                {
                    $check_traveller = 0;                    
                } 
            } 
            //System::debug($check_traveller);exit();  
            if($check_traveller == 0)
            {
                echo '<script>alert("Ten khach nay da duoc xoa truoc do, vui long kiem tra lai. Xin cam on!");</script>';
                echo '<script>window.parent.location.reload();</script>';
                die;
                return false;
            }                   
        }/** End */
/*--------------------------send mail--------------------------*/
        if(file_exists('cache/portal/default/config/config_email.php'))
        {
            require_once ('cache/portal/default/config/config_email.php');
        } 
        if(Url::get('folio_id')!='' && ROOM_INVOICE==1)
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
		if(Url::get('action') == 1)
        {
            /** Manh khoi tao log folio **/
            $type = '';
            $title = '';
            $description = '';
            /** end khoi tao log **/
			if($this->check() and Url::get('traveller_id') and Url::get('traveller_id') != 0)
            {
				$split_invoices = array();
				$rt_id = Url::get('traveller_id');                
                /** manh log **/
                $traveller = DB::fetch("SELECT concat(concat(traveller.first_name,' '),traveller.last_name) as full_name FROM traveller inner join reservation_traveller on reservation_traveller.traveller_id=traveller.id WHERE reservation_traveller.id=".Url::get('traveller_id'));
                $description_traveller = "<p>Tên khách: ".$traveller['full_name']."</p>";
                /** end manh **/
				if(Url::get('add_payment'))
                { 
					$rr_id = Url::get('add_payment');	  
					$t = 1;
				}
                else if(Url::get('rr_id'))
                {
					$rr_id = Url::get('rr_id');	
                    /** manh log **/
                    $room = DB::fetch("SELECT room.name FROM room inner join reservation_room on reservation_room.room_id=room.id WHERE reservation_room.id=".Url::get('rr_id'));
                    $description_traveller .= "<p>Tên phòng: ".$room['name']."</p>";
                    /** end manh **/
					$t =0;   
				}
				$check = 0;
				$reservation_id = DB::fetch('select reservation_room.reservation_id 
        									from reservation_room 
        										INNER JOIN reservation_traveller ON reservation_room.id = reservation_traveller.reservation_room_id
        									where reservation_traveller.id='.$rt_id.'','reservation_id');                                        
				$travellers = $this->get_reservation_traveller($rr_id);
				$folio_id = Url::get('folio_id')?Url::get('folio_id'):'';
                
				$count = 0;
				if($folio_id!='')
                {
					$invoice_old = $this->get_traveller_folio($rt_id,$rr_id,$folio_id);
                }
                else
                {
					$invoice_old = array();	
				}
				$count = 0;
				$total_amount = 0;
				$total_deposit = 0; $total_room = 0;
				
					//--------------------------------------Thuc hien them moi hoa don tach-----------------------------------//
				$split_invoices = Url::get('traveller_folio');
                
				$add_paids = Url::get('add_paid');
				$add_paid_old = array();
				if(Url::get('folio_id'))
                {
					$add_paid_old = $this->get_add_paid($rt_id,Url::get('folio_id'));
				}
                if(Url::get('cmd')!='add_payment')
                {
					if(!empty($add_paid_old))
                    {
						foreach($add_paid_old as $d => $paid_old)
                        {					
							if(!isset($add_paids[$paid_old['id']]))
                            {
                                $traveller_folio = DB::fetch("SELECT * from traveller_folio Where  reservation_traveller_id=".$rt_id." AND add_payment=1 AND reservation_room_id=".$paid_old['id']."");
								DB::delete('traveller_folio',' reservation_traveller_id='.$rt_id.' AND add_payment=1 AND reservation_room_id='.$paid_old['id'].' and folio_id = '.Url::get('folio_id'));
							}
						}
					}
				}
                else
                {
                    if(!empty($add_paid_old))
                    {
						foreach($add_paid_old as $d => $paid_old)
                        {
                            if($paid_old['id'] == $rr_id)
                            {
                                $traveller_folio = DB::fetch("SELECT * from traveller_folio Where  reservation_traveller_id=".$rt_id." AND add_payment=1 AND reservation_room_id=".$paid_old['id']."");
								DB::delete('traveller_folio',' reservation_traveller_id='.$rt_id.' AND add_payment=1 AND reservation_room_id='.$paid_old['id'].' and folio_id = '.Url::get('folio_id'));
                                
							}
						}
					}
                }
                
                if(!empty($invoice_old))
                {
					foreach($invoice_old as $k => $old)
                    {
						if(!isset($split_invoices[$old['id']]))
                        {
                            $traveller_folio = DB::fetch("SELECT * from traveller_folio where id=".$old['traveller_folio_id']);
							DB::delete('traveller_folio',' id='.$old['traveller_folio_id'].'');
                            
						}
					}
				}
                
				if(empty($add_paids) && empty($split_invoices))
                {
					if(Url::get('cmd')=='create_folio' && Url::get('folio_id'))
                    {
						DB::delete('folio',' id = '.Url::get('folio_id').'');
						DB::delete('traveller_folio',' folio_id = '.Url::get('folio_id').'');
						DB::delete('payment',' payment.type=\'RESERVATION\' AND payment.folio_id = '.Url::get('folio_id').'');
                        //start:KID them update reservation_traveller de kiem tra is_folio
                        DB::update('reservation_traveller',array('is_folio'=>0),'id='.Url::get('traveller_id'));
                        //end:KID them update reservation_traveller de kiem tra is_folio
					}
                    else if(Url::get('folio_id') and $tmp = DB::fetch('select * from folio where id = '.Url::get('folio_id').''))
                    {
                        $old_folio['total'] = $tmp['total'] + System::calculate_number(Url::get('total_payment'));//(2)
                        $old_folio['total'] -= Url::get('total_payment_old')?System::calculate_number(Url::get('total_payment_old')):0;
						
                        DB::update('folio',$old_folio+array('last_time'=>time(),'lastest_user_id'=>User::id()),' id='.Url::get('folio_id').'');
                    }
				}
                else
                {
					if(!empty($split_invoices))
                    {
                        /** Manh Log **/
                        $detail_description = "<h3>Chi tiết</h3><hr />";
                        /** end manh **/
                        
						foreach($split_invoices as $k => $split)
                        {
							$count++;
							$split_invoices[$k]['invoice_id'] = $split['id'];
							$split_invoices[$k]['reservation_traveller_id'] = $rt_id;
                            /** START - DAT bo sung list cac khoan cua chang truoc trong truong hop doi phong **/
                            //$split_invoices[$k]['reservation_room_id'] =  $rr_id;	
							$split_invoices[$k]['reservation_room_id'] =  $split['rr_id'];
                            unset($split_invoices[$k]['rr_id']);
                            /** START - DAT bo sung list cac khoan cua chang truoc trong truong hop doi phong **/
							$split_invoices[$k]['reservation_id'] =  $reservation_id;
                            /** THL **/
							$split_invoices[$k]['amount'] = System::calculate_number($split['amount'])/(1+$split['service_rate']/100)/(1+$split['tax_rate']/100);
                            $split_invoices[$k]['total_amount'] = System::calculate_number($split['amount']) ; 
                            /** THL **/
                        	$split_invoices[$k]['foc'] =  Url::get('foc')?Url::get('foc'):'';
							$split_invoices[$k]['foc_all'] = (Url::get('foc_all')==1)?Url::get('foc_all'):0;
							$split_invoices[$k]['id'] = $rt_id.'_'.$split_invoices[$k]['type'].'_'.$split_invoices[$k]['invoice_id'].'_'.$t;	
							if(Url::get('rr_id') == $rr_id)
                            {
								$split_invoices[$k]['add_payment'] = 0;
							}
                            else
                            {
								$split_invoices[$k]['add_payment'] = 1;	
							}
                            /** Manh log  **/
                            $detail_description .= "<p>"." Loại: ".$split['type']." Chi tiết: ".$split['description']." Ngày: ".$split['full_date']." Số tiền: ".$split['amount']." Phí dịch vụ ".$split['service_rate']." Thuế: ".$split['tax_rate']."</p>";
                            /** end Manh **/
                        }
    					$total_amount =  System::calculate_number(Url::get('total_amount'));
    					$total_payment =  System::calculate_number(Url::get('total_payment'));
    					$service_amount = System::calculate_number(trim(Url::get('service_charge_amount')));
    					$tax_amount = System::calculate_number(trim(Url::get('tax_amount')));
    					if(Url::get('folio_id'))
                        {
                            /** truong hop edit log - manh **/
                            $type = "Edit Guest's Folio";
                            if($folio_code = DB::fetch('select code from folio where id='.Url::get('folio_id'),'code')){
                                $title = "Edit Guest's Folio id: #No.F".str_pad($folio_code,6,"0",STR_PAD_LEFT)."";
                                $description = "<p>Mã hóa đơn:"."No.F".str_pad($folio_code,6,"0",STR_PAD_LEFT)."</p><hr />";
                            }else{
                                $title = "Edit Guest's Folio id: #Ref".str_pad(Url::get('folio_id'),6,"0",STR_PAD_LEFT)."";
                                $description = "<p>Mã hóa đơn:"."Ref".str_pad(Url::get('folio_id'),6,"0",STR_PAD_LEFT)."</p><hr />";
                            }
                            $description .= $description_traveller;
                            $description .= $detail_description;
                            /** end manh **/
    						$folio_id = Url::get('folio_id');
    						if(Url::get('add_payment'))
                            { 
    							$rr=Url::get('rr_id')?Url::get('rr_id'):'';
    						
                            }
                            else if(Url::get('rr_id'))
                            {
    							$rr =$rr_id;
    						}
    						$member_code = Url::get("member_code")?Url::get("member_code"):"";
                            $member_level_id = Url::get("member_level_id")?Url::get("member_level_id"):"";
                            $create_member_date = Url::get("create_member_date")?Url::get("create_member_date"):"";
    						if($count>0 && Url::get('cmd')=='create_folio')
                            {
    							DB::update('folio',array('last_time'=>time(),'lastest_user_id'=>User::id(),'lastest_edit_time'=>time(),'member_code'=>$member_code,'member_level_id'=>$member_level_id,'create_member_date'=>$create_member_date,'total'=>($total_payment),'tax_amount'=>$tax_amount,'service_amount'=>$service_amount,'reservation_room_id'=>$rr,'reservation_id'=>$reservation_id,'user_id'=>Session::get('user_id')),' id = '.Url::get('folio_id').'');	
    						    /**  Manh log **/
                                $log_id = System::log($type,$title,$description,str_pad(Url::get('folio_id'),6,"0",STR_PAD_LEFT)) ; 
                                System::history_log('RECODE',$reservation_id,$log_id);
                                System::history_log('FOLIO',Url::get('folio_id'),$log_id);
                                /** end manh **/
                            }
                            else if(Url::get('cmd')=='add_payment')
                            {
    							$old_folio = DB::fetch('select * from folio where id = '.Url::get('folio_id').'');
                                //start: KID thay doi dong (1) thanh dong (2) de in ra hoa don dung khi có add payment	
    							//$old_folio['total'] += $total_amount;//(1)
                                $old_folio['total'] += $total_payment;//(2)
                                $old_folio['total'] -= Url::get('total_payment_old')?System::calculate_number(Url::get('total_payment_old')):0;
                                //end: KID thay doi dong (1) thanh dong (2) de in ra hoa don dung khi có add payment
    							$old_folio['service_amount'] += $service_amount;
    							$old_folio['tax_amount'] += $tax_amount;
                                $old_folio['member_code'] = $member_code;
                                $old_folio['member_level_id'] = $member_level_id;
                                $old_folio['create_member_date'] = $create_member_date;                            
    							DB::update('folio',$old_folio,' id='.Url::get('folio_id').'');
    						}
    					}
                        else
                        {
                            $member_code = Url::get("member_code")?Url::get("member_code"):"";
                            $member_level_id = Url::get("member_level_id")?Url::get("member_level_id"):"";
                            $create_member_date = Url::get("create_member_date")?Url::get("create_member_date"):"";
    						$number = DB::fetch('select max(num) as num from folio where reservation_traveller_id = '.$rt_id.'','num');
    						if($number && $number!='')
                            {
    							$number = $number+1;
    						}
                            else
                            {
    							$number =1;
    						}
    						if($count>0)
                            {
                                $folio_id = DB::insert('folio',array(
                                                        'last_time'=>time(),
                                                        'lastest_user_id'=>User::id(),
                                                        'reservation_traveller_id'=>$rt_id,
                                                        'member_code'=>$member_code,
                                                        'member_level_id'=>$member_level_id,
                                                        'create_member_date'=>$create_member_date,
                                                        'reservation_room_id'=>$rr_id,
                                                        'total'=>$total_payment,
                                                        'create_time'=>time(),
                                                        'portal_id'=>''.PORTAL_ID.'',
                                                        'num'=>$number,
                                                        'tax_amount'=>$tax_amount,
                                                        'service_amount'=>$service_amount,
                                                        'reservation_id'=>$reservation_id,
                                                        'user_id'=>Session::get('user_id'),
                                                        'check_edit'=>'1'
                                                        ));		
                                //start:KID them update reservation_traveller de kiem tra is_folio
                                DB::update('reservation_traveller',array('is_folio'=>1),'id='.Url::get('traveller_id'));
                                //end:KID them update reservation_traveller de kiem tra is_folio    							
                                /** truong hop add log - manh **/
                                $type = "ADD Guest's Folio";
                                $title = "ADD Guest's Folio id: #Ref".str_pad($folio_id,6,"0",STR_PAD_LEFT)."";  // Oanh edit
                                $description = "<p>Mã hóa đơn: Ref".str_pad($folio_id,6,"0",STR_PAD_LEFT)."</p><hr />"; // Oanh edit
                                $description .= $description_traveller;
                                $description .= $detail_description;
                                $log_id = System::log($type,$title,$description,str_pad($folio_id,6,"0",STR_PAD_LEFT)); // Oanh edit
                                System::history_log('RECODE',$reservation_id,$log_id);
                                System::history_log('FOLIO',$folio_id,$log_id);
                                /** end manh **/
                                $check = 1;	
    						}
                            else
                            { 
                                return;
    						}
                            
    					}
    					if(!empty($invoice_old))
                        {
    						foreach($invoice_old as $k => $old)
                            {
    							if(!isset($split_invoices[$old['id']]))
                                {
                                    $traveller_folio = DB::fetch("SELECT * from traveller_folio where id=".$old['traveller_folio_id']);
    								DB::delete('traveller_folio',' id='.$old['traveller_folio_id'].'');
                                    
    							}
    						}
    					}
    					if(!empty($split_invoices))
                        {
    						foreach($split_invoices as $key => $value)
                            {
    							/*if(strpos('_',$value['description'])>=0){
    								$value['description'] = substr($value['description'],0,strpos('_',$value['description']));
    							}*/
    							$value['folio_id'] = $folio_id;
    
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
                    }
                    else if(Url::get('folio_id') and $tmp = DB::fetch('select * from folio where id = '.Url::get('folio_id').''))
                    {
                        $old_folio['total'] = $tmp['total'] + System::calculate_number(Url::get('total_payment'));//(2)
                        $old_folio['total'] -= Url::get('total_payment_old')?System::calculate_number(Url::get('total_payment_old')):0;
						DB::update('folio',$old_folio+array('last_time'=>time(),'lastest_user_id'=>User::id()),' id='.Url::get('folio_id').'');
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
                   
                    if($array_folio_before!=$array_folio_after || $array_folio_traveller_before!=$array_folio_traveller_after)
                    {
                        
                        DB::update('folio',array('check_edit'=>'1','last_time'=>time(),'lastest_user_id'=>User::id()),'id='.Url::get('folio_id'));
                    }                                
                }
    /*--------------------------send mail--------------------------*/            
    			if(Url::get('act')=='payment'){// Save v? th?c hi?n thanh to?n
                    $get_member_code = Url::get('member_code')?Url::get('member_code'):"";
    				$tt = 'form.php?block_id=428&cmd=payment&id='.Url::get('rr_id').'&member_code='.$get_member_code.(Url::get('fast')?'&fast=1':'').'&r_id='.$reservation_id.'&traveller_id='.Url::get('traveller_id').'&type=RESERVATION&act=create_folio&total_amount='.System::calculate_number(Url::get('total_payment')).'&folio_id='.$folio_id.'&portal_id='.PORTAL_ID.''; 
                }
                else
                {
                    if(Url::get('act')=='save_and_print' and isset($folio_id) and $folio_id!=''){
                        $tt = 'form.php?block_id='.Module::block_id().'&cmd=create_folio&traveller_id='.Url::get('traveller_id').'&rr_id='.Url::get('rr_id').'&folio_id='.$folio_id.'&check_payment='.(Url::get('check_payment')?Url::get('check_payment'):0).(Url::get('fast')?'&fast=1':'');
                        echo '<script>window.open(\'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/'.Url::$root.'?page=view_traveller_folio&cmd=invoice&traveller_id='.Url::get('traveller_id').'&folio_id='.$folio_id.'\');</script>';
                    }
                    else{
                        if(Url::get('cmd')=='add_payment' && $check==1)
                        {
        					$con = '&folio_id='.$folio_id;	
        				}
                        else
                        { 
                            $con='';
                        }
    				    $tt = 'form.php?block_id='.Module::block_id().'&cmd=create_folio&rr_id='.Url::get('rr_id').'&traveller_id='.Url::get('traveller_id').''.(Url::get('fast')?'&fast=1':'').$con;
                    }
                }   
    			echo '<script>window.location.href = \''.$tt.'\'</script>';
                
                exit();
			}
            else
            { 
				$this->error('traveller_id','you_have_to_select_traveller_id');
			}
            
            
		}
	}
	function draw(){  	   
        $this->map = array();
		$not_selected = '';
		$item_type = '';
		$total_payment = 0;
		if(isset($GLOBALS['night_audit_date'])){
			$today_date = $GLOBALS['night_audit_time'];
		}else{
			$today_date = date('d/m/Y');
		}
		if(Url::get('add_payment') && Url::get('traveller_id')){
			$rr_id = Url::get('add_payment');
			$id = Url::get('add_payment');
            //start:KID them de loai bo ban ghi add_payment'                                  
            $add_pm = 'and traveller_folio.add_payment =1 and traveller_folio.reservation_room_id = '.$rr_id;
            //end:KID them de loai bo ban ghi add_payment		
		}else{
			$rr_id =Url::iget('rr_id');		
			$id = Url::iget('rr_id');
            $add_pm = 'and traveller_folio.add_payment !=1';
		}
		$checkout_id = '';
		for($i=0;$i<6-strlen($id);$i++){
			$checkout_id .= '0';
		}
		$checkout_id .= $id;
		require_once 'packages/core/includes/utils/time_select.php';
		require_once 'packages/core/includes/utils/currency.php';
		$paid_group = 0;
       // Lay thong tin invoied da thanh toan cho phong khac
		$travel_id='';
		if(!Url::get('traveller_id'))
        {
			$travel_id = DB::fetch('select reservation_traveller.id as traveller_id from reservation_traveller INNER JOIN reservation_room ON reservation_room.id = reservation_traveller.reservation_room_id WHERE reservation_room.id='.$rr_id.' AND (reservation_traveller.status=\'CHECKIN\' OR reservation_traveller.status=\'CHECKOUT\')','traveller_id');
			$_REQUEST['travellers_id'] = $travel_id;
			$_REQUEST['traveller_id'] = $travel_id;            
		}
        else
        {
			$travel_id = Url::get('traveller_id');            
		}
		// L?y ra c?c b?n ghi d? thanh to?n, c?a kh?ch n?y ho?c c?c kh?ch c?ng ph?ng
		$folio_traveller = DB::fetch('select min(id) as id from folio where reservation_traveller_id = '.$travel_id.' AND reservation_room_id='.$rr_id.'','id');
		$folio_id = Url::get('folio_id')?Url::get('folio_id'):'';
        //start: KID them tinh balance
        $balance = 0;
        if(Url::get('folio_id'))
            $balance += $this->payment(Url::get('folio_id'));
        $total_amount_folio = 0;
		$traveller_folios = array();
		$m = 0;
		if($folio_id && $folio_id !='')
        {
			$traveller_folios = DB::fetch_all('SELECT 
													(traveller_folio.type || \'_\' || traveller_folio.invoice_id) as id 
													,traveller_folio.invoice_id
													,traveller_folio.type
													,traveller_folio.date_use
													,traveller_folio.description
													,traveller_folio.amount
													,traveller_folio.percent
													,traveller_folio.reservation_room_id as rr_id
													,traveller_folio.reservation_id as r_id
													,traveller_folio.tax_rate
													,traveller_folio.service_rate
													,traveller_folio.folio_id
                                                    ,reservation_room.status
                                                    ,folio.total as total_amount_folio
											FROM traveller_folio
                                                inner join folio on folio.id=traveller_folio.folio_id
                                                inner join reservation_room on traveller_folio.reservation_room_id = reservation_room.id     
											WHERE 
                                                traveller_folio.folio_id='.$folio_id.$add_pm.
                                                '');
                                                //traveller_folio.reservation_room_id = '.$rr_id.'
                                                //reservation_traveller_id= '.Url::get('traveller_id').' 
                                                //AND traveller_folio.reservation_room_id = '.$rr_id.'
           /** START OANH **/
           /*
           $count_room = DB::fetch(' SELECT count(reservation_room.room_id) 
                                          FROM   reservation_room
                                                 INNER JOIN reservation_traveller ON reservation_room.id = reservation_traveller.reservation_room_id
        								  WHERE  reservation_traveller.id='.$rt_id.'','reservation_id'
                                        );
           //System::debug($count_room); 
           */
            /** END OANH **/
          
			foreach($traveller_folios as $key => $invoice)
            {
				$traveller_folios[$key]['id'] = $invoice['invoice_id'];
				$traveller_folios[$key]['date'] = date('d/m',Date_Time::to_time(Date_Time::convert_orc_date_to_date($invoice['date_use'],'/')));
                $traveller_folios[$key]['full_date'] = date('d/m/Y',Date_Time::to_time(Date_Time::convert_orc_date_to_date($invoice['date_use'],'/')));
                $total_amount_folio = $invoice['total_amount_folio'];
			}
            /** manh them de lay thong tin thanh vien **/
            $info_member = DB::fetch('SELECT id,member_code,member_level_id,create_member_date FROM folio WHERE id='.$folio_id.'');
            //System::debug($info_member);
            $_REQUEST['member_code'] = $info_member['member_code'];
            $_REQUEST['member_level_id'] = $info_member['member_level_id'];
            $_REQUEST['create_member_date'] = $info_member['create_member_date'];
            /** end manh **/
		}
		if($folio_id=='')
        {
			//$cond = ' OR (reservation_traveller_id='.Url::get('traveller_id').')';	// AND traveller_folio.percent=100
			$cond2 = '';	
		}
        else
        {
			//$cond = ' AND 1>0';	
			$cond2 = 'AND traveller_folio.folio_id != '.$folio_id.'';
		}
        /** START - DAT bo sung list cac khoan cua chang truoc trong truong hop doi phong **/
        $reservation_room_check = DB::fetch('select reservation_room.*,room.name as room_name from reservation_room inner join room on room.id = reservation_room.room_id where reservation_room.id = '.$rr_id);
        $rr_ids = $rr_id.($reservation_room_check['change_room_from_rr']?",".$reservation_room_check['change_room_from_rr']:"");
        
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
                                        ,traveller_folio.reservation_room_id as rr_id
										,folio.reservation_id
										,folio.customer_id
										,folio.reservation_traveller_id as traveller_id
                                        ,folio.code as folio_code -- oanh add
									FROM traveller_folio 
										inner join folio ON folio.id = traveller_folio.folio_id
									WHERE 1>0 AND traveller_folio.reservation_room_id in ('.$rr_ids.')
										 '.$cond2.' ');
        /** END - DAT bo sung list cac khoan cua chang truoc trong truong hop doi phong **/
		$folio_other = array();
		foreach($others as $k => $val){
			if(isset($folio_other[$val['type'].'_'.$val['invoice_id']])){
				$folio_other[$val['type'].'_'.$val['invoice_id']]['amount'] += $val['amount'];
                $folio_other[$val['type'].'_'.$val['invoice_id']]['total_amount'] += $val['total_amount'];
				$folio_other[$val['type'].'_'.$val['invoice_id']]['percent'] += $val['percent'];
			}else{
				$folio_other[$val['type'].'_'.$val['invoice_id']] = $val;
				$folio_other[$val['type'].'_'.$val['invoice_id']]['id'] = $val['invoice_id'];
				$folio_other[$val['type'].'_'.$val['invoice_id']]['href'] = '';
                /** START - DAT bo sung list cac khoan cua chang truoc trong truong hop doi phong **/
                $folio_other[$val['type'].'_'.$val['invoice_id']]['rr_id'] = $val['rr_id'];
                /** END - DAT bo sung list cac khoan cua chang truoc trong truong hop doi phong **/
			}
            	
            /** Oanh add them truong hop neu co ma HD thi cho hien thi MaHD, k thi cho hien thi ma Hoa don tam tinh **/
			if($val['customer_id'] == ''){
                 if(isset($val['folio_code'])){
				    $folio_other[$val['type'].'_'.$val['invoice_id']]['href'] .= '</br><a target="_blank" href="'.'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/'.Url::$root.'?page=view_traveller_folio&cmd=invoice&folio_id='.$val['folio_id'].'&traveller_id='.$val['traveller_id'].''.'">_No.F: '.str_pad($val['folio_code'],6,"0",STR_PAD_LEFT).' - Recode:'.$val['reservation_id'].'</a>';
			     }
                 else 
                 {
                    $folio_other[$val['type'].'_'.$val['invoice_id']]['href'] .= '</br><a target="_blank" href="'.'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/'.Url::$root.'?page=view_traveller_folio&cmd=invoice&folio_id='.$val['folio_id'].'&traveller_id='.$val['traveller_id'].''.'">_Ref: '.str_pad($val['folio_id'],6,"0",STR_PAD_LEFT).' - Recode:'.$val['reservation_id'].'</a>';
                 }
                
            }else{
                 if(isset($val['folio_code'])){
				    $folio_other[$val['type'].'_'.$val['invoice_id']]['href'] .= '</br><a target="_blank" href="'.'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/'.Url::$root.'?page=view_traveller_folio&cmd=group_invoice&folio_id='.$val['folio_id'].'&customer_id='.$val['customer_id'].''.'">_ No.F: '.str_pad($val['folio_code'],6,"0",STR_PAD_LEFT).' - Recode:'.$val['reservation_id'].'</a>';
			     }
                 else
                 {
                    $folio_other[$val['type'].'_'.$val['invoice_id']]['href'] .= '</br><a target="_blank" href="'.'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/'.Url::$root.'?page=view_traveller_folio&cmd=group_invoice&folio_id='.$val['folio_id'].'&customer_id='.$val['customer_id'].''.'">_ Ref.F: '. str_pad($val['folio_id'],6,"0",STR_PAD_LEFT).' - Recode:'.$val['reservation_id'].'</a>';
                 }
            }
            /** end Oanh **/
		}	
		// L?y ra c?c h?a don m? kh?ch n?y d? thanh t?o
		$folio = DB::fetch_all('select folio.id as id, folio.code as folio_code  
										,CONCAT(traveller.first_name,CONCAT(\' \',traveller.last_name)) as name
										,CONCAT(folio.reservation_traveller_id,CONCAT(\' \',folio.num)) as code
										,folio.num
                                        ,folio.total
                                        ,sum(payment.amount*payment.exchange_rate) as amount 
								from 
									folio 
									inner join reservation_traveller on reservation_traveller.id = folio.reservation_traveller_id 
									left outer join traveller on reservation_traveller.traveller_id = traveller.id
                                    left join payment ON payment.folio_id = folio.id 
								where 
									1>0 AND folio.reservation_traveller_id = '.Url::get('traveller_id').' 
                                    and folio.customer_id is null
                               GROUP BY folio.id
                                     ,folio.code 
                                     ,CONCAT(traveller.first_name,CONCAT(\' \',traveller.last_name))
                                     ,CONCAT(folio.reservation_traveller_id,CONCAT(\' \',folio.num))  
                                     ,folio.num
                                     ,folio.total          
                                    ');
		//end
		$_REQUEST['total_payment'] = $total_payment;
//--------------------------------- thong tin ve` reservation------------------------------------------
		$sql='select 
				reservation_room.*,
				traveller.first_name,
				traveller.last_name,
				traveller.nationality_id,
				traveller.id as traveller_id,
				reservation_type.show_price,
				reservation_type.name as reservation_type_name,
				room.name as room_name,
				customer.address,
				customer.name as customer_name,
				customer.id as customer_id
			from 
				reservation_room 
				inner join reservation ON reservation.id = reservation_room.reservation_id
				inner join room on room.id=reservation_room.room_id
				left outer join customer on customer.id = reservation.customer_id
				left outer join reservation_type on reservation_type.id=reservation_room.reservation_type_id
				left outer join reservation_traveller on reservation_traveller.reservation_room_id=reservation_room.id
				left outer join traveller on reservation_traveller.traveller_id=traveller.id
				';
		//============================Thong tin hoa don moi-------------------------------//
		$others = $this->get_reservation_other($rr_id);
		$row = DB::fetch($sql.' where reservation_room.id='.$rr_id.'');
		$row['add_payment'] = 0;
		$add_payments = array();
		if(Url::get('cmd') == 'create_folio' && $folio_id!=''){
			$add_payments = $this->get_add_paid(Url::get('traveller_id'),Url::get('folio_id'));    
			if(!empty($add_payments)){
				$row['add_payment'] = 1;
			}
		}
		$row['paid_group'] = $paid_group;
		//------------------------------------------Kh?ch------------------------------------------------------------		
		$travellers = $this->get_reservation_traveller($rr_id);
        $_REQUEST['member'] = array();
        foreach($travellers as $tra=>$veller)
        {
            $_REQUEST['member'][$veller['id']]['create_member_date'] = Date_Time::to_orc_date(date('d/m/Y'));
            $member_traveller = DB::fetch("SELECT member_code,member_level_id FROM traveller inner join reservation_traveller on reservation_traveller.traveller_id=traveller.id WHERE reservation_traveller.id=".$veller['id']);
            $_REQUEST['member'][$veller['id']]['member_code'] = isset($member_traveller['member_code'])?$member_traveller['member_code']:'';
            $_REQUEST['member'][$veller['id']]['member_level_id'] = isset($member_traveller['member_level_id'])?$member_traveller['member_level_id']:'';
        }
        $this->map['member_list'] = String::array2js($_REQUEST['member']);
		$travellers_ids = $travellers;
	//--------------------------------------lay exchange------------------------------------------------
		require_once 'packages/hotel/packages/reception/modules/CreateTravellerFolio/get_reservation_room.php';
        /** START - DAT bo sung list cac khoan cua chang truoc trong truong hop doi phong **/
		//$items = get_reservation_room_detail($rr_id,$folio_other);
        $arr_rr_ids = explode(',',$rr_ids);
        $items = array();
        $check_room_not_null = true;
        foreach($arr_rr_ids as $k => $v)
        {
            if(DB::exists('select * from reservation_room where id = '.$v))
                $items += get_reservation_room_detail($v,$folio_other);
            if(DB::exists('select * from reservation_room where room_id is null AND id = '.$v))
                $check_room_not_null = false;
        }
        if($check_room_not_null == false)
        {
            echo Portal::language('chua_co_phong_khong_the_tao_thanh_toan');
            exit();
        }
        /** END - DAT bo sung list cac khoan cua chang truoc trong truong hop doi phong **/
			$sql = '
			SELECT
				rt.id,CONCAT(t.first_name,CONCAT(\' \',t.last_name)) AS name
			FROM
				reservation_traveller rt
				left JOIN traveller t ON t.id = rt.traveller_id
			WHERE
				rt.reservation_room_id  = '.$row['id'].'
			ORDER BY
				t.first_name
		';
		$row['reservation_traveller_id_list'] = array('...')+String::get_list(DB::fetch_all($sql));
		
		$traveller_id[0] = '----Traveller----';
		$traveller_id = $traveller_id + String::get_list($travellers_ids);
        //System::debug($travellers_ids);
		$others_id[0]  = '----Order----';
		$others_id = $others_id + String::get_list($others);
		foreach ($items as $l => $t)
        {
            if ($t['date'] != '')
            {
                $items[$l]['date'] = date('d/m', Date_time::to_time($t['date']));
            }
            $items[$l]['full_date'] = $t['date'];
        }
        
        /** THL **/
//        foreach($items as $key => $value)
//        {
//            $items[$key]['amount'] = (number_format(System::calculate_number($value['amount'])*(1+$value['service_rate']/100)*(1+$value['tax_rate']/100),2));
//            $items[$key]['net_amount'] = $items[$key]['amount'];
//        }
//        foreach($traveller_folios as $key => $value)
//        {
//            $traveller_folios[$key]['amount'] = (number_format($value['amount']*(1+$value['service_rate']/100)*(1+$value['tax_rate']/100),2));
//        }
        /** THL **/
        /** Start: daund làm tròn hóa đơn*/
        foreach($items as $key => $value)
        {
            
            $amount_n2d = round($value['amount']*(1+$value['service_rate']/100)*(1+$value['tax_rate']/100));
            $items[$key]['amount'] = System::display_number($amount_n2d);
            $items[$key]['net_amount'] = $items[$key]['amount'];
        }
        foreach($traveller_folios as $key => $value)
        {
            $traveller_folios[$key]['amount'] = (number_format($value['amount']*(1+$value['service_rate']/100)*(1+$value['tax_rate']/100),2));
        }
        /** End: daund làm tròn hóa đơn*/
        
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
                                                                                                                        and reservation_traveller.id!='.Url::get('traveller_id').'
                                                                                                                        '));
        /** lay reservation_id va customer_id cua phong dang duoc xem **/
        $group_folio_data = DB::fetch('select 
                                            reservation.id as reservation_id
                                            ,customer.id as customer_id
                                        from
                                            reservation_traveller
                                            inner join reservation on reservation.id=reservation_traveller.reservation_id
                                            inner join customer on customer.id=reservation.customer_id
                                        where
                                            reservation_traveller.id='.Url::get('traveller_id').'
                                        ');
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
                    									1>0 AND reservation_room.reservation_id = '.$group_folio_data['reservation_id'].'
                                                        AND folio.reservation_traveller_id != '.Url::get('traveller_id').'  
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
        
        $this->map['list_folio_group_in_recode'] = $this->get_folio_other($group_folio_data['reservation_id'],$group_folio_data['customer_id']);
        
        $this->map['last_time'] = time();
        
		$this->parse_layout('split',$row+array(
			'items'=>$items,
			'items_js'=>String::array2js($items),
			'folio_other_js'=>String::array2js($folio_other),
			'traveller_folios_js'=>String::array2js($traveller_folios),
			'travellers_id_list'=>$traveller_id,
			'travellers'=>$travellers_ids,
			'order_ids_list'=>$others_id,
			'add_payments'=>$add_payments,
			'folio'=>$folio,
            'balance'=>$balance,
            'total_amount_folio'=>$total_amount_folio,
            'reservation_id_group'=>$group_folio_data['reservation_id'],
            'customer_id_group'=>$group_folio_data['customer_id'],
		)+$this->map);
        
        
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
function get_traveller_folio($rt_id,$rr_id,$folio_id)
{
		$cond = ' AND traveller_folio.folio_id = '.$folio_id.'';
		$invoice_old = DB::fetch_all('
			SELECT 
				CONCAT(traveller_folio.reservation_traveller_id,CONCAT(\'_\',CONCAT(traveller_folio.type,CONCAT(\'_\',CONCAT(traveller_folio.invoice_id,CONCAT(\'_\',traveller_folio.add_payment)))))) as id
				,traveller_folio.reservation_traveller_id
				,traveller_folio.type,traveller_folio.invoice_id
				,traveller_folio.id as traveller_folio_id 
				,traveller_folio.add_payment 
			FROM 
				traveller_folio 
			WHERE 1=1
                and traveller_folio.add_payment != 1
				--and reservation_traveller_id = '.$rt_id.'
				--AND traveller_folio.reservation_room_id = '.$rr_id.'
				'.$cond.'');
		return 	$invoice_old;	

}
 	function get_reservation_traveller($rr_id)
     {
		$sql_traveller = 'SELECT 
					reservation_traveller.id
					,traveller.first_name || \' \' || traveller.last_name as name
				FROM 	
					reservation_traveller
					INNER JOIN traveller ON reservation_traveller.traveller_id = traveller.id
					INNER JOIN reservation_room ON reservation_traveller.reservation_room_id = reservation_room.id
				WHERE 
					reservation_room.id = '.$rr_id.'
					AND (reservation_traveller.status=\'CHECKIN\' or reservation_traveller.status=\'CHECKOUT\')
					';
		$travellers = DB::fetch_all($sql_traveller);			
		return $travellers;	
	}
	function get_reservation_other($rr_id){
		return $items = DB::fetch_all('select reservation_room.id,CONCAT(reservation_room.id,CONCAT(\'_\',CONCAT(\''.Portal::language('room').'\',room.name))) as name 
									from reservation_room 
										INNER JOIN room ON reservation_room.room_id = room.id
									where reservation_room.status=\'CHECKIN\' 
										AND reservation_room.id <>\''.$rr_id.'\' 
										AND portal_id=\''.PORTAL_ID.'\'
										
										');
										//AND '.time().' > reservation_room.time_in AND '.time().' < reservation_room.time_out	
	}
	function get_add_paid($traveller_id,$folio_id){
		$add_rr = array();
		$adds = DB::fetch_all('SELECT 
						traveller_folio.id
						,traveller_folio.reservation_room_id as rr_id
						,traveller_folio.amount as add_amount
						,room.name as room_name
						,traveller_folio.reservation_traveller_id as rt_id
						,traveller_folio.tax_rate
						,traveller_folio.service_rate
						,traveller_folio.type
                        --start:KID them de check xoa khoan thanh toan
                        ,reservation_room.status
						--end:KID them de check xoa khoan thanh toan
                        ,reservation_room.time_in
						,reservation_room.time_out
					FROM traveller_folio 
						INNER JOIN reservation_room ON reservation_room.id = traveller_folio.reservation_room_id 
						INNER JOIN room ON room.id = reservation_room.room_id 
					WHERE reservation_traveller_id = '.$traveller_id.' AND add_payment=1 AND folio_id='.$folio_id.'');
                 
		if(!empty($adds)){
			$total_room = 0; $total_discount = 0; $total_add = 0; $total_deposit=0;
			$add_rr = array();
			foreach($adds as $k => $ad){
				if(!isset($total_room_add[$ad['rr_id']])){
					$total_room_add[$ad['rr_id']] = 0;
					$total_discount_add[$ad['rr_id']]= 0;
					$total_deposit_add[$ad['rr_id']]= 0;
					$total_other_add[$ad['rr_id']] = 0;
				}
			}
			foreach($adds as $a => $add){
				if($add['type'] == 'ROOM'){
					$total_room_add[$add['rr_id']] += $add['add_amount'];
				}else if($add['type'] == 'DISCOUNT'){
					$total_discount_add[$add['rr_id']] += $add['add_amount'];	
				}else if($add['type'] == 'DEPOSIT'){
					$total_deposit_add[$add['rr_id']] += $add['add_amount'];	
				}else{
					$service_add = ($add['add_amount']*$add['service_rate']/100);
					$tax_add = ($add['add_amount'] + $service_add)*$add['tax_rate']/100;
					$total_other_add[$add['rr_id']] += $add['add_amount'] + $service_add + $tax_add; 	
				}
				if(isset($add_rr[$add['rr_id']])){
					if($add['type'] == 'ROOM'){
						$add_rr[$add['rr_id']]['service_rate'] = $add['service_rate'];
						$add_rr[$add['rr_id']]['tax_rate'] = $add['tax_rate'];	
					}
					$add_rr[$add['rr_id']]['total_room'] = $total_room_add[$add['rr_id']];
					$add_rr[$add['rr_id']]['total_discount'] = $total_discount_add[$add['rr_id']];
					$add_rr[$add['rr_id']]['total_deposit'] = $total_deposit_add[$add['rr_id']];
					$add_rr[$add['rr_id']]['total_other'] = $total_other_add[$add['rr_id']];
				}else{
					$add_rr[$add['rr_id']] = $add;
					$add_rr[$add['rr_id']]['id'] = $add['rr_id'];
					if($add['type'] == 'ROOM'){
						$add_rr[$add['rr_id']]['service_rate'] = $add['service_rate'];
						$add_rr[$add['rr_id']]['tax_rate'] = $add['tax_rate'];	
					}
					$add_rr[$add['rr_id']]['total_room'] = $total_room_add[$add['rr_id']];
					$add_rr[$add['rr_id']]['total_discount'] = $total_discount_add[$add['rr_id']];
					$add_rr[$add['rr_id']]['total_deposit'] = $total_deposit_add[$add['rr_id']];
					$add_rr[$add['rr_id']]['total_other'] = $total_other_add[$add['rr_id']];
				}
				$add_rr[$add['rr_id']]['time_in'] = date('d/m',$add['time_in']);
				$add_rr[$add['rr_id']]['time_out'] = date('d/m',$add['time_out']);
			}
			foreach($add_rr as $b => $arr){
					$reservation_this = DB::fetch('SELECT id,foc,foc_all from reservation_room where id='.$arr['rr_id'].'');
					if($reservation_this['foc']!=''){
						$arr['total_room'] = 0;
					}
					$add_rr[$b]['id'] = $arr['rr_id'];
					$add_rr[$b]['decription'] = Portal::language('add_payment_for_room').' '.$arr['room_name'].'('.$arr['time_in'].' - '.$arr['time_out'].')';
					$total_room_aftex_discount = $arr['total_room'] - $arr['total_discount'];
					$service_aftex_discount = $total_room_aftex_discount * $arr['service_rate']/100;
					$tax_aftex_discount = ($total_room_aftex_discount + $service_aftex_discount)*$arr['tax_rate']/100;
					$add_rr[$b]['add_amount'] = number_format($total_room_aftex_discount + $service_aftex_discount + $tax_aftex_discount + $arr['total_other'] - $arr['total_deposit'],2);
					if($reservation_this['foc_all']==1){
						$add_rr[$b]['add_amount'] = 0;
					}
			}
		}
		return $add_rr;
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
                $log_id = System::log('DELETE','DELETE GUEST\'S FOLIO ID #No.F'.str_pad($folio_code,6,"0",STR_PAD_LEFT),"User id delete: ".User::id()."<br/> Time: ".date('H:i d/m/Y'),$folio);
            }else{
                $log_id = System::log('DELETE','DELETE GUEST\'S FOLIO ID #Ref'.str_pad($folio,6,"0",STR_PAD_LEFT),"User id delete: ".User::id()."<br/> Time: ".date('H:i d/m/Y'),$folio);
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
