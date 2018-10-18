<?php
    class GolfPitchPriceManagerEditForm extends Form
{
	function GolfPitchPriceManagerEditForm()
    {
         Form::Form('GolfRentForm');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
        $this->link_js('packages/core/includes/js/jquery/datepicker.js'); 
        $this->link_js('packages/core/includes/js/jquery/jquery.maskedinput.js');
    }
    function on_submit(){
        //System::debug($_REQUEST);
        if(Url::get('act')=='SAVE' and isset($_REQUEST['bulkrange']) and isset($_REQUEST['golf_hole']))
        {
            $description_detail = '';
            $log_hole = 0;
            // lay pham vi thoi gian
            foreach($_REQUEST['bulkrange'] as $keyRange => $valueRange)
            {
                // xet dieu kien nhap pham vi thoi gian
                if($valueRange['from_date']!='' and $valueRange['to_date']!='' and $valueRange['from_time']!='' and $valueRange['to_time']!='')
                {
                    $price = System::calculate_number($valueRange['price']);
                    // lap cac khoang thoi gian
                    for($i=Date_Time::to_time($valueRange['from_date']);$i<=Date_Time::to_time($valueRange['to_date']);$i+=86400)
                    {
                        $date = getdate($i);
                        $date['weekday'] = strtoupper(substr($date['weekday'],0,3));
                        // Neu tich chon ngay
                        if( isset($valueRange[$date['weekday']]) )
                        {
                            // lay cac goi golf duoc tich chon
                            foreach($_REQUEST['golf_hole'] as $keyHole=>$valueHole)
                            {
                                // xet dieu kien tich chon ca nhom thanh vien
                                if(isset($valueHole['group_traveller']))
                                {
                                    $golf_hole_id = $keyHole;
                                    if($log_hole==0)
                                        $description_detail .= '<p> Hole: '.$valueHole['name'].' + ';
                                    // duyet qua nhom thanh vien
                                    foreach($valueHole['group_traveller'] as $keyGTraveller=>$valueGTraveller)
                                    {
                                        // neu chon nhom thanh vien
                                        if(isset($valueGTraveller['id']))
                                        {
                                            $group_traveller_id = $keyGTraveller;
                                            if($log_hole==0)
                                                $description_detail .= 'Group Member: '.$valueGTraveller['name'].' - ';
                                            $in_date = date('d/m/Y',$i);
                                            $start_time = $i+$this->calc_time($valueRange['from_time']);
                                            $end_time = $i+$this->calc_time($valueRange['to_time']);
                                            // xet dieu kien cat chang
                                            if(DB::exists('select golf_pitch_price.id from golf_pitch_price where golf_hole_id='.$golf_hole_id.' and group_traveller_id='.$group_traveller_id.' and in_date=\''.Date_Time::to_orc_date($in_date).'\' and start_time<='.$end_time.' and end_time>='.$start_time.' and portal_id=\''.PORTAL_ID.'\''))
                                            {
                                                $golf_pitch_price_old = DB::fetch_all('
                                                                                select 
                                                                                    golf_pitch_price.* 
                                                                                from 
                                                                                    golf_pitch_price 
                                                                                where 
                                                                                    golf_hole_id='.$golf_hole_id.' 
                                                                                    and group_traveller_id='.$group_traveller_id.' 
                                                                                    and in_date=\''.Date_Time::to_orc_date($in_date).'\' 
                                                                                    and start_time<='.$end_time.' 
                                                                                    and end_time>='.$start_time.' 
                                                                                    and portal_id=\''.PORTAL_ID.'\'
                                                                                ');
                                                foreach($golf_pitch_price_old as $keyOld=>$valueOld){
                                                    if($valueOld['start_time']<$start_time){
                                                        if($valueOld['end_time']<=$end_time){
                                                            DB::update('golf_pitch_price',array('end_time'=>$start_time-60),'id='.$valueOld['id']);
                                                        }else{
                                                            DB::update('golf_pitch_price',array('end_time'=>$start_time-60),'id='.$valueOld['id']);
                                                            DB::insert('golf_pitch_price',array(
                                                                                'golf_hole_id'=>$golf_hole_id,
                                                                                'group_traveller_id'=>$group_traveller_id,
                                                                                'in_date'=>Date_Time::to_orc_date($in_date),
                                                                                'start_time'=>$end_time+60,
                                                                                'end_time'=>$valueOld['end_time'],
                                                                                'price'=>$valueOld['price'],
                                                                                'portal_id'=>PORTAL_ID
                                                                                ));
                                                        }
                                                    }
                                                    elseif($valueOld['start_time']>=$start_time){
                                                        if($valueOld['end_time']<=$end_time){
                                                            DB::delete('golf_pitch_price','id='.$valueOld['id']);
                                                        }else{
                                                            DB::update('golf_pitch_price',array(
                                                                                'golf_hole_id'=>$golf_hole_id,
                                                                                'group_traveller_id'=>$group_traveller_id,
                                                                                'in_date'=>Date_Time::to_orc_date($in_date),
                                                                                'start_time'=>$end_time+60,
                                                                                'end_time'=>$valueOld['end_time'],
                                                                                'price'=>$valueOld['price']
                                                                                ),'id='.$valueOld['id']);
                                                        }
                                                    }
                                                }
                                                if($price>0){
                                                    DB::insert('golf_pitch_price',array(
                                                                                'golf_hole_id'=>$golf_hole_id,
                                                                                'group_traveller_id'=>$group_traveller_id,
                                                                                'in_date'=>Date_Time::to_orc_date($in_date),
                                                                                'start_time'=>$start_time,
                                                                                'end_time'=>$end_time,
                                                                                'price'=>$price,
                                                                                'portal_id'=>PORTAL_ID
                                                                                ));
                                                }
                                                
                                            }
                                            else
                                            {
                                                if($price>0){
                                                    DB::insert('golf_pitch_price',array(
                                                                                    'golf_hole_id'=>$golf_hole_id,
                                                                                    'group_traveller_id'=>$group_traveller_id,
                                                                                    'in_date'=>Date_Time::to_orc_date($in_date),
                                                                                    'start_time'=>$start_time,
                                                                                    'end_time'=>$end_time,
                                                                                    'price'=>$price,
                                                                                    'portal_id'=>PORTAL_ID
                                                                                    ));
                                                }
                                            }
                                        }
                                    }
                                    if($log_hole==0)
                                        $description_detail .= '</p>';
                                }
                            }
                            $log_hole = 1;
                        }
                    }
                }
                /** log info **/
                $description_detail .= '<p>Range Price From time: '.$valueRange['from_time'].' To Time: '.$valueRange['to_time'].' From Date: '.$valueRange['from_date'].' To Date: '.$valueRange['to_date'].' - Price: '.$valueRange['price'].'</p>';
                $description_detail .= '<p> '.(isset($valueRange['MON'])?'T2,':'').'  '.(isset($valueRange['TUE'])?'T3,':'').'  '.(isset($valueRange['WED'])?'T4,':'').'  '.(isset($valueRange['THU'])?'T5,':'').'  '.(isset($valueRange['FRI'])?'T6,':'').'  '.(isset($valueRange['SAT'])?'T7,':'').'  '.(isset($valueRange['SUN'])?'CN,':'').' </p>';
                /** log **/
            }
            $type_log = '<p>ADD</p>';
            $title_log = '<p>ADD Golf Price</p>';
            System::log($type_log,$title_log,$description_detail);
        }
        Url::redirect('golf_pitch_price_manager');
    }
    function draw(){
        $this->map = array();
        $this->map['golf_hole'] = DB::fetch_all("SELECT * 
                                                    FROM golf_hole
                                                    WHERE portal_id='".PORTAL_ID."'
                                                    ORDER BY name");
        $this->map['group_traveller'] = DB::fetch_all("SELECT * 
                                                    FROM group_traveller
                                                    ORDER BY name");
        //System::debug($this->map);
        $this->parse_layout('edit',$this->map);
    }
    function calc_time($string)
    {
        $arr = explode(':',$string);
        return $arr[0]*3600 + $arr[1]*60;
    }
}    
?>