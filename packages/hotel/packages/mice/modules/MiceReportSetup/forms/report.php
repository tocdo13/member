<?php
class MiceReportSetupForm extends Form
{
	function MiceReportSetupForm()
	{
		Form::Form('MiceReportSetupForm');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
        $this->link_css('packages/hotel/packages/mice/skins/css/font-awesome-4.5.0/css/font-awesome.min.css');
        $this->link_js('packages/core/includes/js/jquery/datepicker.js');
	}
	function draw()
	{
	   $this->map = array();
       $cond = '1>0';
       $department_mice = DB::fetch_all("SELECT * FROM mice_department_setup ORDER BY position");
       $this->map['department_option'] = '<option value="">'.Portal::language('all').'</option>';
       $this->map['department'] = Url::get('department')?Url::get('department'):'';
       if($this->map['department']=='')
       {
            $this->map['department_name'] = Portal::language('all');
       }
       else
       {
            $cond .= 'AND mice_setup_beo.mice_department_id='.$this->map['department'];
       }
       foreach($department_mice as $k=>$v)
       {
            if($this->map['department']==$v['id'])
            {
                $this->map['department_name'] = $v['name'];
            }
            $this->map['department_option'] .= '<option value="'.$v['id'].'">'.$v['name'].'</option>';
       }
       
       $location_mice = DB::fetch_all("SELECT * FROM mice_location_setup ORDER BY position");
       $this->map['location_option'] = '<option value="">'.Portal::language('all').'</option>';
       $this->map['location'] = Url::get('location')?Url::get('location'):'';
       if($this->map['location']=='')
       {
            $this->map['location_name'] = Portal::language('all');
       }
       else
       {
            $cond .= 'AND mice_setup_beo.mice_location_id='.$this->map['location'];
       }
       foreach($location_mice as $k=>$v)
       {
            if($this->map['location']==$v['id'])
            {
                $this->map['location_name'] = $v['name'];
            }
            $this->map['location_option'] .= '<option value="'.$v['id'].'">'.$v['name'].'</option>';
       }
       
       $list_mice = DB::fetch_all("SELECT id FROM mice_reservation WHERE status=1 ORDER BY id");
       $this->map['mice_option'] = '<option value="">'.Portal::language('all').'</option>';
       $this->map['mice'] = Url::get('mice')?Url::get('mice'):'';
       if($this->map['mice']=='')
       {
            $this->map['mice_name'] = Portal::language('all');
       }
       else
       {
            $cond .= 'AND mice_setup_beo.mice_reservation_id='.$this->map['mice'];
       }
       foreach($list_mice as $k=>$v)
       {
            if($this->map['mice']==$v['id'])
            {
                $this->map['mice_name'] = 'MICE+'.$v['id'];
            }
            $this->map['mice_option'] .= '<option value="'.$v['id'].'">'.'MICE+'.$v['id'].'</option>';
       }
       $list_customer = DB::fetch_all("SELECT id,name FROM customer ORDER BY name");
       $this->map['customer_option'] = '<option value="">'.Portal::language('all').'</option>';
       $this->map['customer'] = Url::get('mice')?Url::get('mice'):'';
       if($this->map['customer']=='')
       {
            $this->map['customer_name'] = Portal::language('all');
       }
       else
       {
            $cond .= 'AND mice_reservation.customer_id='.$this->map['customer'];
       }
       foreach($list_customer as $k=>$v)
       {
            if($this->map['customer']==$v['id'])
            {
                $this->map['customer_name'] = $v['name'];
            }
            $this->map['customer_option'] .= '<option value="'.$v['id'].'">'.$v['name'].'</option>';
       }
       $this->map['from_date'] = Url::get('from_date')?Url::get('from_date'):date('d/m/Y');
       $_REQUEST['from_date'] = $this->map['from_date'];
       $this->map['to_date'] = Url::get('to_date')?Url::get('to_date'):date('d/m/Y');
       $_REQUEST['to_date'] = $this->map['to_date'];
       $cond .= 'AND mice_setup_beo.time>=\''.Date_Time::to_time($this->map['from_date']).'\' AND mice_setup_beo.time<=\''.(Date_Time::to_time($this->map['to_date'])+86400).'\'';
       
       $sql = "
                SELECT
                    mice_setup_beo.*
                    ,mice_reservation.id as mice_id
                    ,mice_reservation.contact_name
                    ,mice_reservation.contact_phone
                    ,mice_department_setup.name as department_name
                    ,mice_location_setup.name as location_name
                    ,customer.name as customer_name
                FROM
                    mice_setup_beo
                    inner join mice_department_setup on mice_department_setup.id=mice_setup_beo.mice_department_id
                    inner join mice_location_setup on mice_location_setup.id=mice_setup_beo.mice_location_id
                    inner join mice_reservation on mice_reservation.id=mice_setup_beo.mice_reservation_id
                    left join customer on customer.id=mice_reservation.customer_id
                WHERE
                    ".$cond."
                ORDER BY
                    mice_setup_beo.time
                ";
       $record = DB::fetch_all($sql);
       $items = array();
       
       foreach($record as $key=>$value)
       {
            $in_date = date('d/m/Y',$value['time']);
            $hour = date('H:i',$value['time']);
            
            if(!isset($items[$in_date]))
            {
                $items[$in_date]['id']=$in_date;
                $items[$in_date]['count'] = 0;
                $items[$in_date]['child'] = array();
                
            }
            if(!isset($items[$in_date]['child'][$hour]))
            {
                $items[$in_date]['child'][$hour]['id'] = $hour;
                $items[$in_date]['child'][$hour]['count'] = 0;
                $items[$in_date]['child'][$hour]['child'] = array();
            }
            $items[$in_date]['count']++;
            $items[$in_date]['child'][$hour]['count']++;
            $items[$in_date]['child'][$hour]['child'][$key]=$value;
            
       }
       
       $this->map['items'] = $items;
       //System::debug($items); die;
       $this->parse_layout('report',$this->map);
	}
}
?>