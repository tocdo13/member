<?php
class EditTableMapForm extends Form
{
	function EditTableMapForm()
	{
		Form::Form('EditTableMapForm');
		$this->link_css(Portal::template('hotel').'/css/room.css');
		//$this->link_css('style.css');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
		$this->link_js('packages/core/includes/js/jquery/cookie.js');
		$this->link_css('packages/hotel/skins/default/css/jcarosel.css');
		$this->link_js('packages/core/includes/js/jquery/jquery.jcarousel.min.js');
	}
	function on_submit(){
		if(Url::get('bar_id'))
		{
			Session::set('bar_id',intval(Url::get('bar_id')));
		}
		else if(!Session::is_set('bar_id'))
		{   
			require_once 'packages/hotel/includes/php/hotel.php';
			$bar = Hotel::get_new_bar();
			if($bar){
				Session::set('bar_id',$bar['id']);
			}
			else{
				Session::set('bar_id','');
			}
		}
		$_REQUEST['bar_id'] = Session::get('bar_id');
        if(Url::get('package_id'))
        {
            Url::redirect_current(array('bar_id','in_date','table_group','package_id'=>Url::get('package_id'),'reservation_room_id'=>Url::get('reservation_room_id'),'is_submit'=>1,'group'=>Url::get('group')? Url::get('group'):''));
        }
        else
            Url::redirect_current(array('bar_id','in_date','table_group','is_submit'=>1,'group'=>Url::get('group')? Url::get('group'):''));	
	}
	function draw()
	{  

	   
		$this->cancel_book_expried();
        // Lấy id nhà hàng/phòng trong nhà hàng. - mỗi portal được phân nhánh nhiều nhà hàng
        
        $portals = Portal::get_portal_list();
        $bars = $this->get_total_bars(false);
        
        if(!Url::get('is_submit') && !Url::get('group'))
        {
            foreach($bars as $key=>$value)
            {
                Session::set('bar_id',$bars[$key]['id']);
                $_REQUEST['bar_id'] = Session::get('bar_id');
                break;
            }
        }
		$bar_id = Session::get('bar_id');
        
		$no_of_page = 500;
		$line_per_page = 36;
		$start_page = 1;
		$no_of_page = Url::get('page_no')?Url::get('page_no'):1;
        // lấy tên của nhà hàng theo bar_id.
		$sql_group='SELECT 
                        bar_table.bar_area_id as id
                        ,bar_area.name as name 
                    FROM bar_table 
                        INNER JOIN bar on bar.id=bar_table.bar_id
                        inner join bar_area on bar_area.id=bar_table.bar_area_id
                    WHERE bar_table.bar_id = '.$bar_id.'
                    ORDER BY bar_table.bar_area_id';		
		$groups = DB::fetch_all($sql_group);
        //System::debug($groups);
        // gán tên nhà hàng cho biến $name_group;
        $name_group = '';
        foreach($groups as $k => $gr){
            $name_group = $gr['id'];
            break;
        }
        // mảng $table_group lấy list danh sách tên nhà hàng theo bar_id
		$table_group[0] = '--Group--';
		$table_group = $table_group +String::get_list($groups);
        //System::debug($table_group);
		$cond = '';
		/*if($groups && !empty($groups)){
			foreach($groups as $id=> $group){
				if($group['name'] == Url::get('table_group')){
					$cond = ' and bar_table.table_group = \''.Url::get('table_group').'\'';		
				}
			}
		}*/
        if(Url::get('group')){
            $cond .= ' AND bar_table.bar_area_id=\''.Url::get('group').'\'';
            $name_group = Url::get('group');    
			Session::set('group',Url::get('group'));
        }else{
            $cond .= ' AND bar_table.bar_area_id=\''.$name_group.'\'';
			if(!Session::is_set('group')){
				Session::set('group',$name_group);
			}
        }
        $_REQUEST['name_group'] = $name_group;
		
        
		$_REQUEST['in_date'] =  Url::get('in_date')?Url::get('in_date'):date('d/m/Y',time());
	
        // Đếm số bàn/phòng trong nhà hàng
		$count_table = DB::fetch('select count(bar_table.id) as total
					FROM
						bar_table
						inner join bar on bar.id=bar_table.bar_id
                        inner join bar_area on bar_area.id=bar_table.bar_area_id
					WHERE
						bar_table.portal_id=\''.PORTAL_ID.'\'
						'.$cond.' AND bar_table.bar_id = '.$bar_id.'','total');
        //System::debug($count_table);
        // Lấy danh sách bàn trong nhà hàng. mặc định trạng thái available-> chưa có đặt bàn.
		$sql = '
				SELECT
					bar_table.id
					,bar_table.code
					,bar_table.name as name
					,bar_table.table_group
					,bar_table.bar_id
                    ,bar_table.bar_area_id
					,bar.name as bar_name
					,\'AVAILABLE\' AS status
                    ,\' \' as room_name
                    ,\' \' as traveller_name
					,ROWNUM as rownumber
				FROM
					bar_table
					inner join bar on bar.id=bar_table.bar_id
                    inner join bar_area on bar_area.id=bar_table.bar_area_id
				WHERE
					bar_table.portal_id=\''.PORTAL_ID.'\'
					 '.$cond.' AND bar_table.bar_id = '.$bar_id.'
				ORDER BY
					bar_table.name
			';
        /*SELECT * FROM
			(
        )
			WHERE
			 rownumber > '.(($no_of_page-1)*$line_per_page).' and rownumber<='.($no_of_page*$line_per_page);
        */
		$bar_tables = DB::fetch_all($sql);	
		$floors = array();
		$last_floor = false;			
		$i = 1;
        // lấy những phòng book hoặc checkout trong khoảng thời gian đang xem
		$status_tables_others = DB::fetch_all('
			SELECT
				bar_reservation_table.id,bar_reservation.id as bar_reservation_id,bar_table.id table_id,bar_reservation.status,bar_table.bar_area_id
				,bar_reservation.arrival_time,bar_reservation.total
			FROM
				bar_reservation_table
				inner join bar_reservation on bar_reservation_table.bar_reservation_id = bar_reservation.id
				inner join bar_table on bar_table.id = bar_reservation_table.table_id
				inner join bar on bar.id=bar_table.bar_id
                inner join bar_area on bar_area.id=bar_table.bar_area_id
			WHERE
				bar_reservation.portal_id=\''.PORTAL_ID.'\'
				AND ((bar_reservation.status = \'CHECKOUT\'
				AND bar_reservation.arrival_time >='.Date_Time::to_time(Url::get('in_date')).' AND bar_reservation.arrival_time <'.(Date_Time::to_time(Url::get('in_date'))+24*3600).') 
				OR (bar_reservation.status = \'BOOKED\' AND bar_reservation.departure_time<='.(Date_Time::to_time(Url::get('in_date'))+24*3600).'))
				 '.$cond.'
			ORDER BY
				bar_reservation.id ASC
		');//ABS(bar_reservation.arrival_time - '.(Date_Time::to_time(Url::get('in_date'))+(intval(date('H'))*3600+intval(date('i'))*60)).')	
        // lấy phòng dữ liệu phòng đang book hoặc đang check in trong khoảng thời gian đang xem
        $sql = '
			SELECT
				bar_table.id,
				bar_reservation.id as bar_reservation_id,bar_reservation.agent_name,
				bar_reservation.time_in,bar_reservation.time_out,
				bar_reservation.arrival_time,bar_reservation.departure_time,
				bar_reservation.total,
				bar_reservation.code,
                bar_reservation.receiver_name,
                bar_reservation.receiver_phone,
                room.name as room_name,
                CONCAT(concat(traveller.first_name,\' \'),last_name) as traveller_name,
				bar_table.name as bar_table_name,
                bar_table.bar_area_id,
				bar.name as bar_name,
				bar_reservation_table.num_people,
                bar_reservation.package_id,
                bar_reservation.reservation_room_id,
                customer.name as customer_name,
				DECODE(bar_reservation.status,\'CHECKIN\',\'OCCUPIED\',DECODE(bar_reservation.status,\'RESERVATION\',\'BOOKED\',bar_reservation.status)) AS status
			FROM
				bar_reservation
				inner join bar_reservation_table on bar_reservation_table.bar_reservation_id = bar_reservation.id
				inner join bar_table on bar_table.id = bar_reservation_table.table_id
                inner join bar_area on bar_area.id=bar_table.bar_area_id
				inner join bar on bar.id=bar_table.bar_id
                left join reservation_room on bar_reservation.reservation_room_id = reservation_room.id
                left join reservation_traveller on bar_reservation.reservation_traveller_id = reservation_traveller.id
                left join traveller on reservation_traveller.traveller_id = traveller.id
                left join room on reservation_room.room_id = room.id
                left join customer on customer.id = bar_reservation.customer_id
			WHERE
				bar_reservation.portal_id=\''.PORTAL_ID.'\' AND
				(
                (bar_reservation.status = \'CHECKIN\' AND bar_reservation.arrival_time <'.(Date_Time::to_time(Url::get('in_date')) + 86400).' '.$cond.') 
                OR 
                (bar_reservation.status = \'BOOKED\' AND bar_reservation.arrival_time >='.Date_Time::to_time(Url::get('in_date')).' AND bar_reservation.arrival_time <'.(time()+15*60).' '.$cond.')
                 )
			ORDER BY
				bar_name
		';
        //bar_reservation.arrival_time >='.Date_Time::to_time(Url::get('in_date')).' AND bar_reservation.time_in <'.(Date_Time::to_time(Url::get('in_date'))+24*3600)
        //bar_reservation.arrival_time >='.Date_Time::to_time(Url::get('in_date')).' AND bar_reservation.arrival_time <'.(time()+15*60).'
        //ABS(bar_reservation.arrival_time - '.(Date_Time::to_time(Url::get('in_date'))+(intval(date('H'))*3600+intval(date('i'))*60)).')
		$busy_tables = DB::fetch_all($sql);
		$table_out_list = DB::fetch_all('
			select
				bar_table.id as table_id,
				bar_reservation.id as bar_reservation_id,
                bar_reservation_table.id as id
			from
				bar_table
                inner join bar_area on bar_area.id=bar_table.bar_area_id
				inner join bar_reservation_table on bar_reservation_table.table_id = bar_table.id
				INNER JOIN bar_reservation on bar_reservation.id = bar_reservation_table.bar_reservation_id
			where
			bar_reservation.portal_id=\''.PORTAL_ID.'\'
			AND ((bar_reservation.status = \'CHECKOUT\'
			AND bar_reservation.arrival_time >='.Date_Time::to_time(Url::get('in_date')).' AND bar_reservation.arrival_time <'.(Date_Time::to_time(Url::get('in_date'))+24*3600).') 
			OR (bar_reservation.status = \'BOOKED\' AND bar_reservation.departure_time<='.(Date_Time::to_time(Url::get('in_date'))+24*3600).'))
			 '.$cond.'
		'
		); //AND bar_reservation.arrival_time >='.(time()+15*60).'
        $booked = 0;
        $checkin = 0;
        $over_checkin = 0;
        $available = 0;
		foreach($bar_tables as $key=>$value)
		{
			$id_booked = '';
			$floors[$value['bar_area_id']]['id']= $value['bar_id'];
			$floors[$value['bar_area_id']]['name']= $value['bar_area_id'];
			$value['class'] = 'AVAILABLE';
			$value['agent_name'] = '';
            if(Url::get('package_id'))
            {
                $value['href'] = URL::build('touch_bar_restaurant',array('cmd'=>'add','package_id'=>Url::get('package_id'),'rr_id'=>Url::get('reservation_room_id'),'arrival_time'=>$_REQUEST['in_date']));
            }
            else
			     $value['href'] = URL::build('touch_bar_restaurant',array('cmd'=>'add','arrival_time'=>$_REQUEST['in_date']));
			
            if(Url::get('package_id'))
            {
                $value['href_flat'] = URL::build('touch_bar_restaurant',array('cmd'=>'add','package_id'=>Url::get('package_id'),'rr_id'=>Url::get('reservation_room_id'),'arrival_time'=>$_REQUEST['in_date']));
            }
            else
			     $value['href_flat'] = URL::build('touch_bar_restaurant',array('cmd'=>'add','arrival_time'=>$_REQUEST['in_date']));
            
			$value['arrival_time'] = '';
			$value['departure_time'] = '';
			$value['num_people'] = '';
			if(isset($busy_tables[$key])){
                if(isset($busy_tables[$key]['reservation_room_id'])){
                    $value['receiver_name']=$busy_tables[$key]['receiver_name'];
                    $value['receiver_phone']=$busy_tables[$key]['receiver_phone'];
                    $value['customer_name']=$busy_tables[$key]['customer_name'];
                }
                if(intval($busy_tables[$key]['total'])<0)
                    $value['total'] =0;
                else
				    $value['total'] = $busy_tables[$key]['total'];
				$value['code'] = $busy_tables[$key]['code'];
				$value['date_in'] = date('d/m/Y',$busy_tables[$key]['time_in']?$busy_tables[$key]['time_in']:$busy_tables[$key]['arrival_time']);
                $value['status'] = $this->get_status_checkin_over($busy_tables[$key]['status'],$busy_tables[$key]['departure_time']) ;
                if($value['status']!='AVAILABLE')
                {
                    if($this->checkdate_arrival_departure($busy_tables[$key]['arrival_time'],$busy_tables[$key]['departure_time']))
                    {
                        //cung ngay thang
                        if($value['status']=='OVERCHECKIN')
                            $value['arrival_time'] = date('d/m H:i',$busy_tables[$key]['arrival_time']);
                        else 
                            $value['arrival_time'] = date('H:i',$busy_tables[$key]['arrival_time']);
    				    $value['departure_time'] = date('H:i',$busy_tables[$key]['departure_time']);
                    }
                    else
                    {
                        //khac ngay thang
                        $value['arrival_time'] = date('d/m H:i',$busy_tables[$key]['arrival_time']);
    				    $value['departure_time'] = date('d/m H:i',$busy_tables[$key]['departure_time']);
                    }
                    $value['agent_name'] = $busy_tables[$key]['agent_name'];
                    $value['room_name'] = $busy_tables[$key]['room_name'];
                    $value['traveller_name'] = $busy_tables[$key]['traveller_name'];
    				if($busy_tables[$key]['status']=='BOOKED')
    				{
    				    if($busy_tables[$key]['package_id']!='')
                        {
                            $value['href'] = URL::build('touch_bar_restaurant',array('cmd'=>'edit','package_id'=>$busy_tables[$key]['package_id'],'rr_id'=>$busy_tables[$key]['reservation_room_id'],'id'=>$busy_tables[$key]['bar_reservation_id']));
                        }
                        else
                        {
                            $value['href'] = URL::build('touch_bar_restaurant',array('cmd'=>'edit','id'=>$busy_tables[$key]['bar_reservation_id']));
                        }
    					//$value['href'] = URL::build('touch_bar_restaurant',array('cmd'=>'edit','id'=>$busy_tables[$key]['bar_reservation_id']));
    					$id_booked = $busy_tables[$key]['bar_reservation_id'];
    				}
    				else
    				{  
    				    //START giap.ln add package service 
    				    if($busy_tables[$key]['package_id']!='')
                            $value['href'] = URL::build('touch_bar_restaurant',array('cmd'=>'edit','id'=>$busy_tables[$key]['bar_reservation_id'],'package_id'=>$busy_tables[$key]['package_id'],'rr_id'=>$busy_tables[$key]['reservation_room_id']));
                        else
                            $value['href'] = URL::build('touch_bar_restaurant',array('cmd'=>'edit','id'=>$busy_tables[$key]['bar_reservation_id']));
                        //END 
    				}
    				$value['num_people'] = $busy_tables[$key]['num_people'].' '.Portal::language('people');
                }
                else
                {
                    $value['agent_name'] ='';
                    $value['room_name'] = '';
                    $value['traveller_name'] = '';
                }	
			}
			else
			{
				$value['total'] = '';
			}
			if($value['status']){
				$value['class'] = $value['status'];	
			}
			$value['status_tables_others'] = array();
            switch($value['status']){
                        case "AVAILABLE" : 
                                            $available++;
                                            break;
                        case "BOOKED" :
                                          $booked++;
                                          break;
                        case "OCCUPIED" :
                                          $checkin++;
                                          break;
                        case "OVERCHECKIN" :
                                          $over_checkin++;
                                          break;                                                              
                }
			foreach($status_tables_others as $k=>$v)
            {
				foreach($table_out_list as $tbl_id=>$tbl)
				{
					if($tbl['table_id']==$key and $v['bar_reservation_id']==$tbl['bar_reservation_id'] && $tbl['bar_reservation_id']!=$id_booked){
						$value['status_tables_others'][$v['bar_reservation_id']]['id'] = $v['bar_reservation_id'];
						$value['status_tables_others'][$v['bar_reservation_id']]['status'] = $v['status'];
                        $value['status_tables_others'][$v['bar_reservation_id']]['bar_area_id'] = $v['bar_area_id'];
                        $value['status_tables_others'][$v['bar_reservation_id']]['table_id'] = $v['table_id'];
					}
				}
			}
			$floors[$value['bar_area_id']]['bar_tables'][$i]= $value;
			$floors[$value['bar_area_id']]['bar_id'] = $value['bar_id'];
			$i++;
		}
        $this->map['available'] = $available;
        $this->map['booked'] = $booked;
        $this->map['checkin'] = $checkin;
        $this->map['over_checkin'] = $over_checkin;
		$this->map['list_bar'] = $bars;
		foreach($floors as $j => $floor){
			if(isset($bars[$floor['bar_id']])){
				$bars[$floor['bar_id']]['floors'][$j] = $floor;	
			}
		}
        
        // Thanh add phan hien thi mon an ra man hinh Socket
        if(USE_DISPLAY && USE_DISPLAY==1 && isset($_GET['bar_reservation_id'])){
                if(isset($_GET['type'])){
                    
                    $from_code = $_GET['from_code'];
                    if(isset($_GET['to_table'])){ // Phan nay su dung de chuyen mon
                        $start_date = Date_Time::to_time(date("d/m/Y"));
                        $end_date = $start_date + 3600*24;
                        
                        $to_table = $_GET['to_table'];
                        $sql = "SELECT bar_reservation.id as id 
                        FROM bar_reservation 
                        INNER JOIN bar_reservation_table ON bar_reservation.id = bar_reservation_table.bar_reservation_id 
                        WHERE bar_reservation_table.table_id = $to_table AND bar_reservation.status = 'CHECKIN'";
                        $table = DB::fetch($sql);
                            $to_code = $table['id'];
                    }       
                    else{
                      $to_code = $_GET['to_code'];  
                    } 
                    $sql = "SELECT 
                     bar_reservation_product.id as id,
                     bar_reservation_product.quantity,
                     bar_reservation_product.complete,
                     bar_reservation_product.remain,
                     bar_reservation_product.name,
                     bar_table.name as table_name,
                     product_category.code as code
                    FROM bar_reservation_product 
                        INNER JOIN bar_reservation ON bar_reservation.id = bar_reservation_product.bar_reservation_id
                        INNER JOIN bar_reservation_table ON bar_reservation_table.bar_reservation_id =  bar_reservation.id
                        INNER JOIN bar_table ON bar_table.id = bar_reservation_table.table_id
                        INNER JOIN product ON product.id =  bar_reservation_product.product_id
                        INNER JOIN product_category ON product_category.id = product.category_id              
                    WHERE bar_reservation_product.bar_reservation_id IN($from_code,$to_code) ORDER BY bar_reservation.time_in, bar_reservation_product.id DESC";
                    $arr_product = DB::fetch_all($sql);
                    foreach($arr_product as $key=>$value){
                        $arr_product[$key]['target'] = 'split';
                    }
                }
                else{
                    $bar_reservation_id = $_GET['bar_reservation_id'];
                    $sql = "SELECT 
                     bar_reservation_product.id as id,
                     bar_reservation.status as status,
                     bar_reservation_product.quantity,
                     bar_reservation_product.complete,
                     bar_reservation_product.remain,
                     bar_reservation_product.name,
                     bar_table.name as table_name,
                     product_category.code as code
                    FROM bar_reservation_product 
                        INNER JOIN bar_reservation ON bar_reservation.id = bar_reservation_product.bar_reservation_id
                        INNER JOIN bar_reservation_table ON bar_reservation_table.bar_reservation_id =  bar_reservation.id
                        INNER JOIN bar_table ON bar_table.id = bar_reservation_table.table_id
                        INNER JOIN product ON product.id =  bar_reservation_product.product_id
                        INNER JOIN product_category ON product_category.id = product.category_id              
                    WHERE bar_reservation_product.bar_reservation_id=$bar_reservation_id ORDER BY bar_reservation.time_in, bar_reservation_product.id DESC";
                    $arr_product = DB::fetch_all($sql);
                    
                    if(isset($_GET['target'])){
                        $sql = "SELECT * FROM bar_reservation_table INNER JOIN bar_table ON bar_reservation_table.table_id=bar_table.id WHERE bar_reservation_table.bar_reservation_id=$bar_reservation_id";
                        $result = DB::fetch_all($sql);
                        $table_name = '';
                        foreach($result as $key=>$value){
                            $table_name.=$value['name']."+";
                        }
                        $table_name=substr($table_name,0,strlen($table_name)-1);
                        foreach($arr_product as $key=>$value){
                           $arr_product[$key]['table_name'] = $table_name;
                        }
                    }
                }
                $food_categories = $this->get_list_food_category();
                foreach($arr_product as $key=>$value){
                    foreach($food_categories as $k=>$v){
                            if($value['code'] == $v['code']){
                                $arr_product[$key]['type'] = 'cooking';
                                break;
                            }
                            else{
                                $arr_product[$key]['type'] = 'bar';
                            }
                        }
                }
                
                if(isset($_GET['to_table'])){
                        $sql = "SELECT bar_table.id as id, bar_table.name 
                        FROM bar_table 
                        INNER JOIN bar_reservation_table ON bar_table.id = bar_reservation_table.table_id 
                        INNER JOIN bar_reservation ON bar_reservation.id = bar_reservation_table.bar_reservation_id
                        WHERE bar_reservation.id IN($from_code,$to_code) ORDER BY bar_table.id";
                        $result = DB::fetch_all($sql);
                        $table_name = '';
                        foreach($result as $key=>$value){
                            $table_name.=$value['name']."+";
                        }
                        $table_name=substr($table_name,0,strlen($table_name)-1);
                         $arr_product['table_move_order'] = $table_name;
                         
                    }
                
                
                $arr_product_js = String::array2js($arr_product);
            //unset($_GET['target']);    
            
            $this->map['arr_product_js'] = $arr_product_js;  
            if(isset($_REQUEST['close'])){
                $this->map['close'] = 'ok';
            }
        }
        // end
        
		//System::Debug($bar); exit();
		$this->map['total_page'] = ceil($count_table/$line_per_page);
		$this->map['bars'] = $bars;
        //$this->map['barsjs'] = String::array2js($bars);
		$this->parse_layout('list',$this->map+array('table_group_list'=>$table_group,
                                                'groups'=>$groups,
                                                'name_group'=>$name_group));
	}
    function checkdate_arrival_departure($arrival_time,$departure_time)
    {
        if(date('d/m/Y',$arrival_time)==date('d/m/Y',$departure_time))
            return true;
        return false;
    }
    function get_status_checkin_over($status,$departure_time)
    {
        if($status=='OCCUPIED' || $status=='BOOKED')
        {
            $now = $_REQUEST['in_date'];
            $str_now = explode("/",$now);
            
            $date_now = mktime(0,0,0,$str_now[1],$str_now[0],$str_now[2]);
            $departure = mktime(0,0,0,date('m',$departure_time),date('d',$departure_time),date('Y',$departure_time));
            if($status=='OCCUPIED')
            {
                if($date_now>$departure)
                {
                    return 'OVERCHECKIN';
                }
            }
            if($status=='BOOKED')//neu book qua han se khong duoc hien thi 
            {
                //AVAILABLE
                if($date_now>$departure)
                {
                    return 'AVAILABLE';
                }
            }
        }
        return $status;
    }
	function get_total_bars($bar_id = false){
		//-------- Phan quyen Bar-------------//
		require_once 'packages/hotel/packages/restaurant/includes/table.php';
		$cond_admin = Table::get_privilege_bar();
		$bars = array();
		if(User::is_admin() || $cond_admin){
			$bars = DB::fetch_all('SELECT * FROM BAR where 1=1 '.$cond_admin.' and portal_id = \''.PORTAL_ID.'\' ORDER BY ID ASC');
		}
		return $bars;
	}
	function get_total_bars_select($bar_id = false){
		//-------- Phan quyen Bar-------------//
		require_once 'packages/hotel/packages/restaurant/includes/table.php';
		$cond_admin = Table::get_privilege_bar();
		if(User::is_admin() || $cond_admin){
			$bars = DB::fetch_all('SELECT * FROM BAR where 1=1 '.$cond_admin.' and portal_id = \''.PORTAL_ID.'\' ORDER BY ID ASC');
		}
		$items = '<select name="bar_id" onchange="ChangeBar();" id="bar_id">';
		$items .='<option value="">--Select Bar--</option>';	
		foreach($bars as $id=>$bar){
			if($bar['id'] == $bar_id){
				$items .= '<option value="'.$bar['id'].'" selected="selected">'.$bar['name'].'</option>';	
			}else $items .= '<option value="'.$bar['id'].'">'.$bar['name'].'</option>';	
		}
		$items .='</select>';
		return $items;
	}
    function cancel_book_expried()
    {
        $now = mktime(0,0,0,date('m'),date('d'),date('Y'));
        $conditons = " bar_reservation.status='BOOKED' AND bar_reservation.departure_time <".$now;
		DB::update('bar_reservation',array('status'=>'CANCEL','cancel_time'=>time()),$conditons);	
	}
    static function get_list_food_category(){
		$dp_code = DB::fetch('select department_id as code from bar where portal_id=\''.PORTAL_ID.'\'','code');	
		$structure_id_food = DB::fetch('select structure_id from product_category where code=\'DA\'','structure_id');
		$sql = 'SELECT
					product_category.id
					,product_category.name
					,product_category.structure_id
                    ,product_category.code
				FROM
					product_category
					INNER JOIN product ON product_category.id = product.category_id
					INNER JOIN product_price_list ON product_price_list.product_id = product.id
					INNER JOIN unit ON unit.id = product.unit_id
				WHERE 
					1>0 
					AND (product_category.structure_id > '.$structure_id_food.' AND  product_category.structure_id < '.IDStructure::next($structure_id_food).')
					AND product_price_list.portal_id = \''.PORTAL_ID.'\'
					AND product_price_list.department_code=\''.$dp_code.'\' 
                    AND (product_price_list.end_date is null OR product_price_list.end_date>=\''.Date_Time::to_orc_date(date('d/m/y')).'\')
				ORDER BY product_category.position';	
		$categories = DB::fetch_all($sql);
		//System::Debug($categories);
		return $categories;
	}
}
?>