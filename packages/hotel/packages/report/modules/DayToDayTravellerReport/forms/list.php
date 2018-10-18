<?php
class DayToDayTravellerReportForm extends Form
{
	function DayToDayTravellerReportForm()
	{
		Form::Form('DayToDayTravellerReportForm');
        $this->link_css(Portal::template('hotel').'/css/report.css');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js'); 
	}
    
    function draw()
	{     
        $this->map = array();
        $this->map['date_from'] = Url::sget('date_from')?Url::sget('date_from'):('01/'.date('m/Y'));
        $this->map['date_to'] = Url::sget('date_to')?Url::sget('date_to'):(cal_days_in_month(CAL_GREGORIAN,date('m'),date('Y')).'/'.date('m/Y'));
        $_REQUEST['date_from'] = $this->map['date_from'];
        $_REQUEST['date_to'] = $this->map['date_to'];
        $_REQUEST['line_per_page'] = Url::get('line_per_page')?Url::get('line_per_page'):999;
        if($_REQUEST['line_per_page']<1) $_REQUEST['line_per_page'] = 99;
        $_REQUEST['total_page'] = Url::get('total_page')?Url::get('total_page'):50;
        if($_REQUEST['total_page']<1) $_REQUEST['total_page'] = 50;
        $_REQUEST['start_page'] = Url::get('start_page')?Url::get('start_page'):1;
        if($_REQUEST['start_page']<1) $_REQUEST['start_page'] = 1; 
		if(Url::get('do_search'))
		{
            $cond = '';
            $cond_today = '';
            if(Url::get('portal_id'))
            {
                $portal_id = Url::get('portal_id');
               // $_REQUEST['portal_id'] = $portal_id;
               
            }
            else
            {
                $portal_id = PORTAL_ID;
               // $_REQUEST['portal_id'] = PORTAL_ID;                       
            }
            echo $portal_id;
            
            if($portal_id != 'ALL')
            {
                $cond.=' reservation.portal_id = \''.$portal_id.'\' ';
                $cond_today .=' reservation.portal_id = \''.$portal_id.'\' ';
            }
            else
            {
                $cond .=" 1=1 ";
                $cond_today .=" 1=1 ";
            }
		    if(Url::get('country_id')){
		      $country = Url::get('country_id');
              $_REQUEST['country_id'] = $country;
		    }else{
		      $country = 'all';
              $_REQUEST['country_id'] = $country;
		    }
            if($country != 'all'){
                $cond .= "AND country.id= '".$country."' ";
                $cond_today .= "AND country.id= '".$country."' ";
            }
			$cond .= ' AND ROOM_STATUS.IN_DATE <=\''.date('d-M-Y',Date_Time::to_time($this->map['date_to'])).'\' AND ROOM_STATUS.IN_DATE >= \''.date('d-M-Y',Date_Time::to_time($this->map['date_from'])).'\' 
                        AND ROOM_STATUS.STATUS = \'OCCUPIED\' AND ROOM_STATUS.CHANGE_PRICE != 0
			';
            $cond_today .= ' AND ROOM_STATUS.IN_DATE = \''.date('d-M-Y',Date_Time::to_time($this->map['date_from'])).'\' AND ROOM_STATUS.STATUS = \'OCCUPIED\' AND ROOM_STATUS.CHANGE_PRICE != 0 ';
			require_once 'packages/core/includes/utils/lib/report.php';
			$report = new Report;
            $sql = '
                SELECT
                    ROW_NUMBER() OVER(ORDER BY country.name_2 DESC) AS id,
                    country.name_2 as country,
                    count(reservation_traveller.traveller_id) as count_traveller
                FROM
                    room_status
                    inner join reservation_room on room_status.reservation_room_id = reservation_room.id
                    inner join reservation on reservation_room.reservation_id = reservation.id
                    inner join room on reservation_room.room_id = room.id
                    inner join room_level on room.room_level_id = room_level.id
                    inner join reservation_traveller on reservation_traveller.reservation_room_id = reservation_room.id
                    inner join traveller on traveller.id = reservation_traveller.traveller_id
                    inner join country on traveller.nationality_id = country.id
                WHERE
                    '.$cond.' AND room_level.is_virtual != 1
                GROUP BY
                    country.name_2
            
            ';
            $report->items = DB::fetch_all($sql);
            
            $orcl = '
                SELECT
                    ROW_NUMBER() OVER(ORDER BY country.name_2 DESC) AS id,
                    country.name_2 as country,
                    count(reservation_traveller.traveller_id) as count_today
                FROM
                    room_status
                    inner join reservation_room on room_status.reservation_room_id = reservation_room.id
                    inner join reservation on reservation_room.reservation_id = reservation.id
                    inner join room on reservation_room.room_id = room.id
                    inner join room_level on room.room_level_id = room_level.id
                    inner join reservation_traveller on reservation_traveller.reservation_room_id = reservation_room.id
                    inner join traveller on traveller.id = reservation_traveller.traveller_id
                    inner join country on traveller.nationality_id = country.id
                WHERE
                    '.$cond_today.' AND room_level.is_virtual != 1
                GROUP BY
                    country.name_2
            ';
            $report->item = DB::fetch_all($orcl);
            //System::debug($report->item);
            foreach($report->items as $key=>$value){
                $report->items[$key]['count_today'] = 0;
                foreach($report->item as $id=>$content){
                    if($value['country']==$content['country']){
                        $report->items[$key]['count_today'] += $content['count_today'];
                    }
                }
            }
            //System::debug($report->items);
            $this->phan_trang($report);
		}
		else
		{
		    $this->map['line_per_page'] = $_REQUEST['line_per_page']?$_REQUEST['line_per_page']:999;
            $this->map['total_page'] = $_REQUEST['total_page']?$_REQUEST['total_page']:50;
            $this->map['start_page'] = $_REQUEST['start_page']?$_REQUEST['start_page']:1;   
		    $country_id = 'SELECT country.id as id, country.name_2 as name from country ORDER BY country.name_2 ASC';  
			$this->map['country_id_list'] = array('all'=>'ALL') + String::get_list(DB::fetch_all($country_id));
            $this->map['portal_id_list'] = array('ALL'=>Portal::language('all')) + String::get_list(Portal::get_portal_list());
			$_REQUEST['no_record'] = false;
            $this->parse_layout('search',$this->map);	
		}			
	}
    
