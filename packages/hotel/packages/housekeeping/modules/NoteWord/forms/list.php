<?php
class ListHousekeepingEquipmentForm extends Form
{
	function ListHousekeepingEquipmentForm()
	{
		 Form::Form('ListHousekeepingEquipmentForm');
         $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
    	$this->link_js('packages/core/includes/js/jquery/datepicker.js');
	}
	function draw()
	{
        $note_tree=array();
        $day = date('w')-1;
        $from_date='';
        $to_date = '';
        if(Url::get('from_date')){
          $from_date= Date_Time::to_time(Url::get('from_date'));
        }else{
           $from_date= strtotime('-'.$day.' days 00:00:00');
        }
        if(Url::get('to_date')){
          $to_date= Date_Time::to_time(Url::get('to_date'));
          $to_date = strtotime('+23 hour 59 minutes 59 seconds',$to_date);
        }else{
             $to_date = strtotime('+'.(6-$day).' days 23:59:59');
        }
 
        $note['1'] = DB::fetch_all('select * from note where repeat=1 and account_id= \''.User::id().'\'');
        $note['2']=DB::fetch_all('select * from note where repeat is NULL and account_id= \''.User::id().'\' and start_time>='.$from_date.' and end_time <= '.$to_date.'');
      // System::debug($note);die;
        $note_tree=$this->NoteRepeat($note,$from_date,$to_date);
        krsort($note_tree);
        $date_now= mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));  
           	$this->parse_layout('list',
			array(
                'note_tree'=>$note_tree,
                'date_now'=>$date_now,
                'from_date'=>date('d/m/Y',$from_date),
                'to_date'=>date('d/m/Y',$to_date)
			)
		);
       
    }
    
     /**
	 * function NoteRepeat() return repeat times for weeks.

	 * @param array $note is list woeks.
     * @param timestamp $from_date is start time
	 * @param timestamp $to_date is end time
     * 
     * mô tả giải thuật:
     * chia công việc làm 2 mảng :
     * mảng có lặp và mảng không lặp
     *  -Đối với mảng có lặp:
     *    có 4 lựa chọn lặp là: theo ngày , tuần, tháng và năm 
     *    có 3 lựa chọn kết thúc cho công việc: ko bao giờ kết thúc, sau n lần, và đến ngày nào thì kết thúc  
     *    lưu ý các tham số về thời gian sau: 
     *          1) thời gian băt đầu công việc 
     *          2) thòi gian bắt đầu search
     *          3) thời gian kết thúc search
     *          4) thời gian kết thúc lặp     
     *          5) sau bao lâu lặp 1 lần
     * - Nội dung chính:
     *      input: công việc đang có trạng thai lặp
     *      output: xuất ra mảng 2 chiều có khóa là ngày lặp ,value là các công việc lặp
     * 
     *      xét trong khoảng thời gian search tính ra số lần cần lặp bao gồm t1 và t_all
     *      với t1 là lần đầu tiên, t_all là tổng số lần
     *      tới mỗi thời diểm thì push công việc vào list repeat nếu nó thỏa mãn 1 trong 3 diều kiện lặp
     * 
     * - mô tả các trường hợp đặc biệt:     
     * 
     *      bao gồm trường hợp lặp theo tuần và lặp theo tháng sẽ căn cứ vào ngày bắt đầu để xác định ngày lặp
     *      Lặp theo tuần: 
     *          - khi lặp theo tuần cần chọn lặp vào thứ mấy trong tuần
     *          trong phần xử lý lặp sẽ tính thêm xem thứ n có thỏa mãn các diều kiện lặp ko
     *      Lặp theo tháng:
     *          có 2 sự lựa chọn: ngày trong tháng và ngày trong tuần
     *          - đối với ngày trong tháng thì xét tháng đó có thỏa mãn ngày lựa chọn không
     *          - đối với ngày trong tuần:
     *                thì xem thứ xuất hiện trong tuần đó có tương ứng với thứ xuất hiện trong tuần bắt đầu ko.
     *                cách tình ngày bắt đầu thuộc tuần thứ mấy:
     *                position= (thời gian bắt đầu-(thứ tương ứng(lấy ngày)-ngày đầu tiên))/7+1;             
	 */
     
     
    function NoteRepeat($note,$from_date,$to_date){
    $note_tree=array();
    foreach ($note as $key => $val) {
        //get list work repeat with key=1
        if($key==1){
            foreach ($val as $k => $v) {
                //thoi gian search lon hon thoi gian ket thuc thì lấy thời gian kêt thúc 
                 if($v['repeat_type_end']==3 && $v['repeat_end_time'] !=''){
                    if($v['repeat_end_time']<$to_date){
                        $to_date=$v['repeat_end_time'];
                    }  
                }
                //xét với trường hợp lặp theo ngày, tuần, năm.
                if($v['repeat_type']==1 || $v['repeat_type']==2 || $v['repeat_type']==4){
                    if($v['repeat_type']==1){
                        $n=1;
                        $repeat_type='days';
                    }else if($v['repeat_type']==2){
                        $n=7;
                        $repeat_type='week';
                    }else{
                        $n=365;
                        $repeat_type='year';
                    }
                    // $t_all là tổng thời gian từ lúc search
                    // $t1 là khoảng thời gian bắt đầu công việc tới lúc search
                    $t_all = ceil(($to_date - $v['start_time'])/(60*60*24));   
                    $t_all=ceil($t_all/($v['repeat_time']*$n));            
                    if($from_date<=$v['start_time']){
                        $t1=0;
                    }else{
                        $t1= ceil(($from_date-$v['start_time'])/(60*60*24));
                         $t1=ceil($t1/($v['repeat_time']*$n));
                    }
                
                    if($t1==0 && $t_all>0){
                        $note_tree[date('Ymd',$v['start_time'])][]=$v;
                        $t1++;
                    }
                    //lấy thứ mà công việc sẽ lặp vào hôm đó
                    $thu = explode(',',$v['repeat_week']);
                    
                    for($t1;$t1<=$t_all;$t1++){
                    $a= $t1*$v['repeat_time'].$repeat_type;
                    //nexttime là thời gian lặp đến lần tiếp theo
                    $next_time=strtotime('+'.$a,$v['start_time']);
                    $dk=($v['repeat_type_end']==1)?true:(($v['repeat_type_end']==3)?($next_time<=$v['repeat_end_time']):($v['repeat_end_time']>0));

                    if($v['repeat_type']==1 || $v['repeat_type']==4){
                        if($dk && $next_time<=$to_date){
                                 $note_tree[date('Ymd',$next_time)][]=$v; 
                        }
                    }else if($v['repeat_type']==2){
                        //next_week lấy number tương ứng với thời gian nexttime
                         $next_week=date('N',$next_time);
                         foreach($thu as $k_thu=>$val){
                            $next= abs($next_week-$val);
                            if($val<$next_week){
                                $next='-'.$next; 
                            }else if($val>$next_week){
                                $next='+'.$next;
                            }else{
                                $next=0;
                            }
                            $next_date= strtotime($next.' days',$next_time);
                            if($dk && $next_date<$to_date)
                            $note_tree[date('Ymd',$next_date)][]=$v;
                         }    
                    }   
                    if($v['repeat_type_end']==2){
                       $v['repeat_end_time']--;
                    }
                } 
                //xet với trường hợp lặp theo tháng 
                }else if($v['repeat_type']==3){
                    $start_month_int=date('m',$v['start_time']);
                    $start_year_int = date('Y',$v['start_time']);
                    $start_day_int =  date('d',$v['start_time']);
                    //sday  là number của ngày bắt đầu công việc trong tuần
                    $sday =  date('w', mktime(0, 0, 0, $start_month_int, $start_day_int, $start_year_int));
                    //nếu thời gian search nhỏ hơn thời gian bắt đầu công việc thì lấy mốc thời gian bắt đầu công việc 
                    if($from_date<=$v['start_time']){
                        $t1=0;
                        if($v['start_time']<=$to_date)
                            $note_tree[date('Ymd',$v['start_time'])][]=$v;
                        $t1++;
                    }else if($from_date>$v['start_time']){
                         $t1= $this->getTotalMonths($v['start_time'],$from_date,$v['repeat_time']);
                    }
                   
                   $t_all = $this->getTotalMonths($v['start_time'],$to_date,$v['repeat_time']);
                   //dối với lặp theo ngày trong tuần , tìm công việc bắt đầu trong tuần thứ mấy
                   if($v['repeat_month']==2){
                         $first_day =  date('w', mktime(0, 0, 0, $start_month_int, 01, $start_year_int));
                         //$a là số ngày chêch lệch giữa ngày mùng 1 và ngày cần tìm trong tuần đầu tiên
                        $a = $sday-$first_day;
                        if($first_day<$sday){
                            $find_day =  date('d', mktime(0, 0, 0, date('m',$v['start_time']), 01+$a, date('Y',$v['start_time'])));  
                        }else if($first_day>$sday){
                            $find_day =  date('d', mktime(0, 0, 0, date('m',$v['start_time']), 8+$a, date('Y',$v['start_time'])));    
                        }
                        $position = ((int)$start_day_int-(int)$find_day)%7;
                        if($position==0){
                            $position = ((int)$start_day_int-(int)$find_day)/7;
                        }
                        
                   }
                    $year=1;
                   
                    for($t1;$t1<=$t_all;$t1++){
                        $next_month = $start_month_int + $t1*$v['repeat_time'];
                        $next_year=$next_month;
                            $next_month = $next_month%12;
                            if($next_month==0){
                                $next_month=12;
                            }else{
                                if($next_year>12*$year){
                                    $year++;
                                    $start_year_int++;
                                }else{
                                    $next_month = $next_month%12;
                                } 
                            }
                       if($next_month<10){
                            $next_month='0'.$next_month;
                        }
                        $total_day_of_month = cal_days_in_month(CAL_GREGORIAN, $next_month, $start_year_int); 
                        //xet lặp  với ngày trong tháng                 
                        if($v['repeat_month']==1){
                            if($start_day_int<=$total_day_of_month){
                                $ymd= $start_year_int.$next_month.$start_day_int;
                                $dk=($v['repeat_type_end']==1)?true:(($v['repeat_type_end']==3)?($ymd<=date('Ymd',$v['repeat_end_time'])):($v['repeat_end_time']>0));
                                if($dk && $ymd<=date('Ymd',$to_date)){
                                     $note_tree[$ymd][] = $v;
                                    if($v['repeat_type_end']==2){
                                        $v['repeat_end_time']--;
                                    }
                                }
                            }
                        //xét lặp với ngày trong tuần
                        }else{
                            $first_day =  date('w', mktime(0, 0, 0, $next_month, 01, $start_year_int));
                            $a = $sday-$first_day;
                             if($first_day<$sday){
                                 $day_of_firstweek =  date('d', mktime(0, 0, 0, $next_month, 01+$a,$start_year_int)); 
   
                             }else if($first_day>$sday){
                                $day_of_firstweek =  date('d-m-Y', mktime(0, 0, 0, date('m',$v['start_time']), 8+$a, date('Y',$v['start_time'])));   
                             }else{
                                $day_of_firstweek =$first_day+1;
                             }  
                            
                            $day_of_firstweek= $day_of_firstweek+($position*7);
                             
                            if($day_of_firstweek<=$total_day_of_month  ){
                                $ymd= $start_year_int.$next_month.$day_of_firstweek;                         
                            }else{
                                $day_of_firstweek=$day_of_firstweek-7;
                                $ymd= $start_year_int.$next_month.$day_of_firstweek;
                            }
                            $dk=($v['repeat_type_end']==1)?true:(($v['repeat_type_end']==3)?($ymd<=date('Ymd',$v['repeat_end_time'])):($v['repeat_end_time']>0));
                             if($dk && $ymd>=date('Ymd',$from_date) && $ymd<=date('Ymd',$to_date)){
                                $note_tree[$ymd][] = $v;
                                if($v['repeat_type_end']==2){
                                   $v['repeat_end_time']--;
                                }
                             }     
                        }  
                    }
                }
            }
        }else{
            //get list work no repeat with key=1
           
              foreach ($val as $k => $v) {
                $note_tree[date('Ymd',$v['start_time'])][]=$v;
              }
            }
        }
        
        return $note_tree;
    } 
    
    /**
	 * function getMonth() trả về số lần trong khoảng thời gian xét 

	 * @param timestamp $from_date is start time
	 * @param timestamp $to_date is end time
	 */
    
    function getTotalMonths($from_date,$to_date,$repeat_time){
	$start_years = date('Y',$from_date);
	$to_years= date('Y',$to_date);
  
   
	$start_months= date('m',$from_date);
	$to_months= date('m',$to_date);
	$num_year= (int)$to_years-(int)$start_years;
	$num_month= (int)$to_months - (int)$start_months;
  
	if($num_year>0){
	 
		$months= $num_year*12;
       
		if($num_month>0){
			$num_month=$months+$num_month;
		}else if($num_month==0){
			$num_month = $months;
		}else{
                $num_month = 12-((int)$start_months - (int)$to_months); 
		      if($num_year>1){   
                  $num_month = $months - ($start_months- (int)($to_months)) ;
		      }
		}
	}
	return ceil($num_month/$repeat_time);
    }    
    
}
?>