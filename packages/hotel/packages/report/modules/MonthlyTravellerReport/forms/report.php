<?php
// BÁO CÁO THỐNG KÊ KHÁCH THEO QUỐC TỊCH.
// TIÊU CHÍ BÁO CÁO: THỐNG KÊ KHÁCH Ở THEO TỪNG NGÀY TRONG THÁNG ĐƯỢC CHỌN THEO TỪNG QUỐC GIA
// DỰA THEO MODULE DANH SÁCH QUỐC GIA. ?page=country . 
// NHỮNG QUỐC GIA NÀO ĐƯỢC CHỌN VÀO BÁO CÁO THÌ SẼ HIỂN THỊ RA QUỐC GIA ĐÓ KHI XEM BÁO CÁO Ở CHẾ ĐỘ RÚT GỌN, CÒN LẠI NHỮNG QUỐC GIA KHÁC GOM VÀO MỘT NHÓM "QUỐC GIA KHÁC"
// NẾU CHỌN CHẾ ĐỘ TẤT CẢ SẼ LIST RA TẤT CẢ CÁC QUỐC GIA MÀ KHÔNG PHỤ THUỘC VÀO MODULE DANH SÁCH QUỐC GIA.
// LẤY DỮ LIỆU CHO BÁO CÁO:
// VÌ BÁO CÁO LIST RA SỐ KHÁCH TRONG TỪNG NGÀY TRONG THÁNG NÊN DỰA VÀO TRƯỜNG IN_DATE TRONG ROOM_STATUS ĐỂ LẤY DỮ LIỆU
// TRONG ROOM_STATUS ỨNG VỚI MỖI RESERVATION_ROOM TA INNER JOIN ĐẾN BẢNG RESERVATION_TRAVELLER ĐỂ ĐẾM SỐ LƯỢNG KHÁCH CÓ TRONG PHÒNG ĐÓ
// ĐỂ LẤY ĐƯỢC DANH SÁCH QUỐC GIA ĐƯỢC CHỌN RA BÁO CÁO TRONG MODULE DANH SÁCH QUỐC GIA TA DỰA VÀO TRƯỜNG SELECTED_REPORT TRONG BẢNG COUNTRY
class MonthlyTravellerReportReportForm extends Form
{
	function MonthlyTravellerReportReportForm()
	{
		Form::Form('MonthlyTravellerReportReportForm');
		$this->link_css(Portal::template('hotel').'/css/report.css');
	}
	function draw()
	{
		require_once 'packages/core/includes/utils/time_select.php';
        // KHAI BÁO LIST CHỌN XEM TẤT CẢ = FULL HOẶC RÚT GỌN = SHORT.
        $this->map['option_list'] = array('SHORT'=>Portal::language('short'),'FULL'=>Portal::language('full'));
        $this->map['portal_id_list'] = array('ALL'=>Portal::language('all')) + String::get_list(Portal::get_portal_list());
        //LẤY NGÀY, THÁNG, NĂM HIỆN TẠI:
		$year = get_time_parameter('year', date('Y'), $end_year);
		$month = get_time_parameter('month', date('m'), $end_month);
		$day = get_time_parameter('day', date('d'), $end_day);
        $full_day = cal_days_in_month(CAL_GREGORIAN,$month,$year);
		$date = 'Ngày '.$day.' Tháng '.$month.' Năm '.$year;
        $this->map['date'] = $date;
        $cond = '1>0';
        //  ĐIỀU KIỆN LẤY THEO PORTAL 
        if(Url::get('portal_id') AND Url::get('portal_id')!='ALL'){
            $cond .= 'and reservation.portal_id = \''.Url::get('portal_id').'\'';
        }
        //LẤY THEO TỪ ĐẦU THÁNG ĐẾN CUỐI THÁNG, LẤY THEO MÃ QUỐC GIA NẾU CÓ
		$cond .= '
            AND ( reservation_traveller.arrival_date<=\''.Date_Time::to_orc_date(cal_days_in_month(CAL_GREGORIAN,$month,$year).'/'.$month.'/'.$year).'\' AND reservation_traveller.departure_date>=\''.Date_Time::to_orc_date('01/'.$month.'/'.$year).'\')'
			.(URL::get('country_id')?' and traveller.nationality_id='.URL::get('country_id'):'');
        // láy dữ liệu tương ứng với những quốc gia được chọn trong phần khai báo
        $sql = "
            SELECT
                concat(country.id,reservation_traveller.id) as id,
                country.id as country_id,
                country.name_2 as country_name,
                DATE_TO_UNIX(reservation_traveller.arrival_date) as arrival_date,
                reservation_traveller.arrival_date as from_date,
                DATE_TO_UNIX(reservation_traveller.departure_date) as departure_date,
                reservation_traveller.departure_date as to_date,
                country.selected_report,
                reservation.id as reservation_id,
                reservation_room.change_room_to_rr,
                concat(concat(traveller.first_name,''),traveller.last_name) as traveller_name
            FROM
                reservation_traveller
                inner join reservation_room on reservation_room.id=reservation_traveller.reservation_room_id
                inner join reservation on reservation.id=reservation_room.reservation_id
                left outer join traveller on traveller.id=reservation_traveller.traveller_id
                left outer join country on country.id=traveller.nationality_id
            WHERE
                $cond
                and reservation_room.status != 'CANCEL'
            ORDER BY reservation.id DESC
        ";
        $traveller = DB::fetch_all($sql);
        //System::debug($sql);
        //System::debug($traveller);
        $country = DB::fetch_all("
            SELECT DISTINCT
                country.id,
                country.name_2 as country_name,
                country.selected_report
            FROM
                reservation_traveller
                inner join reservation on reservation.id=reservation_traveller.reservation_id
                inner join reservation_room on reservation_room.id=reservation_traveller.reservation_room_id
                inner join traveller on traveller.id=reservation_traveller.traveller_id
                left outer join country on country.id=traveller.nationality_id
            WHERE
                $cond
                and reservation_room.status != 'CANCEL'
        ");
        //System::debug($country);
        $end_date_month = cal_days_in_month(CAL_GREGORIAN,$month,$year);
        $from_date = Date_Time::to_time('01/'.$month.'/'.$year);
        $to_date = Date_Time::to_time(cal_days_in_month(CAL_GREGORIAN,$month,$year).'/'.$month.'/'.$year);
        $report->items = array();
            /** lay ra tat ca cac quoc gia voi so khach tung ngay cho vào $report->items**/
            /** lap mang country de lay ra cac quoc gia co trong bao cao **/
            foreach($country as $id_country=>$value_country){
                /** khoi tao dia tri ban dau cho cac quoc gia **/
                $report->items[$value_country['country_name']]['country_name']=$value_country['country_name'];
                $report->items[$value_country['country_name']]['selected_report']=$value_country['selected_report'];
                $report->items[$value_country['country_name']]['day'] = array();
                    for($i=1;$i<=$end_date_month;$i++){
                        if($i<10){$in_date = '0'.$i;}else{$in_date=$i;}
                        $report->items[$value_country['country_name']]['day'][$in_date] = 0;
                    }
                    /** lap mang traveller de dem so luot khach theo tung ngay **/
                    foreach($traveller as $id_traveller=>$value_traveller){
                        if($value_traveller['country_id']==$value_country['id']){/** neu ma quoc gia cua khach trung voi ma quoc gia cua mang country **/
                            /** truong hop dayuse va doi phong trong ngay **/
                            if($value_traveller['departure_date']==$value_traveller['arrival_date']){
                                if(empty($value_traveller['change_room_to_rr'])){/** truong hop dayuse **/
                                    $content_date = date('d/m/Y',$value_traveller['arrival_date']);
                                    $arr_content_date = explode("/",$content_date);
                                    $report->items[$value_country['country_name']]['day'][$arr_content_date[0]] += 1;
                                }/** else{} con lai la truong hop doi phong trong ngay - khong dem khach **/
                            }else{
                                for($i=1;$i<=$end_date_month;$i++){
                                    if($i<10){$in_date = '0'.$i;}else{$in_date=$i;}
                                    $time = Date_Time::to_time($in_date.'/'.$month.'/'.$year);
                                    if(($time>=$value_traveller['arrival_date']) AND ($time<$value_traveller['departure_date']))
                                    $report->items[$value_country['country_name']]['day'][$in_date] += 1;
                                }
                            }
                        }/** end if **/
                    }
            }
        //System::debug($report->items);
        if(Url::get('option') AND Url::get('option')=='FULL')
        {
            /** lấy đầy đủ **/ 
        }
        else
        {
            /** lấy rút gọn - hiển thị quốc gia có select-report=1, còn lại cho vào quốc gia khách hết **/
            $other_country['other']['country_name']='country_other';
            $other_country['other']['selected_report']=0;
            $other_country['other']['day'] = array();
            for($i=1;$i<=$end_date_month;$i++)
            {
                if($i<10){$in_date = '0'.$i;}else{$in_date=$i;}
                $other_country['other']['day'][$in_date] = 0;
            }
            foreach($report->items as $key=>$value)
            {
                if($value['selected_report']!=1)
                {
                    for($i=1;$i<=$end_date_month;$i++)
                    {
                        if($i<10){$in_date = '0'.$i;}else{$in_date=$i;}
                        $other_country['other']['day'][$in_date] += $value['day'][$in_date];
                    }
                    unset($report->items[$key]);
                }
            }
            $report->items += $other_country;
        }
 		//System::debug($other_country);
        //exit();    
        if(sizeof($report->items)==0)
		{
			$this->parse_layout('header',
			get_time_parameters()+
				array(
					'year'=>$year,
					'month'=>$month,
					'day'=>$day,
					'end_month'=>$end_month,
					'end_day'=>$end_day,
					'available'=>1,
					'country_id_list'=>array(0=>'')+String::get_list(DB::select_all('country','id <>734 ','name_2')),
					'country_id'=>0
				)+$this->map
			);
		}else{
		    $this->parse_layout('header',
            get_time_parameters()+
    			array(
    				'year'=>$year,
    				'month'=>$month,
    				'day'=>$day,
    				'end_month'=>$end_month,
    				'end_day'=>$end_day,
    				'available'=>0,
    				'country_id_list'=>array(0=>'')+String::get_list(DB::select_all('country',false,'name_2')),
    				'country_id'=>1
    			)+$this->map
		      );
            //lấy tổng từng quốc gia. và tổng tất cả.
            for($k=1;$k<=$full_day;$k++){
                if($k<10){
                    $k='0'.$k;
                }
                $arr_total_country[$k] = 0; 
            }
            foreach($report->items as $id=>$content){
                foreach($content['day'] as $id1=>$content1){
                    if(isset($report->items[$id]['total_country'])){
                        $report->items[$id]['total_country'] += $content1;
                    }else{
                        $report->items[$id]['total_country'] = $content1;
                    }
                    $arr_total_country[$id1] += $content1;
                }
            }
            //System::debug($report->items);
            $this->parse_layout('report',array(
                'full_day'=>$full_day,
                'items'=>$report->items,
                'total_country'=>$arr_total_country
            ));
           $this->parse_layout('footer',$this->map);   
		}
	}
}
?>
