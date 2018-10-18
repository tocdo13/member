<?php
class AddGolfCaddieSchedulerForm extends Form
{
	function AddGolfCaddieSchedulerForm()
	{
		Form::Form('AddGolfCaddieSchedulerForm');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
		$this->link_js('packages/core/includes/js/jquery/jquery.maskedinput.js');
	}
	function on_submit()
	{
       if(isset($_REQUEST['scheduler_caddie'])){
            foreach($_REQUEST['scheduler_caddie'] as $key=>$value){
                $caddie_id = $value['id'];
                if(isset($value['timeline']))
                {
                    foreach($value['timeline'] as $keyRange => $valueRange)
                    {
                        // xet dieu kien nhap pham vi thoi gian
                        if($valueRange['from_date']!='' and $valueRange['to_date']!='' and $valueRange['from_time']!='' and $valueRange['to_time']!='')
                        {
                            // lap cac khoang thoi gian
                            for($i=Date_Time::to_time($valueRange['from_date']);$i<=Date_Time::to_time($valueRange['to_date']);$i+=86400)
                            {
                                $date = getdate($i);
                                $date['weekday'] = strtoupper(substr($date['weekday'],0,3));
                                // Neu tich chon ngay
                                if( isset($valueRange[$date['weekday']]) )
                                {
                                    $in_date = date('d/m/Y',$i);
                                    $start_time = $i+$this->calc_time($valueRange['from_time']);
                                    $end_time = $i+$this->calc_time($valueRange['to_time']);
                                    // xet dieu kien cat chang
                                    if(DB::exists('select golf_caddie_scheduler.id from golf_caddie_scheduler where golf_caddie_id='.$caddie_id.' and in_date=\''.Date_Time::to_orc_date($in_date).'\' and start_time<='.$end_time.' and end_time>='.$start_time.''))
                                    {
                                        DB::delete('golf_caddie_scheduler','golf_caddie_id='.$caddie_id.' and in_date=\''.Date_Time::to_orc_date($in_date).'\' and start_time<='.$end_time.' and end_time>='.$start_time.'');
                                    }
                                    DB::insert('golf_caddie_scheduler',array(
                                                                        'golf_caddie_id'=>$caddie_id,
                                                                        'in_date'=>Date_Time::to_orc_date($in_date),
                                                                        'start_time'=>$start_time,
                                                                        'end_time'=>$end_time
                                                                        ));
                                }
                            }
                        }
                    }
                }
            }
       }
       Url::redirect('golf_caddie_scheduler');
	}	
	function draw()
	{	
		$this->map = array();
        $cond = '1=1';
        $sql = "
                SELECT 
                    golf_caddie.*,
                    concat(concat(golf_caddie.first_name,' '),golf_caddie.last_name) as full_name,
                    CASE
                        WHEN golf_caddie.image_profile is null 
                        THEN
                            CASE
                                WHEN golf_caddie.gender=1
                                THEN 'no_avata_boy.jpg'
                                ELSE 'no_avatar_girl.jpg'
                                END
                        ELSE
                            golf_caddie.image_profile
                    END image_profile    
                FROM
                    golf_caddie
                    left join country on golf_caddie.nationality_id=country.id
                WHERE
                    ".$cond."
                ORDER BY golf_caddie.first_name ASC, golf_caddie.last_name ASC
                ";
        $caddie = DB::fetch_all($sql);
        $this->map['caddie'] = $caddie;
        $this->parse_layout('add',$this->map);
	}
    function calc_time($string)
    {
        $arr = explode(':',$string);
        return $arr[0]*3600 + $arr[1]*60;
    }
}
?>