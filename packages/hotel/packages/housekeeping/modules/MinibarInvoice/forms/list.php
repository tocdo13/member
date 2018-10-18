<?php
class ListMinibarInvoiceForm extends Form
{
	function ListMinibarInvoiceForm()
	{
		Form::Form('ListMinibarInvoiceForm');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
	}
	function draw()
	{
        $this->map = array();
        if(isset($_SESSION['errors']))
        {
            $this->error('','Hóa đơn đã được tạo bill or folio không thể xóa!');
            unset($_SESSION['errors']);                    
        }
        //Start : Luu Nguyen GIap add portal
        $this->map['portal_id_list'] = String::get_list(Portal::get_portal_list())+array('ALL'=>Portal::language('all')); 
        //End   :Luu Nguyen GIap add portal
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
            $cound_r="";
        } 
        //End Luu Nguyen Giap add portal
        
		$cond .= '
				AND housekeeping_invoice.type=\'MINIBAR\' '
				.(URL::get('minibar_id')?'and housekeeping_invoice.minibar_id = \''.URL::get('minibar_id').'\'':'') 
				.(URL::get('employee_id')?'and housekeeping_invoice.employee_id = \''.URL::get('employee_id').'\'':'')
				.(URL::get('time_start')?' and housekeeping_invoice.time>=\''.Date_Time::to_time(URL::get('time_start')).'\'':'')
				.(URL::get('time_end')?' and housekeeping_invoice.time<=\''.(Date_Time::to_time(URL::get('time_end'))+86400).'\'':'')
		;
		$item_per_page = 100;
        DB::query('
			select count(*) as acount
			from 
					housekeeping_invoice
					left outer join minibar on minibar.id=housekeeping_invoice.minibar_id 
					left outer join reservation_room on reservation_room.id=housekeeping_invoice.reservation_room_id and reservation_room.room_id=minibar.room_id
					left outer join traveller on traveller.id=reservation_room.traveller_id
					left outer join party on party.user_id = housekeeping_invoice.user_id
			where '.$cond.'
		');
		$count = DB::fetch();
		require_once 'packages/core/includes/utils/paging.php';
		$paging = paging($count['acount'],$item_per_page);
		$sql = '
			SELECT * FROM
			(
				SELECT 
					housekeeping_invoice.id
					,housekeeping_invoice.code
					,housekeeping_invoice.note
					,housekeeping_invoice.time
					,housekeeping_invoice.total
					,housekeeping_invoice.tax_rate
					,housekeeping_invoice.fee_rate
					,housekeeping_invoice.discount
					,housekeeping_invoice.prepaid
					,housekeeping_invoice.position
					,concat(concat(traveller.first_name,\' \'),traveller.last_name) as reservation_room_id 
					,P1.name_'.Portal::language().' as user_name
					,housekeeping_invoice.user_id
					,housekeeping_invoice.last_modifier_id
					,minibar.name as minibar						 
					,P2.name_1 as last_modifier_name
					,room.name as room_name
					,reservation_room.status
					,to_char(reservation_room.arrival_time,\'DD/MM/YY\') as arrival_time
					,to_char(reservation_room.departure_time,\'DD/MM/YY\') as departure_time
					,row_number() over (order by housekeeping_invoice.time desc) as rownumber
					,housekeeping_invoice.group_payment
				FROM 
					housekeeping_invoice
					left outer join minibar on minibar.id=housekeeping_invoice.minibar_id 
					left outer join reservation_room on reservation_room.id=housekeeping_invoice.reservation_room_id and reservation_room.room_id=minibar.room_id					
					left outer join room on room.id = reservation_room.room_id 
					left outer join traveller on traveller.id=reservation_room.traveller_id
					left outer join party P1 on P1.user_id = housekeeping_invoice.user_id
					left outer join party P2 on P2.user_id=housekeeping_invoice.last_modifier_id
				WHERE '.$cond.'
			)
			where 
				rownumber > '.((page_no()-1)*$item_per_page).' and rownumber <= '.(page_no()*$item_per_page).'
		';
		$items = DB::fetch_all($sql);
		foreach($items as $key=>$item)
		{
			$items[$key]['group_payment'] = $item['group_payment']?Portal::language('yes'):Portal::language('No');
			$items[$key]['date'] = date('d/m/Y',$item['time']);
            $remain = round($item['total']-$item['prepaid']); /** daund add round remain*/
			$items[$key]['remain'] = System::display_number($remain);
			$act_total = DB::fetch('select sum(price*quantity) as total from housekeeping_invoice_detail where invoice_id = '.$item['id'].' group by invoice_id','total');
			$act_total -= round($act_total*$item['discount']/100,ROUND_PRECISION);
			$act_total += round($act_total*$item['fee_rate']/100,ROUND_PRECISION);
			$act_total += round($act_total*$item['tax_rate']/100,ROUND_PRECISION);
			$items[$key]['act_total'] = System::display_number($act_total); 
            if($act_total!=$items[$key]['total'])
            {
				$items[$key]['fix']	 = ' <span style="color:#FF0000;">'.System::display_number($act_total).' - (Fix...?) </span>';
			}
            else
            {
				$items[$key]['fix']	 = '';
			}
			$items[$key]['fix']	 = '';
            $item['total'] = round($item['total']); /** daund add round total*/
			$items[$key]['total']= System::display_number($item['total']);
			$items[$key]['prepaid']=System::display_number($item['prepaid']); 
		}
        if($cound_r=='')
        {
            DB::query('
			select
				minibar.id, minibar.name || \'-\' || party.name_1 as name
			from 
				minibar
				left outer join room on room.id = minibar.room_id
                left join party on room.portal_id = party.user_id
			order by 
				room.portal_id,minibar.name
		');
        }
        else{
            DB::query('
			select
				minibar.id, minibar.name
			from 
				minibar
				left outer join room on room.id = minibar.room_id
			where
				'.$cound_r.'
			order by 
				minibar.name
		');
        }
		$minibar_id_list = array(''=>'')+String::get_list(DB::fetch_all()); 
		DB::query('select
			id, employee_profile.name as name
			from employee_profile
			order by name'
		);
		$employee_id_list = array(''=>'')+String::get_list(DB::fetch_all());
        if(User::id()=='developer14')
        {
            //System::debug($items);
        }
		$this->parse_layout('list',$this->map+
			array(
				'items'=>$items,
				'paging'=>$paging,
				'minibar_id_list' => $minibar_id_list, 
				'minibar_id' => Url::get('minibar_id',''), 
				'employee_id_list' => $employee_id_list,
				'employee_id' => Url::get('employee_id','')
			)
		);
	}
}
?>