	function phan_trang(&$report){
	   $n = sizeof($report->items);
       if($n<=0){
			$this->map['line_per_page'] = $_REQUEST['line_per_page']?$_REQUEST['line_per_page']:999;
            $this->map['total_page'] = $_REQUEST['total_page']?$_REQUEST['total_page']:50;
            $this->map['start_page'] = $_REQUEST['start_page']?$_REQUEST['start_page']:1;   
		    $country_id = 'SELECT country.id as id, country.name_2 as name from country ORDER BY country.name_2 ASC';  
			$this->map['country_id_list'] = array('all'=>'ALL') + String::get_list(DB::fetch_all($country_id));
            $this->map['portal_id_list'] = array('all'=>Portal::language('all')) + String::get_list(Portal::get_portal_list());
			$_REQUEST['no_record'] = true;
            //Start Luu Nguyen Giap comment for search portal_id
          //  $this->parse_layout('search',$this->map);
           // exit();
            //End Luu Nguyen Giap comment for search portal_id
       }
       $pages = array();
       $count = 0;
       $i=1;
       if($n<=$_REQUEST['line_per_page']){
            $this->map['country_id'] = $_REQUEST['country_id'];
            $this->parse_layout('header',$this->map);
            $this->parse_layout('list',array('items'=>$report->items,'num_page'=>'1','total_page'=>'1'));
       }else{
            foreach($report->items as $key=>$value){
                $count += 1;
                if($count > $_REQUEST['line_per_page']){
                    $count = 1;
                    $i +=1; 
                }
                $pages[$i][$key]=$value;
            }
            $total_page = sizeof($pages);
            $this->parse_layout('header',array());
            foreach($pages as $num_page=>$page){
                if(($num_page>=$_REQUEST['start_page']) AND ($num_page<=$_REQUEST['total_page']))
                $this->in_trang($num_page,$page,$total_page);
            }
       }
	}
    function in_trang($num_page,$page,$total_page){
        $this->parse_layout('list',array(
                                    'items'=>$page,
                                    'num_page'=>$num_page,
                                    'total_page'=>$total_page
                                    ));
    }
}
?>