<?php
class ManagePlanInMonthForm extends Form{
	function ManagePlanInMonthForm()
    {
		Form::Form('ManagePlanInMonthForm');
        $this->add('plan_in_month.name',new TextType(true,'name',0,255));
		$this->add('plan_in_month.value',new TextType(true,'value',0,255));
        $this->add('plan_in_month.currency_id',new TextType(true,'value',0,255));
		$this->link_js('packages/core/includes/js/multi_items.js');
        require_once 'packages/hotel/includes/php/module.php';
	}
    
	function on_submit()
    {
        if(Url::get('save'))
        {
            if($this->check())
            {
                if(URL::get('deleted_ids'))
                {
    				$ids = explode(',',URL::get('deleted_ids'));
    				foreach($ids as $id)
                    {
    					DB::delete_id('plan_in_month',$id);
    				}
    			}
                
                if(isset($_REQUEST['mi_plan']))
                {
                	foreach($_REQUEST['mi_plan'] as $key=>$record)
                	{
                        $record['year'] = Url::get('year');
                        $record['portal_id'] = PORTAL_ID; 
                		$record['value'] = System::calculate_number($record['value']);
                        $record['room_type_id'] = System::calculate_number($record['room_type_id']);
                		$record['bar'] = System::calculate_number($record['bar']);
                        $record['bar_index'] = System::calculate_number($record['bar_index']);
    					if($record['id'] and DB::exists_id('plan_in_month',$record['id']))
                        {
                            DB::update_id('plan_in_month',$record,$record['id']);
                        }
                        else
                        {
                        	unset($record['id']);
                            
                            if( $old_id = DB::fetch('SELECT * FROM plan_in_month WHERE name=\''.$record['name'].'\' and total = \''.$record['total'].'\' and month = \''.$record['month'].'\' and year = '.$record['year'].' and portal_id = \''.$record['portal_id'].'\' ','id'))
                            {
    							DB::update_id('plan_in_month',array('bar'=>$record['bar'],'total'=>$record['total'],'value'=>$record['value'],'currency_id'=>$record['currency_id']),$old_id);
    						}
                            else
                            {
                                DB::insert('plan_in_month',$record);    
                            }
    							
                        }
                    }
                    Url::redirect_current();
                }
                else
                {
                	return;
                }
            }
        }

	}	
	function draw()
    {
        $this->map = array();
        $currency = DB::fetch_all('Select * from currency where allow_payment = 1 order by id desc');
        //System::debug($currency);
        $currency_id_list = '<option value="">'.Portal::language('select').'</option>
                             <option value="%">'.Portal::language('%').'</option>
                             <option value="USD">'.Portal::language('USD').'</option>';
        foreach($currency as $k=>$v)
        {
            if($k!='USD')
                $currency_id_list.= '<option value="'.$k.'">'.$v['name'].'</option>';
        }
        $this->map['currency_id_list'] = $currency_id_list;
        $room_type = DB::fetch_all('select room_type.id, room_type.name from room_type');
        //System::debug($room_type);
        $room_type_list = '<option value="">'.Portal::language('select').'</option>';
            foreach($room_type as $value)
                {
                    $room_type_list.='<option value="'.$value['id'].'">'.$value['name'].'</option>';
                }
        $this->map['room_type_list'] = $room_type_list;
        
        $bar = DB::fetch_all('select bar.id, bar.name from bar');
        $bar_list = '<option value="">'.Portal::language('select').'</option>';
        foreach($bar as $value)
                {
                    $bar_list.='<option value="'.$value['id'].'">'.$value['name'].'</option>';
                }    
        $this->map['bar_list'] = $bar_list;
        $bar_index = '<option value="">'.Portal::language('select').'</option>
                        <option value="is_processed">'.Portal::language('Hàng chuyển bán').'</option>
                        <option value="product">'.Portal::language('Sản phẩm').'</option>
                        <option value="service">'.Portal::language('Dịch vụ').'</option>';
        
        $this->map['bar_index'] = $bar_index;                
        $month_list = '
                        <option value="">'.Portal::language('select').'</option>
                        <option value="01">'.Portal::language('Tháng 01').'</option>
                        <option value="02">'.Portal::language('Tháng 02').'</option>
                        <option value="03">'.Portal::language('Tháng 03').'</option>
                        <option value="04">'.Portal::language('Tháng 04').'</option>
                        <option value="05">'.Portal::language('Tháng 05').'</option>
                        <option value="06">'.Portal::language('Tháng 06').'</option>
                        <option value="07">'.Portal::language('Tháng 07').'</option>
                        <option value="08">'.Portal::language('Tháng 08').'</option>
                        <option value="09">'.Portal::language('Tháng 09').'</option>
                        <option value="10">'.Portal::language('Tháng 10').'</option>
                        <option value="11">'.Portal::language('Tháng 11').'</option>
                        <option value="12">'.Portal::language('Tháng 12').'</option>
                        ';
        $this->map['month_list'] = $month_list;
        $total = '
                        <option value="">'.Portal::language('select').'</option>
                        <option value="KHTH1">'.Portal::language('Lượng Khách: Châu Âu').'</option>
                        <option value="KHTH2">'.Portal::language('Lượng Khách: Châu Á').'</option>
                        <option value="KHTH3">'.Portal::language('Lượng Khách: Các Nước Khác').'</option>
                        <option value="KHTH4">'.Portal::language('Lượng Khách: Huế').'</option>
                        <option value="KHTH5">'.Portal::language('Lượng Khách: Hà Nội - TP.HCM').'</option>
                        <option value="KHTH6">'.Portal::language('Lượng Khách: Khách VN vùng khác').'</option>
                        <option value="KHTH7">'.Portal::language('Tổng số phòng').'</option>
                        <option value="KHTH8">'.Portal::language('Phòng có thể cho thuê').'</option>
                        <option value="KHTH9">'.Portal::language('Phòng có khách').'</option>
                        <option value="KHTH10">'.Portal::language('Vé vào cửa bình quân').'</option>
                        <option value="KHTH11">'.Portal::language('Giá phòng bình quân').'</option>
                        <option value="KHTH12">'.Portal::language('Vé vào cửa').'</option>
                        <option value="KHTH13">'.Portal::language('Doanh thu bán phòng').'</option>
                        <option value="KHTH14">'.Portal::language('Doanh thu buồng').'</option>
                        <option value="KHTH15">'.Portal::language('Doanh thu nhà hàng').'</option>
                        <option value="KHTH16">'.Portal::language('Highwire').'</option>
                        <option value="KHTH17">'.Portal::language('Zipline').'</option>
                        <option value="KHTH18">'.Portal::language('Làng nghề').'</option>
                        <option value="KHTH19">'.Portal::language('Bán hàng').'</option>
                        <option value="KHTH20">'.Portal::language('Doanh thu Spa').'</option>
                        <option value="KHTH21">'.Portal::language('Doanh thu phòng họp').'</option>
                        <option value="KHTH22">'.Portal::language(' DV khác').'</option>
                        <option value="KHTH23">'.Portal::language('Doanh Thu Thuần').'</option>
                        ';
        $this->map['total'] = $total;
        $name_list = '<option value="">'.Portal::language('select').'</option>
                        <option value="ROOM">'.Portal::language('Doanh Thu Phòng').'</option>
                        <option value="MINIBAR">'.Portal::language('Doanh Thu Minibar').'</option>
                        <option value="LAUNDRY">'.Portal::language('Doanh Thu Laundry').'</option>
                        <option value="EXTRA_SERVICES">'.Portal::language('Doanh Thu DV Khác').'</option>';
        $this->map['name_list'] = $name_list;

		$sql = 'SELECT plan_in_month.* FROM plan_in_month where portal_id=\''.PORTAL_ID.'\' and year = '.(Url::get('year')?Url::get('year'):date('Y')).' order by plan_in_month.id';
		$plans = DB::fetch_all($sql);
		foreach($plans as $k=>$v)
        {
            $plans[$k]['value'] = System::display_number($plans[$k]['value']);
        }
        //System::Debug($plans);

		$_REQUEST['mi_plan'] = $plans;
        	
		$this->parse_layout('edit',$this->map);
	}
}
?>