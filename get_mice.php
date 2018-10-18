<?php 
    define( 'ROOT_PATH', strtr(dirname( __FILE__ ) ."/",array('\\'=>'/')));
    set_include_path(ROOT_PATH);
    require_once 'packages/core/includes/system/config.php';
    
    switch($_REQUEST['cmd'])
    {
        case "update_extra":
        {
            $id = $_REQUEST['id'];
            $r_r_id = $_REQUEST['r_r_id'];
            echo json_encode(update_extra($id,$r_r_id)); break;
        }
        case "split_mice":
        {
            $id = $_REQUEST['id'];
            $key = $_REQUEST['key'];
            $mice_id = $_REQUEST['mice_id'];
            echo json_encode(split_mice($mice_id,$key,$id)); break;
        }
        case "get_list_mice":
        {
            echo json_encode(get_list_mice()); break;
        }
        case "join_mice":
        {
            $id = $_REQUEST['id'];
            $key = $_REQUEST['key'];
            $mice_id = $_REQUEST['mice_id'];
            echo json_encode(join_mice($mice_id,$key,$id)); break;
        }
        case "check_folio_reservation":
        {
            $id = $_REQUEST['id'];
            echo json_encode(check_folio_reservation($id)); break;
        }
        default: echo '';break;
    }
    
    function update_extra($id,$r_r_id)
    {
        DB::update('extra_service_invoice',array('reservation_room_id'=>$r_r_id),'id='.$id);
        return $id;
    }
    function split_mice($mice_id,$key,$id)
    {
        $check = 0;
        if(DB::exists("SELECT id FROM mice_reservation WHERE close=1 AND id=".$mice_id))
        {
            return 2;
        }
        if($key=='REC')
        {
            require_once 'packages/hotel/packages/mice/modules/MiceReservation/db.php';
            $reservation_room = DB::fetch_all('SELECT id FROM reservation_room WHERE reservation_id='.$id);
            foreach($reservation_room as $key=>$value)
            {
                $invoice = MiceReservationDB::get_total_room($value['id']);
                foreach($invoice as $k=>$v)
                {
                    if(DB::exists('SELECT id from mice_invoice_detail where invoice_id='.$v['id'].' AND type=\''.$v['type'].'\''))
                    {
                        $check = 1;
                        break;
                    }
                }
                if($check == 1)
                {
                    break;
                }
            }
            if($check==0)
            {
                DB::update('reservation',array('mice_reservation_id'=>'','mice_action_module'=>0),'id='.$id);
            }
        }
        elseif($key=='EXS')
        {
            $extra_service_invoice_detail = DB::fetch_all("SELECT id FROM extra_service_invoice_detail where invoice_id=".$id);
            foreach($extra_service_invoice_detail as $key=>$value)
            {
                if(DB::exists('SELECT id from mice_invoice_detail where invoice_id='.$value['id'].' AND type=\'EXTRA_SERVICE\''))
                {
                    $check = 1;
                    break;
                }
            }
            if($check==0)
            {
                DB::update('extra_service_invoice',array('mice_reservation_id'=>'','mice_action_module'=>0),'id='.$id);
            }
            
        }
        elseif($key=='TICKET')
        {
            if(DB::exists('SELECT id from mice_invoice_detail where invoice_id='.$id.' AND type=\'TICKET\''))
            {
                $check = 1;
            }
            if($check==0)
            {
                DB::update('ticket_reservation',array('mice_reservation_id'=>'','mice_action_module'=>0),'id='.$id);
            }
            
        }
        elseif($key=='RES')
        {
            if(DB::exists('SELECT id from mice_invoice_detail where invoice_id='.$id.' AND type=\'BAR\''))
            {
                $check = 1;
            }
            if($check==0)
            {
                DB::update('bar_reservation',array('mice_reservation_id'=>'','mice_action_module'=>0),'id='.$id);
            }
            
        }
        elseif($key=='VENDING')
        {
            if(DB::exists('SELECT id from mice_invoice_detail where invoice_id='.$id.' AND type=\'VE\''))
            {
                $check = 1;
            }
            if($check==0)
            {
                DB::update('ve_reservation',array('mice_reservation_id'=>'','mice_action_module'=>0),'id='.$id);
            }
        }
        elseif($key=='BANQUET')
        {
            if(DB::exists('SELECT id from mice_invoice_detail where invoice_id='.$id.' AND type=\'BANQUET\''))
            {
                $check = 1;
            }
            if($check==0)
            {
                DB::update('party_reservation',array('mice_reservation_id'=>'','mice_action_module'=>0),'id='.$id);
            }
            
        }
        
        return $check;
    }
    function get_list_mice()
    {
        $list_mice = DB::fetch_all("SELECT mice_reservation.*,TO_CHAR(mice_reservation.start_date,'DD/MM/YYYY') as start_date,TO_CHAR(mice_reservation.end_date,'DD/MM/YYYY') as end_date FROM mice_reservation WHERE close!=1");
        return $list_mice;
    }
    function join_mice($mice_id,$key,$id)
    {
        $check = 0;
        if(DB::exists('SELECT id FROM mice_reservation WHERE id='.$mice_id.' AND close!=1'))
        {
            $arr = array('mice_reservation_id'=>$mice_id,'mice_action_module'=>$mice_id);
            
            if($key=='REC')
            {
                DB::update('reservation',$arr,'id='.$id);
                $check=1;
            }
            elseif($key=='EXS')
            {
                DB::update('extra_service_invoice',$arr,'id='.$id);
                $check=1;
            }
            elseif($key=='RES')
            {
                DB::update('bar_reservation',$arr,'id='.$id);
                $check=1;
            }
            elseif($key=='VENDING')
            {
                DB::update('ve_reservation',$arr,'id='.$id);
                $check=1;
            }
            elseif($key=='BANQUET')
            {
                DB::update('party_reservation',$arr,'id='.$id);
                $check=1;
            }
            elseif($key=='TICKET')
            {
                DB::update('ticket_reservation',$arr,'id='.$id);
                $check=1;
            }
        }
        return $check;
    }
    function check_folio_reservation($reservation_id)
    {
        return DB::fetch_all("SELECT id FROM folio WHERE (payment_time is null OR payment_time='') AND reservation_id=".$reservation_id);
    }
?>