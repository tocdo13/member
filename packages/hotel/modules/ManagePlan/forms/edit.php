<?php
class ManagePlanForm extends Form{
	function ManagePlanForm()
    {
		Form::Form('ManagePlanForm');
    	$this->add('plan.code',new TextType(true,'code',0,255));
		$this->add('plan.value',new TextType(true,'value',0,255));
        $this->add('plan.currency_id',new TextType(true,'value',0,255));
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
    					DB::delete_id('plan',$id);
    				}
    			}
                
                if(isset($_REQUEST['mi_plan']))
                {
                	foreach($_REQUEST['mi_plan'] as $key=>$record)
                	{
                        $record['year'] = Url::get('year');
                        $record['portal_id'] = PORTAL_ID; 
                		$record['value'] = System::calculate_number($record['value']);
                        $record['position'] = System::calculate_number($record['position']);
                		
    					if($record['id'] and DB::exists_id('plan',$record['id']))
                        {
                            DB::update_id('plan',$record,$record['id']);
                        }
                        else
                        {
                        	unset($record['id']);
                            
                            if( $old_id = DB::fetch('SELECT * FROM plan WHERE code=\''.$record['code'].'\' and year = '.$record['year'].' and portal_id = \''.$record['portal_id'].'\' ','id'))
                            {
    							DB::update_id('plan',array('value'=>$record['value'],'currency_id'=>$record['currency_id']),$old_id);
    						}
                            else
                            {
                                DB::insert('plan',$record);    
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
        //System::debug($currency_id_list);
        $currency_id_list = '<option value="">'.Portal::language('select').'</option>
                             <option value="%">'.Portal::language('%').'</option>
                             <option value="USD">'.Portal::language('USD').'</option>';
        foreach($currency as $k=>$v)
        {
            if($k!='USD')
                $currency_id_list.= '<option value="'.$k.'">'.$v['name'].'</option>';
        }
        $this->map['currency_id_list'] = $currency_id_list;
		
        $code_list = '<option value="">'.Portal::language('select').'</option>
                      <option value="REVENUE">'.Portal::language('revenue').'</option>
                      <option value="ROOM_REVENUE">'.Portal::language('room_revenue').'</option>
                      <option value="AVERAGE_OCCUPANCY">'.Portal::language('average_occupancy').'</option>
                      <option value="AVERAGE_PRICE">'.Portal::language('average_price').'</option>
                      <option value="BAR_REVENUE">'.Portal::language('bar_revenue').'</option>
                      <option value="OTHER_REVENUE">'.Portal::language('other_revenue').'</option>
                      <option value="MASSAGE">'.Portal::language('massage').'</option>
                      <option value="PHONE">'.Portal::language('phone').'</option>
                      <option value="TRANSPORT">'.Portal::language('transport').'</option>
                      <option value="OTHER">'.Portal::language('other').'</option>
                      <option value="PPV_REVENUE">'.Portal::language('ppv_revenue').'</option>
                      <option value="CLUB9_REVENUE">'.Portal::language('club9_revenue').'</option>';
        $this->map['code_list'] = $code_list;
        $name_list = array(
                           'REVENUE'=>array('name_1'=>'Tổng doanh thu','name_2'=>'Revenue'),
                           'ROOM_REVENUE'=>array('name_1'=>'Doanh thu phòng','name_2'=>'Room revenue'),
                           'AVERAGE_OCCUPANCY'=>array('name_1'=>'Công suất bình quân','name_2'=>'Average occupancy'),
                           'AVERAGE_PRICE'=>array('name_1'=>'Giá phòng bình quân','name_2'=>'Average price'),
                           'BAR_REVENUE'=>array('name_1'=>'Doanh thu nhà hàng','name_2'=>'Bar revenue'),
                           'OTHER_REVENUE'=>array('name_1'=>'Doanh thu khác','name_2'=>'Other revenue'),
                           'MASSAGE'=>array('name_1'=>'Massage','name_2'=>'Massage'),
                           'PHONE'=>array('name_1'=>'Điện thoại','name_2'=>'Phone'),
                           'TRANSPORT'=>array('name_1'=>'Vận chuyển','name_2'=>'Transport'),    
                           'OTHER'=>array('name_1'=>'Khác','name_2'=>'Other'), 
                           'PPV_REVENUE'=>array('name_1'=>'Doanh thu PPV','name_2'=>'PPV revenue'), 
                           'CLUB9_REVENUE'=>array('name_1'=>'Doanh thu CLUB 9','name_2'=>'Club 9 revenue'),  
                            );
        $this->map['name_list_to_js'] = String::array2js($name_list);
        //System::debug($this->map['name_list_to_js']);

		$sql = 'SELECT plan.* FROM plan where portal_id=\''.PORTAL_ID.'\' and year = '.(Url::get('year')?Url::get('year'):date('Y')).' order by plan.id';
		$plans = DB::fetch_all($sql);
		foreach($plans as $k=>$v)
        {
            $plans[$k]['value'] = System::display_number($plans[$k]['value']);
        }

		$_REQUEST['mi_plan'] = $plans;
        	
		$this->parse_layout('edit',$this->map);
	}
}
?>