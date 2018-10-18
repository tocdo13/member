<?php
class EditRateCodeForm extends Form
{
	function EditRateCodeForm()
	{
		Form::Form();
		$this->link_js('packages/core/includes/js/multi_items.js');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_css(Portal::template('hotel').'/css/style.css');
		$this->add('name',new TextType(true,'miss_name',0,255));
	}
	function on_submit()
    {
        if(Url::get('cmd')=='add')
        {
            $this->insert_rate_code();
        }
        elseif(Url::get('cmd')=='edit')
        {
            $this->update_rate_code();
        }
        else{}
        Url::redirect_current();
		
	}
	function draw()
	{
		$this->map = array();
        if(Url::get('cmd')=='add')
        {
            $this->map['edit_rate_code'] ='Thêm mới rate code';
            $this->load_form_add();
        }
        else
        {
            $this->map['edit_rate_code'] ='Sửa rate code';
            $this->load_form_edit();
        }
        
        //2. hien thi trang thai tuan xuat: hang ngay, hang tuan, hang thang, hang nam
        $frequence_options ="<option value='DAILY'>Hàng ngày</option>";
        $frequence_options .="<option value='WEEKLY'>Hàng tuần</option>";
        $frequence_options .="<option value='MONTHLY'>Hàng tháng</option>";
        $frequence_options .="<option value='YEARLY'>Hàng năm</option>";
        $this->map['frequence_options'] = $frequence_options;
        //end tan xuat
        
        //3. hien thi loai ngay: binh thuong, ngay le, dac biet
        $date_type_options = "<option value='NORMAL'>Ưu Tiên 1</option>";
        $date_type_options .= "<option value='CELEBRATE'>Ưu Tiên 2</option>";
        $date_type_options .= "<option value='SPECIAL'>Ưu Tiên 3</option>";
        $this->map['date_type_options'] = $date_type_options;
        //end loai ngay
        
        
		$this->parse_layout('edit',$this->map);
	}	
    function set_rate_code()
    {
        $start_date = Date_Time::to_orc_date(Url::get('start_date'));
        $end_date = Date_Time::to_orc_date(Url::get('end_date'));
        $frequence = Url::get('frequence_id');
        $date_type = Url::get('date_type_id');
        if($date_type=='SPECIAL')
            $priority = 2;
        elseif($date_type=='CELEBRATE')
            $priority = 1;
        else
            $priority = 0;
        //1. ghi thong tin tren table Rate_code
        $arr_rate_code = array('code'=>Url::get('code'),
                                'name'=>Url::get('name'),
                                'start_date'=>$start_date,
                                'end_date'=>$end_date,
                                'frequence'=>$frequence,
                                'date_level'=>$date_type,
                                'priority'=>$priority);
        if($frequence=='WEEKLY')
        {
            $weekly='';
            for($i=2;$i<9;$i++)
            {
                if(isset($_REQUEST['chbT'.$i]))
                    $weekly .=$i.',';
            }
            $weekly = substr($weekly,0,strlen($weekly)-1);
            $arr_rate_code +=array('weekly'=>$weekly);
        }
        return $arr_rate_code;
    }
    function insert_rate_code()
    {
        //1. ghi thong tin rate code 
        $arr_rate_code = $this->set_rate_code();
        $rate_code_id = DB::insert('rate_code',$arr_rate_code);
        //2. ghi thong tin tren rate_room_level
        foreach($_REQUEST['mi_room_level'] as $key=>$value)
        {
            $price = System::calculate_number($value['price']);
            $arr_rate_room = array('room_level_id'=>$value['id'],
                                'rate_code_id'=>$rate_code_id,
                                'price'=>$price);
            
            DB::insert('rate_room_level',$arr_rate_room);
        }
        
        //3. ghi thong tin tren rate_customer_group 
        $customer_group_count = Url::get('customer_group_count');
        for($i=1;$i<=$customer_group_count;$i++)
        {
            if(Url::get('item_check_box_'.$i))
            {
                $arr_customer_group = array('customer_group_id'=>Url::get('customer_group_id_'.$i),
                                            'rate_code_id'=>$rate_code_id);
                DB::insert('rate_customer_group',$arr_customer_group);
            }
        }
    }
    function update_rate_code()
    {
        //1. cap nhat thong tin rate code 
        $arr_rate_code = $this->set_rate_code();
        DB::update('rate_code',$arr_rate_code,'id='.Url::get('id'));
        //2. cap nhat thong tin gia hang phong 
        DB::delete('rate_room_level','rate_code_id='.Url::get('id'));
        foreach($_REQUEST['mi_room_level'] as $key=>$value)
        {
            $price = System::calculate_number($value['price']);
            $arr_rate_room = array('room_level_id'=>$value['id'],
                                'rate_code_id'=>Url::get('id'),
                                'price'=>$price);
            
            DB::insert('rate_room_level',$arr_rate_room);
        }
        //3. cap nhat thong tin nhom nguon khach  
        DB::delete('rate_customer_group','rate_code_id='.Url::get('id'));
        $customer_group_count = Url::get('customer_group_count');
        for($i=1;$i<=$customer_group_count;$i++)
        {
            if(Url::get('item_check_box_'.$i))
            {
                $arr_customer_group = array('customer_group_id'=>Url::get('customer_group_id_'.$i),
                                            'rate_code_id'=>Url::get('id'));
                DB::insert('rate_customer_group',$arr_customer_group);
            }
        }
    }
    function load_form_add()
    {
        //1. hien thi danh sach hang phong 
        $sql = '
			SELECT
				room_level.id,
                room_level.brief_name as code,
                room_level.name
			FROM
				room_level 
            WHERE portal_id=\''.PORTAL_ID.'\' order by room_level.id';
		$room_levels = DB::fetch_all($sql);
		//System::Debug($bars);
		$i=1;
		foreach($room_levels as $key => $value)
        {
			$room_levels[$key]['index'] = $i++;
            $room_levels[$key]['price'] = '';
		}
		$_REQUEST['mi_room_level'] = $room_levels;
        //end hien thi danh sach hang phong 

        //4. hien thi tat ca cac nguon khach
        $sql = "SELECT id,name FROM customer_group ORDER BY name";
        $customer_groups = DB::fetch_all($sql);
        $i = 1;
        foreach($customer_groups as $key=>$value)
        {
            $customer_groups[$key]['index'] = $i++;
        }
        $this->map['customer_groups'] = $customer_groups;
        //end hien thi nguon khach 
        
    }
    
