<?php
class ListGolfCaddieSchedulerForm extends Form
{
    function ListGolfCaddieSchedulerForm()
    {
        Form::Form('ListGolfCaddieSchedulerForm');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
		$this->link_js('packages/core/includes/js/jquery/jquery.maskedinput.js');                       
    }
    function on_submit()
	{
	   
	}	
    function draw()
    {
        $this->map = array();
        $start_date = $_REQUEST['start_date'] = isset($_REQUEST['start_date'])?$_REQUEST['start_date']:date('d/m/Y');
        $view_by = $_REQUEST['view_by'] = isset($_REQUEST['view_by'])?$_REQUEST['view_by']:'week';
        if($view_by=='week'){
            $end_date = $_REQUEST['end_date'] = date('d/m/Y',Date_Time::to_time($start_date)+8*68400);
            $this->map['width'] = '140';
        }else{
            $end_date = $_REQUEST['end_date'] = date('d/m/Y',Date_Time::to_time($start_date)+30*68400);
            $this->map['width'] = '40';
        }
        
        $timeline = array();
        for($i=Date_Time::to_time($start_date);$i<=Date_Time::to_time($end_date);$i+=86400){
            $timeline[$i] = getdate($i);
            $timeline[$i]['id'] = $i;
            if(strtoupper($timeline[$i]['month'])!='JULY'){
                $timeline[$i]['month'] = substr($timeline[$i]['month'],0,3);
            }
            $timeline[$i]['weekday'] = substr($timeline[$i]['weekday'],0,3);
        }
        
        $this->map['timeline'] = $timeline;
        
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
        foreach($caddie as $key=>$value){
            $caddie[$key]['timeline'] = $timeline;
        }
        
        $scheduler = DB::fetch_all('
                                    SELECT
                                        golf_caddie_scheduler.*,
                                        TO_CHAR(golf_caddie_scheduler.in_date,\'DD/MM/YYYY\') as in_date
                                    FROM
                                        golf_caddie_scheduler
                                        inner join golf_caddie on golf_caddie.id=golf_caddie_scheduler.golf_caddie_id
                                    WHERE
                                        golf_caddie_scheduler.in_date>=\''.Date_Time::to_orc_date($start_date).'\'
                                        and golf_caddie_scheduler.in_date<=\''.Date_Time::to_orc_date($end_date).'\'
                                    ORDER BY
                                        golf_caddie_scheduler.golf_caddie_id,
                                        golf_caddie_scheduler.start_time
                                    ');
        foreach($scheduler as $key=>$value){
            $in_time = Date_Time::to_time($value['in_date']);
            $value['start_house'] = date('H:i',$value['start_time']);
            $value['end_house'] = date('H:i',$value['end_time']);
            if(isset($caddie[$value['golf_caddie_id']]['timeline'][$in_time])){
                $caddie[$value['golf_caddie_id']]['timeline'][$in_time]['scheduler'][$key] = $value;
            }
        }
        
        $this->map['caddie'] = $caddie;
        $this->parse_layout('list',$this->map);
        
    }
    function calc_time($string)
    {
        $arr = explode(':',$string);
        return $arr[0]*3600 + $arr[1]*60;
    }
}
?>