<?php 
class CreateTravellerFolio extends Module
{
	function CreateTravellerFolio($row)
	{
	    /** manh: check last time **/
        if(Url::get('check_last_time')){
            $data = array('status'=>'','user'=>'','time'=>'');
            $last_time = DB::fetch('select last_time,lastest_user_id as user_id from folio where id='.Url::get('folio_id'));
            if($last_time['last_time']!=0 and $last_time['last_time']>Url::get('last_time')){
                $data = array('status'=>'error','user'=>$last_time['user_id'],'time'=>date('H:i:s d/m/Y',$last_time['last_time']));
            }
            echo json_encode($data);
            exit();
        }
        /** end manh **/
	    if(Url::get('delete_items_split_folio') and Url::get('reservation_room_id')){
	       if(DB::exists('select id from reservation_room where id='.Url::get('reservation_room_id').' and status=\'CHECKOUT\'')){
	           echo 1; // phong da CO khong dc xoa hoa don
	       }else{
	           echo 2;
	       }
           exit();
	    }
	    if(Url::get('change_traveller_folio') and Url::get('folio_id')){
	       $check = 1; // trang thai chua duoc doi ten khach - khach da check-out hoac khong ton tai
           
	       if(Url::get('folio_id') and DB::exists('select reservation_traveller.id from reservation_traveller inner join reservation_room on reservation_traveller.reservation_room_id=reservation_room.id where reservation_room.status=\'CHECKIN\' and reservation_traveller.id='.Url::get('change_traveller_folio')))
           {
                $folio = DB::fetch('select
                                        folio.id
                                        ,folio.total
                                        ,sum(payment.amount*payment.exchange_rate) as amount
                                    from 
									   folio
                                       left join payment ON payment.folio_id = folio.id
                                    where
                                        folio.id='.Url::get('folio_id').'
                                    group by
                                        folio.id
                                        ,folio.total
                                     ');
                //System::debug($folio);
                $reservation_id = DB::fetch('select reservation_id from reservation_traveller where id='.Url::get('change_traveller_folio'),'reservation_id');
                if(($folio['total']-$folio['amount'])>1000){
                    if(Url::get('is_group_folio')){
                        DB::update('folio',array('reservation_traveller_id'=>Url::get('change_traveller_folio')),'folio.id='.Url::get('folio_id'));
                    }else{
                        $r_r_room = DB::fetch('select reservation_room_id from reservation_traveller where id='.Url::get('change_traveller_folio'),'reservation_room_id');
                        
                        DB::update('folio',array('reservation_traveller_id'=>Url::get('change_traveller_folio'),'reservation_id'=>$reservation_id),'folio.id='.Url::get('folio_id'));
                        $traveller_folio = DB::fetch_all('select * from traveller_folio where folio_id='.Url::get('folio_id'));
                        foreach($traveller_folio as $key=>$value){
                            if($value['reservation_room_id']!=$r_r_room){
                                DB::update('traveller_folio',array('reservation_traveller_id'=>Url::get('change_traveller_folio'),'reservation_id'=>$reservation_id,'add_payment'=>1),'traveller_folio.id='.$value['id']);
                            }else{
                                DB::update('traveller_folio',array('reservation_traveller_id'=>Url::get('change_traveller_folio'),'reservation_id'=>$reservation_id,'add_payment'=>0),'traveller_folio.id='.$value['id']);
                            }
                        }
                    }
                    $check=2; // update thanh cong
                }else{
                    $check=3; // folio da thanh toan (folio mau do)
                }
           }
           echo $check;
           exit();
	    }
        
        Module::Module($row);
		switch(URL::get('cmd'))
		{
			case 'create_folio':
				if(Url::get('rr_id')){
					require_once 'forms/split.php';
					$this->add_form(new CreateTravellerFolioForm());break;	
				}
			case 'add_payment':
				if(Url::get('add_payment') && Url::get('traveller_id')){
					require_once 'forms/split.php';
					$this->add_form(new CreateTravellerFolioForm());break;
				}else{
					Url::redirect();
				}
			case 'group_folio':
				if(Url::get('id')){
                    require_once 'forms/split_group.php';
					$this->add_form(new CreateGroupFolioForm());break;	
				}else{
					echo 'ko co id';	
					exit();
				}
		}
	}
}
?>