<?php
class MargeRoomInvoiceForm extends Form
{
	function MargeRoomInvoiceForm()
	{
		Form::Form('MargeRoomInvoiceForm');
		$this->add('MargeRoomInvoice.invoice_id',new IDType(true,'invoice','reservation_room'));
		$this->add('MargeRoomInvoice.other_invoice_id',new IDType(true,'other_invoice','reservation_room'));
		$this->link_js('packages/core/includes/js/multi_items.js');
	}
	function on_submit()
	{
		if($this->check())
		{			
			$error = false;
			$reservation_id = 0;
			if(Url::get('act')=='marge_invoice'){
				if(isset($_REQUEST['MargeRoomInvoice']))
				{				
					/**
                        vd ghép hóa đơn B vào A
                        th ghep hoa don chi check phong B
                        th1: B da tao hoa don (ca phong ca nhom ko cho ghep)
                        th2: rcode chua phong B co dat coc nhom. neu rcode do chi con 1 phong B chua CO va khoan dat coc nhom chua duoc tao hoa don het thi khong cho ghep
                     **/
                    /** Kimtan them check hoa don phu da co khoan nao tao folio hay chua **/
                    $check = true;
                    $invoice_false = '';
                    $check_deposit_group = true;
                    $invoice_false1 = '';
                    $check_mice = true;
                    foreach($_REQUEST['MargeRoomInvoice'] as $key=>$record)
					{
		               if(DB::fetch('select * from traveller_folio where reservation_room_id = '.$record['other_invoice_id']))
                       {
                           $check = false;
                           $invoice_false .= $record['other_invoice_id'].',';
                       }
                       else
                       {
                            $r_id = DB::fetch('select reservation_id from reservation_room where id = '.$record['other_invoice_id'],'reservation_id');
                            $r_id_2 = DB::fetch('select reservation_id from reservation_room where id = '.$record['invoice_id'],'reservation_id');
                            if(($r_id and DB::fetch('select mice_reservation_id from reservation where id = '.$r_id,'mice_reservation_id')!='') or ($r_id_2 and DB::fetch('select mice_reservation_id from reservation where id = '.$r_id_2,'mice_reservation_id')!=''))
                            {
                                $check_mice = false;
                                $invoice_false1 .= $record['other_invoice_id'].',';
                            }
                            else
                            {
                                $check_deposit_group = true;
                                if($r_id and DB::fetch('select * from payment where reservation_id = '.$r_id.' and type_dps=\'GROUP\''))
                                {
                                    $sql = 'select count(*) as count from reservation_room where reservation_id = '.$r_id;
                                    $check_count = DB::fetch($sql.' and (status!=\'CANCEL\')','count');
                                    $check_count2 = DB::fetch($sql.' and (status=\'CHECKIN\' or status=\'BOOKED\')','count');
                                    $deposit_group = DB::fetch('select sum(amount) as total from payment where reservation_id = '.$r_id.' and type_dps=\'GROUP\'','total');
                                    if($check_count==1)
                                    {
                                        if($deposit_group>0)
                                        {
                                            $check_deposit_group=false;
                                            $invoice_false1 .= $record['other_invoice_id'].',';
                                        }
                                    }
                                    else
                                    {
                                        if($check_count2==1)
                                        {
                                            $detail_dps_group=DB::fetch('select sum(amount) as total from traveller_folio where traveller_folio.type=\'DEPOSIT_GROUP\' and traveller_folio.reservation_id ='.$r_id,'total');
                                            if($deposit_group>$detail_dps_group)
                                            {
                                                $check_deposit_group=false;
                                                $invoice_false1 .= $record['other_invoice_id'].',';
                                            }
                                        }
                                    }
                                }
                            }
                       }
                    }
                    if($check==false)
                    {
                        $error = true;
                        $this->error('invoice_id',$invoice_false.' '.Portal::language('da_duoc_tao_hoa_don_khong_the_ghep_lam_so_HD_phu'));
                    }
                    else
                    {
                    /** end Kimtan them check hoa don phu da co khoan nao tao folio hay chua **/
                        if($check_mice==false)
                        {
                            $error = true;
                            $this->error('invoice_id',$invoice_false1.' '.Portal::language('da_ton_tai_trong_mice_khong_the_tach_ghep'));
                        }else
                        {
                            if($check_deposit_group==false)
                            {
                                $error = true;
                                $this->error('invoice_id',$invoice_false1.' '.Portal::language('ton_tai_dat_coc_nhom_chua_tao_hoa_don_va_chi_con_1_phong_nen_khong_the_ghep_lam_so_HD_phu'));
                            }
                            else
                            {
                                foreach($_REQUEST['MargeRoomInvoice'] as $key=>$record)
            					{
            						$invoice_id = $record['invoice_id'];
            						$other_invoice_id = $record['other_invoice_id'];
                                    //echo $invoice_id.'-'.$other_invoice_id;exit();
            						if($invoice_id and $other_invoice_id and $reservation_room = DB::fetch('select reservation_room.* from reservation_room INNER JOIN reservation  on reservation.id = reservation_room.reservation_id where  reservation_room.id='.$invoice_id.' and reservation.portal_id=\''.PORTAL_ID.'\'') and $other_reservation_room = DB::fetch('select reservation_room.* from reservation_room inner join reservation on reservation.id = reservation_room.reservation_id where reservation_room.id='.$other_invoice_id.' and reservation.portal_id=\''.PORTAL_ID.'\'')){
            							DB::update('reservation_room',array('reservation_id'=>$reservation_room['reservation_id']),'id='.$other_reservation_room['id']);
            							DB::update('room_status',array('reservation_id'=>$reservation_room['reservation_id']),'reservation_room_id='.$other_reservation_room['id']);
            							DB::update('reservation_traveller',array('reservation_id'=>$reservation_room['reservation_id']),'reservation_room_id='.$other_reservation_room['id']);
                                        if(!DB::exists('select * from reservation_room where reservation_id = '.$other_reservation_room['reservation_id'].'')){
            								echo 'Deleting...';
            								DB::delete('reservation','id = '.$other_reservation_room['reservation_id']);
            							}
								/** Daund: update last_time, lastest_user_id reservation */
                                				DB::update('reservation', array('last_time' => time(), 'lastest_user_id'=> User::id()), 'id='.$reservation_room['reservation_id']);
            							$title = 'Marge invoice #'.$invoice_id.' to #'.$other_invoice_id.'';
            							$description = 'Marge invoice #'.$invoice_id.' to #'.$other_invoice_id.'';
            							$log_id = System::log('Marge',$title,$description,$invoice_id);
            							$reservation_id = $reservation_room['reservation_id'];
                                        System::history_log('RECODE',$reservation_id,$log_id);
                                        DB::update('reservation',array('last_time'=>time(),'lastest_user_id'=>User::id()),'id='.$reservation_id);
            						}else{
            							$error = true;
            							$this->error('invoice_id','invoice_does_not_exists');
            						}
            					}
                            }
                        }
                    }
				}
			}else{
				if(isset($_REQUEST['MargeRoomInvoice']))
				{			
					$check = true;
                    $invoice_false = '';
                    $check_deposit_group = true;
                    $invoice_false1 = '';
                    $check_mice = true;
                    foreach($_REQUEST['MargeRoomInvoice'] as $key=>$record)
					{
		               if(DB::fetch('select * from traveller_folio where reservation_room_id = '.$record['invoice_id']))
                       {
                           $check = false;
                           $invoice_false .= $record['invoice_id'].',';
                       }
                       else
                       {
                            $r_id = DB::fetch('select reservation_id from reservation_room where id = '.$record['invoice_id'],'reservation_id');
                            if($r_id and DB::fetch('select mice_reservation_id from reservation where id = '.$r_id,'mice_reservation_id')!='')
                            {
                                $check_mice = false;
                                $invoice_false1 .= $record['invoice_id'].',';
                            }
                            else
                            {
                                $check_deposit_group = true;
                                if($r_id and DB::fetch('select * from payment where reservation_id = '.$r_id.' and type_dps=\'GROUP\''))
                                {
                                    $sql = 'select count(*) as count from reservation_room where reservation_id = '.$r_id;
                                    $check_count = DB::fetch($sql.' and (status!=\'CANCEL\')','count');
                                    $check_count2 = DB::fetch($sql.' and (status=\'CHECKIN\' or status=\'BOOKED\')','count');
                                    $deposit_group = DB::fetch('select sum(amount) as total from payment where reservation_id = '.$r_id.' and type_dps=\'GROUP\'','total');
                                    if($check_count==1)
                                    {
                                        if($deposit_group>0)
                                        {
                                            $check_deposit_group=false;
                                            $invoice_false1 .= $record['invoice_id'].',';
                                        }
                                    }
                                    else
                                    {
                                        if($check_count2==1)
                                        {
                                            $detail_dps_group=DB::fetch('select sum(amount) as total from traveller_folio where traveller_folio.type=\'DEPOSIT_GROUP\' and traveller_folio.reservation_id ='.$r_id,'total');
                                            if($deposit_group>$detail_dps_group)
                                            {
                                                $check_deposit_group=false;
                                                $invoice_false1 .= $record['invoice_id'].',';
                                            }
                                        }
                                    }
                                }
                            }
                       }
                       //exit();
                    }
                    if($check==false)
                    {
                        $error = true;
                        $this->error('invoice_id',$invoice_false.' '.Portal::language('da_duoc_tao_hoa_don_khong_the_tach'));
                    }
                    else
                    {
                    /** end Kimtan them check hoa don phu da co khoan nao tao folio hay chua **/
                        if($check_mice==false)
                        {
                            $error = true;
                            $this->error('invoice_id',$invoice_false1.' '.Portal::language('da_ton_tai_trong_mice_khong_the_tach_ghep'));
                        }
                        else
                        {
                            if($check_deposit_group==false)
                            {
                                $error = true;
                                $this->error('invoice_id',$invoice_false1.' '.Portal::language('ton_tai_dat_coc_nhom_chua_tao_hoa_don_va_chi_con_1_phong_nen_khong_the_tach'));
                            }
                            else
                            {
                                $new_reservation_id = 0;	
            					foreach($_REQUEST['MargeRoomInvoice'] as $key=>$record)
            					{
            						$invoice_id = $record['invoice_id'];
            						if($invoice_id  and $reservation_room = DB::select('reservation_room','id='.$invoice_id)){
            							if(DB::exists('select id,reservation_id from reservation_room where reservation_id='.$reservation_room['reservation_id'].' and id<>'.$invoice_id)){
            								if($new_reservation_id==0){
            									$old_reservation = DB::select('reservation','id='.$reservation_room['reservation_id']);
            									$new_reservation_id = DB::insert('reservation', 
            										array(
            											'user_id'=>Session::get('user_id'),
            											'portal_id'=>PORTAL_ID,
            											'time'=>time(),
            											'customer_id'=>$old_reservation['customer_id'],
            											'tour_id'=>$old_reservation['tour_id'],
                                                        'cut_of_date'=>$old_reservation['cut_of_date']
            										)
            									);
									/** Daund: update last_time, lastest_user_id reservation */
                                    					DB::update('reservation', array('last_time' => time(), 'lastest_user_id'=> User::id()), 'id='.$reservation_room['reservation_id']);
            								}
            								DB::update('reservation_room',array('reservation_id'=>$new_reservation_id),'id='.$invoice_id);
                                            DB::update('room_status',array('reservation_id'=>$new_reservation_id),'reservation_room_id='.$invoice_id);
                                            DB::update('reservation_traveller',array('reservation_id'=>$new_reservation_id),'reservation_room_id='.$invoice_id);
                                            //giap.ln them truong hop tach phong thi lay ra nhung hoa don cua phong do ra theo phong tach
                                            //lay ra nhung folio thanh toan cua reservation  
                                            $sql = "SELECT folio.*
                                                    FROM folio 
                                                    WHERE folio.reservation_id=".$reservation_room['reservation_id'];
                                            $items = DB::fetch_all($sql);
                                            foreach($items as $row)
                                            {
                                                if($row['reservation_room_id']==$invoice_id)//neu la hoa don cua phong dang tach thi chuyen ve phong do
                                                {
                                                    DB::update('folio',array('reservation_id'=>$new_reservation_id),'id='.$row['id']);
                                                    DB::update('traveller_folio',array('reservation_id'=>$new_reservation_id),'folio_id='.$row['id']);
                                                } 
                                                else
                                                {
                                                    if($row['reservation_room_id']=='')
                                                    {
                                                        if(DB::exists("SELECT * FROM traveller_folio WHERE folio_id=".$row['id']." AND reservation_room_id!=".$invoice_id))
                                                        {
                                                            //giap.ln chua biet phai lam the nao?
                                                            //co the coi nhu nhung khoan tach ra da duoc thanh toan ho
                                                        }
                                                        else
                                                        {
                                                            DB::update('folio',array('reservation_id'=>$new_reservation_id),'id='.$row['id']);
                                                            DB::update('traveller_folio',array('reservation_id'=>$new_reservation_id),'folio_id='.$row['id']);
                                                        }
                                                    }
                                                }
                                            }
                                            //end giap.ln 
            								$title = 'Splited invoice #'.$invoice_id.'';
            								$description = 'Splited invoice #'.$invoice_id.'';
            								$log_id = System::log('Split',$title,$description,$invoice_id);
            								$reservation_id = $new_reservation_id;
                                            System::history_log('RECODE',$reservation_id,$log_id);
                                            DB::update('reservation',array('last_time'=>time(),'lastest_user_id'=>User::id()),'id='.$reservation_id);
            							}else{
            								$error = true;
            								$this->error('invoice_id','invoice_can_not_be_splited_because_has_only_one_room');
            							}
            						}else{
            							$error = true;
            							$this->error('invoice_id','invoice_does_not_exists');
            						}
            					}
                            }
                        }
                    }
				}
			}
			if($error==false){
				Url::redirect('reservation',array('cmd'=>'edit','id'=>$reservation_id));
			}
		}
	}	
	function draw()
	{
		if(!isset($_REQUEST['MargeRoomInvoice']))
		{
			$MargeRoomInvoice = array(
				''=>array(
					'id'=>'',
					'invoice_id'=>'',
					'other_invoice_id'=>''
				)
			);
			$_REQUEST['MargeRoomInvoice'] = $MargeRoomInvoice;
		}
        
		$this->parse_layout('edit');
	}
}
?>
