<?php 
class RevenExpenAddForm extends Form{
    function RevenExpenAddForm(){
        Form::Form('RevenExpenAddForm');
        $this->link_css('packages/core/includes/js/jquery/window/css/jquery.window.4.04.css');
		$this->link_js('packages/core/includes/js/jquery/window/jquery.window.js');
        $this->link_js('packages/core/includes/js/jquery/jquery.mask.min.js');
        $this->link_js('packages/core/includes/js/multi_items.js');
        $this->link_js('packages/core/includes/js/jquery/datepicker.js');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
    }
    function on_submit()
    {
        if(Url::get('type_all'))
        {
            if(isset($_REQUEST['btnSave']))
            {
               $items = DB::fetch_all("select 
                                    REVEN_EXPEN_ITEMS.id, 
                                    REVEN_EXPEN_ITEMS.name, 
                                    REVEN_EXPEN_ITEMS.group_id 
                                from REVEN_EXPEN_ITEMS 
                                inner join REVEN_EXPEN_GROUP on REVEN_EXPEN_ITEMS.group_id = REVEN_EXPEN_GROUP.id
                                order by name");
        
                $groups = DB::fetch_all("select id, name from REVEN_EXPEN_GROUP order by name");
                
                $users = DB::fetch_all("select user_id as id, name_1 as name from PARTY order by name");
                
                $input_id = Session::get('user_id');
                
                if(isset($_REQUEST['mi_group']))
                {
                    foreach($_REQUEST['mi_group'] as $key=>$record)
                    {
                        //System::debug($record);
                        //exit();
                        //echo "***************";
                        $time;
                        if($record['time'])
                            $time = Date_Time::to_time($record['time']);
                        else
                            $time = Date_Time::to_time(date('d/m/Y'));
                        
                        $h = date('H');
                        $m = date('i'); 
                        if($record['hms'])
                        {
                            $arr = explode(":",$record['hms']);
                            $h = $arr[0];
                            $m = $arr[1];
                        }
                        
                        $time += $h*60*60 + $m*60;
                        
                        $record['date_cf'] = $time;
                        $record['amount'] = str_replace(",","",$record['amount']);
                        $record['input_id'] = $input_id;
                        $record['group_id'] = $items[$record['item_id']]['group_id'];
                        
                        $record['item_name'] = $items[$record['item_id']]['name'];
                        $record['member_name'] = $users[$record['member_id']]['name'];
                        $record['input_name'] = $users[$record['input_id']]['name'];
                        $record['group_name'] = $groups[$record['group_id']]['name'];
                        
                        $record['portal_id'] = PORTAL_ID;
                        
                        unset($record['time']);
                        unset($record['hms']);
                        //System::debug($record);
                        //exit();
                        if($record['id'] and DB::exists_id('REVEN_EXPEN',$record['id']))
                        {
                            $bar_id = $record['id'];
                            DB::update('REVEN_EXPEN',$record,'id=\''.$bar_id.'\'');
                        }
                        else
                        {
                            unset($record['id']);
                            $id = DB::insert('REVEN_EXPEN',$record);
                        }
                    }
                }
                //echo "<script>history.go(-2)</script>";
                Url::redirect_current(array('type'=>Url::get('type_all'))); 
            }
            else
            {
               Url::redirect_current(array('cmd'=>'add','type'=>Url::get('type_all'))); 
            }
            
        }
        
    }
    function draw(){
        

        if(isset($_REQUEST['ids']))
        {
            $cond = ' 1>0 ';
			$sql = '
				SELECT
					*
				FROM
					REVEN_EXPEN
                where id in ('.$_REQUEST['ids'].') and type ='.$_REQUEST['type'].' and portal_id = \''.PORTAL_ID.'\'
                order by id';
			$bars = DB::fetch_all($sql);
			$i=1;
			foreach($bars as $key => $value)
            {
				$bars[$key]['no'] = $i;
                $bars[$key]['time'] = date('d/m/Y',$bars[$key]['date_cf']);
                $bars[$key]['hms'] = date('H:i',$bars[$key]['date_cf']);
				$i++;
			}
            
			$_REQUEST['mi_group'] = $bars;
        }
        else{
            //Luu Nguyen Giap : get list thu -chi theo type
            
            $sql = '
                SELECT
                    REVEN_EXPEN.id,REVEN_EXPEN.amount,REVEN_EXPEN.currency_id,REVEN_EXPEN.item_id,
                    REVEN_EXPEN.date_cf,REVEN_EXPEN.member_id ,REVEN_EXPEN.note,REVEN_EXPEN_ITEMS.status as type
                FROM
                    REVEN_EXPEN
                INNER JOIN REVEN_EXPEN_ITEMS ON REVEN_EXPEN_ITEMS.id=REVEN_EXPEN.item_id 
                where REVEN_EXPEN_ITEMS.status='.Url::get('type');
       
            $bars = DB::fetch_all($sql);
            $i=1;
            foreach($bars as $key => $value)
            {
                $bars[$key]['no'] = $i;
                $bars[$key]['time'] = date('d/m/Y',$bars[$key]['date_cf']);
                $bars[$key]['hms'] = date('H:i',$bars[$key]['date_cf']);
                $i++;
            }
            
            //$_REQUEST['mi_group'] = $bars;
            //$_REQUEST['mi_group'] = '';
            //Luu Nguyen Giap end
        } 
        //System::debug($_REQUEST);exit();
        $db_items = DB::fetch_all("select id, id as name from CURRENCY where allow_payment = 1 order by name");
		$currency_id_options = '';
		foreach($db_items as $item)
		{
            $currency_id_options .= '<option value="'.$item['id'].'">'.$item['name'].'</option>';
		}
        
        $db_items = DB::fetch_all('select 
                                        REVEN_EXPEN_ITEMS.id, 
                                        REVEN_EXPEN_ITEMS.name 
                                    from REVEN_EXPEN_ITEMS 
                                    inner join REVEN_EXPEN_GROUP on REVEN_EXPEN_ITEMS.group_id = REVEN_EXPEN_GROUP.id
                                    --Luu Nguyen Giap edit quan ly thu chi search theo dieu kien thu or chi
                                    Where  REVEN_EXPEN_ITEMS.status='.Url::get('type').'
                                    --Luu Nguyen Giap end
                                    order by REVEN_EXPEN_ITEMS.name');
		$item_id_options = '';
		foreach($db_items as $item)
		{
			$item_id_options .= '<option value="'.$item['id'].'">'.$item['name'].'</option>';
		}	
        
        $db_items = DB::fetch_all('select user_id as id, name_1 as name 
                                    from PARTY 
                                        inner join account on account.id = party.user_id
                                    where party.type =\'USER\' and account.id =
                                    '.(Url::get('cmd')=='add'?'\''.User::id().'\'':'(select member_id from REVEN_EXPEN where id='.$_REQUEST['ids'].')').'
                                    
                                    order by name');
        
		$user_id_options = '';
		foreach($db_items as $item)
		{
			$user_id_options .= '<option value="'.$item['id'].'">'.$item['name'].'</option>';
		}
 	    
        
		$this->parse_layout('add',array('user_id_options' => $user_id_options,
                                            'item_id_options' => $item_id_options,
                                            'currency_id_options' => $currency_id_options));
    }
}
?>
