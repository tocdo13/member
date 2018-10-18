<?php
class RevenExpenReportForm extends Form
{
    function RevenExpenReportForm()
    {
        Form::Form('RevenExpenReportForm');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');       
    }
    
    function draw()
    {
        $summary = array();
        
        //search
        $summary['from_day'] = Url::get('from_date',date('d/m/Y'));
        $summary['to_day'] = Url::get('to_date',date('d/m/Y'));
        $summary['line_per_page'] = Url::get('line_per_page',999);
        $summary['no_of_page'] = Url::get('no_of_page',500);
        $summary['from_page'] = Url::get('from_page',1);
        if(!isset($_REQUEST['portal_id']))
            $_REQUEST['portal_id'] = PORTAL_ID;
        
        $currencys = DB::fetch_all("select id, id as name from CURRENCY where allow_payment = 1 order by name");
        
        //balance
        if(Url::get('portal_id'))
            $balance = DB::fetch_all("select * from REVEN_EXPEN_BALANCE where portal_id = '".Url::get('portal_id')."'");
        else
            $balance = DB::fetch_all("select curency_id as id,curency_id,sum(amount) as amount from REVEN_EXPEN_BALANCE group by curency_id");
        $begin_balance = array();
        
        foreach($currencys as $k=>$v)
        {
            $begin_balance['balance_'.$k] = 0;
            foreach($balance as $key=>$value)
            {
                if($value['curency_id'] == $k)
                {
                    $begin_balance['balance_'.$k] = $value['amount'];
                }
            }
        }
        
        //$from_date = Date_Time::to_orc_date($summary['from_day']);
        $from_date = Date_Time::to_time($summary['from_day']);
        $cond1 = " where REVEN_EXPEN.date_cf < ".$from_date.(Url::get('portal_id')?" and REVEN_EXPEN.portal_id = '".Url::get('portal_id')."'":"");
        $cond2 = " where PAYMENT.time < ".$from_date.(Url::get('portal_id')?" and PAYMENT.portal_id = '".Url::get('portal_id')."'":"");
        $items = DB::fetch_all(
            "select *
            from
            (
                SELECT
                    ''||REVEN_EXPEN.id as id,
                    REVEN_EXPEN.date_cf,
                    REVEN_EXPEN.amount,
                    REVEN_EXPEN.item_id,
                    REVEN_EXPEN.member_id,
                    REVEN_EXPEN.note,
                    REVEN_EXPEN.input_id,
                    REVEN_EXPEN.type,
                    REVEN_EXPEN.currency_id,
                    REVEN_EXPEN_ITEMS.name as item_name,
                    REVEN_EXPEN.member_name,
                    REVEN_EXPEN.input_name,
                    REVEN_EXPEN.group_id,
                    REVEN_EXPEN_GROUP.name as group_name
                FROM
    				REVEN_EXPEN
                    inner join REVEN_EXPEN_ITEMS on REVEN_EXPEN_ITEMS.id = REVEN_EXPEN.item_id
                    inner join REVEN_EXPEN_GROUP on REVEN_EXPEN_ITEMS.group_id = REVEN_EXPEN_GROUP.id
                    ".$cond1."
            UNION
                SELECT
                    'P_'||id as id,
                    time as date_cf,
                    amount,
                    -1 as item_id,
                    'payment' member_id,
                    'payment' as note,
                    user_id as input_id,
                    CASE
    					WHEN
    						PAYMENT_TYPE_ID = 'CASH'
    					THEN
    						1
    					ELSE
    						-1
    				END type,
                    currency_id,
                    'payment' as item_name,
                    'hotel' as member_name,
                    'dat' as input_name,
                    -1 as group_id,
                    'payment' as group_name
                FROM
    				PAYMENT".$cond2." 
                    and (PAYMENT_TYPE_ID = 'CASH' OR PAYMENT_TYPE_ID = 'REFUND')
            )
            order by date_cf
            ");
        //System::debug($items);exit();
        foreach($items as $key=>$value)
        {
            foreach($currencys as $k=>$v)
            {
                if($value['type'] == -1)
                {
                    if($value['currency_id'] == $k)
                    {
                        $items[$key]['chi_'.$k] = $value['amount'];
                    }
                    else    
                        $items[$key]['chi_'.$k] = 0;
                        
                    $items[$key]['thu_'.$k] = 0;
                }
                else
                {
                    if($value['currency_id'] == $k)
                    {
                        $items[$key]['thu_'.$k] = $value['amount'];
                    }
                    else    
                        $items[$key]['thu_'.$k] = 0;
                        
                    $items[$key]['chi_'.$k] = 0;
                } 
                
                $begin_balance['balance_'.$k] += $items[$key]['thu_'.$k] - $items[$key]['chi_'.$k];
            }
        }
        
        //items
        $cur_balance = $begin_balance;
        
        //$from_date = Date_Time::to_orc_date($summary['from_day']);
        //$to_date = Date_Time::to_orc_date($summary['to_day']);
        $from_date = Date_Time::to_time($summary['from_day']);
        $to_date = Date_Time::to_time($summary['to_day']) + 24*60*60;
        $cond1 = " where REVEN_EXPEN.date_cf >= ".$from_date." and REVEN_EXPEN.date_cf <= ".$to_date.(Url::get('portal_id')?" and REVEN_EXPEN.portal_id = '".Url::get('portal_id')."'":"");
        $cond2 = " where PAYMENT.time >= ".$from_date." and PAYMENT.time <= ".$to_date.(Url::get('portal_id')?" and PAYMENT.portal_id = '".Url::get('portal_id')."'":"");
        $items = DB::fetch_all(
            "select *
            from
            (
                SELECT
                    ''||REVEN_EXPEN.id as id,
                    REVEN_EXPEN.date_cf,
                    REVEN_EXPEN.amount,
                    REVEN_EXPEN.item_id,
                    REVEN_EXPEN.member_id,
                    REVEN_EXPEN.note,
                    REVEN_EXPEN.input_id,
                    REVEN_EXPEN.type,
                    REVEN_EXPEN.currency_id,
                    REVEN_EXPEN_ITEMS.name as item_name,
                    REVEN_EXPEN.member_name,
                    REVEN_EXPEN.input_name,
                    REVEN_EXPEN.group_id,
                    REVEN_EXPEN_GROUP.name as group_name
                FROM
    				REVEN_EXPEN
                    inner join REVEN_EXPEN_ITEMS on REVEN_EXPEN_ITEMS.id = REVEN_EXPEN.item_id
                    inner join REVEN_EXPEN_GROUP on REVEN_EXPEN_ITEMS.group_id = REVEN_EXPEN_GROUP.id
                    ".$cond1."
            UNION
                SELECT
                    'P_'||id as id,
                    time as date_cf,
                    amount,
                    -1 as item_id,
                    'payment' member_id,
                    '' as note,
                    user_id as input_id,
                    CASE
    					WHEN
    						PAYMENT_TYPE_ID = 'CASH'
    					THEN
    						1
    					ELSE
    						-1
    				END type,
                    currency_id,
                    type||'_'||bill_id as item_name,
                    'hotel' as member_name,
                    'dat' as input_name,
                    -1 as group_id,
                    'payment' as group_name
                FROM
    				PAYMENT".$cond2." 
                    and (PAYMENT_TYPE_ID = 'CASH' OR PAYMENT_TYPE_ID = 'REFUND')
            )
            order by date_cf
            ");
        //System::debug($items);
        
        $pages = array();
        
        $common = array();
        foreach($currencys as $k=>$v)
        {
            $common['chi_'.$k] = 0;
            $common['thu_'.$k] = 0;
        }
        
        $index = 1;
        $num_item = count($items);
        //System::debug($items);
        foreach($items as $key=>$value)
        {
            if($value['item_id'] == -1)
            {
                $temp = strtolower($value['item_name']);
                $arr_temp = explode("_",$temp);
                
                switch ($arr_temp[0])
                {
                    case "reservation":
                        if($value['type']==1) 
                        $items[$key]['item_name'] = "Lễ tân thu tiền";
                        else
                        $items[$key]['item_name'] = "Lễ tân trả lại";
                        break;
                    case "bar": 
                        if($value['type']==1) 
                        $items[$key]['item_name'] = "Nhà hàng thu tiền";
                        else
                        $items[$key]['item_name'] = "Nhà hàng trả lại";
                        break;
                    default : 
                        if($value['type']==1) 
                        $items[$key]['item_name'] = "Thu tiền từ các dịch vụ khác";
                        else
                        $items[$key]['item_name'] = "trả lại tiền từ các dịch vụ khác";
                        break;
                }
                //$items[$key]['item_name'] = Portal::language("revenue")." ".strtolower(Portal::language("from"))." ".Portal::language($arr_temp[0]);
                if($arr_temp[1])
                    $items[$key]['note'] = "Bill_id : ".$arr_temp[1];
            }
            
            $index_page = ceil($index/$summary['line_per_page']);
            if(($index % $summary['line_per_page'] == 1 or ($index % $summary['line_per_page'] == 0 and $summary['line_per_page'] == 1)) and $index > 1)
                $pages[$index_page]['start'] = $common+$cur_balance;
            
            $items[$key]['stt'] = $index;
            //$items[$key]['date_cf'] = Date_Time::convert_orc_date_to_date($value['date_cf'],"/");
            $items[$key]['date_cf'] = Date_Time::display_date($value['date_cf']);
            
            foreach($currencys as $k=>$v)
            {
                if($value['type'] == -1)
                {
                    if($value['currency_id'] == $k)
                    {
                        $items[$key]['chi_'.$k] = $value['amount'];
                        $common['chi_'.$k] += $value['amount'];
                    }
                    else    
                        $items[$key]['chi_'.$k] = 0;
                        
                    $items[$key]['thu_'.$k] = 0;
                }
                else
                {
                    if($value['currency_id'] == $k)
                    {
                        $items[$key]['thu_'.$k] = $value['amount'];
                        $common['thu_'.$k] += $value['amount'];
                    }
                    else    
                        $items[$key]['thu_'.$k] = 0;
                        
                    $items[$key]['chi_'.$k] = 0;
                } 
                
                $cur_balance['balance_'.$k] += $items[$key]['thu_'.$k] - $items[$key]['chi_'.$k];
                $items[$key]['balance_'.$k] = $cur_balance['balance_'.$k];
            }
            
            $pages[$index_page]['items'][$key] = $items[$key];
            $pages[$index_page]['page'] = $index_page;
            
            if($index % $summary['line_per_page'] == 0 or $index == $num_item)
                $pages[$index_page]['end'] = $common+$cur_balance;
                
                
            $index++;
        }
        $common += $cur_balance;
        
        $summary['total_page'] = count($pages);
        
        for($index_page = 1; $index_page < $summary['from_page']; $index_page++)
        {
            unset($pages[$index_page]);
        }
        for($index_page = $summary['no_of_page']+$summary['from_page']; $index_page <= $summary['total_page']; $index_page++)
        {
            unset($pages[$index_page]);
        }
        $this->parse_layout('report',$summary+array("items"=>$items,
                                            "pages"=>$pages,
                                            "currencys"=>$currencys,
                                            "common"=>$common,
                                            "begin_balance"=>$begin_balance,
                                            "portal_id_list"=>array(''=>Portal::language('all')) + String::get_list(Portal::get_portal_list())));
    }
}

?>