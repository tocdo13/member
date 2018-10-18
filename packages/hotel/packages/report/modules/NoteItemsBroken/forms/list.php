<?php
class WSDailyStaffForm extends Form
{
	function WSDailyStaffForm()
	{
		Form::Form('WSDailyStaffForm');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
	}
    	
	function draw()
	{
		$this->map = array();
        $month=array('01'=>'JAN','02'=>'FEB','03'=>'MAR','04'=>'APR','05'=>'MAY','06'=>'JUN','07'=>'JUL','08'=>'AUG','09'=>'SEP','10'=>'OCT','11'=>'NOV','12'=>'DEC');
         if(isset($_REQUEST['from_date']))
         {
            $from_date = explode("/",$_REQUEST['from_date']);
            $d =mktime(0,0,0,$from_date[1],$from_date[0],$from_date[2]);
            $from_date_view =$from_date[0].'-'.$month[$from_date[1]].'-'.date('y');
         }
         else
         {
            $_REQUEST['from_date'] = date('d/m/Y');
            $from_date_view =date('d').'-'.$month[date('m')].'-'.date('y'); 
         }
         if(isset($_REQUEST['to_date']))
         {
            $from_date = explode("/",$_REQUEST['to_date']);
            $d =mktime(0,0,0,$from_date[1],$from_date[0],$from_date[2]);
            $to_date_view =$from_date[0].'-'.$month[$from_date[1]].'-'.date('y');
         }
         else
         {
            $_REQUEST['to_date'] = date('d/m/Y');
            $to_date_view =date('d').'-'.$month[date('m')].'-'.date('y'); 
         }
        $items = $this->loadData($from_date_view,$to_date_view);
        $this->map['items'] = $items;
        //System::debug($items);
        $this->parse_layout('list',$this->map);
	}
    function loadData($from_date,$to_date)
    {
        $sql = "SELECT ws_daily_room.id,
                    ws_daily_room.ws_daily_staff_id,
                    ws_daily_staff.date_ws,
                    ws_daily_staff.staff_name,
                    ws_daily_room.room_id,room.name as room_name,
                    ws_daily_room.reservation_room_id
                FROM ws_daily_staff
                INNER JOIN ws_daily_room ON ws_daily_staff.id=ws_daily_room.ws_daily_staff_id
                INNER JOIN room ON  ws_daily_room.room_id=room.id
                WHERE ws_daily_staff.date_ws>='".$from_date."' and ws_daily_staff.date_ws<='".$to_date."'
                ORDER BY ws_daily_room.ws_daily_staff_id desc,ws_daily_staff.date_ws desc,room.name";
        $arr = DB::fetch_all($sql);
        $items = array();
        $date = false;
        $staff_old = false;
        $i= 1;
        $row_span = 1;
        //System::debug($arr);
        $k =0;
        foreach($arr as $row)
        {
            if($date!=$row['date_ws'])
            {
                $items[$row['date_ws']]['date_ws'] = $row['date_ws'];
                
                $items[$row['date_ws']]['row_span'] = 0;
                $items[$row['date_ws']]['staffs'] = array();
                $k = -1;
                
                $items[$row['date_ws']]['index'] = $i++;
                $date=$row['date_ws'];
            }
            
            if($staff_old!=$row['ws_daily_staff_id'])
            {
                $items[$row['date_ws']]['staffs'][$row['ws_daily_staff_id']] = array();
                $items[$row['date_ws']]['staffs'][$row['ws_daily_staff_id']]['ws_daily_staff_id'] = $row['ws_daily_staff_id'];
                $items[$row['date_ws']]['staffs'][$row['ws_daily_staff_id']]['staff_name'] = $row['staff_name'];
                $items[$row['date_ws']]['staffs'][$row['ws_daily_staff_id']]['str_room']= $row['room_name'].', ';

                $items[$row['date_ws']]['row_span']++;
                $staff_old = $row['ws_daily_staff_id'];
            }
            else
            {
                $items[$row['date_ws']]['staffs'][$row['ws_daily_staff_id']]['str_room'] .=$row['room_name'].', ';
            }
            
        }
        
        //System::debug($items);
        return $items;
    }
    
}
?>