<?php
class ListLaundryInvoiceForm extends Form
{
	function ListLaundryInvoiceForm()
	{
		Form::Form('ListLaundryInvoiceForm');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
		if(Url::get('act')=='delete' and Url::check('invoice_id') and DB::exists('select id from housekeeping_invoice where id=\''.Url::iget('invoice_id').'\''))
		{
		    //System::debug($_REQUEST);exit();
            if(DB::exists('
                                SELECT
                                    mice_invoice_detail.invoice_id as id,
                                    housekeeping_invoice.position as hk_id,
                                    mice_invoice_detail.mice_invoice_id
                                FROM
                                    mice_invoice_detail
                                    INNER JOIN housekeeping_invoice on housekeeping_invoice.id = mice_invoice_detail.invoice_id
                                WHERE
                                    housekeeping_invoice.id =\''.Url::iget('invoice_id').'\' and mice_invoice_detail.type = \'LAUNDRY\'
                                    
            '))
            {
                $this->error('','Hóa đơn LD_'.$_REQUEST['laundry_code'].' đã được tạo Bill không được xóa!');
                return false;                
            }
            if(DB::exists('
                                SELECT
                                    traveller_folio.invoice_id as id,
                                    housekeeping_invoice.position as hk_id,
                                    traveller_folio.folio_id
                                FROM
                                    traveller_folio
                                    INNER JOIN housekeeping_invoice on housekeeping_invoice.id = traveller_folio.invoice_id
                                WHERE
                                    housekeeping_invoice.id =\''.Url::iget('invoice_id').'\' and traveller_folio.type = \'LAUNDRY\'
                                    
            '))
            {
                $this->error('','Hóa đơn LD_'.$_REQUEST['laundry_code'].' đã được tạo folio không được xóa!');
                return false;            
            }
			$row = DB::select('housekeeping_invoice',Url::iget('invoice_id'));
			if(!DB::exists('Select id From reservation Where id = '.$row['reservation_room_id'].' And status=\'CHECKOUT\''))
            {
				DB::delete('housekeeping_invoice','id=\''.Url::iget('invoice_id').'\'');
				DB::delete('housekeeping_invoice_detail','invoice_id=\''.Url::iget('invoice_id').'\'');
                $log_id = System::log('DELETE','Delete Laundry Invocie LD_'.$row['position'],'Delete Laundry Invocie LD_'.$row['position'],Url::iget('invoice_id'));
                $reservation = DB::fetch('select reservation_id from reservation_room where id='.$row['reservation_room_id'],'reservation_id');
                System::history_log('RECODE',$reservation,$log_id);
                System::history_log('LAUNDRY',Url::iget('invoice_id'),$log_id);
			}
            else
            {
				echo  '
					<script>alert("'.Portal::language('you_have_no_right_to_delete').'");window.location="'.Url::build_current().'";</script>;
				';
				exit();
			}
		}
	}
	function draw()
	{
        $this->map=array();
        if(isset($_SESSION['errors']))
        {
            $this->error('','Hóa đơn đã được tạo bill or folio không thể xóa!');
            unset($_SESSION['errors']);                    
        }
        //Start : Luu Nguyen GIap add portal
        $this->map['portal_id_list'] = String::get_list(Portal::get_portal_list())+array('ALL'=>Portal::language('all')); 
        //End   :Luu Nguyen GIap add portal
		if(Url::get('room_id')!='')
		{
		  
			$room = DB::select('room','id=\''.Url::get('room_id').'\' and room.portal_id=\''.PORTAL_ID.'\'');
			$room_cond = ' and housekeeping_invoice.minibar_id=\''.$room['id'].'\'';
		}
		else //Mac dinh thi chay vao day
		{
			$room_cond = '';
		}

        
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
            $cond ="  housekeeping_invoice.portal_id ='".$portal_id."' ";
            $cound_r =" room.portal_id='".$portal_id."'";
        }
        else
        {
            $cond=" 1=1 ";
            $cound_r='';
        } 
        //End Luu Nguyen Giap add portal
        
		$cond .= ' and housekeeping_invoice.type=\'LAUNDRY\''
				.(Url::get('reservation_room_id')?'and housekeeping_invoice.reservation_room_id = \''.Url::get('reservation_room_id').'\'':'')
				.(Url::get('time_start')?' and housekeeping_invoice.time>=\''.Date_Time::to_time(Url::get('time_start')).'\'':'')
				.(Url::get('time_end')?' and housekeeping_invoice.time<\''.(Date_Time::to_time(Url::get('time_end'))+86400).'\'':'') 
                .$room_cond
		;
		$item_per_page = 100;
        
        //Trong hoa don giat la, minibar id kho phai dang M-#default-108 ma la id room
        //User_id la nguoi lap phieu
		DB::query('
			select 
                count(*) as acount
			from 
				housekeeping_invoice
				left join reservation_room on reservation_room.id=housekeeping_invoice.reservation_room_id
				left join traveller on traveller.id=reservation_room.traveller_id
				left join party on party.user_id=housekeeping_invoice.user_id
				--inner join room on room.id = housekeeping_invoice.minibar_id
			where 
                '.$cond.'
		');
		$count = DB::fetch();
		require_once 'packages/core/includes/utils/paging.php';
		$paging = paging($count['acount'],$item_per_page);
        
		$sql = '
			SELECT * from
			(
				SELECT 
					housekeeping_invoice.id,
					housekeeping_invoice.code,
					housekeeping_invoice.note,
                    room.name as room_name,
                    to_char(reservation_room.arrival_time,\'DD/MM/YY\') as arrival_time,
                    to_char(reservation_room.departure_time,\'DD/MM/YY\') as departure_time,
                    reservation_room.status,
                    housekeeping_invoice.time,
                    traveller.first_name || \' \' || traveller.last_name as name,            
                    housekeeping_invoice.total,
                    housekeeping_invoice.group_payment,
                    housekeeping_invoice.user_id,
		    housekeeping_invoice.position,
                    P1.name_'.Portal::language().' as user_name,
                    last_modifier_id,
                    P2.name_1 as last_modifier_name,
                    ROW_NUMBER() over (order by housekeeping_invoice.id desc) as rownumber
				FROM 
					housekeeping_invoice
					INNER JOIN reservation_room on reservation_room.id = housekeeping_invoice.reservation_room_id
                    INNER JOIN room on room.id = reservation_room.room_id
					LEFT JOIN traveller on traveller.id = reservation_room.traveller_id
					LEFT JOIN party P1 on P1.user_id = housekeeping_invoice.user_id
                    LEFT JOIN party P2 on housekeeping_invoice.last_modifier_id = P2.user_id
				where 
                    '.$cond.'
				order by 
					housekeeping_invoice.id desc
			)
			WHERE 
				rownumber > '.((page_no()-1)*$item_per_page).' and rownumber <= '.(page_no()*$item_per_page).'
		';
		
		$items = DB::fetch_all($sql);
		foreach($items as $key=>$item)
		{
			 $items[$key]['total']=System::display_number(round($item['total']));
			 $items[$key]['time'] = date('d/m/Y',$item['time']);
			 $items[$key]['group_payment'] = $item['group_payment']?Portal::language('yes'):Portal::language('No');
		}
		require_once 'packages/hotel/includes/php/hotel.php';
		$guest = Hotel::get_reservation_guest();
		$reservation_id_list = array(''=>'----')+String::get_list($guest); 
		$i=1;
		foreach ($items as $key=>$value)
		{
			$items[$key]['i']=$i++;
		}
		$just_edited_id['just_edited_ids'] = array();
		if(Url::get('selected_ids'))
		{
			if(is_string(Url::get('selected_ids')))
			{
				if (strstr(Url::get('selected_ids'),','))
				{
					$just_edited_id['just_edited_ids']=explode(',',Url::get('selected_ids'));
				}
				else
				{
					$just_edited_id['just_edited_ids']=array('0'=>Url::get('selected_ids'));
				}
			}
		}
        
		//danh sach room
        if($cound_r=='')
        {
            DB::query('
			select
            room.id,
            room.name || \'-\' || party.name_1 as name
			from 
				room 
                left join party on room.portal_id = party.user_id
            where
                room.close_room=1
			order by 
				room.name
		');
        }
        else{
            DB::query('
			select
				room.id,
                room.name 
			from 
				room 
			WHERE
				'.$cound_r.'
                and room.close_room=1
			order by 
				room.name
		');
        }
		$room_id_list = array(''=>'----')+String::get_list(DB::fetch_all());         
		$this->parse_layout('list',$just_edited_id+
			array(
				'items'=>$items,
				'paging'=>$paging,
				'reservation_id_list' => $reservation_id_list,
				'reservation_room_id' => Url::get('reservation_room_id',''), 
				'room_id_list' => $room_id_list,
				'room_id' => Url::get('room_id',''), 
				
			)+$this->map
		);
	}
}
?>
