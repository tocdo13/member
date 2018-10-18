<?php
class ListSettlementForm extends Form
{
    function ListSettlementForm()
    {
        Form::Form('ListSettlementForm');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
    }
    
    function on_submit()
    {
        $selected_ids = Url::get('selected_ids');
        if(!empty($selected_ids))
        {
			foreach($selected_ids as $id)
			{
                DB::delete_id( 'customer_debt_settlement', $id );
			}  
        } 
    }
    function draw()
    {
        $cond = ' customer_debt_settlement.portal_id = \''.PORTAL_ID.'\' ';
        if(Url::get('from_date'))
        {
            $date_from = Url::get('from_date')?Date_Time::to_orc_date(Url::get('from_date')):$this->get_beginning_date_of_week();
            $this->map['from_date'] = Date_Time::convert_orc_date_to_date($date_from,'/');
            $_REQUEST['from_date'] = $this->map['from_date'];
            $time_from = Date_Time::to_time(Date_Time::convert_orc_date_to_date($date_from,'/'));
            $cond .= ' and customer_debt_settlement.time >= '.$time_from.' ';
        }
        if(Url::get('to_date'))
        {
    		$date_to = Url::get('to_date')?Date_Time::to_orc_date(Url::get('to_date')):$this->get_end_date_of_week();
    		$this->map['to_date'] = Date_Time::convert_orc_date_to_date($date_to,'/');
            $_REQUEST['to_date'] = $this->map['to_date'];
    		$time_to = Date_Time::to_time(Date_Time::convert_orc_date_to_date($date_to,'/')) + (24*3600);
            $cond .= ' and customer_debt_settlement.time <= '.$time_to.' ';
        }
        
        $cond .= Url::get('customer_id')?' AND customer_id = '.Url::get('customer_id').' ':'';
        
        $item_per_page = 50;
		$sql = 'SELECT count(*) AS acount FROM customer_debt_settlement where '.$cond.' ';
		require_once 'packages/core/includes/utils/paging.php';
		$this->map['total'] =  DB::fetch($sql,'acount');
		$this->map['paging'] =  paging($this->map['total'],$item_per_page,10,$smart=false,$page_name='page_no',array('from_date','to_date','customer_id'));

        $sql = '
			SELECT * FROM
			(
				SELECT
					customer_debt_settlement.*,
                    vending_customer.name as customer_name,
					ROW_NUMBER() OVER (ORDER BY customer_debt_settlement.id) as rownumber
				FROM
					customer_debt_settlement
                    inner join vending_customer on customer_debt_settlement.customer_id = vending_customer.id
                where 
                    '.$cond.'
				ORDER BY
					customer_debt_settlement.id
			)
			WHERE
			 	rownumber > '.((page_no()-1)*$item_per_page).' and rownumber<='.((page_no())*$item_per_page).'	
		';
		$items = DB::fetch_all($sql);
        
        $i = ((page_no()-1)*$item_per_page)+1;
        foreach($items as $k => $v)
        {
            $items[$k]['stt'] = $i++;
            $items[$k]['total'] = System::display_number($items[$k]['total']);
            $items[$k]['create_date'] = Date_Time::convert_orc_date_to_date($items[$k]['create_date'],'/');
            $items[$k]['time'] = date('d/m/Y',$items[$k]['time'] );
            $items[$k]['lastest_edited_time'] = $items[$k]['lastest_edited_time']? date('d/m/Y',$items[$k]['lastest_edited_time'] ) :'';
        }
        $this->map['customer_id_list'] = array(''=>Portal::language('All'))+String::get_list(DB::select_all('vending_customer',''));
        //System::debug($items);
        $this->map['items'] = $items;
        $this->map['title'] = Portal::language('list_settlement');
        $this->parse_layout('list',$this->map);
    }
    
    function get_beginning_date_of_week(){
		$today = date('d/m/Y');
		$time_today = Date_Time::to_time($today);
		$day_of_week = date('w',$time_today);
		$day_begin_of_week = $time_today  - (24 * 3600 * $day_of_week);
		return (Date_Time::to_orc_date(date('d/m/Y',$day_begin_of_week)));
	}
	function get_end_date_of_week(){
		$today = date('d/m/Y');
		$time_today = Date_Time::to_time($today);
		$day_of_week = date('w',$time_today);
		$end_of_week = $time_today + (24 * 3600 * (6 - $day_of_week));
		return (Date_Time::to_orc_date(date('d/m/Y',$end_of_week)));
	}
}

?>