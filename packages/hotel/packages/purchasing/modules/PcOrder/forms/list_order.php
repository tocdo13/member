<?php
class ListOrderForm extends Form
{
    function ListOrderForm()
    {
        Form::Form('ListOrderForm');
        $this->link_css(Portal::template('hotel').'/css/style.css');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
        $this->link_js('packages/core/includes/js/jquery/datepicker.js');
        $this->link_css('packages/core/skins/default/css/jquery.autocomplete.css');
        $this->link_js('packages/core/includes/js/jquery/jquery.autocomplete.js');
        define("CREATED","CREATED");
        define("ACCOUNTANT","ACCOUNTANT");
        define("DIRECTOR","DIRECTOR");
        define("COMPLETE","COMPLETE");
        define("CANCEL","CANCEL");
        define("CLOSE","CLOSE");
        define("APART_CLOSE","APART_CLOSE");
    }
    function draw()
    {
        require_once 'packages/core/includes/utils/vn_code.php';
        if(!isset($_REQUEST['from_date']))
		{
			$_REQUEST['from_date'] = date('d/m/Y',(Date_Time::to_time(date('d/m/Y'))-7*86400)) ;	
		}
		if(!isset($_REQUEST['to_date']))
		{
			$_REQUEST['to_date'] = date('d/m/Y',time());
		}		
        $cond = '1=1';
        if(Url::get('from_date'))
        {
            $cond .= ' AND pc_order.create_time >= \''.Date_Time::to_time($_REQUEST['from_date']).'\'';
        }
        if(Url::get('to_date'))
        {
            $cond .= ' AND pc_order.create_time <= \''.(Date_Time::to_time($_REQUEST['to_date'])+86399).'\'';
        }
        if(Url::get('invoice_code'))
        {
            $cond .= ' AND pc_order.code LIKE \''.'%'.$_REQUEST['invoice_code'].'%'.'\'';
        }
        if(Url::get('invoice_name'))
        {
            $cond .= ' AND (pc_order.name LIKE \''.'%'.$_REQUEST['invoice_name'].'%'.'\'';
            $cond .= ' OR pc_order.name LIKE \''.'%'.mb_strtoupper($_REQUEST['invoice_name']).'%'.'\')';
        }
        if(Url::get('create_user'))
        {
            if(Url::get('create_user') != 'ALL')
            {
                $cond .= ' AND pc_order.creater = \''.$_REQUEST['create_user'].'\'';                
            }
        }
        if(Url::get('status'))
        {
            if(Url::get('status') != 'ALL')
            {
                if(Url::get('status') != 'APART_CLOSE' || Url::get('status') != 'CLOSE')
                {
                    switch(Url::get('status')){
                        case 'CREATED':
                            $status = 1;
                            break;
                        case 'ACCOUNTANT':
                            $status = 2;
                            break;
                        case 'DIRECTOR':
                            $status = 3;
                            break;
                        case 'COMPLETE':
                            $status = 4;
                            break;
                        case 'CANCEL':
                            $status = 0;
                            break;
                        default:
                            break;; 
                    }            
                }
                if(Url::get('status') == 'APART_CLOSE' || Url::get('status') == 'CLOSE')
                {
                    $cond .= ' AND pc_order.import_status = \''.$_REQUEST['status'].'\'';                                
                }else
                {
                    $cond .= ' AND pc_order.status = \''.$status.'\'';  
                }
            }
        }
        if(Url::get('supplier_name'))
        {
            $cond .= ' AND (supplier.name LIKE \''.'%'.$_REQUEST['supplier_name'].'%'.'\'';
            $cond .= ' OR supplier.name LIKE \''.'%'.mb_strtoupper($_REQUEST['supplier_name']).'%'.'\')';
        }
        $this->map = array();
        //1.1. lay ra nhung san pham thuoc 1 bo phan nao do da duoc tao hoa don
        $item_per_page = 100;
        DB::query("
    			SELECT 
    				count(pc_order.id) as acount
                FROM 
                    pc_order
                    INNER JOIN supplier ON supplier.id=pc_order.pc_supplier_id
                WHERE ".$cond."
		");
        $count = DB::fetch();
        require_once 'packages/core/includes/utils/paging.php';
		$paging = paging($count['acount'],$item_per_page,10,false,'page_no',array('cmd','invoice_code','invoice_name','create_user','status','from_date','to_date','supplier_name'));
        $sql ="SELECT * FROM
                    (
                        SELECT pc_order.*,
                            supplier.name as supplier_name
                            ,row_number() over (".(URL::get('order_by')?"order by ".URL::get('order_by').(URL::get('order_dir')?" ".URL::get('order_dir'):""):"order by pc_order.id DESC").") as rownumber
                        FROM 
                            pc_order
                            INNER JOIN supplier ON supplier.id=pc_order.pc_supplier_id
                        WHERE
                            ".$cond."
                        ORDER BY pc_order.id desc
                    )
                WHERE
                    rownumber > ".(page_no()-1)*$item_per_page." and rownumber<=".(page_no()*$item_per_page)."    
        ";
        $items = DB::fetch_all($sql);
        $index = 1;
        foreach($items as $key=>$value)
        {
            $items[$key]['index'] = $index++;
            if($value['status']==0)//neu hoa don bi huy
            {
                $sql = "select account.id,
                            party.name_1 as fullname
                        from account 
                        inner join party on party.user_id=account.id
                        where account.id='".$value['cancel_user']."'";
                $item = DB::fetch($sql);
                $items[$key]['cancel_user'] = $item['fullname'];
                if($value['time_cancel']!='')
                    $items[$key]['time_cancel'] = date('d/m/Y H:i',$value['time_cancel']);
            }
            

            $items[$key]['create_time'] = date('d/m/Y H:i',$value['create_time']);
            if($value['last_edit_time']!='')
            {
                $items[$key]['last_edit_time'] = date('d/m/Y H:i',$value['last_edit_time']);
            }
            else
                $items[$key]['last_edit_time'] =''; 
            $items[$key]['total'] = System::display_number($value['total']);
            switch ($value['status']) {
                case '1':
                    $items[$key]['status'] = CREATED;
                    break;
                case '2':
                    $items[$key]['status'] = ACCOUNTANT;
                    break;
                case '3':
                    $items[$key]['status'] = DIRECTOR;
                    break;
                case '4':
                    $items[$key]['status'] = COMPLETE;
                    break;
                case '0':
                    $items[$key]['status'] = CANCEL;
                    break;
                default:
                    break;
            }
            if($value['import_status']==CLOSE)
                $items[$key]['status'] = CLOSE;
            if($value['import_status']==APART_CLOSE)
                $items[$key]['status'] = APART_CLOSE;
        }
        foreach($items as $k => $v)
        {
            if(Url::get('status'))
            {
                if(Url::get('status') == 'COMPLETE')
                {
                    if($v['status'] == 'CLOSE' || $v['status'] == 'APART_CLOSE')
                        unset($items[$k]);
                }
            }
        }
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
        /** 7211 end*/
        $this->map['create_user_list'] = array('ALL'=>Portal::language('select'))+String::get_list($users);
        $this->map['status_list'] = array(
                'ALL'=>Portal::language('all'),
                'CREATED'=>'Đã tạo đơn hàng',
                'ACCOUNTANT'=>'KT trưởng đã duyệt',
                'DIRECTOR'=>'Giám đốc đã duyệt',
                'COMPLETE'=>'Đã mua hàng',
                'APART_CLOSE'=>'Đã đóng một phần',
                'CLOSE'=>'Đã đóng',
                'CANCEL'=>'Hủy bỏ',
        );
        $this->map['items'] = $items;
        $this->map['users'] = $users;
        $this->map['paging'] = $paging;
        $this->parse_layout('list_order',$this->map);
    }   
}
?>