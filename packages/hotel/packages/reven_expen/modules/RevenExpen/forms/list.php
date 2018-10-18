<?php 
class RevenExpenListForm extends Form{
    function RevenExpenListForm(){
        Form::Form('RevenExpenListForm');
        $this->link_js('packages/core/includes/js/jquery/datepicker.js');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
    }
    function draw(){
        //System::debug($_REQUEST);
        $temp = $_REQUEST;
        if(!isset($_REQUEST['type']))
        {
            $_REQUEST['type'] = 1;
        }
        
        $type = $_REQUEST['type'];
        
        $cond = ' where 1>0 '."and REVEN_EXPEN.type=".$type;
        
        if(isset($_REQUEST['code']) and $_REQUEST['code'])
        {
            $cond .= " and REVEN_EXPEN.id=".$_REQUEST['code'];
        }
        
        if(isset($_REQUEST['item_id']) and $_REQUEST['item_id'])
        {
            $cond .= " and REVEN_EXPEN.item_id=".$_REQUEST['item_id'];
        }
        
        if(isset($_REQUEST['group_id']) and $_REQUEST['group_id'])
        {
            $cond .= " and REVEN_EXPEN.group_id=".$_REQUEST['group_id'];
        }
        
        if(isset($_REQUEST['member_id']) and $_REQUEST['member_id'])
        {
            $cond .= " and REVEN_EXPEN.member_id='".$_REQUEST['member_id']."'";
        }
        
        if(isset($_REQUEST['from_date']) and $_REQUEST['from_date']
            and isset($_REQUEST['to_date']) and $_REQUEST['to_date']
            and ($_REQUEST['from_date']<= $_REQUEST['from_date']))
        {
            $from_date = Date_Time::to_time($_REQUEST['from_date']);
            $to_date = Date_Time::to_time($_REQUEST['to_date'])+24*3600;
            $cond .= " and REVEN_EXPEN.date_cf >=".$from_date." and REVEN_EXPEN.date_cf <".$to_date."";
        }
        
        $cond .= " and REVEN_EXPEN.portal_id = '".PORTAL_ID."'"; 
        
        //$order = ' order by REVEN_EXPEN.group_id, REVEN_EXPEN.item_id, REVEN_EXPEN.date_cf';
        $order = ' order by REVEN_EXPEN.date_cf desc';
        
        $sql = '
            SELECT
				count(*) as total
            FROM
				REVEN_EXPEN
                inner join REVEN_EXPEN_ITEMS on REVEN_EXPEN_ITEMS.id = REVEN_EXPEN.item_id
                inner join REVEN_EXPEN_GROUP on REVEN_EXPEN_ITEMS.group_id = REVEN_EXPEN_GROUP.id
                ';
        
        $item_per_page = 50;
		DB::query($sql.$cond.$order);
		$count = DB::fetch();
		require_once 'packages/core/includes/utils/paging.php';
		$paging = paging($count['total'],$item_per_page,5,false,'page_no',array( 'portal_id'=>Url::get('portal_id'),'type'=>'type','category_id','code','name' ) );
    
        $sql = '
    		select * from(
    			SELECT
    				REVEN_EXPEN.id,
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
                    REVEN_EXPEN_GROUP.name as group_name,
                    row_number() over ('.$order.') as rownumber
                FROM
    				REVEN_EXPEN
                    inner join REVEN_EXPEN_ITEMS on REVEN_EXPEN_ITEMS.id = REVEN_EXPEN.item_id
                    inner join REVEN_EXPEN_GROUP on REVEN_EXPEN_ITEMS.group_id = REVEN_EXPEN_GROUP.id
                    '.$cond.'
    			)
			where
				rownumber > '.((page_no()-1)*$item_per_page).' and rownumber<='.((page_no())*$item_per_page).'
		';
        //echo page_no(); 
        //exit();
        $items = DB::fetch_all($sql);
        $index = 0;
        foreach($items as $key=>$value)
        {
            $items[$key]['i'] = $index++;
            $items[$key]['date_cf'] = Date_Time::display_date($value['date_cf']);
        }
        
        $db_items = DB::fetch_all("select 
                                        REVEN_EXPEN_ITEMS.id, 
                                        REVEN_EXPEN_ITEMS.name 
                                    from REVEN_EXPEN_ITEMS 
                                    inner join REVEN_EXPEN_GROUP on REVEN_EXPEN_ITEMS.group_id = REVEN_EXPEN_GROUP.id
                                    order by name");
		$item_id_options = '';
		foreach($db_items as $item)
		{
            
			$item_id_options .= '<option value="'.$item['id'].'">'.$item['name'].'</option>';
		}
        
        $db_items = DB::fetch_all("select id, name from REVEN_EXPEN_GROUP order by name");
		$group_id_options = '';
		foreach($db_items as $item)
		{
			$group_id_options .= '<option value="'.$item['id'].'">'.$item['name'].'</option>';
		}	
        
        $db_items = DB::fetch_all("select user_id as id, name_1 as name from PARTY order by name");
        
		$user_id_options = '';
		foreach($db_items as $item)
		{
			$user_id_options .= '<option value="'.$item['id'].'">'.$item['name'].'</option>';
		}
        
        $this->parse_layout('list',array("items"=>$items,
                                        "type"=>$type,
                                        'paging'=>$paging,
                                        'item_id_options'=>$item_id_options,
                                        'group_id_options'=>$group_id_options,
                                        'user_id_options'=>$user_id_options));
    }
}
?>
