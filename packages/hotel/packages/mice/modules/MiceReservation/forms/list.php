<?php
class ListMiceReservationForm extends Form
{
	function ListMiceReservationForm()
    {
		Form::Form('ListMiceReservationForm');
        $this->link_css('packages/hotel/packages/mice/skins/css/font-awesome-4.5.0/css/font-awesome.min.css');
        $this->link_css('packages/hotel/packages/mice/skins/jquery.windows-engine.css');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
        $this->link_js('packages/core/includes/js/jquery/datepicker.js');
        $this->link_js('packages/hotel/packages/mice/skins/jquery.windows-engine.js');
	}
	function draw()
    {
        require_once 'packages/core/includes/utils/vn_code.php';
		$this->map = array();
        $cond = '1=1';
        $this->map['start_date'] = '';
        $this->map['end_date'] = '';
        $this->map['note'] = '';
        $this->map['last_editer'] = '';
        $this->map['status'] = '';
        if(Url::get('start_date') != '')
        {
            $cond .= ' AND mice_reservation.end_date >= \''.(Url::get('start_date')?Date_Time::to_orc_date(Url::get('start_date')):'').'\'';
        }
        //$this->map['start_date'] = Url::get('start_date')?Url::get('start_date'):date('d/m/Y');
        //$_REQUEST['start_date'] = $this->map['start_date'];
        if(Url::get('end_date') != '')
        {
            $cond .= ' AND mice_reservation.start_date <= \''.(Url::get('end_date')?Date_Time::to_orc_date(Url::get('end_date')):'').'\'';
        }
        //$this->map['end_date'] = Url::get('end_date')?Url::get('end_date'):date('d/m/Y');
        //$_REQUEST['end_date'] = $this->map['end_date'];
        
        //echo $cond;
        
        if(Url::get('note'))
        {
            $cond .= ' AND LOWER(FN_CONVERT_TO_VN(mice_reservation.note)) like \'%'.convert_utf8_to_latin(mb_strtolower(Url::sget('note'),'utf-8')).'%\'';
            $this->map['note'] = Url::get('note');
        }
        if(Url::get('last_editer')!='')
        {
            $cond .= ' AND mice_reservation.last_editer = \''.Url::get('last_editer').'\'';
            $this->map['last_editer'] = Url::get('last_editer');
        }
        if(Url::get('status')=='NO' OR Url::get('status')=='YES')
        {
            if(Url::get('status')=='NO')
                $cond .= ' AND mice_reservation.status is null ';
            else
                $cond .= ' AND mice_reservation.status = \'1\'';
            
            $this->map['status'] = Url::get('status');
        }
        
        $all_editer = DB::fetch_all('select account.id, party.full_name from account inner join party on party.user_id=account.id where account.is_active=1 AND party.type=\'USER\' order by account.id');
        /** 7211 */
		$user_privigele=DB::fetch('select group_privilege_id from account_privilege_group where account_id=\''.User::id().'\'');
        if(!$user_privigele or $user_privigele==3 or $user_privigele==4){
            
            $users = DB::fetch_all('
			SELECT
				account.id,account.id as name,account.is_active
			FROM
				account
                INNER JOIN party on party.user_id=account.id
			WHERE
				party.type=\'USER\'
		');
        }else{
            $users = DB::fetch_all('
			SELECT
				account.id,account.id as name,account.is_active
			FROM
				account
                INNER JOIN party on party.user_id=account.id
                INNER JOIN account_privilege_group ON account_privilege_group.account_id=account.id
			WHERE
				party.type=\'USER\'
				AND account_privilege_group.group_privilege_id is not null and account_privilege_group.group_privilege_id !=3 and account_privilege_group.group_privilege_id !=4
		');
        }
        $this->map['users'] =$users;
         /** 7211 end*/
        $this->map['all_editer'] = '<option value="">'.Portal::language('all').'</option>';
        foreach($users as $k=>$v)
        {
            $this->map['all_editer'] .= '<option value="'.$v['id'].'">'.$v['id'].'</option>';
        }
        
        $this->map['all_status'] = '<option value="">'.Portal::language('all').'</option>';
        $this->map['all_status'] .= '<option value="NO">'.Portal::language('mice_confirming').'</option>';
        $this->map['all_status'] .= '<option value="YES">'.Portal::language('mice_confirm').'</option>';
        
        $list_mice = DB::fetch_all("SELECT id,concat('MICE+',id) as name FROM mice_reservation WHERE portal_id='".PORTAL_ID."' ORDER BY id");
        $this->map['code_list'] = array(''=>Portal::language('all'))+String::get_list($list_mice);
        $this->map['code'] = '';
        if(Url::get('code'))
        {
            $this->map['code'] = Url::get('code');
            $cond .= ' AND mice_reservation.id = '.Url::get('code');
        }
        
        if(Url::get('contact_phone'))
        {
            $cond .= ' AND LOWER(FN_CONVERT_TO_VN(mice_reservation.contact_phone)) like \'%'.convert_utf8_to_latin(mb_strtolower(Url::sget('contact_phone'),'utf-8')).'%\'';
            $this->map['contact_phone'] = Url::get('contact_phone');
        }
        
        if(Url::get('contact_person'))
        {
            $cond .= ' AND LOWER(FN_CONVERT_TO_VN(mice_reservation.contact_name)) like \'%'.convert_utf8_to_latin(mb_strtolower(Url::sget('contact_person'),'utf-8')).'%\'';
            $this->map['contact_person'] = Url::get('contact_person');
        }
        
        //echo $cond;
        
        $active_department = DB::fetch_all('Select 
                                                department.code as id,
                                                department.name_'.Portal::language().' as name 
                                            from 
                                                department 
                                                inner join portal_department on department.code = portal_department.department_code 
                                            where
                                                portal_department.portal_id = \''.PORTAL_ID.'\'
                                                and department.parent_id = 0 AND department.code != \'WH\'
                                                and department.mice_use=1
                                        ');
        $MiceList = DB::fetch_all("SELECT mice_reservation.*,TO_CHAR(mice_reservation.start_date,'DD/MM/YYYY') as start_date,TO_CHAR(mice_reservation.end_date,'DD/MM/YYYY') as end_date FROM mice_reservation WHERE ".$cond." AND mice_reservation.portal_id='".PORTAL_ID."' ORDER BY mice_reservation.create_time DESC");
        //System::debug($cond);
        $i=1;
        foreach($MiceList as $keyitem=>$valueitem)
        {
            $MiceList[$keyitem]['stt'] = $i++;
            $MiceList[$keyitem]['status_rec'] = 'StatusWhite'; // khong co dich vu
            $MiceList[$keyitem]['status_res'] = 'StatusWhite'; // khong co dich vu
            $MiceList[$keyitem]['status_vending'] = 'StatusWhite'; // khong co dich vu
            $MiceList[$keyitem]['status_banquet'] = 'StatusWhite'; // khong co dich vu
            $MiceList[$keyitem]['status_ticket'] = 'StatusWhite'; // khong co dich vu
            $MiceList[$keyitem]['check_un_confirm'] = 1; // duoc huy xac nhan
            foreach($active_department as $key=>$value)
            {
                if($value['id']=='REC')
                {
                    $ReservationRoomList = DB::fetch_all(" SELECT 
                                                                reservation_room.* 
                                                            FROM 
                                                                reservation_room 
                                                                INNER JOIN reservation ON reservation_room.reservation_id=reservation.id 
                                                            WHERE 
                                                                reservation.mice_reservation_id=".$valueitem['id']." 
                                                                AND reservation_room.status!='CANCEL' ");
                    if(sizeof($ReservationRoomList)>0)
                        $MiceList[$keyitem]['status_rec'] = 'StatusRed'; // co dich vu va check out het
                    foreach($ReservationRoomList as $K_ResRoom=>$V_ResRoom)
                    {
                        if($V_ResRoom['status']!='CHECKOUT')
                            $MiceList[$keyitem]['status_rec'] = 'StatusBlue'; // co dich vu va dang su dung
                        if($V_ResRoom['status']!='BOOKED')
                            $MiceList[$keyitem]['check_un_confirm'] = 0; // ko duoc huy xac nhan
                    }
                    
                }
                if($value['id']=='RES')
                {
                    $BarReservationList = DB::fetch_all(" SELECT 
                                                            bar_reservation.*
                                                            FROM 
                                                            bar_reservation 
                                                            WHERE 
                                                            bar_reservation.mice_reservation_id=".$valueitem['id']." 
                                                            AND bar_reservation.status!='CANCEL'");
                    if(sizeof($BarReservationList)>0)
                        $MiceList[$keyitem]['status_res'] = 'StatusRed'; // co dich vu va check out het
                    foreach($BarReservationList as $K_Bar=>$V_Bar)
                    {
                        if($V_Bar['status']!='CHECKOUT')
                            $MiceList[$keyitem]['status_res'] = 'StatusBlue'; // co dich vu va dang su dung
                        if($V_Bar['status']!='BOOKED')
                            $MiceList[$keyitem]['check_un_confirm'] = 0; // ko duoc huy xac nhan
                    }
                }
                if($value['id']=='VENDING')
                {
                    $VendingReservationList = DB::fetch_all(" SELECT 
                                                                ve_reservation.id,
                                                                ve_reservation.time
                                                                FROM 
                                                                    ve_reservation 
                                                                WHERE 
                                                                    ve_reservation.mice_reservation_id=".$valueitem['id']."");
                    if(sizeof($VendingReservationList)>0)
                        $MiceList[$keyitem]['status_vending'] = 'StatusRed'; // co dich vu va check out het
                    foreach($VendingReservationList as $K_Ve=>$V_ve)
                    {
                        if($V_ve['time']>time())
                            $MiceList[$keyitem]['status_vending'] = 'StatusBlue'; // co dich vu va dang su dung
                        if($V_ve['time']<time())
                            $MiceList[$keyitem]['check_un_confirm'] = 0; // ko duoc huy xac nhan
                    }
                }
                if($value['id']=='BANQUET')
                {
                    $PartyReservationList = DB::fetch_all(" SELECT 
                                                                party_reservation.id,
                                                                party_reservation.status
                                                            FROM 
                                                                party_reservation 
                                                            WHERE 
                                                                party_reservation.mice_reservation_id=".$valueitem['id']." 
                                                                AND party_reservation.status!='CANCEL' ");
                    if(sizeof($PartyReservationList)>0)
                        $MiceList[$keyitem]['status_banquet'] = 'StatusRed'; // co dich vu va check out het
                    foreach($PartyReservationList as $K_Party=>$V_Party)
                    {
                        if($V_Party['status']!='CHECKOUT')
                            $MiceList[$keyitem]['status_banquet'] = 'StatusBlue'; // co dich vu va dang su 
                        if($V_Party['status']!='BOOKED')
                            $MiceList[$keyitem]['check_un_confirm'] = 0; // ko duoc huy xac nhan
                    }
                }
                if($value['id']=='TICKET')
                {
                    $TicketInvoice = DB::fetch_all(" SELECT
                                                        ticket_invoice.*
                                                        ,TO_CHAR(ticket_invoice.date_used,'DD/MM/YYYY') as in_date
                                                    FROM 
                                                        ticket_invoice 
                                                        inner join ticket_reservation on ticket_reservation.id=ticket_invoice.ticket_reservation_id
                                                    WHERE 
                                                        ticket_reservation.mice_reservation_id=".$valueitem['id']."");
                    if(sizeof($TicketInvoice)>0)
                        $MiceList[$keyitem]['status_ticket'] = 'StatusRed'; // co dich vu va check out het
                    foreach($TicketInvoice as $K_Ticket=>$V_Ticket)
                    {
                        if($V_Ticket['export_ticket']!=1)
                        {
                            $MiceList[$keyitem]['status_ticket'] = 'StatusBlue'; // co dich vu va dang su dung
                            $MiceList[$keyitem]['check_un_confirm'] = 0; // ko duoc huy xac nhan
                        }
                    }
                }
            }
        }
        //System::debug($MiceList);
        $this->map['items'] = $MiceList;
        
        $this->parse_layout('list',$this->map);
    }
}
?>
