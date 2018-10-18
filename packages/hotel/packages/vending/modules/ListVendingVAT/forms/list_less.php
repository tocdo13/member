<?php
class ListLessVendingVATForm extends Form
{
	function ListLessVendingVATForm()
	{
		Form::Form('ListLessVendingVATForm');
		$this->link_css('skins/default/restaurant.css');		
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
		
	}
	function on_submit()
    {	
        if(Url::get('print_group_vat'))
        {
            if(!Url::get('selected_ids'))
            {
                $this->error('miss_folio',Portal::language('you_must_choose_folio'),false);
            }
            else
            {
                $selected_ids = Url::get('selected_ids');
                $b_r_id ='';
                if(!empty($selected_ids))
                {
        			foreach( $selected_ids as $id)
        			{
                        $b_r_id.= $id.',';
        			}
                    Url::redirect('vat_bill',array('cmd'=>'entry_vending','v_r_id'=>trim($b_r_id,','),'department'=>'VEND','arrival_time'=>Url::get('from_arrival_time'),'departure_time'=>Url::get('from_arrival_time')));  
                }
            }    
        }
        else
        if(Url::get('print_all_vat'))//in tất cả trong ngày
        {
            $date = Url::get('from_arrival_time')?Url::get('from_arrival_time'):date('d/m/Y');
            $cond = '
                        1>0 and ve_reservation.portal_id=\''.PORTAL_ID.'\'
                        and ve_reservation.time>='.Date_Time::to_time( $date ).'
                        and ve_reservation.time<'.(Date_Time::to_time( $date )+(3600*24)).'
                    '
                    ;
    		DB::query('
    				select 
    					ve_reservation.id
    				from 
    					ve_reservation
    				where 
                        '.$cond.'
                        and ve_reservation.total <200000
                    order by 
                        id desc
    		');
    		$items = DB::fetch_all();
            if(!empty($items))
            {
                $b_r_id ='';
    			foreach( $items as $key=>$value)
    			{
                    $b_r_id.= $value['id'].',';
    			}
                Url::redirect('vat_bill',array('cmd'=>'entry_vending','v_r_id'=>trim($b_r_id,','),'department'=>'VEND','arrival_time'=>$date,'departure_time'=>$date));  
            }   
        }
	}
	function draw()
	{
        $this->map = array();
        $this->map['date'] = Url::get('from_arrival_time')?Url::get('from_arrival_time'):date('d/m/Y');
        $_REQUEST['from_arrival_time'] = $this->map['date'];
        //.(Url::get('to_arrival_time')!=''?(' and bar_reservation.arrival_time<'.(Date_Time::to_time(Url::get('to_arrival_time'))+(3600*24))):'')
		$cond = '
				1>0 and ve_reservation.portal_id=\''.PORTAL_ID.'\''
				.(Url::get('customer_name')!=''?' and lower(ve_reservation.customer_name) LIKE \'%'.strtolower(addslashes(Url::get('customer_name'))).'%\'':'') 
				.(
                    Url::get('from_arrival_time')!=''?( 
                                                    ' and ve_reservation.time>='.Date_Time::to_time( $this->map['date'] )
                                                    . 'and ve_reservation.time<'.(Date_Time::to_time( $this->map['date'] )+(3600*24)) 
                                                    ):''
                 )
				.(Url::get('total_from')!=''?' and total>='.intval(Url::get('total_from')):'')
				.(Url::get('total_to')!=''?' and total<='.intval(Url::get('total_to')):'')
				.(Url::get('invoice_number')!=''?' and ve_reservation.id = '.trim(Url::iget('invoice_number')).'':'')
		;
		$item_per_page = 100;
        $cond .= '
                and ve_reservation.total <200000
                ';
		DB::query('
			select count(*) as acount
			from 
				ve_reservation
			where '.$cond.'
		');
        //System::debug($cond);
		$count = DB::fetch();
		require_once 'packages/core/includes/utils/paging.php';
		$paging = paging($count['acount'],$item_per_page,10,false,'page_no',array('cmd','invoice_number','from_arrival_time','to_arrival_time','status','bars'));
		DB::query('
			select * from(
				select 
					ve_reservation.id, 
					ve_reservation.code as code,
					ve_reservation.time,
					ve_reservation.total,
                    ve_reservation.agent_name as agent_name,
					ve_reservation.user_id,
                    vat_bill.ve_reservation_id,
                    vat_bill.code as vat_bill_code,
					row_number() OVER (order by ABS(ve_reservation.time - '.time().')) AS rownumber
				from 
					ve_reservation
                    left join vat_bill on ve_reservation.vat_code  = vat_bill.id
				where 
                    '.$cond.'
                order by 
                    id desc 
			)
			where
				rownumber > '.((page_no()-1)*$item_per_page).' and rownumber<='.((page_no())*$item_per_page).'
		');
		$items = DB::fetch_all();
		if($items)
		{
            //Lấy ra các id của bar_reservation
            $b_r_ids = array_keys($items);
            //Tạo cond
            $cond = '';
			for($i=0;$i<count($b_r_ids);$i++)
            {
				if($b_r_ids[$i] && $b_r_ids[$i]!='')
                {
					$cond .= ($i==0)?(' ve_reservation_id LIKE \''.$b_r_ids[$i].'%\''):(' OR ve_reservation_id LIKE \''.$b_r_ids[$i].'%\'');  	
				}
			}
            //Lấy ra các VAT của các bar_reservation_id trên
            $vat_bill = DB::fetch_all('select vat_bill.*  from vat_bill where ( '.$cond.' ) and portal_id = \''.PORTAL_ID.'\' and department = \'TICKET\' ');
            //System::debug($cond);
            //System::debug($vat_bill);
            foreach($items as $k=>$itm)
			{	
				$items[$k]['total'] = System::display_number($items[$k]['total']);
                $items[$k]['time'] = date('d/m/Y',$itm['time']);
                //Gán VAT code vào từng item
                foreach($vat_bill as $key=>$value)
                {
                    //so sánh chuỗi để gán VAT code => có thể bị nhầm vì vat code có b_r_id = 12,11,10 thì bar_res_id = 1 vẫn nhận VAT code này
                    //=> phân tích chuỗi thành mảng, kiểm tra in_array
                    if( in_array( $itm['id'] , explode( ',' , $value['ve_reservation_id']  ) ) )
                    {
                        $items[$k]['vat_bill_code'] = $value['code'];
                        //break => lấy ra VAT code mới nhất vì 1 bar_res_id GROUP có thể đc in ở nhiều. ví dụ 6,7 và 6,7,8
                        break;
                    }
                }	
			}
		}
        //System::debug($items);
        $this->parse_layout('list_less',array(
			'items'=>$items,
			'paging'=>$paging
		));
	}
}
?>