    function load_form_edit()
    {
        //1. hien thi thong tin rate code 
        $sql = "SELECT *
                FROM rate_code
                WHERE rate_code.id=".Url::get('id');
                
        $row = DB::fetch($sql);
        
        
        $_REQUEST['code'] = $row['code'];
        $_REQUEST['name'] = $row['name'];
        $_REQUEST['start_date'] =Date_Time::convert_orc_date_to_date($row['start_date'],"/");
        $_REQUEST['end_date'] = Date_Time::convert_orc_date_to_date($row['end_date'],"/");
        $_REQUEST['frequence_id'] = $row['frequence'];
        $_REQUEST['date_type_id'] = $row['date_level'];
        if($row['weekly']!='')
        {
            $_REQUEST['weekly'] = $row['weekly'];
        }
        //1. hien thi danh sach hang phong 
        $sql = '
			SELECT
				room_level.id,
                room_level.brief_name as code,
                room_level.name,
                rate_room_level.price
			FROM
				room_level 
            INNER JOIN rate_room_level ON rate_room_level.room_level_id=room_level.id
            WHERE portal_id=\''.PORTAL_ID.'\' 
                AND rate_room_level.rate_code_id='.Url::get('id').'
            order by room_level.id';
		$room_levels = DB::fetch_all($sql);

		$i=1;
		foreach($room_levels as $key => $value)
        {
			$room_levels[$key]['index'] = $i++;
            $room_levels[$key]['price'] = System::display_number($value['price']);
		}
		$_REQUEST['mi_room_level'] = $room_levels;
        //end hien thi danh sach hang phong 
        
        //4. hien thi tat ca cac nguon khach
        $sql = "SELECT customer_group.id,
                        customer_group.name,
                        rate_customer_group.id as rate_customer_group_id 
                FROM 
                    customer_group 
                    LEFT JOIN rate_customer_group ON rate_customer_group.customer_group_id=customer_group.id 
                    AND rate_customer_group.rate_code_id=".Url::get('id')."
                ORDER BY customer_group.name";
        $customer_groups = DB::fetch_all($sql);
        $i = 1;
        foreach($customer_groups as $key=>$value)
        {
            $customer_groups[$key]['index'] = $i++;
        }
        
        $this->map['customer_groups'] = $customer_groups;
    }
}
?>