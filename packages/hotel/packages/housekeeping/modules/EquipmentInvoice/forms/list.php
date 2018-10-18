<?php
class ListEquipmentInvoiceForm extends Form
{
	function ListEquipmentInvoiceForm()
	{
		Form::Form('ListEquipmentInvoiceForm');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
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
        $this->map['portal_id_list'] = array('ALL'=>Portal::language('all'))+String::get_list(Portal::get_portal_list()); 
        
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
            //$cound_r =" room.portal_id='".$portal_id."'";
        }
        else
        {
            $cond=" 1=1 ";
        } 
        //End Luu Nguyen Giap add portal
        
		$cond .= '
				AND housekeeping_invoice.type=\'EQUIP\' '
				.(Url::get('minibar_id')?'
					and housekeeping_invoice.minibar_id = \''.Url::get('minibar_id').'\'
				':'') 
				.(Url::get('time_start')?' and housekeeping_invoice.time>=\''.Date_Time::to_time(Url::get('time_start')).'\'':'')
				.(Url::get('time_end')?' and housekeeping_invoice.time<=\''.(Date_Time::to_time(Url::get('time_end'))+86400).'\'':'')
		;
        //KimTan
        $cond.=Url::get('room_id')?'and room.id =\''.Url::get('room_id').'\'':'';
        //end KimTan
		$item_per_page = 100;
		DB::query('
			select count(*) as acount
			from 
					housekeeping_invoice
					inner join room on room.id=housekeeping_invoice.minibar_id 
					inner join reservation_room on reservation_room.id=housekeeping_invoice.reservation_room_id and reservation_room.room_id=room.id
					left outer join traveller on traveller.id=reservation_room.traveller_id
					left outer join party on party.user_id = housekeeping_invoice.user_id
			where '.$cond.'
		');
		$count = DB::fetch();
		require_once 'packages/core/includes/utils/paging.php';
		$paging = paging($count['acount'],$item_per_page,10,false,'page_no',array('room_id','time_start','time_end'));
		$sql = '
			SELECT * FROM
			(
				SELECT 
					housekeeping_invoice.id
					,housekeeping_invoice.status
					,housekeeping_invoice.code
					,housekeeping_invoice.time
					,housekeeping_invoice.total
					,housekeeping_invoice.tax_rate
					,housekeeping_invoice.prepaid
					,concat(concat(traveller.first_name,\' \'),traveller.last_name) as reservation_room_id 
					,party.name_'.Portal::language().' as user_name
					,housekeeping_invoice.user_id
					,housekeeping_invoice.last_modifier_id
					,housekeeping_invoice.note
					,room.name as minibar
					,staff.name_1 as last_modifier_name
					,row_number() over (order by housekeeping_invoice.time desc) as rownumber
					,housekeeping_invoice.group_payment
                    ,housekeeping_invoice.position
				FROM 
					housekeeping_invoice
					inner join room on room.id=housekeeping_invoice.minibar_id 
					inner join reservation_room on reservation_room.id=housekeeping_invoice.reservation_room_id
					left outer join traveller on traveller.id=reservation_room.traveller_id
					left outer join party on party.user_id = housekeeping_invoice.user_id
					left outer join party staff on staff.user_id=housekeeping_invoice.last_modifier_id
				WHERE 
					'.$cond.'
			)
			where 
				rownumber > '.((page_no()-1)*$item_per_page).' and rownumber <= '.(page_no()*$item_per_page).'
		';// and reservation_room.room_id=room.id
		$items = DB::fetch_all($sql);
		foreach($items as $key=>$item)
		{
			$items[$key]['date'] = date('d/m/Y',$item['time']);
			$items[$key]['remain'] = System::display_number(($item['total']-$item['prepaid']));
			$items[$key]['total'] = System::display_number($item['total']); 
			$items[$key]['prepaid'] = System::display_number($item['prepaid']); 
		}
		DB::query('select
                        id, name
        			from 
                        room
        			where 
                        room.portal_id=\''.PORTAL_ID.'\'
                        and room.close_room=1
        			order by name
        			'
        		);
		$room_id_list = array(''=>'')+String::get_list(DB::fetch_all()); 
		$this->parse_layout('list',$this->map+
			array(
				'items'=>$items,
				'paging'=>$paging,
				'room_id_list' => $room_id_list, 
				'room_id' => Url::get('room_id',''), 
			)
		);
	}
}
?>